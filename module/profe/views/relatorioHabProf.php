<?php
if (!defined('ABSPATH')) {
    exit;
}
$id_pessoa = toolErp::id_pessoa();
$tpd = $model->turmaDisciplina($id_pessoa);

?>
<div class="body">
    <div class="fieldTop">
        Relatório de Habilidades <?= date('Y') ?>
    </div>
    <div class="geral">
        <?php
        if (!empty($tpd)) {

            foreach ($tpd as $key => $value) {

                if (!is_numeric($key)) {
        ?>
                    <form action="<?= HOME_URI . '/profe/habTabela' ?>" method="post" id="form_ids-<?= $value['id_turma'] ?>-<?= $value['id_disc'] ?>">
                        <div class="border" id="border-main" onclick="document.getElementById('form_ids-<?= $value['id_turma'] ?>-<?= $value['id_disc'] ?>').submit()" style="cursor: pointer; margin-bottom:20px;">
                            <div class="col" align="center">
                                <h3><?= $value['nome_turma'] ?></h3>
                                <h5><?= $value['nome_disc'] ?></h5>
                                <h5><?= $value['escola'] ?></h5>
                                <input type="hidden" name="id_turma" value="<?= $value['id_turma'] ?>">
                                <input type="hidden" name="id_disc" value="<?= $value['id_disc'] ?>">
                                <input type="hidden" name="n_disc" value="<?= $value['nome_disc'] ?>">
                                <input type="hidden" name="n_turma" value="<?= $value['nome_turma'] ?>">
                                <input type="hidden" name="id_pl" value="<?= $value['id_pl'] ?>">
                                <input type="hidden" name="escola" value="<?= $value['escola'] ?>">
                                <input type="hidden" name="id_curso" value="<?= $value['id_curso'] ?>">
                                <input type="hidden" name="id_ciclo" value="<?= $value['id_ciclo'] ?>">
                            </div>
                        </div>
                    </form>
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
        ?>
    </div>
</div>