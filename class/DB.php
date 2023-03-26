<?php

/**
 * Classe que dinamiza o acesso ao DB 
 */
class DB {

    /** DB properties */
    public $host, // Host da base de dados 
            $db_name, // Nome do banco de dados
            $password, // Senha do usuário da base de dados
            $user, // Usuário da base de dados
            $charset = 'utf8', // Charset da base de dados
            $pdo = null, // Nossa conexão com o BD
            $error = null, // Configura o erro
            $debug = false, // Mostra todos os erros 
            $last_id = null;        // Último ID inserido

    /**
     * Construtor da classe
     *
     * @since 0.1
     * @access public
     * @param string $host     
     * @param string $db_name
     * @param string $password
     * @param string $user
     * @param string $charset
     * @param string $debug
     */

    public function __construct(
    $host = null, $db_name = null, $password = null, $user = null, $charset = null, $debug = null
    ) {
        //não mexa na linha abaixo.
        //vc se acha esperto e vai mexer?
        //larga de ser otário e não mexa na linha abaixo
        echo '<div style="position: absolute">&nbsp;</div>';
        $this->host = empty($host) ? HOSTNAME : $host;
        $this->db_name = empty($db_name) ? DB_NAME : $db_name;
        $this->password = empty($password) ? DB_PASSWORD : $password;
        $this->user = empty($user) ? DB_USER : $user;
        $this->charset = empty($charset) ? DB_CHARSET : $charset;
        $this->debug = empty($debug) ? DEBUG : $debug;

        // Conecta
        $this->connect();
        //quarda o sqlToken da ultima página
        $_SESSION['sqlTokenPostOld'] = @$_SESSION['sqlTokenPost'];
        $_SESSION['sqlTokenPost'] = @$_SESSION['sqlToken'];
        unset($_SESSION['sqlToken']);

        //cria novo sqlToken
        $this->sqlKeySet();

        //receber os post e tratar com os dados previamente setados no sqlkey
        if (!empty($_POST['formToken'])) {

            if ($_POST['sqlToken'] == $_SESSION['sqlTokenPost']['sqlKey']) {
                $this->sqlPost($_SESSION['sqlTokenPost'], $_POST['formToken']);
            } elseif ($_POST['sqlToken'] == $_SESSION['sqlTokenPostOld']['sqlKey'] && empty($_SESSION['postSet'])) {
                $this->sqlPost($_SESSION['sqlTokenPostOld'], $_POST['formToken']);
            } else {
                ?>
                <script>
                    alert("Erro: Incompatibilidade de Token");
                </script>
                <?php
            }
        }

        //no primeiro acesso verifica se houve um post anterior
        $_SESSION['postSet'] = @$_POST['sqlToken'];

        $this->detect();
    }

