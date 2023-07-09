<?php

/**
 * possibilita capturar uma assinatura digital
 */

class assinaturaDigital {

    private $btn_disable = [];
    private $btn_clear = [];
    private $act_change = [];
    private $css_box = [];
    private $classIdentifier = 'js-signature';
    private $ambiente = '';

    public function __construct() {
        $this->btn_disable = [ 'enable' => false, 'name' => 'Desabilitar', 'attr' => [] ];
        $this->btn_clear = [ 'enable' => true, 'name' => 'Limpar', 'attr' => [] ];
        $this->act_change = [ 'enable' => true ];
        $this->css_box = [ 
            'width'=>"400px",
            'height'=>"200px"
        ];
    }

    /**
     * permite definir o identificador JS do campo da assinatura
     * @param string $identifier -> padrão é a classe ".js-signature"
     */
    public function setName($name)
    {
        if (empty($name)) return;
        $this->classIdentifier = $name;
    }

    /**
     * permite configurar o botão de DESABILITAR
     * @param bool $set -> Se o botao estará ativo ou nao
     * @param string $name -> o nome do botão
     * @param array $attr -> qualquer atributo no botão no formato [ "atributo" => "valor do atributo" ]
     *                  - não passar o ID pois já é gerado um ID
     * @param string $function -> função JS a ser executada quando clicar no botão
     */
    public function bntDisable($set=true, $name = "Desabilitar", $attr = [], $function = NULL)
    {
        $this->btn_disable = [
            'enable' => !empty($set),
            'name' => $name,
            'attr' => $attr,
            'function' => $function,
        ];
    }

    /**
     * permite configurar o botão de LIMPAR O CONTEÚDO
     * @param bool $set -> Se o botao estará ativo ou nao
     * @param string $name -> o nome do botão
     * @param array $attr -> qualquer atributo no botão no formato [ "atributo" => "valor do atributo" ]
     *                  - não passar o ID pois já é gerado um ID
     * @param string $function -> função JS a ser executada quando clicar no botão
     */
    public function bntClear($set=true, $name = "Desabilitar", $attr = [], $function = NULL)
    {
        $this->btn_clear = [
            'enable' => !empty($set),
            'name' => $name,
            'attr' => $attr,
            'function' => $function,
        ];
    }

    /**
     * permite configurar uma ação JS para ser executada quando houver conteúdo da assinatura digital
     * @param bool $set -> Se a ação estará ativa ou nao
     * @param string $function -> função JS a ser executada quando o evento change for disparado
     */
    public function actChange($set=true, $function = NULL)
    {
        $this->act_change = [
            'enable' => !empty($set),
            'function' => $function,
        ];
    }

    /**
     * permite configurar o CSS da Caixa de Texto onde será feita a assinatura
     * @param array $data -> qualquer atributo CSS no botão, exemplo [ "width" => "100px" ]
     */
    public function setCSSBox($data=[])
    {
        if (empty($data)) return;
        foreach ($data as $key => $value) {
            if (isset($this->css_box[$key])) {
                $this->css_box[$key] = $value;
            }
        }
    }

    /**
     * permite definir o ambiente em que está sendo assinado
     * @param string $ambiente
     */
    public function setAmbiente($name)
    {
        if (empty($name)) $name='';
        $this->ambiente = $name;
    }

    /**
     * Gera todo o script
     * para recuperar o conteúdo da assinatura digital chame a função JS getSVG()
     */
    public function gerar()
    {
        $this->generateAssets();
        $this->generateButtons();
        $this->generateActions();
    }

