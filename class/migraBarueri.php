<?php

//require '../class/siem.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of migraBarueri
 *
 * @author mc
 */
class migraBarueri {

    public $_db;

    public function __construct() {
        
    }

    public static function funcionarios() {
        $_db = new DB;
        //instancias terceirizadas
        $sql = "SELECT * FROM `instancia` WHERE `fk_id_tp` = 5 or terceirizada = 1 ";
        $query = $_db->query($sql);
        $terc = $query->fetchAll();
        foreach ($terc as $v) {
            @$terceirizados[] = $v['id_inst'];
        }

        if (empty($terceirizados)) {
            $sql = "DELETE FROM `ge_funcionario` WHERE  1 ";
        } else {
            $sql = "DELETE FROM `ge_funcionario` WHERE  fk_id_inst  NOT IN (" . implode(',', $terceirizados) . ") or fk_id_inst IS NULL";
        }
        $query = $_db->query($sql);


        $fields = " FUNCIONARIO, NASCIMENTO, CPF, SEXO, MATRICULA, SIT_ATUAL, fk_id_inst, FUNCAO ";
        $sql = "SELECT DISTINCT $fields FROM `funcionarios` "
                . "left join fuc_escola on fuc_escola.SUB_SECAO_TRABALHO  like funcionarios.SUB_SECAO_TRABALHO "
                . "left join  ge_escolas on ge_escolas.cie_escola = fuc_escola.cie "
                . " where  `CPF` IS NOT NULL ";
        
        $query = $_db->query($sql);
        $func = $query->fetchAll();
        foreach ($func as $v) {
            if (strlen($v['NASCIMENTO']) == 8) {
                $dia = substr($v['NASCIMENTO'], 0, 2);
            } else {
                $dia = '0' . substr($v['NASCIMENTO'], 0, 1);
            }
            $dt = substr($v['NASCIMENTO'], -4) . '-' . substr($v['NASCIMENTO'], -6, 2) . '-' . $dia;



            $sql = "SELECT id_pessoa, n_social FROM `pessoa` WHERE cpf = " . str_pad($v['CPF'], 11, "0", STR_PAD_LEFT);
            $query = $_db->query($sql);
            $existe = $query->fetch();

            if (empty($existe['n_social'])) {
                $social = explode(' ', $v['FUNCIONARIO'])[0];
            } else {
                $social = $existe['n_social'];
            }
            if (@$existe['id_pessoa']) {
                $pessoa = [
                    'id_pessoa' => $existe['id_pessoa'],
                    'n_pessoa' => $v['FUNCIONARIO'],
                    'n_social' => $social,
                    'dt_nasc' => $dt,
                    'ativo' => 1,
                    'cpf' => str_pad($v['CPF'], 11, "0", STR_PAD_LEFT),
                    'sexo' => $v['SEXO']
                ];
            } else {
                $pessoa = [
                    'id_pessoa' => NULL,
                    'n_pessoa' => $v['FUNCIONARIO'],
                    'n_social' => $social,
                    'dt_nasc' => $dt,
                    'ativo' => 1,
                    'cpf' => str_pad($v['CPF'], 11, "0", STR_PAD_LEFT),
                    'sexo' => $v['SEXO']
                ];
            }
            $id = $_db->ireplace('pessoa', $pessoa, 1);
          
            $rm = [
                'id_func' => NULL,
                'fk_id_pessoa' => $id,
                'rm' => intval($v['MATRICULA']),
                'funcao' => $v['FUNCAO'],
                'situacao' => $v['SIT_ATUAL'],
                'fk_id_inst' => $v['fk_id_inst']
            ];


            $_db->ireplace('ge_funcionario', $rm, 1);
        }
                $fields = "  MATRICULA, SIT_ATUAL, SUB_SECAO_TRABALHO ";
        $sql = "SELECT $fields FROM `funcionarios` "
                . " where  SUB_SECAO_TRABALHO like '%cap-c%'  or SUB_SECAO_TRABALHO like '%ECRETARIA DE EDUCACAO%'  or SUB_SECAO_TRABALHO like  '%UPERVISAO E PROJETOS NAS ESCOLAS%'";
        $query = $_db->query($sql);
        $func = $query->fetchAll();
        foreach ($func as $v){
            $rm = [
                'rm' => intval($v['MATRICULA']),
                'fk_id_inst'=>13
            ];
            $sql="update ge_funcionario set fk_id_inst = 13 "
                    . "where rm = ".intval($v['MATRICULA']);
            $query = $_db->query($sql);
        }
         
    }

