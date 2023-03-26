<?php

class crud {

    public $host, // Host da base de dados 
            $db_name, // Nome do banco de dados
            $password, // Senha do usuário da base de dados
            $user, // Usuário da base de dados
            $charset, // Charset da base de dados
            $pdo = null, // Nossa conexão com o BD
            $error = null, // Configura o erro
            $debug = false, // Mostra todos os erros 
            $last_id = null, // Último ID inserido
            $token,
            $where_field,
            $where_field_value,
            $datatoken;

    public function __construct(
    $host = HOSTNAME, $db_name = DB_NAME, $password = DB_PASSWORD, $user = DB_USER, $charset = DB_CHARSET, $debug = false
    ) {

        $this->host = $host;
        $this->db_name = $db_name;
        $this->password = $password;
        $this->user = $user;
        $this->charset = $charset;
        $this->debug = $debug;

        $this->connect();

        if (isset($_POST['formToken']) && !empty($_POST['formToken'])) {
            $this->formToken();
        }
    }

    private function formToken() {
        $this->datatoken = $this->tokenCheck();

        if (!empty($this->datatoken)) {

            if (!empty($this->datatoken['action_ft'])) {
                $table = $this->datatoken['table_ft'];
                $action = $this->datatoken['action_ft'];
                $hiddenAlert = $this->datatoken['hiddenAlert'];
                if (!empty($this->datatoken['hidden_ft'])) {
                    $hidden = unserialize($this->datatoken['hidden_ft']);
                }
                if (!empty($this->datatoken['col_ft'])) {
                    $col_ = unserialize($this->datatoken['col_ft']);
                }

                foreach ($_POST as $v) {
                    if (is_array($v)) {
                        $dados = $v;
                    }
                }

                if (!empty($dados) && !empty($hidden)) {
                    foreach ($hidden as $kh => $h) {
                        $dados[$kh] = $h;
                        $_POST[$kh] = $h;
                    }
                } elseif (!empty($hidden)) {
                    $dados = $hidden;
                }

                if ($action == 'delete') {
                    $dados = $this->normatize($table, $dados, @$col_);
                    $this->delete($table, $this->where_field, $this->where_field_value);
                } elseif ($action == 'ireplace') {
                    $this->ireplace($table, $dados, $hiddenAlert, @$col_);
                }
                $tk['dados'] = serialize($dados);
                $tk['at_ft'] = 0;
                $tk['dt_ft'] = date('Y-m-d H:i:s');
                $mongo = new mongoCrude();
                $mongo->update('formtoken', ['id_ft' => @$_POST['formToken']], $tk);
            } else {
                $this->token = $this->datatoken['table_ft'];
            }
        } else {
            tool::alert("Incompatibilidade de Token");
        }
    }

    public function tokenCheck($table = NULL, $disable = false) {
        if (!empty($_POST['formToken'])) {
            if (!empty($table)) {
                $filter['table_ft'] = $table;
                $table = " and table_ft like '$table' ";
            }
            $filter['id_ft'] = $_POST['formToken'];
            $filter['fk_id_pessoa'] = tool::id_pessoa();
            $filter['at_ft'] = '1';

            $mongo = new mongoCrude();
            @$this->datatoken = (array) $mongo->query('formtoken', $filter)[0];
            if (!empty($this->datatoken)) {

                if (!empty($disable)){
                    $tk['at_ft'] = 0;
                    $tk['dt_ft'] = date('Y-m-d H:i:s');
                    $mongo = new mongoCrude();
                    $mongo->update('formtoken', ['id_ft' => $filter['id_ft']], $tk);
                }

                return $this->datatoken;
            } else {
                return;
            }
        } else {
            return;
        }
    }

    final protected function connect() {

        $pdo_details = "mysql:host={$this->host};";
        $pdo_details .= "dbname={$this->db_name};";
        $pdo_details .= "charset={$this->charset};";

        try {

            $this->pdo = new PDO($pdo_details, $this->user, $this->password);

            if ($this->debug === true) {

                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            }

            unset($this->host);
            unset($this->db_name);
            unset($this->password);
            unset($this->user);
            unset($this->charset);
        } catch (PDOException $e) {

            // Verifica se devemos debugar
            if ($this->debug === true) {

                // Mostra a mensagem de erro
                echo "Erro: " . $e->getMessage();
            }

            die();
        }
    }

