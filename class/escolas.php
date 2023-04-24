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
class escolas {

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

        $s = [];
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

    public static function idEscolas() {
        $sql = "select id_inst, n_inst from instancia "
                . "join ge_escolas on ge_escolas.fk_id_inst = instancia.id_inst "
                . " order by n_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return tool::idName($array);
    }

    public static function n_inst_turma($id_turma) {
        $sql = "SELECT i.n_inst from instancia i "
                . " JOIN ge_turmas t ON t.fk_id_inst = i.id_inst "
                . " WHERE t.id_turma = $id_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($array)) {
            return $array['n_inst'];
        }
        
    }

    public static function n_inst($id_inst) {
        $sql = "SELECT i.n_inst from instancia i "
                . " WHERE i.id_inst = $id_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($array)) {
            return $array['n_inst'];
        }
        
    }

}
