<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tool
 *
 * @author mc
 */
class tool {

    /**
     * 1 = sim senão é nao
     * @param type $valor
     * @return type
     */
    public static function simnao($valor) {
        return $valor == 1 ? 'Sim' : 'Não';
    }

    /**
     * grava um valor no $_SESSION['error1']
     * @param type $erro
     */
    public static function erro($erro) {
//foi para o footer.php para evitar error css
        $_SESSION['error1'] = $erro;
    }

    /**
     * grava um valor no $_SESSION['error1']
     * @param type $erro
     */
    public static function sucesso($sucesso = NULL) {
        if (empty($sucesso)) {
            $sucesso = "Os dados foram salvos com sucesso!";
        }
//foi para o footer.php para evitar error css
        $_SESSION['error1'] = $sucesso;
    }

    /**
     * cria um alert no javaScript
     * @param type $param
     */
    public static function alert($param) {
        ?>
        <script>
            alert("<?php echo $param ?>");
        </script>
        <?php
    }

    public static function alertManutencao() {
        tool::alert("Estamos trabalhando nisso! Desde já agradecemos a compreensão");
    }

    public static function manutencao($exit = NULL) {
        tool::modalInicio('width: 50%');
        ?>
        <div style="text-align: center">
            <img src="<?php echo HOME_URI ?>/views/_images/construcao.gif" width="584" height="476" alt="construcao"/>
        </div>
        <?php
        tool::modalFim();
        if (!empty($exit)) {
            exit();
        }
    }

    /**
     * retorna uma expreção 1=a;2=b;3=c em forma de array, sem setar o $value reotrna só o key referente
     * @param type $array exempro 1=a;2=b;3=c
     * @param type $value
     * @return type
     */
    public static function converteElement($array, $value = NULL) {
        $con = explode(';', $array);
        foreach ($con as $vc) {
            $con_ = explode('=', $vc);
            $convert[$con_[0]] = $con_[1];
        }
        if (!empty($value)) {
            return $convert[$value];
        } else {
            return $convert;
        }
    }

    /**
     * retorna uma expreção 1=a;2=b;3=c em forma de array
     * @param type $array  exempro 1=a;2=b;3=c se for a;b;c tem que setar $valueKey = 1
     * @param type $valueKey
     * @return type
     */
    public static function converteArray($array, $valueKey = null) {
        $con = explode(';', $array);
        foreach ($con as $vc) {
            $con_ = explode('=', $vc);
            if (empty($valueKey)) {
                $convert[] = [$con_[0], $con_[1]];
            } else {
                if (!empty($con_[1])) {
                    $convert[$con_[0]] = $con_[1];
                } else {
                    $convert[$con_[0]] = $con_[0];
                }
            }
        }

        return $convert;
    }

    /**
     * escapas os aspas para não dar erro no DB e no imput do html
     * @param type $string
     * @return type
     */
    public static function escapaAspa($string) {
        $string = str_replace('"', '', $string);
        return str_replace("'", '', $string);
    }

    /**
     * array[0] = Array do form enviado
     * array[1] = Array do nome do campo
     * array[2] = valor composto ex: 1[nomecampo]
     * array[3] = post
     * @param type $field
     * @return type
     */
    public static function extratPost($field) {

        $name = explode('[', $field);
        if (count($name) > 1) {
            $name[1] = substr($name[1], 0, -1);
            if (!is_numeric($name[0])) {
                $name[1] = tool::encrypt($name[1]);
            }
            $name[2] = $name[0] . '[' . $name[1] . ']';
            if (is_array(@$_REQUEST[$name[0]])) {
                $name[3] = @$_REQUEST[$name[0]][$name[1]];
            } else {
                $name[3] = @$_REQUEST[$name[1]];
            }
        } else {
            $name[2] = $name[1] = $name[0];
            $name[3] = @$_REQUEST[$name[0]];
        }
        return $name;
    }

    /**
     * recebe um nome de campo do imput, se for um envio de array (ex a[teste]) ele prepara um post sem array (ex teste),
     * @param type $name  nome de campo do imput
     * @param type $post ignora  nome de campo do imput e devolve o post
     * @return type
     */
    public static function postFormat($name, $post = NULL) {
        if (empty($post)) {

            $pos = strpos($name, '[');
            if ($pos === false) {
                $namePost = @$_POST[$name];
            } else {
                $postex = explode('[', $name);
                $namePost = @$_POST[$postex[0]][substr($postex[1], 0, -1)];
            }
        } else {
            $namePost = $post;
        }

        return $namePost;
    }

    public static function includeView($view) {
        return ABSPATH . '/views/relat/' . $view . '.php';
    }

    /**
     * lisra um diretório
     * @param type $endereco diretorio
     * @return type
     */
    public static function dirList($endereco) {
        $d = scandir($endereco);
        unset($d[0]);
        unset($d[1]);
        return $d;
    }

    /**
     * abrevia um nome
     * @param type $name nome
     * @param type $count o número de caracteres máximo antes de ser truncado
     * @return string
     */
    public static function abreviaLogradouro($name, $count = 30) {
        if (strlen($name) > $count) {
            $t = explode(' ', $name);
            $name0 = $t[0];
            $name1 = $t[1];
            $name = $name0 . ' ' . $name1;
            for ($c = 2; $c < (count($t) - 2); $c++) {
                if (strlen($t[$c]) > 2) {
                    $name .= ' ' . substr(tool::removeAcentos($t[$c]), 0, 1) . '.';
                }
            }
            $name .= ' ' . $t[count($t) - 1];
        }
        return $name;
    }

    public static function abrevia($name, $count = 30) {
        if (strlen($name) > $count) {
            $t = explode(' ', $name);
            $name = $t[0];

            for ($c = 1; $c < (count($t) - 1); $c++) {
                if (strlen($t[$c]) > 2) {
                    $name .= ' ' . mb_substr($t[$c], 0, 1) . '.';
                }
            }
            $name .= ' ' . $t[count($t) - 1];
        }
        return $name;
    }

    /**
     * inclui o caminho para gerar formulario
     * @param type $form array [array, fields, titulo=null]
     */
    public static function relatSimples($form) {
        require ABSPATH . '/views/relat/simples.php';
    }

    public static function relatSimplesNovo($form) {
        require ABSPATH . '/views/relat/simples_novo.php';
    }

    /**
     *
      $abas[1] = [ 'nome' => "", 'ativo' => 1, 'hidden' => 'array', 'link' => "", ];
     * @param type $abas
     */
    public static function abas($abas) {
        require ABSPATH . '/views/relat/abas.php';

        return $activeNav;
    }

    public static function relatSimples1($form) {
        require ABSPATH . '/views/relat/simples1.php';
    }

