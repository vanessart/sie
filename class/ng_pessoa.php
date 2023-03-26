<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ng_pessoa
 *
 * @author marco
 */
class ng_pessoa {

    public $id_pessoa;
    public $dadosPessoais;
    public $endereco;

    public function __construct($id_pessoa) {
        $this->id_pessoa = $id_pessoa;
        $fields = "`id_pessoa`, `n_pessoa`, `n_social`, `dt_nasc`, `email`, `ativo`, `cpf`, `sexo`, `ra`, `ra_dig`, `ra_uf`, `rg`, `rg_dig`, `rg_oe`, `rg_uf`, `dt_rg`, `fk_id_rt`, `certidao`, `sus`, `nacionalidade`, `uf_nasc`, `cidade_nasc`, `cor_pele`, `obs`, `novacert_cartorio`, `novacert_acervo`, `novacert_regcivil`, `novacert_ano`, `novacert_tipolivro`, `novacert_numlivro`, `novacert_folha`, `novacert_termo`, `novacert_controle`, `dt_gdae`, `nis`, `emailgoogle`, `inep`, pai, mae, deficiencia FROM `pessoa`";
        $sql = "SELECT $fields WHERE id_pessoa = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $this->dadosPessoais = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function endereco() {
        $sql = "SELECT * FROM `endereco` WHERE `fk_id_pessoa` = " . $this->id_pessoa;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        $this->endereco = $array;

        return $array;
    }

    public static function telefone($id_pessoa) {
        $sql = "SELECT tt.n_tt, t.* FROM telefones t "
                . " LEFT JOIN telefones_tipo tt on tt.id_tt = t.fk_id_tt "
                . " WHERE t.fk_id_pessoa = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function setTelefones($id_pessoa, $telefones=[])
    {
        foreach ($telefones as $k => $v) {
            if (!empty($v)) {
                $sql = "SELECT * FROM `telefones` WHERE `fk_id_pessoa` = " . $id_pessoa . " AND `num` = '".$v['num']."' and fk_id_tp = ".$v['fk_id_tp'];
                $query = pdoSis::getInstance()->query($sql);
                $array = $query->fetch(PDO::FETCH_ASSOC);
                if (empty($array)) {
                    $sql = "REPLACE INTO `telefones` (`fk_id_pessoa`, `fk_id_tt`, `fk_id_tp`, `ddd`, `num`, `tipo`, `complemento`) VALUES ("
                            . @$id_pessoa . ", "
                            . @$v['fk_id_tt'] . ", "
                            . @$v['fk_id_tp'] .", "
                            . "'". @$v['ddd'] ."', "
                            . "'". @$v['num'] ."', "
                            . "'". @$v['tipo'] ."', "
                            . "'". @$v['complemento'] ."' "
                            . ");";
                    $query = pdoSis::getInstance()->query($sql);
                } else {
                    $sql = "UPDATE `telefones` SET `ddd` = '".@$v['ddd']."', `num` = '".@$v['num']."', `tipo` = '".@$v['tipo']. "', `complemento` = '".@$v['complemento']. "', `fk_id_tp` = '".@$v['fk_id_tp']. "', `fk_id_tt` = '".@$v['fk_id_tt']. "' WHERE `id_tel` = " . $array['id_tel'];
                    $query = pdoSis::getInstance()->query($sql);
                }
            }
        }
    }
}
