<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of form
 *
 * @author marco
 */
class form {

    /**
     * 
     * @param type $hidden Array key => valor
     * @return string
     */
    public static function hidden($hidden, $noClass = NULL) {


        $h = NULL;
        if (!empty($hidden)) {
            foreach ($hidden as $k => $v) {
                if ($k != 'formToken' && empty($noClass)) {
                    $classe = 'class="form-class"';
                } else {
                    $classe = NULL;
                }
                @$h .= "<input $classe type=\"hidden\" name=\"$k\" value=\"" . (str_replace('"', "''", $v)) . "\" />";
            }
        }
        return $h;
    }

    public static function token($table, $action = NULL, $hidden = NULL, $col = NULL, $debug = NULL) {

        $key = md5(uniqid(rand(), true));
        $doc['id_ft'] = $key;
        $doc['table_ft'] = $table;
        $doc['col_ft'] = (empty($col) ? NULL : serialize($col));
        $doc['action_ft'] = $action;
        $doc['hidden_ft'] = (serialize($hidden));
        $doc['at_ft'] = '1';
        $doc['fk_id_pessoa'] = (tool::id_pessoa());
        $doc['dt_ft'] = date("Y-m-d H:i:s");
        $doc['debug'] = $debug;
        $mongo = new mongoDb();
        $mongo->insert('formtoken', $doc);
        $_POST['_tokenPage'][] = $key;
        return ['formToken' => $key];
    }

    /**
     * 
     * @param type $table Nome da Tabela
     * @param type $action delete ou ireplace (se tiver id é update senão é insert)
     * @param type $hidden input oculto - sempre incluir o ID pelo parametro $hidden
     * @param type $col esconder nomes ex: ['banana'=>'id_pessoa]
     * @param type $debug qualquer valor para debugar
     * @return type
     */
    public static function hiddenToken($table, $action = NULL, $hidden = NULL, $col = NULL, $debug = NULL) {
        $keyToken = form::token($table, $action, $hidden, $col, $debug);
        $hidden = $keyToken;

        return form::hidden($hidden);
    }

    public static function input($name = NULL, $titulo = NULL, $post = NULL, $atrib = NULL, $placeholder = NULL, $type = NULL) {

        if(empty($type)){
            $type = 'text';
        }
        if (empty($post)) {
            $post = form::extratPost($name);
        }

        $input = '
        <div  style="width: 100%" class="input-group">
            <span class="input-group-addon " id="basic-addon1" >' . $titulo . '</span>
            <input type="'.$type.'" name="' . $name . '" value="' . $post . '" class="form-control" placeholder="' . @$placeholder . '" aria-describedby="basic-addon1" ' . $atrib . '>
        </div>
        ';

        return $input;
    }

    /**
     *  onclick="document.limpar.submit()"
     * @param type $hidden
     */
    public static function limpar($hidden = NULL) {
        ?>
        <form name="limpar" method = "POST">
            <?php echo form::hidden($hidden) ?>
        </form>
        <?php
    }

    public static function extratPost($field) {
        $field_ = explode('[', $field);
        if (count($field_) > 1) {
            $name = substr($field_[1], 0, -1);
            if (!empty($_POST[$field_[0]][$name])) {
                $post = $_POST[$field_[0]][$name];
            } elseif (!empty($_POST[$name])) {
                $post = $_POST[$name];
            } else {
                $post = NULL;
            }
        }
        return @$post;
    }

    public static function extraName($name) {
        $field_ = explode('[', $name);
        if (count($field_) > 1) {
            $name = substr($field_[1], 0, -1);
        }
        return $name;
    }

