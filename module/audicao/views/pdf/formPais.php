<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$qrCode = filter_input(INPUT_POST, 'qrCode', FILTER_SANITIZE_NUMBER_INT);
$form = $model->getForm(1);
$dados_aluno = $model->getPessoa($id_pessoa);

$d = toolErp::encrypt($id_pessoa);
$end = $_SERVER['HTTP_HOST'].HOME_URI .'/audicao/formPais?d='. $d;

 ob_clean();
 ob_start();
 //$pdf = new pdf();
?>
<style type="text/css">
    .titulo_anexo{
        color: black;
        font-weight: bold;
        text-align: center;
        font-size: 13px;
        margin-top: -10px;
    }
    .titulo_anexo2{
        color: black;
        font-weight: bold;
        text-align: center;
        font-size: 14px;
        text-decoration: underline;
    }
    .sub_anexo{
        font-weight: bold;
        text-align: center;
    }
    .sub2_anexo{
        font-weight: bold;
        text-align: center;
        FONT-SIZE: 14px;

    }
    .comp{
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        color: red;
    }
    .tit_table{
        font-weight: bold;
    }
    .tit_linha{
        font-weight: bold;
        font-size: 12px;
        width: 30%;
    }
    .tabela{
        width: 100%;
        border: 1;
        font-size: 12px;
        cellspacing: 0;
        cellpadding: 2;
    }
    .tabela td{
        padding: 4px;
    }
</style>

<div class="row">
    <div class="col titulo_anexo">Protocolo de Investigação de Saúde Auditiva - Campanha da Audição – Caminhos do Som – Ano <?= date('Y') ?></div>
</div>
<br>
<div class="row">
    <div class="col titulo_anexo2">QUESTIONÁRIO AOS PAIS</div>
</div>
<br />
<table style="font-size: 12px; width: 100%; border-collapse: collapse; " border=1 cellspacing=0 cellpadding=5>
    <tr>
        <td>
            <b>Aluno:</b> <?= $dados_aluno['n_pessoa'] ?>
        </td>
        <td rowspan="6" style="border-right: none; border-bottom: none; border-top: none;text-align: right;">
            <img src="<?= HOME_URI ?>/app/code/php/qr_img.php?d=<?= urlencode($end) ?>&.PNG" width="120" height="120"/>
        </td>
    </tr>
    <tr>
        <td>
            <b>Data de Nasc:</b> <?= dataErp::converteBr($dados_aluno['dt_nasc']) ?>
        </td>
    </tr>
    <tr>
        <td>
            <b>Nome da Mãe:</b> <?= $dados_aluno['mae'] ?>
        </td>
    </tr>
    <tr>
        <td>
            <b>Endreço:</b> <?= $dados_aluno['endereco'] ?>
        </td>
    </tr>
    <tr>
        <td>
            <b>Escola:</b> <?= toolErp::n_inst() ?>
        </td>
    </tr>
    <tr>
        <td>
            <b>Telefone:</b> <?= $dados_aluno['tel'] ?>
        </td>
    </tr>
</table>

<?php 
if (empty($qrCode)) {
   $model->getViewPDF($form,$id_pessoa,1);  
}
//$pdf->exec();
tool::pdf();
?>
