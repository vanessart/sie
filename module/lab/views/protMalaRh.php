<?php
if (!defined('ABSPATH'))
    exit;
ob_start();
$pdf = new pdf();
$_POST = $pdf->autentica(1);
$rm = filter_input(INPUT_POST, 'rm', FILTER_SANITIZE_STRING);
$id_ce = intval(@$_POST['id_ce']);
if ($id_ce) {
    $dados = $model->emppretimo($id_ce, 1);
} else {
    echo 'Não tenho a menor ideia do que você esta fazendo aqui :(';
    exit();    
}
?>
<style>
    td{
        padding: 3px;
    }
</style>
<br /><br />
<div style="text-align: center; font-size: 22px; font-weight: bold">
    D E C L A R A Ç Ã O
</div>
<br /><br /><br />

<div>
    <p style="text-align: justify">
        Declaro para fins de extinção de vínculo funcional que <?= toolErp::sexoArt($dados['sexo']) ?> servidor<?= $dados['sexo']=='F'?'a':'' ?> <span style="font-weight: bold"><?= $dados['n_pessoa'] ?></span>, CPF <?= $dados['cpf'] ?>, <span style="font-weight: bold">NÃO</span> necessita devolver o equipamento descrito abaixo, pois encontra-se ativo na matrícula <?= $rm ?> .
    </p>
    <p style="text-align: justify">
        O servidor esteve presente no Departamento Técnico de Tecnologia da Informação Educacional nesta data, devendo apresentar este documento à Secretaria de Administração para fins de rescisão.
    </p>
</div>
<table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
    <tr>
        <td colspan="2" style="text-align: center">
            Equipamento
        </td>
    </tr>
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
</table>
<div style="text-align: right; padding: 50px">
    <?= CLI_CIDADE ?>, <?= data::porExtenso(date("Y-m-d")) ?>
</div>
<div style="padding: 35px; width: 65%; margin-left: 35%">
    <hr>
    (assinatura e carimbo do responsável DTTIE ou autenticação digital)
</div>


<?php
$pdf->exec();