    /**
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select; se for um número cria opção de números
     * @param type $atrib qualque atributo dentro do select
     * @param type $form cria um form sem botão (onchange) 1 = action="" qualque outro valor vira o URL
     * @param type $hidden inclui input oculto formato array (key = name; valor = valor)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $name Nome do campo e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select
     * @param type $titulo no formulario vai na frente do input / se for um array, o 1º é o título e o segundo é a 1º opcão do imput 
     * @param type $style: pertence a DIV e não ao input
     * @param type $placeholder
     * @param type $jqOption   array [metodo, id_do_select_de_destino
     */
    public static function select($name, $options, $titulo = NULL, $post = NULL, $form = NULL, $hidden = NULL, $atrib = NULL, $style = NULL, $jqOption = NULL) {
        if (empty($style)) {
            $style = "width: 100%";
        }
        if (empty($post)) {
            $post = form::extratPost($name);
        }
        $jsName = form::extraName($name);
        ?>
        <style>
            .input-group-addon{

                border: 1px solid #ccc;
                width: 120px;
                border-radius: 3px;
                overflow: hidden;
                background: #fafafa  no-repeat 90% 50%;
            }
            .appearance-select{
                height:30px; /* Altura do select, importante para que tenha a mesma altura em todo os navegadores */
            }
            .select-style {
                border: 1px solid #ccc;
                width: 120px;
                border-radius: 3px;
                overflow: hidden;
                background: #fafafa url("img/icon-select.png") no-repeat 90% 50%;
            }

            .select-style select {
                padding: 5px 8px;
                width: 130%;
                border: none;
                box-shadow: none;
                background: transparent;
                background-image: none;
                -webkit-appearance: none;
            }

            .select-style select:focus {
                outline: none;
            }

        </style>
        <!-- Latest compiled and minified CSS -->
        <!--<link rel="stylesheet" href="<?php echo HOME_URI; ?>/includes/css/bootstrap-select.min.css">-->
        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">-->
        <!-- Latest compiled and minified JavaScript -->
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>-->

        <?php
        if (!empty($form)) {
            ?>
            <form name="<?php echo $jsName . 'Form' ?>" action="<?php echo ($form != 1 ? $form : '') ?>" method="POST">
                <?php
            }
            if (!empty($hidden)) {
                echo form::hidden($hidden);
            }
            if (!empty($titulo)) {
                if (is_array($titulo)) {
                    $titulo_ = $titulo[0];
                    $tituloOption = $titulo[1];
                } else {
                    $titulo_ = $titulo;
                }
                ?>
                <div style="width: 100%" class="input-group" style="width: 1px">
                    <span class="input-group-addon" id="basic-addon1" ><?php echo $titulo_ ?></span>
                    <?php
                }
                if (empty($jqOption)) {
                    $onchange = "document." . $jsName . "Form.submit()";
                } else {
                    if (empty($_POST['contaSel'])) {
                        $_POST['contaSel'] = 1;
                        $_POST['contaSelect'][1] = $jsName;
                    }
                    $onchange = "this.options[this.selectedIndex].onclick()";
                }
                ?>

                    <select class="form-class  <?php echo empty($_POST['contaSel'])?1:$_POST['contaSel'] ?> appearance-select"  <?php echo count($options) > 10 ? 'data-live-search="true"' : '' ?>  <?php echo empty($atrib) ? 'id="' . $jsName . '"' : $atrib ?> name="<?php echo $name ?>" onchange="<?php echo $onchange ?>" style="<?php echo $style ?>">
                    <option value=""><?php echo @$tituloOption ?></option>
                    <?php
                    if (!is_array(current($options))) {
                        foreach ($options as $k => $v) {
                            ?>
                            <option <?php echo!empty($jqOption) ? ' onclick="' . $jsName . '(\'' . $k . '\')"' : '' ?> <?php echo ($post == $k ? 'selected' : '') ?> value="<?php echo $k ?>"><?php echo $v ?></option>
                            <?php
                        }
                    } else {
                        foreach ($options as $ki => $vi) {
                            ?>
                            <optgroup label="<?php echo $ki ?>">
                                <?php
                                foreach ($vi as $k => $v) {
                                    ?>
                                    <option <?php echo!empty($jqOption) ? ' onclick="' . $jsName . '(\'' . $k . '\')"' : '' ?> <?php echo ($post == $k ? 'selected' : '') ?> value="<?php echo $k ?>"><?php echo $v ?></option>
                                    <?php
                                }
                                ?>
                            </optgroup>
                            <?php
                        }
                    }
                    ?>
                </select>
                <?php
                if (!empty($titulo)) {
                    ?>
                </div>
                <?php
            }
            if (!empty($form)) {
                ?>
            </form>
            <?php
        }
        if (is_array(@$jqOption)) {
            ?>
            <script type="text/javascript">
                function <?php echo $jsName ?>(val) {

                    $.getJSON('<?php echo $_SERVER['REQUEST_URI'] ?>?jqFunction=<?php echo $jqOption[0] ?>&val=' + val, function (dados) {
                        var option = '<option value="">Selecione</option>';
                        $.each(dados, function (i, obj) {
                            option += '<option onclick="<?php echo $jqOption[1] ?>(\'' + i + '\')" value="' + i + '">' + obj + '</option>';
                        })
                        $('#<?php echo $jqOption[1] ?>').html(option).show();
                    })
            <?php
            for ($c = ($_POST['contaSel'] + 2); $c < 10; $c++) {
                ?>
                        $(".<?php echo $c ?>").empty();//retira os elementos antigos
                        var option = document.createElement('option');
                        $(option).attr({value: ''});
                        $(option).append('carregando');
                        $(".<?php echo $c ?>").append(option);
                <?php
            }
            ?>

                }
            </script>
            <?php
        }
        if (!empty($_POST['contaSelect'])) {

            @$_POST['contaSelect'][++$_POST['contaSel']] = $jsName;
        }
    }

