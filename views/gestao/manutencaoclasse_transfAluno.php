<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#myModal").modal('show');
    });
</script>
<div id="myModal" class="modal fade">
    <div class="modal-dialog" style="width: 90%; font-size: 20px">
        <div class="modal-content">

            <div class="modal-body">
                <div style="text-align: right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">X</button>
                </div>
                <?php
                if (!empty($_POST['id_turma_aluno'])) {
                    $dados = sql::get(['pessoa', 'ge_turma_aluno', 'ge_turmas'], 'n_pessoa, id_pessoa, ge_turmas.fk_id_ciclo,  ge_turmas.codigo, ge_turma_aluno.chamada, ge_turmas.periodo_letivo, ge_turmas.fk_id_inst, ge_turma_aluno.situacao, ge_turmas.id_turma', ['id_turma_aluno' => $_POST['id_turma_aluno']], 'fetch');
                    extract($dados);
                    if ($model->verificaalunotrans($id_pessoa) == "Pendente") {
                        ?>
                        <div class="text-center">
                            Aluno com Transferência Pendente
                        </div>
                        <?php
                    } else {
                        //escolas possiveis para transferencia
                        $escolasPossiveis = $model->escolaciclo($fk_id_inst, $fk_id_ciclo);
                        if ($situacao == "Frequente") {
                            ?>
                            <table class="table table-bordered table-striped" > 
                                <thead>
                                <label>Dados do Aluno</label>
                                <tr>
                                    <td>
                                        RSE
                                    </td>
                                    <td>
                                        Cód.Classe
                                    </td>
                                    <td>
                                        Chamada
                                    </td>
                                    <td>
                                        Nome Aluno
                                    </td>
                                    <td>
                                        Situação
                                    </td>
                                </tr>

                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <?php echo $id_pessoa ?>
                                        </td>
                                        <td>
                                            <?php echo $codigo ?>
                                        </td>
                                        <td>
                                            <?php echo $chamada ?>
                                        </td>
                                        <td>
                                            <?php echo $n_pessoa ?>
                                        </td>
                                        <td>
                                            <?php echo $situacao ?>
                                        </td>
                                    </tr>
                                </tbody>

                            </table>

                            <div style="text-align: center">
                                Tipo de Transferência
                                <br /><br />
                                <input type="button" class="art-button" style= "width: 20%" value="Transferência na Rede Municipal" onclick="document.getElementById('rede').style.display = '';document.getElementById('redef').style.display = 'none';"/>
                                <input type="button" class="art-button" style= "width: 22%" value="Transferência Fora da Rede" onclick="document.getElementById('redef').style.display = '';document.getElementById('rede').style.display = 'none';"/>
                                <br /><br />
                            </div>
                            <input type="hidden" name="turma" value="<?php echo $_POST['turma'] ?>" />              
                            <div style="height: 250px">
                                <form method="POST">
                                    <div id="rede"  style="height: 220px; display: none">   
                                        <div style="text-align: center">
                                            Solicitação de Transferência
                                        </div>
                                        <br />
                                        <br />
                                        <label><?php echo $n_pessoa ?></label>
                                        <br />
                                        <label><?php echo "RSE: " . $id_pessoa . " Cód. Classe: " . $codigo . " chamada nº. : " . $chamada ?></label>
                                        <br />
                                        <br />
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                Selecione Escola 
                                                <br />
                                            </div>
                                            <div>
                                                <select required name="op">
                                                    <option></option>
                                                    <?php
                                                    foreach ($escolasPossiveis as $a) {
                                                        ?>                                         
                                                        <option value ="<?php echo $a['id_inst'] ?>">
                                                            <?php echo $a['n_inst'] ?>
                                                        </option>                                        
                                                        <?php
                                                    }
                                                    ?>
                                                </select>

                                            </div>
                                        </div>
                                        <br />
                                        <div style="text-align: center">
                                            <?php echo DB::hiddenKey('transfSolicita') ?>
                                            <input type="hidden" name="turma" value="<?php echo $id_turma ?>" />
                                            <input type="hidden" name="id_turma_aluno" value="<?php echo $_POST['id_turma_aluno'] ?>" /> 
                                            <input type="submit" style="width: 62%" class ="art-button" value="Enviar Solicitação" />
                                        </div>
                                    </div>
                                </form>
                                <div id="redef" style=" display: none">
                                    <div class="fieldTop">
                                        Transferência Fora da Rede Municipal
                                    </div>
                                    <div class="panel panel-body">
                                        <form method="POST">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php formulario::input('1[destino_escola]', 'Informe Escola de Destino') ?>
                                                </div>
                                            </div>
                                            <br />
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <?php formulario::input('1[destino_escola_cidade]', 'Informe Cidade de Destino') ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?php
                                                    $e = sql::get('estados', '*', NULL);
                                                    $uf = tool::idName($e, 'id_estado', 'sigla');
                                                    ?>
                                                    UF: 
                                                    <?php echo formOld::select('1[destino_escola_uf]', $uf, ' required', NULL, NULL, @$dados['uf_nasc']) ?>
                                                </div>
                                            </div>
                                            <br />
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php formulario::input('1[justificativa_transf]', ' Observação') ?>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <?php echo DB::hiddenKey('ge_turma_aluno', 'replace') ?>
                                                <input type="hidden" name="turma" value="<?php echo $id_turma ?>" />
                                                <input type="hidden" name="1[situacao]" value="Transferido Escola" />
                                                <input type="hidden" name="1[tp_destino]" value="TE" />
                                                <input type="hidden" name="1[dt_transferencia]" value="<?php echo date("Y-m-d") ?>" />
                                                <input type="hidden" name="1[id_turma_aluno]" value="<?php echo $_POST['id_turma_aluno'] ?>" />
                                                <br />
                                                <br />
                                                <input type="submit" style="width: 95%" class ="art-button"  value="Transferir Aluno" />
                                            </div>
                                        </form> 
                                    </div>

                                </div>

                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <br />
                            </div>
                            <?php
                        } else {
                            ?>
                            <div style="text-align: center">
                                Selecionar aluno frequente
                                <br /><br />
                            </div>
                            <?php
                        }
                    }
                } else {
                    ?>
                    <div style="text-align: center">
                        Favor selecionar aluno
                        <br /><br />
                    </div>
                    <?php
                }
                ?>
            </div>

        </div>
    </div>
</div>