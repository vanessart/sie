<?php
if (!defined('ABSPATH'))
    exit;
$robot = @$_REQUEST['robot'];
if (empty($robot)) {
    $data = @$_REQUEST['data'];
$activeNav = filter_input(INPUT_POST, 'activeNav', FILTER_SANITIZE_NUMBER_INT);
} else {
    $data = [date('Y-m-d', strtotime("-30 days"))];
    include ABSPATH . "/module/cit/views/_importRh/2.php";
    die();
}
//$dados = rhImport::funcionarios($datas)
?>
<div class="body">
    <div class="fieldTop">
        Importar Funcionários do Sistema do RH
    </div>
    <br /><br />
    <?php
    $abas[1] = ['nome' => "Por Matrícula", 'ativo' => 1];
    $abas[2] = ['nome' => "Por Data", 'ativo' => 1];
    $aba = report::abas($abas);
    include ABSPATH . "/module/cit/views/_importRh/$aba.php";
    ?>
</div>
<?php

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

    if (in_array($dados['idsituacao'] , [93, 122, 123, 15, 120, 51, 121])) {
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
