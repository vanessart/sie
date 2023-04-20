<?php
if (!defined('ABSPATH'))
    exit;

$id_pessoa = toolErp::id_pessoa();
$tpd = $model->turmaDisciplina($id_pessoa);
?>
<div class="body">
    <?php
    if (!empty($tpd)) {

        foreach ($tpd as $key => $value) {

            if (!is_numeric($key)) {
                ?>
                <form target="_blank" action="<?= HOME_URI . '/profe/consolidado' ?>" method="post" id="form_ids-<?= $value['id_turma'] ?>-<?= $value['id_disc'] ?>">
                    <input type="hidden" name="n_disc" value="<?= $value['nome_disc'] ?>">
                    <input type="hidden" name="id_turma" value="<?= $value['id_turma'] ?>">
                    <input type="hidden" name="id_curso" value="<?= $value['id_curso'] ?>">
                    <input type="hidden" name="id_disc" value="<?= $value['id_disc'] ?>">
                    <input type="hidden" name="id_pl" value="<?= $value['id_pl'] ?>">
                    <input type="hidden" name="n_inst" value="<?= $value['escola'] ?>">
                    <div style="text-align: center">
                        <button style="width: 80%" class="btn btn-primary">
                            <?= $value['nome_turma'] ?> -  <?= $value['nome_disc'] ?> - <?= $value['escola'] ?>
                        </button>
                    </div>
                </form>
                <br /><br />
                <?php
            }
        }
    } else {
        ?>
        <div class="alert alert-danger text-center">
            <p>
                Você não tem turmas alocadas.
            </p>
            <p>
                Procure a secretaria de sua Escola.
            </p>
        </div>
        <?php
    }
    ################################## esconde #####################################
    if (in_array(tool::id_pessoa(), [1, 26])) {
        ?>
        <div class="fieldTop">
            Consolidado 2022
        </div>
        <?php
        $tpd = $model->turmaDisciplina($id_pessoa, 2022);
        if (!empty($tpd)) {

            foreach ($tpd as $key => $value) {

                if (!is_numeric($key)) {
                    ?>
                    <form target="_blank" action="<?= HOME_URI . '/profe/consolidado' ?>" method="post" id="form_ids-<?= $value['id_turma'] ?>-<?= $value['id_disc'] ?>">
                        <input type="hidden" name="n_disc" value="<?= $value['nome_disc'] ?>">
                        <input type="hidden" name="id_turma" value="<?= $value['id_turma'] ?>">
                        <input type="hidden" name="id_curso" value="<?= $value['id_curso'] ?>">
                        <input type="hidden" name="id_disc" value="<?= $value['id_disc'] ?>">
                        <input type="hidden" name="id_pl" value="<?= $value['id_pl'] ?>">
                        <input type="hidden" name="n_inst" value="<?= $value['escola'] ?>">
                        <div style="text-align: center">
                            <button style="width: 80%" class="btn btn-info">
                                <?= $value['nome_turma'] ?> -  <?= $value['nome_disc'] ?> - <?= $value['escola'] ?>
                            </button>
                        </div>
                    </form>
                    <br /><br />
                    <?php
                }
            }
        } else {
            ?>
            <div class="alert alert-danger text-center">
                <p>
                    Você não teve turmas alocadas em 2022.
                </p>
            </div>
            <?php
        }
    }
    ?>
</div>
