<?php
if (!defined('ABSPATH'))
    exit;
$id_aval = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

$aval = sql::get('apd_doc_aval', 'dt_inicio,dt_fim,av_socializacao,av_aspectos,av_pensamento,av_aspectosF,av_inter,av_obs,fk_id_pessoa_prof,fk_id_pessoa,fk_id_turma', ['id_aval' => $id_aval], 'fetch');

if ($aval) {
    $id_pessoa_aluno = $aval['fk_id_pessoa'];
    $prof = toolErp::n_pessoa($aval['fk_id_pessoa_prof']); 
    $prof_sexo = toolErp::sexo_pessoa($aval['fk_id_pessoa_prof']);
   if ($prof_sexo == 'M') {
       $prof_artigo = 'Prof.';
    }else{
       $prof_artigo = 'Profa.';

    }
    $prof_turma = $model->profeSala($id_pessoa_aluno);
    $prof_sexo = $model->profeSala($id_pessoa_aluno,'sexo');
    $turma = sql::get('ge_turmas', 'fk_id_inst,periodo,periodo_letivo,n_turma', ['id_turma' => $aval['fk_id_turma']], 'fetch');
    $prof_AEEArray = $model->getProfAEE($id_pessoa_aluno);
    $prof_AEE = $prof_AEEArray['n_pessoa'].' ('.$prof_AEEArray['rm'].')';
    $prof_ = $prof_AEEArray['prof'];
    $n_turma = $turma['periodo_letivo']." / ".$turma['n_turma'];
    $n_pessoa = toolErp::n_pessoa($id_pessoa_aluno);
    $turmaAEE = $model->getTurmaAluno($id_pessoa_aluno);
    $poloAEE = $turmaAEE['n_inst'];
    $escola = toolErp::n_inst($turma['fk_id_inst']);
    $aluno = sql::get('pessoa', 'dt_nasc', ['id_pessoa' => $id_pessoa_aluno],'fetch');
    $entre = sql::get('apd_doc_entrevista','cid', ['fk_id_pessoa' =>  $id_pessoa_aluno, 'fk_id_turma' => $aval['fk_id_turma'] ], 'fetch');

    $sexo_aluno = toolErp::sexo_pessoa($id_pessoa_aluno);
    $texto_nome_aluno = 'Nome do Aluno';
    if ($sexo_aluno == 'M') {
       $texto_nome_aluno = 'Nome do Aluno';
    }else{
        $texto_nome_aluno = 'Nome da Aluna';
    }
}

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
<div class="body">
    <?= toolErp::cabecalhoSimples() ?>
    <br />

    <table style="width: 100%; border-collapse: collapse; " border=1 cellspacing=0 cellpadding=2>
        <tr>
            <td>
                <table style="width: 100%; border-collapse: collapse; " border=0 cellspacing=0 cellpadding=2>
                    <tr>
                        <td colspan="3" align="center">
                            <?php
                            if (file_exists(ABSPATH . '/pub/fotos/' . @$id_pessoa_aluno . '.jpg')) {
                                ?>  

                                <img src="<?= HOME_URI ?>/pub/fotos/<?= $id_pessoa_aluno ?>.jpg" width="100" height="120">

                                <?php
                            } else {
                                ?>
                                <img src="<?= HOME_URI ?>/includes/images/anonimo.jpg" width="100" height="120"/>
                                
                                <?php
                            }
                            ?> 
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                           <span class="tit_table"><?= $texto_nome_aluno ?>: </span> <?= @$n_pessoa ?> 
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                           <span class="tit_table">Nasc.: </span> <?= dataErp::converteBr(@$aluno['dt_nasc']) ?>  
                        </td>
                        <td>
                           <span class="tit_table"> Ano/Turma:</span> <?= @$n_turma ?>
                        </td>
                        <?php 
                        if (!empty($entre['cid'])) {?>
                            <td>
                               <span class="tit_table">CID: </span><?= @$entre['cid'] ?><br>
                            </td>
                           <?php  
                        }?>
                    </tr>
                    <tr>
                        <td colspan="3">
                           <span class="tit_table">Polo do AEE: </span><?= @$poloAEE ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <span class="tit_table">U.E. de Origem: </span><?= @$escola ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                           <span class="tit_table"><?= $prof_ ?> Educação Especial: </span><?= @$prof_AEE ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                           <span class="tit_table"><?= $prof_sexo ?> do Ensino Regular: </span><?= @$prof_turma ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <br>

<div class="row">
    <div class="col sub_anexo">1.7 - RELATÓRIO DE AVALIAÇÃO INICIAL DO ALUNO - <?= date('Y') ?></div>
</div>
<br>

 <table style="width: 100%; border-collapse: collapse;" border=0 cellspacing=0 cellpadding=2>
    <tr>
        <td style="font-weight: bold; text-align: center;">
            Data de Início: <?= dataErp::converteBr(@$aval['dt_inicio']) ?>
        </td>
        <td style="font-weight: bold; text-align: center;">
            Data de Término: <?= dataErp::converteBr(@$aval['dt_fim']) ?>
        </td>
    </tr>
</table>
<br>

<span class="titulo"> I - SOCIALIZAÇÃO / COMPORTAMENTO ASPECTOS SOCIOAFETIVO </span>
<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td>
            <?= @$aval['av_socializacao'] ?>
        </td>
    </tr>
</table>
<br>

<span class="titulo">II - ASPECTOS DA LINGUAGEM ORAL, GESTUAL, ESCRITA, E COMUNICAÇÃO </span>
<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td>
            <?= @$aval['av_aspectos'] ?>
        </td>
    </tr>
</table>
<br>

<span class="titulo">III - PENSAMENTO LÓGICO/COGNIÇÃO</span>
<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td>
            <?= @$aval['av_pensamento'] ?>
        </td>
    </tr>
</table>
<br>

<span class="titulo">IV - ASPECTOS FÍSICOS / MOTRICIDADE</span>
<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td>
            <?= @$aval['av_aspectosF'] ?>
        </td>
    </tr>
</table>
<br>

<span class="titulo"> V - INTERVENÇÕES QUE FORAM REALIZADAS NO DECORRER DA AVALIAÇÃO</span>
<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td>
            <?= @$aval['av_inter'] ?>
        </td>
    </tr>
</table>
<br>

<span class="titulo"> OBSERVAÇÕES IMPORTANTES</span>
<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td>
            <?= @$aval['av_obs'] ?>
        </td>
    </tr>
</table>
<br><br>


<div class="row">
    <div class="col sub_anexo">_________________________________________________________________</div>
</div>

<div class="row">
    <div class="col sub_anexo"><?= @$prof ?></div>
</div>

<div class="row">
    <div class="col sub_anexo"><?= $prof_artigo ?> Educação Especial</div>
</div>
</div>

<script type="text/javascript">
    window.onload = function() {
         this.print();
    }
</script>
