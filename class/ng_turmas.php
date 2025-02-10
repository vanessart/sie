<?php

class ng_turmas {

    public static function Listar($ciclo = NUll, $id_curso = NULL, $id_ensino = NULL, $status = NULL, $id_inst = NULL)
    {
        $sqlAux = '';
        if (!empty($ciclo) || $ciclo == '0') {
            $sqlAux .= "and ci.id_ciclo in ($ciclo)";
        }
        if (!empty($id_curso)) {
            $sqlAux .= "and c.id_curso = '$id_curso'";
        }
        if (!empty($id_ensino)) {
            $sqlAux .= "and fk_id_tp_ens = '$id_ensino'";
        }
        if (empty($id_inst)) {
            $id_inst = toolErp::id_inst();
        }

        if (empty($status)) {
            $status = '0,1';
        }

        $sql = "SELECT "
                . " t.id_turma, t.n_turma, t.fk_id_pl, t.codigo, t.periodo, t.prodesp, t.status, ci.n_ciclo, ci.aprova_automatico, c.n_curso, g.n_grade, g.id_grade, s.n_sala, s.cadeirante "
                . " FROM `ge_turmas` t "
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo  "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " JOIN ge_cursos c on c.id_curso = ci.fk_id_curso "
                . " LEFT JOIN ge_grades g on g.id_grade = t.fk_id_grade "
                . " LEFT JOIN salas s on s.id_sala = t.fk_id_sala "
                . " LEFT JOIN predio pr on pr.id_predio = s.fk_id_predio "
                . " LEFT JOIN ge_turma_status ts on t.status = ts.id_ts "
                . " WHERE t.`fk_id_inst` =  " . $id_inst
                . " AND c.extra <> 1 AND pl.at_pl = 1"
                . " AND t.status in ($status) "
                . $sqlAux
                . " ORDER BY c.n_curso, ci.sg_ciclo, t.letra";
        $query = pdoSis::getInstance()->query($sql);
        $c = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($c)) {
            return $c;
        } else {
            return [];
        }
    }

    /**
     *  * option para select por ciclos, cursos, segmentos, ano, status
     * se não enviar a instâcia a instâcia será a logada
     * @param type $ciclo
     * @param type $id_curso
     * @param type $id_ensino segmento
     * @param type $ano
     * @param int $status
     * @param type $id_inst instancia
     * @return type
     */
    public static function optionNome($ciclo = NUll, $id_curso = NULL, $id_ensino = NULL, $status = NULL, $id_inst = NULL) {
        $turma = self::Listar($ciclo, $id_curso, $id_ensino, $status, $id_inst);

        if (empty($turma)) {
            return [];
        }

        foreach ($turma as $v) {
            $option[$v['id_turma']] = $v['n_turma'];
        }

        return $option;
    }

    public static function validaEscolhaTurma($ins, $transf = false){ 
        $inscricoes = self::getInscricoes($ins['fk_id_pessoa']);
        $p = self::getPessoa($ins['fk_id_pessoa']);
        $idade = data::idade($p['dt_nasc']);

        if (tool::variavelVazia(@$ins['id_curso'], @$ins['dia_sem'], @$ins['hora_inicio'], @$ins['hora_fim'], @$ins['idade_ate'], @$ins['idade_de'])) {
            $t = self::getTurma($ins['fk_id_turma']);
            $ins['id_curso'] = $t['id_curso'];
        }

        if ((!empty($ins['idade_ate']) && $idade > $ins['idade_ate']) || (!empty($ins['idade_de']) && $idade < $ins['idade_de'])) {
            return "Idade incompatível com a idade permitida para esta Turma";
        }

        if (!empty($inscricoes)) {
            $setup = self::setup();
            $n_curso = strtolower($setup['n_curso']);
            $n_curso_artigo = $setup['n_curso_artigo'];

            if (empty($transf) && !self::podeMatricSemVaga() && count($inscricoes) >= $setup['qt_curso_aluno']){
                $s = count($inscricoes) > 1 ? 's' : '';
                return 'Você já está inscrito em '.count($inscricoes).' '.$n_curso.$s.' e não pode se inscrever em um'.($n_curso_artigo=='a' ? 'a' : '').' nov'.($n_curso_artigo=='a' ? 'a' : 'o').' '.$n_curso;
            }

            foreach ($inscricoes as $key => $value) {
                // if ($value['dia_sem'] == $ins['dia_sem']){
                    // return 'Não é possível se inscrever em mais de um'.($n_curso_artigo=='a' ? 'a' : '').' '.$n_curso.' no mesmo dia. Você já está inscrito n'.($n_curso_artigo=='a' ? 'a' : 'o').' '.$n_curso.' ['.$value['n_curso'].'] na ['.data::diasDaSemana($value['dia_sem']-1, null, true).']';
                // }

                if (empty($transf) && $value['id_curso'] == $ins['id_curso']){
                    return 'Você já está inscrito n'.($n_curso_artigo=='a' ? 'a' : 'o').' '.$n_curso.' ['.$value['n_curso'].']';
                }

                // se for no mesmo dia, valida o horario
                if ($value['dia_sem'] == $ins['dia_sem']) {
                    $valIni = self::validHoursRange($ins['hora_inicio'], $value['hora_inicio'], $value['hora_fim']);
                    $valTer = self::validHoursRange($ins['hora_fim'], $value['hora_inicio'], $value['hora_fim']);
                    if ($valIni || $valTer) {
                        // return 'Não é possível se inscrever nest'.($n_curso_artigo=='a' ? 'a' : 'e').' '.$n_curso.' das '.$ins['hora_inicio'].' às '.$ins['hora_fim'].' pois você já está inscrito n'.($n_curso_artigo=='a' ? 'a' : 'o').' '.$n_curso.' ['.$value['n_curso'].'] entre às '. $value['hora_inicio'] .' e '. $value['hora_fim'] .' na ['.data::diasDaSemana($value['dia_sem']-1, null, true).']';
                        return 'Neste horário você estará n'.($n_curso_artigo=='a' ? 'a' : 'o').' '.$n_curso.' ['.$value['n_curso'].'] entre às '. $value['hora_inicio'] .' e '. $value['hora_fim'];
                    }
                }
            }
        }

        $vagas = self::getVagas($ins['fk_id_turma']);
        $vagas = current($vagas);
        if (!self::podeMatricSemVaga() && empty($vagas['vagas'])){
            return 'Não há vagas disponíveis para '.($n_curso_artigo=='a' ? 'a' : 'o').' '.$n_curso.' '.$vagas['n_curso'].']';
        }

        return null;
    }

    public static function getInscricoes($id_pessoa)
    {
        $sql = "SELECT tt.*, tc.id_curso, tc.n_curso, tp.id_inst AS id_polo, tp.n_inst AS n_polo, ti.id_inscricao, DATE_FORMAT(ti.dt_inscricao, '%d/%m/%Y') AS dt_inscricao, ti.* "
            . " FROM (
                    select fk_id_pessoa, id_turma_aluno AS id_inscricao, dt_matricula dt_inscricao, fk_id_turma
                    from ge_turma_aluno 
                    WHERE fk_id_pessoa = $id_pessoa AND fk_id_tas = 0
                    group by fk_id_turma
                ) ti "
            . " JOIN ge_turmas tt ON ti.fk_id_turma = tt.id_turma "
            . " JOIN ge_ciclos c on tt.fk_id_ciclo = c.id_ciclo  "
            . " JOIN ge_cursos tc ON c.fk_id_curso = tc.id_curso "
            . " JOIN ge_tp_ensino tm ON tc.fk_id_tp_ens = tm.id_tp_ens "
            . " JOIN ge_periodo_letivo pl ON tt.fk_id_pl = pl.id_pl "
            . " JOIN instancia tp ON tt.fk_id_inst = tp.id_inst "
            . " WHERE ti.fk_id_pessoa = $id_pessoa AND pl.at_pl = 1 "
            . " GROUP BY tt.id_turma";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public static function getPessoa($id_pessoa) {
        $sql = " 
            SELECT 
                p.id_pessoa, 
                p.n_pessoa, 
                p.sexo, 
                p.email, 
                p.dt_nasc,
                COALESCE(
                    (
                        SELECT COUNT(*)
                        FROM ge_turma_aluno ta
                        INNER JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma
                        INNER JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl AND pl.at_pl = 1
                        WHERE ta.fk_id_pessoa = p.id_pessoa AND ta.fk_id_tas = 0
                    ), 
                    0
                ) AS qt_turmas
            FROM 
                pessoa p
            WHERE 
                p.id_pessoa = $id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    
    public static function getTurma($id_turma) {
        $sql = " SELECT t.id_turma, t.n_turma, ci.id_ciclo, c.id_curso "
                . " FROM ge_turmas t "
                . " JOIN ge_ciclos ci ON ci.id_ciclo = t.fk_id_ciclo "
                . " JOIN ge_cursos c ON c.id_curso = ci.fk_id_curso "
                . " WHERE t.id_turma = $id_turma";
        $query = pdoSis::getInstance()->query($sql);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public static function getVagas($id_turma)
    {
        $sql = "SELECT IF(tt.qt_turma > 0, tt.qt_turma, ts.qt_turma) - IFNULL((
                SELECT COUNT(1) FROM ge_turmas ct
                    JOIN ge_periodo_letivo cpl ON ct.fk_id_pl = cpl.id_pl
                    JOIN ge_turma_aluno cta ON cta.fk_id_turma = ct.id_turma AND cta.fk_id_tas = 0
                    WHERE cpl.at_pl = 1 AND ct.id_turma = tt.id_turma
                    GROUP BY ct.id_turma 
                ),0) AS vagas, tc.n_curso "
            . " FROM ge_turmas tt "
            . " JOIN ge_ciclos c on tt.fk_id_ciclo = c.id_ciclo  "
            . " JOIN ge_cursos tc ON c.fk_id_curso = tc.id_curso "
            . " JOIN ge_tp_ensino tm ON tc.fk_id_tp_ens = tm.id_tp_ens "
            . " JOIN ge_periodo_letivo pl ON tt.fk_id_pl = pl.id_pl "
            . " JOIN instancia tp ON tt.fk_id_inst = tp.id_inst "
            . " WHERE tt.id_turma = $id_turma AND pl.at_pl = 1 ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $r = [];
        if (!empty($array)) {
            $r = $array;
        }
        return $r;
    }
}
