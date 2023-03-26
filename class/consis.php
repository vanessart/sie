<?php

/**
 * Quando criado um metodo com o nome de uma tabela a classe DB busca a consistencia no método antes de inserir os dados
 * Description of validar
 * Sempre que criar metodos retornar o $fields
 * @author mc
 */
class consis {

    public static function vagar($field) {
        log::logSet(tool::id_pessoa() . ' alterou inscrição ' . @$field['id_vaga'] . ' (status: ' . $field['status']);
        return $field;
    }

    public static function users($field) {
        if (!empty($_POST['senhaProf'])) {
            $sql = "select "
                    . "distinct p.n_pessoa, p.id_pessoa, p.sexo, p.email, pe.rm, pe.id_pe, u.id_user "
                    . "from ge_prof_esc pe "
                    . " join ge_funcionario f on f.rm = pe.rm "
                    . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                    . " left join users u on u.fk_id_pessoa = p.id_pessoa "
                    . " where pe.fk_id_inst = " . tool::id_inst()
                    . " order by n_pe ";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($array as $v) {
                $id_pessoa[] = $v['id_pessoa'];
            }
            log::logSet('Alterou a senha prof. ' . @$_POST['prof']);
        }
        if (!empty($_POST['senhaProf']) && !in_array($field['fk_id_pessoa'], $id_pessoa)) {
            $erroTeste = 1;
            return "Algo errado não está certo.  :(  Procure o administrador.";
        }

        if (!empty($_POST['senhafun'])) {
            $sql = "select "
                    . "distinct p.n_pessoa, p.id_pessoa, p.sexo, p.email, u.id_user "
                    . "from acesso_pessoa ap "
                    . " join pessoa p on p.id_pessoa = ap.fk_id_pessoa "
                    . " left join users u on u.fk_id_pessoa = p.id_pessoa "
                    . " where ap.fk_id_inst = " . tool::id_inst()
                    . " order by n_pessoa ";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($array as $v) {
                $id_pessoa[] = $v['id_pessoa'];
            }
            log::logSet('Alterou a senha de um funcionário. ');
        }
        if (!empty($_POST['senhafun']) && !in_array($field['fk_id_pessoa'], $id_pessoa)) {
            $erroTeste = 1;
            return "Algo errado não está certo.  :(  Procure o administrador.";
        }
        if (empty($erroTeste)) {
            $field['user_session_id'] = uniqid(2938456433);
            if (empty($field['id_user'])) {
                @$field['id_user'] = user::session('id_user');
            } elseif (@$field['id_user'] == "X") {
                @$field['id_user'] = NULL;
            }

            if (!empty($field['user_password'])) {
                $field_value = $field['user_password'];
                if (strlen($field_value) < 8) {

                    return "A senha precisa de pelo menos 8 caracteres ";
                } elseif (($field_value != @$_POST['password']) && !empty(@$_POST['password'])) {

                    return "Senhas diferentes";
                } else {
                    foreach (str_split($field_value) as $v) {
                        if (is_numeric($v)) {
                            @$n = 1;
                        } elseif (ctype_alpha($v)) {
                            @$c = 1;
                        } else {
                            @$e = 1;
                        }
                    }

                    if (!empty($e)) {

                        return "A senha Só pode ser composta de letras e números ";
                    } elseif (empty($c) || empty($n)) {

                        return "A senha tem que ser composta de letras e números ";
                    } else {

                        $pos = strpos($field_value, "123");
                        for ($f = 0; $f < strlen($field_value); $f++) {
                            @$test[$field_value[$f]]++;
                            if ($test[$field_value[$f]] > 3) {
                                $test_ = 1;
                            }
                        }
                        if ($pos === false && empty($test_)) {
                            $password_hash = new PasswordHash(8, FALSE);
                            // Cria o hash da senha
                            $field_value = $password_hash->HashPassword($field_value);
                            $field['user_password'] = $field_value;
                            $field['ativo'] = 1;
                            return $field;
                        } else {
                            return "Muito fácil! Tenta outra";
                        }
                    }
                }
            } elseif (!empty($field['ativo'])) {
                $ativo = sql::get('users', 'ativo', ['id_user' => $field['id_user']], 'fetch')['ativo'];
                if ($ativo == 1) {
                    $field['ativo'] = 0;
                } else {
                    $field['ativo'] = 1;
                }

                return $field;
            } else {
                return "Algo errado não está certo.  :(  Procure o administrador.";
            }
        }
    }

