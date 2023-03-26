<?php

/**
 * Formulario utilizando a classa bootstrap
 * para formularios criptografados o uso desta classe é obrigatório
 */
class formulario {

    /**
     *
     * @param type $hidden Array key => valor
     * @return string
     */
    public static function hidden($hidden) {


        $h = NULL;
        foreach ($hidden as $k => $v) {
            $k = tool::extratPost($k);
            @$h .= "<input type=\"hidden\" name=\"$k[2]\" value=\"" . (str_replace('"', "''", $v)) . "\" />";
        }
        return $h;
    }

    /**
     *
     * @param type $name Nome do campo e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select
     * @param type $atrib qualque atributo dentro do select
     * @param type $form cria um form sem botão (onchange) 1 = action="" qualque outro valor vira o URL
     * @param type $hidden inclui input oculto formato array (key = name; valor = valor)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $titulo no formulario vai na frente do input
     * @param type $style: pertence a DIV e não ao input
     * @param type $placeholder
     */
    public static function input($name = NULL, $titulo = NULL, $style = NULL, $post = NULL, $atrib = NULL, $placeholder = NULL, $type='text') {
        $name = tool::extratPost($name);
        if (empty(@$post) && !isset($post)) {
            $post = @$name[3];
        }
        ?>
        <div class="input-group" style="<?php echo $style ?>">
            <span class="input-group-addon " id="basic-addon1" ><?php echo $titulo ?></span>
            <input type="<?= $type ?>" name="<?php echo $name[2] ?>" value="<?php echo $post ?>" class="form-control" placeholder="<?php echo @$placeholder ?>" aria-describedby="basic-addon1" <?php echo $atrib ?>>
        </div>
        <?php
    }

