<?php
if (!defined('ABSPATH'))
    exit;
$proj = sql::get(['projeto_projeto', 'projeto_setor']);
if ($proj) {
    $token = formErp::token('projeto_projeto', 'delete');
    foreach ($proj as $k => $v) {
        $proj[$k]['at_proj'] = toolErp::simnao($v['at_proj']);
        $proj[$k]['editar'] = '<button class="btn btn-info" onclick="edit(' . $v['id_proj'] . ')">Editar</button>';
        $proj[$k]['del'] = formErp::submit('Apagar', $token, ['1[id_proj]' => $v['id_proj']]);
        $proj[$k]['ac'] = formErp::submit('Acessar', null, ['id_proj'=>$v['id_proj'], 'activeNav'=>2]);
    }
    $form['array'] = $proj;
    $form['fields'] = [
        'ID' => 'id_proj',
        'Nome' => 'n_proj',
        'Setor' => 'n_setor',
        'Início' => 'dt_inicio',
        'Término' => 'dt_fim',
        'Ativo' => 'at_proj',
        '||2' => 'del',
        '||1' => 'editar',
        '||3' => 'ac',
    ];
}
?>

<button class="btn btn-info" onclick="edit()">
    Novo Projeto
</button>
<br /><br />
<?php
if (!empty($form)) {
    report::simple($form);
}
?>
<form id="form" target="frame" action="<?= HOME_URI ?>/projeto/def/formProj.php" method="POST">
    <input type="hidden" name="id_proj" id="id_proj" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            document.getElementById("id_proj").value = id;
        } else {
            document.getElementById("id_proj").value = '';
        }
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>