<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#myModal").modal('show');
    });
</script>
<div id="myModal" class="modal fade">
    <div class="modal-dialog" style="width: 90%">
        <div class="modal-content">

            <div class="modal-body">
                <div style="text-align: right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">X</button>
                </div>
                <?php
                if (!empty($_POST['id_turma_aluno'])) {
                    $dados = sql::get(['pessoa', 'ge_turma_aluno', 'ge_turmas'], ' ge_turmas.codigo, n_pessoa, id_pessoa, ge_turma_aluno.chamada, ge_turma_aluno.situacao, ge_turmas.id_turma', ['id_turma_aluno' => $_POST['id_turma_aluno']], 'fetch');
                    extract($dados);
                   
                    ?>
                    <form  method="POST">
                        <label><?php echo $n_pessoa ?></label>
                            <div>
                                <?php formulario::select('id_turma', $cod, 'Selecione Código da Classe para '.$Reclassificar.' o Aluno:', NULL, NULL, NULL, 'required')  ?>
                            </div>
             
                        <br />
                        <div style="padding-left: 40px">
                            <?php echo DB::hiddenKey('reclassificado') ?>
                            <input type="hidden" name="situacao" value="<?php echo $Reclassificado ?>" />
                            <input type="hidden" name="id_turma_aluno" value="<?php echo $_POST['id_turma_aluno'] ?>" />
                            <input style="width: 45.5%; font-weight: 900" class="art-button" type="submit" value="Salvar" />                      
                        </div>

                        <input type="hidden" name="turma" value="<?php echo $_POST['turma'] ?>" />
                    </form>
                    <?php
                } else {
                    ?>
                    <div style="font-size: 20px; text-align: center">
                        Favor selecionar aluno
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>       

    </div>
</div>