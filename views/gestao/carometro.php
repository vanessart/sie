<?php
 if (!gtMain::gdaeAtivo(tool::id_inst())) {                                   
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
        include ABSPATH . '/views/gestao/carometro_tab.php';
    }
    ?>

</div>
<?php
      } else {
    ?>
<div class="alert alert-danger" style="text-align: center; font-weight: bold; font-size: 22px">
   Página Desativada
</div>
                                <?php
}   