    public function query($stmt, $data_array = null) {

        $query = $this->pdo->prepare($stmt);
        $check_exec = $query->execute($data_array);

        if ($check_exec) {

            return $query;
        } else {

            $error = $query->errorInfo();
            $this->error = $error[2];

            return false;
        }
    }

    /**
     * insert - Insere valores
     *
     * Insere os valores e tenta retornar o último id enviado
     *
     * @since 0.1
     * @access public
     * @param string $table O nome da tabela
     * @param array ... Ilimitado número de arrays com chaves e valores
     * @return object|bool Retorna a consulta ou falso
     */
    public function insert($table, $fields, $hideAlert = NULL, $debug = NULL) {
        // Configura o array de colunas
        $cols = array();

        // Configura o valor inicial do modelo
        $place_holders = '(';

        // Configura o array de valores
        $values = array();

        // O $j will assegura que colunas serão configuradas apenas uma vez
        $j = 1;

        // Obtém os argumentos enviados
        $data = [$table, $fields];

        // É preciso enviar pelo menos um array de chaves e valores
        if (!isset($data[1]) || !is_array($data[1])) {
            return;
        }

        // Faz um laço nos argumentos
        for ($i = 1; $i < count($data); $i++) {

            // Obtém as chaves como colunas e valores como valores
            foreach ($data[$i] as $col => $val) {

                // A primeira volta do laço configura as colunas
                if ($i === 1) {
                    $cols[] = "`$col`";
                }

                if ($j <> $i) {
                    // Configura os divisores
                    $place_holders .= '), (';
                }

                // Configura os place holders do PDO
                $place_holders .= '?, ';
                if ($val === '') {
                    $val = NULL;
                }
                // Configura os valores que vamos enviar
                $values[] = $val;

                $j = $i;
            }

            // Remove os caracteres extra dos place holders
            $place_holders = substr($place_holders, 0, strlen($place_holders) - 2);
        }

        // Separa as colunas por vírgula
        $cols = implode(', ', $cols);

        // Cria a declaração para enviar ao PDO
        $stmt = "INSERT INTO `$table` ( $cols ) VALUES $place_holders) ";
        if (!empty($debug)) {
            echo "INSERT INTO `$table` ( $cols ) VALUES ('" . implode("','", $values) . "'    ) ";
        }
        // Insere os valores
        $insert = $this->query($stmt, $values);
        if (!empty($this->datatoken['debug']) || $this->debug == TRUE) {
            echo '<br /><br />-- Debug --<br /><br />';
            echo sql::debugPdo($stmt, $values);
            echo '<br /><br />---';
        }
        // Verifica se a consulta foi realizada com sucesso
        if ($insert) {
            if (empty($hideAlert))
                tool::alert("Salvo com sucesso!");
            // Verifica se temos o último ID enviado
            if (method_exists($this->pdo, 'lastInsertId') && $this->pdo->lastInsertId()
            ) {
                // Configura o último ID
                $this->last_id = $this->pdo->lastInsertId();
            }

            // Retorna a consulta
            return $this->last_id;
        } else {

            if (!empty($debug)) {
                echo $this->error;
            }

            if (empty($hideAlert))
                tool::alert("Lamento, algo não deu certo!");
        }

        // The end :)
        return;
    }

// insert

