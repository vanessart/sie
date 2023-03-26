<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of consistency
 *
 * @author marco
 */
class consistency {

    public static function ge_turmas($fields) {
        
        if (empty($fields['fk_id_ciclo']) || empty($fields['fk_id_grade'])) {
            return ;
        }
        return $fields;
    }

    public static function pessoa($fields) {
        if (isset($fields['n_pessoa'])) {
            if (strlen($fields['n_pessoa']) < 5) {
                return;
            }
        }
        if (!empty($fields['certidaoL']) || !empty($fields['certidaoF']) || !empty($fields['certidaoT'])) {
            if (!empty($fields['certidaoL']) && !empty($fields['certidaoF']) && !empty($fields['certidaoT'])) {
                if (!empty($fields['certidao'])) {
                    return 'Preencha a Certidão Antiga ou a Nova';
                } else {
                    $fields['certidao'] = $fields['certidaoL'] . '-' . $fields['certidaoF'] . '-' . $fields['certidaoT'];
                }
            } else {
                return 'Preencha todos os Campos da Certidão Antiga';
            }
        }
        if (empty($fields['id_pessoa'])) {
            if (!empty($fields['cpf'])) {
                $cpf = sql::get('pessoa', 'n_pessoa, cpf', ['cpf' => $fields['cpf']], 'fetch');
                if (!empty($cpf['cpf'])) {
                    return 'Não foi possível Cadastrar. Este CPF está cadastrado para ' . $cpf['n_pessoa'];
                }
            }
            if (!empty($fields['email'])) {
                $cpf = sql::get('pessoa', 'n_pessoa, email', ['email' => $fields['email']], 'fetch');
                if (!empty($cpf['email'])) {
                    return 'Não foi possível Cadastrar. Este E-mail está cadastrado para ' . $cpf['n_pessoa'];
                }
            }
            if (!empty($fields['certidao'])) {
                $cpf = sql::get('pessoa', 'n_pessoa, certidao', ['certidao' => $fields['certidao']], 'fetch');
                if (!empty($cpf['certidao'])) {
                    return 'Não foi possível Cadastrar. Esta Certidão está cadastrada para ' . $cpf['n_pessoa'];
                }
            }
        }
        unset($fields['certidaoL']);
        unset($fields['certidaoF']);
        unset($fields['certidaoT']);
        if (!empty($fields['n_pessoa'])) {
            $fields['n_pessoa'] = trim($fields['n_pessoa']);
        }
        if (!empty($fields['n_social'])) {
            $fields['n_social'] = trim($fields['n_social']);
        }
        return $fields;
    }

