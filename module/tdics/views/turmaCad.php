<?php
if (!defined('ABSPATH'))
    exit;
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
if ($id_pl && $id_polo) {
    $sql = "SELECT t.id_turma, COUNT(ta.id_ta) n_ct FROM tdics_turma t "
            . " JOIN tdics_turma_aluno ta on ta.fk_id_turma = t.id_turma "
            . " and t.fk_id_pl = $id_pl AND t.fk_id_polo = $id_polo "
            . " GROUP BY t.id_turma ";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    if ($array) {
        $temAlu = toolErp::idName($array);
    }
}
$pls = sql::idNome('tdics_pl', 'where ativo in (1,2)');
if (empty($id_pl)) {
    $id_pl = sql::get('tdics_pl', 'id_pl', ['ativo' => 1], 'fetch')['id_pl'];
}
$polos = sql::idNome('tdics_polo');
if ($id_polo) {
    $turmas = sql::get(['tdics_turma', 'tdics_curso'], '*', ['fk_id_polo' => $id_polo, '>' => 'n_turma', 'fk_id_pl' => $id_pl]);
    if ($turmas) {
        $tonkenDel = formErp::token('tdics_turma', 'delete');
        foreach ($turmas as $k => $v) {
            $turmas[$k]['ac'] = '<button class="btn btn-info" onclick="edit(' . $v['id_turma'] . ')">Editar</button>';
            $turmas[$k]['dia'] = $model->diaSemana($v['dia_sem']);
            $turmas[$k]['periodo'] = $v['periodo'] == 'M' ? 'Manhã' : 'Tarde';
            $turmas[$k]['horario'] = $v['horario'] . 'º Horário';
            if (empty($temAlu[$v['id_turma']])) {
                $turmas[$k]['del'] = formErp::submit('Excluir', $tonkenDel, ['1[id_turma]' => $v['id_turma'], 'id_pl' => $id_pl, 'id_polo' => $id_polo]);
            } else {
                $turmas[$k]['del'] = '<button class="btn btn-outline-dark"><img src="' . HOME_URI . '/views/_images/aluno.png" alt="alt"/></button>';
            }
        }
        $form['array'] = $turmas;
        $form['fields'] = [
            'ID' => 'id_turma',
            'Nome' => 'n_turma',
            'Curso' => 'n_curso',
            'Dia da Semana' => 'dia',
            'Período' => 'periodo',
            'Horário' => 'horario',
            '||2' => 'del',
            '||1' => 'ac'
        ];
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Cadastro de Turmas
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_pl', $pls, 'Período Letivo', $id_pl, 1, ['id_polo' => $id_polo]) ?>
        </div>
        <div class="col">
            <?= formErp::select('id_polo', $polos, 'Núcleo', $id_polo, 1, ['id_pl' => $id_pl]) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col" style="text-align: left; font-weight: bold; font-size: 1.8em; padding: 10px">
            <?php
            if ($id_polo) {
                echo $polos[$id_polo] . ' - ' . $pls[$id_pl];
            }
            ?>
        </div>
        <div class="col" style="text-align: right; padding-right: 100px">
            <?php
            if ($id_polo) {
                ?>
                <button class="btn btn-primary" onclick="edit()">Nova Turma</button>
                <?php
            }
            ?>
        </div>
    </div>
    <br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/def/formTurma" target="frame" id="form" method="POST">
    <input type="hidden" name="id_polo" value="<?= $id_polo ?>" />
    <input type="hidden" name="id_pl" value="<?= $id_pl ?>" />
    <input type="hidden" name="id_turma" id="id_turma" value="" />
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
            id_turma.value = id;
        } else {
            id_turma.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>