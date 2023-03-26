<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of turmas

 * @author marco
 */
class turmas {

    /**
     *  * listas as classes por ciclos, cursos, segmentos, ano, status
     * se não enviar a instâcia a instâcia será a logada
     * @param type $ciclo
     * @param type $id_curso
     * @param type $id_ensino segmento
     * @param type $ano
     * @param int $status
     * @param type $id_inst instancia
     * @return type
     */
    public static function Listar($ciclo = NUll, $id_curso = NULL, $id_ensino = NULL, $ano = NULL, $status = NULL, $id_inst = NULL) {
        if (empty($ano)) {
            $ano = " and periodo_letivo like '%". date('Y')."%' ";
        }else{
            if($ano > 2000){
                $ano = " and periodo_letivo like '%$ano%' ";
            } else {
                $ano = " and fk_id_pl = $ano ";
            }
            
        }
        if (!empty($ciclo) || $ciclo == '0') {
            $ciclo = "and id_ciclo in ($ciclo)";
        }
        if (!empty($id_curso)) {
            $id_curso = "and id_curso = '$id_curso'";
        }
        if (!empty($id_ensino)) {
            $id_ensino = "and fk_id_tp_ens = '$id_ensino'";
        }
        if (empty($id_inst)) {
            $id_inst = tool::id_inst();
        }

        if (empty($status)) {
            $status = '0,1';
        }
        $sql = "SELECT "
                . " id_turma, n_turma, fk_id_pl, codigo, periodo, prodesp, status, n_ciclo, aprova_automatico, n_curso, n_grade, id_grade, n_sala, cadeirante "
                . " FROM `ge_turmas` "
                . "join ge_ciclos on ge_ciclos.id_ciclo =  ge_turmas.fk_id_ciclo "
                . "join ge_cursos on ge_cursos.id_curso = ge_ciclos.fk_id_curso "
                . "left join ge_grades on ge_grades.id_grade = ge_turmas.fk_id_grade "
                . "left join salas on salas.id_sala = ge_turmas.fk_id_sala "
                . " WHERE `fk_id_inst` =  " . $id_inst
                . ' and ge_cursos.extra <> 1 '
                . $ano
                . " and ge_turmas.status in ($status) "
                . $ciclo
                . $id_curso
                . $id_ensino
                . " order by n_curso, sg_ciclo, letra";
        $query = pdoSis::getInstance()->query($sql);
        $c = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($c)) {
            return $c;
        } else {
            return;
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
    public static function option($ciclo = NUll, $id_curso = NULL, $id_ensino = NULL, $ano = NULL, $status = NULL, $id_inst = NULL) {
        $turma = turmas::Listar($ciclo, $id_curso, $id_ensino, $ano, $status, $id_inst);
        if (!empty($turma)) {
            foreach ($turma as $v) {
                $option[$v['id_turma']] = $v['codigo'];
            }
        }
        if (!empty($option)) {
            return $option;
        } else {
            return;
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
    public static function optionNome($ciclo = NUll, $id_curso = NULL, $id_ensino = NULL, $ano = NULL, $status = NULL, $id_inst = NULL) {
        $turma = turmas::Listar($ciclo, $id_curso, $id_ensino, $ano, $status, $id_inst);

        if (empty($turma)) {
            return [];
        }

        foreach ($turma as $v) {
            $option[$v['id_turma']] = $v['n_turma'];
        }

        return $option;
    }
    
    public static function classe($id_turma) {
        $sql = "SELECT "
                . " ta.fk_id_turma, t.codigo as codigo_classe, ta.situacao, p.id_pessoa, p.n_pessoa, ta.chamada, p.dt_nasc, ta.id_turma_aluno, t.n_turma "
                . " FROM `ge_turma_aluno` ta "
                . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " join pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " WHERE fk_id_turma =  " . $id_turma
                . " order by chamada";
        $query = pdoSis::getInstance()->query($sql);
        $c = $query->fetchAll(PDO::FETCH_ASSOC);
        return $c;
    }

    public static function disciplinas($id_turma) {
        $id_grade = sql::get('ge_turmas', 'fk_id_grade', ['id_turma' => $id_turma], 'fetch')['fk_id_grade'];
        $disc_ = sql::get(['ge_aloca_disc', 'ge_disciplinas'], ' id_disc, n_disc, nucleo_comum ', ['fk_id_grade' => $id_grade, '>' => 'ordem']);
        foreach ($disc_ as $v) {
            if ($v['nucleo_comum'] == 1) {
                $disc['nucleComum'][$v['id_disc']] = $v['n_disc'];
            } else {
                $disc['disciplina'][$v['id_disc']] = $v['n_disc'];
            }
        }
        return $disc;
    }

    public static function disciplinasAbrev($id_turma) {
        $id_grade = sql::get('ge_turmas', 'fk_id_grade', ['id_turma' => $id_turma], 'fetch')['fk_id_grade'];
        $disc_ = sql::get(['ge_aloca_disc', 'ge_disciplinas'], ' id_disc, sg_disc, nucleo_comum ', ['fk_id_grade' => $id_grade, '>' => 'ordem']);
        foreach ($disc_ as $v) {
            if ($v['nucleo_comum'] == 1) {
                $disc['nucleComum'][$v['id_disc']] = $v['sg_disc'];
            } else {
                $disc['disciplina'][$v['id_disc']] = $v['sg_disc'];
            }
        }
        return $disc;
    }

    public static function transfSituacao($id_turma) {

        $e = sql::get('ge_turmas', 'transferencia', ['id_turma' => $id_turma], 'fetch');

        if (!empty($e)) {
            return $e['transferencia'];
        } else {
            return;
        }
    }

    public static function status($id_turma) {

        $e = sql::get('ge_turmas', 'status', ['id_turma' => $id_turma], 'fetch')['status'];

        if (!empty($e)) {
            return $e;
        } else {
            return;
        }
    }

}
