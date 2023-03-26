<?php
if (!defined('ABSPATH'))
    exit;
$id_entre = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$bimestre = filter_input(INPUT_POST, 'bimestre', FILTER_SANITIZE_NUMBER_INT);
//$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$id_pdi = filter_input(INPUT_POST, 'id_pdi', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa_aluno = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_turma_aluno = $model->getTurmaAluno($id_pessoa_aluno,1)['id_turma'];
$atualiza = $model->getAtualizacaoEntre($id_entre);
if (empty($id_turma)) {
   $id_turma = $id_turma_aluno;
}
$entre = sql::get('apd_doc_entrevista','medicamento,terapia,pessoas_casa,gestacao,intercorrencia,cognitivo,vida_esc,convivio,aliment,considera,desenvol,lingu,fisico,sens,hist_saude,habitos,outras_info,resp_info,dt_entrevista,cid', ['id_entre' => $id_entre], 'fetch');
$turma = sql::get('ge_turmas', 'fk_id_inst,periodo,periodo_letivo,n_turma', ['id_turma' => $id_turma], 'fetch');

$id_inst = $turma['fk_id_inst'];
$escola = toolErp::n_inst($id_turma);
if ($turma['periodo'] == 'M') {
    $periodo = "Manhã";
}else if($turma['periodo'] == 'T'){
    $periodo = "Tarde";
}else{
    $periodo = "Integral";
}
$n_turma = $turma['periodo_letivo']." / ".$turma['n_turma']." / ".$periodo;
$aluno = sql::get('pessoa', 'dt_nasc,pai,mae', ['id_pessoa' => $id_pessoa_aluno],'fetch');
$prof_AEEArray = $model->getProfAEE($id_pessoa_aluno);
$prof_AEE = $prof_AEEArray['n_pessoa'];

if (!empty($aluno)) {
    $idade = $model->idade($aluno['dt_nasc']);
}

$desenvol = [];
$fisico = [];
$lingu = [];
$sens = [];
$hist_saude = [];

if (!empty($entre)) {
   $desenvol = explode(",", $entre['desenvol']);
   $fisico = explode(",", $entre['fisico']);
   $lingu = explode(",", $entre['lingu']);
   $sens = explode(",", $entre['sens']);
   $hist_saude = explode(",", $entre['hist_saude']);
}

$n_desenvol = $model->GetrespEntre('desenvol',$desenvol,$id_entre);
$n_sens = $model->GetrespEntre('sens',$sens, $id_entre);
$n_fisico = $model->GetrespEntre('fisico',$fisico,$id_entre);
$n_lingu = $model->GetrespEntre('lingu',$lingu,$id_entre);
$n_hist_saude = $model->GetrespEntre('hist_saude',$hist_saude,$id_entre);

?>
<style type="text/css">
    .titulo_anexo{
        color: #16baed;
        font-weight: bold;
        text-align: center;
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
        font-size: 18px;
    }
    .tit_linha{
        font-weight: bold;
        
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

<div class="body">
    <?= toolErp::cabecalhoSimples() ?>
    <br />

<div class="row">
    <div class="col sub_anexo">1.4 - ENTREVISTA FAMILIAR - <?= date('Y') ?><br>(Realizado pelo professor do AEE)</div>
</div>
<br>

<div class="row">
    <div class="col tit_table">1) Dados pessoais:</div>
</div>
<br>

<table style="width: 100%; border-collapse: collapse;" border=0 cellspacing=0 cellpadding=2>
    <tr>
        <td colspan="3">
            <span class="tit_linha">Nome do Aluno: </span><?= @$n_pessoa ?>
        </td>
    </tr>
    <tr>
        <td>
           <span class="tit_linha">Data de Nascimento: </span><?= dataErp::converteBr($aluno['dt_nasc']) ?>
        </td>
        <td>
           <span class="tit_linha">Idade: </span><?= @$idade ?>
        </td>
        <td>
           <span class="tit_linha">CID: </span><?= @$entre['cid'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <span class="tit_linha">Medicamentos: </span><?=  @$entre['medicamento'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <span class="tit_linha">Terapias: </span><?=  @$entre['terapia'] ?>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <span class="tit_linha">Escola Regular: </span><?=  $escola ?>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <span class="tit_linha">Ano/Turma/Turno: </span><?=  @$n_turma ?>
        </td>
    </tr>
    
</table>
<br>

<div class="row">
    <div class="col tit_table">2) Dados familiares:</div>
</div>
<br>

<table style="width: 100%; border-collapse: collapse;" border=0 cellspacing=0 cellpadding=2>
    <tr>
        <td>
            <span class="tit_linha">Nome do Pai: </span><?= @$aluno['pai'] ?>
        </td>
    </tr>
    <tr>
        <td>
            <span class="tit_linha">Nome da Mãe: </span><?= @$aluno['mae'] ?>
        </td>
    </tr>
    <tr>
        <td>
            <span class="tit_linha">Pessoas que moram na casa: </span><?= @$entre['pessoas_casa'] ?>
        </td>
    </tr>
    
</table>
<br>

<div class="row">
    <div class="col tit_table">3) A gestação foi planejada?</div>
