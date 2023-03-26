<?php
if (empty($_POST['pesq'])) {
    $id_inst = tool::id_inst();
} else {
    $id_inst = $_POST['id_inst'];
}
$status = 'Deferim';
?>

<div class="fieldBody">
    <div class="fieldTop">
        Inscrições aguardando deferimento
    </div>
    <br /><br />
    <?php
    $model->pesq(@$id_inst, @$status);
    