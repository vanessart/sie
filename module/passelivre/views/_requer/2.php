<?php
if (!defined('ABSPATH'))
    exit;
$docs = sql::get(['pl_doc', 'pl_doc_tipo'], '*', ['fk_id_passelivre' => $id_passelivre]);
if ($docs) {
    $token = formErp::token('pl_doc', 'delete');
    foreach ($docs as $k => $v) {
        $docs[$k]['ac'] = formErp::submit('Baixar', null, null, HOME_URI . '/pub/passelivre/' . $v['end'], 1);
        $docs[$k]['dt'] = explode(' ', $v['dt_doc'])[0];
        $docs[$k]['h'] = substr(explode(' ', $v['dt_doc'])[1], 0, 5);
        $docs[$k]['del'] = formErp::submit('Excluir', $token, ['1[id_doc]' => $v['id_doc'], 'activeNav' => 2, 'cie' => $cie, 'id_passelivre' => $id_passelivre]);
    }
    $form['array'] = $docs;
    $form['fields'] = [
        'Documento' => 'n_dt',
        'Data' => 'dt',
        'Hora' => 'h',
        '||2' => 'del',
        '||1' => 'ac'
    ];
}
?>
<button class="btn btn-info" onclick="up()">
    Novo Uploads
</button>
<br /><br />
<?php
if (!empty($form)) {
    report::simple($form);
}
?>
<form action="<?= HOME_URI ?>/passelivre/def/formUp.php" target="frame" id="form" method="POST">
    <?=
    formErp::hidden([
        'cie' => $cie,
        'id_passelivre' => $id_passelivre
    ])
    ?>
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="frame" style="width: 100%; height: 40vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function up() {
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>


<!-- activeNav -->