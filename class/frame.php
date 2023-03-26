<?php

/* manipulação de dados referentes aos framework
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of frame
 *
 * @author marco
 */
class frame {
/**
 * cria um relatorio de frameworks
 * @param type $nome parte do nome do framework
 */
    public static function relat($nome = NULL) {
        if (!empty($nome)) {
            $nome = "where n_fr like '%$nome%'";
        }
        $sql = "SELECT * FROM framework $nome "
                . "ORDER BY n_fr";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $sqlkey = DB::sqlKey('framework', 'delete');
        foreach ($array as $k => $v) {
            $array[$k]['del'] = $v['id_fr']!=1?formOld::submit('Apagar', $sqlkey, ['1[id_fr]' => $v['id_fr']]):'';
            $array[$k]['edit'] = formOld::submit('Editar', NULL, $v);
            $array[$k]['ativo'] = tool::simnao($v['ativo']);
        }
        $form['array'] = $array;
        $form['fields'] = [
            'Nome' => 'n_fr',
            'ID' => 'id_fr',
            'Endereço.' => 'end_fr',
            'Ativo' => 'ativo',
            '||1' => 'del',
            '||2' => 'edit'
        ];

        tool::relatTable($form);
    }
/**
 * busca de um framework
 * @param type $search
 * @param type $field
 * @param type $fields
 * @return boolean
 */
    public static function get($search = null, $field = 'id_fr', $fields = '*') {
        if (!empty($search)) {
            $sql = "select * from framework where $field = '$search'";
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
