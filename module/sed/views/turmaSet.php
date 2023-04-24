<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = tool::id_inst();
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_pl = ng_main::periodoSet($id_pl);
$periodos = ng_main::periodosPorSituacao();
$periodoDia = ng_main::periodoDoDia();
$turmas = gtTurmas::turmas($id_inst, $id_pl);
foreach ($turmas as $v) {
    if ($v['rm_prof']) {
        $rm[] = $v['rm_prof'];
    }
}
if (!empty($rm)) {
    $sql = " select p.n_pessoa, rm as id_rm from ge_funcionario f "
            . " join pessoa p on p.id_pessoa = fk_id_pessoa "
            . " where f.rm in ('" . implode("', '", $rm) . "')";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    $prof = toolErp::idName($array);
}
foreach ($turmas as $key => $v) {
    $turmas[$key]['botao'] = formErp::submit('Acessar', null, ['id_turma' => $v['id_turma'], 'id_pl' => $id_pl], HOME_URI . '/sed/turmas');
    $turmas[$key]['editar'] = ' <button class="btn btn-warning" onclick="NovaTurma(' . (@$v['id_turma']) . ')">Editar</button>';
    $turmas[$key]['periodo'] = $periodoDia[$v['periodo']];
    $turmas[$key]['rm'] = @$prof[$v['rm_prof']];
}
$form['array'] = $turmas;
$form['fields'] = [
    'ID' => 'id_turma',
    'Nº Prodesp' => 'prodesp',
    'Curso' => 'n_curso',
    "Turma" => 'n_turma',
    'Período' => 'periodo',
    'Professor Responsável' => 'rm',
    "||2" => 'botao',
    "||3" => 'editar'
];
?>
<div class="body">
    <div class="row">
        <div class="col">
            <?= formErp::select('id_pl', $periodos, 'PERIODO LETIVO', $id_pl, 1) ?><br>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <button class="btn btn-warning" onclick="NovaTurma()">Nova Turma</button>
        </div>
    </div>
    <?= report::simple($form) ?>
</div>

<form action="<?= HOME_URI ?>/sed/def/formCurso.php" target="frame" method="post" id="form">
    <input type="hidden" name="id_turma" id="id_turma">
</form>

<?php
toolErp::modalInicio();
?>
<iframe name="frame" style="width:100%; height:80vh; border:none; "></iframe>
    <?php
    toolErp::modalFim();
    ?>

<script>
    function NovaTurma($id)
    {
        if ($id) {
            document.getElementById('id_turma').value = $id;
        } else {
            document.getElementById('id_turma').value = '';

        }
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>