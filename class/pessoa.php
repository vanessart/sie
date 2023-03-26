<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pessoa
 *
 * @author marco
 */
class pessoa {

    public static function get($search = null, $field = 'id_pessoa', $fields = '*') {
        if (!empty($search)) {
            $sql = "select * from pessoa where $field = '$search' "
                    . " and id_pessoa != 1 ";
            $query = autenticador::getInstance()->query($sql);
            $array = $query->fetch(PDO::FETCH_ASSOC);
            if ($array) {
                return $array;
            } else {
                return null;
            }
        }
    }

    /**
     * faz a consistencia antes de inserir ou alterar os dados de uma pessoa
     * @param type $post dados da pessoa
     * @param type $mini só faz a consistencia do email
     */
    public static function replace($post, $mini = NULL) {
        @$post['emailgoogle'] = trim(@$post['emailgoogle']);
        @$emailExiste = pessoa::get(@$post['emailgoogle'], 'emailgoogle', 'id_pessoa')['id_pessoa'];
        @$emailExiste = $post['id_pessoa'] == $emailExiste ? NULL : $emailExiste;
        $erro = 1;
        if (empty($mini)) {
            $cpfExiste = pessoa::get(@$post['cpf'], 'cpf', 'id_pessoa');
            if (!empty($cpfExiste['id_pessoa']) && !empty($post['id_pessoa'])) {
                @$cpfExiste = $post['id_pessoa'] == $cpfExiste['id_pessoa'] ? NULL : $cpfExiste['id_pessoa'];
            } else {
                @$cpfExiste = null;
            }
            
            if (empty($post['n_pessoa'])) {
                tool::erro('Preencha o Nome');
            } elseif (empty($post['cpf']) && empty($post['emailgoogle'])) {
                tool::erro('Preencha o CPF ou E-mail');
            } elseif ((validar::Cpf($post['cpf']) != 1)) {
                tool::erro("CPF Inválido");
            } elseif (!strripos($post['emailgoogle'], '@') && !empty($post['emailgoogle'])) {
                tool::erro("E-mail Inválido");
            } elseif ($cpfExiste && !empty($post['cpf'])) {
                tool::erro("CPF já existe");
            } elseif ($emailExiste && !empty($post['emailgoogle'])) {
                tool::erro("E-mail  já existe");
            } else {
                $erro = 0;
            }
        } else {
            if (!strripos($post['emailgoogle'], '@') && !empty($post['emailgoogle'])) {
                tool::erro("E-mail Inválido");
            } elseif ($emailExiste && !empty($post['emailgoogle'])) {
                tool::erro("E-mail já existe");
            } else {
                $erro = 0;
            }
        }

        if ($erro == 0) {
            if (!empty($post['dt_nasc'])) {
                $post['dt_nasc'] = data::converteUS($post['dt_nasc']);
            }
            $dbp = new DB(AUT_HOSTNAME, AUT_DB_NAME, AUT_DB_PASSWORD, AUT_DB_USER);

            if (empty($post['emailgoogle'])) {
                unset($post['emailgoogle']);
            }

            $sTels = [];
            if (!empty($post['fk_id_tt'])){
                for ($i=1; $i <= 3; $i++){
                    if (!empty($post['tel'.$i])) {
                        $sTels[] = [
                            'ddd' => $post['ddd'][$i],
                            'num' => $post['tel'.$i], 
                            'fk_id_tt' => $post['fk_id_tt'][$i], 
                            'complemento' => $post['tel_comp'][$i], 
                            'fk_id_tp' => 1, 
                            'tipo' => 'Fixo',
                        ];
                    }
                }

                unset($post['ddd']);
                unset($post['fk_id_tt']); 
                unset($post['tel_comp']);
            }

            $teste = $dbp->ireplace('pessoa', $post, ["O cadastro foi realizado com Sucesso!", "Houve um erro!"]);
            if (empty($post['id_pessoa'])) {
                $id_pessoa = $teste;
            } else {
                $id_pessoa = $post['id_pessoa'];
            }

            if (!empty($sTels)){
                ng_pessoa::setTelefones($id_pessoa, $sTels);
            }
        }

        return $id_pessoa;
    }

    /**
     * 
     * @param type $search nome, email ou cpf
     */
    public static function relat($search) {
        $where = " (n_pessoa LIKE '%$search%' OR cpf LIKE '$search' OR email LIKE '%$search%') ";
        $sql = "SELECT * FROM pessoa "
                . "where "
                . $where
                . " ORDER BY n_pessoa limit 0, 100";
        $query = autenticador::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        //   $sqlkey = DB::sqlKey('pessoa', 'delete');
        foreach ($array as $k => $v) {
            $v['novo'] = 1;
            //   $array[$k]['del'] = formOld::submit('Apagar', $sqlkey, ['1[id_pessoa]' => $v['id_pessoa']]);
            $array[$k]['edit'] = formOld::submit('Editar', NULL, $v);
            $array[$k]['acesso'] = formOld::submit('Acessar Usuário', NULL, ['user' => $v['id_pessoa']], HOME_URI . '/adm/user');
            $array[$k]['func'] = formOld::submit('Funcionário', NULL, ['cpf_' => $v['cpf'], 'pesq' => 1], HOME_URI . '/suporte/func/');
        }
        $form['array'] = $array;
        $form['fields'] = [
            'Nome' => 'n_pessoa',
            'CPF' => 'cpf',
            'E-mail' => 'emailgoogle',
            'Dt. Nasc.' => 'dt_nasc',
            'Nome Social' => 'n_social',
            '||1' => 'del',
            '||2' => 'edit',
            '||4' => 'func',
            '||3' => 'acesso'
        ];

        tool::relatSimples($form);
    }

    /**
     * cor de pele - null volta todas
     * @param type $cor
     * @return string
     */
    public static function corPela($cor = NULL) {

        $cores = [
            0 => '',
            1 => 'Branca',
            2 => 'Preta',
            3 => 'Amarela',
            4 => 'Parda',
            5 => 'Indigena',
            6 => 'Não informado'
        ];

        if (!empty($cor)) {
            return $cores[$cor];
        } else {
            return $cores;
        }
    }

    public static function civil() {
        $civil = ['Solteiro', 'Casado', 'Separado', 'Divorciado', 'Viúvo'];

        return $civil;
    }

    public static function buscar($search, $hidden, $button = 'Acessar') {
        $array = sql::get('pessoa', '*', "where id_pessoa = '$search' or n_pessoa like '%$search%' or cpf like '$search'");
        foreach ($array as $k => $v) {
            $v = array_merge($v, $hidden);
            $array[$k]['edit'] = formOld::submit($button, NULL, $v);
        }
        $form['array'] = $array;
        $form['fields'] = [
            'Nome' => 'n_pessoa',
            'CPF' => 'cpf',
            'E-mail' => 'emailgoogle',
            'Dt. Nasc.' => 'dt_nasc',
            'Nome Social' => 'n_social',
            '||2' => 'edit',
        ];

        tool::relatSimples($form);
    }

}
