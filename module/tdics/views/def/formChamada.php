<?php
if (!defined('ABSPATH'))
    exit;
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$data = filter_input(INPUT_POST, 'data', FILTER_UNSAFE_RAW);

$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_UNSAFE_RAW);
$n_polo = filter_input(INPUT_POST, 'n_polo', FILTER_UNSAFE_RAW);
$alu = $model->alunoEsc($id_pl, null, null, $id_turma);

$mongo = new mongoCrude('Tdics');
@$presArr = (array) $mongo->query('presece_' . $id_pl, ['id_turma' => $id_turma, 'data' => $data])[0];
if ($presArr) {
    $pres = (array) $presArr['ch'];
    $jt = (array) $presArr['jt'];
}
if (empty($data)) {
    $data = date("Y-m-d");
}
?>
<div class="body">
    <form action="<?= HOME_URI ?>/tdics/chamada" target="_parent" method="POST">
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    Turma: <?= $n_turma ?>
                </td>
                <td>
                    Núcleo: <?= $n_polo ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= formErp::input('data', 'Data', $data, null, null, 'date') ?>
                </td>
                <td>
                    <?= formErp::checkbox(null, 1, 'Presença para todos', null, ' onclick=" $(\'.pres\').not(this).prop(\'checked\', this.checked); $(\'.just\').not(this).prop(\'checked\', false)" ') ?>
                </td>
            </tr>
        </table>
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    Presente
                </td>
                <td>
                    Justicado
                </td>
                <td>
                    Nome
                </td>
                <td>
                    RSE
                </td>
            </tr>
            <?php
            foreach ($alu as $v) {
                ?>
                <tr>
                    <td>
                        <?= formErp::checkbox('ch[' . $v['id_pessoa'] . ']', 1, null, @$pres[$v['id_pessoa']], 'class="pres" id="ps_' . $v['id_pessoa'] . '" onclick="prestira(' . $v['id_pessoa'] . ',1)"') ?>
                    </td>
                    <td>
                        <?= formErp::checkbox('jt[' . $v['id_pessoa'] . ']', 1, null, @$jt[$v['id_pessoa']], ' class="just" id="jt_' . $v['id_pessoa'] . '" onclick="prestira(' . $v['id_pessoa'] . ',2)" ') ?>
                    </td>
                    <td>
                        <?= $v['n_pessoa'] ?>
                    </td>
                    <td>
                        <?= $v['id_pessoa'] ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?= formErp::textarea('ocorrencia', @$presArr['ocorrencia'], 'Ocorrência') ?>
        <div style="text-align: center; padding: 40px">
            <?=
            formErp::hidden([
                'id_turma' => $id_turma,
                'id_polo' => $id_polo,
                'id_pl' => $id_pl,
                'n_turma' => $n_turma,
                'n_polo' => $n_polo
            ])
            . formErp::hiddenToken('chamadaSalvar')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
<script>
    function prestira(id, c) {
        ch = document.getElementById('ps_' + id);
        jst = document.getElementById('jt_' + id);
        if (c == 1) {
            if (ch.checked === true) {
                jst.checked = false;
            }
        } else {
            if (jst.checked === true) {
                ch.checked = false;
            }
        }
    }

</script>