    /**
     * formata o campo data
     * @param type $id
     * @return type
     */
    public static function dataFormat() {
        return 'style="width: 200px"  OnKeyUp="mascaraData(this, event);"  maxlength="10" size="10" min="10" ';
    }

    /**
     * select a partir de uma tabela sendo value = id_... e motrarár o n_...
     * @param type $table Tabela
     * @param type $where where no DB
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select; se for um número cria opção de números
     * @param type $atrib qualque atributo dentro do select
     * @param type $form cria um form sem botão (onchange) 1 = action="" qualque outro valor vira o URL
     * @param type $hidden inclui input oculto formato array (key = name; valor = valor)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $name Nome do campo e do form (para submit sem botão)
     * @param type $titulo no formulario vai na frente do input
     * @param type $style: pertence a DIV e não ao input
     * @param type $placeholder
     */
    public static function selectDB($table, $name = NULL, $titulo = NULL, $post = NULL, $form = NULL, $hidden = NULL, $options = NULL, $where = NULL, $atrib = NULL) {
        if (empty($options)) {
            $col = sql::columns($table);
            foreach ($col as $k => $v) {
                if (substr($v, 0, 3) == 'id_') {
                    $fk = $v;
                } elseif (substr($v, 0, 2) == 'n_') {
                    $value = $v;
                    $n_ = $v;
                }
            }
            if (empty($where)) {
                $where['>'] = $n_;
            }
        } else {
            $fk = key($options);
            $value = $options[$fk];
        }
        if (empty($name)) {
            $name = $fk;
        }

        $array = sql::get($table, '*', $where);
        foreach ($array as $v) {
            $options_[$v[$fk]] = $v[$value];
        }
        if (!empty($options_)) {
            return form::select($name, $options_, $titulo, $post, $form, $hidden, $atrib);
        } else {
            echo 'Select Não configurado';
        }
    }

    /**
     * 
     * @param type $name
     * @param string $options [mínimo,máximo.posfixo]
     * @param type $titulo
     * @param type $post
     * @param type $form
     * @param type $hidden
     * @param type $atrib
     * @param type $style
     * @return type
     */
    public static function selectNum($name, $options, $titulo = NULL, $post = NULL, $form = NULL, $hidden = NULL, $atrib = NULL, $style = NULL) {
        for ($c = $options[0]; $c <= $options[1]; $c++) {
            $opt[$c] = $c . @$options[2];
        }
        return form::select($name, $opt, $titulo, $post, $form, $hidden, $atrib, $style);
    }

    /**
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $atrib qualque atributo dentro do select
     * @param type $titulo no formulario vai na frente do input
     * @param type $style: pertence a DIV e não ao input
     */
    public static function checkbox($name, $value, $titulo = NULL, $post = NULL, $atrib = NULL, $style = NULL) {
        if (empty($post)) {
            $post = form::extratPost($name);
        }
        ?>
        <input type="hidden" name="<?php echo $name ?>" value="0" />
        <label class="container">
            <span style="font-size: 14px"><?php echo $titulo ?></span> 
            <input <?php echo ($post == $value ? "checked" : '') ?> type="checkbox" name="<?php echo $name ?>" value="<?php echo $value ?>" <?php echo $atrib ?> />
            <span class="checkmark"></span>
        </label>
        <?php
    }

    /**
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $atrib qualque atributo dentro do select
     * @param type $titulo no formulario vai na frente do input
     * @param type $style: pertence a DIV e não ao input
     */
    public static function radio($name, $value, $titulo = NULL, $post = NULL, $atrib = NULL, $style = NULL) {
        if (empty($post)) {
            $post = form::extratPost($name);
        }
        ?>
        <label class="container">
            <span style="font-size: 14px"><?php echo $titulo ?></span> 
            <input <?php echo ($post == $value ? "checked" : '') ?> type="radio" name="<?php echo $name ?>" value="<?php echo $value ?>" <?php echo $atrib ?> />
            <span class="checkmark"></span>
        </label>
        <?php
    }

