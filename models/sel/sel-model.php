<?php

class selModel extends MainModel {

    public $db;
    public $_inscricao;
    public $_seletiva;
    public $_cpf_tmp;
    /**
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
        $this->db = new DB(NULL, 'ps');

// Configura o controlador
        $this->controller = $controller;

// Configura os parâmetros
//$this->parametros = $this->controller->parametros;
// Configura os dados do usuário
        $this->userdata = $this->controller->userdata;
        if (!empty($_REQUEST['limpasess'])) {
            unset($_SESSION['tmp']);
        }
        if (DB::sqlKeyVerif('parte1')) {
            $erro = 1;
            $post = $_POST[1];
            if (!empty($_POST['cargo'])) {
                @$post['cargo'] .= '|';
                foreach ($_POST['cargo'] as $v) {
                    $erro = 0;
                    @$post['cargo'] .= $v . '|';
                }
            }
            if ($erro == 1) {
                tool::alert('Selecione pelo menos um cargo.');
            } else {
                $erro = 0;
                $campos = [
                    'n_insc',
                    'rg',
                    'rg_oe',
                    'dt_nasc',
                    'filhos',
                    'logradouro',
                    'num',
                    'bairro',
                    'cidade',
                    'uf',
                    'confirma'
                ];
                foreach ($campos as $k => $v) {
                    if (empty($post[$v])) {
                        $erro = 1;
                    } else {
                        $campos[$k]= trim($v);
                    }
                }
                if ($erro == 1) {
                    tool::alert('Preencha todos os campos.');
                } else {
                    $post['cpf'] = $_SESSION['tmp']['cpf'];
                    $post['dt_inscr'] = date("Y-m-d");
                    $post['chave'] = substr(uniqid(), 0, 6);
                    $post['dt_nasc'] = data::converteUS($post['dt_nasc']);
                    $post['id_inscr'] = @$_SESSION['tmp']['id'];
                    $post['fk_id_sel'] = $_SESSION['tmp']['ps'];
                    $id = $_SESSION['tmp']['id'] = $this->db->ireplace('sel_inscricacao', $post);
                    if (!empty($post['email'])) {
                        $this->_seletiva = $this->seletiva($_SESSION['tmp']['ps']);
                        $texto = "Seu Código de Acesso ao " . $this->_seletiva['n_sel'] . " é <br /><br />006" . $post['chave'] . str_pad($id, 6, "0", STR_PAD_LEFT);
                        sendEmail::recuperaCodigo($post['n_insc'], $post['email'], $texto);
                        return 'sel';
                    }
                }
            }
        }
    }

    public function page() {
        if (!empty($_REQUEST['ps'])) {
            $ps= explode('-', $_REQUEST['ps']);
            
            unset($_SESSION['tmp']);
            $sel = $this->seletiva($ps[0], 1);
            if(!empty($ps[1])){
                $this->_cpf_tmp = $ps[1];
            }
            if (empty($sel['id_sel'])) {
                tool::alert("Inscrições fechadas ou Processo Seletivo inexistente.");
                return 'ini';
            } else {
                $this->_seletiva = $sel;
                $_SESSION['tmp']['ps'] = $_REQUEST['ps'];

                return 'sel';
            }
        } elseif (!empty(DB::sqlKeyVerif('valida'))) {
            if (empty($_REQUEST['cpf'])) {

                tool::alert("Por favor, digite o CPF ou Código de Acesso.");

                return 'sel';
            } else {
                
                $page = $this->valida($_REQUEST['cpf']);
                
                if ($page == 'view') {
                    $page = $this->_seletiva['fase'];
                }
                return $page;
            }
        } elseif (!empty(@$_SESSION['tmp']['id'] && !empty(@$_SESSION['tmp']['ps']))) {
            $sel = $this->_seletiva = $this->seletiva($_SESSION['tmp']['ps'], 1);
            if (empty($sel['id_sel'])) {
                ?>
                <div class="alert alert-danger" style="font-size: 20px; width: 80%;margin: 0 auto; text-align: center">
                    Erro ao acessar os Dados.
                </div>
                <?php
                return 'sel';
            } else {
                $this->_seletiva = $this->seletiva($_SESSION['tmp']['ps'], 1);
                $this->_inscricao = $this->inscr($_SESSION['tmp']['id']);
                return '0_1';
            }
        } elseif (empty(@$_SESSION['tmp']['id']) && !empty(@$_SESSION['tmp']['cpf'])) {
            $sel = $this->_seletiva = $this->seletiva($_SESSION['tmp']['ps'], 1);
            if (empty($sel['id_sel'])) {
                ?>
                <div class="alert alert-danger" style="font-size: 20px; width: 80%;margin: 0 auto; text-align: center">
                    Erro ao acessar os Dados.
                </div>
                <?php
                return 'sel';
            } else {
                $this->_seletiva = $this->seletiva($_SESSION['tmp']['ps'], 1);
                return '0_0';
            }
        } else {
            unset($_SESSION['tmp']);
            return 'ini';
        }
    }

    public function seletiva($id = NULL, $situacao = NULL) {
        if (!empty($situacao)) {
            $situacao = " and at_sel = '$situacao'";
        }
        if (!empty($id)) {
            $id = " and id_sel = '$id'";
        }
        $sql = "select * from sel_seletiva "
                . "where 1 = 1 "
                . " $situacao "
                . " $id "
                . " and at_sel = 1 "
                . "order by at_sel desc, dt_ini_inscr ";
        $query = $this->db->query($sql);
        if (empty($id)) {
            $sel = $query->fetchAll();
        } else {
            $sel = $query->fetch();
        }

        return $sel;
    }

    public function valida($cpf) {
        $cpf = str_replace(array('.', '-', ' '), '', $cpf);

        /**
          if (!$captcha_data) {
          tool::alert("Por favor, confirme o captcha.");
          return 'sel';
          } else {
          $resposta = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LdZvSkTAAAAAHF8Nalmnb_1Zz9i8hQex6e6ZBkF&response=" . $captcha_data . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
          if ($resposta . 'success') {
         * 
         */
        if (strlen($cpf) == 11) {
            $cpfValida = validar::Cpf($cpf);
            if ($cpfValida == 1) {
                tool::alert("CPF inválido.");
                return 'sel';
            } else {
                $_SESSION['tmp']['cpf'] = $cpf;

                $sel = $this->_seletiva = $this->seletiva($_SESSION['tmp']['ps'], 1);
                if (empty($sel['id_sel'])) {
                    ?>
                    <div class="alert alert-danger" style="font-size: 20px; width: 80%;margin: 0 auto; text-align: center">
                        Inscrições fechadas ou Processo Seletivo inexistente.
                    </div>
                    <?php
                    return 'ini';
                } else {
                    $inscr = $this->inscr($cpf, 'cpf');

                    if (empty($inscr['id_inscr'])) {
                        $this->_seletiva = $this->seletiva($_SESSION['tmp']['ps'], 1);
                        return '0_0';
                    } else {
                        ?>
                        <div class="alert alert-danger" style="font-size: 20px; width: 80%;margin: 0 auto; text-align: center">
                            Você já está cadastrado para o <?php echo $this->_seletiva['n_sel'] ?>.
                            <br /><br />
                            Utilize o Código de Acesso que consta em seu protocolo
                            <?php
                            if (!empty($inscr['email'])) {
                                ?>
                                <br /><br />
                                Mandamos uma mensagem para <?php echo substr($inscr['email'], 0, 3) . '___@____', substr($inscr['email'], -5) ?> com seu Código de Acesso.
                                <?php
                            }
                            ?>
                        </div>

                        <?php
                        if (!empty($inscr['email'])) {
                            $texto = "Seu Código de Acesso ao " . $this->_seletiva['n_sel'] . " é <br /><br />006" . $inscr['chave'] . str_pad($inscr['id_inscr'], 6, "0", STR_PAD_LEFT);
                            sendEmail::recuperaCodigo($inscr['n_insc'], $inscr['email'], $texto);
                            return 'sel';
                        }

                        return 'sel';
                    }
                }


                return "0";
            }
        } elseif (strlen($cpf) == 15) {
            $prefixo = substr($cpf, 0, 3);
            if ($prefixo == '006') {
                $chave = substr($cpf, 3, 6);
                $protocolo = substr($cpf, 9, 6);
                $sql = "SELECT * FROM `sel_inscricacao` WHERE `id_inscr` = " . intval($protocolo) . " AND `chave` LIKE '$chave' ";
                $query = $this->db->query($sql);
                $prot = $query->fetch();
                if (!empty($prot['id_inscr'])) {
                    $_SESSION['tmp']['id'] = intval($protocolo);
                    $this->_seletiva = $this->seletiva($_SESSION['tmp']['ps']);
                    $this->_inscricao = $this->inscr(intval($protocolo));

                    return 'view';
                } else {
                    tool::alert("Código de Acesso inválido");
                    return 'ini';
                }
            } else {
                tool::alert("Código de Acesso inválido");
                return 'ini';
            }
        } else {

            tool::alert("CPF ou Código de Acesso inválido.");

            return 'sel';
        }
        /**
          } else {
          tool::alert("Erro ao confirmar o captcha.");
          return 'sel';
          }
          }
         * 
         */
    }

    public function inscr($search, $field = "id_inscr") {
        $sql = "SELECT * FROM `sel_inscricacao` WHERE `$field` = '$search' ";
        $query = $this->db->query($sql);
        $array = $query->fetch();
        for ($c = 1; $c <= 3; $c++) {
            if (!empty($array['tel' . $c])) {
                $array['tel'][] = $array['tel' . $c];
            }
        }

        return $array;
    }

    public function cargos($cargo) {
        $cargo = sql::idNome('sel_cargo', ['fk_id_sel ' => $cargo]);

        return $cargo;
    }

    public function pdf() {
        if (!empty($_SESSION['tmp']['ps']) && !empty($_SESSION['tmp']['id'])) {
            $this->_seletiva = $this->seletiva($_SESSION['tmp']['ps']);
            $this->_inscricao = $this->inscr($_SESSION['tmp']['id']);
        } else {
            exit();
        }
    }

}
