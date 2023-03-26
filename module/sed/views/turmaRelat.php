
<style>
    .grd button{
        width: 100%;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
$tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_NUMBER_INT);
if (!$tipo) {
    $tipo = 'escola';
}
$id_inst = tool::id_inst();
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_pl = ng_main::periodoSet($id_pl);
$periodos = ng_main::periodosPorSituacao();
$turmas = gtTurmas::idNome($id_inst, $id_pl);
$id_turma = @$_POST['id_turma'];
if (!$id_turma) {
    $id_turma = [];
} else {
    foreach ($turmas as $k => $v) {
        if (in_array($k, $id_turma)) {
            $turmalist[$k] = $v;
        }
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Relatórios - Turmas
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_pl', $periodos, 'Período Letivo', $id_pl, 1) ?>
        </div>
        <div class="col">
            <button class="btn btn-info" onclick=" $('#myModal').modal('show');$('.form-class').val('')" >
                Clique Aqui para Selecionar as Turmas
            </button>
        </div>
    </div>
    <br />
    <?php
    toolErp::modalInicio();
    ?>
    <div class="fieldTop">
        Selecione as Turmas que deseja consultar
    </div>
    <div class="row">
        <div class="col-6" >
        </div>
        <div class="col" >
            <?= formErp::checkbox('id_turma[]', 1, 'Selecionar Todos', null, ' onclick="$(\'input:checkbox\').prop(\'checked\', this.checked);"') ?>
        </div>
    </div>
    <br />
    <form method="POST">
        <?php
        $in = null;
        foreach ($turmas as $id => $v) {
            if ($in != substr($v, 0, 1)) {
                if (empty($pri)) {
                    $pri = 1;
                    ?>
                    <div class="row border">
                        <?php
                    } else {
                        ?>
                    </div>
                    <br /><br />
                    <div class="row border">
                        <?php
                    }
                }
                $in = substr($v, 0, 1);
                ?>
                <div class="col">
                    <?= formErp::checkbox('id_turma[' . $id . ']', $id, $v, in_array($v, $id_turma) ? $v : null) ?>
                </div>
                <?php
            }
            ?>
            <div style="text-align: center; padding: 30px">
                <button class="btn btn-success" type="submit">
                    Continuar
                </button>
            </div>
    </form>
    <?php
    toolErp::modalFim();
    ?>

</div>

<?php
if (!empty($turmalist)) {
    ?>
    <div class="alert alert-success" style="font-weight: bold; font-size: 1.5em">
        <p>
            Turmas selecionadas:
        </p>
        <p>
            <?= toolErp::virgulaE($turmalist) ?>
        </p>
    </div>
    <?php
}
if ($id_turma) {
    include ABSPATH . "/module/sed/views/_turmaRelat/turma.php";
}
?>
</div>
