<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$n_campanha = $model->campanha();
$dados_aluno = $model->getPessoa($id_pessoa);
$oa = concord::oa($id_pessoa);
$OA = concord::oa($id_pessoa,1);
$seu = concord::seu($id_pessoa);
$SEU = concord::seu($id_pessoa,1);
ob_clean();
ob_start();
$pdf = new pdf();
?>
<style type="text/css">
    .titulo_anexo{
        color: black;
        font-weight: bold;
        text-align: center;
        font-size: 20px;
    }
</style>
<div class="row" >
    <div class="col titulo_anexo">CONVOCAÇÃO</div>
</div>
<br><br>
<div class="row">
    <div class="col">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Informamos que <?= $oa ?> alun<?= $oa ?> <b><?= $dados_aluno['n_pessoa'] ?></b> foi selecionad<?= $oa ?> para Triagem Auditiva pela Campanha de Audição Escolar <b><?= $n_campanha ?></b>. Entrar em contato com a SDPD (Secretaria da Pessoa com Deficiência) - 11 4194-4938, 11 4194-4945, 11 4194-0525 ramal 163 de segunda a sexta das 8h às 12h e das 13h às 16h.
</div>
</div>
<br>
<div class="row">
    <div class="col"><b>ATENÇÃO! ESTE EXAME É DE EXTREMA IMPORTÂNCIA PARA O PLENO DESENVOLVIMENTO DE <?= $SEU ?> FILH<?= $OA ?></b><br>
    </div>
</div>
<br><br>
<div class="row">
    <div class="col" style="text-align:right;"><?= CLI_CIDADE ?>, <?= date("d") ?> de <?= data::mes(date("m")) ?> de <?= date("Y") ?> </div>
</div>
<?php
 //$pdf->exec();
    tool::pdf();
?>
