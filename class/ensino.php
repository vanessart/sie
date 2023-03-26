<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ensino
 *
 * @author marco
 */
class ensino {

    //put your code here
    public static function disciplina() {
        $dis = sql::get('ge_disciplinas', 'id_disc, n_disc');
        foreach ($dis as $v) {
            $disc[$v['id_disc']] = $v['n_disc'];
        }
        return $disc;
    }

    public static function qaFund($id_inst = NULL, $ano = NULL) {

        if (!empty($id_inst)) {
            $id_inst = " and a.fk_id_inst = $id_inst";
        }
        if (empty($ano)) {
            $ano = date("Y");
        }

        $sql = "SELECT "
                . "fk_id_ciclo, periodo, t.fk_id_inst, id_turma, i.n_inst, e.cie_escola "
                . "FROM ge_turma_aluno a "
                . " JOIN ge_turma_aluno_situacao tas on tas.id_tas = a.fk_id_tas AND tas.id_tas = 0 "
                . "JOIN ge_turmas t on t.id_turma = a.fk_id_turma "
                . "join instancia i on i.id_inst = t.fk_id_inst "
                . "join ge_escolas e on i.id_inst = e.fk_id_inst "
                . "WHERE fk_id_ciclo <= 9  "
                . "AND t.periodo_letivo = '" . $ano . "'"
                . "$id_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $alu = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($alu as $v) {
            @$a['escola'][$v['fk_id_inst']] = $v['n_inst'];
            @$a['cie'][$v['fk_id_inst']] = $v['cie_escola'];
            $periodo = $v['periodo'] == 'I' ? 'M' : $v['periodo'];
            @$a['Aluno'] ++;
            @$a['AlunoEscola'][$v['fk_id_inst']] ++;
            @$a['AlunoCicloPeriodo'][$v['fk_id_inst']][$periodo] [$v['fk_id_ciclo']] ++;
            @$a['totalAlunoCiclo'][$v['fk_id_ciclo']] ++;
        }
        $sql = "SELECT "
                . "fk_id_ciclo, periodo, t.fk_id_inst "
                . "FROM ge_turmas t "
                . "WHERE fk_id_ciclo <= 9  "
                . " and periodo_letivo like '%" . $ano . "' "
                . "$id_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $turma = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($turma as $v) {
            $periodo = $v['periodo'] == 'I' ? 'M' : $v['periodo'];
            @$a['turmas'][$v['fk_id_inst']][$periodo][$v['fk_id_ciclo']] ++;
            @$a['totalTurmas'][$v['fk_id_ciclo']] ++;
            @$a['totalTurmasG'] ++;
        }

        return $a;
    }

    public static function qa($ciclos, $id_inst = NULL, $ano = NULL) {
        $unicoPeriodo = ['G', 'I', 'N'];

        if (!empty($id_inst)) {
            $id_inst = " and a.fk_id_inst = $id_inst";
        }
        if (empty($ano)) {
            $ano = date("Y");
        }

        $sql = "SELECT "
                . " fk_id_ciclo, periodo, t.fk_id_inst, id_turma, i.n_inst, e.cie_escola "
                . " FROM ge_turma_aluno a "
                . " JOIN ge_turma_aluno_situacao tas on tas.id_tas = a.fk_id_tas AND tas.id_tas = 0 "
                . " JOIN ge_turmas t on t.id_turma = a.fk_id_turma "
                . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " join instancia i on i.id_inst = t.fk_id_inst "
                . " join ge_escolas e on i.id_inst = e.fk_id_inst "
                . " WHERE fk_id_ciclo in (" . implode(",", $ciclos) . ")  "
                . " AND t.periodo_letivo like '%" . $ano . "'"
                . " $id_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $alu = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($alu as $v) {
            @$a['escola'][$v['fk_id_inst']] = $v['n_inst'];
            @$a['cie'][$v['fk_id_inst']] = $v['cie_escola'];
            $periodo = in_array($v['periodo'], $unicoPeriodo) ? 'M' : $v['periodo'];
            @$a['Aluno'] ++;
            @$a['AlunoEscola'][$v['fk_id_inst']] ++;
            @$a['AlunoCicloPeriodo'][$v['fk_id_inst']][$periodo] [$v['fk_id_ciclo']] ++;
            @$a['totalAlunoCiclo'][$v['fk_id_ciclo']] ++;
        }
        $sql = "SELECT "
                . "fk_id_ciclo, periodo, t.fk_id_inst "
                . "FROM ge_turmas t "
                . "WHERE fk_id_ciclo in (" . implode(",", $ciclos) . ")   "
                . " and periodo_letivo like '%" . $ano . "' "
                . "$id_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $turma = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($turma as $v) {

            $periodo = in_array($v['periodo'], $unicoPeriodo) ? 'M' : $v['periodo'];
            @$a['turmas'][$v['fk_id_inst']][$periodo][$v['fk_id_ciclo']] ++;
            @$a['totalTurmas'][$v['fk_id_ciclo']] ++;
            @$a['totalTurmasG'] ++;
        }

        return $a;
    }

    public static function aulasDisc() {
        $n_disc = disciplina::discId();

        $sql = "SELECT id_inst, n_inst FROM ge_escolas e "
                . "join instancia i on i.id_inst = e.fk_id_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {

            $sql = "SELECT * FROM ge_aloca_prof p "
                    . "join ge_turmas t on t.id_turma = p.fk_id_turma "
                    . "WHERE p.fk_id_inst = '" . $v['id_inst'] . "' ";
            $query = pdoSis::getInstance()->query($sql);
            $classe = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($classe as $cl) {
                @$dados[$v['n_inst']][$cl['periodo']][$n_disc[$cl['iddisc']]] ++;
            }
        }
        return $dados;
    }

}
