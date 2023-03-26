<?php
$sqlkey = DB::sqlKey('vagas', 'replace');



if (empty($_POST['id_vaga'])) {
    $id_vaga = @$_POST['last_id'];
} else {
    $id_vaga = $_POST['id_vaga'];
}


if (!empty($id_vaga)) {
    $dados = sql::get('vagas', '*', ['id_vaga' => $id_vaga], 'fetch');
    if (($dados['status'] != "Edição")) {
        $readonly = 'disabled';
    } else {
        $readonly = NULL;
    }
} elseif (!empty($_POST['id_pessoa'])) {
    $pessoa = sql::get('pessoa', '*', ['id_pessoa' => $_POST['id_pessoa']], 'fetch');
    var_dump($pessoa);
    $dados['fk_id_pessoa'] = @$pessoa['id_pessoa'];
    $dados['n_aluno'] = @$pessoa['n_pessoa'];
    $dados['sx_aluno'] = @$pessoa['sexo'] == 'F' ? 'Feminino' : (@$pessoa['sexo'] == 'M' ? 'Masculino' : '');
    $dados['mae'] = @$pessoa['mae'];
    $dados['pai'] = @$pessoa['pai'];
    $dados['responsavel'] = @$pessoa['responsavel'];
    $dados['cpf_resp'] = @$pessoa['cpf_respons'];
    $dados['tel1'] = @$pessoa['tel1'];
    $dados['tel2'] = @$pessoa['tel2'];
    $dados['tel3'] = @$pessoa['tel3'];
    $dados['dt_aluno'] = @$pessoa['dt_nasc'];
    $dados['cn_matricula'] = @$pessoa['certidao'];
    $dados['rg_aluno'] = @$pessoa['rg'];
    $dados['oe_rg_aluno'] = @$pessoa['rg_oe'];
    $dados['uf_rg_aluno'] = @$pessoa['rg_uf'];
    $dados['nacionalidade'] = @$pessoa['nacionalidade'];
    $dados['uf_nasc'] = @$pessoa['uf_nasc'];
    $dados['cidade_nasc'] = @$pessoa['cidade_nasc'];
    $dados['bairro'] = @$pessoa['bairro'];

    $dados['dt_rg_aluno'] = @$pessoa['dt_rg'];
    $dados['dt_resp'] = @$pessoa['dt_resp'];
    $dados['dt_vagas'] = @$pessoa['dt_vagas'];
    
} else {
    $readonly = NULL;
}



?>
<div class="fieldBody">
    <div class="fieldTop">
        Inscrição para vagas
    </div>
    <br /><br />
    <div class="row">
        <?php
        if (@$_REQUEST['abad'] == 1 && user::session('n_nivel') == 'Diretor') {
            $aba = '2';
        } elseif (empty($_REQUEST['aba'])) {
            $aba = '0';
        } else {
            $aba = $_REQUEST['aba'];
        }
        $titulo = ['Cadastro Geral', 'Protocolo', 'Deferimento'];
        ?>
        <table style="width: 100%">
            <tr>
                <?php
                for ($c = 0; $c < 3; $c++) {
                    ?>
                    <td style="width: 33%">
                        <form method="POST">
                            <input type="hidden" name="id_vaga" value="<?php echo $id_vaga ?>" />
                            <input type="hidden" name="aba" value="<?php echo $c ?>" />
                            <button class="btn btn-<?php echo $aba == $c ? 'primary' : (empty($id_vaga) ? 'default' : ($c == 2 && user::session('n_nivel') == 'Escola' ? 'default' : 'warning')) ?>" style="width: 100%; font-weight: bold; " <?php echo empty($id_vaga) && $c <> 0 ? 'disabled' : '' ?> <?php echo $c == 2 && user::session('n_nivel') == 'Escola' ? 'disabled' : '' ?> >
                                <span style="float: left">
                                    <?php echo $titulo[$c] ?>
                                </span>
                                <span style="float: right" class="badge"><?php echo $c + 1 ?></span>
                            </button>
                        </form>
                    </td>
                    <?php
                }
                ?>
            </tr>
        </table>
    </div>
    <br /><br />
    <?php

    include ABSPATH . '/views/vagas/cada_' . $aba . '.php';
    ?>
</div>

<?php
       if (!empty($_POST['matricular'])) {
           include ABSPATH . '/views/vagas/cada_matricular.php';
       }
                                    ?>
