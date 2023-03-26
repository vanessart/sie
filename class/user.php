<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 * @author marco
 */
class user {

    public static function get($search = NULL) {
        $sql = "SELECT *, users.ativo as ativo FROM `pessoa` "
                . "LEFT JOIN users  on pessoa.id_pessoa = users.fk_id_pessoa "
                . "where cpf = '$search' "
                . "or email like '$search' "
                . "or id_pessoa like '$search'";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }
/**
 * gera uma senha aleatoria e válida
 * @return type
 */
    public static function gerarSenha() {

        $num = "123456789";
        $caracteres = "abcdefghijklmnpqrstuvwxyz";
        $m1 = substr(str_shuffle($num), 0, 4);
        $m2 = substr(str_shuffle($caracteres), 0, 4);
        $m3 = $m1 . $m2;
        $senha = substr(str_shuffle($m3), 0, 8);
        return $senha;
    }
/**
 * devolve os sistema que a pessoa tem acesso
 * @param type $id_pessoa
 * @return type
 */
    public static function permissao($id_pessoa) {
        $sql = "SELECT * FROM acesso_pessoa "
                . "join grupo on grupo.id_gr = acesso_pessoa.fk_id_gr "
                . "join instancia on instancia.id_inst = acesso_pessoa.fk_id_inst "
                . "where fk_id_pessoa = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }
/**
 * fora de uso
 * reotrna oas telefones da pessoa
 * @param type $fkid
 * @return type
 */
    public static function telefone($fkid = NULL) {
        if (empty($fkid)) {
            $fkid = $_SESSION['userdata']['id_pessoa'];
        }
        return $tel = sql::get('telefones', 'id_tel, num,tipo', ['fkid' => $fkid,'fk_id_tp'=>1]);
    }

    /**
     * reotrna os daods e parametros do usuário
     * @param type $param um paramentro específivo, se for 'nome' volta o nome customisado
     * @return type
     */
    public static function session($param = NULL) {

        if (empty($param)) {
            return $_SESSION['userdata'];
        } elseif ($param == 'nome') {
            if (empty($_SESSION['userdata']['n_social'])) {
                $nome = explode(' ', $_SESSION['userdata']['n_pessoa']);
                return ucfirst(strtolower($nome[0]));
            } else {
                return $_SESSION['userdata']['n_social'];
            }
        } else {
            return $_SESSION['userdata'][$param];
        }
    }

}
