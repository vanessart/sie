<?php
if (!defined('ABSPATH')) {
    exit;
}
$id_pessoa = toolErp::id_pessoa();
$tpd = $model->turmaDisciplina($id_pessoa);
?>
<div class="body">
    <div class="fieldTop">
        Projetos <?= date('Y') ?>
    </div>
    <div class="geral">
        <?php
        if (!empty($tpd)) {

            foreach ($tpd as $key => $value) {

                if (!is_numeric($key) && in_array($value['id_curso'], [3, 7, 8])) {
                    $temTurma = 1
                    ?>
                    <form action="<?= HOME_URI . '/profe/projeto' ?>" method="post" id="form_ids-<?= $value['id_turma'] ?>-<?= $value['id_disc'] ?>">
                        <input type="hidden" name="id_turma" value="<?= $value['id_turma'] ?>">
                        <input type="hidden" name="fk_id_turma" value="<?= $value['id_turma'] ?>">
                        <input type="hidden" name="id_disc" value="<?= $value['id_disc'] ?>">
                        <input type="hidden" name="fk_id_disc" value="<?= $value['id_disc'] ?>">
                        <input type="hidden" name="n_disc" value="<?= $value['nome_disc'] ?>">
                        <input type="hidden" name="n_turma" value="<?= $value['nome_turma'] ?>">
                        <input type="hidden" name="id_pl" value="<?= $value['id_pl'] ?>">
                        <input type="hidden" name="escola" value="<?= $value['escola'] ?>">
                        <input type="hidden" name="id_curso" value="<?= $value['id_curso'] ?>">
                        <input type="hidden" name="id_ciclo" value="<?= $value['id_ciclo'] ?>">
                         <input type="hidden" name="fk_id_ciclo" value="<?= $value['id_ciclo'] ?>">
                       <input type="hidden" name="id_inst" value="<?= $value['id_inst'] ?>">
                        <div style="text-align: center">
                            <button class="btn btn-info" style="width: 650px">
                                <?= $value['nome_turma'] ?> - 
                                <?= $value['nome_disc'] ?> - 
                                <?= $value['escola'] ?> - 
                            </button>
                        </div>
                    </form>
                    <br /><br />
                    <?php
                }
            }
        }
        if (empty($temTurma)) {
            ?>
            <div class="alert alert-danger text-center">
                <p>
                    Você não tem turmas alocadas ou suas turmas não utilizam esta funcionalidade.
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