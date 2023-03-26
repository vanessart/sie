<div class="fieldBody">

    <?php
    if (!empty($id_inst)) {
        if ($turmas = hab::turmas($id_inst)) { //está configurado no constructor
            if (!empty($_POST['id_turma'])) {
                $id_turma = $_POST['id_turma'];
                $id_inst = $_POST['id_inst'];
                // $tamanho = ((300 + (count(@$escolas[@$id_inst]['disciplinas'][@$id_turma])*100) + (count(@$escolas[@$id_inst]['nucleoComum'][@$id_turma])*100))*1);

                if (!empty($_POST['id_pessoa_a'])) {
                    tool::modalInicio('width: 90%');
                    include ABSPATH . '/views/hab/relturma.php';
                    tool::modalFim();
                }
            }
            ?>
            <br />
            <div class="fieldBorder2" role="alert" style="text-align: center; font-weight: bold; font-size: 16px; padding: 25px; width: 80%; margin: 0 auto">
                <?php // echo $turma['escola']  ?>
                <br /><br />
                <div class="row">
                    <div class="col-md-3">
                        <?php
                        $turmas = hab::turmas($id_inst);
                        foreach ($turmas as $v) {
                            $options[$v['id_turma']] = $v['n_turma'];
                        }
                        formulario::select('id_turma', $options, 'Turma', @$_POST['id_turma'], 1, ['id_turma' => $v['id_turma'], 'id_inst' => $id_inst,]);
                        ?> 

                    </div>
                </div>

                <div class="row">
                    <?php
                    if (!empty($_POST['id_turma'])) {
                        $alunos_ = alunos::listar($_POST['id_turma'], $id_inst);

                        foreach ($alunos_ as $v) {

                            $id_aluno[] = $v['id_pessoa'];
                            $alunos[$v['chamada']] = $v;
                            ?>
                            <div class="col-md-4">
                                <form method="POST">
                                    <input type="hidden" name="id_pessoa_a" value="<?php echo $v['id_pessoa'] ?>" />
                                    <input type="hidden" name="id_turma" value="<?php echo $_POST['id_turma'] ?>" />
                                    <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                                    <button class="btn btn-info">
                                        <?php echo $v['n_pessoa']; ?>
                                    </button>
                                </form>
                            </div>
                            <?php
                        }
                    }
                    ?>

                </div>
            </div>
            <?php
        }
    }
    ?>
</div>