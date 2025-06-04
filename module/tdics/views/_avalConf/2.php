<?php
if (!defined('ABSPATH'))
    exit;
$hidden = [
    'id_ag' => $id_ag,
    'id_pl' => $id_pl,
    'id_aval' => $id_aval,
    'activeNav' => 2,
];
$sql = "SELECT * FROM `tdics_aval_quest` WHERE `fk_id_aval` = $id_aval ORDER BY `ordem` ASC ";
$query = pdoSis::getInstance()->query($sql);
$quest = $query->fetchAll(PDO::FETCH_ASSOC);
if ($quest) {
    $token = formErp::token('tdics_aval_quest', 'delete');
    foreach ($quest as $k => $v) {
        $quest[$k]['ed'] = '<button class="btn btn-success" onclick="edit(' . ($v['id_quest']) . ')">Editar</button>';
        $hidden['1[id_quest]'] = $v['id_quest'];
        $quest[$k]['del'] = formErp::submit('Excluir', $token, $hidden);
    }
    $form['array'] = $quest;
    $form['fields'] = [
        'Ordem' => 'ordem',
        'Análise' => 'n_quest',
        'Momento' => 'momento',
        'ID' => 'id_quest',
        '||1' => 'del',
        '||2' => 'ed'
    ];
}
?>
<br /><br />
<button class="btn btn-success" onclick="edit()">
    Nova Questão
</button>
<br /><br />
<?php
if (!empty($form)) {
    report::simple($form);
}
?>
<form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/def/formQuest" target="frame" id="form" method="POST">
    <input type="hidden" name="id_quest" id="id_quest" value="" />
    <?=
    formErp::hidden($hidden)
    ?>
</form>
<?php
toolErp::modalInicio(null, 'modal-fullscreen');
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            id_quest.value = id;
        } else {
            id_quest.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>