    /**
      public static function funcionarios() {
      $_db = new DB;
      $fields = " FUNCIONARIO, NASCIMENTO, CPF, SEXO, MATRICULA, SIT_ATUAL, fk_id_inst, FUNCAO ";
      $sql = "SELECT $fields FROM `funcionarios` "
      . "left join fuc_escola on fuc_escola.SUB_SECAO_TRABALHO  like funcionarios.SUB_SECAO_TRABALHO "
      . "left join  ge_escolas on ge_escolas.cie_escola = fuc_escola.cie "
      . " where  `CPF` IS NOT NULL ";
      $query = $_db->query($sql);
      $func = $query->fetchAll();
      foreach ($func as $v) {
      if (strlen($v['NASCIMENTO']) == 8) {
      $dia = substr($v['NASCIMENTO'], 0, 2);
      } else {
      $dia = '0' . substr($v['NASCIMENTO'], 0, 1);
      }
      $dt = substr($v['NASCIMENTO'], -4) . '-' . substr($v['NASCIMENTO'], -6, 2) . '-' . $dia;



      $sql = "SELECT id_pessoa, n_social FROM `pessoa` WHERE cpf = " . str_pad($v['CPF'], 11, "0", STR_PAD_LEFT);
      $query = $_db->query($sql);
      $existe = $query->fetch();

      if (empty($existe['n_social'])) {
      $social = explode(' ', $v['FUNCIONARIO'])[0];
      } else {
      $social = $existe['n_social'];
      }
      if (@$existe['id_pessoa']) {
      $pessoa = [
      'id_pessoa' => $existe['id_pessoa'],
      'n_pessoa' => $v['FUNCIONARIO'],
      'n_social' => $social,
      'dt_nasc' => $dt,
      'ativo' => 1,
      'cpf' => $v['CPF'],
      'sexo' => $v['SEXO']
      ];
      } else {
      $pessoa = [
      'id_pessoa' => NULL,
      'n_pessoa' => $v['FUNCIONARIO'],
      'n_social' => $social,
      'dt_nasc' => $dt,
      'ativo' => 1,
      'cpf' => $v['CPF'],
      'sexo' => $v['SEXO']
      ];
      }
      $id = $_db->ireplace('pessoa', $pessoa, 1);

      $sql = "SELECT id_func, rm FROM `ge_funcionario` where rm = " . intval($v['MATRICULA']);
      $query = $_db->query($sql);
      $existRm = $query->fetch();

      if (empty(@$existRm['id_func'])) {
      $rm = [
      'id_func' => NULL,
      'fk_id_pessoa' => $id,
      'rm' => intval($v['MATRICULA']),
      'funcao' => $v['FUNCAO'],
      'situacao' => $v['SIT_ATUAL'],
      'fk_id_inst' => $v['fk_id_inst']
      ];
      } else {
      $rm = [
      'id_func' => $existRm['id_func'],
      'fk_id_pessoa' => $id,
      'rm' => intval($v['MATRICULA']),
      'funcao' => $v['FUNCAO'],
      'situacao' => $v['SIT_ATUAL'],
      'fk_id_inst' => $v['fk_id_inst']
      ];
      }

      $_db->ireplace('ge_funcionario', $rm, 1);

      }
      }
     * 
     */
}
