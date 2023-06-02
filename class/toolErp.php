<?php

class toolErp {

    public static $secret_key = 'c756deF012tj811k0l3';
    public static $secret_iv = 'v867efG123uk922l1m4';
    public static $encrypt_method = "AES-256-CBC";

    /**
     * cria um alert no javaScript
     * @param type $param
     */
    public const MAPS = 'tomtom'; //google

    public static function alert($param) {
        ?>
        <script>
            alert("<?php echo $param ?>");
        </script>
        <?php
    }

    public static function alertModal($param, $class = null, $alert = NULL) {
        if (empty($class)) {
            $class = 'modal-lg';
        }
        toolErp::modalInicio( 1, $class, 'alertModal');
        ?>
        <div style="text-align: center;font-size: 18px">
            <?php echo $param . '<br /><br />' ?>
        </div>
        <?php
        toolErp::modalFim();
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

    public static function email() {
        return @$_SESSION['userdata']['email'];
    }

    public static function n_pessoa($id_pessoa = NULL) {
        if (empty($id_pessoa)) {
            return @ucwords(strtolower(@$_SESSION['userdata']['n_pessoa']));
        } else {
            @$n_pessoa = sql::get('pessoa', 'n_pessoa', ['id_pessoa' => $id_pessoa], 'fetch')['n_pessoa'];
            return @ucwords(strtolower($n_pessoa));
        }
    }

    public static function sexo_pessoa($id_pessoa = NULL) {
        if (empty($id_pessoa)) {
            return @$_SESSION['userdata']['sexo'];
        } else {
            @$sexo = sql::get('pessoa', 'sexo', ['id_pessoa' => $id_pessoa], 'fetch')['sexo'];
            return $sexo;
        }
    }

    public static function session($param = NULL) {
        if (!empty($_SESSION['userdata'])) {
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
        } else {
            toolErp::redirect('/', []);
        }
    }

    public static function id_nilvel() {
        return @$_SESSION['userdata']['id_nivel'];
    }

    public static function cie($id_inst = NULL) {
        $id_inst = toolErp::id_inst($id_inst);
        return gt_escolas::dados(NULL, 'cie')['cie'];
    }

    /**
     * 1 = sim senão é nao
     * @param type $valor
     * @return type
     */
    public static function simnao($valor) {
        return $valor == 1 ? 'Sim' : 'Não';
    }

    /**
     * <input type="submit" onclick=" $('#myModal').modal('show');$('.form-class').val('')" value="" />
     * @param type $ativado
     * @param type $class 'modal-xl', 'modal-lg', 'modal-sm', 'modal-fullscreen' padrão = modal-xl
     * @param type $nome
     * @param type $titulo
     */
    public static function modalInicio($ativado = 0, $class = NULL, $nome = NULL, $titulo = NULL) {
        if (empty($nome)) {
            $nome = 'myModal';
        }
        $classSet = ['modal-xl', 'modal-lg', 'modal-sm', 'modal-fullscreen'];
        if (!in_array($class, $classSet)) {
            $class = 'modal-xl';
        }
        if ($ativado != 0) {
            ?>
            <script>
                $(document).ready(function () {
                    $('#<?= $nome ?>').modal('show');
                });
            </script>
            <?php
        }
        ?>
        <div class="modal fade" id="<?= $nome ?>" tabindex="-1" aria-labelledby="<?= $nome ?>Label" aria-hidden="true">
            <div class="modal-dialog <?= $class ?>">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="<?= $nome ?>Label"><?= $titulo ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
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

    /**
     *     
      $abas[1] = [ 'nome' => "", 'ativo' => 1, 'hidden' => 'array', 'link' => "", ];
     * @param type $abas
     */
    public static function abas($abas, $btn = NULL, $aba = NULL) {
        if (empty($btn)) {
            $btn = ["outline-light", "info", "outline-info"];
        }
        if (empty($aba)) {
            $aba = 'activeNav';
        }
        include ABSPATH . '/includes/views/tab/abas.php';

        return $activeNav;
    }

    public static function title($title) {
        include ABSPATH . '/includes/views/title.php';
    }

    /**
     * inclui o caminho para gerar formulario
     * @param type $form array [array, fields, titulo=null]
     */
    public static function relatSimples($form) {
        require ABSPATH . '/includes/views/report/simples.php';
    }

    public static function relatSimples1($form) {
        require ABSPATH . '/includes/views/report/simples_1.php';
    }

    /**
     * inclui o caminho para gerar formulario com Apagar e Acessar
     *  criar variáveis "apagar" e "acessar" no $form['fields']
     * Atenção!!! Voce só pode ter 1 "id_" no array
     * @param type $form array [array, fields, titulo=null]
     */
    public static function relatForms($form, $table = NULL, $hidden = NULL, $location = NULL, $target = NULL, $msg = NULL) {
        require ABSPATH . '/includes/views/report/forms.php';
    }

    public static function relatVert($form) {
        require ABSPATH . '/includes/views/report/relatVert.php';
    }

    /**
     * retorna Feminino para sexo = F e Masculino para sexo = M
     * @param type $sigla
     * @return string
     */
    public static function sexo($sigla = NULL) {
        $sexo = ['F' => 'Feminino', 'M' => 'Masculino'];
        if (empty($sigla)) {
            return $sexo;
        } else {
            return $sexo[$sigla];
        }
    }

    public static function sexoSet($sigla = NULL) {
        $sexo = ['F' => 'Feminino', 'M' => 'Masculino'];
        if (empty($sigla)) {
            return;
        } else {
            return $sexo[$sigla];
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

    public static function redirect($url, $hidden = null) {
        ?>
        <form name="voltar1544" method="POST" action="<?php echo $url ?>">
            <?php echo form::hidden($hidden) ?>
        </form>
        <script>
            document.voltar1544.submit();
        </script>
        <?php
    }

    /**
     * 
     * @param type $array extrai um array com id e nome Obs: só pode ter u id_ e um n_
     */
    public static function arrayIdName($array, $key = NULL, $value = NULL) {
        if (!is_array(current($array))) {
            $array = [0 => $array];
        }
        if (empty($value) || empty($key)) {
            foreach (current($array) as $k => $v) {
                if (substr($k, 0, 3) == 'id_' && empty($key)) {
                    $key = $k;
                } elseif (substr($k, 0, 2) == 'n_' && empty($value)) {
                    $value = $k;
                }
            }
        }
        if (!empty($value)) {
            foreach ($array as $v) {
                $na[@$v[$key]] = $v[$value];
            }
        }

        return $na;
    }

##############################################################################################

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
                    $name .= ' ' . substr(toolErp::removeAcentos($t[$c]), 0, 1) . '.';
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
                    $name .= ' ' . substr($t[$c], 0, 1) . '.';
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
    public static function relatTable($form) {
        require ABSPATH . '/views/relat/table.php';
    }

    /**
     * devolve bom dia, boa tarde ou boa noite
     * @return string
     */
    public static function bom() {
        if (@date('H') <= 12) {
            $bom = 'Bom dia';
        } elseif (@date('H') <= 18) {
            $bom = 'Boa tarde';
        } else {
            $bom = 'Boa noite';
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
     * @param type $string
     * @return type
     */
    public static function encrypt( $string ) {
        $output = false;
        $key = hash( 'sha256', self::$secret_key );
        $iv = substr( hash( 'sha256', self::$secret_iv ), 0, 16 );

        $output = base64_encode( openssl_encrypt( $string, self::$encrypt_method, $key, 0, $iv ) );

        return $output;
    }

    /**
     * descriptografa um valor Atenção, se token = NULL só descriptografa na mesma sessão depois o dado é inutilizado
     * @param type $string
     * @return type
     */
    public static function decrypt( $string ) {
        $output = false;
        $key = hash( 'sha256', self::$secret_key );
        $iv = substr( hash( 'sha256', self::$secret_iv ), 0, 16 );

        $output = openssl_decrypt( base64_decode( $string ), self::$encrypt_method, $key, 0, $iv );

        return $output;
    }

    /**
     * redericiona para a página home
     */
    public static function sair() {
        echo '<meta http-equiv="Refresh" content="0; url=' . HOME_URI . '">';
        echo '<script type="text/javascript">window.location.href = "' . HOME_URI . '";</script>';
    }

    /**
     * devolve o Nome da instancia logada
     * @return type
     */
    public static function n_inst() {
        return @$_SESSION['userdata']['n_inst'];
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

    /**
     * devolve o id da instancia logada se for uma escola
     * @return type
     */
    public static function id_instEscola() {
        $id_inst = @$_SESSION['userdata']['id_inst'];
        $sql = "SELECT * FROM `gt_escola` WHERE `fk_id_inst` = $id_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($array)) {
            return $id_inst;
        } else {
            return;
        }
    }

    /**
     * devolve o sistema logada
     * @return type
     */
    public static function id_sistema() {
        return @$_SESSION['userdata']['id_sistema'];
    }

    /**
     * devolve o nível logada
     * @return type
     */
    public static function n_nivel() {
        return @$_SESSION['userdata']['n_nivel'];
    }

    /**
     * devolve o sistema logado
     * @return type
     */
    public static function n_sistema() {
        return @$_SESSION['userdata']['n_sistema'];
    }

    /**
     * devolve o arquivo do sistema logado
     * @return type
     */
    public static function arquivo() {
        return @$_SESSION['userdata']['arquivo'];
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
            1 => '#4B0082',
            2 => '#4682B4',
            3 => '#FF4500',
            4 => '#006400',
            5 => '#8B4513',
            6 => '#2F4F4F',
            7 => '#FF0000',
            8 => '#7B68EE',
            9 => '#2F4F4F',
            10 => '#7B68EE',
            11 => '#4B0082',
            12 => '#4682B4',
            13 => '#FF4500',
            14 => '#006400',
            15 => '#8B4513',
            16 => '#2F4F4F',
            17 => '#FF0000'
        ];
        if (empty($cor)) {
            return $cores;
        } else {
            return $cores[$cor];
        }
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

    public static function distanciaGoogle($origem, $destino) {
        if (!empty($origem) && !empty($destino)) {
            $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $origem . "&destinations=" . $destino . "&mode=walking&language=pt-BR&key=AIzaSyAhkvKXeieQcWcLx7n8MFFEWH_r7fvWQpU";
            $maps = file_get_contents($url);
            $maps = json_decode($maps, true);
            @$distancia = $maps['rows'][0]['elements'][0]['distance']['text'];
            @$tempo = $maps['rows'][0]['elements'][0]['duration']['text'];

            return [$distancia, $tempo];
        } else {
            return;
        }
    }
    public static function arrSimples($array_, $value) {
        $a = [];
        foreach ($array_ as $v => $k) {
            $a[] = $k[$value];
        }
        return !empty($a) ? implode($a, ',') : '';
    }
    public static function virgulaE($array_, $fixo = NULL) {

        if (!empty($array_)) {
            foreach ($array_ as $v) {
                if (!empty($v)) {
                    $array[] = $v;
                }
            }
            if (!empty($array)) {
                $num = count($array);
                if ($num == 1) {
                    return current($array) . $fixo;
                } elseif ($num == 2) {
                    return implode($fixo . ' e ', $array) . $fixo;
                } else {
                    for ($c = 0; $c < $num; $c++) {
                        if ($c < ($num - 2)) {
                            @$texto .= $array[$c] . $fixo . ', ';
                        } elseif ($c == ($num - 2)) {
                            @$texto .= $array[$c] . $fixo . ' e ';
                        } else {
                            @$texto .= $array[$c] . $fixo;
                        }
                    }
                    return $texto;
                }
            }
        }
    }

    public static function varGet($array) {
        foreach ($array as $k => $v) {
            $v = urlencode($v);
            $g[] = "$k=$v";
        }
        $g = implode('&', $g);

        return $g;
    }

    public static function codigoBarra($numero, $largura = 5, $altura = 50) {
        $fino = $largura;
        $largo = $largura * 3;

        $barcodes[0] = '00110';
        $barcodes[1] = '10001';
        $barcodes[2] = '01001';
        $barcodes[3] = '11000';
        $barcodes[4] = '00101';
        $barcodes[5] = '10100';
        $barcodes[6] = '01100';
        $barcodes[7] = '00011';
        $barcodes[8] = '10010';
        $barcodes[9] = '01010';

        for ($f1 = 9; $f1 >= 0; $f1--) {
            for ($f2 = 9; $f2 >= 0; $f2--) {
                $f = ($f1 * 10) + $f2;
                $texto = '';
                for ($i = 1; $i < 6; $i++) {
                    $texto .= substr($barcodes[$f1], ($i - 1), 1) . substr($barcodes[$f2], ($i - 1), 1);
                }
                $barcodes[$f] = $texto;
            }
        }

        $bcode = '<img src="' . HOME_URI . '/includes/images/barcode/p.gif" width="' . $fino . '" height="' . $altura . '" border="0" />';
        $bcode .= '<img src="' . HOME_URI . '/includes/images/barcode/b.gif" width="' . $fino . '" height="' . $altura . '" border="0" />';
        $bcode .= '<img src="' . HOME_URI . '/includes/images/barcode/p.gif" width="' . $fino . '" height="' . $altura . '" border="0" />';
        $bcode .= '<img src="' . HOME_URI . '/includes/images/barcode/b.gif" width="' . $fino . '" height="' . $altura . '" border="0" />';

        $bcode .= '<img ';

        $texto = $numero;

        if ((strlen($texto) % 2) <> 0) {
            $texto = '0' . $texto;
        }

        while (strlen($texto) > 0) {
            $i = round(substr($texto, 0, 2));
            $texto = substr($texto, strlen($texto) - (strlen($texto) - 2), (strlen($texto) - 2));

            if (isset($barcodes[$i])) {
                $f = $barcodes[$i];
            }

            for ($i = 1; $i < 11; $i += 2) {
                if (substr($f, ($i - 1), 1) == '0') {
                    $f1 = $fino;
                } else {
                    $f1 = $largo;
                }

                $bcode .= 'src="' . HOME_URI . '/includes/images/barcode/p.gif" width="' . $f1 . '" height="' . $altura . '" border="0">';
                $bcode .= '<img ';

                if (substr($f, $i, 1) == '0') {
                    $f2 = $fino;
                } else {
                    $f2 = $largo;
                }

                $bcode .= 'src="' . HOME_URI . '/includes/images/barcode/b.gif" width="' . $f2 . '" height="' . $altura . '" border="0">';
                $bcode .= '<img ';
            }
        }
        $bcode .= 'src="' . HOME_URI . '/includes/images/barcode/p.gif" width="' . $largo . '" height="' . $altura . '" border="0" />';
        $bcode .= '<img src="' . HOME_URI . '/includes/images/barcode/b.gif" width="' . $fino . '" height="' . $altura . '" border="0" />';
        $bcode .= '<img src="' . HOME_URI . '/includes/images/barcode/p.gif" width="1" height="' . $altura . '" border="0" />';


        return $bcode;
    }

    /**
     * 
     * @param type $inputs extract(toolErp::postFilter(['input', 'input']));
     * @param type $type 1 == int; 2==string
     * @param type $method POST, GET, REQUEST
     */
    public static function postFilter($inputs, $type = 1) {
        foreach ($inputs as $v) {
            if ($type == 1) {
                $array[$v] = filter_input(INPUT_POST, $v, FILTER_SANITIZE_NUMBER_INT);
            } elseif ($type == 2) {
                $array[$v] = filter_input(INPUT_POST, $v, FILTER_UNSAFE_RAW);
            }
        }

        return $array;
    }

    public static function coresBasicas() {
        $cor = [
            1 => 'Azul',
            2 => 'Vermelho',
            3 => 'Marrom',
            4 => 'Amarelo',
            5 => 'Verde',
            6 => 'Laranja',
        ];

        return $cor;
    }

    public static function getHttp($hostname, $filepath = "", $postData = "", $getData = "", $port = 80, $timeout = 30, $userAgent = "", $bytes = 1024) {
        $fp = fsockopen($hostname, $port, $errno, $errstr, $timeout);

        if (!$fp) {
            fclose($fp);
            return "$errstr ($errno)";
        } else {
            (strlen($postData) > 0) ? $method = "POST" : $method = "GET";

            fwrite($fp, "$method $filepath$getData HTTP/1.1\r\n");

            fwrite($fp, "Host: $hostname\r\n");

            if (strlen($postData) > 0) {
                fwrite($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
                fwrite($fp, "Content-Length: " . strlen($postData) . "\r\n");
            }
            if (strlen($userAgent) > 0)
                fwrite($fp, "User-agent: $userAgent\r\n");

            fwrite($fp, "Connection: close\r\n\r\n");
            fwrite($fp, $postData);

            $txt = "";
            while (!feof($fp)) {
                $txt .= fgetss($fp, $bytes);
            }

            fclose($fp);
            return $txt;
        }
    }

    public static function jsonSanifica($json) {
        $json = trim(explode('charset=UTF-8\n', $json)[1]);
        // $json = trim(substr($json, stripos($json, '{')));
        //$json = trim(substr($json, stripos($json, '{')));
        //  $json = trim(substr($json, 0, strrpos($json, '}') + 1));
        return $json;
    }

    public static function mobile() {
        @$screenWidth = @$_SESSION['userdata']['screenWidth'];
        if ($screenWidth < 980) {
            return true;
        }
    }

    /**
     * 
     * @param type $cpf
     * @return int se o CPF estiver correto, devolve o CPF sem traço ou barra
     */
    public static function cpfValida($cpf) {

        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return 0;
        }
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return 0;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return 0;
            }
        }
        return $cpf;
    }

    public static function cpfNormaliza($cpf) {
        $cpfN = '';
        foreach (str_split($cpf) as $v) {
            if (is_numeric($v)) {
                $cpfN .= $v;
            }
        }
        return $cpfN;
    }

    public static function classAlert($cor) {
        if ($cor > 7) {
            $cor = $cor % 7;
        }
        $alert = [
            1 => 'primary',
            2 => 'secondary',
            3 => 'success',
            4 => 'danger',
            5 => 'warning',
            6 => 'info',
            7 => 'dark',
        ];

        if (!empty($alert[$cor])) {
            return $alert[$cor];
        }
    }

    public static function paginaRetorna($url) {
        $_SESSION['UrlReturnPage'] = $url;
    }

    public static function divAlert($classe, $mensagem) {
        ?>
        <div class="alert alert-<?= $classe ?>" style="padding-top:  10px; padding-bottom: 0">
            <div class="row" style="padding-bottom: 15px;">
                <div class="col" style="font-weight: bold; text-align: center;">
                    <?= $mensagem ?>
                </div>
            </div>
        </div>
        <?php
    }

    public static function valorPorExtenso($valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false)
    {
        //$valor = self::removerFormatacaoNumero( $valor );
        $singular = null;
        $plural = null;
        $rt = null;

        if ( $bolExibirMoeda )
        {
            $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
        }
        else
        {
            $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("", "", "mil", "milhões", "bilhões", "trilhões","quatrilhões");
        }
        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezessete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
 
        if ( $bolPalavraFeminina )
        {
            if ($valor == 1) 
            {
                $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
            }
            else 
            {
                $u = array("", "um", "duas", "três", "quatro", "cinco", "seis","sete", "oito", "nove");
            }

            $c = array("", "cem", "duzentas", "trezentas", "quatrocentas","quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");
        }

        $z = 0;

        $valor = number_format( $valor, 2, ".", "." );
        $inteiro = explode( ".", $valor );

        for ( $i = 0; $i < count( $inteiro ); $i++ ) 
        {
            for ( $ii = mb_strlen( $inteiro[$i] ); $ii < 3; $ii++ )
            {
                $inteiro[$i] = "0" . $inteiro[$i];
            }
        }

        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $rt = null;
        $fim = count( $inteiro ) - ($inteiro[count( $inteiro ) - 1] > 0 ? 1 : 2);
        for ( $i = 0; $i < count( $inteiro ); $i++ )
        {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
            $t = count( $inteiro ) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ( $valor == "000")
                $z++;
            elseif ( $z > 0 )
                $z--;

            if ( ($t == 1) && ($z > 0) && ($inteiro[0] > 0) )
                $r .= ( ($z > 1) ? " de " : "") . $plural[$t];

            if ( $r ) {
                $rt = $rt . (
                    (($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) 
                    ? ( ($i < $fim) ? ", " : " e ") 
                    : " "
                ) . $r;
            }
        }

        $rt = mb_substr( $rt, 1 );
        return($rt ? trim( $rt ) : "zero");
    }

    public static function tooltip($mensagem, $width=null){?>
        <div class="tooltipB">
            <span class="tooltipSpan user-select-none btn-outline-warning rounded-circle">&#x2139;</span>
            <span class="tooltipBtext" style="min-width: <?= $width ?>;"><?= $mensagem ?></span>
        </div>
    <?php
    }

    public static function cabecalhoSimples($n_inst = null){?>
        <table style="width: 100%" cellspacing=0 cellpadding=2>
            <tr>
                <td  style="width: 10%; text-align: center">
                    <img style="width: 100px" src="<?php echo HOME_URI ?>/views/_images/brasao.jpg"/>
                </td>
                <td style="padding-top: 5px; width: 80%; text-align: center">
                    <div style="font-size: 22px; font-weight: bold">
                        <?php echo CLI_NOME ?>
                        <br />
                        SE - Secretaria de Educação
                    </div>
                     <?php
                    if (!empty($n_inst)) {?>
                       <div style="font-size: 18px">
                            <?= $n_inst ?>
                        </div> 
                        <?php
                    }?>
                </td>
            </tr>  
        </table>
    <?php
    }

    public static function encodeURI($uri)
    {
        return preg_replace_callback("{[^0-9a-z_.!~*'();,/?:@&=+$#-]}i", function ($m) {
            return sprintf('%%%02X', ord($m[0]));
        }, $uri);
    }

    public static function geraMapaGoogle(string $latLongOrigem, string $latLongDestino, $width = "100%", $height = "500px")
    {
        ?>
        <iframe
            frameborder="0" style="border:0; width: <?= $width ?>; height: <?= $height ?>"
            src="https://maps.google.com/maps?width=100%&height=300&hl=pt&q=<?= toolErp::encodeURI($latLongOrigem) ?>&center=<?= $latLongOrigem ?>&t=&z=14&ie=UTF8&iwloc=B&output=embed&mode=walking" allowfullscreen="">
        </iframe>
        <?php
    }

    public static function geraMapaRouteGoogle(string $latLongOrigem, string $latLongDestino, $width = "100%", $height = "500px")
    {
        ?>
        <iframe
            frameborder="0" style="border:0; width: <?= $width ?>; height: <?= $height ?>"
            src="https://maps.google.com/maps?width=100%&height=300&hl=pt&q=<?= toolErp::encodeURI($latLongOrigem) ?>&center=<?= $latLongOrigem ?>&t=&z=14&ie=UTF8&iwloc=B&output=embed&mode=walking" allowfullscreen="">
        </iframe>
        <?php
    }

    // identificar a geolocalização by endereço
    public static function getGeoByEndereco($logradouro = null, $numero = null, $bairro = null, $cep = null, $cidade = null, $uf = null)
    {
        try {
            $end = "";

            if (!empty($logradouro)) $end .= (!empty($end) ? "," : "") . $logradouro;
            if (!empty($numero))     $end .= (!empty($end) ? "," : "") . $numero;
            if (!empty($bairro))     $end .= (!empty($end) ? "," : "") . $bairro;
            if (!empty($cep))        $end .= (!empty($end) ? "," : "") . $cep;
            if (!empty($cidade))     $end .= (!empty($end) ? "," : "") . $cidade;
            if (!empty($uf))         $end .= (!empty($end) ? "," : "") . $uf;

            if (empty($end)) {
                throw new Exception();
            }

            $url = "https://api.tomtom.com/search/2/search/". self::encodeURI($end).".json?key=". API_TOMTOM;
            $maps = file_get_contents($url);
            $maps = json_decode($maps, true);
            if (empty($maps)){
                throw new Exception();
            }

            if ( !isset($maps['results'][0])){
                throw new Exception();
            }

            $lat = $maps['results'][0]['position']['lat'];
            $long = $maps['results'][0]['position']['lon'];

            return [
                'lat' => $lat,
                'long' => $long,
            ];

        } catch (Exception $e){
            return [
                'lat' => 0,
                'long' => 0,
            ];
        }
    }

    /**
     * gera mapa SEM rotas com mutiplos pontos
     * @param array $location
     *          [ ['lat', 'long'], ['lat', 'long'], ...]
     */
    public static function geraMapa(array $location)
    {
        if (empty($location)) {
            return;
        }

        if (!isset($location[0]['lat'])) {
            return;
        }

        if (!isset($location[0]['long'])) {
            return;
        }

        $centerLoc = $location[0]['long'] .",". $location[0]['lat'];
        ?>
            <link rel='stylesheet' type='text/css' href='<?= HOME_URI ?>/includes/css/maps.css'>
            <script src="<?= HOME_URI ?>/includes/js/maps-web.min.js"></script>
            <script>(function(){ window.SS = window.SS || {}; SS.Require = function (callback){ if (typeof callback === 'function') { if (window.SS && SS.EventTrack) { callback(); } else { var siteSpect = document.getElementById('siteSpectLibraries'); var head = document.getElementsByTagName('head')[0]; if (siteSpect === null && typeof head !== 'undefined') { siteSpect = document.createElement('script'); siteSpect.type = 'text/javascript'; siteSpect.src = '/__ssobj/core.js+ssdomvar.js+generic-adapter.js';siteSpect.async = true; siteSpect.id = 'siteSpectLibraries'; head.appendChild(siteSpect); } if (window.addEventListener){ siteSpect.addEventListener('load', callback, false); } else { siteSpect.attachEvent('onload', callback, false); } } } };})(); </script> </head>
            <div id="map" style="width: 100%; height: 100%;"></div>
            <script>
                //var bounds = [[<?= "latLongOrigem" ?>], [<?= "latLongDestino" ?>]];
                var m = tt.map({
                    key: "<?= API_TOMTOM ?>",
                    container: "map",
                    zoom: 12,
                    center: [<?= $centerLoc ?>]
                });

                let bounds = new tt.LngLatBounds();
                let locations = new Array;

                <?php foreach ($location as $k => $loc) { ?>

                    <?php if (!empty($loc['img'])) { ?>
                        let icon<?= $k ?> = document.createElement('div');
                        icon<?= $k ?>.className = 'marker-icon';
                        icon<?= $k ?>.style.backgroundImage = 'url(<?= $loc['img'] ?>)';
                    
                        let marker<?= $k ?> = new tt.Marker({element: icon<?= $k ?>});
                    <?php } else { ?>
                        let marker<?= $k ?> = new tt.Marker();
                    <?php } ?>

                    marker<?= $k ?>.setLngLat([<?= $loc['long'] .','. $loc['lat'] ?>]).addTo(m);
                    locations.push(marker<?= $k ?>.getLngLat());
                    bounds.extend(tt.LngLat.convert(marker<?= $k ?>.getLngLat()));

                <?php } ?>

                m.fitBounds(bounds, {padding: 80});
           </script>
        <?php
    }

    /**
     * gera mapa COM rotas com mutiplos pontos
     * @param array $location
     *          [ ['lat', 'long'], ['lat', 'long'], ...]
     */
    public static function geraMapaRoute(array $location)
    {
        if (empty($location)) {
            return;
        }

        if (!isset($location[0]['lat'])) {
            return;
        }

        if (!isset($location[0]['long'])) {
            return;
        }

        $centerLoc = $location[0]['long'] .",". $location[0]['lat'];
        // $latLongOrigem = $longOrigem .','. $latOrigem;
        // $latLongDestino = $longDestino .','. $latDestino;
        ?>
            <link rel='stylesheet' type='text/css' href='<?= HOME_URI ?>/includes/css/maps.css'>
            <script src="<?= HOME_URI ?>/includes/js/maps-web.min.js"></script>
            <script src="<?= HOME_URI ?>/includes/js/maps-services-web.min.js"></script>
            <script>(function(){ window.SS = window.SS || {}; SS.Require = function (callback){ if (typeof callback === 'function') { if (window.SS && SS.EventTrack) { callback(); } else { var siteSpect = document.getElementById('siteSpectLibraries'); var head = document.getElementsByTagName('head')[0]; if (siteSpect === null && typeof head !== 'undefined') { siteSpect = document.createElement('script'); siteSpect.type = 'text/javascript'; siteSpect.src = '/__ssobj/core.js+ssdomvar.js+generic-adapter.js';siteSpect.async = true; siteSpect.id = 'siteSpectLibraries'; head.appendChild(siteSpect); } if (window.addEventListener){ siteSpect.addEventListener('load', callback, false); } else { siteSpect.attachEvent('onload', callback, false); } } } };})(); </script> </head>
            <div id="map" style="width: 100%; height: 100%;"></div>
            <script>
                var m = tt.map({
                    key: "<?= API_TOMTOM ?>",
                    container: "map",
                    zoom: 12,
                    center: [<?= $centerLoc ?>]
                });

                let locations = new Array;

                <?php foreach ($location as $k => $loc) { ?>

                    <?php if (!empty($loc['img'])) { ?>
                        let icon<?= $k ?> = document.createElement('div');
                        icon<?= $k ?>.className = 'marker-icon';
                        icon<?= $k ?>.style.backgroundImage = 'url(<?= $loc['img'] ?>)';
                    
                        let marker<?= $k ?> = new tt.Marker({element: icon<?= $k ?>});
                    <?php } else { ?>
                        let marker<?= $k ?> = new tt.Marker();
                    <?php } ?>

                    marker<?= $k ?>.setLngLat([<?= $loc['long'] .','. $loc['lat'] ?>]).addTo(m);
                    locations.push(marker<?= $k ?>.getLngLat());

                <?php } ?>

                var serviceMap = tt.services.calculateRoute({
                  key: "<?= API_TOMTOM ?>",
                  locations: locations,
                  travelMode: 'pedestrian'
                }).then(function(routeData) {
                    var r = routeData.toGeoJson();

                    var color = 'blue';
                    route = m.addLayer({
                        id: color,
                        type: 'line',
                        source: {
                            type: 'geojson',
                            data: r,
                        },
                        paint: {
                            'line-color': color,
                            'line-width': 6,
                            'line-opacity': 0.3
                        }
                    });

                    var bounds = new tt.LngLatBounds();
                    r.features[0].geometry.coordinates.forEach(function(point) {
                        if (Array.isArray(point[0])) {
                            point.forEach(function(pt) {
                                bounds.extend(tt.LngLat.convert(pt));
                            });
                        } else {
                            bounds.extend(tt.LngLat.convert(point));
                        }
                    });
                    m.fitBounds(bounds, {padding: 80});
                });
           </script>
        <?php
    }

    /**
     * gera mapa SEM rotas com mutiplos pontos
     * @param array $location
     *          [ ['lat', 'long'], ['lat', 'long'], ...]
     */
    public static function geraMapaCalor(array $location, $data = [])
    {
        if (empty($location)) {
            return;
        }

        if (!isset($location[0]['lat'])) {
            return;
        }

        if (!isset($location[0]['long'])) {
            return;
        }

        $centerLoc = $location[0]['long'] .",". $location[0]['lat'];
        ?>
            <link rel='stylesheet' type='text/css' href='<?= HOME_URI ?>/includes/css/maps.css'>
            <script src="<?= HOME_URI ?>/includes/js/maps-web.min.js"></script>
            <script>(function(){ window.SS = window.SS || {}; SS.Require = function (callback){ if (typeof callback === 'function') { if (window.SS && SS.EventTrack) { callback(); } else { var siteSpect = document.getElementById('siteSpectLibraries'); var head = document.getElementsByTagName('head')[0]; if (siteSpect === null && typeof head !== 'undefined') { siteSpect = document.createElement('script'); siteSpect.type = 'text/javascript'; siteSpect.src = '/__ssobj/core.js+ssdomvar.js+generic-adapter.js';siteSpect.async = true; siteSpect.id = 'siteSpectLibraries'; head.appendChild(siteSpect); } if (window.addEventListener){ siteSpect.addEventListener('load', callback, false); } else { siteSpect.attachEvent('onload', callback, false); } } } };})(); </script> </head>
            <div id="map" style="width: 100%; height: 100%;"></div>
            <script>
                //var bounds = [[<?= "latLongOrigem" ?>], [<?= "latLongDestino" ?>]];
                var m = tt.map({
                    key: "<?= API_TOMTOM ?>",
                    container: "map",
                    zoom: 13,
                    center: [<?= $centerLoc ?>]
                });

                let bounds = new tt.LngLatBounds();
                let locations = new Array;

                <?php foreach ($location as $k => $loc) { ?>

                    <?php if (!empty($loc['img'])) { ?>
                        let icon<?= $k ?> = document.createElement('div');
                        icon<?= $k ?>.className = 'marker-icon';
                        icon<?= $k ?>.style.backgroundImage = 'url(<?= $loc['img'] ?>)';
                    
                        let marker<?= $k ?> = new tt.Marker({element: icon<?= $k ?>});
                    <?php } else { ?>
                        let marker<?= $k ?> = new tt.Marker();
                    <?php } ?>

                    marker<?= $k ?>.setLngLat([<?= $loc['long'] .','. $loc['lat'] ?>]).addTo(m);
                    locations.push(marker<?= $k ?>.getLngLat());
                    bounds.extend(tt.LngLat.convert(marker<?= $k ?>.getLngLat()));

                <?php } ?>

                // m.fitBounds(bounds, {padding: 80});

                <?php
                if (!empty($data)) {
                    $json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    ?>

                    const data = JSON.parse("<?php echo addslashes($json) ?>");
                    
                    setTimeout(function(){
                        m.addLayer({
                            id: 'density',
                            source: {
                                type: 'geojson',
                                data,
                            },
                            type: 'heatmap',
                            paint: {
                                'heatmap-radius': [
                                    "interpolate",
                                    ["linear"],
                                    ["zoom"],
                                    10, 2,
                                    11, 20,
                                ],
                                'heatmap-weight': {
                                    type: 'exponential',
                                    property: 'density',
                                    stops: [
                                        [1, 0],
                                        [10000, 1],
                                    ],
                                },
                            },
                        });
                    }, 500);

                <?php } ?>

           </script>
        <?php
    }

    public static function distancia(float $latitude1, float $longitude1, float $latitude2, float $longitude2, string $unit = null)
    {
        try {
            $origem = "";
            $destino = "";

            if (!empty($latitude1) && !empty($longitude1)) {
                $origem = $latitude1.",".$longitude1;
            }

            if (!empty($latitude2) && !empty($longitude2)) {
                $destino = $latitude2.",".$longitude2;
            }

            if (empty($origem) || empty($destino)) {
                throw new Exception();
            }

            $url = "https://api.tomtom.com/routing/1/calculateRoute/". self::encodeURI($origem .":". $destino )."/json?sectionType=pedestrian&travelMode=pedestrian&vehicleMaxSpeed=3&key=". API_TOMTOM;
            $maps = @file_get_contents($url);
            $maps = json_decode($maps, true);
            if (empty($maps)){
                throw new Exception();
            }

            $distancia = floatval($maps['routes'][0]['summary']['lengthInMeters']); //metros
            $tempo = gmdate("H:i:s", $maps['routes'][0]['summary']['travelTimeInSeconds'] / 60); //minutos

            //caso Auto e seja menor q 1 kilometro, retorna em metros
            if ( (empty($unit) || $unit == 'A') && $distancia < 1000){
                $unit = 'M';
            }

            if (empty($unit)) {
                $unit = 'M';
            }

            switch($unit) {
                case 'M':
                    $u = " m";
                    break;
                case 'A' :
                case 'K' :
                    $distancia = $distancia / 1000;
                    $u = " km";
                    break;
            }

            return [round($distancia,2).$u, $tempo];

        } catch (Exception $e){
            return ["0 m", 0];
        }
    }

    /**
     * busca a distancia entre dois pontos longitudinais
     * @param type $unit
     *          A: Auto (km ou metros)
     *          K: Kilometros
     *          M: Metros
     */
    public static function distanciaRaio(float $latitude1, float $longitude1, float $latitude2, float $longitude2, string $unit = 'A'){
        $theta = $longitude1 - $longitude2;
        $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $distance = acos($distance); 
        $distance = rad2deg($distance); 
        $distance = $distance * 60 * 1.1515; 
        $distance = $distance * 1.609344; // KM

        //caso Auto e seja menor q 1 kilometro, retorna em metros
        if ($unit == 'A' && $distance < 1){
            $unit = 'M';
        }

        switch($unit) {
            case 'M':
                $distance = $distance * 1000;
                $u = " m";
                break;
            case 'A' :
            case 'K' :
                $u = " km";
                break;
        }

        //tempo
        return [
            round($distance,2).$u, 
            0,
        ];
    }

    public static function geraExcel($dados, $download = true)
    {
        ob_clean();

        if (empty($dados)) {
            ?>
            <div>
                Não Existem Dados referente a esta consulta.
            </div>
            <?php
            return;
        }

        if (PHP_SAPI == 'cli')
            die('This example should only be run from a Web Browser');

        require_once ABSPATH . '/vendor/autoload.php';

        // Create new PHPExcel object
        $objPHPExcel = new PhpOffice\PhpSpreadsheet\Spreadsheet();

        // delete the default active sheet
        $objPHPExcel->removeSheetByIndex(0);

        $nomearquivo = 'arquivo-'. date('YmdHis');

        // Create "Sheet 1" tab as the first worksheet.
        // https://phpspreadsheet.readthedocs.io/en/latest/topics/worksheets/adding-a-new-worksheet
        $worksheet1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($objPHPExcel, $nomearquivo);
        $objPHPExcel->addSheet($worksheet1, 0);

        $newData = array();
        $firstLine = true;

        foreach ($dados as $dataRow)
        {
            if ($firstLine)
            {
                $newData[] = array_keys($dataRow);
                $firstLine = false;
            }

            $newData[] = array_values($dataRow);
        }

        $worksheet1->fromArray($newData);

        // Change the widths of the columns to be appropriately large for the content in them.
        foreach ($worksheet1->getColumnIterator() as $column)
        {
            $worksheet1->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
        }

        if ($download) {
            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $nomearquivo . '.xlsx"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0
        }

        // Save to file.
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($objPHPExcel);
        $writer->save($nomearquivo.'xlsx');
        exit;
    }

    public static function chat($id_chat, $id_pessoa = null, $id_pessoa_aluno = null, $hidden = null, $titulo = null) {
        $WHERE = '';
        if (!empty($id_pessoa_aluno)) {
           $WHERE =  $WHERE." AND fk_id_pessoa_aluno = $id_pessoa_aluno";
        }
        
        $sql = "SELECT m.mensagem, m.dt_mensagem, p.n_pessoa, m.fk_id_pessoa "
                . " FROM ge_mensagem m "
                . " JOIN pessoa p on p.id_pessoa = m.fk_id_pessoa "
                . " WHERE m.fk_id_chat = " . $id_chat
                . $WHERE
                . " ORDER BY dt_mensagem";

        $query = pdoSis::getInstance()->query($sql);
        $arrayMensagem = $query->fetchAll(PDO::FETCH_ASSOC);
        $pessoa = [];
        foreach ($arrayMensagem as $key => $value) {
            $k = array_search($value['fk_id_pessoa'], $pessoa);

            if ($k === false) {
                $pessoa[] = $value['fk_id_pessoa'];
                $k = array_search($value['fk_id_pessoa'], $pessoa);
            }

            $arrayMensagem[$key]['cor'] = $k;
            $arrayMensagem[$key]['dt_mensagem'] = dataErp::converteBr($value['dt_mensagem']);
            $arrayMensagem[$key]['n_pessoa'] = explode(' ', $value['n_pessoa'])[0];
        }
        include ABSPATH . '/includes/views/chat/chat.php';
    }

    public static function pegaIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = null;
        }

        return $ip;
    }

    public static function ordenaArray($array=NULL, $attr='')
    {
        if (!empty($array) && is_array($array)) {
            usort($array, fn($a, $b) => $a[$attr] <=> $b[$attr]);
        }
        return $array;
    }

    /**
     * procura um valor, através de um dos atributos de um array multimensional
     * retorna NULL ou a Key do array
     * @param string $id: Valor a ser procurado
     * @param array $array: Array onde irá fazer a pesquisa
     * @param string $attr: atributo do array onde fará a pesquisa
     * @return string
     */
    public static function searchForId($id, $array, $attr) {
       if (!isset($array)) return null;
       if (empty($array)) return null;
       if (!is_array($array)) return null;
       foreach ($array as $key => $val) {
            if (!isset($val[$attr])) {
                return null;
            }
            if ($val[$attr] == $id) {
               return $key;
            }
       }
       return null;
    }

    /**
     * Insere linha animada em CSS
     * Para modificar stilo usar array. Exemplo:['width'=>'10%', 'height'=>'1px']
     * 
     */
    public static function linha($style=[]){
        if (empty($style)){
            $style = [
                'width' => '100%'
            ];
        }
        $st = '';
        foreach ($style as $key => $value) {
            $st .= $key.':'.$value.';';
        }
        
        ?>
        <div class="loader-line noprint" style="<?php echo $st ?>"></div>
    <?php
    }

    public static function fotoEndereco($id_pessoa) {
        if (file_exists(ABSPATH . "/pub/fotos/" . $id_pessoa . ".jpg")) {
            return HOME_URI . '/pub/fotos/' . $id_pessoa . '.jpg?' . uniqid();
        } elseif (file_exists(ABSPATH . "/pub/fotos/" . $id_pessoa . ".png")) {
            return HOME_URI . '/pub/fotos/' . $id_pessoa . '.png?' . uniqid();
        } else {
            return HOME_URI . '/includes/images/anonimo.jpg';
        }
    }

    public static function implodeMultiple($array, $campo, $separador)
    {
        if (!is_array($array)) {
            return null;
        }

        if (empty($campo)) {
            return null;
        }

        if (empty($separador)) {
            return null;
        }

        return implode($separador, array_map(function ($entry) use ($campo) {
            return $entry[$campo];
        }, $array));
    }
}
