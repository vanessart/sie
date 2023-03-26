<?php

class ng_professor {

    public static function gets() {
        $sql = "SELECT"
                . " id_fun, id_pessoa, n_pessoa, sexo, id_inst, n_inst, id_fs, n_fs, id_ff, n_ff "
                . " FROM funcionario f "
                . " JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " JOIN instancia i on i.id_inst = f.fk_id_inst "
                . " JOIN funcionario_situacao fs on fs.id_fs = f.fk_id_fs "
                . " JOIN funcionario_funcao ff on ff.id_ff = f.fk_id_ff "
                . " order by n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function idName() {
        $array = sql::get(['funcionario', 'pessoa'], 'CONCAT( n_pessoa," (",registro,")") as n_pessoa, id_fun ');

        return tool::idName($array);
    }

    public static function professorEscola($id_inst, $id_fun = NULL, $fields = NULL) {
        if (!empty($id_fun)) {
            $id_fun = " and fk_id_fun = $id_fun";
        }
        if (empty($fields)) {
            $fields = " f.id_fun, f.registro, p.n_pessoa, p.id_pessoa, "
                    . " ape.id_ape, ape.disciplinas, u.id_user, "
                    . " ape.hac_dia, ape.hac_periodo, ape. nao_hac ";
        }
        $sql = "SELECT $fields "
                . " FROM gt_aloca_prof_esc ape "
                . " join funcionario f on f.id_fun = ape. fk_id_fun "
                . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " LEFT JOIN users u on u.fk_id_pessoa=p.id_pessoa "
                . " WHERE ape.`fk_id_inst` = $id_inst "
                . $id_fun
                . " order by n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        if (empty($id_fun)) {
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $array = $query->fetch(PDO::FETCH_ASSOC);
        }



