<?php
if (!defined('ABSPATH'))
    exit;
$ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_NUMBER_INT);
if (empty($ano)) {
    $ano = date("Y");
}
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$proc = filter_input(INPUT_POST, 'proc', FILTER_SANITIZE_NUMBER_INT);
if ($proc) {
    include ABSPATH . '/module/sed/views/_carometro/crop.php';
} else {
    ?>
    <div class="body">
        <div class="fieldTop">
            Carômetro
        </div>
        <div class="row">
            <div class="col-md-4">
                <?php
                if ($_SESSION['userdata']['id_nivel'] != 24) {
                    echo formErp::selectNum('ano', [date("Y"), (date("Y") + 1)], 'Ano', $ano, 1, ['id_turma' => $id_turma]);
                }
                ?>
            </div>
            <div class="col-md-4">
                <?php
                if ($_SESSION['userdata']['id_nivel'] == 24) {
                    $options = professores::classes(tool::id_pessoa())['classesEscolas'];
                    echo formErp::dropDownList('id_turma', $options, 'Turma', $id_turma, 1, ['ano' => $ano]);
                } else {
                    $options = ng_turmas::optionNome(null, null, null, null, toolErp::id_inst()) ;
                    echo formErp::select('id_turma', $options, 'Turma', $id_turma, 1, ['ano' => $ano]);
                }
                ?>
            </div>
            <?php
            if ($id_turma) {
                ?>
                <div class="col-md-4 text-center">
                    <form action="<?php echo HOME_URI ?>/sed/pdfcarometro" target="_blank" method="POST">
                        <input type="hidden" name="id_turma" value="<?php echo $id_turma ?>" />
                        <button class="btn btn-info" type="submit">
                            Imprimir
                        </button>
                    </form>
                </div>
                <?php
            }
            ?>
        </div>
        <?php
        if ($id_turma) {
            $aluno = turmas::classe($id_turma);
            ?>
            <br /><br />
            <?php
            include ABSPATH . '/module/sed/views/_carometro/tab.php';
        }
        ?>

    </div>
    <?php
}
