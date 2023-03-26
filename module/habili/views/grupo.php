<?php
if (!defined('ABSPATH'))
    exit;
$id_gh = filter_input(INPUT_POST, 'id_gh', FILTER_SANITIZE_NUMBER_INT);
$modal = filter_input(INPUT_POST, 'modal', FILTER_SANITIZE_NUMBER_INT);
?>
<div class="body">
    <div class="fieldTop">
        Grupos de Habilidades
    </div>
    <br /><br />
    <?= formErp::button('Novo Grupo', null, "habil()") ?>
    <br /><br />
    <?php
    $grup = coordena::grupHab();
    $form['array'] = $grup;
    $form['fields'] = [
        'ID' => 'id_gh',
        'Grupo' => 'n_gh',
        'Segmento' => 'n_seg',
        'Ativo?' => 'at_gh',
    ];

    report::forms($form, 'coord_grup_hab', ['modal' => 1])
    ?>
</div>
<form target="frame" id="formFrame" action="<?= HOME_URI ?>/habili/def/formHabGrupo.php" method="POST">
    <input id="id_gh" type="hidden" name="id_gh" value="<?= @$id_gh ?>" />
</form>
<?php
toolErp::modalInicio($modal);
?>
<iframe name="frame" style="width: 100%; border: none; height: 80vh"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
<?php
if (!empty($modal)) {
    ?>
        $(document).ready(function () {
            $('#formFrame').submit();
        });
    <?php
}
?>
    function habil() {
        jQuery('#id_gh').val('');
        jQuery('#myModal').modal('show');
        jQuery('.form-class').val('');
        jQuery('#formFrame').submit();
    }
</script>
