<?php
if (!defined('ABSPATH'))
    exit;
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$data = filter_input(INPUT_POST, 'data', FILTER_UNSAFE_RAW);

$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_UNSAFE_RAW);
$n_polo = filter_input(INPUT_POST, 'n_polo', FILTER_UNSAFE_RAW);
$alu = $model->alunoChamada($id_turma);
$mongo = new mongoCrude('Maker');
@$pres = (array) $mongo->query('presece_' . $id_pl, ['id_turma' => $id_turma, 'data'=>$data])[0]->ch;
if (empty($data)) {
    $data = date("Y-m-d");
}
?>
<div class="body">
    <form action="<?= HOME_URI ?>/maker/chamada" target="_parent" method="POST">
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    Turma: <?= $n_turma ?>
                </td>
                <td>
                    Polo: <?= $n_polo ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= formErp::input('data', 'Data', $data, null, null, 'date') ?>
                </td>
                <td>
                    <?= formErp::checkbox(null, 1, 'Presença para todos', null, ' onclick=" $(\'input:checkbox\').not(this).prop(\'checked\', this.checked)" ') ?>
                </td>
            </tr>
        </table>
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    Nome
                </td>
                <td>
                    RSE
                </td>
                <td>

                </td>
            </tr>
            <?php
            foreach ($alu as $v) {
                ?>
                <tr>
                    <td>
                        <?= formErp::checkbox('ch[' . $v['id_pessoa'] . ']', 1, $v['n_pessoa'], @$pres[$v['id_pessoa']]) ?>
                    </td>
                    <td>
                        <?= $v['id_pessoa'] ?>
                    </td>
                    <td>
                        <?= $v['confirma'] ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
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