    /**
     * Update simples
     *
     * Atualiza uma linha da tabela baseada em um campo
     *
     * @since 0.1
     * @access protected
     * @param string $table Nome da tabela
     * @param string $where_field WHERE $where_field = $where_field_value
     * @param string $where_field_value WHERE $where_field = $where_field_value
     * @param array $values Um array com os novos valores
     * @return object|bool Retorna a consulta ou falso
     */
    public function update($table, $where_field, $where_field_value, $values, $hideAlert = NULL) {
        // Você tem que enviar todos os parâmetros
        if (empty($table) || empty($where_field) || empty($where_field_value)) {
            return;
        }

        // Começa a declaração
        $stmt = " UPDATE `$table` SET ";

        // Configura o array de valores
        $set = array();

        // Configura a declaração do WHERE campo=valor
        $where = " WHERE `$where_field` = ? ";

        // Você precisa enviar um array com valores
        if (!is_array($values)) {
            return;
        }

        // Configura as colunas a atualizar
        foreach ($values as $column => $value) {
            $set[] = " `$column` = ?";
        }

        // Separa as colunas por vírgula
        $set = implode(', ', $set);

        // Concatena a declaração
        $stmt .= $set . $where;

        // Configura o valor do campo que vamos buscar
        $values[] = $where_field_value;

        // Garante apenas números nas chaves do array
        $values = array_values($values);

        // Atualiza
        $update = $this->query($stmt, $values);
        if (!empty($this->datatoken['debug']) || $this->debug == TRUE) {
            echo '<br /><br />-- Debug --<br /><br />';
            echo sql::debugPdo($stmt, $values);
            echo '<br /><br />---';
        }
        // Verifica se a consulta está OK
        if ($update) {
            if (empty($hideAlert))
                tool::alert("Salvo com sucesso!");
            // Retorna a consulta
            return $update;
        } else {
            if (empty($hideAlert))
                tool::alert("Lamento, algo não deu certo!");
        }

        // The end :)
        return;
    }

    /**
     * REPLACE - Insere valores
     *
     * Insere os valores e tenta retornar o último id enviado
     *
     * @since 0.1
     * @access public
     * @param string $table O nome da tabela
     * @param array ... Ilimitado número de arrays com chaves e valores
     * @return object|bool Retorna a consulta ou falso
     */
    public function replace($table, $fields, $hideAlert = NULL) {
        // Configura o array de colunas
        $cols = array();

        // Configura o valor inicial do modelo
        $place_holders = '(';

        // Configura o array de valores
        $values = array();

        // O $j will assegura que colunas serão configuradas apenas uma vez
        $j = 1;

        // Obtém os argumentos enviados
        $data = func_get_args();
        unset($data[2]);
        // É preciso enviar pelo menos um array de chaves e valores
        if (!isset($data[1]) || !is_array($data[1])) {
            return;
        }

        // Faz um laço nos argumentos
        for ($i = 1; $i < count($data); $i++) {

            // Obtém as chaves como colunas e valores como valores
            foreach ($data[$i] as $col => $val) {

                // A primeira volta do laço configura as colunas
                if ($i === 1) {
                    $cols[] = "`$col`";
                }

                if ($j <> $i) {
                    // Configura os divisores
                    $place_holders .= '), (';
                }

                // Configura os place holders do PDO
                $place_holders .= '?, ';

                // Configura os valores que vamos enviar
                $values[] = $val;

                $j = $i;
            }

            // Remove os caracteres extra dos place holders
            $place_holders = substr($place_holders, 0, strlen($place_holders) - 2);
        }

        // Separa as colunas por vírgula
        $cols = implode(', ', $cols);

        // Cria a declaração para enviar ao PDO
        $stmt = "REPLACE INTO `$table` ( $cols ) VALUES $place_holders) ";

        // Insere os valores
        $replace = $this->query($stmt, $values);

        // Verifica se a consulta foi realizada com sucesso
        if ($replace) {
            if (empty($hideAlert))
                tool::alert("Salvo com sucesso!");
            // Verifica se temos o último ID enviado
            if (method_exists($this->pdo, 'lastInsertId') && $this->pdo->lastInsertId()
            ) {
                // Configura o último ID
                $this->last_id = $this->pdo->lastInsertId();
            }

            // Retorna a consulta
            return $this->last_id;
        } else {
            if (empty($hideAlert))
                tool::alert("Lamento, algo não deu certo!");
        }

        // The end :)
        return;
    }

// update

