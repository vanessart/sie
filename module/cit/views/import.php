<?php
if (!defined('ABSPATH'))
    exit;
ini_set('memory_limit', '2000M');
$data = date('Y-m-d', strtotime("-30 days"));
//$data='1980-06-02';
$dados = cit::funcionarios(null, $data);
///$dados = cit::funcionarios(null, $data);

$sql = "SELECT IDFUNCIONARIO FROM `funcionarios_rh` WHERE MATRICULA NOT in ( SELECT `rm` FROM `ge_funcionario` ) ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
if ($array) {
    $idFunc = 'Incluidos pelo arquivo funcionario_rh: ';
    foreach ($array as $v) {
        $dad = cit::funcionarios($v['IDFUNCIONARIO']);
        $idFunc .= ', ' . $v['IDFUNCIONARIO'];
        $dados[] = current($dad);
    }
    $sql = "INSERT INTO `cit_erro` (`id_erro`, `n_erro`) VALUES (NULL, '$idFunc');";
    try {
        $query = pdoSis::getInstance()->query($sql);
    } catch (Exception $exc) {
        echo $exc->getTraceAsString();
    }
}
##################            
?>
<pre>   
    <?php
    print_r($dados);
    ?>
</pre>
<?php
###################

if (!empty($dados)) {
    foreach ($dados as $v) {
        if (is_object($v)) {
            unset($ins);
            $ins['id_funcionario'] = $v->idfuncionario;
            $ins['nome'] = $v->nome;
            $ins['registro'] = $v->registro;
            $ins['cpf'] = $v->cpf;
            $ins['idtipocontrato'] = $v->idtipocontrato;
            $ins['tipocontrato'] = $v->tipocontrato;
            $ins['demitido'] = $v->demitido;
            $ins['datanascimento'] = $v->datanascimento;
            $ins['sexo'] = $v->sexo;
            $ins['endereco'] = $v->endereco;
            $ins['numero'] = $v->numero;
            $ins['complemento'] = $v->complemento;
            $ins['bairro'] = $v->bairro;
            $ins['cidade'] = $v->cidade;
            $ins['uf'] = $v->uf;
            $ins['cep'] = $v->cep;
            $ins['dddresidencia'] = $v->dddresidencia;
            $ins['telefoneresidencia'] = $v->telefoneresidencia;
            $ins['dddcelular'] = $v->dddcelular;
            $ins['telefonecelular'] = $v->telefonecelular;
            $ins['email'] = $v->email;

            $dados_ = cit::funcionarios($v->idfuncionario, null, 'cargos');
            if (is_array($dados_)) {
                $arr = end($dados_);
                $ins['idcargo'] = $arr->idcargo;
                $ins['cargo'] = $arr->cargo;
                $ins['idmodalidade'] = $arr->idmodalidade;
                $ins['modalidade'] = $arr->modalidade;
            }

            $dados_ = cit::funcionarios($v->idfuncionario, null, 'situacoes');
            if (is_array($dados_)) {
                $arr = end($dados_);
                $ins['idsituacao'] = $arr->idsituacao;
                $ins['situacao'] = $arr->situacao;
            }
            $dados_ = cit::funcionarios($v->idfuncionario, null, 'remanejamentos');
            if (is_array($dados_)) {
                $arr = end($dados_);
                $ins['idlotacao'] = $arr->idlotacao;
                $ins['lotacao'] = $arr->descricao;
            }

            $id_funcionario = $model->db->ireplace('cit_funcionario', $ins, 1);
            if (!$id_funcionario) {
                $inss = json_encode($ins);
                $sql = "INSERT INTO `cit_erro` (`id_erro`, `n_erro`) VALUES (NULL, '$inss');";
                try {
                    $query = pdoSis::getInstance()->query($sql);
                } catch (Exception $exc) {
                    echo $exc->getTraceAsString();
                }
            }
            $id_pessoa = pessoa($ins, $model);
            if ($id_pessoa) {
                tel($id_pessoa, $v->telefoneresidencia, $v->dddresidencia, 2);
                tel($id_pessoa, $v->telefonecelular, $v->dddcelular, 1);
                endereco($id_pessoa, $ins);
                ge_funcionario($id_pessoa, $ins);
            }
        }
    }
}

function pessoa($ins, $model) {

    $sql = "SELECT * FROM `pessoa` WHERE `cpf` LIKE '" . $ins['cpf'] . "'";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetch(PDO::FETCH_ASSOC);
    if (!empty($array['id_pessoa'])) {
        $p['id_pessoa'] = $array['id_pessoa'];
    }
    $p['cpf'] = $ins['cpf'];
    $p['sexo'] = $ins['sexo'];
    $p['n_pessoa'] = $ins['nome'];
    $p['dt_nasc'] = substr($ins['datanascimento'], 0, 10);
    $p['sexo'] = $ins['sexo'];
    // $p['email'] = $ins['email'];

    $id_pessoa = $model->db->ireplace('pessoa', $p, 1);

    if ($id_pessoa) {
        return $id_pessoa;
    }
}

