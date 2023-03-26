<div class="fieldBody">

    <?php
    if (!empty($_POST['id_turma'])) {
        $id_turma = $_POST['id_turma'];
        $id_inst = $_POST['id_inst'];
        // $tamanho = ((300 + (count(@$escolas[@$id_inst]['disciplinas'][@$id_turma])*100) + (count(@$escolas[@$id_inst]['nucleoComum'][@$id_turma])*100))*1);

        if (!empty($_POST['id_pessoa_a'])) {
            tool::modalInicio('width: 90%');
            include ABSPATH . '/views/hab/rel_hab_escola.php';
            tool::modalFim();
        }
    }
    ?>
    <br />
    <div class="fieldBorder2" role="alert" style="text-align: center; font-weight: bold; font-size: 16px; padding: 25px; width: 80%; margin: 0 auto">
        <?php // echo $turma['escola']  ?>
        <br /><br />
        <div class="row">
            <div class="col-md-5">
                <?php
                $escolas = hab::listescolas();
                foreach ($escolas as $v) {
                    $options[$v['id_inst']] = $v['n_inst'];
                }
                formulario::select('id_inst', $options, 'Escolas', @$_POST['id_inst'], 1, ['id_inst' => $v['id_inst']]);
                ?> 

            </div>
            <div class="col-md-3">
                <?php
                formulario::select('id_filtro', ['1' => 'Turma', '2' => 'Ciclo', '3' => 'Disciplina', '4' => 'Professor'], 'Filtrar:', @$_POST['id_filtro'], 1, ['id_inst' => @$_POST['id_inst']]);
                ?>
            </div>
        </div>

        <div class="row">
            <?php
            if (!empty(@$_POST['id_inst'] && @$_POST['id_filtro'] == 1)) {
                $turmas = hab::turmas($_POST['id_inst']);

                foreach ($turmas as $v) {
                    ?>
                    <div class="col-md-4">
                        <form method="POST">
                            <input type="hidden" name="id_turma" value="<?php echo $_POST['id_turma'] ?>" />
                            <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                            <button class="btn btn-info">
                                <?php echo $v['n_turma']; ?>
                            </button>
                        </form>
                    </div>
                    <?php
                }
            }
            if (!empty(@$_POST['id_inst'] && @$_POST['id_filtro'] == 2)) {
                $ciclos = hab::ciclos($_POST['id_inst']);

                foreach ($ciclos as $v) {
                    ?>
                    <div class="col-md-4">
                        <form method="POST">
                            <input type="hidden" name="id_turma" value="<?php echo $_POST['id_turma'] ?>" />
                            <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                            <input type="hidden" name="id_ciclo" value="<?php echo $v['id_ciclo'] ?>" />
                            <button class="btn btn-info">
                                <?php echo $v['n_ciclo']; ?>
                            </button>
                        </form>
                    </div>
                    <?php
                }
            }
            if (!empty(@$_POST['id_inst'] && @$_POST['id_filtro'] == 3)) {
                $disciplinas = hab::disciplinas($_POST['id_inst']);

                 foreach ($disciplinas as $v) {
                    ?>
                    <div class="col-md-4">
                        <form method="POST">
                            <input type="hidden" name="id_turma" value="<?php echo $_POST['id_turma'] ?>" />
                            <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                            <input type="hidden" name="id_ciclo" value="<?php echo $v['id_ciclo'] ?>" />
                            <button class="btn btn-info" style="padding-left: 2px;">
                                <?php echo $v['n_disc']; ?>
                            </button>
                        </form>
                    </div>
                    <?php
                }
                
            }
            
             if (!empty(@$_POST['id_inst'] && @$_POST['id_filtro'] == 4)) {
                    $professores = hab::professores($_POST['id_inst']);

                    foreach ($professores as $v) {
                        ?>
                        <div class="col-md-4">
                            <form method="POST">
                                <input type="hidden" name="id_turma" value="<?php echo $_POST['id_turma'] ?>" />
                                <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                                <button class="btn btn-info" style="padding-left: 2px;">
                                    <?php echo $v['n_pe']; ?>
                                </button>
                            </form>
                        </div>
                        <?php
                    }
                }
            ?>

        </div>
    </div>

</div>