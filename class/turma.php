<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of turma
 *
 * @author marco
 */
class turma {

    public static function liste($search = NULL, $periodo = NULL, $field = 'fk_id_inst', $ciclos = NULL) {
        if (empty($search)) {
            $search = tool::id_inst();
        }
        if (empty($periodo)) {
            $pe = gtMain::periodos(1);
            foreach ($pe as $k => $v) {
                $periodo[] = $k;
            }
            $periodo = implode(',', $periodo);
        }
        if (!empty($ciclos)) {
            $ciclos = " and fk_id_ciclo in ($ciclos) ";
        }
        $fields = " c.id_curso, c.sg_curso, c.notas, c.corte, "
                . " c.atual_letiva, s.n_sala, c.n_curso, ci.sg_ciclo, "
                . " ci.id_ciclo, ci.n_ciclo, pr.n_predio, "
                . " t.id_turma, t.n_turma, t.fk_id_inst, t.fk_id_ciclo,"
                . " t.fk_id_grade as fk_id_grade, t.fk_id_sala,"
                . " t.professor,t.rm_prof, t.codigo, t.periodo,"
                . " t.periodo_letivo, t.fk_id_pl, t.letra, t.prodesp,"
                . " t.status";
        $sql = "select $fields from ge_turmas t "
                . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo  "
                . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " join ge_cursos c on c.id_curso = ci.fk_id_curso "
                . " left join salas s on s.id_sala = t.fk_id_sala "
                . " left join predio pr on pr.id_predio = s.fk_id_predio "
                . " where $field = $search "
                . " $ciclos "
                . " and pl.id_pl in ($periodo) "
                . " order by c.n_curso, ci.sg_ciclo, t.letra";
        $query = autenticador::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    /**
     * posssíveis status para turma
     * @return type
     */
    public static function status($id_turma = NULL) {
        $status = ['Fechada', 'Aberta', 'Cancelada', 'Inativa', 'Encerrada'];
        if (empty($id_turma)) {
            return $status;
        } else {
            $turma = sql::get(['ge_turmas', 'ge_periodo_letivo'], 'status, at_pl', ['id_turma' => $id_turma], 'fetch');
            if (@$turma['status'] == 1) {
                return curso::periodoLetivoSituacao(@$turma['at_pl']);
            } else {
                return $status[@$turma['status']];
            }
        }
    }

    public static function codigo($id_turma = NULL) {
        $codigo = sql::get('ge_turmas', 'codigo', ['id_turma' => $id_turma], 'fetch')['codigo'];
        return $codigo;
    }

    public static function disciplinas($id_turma, $fields = NULL) {
        if (empty($fields)) {
            $fields = "ge_turmas.fk_id_grade, nucleo_comum, id_disc, n_disc, sg_disc, aulas";
        }

        $sql = "select $fields from ge_turmas "
                . "join ge_aloca_disc on ge_aloca_disc.fk_id_grade = ge_turmas.fk_id_grade "
                . "join ge_disciplinas on ge_disciplinas.id_disc = ge_aloca_disc.fk_id_disc "
                . "where id_turma = $id_turma";
        $query = autenticador::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function habilidades($id_turma, $fields = NULL) {
        if (empty($fields)) {
            $fields = "ge_turmas.fk_id_grade, nucleo_comum, id_hab, n_hab, sg_hab";
        }

        $sql = "select $fields from ge_turmas "
                . "join ge_aloca_hab on ge_aloca_hab.fk_id_grade = ge_turmas.fk_id_grade "
                . "join ge_habilidades on ge_habilidades.id_hab = ge_aloca_hab.fk_id_hab "
                . "where id_turma = $id_turma";
        $query = autenticador::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function ultimo_turmaNovaChamada($idturma) {

        $sql = "Select *  from ge_turma_aluno Where fk_id_turma = " . $idturma . " order by chamada desc limit 0,1";
        $query = pdoSis::getInstance()->query($sql);
        $a = $query->fetch(PDO::FETCH_ASSOC);

        $a['chamada'] += 1;

        return $a;
    }

    public static function periodoTodos($at_pl = NULL) {
        if (empty($at_pl)) {
            $at_pl = 1;
        }
        $tp = sql::get('ge_periodo_letivo', 'n_pl', ['at_pl' => $at_pl]);
        foreach ($tp as $v) {
            $todosPeriodos[] = $v['n_pl'];
        }

        return $todosPeriodos;
    }

    public static function option($id_inst = NULL, $periodos = NULL, $field = 'fk_id_inst', $ciclos = NULL) {
        $turma = turma::liste($id_inst, $periodos, $field, $ciclos);
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

}
