<?php
if (!empty($_POST['turma'])) {
    $id_turma = $_POST['turma'];
    @$classe = turmas::classe($id_turma);
    if (empty(turmas::transfSituacao($id_turma))) {
        $button = "submit";
        $valor = "Transferir Aluno";
    } else {
        $button = "button";
        $valor = "Transferir Aluno (Trancado)";
    }
}
$turmaOptions = turmas::option();
if (!empty($_POST['turma'])) {

    if (@$_POST['atz_chamada_m'] == "Alterar Chamada Manual") {
        include ABSPATH . '/views/gestao/manutencaoclasse_chamadaManual.php';
    } elseif (@$_POST['matricular_aluno'] == "Matricular Aluno") {
        include ABSPATH . '/views/gestao/manutencaoclasse_matriculaAluno.php';
    } elseif (@$_POST['mudar'] == "Mudar Situação") {
        include ABSPATH . '/views/gestao/manutencaoclasse_mudarSituacao.php';
    } elseif (@$_POST['recla'] == "Reclassificar") {
        $Reclassificado = "Reclassificado";
        $Reclassificar = "Reclassificar";
        $cod = $model->pegacodclasse(tool::id_inst(), $id_turma, 1);
        include ABSPATH . '/views/gestao/manutencaoclasse_reclassificar.php';
    } elseif (@$_POST['rem'] == "Remanejar") {
        $Reclassificar = "Remanejar";
        $Reclassificado = "Remanejado";
        $cod = $model->pegacodclasse(tool::id_inst(), $id_turma);
        unset($cod[$id_turma]);
        include ABSPATH . '/views/gestao/manutencaoclasse_reclassificar.php';
    } elseif (@$_POST['transf'] == "Transferir Aluno") {
        include ABSPATH . '/views/gestao/manutencaoclasse_transfAluno.php';
    } elseif (@$_POST['exclui_aluno'] == "Excluir Matricula do Aluno") {
        include ABSPATH . '/views/gestao/manutencaoclasse_excluir.php';
    }
}
?>
<div class="fieldBody" style="font-size: 16px; width: 100%">
    <div class="fieldTop">
        Manutenção da Classe
    </div>
    <div class="row">
        <div class="col-md-3">
            <?php formulario::select('turma', $turmaOptions, 'Selecione Código da Classe:', @$id_turma, 1) ?>
        </div>
    </div>
    <br /><br />
    <?php
    if (!empty($id_turma)) {
        ?>
        <!--
        link para a vida escolar do aluno
        -->
        <form action="<?php echo HOME_URI ?>/gestao/cadaluno" id="pessoa" method="POST">
            <input type="hidden" name="aba" value="esc" />
            <input id="ps" type="hidden" name="id_pessoa" value="" />
        </form>
        <script>
            function lin(idpessoa) {
                document.getElementById("ps").value = idpessoa;
                document.getElementById("pessoa").submit();
            }
        </script>

        <div id="principal1" class="container" style="display: <?php //echo !empty($_POST['id_pessoa_chamada'])||!empty($_POST['atz_chamada_m'])||!empty($_POST['matricular_aluno'])? @$_POST['display']:''        ?>">
            <form method="POST">
                <div class ="row">
                    <div class ="col-md-8">
                        <div style="width: 100%;overflow: auto">
                            <?php
                            foreach ($classe as $k => $v) {
                                $classe[$k]['n_pessoa'] = '<a href="#" onclick="lin(\'' . $v['id_pessoa'] . '\')" id="nome" style="color: blue">' . $v['n_pessoa'] . '</a>';
                                $classe[$k]['tur'] = '<input type="radio" name="id_turma_aluno" value="' . $v['id_turma_aluno'] . '" />';
                            }
                            $form['array'] = $classe;
                            $form['fields'] = [
                                '||1' => 'tur',
                                'RSE' => 'id_pessoa',
                                'Ch' => 'chamada',
                                'Nome Aluno' => 'n_pessoa',
                                'Situação' => 'situacao'
                            ];
                            tool::relatSimples1($form);
                            ?>
                        </div>
                    </div>
                    <input type="hidden" name="display" value="none" />
                    <div class ="col-md-4">
                        Clique na opção desejada

                          <div style="display: none">                   
                            <input type="hidden" name="turma" value="<?php echo $_POST['turma'] ?>" /> 
                            <input style="width: 100%;" class="art-button" type="submit" name="atz_chamada_a" value="Alterar Chamada Automática" />
                        </div>
                        <br />
                        <div>
                            <input style="width: 100%;" class="art-button" type="submit" name="atz_chamada_m" value="Alterar Chamada Manual" />
                        </div>
                        <br />
                        <div>                    
                            <input style="width: 100%;" class="art-button" type="submit" name="matricular_aluno" value="Matricular Aluno" />
                        </div> 
                        <br />
                        <div>  
                            <input style="width: 100%;" class="art-button" type="<?php echo $button ?>" name="transf" value="<?php echo $valor ?>" />
                        </div>      
                        <br />
                        <div>                    
                            <input style="width: 100%;" class="art-button" type="submit" name="mudar" value="Mudar Situação" />
                        </div> 
                        <br />
                        <div style="display: ">
                            <input style="width: 100%;" class="art-button" type="submit" name="exclui_aluno" value="Excluir Matricula do Aluno" /> 
                        </div> 
                        <br />
                        <div>
                            <input style="width: 100%;" class="art-button" type="submit" name="recla" value="Reclassificar" /> 
                        </div>
                        <br />
                        <div>
                            <input style="width: 100%;" class="art-button" type="submit" name="rem" value="Remanejar" /> 
                        </div> 
                    </div>
                </div>
            </form>   
        </div>

        <?php
    }
    ?>

</div>

