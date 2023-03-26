<?php
if (!defined('ABSPATH'))
    exit;
$todos = filter_input(INPUT_POST, 'todos', FILTER_SANITIZE_NUMBER_INT);
$id_ce = filter_input(INPUT_POST, 'id_ce', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$serial = filter_input(INPUT_POST, 'serial', FILTER_SANITIZE_STRING);
$resp = trim(filter_input(INPUT_POST, 'resp', FILTER_SANITIZE_STRING));
$cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_STRING);
$rm = filter_input(INPUT_POST, 'rm', FILTER_SANITIZE_STRING);
$id_ch = filter_input(INPUT_POST, 'id_ch', FILTER_SANITIZE_NUMBER_INT);
$id = $model->emprestaSalvaRede();
if ($id) {
    $id_ce = $id;
}
$chromes = sql::get('lab_chrome', 'id_ch, serial as n_seria');
//$chromes = sql::get(['lab_chrome_emprestimo', 'lab_chrome'], 'id_ch, serial as n_seria');
$chromes = toolErp::idName($chromes);


$par = [
    'serial' => $serial,
    'resp' => $resp,
    'cpf' => $cpf,
    'rm' => $rm,
    'id_ch' => $id_ch,
];
if (!empty($id_ce)) {
    $empretimo = $model->emppretimo($id_ce, 1);
    @$aba2 = $empretimo['n_pessoa'] . ' - ' . $empretimo['serial'];
    @$aba1 = "Reiniciar Pesquisa";
    $aba2At = 1;
} elseif (!empty($id_pessoa)) {
    $empretimo = $model->servidor($id_pessoa);
    @$aba2 = $empretimo['n_pessoa'];
    @$aba1 = "Reiniciar Pesquisa";
    $aba2At = 1;
} else {
    @$aba1 = "Equipamentos Emprestados";
    $aba2 = '&nbsp;';
    $aba2At = null;
}
//$model->chromeRedeEmpresta($par);
?>
<div class="body">
    <div class="fieldTop">
        Emprestimo de Chromebook - Rede
    </div>
    <br />
    <?php
    if (!$id_pessoa && !$id_ch) {
        ?>
        <form method="POST">
            <div class="fieldTop">
                Utilize apenas um filtro por vez (Responsável, CPF, Matrícula ou nº de Série)
            </div>
            <div class="row">
                <div class="col-4">
                    <?= formErp::select('id_ch', $chromes, 'Chromebook', $id_ch) ?>
                </div>
                <div class="col-4">
                    <?= formErp::input('rm', 'Matrícula', $rm) ?>
                </div>
                <div>
                    <?= formErp::select('todos', [1 => 'Todos'], ['Empréstimo', 'Ativo'], $todos) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-4">
                    <?= formErp::input('resp', 'Responsável', $resp) ?>
                </div>
                <div class="col-4">
                    <?= formErp::input('cpf', 'CPF (só número)', $cpf) ?>
                </div>
                <div class="col-2 text-center">
                    <button class="btn btn-info" type="submit">
                        Filtrar
                    </button>
                </div>
            </div>
            <br />
        </form>
        <div class="alert alert-warning">
            Para novos emprétimos ou consulta de "Nada Consta" pesquise pelo CPF.
        </div>
        <form id="limpar">
        </form>
        <br /><br />
        <?php
    }
    $abas[1] = ['nome' => @$aba1, 'ativo' => 1, 'hidden' => []];
    $abas[2] = ['nome' => $aba2, 'ativo' => $aba2At, 'hidden' => ($par + ['id_pessoa' => @$empretimo['id_pessoa'], 'id_ce'=>@$empretimo['id_ce']])];
    $aba = report::abas($abas);
    include ABSPATH . "/module/lab/views/_emprestRede/$aba.php";
    ?>
</div>
