<?php
extract($_POST);

$h['M1'] = '7h20 às 9h20';
$h['M2'] = '9h20 às 11h20';
$h['T1'] = '13h50 às 15h50';
$h['T2'] = '15h50 às 17h50';
ob_start();
$pdf = new pdf();
$pdf->headerSet = null;
$pdf->mgt = 0;
?>
<img src="<?= HOME_URI ?>/includes/images/maker/tdics.png" alt="alt"/>
<br /><br />
<div style="text-align: center; font-weight: bold; font-size: 20px">
    PROJETO - PARNAÍBA - MAKER LABS
</div>
<br />
<div style="text-align: center; font-weight: bold; font-size: 19px; color: red">
    Público Alvo: 5º ao 9º anos
</div>
<br /><br />
<div style="text-align: center; font-weight: bold; font-size: 19px;">
    TERMO DE MATRÍCULA
</div>
<br /><br />
<div style="text-align: justify; font-weight: bold; font-size: 16px;">
    Eu________________________________________________________________________________________, 
    <br /><br />
    portador (a) do RG
    __________________________________________________ responsável legal pel<?= toolErp::sexoArt($sexo) ?> alun<?= toolErp::sexoArt($sexo) ?>
    <?= $n_pessoa ?>, matriculad<?= toolErp::sexoArt($sexo) ?> no <?= $turmaEsc ?> 
    na <?= $n_inst ?>,
    <br /><br />
    AUTORIZO a participação d<?= toolErp::sexoArt($sexo) ?> alun<?= toolErp::sexoArt($sexo) ?> no Projeto MAKER LABS, conforme segue:
</div>
<br /><br />
<table style="width: 100%; margin: auto; font-weight: bold; font-size: 15px"  cellspacing=0 cellpadding=2 bordercolor="666633"> 
    <tr>
        <td>
            NÚCLEO (Escola):
        </td>
        <td colspan="3">
            <?= $n_polo ?>
        </td>
    </tr>
    <tr>
        <td>
            CURSO:
        </td>
        <td>
            <?= $n_curso ?>
        </td>
        <td>
            CÓD. TURMA:
        </td>
        <td>
            <?= $n_turma ?>
        </td>
    </tr>
    <tr>
        <td>
            DIA DA SEMANA: 
        </td>
        <td>
            <?= $dia ?>
        </td>
        <td>
            HORÁRIO:
        </td>
        <td>
            <?= $h[$periodo . $horario] ?>
        </td>
    </tr>
</table>
<br /><br />
<div style="text-align: justify; font-weight: bold; font-size: 16px;">
    Estou de acordo com o cronograma e demais regulamentos já estabelecidos pela Secretaria
    Municipal de Educação no ato da matrícula no Ensino Fundamental.
    <br /><br />
    Sem mais,

</div>
<br />
<div style="text-align: right; padding: 30px; font-weight: bold; font-size: 16px;">
    <?= CLI_CIDADE ?>, <?= dataErp::porExtenso(date("Y-m-d")) ?>
    <br /><br /><br /><br /><br /><br /><br />
    ___________________________________________________
    <br />  
    Assinatura: Responsável Legal
</div>
<?php
$pdf->exec('Termo_de_Matr.pdf');
