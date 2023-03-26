<?php
if (!defined('ABSPATH'))
    exit;

$ids = filter_input(INPUT_POST, 'ids', FILTER_SANITIZE_STRING);
if ($ids) {
    $sql = " SELECT "
            . " p.id_pessoa, p.n_pessoa, p.emailgoogle, ch.id_ch, ch.serial, f.funcao, f.situacao, f.rm, i.n_inst, i.id_inst "
            . " FROM ge_funcionario f "
            . " JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa "
            . " JOIN instancia i on i.id_inst = f.fk_id_inst "
            . " left join lab_chrome_emprestimo e on e.fk_id_pessoa = f.fk_id_pessoa "
            . " LEFT JOIN lab_chrome ch on ch.id_ch = e.fk_id_ch "
            . " WHERE p.id_pessoa in ($ids)";
    $query = pdoSis::getInstance()->query($sql);
    $array1 = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($array1 as $v){
        $array[$v['id_pessoa']]=$v;
    }
}
?>
<div class="body">
    <?php
    foreach ($array as $v) {
        ?>
        <table class="table table-bordered table-hover table-striped border">
            <tr>
                <td style="width: 15%">
                    Nome
                </td>
                <td colspan="3">
                    <?= $v['n_pessoa'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Situação
                </td>
                <td>
                    <?= $v['situacao'] ?>
                </td>
                <td>
                    Matrícula
                </td>
                <td>
                    <?= $v['rm'] ?>
                </td>
            </tr>
            <tr>
                <td style="width: 20%">
                    E-mail
                </td>
                <td colspan="3">
                    <?= $v['emailgoogle'] ?>
                </td>
            </tr>
            <tr>
                <td style="width: 20%">
                    N. Série
                </td>
                <td colspan="3">
                    <?= $v['serial'] ?>
                </td>
            </tr>
            <tr>
                <td style="width: 20%">
                    Função
                </td>
                <td colspan="3">
                    <?= $v['funcao'] ?>
                </td>
            </tr>
            <tr>
                <td style="width: 20%">
                    Escola
                </td>
                <td colspan="3">
                    <?= $v['n_inst'] ?>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center">
                    <button onclick="turmas(<?= $v['rm'] ?>)" class="btn btn-warning">
                        Turmas
                    </button>
                </td>
            </tr>
        </table>
        <?php
    }
    ?>
</div>
<form id="formFramet" target="framet" action="<?= HOME_URI ?>/lab/def/listTurmas.php" method="POST">
    <input type="hidden" id="rm" name="rm" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="framet" style="width: 100%; border: none; height: 80vh"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function turmas(rm) {
        document.getElementById("rm").value = rm;
        document.getElementById('formFramet').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');

    }
</script>