    /**
     * cria um formulario com campos ocultos e um botao submit
     * @param type $value valor do botao submit
     * @param type $token token para a classe DB
     * @param type $hidden campos oculto - tem que ser array
     * @param type $location action do post
     * @param string $target 1 para abrir em nova aba
     * @param type $msg alternativa para o alert
     * @return string
     */
    public static function submit($value = 'Enviar', $token = NULL, $hidden = NULL, $location = NULL, $target = NULL, $msg = NULL, $class = NULL, $style = NULL, $buttonName = NULL) {
        if (empty($class) && $value == 'Apagar') {
            $class = 'btn btn-danger';
        } elseif (empty($class)) {
            $class = 'btn btn-primary';
        }
        $glyphicon = form::glyphiconButton($value);
        if ($target == 1) {
            $target = "target=\"_blank\"";
        }
        //id para o javaScript
        $id = uniqid("form");
        if (!empty($token)) {
            $hidden = array_merge($hidden, $token);
        }
        $submit = "<form id=\"$id\" action = \"$location\" $target method = \"POST\">";
        if (!empty($hidden)) {

            $submit .= form::hidden($hidden, 1);
        }
        if (empty($token) || $value == 'Editar') {
            $submit .= "<button value = \"$value\" class=\"$class\" type=\"submit\" style=\"" . $style . "\">" . @$glyphicon . (empty($buttonName) ? $value : $buttonName) . "</button>";
        } else {
            $submit .= "<button value = \"$value\" class=\"$class\" type=\"button\" onclick=\"formjs('$id','" . (empty($msg) ? $value : $msg) . "')\"  style=\"" . $style . "\" >" . @$glyphicon . (empty($buttonName) ? $value : $buttonName) . "</button>";
        }
        $submit .= "</form>";

        return $submit;
    }

    public static function glyphiconButton($value) {
        $gly = [
            'Salvar' => '<span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>&nbsp;&nbsp;',
            'Apagar' => '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>&nbsp;&nbsp;',
            'Acessar' => '<span class="glyphicon glyphicon-log-in" aria-hidden="true"></span>&nbsp;&nbsp;',
            'Editar' => '<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>&nbsp;&nbsp;'
        ];
        return @$gly[$value];
    }

    /**
     * gera uma senha aleatoria e válida
     * @return type
     */
    public static function gerarSenha() {

        $num = "123456789";
        $caracteres = "abcdefghijklmnpqrstuvwxyz";
        $m1 = substr(str_shuffle($num), 0, 4);
        $m2 = substr(str_shuffle($caracteres), 0, 4);
        $m3 = $m1 . $m2;
        $senha = substr(str_shuffle($m3), 0, 8);
        return $senha;
    }

    public static function textarea($name = NULL, $post = NULL, $placeholder = NULL, $height = '100', $editor = NULL) {
        if (!empty($editor)) {
            ?>
            <script src="<?php echo HOME_URI ?>/app/ckeditor/ckeditor.js"></script>
            <?php
        }
        ?>
        <textarea style="width: 100%; height: <?php echo $height ?>px" id="<?php echo $editor ?>" name="<?php echo $name ?>" <?php echo empty($post) ? 'placeholder="' . $placeholder . '"' : '' ?> ><?php echo $post ?></textarea>
        <?php
        if (!empty($editor)) {
            ?>
            <script>
                CKEDITOR.replace('<?php echo $editor ?>');
            </script>
            <?php
        }
    }

