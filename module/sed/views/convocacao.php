<?php
$ev = sql::get('ge_eventos', '*', ['fk_id_inst' => tool::id_inst(), '<' => 'dt_evento']);
$token = formErp::token('ge_eventos', 'delete');
foreach ($ev as $key => $v) {
    $ev[$key]['excluir'] = formErp::submit('Excluir', $token, ['1[id_evento]' => $v['id_evento']]);
    $ev[$key]['even'] = '<button class="btn btn-info" onclick="aces(' . $v['id_evento'] . ')">Editar</button>';
    $ev[$key]['grupo'] = formErp::submit('Selecionar', null, $v, HOME_URI . '/sed/eventos');
}
$form['array'] = $ev;
$form['fields'] = [
    'Assunto' => 'evento',
    'Data' => 'dt_evento',
    'Local' => 'local_evento',
    '||1' => 'excluir',
    '||2' => 'even',
    '||4' => 'grupo'
];
?>
<div class="body">
    <div class="fieldTop">
        Eventos e Convocações
    </div>
    <div class="row">
        <div class="col">
            <button class="btn btn-success" onclick="aces()">
                Cadastrar nova Convocação/Evento
            </button>
        </div>
    </div>
    <br />
    <form target="frame" id="form" action="<?= HOME_URI ?>/sed/def/formDeclaracao.php" method="POST">
        <input type="hidden" name="id_evento" id="id_evento" value="" />
    </form>
    <?php
    if (!empty($form)) {
        tool::relatSimples($form);
    }
    toolErp::modalInicio();
    ?>
    <iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
        <?php
        toolErp::modalFim();
        ?>
</div>

<script>
    function aces(id) {
        if (id) {
            id_evento.value = id;
        } else {
            id_evento.value = id;
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>