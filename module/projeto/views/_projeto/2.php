<?php
if (!defined('ABSPATH'))
    exit;
?>
<button class="btn btn-info" onclick="edit()">
    Nova tarefa
</button>



<form target="frame" id="form" action="<?= HOME_URI ?>/projeto/def/formTarefa.php" method="POST">
    <?=
    formErp::hidden([
        'id_proj' => $id_proj,
    ])
    ?>
    <input type="hidden" name="id_tar" id="id_tar" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame" ></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            id_tar.value = id;
        } else {
            id_tar.value = "";
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>