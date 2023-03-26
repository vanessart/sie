<?php
if (!defined('ABSPATH')) {
    exit;
}
$sistema = $model->getSistema('22','48,2,18,53,54,55,24,56');
if (in_array(toolErp::id_nilvel(), [2, 18, 53, 54, 22])) {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    if (empty($id_inst)) {
       $id_inst = toolErp::id_inst(); 
    }
    if (toolErp::id_nilvel() == 22) {
        $escolas = ng_escolas::idEscolasSupervisor(tool::id_pessoa(),[3, 7, 8]);
    }else{
        $escolas = ng_escolas::idEscolas([3, 7, 8]);
    }
} else {
    $id_inst = toolErp::id_inst();
}
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);

$Escola = new escola($id_inst);
//$tpd = $Escola->turmas(NULL, '3,7,8,10');
$tpd = $model->turmasProjeto($id_inst);
?>
<div class="body">
    <div class="fieldTop">
        Projetos <?= date('Y') ?>
    </div>
    <?php
    if (in_array(toolErp::id_nilvel(), [2, 18, 53, 54, 22])) {
        echo formErp::select('id_inst', $escolas, 'Escola', $id_inst, 1) . '<br>';
    }
    ?>
    <div class="geral">
        <?php
        if (!empty($tpd)) {

            foreach ($tpd as $key => $value) {

                // if (!is_numeric($key) && in_array($value['id_curso'], [3, 7, 8])) {
                //     $temTurma = 1
                ?>
                <form action="<?= HOME_URI . '/'. $sistema .'/projetoCoordList' ?>" method="post" id="form_ids-<?= $value['id_turma'] ?>">
                    <input type="hidden" name="id_turma" value="<?= $value['id_turma'] ?>">
                    <input type="hidden" name="fk_id_disc" value="27">
                    <input type="hidden" name="n_turma" value="<?= $value['n_turma'] ?>">
                    <input type="hidden" name="fk_id_ciclo" value="<?= $value['id_ciclo'] ?>">
                    <input type="hidden" name="id_inst" value="<?= toolErp::id_inst() ?>">
                    <div style="text-align: center">
                        <button class="btn btn-info" style="width: 650px">
                            <?= $value['n_turma'] ?> - 
                            <?= $value['n_curso'] ?>
                        </button>
                    </div>
                </form>
                <br /><br />
                <?php
                //}
            }
        }
        ?>
    </div>
</div>
<?php
if (in_array(toolErp::id_nilvel(), [2, 18, 53, 54, 22]) && !empty($id_turma)) {
    ?>
    <script>
        $(document).ready(function () {
            document.getElementById('form_ids-<?= $id_turma ?>').submit();
        });
    </script>
    <?php
}
?>