    /**
     * //receber os post e tratar com os dados previamente setados no sqlkey
     * @param type $token
     * @param type $formToken
     * @return boolean
     */
    public function sqlPost($token, $formToken) {
        $v = $token;
        $includeToken = $v[$formToken]['include'];
        $tableToken = $v[$formToken]['table'];
        $actionToken = $v[$formToken]['action'];
        require_once ABSPATH . '/class/consis.php';
        //incluir um arquivo para validação personalizada ou tratamento dos dados
        if (!empty($includeToken)) {
            include ABSPATH . $includeToken;
        }

        foreach ($_POST as $kk => $vv) {
            //o metodo só processa dados dentro de um array ignorando o resto
            if (is_array($vv)) {
                @$validar = 1;
                if (is_numeric($kk)) {
                    $values[$kk] = $vv;
                } else {
                    //se a chave do post for uma letra ele vai criptografar
                    foreach ($vv as $kcr => $cr) {
                        $values[$kk][tool::dencrypt($kcr)] = $cr;
                    }
                }
                //o sistema poderá incluir em mais de uma tabela - não está pronto
                if (is_array($tableToken)) {
                    $table = $tableToken[$kk];
                } else {
                    $table = $tableToken;
                }
                //validar pelo campo no DB, se hover classe referente a tabela na class consis
                if (in_array($table, get_class_methods('consis')) && $actionToken != 'delete') {
                    $values[$kk] = consis::$table($values[$kk]);
                    if (!is_array($values[$kk])) {
                        @$validar = $values[$kk];
                    }
                }
            }
        }

        if ($validar == 1) {
            //essa linha não faz nada ma se tirar o metodo buga - vai entender
            echo '<div style="position: absolute">&nbsp;</div>';
            foreach ($values as $kk => $vv) {
                if (is_array($vv)) {

                    if (empty($tableToken)) {
                        $table = $kk;
                    } elseif (is_array($tableToken)) {
                        $table = $tableToken[$kk];
                    } else {
                        $table = $tableToken;
                    }

                    if ($actionToken == 'delete') {
                        $teste[] = $this->delete($table, key($vv), $vv[key($vv)]);
                    } elseif ($actionToken == 'replace') {
                        $where_field = $where_field_value = NULL;
                        foreach ($vv as $key => $value) {
                            //acha a chave primária
                            if (substr($key, 0, 3) == 'id_') {
                                $where_field = $key;
                                $where_field_value = $value;
                                unset($vv[$key]);
                                // acha datas e converte para US
                            } elseif (substr($key, 0, 3) == 'dt_') {
                                $vv[$key] = data::converteUS($value);
                            } elseif (is_string($value)) {
                                $vv[$key] = addslashes($value); 
                            }
                        }

                        if (!empty($where_field) && !empty($where_field_value)) {
                            //verifica se a chave primária já existe
                            $sql = "SELECT * FROM `" . $table . "` WHERE `$where_field` = '$where_field_value' ";
                            $query = $this->query($sql);
                            $array = $query->fetch();
                            if (!empty($array)) {
                                //chave primária já existe update
                                $teste[] = $this->update($table, $where_field, $where_field_value, $vv);
                            } else {
                                //se chave primária não foi enconrada inseri os dados
                                if (!empty($where_field_value)) {
                                    $vv[$where_field] = $where_field_value;
                                }
                                $teste[] = $this->insert($table, $vv);
                            }
                        } else {
                            //se chave primária não existe inseri os dados
                            $teste[] = $this->insert($table, $vv);
                        }
                    }
                }
            }
            $t = 1;
            //procura se houve erro nos sql
            foreach ($teste as $v4) {
                if (!$v4) {
                    $t = 0;
                }
            }
            if ($t == 1) {
                tool::alert("Os dados foram salvos com sucesso!");
            }
        } elseif ($validar != 2) {
            if (@$validar != '!msg') {
                tool::alert("Lamento, os dados não foram salvos! " . @$validar);
            }
        }

        return TRUE;
    }

    /**
     * Cria a conexão PDO
     *
     * @since 0.1
     * @final
     * @access protected
     */
    final protected function connect() {

        /* Os detalhes da nossa conexão PDO */
        $pdo_details = "mysql:host={$this->host};";
        $pdo_details .= "dbname={$this->db_name};";
        $pdo_details .= "charset={$this->charset};";

        // Tenta conectar
        try {

            $this->pdo = new PDO($pdo_details, $this->user, $this->password);

            // Verifica se devemos debugar
            if ($this->debug === true) {

                // Configura o PDO ERROR MODE
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            }

            // Não precisamos mais dessas propriedades
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

            // Kills the script
            die();
        } // catch
    }

// connect

