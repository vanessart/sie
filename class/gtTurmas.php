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
class gtTurmas {

    public static function turmas($search = NULL, $periodo = NULL, $field = 'fk_id_inst', $ciclos = NULL, $fields = NULL) {
        if (empty($search)) {
            $search = tool::id_inst();
        }
        if (!empty($periodo)) {

            if (!empty($ciclos)) {
                $ciclos = " and fk_id_ciclo in ($ciclos) ";
            }
            if (empty($fields)) {
                $fields = " c.id_curso, c.sg_curso, c.notas, c.corte, "
                        . " c.atual_letiva, s.n_sala, c.n_curso, ci.sg_ciclo, "
                        . " ci.id_ciclo, ci.n_ciclo, pr.n_predio, "
                        . " t.id_turma, t.n_turma, t.fk_id_inst, t.fk_id_ciclo,"
                        . " t.fk_id_grade as fk_id_grade, t.fk_id_sala,"
                        . " t.professor,t.rm_prof, t.codigo, t.periodo,"
                        . " t.periodo_letivo, t.fk_id_pl, t.letra, t.prodesp,"
                        . " ts.n_st, pl.at_pl";
            }
            $sql = "select $fields from ge_turmas t "
                    . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo  "
                    . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                    . " join ge_cursos c on c.id_curso = ci.fk_id_curso "
                    . " left join salas s on s.id_sala = t.fk_id_sala "
                    . " left join predio pr on pr.id_predio = s.fk_id_predio "
                    . " left join ge_turma_status ts on t.status = ts.id_ts "
                    . " where $field = $search "
                    . " $ciclos "
                    . " and pl.id_pl in ( $periodo ) "
                    . " order by c.n_curso, ci.sg_ciclo, t.letra";
            $query = autenticador::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);

            return !empty($array) ? $array : [];
        }

        return [];
    }

    public static function alunos($id_turma, $fields) {
        if (empty($fields)) {
            $fields = " id_pessoa, chamada, situacao, id_turma_aluno, "
                    . " n_pessoa, dt_nasc, ra, "
                    . " id_turma, n_turma, fk_id_ciclo, "
                    . " codigo, letra ";
        }
        $sql = "select $fields from pessoa p "
                . "join ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa "
                . "join ge_turmas t on t. id_turma = ta.fk_id_turma "
                . " where id_turma = $id_turma "
                . "order by chamada";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function get($id_turma, $status = 1) {
        $fields = "n_turma, fk_id_inst as id_inst, fk_id_ciclo as id_ciclo, "
                . " ci.fk_id_grade as id_grade, rm_prof, codigo, periodo, fk_id_pl as id_pl, "
                . " prodesp, c.id_curso, un_letiva, sg_letiva, qt_letiva, atual_letiva ";
        $sql = "select $fields from ge_turmas t "
                . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                . " join ge_cursos c on c.id_curso = ci.fk_id_curso "
                . " where t.id_turma = $id_turma "
                . " and t.status = $status";
        $query = pdoSis::getInstance()->query($sql);
        $turma = $query->fetch(PDO::FETCH_ASSOC);

        return $turma;
    }

    public static function professores($id_turma) {
        $sql = "SELECT "
                . "ap.iddisc, p.n_pessoa, p.id_pessoa, p.cpf, ap.prof2, f.rm "
                . "FROM ge_aloca_prof ap "
                . "left join ge_funcionario f on f.rm = ap.rm "
                . "LEFT JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . "WHERE ap.fk_id_turma = $id_turma "
                . "and p.n_pessoa is not null";
        $query = pdoSis::getInstance()->query($sql);
        $pd = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($pd as $v) {
            
            $profDisc[$v['prof2']][$v['iddisc']] =[
                  'nome'=> @$v['n_pessoa'],
                'rm'=> @$v['rm'],
                'cpf'=> @$v['cpf'],
                'id_pessoa'=> @$v['id_pessoa'],
               'abrev'=> tool::abrevia(@$v['n_pessoa'], 20),
            ];
        }
        
        return @$profDisc;
    }

    /**
     * 1 => 'Segunda', 2 => 'Terça', 3 => 'Quarta', 4 => 'Quinta', 5 => 'Sexta'
     * @param type $id_turma
     */
    public static function horario($id_turma){
        $h = sql::get('ge_horario', '*', ['fk_id_turma'=>$id_turma]);
        foreach ($h as $v){
            $horario[$v['dia_semana']][$v['aula']]=$v['iddisc'];
        }
        
        return @$horario;
    }
    
    public static function reforco($id_turma){
        $h = sql::get('ge_horario_ref', '*', ['fk_id_turma'=>$id_turma]);
        foreach ($h as $v){
            $dias[$v['dia_semana']]=$v['dia_semana'];
        }
        
        return @$dias;
    }
    
    public static function idNome($id_inst, $periodoLetivo= NULL, $ciclos = NULL){
        if(empty($id_inst)){
            $id_inst = tool::id_inst();
        }
        $turmas = gtTurmas::turmas($id_inst, $periodoLetivo, 'fk_id_inst', $ciclos);
        foreach ($turmas as $v){
            $idNome[$v['id_turma']]= $v['n_turma'];
        }
        
        return @$idNome;
    }
}