    /**
     * coloca ob_start() no inicio do arquivo. Inicializa o buffer e bloqueia qualquer saída para o navegador
     */
    public static function pdf($orientation = 'P', $mode = 'c', $format = 'A4', $default_font_size = 0, $default_font = '', $mgl = 15, $mgr = 15, $mgt = 16, $mgb = 20, $mgh = 9, $mgf = 9) {
        $body = ob_get_contents();
        ob_end_clean();
        error_reporting(0);
        ini_set('display_errors', 0);
        //include( ABSPATH . "/app/mpdf/mpdf.php");
        //$mpdf = new mPDF($mode, $format, $default_font_size, $default_font, $mgl, $mgr, $mgt, $mgb, $mgh);

        require_once __DIR__ . '/../vendor/autoload.php';
        $config = [
            'tempDir' => __DIR__ . '/../tmp'
        ];
        $mpdf = new \Mpdf\Mpdf($config);

        $footer = "<div style=\"padding: 8px; background-color: #D4DF92;\" ><table width=\"1000\"><tr><td style=\" font-weight: bold;width: 300px\">".SISTEMA_NOME."</td><td style=\" text-align: center\">".ucfirst(CLI_CIDADE).", " . date("d") . " de " . data::mes(date("m")) . " de " . date("Y") . "</td><td  style=\"width: 300px\" align=\"right\">{PAGENO}/{nb}</td></tr></table></div>";

        $mpdf->SetHTMLFooter($footer);
        $css = file_get_contents(ABSPATH . "/views/_css/style.css");
        $mpdf->WriteHTML($css, 1);
        $css1 = file_get_contents(ABSPATH . "/views/_css/bootstrap-theme.css");
        $mpdf->WriteHTML($css1, 1);
        $css2 = file_get_contents(ABSPATH . "/views/_css/bootstrap.css");
        $mpdf->WriteHTML($css2, 1);
        if ($orientation == 'L' || $orientation == 'l') {
             $mpdf->AddPage('L', 'A4', 0, '', '', 15, 15, 10, 10, 10, 6);
        }
        $mpdf->WriteHTML($body);

        $mpdf->Output();
        exit;
    }

    /**
     * coloca ob_start() no inicio do arquivo. Inicializa o buffer e bloqueia qualquer saída para o navegador
     */
    public static function pdfSimpleSemRodape($orientation = 'P', $mode = 'c', $format = 'A4', $default_font_size = 0, $default_font = '', $mgl = 15, $mgr = 15, $mgt = 16, $mgb = 20, $mgh = 9, $mgf = 9) {
        $body = ob_get_contents();
        ob_end_clean();

        error_reporting(1);
        ini_set('display_errors', 1);
        //include( ABSPATH . "/app/mpdf/mpdf.php");
        //$mpdf = new mPDF($mode, $format, $default_font_size, $default_font, $mgl, $mgr, $mgt, $mgb, $mgh);

        require_once __DIR__ . '/../vendor/autoload.php';

        $config = [
            'tempDir' => __DIR__ . '/../tmp'
        ];

        $mpdf = new \Mpdf\Mpdf($config);


        $mpdf->SetHTMLFooter();
        $css = file_get_contents(ABSPATH . "/views/_css/style.css");
        $mpdf->WriteHTML($css, 1);
        $css1 = file_get_contents(ABSPATH . "/views/_css/bootstrap-theme.css");
        $mpdf->WriteHTML($css1, 1);
        $css2 = file_get_contents(ABSPATH . "/views/_css/bootstrap.css");
        $mpdf->WriteHTML($css2, 1);
        if ($orientation == 'L' || $orientation == 'l') {
            $mpdf->AddPage('L', 'A4', 0, '', '', 15, 15, 58, 15, 20, 6);
        }
        $mpdf->WriteHTML($body);

        $mpdf->Output();
        ob_end_flush();
        exit;
    }

    public static function pdfSemRodape($orientation = 'P', $mode = 'c', $format = 'A4', $default_font_size = 0, $default_font = '', $mgl = 15, $mgr = 15, $mgt = 35, $mgb = 20, $mgh = 9, $mgf = 9) {
        $escola = new escola();
        $body = ob_get_contents();
        ob_end_clean();
        error_reporting(0);
        ini_set('display_errors', 0);
        //include( ABSPATH . "/app/mpdf/mpdf.php");
        //$mpdf = new mPDF($mode, $format, $default_font_size, $default_font, $mgl, $mgr, $mgt, $mgb, $mgh);

        require_once __DIR__ . '/../vendor/autoload.php';
        $config = [
            'tempDir' => __DIR__ . '/../tmp'
        ];
        $mpdf = new \Mpdf\Mpdf($config);

        $mpdf->SetHTMLHeader($escola->cabecalho());
        $mpdf->SetHTMLFooter();
        $css = file_get_contents(ABSPATH . "/views/_css/style.css");
        $mpdf->WriteHTML($css, 1);
        $css1 = file_get_contents(ABSPATH . "/views/_css/bootstrap-theme.css");
        $mpdf->WriteHTML($css1, 1);
        $css2 = file_get_contents(ABSPATH . "/views/_css/bootstrap.css");
        $mpdf->WriteHTML($css2, 1);
        if ($orientation == 'L' || $orientation == 'l') {
            //$mpdf->AddPage('L', 'A4', 0, '', '', 15, 15, 58, 15, 20, 6);
        }
        $mpdf->AddPage('P', 'A4', 0, '', '', 15, 15, 38, 15, 9, 9);
        $mpdf->WriteHTML($body);

        $mpdf->Output();
        exit;
    }

    /**
     * coloca ob_start() no inicio do arquivo. Inicializa o buffer e bloqueia qualquer saída para o navegador
     */
    public static function pdfEscola($orientation = 'P', $id_inst = NULL, $mode = 'c', $format = 'A4', $default_font_size = 0, $default_font = '', $mgl = 15, $mgr = 15, $mgt = 35, $mgb = 20, $mgh = 9, $mgf = 9) {
        $escola = new escola($id_inst);
        $body = ob_get_contents();
        ob_end_clean();
        error_reporting(0);
        ini_set('display_errors', 0);
        //include( ABSPATH . "/app/mpdf/mpdf.php");
        //$mpdf = new mPDF($mode, $format, $default_font_size, $default_font, $mgl, $mgr, $mgt, $mgb, $mgh);

        require_once __DIR__ . '/../vendor/autoload.php';
        $config = [
            'tempDir' => __DIR__ . '/../tmp'
        ];
        $mpdf = new \Mpdf\Mpdf($config);

        $footer = "<div style=\"padding: 5px; background-color: #D4DF92;\" ><table width=\"100%\"><tr><td style=\" font-weight: bold; font-size: 8pt; width: 100px\">".SISTEMA_NOME."</td><td style=\" text-align: center;font-size: 8pt;font-weight: bold\">".ucfirst(CLI_CIDADE).", " . date("d") . " de " . data::mes(date("m")) . " de " . date("Y") . "</td><td  style=\"width: 100px; font-size: 8pt; font-weight: bold\" align=\"right\">{PAGENO}/{nb}</td></tr></table></div>";
        $mpdf->SetHTMLHeader($escola->cabecalho());
        $mpdf->SetHTMLFooter($footer);
        $css = file_get_contents(ABSPATH . "/views/_css/style.css");
        $mpdf->WriteHTML($css, 1);
        $css1 = file_get_contents(ABSPATH . "/views/_css/bootstrap-theme.css");
        $mpdf->WriteHTML($css1, 1);
        $css2 = file_get_contents(ABSPATH . "/views/_css/bootstrap.css");
        $mpdf->WriteHTML($css2, 1);
        if ($orientation == 'L' || $orientation == 'l') {
            $mpdf->AddPage('L', 'A4', 0, '', '', 15, 15, 45, 15, 20, 6);
        } else {
            $mpdf->AddPage('P', 'A4', 0, '', '', 15, 15, 45, 15, 20, 6);
        }
        $mpdf->WriteHTML($body);

        $mpdf->Output();
        exit;
    }

