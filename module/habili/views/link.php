<?php
if (!defined('ABSPATH'))
    exit;
$data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING);
if (empty($data)) {
    $data = date("Y-m-d");
}
$aulas = $model->aulaDiscDia(toolErp::id_inst(), date('w', strtotime($data)));
?>
<div class="body">
    <div class="fieldTop">
        Gerar Links 
    </div>
    <div class="row">
        <div class="col-3">
            <form id="form" method="POST">
                <?= formErp::input('data', 'Data', $data, 'min="' . date("Y-m-d") . '" onchange="form.submit()" ', null, 'date') ?>
            </form>
        </div>
    </div>
    <br />
</div>
<div style="width: 90%; margin: auto">
    <form action="<?= HOME_URI ?>/habili/def/geraLink.php" target="frame" method="POST">
        <table class="table table-bordered table-hover table-striped">
            <?php
            if ($aulas) {
                foreach ($aulas as $id_turma => $v) {
                    ?>
                    <tr>
                        <td>
                            <?= $v['n_turma'] ?>
                        </td>
                        <td>
                            <?php
                            if ($v['disc']) {
                                foreach ($v['disc'] as $iddisc => $y) {
                                    ?>
                                <td colspan="<?= count($y['aulas']) ?>">
                                    <?= formErp::checkbox($id_turma . '[' . $iddisc . ']', $v['n_turma'] . ' - ' . $y['n_disc'] . ' (' . toolErp::virgulaE($y['aulas'], 'ª') . (count($y['aulas']) > 1 ? ' Aulas' : ' aula') . ')', $y['n_disc'] . ' (' . toolErp::virgulaE($y['aulas'], 'ª') . (count($y['aulas']) > 1 ? ' Aulas' : ' aula') . ')') ?>
                                </td>
                                <?php
                            }
                        }
                        ?>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <div class="alert alert-warning">
                    Para usar esta funcionalidade é necessário preencher o horário escolar, mesmo que não tenha um professor alocado na disciplina.
                </div>
                <?php
            }
            ?>
        </table>
        <div style="text-align: center; padding: 30px">
            <?= formErp::hidden(['data' => $data]) ?>
            <?php
            if ($aulas) {
                ?>
                <button class="btn btn-success" onclick="$('#myModal').modal('show');$('.form-class').val('');">
                    Gerar Links
                </button>
                <?php
            }
            ?>
        </div>
    </form>
</div>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
<?php
toolErp::modalFim();
?>