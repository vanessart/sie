<?php
################################## esconde #####################################
if (!in_array(tool::id_pessoa(), [1, 6])) {
    echo 'Em Desenvolvimento';
    exit();
}
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
    <form target="frame" id="form" action="<?= HOME_URI ?>/sed/def/formeDeclaracao.php" method="POST">
        <input type="hidden" name="id_evento" id="id_evento" value="" />
    </form>
    <?php
    toolErp::modalInicio();
    ?>
    <iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
</div>
<?php
$ev = sql::get('ge_eventos', '*', ['fk_id_inst' => tool::id_inst(), '<' => 'dt_evento']);
$sqlkey = DB::sqlKey('ge_eventos', 'delete');
foreach ($ev as $key => $v) {
    $v['id_eve'] = $v['id_evento'];
    $v['l'] = 1;
    $ev[$key]['excluir'] = formulario::submit('Excluir', $sqlkey, ['1[id_evento]' => $v['id_evento']]);
    $ev[$key]['even'] = formulario::submit('Editar', null, $v);
    $ev[$key]['classe'] = formulario::submit('Selecionar Classe', null, ['id_eve' => $v['id_evento']], HOME_URI . '/gestao/convocacao_lista');
    $ev[$key]['grupo'] = formulario::submit('Criar Grupo', null, $v, HOME_URI . '/gest/eventos');
}
$form['array'] = $ev;
$form['fields'] = [
    'Assunto' => 'evento',
    'Data' => 'dt_evento',
    'Local' => 'local_evento',
    '||1' => 'excluir',
    '||2' => 'even',
    '||3' => 'classe',
    '||4' => 'grupo'
];
tool::relatSimples($form);
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