    public static function pdfGiz($orientation = 'P', $mode = 'c', $format = 'A4', $default_font_size = 0, $default_font = '', $mgl = 15, $mgr = 15, $mgt = 35, $mgb = 20, $mgh = 9, $mgf = 9) {

        $header = '<table style="width: 100%">'
                . '<tr>'
                . '<td>'
                . '<img style="width: 250px" src="' . HOME_URI . '/views/giz/img/1.jpg"/>'
                . '</td>'
                . '<td style=" text-align: center">'
                . '<img style="width: 190px;" src="' . HOME_URI . '/views/giz/img/2.png"/>'
                . '</td>'
                . '<td style=" text-align: right" >'
                . '<img style="width: 200px" src="' . HOME_URI . '/views/giz/img/3.jpg"/>'
                . '</td>'
                . '</table>';

        $body = ob_get_contents();
        ob_end_clean();
        //include( ABSPATH . "/app/mpdf/mpdf.php");
        error_reporting(0);
        ini_set('display_errors', 0);
        //$mpdf = new mPDF($mode, $format, $default_font_size, $default_font, $mgl, $mgr, $mgt, $mgb, $mgh);


        require_once __DIR__ . '/../vendor/autoload.php';
        $config = [
            'tempDir' => __DIR__ . '/../tmp'
        ];
        $mpdf = new \Mpdf\Mpdf($config);

        $footer = "<div style=\"padding: 8px; background-color: #D4DF92;\" ><table width=\"1000\"><tr><td style=\" font-weight: bold;width: 300px\">".SISTEMA_NOME."</td><td style=\" text-align: center\">".ucfirst(CLI_CIDADE).", " . date("d") . " de " . data::mes(date("m")) . " de " . date("Y") . "</td><td  style=\"width: 300px\" align=\"right\">{PAGENO}/{nb}</td></tr></table></div>";
        $mpdf->SetHTMLHeader($header);
        $mpdf->SetHTMLFooter($footer);
        $css = file_get_contents(ABSPATH . "/views/_css/style.css");
        $mpdf->WriteHTML($css, 1);
        $css1 = file_get_contents(ABSPATH . "/views/_css/bootstrap-theme.css");
        $mpdf->WriteHTML($css1, 1);
        $css2 = file_get_contents(ABSPATH . "/views/_css/bootstrap.css");
        $mpdf->WriteHTML($css2, 1);
        if ($orientation == 'L' || $orientation == 'l') {
            $mpdf->AddPage('L', 'A4', 0, '', '', 15, 15, 58, 15, 20, 6);
        }
        $mpdf->WriteHTML($body);

        $mpdf->Output();
        exit;
    }

    public static function pdfSecretaria($orientation = 'P', $mode = 'c', $format = 'A4', $default_font_size = 0, $default_font = '', $mgl = 15, $mgr = 15, $mgt = 35, $mgb = 20, $mgh = 9, $mgf = 9) {

        $header = '<table style="width: 100%">'
                . '<tr>'
                . '<td>'
                . '<img style="width: 80px" src="' . HOME_URI . '/views/_images/brasao.jpg"/>'
                . '</td>'
                . '<td style=" text-align: right">'
                . '<img style="width: 350px;" src="' . HOME_URI . '/views/_images/logose.png"/>'
                . '</td>'
                . '</table>';

        $body = ob_get_contents();
        ob_end_clean();
        //include( ABSPATH . "/app/mpdf/mpdf.php");
        error_reporting(0);
        ini_set('display_errors', 0);
        //$mpdf = new mPDF($mode, $format, $default_font_size, $default_font, $mgl, $mgr, $mgt, $mgb, $mgh);

        require_once __DIR__ . '/../vendor/autoload.php';
        $config = [
            'tempDir' => __DIR__ . '/../tmp'
        ];
        $mpdf = new \Mpdf\Mpdf($config);

        $footer = "<div style=\"padding: 8px; background-color: #D4DF92;\" ><table width=\"1000\"><tr><td style=\" font-weight: bold;width: 300px\">".SISTEMA_NOME."</td><td style=\" text-align: center\">".ucfirst(CLI_CIDADE).", " . date("d") . " de " . data::mes(date("m")) . " de " . date("Y") . "</td><td  style=\"width: 300px\" align=\"right\">{PAGENO}/{nb}</td></tr></table></div>";
        $mpdf->SetHTMLHeader($header);
        $mpdf->SetHTMLFooter($footer);
        $css = file_get_contents(ABSPATH . "/views/_css/style.css");
        $mpdf->WriteHTML($css, 1);
        $css1 = file_get_contents(ABSPATH . "/views/_css/bootstrap-theme.css");
        $mpdf->WriteHTML($css1, 1);
        $css2 = file_get_contents(ABSPATH . "/views/_css/bootstrap.css");
        $mpdf->WriteHTML($css2, 1);
        if ($orientation == 'L' || $orientation == 'l') {
            $mpdf->AddPage('L', 'A4', 0, '', '', 15, 15, 58, 15, 20, 6);
        }
        $mpdf->WriteHTML($body);

        $mpdf->Output();
        exit;
    }

    /**
     * inclui o caminho para gerar formulario
     * @param type $form array [array, fields, titulo=null]
     */
    public static function relatTable($form) {
        require ABSPATH . '/views/relat/table.php';
    }

    /**
     * devolve bom dia, boa tarde ou boa noite
     * @return string
     */
    public static function bom() {
        if (@date('H') <= 12) {
            $bom = 'Bom Dia';
        } elseif (@date('H') <= 18) {
            $bom = 'Boa Tarde';
        } else {
            $bom = 'Boa Noite';
        }

        return $bom;
    }

    /**
     * remove os acentos das palavras
     * @param type $string string
     * @return type
     */
    public static function removeAcentos($string) {
        $string = preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);

        $string = str_replace('º', 'o.', $string);
        $string = str_replace('ª', 'a.', $string);
        $string = str_replace('ç', 'c', $string);
        $string = str_replace('Ç', 'C', $string);

