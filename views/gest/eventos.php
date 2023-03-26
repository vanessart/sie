<?php
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
if (!empty($_POST['id_eve'])) {
    $id_eve = $_POST['id_eve'];
    $_SESSION['tmp']['id_eve'] = $id_eve;
} else {
    $id_eve = $_SESSION['tmp']['id_eve'];
}

if (!empty($_POST['selecao'])) {
    foreach ($_POST['as'] as $v) {
        $cad['fk_id_evento'] = $id_eve;
        $cad['fk_id_pessoa'] = $v;
        $model->db->ireplace('ge_evento_aluno', $cad, 1);
    }
}

if (!empty($_POST['selecao2'])) {
    foreach ($_POST['as2'] as $vv) {
        $model->db->delete('ge_evento_aluno', 'id_ea', $vv);
    }
}
//menu
@$alunoevento = sql::get('ge_evento_aluno', 'fk_id_evento', ['fk_id_evento' => $id_eve], 'fetch')['fk_id_evento'];
@$grupoe = sql::get('ge_grupo_evento', 'fk_id_evento', ['fk_id_evento' => $id_eve], 'fetch')['fk_id_evento'];

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
@$descEve = $_POST['evento'];

if (empty($_REQUEST['tabClass'])) {
    $tabClass = '0';
} else {
    $tabClass = $_REQUEST['tabClass'];
}
$id_turma = @$_POST['id_turma'];
?>
<div class="fieldBody">
    <div class="fieldTop">
        Convocações, Autorizações e Eventos 
        
    </div>
    <div style="color: red" class="fieldTop">
        <br />
        <?php echo "EVENTO SELECIONADO: " . @$descEve ?>
    </div>
    <br />
    <div class="row">
        <?php
        $titulo = ['Selecionar os Alunos', 'Imprimir Convocação', 'Imprimir Autorização', 'Criar Grupos', 'Alocar Alunos', 'Selecionar Evento'];
        $onclik = [1 => "document.getElementById('conv').submit()", 2 => "document.getElementById('aut').submit()"];
        for ($c = 0; $c < 6; $c++) {
            if (empty($habilitado[$c])) {
                ?>
                <div class="col-md-2">
                    <button type="button" class="btn btn-default" style="width: 100%; font-weight: bold;">
                        <?php echo $titulo[$c] ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span style="" class="badge"><?php echo $c + 1 ?></span>
                    </button>
                </div>
                <?php
            } elseif (empty($onclik[$c])) {
                ?>
                <div class="col-md-2">
                    <form method="POST">
                        <input type="hidden" name="id_eve" value="<?php echo $id_eve ?>" />
                        <input type="hidden" name="tabClass" value="<?php echo $c ?>" />
                        <input type="hidden" name ="evento" value="<?php echo @$descEve ?>" />  
                        <button class="btn btn-<?php echo $tabClass == $c ? 'success' : 'info' ?>" style="width: 100%; font-weight: bold;">
                            <?php echo $titulo[$c] ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span style="" class="badge"><?php echo $c + 1 ?></span>
                        </button>
                    </form>
                </div>
                <?php
            } else {
                ?>
                <div class="col-md-2">
                    <button type="button" onclick="<?php echo @$onclik[$c] ?>" class="btn btn-<?php echo $tabClass == $c ? 'success' : 'info' ?>" style="width: 100%; font-weight: bold;">
                        <?php echo $titulo[$c] ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span style="" class="badge"><?php echo $c + 1 ?></span>
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
            require ABSPATH . '/views/gest/convocacao.php';
            break;
        default :
            include ABSPATH . '/views/gest/eventos' . $tabClass . '.php';
    }
    ?>

    <form target="_blank" action="<?php echo HOME_URI ?>/gestao/convocacaopdf" name="conv" id="conv" method="POST">    
        <input type="hidden" name="id_eve" value="<?php echo @$_POST['id_eve'] ?>" />    
        <input type="hidden" name="tabClass" value="<?php echo "1" ?>" />
    </form>

    <form target="_blank" action="<?php echo HOME_URI ?>/gestao/autorizacaopdf" name="aut" id="aut" method="POST">
        <input type="hidden" name="id_eve" value="<?php echo @$_POST['id_eve'] ?>" /> 
        <input type="hidden" name="tabClass" value="<?php echo "2" ?>" />
    </form>

</div>

