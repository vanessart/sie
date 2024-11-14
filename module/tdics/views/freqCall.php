<?php
if (!defined('ABSPATH'))
    exit;
$id_sit = filter_input(INPUT_POST, 'id_sit', FILTER_SANITIZE_NUMBER_INT);
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$frenq = $model->frequeciaAluno();
$cursos = sql::idNome($model::$sistema . '_curso');
$polos = sql::idNome($model::$sistema . '_polo');
$sit = sql::idNome($model::$sistema . '_call_center_sit');
foreach ($frenq as $k => $v) {
    if (empty($v[1])) {
        $falt[$k] = $v[0];
        $faltid[$k] = $k;
    }
}
if (!empty($faltid)) {
    $alunos = $model->alunosIds($faltid, $id_curso, $id_polo, $id_sit);
    $ct = 1;
    foreach ($alunos as $k => $v) {
        $alunos[$k]['falt'] = $falt[$v['id_pessoa']];
        $alunos[$k]['ct'] = $ct++;
        $alunos[$k]['ac'] = '<button class="btn btn-primary" onclick="as(' . $v['id_pessoa'] . ')">Acessar</button>';
        if ($v['fk_id_sit'] > 0) {
            $color = 'red';
        } else {
            $color = 'black';
        }
        $alunos[$k]['sit'] = '<span style="color: ' . $color . '">' . $sit[intval($v['fk_id_sit'])] . '</span>';
    }
    $form['array'] = $alunos;
    $form['fields'] = [
        'Quant.' => 'ct',
        'Matrícula' => 'id_pessoa',
        'Nome' => 'n_pessoa',
        'Núcleo' => 'n_polo',
        'Curso' => 'n_curso',
        'Turma' => 'n_turma',
        'Escola de Origem' => 'n_inst',
        'Turma de Origem' => 'turmaEsc',
        'Faltas' => 'falt',
        'Situação' => 'sit',
        '||1' => 'ac'
    ];
}
?>

<div class="body">
    <div class="fieldTop">
        <p>
            Ausentes
        </p>
        <p>
            <?php
            if (!empty($falt)) {
                echo count($alunos) . ' Casos';
            }
            ?>
        </p>
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_polo', $polos, 'Núcleo', $id_polo, 1, ['id_curso' => $id_curso, 'id_sit' => $id_sit]) ?>
        </div>
        <div class="col">
            <?= formErp::select('id_curso', $cursos, 'Curso', $id_curso, 1, ['id_polo' => $id_polo, 'id_sit' => $id_sit]) ?>
        </div>
        <div class="col">
            <?= formErp::select('id_sit', $sit, 'Situação', $id_sit, 1, ['id_polo' => $id_polo, 'id_curso' => $id_curso]) ?>
        </div>
    </div>
    <br />
    <?php
    if (!empty($faltid)) {
        report::simple($form);
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/def/call" id="form" target="frame" method="POST">
    <input type="hidden" name="id_pessoa" id="id_pessoa" value="" />
    <?=
    formErp::hidden([
        'id_sit' => $id_sit,
        'id_polo' => $id_polo,
        'id_curso' => $id_curso
    ])
    ?>
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function as(id) {
        id_pessoa.value = id;
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>