<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of escolas
 *
 * @author marco
 */
class ng_escolas {

    public static function get($search, $field = 'id_escola') {
        $sql = "select * from instancia "
                . "join ge_escolas on ge_escolas.fk_id_inst = instancia.id_inst "
                . "where $field like '$search'";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($array['fk_id_tp_ens'])) {
            $sql = "select id_tp_ens, n_tp_ens from ge_tp_ensino where id_tp_ens in (" . str_replace('|', ',', substr($segmentos = $array['fk_id_tp_ens'], 1, -1)) . ")";
            $query = pdoSis::getInstance()->query($sql);
            $seg = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($seg as $v) {
                $array['segmentos'][$v['id_tp_ens']] = $v['n_tp_ens'];
            }
        }
        return $array;
    }

    public static function gets($search = NULL, $field = 'id_escola', $fields = '*') {
        if (!empty($search)) {
            $search = "and $field like '$search' ";
        }
        $sql = "select $fields from instancia "
                . "join ge_escolas on ge_escolas.fk_id_inst = instancia.id_inst "
                . "where instancia.ativo = 1"
                . " $search "
                . " order by n_inst";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchALL(PDO::FETCH_ASSOC);

        asort($array);

        return $array;
    }

    public static function idInst($search = NULL, $field = NULL, $se = NULL) {
        $esc = escolas::gets($search, $field, 'id_inst, n_inst');

        foreach ($esc as $v) {
            $s[$v['id_inst']] = $v['n_inst'];
        }
        if (!empty($se)) {
            $s[13] = 'Secretaria de Educação';
        }
        asort($s);
        return $s;
    }

    public static function liste($nome = NULL) {

        $sql = "select * from instancia "
                . "join ge_escolas on ge_escolas.fk_id_inst = instancia.id_inst "
                . "where n_inst like '%$nome%' "
                . " order by n_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function getAbrangencia($field = NULL, $search = NULL, $order = NULL) {

        if (empty($order)) {
            $order = "logradouro";
        }

        if (!empty($search) && !empty($field)) {
            if ($field == 'id_abrange') {
                $field = "WHERE `$field` = '$search' ";
            } else {
                $field = "WHERE `$field` LIKE '%" . str_replace("'", "", $search) . "%' ";
            }


            $sql = "SELECT "
                    . "a.id_abrange, a.cep, a.logradouro, a.bairro, i.id_inst, i.n_inst, a.status, a.cidade, uf  "
                    . "FROM ge_abrangencia a "
                    . "join instancia i on i.id_inst = a.fk_id_inst "
                    . "$field "
                    . "order by $order";

            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);

            return $array;
        } else {
            return NULL;
        }
    }

    public static function cursoEscolas($id_curso) {
        $sql = "select id_inst, n_inst from instancia i "
                . "join ge_escolas e on i.id_inst = e.fk_id_inst "
                . " where fk_id_tp_ens like '%|$id_curso|%'";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $esc[$v['id_inst']] = $v['n_inst'];
        }

        return $esc;
    }

    public static function idEscolas($cursos = null) {
        $ids = null;
        if ($cursos) {
            $sql = "SELECT * FROM `sed_inst_curso` WHERE `fk_id_curso` IN (" . implode(', ', $cursos) . ") ";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($array) {
                $ids = implode(', ', array_column($array, 'fk_id_inst'));
                $ids = " and i.id_inst in ($ids)";
            }
        }
        $sql = "select id_inst, n_inst from instancia i "
                . " join ge_escolas e on e.fk_id_inst = i.id_inst $ids "
                . " order by n_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return tool::idName($array);
    }

    public static function idEscolasSupervisor($id_pessoa,$cursos=null) {
        $ids = '';
        if ($cursos) {
            $sql = "SELECT * FROM `sed_inst_curso` WHERE `fk_id_curso` IN (" . implode(', ', $cursos) . ") ";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($array) {
                $ids = implode(', ', array_column($array, 'fk_id_inst'));
                $ids = " and i.id_inst in ($ids)";
            }
        }
        $sql = "SELECT id_inst, n_inst from instancia i "
                . " JOIN ge_escolas ge ON ge.fk_id_inst = i.id_inst $ids"
                . " JOIN vis_setor_instancia vsi ON vsi.fk_id_inst = i.id_inst AND vsi.at_setor_instancia = 1 "
                . " JOIN vis_setor vs ON vs.id_setor = fk_id_setor AND vs.at_setor = 1"
                . " WHERE vs.fk_id_pessoa = $id_pessoa "
                . " ORDER BY i.n_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return tool::idName($array);
    }

}
