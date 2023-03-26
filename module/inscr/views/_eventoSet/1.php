<?php
if (!defined('ABSPATH'))
    exit;

if ($evento) {
    foreach ($evento as $k => $v) {
        $evento[$k]['at_evento'] = toolErp::simnao($v['at_evento']);
        $evento[$k]['public'] = toolErp::simnao($v['public']);
        $evento[$k]['recurso'] = toolErp::simnao($v['recurso']);
        $evento[$k]['ac'] = formErp::submit('Acessar', null, ['activeNav' => 3, 'id_evento' => $v['id_evento']]);
        $evento[$k]['edit'] = '<button class="btn btn-info" onclick="edit(' . $v['id_evento'] . ')">Editar</button>';
    }
    $form['array'] = $evento;
    $form['fields'] = [
        'ID' => 'id_evento',
        'Nome' => 'n_evento',
        'Início' => 'dt_inicio',
        'Término' => 'dt_fim',
        'Ativo' => 'at_evento',
        'Publicado' => 'public',
        'Recurso' => 'recurso',
        '||2' => 'edit',
        '||1' => 'ac',
    ];
}
?>
<button class="btn btn-info" onclick="edit()">
    Novo Evento
</button>
<br /><br />
<?php
if (!empty($form)) {
    report::simple($form);
}
?>
<form action="<?= HOME_URI ?>/inscr/def/formEventoSet.php" id="form" target="frame" method="POST">
    <input type="hidden" name="id_evento" id="id_evento" value="" />
</form>
<?php
toolErp::modalInicio(null, null, null, 'Evento');
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            id_evento.value = id;
        } else {
            id_evento.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>