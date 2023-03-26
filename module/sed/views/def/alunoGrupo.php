<?php
if (!defined('ABSPATH'))
    exit;

$id_gr = @$_REQUEST['id_gr'];
$activeNav = filter_input(INPUT_POST, 'activeNav', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$ga = $model->alunosGrupo($id_gr);
if ($ga) {
    ?>
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <td>

            </td>
            <td>
                Nome
            </td>
            <td>
                RSE
            </td>
            <td>
                Turma
            </td>
            <td>
                Escola
            </td>
        </tr>
        <?php
        foreach ($ga as $v) {
            ?>
            <tr>
                <td>
                    <form method="POST">
                        <?=
                        formErp::hidden([
                            'id_pessoa' => $v['id_pessoa'],
                            'id_gr' => $id_gr,
                        ])
                        . formErp::hiddenToken('excluiGA')
                        ?>
                        <button style="font-weight: bold" class="btn btn-light">
                            <img src="<?= HOME_URI ?>/includes/images/close.png" alt="X"/>
                        </button>
                    </form>
                </td>
                <td>
                    <?= $v['n_pessoa'] ?>
                </td>
                <td>
                    <?= $v['id_pessoa'] ?>
                </td>
                <td>
                    <?= $v['n_turma'] ?>
                </td>
                <td>
                    <?= $v['n_inst'] ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
}
