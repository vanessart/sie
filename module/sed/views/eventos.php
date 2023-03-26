<?php
$id_evento = filter_input(INPUT_POST, 'id_evento', FILTER_SANITIZE_NUMBER_INT);
$id_inst = toolErp::id_inst();
$pls = ng_main::periodosAtivos();
if (!empty($_POST['selecao_4'])) {
    foreach ($_POST['as'] as $v) {
        $cad['id_ea'] = $v;
        $cad['fk_id_grupo_e'] = $_POST['id_grupo'];
        $model->db->ireplace('ge_evento_aluno', $cad, 1);
    }
}

if (!empty($_POST['selecao2_4'])) {
    foreach ($_POST['as2'] as $v) {
        $cad['id_ea'] = $v;
        $cad['fk_id_grupo_e'] = NULL;
        $model->db->ireplace('ge_evento_aluno', $cad, 1);
    }
}
if ($id_evento) {
    $_SESSION['tmp']['id_evento'] = $id_evento;
} else {
    $id_evento = $_SESSION['tmp']['id_evento'];
}

if (!empty($_POST['selecao'])) {
    foreach ($_POST['as'] as $v) {
        $cad['fk_id_evento'] = $id_evento;
        $cad['fk_id_pessoa'] = $v;
        $model->db->ireplace('ge_evento_aluno', $cad, 1);
    }
}

if (!empty($_POST['selecao2'])) {
    foreach ($_POST['as2'] as $vv) {
        $model->db->delete('ge_evento_aluno', 'id_ea', $vv, 1);
    }
}
//menu
@$alunoevento = sqlErp::get('ge_evento_aluno', 'fk_id_evento', ['fk_id_evento' => $id_evento], 'fetch')['fk_id_evento'];
@$grupoe = sqlErp::get('ge_grupo_evento', 'fk_id_evento', ['fk_id_evento' => $id_evento], 'fetch')['fk_id_evento'];

$habilitado[0] = 1;
$habilitado[3] = 1;
$habilitado[5] = 1;
if (!empty($alunoevento) && !empty($grupoe)) {
    $habilitado[4] = 1;
} else {
    $habilitado[4] = NULL;
}

if (empty($alunoevento)) {
    $habilitado[1] = NULL;
    $habilitado[2] = NULL;
} else {
    $habilitado[1] = 1;
    $habilitado[2] = 1;
}

//fim menu
@$descEve = filter_input(INPUT_POST, 'evento');

if (empty($_REQUEST['tabClass'])) {
    $tabClass = '0';
} else {
    $tabClass = $_REQUEST['tabClass'];
}
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
?>
<div class="body">
    <div class="fieldTop">
        <p>
            Convocações, Autorizações e Eventos 
        </p>
        <p>
            <?= "Evento: " . @$descEve ?>
        </p>
    </div>
    <br />
    <div class="row">
        <?php
        $titulo = ['Selecionar os Alunos', 'Imprimir Convocação', 'Imprimir Autorização', 'Criar Grupos', 'Alocar Alunos nos Grupos', 'Selecionar Outro Evento'];
        $onclik = [1 => "document.getElementById('conv').submit()", 2 => "document.getElementById('aut').submit()"];
        foreach ([0, 1, 2, 5]as $c) {
            if (empty($habilitado[$c])) {
                ?>
                <div class="col-3">
                    <button type="button" class="btn btn-outline-dark" style="width: 100%; font-weight: bold;">
                        <?= $titulo[$c] ?>
                    </button>
                </div>
                <?php
            } elseif ($c == 5) {
                ?>
                <div class="col-3">
                    <form action="<?= HOME_URI ?>/sed/convocacao" method="POST">
                        <button class="btn btn-warning" style="width: 100%; font-weight: bold;">
                            <?= $titulo[$c] ?>
                        </button>
                    </form>
                </div>
                <?php
            } elseif (empty($onclik[$c])) {
                ?>
                <div class="col-3">
                    <form method="POST">
                        <input type="hidden" name="id_evento" value="<?= $id_evento ?>" />
                        <input type="hidden" name="tabClass" value="<?= $c ?>" />
                        <input type="hidden" name ="evento" value="<?= @$descEve ?>" />  
                        <button class="btn btn-<?= $tabClass == $c ? 'success' : 'info' ?>" style="width: 100%; font-weight: bold;">
                            <?= $titulo[$c] ?>
                        </button>
                    </form>
                </div>
                <?php
            } else {
                ?>
                <div class="col-3">
                    <button type="button" onclick="<?= @$onclik[$c] ?>" class="btn btn-<?= $tabClass == $c ? 'success' : 'info' ?>" style="width: 100%; font-weight: bold;">
                        <?= $titulo[$c] ?>
                    </button>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <?php
    switch ($tabClass) {
        case 1:
        case 2:
            break;
        case 5:
            break;
        default :
            include ABSPATH . '/module/sed/views/_eventos/' . $tabClass . '.php';
    }
    ?>

    <form target="_blank" action="<?= HOME_URI ?>/sed/pdf/convocacaopdf.php" name="conv" id="conv" method="POST">    
        <input type="hidden" name="id_evento" value="<?= @$_POST['id_evento'] ?>" />    
        <input type="hidden" name="tabClass" value="<?= "1" ?>" />
    </form>

    <form target="_blank" action="<?= HOME_URI ?>/sed/pdf/autorizacaopdf.php" name="aut" id="aut" method="POST">
        <input type="hidden" name="id_evento" value="<?= @$_POST['id_evento'] ?>" /> 
        <input type="hidden" name="tabClass" value="<?= "2" ?>" />
    </form>

</div>

