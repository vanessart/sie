<?php

class citModel extends MainModel {

    public $db;

    /**
      if($this->db->tokenCheck('table')){

      }
     * Construtor para essa classe
     *
     * Configura o DB, o controlador, os parâmetros e dados do usuário.
     *
     * @since 0.1
     * @access public
     * @param object $db Objeto da nossa conexão PDO
     * @param object $controller Objeto do controlador
     */
    public function __construct($db = false, $controller = null) {
        // Configura o DB (PDO)
        $this->db = new crud();
        // Configura o controlador
        $this->controller = $controller;

        // Configura os parâmetros
        $this->parametros = $this->controller->parametros->variavel;

        // Configura os dados do usuário
        $this->userdata = $this->controller->userdata;

        //seta o select dinamico
        if ($opt = formErp::jqOption()) {
            $metodo = $opt[0];
            if (in_array($metodo, get_class_methods($this))) {
                $this->$metodo($opt[1]);
            }
        }
        if ($this->db->tokenCheck('emailsDesativados')) {
            $this->emailsDesativados();
        } elseif ($this->db->tokenCheck('emailRecicla')) {
            $this->emailRecicla();
        }
    }

    public function emailRecicla() {
        $data = filter_input(INPUT_POST, 'data');
        $apagado = filter_input(INPUT_POST, 'apagado');
        $sql = "UPDATE `ge_emails_desativados` SET `apagado` = '$apagado' WHERE data = '$data' ";
        $query = pdoSis::getInstance()->query($sql);
    }

    public function lotacao() {
        $sql = " SELECT c.*, i.n_inst, i.id_inst FROM func_integra c "
                . " left join instancia i on i.id_inst = c.fk_id_inst "
                . " ORDER BY i.id_inst ASC ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function emailsDesativados() {
        $data = date("Y-m-d");
        ini_set('memory_limit', '2000M');
        $sql = "SELECT "
                . " ta.fk_id_pessoa, p.n_pessoa, p.emailgoogle "
                . " FROM ge_turma_aluno ta "
                . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                . " AND p.emailgoogle like '%@aluno.barueri.br' "
                . " AND ta.fk_id_pessoa not in ( "
                . " SELECT ta.fk_id_pessoa FROM ge_turma_aluno ta "
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " AND fk_id_tas = 0 "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " AND pl.at_pl in (1,2) "
                . ")";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $v) {
                $ins['id_pessoa_ed'] = $v['fk_id_pessoa'];
                $ins['email'] = $v['emailgoogle'];
                $ins['data'] = $data;
                $id = $this->db->ireplace('ge_emails_desativados', $ins, 1);
                echo '<br />' . $id;
                if ($id) {
                    $sql = "UPDATE `pessoa` SET `emailgoogle` = null WHERE `pessoa`.`id_pessoa` = " . $v['fk_id_pessoa'];
                    $query = pdoSis::getInstance()->query($sql);
                }
            }
        }
    }

    public function gerarEmail($cursos) {

         $sql = "SELECT p.id_pessoa, p.n_pessoa, p.emailgoogle, p.ra, p.dt_nasc FROM ge_turma_aluno ta "
        . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma AND ta.fk_id_tas = 0 "
        . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo AND ci.fk_id_curso in (" . implode(', ', $cursos) . ") "
        . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
        . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa AND (p.emailgoogle REGEXP '^[0-9]+$' OR p.emailgoogle = '' OR p.emailgoogle is null ) ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!$array) {
            ?>
            <div class="alert alert-warning">
                Não Há novos Alunos
            </div>
            <?php
            return;
        }
        $nomeArquivo = date("Y-m-d");
        foreach ($array as $v) {
            $nasc = substr($v['dt_nasc'], -2) . substr($v['dt_nasc'], 5, 2);
            $id_pessoa = $v['id_pessoa'];
            $n_pessoa = $v['n_pessoa'];
            $n_pessoa_ = toolErp::removeAcentos($v['n_pessoa']);
            $n_pessoa_ = str_replace(['ç', 'Ç'], ['c', 'C'], $n_pessoa_);
            $ra = $v['ra'];
            $n = explode(' ', $n_pessoa_);
            $primeiroNome = $n[0];
            if (count($n) > 2) {
                $posNome = $n[1];
            } else {
                $posNome = null;
            }
            $ultimoNome = end($n);
            unset($n[0]);
            $sobrenome = implode(' ', $n);
            $passou = 0;
            $ct = 0;
            while (empty($passou)) {
                $passou = 1;
                if ($ct > 10) {
                    echo '<br />Erro no Aluno ' . $n_pessoa . ' RSE: ' . $id_pessoa;
                    continue;
                }
                if ($ct == 0) {
                    echo '<br />' . $emailgoogle = (strtolower($primeiroNome . $ultimoNome)) . '.' . $nasc . '@aluno.barueri.br';
                } elseif ($ct == 1) {
                    echo '<br />' . $emailgoogle = (strtolower($primeiroNome . $posNome)) . '.' . $nasc . '@aluno.barueri.br';
                } elseif ($ct == 2) {
                    echo '<br />' . $emailgoogle = (strtolower($primeiroNome . str_replace(' ', '', $sobrenome))) . '.' . $nasc . '@aluno.barueri.br';
                } else {
                    echo '<br />' . $emailgoogle = (strtolower($primeiroNome . $ultimoNome)) . '.' . $nasc . '_' . ($ct - 2) . '@aluno.barueri.br';
                }
                $sql = "UPDATE `pessoa` SET `emailgoogle` = '$emailgoogle' WHERE `pessoa`.`id_pessoa` = $id_pessoa";
                try {
                    $query = pdoSis::getInstance()->query($sql);
                    if ($query) {
                        echo ' => ok';
                    }
                } catch (Exception $exc) {
                    echo ' => Falhou.';
                    $passou = 0;
                }

                $ct++;
            }
            $sql = "INSERT INTO ge_controle_email "
                    . " (fk_id_pessoa, nome_aluno, nome_arquivo, status, campo1, campo2, campo3, campo4, campo6, campo17) VALUES "
                    . " ('$id_pessoa', '$n_pessoa', '$nomeArquivo', 'Pendente', '$primeiroNome', '$sobrenome', '$emailgoogle', 'google123', 'Alunos', '$ra')";
            try {
                $query = pdoSis::getInstance()->query($sql);
            } catch (Exception $exc) {
                echo '<br />Erro no Aluno ' . $n_pessoa . ' RSE: ' . $id_pessoa;
            }
        }
        toolErp::alert(count($array) . ' E-mails Novos');
    }

}

#5827822