    /**
     * Faz a inclusão das bibliotecas CSS e JS que serão usadas
     * IMPORTANTE: Já espera-se que esteja inclusa a biblioteca JQUERY
     */
    public function generateAssets()
    {
        ?>
        <!-- JS -->
        <script type="text/javascript">
            const loadScript = (FILE_URL, async = true, type = "text/javascript") => {
                return new Promise((resolve, reject) => {
                    try {
                        const scriptEle = document.createElement("script");
                        scriptEle.type = type;
                        scriptEle.async = async;
                        scriptEle.src =FILE_URL;

                        scriptEle.addEventListener("load", (ev) => {
                            resolve({ status: true });
                        });

                        scriptEle.addEventListener("error", (ev) => {
                            reject({
                                status: false,
                                message: `Failed to load the script ＄{FILE_URL}`
                            });
                        });

                        document.body.appendChild(scriptEle);
                    } catch (error) {
                        reject(error);
                    }
                });
            };

            // SIGNATURE
            window.addEventListener("load", (event) => {
                setTimeout(function(){
                    loadScript("<?php echo HOME_URI ?>/<?php echo INCLUDE_FOLDER ?>/js/signature/jq-signature.min.js")
                    .then( data  => {
                        console.log("Script loaded successfully", data);
                        if (jQuery('.<?= $this->classIdentifier ?>').length) {
                            jQuery('.<?= $this->classIdentifier ?>').jqSignature();
                        }

                    });
                }, 500);
            });

            function strip_html_tags(str)
            {
                if ((str===null) || (str===''))
                   return false;
                else
                    str = str.toString();
                return str.replace(/<\?[^>]*>|<\![^>]*>|[\n\t]/g, '');
            }

            function getAssinatura(){
                const dataUrl = $('.<?= $this->classIdentifier ?>').jqSignature('getDataURL');
                const image_parts  = dataUrl.split(";base64,");
                const image_type = image_parts[0].split("image/")[1];

                if ($('input[name=__jq_data]').length == 0){
                    var d = $('<input>').attr({'type': 'hidden', 'name': '__jq_data', 'value': dataUrl});
                    d.insertAfter('.<?= $this->classIdentifier ?>');
                } else {
                    $('input[name=__jq_data]').val(dataUrl);
                }
                if ($('input[name=__jq_type]').length == 0){
                    var t = $('<input>').attr({'type': 'hidden', 'name': '__jq_type', 'value': image_parts[0].split(":")[1]});
                    t.insertAfter('.<?= $this->classIdentifier ?>');
                }
                if ($('input[name=__jq_extension]').length == 0){
                    var e = $('<input>').attr({'type': 'hidden', 'name': '__jq_extension', 'value': image_type});
                    e.insertAfter('.<?= $this->classIdentifier ?>');
                }
                if ($('input[name=__jq_ambiente]').length == 0){
                    var a = $('<input>').attr({'type': 'hidden', 'name': '__jq_ambiente', 'value': '<?= $this->ambiente ?>'});
                    a.insertAfter('.<?= $this->classIdentifier ?>');
                }

                return {
                    'typeElement': image_parts[0].split(":")[1],
                    'base64': image_parts[1],
                    'extension': image_type,
                    'data': dataUrl,
                };
            }
        </script>

        <!-- CSS -->
        <style>.<?= $this->classIdentifier ?>{
            <?php if (!empty($this->css_box)) {
                foreach ($this->css_box as $key => $value) {
                    echo $key .": ". $value.";";
                }
            } ?>
        }</style>
        <div class="<?= $this->classIdentifier ?> noprint"
            data-width="<?php echo preg_replace('/\D[.]/', '', $this->css_box['width']) ?>"
            data-height="<?php echo preg_replace('/\D[.]/', '', $this->css_box['height']) ?>"
            data-border="1px solid #1ABC9C"
            data-background="#fff"
            data-line-color="#000"
            data-auto-fit="true"
            >
        </div>
        <?php
    }

    /**
     * Cria os botões configurados para serem exibidos
     */
    public function generateButtons()
    {
        if (!$this->hasElement()){
            return;
        }

        if ($this->btn_disable['enable'] == true){
            echo '<button id="sign-ad-disable"';
            $noPrintDis = false;
            if (!empty($this->btn_disable['attr'])) {
                foreach ($this->btn_disable['attr'] as $k => $v) {
                    if ($k == 'class'){
                        $noPrintDis = true;
                        $v = 'noprint '.$v;
                    }
                    echo $k.'="'.$v.'" ';
                }
            }
            if (!$noPrintDis){
                echo ' class="noprint" ';
            }
            echo '>'. $this->btn_disable['name'] .'</button>';
        }
        if ($this->btn_clear['enable'] = true){
            echo '<button id="sign-ad-clear"';
            $noPrintCle = false;
            if (!empty($this->btn_clear['attr'])) {
                foreach ($this->btn_clear['attr'] as $k => $v) {
                    if ($k == 'class'){
                        $noPrintCle = true;
                        $v = 'noprint '.$v;
                    }
                    echo $k.'="'.$v.'" ';
                }
            }
            if (!$noPrintCle){
                echo ' class="noprint" ';
            }
            echo '>'. $this->btn_clear['name'] .'</button>';
        }
    }

