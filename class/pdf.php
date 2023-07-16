<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pdf
 *
 * @author mc
 */
class pdf {

    public $orientation = 'P';
    public $mode = 'c';
    public $format = 'A4';
    public $default_font_size = 0;
    public $default_font = '';
    public $mgl = 15;
    public $mgr = 15;
    public $mgt = 38;
    public $mgb = 20;
    public $mgh = 9;
    public $mgf = 9;
    public $footerSet = true;
    public $headerSet = true;
    public $headerAlt = false;
    public $headerContent = null;
    public $title;
    public $address = true;
    public $autenticaSet = null;
    public $id_inst = null;

    public function __construct() {
        if (empty($this->id_inst)) {
            $this->id_inst = toolErp::id_inst();
        }
    }

    public function autenticaSistema($token, $path, $id) {
        $site = BASE_URL;
        $end = $site . HOME_URI . '/' . $path . '?id=' . $id . '&token=' . urlencode($token);
        $f = '<table style="width: 100%"><tr><td style="width: 100px">'
                . '<img src="' . HOME_URI . '/app/code/php/qr_img.php?d=' . urlencode($end) . '&.PNG" width="150" height="150"/>'
                . '</td><td>'
                . '<p>Documento autenticado pelo sistema</p>'
                . "<p>Acesso em $end</p>"
                . '</td></tr></table>';

        $this->autenticaSet = $f;
    }

    /**
     * incluir desta maneira:
     * $_POST = $pdf->autentica();
     * data da imprição está na variável "dt_PdfPrint"
     * receber variaveis sem filtro, direto do post
     * @return type
     */
    public function autentica($obrigatorio = null) {
        $naoDigit = filter_input(INPUT_POST, 'naoDigit', FILTER_SANITIZE_NUMBER_INT);
        if ($naoDigit) {
            return $_REQUEST;
        }
        $token = @$_REQUEST['token'];
        $senha = filter_input(INPUT_POST, 'senha17uwgstegddg', FILTER_UNSAFE_RAW);
        if ($token) {
            $sql = "SELECT * FROM `pdf_autentica` WHERE `token` LIKE '$token'";
            $query = pdoSis::getInstance()->query($sql);
            $dados = $query->fetch(PDO::FETCH_ASSOC);

            if ($dados) {
                $this->id_inst = $dados['fk_id_inst'];
                if ($dados['post']) {
                    $post = json_decode($dados['post'], true);
                    $post['dt_PdfPrint'] = substr($dados['time_stamp'], 0, 10);
                    $this->footerUatentica($dados['nome'], $dados['token'], $dados['path']);
                    return $post;
                }
            }
            return;
        } elseif (empty(toolErp::id_pessoa())) {
            ?>
            <div class="alert alert-danger" style="font-weight: bold; text-align: center; height: 80vh">
                Precisa do token ou estar logado para acessar este documento.
            </div>
            <?php
            exit();
        }
        if ($senha) {

            @$sen = sql::get('users', 'user_password', ['fk_id_pessoa' => toolErp::id_pessoa()], 'fetch')['user_password'];
            if ($sen) {
                $path = $_REQUEST['path'];
                $phpass = new PasswordHash(8, false);
                $teste = $phpass->CheckPassword($senha, $sen);
                if ($teste == 1) {
                    unset($_REQUEST['senha17uwgstegddg']);
                    unset($_POST['senha17uwgstegddg']);
                    $post = json_encode($_REQUEST);
                    $tokenId = uniqid(toolErp::id_pessoa());
                    $sql = "INSERT INTO `pdf_autentica` (`id_pa`, `fk_id_pessoa`, `nome`, `token`, `post`, `path`, `fk_id_inst`) VALUES ("
                            . "NULL,"
                            . " '" . toolErp::id_pessoa() . "',"
                            . " '" . toolErp::n_pessoa() . "',"
                            . " '$tokenId',"
                            . " '$post',"
                            . " '$path',"
                            . " '" . $this->id_inst . "');";
                    $query = pdoSis::getInstance()->query($sql);
                    $this->footerUatentica(toolErp::n_pessoa(), $tokenId, $path);

                    return $_REQUEST;
                } else {
                    $erro = true;
                    unset($senha);
                }
            } else {
                unset($senha);
            }
        }
        if (empty($senha)) {
            require ABSPATH . '/'. INCLUDE_FOLDER .'/structure/header.php';
            toolErp::modalInicio(1);
            if (!empty($erro)) {
                ?>
                <div class="alert alert-danger text-center">
                    Falha de autenticação
                </div>
                <?php
            }
            ?>
            <form method="POST">
                <table style="margin: 0 auto">
                    <tr>
                        <td>
                            <?=
                            formErp::hidden($_REQUEST)
                            . formErp::input('senha17uwgstegddg', 'Digite sua Senha', null, ' required ', null, 'password')
                            ?>
                        </td>
                        <td>
                            <button class="btn btn-info">
                                Enviar
                            </button> 
                        </td>
                    </tr>
                </table>
                <br />
            </form>
            <?php
            if (empty($obrigatorio)) {
                ?>
                <form method="POST">
                    <?=
                    formErp::hidden($_REQUEST)
                    . formErp::hidden(['naoDigit' => 1])
                    ?>
                    <div style="text-align: center">  
                        <button class="btn btn-warning">
                            Não Autenticar Digitalmente
                        </button>
                    </div>
                </form>
                <?php
            }
            ?>
            <br />
            <?php
            tool::modalFim();
            exit();
        }
    }

