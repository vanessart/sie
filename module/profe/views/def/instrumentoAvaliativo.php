<?php
if (!defined('ABSPATH'))
    exit;
$origem = filter_input(INPUT_POST, 'origem');
if (empty($origem)) {
    $origem = "/profe/chamada";
}
$tiposAvaliacao = $model->tiposAvaliacao();
$model->setPesoAvaliacao(1);
$hidden = [
    'id_inst' => filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT),
    "id_pl" => filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT),
    "id_ciclo" => filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT),
    "id_pessoa" => filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT),
    "id_turma" => filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT),
    "id_inst" => filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT),
    "id_curso" => filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT),
    "peso" => $model->getPesoAvaliacao(),
    "atual_letiva" => filter_input(INPUT_POST, 'atual_letiva', FILTER_SANITIZE_NUMBER_INT),
    "timeStamp" => date('Y-m-d H:i:s'),
    'hlo' => 3,
    "nome_disc" => filter_input(INPUT_POST, 'nome_disc', FILTER_SANITIZE_NUMBER_INT),
    "id_disc" => filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_NUMBER_INT),
    "nome_turma" => filter_input(INPUT_POST, 'nome_turma', FILTER_SANITIZE_NUMBER_INT),
    "escola" => filter_input(INPUT_POST, 'escola'),
    'nome_turma' => filter_input(INPUT_POST, 'nome_turma'),
    'nome_disc' => filter_input(INPUT_POST, 'nome_disc'),
    'n_turma' => filter_input(INPUT_POST, 'nome_turma'),
    'n_disc' => filter_input(INPUT_POST, 'nome_disc'),
    'id_disc' => filter_input(INPUT_POST, 'id_disc'),
    'data' => filter_input(INPUT_POST, 'data')
];

$dataUnidadeLetiva = $model->dataUnidadeLetiva($hidden['id_curso'], $hidden['id_pl'], $hidden['atual_letiva']);
if (!empty($_POST['uniqid'])) {
    $uniqid = $_POST['uniqid'];
    $mongo = new mongoCrude('Diario');
    $arr = $mongo->query('instrumentos.' . $hidden['id_pl'], ["uniqid" => $uniqid])[0];
    $hidden['uniqid'] = $uniqid;
} else {
    $hidden['uniqid'] = uniqid();
}
?>

<div class="body">
    <form method="post" action="<?= HOME_URI . $origem ?>" target="_parent">
        <div class="row">
            <div class="col">
                <?= formErp::input('instrumentoNome', 'Nome', @$arr->instrumentoNome, "required") ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-6">
                <?=
                formErp::select('instrumentoTipo', toolErp::idName($tiposAvaliacao), 'Tipo de Avaliação', @$arr->instrumentoTipo)
                . formErp::hidden($hidden)
                . formErp::hiddenToken('cadastraInstrumentoAvaliativo')
                ?>
            </div>
            <div class="col-6">
                <?php
                if ($hidden['id_disc'] == 'nc') {
                    $disc_ = ng_main::disciplinas($hidden['id_turma']);
                    foreach ($disc_ as $v) {
                        if ($v['nucleo_comum'] == 1) {
                            $disc[$v['id_disc']] = $v['n_disc'];
                        }
                    }
                    if (!empty($disc)) {
                        echo formErp::select('id_disc_nc', $disc, 'Disciplina', @$arr->id_disc_nc);
                    }
                    ?>
                    <?php
                }
                ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-6 ">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Data</div>
                    </div>
                    <input type="date" name="dataAvaliacao" id="" class="form-control" required min="<?= $dataUnidadeLetiva['dt_inicio'] ?>" max="<?= $dataUnidadeLetiva['dt_fim'] ?>" value="<?= @$arr->dataAvaliacao ?>">
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col" style="margin-top:10px">
                    <?= formErp::select('ativo', ['Não', 'Sim'], 'Ativo', empty($arr) ? 1 : $arr->ativo) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col text-center" style="margin-top:10px; ">
                    <?= formErp::button('Enviar') ?>
                </div>
            </div>
    </form>
</div>
