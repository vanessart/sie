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
}
