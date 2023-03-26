<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa_apd = filter_input(INPUT_POST, 'id_pessoa_apd', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
if (toolErp::id_nilvel() == 24) {
    $action = HOME_URI."/apd/docRel";
    $activeNav = 3;
} else {
     $action = HOME_URI."/apd/apd";
     $activeNav = 2;
}?>
<div class="body">
    <div class="fieldTop">
        Laudo do Aluno - AEE
    </div>
    <form action="<?= $action ?>" target="_parent" method="POST" enctype="multipart/form-data" name="formapdup" id="formapdup">
        <div class="row">
            <div class="col">
                <?= formErp::input('tipo', 'Tipo de Documento') ?>
            </div>

        </div>
        <br />
        <div class="row">
            <div class="col">
                <input class="btn btn-primary" required="required" type="file" name="arquivo" value="" id='selecao-arquivo' />
            </div>
            <div class="col">
                <button class="btn btn-success">
                    Enviar Arquivo
                </button>
            </div>
        </div>
        <br />
        <?=
        formErp::hidden([
            'id_pessoa_apd' => $id_pessoa_apd,
            'id_inst' => $id_inst,
            'activeNav' => $activeNav,
            'id_pessoa' => $id_pessoa,
            'n_turma' => $n_turma,
            'id_turma' => $id_turma,
        ])
        . formErp::hiddenToken('uploadDoc');
        ?>
    </form>
</div>
