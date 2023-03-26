<?php
if (!defined('ABSPATH'))
    exit;
$segs = sql::get('ge_tp_ensino');
foreach ($segs as $k => $v) {
    $segs[$k]['at_seg'] = toolErp::simnao($v['at_seg']);
    $segs[$k]['edit'] = '<input class="btn btn-info" type="submit" onclick="edit(' . $v['id_tp_ens'] . ')" value="Editar" />';
    $segs[$k]['ac'] = formErp::submit('Cursos', null, ['id_tp_ens' => $v['id_tp_ens'], 'activeNav' => 2]);
}
$form['array'] = $segs;
$form['fields'] = [
    'ID' => 'id_tp_ens',
    'Nome' => 'n_tp_ens',
    'Abrev.' => 'sigla',
    'Sequência' => 'sequencia',
    'Ativo' => 'at_seg',
    '||1' => 'edit',
    '||2' => 'ac'
];
?>
<div class="Body">
    <div class="row">
        <div class="col-sm-3" style="padding: 20px">
            <input class="btn btn-info" type="submit" onclick="edit()" value="Novo Segmento" />
        </div>
    </div>
    <?php
    report::simple($form);
    ?>
</div>
<form target="frame" id="form" action="<?= HOME_URI ?>/sed/def/formSegmento.php" method="POST">
    <input type="hidden" name="id_tp_ens" id="id_tp_ens" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; border: none; height: 80vh" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            document.getElementById('id_tp_ens').value = id;
        } else {
            document.getElementById('id_tp_ens').value = '';
        }
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-control').val('');
    }
    ;
</script>