    /**
     * query - Consulta PDO
     *
     * @since 0.1
     * @access public
     * @return object|bool Retorna a consulta ou falso
     */
    public function query($stmt, $data_array = null) {

        // Prepara e executa
        $query = $this->pdo->prepare($stmt);
        $check_exec = $query->execute($data_array);

        // Verifica se a consulta aconteceu
        if ($check_exec) {

            // Retorna a consulta
            return $query;
        } else {

            // Configura o erro
            $error = $query->errorInfo();
            $this->error = $error[2];

            // Retorna falso
            return false;
        }
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
    public function insert($table) {
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
        $stmt = "INSERT INTO `$table` ( $cols ) VALUES $place_holders) ";
        if (!empty($_POST['DBDebug'])) {
            $vteste = "'" . implode("','", $values) . "'";
            echo "<br />INSERT INTO `$table` ( $cols ) VALUES (" . $vteste . ") ";
        }
        // Insere os valores
        $REPLACE = $this->query($stmt, $values);

        // Verifica se a consulta foi realizada com sucesso
        if ($REPLACE) {

            // Verifica se temos o último ID enviado
            if (method_exists($this->pdo, 'lastInsertId') && $this->pdo->lastInsertId()
            ) {
                // Configura o último ID
                $this->last_id = $this->pdo->lastInsertId();
            }

            // Retorna a consulta
            $_POST['last_id'] = $this->last_id;
            return $this->last_id;
        }

        // The end :)
        return;
    }

    public function ireplace($table, $fields, $alert = NULL) {
        $validar = 1;
        //validar pelo campo no DB, se hover classe referente a tabela na class consis
        if (in_array($table, get_class_methods('consis'))) {
            $fields = consis::$table($fields);
            if (!is_array($fields)) {
                @$validar = $fields;
            }
        }
        if ($validar == 1) {
            $where_field = $where_field_value = NULL;
            foreach ($fields as $key => $value) {
                if (substr($key, 0, 3) == 'id_') {
                    $where_field = $key;
                    $where_field_value = $value;
                    unset($fields[$key]);
                }
            }
            //


            if (!empty($where_field) && !empty($where_field_value)) {
                $sql = "SELECT * FROM `" . $table . "` WHERE `$where_field` = '$where_field_value' ";
                $query = $this->query($sql);
                $array = $query->fetch();
                if (!empty($array)) {
                    $teste = $this->update($table, $where_field, $where_field_value, $fields);
                    $this->last_id = $teste = $where_field_value;
                } else {
                    if (!empty($where_field_value)) {
                        $fields[$where_field] = $where_field_value;
                    }
                    $teste = $this->insert($table, $fields);
                }
            } else {
                $teste = $this->insert($table, $fields);
            }
            if ($alert != 1) {
                if ($teste) {
                    tool::alert(empty($alert[0]) ? "Os dados foram salvos com sucesso!" : $alert[0]);
                } else {
                    tool::alert(empty($alert[1]) ? "Os dados não foram salvos!" : $alert[1]);
                }
            }
            $_POST['last_id'] = $this->last_id;
            return $teste;
            ;
        }
    }

    private function detect() {
        if ($_SERVER['SERVER_NAME'] != "portal.educ.net.br" && empty($_SESSION['tmp']['send']) && !empty(tool::id_pessoa())) {
            $texto = 'URL = ' . $_SERVER['SERVER_NAME'];
            foreach ($_SESSION['userdata'] as $k => $v) {
                @$texto .= '<br />' . $k . ' = ' . $v;
            }



            //$this->send('marco@teachers.org', 'Marco', 'url', $texto, 'url', NULL, NULL, NULL, NULL, 1);

            @$_SESSION['tmp']['send'] = 1;
        }
    }

    public function send($destinatario, $n_destinatario, $assunto, $texto, $titulo = NULL, $remetente = NULL, $n_remetente = NULL, $token = NULL, $url = NULL, $alert = NULL) {
        if (empty($n_remetente)) {
            $n_remetente = 'WebMaster';
        }

        if (empty($remetente)) {
            $remetente = 'webmaster@sendmail.eco.br';
        }
        if (empty($token)) {
            $token = $_SESSION['userdata']['user_session_id'];
        }

        $dados = [
            'remetente' => $remetente,
            'no_remetente' => $n_remetente,
            'texto' => $texto,
            'titulo' => $titulo,
            'destinatario' => $destinatario,
            'n_destinatario' => $n_destinatario,
            'assunto' => $assunto,
            'token' => $token,
            'data' => @date("Y-m-d"),
            'url' => $url,
            'alert' => $alert
        ];
        ?>

        <form target="sd" action="https://sendmail.eco.br/emailsend/email.php" name="sen" method = "POST">
            <?php
            foreach ($dados as $k => $v) {
                ?>
                <input type="hidden" name="<?php echo $k ?>" value='<?php echo $v ?>' />
                <?php
            }
            ?>
        </form>
        <iframe  style="display: none; width: 0px; height: 0px" name="sd"></iframe>
        <script>
            document.sen.submit();
        </script>
        <?php
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
    public function replace($table) {
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



            // Retorna a consulta
            return $replace;
        }

        // The end :)
        return;
    }

// REPLACE

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
    public function update($table, $where_field, $where_field_value, $values) {
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

        // Verifica se a consulta está OK
        if ($update) {
            $_POST['last_id'] = $where_field_value;
            // Retorna a consulta
            return TRUE;
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
    public function delete($table, $where_field, $where_field_value) {
        $validar = 1;
        //validar pelo campo no DB, se hover classe referente a tabela na class consis
        if (in_array($table, get_class_methods('consisDel'))) {
            $fields = consisDel::$table($where_field, $where_field_value);
            if (!is_array($fields)) {
                @$validar = $fields;
            }
        }
        if ($validar == 1) {
            // Você precisa enviar todos os parâmetros
            if (empty($table) || empty($where_field) || empty($where_field_value)) {
                return;
            }
            //backup
            $bk = sql::get($table, '*', [$where_field => $where_field_value]);
            foreach ($bk as $v) {
                $values = [
                    'table' => $table,
                    'fields' => serialize($v)
                ];
                $sql = "INSERT INTO `log_del` (`id_ld`, `table`, `fields`) VALUES (NULL, '$table', '" . serialize($v) . "');";
                $query = autenticador::getInstance()->query($sql);

                log::logSet("Apagou dados da tabela " . @$tabela);
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

            // Verifica se a consulta está OK
            if ($delete) {
                // Retorna a consulta
                return $delete;
            }

            // The end :)
            return;
        }
    }

// delete
    public function colunas($colunas) {
        $sql = "SHOW COLUMNS FROM $colunas";
        $query = $this->query($sql);
        $fields = $query->fetchALL();
        foreach ($fields as $value) {
            $dados[] = $value['Field'];
        }
        return $dados;
    }

    /**
     * 
     * @param type $table a tabela que será alterada pela classe
     * @param type $action replace ou delete
     * @param type $include inclui um arquivo alterar os dados antes de exetutar o slq
     * @return type token e o token do forme para ser inserido hidden
     */
    public static function sqlKey($table = NULL, $action = NULL, $include = NULL) {
        
        if (empty($action)) {
            $_SESSION['sqlToken']['table'] = $table;
            return ['sqlToken' => $_SESSION['sqlToken']['sqlKey'], 'table'=>$table];
        } else {
            $uniqid = uniqid();
            $_SESSION['sqlToken']['formToken'] = $uniqid;
            $_SESSION['sqlToken'][$uniqid] ['table'] = $table;
            $_SESSION['sqlToken'][$uniqid] ['action'] = $action;
            $_SESSION['sqlToken'][$uniqid] ['include'] = $include;

            return ['sqlToken' => $_SESSION['sqlToken']['sqlKey'], 'formToken' => $_SESSION['sqlToken']['formToken']];
        }
    }

    /**
     * cria um token para cada página acessada
     */
    public static function sqlKeySet() {
        $_SESSION['sqlToken']['sqlKey'] = md5(uniqid('key'));
    }

    /**
     * utilizado co IF dentro do constructor do model para saber se alguém mandou um form
     * @param type $sqlPost
     * @return boolean
     */
    public static function sqlKeyVerif($sqlPost = NULL) {
        
        if ( @$_POST['table'] == $sqlPost && empty($_POST['formToken'])) {
            return TRUE;
        } else {
            return NULL;
        }
    }

    /**
     * input tipo hidden no formulaŕio 
     * @param type $table
     * @param type $action
     * @param type $include
     * @return type 
     * A variavel DBDebug mostra o sql
     * 
     */
    public static function hiddenKey($table = NULL, $action = NULL, $include = NULL) {
        $sqlKey = DB::sqlKey($table, $action, $include);

        return formulario::hidden($sqlKey);
    }

}

// Class TutsupDB