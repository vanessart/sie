<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of grupo
 *
 * @author marco
 */
class grupo {

    public static function relat($nome = NULL) {
        if (!empty($nome)) {
            $nome = "where n_gr like '%$nome%'";
        }
        $sql = "SELECT * FROM grupo $nome "
                . "ORDER BY n_gr";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $sqlkey = DB::sqlKey('grupo', 'delete');
        foreach ($array as $k => $v) {
            $array[$k]['del'] = formOld::submit('Apagar', $sqlkey, ['1[id_gr]' => $v['id_gr']]);
            $array[$k]['edit'] = formOld::submit('Editar', NULL, $v);
            $array[$k]['ativo'] = tool::simnao($v['at_gr']);
        }
        $form['array'] = $array;
        $form['fields'] = [
            'Nome' => 'n_gr',
            'ID' => 'id_gr',
            'Ativo' => 'ativo',
            '||1' => 'del',
            '||2' => 'edit'
        ];

        tool::relatSimples($form);
    }

    public static function get($search = null, $field = 'id_gr', $fields = '*') {
        if (!empty($search)) {
            $sql = "select * from grupo where $field = '$search'";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetch(PDO::FETCH_ASSOC);
            if ($array) {
                return $array;
            } else {
                return FALSE;
            }
        }
    }

}