        return $string;
    }

    /**
     * criptografa um valor Atenção, se token = NULL só vale para uma sessão depois o dado é inutilizado
     * @param type $dado
     * @param type $token
     * @return type
     */
    public static function encrypt($dado, $token = NULL) {
        if (empty($token)) {
            $token = $_SESSION['userdata']['user_session_id'];
        }
        return @openssl_encrypt($dado, 'aes128', $token);
    }

    /**
     * descriptografa um valor Atenção, se token = NULL só descriptografa na mesma sessão depois o dado é inutilizado
     * @param type $dado
     * @param type $token
     * @return type
     */
    public static function dencrypt($dado, $token = NULL) {
        if (empty($token)) {
            $token = $_SESSION['userdata']['user_session_id'];
        }
        return @openssl_decrypt($dado, 'aes128', $token);
    }

    /**
     * redericiona para a página home
     */
    public static function sair() {
        echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '">';
        echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '";</script>';
    }

    /**
     * devolve o id da instancia logada
     * @return type
     */
    public static function id_inst($id_inst = NULL) {
        if (empty($id_inst)) {
            return @$_SESSION['userdata']['id_inst'];
        } else {
            return $id_inst;
        }
    }

    public static function n_inst() {
        return @$_SESSION['userdata']['n_inst'];
    }

    /**
     * devolve o nível logada
     * @return type
     */
    public static function n_nivel() {
        return $_SESSION['userdata']['n_nivel'];
    }

    /**
     * devolve o id_nivel logada
     * @return type
     */
    public static function id_nivel() {
        return $_SESSION['userdata']['id_nivel'];
    }

    /**
     * é obvio!!!
     * @return type
     */
    public static function id_pessoa($id_pessoa = NULL) {
        if (empty($id_pessoa)) {
            return @$_SESSION['userdata']['id_pessoa'];
        } else {
            return $id_pessoa;
        }
    }

    public static function cie() {
        $id_inst = $_SESSION['userdata']['id_inst'];
        return sql::get('ge_escolas', 'cie_escola', ['fk_id_inst' => $id_inst], 'fetch')['cie_escola'];
    }

    /**
     * retorna Feminino para sexo = F e Masculino para sexo = M
     * @param type $sigla
     * @return string
     */
    public static function sexo($sigla) {
        if ($sigla == 'F') {
            return 'Feminino';
        } elseif ($sigla == 'M') {
            return 'Masculino';
        } else {
            return;
        }
    }

    /**
     * retorna 'a' para sexo = F e 'o' para sexo = M
     * @param type $sigla
     * @return string
     */
    public static function sexoArt($sigla = NULL) {
        if ($sigla == 'F') {
            return 'a';
        } elseif ($sigla == 'M') {
            return 'o';
        } else {
            return 'o(a)';
        }
    }

    public static function getTxt($path, $file) {

        header("Content-Type: application/save");
        header("Content-Length:" . filesize($file));
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header("Content-Transfer-Encoding: binary");
        header('Expires: 0');
        header('Pragma: no-cache');

        readfile($path . $file);
        exit;
    }

    public static function impotCsv($file, $table) {
        $delimitador = ',';
        $cerca = '"';

        // Abrir arquivo para leitura
        $f = fopen($file, 'r');
        if ($f) {
            // Ler cabecalho do arquivo
            $cabecalho = fgetcsv($f, 0, $delimitador, $cerca);
            foreach ($cabecalho as $k => $cb) {
                $c[$k] = 1;
            }
            // Enquanto nao terminar o arquivo
            while (!feof($f)) {

                // Ler uma linha do arquivo
                $linha = fgetcsv($f, 0, $delimitador, $cerca);

                if ($linha) {

                    foreach ($linha as $k => $v) {
                        if (@$c[$k] < strlen($v)) {
                            @$c[$k] = strlen($v);
                        }
                    }
                }
            }


            foreach ($cabecalho as $k => $cb) {
                @$col .= " `" . $cb . "` varchar(" . $c[$k] . ") NULL, ";
                @$coll .= " `" . $cb . "`, ";
            }
            $col = substr($col, 0, -2);
            $coll = substr($coll, 0, -2);
            echo $sql = "CREATE TABLE IF NOT EXISTS $table ($col) ENGINE = InnoDB;";
            echo '<br /><br /><br />';
            $query = teste::getInstance()->query($sql);
        }
        $f = fopen($file, 'r');
        while (!feof($f)) {

// Ler uma linha do arquivo
            $linha = fgetcsv($f, 0, $delimitador, $cerca);
            foreach ($linha as $k => $l) {
                $linha[$k] = str_replace("'", "", $l);
            }
            if ($linha) {
                $value = "'" . implode("','", $linha) . "'";
                $sql = "replace INTO `$table` ($coll) VALUES ($value);";
                $query = teste::getInstance()->query($sql);
            }
        }
        fclose($f);
    }

    public static function tokenSql() {
        return substr((date("yhdm") / 3.5288 * 68), 0, 20);
    }

    /**
     *
     * @param type $style
     * @param type $desativado  Não inicia o modal - necessita <input type="submit" onclick=" $('#myModal').modal('show');" value="" />
     */
    public static function modalInicio($style = 'width: 95%', $desativado = NULL, $nome = 'myModal') {
        ?>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <?php
        if (empty($desativado)) {
            ?>
            <script type="text/javascript">
            $(document).ready(function () {
                $("#<?php echo $nome ?>").modal('show');
            });
            </script>
            <?php
        }
        ?>
        <div id="<?php echo $nome ?>" class="modal fade" style="overflow: auto">
            <div class="modal-dialog" style="<?php echo @$style ?>">
                <div class="modal-content">

                    <div class="modal-body">
                        <div style="text-align: right">
                            <button type="button" class="btn btn-default" data-dismiss="modal">X</button>
                        </div>
                        <?php
                    }

                    public static function modalFim() {
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public static function sortNome($qt = 1, $incluir = NULL) {
        if (!empty($incluir)) {
            foreach ($incluir as $v) {
                $nomes[$v] = $v;
            }
        }
        $nome = [
            1 => 'FERNANDO', 2 => 'MARLENE', 3 => 'SIMONE', 4 => 'JEANETE', 5 => 'APARECIDA', 6 => 'ROSELY', 7 => 'EDNA', 8 => 'SUZY', 9 => 'CLEIDE', 10 => 'IRACEMA', 11 => 'NELSON', 12 => 'NATALIA', 13 => 'SILVIA', 14 => 'CLAUDIA', 15 => 'ALMIRO', 16 => 'GRACA', 17 => 'ANTONIETA', 18 => 'NIVALDO', 19 => 'VANIA', 20 => 'SOLANGE', 21 => 'ROSANGELA', 22 => 'DOUGLAS', 23 => 'MARGARIDA', 24 => 'ESTER', 25 => 'LILIAN', 26 => 'JULIANO', 27 => 'MARIZA', 28 => 'EDINETE', 29 => 'ELVIRA', 30 => 'MARCIA', 31 => 'VANDA', 32 => 'TELMA', 33 => 'JANAINA', 34 => 'GABRIELA', 35 => 'AILTON', 36 => 'LUCIANA', 37 => 'CLAUDETE', 38 => 'FRANCISCA', 39 => 'LIDIA', 40 => 'SANDRA', 41 => 'MARLI', 42 => 'NEIDE', 43 => 'MARINA', 44 => 'JENIZ', 45 => 'LUIZA', 46 => 'JOSIMARA', 47 => 'MARIA', 48 => 'LAYZA', 49 => 'TIARA', 50 => 'IOLANDA', 51 => 'SHEILA', 52 => 'CRISTINA', 53 => 'ALCELI', 54 => 'RITA', 55 => 'ELIANE', 56 => 'DENISE', 57 => 'FLAVIA', 58 => 'MADALENA', 59 => 'LUCIMARA', 60 => 'ADRIANA', 61 => 'DEBORAH', 62 => 'ELIANA', 63 => 'MONICA', 64 => 'DEISE', 65 => 'EDEGARDA', 66 => 'JUDITH', 67 => 'DEBORA', 68 => 'GELSA', 69 => 'WALKIRIA', 70 => 'PATRICIA', 71 => 'FABIANA', 72 => 'REGINA', 73 => 'ROSELAINE', 74 => 'SONIA', 75 => 'NEUSIMAR', 76 => 'ANA', 77 => 'ANGELA', 78 => 'FATIMA', 79 => 'CECILIA', 80 => 'HELLEN', 81 => 'ANTONIA', 82 => 'ESDRAS', 83 => 'SHIRLEI', 84 => 'ROSANA', 85 => 'LIRIAN', 86 => 'NELCIONE', 87 => 'ELAINE', 88 => 'LEILA', 89 => 'ELENICE', 90 => 'ROSA', 91 => 'ELIZETE', 92 => 'ADAUTO', 93 => 'JULIANA', 94 => 'BENEDITA', 95 => 'HELENA', 96 => 'NILANDE', 97 => 'LUZIA', 98 => 'MAGALI', 99 => 'ISABEL', 100 => 'VALERIA', 101 => 'MARISA', 102 => 'JOSIMARE', 103 => 'PRISCILA', 104 => 'ANDREA', 105 => 'GLEICE', 106 => 'ROSICLER', 107 => 'SANCLEUDA', 108 => 'VANESSA', 109 => 'SILVANETE', 110 => 'REGIANE', 111 => 'MILLENA', 112 => 'ELENITA', 113 => 'MARGENIL', 114 => 'ONDINA', 115 => 'ZILDA', 116 => 'FLORIANO', 117 => 'BENJAMIN', 118 => 'DIRLAYNE', 119 => 'RAIMUNDO', 120 => 'EOVANI', 121 => 'SUZI', 122 => 'PAULO', 123 => 'JOSE', 124 => 'ALESSANDRA', 125 => 'GILSON', 126 => 'LUCIA', 127 => 'SELMA', 128 => 'KATIA', 129 => 'EDER', 130 => 'ELISIA', 131 => 'MARCOS', 132 => 'NADIA', 133 => 'ELISABETH', 134 => 'SANDERLI', 135 => 'CELIA', 136 => 'MIRIAM', 137 => 'GIVANI', 138 => 'EUNICE', 139 => 'JOSILDA', 140 => 'FERNANDA', 141 => 'INES', 142 => 'SILVANA', 143 => 'HOSANA', 144 => 'HUMBERTO', 145 => 'NORANEIDE', 146 => 'NEUZA', 147 => 'IRENE', 148 => 'ANGELITA', 149 => 'DULCE', 150 => 'VIVIAN', 151 => 'JOSEMARIA', 152 => 'GILMAR', 153 => 'JAMIL', 154 => 'SUELI', 155 => 'VALKIRIA', 156 => 'MARILAINE', 157 => 'DAINEIA', 158 => 'JOANA', 159 => 'ARLETE', 160 => 'MARCELA', 161 => 'EUNADES', 162 => 'FABIOLA', 163 => 'CIBELE', 164 => 'RENATA', 165 => 'GLAUCIA', 166 => 'TARCILIA', 167 => 'KARINE', 168 => 'AURILENE', 169 => 'LAZARA', 170 => 'MESSIAS', 171 => 'STAEL', 172 => 'VERA', 173 => 'NICACIA', 174 => 'HERICA', 175 => 'LUCIMARY', 176 => 'FILOMENA', 177 => 'ELISABETE', 178 => 'ROSMARINA', 179 => 'EDINELIA', 180 => 'ODINEIA', 181 => 'EDENA', 182 => 'RUTH', 183 => 'ERICA', 184 => 'ROSEMAR', 185 => 'CLARI', 186 => 'SILMARA', 187 => 'ELBA', 188 => 'EDNACIR', 189 => 'ANDERSON', 190 => 'LUCIANE', 191 => 'EVARISTO', 192 => 'ROSIMEIRE', 193 => 'SUELY', 194 => 'ELENY', 195 => 'SIDINEIA', 196 => 'CARMEN', 197 => 'CLEONICE', 198 => 'LILIANE', 199 => 'SIDNALVA', 200 => 'GISLAINE', 201 => 'DELFINA', 202 => 'CAROLINA', 203 => 'POLIANE', 204 => 'HELIA', 205 => 'VERONICA', 206 => 'LUIS', 207 => 'EDSON', 208 => 'JOSELITA', 209 => 'RODOLFO', 210 => 'ROSELANE', 211 => 'MARILENE', 212 => 'JAQUELINE', 213 => 'ANALU', 214 => 'EMERSON', 215 => 'HELVIO', 216 => 'ROGERIO', 217 => 'ARIONILDO', 218 => 'JOVINETA', 219 => 'ALDRIA', 220 => 'LUNA', 221 => 'SIDNEI', 222 => 'RUTY', 223 => 'RENATO', 224 => 'LUCIMEIRE', 225 => 'JOICIARA', 226 => 'JOSEVALDO', 227 => 'SIDNEIA', 228 => 'IGOR', 229 => 'CILSA', 230 => 'ZELIA', 231 => 'JONHSON', 232 => 'ALEXANDRINA', 233 => 'DALVA', 234 => 'VALDINEIA', 235 => 'ITAMAR', 236 => 'JOAO', 237 => 'SIMONI', 238 => 'EDISON', 239 => 'ERISMILDO', 240 => 'ANDREIA', 241 => 'VALQUIRIA', 242 => 'ALDA', 243 => 'ALEXANDRE', 244 => 'ROSELI', 245 => 'ANDRE', 246 => 'LUZINETE', 247 => 'VASTY', 248 => 'WILSON', 249 => 'MARINETE', 250 => 'GIANETE', 251 => 'ELZA', 252 => 'ARIZABETH', 253 => 'TAYANA', 254 => 'ELCIO', 255 => 'ELIZABETE', 256 => 'CRISTIANI', 257 => 'GIOVANA', 258 => 'ELIZANGELA', 259 => 'RAQUEL', 260 => 'JUPIARA', 261 => 'MARIO', 262 => 'CELESTE', 263 => 'LYGIA', 264 => 'MARTA', 265 => 'EDINEIA', 266 => 'CRISTIANE', 267 => 'LEANDRA', 268 => 'VILMA', 269 => 'LIDIANE', 270 => 'SABINA', 271 => 'ALCIONE', 272 => 'JOELLEN', 273 => 'ROBSON', 274 => 'NEUSA', 275 => 'SUZANA', 276 => 'WANDERLEY', 277 => 'OSMARINA', 278 => 'PENHA', 279 => 'MAURITA', 280 => 'CINIRA', 281 => 'ISABELA', 282 => 'CORINA', 283 => 'ELINEIA', 284 => 'ERIKA', 285 => 'ELISANGELA', 286 => 'IVANETE', 287 => 'REINALDO', 288 => 'EDILENE', 289 => 'MARCIO', 290 => 'NILVA', 291 => 'CATIA', 292 => 'RALPH', 293 => 'ROSIMEIRI', 294 => 'ROSEMARI', 295 => 'MARGARETE', 296 => 'VICTOR', 297 => 'TANIA', 298 => 'NILZETE', 299 => 'TEREZA', 300 => 'SUSANA', 301 => 'ZELINDA', 302 => 'DANIEL', 303 => 'EULALIA', 304 => 'CARLOS', 305 => 'ANTONIO', 306 => 'IRIS', 307 => 'RUBIA', 308 => 'ROSELANIA', 309 => 'IDINEI', 310 => 'VIVIANE', 311 => 'DAMARYS', 312 => 'DORANEI', 313 => 'HELOISA', 314 => 'JANETE', 315 => 'RENILDA', 316 => 'ROBERTA', 317 => 'MILENA', 318 => 'APARECIDO', 319 => 'ROSEMEIRE', 320 => 'VALMIR', 321 => 'LUCILA', 322 => 'WANDERLEI', 323 => 'IDAMARIS', 324 => 'IVONETE', 325 => 'NICANOR', 326 => 'VALDECI', 327 => 'DORIVAL', 328 => 'NICEIA', 329 => 'IRACI', 330 => 'AGNA', 331 => 'CLAUDITE', 332 => 'LEVITICO', 333 => 'DINAIR', 334 => 'REGILENE', 335 => 'LUCI', 336 => 'DAMARES', 337 => 'SILVANIA', 338 => 'LAELSON', 339 => 'LEIA', 340 => 'ZENAIDE', 341 => 'MARA', 342 => 'GIVANETE', 343 => 'ADEMIR', 344 => 'ALESSANDRO', 345 => 'JOELI', 346 => 'TERESINHA', 347 => 'ALEXANDRA', 348 => 'MICHELLE', 349 => 'EVANDRO', 350 => 'TEREZINHA', 351 => 'ESMERALDA', 352 => 'DOROTI', 353 => 'ZULEICA', 354 => 'MIRIAN', 355 => 'CARINA', 356 => 'MARLY', 357 => 'ROSIMAR', 358 => 'DANIELA', 359 => 'FRANCISCO', 360 => 'ALICE', 361 => 'JEANE', 362 => 'DAVID', 363 => 'ADRIANO', 364 => 'TALITA', 365 => 'JERRI', 366 => 'LEONI', 367 => 'CICERO', 368 => 'RENE', 369 => 'ELIETE', 370 => 'EDWIGES', 371 => 'NELCI', 372 => 'CONCEICAO', 373 => 'BEATRIZ', 374 => 'JONIA', 375 => 'GISELE', 376 => 'BRUNA', 377 => 'ANALICE', 378 => 'TATIANE', 379 => 'TAIS', 380 => 'GILMA', 381 => 'JOSEFA', 382 => 'ALVARO', 383 => 'CLEUZA', 384 => 'MARIANGELA', 385 => 'IZABEL', 386 => 'YOLANDA', 387 => 'VANA', 388 => 'ROSIMARY', 389 => 'LOLITA', 390 => 'SIDNEY', 391 => 'DIMAS', 392 => 'LINDALVA', 393 => 'CARMEM', 394 => 'VALCICLEIDE', 395 => 'ROSE', 396 => 'LUCINEIA', 397 => 'ROSILENE', 398 => 'CASSIA', 399 => 'CELINA', 400 => 'CICERA', 401 => 'LEUZINA', 402 => 'BRENDA', 403 => 'SAMUEL', 404 => 'LEANDRO', 405 => 'GERALDO', 406 => 'IARA', 407 => 'NARA', 408 => 'DERCILENE', 409 => 'HELIEL', 410 => 'MARIANA', 411 => 'WALDIR', 412 => 'ALINE', 413 => 'ENEIDA', 414 => 'LETICIA', 415 => 'BRUNO', 416 => 'CLEUSA', 417 => 'JUDITE', 418 => 'SILVIO', 419 => 'NILZA', 420 => 'ADELAIDE', 421 => 'HELEN', 422 => 'IVANA', 423 => 'GISELI', 424 => 'VALDICE', 425 => 'ELIZA', 426 => 'RAIMUNDA', 427 => 'TERTULIANO', 428 => 'GENI', 429 => 'WALTER', 430 => 'JEFFERSON', 431 => 'KATHIA', 432 => 'ODETE', 433 => 'JUSCELINA', 434 => 'LUCIMAR', 435 => 'ROSEANE', 436 => 'JANDIRA', 437 => 'OSARIA', 438 => 'ALVANY', 439 => 'FRANCINE', 440 => 'KLEBER', 441 => 'SIRLEI', 442 => 'ENILDA', 443 => 'EVANIR', 444 => 'FABIO', 445 => 'RONILDA', 446 => 'ROMULO', 447 => 'VASTI', 448 => 'CLAUDINEI', 449 => 'CRISTIENE', 450 => 'ARIOVALDO', 451 => 'CAMILA', 452 => 'PAULA', 453 => 'GISELLE', 454 => 'ESTELA', 455 => 'CREUSA'
        ];
        if ($qt > 0) {
            while (count(@$nomes) < $qt) {
                $key = mt_rand(1, 455);
                @$nomes[$nome[$key]] = $nome[$key];
            }
            shuffle($nomes);
            return $nomes;
        }
    }

    public static function cor($cor = NULL) {
        $cores = [
            1 => '#0000FF',
            2 => '#4B0082',
            3 => '#4682B4',
            4 => '#FF4500',
            5 => '#006400',
            6 => '#8B4513',
            7 => '#2F4F4F',
            8 => '#FF0000',
            9 => '#7B68EE',
            10 => '#2F4F4F',
            11 => '#0000FF',
            12 => '#4B0082',
            13 => '#4682B4',
            14 => '#FF4500',
            15 => '#006400',
            16 => '#8B4513',
            17 => '#2F4F4F',
            18 => '#FF0000',
            19 => '#7B68EE',
            21 => '#0000FF',
            22 => '#4B0082',
            23 => '#4682B4',
            24 => '#FF4500',
            25 => '#006400',
            26 => '#8B4513',
            27 => '#2F4F4F',
            28 => '#FF0000',
            29 => '#7B68EE',
            30 => '#2F4F4F',
            31 => '#0000FF',
            32 => '#4B0082',
            33 => '#4682B4',
            34 => '#FF4500',
            35 => '#006400',
            36 => '#8B4513',
            37 => '#2F4F4F',
            38 => '#FF0000',
            39 => '#7B68EE',
            41 => '#0000FF',
            42 => '#4B0082',
            43 => '#4682B4',
            44 => '#FF4500',
            45 => '#006400',
            46 => '#8B4513',
            47 => '#2F4F4F',
            48 => '#FF0000',
            49 => '#7B68EE',
            50 => '#2F4F4F',
            51 => '#0000FF',
            52 => '#4B0082',
            53 => '#4682B4',
            54 => '#FF4500',
            55 => '#006400',
            56 => '#8B4513',
            57 => '#2F4F4F',
            58 => '#FF0000',
            59 => '#7B68EE'
        ];
        if (empty($cor)) {
            return $cores;
        } else {
            return $cores[$cor];
        }
    }

    public static function alertModal($param) {
        tool::modalInicio();
        ?>
        <div style="text-align: center;font-size: 18px">
            <?php echo $param ?>
        </div>
        <?php
        tool::modalFim();
    }

    public static function idName($array = NULL, $id = NULL, $name = NULL) {
        if (!empty($array)) {
            if (empty($id) || empty($name)) {
                foreach (current($array) as $k => $v) {
                    if (substr($k, 0, 3) == 'id_') {
                        $id = $k;
                    } elseif (substr($k, 0, 2) == 'n_') {
                        $name = $k;
                    }
                }
            }
            foreach ($array as $v) {
                $in[$v[$id]] = $v[$name];
            }

            return $in;
        } else {
            return;
        }
    }

    public static function virgulaE($array) {
        $num = count($array);
        if ($num == 1) {
            return current($array);
        } elseif ($num == 2) {
            return implode(' e ', $array);
        } else {
            for ($c = 0; $c < $num; $c++) {
                if ($c < ($num - 2)) {
                    @$texto .= $array[$c] . ', ';
                } elseif ($c == ($num - 2)) {
                    @$texto .= $array[$c] . ' e ';
                } else {
                    @$texto .= $array[$c];
                }
            }
            return$texto;
        }
    }

    public static function distancia($origem, $destino) {
        if (!empty($origem) && !empty($destino)) {
            $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $origem . "&destinations=" . $destino . "&mode=walking&language=pt-BR&key=AIzaSyDgyrNYpi6MldsIok86VBzqoGCFKfyQJas";
            $maps = file_get_contents($url);
            $maps = json_decode($maps, true);
            $distancia = @$maps['rows'][0]['elements'][0]['distance']['text'];
            $tempo = @$maps['rows'][0]['elements'][0]['duration']['text'];

            return [$distancia, $tempo];
        } else {
            return;
        }
    }

    public static function serializeCorrector($data) {
        return preg_replace_callback('!s:(\d+):"(.*?)";!', function($m) {
            return 's:' . strlen($m[2]) . ':"' . $m[2] . '";';
        }, $data);
    }

    public static function pdfsecretaria2old($orientation = 'P', $mode = 'c', $format = 'A4', $default_font_size = 0, $default_font = '', $mgl = 15, $mgr = 15, $mgt = 35, $mgb = 20, $mgh = 9, $mgf = 9) {

        $header = '<table style="width: 100%; border: 1px solid">'
                . '<tr>'
                . '<td rowspan = "5">'
                . '<img style="width: 70px" src="' . HOME_URI . '/views/_images/brasao.jpg"/>'
                . '</td>'
                . '<td style="font-size: 16px">'.ucfirst(CLI_NOME).'</td>'
                . '<td rowspan = "5" style=" text-align: right">'
                . '<img style="width: 190px;" src="' . HOME_URI . '/views/_images/logo_relatorio.jpg"/>'
                . '</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 14px">SE - Secretaria de Educação</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 10px">'.CLI_END.'</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 10px">'.CLI_BAIRRO.' - '.CLI_CIDADE.' - '.CLI_UF.' CEP '.CLI_CEP.'<br />Fone '.CLI_FONE.'</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 10px">'.CLI_URL.' - Email: '.CLI_MAIL.'</td>'
                . '</tr>'
                . '</table>';

        $body = ob_get_contents();
        ob_end_clean();
        error_reporting(0);
        ini_set('display_errors', 0);
        //include( ABSPATH . "/app/mpdf/mpdf.php");
        //$mpdf = new mPDF($mode, $format, $default_font_size, $default_font, $mgl, $mgr, $mgt, $mgb, $mgh);

        require_once __DIR__ . '/../vendor/autoload.php';
        $config = [
            'tempDir' => __DIR__ . '/../tmp'
        ];
        $mpdf = new \Mpdf\Mpdf($config);


        $footer = "<div style=\"padding: 8px; background-color: #D4DF92;\" ><table width=\"1000\"><tr><td style=\" font-weight: bold;width: 200px\">".SISTEMA_NOME."</td><td style=\" text-align: center\">".ucfirst(CLI_CIDADE).", " . date("d") . " de " . data::mes(date("m")) . " de " . date("Y") . "</td><td  style=\"width: 300px\" align=\"right\">{PAGENO}/{nb}</td></tr></table></div>";
        $mpdf->SetHTMLHeader($header);
        $mpdf->SetHTMLFooter($footer);
        $css = file_get_contents(ABSPATH . "/views/_css/style.css");
        $mpdf->WriteHTML($css, 1);
        $css1 = file_get_contents(ABSPATH . "/views/_css/bootstrap-theme.css");
        $mpdf->WriteHTML($css1, 1);
        $css2 = file_get_contents(ABSPATH . "/views/_css/bootstrap.css");
        $mpdf->WriteHTML($css2, 1);
        if ($orientation == 'L' || $orientation == 'l') {
            $mpdf->AddPage('L', 'A4', 0, '', '', 15, 15, 58, 15, 20, 6);
        }
        $mpdf->WriteHTML($body);

        $mpdf->Output();
        exit;
    }

    public static function pdfsecretaria2($orientation = 'P', $mode = 'c', $format = 'A4', $default_font_size = 0, $default_font = '', $mgl = 15, $mgr = 15, $mgt = 16, $mgb = 20, $mgh = 9, $mgf = 9) {

        $header = '<table style="width: 100%; border: 1px solid">'
                . '<tr>'
                . '<td rowspan = "5">'
                . '<img style="width: 70px" src="' . HOME_URI . '/views/_images/brasao.jpg"/>'
                . '</td>'
                . '<td style="font-size: 16px"> '.CLI_NOME.'</td>'
                . '<td rowspan = "5" style=" text-align: right">'
                . '<img style="width: 190px;" src="' . HOME_URI . '/views/_images/logo_relatorio.jpg"/>'
                . '</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 14px">SE - Secretaria de Educação</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 10px">'.CLI_END.'</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 10px">'.CLI_BAIRRO.' - '.CLI_CIDADE.' - '.CLI_UF.' CEP '.CLI_CEP.'<br />Fone '.CLI_FONE.'</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 10px">'.CLI_URL.' - Email: '.CLI_MAIL.'</td>'
                . '</tr>'
                . '</table>';

        $body = ob_get_contents();
        ob_end_clean();
        error_reporting(0);
        ini_set('display_errors', 0);
        //include( ABSPATH . "/app/mpdf/mpdf.php");
        //$mpdf = new mPDF($mode, $format, $default_font_size, $default_font, $mgl, $mgr, $mgt, $mgb, $mgh);

        require_once __DIR__ . '/../vendor/autoload.php';
        $config = [
            'tempDir' => __DIR__ . '/../tmp'
        ];
        $mpdf = new \Mpdf\Mpdf($config);


        $footer = "<div style=\"padding: 8px; background-color: #D4DF92;\" ><table width=\"1000\"><tr><td style=\" font-weight: bold;width: 200px\">".SISTEMA_NOME."</td><td style=\" text-align: center\">".ucfirst(CLI_CIDADE).", " . date("d") . " de " . data::mes(date("m")) . " de " . date("Y") . "</td><td  style=\"width: 300px\" align=\"right\">{PAGENO}/{nb}</td></tr></table></div>";
        $mpdf->SetHTMLHeader($header);
        $mpdf->SetHTMLFooter($footer);
        $css = file_get_contents(ABSPATH . "/views/_css/style.css");
        $mpdf->WriteHTML($css, 1);
        $css1 = file_get_contents(ABSPATH . "/views/_css/bootstrap-theme.css");
        $mpdf->WriteHTML($css1, 1);
        $css2 = file_get_contents(ABSPATH . "/views/_css/bootstrap.css");
        $mpdf->WriteHTML($css2, 1);
        if ($orientation == 'L' || $orientation == 'l') {
            $mpdf->AddPage('L', 'A4', 0, '', '', 15, 15, 38, 15, 12, 6);
        } else {
            $mpdf->AddPage('P', 'A4', 0, '', '', 15, 15, 38, 15, 12, 6);
        }
        $mpdf->WriteHTML($body);

        $mpdf->Output();
        exit;
    }

    public static function pdfsecretaria3($orientation = 'P', $mode = 'c', $format = 'A4', $default_font_size = 0, $default_font = '', $mgl = 15, $mgr = 15, $mgt = 35, $mgb = 20, $mgh = 9, $mgf = 9) {

        $header = '<table style="width: 100%">'
                . '<tr>'
                . '<td rowspan = "5">'
                . '<img style="width: 70px" src="' . HOME_URI . '/views/_images/brasao.jpg"/>'
                . '</td>'
                . '<td style="font-size: 16px; padding-left: 10px">'.CLI_NOME.'</td>'
                . '<td rowspan = "5" style=" text-align: right">'
                . '<img style="width: 280px;" src="' . HOME_URI . '/views/_images/logose.png"/>'
                . '</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 14px; padding-left: 10px">SE - Secretaria de Educação</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 10px; padding-left: 10px">'.CLI_END.'</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 10px;padding-left: 10px">'.CLI_BAIRRO.' - '.CLI_CIDADE.' - '.CLI_UF.' CEP '.CLI_CEP.'<br />Fone '.CLI_FONE.'</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 10px; padding-left: 10px ">'.CLI_URL.'</td>'
                . '</tr>'
                . '</table>';

        $body = ob_get_contents();
        ob_end_clean();
        error_reporting(0);
        ini_set('display_errors', 0);
        //include( ABSPATH . "/app/mpdf/mpdf.php");
        //$mpdf = new mPDF($mode, $format, $default_font_size, $default_font, $mgl, $mgr, $mgt, $mgb, $mgh);

        require_once __DIR__ . '/../vendor/autoload.php';
        $config = [
            'tempDir' => __DIR__ . '/../tmp'
        ];
        $mpdf = new \Mpdf\Mpdf($config);

        $footer = "<div style=\"padding: 8px; background-color: #D4DF92;\" ><table width=\"1000\"><tr><td style=\" font-weight: bold;width: 200px\">".SISTEMA_NOME."</td><td style=\" text-align: center\">".ucfirst(CLI_CIDADE).", " . date("d") . " de " . data::mes(date("m")) . " de " . date("Y") . "</td><td  style=\"width: 300px\" align=\"right\">{PAGENO}/{nb}</td></tr></table></div>";
        $mpdf->SetHTMLHeader($header);
        $mpdf->SetHTMLFooter($footer);
        $css = file_get_contents(ABSPATH . "/views/_css/style.css");
        $mpdf->WriteHTML($css, 1);
        $css1 = file_get_contents(ABSPATH . "/views/_css/bootstrap-theme.css");
        $mpdf->WriteHTML($css1, 1);
        $css2 = file_get_contents(ABSPATH . "/views/_css/bootstrap.css");
        $mpdf->WriteHTML($css2, 1);
        if ($orientation == 'L' || $orientation == 'l') {
            $mpdf->AddPage('L', 'A4', 0, '', '', 15, 15, 58, 15, 20, 6);
        }
        $mpdf->WriteHTML($body);

        $mpdf->Output();
        exit;
    }

    public static function pdf2($orientation = 'P', $mode = 'c', $format = 'A4', $default_font_size = 0, $default_font = '', $mgl = 15, $mgr = 15, $mgt = 16, $mgb = 20, $mgh = 9, $mgf = 9) {
        $body = ob_get_contents();
        ob_end_clean();
        error_reporting(0);
        ini_set('display_errors', 0);
        //include( ABSPATH . "/app/mpdf/mpdf.php");
        //$mpdf = new mPDF($mode, $format, $default_font_size, $default_font, $mgl, $mgr, $mgt, $mgb, $mgh);

        require_once __DIR__ . '/../vendor/autoload.php';
        $config = [
            'tempDir' => __DIR__ . '/../tmp'
        ];
        $mpdf = new \Mpdf\Mpdf($config);
        $mpdf->SetHTMLFooter($footer);
        $css = file_get_contents(ABSPATH . "/views/_css/style.css");
        $mpdf->WriteHTML($css, 1);
        $css1 = file_get_contents(ABSPATH . "/views/_css/bootstrap-theme.css");
        $mpdf->WriteHTML($css1, 1);
        $css2 = file_get_contents(ABSPATH . "/views/_css/bootstrap.css");
        $mpdf->WriteHTML($css2, 1);
        if ($orientation == 'L' || $orientation == 'l') {
            $mpdf->AddPage('L', 'A4', 0, '', '', 15, 15, 58, 15, 20, 6);
        }
        $mpdf->WriteHTML($body);

        $mpdf->Output();
        exit;
    }
    
    public static function pdfSecretariaE($orientation = 'P', $mode = 'c', $format = 'A4', $default_font_size = 0, $default_font = '', $mgl = 15, $mgr = 15, $mgt = 35, $mgb = 20, $mgh = 50, $mgf = 9) {

        $header = '<table style="width: 100%; border: 1px solid">'
                . '<tr>'
                . '<td rowspan = "5">'
                . '<img style="width: 70px" src="' . HOME_URI . '/views/_images/brasao.jpg"/>'
                . '</td>'
                . '<td style="font-size: 18px">'.CLI_NOME.'</td>'
                . '<td rowspan = "5" style=" text-align: right">'
                . '<img style="width: 210px;" src="' . HOME_URI . '/views/_images/logo_relatorio.jpg"/>'
                . '</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 16px">SE - Secretaria de Educação</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 12px">'.CLI_END.'</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 12px">'.CLI_BAIRRO.' - '.CLI_CIDADE.' - '.CLI_UF.' CEP '.CLI_CEP.' Fone '.CLI_FONE.'</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 12px">'.CLI_URL.' - Email: '.CLI_MAIL.'</td>'
                . '</tr>'
                . '</table>';

        $body = ob_get_contents();
        ob_end_clean();
        
        require_once __DIR__ . '/../vendor/autoload.php';
        $config = [
            'tempDir' => __DIR__ . '/../tmp'
        ];
        $mpdf = new \Mpdf\Mpdf($config);

        $footer = "<div style=\"padding: 8px; background-color: #D4DF92;\" ><table width=\"1000\"><tr><td style=\" font-weight: bold;width: 200px\">".SISTEMA_NOME."</td><td style=\" text-align: center\">".CLI_CIDADE.", " . date("d") . " de " . data::mes(date("m")) . " de " . date("Y") . "</td><td  style=\"width: 300px\" align=\"right\">{PAGENO}/{nb}</td></tr></table></div>";
        $mpdf->SetHTMLHeader($header);
        $mpdf->SetHTMLFooter($footer);
        $css = file_get_contents(ABSPATH . "/views/_css/style.css");
        $mpdf->WriteHTML($css, 1);
        $css1 = file_get_contents(ABSPATH . "/views/_css/bootstrap-theme.css");
        $mpdf->WriteHTML($css1, 1);
        $css2 = file_get_contents(ABSPATH . "/views/_css/bootstrap.css");
        $mpdf->WriteHTML($css2, 1);
        
        if ($orientation == 'L' || $orientation == 'l') {
            $mpdf->AddPage('L', 'A4', 0, '', '', 15, 15, 38, 18, 12, 10);
        } else {
            $mpdf->AddPage('P', 'A4', 0, '', '', 15, 15, 38, 15, 20, 6);
        }
        $mpdf->WriteHTML($body);

        $mpdf->Output();
        exit;
    }
}
