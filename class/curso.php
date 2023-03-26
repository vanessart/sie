<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of curso
 *
 * @author marco
 */
class curso {

    public static function ciclos($id_curso) {
        $sql = "select *, ge_ciclos.ativo as atciclo from ge_ciclos "
                . "join ge_cursos on  ge_cursos.id_curso = ge_ciclos.fk_id_curso "
                . "left join ge_grades on ge_grades.id_grade = ge_ciclos.fk_id_grade "
                . "where id_curso = $id_curso "
                . "order by n_ciclo ";
        $query = pdoSis::getInstance()->query($sql);
        $ciclos = $query->fetchAll(PDO::FETCH_ASSOC);
        /**
          $ciclos = sql::get(['ge_ciclos', 'ge_cursos'], '*', ['id_curso' => $id_curso, '>' => 'n_ciclo']);
         * 
         */
        return $ciclos;
    }

    /**
     * define se o curso será anual, semestral etc
     * @param type $tipo
     * @return string
     */
    public static function periodtipo($tipo = NULL) {
        $p_ = sql::get('ge_periodicidade', '*');
        foreach ($p_ as $v) {
            $p[$v['id_pe']] = $v['n_pe'];
        }
        if (empty($tipo)) {
            return $p;
        } else {
            return $p[$tipo];
        }
    }

    /**
     * se for anual o ano e o próximo ano, e assim vai
     * @param type $periodicidade refere a classe acima
     */
    public static function periodList($id_ciclo) {
        $sql = " select * from ge_periodo_ciclo pc "
                . " join ge_periodo_letivo pl on pl.id_pl = pc.fk_id_pl "
                . " where fk_id_ciclo = $id_ciclo "
                . " and at_pl in (1,2) ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $list[$v['id_pl']] = $v['n_pl'];
        }
        return $list;
    }

    public static function periodoLetivoAtual($id_curso) {
        $sql = "select n_pl from ge_turmas t "
                . "join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " where at_pl = 1 "
                . "and t.id_turma = '$id_turma'";
        $query = autenticador::getInstance()->query($sql);
        return $query->fetch(PDO::FETCH_ASSOC)['n_pl'];
    }

    public static function gradeCiclo($id_ciclo, $idNome = NULL) {
        $sql = "select g.id_grade, g.n_grade from ge_grades g "
                . " join ge_curso_grade cg on cg.fk_id_grade = g.id_grade "
                . " where cg.fk_id_ciclo =  " . @$id_ciclo;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($array)) {
            if (empty($idNome)) {
                return $array;
            } else {
                foreach ($array as $v) {
                    $grade[$v['id_grade']] = $v['n_grade'];
                }
                return $grade;
            }

            formulario::select('id_grade', $grade, 'Grades', @$_POST['id_grade'], 1, $hidden);
            $hidden['id_grade'] = @$_POST['id_grade'];
        } else {
            ?>
            <div class="alert alert-danger">
                Não há grade alocada neste ciclo
            </div>
            <?php
            return;
        }
    }

    public static function periodoLetivoSituacao($situacao = NULL) {
        $si = ['Inativo', 'Ativo', 'Previsto'];
        if (empty($situacao)) {
            return $si;
        } else {
            return $si[$situacao];
        }
    }

    public static function periodoLetMax() {
        $sql = "SELECT `qt_letiva` FROM `ge_cursos` "
                . " WHERE `ativo` = 1 "
                . "order by qt_letiva desc";
        $query = autenticador::getInstance()->query($sql);
        return $query->fetch(PDO::FETCH_ASSOC)['qt_letiva'];
    }

    public static function unidLetivaAtual($id_turma) {
        $sql = "select atual_letiva from ge_cursos c "
                . "join ge_ciclos ci on ci.fk_id_curso = c.id_curso "
                . "join ge_turmas t on t.fk_id_ciclo = ci.id_ciclo "
                . "where id_turma = '$id_turma'";
        $query = autenticador::getInstance()->query($sql);
        return $query->fetch(PDO::FETCH_ASSOC)['atual_letiva'];
    }

    public static function id_pl_atual() {
        $sql = "select id_pl, n_pl from ge_periodo_letivo "
                . " where at_pl = 1 AND semestre = 0 ";
        $query = autenticador::getInstance()->query($sql);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

}