    /**
     * Cria as ações dos botões configurados
     */
    public function generateActions()
    {
        if (!$this->hasElement()){
            return;
        }

        ?>
        <script type="text/javascript">
        $(function() {

            <?php if ($this->btn_disable['enable'] == true){ ?>
            $('#sign-ad-disable').click(function() {
                var disable = $(this).text() === 'Desabilitar';
                $(this).text(disable ? 'Habilitar' : 'Desabilitar');
                $('.<?= $this->classIdentifier ?>').jqSignature('enableDisableCanvas');
                <?php
                if (!empty($this->btn_disable['function'])){
                    echo "if (typeof ".$this->btn_disable['function'] ." === 'function') {".$this->btn_disable['function'] ."()}";
                }
                ?>
            });
            <?php } ?>

            <?php if ($this->btn_clear['enable'] == true){ ?>
            $('#sign-ad-clear').click(function(e) {
                $('.<?= $this->classIdentifier ?>').jqSignature('clearCanvas');
                <?php
                if (!empty($this->btn_clear['function'])){
                    echo "if (typeof ".$this->btn_clear['function'] ." === 'function') {".$this->btn_clear['function'] ."(e)}";
                }
                ?>
            });
            <?php } ?>

            $('.<?= $this->classIdentifier ?>').on('jq.signature.changed', function(e) {
                getAssinatura();
                <?php if (!empty($this->act_change['function'])){ ?>
                    <?php echo "if (typeof ".$this->act_change['function'] ." === 'function') {".$this->act_change['function'] ."(e)}"; ?>
                <?php } ?>
            });
        });
        </script>
        <?php
    }

    /**
     * Verifica se existe algum dos botões configurados
     */
    public function hasElement(){
        return ($this->btn_disable['enable'] == true || $this->btn_clear['enable']);
    }

    /**
     * Responsável por salvar na database os dados da assinatura digital
     * @param array $fields -> nome dos campos adicionais da tabela para ser populada
     */
    public static function salvarAssinatura($fields = []){
        try {
            if (!isset($_POST["__jq_data"])) {
                throw new Exception("Campo \"__jq_data\" não informado");
            }
            if (!isset($_POST["__jq_type"])) {
                throw new Exception("Campo \"__jq_type\" não informado");
            }
            if (!isset($_POST["__jq_extension"])) {
                throw new Exception("Campo \"__jq_extension\" não informado");
            }
            if (!isset($_POST["__jq_ambiente"])) {
                throw new Exception("Campo \"__jq_ambiente\" não informado");
            }

            if (!empty($fields)) {
                $f = [];
                $v = [];
                foreach ($fields as $key => $value) {
                    $f[] = "`$key`";
                    $v[] = is_null($value) ? "NULL" : "'$value'";
                }

                $fields = !empty($f) ? ",". implode(",", $f) : '';
                $values = !empty($v) ? ",". implode(",", $v) : '';
            } else {
                $fields = '';
                $values = '';
            }

            $_id_inst = toolErp::id_inst();
            $_id_pessoa = toolErp::id_pessoa();
            if (empty($_id_inst)) {
                $_id_inst = "NULL";
            }
            if (empty($_id_pessoa)) {
                $_id_pessoa = "NULL";
            }

            $sql = "INSERT INTO `asd_assinatura` (`fk_id_inst`, `fk_id_pessoa`, `ambiente`, `assinatura`, `tipo`, `extension`, `IP`, `dt_update` $fields ) VALUES ("
                    . $_id_inst .","
                    . $_id_pessoa .","
                    . " '".$_POST["__jq_ambiente"]."',"
                    . " '".$_POST["__jq_data"]."',"
                    . " '".$_POST["__jq_type"]."',"
                    . " '".$_POST["__jq_extension"]."',"
                    . " '". toolErp::pegaIp() ."',"
                    . " NOW()"
                    . $values
                . ");";
            $idAssinatura = pdoSis::action($sql);

        } catch (Exception $e){
            toolErp::alert($e->getMessage());
            $idAssinatura = 0;
        }
        return $idAssinatura;
    }

    /**
     * Responsável por recuperar a assinatura digital na database
     * @param int $idAssinatura -> id da Assinatura gerada
     */
    public static function getAssinatura($idAssinatura){
        if (empty($idAssinatura)) return [];

        $sql = "SELECT asd.id_assinatura, asd.fk_id_inst, i.n_inst, asd.fk_id_pessoa, p.n_pessoa, asd.assinatura, asd.tipo, asd.extension, asd.IP, DATE_FORMAT(asd.dt_update, '%d/%m/%Y %H:%i:%s') AS dt_update "
            . " FROM `asd_assinatura` asd "
            . " INNER JOIN instancia i ON asd.fk_id_inst = i.id_inst "
            . " INNER JOIN pessoa p ON asd.fk_id_pessoa = p.id_pessoa "
            . " WHERE asd.id_assinatura =  $idAssinatura";

        $q = pdoSis::getInstance()->query($sql);
        return $q;
    }

}