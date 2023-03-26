<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_STRING);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$n_disc = filter_input(INPUT_POST, 'n_disc', FILTER_SANITIZE_STRING);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$escola = filter_input(INPUT_POST, 'escola', FILTER_SANITIZE_STRING);
$atual_letiva = filter_input(INPUT_POST, 'atual_letiva', FILTER_SANITIZE_STRING);

$hidden = [
    'id_ciclo' => $id_ciclo,
    'id_inst' => $id_inst,
    'id_pl' => $id_pl,
    'id_turma' => $id_turma,
    'id_curso' => $id_curso,
    'n_turma' => $n_turma,
    'n_disc' => $n_disc,
    'id_disc' => $id_disc
];
foreach ($hidden as $v) {
    if (is_null($v)) {
        if (toolErp::id_nilvel() == 24) {
            ?>
            <meta http-equiv="refresh" content="0; URL='<?= HOME_URI ?>/profe/lancNota'"/>
            <?php
        } elseif (toolErp::id_nilvel() == 48) {
            ?>
            <meta http-equiv="refresh" content="0; URL='<?= HOME_URI ?>/profe/lancNotaCoord'"/>
            <?php
        } else {
            ?>
            <div class="alert alert-danger" role="alert">
                Não sei o que você está fazendo aqui. 
                <br />
                Na dúvida, procure a Katia ou o Mario.
                <br />
                O Marco não, ele muito chato.
            </div>
            <?php
        }
        exit();
    }
}

$alunos = ng_escola::alunoPorTurma($id_turma);
$idsPessoa = array_column($alunos, 'id_pessoa');
$letiva = $model->letivaDados($id_turma);
if (empty($atual_letiva)) {
    $atual_letiva = $letiva['atual_letiva'];
}
$set = $model->notaSet($id_curso, $atual_letiva);
if (toolErp::id_nilvel() == 24) {
    $libera = $set['prof'];
} elseif (toolErp::id_nilvel() == 55) {
    $libera = $set['coord'];
} else {
    $libera = null;
}

$notaFalta = $model->notaFaltaBim($id_curso, $id_pl, $idsPessoa, $atual_letiva);

$ch = $model->chamadaPorUnidadeLetiva($id_pl, $id_turma, $id_disc, $atual_letiva);

$instrumentos = $model->retornaInstrumentosAvaliativos($id_pl, $id_turma, $atual_letiva, $id_disc);

foreach ($instrumentos as $key => $value) {
    foreach ($alunos as $k => $v) {
        if (isset($value->notas)) {
            $notasArr = (array) $value->notas;
            @$alunos[$k]['notasAluno'][$value->uniqid] = $notasArr[$v['id_pessoa']];
        }
    }
}
?>
<div class="body">
    <div class="row">
        <div class="col-3">
            <?= formErp::selectNum('atual_letiva', [1, $letiva['atual_letiva']], $letiva['un_letiva'], $atual_letiva, 1, $hidden) ?>
        </div>
        <div class="col-8"style="font-weight: bold; font-size: 2.2em; text-align: center;">
            Lançamento de Notas <?= $atual_letiva ?> º <?= $letiva['un_letiva'] ?> de <?= date('Y') ?>
        </div>
        <div class="col-1">
            <?php
            if (toolErp::id_nilvel() == 24) {
                $coord = null;
            } elseif (toolErp::id_nilvel() == 48) {
                $coord = 'coord';
            }
            ?>
            <form action="<?= HOME_URI ?>/profe/lancNota<?= $coord ?>" method="POST">
                <?=
                formErp::hidden([
                    'id_turma' => $id_turma
                ])
                ?>
                <button class="btn btn-primary">
                    Voltar
                </button>
            </form>
        </div>
    </div>
    <div style="font-weight: bold; font-size: 2.0em; text-align: center; padding: 20px">
        <?= $n_turma ?> - <?= $n_disc ?>
    </div>
    <?php
    if (!$libera) {
        ?>
        <div class="alert alert-danger">
            Sistema Fechado para Lançamento
        </div>
        <?php
    }
    if (count($instrumentos) < 1) {
        ?>
        <div class="alert alert-danger" role="alert">
            Não há instrumentos avaliativos registrado neste <?= $letiva['un_letiva'] ?>
        </div>
        <?php
    } elseif (count($instrumentos) < 3) {
        ?>
        <div class="alert alert-warning" style="font-weight: bold; text-align: center">
            Lance, no mínimo, três avaliações
        </div>
        <?php
    }
    ?>
    <div class="alert alert-primary" style="font-weight: bold; text-align: center">
        ATENÇÃO: Senhor(a) Professor(a), as médias transportadas, caso necessário, poderão ser ajustadas manualmente diretamente nos campos abaixo.
    </div> 
    <?php
    if ($id_disc == 'nc') {
        include ABSPATH . '/module/habili/views/_lancNotaSet/nc.php';
    } else {
        include ABSPATH . '/module/habili/views/_lancNotaSet/peb2.php';
    }
    ?> 
</div>

<script>
    function transp() {
        if (diasQt.value) {
            if (confirm("Esta ação irá subscrever o campo notas com o campo média. Prosseguir?")) {
                transpInstr.value = '1';
                form.submit();
            }
        } else {
            alert('Preencha o Total de Aulas');
        }
    }
</script>