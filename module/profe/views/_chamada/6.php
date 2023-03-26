<?php
if (!defined('ABSPATH'))
    exit;
include ABSPATH . '/module/profe/views/_chamada/6_7.php';
foreach ($alunos as $v) {
    ?>
    <div class="border" role="alert" style="font-size:17px; margin-bottom:30px">
        <table style="width: 100%">
            <tr>
                <td style="width: 80px">
                    <?php if (file_exists(ABSPATH . '/pub/fotos/' . $v['id_pessoa'] . '.jpg')) { ?>
                        <img style="width: 100%" src="<?= HOME_URI . '/pub/fotos/' . $v['id_pessoa'] . '.jpg' ?>">
                    <?php } else { ?>
                        <img style="width: 100%" src="<?= HOME_URI . '/includes/images/anonimo.jpg' ?>">
                    <?php } ?>  
                </td>
                <td style="padding: 15px">
                    <p style="font-size:22px">
                        <?= "Nº " . $v['chamada'] . ' - ' . toolErp::abreviaLogradouro($v['n_pessoa']) ?>
                    </p>  
                </td>
                <td style="text-align: right; padding: 5px">
                    <button onclick="acSonda(<?= $v['id_pessoa'] ?>)" class="btn btn-outline-success" style="width: 50px; height: 50px; border-radius: 50%">
                        <img style="width: 30px" src="<?= HOME_URI ?>/includes/images/ir.png" alt="alt"/>
                    </button>
                </td>
            </tr>
        </table>
    </div>
    <?php
}
?>
<form action="<?= HOME_URI ?>/profe/def/formSondaAluno.php" id="formSonda" target="frame" method="POST">
    <input type="hidden" name="id_pessoa" id="id_pessoa" value="" />
    <?=
    formErp::hidden([
        'data' => $data,
        'at_set' => $at_set,
        'id_gh' => $sond['fk_id_gh'],
        'id_pl' => $sond['fk_id_pl'],
        'id_ciclo' => $id_ciclo,
        'at_sonda' => $at_sonda,
        'id_curso' => $id_curso,
        'id_disc' => $id_disc,
        'id_turma' => $id_turma,
        'id_inst' => $id_inst
    ])
    ?>
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function acSonda(id) {
        id_pessoa.value = id;
        formSonda.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>
