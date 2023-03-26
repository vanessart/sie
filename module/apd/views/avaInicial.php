<?php
if (!defined('ABSPATH'))
    exit;

if(toolErp::id_nilvel() == 10) {
    $disabled = "disabled";
}else{
    $disabled = "";
}
$id_pessoa_aluno = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_turma_AEE = filter_input(INPUT_POST, 'id_turma_AEE', FILTER_SANITIZE_NUMBER_INT);
$id_aval = filter_input(INPUT_POST, 'id_aval', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa_prof = toolErp::id_pessoa();

$aval = sql::get('apd_doc_aval', 'dt_inicio,dt_fim,av_socializacao,av_aspectos,av_pensamento,av_aspectosF,av_inter,av_obs', ['id_aval' => $id_aval], 'fetch');

if (!empty($aval['dt_inicio'])) {
    $data_inicio = $aval['dt_inicio'];
}else{
    $data_inicio = date("Y-m-d");
}

?>
<style type="text/css">
    .input-group-text
    {
    display: none;
    }

    .titulo { 
        color: #888;
        font-size: 16px;
        padding-bottom: 5px;
    }
    .tituloG { 
        font-weight: bold;
        color: #888;
        font-size: 18px;
        margin-bottom: 5px;
        text-align: center;
        padding: 10px;
        padding-bottom: 20px;
    }
    .info{
        margin-bottom: 5px;
    }
</style>

<div class="body">
    <div class="tituloG">
        RELATÓRIO DE AVALIAÇÃO INICIAL DO ALUNO
    </div>

    <form id="formEnvia" target="_parent" action="<?= HOME_URI ?>/apd/doc" method="POST">
        <div class="row">
            <div class="col"> 
                <span class="titulo">DATA DE INÍCIO <?= $data_inicio ?></span>
                <?= (toolErp::id_nilvel() <> 24) ? data::converteBr($data_inicio) : formErp::input('1[dt_inicio]', 'DATA DE INÍCIO', $data_inicio, ' required', null, 'date')  ?>
            </div>
            <div class="col">
                <span class="titulo">DATA DE TÉRMINO</span>
                <?= (toolErp::id_nilvel() <> 24) ? data::converteBr(@$aval['dt_fim']) : formErp::input('1[dt_fim]', 'DATA DE TÉRMINO', @$aval['dt_fim'], null, null, 'date') ?>
            </div>
        </div>
        <br /><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> I - SOCIALIZAÇÃO / COMPORTAMENTO ASPECTOS SOCIOAFETIVO </span>
                <?= toolErp::tooltip("Afetividade, autoconceito, relacionamnto social e comportamentos","80vh") ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= (toolErp::id_nilvel() <> 24) ? @$aval['av_socializacao'] : formErp::textarea('1[av_socializacao]', @$aval['av_socializacao']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> II - ASPECTOS DA LINGUAGEM ORAL, GESTUAL, ESCRITA, E COMUNICAÇÃO </span>
                <?= toolErp::tooltip("Linguagem verbal/não verbal, motricidade oral, afabetização, entre outros","70vh") ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= (toolErp::id_nilvel() <> 24) ? @$aval['av_aspectos'] : formErp::textarea('1[av_aspectos]', @$aval['av_aspectos']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> III - PENSAMENTO LÓGICO/COGNIÇÃO </span>
                <?= toolErp::tooltip("Desenvolvimento do pensamento, noção de tempo/espaço, atenção, memória, criatividade, entre outros","120vh") ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= (toolErp::id_nilvel() <> 24) ? @$aval['av_pensamento'] : formErp::textarea('1[av_pensamento]', @$aval['av_pensamento']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> IV - ASPECTOS FÍSICOS / MOTRICIDADE </span>
                <?= toolErp::tooltip("Motricidade ampla, dominância lateral, motricidade fina, postura, entre outros","110vh") ?>
            </div>
        </div>
        <div class="row">
            <div class="col">  
                <?= (toolErp::id_nilvel() <> 24) ? @$aval['av_aspectosF'] : formErp::textarea('1[av_aspectosF]', @$aval['av_aspectosF']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col titulo">
                <span> V - INTERVENÇÕES QUE FORAM REALIZADAS NO DECORRER DA AVALIAÇÃO </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
            <?= (toolErp::id_nilvel() <> 24) ? @$aval['av_inter'] : formErp::textarea('1[av_inter]', @$aval['av_inter']) ?>
            </div>
        </div>
        <br><br>
        
        <div class="row">
            <div class="col titulo">
                <span> OBSERVAÇÕES IMPORTANTES </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= (toolErp::id_nilvel() <> 24) ? @$aval['av_obs'] : formErp::textarea('1[av_obs]', @$aval['av_obs']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col text-center">
                <?=
                formErp::hidden([
                    '1[id_aval]' => $id_aval ,
                    '1[fk_id_turma]' => $id_turma ,
                    '1[fk_id_pessoa_prof]' => $id_pessoa_prof,
                    '1[fk_id_pessoa]' => $id_pessoa_aluno,
                    'id_turma' => $id_turma_AEE,
                ])
                . formErp::hiddenToken('apd_doc_aval', 'ireplace')
                . formErp::button('Salvar');
                ?>            
            </div>
        </div>
    </form>
</div>
