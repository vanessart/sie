<?php
if (!defined('ABSPATH'))
    exit;
$_pl = sql::get('tdics_pl', '*', ' where ativo in (1)', 'fetch');
$id_pl = !empty($_pl) ? $_pl['id_pl'] : 0;
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$polos = sql::idNome('tdics_polo');
if ($id_polo) {
    $turmas = sql::idNome('tdics_turma', ['fk_id_polo' => $id_polo, 'fk_id_pl' => $id_pl]);
}
if ($id_turma) {
    $mongo = new mongoCrude( ucfirst($this->sistema) );
    $pres = $mongo->query('presece_' . $id_pl, ['id_turma' => $id_turma]);
    $token = formErp::token('apagaCh');
    if (!empty($pres)) {
        foreach ($pres as $v) {
            @$ct = [];
            $form['array'][$v->data]['data'] = $v->data;
            if (!empty($v->ch)) {
                foreach ($v->ch as $pf) {
                    @$ct[$pf]++;
                }
            }

            if (!empty($v->jt)) {
                foreach ($v->jt as $pf) {
                    if ($pf) {
                        @$ct[0]--;
                        @$ct[2]++;
                    }
                }
            }

            @$form['array'][$v->data]['Presentes'] = $ct[1];
            $form['array'][$v->data]['Faltaram'] = @$ct[0];
            $form['array'][$v->data]['Justificadas'] = @$ct[2];
            $form['array'][$v->data]['ac'] = '<button class="btn btn-warning" onclick="ch(\'' . $v->data . '\')">Acessar</button>';
            $form['array'][$v->data]['del'] = formErp::submit('Apagar', $token, ['id_turma' => $id_turma, 'id_polo' => $id_polo, 'id_pl' => $id_pl, 'data' => $v->data]);
        }

        $form['fields'] = [
            'Data' => 'data',
            'Presentes' => 'Presentes',
            'Justificadas' => 'Justificadas',
            'Faltaram' => 'Faltaram',
            '||2' => 'del',
            '||1' => 'ac'
        ];
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Chamada
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_polo', $polos, 'Núcleo', $id_polo, 1) ?>
        </div>
        <div class="col">
            <?php
            if ($id_polo) {
                echo formErp::select('id_turma', $turmas, 'Turma', $id_turma, 1, ['id_polo' => $id_polo]);
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
    <form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/def/formChamada" target="frame" id="formCh" method="POST">
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