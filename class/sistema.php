<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sistema
 *
 * @author marco
 */
class sistema {

    public static function relat($nome = NULL) {
        if (!empty($nome)) {
            $nome = "where n_sistema like '%$nome%'";
        }
        $sql = "SELECT *, sistema.ativo ativo FROM sistema $nome "
                . "LEFT JOIN framework on sistema.fk_id_fr = framework.id_fr "
                . "ORDER BY n_sistema";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $sqlkey = DB::sqlKey('sistema', 'delete');
        foreach ($array as $k => $v) {
            $array[$k]['del'] = $v['protegido'] != 1 ? formOld::submit('Apagar', $sqlkey, ['1[id_sistema]' => $v['id_sistema']]) : '';
            $array[$k]['edit'] = $v['protegido'] != 1 ? formOld::submit('Editar', NULL, $v) : '';
            $array[$k]['ativo'] = tool::simnao($v['ativo']);
        }
        $form['array'] = $array;
        $form['fields'] = [
            'ID' => 'id_sistema',
            'Subsistema' => 'n_sistema',
            'Arquino' => 'arquivo',
            'Framework' => 'n_fr',
            'Ativo' => 'ativo',
            '||1' => 'del',
            '||2' => 'edit'
        ];

        tool::relatSimples($form);
    }

    public static function get($search = null, $field = 'id_sistema', $fields = '*') {
        if (!empty($search)) {
            $sql = "select *, sistema.ativo ativo from sistema "
                    . "LEFT JOIN framework on sistema.fk_id_fr = framework.id_fr "
                    . "where $field = '$search'";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetch(PDO::FETCH_ASSOC);
            if ($array) {
                return $array;
            } else {
                return FALSE;
            }
        }
    }
/**
 * mostra a saudação na página inicial do sistema
 * @param type $id_sistema
 * @param type $id_nivel
 * @return type
 */
    public static function msg($id_sistema = NULL, $id_nivel = NULL) {
        if (empty($id_sistema) && empty($id_nivel)) {
            $id_sistema = $_SESSION['userdata']['id_sistema'];
            $id_nivel = $_SESSION['userdata']['id_nivel'];
            $usuario = 1;
        }
        $sql = "SELECT msg FROM `saudacao` WHERE "
        . "`id_sistema` = " . $id_sistema
        . " and `fk_id_nivel` = " . $id_nivel;
        $query = autenticador::getInstance()->query($sql);
        if($query){
        $array = $query->fetch(PDO::FETCH_ASSOC)['msg'];
        if (!empty($usuario)) {
            $nome = explode(' ', user::session('nome'));
            $nome[0] = ucwords(strtolower($nome[0]));
            $array = str_replace('bomdia', tool::bom(), $array);
            $array = str_replace('nomepessoa', $nome[0], $array);
        }

        return $array;
        }  else {
            return NULL;    
        }
    }
/**
 * reotrna os niveis de acesso permitido para o referido subsistema
 * @param type $id_sistema
 * @return type
 */
    public static function niveis($id_sistema) {
        $n = sql::get('sistema', 'niveis', ['id_sistema' => $id_sistema], 'fetch');
        if (!empty($n['niveis'])) {
            $n_ = unserialize($n['niveis']);
            $n__ = implode(',', $n_);
            $ni = sql::get('nivel', 'id_nivel, n_nivel', "where id_nivel in ($n__)");
            foreach ($ni as $v) {
                $niveis[$v['id_nivel']] = $v['n_nivel'];
            }

            return @$niveis;
        } else {
            return NULL;
        }
    }

}
