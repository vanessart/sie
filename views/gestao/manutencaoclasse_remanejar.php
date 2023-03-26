<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#myModal").modal('show');
    });
</script>
<div id="myModal" class="modal fade">
    <div class="modal-dialog" style="width: 90%; text-align: center; font-size: 20px">
        <div class="modal-content">

            <div class="modal-body">
                <div style="text-align: right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">X</button>
                </div>
                <?php
                if (!empty($_POST['id_pessoa_chamada'])) {

                    $dados = explode('|', $_POST['id_pessoa_chamada']);
                    $serie = substr($dados[3], 3, 1);
                    $nome = $dados[2];

                    $cod = turmas::option($serie);
                    unset($cod[$_POST['turma']])
                    ?>
                    <br /><br />
                    <form name="codturma" method="POST">
                        <?php echo $nome ?>
                        <br /><br />
                        <div class="input-group">
                            <div class="input-group-addon">
                                Selecione Código da Classe para Remanejar o Aluno:
                            </div>
                            <div class="input-group-addon">
                                <select name="opt">
                                    <option></option>
                                    <?php
                                    foreach ($cod as $k => $a) {
                                        ?>                                         
                                        <option value ="<?php echo $k . '|' . $a ?>">
                                            <?php echo $a ?>
                                        </option>                                        
                                        <?php
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>
                        <br /><br />
                        <?php formulario::input('obs', 'Observação') ?>
                        <br /><br />
                        <input style="width: 46%; font-weight: 900" class="art-button" type="submit" name="remanejar" value="Salvar" />                      

                        <input type="hidden" name="al" value="<?php echo $_POST['id_pessoa_chamada'] ?>" /> 
                        <input type="hidden" name="turma" value="<?php echo $_POST['turma'] ?>" />
                    </form>

                    <?php
                } else {
                    ?>
                    <div style="text-align: center; font-size: 20px">
                        Favor selecionar aluno
                    </div>
                    <?php
                }
                ?>
            </div>

        </div>
    </div>
</div>