    /**
     * Delete
     *
     * Deleta uma linha da tabela
     *
     * @since 0.1
     * @access protected
     * @param string $table Nome da tabela
     * @param string $where_field WHERE $where_field = $where_field_value
     * @param string $where_field_value WHERE $where_field = $where_field_value
     * @return object|bool Retorna a consulta ou falso
     */
    public function delete($table, $where_field, $where_field_value, $hideAlert = NULL) {
        //backup e log
        $bk = sql::get($table, '*', [$where_field => $where_field_value]);
        foreach ($bk as $v) {
            $doc['table'] = $table;
            $doc['fields'] = serialize($v);
            $doc['fk_id_pessoa'] = tool::id_pessoa();
            $doc['dt_ld'] = date("Y-m-d");

            $mongo = new mongoCrude();
            $mongo->insert('log_del', $doc);

            log::logSet("Apagou dados da tabela " . @$tabela);
        }
        // Você precisa enviar todos os parâmetros
        if (empty($table) || empty($where_field) || empty($where_field_value)) {
            return;
        }

        // Inicia a declaração
        $stmt = " DELETE FROM `$table` ";

        // Configura a declaração WHERE campo=valor
        $where = " WHERE `$where_field` = ? ";

        // Concatena tudo
        $stmt .= $where;

        // O valor que vamos buscar para apagar
        $values = array($where_field_value);

        // Apaga
        $delete = $this->query($stmt, $values);
        if (!empty($this->datatoken['debug'])) {
            echo '<br /><br />-- Debug --<br /><br />';
            echo sql::debugPdo($stmt, $values);
            echo '---';
        }
        // Verifica se a consulta está OK
        if ($delete) {
            if (empty($hideAlert))
                tool::alert("Apagado com sucesso!");
            // Retorna a consulta
            return $delete;
        } else {
            if (empty($hideAlert))
                tool::alert("Lamento, algo não deu certo!");
        }

        return;
    }

    public function ireplace($table, $dados, $hideAlert = NULL, $col = NULL, $debug = NULL) {
        if (!empty($debug)) {
            $this->debug = TRUE;
        }
        $dados = $this->normatize($table, $dados, $col);

        $dados = $this->consistency($table, $dados);

        if (is_array($dados)) {
            if (!empty($this->where_field) && !empty($this->where_field_value)) {
                //verifica se a chave primária já existe
                $sql = "SELECT `$this->where_field` FROM `" . $table . "` WHERE `$this->where_field` = '$this->where_field_value' ";
                $query = $this->query($sql);
                $array = $query->fetch();

                if (!empty($array)) {
                    //chave primária já existe update
                    $this->update($table, $this->where_field, $this->where_field_value, $dados, $hideAlert);
                    $this->last_id = $this->where_field_value;
                } else {
                    $this->last_id = $this->insert($table, $dados, $hideAlert);
                }
            } else {
                $this->last_id = $this->insert($table, $dados, $hideAlert);
            }
            $_POST['last_id'] = $this->last_id;

            return $this->last_id;
        } else {
            tool::alert($dados);
        }
    }

    private function normatize($table, $dados, $col = NULL) {
        $this->where_field = NULL;
        $this->where_field_value = NULL;
        foreach ($dados as $key => $value) {
            if ($value == '') {
                $dados[$key] = NULL;
            }
            if (!empty($col)) {
                if (array_key_exists($key, $col)) {
                    $dados[$col[$key]] = $value;
                    unset($dados[$key]);
                }
            }
            //acha a chave primária
            if (substr($key, 0, 3) == 'id_') {
                $where_field = $key;
                $where_field_value = $value;
                // acha datas e converte para US
            } elseif (substr($key, 0, 3) == 'dt_') {
                $dados[$key] = data::converteUS($value);
            } elseif (is_array($value)) {
                $dados[$key] = base64_encode(serialize($value));
            }
        }
        if (!empty($where_field_value) && !empty($where_field)) {
            $this->where_field = $where_field;
            $this->where_field_value = $where_field_value;
        }

        return $dados;
    }

    private function consistency($table, $dados) {
        //validar pelo campo no DB, se hover classe referente a tabela na class consistency
        if (in_array($table, get_class_methods('consistency'))) {
            $dados = consistency::$table($dados);
        }


        return $dados;
    }

}