    public static function users($fields) {

        //confirurar apelido
        if (!empty($fields['n_user'])) {
            $fields['n_user'] = trim($fields['n_user']);
            $teste = sql::get('users', 'id_user', " where n_user like '" . $fields['n_user'] . "' and id_user != '" . $fields['id_user'] . "' ", 'fetch');
            if (!empty($teste)) {
                return "Este apelido já está em uso!";
            }
        }
        if (empty($fields['user_session_id'])) {
            $fields['user_session_id'] = uniqid(2938456433);
        }
        if (!empty($fields['user_password'])) {
            $field_value = $fields['user_password'];
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
                        $password_hash = new passwordHash(8, FALSE);
                        // Cria o hash da senha
                        $field_value = $password_hash->HashPassword($field_value);
                        $fields['user_password'] = $field_value;
                        $fields['ativo'] = 1;
                    } else {
                        return "Muito fácil! Tenta outra";
                    }
                }
            }
        }
        $horas = @$_POST['horas'];
        if (!empty($_POST['horasSet'])) {
            $fields['horas'] = NULL;
            if (is_array($horas)) {
                $fields['horas'] = serialize($horas);
            }
        }
        return $fields;
    }

    public static function gt_turma($fields) {

        if (!empty($fields['fk_id_ciclo']) && !empty($fields['periodo']) && !empty($fields['letra'])) {
            $ciclo = sql::get(['gt_ciclo', 'gt_curso'], 'abrev_cur, abrev_ciclo, n_ciclo', ['id_ciclo' => $fields['fk_id_ciclo']], 'fetch');

            $fields['n_turma'] = $ciclo['n_ciclo'] . ' ' . $fields['letra'];
            $fields['codigo'] = $ciclo['abrev_cur'] . $fields['periodo'] . $ciclo['abrev_ciclo'] . $fields['letra'];
        }

        if (!empty($fields['n_turma']) && !empty($fields['fk_id_inst']) && !empty($fields['fk_id_pl'])) {
            $id_turma = sql::get('gt_turma', 'id_turma', ['fk_id_pl' => $fields['fk_id_pl'], 'n_turma' => $fields['n_turma'], 'fk_id_inst' => $fields['fk_id_inst']], 'fetch')['id_turma'];
            if (empty($id_turma) || @$fields['id_turma'] == @$id_turma) {
                return $fields;
            } else {
                return'Já existe uma classe com este nome nesta escola';
            }
        } else {
            return $fields;
        }
    }

    public static function gt_turma_aluno($fields) {
        if (!empty($fields['fk_id_sit']) && @$fields['fk_id_sit'] != 0) {
            $fields['dt_matricula_fim'] = date("Y-m-d");
        }
        return $fields;
    }

    public static function modelo__($fields) {

        return $fields;
    }

    public static function gt_conselho($fields) {

        if ($fields['fechado'] == 1 && !empty($fields['id_turma_con'])) {
            aval::fecharConselho($fields['id_turma_con']);
        }

        return $fields;
    }

    public static function funcionario($fields) {

        //se for professor dar acesso ao grupo professor
        $func = sql::get('funcionario_funcao', 'n_ff', ['id_ff' => $fields['fk_id_ff']], 'fetch')['n_ff'];
        if (substr($func, 0, 4) == 'prof') {
            $ac = sql::get('acesso_pessoa', 'fk_id_pessoa', ['fk_id_pessoa' => $fields['fk_id_pessoa'], 'fk_id_gr' => 5], 'fetch');
            if (empty($ac)) {
                $sql = "INSERT INTO `acesso_pessoa` (`id_ac`, `fk_id_pessoa`, `fk_id_gr`, `fk_id_inst`) VALUES ("
                        . "NULL, '" . $fields['fk_id_pessoa'] . "', '5', '3');";
                $query = pdoSis::getInstance()->query($sql);
            }
        }
        return $fields;
    }

    public static function projeto_projeto($fields) {

        if ($fields['dt_inicio'] > $fields['dt_fim']) {
            toolErp::alert('A data de inínio (' . data::converteBr($fields['dt_inicio']) . ') é posterior a de término (' . data::converteBr($fields['dt_fim']) . ')');
            unset($fields['dt_fim']);
        }
        return $fields;
    }

    public static function profe_projeto($fields) {
        if (!empty($fields['dt_inicio']) && !empty($fields['dt_fim'])) {
            if ($fields['dt_inicio'] > $fields['dt_fim']) {
                toolErp::alert('A data de inínio (' . data::converteBr($fields['dt_inicio']) . ') é posterior a de término (' . data::converteBr($fields['dt_fim']) . ')');
                unset($fields['dt_fim']);
            }
        }
        return $fields;
    }

    public static function inscr_certificado_deferimento($fields) {
        if (empty($fields['pontos'])) {
            $fields['pontos'] = '0';
        }

        return $fields;
    }

    public static function inscr_incritos_3($fields) {
        foreach ($fields as $k => $v) {
            if (is_string($v)) {
                $fields[$k] = mb_strtoupper($v);
            }
        }

        return $fields;
    }
/**
    public static function inscr_recurso($fields) {
        if (empty($fields['motivo']) && empty($fields['concluido'])) {
            return 'Responda o campo obrigatório';
        } else {
            return $fields;
        }
    }
 * 
 * @param type $fields
 * @return string
 */

    public static function apd_aluno($fields) {
        if (!empty($fields['fk_id_pessoa'])) {
            $cpf = sql::get('apd_aluno', 'fk_id_pessoa', ['fk_id_pessoa' => $fields['fk_id_pessoa']], 'fetch');
            if (!empty($cpf['fk_id_pessoa'])) {
                return 'Não foi possível Cadastrar. Este Aluno já está cadastrado como AEE';
            }
        }

        return $fields;
    }

    public static function cadampe_pedido($fields) {
        unset($fields['fk_id_turma']);

        return $fields;
    }

    public static function htpc_pauta_proposta($fields) {
        unset($fields['id_curso']);

        return $fields;
    }

    public static function htpc_ata($fields)
    {
        if ( isset($fields['id_ata']) )
        {
            if ( isset($fields['status']) && $fields['status'] == 'F' )
            {
                $ata = sql::get('htpc_ata', 'status', ['id_ata' => $fields['id_ata']], 'fetch');
                if (!empty($ata) && $ata['status'] != 'F')
                {
                    $_POST['ausenteParaTodos'] = true;
                }
            }
        }
        return $fields;
    }

    public static function transporte_veiculo($fields) {

        $fields['fk_id_pessoa'] = tool::id_pessoa();
        if (empty($fields['id_tv'])) {
            $teste = sql::get('transporte_veiculo', 'n_tv', ['n_tv' => $fields['n_tv']], 'fetch');
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

    public static function transporte_setup($fields) {
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

    public static function transporte_empresa($fields) {
        if (empty(@$fields['email'])) {
            return $fields;
        }

        $kbum = explode(";", $fields['email']);
        $e = '';
        $pv = '';
        foreach ($kbum as $key => $value) {
            if (!empty(trim($value)) && filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $e .= $pv . strtolower(trim($value));
                $pv = ";";
            }
        }
        if (empty($e)) {
            return $fields;
        }
        
        $fields['email'] = $e;
        return $fields;
    }
}
