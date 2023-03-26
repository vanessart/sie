<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of log
 *
 * @author marco
 */
class log {

    public static function logSet($descricacao) {
        @$descricacao = addslashes($descricacao);
        @$id_pessoa = $_SESSION['userdata']['id_pessoa'];
        @$id_sistema = $_SESSION['userdata']['id_sistema'];
        @$id_inst = $_SESSION['userdata']['id_inst'];
        if (!empty($id_pessoa)) {

            $log['n_pessoa'] = user::session('n_pessoa');
            $log['cpf'] = user::session('cpf');
            $log['n_sistema'] = @$_SESSION['userdata']['n_sistema'];
            $log['n_nivel'] = @$_SESSION['userdata']['n_nivel'];
            $log['n_inst'] = @$_SESSION['userdata']['n_inst'];



            $log['id_pessoa'] = @$id_pessoa;
            $log['fk_id_sistema'] = @$id_sistema;
            $log['fk_id_inst'] = @$id_inst;
            $log['descricacao'] = @$descricacao;
            $log['data'] = date("Y-m-d H:i:s");
            $mongo = new mongoCrude();
            $mongo->insert('log', $log);
            /**
              $sql = "INSERT INTO `log` (`id_log`, `fk_id_pessoa`, `fk_id_sistema`, `fk_id_inst`, `descricacao`, `data`) VALUES ("
              . "NULL, '$id_pessoa', '" . intval($id_sistema) . "', '" . intval($id_inst) . "', '$descricacao', '" . date("Y-m-d H:i:s") . "'"
              . ")";
              $query = autenticador::getInstance()->query($sql);
             * 
             */
        }
    }

    public static function logGet($n_pessoa = NULL, $id_sistema = NULL, $id_inst = NULL, $data = NULL, $limit = 50) {
        if (!empty($n_pessoa)) {
            if (is_numeric($n_pessoa)) {
                $log['id_pessoa'] = $n_pessoa;
            } else {
                $log['n_pessoa'] = '/' . $n_pessoa . '/i';
            }
        }
        if (!empty($id_sistema)) {
            $log['fk_id_sistema'] = $id_sistema;
        }
        if (!empty($id_inst)) {
            $log['fk_id_inst'] = $id_inst;
        }
        if (!empty($data)) {
            $log['data'] = '/' . $data . '/i';
        }
        
        $mongo = new mongoCrude();
        $array = $mongo->query('log', $log, ['limit' => $limit, 'sort' => ['data' => -1]]);

        $array = (array) $array;
        foreach ($array as $k => $v) {
            $v = (array) $v;
            unset($v['_id']);
            $ar[$k] = $v;
            $ar[$k]['hora'] = substr($v['data'], 11);
            $ar[$k]['data'] = data::converte(substr($v['data'], 0, 10));
        }
        return @$ar;
       
        return;
    }

    public static function excluidos($tabela, $campos, $n_campos = NULL) {
        $sql = "INSERT INTO `excluidos` (`id_exc`, `tabela`, `campos`, `n_campos`) VALUES (NULL, '$tabela', '$campos', '$n_campos');";
        $query = autenticador::getInstance()->query($sql);
    }

}
