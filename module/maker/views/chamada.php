<?php
if (!defined('ABSPATH'))
    exit;
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$setup = sql::get(['maker_setup', 'ge_periodo_letivo'], 'n_pl, id_pl', null, 'fetch');
$id_pl = $setup['id_pl'];
$polos = sql::idNome('maker_polo');
$mongo = new mongoCrude('Maker');
$pres = $mongo->query('presece_' . $id_pl, ['id_turma' => $id_turma]);
$token = formErp::token('apagaCh');
if (!empty($pres)) {
    foreach ($pres as $v) {
        @$ct = [];
        $form['array'][$v->data]['data'] = $v->data;
        foreach ($v->ch as $pf) {
            @$ct[$pf]++;
        }
        @$form['array'][$v->data]['Presentes'] = $ct[1];
        $form['array'][$v->data]['Faltaram'] = @$ct[0];
        $form['array'][$v->data]['ac'] = '<button class="btn btn-warning" onclick="ch(\'' . $v->data . '\')">Acessar</button>';
        $form['array'][$v->data]['del'] = formErp::submit('Apagar', $token, ['id_turma' => $id_turma, 'id_polo' => $id_polo, 'id_pl' => $id_pl, 'data' => $v->data]);
    }

    $form['fields'] = [
        'Data' => 'data',
        'Presentes' => 'Presentes',
        'Faltaram' => 'Faltaram',
        '||2' => 'del',
        '||1' => 'ac'
    ];
}
?>

<div class="body">
    <div class="fieldTop">
        Chamada
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_polo', $polos, 'Polo', $id_polo, 1) ?>
        </div>
        <div class="col">
            <?php
            if ($id_polo) {
                $turmas = $model->turmasPolo($id_polo);
                if ($turmas) {
                    echo formErp::select('id_turma', $turmas, 'Turma', $id_turma, 1, ['id_polo' => $id_polo]);
                } else {
                    echo 'Não há turmas';
                }
            }
            ?>
        </div>
    </div>
    <br />
    <?php
    if ($id_turma) {
        ?>
        <div class="row">
            <div class="col">
                <button class="btn btn-success" onclick="ch()">
                    Nova chamada
                </button>
            </div>
            <div class="col">

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
<?php
if ($id_turma) {
    ?>
    <form action="<?= HOME_URI ?>/maker/def/formChamada" target="frame" id="formCh" method="POST">
        <?=
        formErp::hidden([
            'id_turma' => $id_turma,
            'id_polo' => $id_polo,
            'id_pl' => $id_pl,
            'n_turma' => $turmas[$id_turma],
            'n_polo' => $polos[$id_polo]
        ])
        ?>
        <input type="hidden" name="data" id="data" />
    </form>
    <?php
    toolErp::modalInicio();
    ?>
    <iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
        <?php
        toolErp::modalFim();
        ?>
    <script>
        function ch(id) {
            if (id) {
                data.value = id;
            } else {
                data.value = "";
            }
            formCh.submit();
            $('#myModal').modal('show');
            $('.form-class').val('');
        }
    </script>
    <?php
}