        return $array;
    }

    public static function profEscDisc($id_inst) {
        $prof = professor::professorEscola($id_inst);
        foreach ($prof as $v) {
            $disc = explode(',', $v['disciplinas']);
            foreach ($disc as $y) {
                @$profDisc[$y][$v['id_fun']] = $v['n_pessoa'] . '(' . $v['registro'] . ')';
            }
        }

        return @$profDisc;
    }

    public static function alocacao($search, $field = 'fk_id_turma') {
        $a = sql::get('gt_aloca_prof_disc', '*', [$field => $search]);
        if (!empty($a)) {
            foreach ($a as $v) {
                $alocado[$v['fk_id_disc']][$v['prof']] = $v['fk_id_fun'];
            }

            return$alocado;
        }
    }

    public static function turmasDisc($id_fun) {
        $id_pl = gt_main::periodoAtivo();

        $sql = "SELECT "
                . " t.id_turma, t.n_turma, t.fk_id_pl, i.n_inst, i.id_inst, d.id_disc, d.n_disc "
                . " FROM gt_aloca_prof_disc apd "
                . " JOIN gt_turma t on t.id_turma = apd.fk_id_turma "
                . " JOIN instancia i on i.id_inst = t.fk_id_inst "
                . " JOIN gt_disciplina d on d.id_disc = apd.fk_id_disc "
                . " WHERE `fk_id_fun` LIKE '$id_fun' "
                . " AND i.id_inst = " . tool::id_inst()
                . " AND t.fk_id_pl IN (" . (implode(',', array_keys($id_pl))) . ")";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function cursos($id_pessoa) {
        $sql = "SELECT DISTINCT c.* FROM gt_aloca_prof_disc ad "
                . "JOIN gt_turma t on t.id_turma = ad.fk_id_turma "
                . " JOIN gt_ciclo ci on ci.id_ciclo = t.fk_id_ciclo "
                . " JOIN gt_curso c on c.id_cur = ci.fk_id_cur "
                . " JOIN funcionario f on f.id_fun = ad.fk_id_fun "
                . " WHERE f.fk_id_pessoa = $id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function ciclosDisc($id_pessoa, $id_inst = NULL, $fields = NULL, $id_plArray = NULL) {
        if($id_inst){
            $id_inst = " and t.fk_id_inst = $id_inst ";
        }
        $fialds = " ap.iddisc, ap.iddisc id_disc, ap.rm, IFNULL(d.n_disc, 'Núcleo Comum') n_disc, ci.n_ciclo, ci.id_ciclo, i.n_inst, i.id_inst, "
                . " c.atual_letiva, c.qt_letiva, c.un_letiva, t.fk_id_pl id_pl, c.id_curso ";
        $sql = " SELECT $fialds FROM pessoa p "
                . " JOIN ge_funcionario f on f.fk_id_pessoa = p.id_pessoa AND f.fk_id_pessoa = $id_pessoa "
                . " JOIN ge_aloca_prof ap on ap.rm = f.rm "
                . " JOIN ge_turmas t on t.id_turma = ap.fk_id_turma $id_inst"
                . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " JOIN ge_cursos c on c.id_curso = ci.fk_id_curso "
                . " JOIN instancia i on i.id_inst = ap.fk_id_inst LEFT "
                . " JOIN ge_disciplinas d on d.id_disc = ap.iddisc"
                . " order by n_ciclo ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $cicloDiscEsc[$v['id_inst'] . '_' . $v['id_ciclo'] . '_' . $v['iddisc']] = $v;
        }
        if (!empty($cicloDiscEsc)) {
            return $cicloDiscEsc;
        }
    }

    public static function disciplinas($id_pessoa) {
        $sql = "SELECT "
                . " DISTINCT d.id_disc, d.n_disc "
                . " FROM gt_aloca_prof_disc a "
                . " JOIN gt_disciplina d ON d.id_disc = a.fk_id_disc "
                . " JOIN gt_turma t on t.id_turma = a.fk_id_turma "
                . " JOIN funcionario f on f.id_fun = a.fk_id_fun "
                . " WHERE f.fk_id_pessoa = $id_pessoa "
                . " AND t.fk_id_inst = " . tool::id_inst();
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return tool::idName($array);
    }

    public static function profTurmaPl($id_pessoa) {
        $sql = "SELECT "
                . " t.id_turma, t.n_turma, fk_id_pl "
                . " FROM funcionario f "
                . " JOIN gt_aloca_prof_disc a on a.fk_id_fun = f.id_fun "
                . " JOIN gt_turma t on t.id_turma = a.fk_id_turma "
                . " JOIN gt_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " WHERE f.fk_id_pessoa = $id_pessoa "
                . " AND pl.at_pl = 1 "
                . " AND t.fk_id_inst = " . tool::id_inst();
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($array)) {
            return $array;
        } else {
            return;
        }
    }

    public static function profTurma($id_pessoa) {
        $sql = "SELECT "
                . " t.id_turma, concat(t.n_turma, ' - ', i.n_inst) n_turma "
                . " FROM ge_funcionario f "
                . " JOIN ge_aloca_prof a on a.rm = f.rm "
                . " JOIN ge_turmas t on t.id_turma = a.fk_id_turma "
                . " Join instancia i on  i.id_inst = t.fk_id_inst "
                . " WHERE f.fk_id_pessoa = $id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($array)) {
            return tool::idName($array);
        } else {
            return;
        }
    }

    public static function aulaDiaSemana($id_pessoa) {
        $sql = "SELECT "
                . " d.id_disc, h.dia_semana, h.aula, t.id_turma "
                . " FROM gt_horario h "
                . " JOIN gt_aloca_prof_disc a on a.fk_id_turma = h.fk_id_turma "
                . " JOIN funcionario f on f.id_fun = a.fk_id_fun "
                . " JOIN gt_disciplina d on d.id_disc = a.fk_id_disc "
                . " JOIN gt_turma t on t.id_turma = a.fk_id_turma "
                . " JOIN gt_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " WHERE 1 "
                . " AND f.fk_id_pessoa = $id_pessoa "
                . " AND pl.at_pl = 1 ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $ad[$v['id_turma']][$v['id_disc']][$v['dia_semana']][$v['aula']] = $v['aula'];
        }

        return @$ad;
    }

    public static function horario($id_pessoa, $id_inst, $id_turma = null) {
        if (!empty($id_turma)) {
            $id_turma = " And h.fk_id_turma = $id_turma ";
        }
        $sql = "SELECT "
                . " d.n_disc, d.id_disc, h.dia_semana, h.aula, t.n_turma, t.id_turma, t.sala_virtual "
                . " FROM gt_horario h "
                . " JOIN gt_aloca_prof_disc a on a.fk_id_turma = h.fk_id_turma "
                . " JOIN funcionario f on f.id_fun = a.fk_id_fun "
                . " JOIN gt_disciplina d on d.id_disc = a.fk_id_disc "
                . " JOIN gt_turma t on t.id_turma = a.fk_id_turma "
                . " JOIN gt_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " WHERE 1 "
                . $id_turma
                . " AND f.fk_id_pessoa = $id_pessoa "
                . " AND t.fk_id_inst = $id_inst "
                . " AND pl.at_pl = 1 ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $h[$v['dia_semana']][$v['aula']] = [
                'n_turma' => $v['n_turma'],
                'n_disc' => $v['n_disc'],
                'id_turma' => $v['id_turma'],
                'id_disc' => $v['id_disc'],
                'sala_virtual' => $v['sala_virtual'],
            ];
        }
        if (!empty($h)) {
            ksort($h);

            return $h;
        } else {
            return;
        }
    }

    public static function turmasDiscEsc($id_pessoa, $id_ciclo, $id_disc, $id_inst) {
        $sql = " select t.id_turma, t.n_turma from pessoa p "
                . " join ge_funcionario f on f.fk_id_pessoa = p.id_pessoa and p.id_pessoa = $id_pessoa "
                . " join ge_aloca_prof ap on ap.rm = f.rm and ap.fk_id_inst = $id_inst"
                . " join ge_turmas t on t.id_turma = ap.fk_id_turma and fk_id_ciclo = $id_ciclo ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $v) {
                $t[$v['id_turma']] = $v['n_turma'];
            }
            return $t;
        }
    }

    public static function profeSalaAluno($id_pessoa, $sexo=null) {
        $sql = "SELECT DISTINCT prof.rm, p.n_pessoa, p.id_pessoa, p.sexo "
        . " FROM ge_turma_aluno ta "
        . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma AND fk_id_ciclo != 32"
        . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
        . " JOIN ge_aloca_prof prof on prof.fk_id_turma = t.id_turma "
        . " join ge_funcionario f on f.rm = prof.rm "
        . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
        . " WHERE ta.fk_id_pessoa = $id_pessoa;";
        $query = pdoSis::getInstance()->query($sql);
        $nc = $query->fetchAll(PDO::FETCH_ASSOC);
        $prof_turma = "";
        $vir = "";
        $plural = false;
        $sexo_prof = 'Professor';

        foreach ($nc as $k => $v) {
            $prof_turma = $prof_turma.$vir." ".$v['n_pessoa'] . ' (' . $v['rm'] . ')';
            $vir = ",";
            if (!empty($sexo)) {
                if (count($nc) == 1){

                    if ($v['sexo'] == 'F') {
                       $sexo_prof .= 'a';
                    }

                } else {

                    if ($v['sexo'] == 'M') {
                       $sexo_M = true;
                    }
                    if ($v['sexo'] == 'F') {
                       $sexo_F = true;
                    }

                }
            }
        }
        if (!empty($sexo)) {
            if (!empty($nc)) {
                if (!empty($sexo_M) && !empty($sexo_F)) {
                    $sexo_prof = 'Professores(as)';
                } elseif (!empty($sexo_M)) {
                    $sexo_prof = 'Professores';
                } elseif (!empty($sexo_F)) {
                    $sexo_prof = 'Professoras';
                }
            }

            return $sexo_prof;
        }else{
            return $prof_turma;
        }  
    }

}
