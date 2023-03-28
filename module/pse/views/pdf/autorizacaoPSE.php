<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$n_campanha = filter_input(INPUT_POST, 'n_campanha', FILTER_SANITIZE_STRING);
$resp = filter_input(INPUT_POST, 'resp', FILTER_SANITIZE_NUMBER_INT);
$qrCode = filter_input(INPUT_POST, 'qrCode', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$oa = concord::oa($id_pessoa);
$dados_aluno = $model->getPessoa($id_pessoa);

if (empty($id_pl)) {
   $id_pl = $model->campanha('id_pl'); 
}

if (empty($n_campanha)) {
   $n_campanha = $model->campanha(); 
}
$form = formDB::getForm(1);
$d = toolErp::encrypt($id_pessoa);
$end = $_SERVER['HTTP_HOST'].HOME_URI .'/pse/autorizacaoPSE?d='. $d;

 ob_clean();
 ob_start();
?>
<style type="text/css">
    .per::first-letter {
    text-transform: uppercase;
    }
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
    <div class="col titulo_anexo">AUTORIZAÇÃO DE PARTICIPAÇÃO NAS ATIVIDADES PROGRAMA SAÚDE NA ESCOLA <br> <br>PSE - <?= @$n_campanha ?></div>
</div>
<br>
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
    $assinaturaArray = formDB::getAssinatura(1,$id_pessoa,$id_pl);
    if (!empty($assinaturaArray)) {
        $dadosForm = formDB::getViewPDFformatado($form,$id_pessoa,1,$id_pl);
        $assinatura = '<img src="'. $assinaturaArray['assinatura'] .'" style="width:25%" />';
        $assinatura_rod = "Documento assinado digitalmente por ".strtoupper($dadosForm['21']).". <br>Data: ". date('d/m/Y H:i:s', strtotime($assinaturaArray['dt_update'])) ." | IP: ". $assinaturaArray['IP'];
        ?>
        <br><br>
        <div class="row">
            <div  class="col col-sm">
                Eu, <?= @$dadosForm['21']?>, RG <?= $dadosForm['20'] ?>, responsável legal por <?= $dados_aluno['n_pessoa'] ?>,  declaro serem verdadeiras as informações acima.
            </div>
        </div>
        <br>
        <div style="text-align: center;margin: 40px 0;">
            <div style="margin: 0 auto;display: inline-block;vertical-align: middle;<?php if (empty($externo)) { ?>width: 49%;<?php } ?>">
                <div style="text-align:center;margin-top: 30px;">
                    <div><?php echo $assinatura ?></div>
                </div>
                <p style="width: 350px;margin: 0 auto;border-top: 1px solid #000;"><?= $dadosForm['21'] ?></p>
            </div>
        </div>
        <div style="position: absolute;bottom: 70px;color: #a1a1a1;">
            <?php echo $assinatura_rod ?>
        </div>
        <?php
        
    }else{
        $dadosForm = formDB::getViewPDF($form,$id_pessoa,1,$id_pl);
        ?>
        <br><br>
        <div class="row">
            <div  class="col col-sm">
                Eu, ___________________________________________________________________, RG _____________________________, responsável legal pel<?= $oa ?> alun<?= $oa ?> <?= $dados_aluno['n_pessoa'] ?>,  declaro serem verdadeiras as informações acima. DECLARO ESTAR CIENTE E DE ACORDO.
            </div>
        </div>
        <br><br>
        <div class="row" style="text-align:right;">
            <div  class="col col-sm">
                <?= CLI_CIDADE ?>, <?= date("d") ?> de <?= data::mes(date("m")) ?> de <?= date("Y") ?>
            </div>
        </div> 
        <br><br>
        <div class="row" style="text-align:center;">
            <div  class="col col-sm">
                _________________________________________________________________________________ 
            </div>
        </div> 
        <div class="row" style="text-align:center;">
            <div  class="col col-sm">
                Assinatura do Responsável Legal
            </div>
        </div> 
        <br>
        <?php 
    }
}
tool::pdf();
?>
