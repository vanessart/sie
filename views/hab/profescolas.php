


<div class="fieldBody">

    <?php
    if (!empty($id_pessoa)) {
        if ($escolas = professores::classesDisc($id_pessoa)) { //está configurado no constructor
            if (!empty($_POST['id_turma'])) {
                $id_turma = $_POST['id_turma'];
                $id_inst = $_POST['id_inst'];
                // $tamanho = ((300 + (count(@$escolas[@$id_inst]['disciplinas'][@$id_turma])*100) + (count(@$escolas[@$id_inst]['nucleoComum'][@$id_turma])*100))*1);
                if (!empty($escolas[$id_inst]['turmas'][$id_turma])) {
                    tool::modalInicio('width: 90%');
                    include ABSPATH . '/views/hab/profaval.php';
                    tool::modalFim();
                }
            }

            foreach ($escolas as $id_inst => $turma) {
                ?>
                <br />
                <div class="fieldBorder2" role="alert" style="text-align: center; font-weight: bold; font-size: 16px; padding: 25px; width: 80%; margin: 0 auto">
                    <?php echo $turma['escola'] ?>
                    <br /><br />
                    <div class="row">
                        <?php
                        foreach ($turma['turmas'] as $id_turma => $t) {
                            ?>
                            <div class="col-md-2">
                                <form name="form<?php echo $id_turma ?>" action="<?php echo HOME_URI ?>/hab/profaval" method="POST">
                                    <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
                                    <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                                    <input type="hidden" name="id_turma" value="<?php echo $id_turma ?>" />
                                    <button class="btn btn-info">
                                        <?php echo $t ?>
                                    </button>
                                </form>
                            </div>
                            <?php
                        }
                        ?>

                    </div>
                </div>
                <?php
            }
        }
    }
    ?>
</div>