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
class formErp {

    /**
     * 
     * @param type $hidden Array key => valor
     * @return string
     */
    public static function hidden($hidden, $noClass = NULL) {


        $h = NULL;
        if (!empty($hidden)) {
            foreach ($hidden as $k => $v) {
                @$h .= "<input type=\"hidden\" name=\"$k\" value=\"" . (str_replace('"', "''", $v)) . "\" />";
            }
        }
        return $h;
    }

    public static function token($table, $action = NULL, $hidden = NULL, $col = NULL, $hiddenAlert = NULL, $debug = NULL) {

        $key = md5(uniqid(rand(), true));
        $doc['id_ft'] = $key;
        @$doc['url'] = explode('=', $_SERVER['QUERY_STRING'])[1];
        $doc['table_ft'] = $table;
        $doc['col_ft'] = (empty($col) ? NULL : serialize($col));
        $doc['action_ft'] = $action;
        $doc['hidden_ft'] = (serialize($hidden));
        $doc['at_ft'] = '1';
        $doc['fk_id_pessoa'] = (toolErp::id_pessoa());
        $doc['dt_ft'] = date("Y-m-d H:i:s");
        $doc['debug'] = $debug;
        $doc['hiddenAlert'] = $hiddenAlert;
        $mongo = new mongoCrude();
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
    public static function hiddenToken($table, $action = NULL, $col = NULL, $hidden = NULL, $hiddenAlert = null, $debug = NULL) {
        $keyToken = formErp::token($table, $action, $hidden, $col, $hiddenAlert, $debug);
        $hidden = $keyToken;

        return formErp::hidden($hidden);
    }

    public static function input($name = NULL, $titulo = NULL, $post = NULL, $atrib = NULL, $placeholder = NULL, $type = 'text', $class = null) {

        if (empty($post)) {
            $post = formErp::extratPost($name);
        }
        if (is_array($post)) {
            $post = null;
        }
        $input = '<div class="input-group">';
        if (!empty($titulo)) {
            $input .= '<div class="input-group-prepend">'
                . '<div class="input-group-text">' . $titulo . '</div>'
            . '</div>';
        }
        $input .= ' <input type="' . $type . '" name="' . $name . '" value="' . $post . '" class="form-control '.$class.'" placeholder="' . $placeholder . '" aria-describedby="basic-addon1" ' . $atrib . '>'
                . '</div>';

        return $input;
    }

    /**
     *  onclick="document.limpar.submit()"
     * @param type $hidden
     */
    public static function limpar($hidden = NULL) {
        ?>
        <form name="limpar" method = "POST">
            <?php echo formErp::hidden($hidden) ?>
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

    public static function extratName($field) {
        $field_ = explode('[', $field);
        if (count($field_) > 1) {
            $name = substr($field_[1], 0, -1);
        }
        return @$name;
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
     */
    public static function select($name, $options, $titulo = NULL, $post = NULL, $form = NULL, $hidden = NULL, $atrib = NULL, $style = NULL, $jqOption = NULL, $noSelectpicker = NULL) {
        if (empty($options)) {
            $options = [];
        }
        if (empty($post)) {
            $post = formErp::extratPost($name);
        }
        if (!empty($name)) {
            $idForm = formErp::extraName($name) . '_';
        }
        $jsName = formErp::extraName($name);
        if (!empty($form)) {
            ?>
            <form name="<?php echo $jsName . 'Form' ?>" action="<?php echo ($form != 1 ? $form : '') ?>" method="POST">
                <?php
            }
            if (!empty($hidden)) {
                echo formErp::hidden($hidden);
            }
            if (!empty($titulo)) {
                if (is_array($titulo)) {
                    $titulo_ = $titulo[0];
                    $tituloOption = $titulo[1];
                } else {
                    $titulo_ = $titulo;
                }
                ?>

                <div class="input-group">
                    <table>
                        <tr>
                            <td>
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="<?= $idForm ?>"><?php echo $titulo_ ?></label>
                                </div>
                            </td>
                            <td>
                                <?php
                            }
                            if (empty($jqOption)) {
                                $onchange = "if(document." . $jsName . "Form) {document." . $jsName . "Form.submit()}";
                            } else {
                                if (empty($_POST['contaSel'])) {
                                    $_POST['contaSel'] = 1;
                                    $_POST['contaSelect'][1] = $jsName;
                                }
                                $onchange = "this.options[this.selectedIndex].onclick()";
                            }
                            ?>

                            <select id="<?= $idForm ?>"  <?= empty($noSelectpicker) ? 'class="selectpicker"' : '' ?>  <?php echo (count($options) > 10 || empty($options)) ? 'data-live-search="true"' : '' ?>  <?php echo $atrib ?> name="<?php echo $name ?>" onchange="<?= $onchange ?>" style="<?php echo $style ?>; height: 38px"  data-width="fit" data-style="btn-outline-info" >
                                <option value=""><?php echo @$tituloOption ?></option>
                                <?php
                                if (!is_array(current($options))) {
                                    foreach ($options as $k => $v) {
                                        $s = '';
                                        if (is_array($post)){
                                            if (in_array($k, $post)) {
                                                $s = 'selected';
                                            }
                                        } else {
                                            if ($post == $k) {
                                                $s = 'selected';
                                            }
                                        }
                                        ?>
                                        <option <?php echo!empty($jqOption) ? ' onclick="' . $jsName . '(\'' . $k . '\')"' : '' ?> style="z-index: 999" <?php echo $s ?> value="<?php echo $k ?>"><?php echo $v ?></option>
                                        <?php
                                    }
                                } else {
                                    foreach ($options as $ki => $vi) {
                                        if (is_array($vi)) {
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
                                }
                                ?>
                            </select>
                            <?php
                            if (!empty($titulo)) {
                                ?>
                            </td>
                        </tr>
                    </table>
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
                    jQuery.noConflict();
                    jQuery.getJSON('<?php echo $_SERVER['REQUEST_URI'] ?>?jqFunction=<?php echo $jqOption[0] ?>&val=' + val, function (dados) {
                        var option = '<option value="">Selecione</option>';
                        dados = sortByValue(dados);
                        jQuery.each(dados, function (i, obj) {
                            option += '<option onclick="<?php echo $jqOption[1] ?>(\'' + obj[1] + '\')" value="' + obj[1] + '">' + obj[0] + '</option>';

                        })
                        jQuery('#<?php echo $jqOption[1] ?>_').html(option).show().selectpicker('refresh');


                    })
            <?php
            for ($c = ($_POST['contaSel'] + 2); $c < 10; $c++) {
                ?>
                        jQuery(".<?php echo $c ?>").empty();//retira os elementos antigos
                        var option = document.createElement('option');
                        jQuery(option).attr({value: ''});
                        jQuery(option).append('carregando');
                        jQuery(".<?php echo $c ?>").append(option);
                <?php
            }
            ?>

                }
                function sortByValue(jsObj) {
                    var sortedArray = [];
                    for (var i in jsObj)
                    {
                        // Push each JSON Object entry in array by [value, key]
                        sortedArray.push([jsObj[i], i]);
                    }
                    return sortedArray.sort();
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
            return formErp::select($name, $options_, $titulo, $post, $form, $hidden, $atrib);
        } else {
            $t = !empty($titulo) ? " [$titulo]" : "";
            echo 'Select Não configurado'.$t;
        }
    }

    /**
     * 
     * @param type $name
     * @param string $options [mínimo,máximo.posfixo, pós fixo]
     * @param type $titulo
     * @param type $post
     * @param type $form
     * @param type $hidden
     * @param type $atrib
     * @param type $style
     * @return type
     */
    public static function selectNum($name, $options, $titulo = NULL, $post = NULL, $form = NULL, $hidden = NULL, $atrib = NULL, $style = NULL, $jqOption = null) {
        for ($c = $options[0]; $c <= $options[1]; $c++) {
            $opt[$c] = $c . @$options[2];
        }
        return formErp::select($name, $opt, $titulo, $post, $form, $hidden, $atrib, $style, $jqOption);
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
            $post = formErp::extratPost($name);
        }
        $chk = '<input type="hidden" name="' . $name . '" value="0" />';
        $chk .= '<label class="container">';
        if (!empty($titulo)) {
            $chk .= '<span style="font-size: 14px; padding-left: 15px">' . $titulo . '</span>';
        }
        $chk .= '<input ' . ($post == $value ? "checked" : '') . ' type="checkbox" name="' . $name . '" value="' . $value . '" ' . $atrib . ' />';
        $chk .= '<span class="checkmark"></span>';
        $chk .= '</label>';

        return $chk;
    }

    /**
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $atrib qualque atributo dentro do select
     * @param type $titulo no formulario vai na frente do input
     * @param type $style: pertence a DIV e não ao input
     */
    public static function checkboxJq($name, $value, $titulo = NULL, $post = NULL, $atrib = NULL, $style = NULL) {
        $id = uniqid();
        if (empty($post)) {
            $post = formErp::extratPost($name);
        }
        $chk = '<label class="container">';
        $chk .= '<span style="font-size: 14px; padding-left: 15px">' . $titulo . '</span>';
        $chk .= '<input onclick="chec' . $id . '()" ' . (($post == $value) ? "checked" : '') . ' type="checkbox" ' . $atrib . ' />';
        $chk .= '<span class="checkmark"></span>';
        $chk .= '</label>';
        $chk .= '<input id="' . $id . '" type="hidden" name="' . $name . '" value="' . intval($post) . '" />';
        $chk .= '<script> function chec' . $id . '() { if(jQuery(\'#' . $id . '\').val() == \'' . $value . '\'){  jQuery(\'#' . $id . '\').val(0);  }else{  jQuery(\'#' . $id . '\').val( \'' . $value . '\') } }</script>';
        ?>

        <?php
        return $chk;
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
            $post = formErp::extratPost($name);
        }
        ?>
        <label class="container">
            <span style="font-size: 14px; padding-left: 15px;"><?php echo $titulo ?></span> 
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
        if (empty($class) && in_array($value, ['Apagar', 'Excluir'])) {
            $class = 'btn btn-outline-danger';
        } elseif (empty($class)) {
            $class = 'btn btn-info';
        }
        $glyphicon = formErp::glyphiconButton($value);
        if ($target == 1) {
            $target = "target=\"_blank\"";
        } elseif (!empty($target)) {
            $target = "target=\"$target\"";
        }
        //id para o javaScript
        $id = uniqid("form");
        if (!empty($token)) {
            $hidden = array_merge($hidden, $token);
        }
        $submit = "<form id=\"$id\" action = \"$location\" $target method = \"POST\">";
        if (!empty($hidden)) {

            $submit .= formErp::hidden($hidden, 1);
        }
        if (empty($token) || $value == 'Editar') {
            $submit .= "<button value = \"$value\" class=\"$class\" type=\"submit\" style=\"; " . $style . "\">" . @$glyphicon . (empty($buttonName) ? $value : $buttonName) . "</button>";
        } else {
            $submit .= "<button value = \"$value\" class=\"$class\" type=\"button\" onclick=\"formjs('$id','" . (empty($msg) ? $value : $msg) . "')\"  style=\"white-space: nowrap;" . $style . "\" >" . @$glyphicon . (empty($buttonName) ? $value : $buttonName) . "</button>";
        }
        $submit .= "</form>";

        return $submit;
    }

    public static function glyphiconButton($value) {
        $gly = [
            'Salvar' => '<img src="' . HOME_URI . '/includes/images/icons/check-circle.svg" alt = " "/>&nbsp;&nbsp;',
            'Apagar' => '<img src="' . HOME_URI . '/includes/images/icons/x-circle.svg" alt = " "/>&nbsp;&nbsp;',
            'Acessar' => '<img src="' . HOME_URI . '/includes/images/icons/arrow-right-circle.svg" alt = " "/>&nbsp;&nbsp;',
            'Editar' => '<img src="' . HOME_URI . '/includes/images/icons/dash-circle.svg" alt = " "/>&nbsp;&nbsp;'
        ];
        // if (!empty($gly[$value]))
        //  return $gly[$value];
        return;
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

    public static function textarea($name = NULL, $post = NULL, $titulo = NULL, $height = '100', $editor = NULL) {
        if (empty($editor)) {
            ?>
            <div class="input-group">
                <span class="input-group-text"><?= @$titulo ?></span>
                <textarea style="height: <?= @$height ?>px" name="<?= $name ?>" class="form-control" aria-label="With textarea"><?= $post ?></textarea>
            </div>
            <?php
        } else {
            ?>
            <script src="<?php echo HOME_URI ?>/app/ckeditor/ckeditor.js"></script>
            <textarea style="width: 100%; height: <?php echo $height ?>px" id="<?php echo $editor ?>" name="<?php echo $name ?>" ><?php echo $post ?></textarea>
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
                $token = formErp::token($table, 'delete');
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
                    unset($hiddenDel['modal']);
                    $array[$k]['apagar'] = formErp::submit('Apagar', $token, $hiddenDel);
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
                    $array[$k]['acessar'] = formErp::submit('Acessar', NULL, $hiddenAc, $location, $target, $msg, NULL, NULL, $buttonName);
                } else {
                    $array[$k]['acessar'] = formErp::button('Acessar', NULL, str_replace('%', "'" . toolErp::varGet($hiddenAc) . "'", $onclick), NULL, NULL, NULL, NULL, NULL, $buttonName);
                }
            }
            return $array;
        }
    }

    public static function buttonLong($text, $glyphicon = NULL, $onclick = NULL, $btn = 'info', $tooltip = NULL) {
        if (empty($glyphicon)) {
            $glyphicon = formErp::glyphiconButton($text);
        } else {
            $glyphicon = '<img src="' . HOME_URI . '/includes/images/icons/' . $glyphicon . '.svg" alt = " "/>';
        }

        if (!empty($onclick)) {
            $onclick = ' onclick="' . $onclick . '" ';
            $type = 'button';
        } else {
            $type = 'submit';
        }
        if (!in_array($btn, ['outline-warning', 'outline-info'])) {
            $color = 'white';
        } else {
            $color = 'black';
        }
        if (!empty($tooltip)) {
            $tooltip = ' data-toggle="tooltip" data-placement="top" title="' . $tooltip . '" ';
        }
        $b = '<button type="' . $type . '" class="btn btn-' . $btn . '" ' . $onclick . ' style="white-space: nowrap; width: 100%; text-align: left; color:' . $color . '" ' . $tooltip . ' >'
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

    public static function button($text = NULL, $glyphicon = NULL, $onclick = NULL, $btn = NULL, $name = NULL, $value = NULL, $width = NULL, $tooltip = NULL, $buttonName = NULL, $atrib = NULL) {
        if (empty($btn)) {
            $btn = 'info';
        }
        if (empty($atrib)){
            $atrib = '';
        }

        if (empty($glyphicon)) {
            $glyphicon = formErp::glyphiconButton($text);
        } else {
            $glyphicon = '<img src="' . HOME_URI . '/includes/images/icons/' . $glyphicon . '.svg" alt = " "/>';
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
        if (!in_array($btn, ['warning', 'info', 'Dark', 'Secondary'])) {
            $color = 'white';
        } else {
            $color = 'black';
        }

        if (!empty($tooltip)) {
            $tooltip = ' data-toggle="tooltip" data-placement="top" title="' . $tooltip . '" ';
        }
        $b = '<button type="' . $type . '" class="btn btn-' . $btn . ' " ' . $onclick . ' '.$atrib.' style="white-space: nowrap; width: ' . $width . '%; text-align: left; color:' . $color . '" ' . (empty($value) ? '' : 'value="' . $value . '"') . ' ' . (empty($name) ? '' : 'name="' . $name . '"') . '  ' . $tooltip . '>'
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

    public static function dropDownList($name, $options, $titulo = NULL, $post = NULL, $form = NULL, $hidden = NULL, $atrib = NULL, $style = NULL) {
        if (empty($options)) {
            $options = [];
        }
        if (empty($post)) {
            $post = formErp::extratPost($name);
        }
        if (!empty($name)) {
            $idForm = formErp::extraName($name) . '_';
        }
        $jsName = formErp::extraName($name);
        if (!empty($form)) {
            ?>
            <form name="<?php echo $jsName . 'Form' ?>" action="<?php echo ($form != 1 ? $form : '') ?>" method="POST">
                <?php
            }
            if (!empty($hidden)) {
                echo formErp::hidden($hidden);
            }
            ?>
            <div class="input-group mb-3">
                <?php
                if (!empty($titulo)) {
                    if (is_array($titulo)) {
                        $titulo_ = $titulo[0];
                        $tituloOption = $titulo[1];
                    } else {
                        $titulo_ = $titulo;
                    }
                    ?>

                    <div class="input-group-prepend">
                        <label class="input-group-text" for="inputGroupSelect01"><?= $titulo ?></label>
                    </div>
                    <?php
                }
                $onchange = "if(document." . $jsName . "Form) {document." . $jsName . "Form.submit()}";
                ?>
                <select class="form-select" id="<?= $idForm ?>" class="custom-select" <?php echo $atrib ?> name="<?php echo $name ?>" onchange="<?= $onchange ?>" style="<?php echo $style ?>; height: 38px"  >
                    <option value=""><?php echo @$tituloOption ?></option>
                    <?php
                    if (!is_array(current($options))) {
                        foreach ($options as $k => $v) {
                            ?>
                            <option style="z-index: 999" <?php echo ($post == $k ? 'selected' : '') ?> value="<?php echo $k ?>"><?php echo $v ?></option>
                            <?php
                        }
                    } else {
                        foreach ($options as $ki => $vi) {
                            ?>
                            <optgroup label="<?php echo $ki ?>">
                                <?php
                                foreach ($vi as $k => $v) {
                                    ?>
                                    <option  <?php echo ($post == $k ? 'selected' : '') ?> value="<?php echo $k ?>"><?php echo $v ?></option>
                                    <?php
                                }
                                ?>
                            </optgroup>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <?php
            if (!empty($form)) {
                ?>
            </form>
            <?php
        }
    }

    public static function limit($limit = [0, 1000])
    {
        return " LIMIT $limit[0], $limit[1] ";
    }
}
