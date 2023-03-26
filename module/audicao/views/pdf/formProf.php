<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_campanha = filter_input(INPUT_POST, 'id_campanha_pdf', FILTER_SANITIZE_NUMBER_INT);
$form = $model->getForm(2);
$prof_responde = sql::get('audicao_form_resposta', 'fk_id_pessoa_responde', ['fk_id_form' => '2', 'fk_id_pessoa' => $id_pessoa, 'fk_id_campanha' => $id_campanha ], 'fetch');
if (!empty($prof_responde)) {
   $prof = $model->getProf($prof_responde['fk_id_pessoa_responde']); 
}
$dados_aluno = $model->getPessoa($id_pessoa);
$aluno_turma = $model->alunoSala($id_pessoa);
$result_escola = sql::get('ge_escolas', 'cie_escola', ['fk_id_inst' => toolErp::id_inst()], 'fetch');
$cie_escola = $result_escola['cie_escola'];
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
    <div class="col titulo_anexo2">QUESTIONÁRIO AOS PROFESSORES</div>
</div>
<br />
<table style="width: 100%; border-collapse: collapse; font-size: 12px; " border=1 cellspacing=0 cellpadding=5>
    <tr>
        <td colspan="2">
            <b>Aluno:</b> <?= $dados_aluno['n_pessoa'] ?>
        </td>
        <td>
            <b>Mãe:</b> <?= $dados_aluno['mae'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="2" width="95%">
            <b>Professor:</b> <?= @$prof['n_pessoa'] ?>
        </td>
        <td >
            <b>RM:</b> <?= @$prof['rm'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Escola:</b> <?= toolErp::n_inst() ?>
        </td>
        <td>
            <b>Código CIE:</b> <?= $cie_escola ?>
        </td>
    </tr>
    <tr>
        <td >
            <b>Turma:</b> <?= $aluno_turma['n_turma'] ?>
        </td>
        <td >
            <b>Período:</b> <?= $aluno_turma['periodo'] ?>
        </td>
        <td >
            <b>Código de Classe:</b> <?= $aluno_turma['codigo'] ?>
        </td>
    </tr>
</table>

<?php $model->getViewPDF($form,$id_pessoa,2); ?>
<div class="row">
    <div class="col titulo_anexo"><b>____________________________________________________________________________________________________________________</b></div>
</div>
<br>
<div>
    <b>CAMPO A SER PREENCHIDO EXCLUSIVAMENTE PELA SDPD</b>
</div>
<br>
<div>
    <b>Encaminhamentos:</b> ( ) Triagem Auditiva ( ) Queixa Vestibular
</div>
<div class="row">
    <div class="col"><b>Usuário de: </b>( ) AASI ( ) IC</div>
</div>
<div class="row">
    <div class="col"><b>( ) Dispensado</b></div>
</div>
<div class="row">
    <div class="col titulo_anexo"><b>____________________________________________________________________________________________________________________</b></div>
</div>
<div class="row">
    <div class="col" style="font-size: 10px;">
        <b>Elaborado por: Fonoaudiólogas do Depto Técnico Tecnologia Assistiva - SDPD</b>
        <br /><b>Solange M. Lança</b>
        <br /><b>Rina Lamboglia</b>
        <br /><b>Carolina Calsolari</b>
    </div>
</div>
<?php
 //$pdf->exec();
    tool::pdf();
?>
