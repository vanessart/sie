<?php

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__).'/error_log.txt');
error_reporting(E_ALL);

class geralModel extends MainModel
{
    public $db;
    public $_acesso_resp;
    public $_nomesAluno;

    /**
     * Construtor para essa classe.
     *
     * Configura o DB, o controlador, os parâmetros e dados do usuário.
     *
     * @since 0.1
     *
     * @param object $db         Objeto da nossa conexão PDO
     * @param object $controller Objeto do controlador
     */
    public function __construct($db = false, $controller = null)
    {
        // Configura o DB (PDO)
        $this->db = new DB(AUT_HOSTNAME, AUT_DB_NAME, AUT_DB_PASSWORD, AUT_DB_USER);

        // Configura o controlador
        $this->controller = $controller;

        // Configura os parâmetros
        //$this->parametros = $this->controller->parametros;
        // Configura os dados do usuário
        $this->userdata = $this->controller->userdata;

        if ($this->db->sqlKeyVerif('PessoaEdit')) {
            $_POST[1]['id_pessoa'] = user::session('id_pessoa');
            pessoa::replace($_POST[1], 1);
        }
        if ($this->db->sqlKeyVerif('saudacao')) {
            $this->db->replace('saudacao', $_POST[1]);
        }

        if ($this->db->sqlKeyVerif('cpf')) {
            if (!empty($_POST['cpf'])) {
                //valida cpf
                $valida_cpf = validateCpf($_POST['cpf']);
                
                if (false === $valida_cpf) {
                    tool::alert('CPF inválido!');
                } else {
                    $cpf = clearCpf($_POST['cpf']);
                    $this->certificaPai($cpf);
                }
            } else {
                tool::alert('Digite o CPF');
            }
        }
        if ($this->db->sqlKeyVerif('resp')) {
            $this->confirmaUser();
        }
    }

    public function dadosUser()
    {
        $query = $this->db->query("select * from pessoa where id_pessoa = '".$_SESSION['userdata']['id_pessoa']."' ");

        return $query->fetch();
    }

    public function certificaPai($cpf)
    {
        $sql = 'SELECT id_pessoa, n_pessoa, responsavel, cpf_respons, dt_nasc, sexo FROM ge_turma_aluno ta '
                .' join pessoa p on p.id_pessoa = ta.fk_id_pessoa '
                ." WHERE p.cpf_respons = {$cpf} ";
        $query = $this->db->query($sql);
        $a = $query->fetchAll();
        if (!empty($a)) {
            foreach ($a as $v) {
                $n = explode(' ', $v['n_pessoa'])[0];
                $nomes[] = $n;
                $alunos['responsavel'] = $v['responsavel'];
                $alunos['cpf'] = $v['cpf_respons'];
                $alunos['aluno'][$v['id_pessoa']]['nome'] = $v['n_pessoa'];
                $alunos['aluno'][$v['id_pessoa']]['rse'] = $v['id_pessoa'];
                $alunos['aluno'][$v['id_pessoa']]['nasc'] = $v['dt_nasc'];
            }
            $this->_nomesAluno = $nomes;
            $this->_acesso_resp = $alunos;
        } else {
            tool::alert('Não há aluno vinculado a este CPF. Por favor procure a secretaria da escola.');
        }
    }

    public function confirmaUser()
    {
        $sql = 'SELECT id_pessoa, n_pessoa, responsavel, cpf_respons, dt_nasc, sexo FROM ge_turma_aluno ta '
                .' join pessoa p on p.id_pessoa = ta.fk_id_pessoa '
                .' WHERE p.cpf_respons = '.$_POST['cpf']
                .' and id_pessoa = '.$_POST['rse']
                ." and n_pessoa like '".$_POST['nome']."%' "
                ."and dt_nasc = '".data::converteUS($_POST['data'])."'";
        //pre($sql);
        $query = $this->db->query($sql);
        $a = $query->fetch();
        
        if (!empty($a)) {
            @$id_pessoaResp = sql::get('pessoa', 'id_pessoa', ['cpf' => $a['cpf_respons']], 'fetch')['id_pessoa'];
            //var_dump($id_pessoaResp);
            $post = $_POST[1];
            $post['n_pessoa'] = $a['responsavel'];
            $post['cpf'] = $a['cpf_respons'];
            //pre($post);

            foreach ($post as $k => $v) {
                if (!empty($post[$k])) {
                    $post_[$k] = $post[$k];
                }
            }

            if (empty($id_pessoaResp)) {
                $post_['id_pessoa'] = null;
            } else {
                $post_['id_pessoa'] = $id_pessoaResp;
            }
            if (!empty($post_['dt_nasc'])) {
                $post_['dt_nasc'] = data::converteUS($post_['dt_nasc']);
            }
            $post_['ativo'] = 1;
            //pre($post_);
            //$id_pessoaResp = $this->db->ireplace('pessoa', $post_);
            $this->db->ireplace('pessoa', $post_,1);
            //var_dump($id_pessoaResp);
            if(empty($id_pessoaResp)){
                $id_pessoaResp = sql::get('pessoa', 'id_pessoa', ['cpf' => $a['cpf_respons']], 'fetch')['id_pessoa'];
            }
            @$id_user = sql::get('users', 'id_user', ['fk_id_pessoa' => $id_pessoaResp], 'fetch')['id_user'];
            //var_dump($id_user);
            if (!empty($id_user)) {
                $users['id_user'] = $id_user;
            } else {
                $users['id_user'] = 'X';
                $users['fk_id_pessoa'] = $id_pessoaResp;
            }
            $users['ativo'] = '1';
            //pre($users);
            //so reseta senha de quem nao tem outros sistemas
            $sql = "SELECT fk_id_gr FROM `acesso_pessoa` WHERE `fk_id_pessoa` = '{$id_pessoaResp}' AND `fk_id_gr` != 25 ";
            $query = pdoSis::getInstance()->query($sql);
            @$outroGrupo = $query->fetch(PDO::FETCH_ASSOC)['fk_id_gr'];
            if (empty($outroGrupo)) {
                $senha = user::gerarSenha();
                $users['user_password'] = $senha;
                $_POST['senhaPais'] = $senha;
                $this->db->ireplace('users', $users, 1);
            } else {
                $_POST['senhaPais'] = 1;
            }
            $sql = "select id_ac from acesso_pessoa WHERE `fk_id_pessoa` = '{$id_pessoaResp}' AND `fk_id_gr` = 25 ";
            //pre($sql);
            $query = $this->db->query($sql);
            $array = $query->fetch();
            if (empty($array)) {
                $acesso['fk_id_pessoa'] = $id_pessoaResp;
                $acesso['fk_id_gr'] = 25;
                $acesso['fk_id_inst'] = 13;
                $this->db->ireplace('acesso_pessoa', $acesso, 1);
            }
        } else {
            @$_SESSION['tmp']['tentativa']++;
            tool::alert('Dados Incorretos. '.@$_SESSION['tmp']['tentativa'].'ª tentativa');
        }
    }
}
