<?php
if (!defined('ABSPATH'))
    exit;
$esc = sql::get('pl_escola_externa', '*', ['>' => 'n_ee']);
if ($esc) {
    foreach ($esc as $k => $v) {
        $esc[$k]['ac'] = '<button class="btn btn-info" onclick="esc(' . $v['id_ee'] . ')">Editar</button>';
    }
    $form['array'] = $esc;
    $form['fields'] = [
        'Nome' => 'n_ee',
        'CIE' => 'cie',
        'E-mail' => 'email',
        '||1' => 'ac'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Escolas Externas
    </div>
    <button class="btn btn-info" onclick="esc()">
        Nova Escola
    </button>
    <br /><br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/passelivre/def/formEscola.php" id="form" target="frame" method="POST">
    <input type="hidden" name="id_ee" id="id_ee" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function esc(id) {
        if (id) {
            document.getElementById('id_ee').value = id;
        } else {
            document.getElementById('id_ee').value = '';
        }
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>