</div>
<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td>
            <?= @$entre['gestacao'] ?>
        </td>
    </tr>
</table>
<br>

<div class="row">
    <div class="col tit_table">4) Houve Intercorrências neonatal?</div>
</div>
<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td>
            <?= @$entre['gestacao'] ?>
        </td>
    </tr>
</table>
<br>

<div class="row">
    <div class="col tit_table">5) Desenvolvimento:</div>
</div>
<br>

<table style="width: 100%; border-collapse: collapse;" border=0 cellspacing=0 cellpadding=2>
    <tr>
        <td>
            <span class="tit_linha">Desenvolvimento Psicomotor -> </span><?= @$n_desenvol ?>
        </td>
    </tr>
    <tr>
        <td>
            <span class="tit_linha">Linguagem -> </span><?= @$n_lingu ?>
        </td>
    </tr>
    <tr>
        <td>
            <span class="tit_linha">Físico -> </span><?= @$n_fisico ?>
        </td>
    </tr>
    <tr>
        <td>
            <span class="tit_linha">Sensorial -> </span><?= @$n_sens ?>
        </td>
    </tr>
    <tr>
        <td>
            <span class="tit_linha">Histórico de Saúde -> </span><?= @$n_hist_saude ?>
        </td>
    </tr>
    
</table>
<br>

<div class="row">
    <div class="col tit_table">6) Vida escolar:</div>
</div>
<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td>
            <?= @$entre['vida_esc'] ?>
        </td>
    </tr>
</table>
<br>

<div class="row">
    <div class="col tit_table">7) Convívio Social:</div>
</div>
<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td>
            <?= @$entre['convivio'] ?>
        </td>
    </tr>
</table>
<br>

<div class="row">
    <div class="col tit_table">8) Alimentação e cuidados pessoais:</div>
</div>
<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td>
            <?= @$entre['aliment'] ?>
        </td>
    </tr>
</table>
<br>

<div class="row">
    <div class="col tit_table">9) Como você considera o convívio familiar?</div>
</div>
<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td>
            <?= @$entre['considera'] ?>
        </td>
    </tr>
</table>
<br>

<div class="row">
    <div class="col tit_table">10) Hábitos Típicos e Atípicos:</div>
</div>
<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td>
            <?= @$entre['habitos'] ?>
        </td>
    </tr>
</table>
<br>

<div class="row">
    <div class="col tit_table">11) Outras informações que julgar necessárias:</div>
</div>
<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td>
            <?= @$entre['outras_info'] ?>
        </td>
    </tr>
</table>
<br>
<?php 
foreach ($atualiza as $v) {?>    
    <div class="row">
        <div class="col tit_table">ATUALIZAÇÃ0 <?= $v['n_pl'] ?></div>
    </div>
    <table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
        <tr>
            <td>
                <?= $v['atualizacao'] ?>
            </td>
        </tr>
    </table>
    <br>
    <?php
}?>
<br>
<br>
<br>

<table style="width: 100%; border-collapse: collapse;" border=0 cellspacing=0 cellpadding=2>
    <tr >
        <td  style="text-align:center;">
            ____________________________________________
        </td>
        <td  style="text-align:center;">
            ____________________________________________
        </td>
    </tr>
    <tr>
        <td  style="text-align:center;">
            <?= @$entre['resp_info'] ?> <br> Responsável pelas informações
        </td>
        <td  style="text-align:center;">
            <?= $prof_AEE ?> <br> Professor(a) AEE
        </td>
    </tr>
</table>
<br>
<br>

<div class="row" style="text-align:center;">
    <div class="col tit_table">Barueri, <?= date("d") ?> de <?= data::mes(date("m")) ?>  de <?= date("Y") ?></div>
</div>

</div>

<script type="text/javascript">
    window.onload = function() {
         this.print();
    }
</script>
