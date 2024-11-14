<?php
if (!defined('ABSPATH'))
    exit;

$set = sql::get('tdics_setup', '*', null, 'fetch');
$id_pl = $set['fk_id_pl_certificado'];
$periodo = sql::get('tdics_pl', 'n_pl', ['id_pl' => $id_pl], 'fetch')['n_pl'];
$perTxt = ' do ' . $periodo;
if (toolErp::id_nilvel() == 8) {
    $id_inst = toolErp::id_inst();
} else {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    $escolas = $model->escolaTdics($id_pl);
}
?>
<div class="body">
    <div class="fieldTop">
        Certificados <?= $perTxt ?>
    </div>
<?php
if (toolErp::id_nilvel() != 8) {
    ?>
        <div class="col">
        <?= formErp::select('id_inst', $escolas, 'Escola', $id_inst, 1) ?>
        </div>
            <?php
        }
        if (!empty($id_pl) && $id_inst) {
            $alunos = $model->alunos($id_pl, null, $id_inst );
            if ($alunos) {
                foreach ($alunos as $k => $v) {
                    $alunos[$k]['ck'] = formErp::checkbox('ck[' . $v['id_pessoa'] . ']', 1);
                }
                $form['array'] = $alunos;
                $form['fields'] = [
                    'Matrícula' => 'id_pessoa',
                    'Nome' => 'n_pessoa',
                //    'Polo' => 'n_polo',
                    formErp::checkbox(null, 1, null, null, 'id="select-all"') . '<label for="car">Selecionar Todos</label>' => 'ck'
                ];
            }
            if (!empty($form)) {
                ?>
            <form target="_blank" action="<?= HOME_URI ?>/<?= $this->controller_name ?>/pdf/cert" method="POST">
                <br /><br />
                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-primary">
                            Gerar PDF
                        </button>
                    </div>
                </div>
                <br />               
        <?php
        echo formErp::hidden([
            'id_pl' => $id_pl,
            'id_inst' => $id_inst,
            'data' => $set['dt_certif']
        ]);
        report::simple($form);
        ?>
            </form>
                <?php
            }
        } else {
            ?>
        <div class="alert alert-danger">
            A emissão de certificados está fechada 
        </div>
    <?php
}
?>
</div>
<script>
    document.getElementById('select-all').onclick = function () {
        var checkboxes = document.querySelectorAll('input[type="checkbox"]');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    }
</script>