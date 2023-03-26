<?php
if (!defined('ABSPATH'))
    exit;
$bimestre = filter_input(INPUT_POST, 'bimestre', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa_aluno = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$id_pdi = filter_input(INPUT_POST, 'id_pdi', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa_prof = toolErp::id_pessoa();

$descr = sql::get('apd_pdi_descritiva','id_descritiva, fk_id_pdi,descri,trabalho,avaliacao,obs, fk_id_pessoa_prof', ['fk_id_pdi' => $id_pdi, 'atualLetiva' => $bimestre], 'fetch');

if (!empty($descr)) {
    $id_descritiva = $descr['id_descritiva'];
}

?>
<style type="text/css">
    .input-group-text
    {
    display: none;
    }

    .titulo { 
        color: black;
        font-size: 16px;
        padding-bottom: 5px;
    }
    .tituloG { 
        font-weight: bold;
        color: black;
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
        DESCRITIVO
    </div>

    <form id="formEnvia" target="_parent" action="<?= HOME_URI ?>/apd/pdi" method="POST">
    
        <div class="row">
            <div class="col">
                <span class="titulo">DESCRIÇÃO DO TRABALHO REALIZADO COM O ALUNO EM PARCERIA COM OS PROFESSORES DA SALA REGULAR E FAMÍLIA </span>
                <?= toolErp::tooltip('Descrever o trabalho pontuando ações desenvolvidas','40vh') ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[descri]', @$descr['descri']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo">TRABALHO COLABORATIVO COM OS DEMAIS PROFISSIONAIS DA ESCOLA, POSSIBILITANDO UM OLHAR INCLUSIVO DE TODA A EQUIPE ESCOLAR </span>
                <?= toolErp::tooltip('Descrever o trabalho pontuando ações desenvolvidas','30vh') ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[trabalho]', @$descr['trabalho']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo">AVALIAÇÃO PROCESSUAL </span>
                <?= toolErp::tooltip('Elencar e avaliar o desenvolvimento do alunodurante todo o processo','100vh') ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[avaliacao]', @$descr['avaliacao']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo">OBSERVAÇÕES RELEVANTES</span>
                <?= toolErp::tooltip('O que julgar necessário para o pleno desenvolvimento do aluno. Descrever as conquistas, os objetivos alcançados e o que é preciso retomar','130vh') ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[obs]', @$descr['obs']) ?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col text-center">
                <?=
                formErp::hidden([
                    '1[fk_id_pdi]' => $id_pdi ,
                    '1[atualLetiva]' => $bimestre ,
                    '1[id_descritiva]' => @$id_descritiva ,
                    '1[fk_id_pessoa_prof]' => $id_pessoa_prof,
                    'id_pessoa' => $id_pessoa_aluno,
                    'id_turma' => $id_turma,
                    'id_turma_AEE' => $id_turma_AEE,
                    'n_pessoa' => $n_pessoa,
                    'bimestre' => $bimestre,
                    'id_pdi' => $id_pdi ,
                    'activeNav' => 4,

                ])
                . formErp::hiddenToken('apd_pdi_descritiva', 'ireplace')
                . formErp::button('Salvar');
                ?>            
            </div>
        </div>

        
</div>