    /**
     * coloca ob_start() no inicio do arquivo. Inicializa o buffer e bloqueia qualquer saída para o navegador
     */
    public function exec($name = NUll) {
        $body = ob_get_contents();
        ob_end_clean();
        error_reporting(0);
        ini_set('display_errors', 0);
        require_once ABSPATH . '/vendor/autoload.php';

        $mpdf = new \Mpdf\Mpdf();
        $mpdf->DeflMargin = $this->mgl;
        $mpdf->DefrMargin = $this->mgr;
        $mpdf->orig_tMargin = $this->mgt;
        $mpdf->tMargin = $this->mgt;
        $mpdf->orig_bMargin = $this->mgb;
        $mpdf->margin_header = $this->mgh;
        $mpdf->margin_footer = $this->mgf;

        if (!empty($this->headerSet)) {
            if (empty($this->title)) {
                $this->title = 'Secretaria de Educação' . "<br />" . tool::n_inst();
            }
            if (!empty($this->headerContent)) {
                $mpdf->SetHTMLHeader($this->headerContent);
            } elseif (empty($this->headerAlt)) {
                $mpdf->SetHTMLHeader($this->header());
            } else {
                $mpdf->SetHTMLHeader($this->headerAlt);
            }
        }
        if (!empty($this->footerSet)) {
            $footer = $this->autenticaSet . "<div style=\"padding: 8px; background-color: silver\" ><table width=\"1000\"><tr><td style=\" font-weight: bold;width: 300px\">".SISTEMA_NOME."</td><td style=\" text-align: center\">". CLI_CIDADE .", " . data::porExtenso(date("Y-m-d")) . "</td><td  style=\"width: 300px\" align=\"right\">{PAGENO}/{nb}</td></tr></table></div>";
        }
        $mpdf->SetHTMLFooter($footer);
        $css = file_get_contents('<link rel="stylesheet" href="' . ABSPATH . '/'. INCLUDE_FOLDER .'/css/style.css">');
        $mpdf->WriteHTML($css, 1);
        $bootstrap = file_get_contents('<link rel="stylesheet" href="' . ABSPATH . '/'. INCLUDE_FOLDER .'/css/bootstrap.min.css">');
        $mpdf->WriteHTML($bootstrap, 1);

        if ($this->orientation == 'L' || $this->orientation == 'l') {
            $mpdf->AddPage('L');
        }
        $mpdf->WriteHTML('<br /><br />' . $body);
        if (empty($name)) {
            $mpdf->Output();
        } else {
            $mpdf->Output($name, true);
        }

        exit;
    }

    public function header() {
        $esc = new ng_escola($this->id_inst);

        return $esc->cabecalho();
    }

    public function footerUatentica($nome, $token, $path) {
        $site = BASE_URL;
        $end = $site . HOME_URI . '/' . $path . '?token=' . $token;
        $f = '<table style="width: 100%"><tr><td style="width: 100px">'
                . '<img src="' . HOME_URI . '/app/code/php/qr_img.php?d=' . $site . '/' . HOME_URI . '/sed/autentica?token=' . $token . '&.PNG" width="90" height="90"/>'
                . '</td><td>'
                . '<p>Documento assinado digitalmente com senha por ' . $nome . '</p>'
                . "<p>Acesso em $end</p>"
                . '</td></tr></table>';

        $this->autenticaSet = $f;
    }

    public function cabecalhoSecretaria() {

        $header = '<table style="width: 100%; border: 1px solid">'
                . '<tr>'
                . '<td rowspan = "5">'
                . '<img style="width: 70px" src="' . HOME_URI . '/views/_images/brasao.jpg"/>'
                . '</td>'
                . '<td style="font-size: 18px; text-align: Center">Prefeitura Municipal de '. ucfirst(CLI_CIDADE) .'</td>'
                . '<td rowspan = "5" style=" text-align: right">'
                . '<img style="width: 210px;" src="' . HOME_URI . '/views/_images/logo_relatorio.jpg"/>'
                . '</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 16px; text-align: Center">SE - Secretaria de Educação</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 10px; text-align: Center">'. CLI_END .'</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 10px; text-align: Center">'. CLI_BAIRRO .' - '. CLI_CIDADE .' - '. CLI_UF .' CEP '. CLI_CEP .' Fone '. CLI_FONE .'</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 10px; text-align: Center">'. CLI_URL .' - Email: '. CLI_MAIL .'</td>'
                . '</tr>'
                . '</table>';

        $this->headerAlt = $header;
    }

}
