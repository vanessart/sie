<?php
if (!defined('ABSPATH'))
    exit;
$bimestre = filter_input(INPUT_POST, 'bimestre', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$id_pdi = filter_input(INPUT_POST, 'id_pdi', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa_aluno = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$sexo_aluno = toolErp::sexo_pessoa($id_pessoa_aluno);
$id_pl_atual = curso::id_pl_atual()['id_pl'];
$texto_nome_aluno = 'Nome do Aluno';
if ($sexo_aluno == 'M') {
   $texto_nome_aluno = 'Nome do Aluno';
}else{
    $texto_nome_aluno = 'Nome da Aluna';
}

$hab = sql::get('apd_pdi_hab', 'id_pdi_hab, fk_id_hab, recursos, didatica, obs,habilidade', ['fk_id_pdi' => $id_pdi, 'atualLetiva' => $bimestre ]);
$descr = sql::get('apd_pdi_descritiva','id_descritiva, fk_id_pdi,descri,trabalho,avaliacao,obs, fk_id_pessoa_prof', ['fk_id_pdi' => $id_pdi, 'atualLetiva' => $bimestre], 'fetch');
$qtd_atend = 0;
$atend = $model->getAtend($id_pdi,$bimestre,null,1);
if (!empty($atend)) {
    $qtd_atend = count($atend );
}
$prof_turma = $model->profeSala($id_pessoa_aluno);
$prof_sexo = $model->profeSala($id_pessoa_aluno,'sexo');
$turmaRegular = $model->getTurmaAluno($id_pessoa_aluno,1);
$turmaAEE = $model->getTurmaAluno($id_pessoa_aluno);
$escola = $turmaRegular['n_inst'];
$poloAEE = $turmaAEE['n_inst'];
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
<?php  
$aluno = sql::get('pessoa', 'dt_nasc', ['id_pessoa' => $id_pessoa_aluno],'fetch');
$infoInical = sql::get('apd_pdi','fk_id_turma,fk_id_turma_AEE,id_pdi,coordenador,duracao_plano,area_priori,aspectos,comunic,asp_cognit,asp_fisico,expressao,conhecimento,novas_tec,autonomia,descr_hab,barreira,fk_id_pessoa_prof', ['id_pdi' => $id_pdi], 'fetch'); 
if ($infoInical) { 
    if ($infoInical['fk_id_turma_AEE']) {
        $fotos = $model->getRegFoto($id_pessoa_aluno,$bimestre,$id_pl);
    }
    $prof_AEEArray = $model->getProfAEE($id_pessoa_aluno);
    $prof_AEE = $prof_AEEArray['n_pessoa'].' ('.$prof_AEEArray['rm'].')';
    $prof_ = $prof_AEEArray['prof'];
    $entre = sql::get('apd_doc_entrevista','medicamento,terapia,cid', ['fk_id_pessoa' =>  $id_pessoa_aluno, 'fk_id_turma' => $infoInical['fk_id_turma'] ], 'fetch');
}?>
<div class="body">
    <?= toolErp::cabecalhoSimples() ?>
    <br />
    <div class="row">
        <div class="col titulo_anexo">DEPARTAMENTO EDUCACIONAL ESPECIALIZADO</div>
    </div>
    <div class="row">
        <div class="col sub_anexo">ANEXO II</div>
    </div>
    <br>

    <table style="width: 100%; border-collapse: collapse; background-color: #e7ffcb;" border=1 cellspacing=0 cellpadding=2>
        <tr>
            <td style="font-weight: bold; text-align: center;">
                <div class="row">
                    <div class="col" >PDI - PLANO DE DESENVOLVIMENTO INDIVIDUAL</div>
                </div>
                <div class="row">
                    <div class="col" style="font-weight: bold; text-align: center;FONT-SIZE: 14px;">PARTE I - INFORMAÇÕES E AVALIAÇÃO DO ALUNO</div>
                </div>
                <div class="row">
                    <div class="col" style="font-weight: bold; text-align: center;FONT-SIZE: 14px;">VIDE PROTOCOLO AEE</div>
                </div>
            </td>
        </tr>
    </table>
    <br>

    <table style="width: 100%; border-collapse: collapse; background-color: #e7ffcb;    " border=1 cellspacing=0 cellpadding=2>
        <tr>
            <td style="font-weight: bold; text-align: center;">
                <div class="row">
                    <div class="col sub_anexo">PDI - PLANO DE DESENVOLVIMENTO INDIVIDUAL</div>
                </div>
                <div class="row">
                    <div class="col sub2_anexo">PARTE II - PLANO PEDAGÓGICO ESPECIALIZADO</div>
                </div>  
            </td>
        </tr>
    </table>
    <br>

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
                           <span class="tit_table"><?= $texto_nome_aluno  ?>: </span> <?= @$n_pessoa ?> 
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                           <span class="tit_table">Nasc.: </span> <?= dataErp::converteBr(@$aluno['dt_nasc']) ?>  
                        </td>
                        <td class="anoAtual" >
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
                           <span class="tit_table">Medicamentos: </span><?= @$entre['medicamento'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                           <span class="tit_table">Terapias: </span><?= @$entre['terapia'] ?>
                        </td>
                    </tr>
                    <tr class="anoAtual">
                        <td colspan="3">
                           <span class="tit_table">Polo do AEE: </span><?= @$poloAEE ?>
                        </td>
                    </tr>
                    <tr class="anoAtual">
                        <td colspan="3">
                            <span class="tit_table">U.E. de Origem: </span><?= @$escola ?>
                        </td>
                    </tr>
                    <tr class="anoAtual">
                        <td colspan="3">
                           <span class="tit_table"><?= $prof_ ?> Educação Especial: </span><?= @$prof_AEE ?>
                        </td>
                    </tr>
                    <tr class="anoAtual">
                        <td colspan="3">
                           <span class="tit_table"><?= $prof_sexo ?> do Ensino Regular: </span><?= @$prof_turma ?>
                        </td>
                    </tr>
                    <tr class="anoAtual">
                        <td colspan="3">
                           <span class="tit_table">Coordenador(a) Pedagógico(a): </span><?= @$infoInical['coordenador'] ?>
                        </td>
                    </tr>
                    <tr>
                         <td colspan="2">
                           <span class="tit_table">Total de Atendimentos no bimestre: </span><?= $qtd_atend ?>
                        </td>
                        <td>
                           <span class="tit_table">Duração do Plano: </span><?= @$infoInical['duracao_plano'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                           <span class="tit_table">Áreas a serem priorizadas: </span><?= @$infoInical['area_priori'] ?>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
    <br>
    <div class="row">
        <div class="col tit_table">Descrição das potencialidades que o aluno já possui:</div>
    </div>
    <br>

    <table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
        <tr>
            <td class="tit_linha">
                Aspectos Socioafetivos / Socialização / Comportamentos
            </td>
            <td>
               <?= @$infoInical['aspectos'] ?>
            </td>
        </tr>
        <tr>
            <td class="tit_linha">
                Comunicação - Linguagem Oral/escrita ou Gestual
            </td>
            <td>
               <?= @$infoInical['comunic'] ?>
            </td>
        </tr>
        <tr>
            <td class="tit_linha">
                Aspectos Cognitivos / Raciocínio Lógico 
            </td>
            <td>
               <?= @$infoInical['asp_cognit'] ?>
            </td>
        </tr>
        <tr>
            <td class="tit_linha">
                Aspectos Físicos / Motricidade 
            </td>
            <td>
               <?= @$infoInical['asp_fisico'] ?>
            </td>
        </tr>
        <tr>
            <td class="tit_linha">
                Expressão Artística 
            </td>
            <td>
               <?= @$infoInical['expressao'] ?>
            </td>
        </tr>
        <tr>
            <td class="tit_linha">
                Conhecimento do Mundo 
            </td>
            <td>
               <?= @$infoInical['conhecimento'] ?>
            </td>
        </tr>
        <tr>
            <td class="tit_linha">
                Novas Tecnologias
            </td>
            <td>
               <?= @$infoInical['novas_tec'] ?>
            </td>
        </tr>
        <tr>
            <td class="tit_linha">
                Autonomia e Vida Diária
            </td>
            <td>
               <?= @$infoInical['autonomia'] ?>
            </td>
        </tr>
        
    </table>
    <br>

    <table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
        <tr>
            <td class="tit_linha">
                Descrição das Habilidades a serem desenvolvidas pelo Aluno 
            </td>
            <td>
               <?= @$infoInical['descr_hab'] ?>
            </td>
        </tr>
    </table>
    <br>

    <table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
        <tr>
            <td class="tit_linha">
                Barreiras
            </td>
            <td>
               <?= @$infoInical['barreira'] ?>
            </td>
        </tr>
    </table>
    <br>

<div class="row">
        <div class="col sub_anexo">PARTE II - PLANO PEDAGÓGICO ESPECIALIZADO</div>
    </div>
    <br>

<table style="width: 100%; border-collapse: collapse; background-color: #e7ffcb;    " border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td style="font-weight: bold; text-align: left; border-right: none; padding-left: 5px;">
            <div class="col sub_anexo">Período <?= $bimestre ?>º Bimestre</div> 
        </td>
        <td style="font-weight: bold; text-align: right; border-left: none; padding-right:5px;">
            <div class="col sub_anexo"></div>
        </td>
    </tr>
</table>
<br>

<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2 bordercolor="666633">
    <tr class="sub_anexo" >
        <td style="font-weight: bold;text-align: center;">
           HABILIDADES
        </td>
        <td style="font-weight: bold;text-align: center;">
           RECURSOS
        </td>
        <td style="font-weight: bold;text-align: center;">
           SITUAÇÃO / SEQUÊNCIA DIDÁTICA
        </td>
        <td  style="font-weight: bold;text-align: center;">
           OBSERVAÇÕES
        </td>
    </tr>
    <?php
    foreach ($hab as $k => $v) {?>
        <tr >
            <td >
               <?= $v["habilidade"] ?>
            </td>
            <td >
               <?= $v["recursos"] ?>
            </td>
            <td >
               <?= $v["didatica"] ?>
            </td>
            <td >
               <?= $v["obs"] ?>
            </td>              
        </tr>
        <?php
    } ?>
</table>
<br>


<div class="row">
    <div class="col tit_table">Trabalho realizado com o aluno dentro da sala do AEE:</div>
</div>
<br>


<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2 bordercolor="666633">
    <tr class="sub_anexo" >
        <td style="font-weight: bold;text-align: center;">
           DATA
        </td>
        <td style="font-weight: bold;text-align: center;">
           AÇÕES EM ATENDIMENTO
        </td>
    </tr>
    <?php
    foreach ($atend as $k => $v) {?>
        <tr >
            <td style="width:20%" >
               <?= dataErp::converteBr($v['dt_atend']) ?>
            </td>
            <td >
               <?= $v["acao"] ?>
            </td>           
        </tr>
        <?php
    } ?>
</table>
<br>

<div class="row">
    <div class="col tit_table">Descrição do trabalho realizado com o aluno em parceria com os professores da sala regular e família:</div>
</div>
<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td>
           <?=  @$descr['descri'] ?>
        </td>
    </tr>
</table>
<br>

<div class="row">
    <div class="col tit_table">Trabalho colaborativo com os demais profissionais da escola, possibilitando um olhar inclusivo de toda a equipe escolar :</div>
</div>
<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td>
           <?=  @$descr['trabalho'] ?>
        </td>
    </tr>
</table>
<br>

<div class="row">
    <div class="col tit_table">Avaliação Processual:</div>
</div>
<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td>
           <?=  @$descr['avaliacao'] ?>
        </td>
    </tr>
</table>
<br>

<div class="row">
    <div class="col tit_table">Observações Relevantes:</div>
</div>
<table style="width: 100%; border-collapse: collapse;" border=1 cellspacing=0 cellpadding=2>
    <tr>
        <td>
           <?=  @$descr['obs'] ?>
        </td>
    </tr>
</table>
<br>

<div class="row">
    <div class="col tit_table">REGISTRO DE IMAGENS</div>
</div>
<?php
if (!empty($fotos)) {
    foreach ($fotos as $v) {
        ?>
        <!--div style="height: 90vh; width: 90vw;"-->
            <table class="table table-bordered border" align="center">
                <tr>
                    <td colspan="3" style="text-align: center; font-weight: bold">
                        <?= $v['n_foto'] ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center;">
                        <?php
                        if (empty($v['link_video'])) {
                            if (file_exists(ABSPATH . '/pub/fotoApd/' . $v['link'])) {?>
                                <img style="max-height: 85vh; max-width: 85vw;" src="<?= HOME_URI . '/pub/fotoApd/' . $v['link'] ?>" alt="foto"/>
                                <?php
                            }
                        }else{?>
                            Use a câmera do celular para acessar o link do video <br>
                            <img src="<?= HOME_URI ?>/app/code/php/qr_img.php?d=<?= urlencode($v['link_video']) ?>&.PNG" width="120" height="120"/>
                            <?php
                        }?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" >
                        <?= data::converteBr($v['dt_foto']) ?><?= !empty($v['fk_id_pessoa_prof']) ? " - Autor: ".toolErp::n_pessoa($v['fk_id_pessoa_prof']) : "" ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <?= $v['descricao'] ?>
                    </td>
                </tr>
            </table>
            <br /><br />
        <!--/div-->
        <?php
    }
}else{
echo '<div style="padding-left: 15px;"><strong>Sem Registro </strong></div>';
} ?>
<br>

<div class="row" class="anoAtual">
    <div class="col tit_table anoAtual"><?= $prof_ ?> Responsável pelas informações: ____________________________________________</div>
</div>
<br>

<div class="row" class="anoAtual">
    <div class="col tit_table anoAtual">Ciência da Equipe de Gestão: __________________________________________________________</div>
</div>
<br>
<br>

<div class="row" style="text-align:center;" class="anoAtual">
    <div class="col tit_table anoAtual">Barueri, <?= date("d") ?> de <?= data::mes(date("m")) ?>  de <?= date("Y") ?></div>
</div>

</div>

<script type="text/javascript">

    <?php 
    if ($id_pl == $id_pl_atual) {?>
        window.onload = function() {
             this.print();
        }
       <?php 
    }else{?>
        $(".anoAtual").hide();
        <?php 
    }?>
</script>