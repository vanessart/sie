<?php
/**
 * está classe está para ser disativada use a classe formulario
 */
class formOld {

    /**
     * 
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select
     * @param type $atrib qualque atributo dentro do select
     * @param type $form cria um form sem botão (onchange) 1 = action="" qualque outro valor vira o URL
     * @param type $hidden inclui input oculto formato array (key = name; valor = valor)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @return string
     */
    public static function select($name, $options, $atrib = NULL, $form = NULL, $hidden = NULL, $post = NULL) {
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
        $select = NULL;
        if (!empty($form)) {
            $select.="<form name=\"" . $name . "Form\" action=\"" . ($form != 1 ? $form : '') . "\" method=\"POST\">";
        }
        if (!empty($hidden)) {
            $select.= formOld::hidden($hidden);
        }
        if (substr($name, -1) == ']') {
            $jsName = explode('[', $name);
            $jsName = substr($jsName[1], 0, -1);
        } else {
            $jsName = $name;
        }
        $select.="<select $atrib  name=\"$name\" onchange=\"document." . $jsName . "Form.submit()\">";
        $select.="<option></option>";
        foreach ($options as $k => $v) {
            $select.="<option " . ($namePost == $k ? 'selected' : '') . " value=\"$k\">$v</option>";
        }
        $select.="</select>";
        if (!empty($form)) {
            $select.="</form>";
        }
        return $select;
    }

    /**
     * 
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select
     * @param type $atrib qualque atributo dentro do select
     * @param type $form cria um form sem botão (onchange) 1 = action="" qualque outro valor vira o URL
     * @param type $hidden inclui input oculto formato array (key = name; valor = valor)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $msg mensagem alternativa para o alert
     * @return string
     */
    public static function submit($value = 'Enviar', $sqlkey = NULL, $hidden = NULL, $location = NULL, $target = NULL, $msg = NULL) {
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
            $submit.= formOld::hidden($hidden);
        }
        if (empty($sqlkey) || $value == 'Editar') {
            $submit.="<input  class=\"art-button\" type = \"submit\" value = \"$value\" />";
        } else {
            $submit.="<input class=\"art-button\" onclick=\"formjs('$id','" . (empty($msg) ? $value : $msg) . "')\" type = \"button\" value = \"$value\" />";
        }
        $submit.="</form>";

