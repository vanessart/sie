<?php
$proc = filter_input(INPUT_POST, 'proc', FILTER_SANITIZE_NUMBER_INT);
if ($proc) {
    include ABSPATH . '/views/gt/_carometro/crop.php';
} else {
    ?>
    <div class="fieldBody">
        <div class="fieldTop">
            Carômetro
        </div>
        <div class="row">
            <div class="col-md-6">
                <?php
                if ($_SESSION['userdata']['id_nivel'] == 24) {
                    $options = professores::classes(tool::id_pessoa())['classesEscolas'];
                } else {
                    $options = turmas::option();
                }

                formulario::select('id_turma', $options, 'Classe', @$_POST['id_turma'], 1);
                ?>
            </div>
            <?php
            if (!empty(@$_POST['id_turma'])) {
                ?>
                <div class="col-md-6 text-center">
                    <form action="<?php echo HOME_URI ?>/gestao/pdfcarometro" target="_blank" method="POST">
                        <input type="hidden" name="id_turma" value="<?php echo @$_POST['id_turma'] ?>" />
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
        if (!empty(@$_POST['id_turma'])) {
            $aluno = turmas::classe(@$_POST['id_turma']);
            ?>
            <br /><br />
            <?php
            include ABSPATH . '/views/gt/_carometro/tab.php';
        }
        ?>

    </div>
    <?php
}
