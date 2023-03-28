<?php
if (!defined('ABSPATH'))
    exit;
ob_start();
$pdf = new pdf();
//$_POST = $pdf->autentica(1);
$id_ce = intval(@$_POST['id_ce']);
if ($id_ce) {
    $dados = $model->emppretimo($id_ce, 1);
}
if (empty($dados)) {
    echo 'Não tenho a menor ideia do que você esta fazendo aqui :(';
    exit();
}
?>
<style>
    td{
        padding: 3px;
    }
</style>
<div style="text-align: center; font-size: 22px; font-weight: bold">
    Devolução de Chromebook
</div>
<br /><br /><br />
<div style="text-align: justify">
    <span style="font-weight: bold"><?= $dados['n_pessoa'] ?></span>, CPF <span style="font-weight: bold"><?= $dados['cpf'] ?></span>, 
    devolveu o equipamento descrito abaixo a este departamento.
</div>
<br />
<table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
    <tr>
        <td>
            Número de Série
        </td>
        <td>
            <?= $dados['serial'] ?>
        </td>
    </tr>
    <tr>
        <td>
            MAC (mac address)
        </td>
        <td>
            <?= $dados['mac'] ?>
        </td>
    </tr>
    <tr>
        <td>
            Modelo
        </td>
        <td>
            <?= $dados['n_cm'] ?>
        </td>
    </tr>
    <!--
    <tr>
        <td>
            Equipamento
        </td>
        <td>
            <?= $dados['equipamento'] ?>
        </td>
    </tr>
    -->
    <tr>
        <td>
            Acessório
        </td>
        <td>
            <?= $dados['carregador'] ?>
        </td>
    </tr>
</table>
<br />
<div style="text-align: justify">
    <?php
    if (!empty($dados['obs'])) {
        ?>
        Observações:
        <br /><br />
        <?= $dados['obs'] ?>
        <?php
    }
    ?>
</div>
<div style="text-align: right; padding: 50px">
    <?= CLI_CIDADE ?>, <?= data::porExtenso($dados['dt_fim']) ?>
</div>

<div style="padding: 35px; width: 65%; margin-left: 35%">
    <hr>
    (assinatura e carimbo do responsável DTTIE ou autenticação digital)
</div>

<?php
$pdf->exec();
