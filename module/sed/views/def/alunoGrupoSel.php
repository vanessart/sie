<?php
if (!defined('ABSPATH'))
    exit;
$id_gr = filter_input(INPUT_POST, 'id_gr', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
if ($id_turma) {
    $alunos = ng_escola::alunoPorTurma($id_turma);
    $ga = array_column($model->alunosGrupo($id_gr), 'id_pessoa');
}
if (!empty($alunos)) {
    ?>
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <td>
                nº
            </td>
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
        foreach ($alunos as $v) {
            if (!in_array($v['id_pessoa'], $ga)) {
                ?>
                <tr id="<?= $v['id_pessoa'] ?>">
                    <td>
                        <?= $v['chamada'] ?>
                    </td>
                    <td>
                        <?= $v['n_pessoa'] ?>
                    </td>
                    <td>
                        <?= $v['id_pessoa'] ?>
                    </td>
                    <td style="width: 50px">
                        <form target="ag" method="POST" action="<?= HOME_URI ?>/sed/def/alunoGrupo.php">
                            <?=
                            formErp::hidden([
                                'id_pessoa' => $v['id_pessoa'],
                                'id_gr' => $id_gr,
                                'activeNav' => 2,
                                'id_turma' => $id_turma
                            ])
                            . formErp::hiddenToken('incluiGA')
                            ?>
                            <button onclick="esconde(<?= $v['id_pessoa'] ?>)" style="font-weight: bold" class="btn btn-outline-info">
                                Incluir
                            </button>
                        </form>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>

    <?php
}
?>
<script>
    function esconde(id) {
        document.getElementById(id).style.display = 'none';
    }
</script>