    /**
     * retorna os formulário "Apagar" e "Acessar" dentro das variáveis "apagar" e "acessar"
     * Se não fornecer a tabela, só retornará o "Acessar"
     * Atenção!!! Voce só pode ter 1 "id_" no array
     * @param type $onclick  troca o acessar por button co JS : "%" será subestituido pela variaveis
     * @param type $array
     */
    public static function submitAcessarApagar($array, $table = NULL, $hidden = NULL, $location = NULL, $target = NULL, $msg = NULL, $onclick = NULL, $buttonName = NULL) {
        if (is_array($location)) {
            $hiddenAcesso = $location;
            $location = NULL;
        }
        if (!empty($array)) {
            if (!empty($table)) {
                $token = form::token($table, 'delete');
            }
            foreach (current($array) as $k => $v) {
                if (substr($k, 0, 3) == 'id_') {
                    $id = $k;
                }
            }
            foreach ($array as $k => $v) {
                if (!empty($table)) {
                    if (!empty($hidden)) {
                        $hiddenDel = array_merge($hidden, ['1[' . $id . ']' => $v[$id]]);
                    } else {
                        $hiddenDel = ['1[' . $id . ']' => $v[$id]];
                    }
                    $array[$k]['apagar'] = form::submit('Apagar', $token, $hiddenDel);
                }
                $v['Acessar'] = 1;
                if (!empty($hidden)) {
                    $hiddenAc = array_merge($v, $hidden);
                } else {
                    $hiddenAc = $v;
                }
                if (!empty($hiddenAcesso)) {
                    $hiddenAc = array_merge($hiddenAc, $hiddenAcesso);
                }
                if (empty($onclick)) {
                    $array[$k]['acessar'] = form::submit('Acessar', NULL, $hiddenAc, $location, $target, $msg, NULL, NULL, $buttonName);
                } else {
                    $array[$k]['acessar'] = form::button('Acessar', NULL, str_replace('%', "'" . tool::varGet($hiddenAc) . "'", $onclick), NULL, NULL, NULL, NULL, NULL, $buttonName);
                }
            }
            return $array;
        }
    }

    public static function buttonLong($text, $glyphicon = NULL, $onclick = NULL, $btn = 'primary', $tooltip = NULL) {
        if (empty($glyphicon)) {
            $glyphicon = form::glyphiconButton($text);
        } else {
            $glyphicon = '<span style="font-size: 30px" class="' . $glyphicon . '" aria-hidden="true"></span>';
        }

        if (!empty($onclick)) {
            $onclick = ' onclick="' . $onclick . '" ';
            $type = 'button';
        } else {
            $type = 'submit';
        }
        if (!in_array($btn, ['warning', 'info'])) {
            $color = 'white';
        } else {
            $color = 'black';
        }
        if (!empty($tooltip)) {
            $tooltip = ' data-toggle="tooltip" data-placement="top" title="' . $tooltip . '" ';
        }
        $b = '<button type="' . $type . '" class="btn btn-' . $btn . ' border1" ' . $onclick . ' style="width: 100%; text-align: left; color:' . $color . '" ' . $tooltip . ' >'
                . '<table style="width: 100%;height: 50px">'
                . ' <tr><td>'
                . $glyphicon
                . '</td><td>'
                . $text
                . '</td></tr>'
                . '</table>'
                . '</button>';

        return $b;
    }

    public static function button($text = NULL, $glyphicon = NULL, $onclick = NULL, $btn = NULL, $name = NULL, $value = NULL, $width = NULL, $tooltip = NULL, $buttonName = NULL) {
        if (empty($btn)) {
            $btn = 'primary';
        }
        if (empty($glyphicon)) {
            $glyphicon = form::glyphiconButton($text);
        } else {
            $glyphicon = '<span class="' . $glyphicon . '" aria-hidden="true"></span>';
        }

        if ($text == 'Salvar') {
            $btn = 'success';
        }
        if (!empty($onclick)) {
            $onclick = ' onclick="' . $onclick . '" ';
            $type = 'button';
        } else {
            $type = 'submit';
        }
        if (!in_array($btn, ['warning', 'info', 'link', 'default'])) {
            $color = 'white';
        } else {
            $color = 'black';
        }
        if ($btn != 'link') {
            $border1 = ' border1 ';
        }
        if (!empty($tooltip)) {
            $tooltip = ' data-toggle="tooltip" data-placement="top" title="' . $tooltip . '" ';
        }
        $b = '<button type="' . $type . '" class="btn btn-' . $btn . @$border1 . ' " ' . $onclick . ' style="width: ' . $width . '%; text-align: left; color:' . $color . '" ' . (empty($value) ? '' : 'value="' . $value . '"') . ' ' . (empty($name) ? '' : 'name="' . $name . '"') . '  ' . $tooltip . '>'
                . $glyphicon
                . (empty($buttonName) ? (!empty($text) ? '&nbsp;&nbsp;' . $text : NULL) : $buttonName)
                . '</button>';

        return $b;
    }

    /**
     * 
     * @return type [metodo, variavel]
     */
    public static function jqOption() {
        if (!empty($_REQUEST['jqFunction']) && !empty($_REQUEST['val'])) {
            $opt[] = $_REQUEST['jqFunction'];
            $opt[] = $_REQUEST['val'];

            return $opt;
        }
    }

}
