<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ng_sed
 *
 * @author marco
 */
class ng_sedErp {

    /**
     * retorna array [TipoEnsino][SerieAno]->id_ciclo
     */
    public static function cicloErpSed() {
        $sql = "SELECT sed.*, ci.n_ciclo, ci.fk_id_grade, ci.sg_ciclo, c.id_curso, c.n_curso, c.sg_curso FROM sed.ciclo_ano sed "
                . "join " . DB_NAME . ".ge_ciclos ci on ci.id_ciclo = sed.id_ciclo "
                . "join " . DB_NAME . ".ge_cursos c on c.id_curso = ci.fk_id_curso ";
        $query = pdoSed::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $id[$v['TipoEnsino']][$v['SerieAno']] = $v;
        }

        return $id;
    }

    /**
     * retorna sigla/nome do turno
     */
    public static function turno($return = 'sg_turno') {

        $sql = "SELECT * FROM `turno`";
        $query = pdoSed::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $sigla[$v['id_turno']] = $v[$return];
        }

        return $sigla;
    }

    public static function periodoLetivo($ano, $semestre = '0') {
        $sql = "SELECT id_pl FROM `ge_periodo_letivo` WHERE `ano` = $ano AND `semestre` = $semestre";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($array['id_pl'])) {
            return $array['id_pl'];
        }
    }

    public static function turmasInst($id_inst, $ano) {
        $sql = "SELECT id_turma, fk_id_ciclo, letra FROM `ge_turmas` WHERE `fk_id_inst` = $id_inst AND `periodo_letivo` LIKE '%$ano'";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $v) {
                $t[$v['fk_id_ciclo']][strtoupper($v['letra'])] = $v['id_turma'];
            }

            return $t;
        }
    }

    public static function escolaCie() {
        $sql = "SELECT "
                . " e.cie_escola, i.n_inst "
                . " FROM ge_escolas e "
                . " JOIN instancia i on i.id_inst = e.fk_id_inst "
                . " WHERE i.ativo = 1 "
                . " order by n_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return toolErp::idName($array, 'cie_escola', 'n_inst');
    }

}