    public static function date($name = NULL, $titulo = NULL, $style = NULL, $post = NULL, $atrib = NULL, $placeholder = NULL) {
        $name = tool::extratPost($name);
        if (empty(@$post) && !isset($post)) {
            $post = @$name[3];
        }
        ?>
        <div class="input-group" style="<?php echo $style ?>">
            <span class="input-group-addon " id="basic-addon1" ><?php echo $titulo ?></span>
            <input type="date" style="line-height:18px" name="<?php echo $name[2] ?>" value="<?php echo $post ?>" class="form-control" placeholder="<?php echo @$placeholder ?>" aria-describedby="basic-addon1" <?php echo $atrib ?>>
        </div>
        <?php
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
     * @param type $titulo no formulario vai na frente do input
     * @param type $style: pertence a DIV e não ao input
     * @param type $placeholder
     */
    public static function select($name, $options, $titulo = NULL, $post = NULL, $form = NULL, $hidden = NULL, $atrib = NULL, $style = NULL) {
        //cria opção de números
        if (is_numeric($options)) {

            $max = $options;
            $options = NULL;
            for ($c = 0; $c <= $max; $c++) {
                $options[$c] = $c;
            }
        }

        $name = tool::extratPost($name);

        if (empty(@$post)) {
            $namePost = @$name[3];
        } else {
            $namePost = $post;
        }

        $jsName = $name[1];

        if (!empty($form)) {
            ?>
            <form name="<?php echo $jsName . 'Form' ?>" action="<?php echo ($form != 1 ? $form : '') ?>" method="POST">
                <?php
            }
            if (!empty($hidden)) {
                echo formulario::hidden($hidden);
            }

            if (count($options) > 50) {
                ?>

                <!-- Latest compiled and minified CSS -->
                <link rel="stylesheet" href="<?php echo HOME_URI; ?>/views/_css/bootstrap-select.min.css">
                <!--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">-->
                <!-- Latest compiled and minified JavaScript -->
                <script src="<?php echo HOME_URI; ?>/views/_js/bootstrap-select.min.js"></script>
               <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>-->
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
   <?php
            }
            ?>
            <div class="input-group" >
                <span class="input-group-addon" id="basic-addon1" ><?php echo $titulo ?></span>
                <select  class="form-class" <?php echo count($options) > 50 ? 'data-live-search="true"' : '' ?> <?php echo $atrib ?> name="<?php echo $name[2] ?>" onchange="document.<?php echo $jsName ?>Form.submit()" style="width: 100% <?php echo $style ?>; height: 30px ">
                    <option></option>
                    <?php
                    if (!is_array(current($options))) {
                        foreach ($options as $k => $v) {
                            ?>
                            <option <?php echo (@$namePost == $k ? 'selected' : '') ?> value="<?php echo $k ?>"><?php echo $v ?></option>
                            <?php
                        }
                    } else {
                        foreach ($options as $ki => $vi) {
                            ?>
                            <optgroup label="<?php echo $ki ?>">
                                <?php
                                foreach ($vi as $k => $v) {
                                    ?>
                                    <option <?php echo (@$namePost == $k ? 'selected' : '') ?> value="<?php echo $k ?>"><?php echo $v ?></option>
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

    /**
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select; se for um número cria opção de números
     * @param type $atrib qualque atributo dentro do select
     * @param type $form cria um form sem botão (onchange) 1 = action="" qualque outro valor vira o URL
     * @param type $hidden inclui input oculto formato array (key = name; valor = valor)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @return string
     */
    public static function selectInput($name, $options, $titulo = NULL, $post = NULL, $atrib = NULL, $style = NULL, $form = NULL, $hidden = NULL) {
        $unic = uniqid($name);
        if (is_numeric($options)) {

            $max = $options;
            $options = NULL;
            for ($c = 0; $c <= $max; $c++) {
                $options[$c] = $c;
            }
        }

        $name = tool::extratPost($name);

        if (empty(@$post)) {
            $post = @$name[3];
        } else {
            $post = $post;
        }
        $jsName = $name[1];

        if (!empty($form)) {
            ?>
            <form name="<?php echo $name[1] . 'Form' ?>" action="<?php echo ($form != 1 ? $form : '') ?>" method="POST">
                <?php
            }
            if (!empty($hidden)) {
                echo formulario::hidden($hidden);
            }
            ?>
            <div class="input-group"  style="<?php echo $style ?>">
                <div class="input-group-btn">
                    <button class="btn btn-default " type="button"><?php echo $titulo ?></button>
                    <button class="btn btn-default dropdown-toggle" aria-expanded="false" aria-haspopup="true" data-toggle="dropdown" type="button">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" style="max-height: 300px;overflow: auto">
                        <?php
                        foreach ($options as $opt) {
                            ?>
                            <li>
                                <a onclick="document.getElementById('<?php echo $unic ?>').value = '<?php echo $opt ?>';<?php echo!empty($form) ? 'document.' . $name[1] . 'Form.submit();' : '' ?>" href="#"><?php echo $opt ?></a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <input <?php echo $atrib ?> id="<?php echo $unic ?>" name="<?php echo $name[2] ?>" class="form-control" type="text" value="<?php echo @ $post ?>" style="background-color: white">
            </div>
            <?php
            if (!empty($form)) {
                ?>
            </form>
            <?php
        }
    }

    /**
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $atrib qualque atributo dentro do select
     * @param type $titulo no formulario vai na frente do input
     * @param type $style: pertence a DIV e não ao input
     */
    public static function checkbox($name, $value, $titulo = NULL, $post = NULL, $style = NULL, $atrib = NULL) {
        $post = tool::postFormat($name, $post);
        ?>
        <input type="hidden" name="<?php echo $name ?>" value="0" />
        <div class="input-group">
            <span class="input-group-addon">
                <input <?php echo ($post == $value ? "checked" : '') ?> type="checkbox" name="<?php echo $name ?>" value="<?php echo $value ?>" <?php echo $atrib ?> />
            </span>
            <input style="<?php echo $style ?>" readonly type="text" class="form-control " value="<?php echo $titulo ?>">
        </div><!-- /input-group -->
        <?php
    }

    public static function label($titulo = null, $style = null, $atrib = NULL) {
        ?>
        <div class="input-group" style="<?php echo $style ?>">
            <span style="<?php echo $style ?>" <?php echo $atrib ?> class="input-group-addon " id="basic-addon1" ><?php echo $titulo ?></span>
        </div>
        <?php
    }

    public static function button($value = 'Salvar', $name = null, $type = 'submit', $style = null, $atrib = null) {
        if (empty($type)) {
            $type = 'submit';
        }
        ?>
        <div class="input-group-btn">
            <input class="btn btn-default" type="<?php echo $type ?>" value="<?php echo $value ?>" name="<?php echo $name ?>" <?php echo $atrib ?> style="<?php echo $style ?>"/>
        </div>
        <?php
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
    public static function selectDB($table, $name = NULL, $titulo = NULL, $post = NULL, $atrib = NULL, $form = NULL, $hidden = NULL, $options = NULL, $where = NULL) {
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
            return formulario::select($name, $options_, $titulo, $post, $form, $hidden, $atrib);
        } else {
            echo 'Select Não configurado';
        }
    }

    /*
     * metodo fora de uso
     */

    public static function telefones($tel = NULL) {
        ?>
        <div class="row">
            <fieldset id="inputs_adicionais" style="border: none">
                <?php
                if (!empty($tel)) {
                    foreach ($tel as $v) {
                        ?>
                        <div class="col-md-2">
                            <div class="input-group"  style="width: 100%">
                                <div style="text-align: center">
                                    Telefone
                                    <select name="tipo[]">
                                        <option <?php echo $v['tipo'] == 'Fixo' ? 'selected' : '' ?>>Fixo</option>
                                        <option <?php echo $v['tipo'] == 'Celular' ? 'selected' : '' ?>>Celular</option>
                                    </select>
                                </div>
                                <input type="hidden" name="id_tel[]" value="<?php echo $v['id_tel'] ?>" />
                                <input type="text" name="tel_esc[]" value="<?php echo $v['num'] ?>" class="form-control" aria-describedby="basic-addon1">
                                <a href="#" onclick="deltel('<?php echo $v['id_tel'] ?>')" style="font-size: 10px; margin-left: 25%">Excluir telefone acima</a>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
                <div class="col-md-2">
                    <div class="input-group"  style="width: 100%">
                        <div style="text-align: center">
                            Telefone
                            <select name="tipo[]">
                                <option>Fixo</option>
                                <option>Celular</option>
                            </select>
                        </div>
                        <input onclick="maistel()" type="text" name="tel_esc[]" value="" class="form-control" aria-describedby="basic-addon1">
                    </div>
                </div>
            </fieldset>
        </div>

        <?php
    }

    /*
     * metodo fora de uso
     */

    public static function telefonesScript($id = NULL, $hidden = NULL) {
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#inputs_adicionais').delegate('a', 'click', function (e) {
                    e.preventDefault();
                    $(this).parent('div').remove();
                });
            });

            function maistel() {
                var input = '<div  class="col-md-2"><div style="text-align: center">Telefone <select name="tipo[]"><option>Fixo</option><option>Celular</option></select></div><input onclick="maistel()" type="text" name="tel_esc[]" value="" class="form-control" aria-describedby="basic-addon1"> <a href="#" class="remove" style="font-size: 10px">Fechar o campo acima</a></div>';

                $('#inputs_adicionais').append(input);
            }

            function deltel(id) {
                if (confirm("Excluir o telefone?")) {
                    document.getElementById("hdeltel").value = id;
                    document.deltelform.submit();
                }
            }
        </script>
        <form name="deltelform" method="POST">
            <?php
            echo DB::hiddenKey('telefones', 'delete');
            if (!empty($hidden)) {

                echo formulario::hidden($hidden);
            }
            ?>
            <input type="hidden" name="id" value="<?php echo @$id ?>" />
            <input id="hdeltel" type="hidden" name="1[id_tel]" value="" />
            <input type="hidden" name="deltel" value="deltel" />
        </form>
        <?php
    }

    /**
     *
     * @param type $name nome do campo
     * @param type $max numero maximo que aparecerá
     * @param type $titulo no form aparecerá em frenya ao select
     * @param type $post var padrão - se não houver é $_POST[$name]
     * @param type $form 1 para submit
     * @param type $hidden icluir campos ocultos caso form = 1
     * @param type $atrib atributos ca div
     * @param type $style
     * @return type
     */
    public static function selectNum($name, $max, $titulo = NULL, $post = NULL, $form = NULL, $hidden = NULL, $atrib = NULL, $style = NULL) {

        if (is_array($max)) {
            $min = $max[0];
            @$inverte = $max[2];
            $max = $max[1];
        } else {
            $min = 0;
            $inverte = NULL;
        }
        if (empty($inverte)) {
            for ($c = $min; $c <= $max; $c++) {
                $options[$c] = $c;
            }
        } else {
            for ($c = $max; $c >= $min; $c--) {
                $options[$c] = $c;
            }
        }


        return formulario::select($name, $options, $titulo, $post, $form, $hidden, $atrib, $style);
    }

    /**
     * formata o campo data
     * @param type $id
     * @return type
     */
    public static function dataConf($id = 1) {
        return 'style="width: 200px" id="data' . $id . '"  OnKeyUp="mascaraData(\'data' . $id . '\', event);"  maxlength="10" size="10" min="10" ';
    }

    /**
     * cria um formulario com campos ocultos e um botao submit
     * @param type $value valor do botao submit
     * @param type $sqlkey token para a classe DB
     * @param type $hidden campos oculto - tem que ser array
     * @param type $location action do post
     * @param string $target 1 para abrir em nova aba
     * @param type $msg alternativa para o alert
     * @return string
     */
    public static function submit($value = 'Enviar', $sqlkey = NULL, $hidden = NULL, $location = NULL, $target = NULL, $msg = NULL, $class = 'btn btn-info') {
        if ($target == 1) {
            $target = "target=\"_blank\"";
        }
        //id para o javaScript
        $id = uniqid("form");
        if (!empty($sqlkey)) {
            $hidden = array_merge($hidden, $sqlkey);
        }
        $submit = "<form id=\"$id\" action = \"$location\" $target method = \"POST\">";
        if (!empty($hidden)) {

            $submit .= formulario::hidden($hidden);
        }
        if (empty($sqlkey) || $value == 'Editar') {
            $submit .= "<button value = \"$value\" class=\"$class\" type=\"submit\">$value</button>";
        } else {
            $submit .= "<button value = \"$value\" class=\"$class\" type=\"button\" onclick=\"formjs('$id','" . (empty($msg) ? $value : $msg) . "')\">$value</button>";
        }
        $submit .= "</form>";

        return $submit;
    }

    /**
     * cria dois formulários : editar e apagar
     * @param type $array dados do relatorio
     * @param type $tableDelete tabela em que a tupla será deletada
     * @param type $id id da busta e do delete
     * Apagar = del
     * Editar = edit
     * @return type
     */
    public static function editDel($array, $tableDelete, $id, $hidden = NULL) {
        $sqlkey = DB::sqlKey($tableDelete, 'delete');
        foreach ($array as $k => $v) {
            unset($hidden_);
            if (empty($hidden)) {
                $hidden_ = [$id => $v[$id]];
                $hidden_del = ['1[' . $id . ']' => $v[$id]];
            } else {
                $id_ = [$id => $v[$id]];
                $hidden_ = array_merge($id_, $hidden);
                $id_del = ['1[' . $id . ']' => $v[$id]];
                $hidden_del = array_merge($id_del, $hidden);
            }

            $array[$k]['edit'] = formulario::submit('Editar', NULL, $hidden_);
            $array[$k]['del'] = formulario::submit('Apagar', $sqlkey, $hidden_del);
        }

        return $array;
    }

    public static function checkboxSimples($name, $value, $titulo = NULL, $post = NULL, $atrib = NULL) {
        $post = tool::postFormat($name, $post);
        return '<label><input ' . ($post == $value ? "checked" : '') . ' type="checkbox" name="' . $name . '" value="' . $value . '" ' . $atrib . ' />' . $titulo . '</label>';
    }

    public static function buttonModal($value, $id = 'myModal', $class = "btn btn-info btn-lg") {
        ?>
        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#<?php echo $id ?>"><?php echo @$value ?></button>
        <?php
    }

    public static function exportarExcel($sql = NULL, $label = 'Exportar') {
        if (!empty($sql)) {
            $tokenSql = substr((date("yhdm") / 3.5288 * 68), 0, 20);
            ?>
            <form target="_blank" method="POST" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php">
                <input type="hidden" name="sql" value="<?php echo @$sql ?>" />
                <input type="hidden" name="tokenSql" value="<?php echo $tokenSql ?>" />
                <input class="btn btn-info" type="submit" value="<?php echo $label ?>" />
            </form>
            <?php
        } else {
            ?>
            <input class="btn btn-default" type="button" value="<?php echo $label ?>" />
            <?php
        }
    }

    public static function textarea($name = NULL, $post = NULL, $placeholder = NULL, $editor = 1) {
        $name = tool::extratPost($name);
        if (empty(@$post)) {
            $post = @$name[3];
        }
        ?>
        <script src="<?php echo HOME_URI ?>/app/ckeditor/ckeditor.js"></script>
        <textarea id="<?php echo $editor ?>" name="<?php echo $name[2] ?>"><?php echo empty($post) ? $placeholder : $post ?></textarea>
        <script>
            CKEDITOR.replace('<?php echo $editor ?>');
        </script>
        <?php
    }

    /**
     * formata o campo data
     * @param type $id
     * @return type
     */
    public static function dataFormat() {
        return 'style="width: 200px"  OnKeyUp="mascaraData(this, event);"  maxlength="10" size="10" min="10" ';
    }

}
