<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of nivelupo
 *
 * @author marco
 */
class nivel {

    public static function relat($nome = NULL) {
        if (!empty($nome)) {
            $nome = "where n_nivel like '%$nome%'";
        }
        $sql = "SELECT * FROM nivel $nome "
                . "ORDER BY n_nivel";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $sqlkey = DB::sqlKey('nivel', 'delete');
        foreach ($array as $k => $v) {
            $array[$k]['del'] = formOld::submit('Apagar', $sqlkey, ['1[id_nivel]' => $v['id_nivel']]);
            $array[$k]['edit'] = formOld::submit('Editar', NULL, $v);
            $array[$k]['ativo'] = tool::simnao($v['ativo']);
        }
        $form['array'] = $array;
        $form['fields'] = [
            'Nome' => 'n_nivel',
            'ID' => 'id_nivel',
            'ID Aut.' => 'fk_id_nivel',
            'Ativo' => 'ativo',
            '||1' => 'del',
            '||2' => 'edit'
        ];

        tool::relatSimples($form);
    }

    public static function get($search = null, $field = 'id_nivel', $fields = '*') {
        if (!empty($search)) {
            $sql = "select * from nivel where $field = '$search'";
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
