<?php
if (!defined('ABSPATH'))
    exit;
$id_turma = filter_input(INPUT_POST, 'fk_id_turma', FILTER_SANITIZE_NUMBER_INT);
if (!$id_turma) {
    ?>
<meta http-equiv="refresh" content="0; URL='<?= HOME_URI ?>/profe/projetoProf'"/>
    <script>
        window.location.href "<?= HOME_URI ?>/profe/projetoProf"
    </script>
    <?php
    die();
}
$id_ciclo = filter_input(INPUT_POST, 'fk_id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'fk_id_disc', FILTER_SANITIZE_NUMBER_INT);
$msg_coord = filter_input(INPUT_POST, 'msg_coord', FILTER_SANITIZE_STRING);
$id_projeto = filter_input(INPUT_POST, 'fk_id_projeto', FILTER_SANITIZE_NUMBER_INT);
$n_projeto = filter_input(INPUT_POST, 'n_projeto', FILTER_SANITIZE_STRING);
$activeNav = filter_input(INPUT_POST, 'activeNav', FILTER_SANITIZE_NUMBER_INT);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$n_status_log = filter_input(INPUT_POST, 'n_status', FILTER_SANITIZE_STRING);
$id_pessoa = toolErp::id_pessoa();
$alunosAEE = $model->ListAlunosAEE($id_turma);
if ($n_turma) {
    $_SESSION['TMP']['n_turma'] = $n_turma;
} else {
    $n_turma = $_SESSION['TMP']['n_turma'];
}

if (!empty($n_status_log)) {
    log::logSet($n_status_log);
}

$hidden = [
    'fk_id_ciclo' => $id_ciclo,
    'fk_id_disc' => $id_disc,
    'fk_id_turma' => $id_turma,
    'n_turma' => $n_turma,
    'id_inst' => $id_inst
];

if (empty($id_projeto)) {
    $id_projeto = filter_input(INPUT_POST, 'id_projeto', FILTER_SANITIZE_NUMBER_INT);
}

if ($id_projeto) {
    $ativo = 1;
} else {
    $ativo = null;
}

if ($activeNav == 1) {
    $ativo = null;
}
?>
<div class="body">
    <?php if (empty($activeNav) || $activeNav == 1) { ?>
        <div class="row">
            <div class="col" style="text-align:right;">
                <form action="<?= HOME_URI ?>/profe/projetoProf" method="POST">
                    <button class="btn btn-warning" style="margin: 0">
                        Voltar
                    </button>
                </form>
            </div>
        </div>
    <?php }
    ?>
    <div class="fieldTop">
        <?= !empty($n_projeto) ? 'Projeto: ' . $n_projeto : 'Projetos - ' . $n_turma ?>
    </div>
    <?php
    $abas[1] = ['nome' => "Todos os Projetos", 'ativo' => 1, 'hidden' => $hidden];
    $abas[4] = ['nome' => "Avaliação Processual Diária", 'ativo' => $ativo, 'hidden' => $hidden + ['fk_id_projeto' => @$id_projeto, 'n_projeto' => @$n_projeto, 'msg_coord' => @$msg_coord]];
    $abas[2] = ['nome' => "Registro Quinzenal", 'ativo' => $ativo, 'hidden' => $hidden + ['fk_id_projeto' => @$id_projeto, 'n_projeto' => @$n_projeto, 'msg_coord' => @$msg_coord]];
    $abas[3] = ['nome' => "Registro de Imagens", 'ativo' => $ativo, 'hidden' => $hidden + ['fk_id_projeto' => @$id_projeto, 'n_projeto' => @$n_projeto, 'msg_coord' => @$msg_coord]];
    if (!empty($alunosAEE)) {
        $abas[5] = ['nome' => "Flexibilização Curricular", 'ativo' => $ativo, 'hidden' => $hidden + ['fk_id_projeto' => @$id_projeto, 'n_projeto' => @$n_projeto, 'msg_coord' => @$msg_coord]];
    }
    $aba = report::abas($abas);
    include ABSPATH . "/module/profe/views/_projeto/$aba.php"
    ?>
</div>

