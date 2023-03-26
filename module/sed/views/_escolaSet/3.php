<?php
if (!defined('ABSPATH'))
    exit;
$cursosAt = $esc->cursos();
if ($cursosAt) {
    $cursosAt = array_keys($cursosAt);
} else {
    $cursosAt = [];
}
$cursos = sql::idNome('ge_cursos');
?>
<form method="POST" >
    <div class="row" style="padding-top: 20px">
        <?php
        foreach ($cursos as $id_curso => $n_curso) {
            ?>
            <div class="col">
                <?= formErp::checkbox('c[' . $id_curso . ']', 1, $n_curso, (in_array($id_curso, $cursosAt) ? 1 : 0)) ?>
            </div>
            <?php
        }
        ?>
    </div>
    <br />
    <div style="text-align: center; padding: 20px">
        <?=
        formErp::hidden([
            'id_inst' => $id_inst,
            'activeNav' => 3
        ])
        . formErp::hiddenToken('salvaInstCurso')
        . formErp::button('Salvar')
        ?>
    </div>
</form>