        return $submit;
    }
    /**
     * 
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select
     * @param type $atrib qualque atributo dentro do select
     * @param type $form cria um form sem botão (onchange) 1 = action="" qualque outro valor vira o URL
     * @param type $hidden inclui input oculto formato array (key = name; valor = valor)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $msg mensagem alternativa para o alert
     * @return string
     */
    public static function hidden($hidden) {

        $h = NULL;
        foreach ($hidden as $k => $v) {
            $h.="<input type=\"hidden\" name=\"$k\" value=\"" . $v . "\" />";
        }
        return $h;
    }
    /**
     * 
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select
     * @param type $atrib qualque atributo dentro do select
     * @param type $form cria um form sem botão (onchange) 1 = action="" qualque outro valor vira o URL
     * @param type $hidden inclui input oculto formato array (key = name; valor = valor)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $msg mensagem alternativa para o alert
     * @return string
     */
    public static function input($name, $atrib = NULL, $post = NULL) {
        if (empty($post)) {
            $pos = strpos($name, '[');
            if ($pos === false) {
                $namePost = @$_POST[$name];
            } else {
                $post = explode('[', $name);
                $namePost = @$_POST[$post[0]][substr($post[1], 0, -1)];
            }
        } else {
            $namePost = $post;
        }
        return "<input $atrib type=\"text\" name=\"$name\" value=\"" . data::converteBr(@$namePost) . "\" />";
    }
    /**
     * 
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select
     * @param type $atrib qualque atributo dentro do select
     * @param type $form cria um form sem botão (onchange) 1 = action="" qualque outro valor vira o URL
     * @param type $hidden inclui input oculto formato array (key = name; valor = valor)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $msg mensagem alternativa para o alert
     * @return string
     */
    public static function buttonSubmit($value, $atrib = NULL) {

        return "<input $atrib type=\"submit\" value=\"$value\" />";
    }
    /**
     * 
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select
     * @param type $atrib qualque atributo dentro do select
     * @param type $form cria um form sem botão (onchange) 1 = action="" qualque outro valor vira o URL
     * @param type $hidden inclui input oculto formato array (key = name; valor = valor)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $msg mensagem alternativa para o alert
     * @return string
     */
    public static function sexo($name = 'id_sexo', $param = 'id_sexo') {
        $sql = "select $param, n_sexo FROM sexo";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $options[$v[$param]] = $v['n_sexo'];
        }

        return formOld::select($name, $options);
    }
    /**
     * 
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select
     * @param type $atrib qualque atributo dentro do select
     * @param type $form cria um form sem botão (onchange) 1 = action="" qualque outro valor vira o URL
     * @param type $hidden inclui input oculto formato array (key = name; valor = valor)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $msg mensagem alternativa para o alert
     * @return string
     */
    public static function tipoUser($name = 'fk_id_tp', $post = NULL) {
        $sql = "select id_tp, n_tp FROM tipo_user";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $options[$v['id_tp']] = $v['n_tp'];
        }

        return formOld::select($name, $options, NULL, NULL, NULL, NULL, $post);
    }

    public static function civil($name = 'id_civil', $param = 'id_civil') {
        $sql = "select $param, n_civil FROM civil";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $options[$v[$param]] = $v['n_civil'];
        }

        return formOld::select($name, $options);
    }
    /**
     * 
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select
     * @param type $atrib qualque atributo dentro do select
     * @param type $form cria um form sem botão (onchange) 1 = action="" qualque outro valor vira o URL
     * @param type $hidden inclui input oculto formato array (key = name; valor = valor)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $msg mensagem alternativa para o alert
     * @return string
     */
    public static function postArray($name) {
        $pos = strpos($name, '[');
        if ($pos === false) {
            $namePost = @$_POST[$name];
        } else {
            $post = explode('[', $name);
            $namePost = @$_POST[$post[0]][substr($post[1], 0, -1)];
        }
        return $namePost;
    }
    /**
     * 
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select
     * @param type $atrib qualque atributo dentro do select
     * @param type $form cria um form sem botão (onchange) 1 = action="" qualque outro valor vira o URL
     * @param type $hidden inclui input oculto formato array (key = name; valor = valor)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $msg mensagem alternativa para o alert
     * @return string
     */
    public static function estado($name = 'id_estado', $param = 'id_estado') {
        $sql = "select $param, n_estado FROM estados";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $options[$v[$param]] = $v['n_estado'];
        }

        return formOld::select($name, $options);
    }
    /**
     * 
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select
     * @param type $atrib qualque atributo dentro do select
     * @param type $form cria um form sem botão (onchange) 1 = action="" qualque outro valor vira o URL
     * @param type $hidden inclui input oculto formato array (key = name; valor = valor)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $msg mensagem alternativa para o alert
     * @return string
     */
    public static function endereco($prefixo, $post = NULL, $id_pessoa = NULL, $width = NULL) {

        if (empty($width)) {
            $width = [
                'cep' => '150px',
                'logradouro' => '600px',
                'num' => '50px',
                'bairro' => '150px',
                'cidade' => '600px',
                'uf' => '50px',
                'complemento' => '600px'
            ];
        }

        if (empty($post)) {
            $post = $_POST;
        }

        if (empty($id_pessoa)) {
            $id_pessoa = @$post['fk_id_pessoa'];
        }

        $end['cep'] = formOld::input($prefixo . '[cep]', "style=\"width: " . $width['cep'] . "\"  id=\"cep\" onblur=\"consultacep(this.value)\" ", @$post['cep']);
        $end['logradouro'] = formOld::input($prefixo . '[logradouro]', 'id="logradouro" style="width: ' . $width['cep'] . '"', @$post['logradouro']);
        $end['num'] = formOld::input($prefixo . '[num]', 'style="width: ' . $width['num'] . '"  type="text" ', @$post['num']);
        $end['bairro'] = formOld::input($prefixo . '[bairro]', 'style="width: ' . $width['bairro'] . '"  id="bairro" ', @$post['bairro']);
        $end['cidade'] = formOld::input($prefixo . '[cidade]', 'id="localidade" style="width: ' . $width['cidade'] . '"', @$post['cidade']);
        $end['uf'] = formOld::input($prefixo . '[uf]', ' style="width: ' . $width['uf'] . '"   id="uf"', @$post['uf']);
        $end['complemento'] = formOld::input($prefixo . '[complemento]', '' . $width['complemento'] . '', @$post['complemento']);
        echo formOld::hidden([$prefixo . '[id_end]' => @$post['id_end'], $prefixo . '[fk_id_pessoa]' => @$id_pessoa]);
        return $end;
    }
    /**
     * 
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select
     * @param type $atrib qualque atributo dentro do select
     * @param type $form cria um form sem botão (onchange) 1 = action="" qualque outro valor vira o URL
     * @param type $hidden inclui input oculto formato array (key = name; valor = valor)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $msg mensagem alternativa para o alert
     * @return string
     */
    public static function selectNum($name, $max, $post = NULL, $atrib = NULL, $form = NULL, $hidden = NULL) {
        for ($c = 0; $c <= $max; $c++) {
            $options[$c] = $c;
        }

        return formOld::select($name, $options, NULL, NULL, NULL, $post);
    }
    /**
     * 
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select
     * @param type $atrib qualque atributo dentro do select
     * @param type $form cria um form sem botão (onchange) 1 = action="" qualque outro valor vira o URL
     * @param type $hidden inclui input oculto formato array (key = name; valor = valor)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $msg mensagem alternativa para o alert
     * @return string
     */
    public static function checkbox($name, $value, $post = NULL) {
        $ch = "<input type=\"hidden\" name=\"$name\" value=\"0\" />";
        $ch.="<input " . ($post == $value ? "checked" : '') . " type = \"checkbox\" name = \"$name\" value = \"$value\" />";
        return $ch;
    }
    /**
     * 
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select
     * @param type $atrib qualque atributo dentro do select
     * @param type $form cria um form sem botão (onchange) 1 = action="" qualque outro valor vira o URL
     * @param type $hidden inclui input oculto formato array (key = name; valor = valor)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $msg mensagem alternativa para o alert
     * @return string
     */
    public static function radio($name, $value, $post = NULL) {
        $ch = "<input " . ($post == $value ? "checked" : '') . " type = \"radio\" name = \"$name\" value = \"$value\" />";
        return $ch;
    }
    /**
     * 
     * @param type $name name do select e do form (para submit sem botão)
     * @param type $options opções do select - array key = value e valor = mostra no select
     * @param type $atrib qualque atributo dentro do select
     * @param type $form cria um form sem botão (onchange) 1 = action="" qualque outro valor vira o URL
     * @param type $hidden inclui input oculto formato array (key = name; valor = valor)
     * @param type $post altera o valor do post, o valor padrao é o $name
     * @param type $msg mensagem alternativa para o alert
     * @return string
     */
    public static function selectGet($table, $name = NULL, $post = NULL, $atrib = NULL, $form = NULL, $hidden = NULL, $options = NULL, $where = NULL) {
        if (empty($options)) {
            $col = sql::columns($table);
            foreach ($col as $k => $v) {
                if (substr($v, 0, 3) == 'id_') {
                    $fk = $v;
                } elseif (substr($v, 0, 2) == 'n_') {
                    $value = $v;
                }
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

        return formOld::select($name, $options_, $atrib, $form, $hidden, $post);
    }

}