function tel($id_pessoa, $num, $ddd, $tipo) {
    $tel = sql::get('telefones', ' num ', ['fk_id_pessoa' => $id_pessoa, 'num' => $num], 'fetch');
    if (empty($tel)) {
        $sql = "INSERT INTO `telefones` (`fk_id_pessoa`, `ddd`, `num`, `fk_id_tt`) VALUES ( "
                . " '" . $id_pessoa . "', "
                . " '" . $ddd . "', "
                . " '" . $num . "', "
                . " '" . $tipo . "' "
                . ")";
        pdoSis::getInstance()->query($sql);
    }
}

function endereco($id_pessoa, $dados) {
    $end = sql::get('endereco', ' fk_id_pessoa, logradouro, num ', ['fk_id_pessoa' => $id_pessoa], 'fetch');
    if ($end) {
        $sql = "UPDATE `endereco` SET "
                . " `cep` = '" . str_replace("'", "", @$dados['cep']) . "', "
                . " `logradouro` = '" . str_replace("'", "", @$dados['endereco']) . "', "
                . " `num` = '" . str_replace("'", "", @$dados['numero']) . "', "
                . " `complemento` = '" . str_replace("'", "", @$dados['complemento']) . "', "
                . " `bairro` = '" . str_replace("'", "", @$dados['bairro']) . "', "
                . " `cidade` = '" . str_replace("'", "", @$dados['cidade']) . "', "
                . " `uf` = '" . str_replace("'", "", @$dados['uf']) . "' "
                . " WHERE fk_id_pessoa = " . $id_pessoa;
    } else {
        $sql = "INSERT INTO `endereco` (`fk_id_pessoa`, `cep`, `logradouro`, `num`, `complemento`, `bairro`, `cidade`, `uf`) VALUES "
                . " ("
                . " '" . $id_pessoa . "', "
                . " '" . @$dados['cep'] . "', "
                . " '" . str_replace("'", "", @$dados['endereco']) . "', "
                . " '" . str_replace("'", "", @$dados['numero']) . "', "
                . " '" . str_replace("'", "", @$dados['complemento']) . "', "
                . " '" . str_replace("'", "", @$dados['bairro']) . "', "
                . " '" . str_replace("'", "", @$dados['cidade']) . "', "
                . " '" . str_replace("'", "", @$dados['uf']) . "' "
                . ")";
    }
    pdoSis::getInstance()->query($sql);
}

function ge_funcionario($id_pessoa, $dados) {

    if ($dados['idsituacao'] == 93) {
        $at_func = '1';
    } else {
        $at_func = '0';
    }
    $sql = "SELECT * FROM `func_integra` WHERE `id_cit` = " . $dados['idlotacao'] . ' and fk_id_inst is not null ';
    $query = pdoSis::getInstance()->query($sql);
    $lotacao = $query->fetch(PDO::FETCH_ASSOC);
    if ($lotacao) {
        $id_inst = $lotacao['fk_id_inst'];
    } else {
        echo 'idlotacao: ' . $dados['idlotacao'] . ' não encontrado<br>';
        $sql = " replace INTO `func_integra` (`id_cit`, `local_trabalho`) VALUES ('" . $dados['idlotacao'] . "', '" . $dados['lotacao'] . "');";
        try {
            $query = pdoSis::getInstance()->query($sql);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        $sql = "INSERT INTO `cit_erro` (`id_erro`, `n_erro`) VALUES (NULL, 'idlotacao: " . $dados['idlotacao'] . " não encontrado');";
        try {
            $query = pdoSis::getInstance()->query($sql);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }

        return;
    }
    $sql = "SELECT * FROM `ge_funcionario` WHERE `rm` =  '" . intval($dados['registro']) . "'";
    $query = pdoSis::getInstance()->query($sql);
    $pessoa = $query->fetchAll(PDO::FETCH_ASSOC);

    if ($pessoa) {
        $sql = "UPDATE `ge_funcionario` SET "
                . " fk_id_pessoa = $id_pessoa, "
                . " `funcao`='" . $dados['cargo'] . "', "
                . " `situacao`='" . $dados['situacao'] . "', "
                . " `fk_id_inst`=$id_inst, "
                . " at_func = '$at_func' "
                . " where rm = '" . intval($dados['registro']) . "'";
    } else {
        $sql = "INSERT INTO `ge_funcionario` (`fk_id_pessoa`, `rm`, `funcao`, `situacao`, `fk_id_inst`, `at_func`) VALUES ("
                . " $id_pessoa, "
                . " '" . intval($dados['registro']) . "', "
                . " '" . $dados['cargo'] . "', "
                . " '" . $dados['situacao'] . "', "
                . " $id_inst, "
                . " '$at_func' "
                . ")";
    }
    pdoSis::getInstance()->query($sql);
}

$sql = "INSERT INTO `cit_erro` (`id_erro`, `n_erro`) VALUES (NULL, 'Fim');";
try {
    $query = pdoSis::getInstance()->query($sql);
} catch (Exception $exc) {
    echo $exc->getTraceAsString();
}