<?php
if (!defined('ABSPATH'))
    exit;
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$alunos = $model->getRespostaByEncaminhamento(null, $id_turma);
$n_campanha = $model->campanha();
 ob_clean();
 ob_start();
 //$pdf = new pdf();
?>
<style type="text/css">
    .titulo_anexo{
        color: black;
        font-weight: bold;
        text-align: center;
        font-size: 20px;
    }
    .titulo_ped{
        color: black;
        font-weight: bold;
        font-size: 14px;
    }
</style>
<?php
foreach ($alunos as $k => $v) {
    $dados_aluno = $model->getPessoa($v['id_pessoa']);
    $oa = concord::oa($v['id_pessoa']);
    if (isset($v['encaminhamento'][2])) {//queixa vestibular
        ?>
        <table style="width: 100%" cellspacing=0 cellpadding=2>
            <tr>
                <td  style="width: 10%; text-align: center">
                    <img style="width: 100px" src="<?php echo HOME_URI ?>/views/_images/logo.jpg"/>
                </td>
                <td style="padding-top: 5px; width: 80%; text-align: center">
                    <div style="font-size: 22px; font-weight: bold">
                        <?= CLI_NOME ?>
                        <br />
                        Secretaria dos Direitos da Pessoa com Deficiência
                    </div>
                     <?php
                    if (!empty($n_inst)) {?>
                       <div style="font-size: 18px">
                            <?= $n_inst ?>
                        </div> 
                        <?php
                    }?>
                </td>
            </tr>  
        </table>
        <br><br>
        <div class="row" >
            <div class="col titulo_anexo">ENCAMINHAMENTO</div>
        </div>
        <br><br>
        <div class="row">
            <div class="col" style="text-align:right;"><?= CLI_CIDADE ?>, <?= date("d") ?> de <?= data::mes(date("m")) ?> de <?= date("Y") ?> </div>
        </div>
        <br /><br>
        <div class="row">
            <div class="col titulo_ped">AO/À<br>Pediatra – Unidade Básica de Saúde – UBS.
         </div>
        </div>
        <br /><br>
        <div class="row">
            <div class="col">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Encaminhamos para avaliação COM OTORRINOLARINGOLOGISTA <?= $oa ?> menor <b><?= $dados_aluno['n_pessoa'] ?></b>, RG <b><?= $dados_aluno['rg'] ?></b>, D.N. <b><?= $dados_aluno['dt_nasc'] ?></b>, alun<?= $oa ?> da <?= toolErp::n_inst() ?>, responsável <b><?= $dados_aluno['mae'] ?></b><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Informamos que <?= $oa ?> usuári<?= $oa ?> está sendo acompanhad<?= $oa ?> pela <b>CAMPANHA DA AUDIÇÃO - <?= $n_campanha ?></b></div>
        </div>
        <br>
        <div class="row">
            <div class="col"><b>OBSERVAÇÕES:</b><br> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Na triagem realizada na Campanha <?= $oa ?> alun<?= $oa ?> apresenta queixas vestibulares passíveis de investigação.</div>
        </div>
        <div class="row">
            <div class="col titulo_anexo"><b>____________________________________________________________________________</b></div>
        </div>
        <div class="row">
            <div class="col titulo_anexo"><b>____________________________________________________________________________</b></div>
        </div>
        <div class="row">
            <div class="col titulo_anexo"><b>____________________________________________________________________________</b></div>
        </div>
        <div class="row">
            <div class="col titulo_anexo"><b>____________________________________________________________________________</b></div>
        </div>
        <div class="row">
            <div class="col titulo_anexo"><b>____________________________________________________________________________</b></div>
        </div>
        <br>
        <div class="row">
            <div class="col">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Favor encaminhar relatório com hipótese diagnóstica com CID e conduta em relação à avaliação e possível indicação de reabilitação vestibular.</div>
        </div>
        <br><br><br>
        <div class="row" style="text-align: center;">
            <div class="col">Atenciosamente<br>Equipe de Fonoaudiologia – Depto de Tecnologia Assistiva – deficiência auditiva<br>
        Secretaria dos Direitos da Pessoa com Deficiência - SDPD</div>
        </div>
        <div style="page-break-after: always"></div> 
        <?php
    }
}
//$pdf->exec();
tool::pdf();
?>