    public static function grupo($fields) {
        if (!empty(@$fields['id_gr'])) {
            $id = "AND id_gr != '" . @$fields['id_gr'] . "' ";
        }
        $sql = "SELECT  id_gr FROM  grupo "
                . "WHERE n_gr like '" . @$fields['n_gr'] . "' "
                . @$id;
        $query = pdoSis::getInstance()->query($sql);
        $teste = $query->fetch(PDO::FETCH_ASSOC);
        if (empty($teste)) {
            return $fields;
        } else {
            tool::alert("Este grupo já existe!");
            return NULL;
        }
    }

    public static function nivel($fields) {
        if (!empty(@$fields['id_nivel'])) {
            $id = "AND id_nivel != '" . @$fields['id_nivel'] . "' ";
        }
        $sql = "SELECT  id_nivel FROM  nivel "
                . "WHERE n_nivel like '" . @$fields['n_nivel'] . "' "
                . @$id;
        $query = pdoSis::getInstance()->query($sql);
        $teste = $query->fetch(PDO::FETCH_ASSOC);
        if (empty($teste)) {
            return $fields;
        } else {
            tool::alert("Este nivel já existe!");
            return NULL;
        }
    }

    public static function acesso_pessoa($fields) {
        $sql = "SELECT * FROM acesso_pessoa "
                . "WHERE fk_id_pessoa = " . $fields['fk_id_pessoa']
                . " AND fk_id_gr = " . $fields['fk_id_gr']
                . " AND fk_id_inst = " . $fields['fk_id_inst'];
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($array)) {
            tool::alert("Este usuário já tem esta permissão de acesso!");
        } else {
            return $fields;
        }
    }

    public static function salas($fields) {
        $fields['largura'] = round(str_replace(',', '.', $fields['largura']), 2);
        $fields['comprimento'] = round(str_replace(',', '.', $fields['comprimento']), 2);
        log::logSet('Cadastrou/alterou uma sala');

        return $fields;
    }

    public static function ge_cursos($fields) {
        if (!empty($fields['notas'])) {
            $fields['notas'] = str_replace(',', '.', $fields['notas']);
        }
        return $fields;
    }

    public static function ge_curso_grade($fields) {
        $teste = sql::get('ge_curso_grade', '*', ['padrao' => 1, 'fk_id_ciclo' => $fields['fk_id_ciclo']], 'fetch');

        if ($fields['padrao'] == 1 && !empty($teste['id_cg'] && $teste['id_cg'] != $fields['id_cg'])) {
            return "Só uma Grade pode ser marcada como Padrão";
        } else {
            if ($fields['padrao'] == 1) {
                $sql = "update ge_ciclos set fk_id_grade = " . $fields['fk_id_grade']
                        . " where id_ciclo = " . $fields['fk_id_ciclo'];
                $query = pdoSis::getInstance()->query($sql);
            }
            return $fields;
        }
    }

    public static function ge_turmas($fields) {
        if (empty($fields['fk_id_ciclo']) || empty($fields['fk_id_grade'])) {
            return;
        }
        if (!empty($fields['fk_id_ciclo']) && !empty($fields['letra'])) {
            $sg_ciclo_sg_curso = sql::get(['ge_ciclos', 'ge_cursos'], 'sg_curso,sg_ciclo, n_ciclo', ['id_ciclo' => $fields['fk_id_ciclo']], 'fetch');
            $fields['codigo'] = $sg_ciclo_sg_curso['sg_curso'] . $fields['periodo'] . $sg_ciclo_sg_curso['sg_ciclo'] . $fields['letra'];
            $fields['n_turma'] = $sg_ciclo_sg_curso['n_ciclo'] . ' ' . $fields['letra'];
        }
        if (!empty($fields['fk_id_pl'])) {
            $fields['periodo_letivo'] = sql::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $fields['fk_id_pl']], 'fetch')['n_pl'];
        }
        return $fields;
    }

    public static function pessoa($fields) {
        if (isset($fields['n_pessoa'])) {
            if (strlen($fields['n_pessoa']) < 5) {
                return;
            }
        }
        if (@$fields['cpf'] == '' && isset($fields['cpf'])) {
            $fields['cpf'] = NULL;
        }
        if (!empty($fields['cpf'])) {
            $fields['cpf'] = preg_replace('/[^0-9]/is', '', $fields['cpf']);
        }
        // @$fields['mae'] = str_replace("'", '', @$fields['mae']);
        // @$fields['pai'] = str_replace("'", '', @$fields['pai']);
        // @$fields['responsavel'] = str_replace("'", '', @$fields['responsavel']);
        if (empty($fields['id_pessoa']) && !empty($fields['certidao'])) {

            $sql = "select id_pessoa, dt_nasc, mae, n_pessoa, certidao from pessoa "
                    . " where "
                    . "(dt_nasc = " . $fields['dt_nasc'] . " AND n_pessoa like '%" . str_replace("'", '', $fields['n_pessoa']) . "%' ) "
                    . " or "
                    . "(mae like '" . str_replace("'", '', $fields['mae']) . "' AND n_pessoa like '%" . str_replace("'", '', $fields['n_pessoa']) . "%' ) "
                    . " or "
                    . " certidao like '" . str_replace("'", '', $fields['certidao']) . "' ";
            $query = pdoSis::getInstance()->query($sql);

            $verific = $query->fetch(PDO::FETCH_ASSOC);

            if (!empty($verific['id_pessoa'])) {
                if (((date("Y") - 6) * 10000) + 331 <= $fields['dt_nasc']) {

                    $fields = NULL
                    ?>
                    <div class="alert alert-danger">
                        Existe no sistema um aluno com os seguintes dados:
                        <br />
                        Nome: <?php echo $verific['n_pessoa'] ?>
                        <br />
                        RSE: <?php echo $verific['id_pessoa'] ?>
                        <br />
                        Mãe: <?php echo $verific['mae'] ?>
                        <br />
                        Certidão de Nascimento; <?php echo $verific['certidao'] ?>
                        <?php
                        $sql = "select ta.n_inst, ta.fk_id_pessoa from ge_turma_aluno ta "
                                . " JOIN ge_turma_aluno_situacao tas on tas.id_tas = ta.fk_id_tas AND tas.id_tas = 0 "
                                . " join instancia i on i.id_inst = ta.fk_id_inst  "
                                . "where ta.fk_id_pessoa = " . $verific['id_pessoa'];
                        $query = pdoSis::getInstance()->query($sql);
                        $frequente = $query->fetch(PDO::FETCH_ASSOC);
                        if (!empty($frequente['fk_id_pessoa'])) {
                            ?>
                            <br /><br />
                            O aluno está matriculado na escola <?php echo $frequente['n_inst'] ?>
                            <?php
                        } else {
                            $_POST['last_id'] = $_POST['id_pessoa'] = $verific['id_pessoa'];
                        }
                        ?>
                    </div>
                    <?php
                    $_POST['aba'] = 'geral';
                }
            }
        }

        if (!empty($fields['n_pessoa'])) {
            $n = explode(' ', $fields['n_pessoa']);
            foreach ($n as $v) {
                if (!empty($v)) {
                    @$nome .= $v . ' ';
                }
            }
            $fields['n_pessoa'] = trim($nome);
        }
        if (empty($fields['id_pessoa'])) {
            log::logSet("Adicionou um novo resgistro de pessoa ");
        } else {
            log::logSet("Alterou dados de registro do ID/RSE " . $fields['id_pessoa']);
        }
        if (!empty($fields['cpf_respons'])) {
            if (substr($fields['cpf_respons'], 0, 4) == 0000) {
                $fields = NULL;
                $_POST['aba'] = 'geral';
                ?>
                <div class="alert alert-danger">
                    Sério  <?php echo explode(' ', user::session('n_pessoa'))[0] ?>?!?
                    <br /><br />
                    CPF do responsável é um monte de zero?
                </div>
                <?php
                log::logSet("Tentou incluir um CPF inválido no sistema");
            } elseif (strlen($fields['cpf_respons']) <> 11) {
                $fields = NULL;
                $_POST['aba'] = 'geral';
                tool::alert('CPF incorreto');
            }
        }

        if (!empty($fields['email']) && empty($fields['id_pessoa'])) {
            $exCpf = sql::get('pessoa', 'email', ['email' => $fields['email']], 'fetch')['email'];
            if (!empty($exCpf)) {
                $_POST['aba'] = 'geral';
                $fields = "Este E-mail já está cadastrado";
            }
        } elseif (!empty($fields['email'])) {
            $em = sql::get('pessoa', 'id_pessoa', ['email' => $fields['email']], 'fetch');
            if (($em['id_pessoa'] <> $fields['id_pessoa']) && !empty($em['id_pessoa'])) {
                $_POST['aba'] = 'geral';
                echo $fields = "Este E-mail já está cadastrado";
            }
        }

        if (!empty($fields['cpf']) && empty($fields['id_pessoa'])) {
            @$exCpf = sql::get('pessoa', 'cpf', ['cpf' => $fields['cpf']], 'fetch')['cpf'];
            if (!empty($exCpf)) {
                $_POST['aba'] = 'geral';
                $fields = "Este CPF já está cadastrado";
            }
        } elseif (!empty($fields['cpf'])) {
            $em = sql::get('pessoa', 'id_pessoa,cpf', ['cpf' => $fields['cpf']], 'fetch');
            if (!empty($em['cpf']) && ($em['id_pessoa'] <> $fields['id_pessoa'])) {
                $_POST['aba'] = 'geral';
                $fields = "Este CPF já está cadastrado";
            }
        }

        // $fields['n_pessoa'] = str_replace([1, 2, 3, 4, 5, 6, 7, 8, 9, 0], ['', '', '', '', '', '', '', '', '', ''], $fields['n_pessoa']);

        return $fields;
    }

    public static function ge_supervisao($fields) {
        log::logSet('Cadastrou/alterou um setor de supervisão');
        return $fields;
    }

    public static function ge_turma_aluno($fields) {

        if (!empty($fields['destino_escola'])) {
            log::logSet("Transfere aluno fora da rede (id_turma_aluno = " . $fields['id_turma_aluno'] . ")");
            return $fields;
        } elseif (!empty($fields['fk_id_pessoa'])) {


            @$fields['origem_escola'] = strtoupper($fields['origem_escola']);

            $sql = "select id_curso, fk_id_pl from ge_cursos c "
                    . " join ge_ciclos ci on ci.fk_id_curso = c.id_curso "
                    . " join ge_turmas t on t.fk_id_ciclo = ci.id_ciclo "
                    . " where id_turma = " . $fields['fk_id_turma'];
            $query = pdoSis::getInstance()->query($sql);
            $verif = $query->fetch(PDO::FETCH_ASSOC);
            $sql = "Select fk_id_pessoa from ge_turma_aluno ta "
                    . " JOIN ge_turma_aluno_situacao tas on tas.id_tas = ta.fk_id_tas AND tas.id_tas = 0 "
                    . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                    . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                    . " join ge_cursos c on c.id_curso = ci.fk_id_curso "
                    . " Where fk_id_pessoa = '" . $fields['fk_id_pessoa'] . "' "
                    . " and c.id_curso = " . $verif['id_curso']
                    . " and t.fk_id_pl = " . $verif['fk_id_pl'];

            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetch(PDO::FETCH_ASSOC);

            if (!empty($array['fk_id_pessoa']) && empty($fields['id_turma_aluno']) && empty($fields['gdae'])) {
                tool::alert("Já Matriculado");
                return "Já Matriculado";
            } else {
                log::logSet('Matriculou o aluno(a) RSE: ' . $fields['fk_id_pessoa']);

                return $fields;
            }
        } elseif (!empty($fields['situacao'])) {
            log::logSet('Alterou a situação para ' . $fields['situacao'] . ' (id_turma_aluno = ' . $fields['id_turma_aluno'] . ')');
            return $fields;
        } else {
            return $fields;
        }
    }

    public static function dttie_suporte_trab($fields) {
        if (!empty($fields['fk_id_inst']) && !empty($fields['fk_id_pessoa'])) {
            @$id_inst = sql::get('ge_funcionario', 'fk_id_inst', ['rm' => $fields['rm']], 'fetch')['fk_id_inst'];
            if (($id_inst != $fields['fk_id_inst']) && !empty($fields['rm'])) {
                $sql = "update ge_funcionario set fk_id_inst = " . $fields['fk_id_inst']
                        . ' where rm = \'' . $fields['rm'] . "'";
                $query = pdoSis::getInstance()->query($sql);
            }
            if (!empty($fields['tel1'])) {
                @$tel1 = sql::get('pessoa', 'tel1', ['id_pessoa' => $fields['fk_id_pessoa']], 'fetch')['tel1'];
                if (@$tel1 != $fields['tel1']) {
                    $sql = "update pessoa set tel1 = '" . $fields['tel1'] . "'"
                            . ' where id_pessoa = ' . $fields['fk_id_pessoa'];
                    $query = pdoSis::getInstance()->query($sql);
                }
            }
        }
        return $fields;
    }

    public static function biro_trab($fields) {
        if (!empty($fields['fk_id_inst']) && !empty($fields['fk_id_pessoa'])) {
            $id_inst = sql::get('ge_funcionario', 'fk_id_inst', ['rm' => $fields['rm']], 'fetch')['fk_id_inst'];
            if ($id_inst != $fields['fk_id_inst']) {
                $sql = "update ge_funcionario set fk_id_inst = " . $fields['fk_id_inst']
                        . ' where rm = ' . $fields['rm'];
                $query = pdoSis::getInstance()->query($sql);
            }
            if (!empty($fields['tel1'])) {
                @$tel1 = sql::get('pessoa', 'tel1', ['id_pessoa' => $fields['fk_id_pessoa']], 'fetch')['tel1'];
                if (@$tel1 != $fields['tel1']) {
                    echo $sql = "update pessoa set tel1 = '" . $fields['tel1'] . "'"
                    . ' where id_pessoa = ' . $fields['fk_id_pessoa'];
                    $query = pdoSis::getInstance()->query($sql);
                }
            }
            return $fields;
        }
    }

    public static function ge_disciplinas($fields) {
        $disc_ = sql::get('ge_disciplinas', 'id_disc', ['>' => 'id_disc']);
        $col = sql::columns("aval_mf_" . date("Y"));

        if (!empty($disc_)) {
            foreach ($disc_ as $v) {
                @$disc .= ' media_' . $v['id_disc'] . ' varchar(11) NOT NULL, ';
                $disc .= ' falta_' . $v['id_disc'] . ' varchar(11) NOT NULL, ';
                $id = $v['id_disc'];
            }
        }
        $id_ = $id + 1;
        $tab = sql::tables();

        if (!in_array('aval_mf_' . date('Y'), $tab)) {

            $sql = "CREATE TABLE`aval_mf_" . date("Y") . "` ("
                    . "`id_mf` int(11) NOT NULL,"
                    . "`fk_id_pessoa` int(11) NOT NULL, "
                    . "`fk_id_turma` int(11) NOT NULL, "
                    . "`fk_id_ciclo` int(11) NOT NULL, "
                    . "`atual_letiva` int(11) NOT NULL, "
                    . @$disc
                    . "` falta_nc` int(11) NOT NULL "
                    . ") ENGINE=InnoDB DEFAULT CHARSET=latin1;";
            $query = pdoSis::getInstance()->query($sql);
        } else {
            $sql = "ALTER TABLE aval_mf_" . date("Y") . " ADD media_" . $id_ . " varchar(11) AFTER falta_" . $id;
            $query = pdoSis::getInstance()->query($sql);
            $sql = "ALTER TABLE aval_mf_" . date("Y") . " ADD falta_" . $id_ . " varchar(11) AFTER media_" . $id_;
            $query = pdoSis::getInstance()->query($sql);
        }
        return $fields;
    }

    public static function hist_esc($fields) {
        log::logSet('Permitiu acesso ao RSE: ' . $fields['fk_id_pessoa'] . ' à Instância ' . $fields['fk_id_inst']);
        return $fields;
    }

    public static function prod1_item($fields) {
        for ($c = 1; $c <= 3; $c++) {
            $fields['valor' . $c] = str_replace(',', '.', $fields['valor' . $c]);
        }
        return $fields;
    }

    public static function transp_veiculo($fields) {

        $fields['fk_id_pessoa'] = tool::id_pessoa();
        if (empty($fields['id_tv'])) {
            $teste = sql::get('transp_veiculo', 'n_tv', ['n_tv' => $fields['n_tv']], 'fetch');
            if (!empty($teste)) {
                tool::alert('O veículo ' . $fields['n_tv'] . ' já existe');

                return;
            }
            log::logSet('Usuário ' . tool::id_pessoa() . 'inserio dados');
        } else {
            log::logSet('Usuário ' . tool::id_pessoa() . 'editou dados');
        }



        return $fields;
    }

    public static function transp_setup($fields) {
        if (!empty($fields['destinoNome'])) {
            foreach ($fields['destinoNome'] as $k => $v) {
                $ativo = empty($fields['destinoAtivo'][$k]) ? 0 : 1;
                $destino[$k] = [
                    'nome' => $v . ' ',
                    'ativo' => $ativo
                ];
            }
            $fields['destino'] = base64_encode(serialize($destino));
            unset($fields['destinoNome']);
            unset($fields['destinoAtivo']);
        }

        return $fields;
    }

}
