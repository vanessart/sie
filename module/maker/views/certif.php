<?php
if (!defined('ABSPATH'))
    exit;
$set = sql::get('maker_setup', '*', null, 'fetch');
if (toolErp::id_nilvel() == 8) {
    $id_inst = toolErp::id_inst();
} else {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    $escolas = $model->escolasMaker();
}

if (!empty($set['fk_id_pl_certificado']) && $id_inst) {
    $id_pl = $set['fk_id_pl_certificado'];
    $periodo = sql::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $id_pl], 'fetch')['n_pl'];
    $perTxt = ' do ' . $periodo;
} else {
    $perTxt = null;
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
    if (!empty($set['fk_id_pl_certificado']) && $id_inst) {
        $alunos = $model->interna($id_inst, 'p.n_pessoa', $id_pl);
        if ($alunos) {
            foreach ($alunos as $k => $v) {
                $alunos[$k]['ck'] = formErp::checkbox('ck[' . $v['RSE'] . ']', 1);
            }
            $form['array'] = $alunos;
            $form['fields'] = [
                'RSE' => 'RSE',
                'Nome' => 'Nome',
                'Polo' => 'n_polo',
                formErp::checkbox(null, 1, null, null, 'id="select-all"') . '<label for="car">Selecionar Todos</label>' => 'ck'
            ];
        }
        if (!empty($form)) {
            ?>
            <form target="_blank" action="<?= HOME_URI ?>/maker/pdf/cert" method="POST">
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
                    'data'=>$set['dt_certif']
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