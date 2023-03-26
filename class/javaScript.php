<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of javaScript
 *
 * @author marco
 */
class javaScript {

    /**
     * esemplo: <input type='text' size='10' value='' onkeypress='return SomenteNumero(event)'>
     */
    public static function somenteNumero() {
        ?>
        <script language='JavaScript'>
            function SomenteNumero(e) {
                var tecla = (window.event) ? event.keyCode : e.which;
                if ((tecla > 47 && tecla < 58))
                    return true;
                else {
                    if (tecla == 8 || tecla == 0)
                        return true;
                    else
                        return false;
                }
            }
        </script>
        <?php
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

    /**
     *       <form method="get" action=".">
      <label>Cep:
      <input name="cep" type="text" id="cep" value="" size="10" maxlength="9" /></label><br />
      <label>Rua:
      <input name="rua" type="text" id="rua" size="60" /></label><br />
      <label>Bairro:
      <input name="bairro" type="text" id="bairro" size="40" /></label><br />
      <label>Cidade:
      <input name="cidade" type="text" id="cidade" size="40" /></label><br />
      <label>Estado:
      <input name="uf" type="text" id="uf" size="2" /></label><br />
      <label>IBGE:
      <input name="ibge" type="text" id="ibge" size="8" /></label><br />
      </form>
     */
    public static function cep() {
        ?>
        <script type="text/javascript" >

            $(document).ready(function () {

                function limpa_formulário_cep() {
                    // Limpa valores do formulário de cep.
                    $("#rua").val("");
                    $("#bairro").val("");
                    $("#cidade").val("");
                    $("#uf").val("");
                    $("#ibge").val("");
                }

                //Quando o campo cep perde o foco.
                $("#cep").blur(function () {

                    //Nova variável "cep" somente com dígitos.
                    var cep = $(this).val().replace(/\D/g, '');

                    //Verifica se campo cep possui valor informado.
                    if (cep != "") {

                        //Expressão regular para validar o CEP.
                        var validacep = /^[0-9]{8}$/;

                        //Valida o formato do CEP.
                        if (validacep.test(cep)) {

                            //Preenche os campos com "..." enquanto consulta webservice.
                            $("#rua").val("...");
                            $("#bairro").val("...");
                            $("#cidade").val("...");
                            $("#uf").val("...");
                            $("#ibge").val("...");

                            //Consulta o webservice viacep.com.br/
                            $.getJSON("//viacep.com.br/ws/" + cep + "/json/?callback=?", function (dados) {

                                if (!("erro" in dados)) {
                                    //Atualiza os campos com os valores da consulta.
                                    $("#rua").val(dados.logradouro);
                                    $("#bairro").val(dados.bairro);
                                    $("#cidade").val(dados.localidade);
                                    $("#uf").val(dados.uf);
                                    $("#ibge").val(dados.ibge);
                                } //end if.
                                else {
                                    //CEP pesquisado não foi encontrado.
                                    limpa_formulário_cep();
                                    alert("CEP não encontrado.");
                                }
                            });
                        } //end if.
                        else {
                            //cep é inválido.
                            limpa_formulário_cep();
                            alert("Formato de CEP inválido.");
                        }
                    } //end if.
                    else {
                        //cep sem valor, limpa formulário.
                        limpa_formulário_cep();
                    }
                });
            });

        </script>
        <?php
    }

    public static function cfpInput() {
        return ' onblur="validarCPF(this.value);" maxlength="14"  onkeypress="return SomenteNumero(event)"';
    }

    public static function cpf() {
        ?>
        <script>
            function validarCPF(cpf) {
                var filtro = /^\d{3}\d{3}\d{3}\d{2}$/i;

                if (!filtro.test(cpf))
                {
                    window.alert("CPF inválido.");
                    document.getElementById(btn).type = 'button';
                    return false;
                }

                cpf = remove(cpf, ".");
                cpf = remove(cpf, "-");

                if (cpf.length != 11 || cpf == "00000000000" || cpf == "11111111111" ||
                        cpf == "22222222222" || cpf == "33333333333" || cpf == "44444444444" ||
                        cpf == "55555555555" || cpf == "66666666666" || cpf == "77777777777" ||
                        cpf == "88888888888" || cpf == "99999999999")
                {
                    window.alert("CPF inválido.");
                    document.getElementById(btn).type = 'button';
                    return false;
                }

                soma = 0;
                for (i = 0; i < 9; i++)
                {
                    soma += parseInt(cpf.charAt(i)) * (10 - i);
                }

                resto = 11 - (soma % 11);
                if (resto == 10 || resto == 11)
                {
                    resto = 0;
                }
                if (resto != parseInt(cpf.charAt(9))) {
                    window.alert("CPF inválido.");
                    document.getElementById(btn).type = 'button';
                    return false;
                }

                soma = 0;
                for (i = 0; i < 10; i++)
                {
                    soma += parseInt(cpf.charAt(i)) * (11 - i);
                }
                resto = 11 - (soma % 11);
                if (resto == 10 || resto == 11)
                {
                    resto = 0;
                }

                if (resto != parseInt(cpf.charAt(10))) {
                    window.alert("CPF inválido.");
                    document.getElementById(btn).type = 'button';
                    return false;
                }
                document.getElementById(btn).type = 'submit';
                return true;
            }
        </script>
        <?php
    }

    public static function divDinanmica($name, $div, $link, $action = 'change') {

        $_POST['hh'] = 69544;
        $post = $_REQUEST;
        unset($post['path']);
        //array_merge($_POST, $_GET)
        if (!empty($post)) {
            foreach ($post as $k => $v) {
                if (!is_array($v)) {
                    @$hidden .= '&' . $k . '=' . $v;
                }
            }
        }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("[name='<?php echo $name ?>']").<?php echo $action ?>(function () {
                    $('#<?php echo $div ?>').load('<?php echo $link ?>?<?php echo $name ?>=' + $("[name='<?php echo $name ?>']").val() + '<?php echo @$hidden ?>');
                });
            });
        </script>
        <?php
    }

    public static function dataMascara() {
        ?>
        <script>
            function mascaraData(campoData, e) {
                if (e.keyCode == 8)
                    return false;

                var data = campoData.value.replace(/\//g, '');
                var d = '', dAux = '';

                for (i = 0; i < data.length; i++) {
                    dAux = data.substring(i, i + 1);
                    if (dAux.search(/[0-9]/g) >= 0) {
                        if (i == 1 || i == 3)
                            dAux += '/';

                        d += dAux;
                    }
                }

                campoData.value = d;
                return true;

            }
        </script>
        <?php
    }

    /**
     *  onblur="checarEmail(this)"
     */
    public static function email() {
        ?>
        <script type="text/javascript">
            function checarEmail(inp) {
                if (inp.value.indexOf('@') == -1
                        || inp.value.indexOf('.') == -1)
                {
                    alert("Por favor, informe um E-MAIL válido!");
                    return false;
                }
            }
        </script>
        <?php
    }

    /**
     *   onkeyup="mascara(this, mtel)" maxlength="15" dentro do input
     * @param string $id id do input
     */
    public static function telMascara() {
        ?>
        <script type="text/javascript">
            /* Máscaras ER */
            function mascara(o, f) {
                v_obj = o
                v_fun = f
                setTimeout("execmascara()", 1)
            }
            function execmascara() {
                v_obj.value = v_fun(v_obj.value)
            }
            function mtel(v) {
                v = v.replace(/\D/g, "");             //Remove tudo o que não é dígito
                v = v.replace(/^(\d{2})(\d)/g, "($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
                v = v.replace(/(\d)(\d{4})$/, "$1-$2");    //Coloca hífen entre o quarto e o quinto dígitos
                return v;
            }
            function id(el) {
                return document.getElementById(el);
            }

        </script>
        <?php
    }

    public static function mascara() {
        ?>
        <script>
            function mascara(el, masc) {
                el.value = masc(el.value)
            }
            function soNumeros(d) {
                return d.replace(/\D/g, "")
            }
            function semNumeros(d) {
                return d.replace(/\d/g, "")
            }
            function soMinusculas(d) {
                return d.toLowerCase()
            }
            function soMaiusculas(d) {
                return d.toUpperCase()
            }
            function cep(d) {
                d = soNumeros(d)
                d = d.replace(/^(\d{5})(\d)/, "$1-$2")
                return d
            }
            function cnpj(d) {
                d = soNumeros(d)
                d = d.replace(/^(\d{2})(\d)/, "$1.$2")
                d = d.replace(/^(\d{2})\.(\d{3})(\d)/, "$1.$2.$3")
                d = d.replace(/\.(\d{3})(\d)/, ".$1/$2")
                d = d.replace(/(\d{4})(\d)/, "$1-$2")
                return d
            }
            function cpf(d) {
                d = soNumeros(d)
                d = d.replace(/(\d{3})(\d)/, "$1.$2")
                d = d.replace(/(\d{3})(\d)/, "$1.$2")

                d = d.replace(/(\d{3})(\d{1,2})$/, "$1-$2")
                return d
            }
            function url(d) {
                d = d.replace(/http:\/\/?/, "")
                d = soMinusculas(d)
                dominio = d
                d = "http://" + dominio
                return d
            }
            function telefone(d) {
                d = soNumeros(d)
                d = d.replace(/^(\d\d)(\d)/g, "($1) $2")
                d = d.replace(/(\d{4})(\d)/, "$1-$2")
                return d
            }
            function data(d) {
                d = soNumeros(d)
                d = d.replace(/(\d{2})(\d)/, "$1/$2")
                d = d.replace(/(\d{2})(\d)/, "$1/$2")
                return d
            }
        </script>
        <?php
    }

    public static function iframeModal($function, $url, $ativado = NULL, $widthModal = NULL, $clean = NULL, $styleIframe = " width: 100%; height: 69vh") {
        toolErp::modalInicio($widthModal, $ativado, $function . 'Modal');
        ?>
        <div id="<?php echo $function ?>Div"></div>
        <?php
        toolErp::modalFim();
        ?>
        <script>
        <?php
        if (!empty($ativado)) {
            echo $function . '();';
        }
        ?>
            function <?php echo $function ?>(variavel) {
                $('#<?php echo $function ?>Modal').modal('show');
        <?php
        if (!empty($clean)) {
            ?>
                    $('.form-class').val('');
            <?php
        }
        ?>
                document.getElementById("<?php echo $function ?>Div").innerHTML = "<iframe style=\"border: none;<?php echo $styleIframe ?>\" src=\"<?php echo $url ?>?" + String(variavel) + "\"></iframe>"
            }
        </script>
        <?php
    }

}
