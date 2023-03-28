<?php
if (!defined('ABSPATH'))
    exit;
ob_start();
$id_hist = filter_input(INPUT_POST, 'id_hist', FILTER_SANITIZE_NUMBER_INT);
$dados = $model->devolveEsc($id_hist);
$obs = explode(' <br />', $dados['obs']);
$func = sql::get(['pessoa', 'ge_funcionario'], 'n_pessoa, rm, cpf, emailgoogle, sexo ', ['id_pessoa' => $dados['id_pessoa']], 'fetch');
?>
<style>
    td{
        padding: 3px;
    }
</style>
<br /><br />
<div style="text-align: center; font-size: 22px; font-weight: bold">
    Devolução de Chromebook para a Escola
</div>
<br /><br /><br />
<div style="text-align: justify">
    <?php
    echo $obs[0];
    ?>
</div>
<br />
<div style="text-align: justify">
    <?php
    if (!empty($func)) {
        ?>
        Foi entregue pel<?= toolErp::sexoArt($dados['sexo']) ?> funcionári<?= toolErp::sexoArt($dados['sexo']) ?> <span style="font-weight: bold"><?= $dados['n_pessoa'] ?></span>, matrícula <span style="font-weight: bold"><?= $func['rm'] ?></span>, CPF <span style="font-weight: bold"><?= $dados['cpf'] ?></span>, 
        o chromebook referido acima.
        <?php
    } else {
       ?>
        Foi entregue pel<?= toolErp::sexoArt($dados['sexo']) ?> alun<?= toolErp::sexoArt($dados['sexo']) ?> <span style="font-weight: bold"><?= $dados['n_pessoa'] ?></span>, RE <span style="font-weight: bold"><?= $dados['id_pessoa'] ?></span>, por intermédio do seu responsável <span style="font-weight: bold"><?= $dados['responsavel'] ?></span>, 
        o chromebook referido acima.
        <?php 
    }
    
    ?>
</div>
<br />
<div style="text-align: justify">
    <?php
    if (!empty($obs[1])) {
        ?>
        Observações anotadas no momento da entrega do chromebook:
        <br /><br />
        <?= $obs[1] ?>
        <?php
    }
    ?>
</div>
<div style="text-align: right; padding: 50px">
    Baruei, <?= data::porExtenso(date("Y-m-d")) ?>
</div>

<div style="padding: 35px; width: 65%; margin-left: 35%">
    Nome:
    <br />
    <hr>
    Função/cargo:
    <br />
    <hr>
    Matrícula:
    <br />
    <hr>
</div>

<?php
tool::pdf();
