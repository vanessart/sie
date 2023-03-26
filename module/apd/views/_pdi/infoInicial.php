<?php
if (!defined('ABSPATH'))
    exit;

$id_pessoa_aluno = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_turma_AEE = filter_input(INPUT_POST, 'id_turma_AEE', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$id_pdi = filter_input(INPUT_POST, 'id_pdi', FILTER_SANITIZE_NUMBER_INT);
$activeNav = filter_input(INPUT_POST, 'activeNav', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa_prof = toolErp::id_pessoa();

$pdi = sql::get('apd_pdi','id_pdi,coordenador,duracao_plano,area_priori,aspectos,comunic,asp_cognit,asp_fisico,expressao,conhecimento,novas_tec,autonomia,descr_hab,barreira', ['id_pdi' => $id_pdi], 'fetch');

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
        INFORMAÇÕES INICIAIS
    </div>

    <form id="formEnvia" target="_parent" action="<?= HOME_URI ?>/apd/pdi" method="POST">

        <div class="row">
            <div class="col"> 
                <span class="titulo">COORDENADOR PEDAGÓGICO</span>
                <?= formErp::input('1[coordenador]', null, @$pdi['coordenador'],' required') ?>
            </div>
            <div class="col">
                <span class="titulo">DURAÇÃO DO PLANO</span>
                <?= formErp::input('1[duracao_plano]', null, @$pdi['duracao_plano']) ?>
            </div>
        </div>
        <br /><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> ÁREAS A SEREM PRIORIZADAS </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[area_priori]', @$pdi['area_priori']) ?>
            </div>
        </div>
        <br><br>

        <div class="tituloG">
        DESCRIÇÃO DAS POTENCIALIDADES QUE O ALUNO JÁ POSSUI
        <?= toolErp::tooltip("Elencar neste espaço as potencialidades do aluno(a), quais habilidades o(a) aluno(a) já desenvolveu em relaçao a:","30vh"); ?>
        </div>

        <div class="row">
            <div class="col">
                <span class="titulo"> ASPECTOS SOCIOAFETIVOS / SOCIALIZAÇÃO / COMPORTAMENTOS </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[aspectos]', @$pdi['aspectos']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> COMUNICAÇÃO - LINGUAGEM ORAL/ESCRITA OU GESTUAL </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[comunic]', @$pdi['comunic']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> ASPECTOS COGNITIVOS / RACIOCÍNIO LÓGICO </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[asp_cognit]', @$pdi['asp_cognit']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> ASPECTOS FÍSICOS / MOTRICIDADE </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[asp_fisico]', @$pdi['asp_fisico']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> EXPRESSÃO ARTÍSTICA </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[expressao]', @$pdi['expressao']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> CONHECIMENTO DO MUNDO </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[conhecimento]', @$pdi['conhecimento']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> NOVAS TECNOLOGIAS </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[novas_tec]', @$pdi['novas_tec']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> AUTONOMIA E VIDA DIÁRIA </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[autonomia]', @$pdi['autonomia']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> DESCRIÇÃO DAS HABILIDADES A SEREM DESENVOLVIDAS PELO ALUNO </span>
                <?= toolErp::tooltip("Pontuar as habilidades que julga necessárias conforme avaliação inicial realizada","80vh") ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[descr_hab]', @$pdi['descr_hab']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> BARREIRAS </span>
                <?= toolErp::tooltip("Elencar as barreiras que impedem o desenvolvimento do(a) aluno(a) com deficiência, podendo ser elas urbanísticas, arquitetôtincas, tecnológicas, na comunicação, a falta de informação e barreiras atitudinais ","140vh") ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[barreira]', @$pdi['barreira']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col text-center">
                <?=
                formErp::hidden([
                    '1[id_pdi]' => $id_pdi ,
                    '1[fk_id_turma]' => $id_turma ,
                    '1[fk_id_pessoa_prof]' => $id_pessoa_prof,
                    '1[fk_id_pessoa]' => $id_pessoa_aluno,
                    '1[fk_id_turma_AEE]' => $id_turma_AEE,
                    'activeNav' => $activeNav,
                    'id_pessoa' => $id_pessoa_aluno,
                    'id_turma' => $id_turma,
                    'id_turma_AEE' => $id_turma_AEE,
                    'n_pessoa' => $n_pessoa,
                ])
                . formErp::hiddenToken('apd_pdi', 'ireplace')
                . formErp::button('Salvar');
                ?>            
            </div>
        </div>

        
</div>