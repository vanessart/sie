<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = toolErp::id_pessoa();
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_STRING);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_STRING);

$disciplinas = $model->letiva($id_curso, $id_pl);
?>


<div class="body">
    <div class="row">
        <div class="col">
            <?php dd($disciplinas) ?>
        </div>
    </div>
</div>