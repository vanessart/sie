<?php
if (!defined('ABSPATH'))
    exit;
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_pl = ng_main::periodoSet($id_pl);
$periodos = ng_main::periodosPorSituacao([1, 2]);
?>
<div class="body">
    <div class="fieldTop">
        Dias Não Letivos
    </div>
    <form method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::select('id_pl', $periodos, 'Período Letivo', @$id_pl); ?>
            </div>
            <div class="col">
                <?= formErp::selectDB('ge_cursos', 'id_curso', 'Curso', $id_curso) ?>
            </div>
            <div class="col">
                <?= formErp::button('Continuar') ?>
            </div>
        </div>
        <br />
    </form>
    <?php
    if (!empty($id_curso) && !empty($id_pl)) {
        $feriados = sql::get('sed_feriados', '*', ['fk_id_curso' => $id_curso, 'fk_id_pl' => $id_pl]);
        if ($feriados) {
            $token = formErp::token('sed_feriados', 'delete');
            foreach ($feriados as $k => $v) {
                $feriados[$k]['del'] = formErp::submit('Apagar', $token, ['1[id_feriado]' => $v['id_feriado'], 'id_curso' => $id_curso, 'id_pl' => $id_pl]);
                $feriados[$k]['ed'] = '<button class="btn btn-info" onclick="edit(' . $v['id_feriado'] . ')">Editar</button>';
            }
            $form['array'] = $feriados;
            $form['fields'] = [
                'ID' => 'id_feriado',
                'Data' => 'dt_feriado',
                'Evento' => 'n_feriado',
                '||1' => 'del',
                '||2' => 'ed'
            ];
        }
        ?>
        <div class="row">
            <div class="col">
                <button class="btn btn-info" onclick="edit()">
                    Novo dia não letivo
                </button>
            </div>

        </div>
        <br />
        <?php
        if (!empty($form)) {
            report::simple($form);
        }
    }
    ?>
</div>
<form target="frame" id="form" action="<?= HOME_URI ?>/sed/def/formFeriado.php" method="POST">
    <input type="hidden" name="id_feriado" id="id_feriado" value="" />
    <?=
    formErp::hidden([
        'id_curso' => $id_curso,
        'id_pl' => $id_pl
    ])
    ?>
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="frame" style="width: 100%; height: 60vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            document.getElementById('id_feriado').value = id;
        } else {
            document.getElementById('id_feriado').value = '';
        }
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>