<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="geral">
    <?php
    if (!empty($tpd)) {

        foreach ($tpd as $key => $value) {

            if (!is_numeric($key)) {
                ?>
                <form action="<?= HOME_URI . '/profe/chamada' ?>" method="post" id="form_ids-<?= $value['id_turma'] ?>-<?= $value['id_disc'] ?>">
                    <div class="border" id="border-main" onclick="document.getElementById('form_ids-<?= $value['id_turma'] ?>-<?= $value['id_disc'] ?>').submit()" style="cursor: pointer">
                        <div class="col" align="center">
                            <h3 ><?= $value['nome_turma'] ?></h3>
                            <h5><?= $value['nome_disc'] ?></h5>
                            <h5><?= $value['escola'] ?></h5>
                            <input type="hidden" name="id_inst" value="<?= $value['id_inst'] ?>">
                            <input type="hidden" name="id_turma" value="<?= $value['id_turma'] ?>">
                            <input type="hidden" name="id_disc" value="<?= $value['id_disc'] ?>">
                            <input type="hidden" name="nome_disc" value="<?= $value['nome_disc'] ?>">
                            <input type="hidden" name="nome_turma" value="<?= $value['nome_turma'] ?>">
                            <input type="hidden" name="id_pl" value="<?= $value['id_pl'] ?>">
                            <input type="hidden" name="escola" value="<?= $value['escola'] ?>">
                            <input type="hidden" name="id_curso" value="<?= $value['id_curso'] ?>">
                            <input type="hidden" name="id_ciclo" value="<?= $value['id_ciclo'] ?>">
                            <input type="hidden" name="aulas" value="<?= $value['aulas'] ?>">
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


