<table  style="font-weight: bold; width: 100%">
    <?php
    $can = $_POST['id_vaga'];
    if (tool::id_inst() == $dados['fk_id_inst']) {
        ?>
        <tr>
            <td  style="text-align: center; font-weight: bold">
                <div class="fieldrow3">
                    <div class="fieldTop">
                        Análise da solicitação de inscrição da criança
                        <br /><br />
                        <strong>
                            <?php echo $dados['n_aluno'] ?>
                        </strong>
                    </div>
                    <br /><br />
                    <form method="POST">
                        <?php echo formulario::hidden($sqlkey) ?>
                        <input type="hidden" name="aba" value="2" />
                        <input type="hidden" name="id_vaga" value="<?php echo $dados['id_vaga'] ?>" />
                        <input type="hidden" name="1[id_vaga]" value="<?php echo $dados['id_vaga'] ?>" />
                        <input type="hidden" name='1[seriacao]' value="<?php echo $seriacao ?>" />
                        <?php
                        if ($dados['status'] == 'Matriculado') {
                            ?>
                            <div class="alert alert-success text-center" style="font-size: 18px">
                                Matriculado
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="input-group" style="width: 100px; margin: 0 auto">
                                <span class="input-group-addon">
                                    Status:
                                </span>
                                <select required name="1[status]" onchange="if (this.options[this.selectedIndex].text != 'Deferido') {
                                            document.getElementById('obs').required = true
                                        }">
                                    <option></option>
                                    <?php
                                    if ($dados['status'] == 'Aguardando Deferimento' || $dados['status'] == 'Pendente') {
                                        ?>
                                        <option <?php echo $dados['status'] == 'Deferido' ? 'disabled selected' : '' ?> >Deferido</option>
                                        <option <?php echo $dados['status'] == 'Indeferido' ? 'disabled selected' : '' ?>>Indeferido</option>
                                        <option <?php echo $dados['status'] == 'Pendente' ? 'disabled selected' : '' ?>>Pendente</option>
                                        <?php
                                    }
                                    ?>
                                    <option <?php echo $dados['status'] == 'Cancelado' ? 'disabled selected' : '' ?>>Cancelado</option>
                                    <option <?php echo $dados['status'] == 'Matriculado' ? 'disabled selected' : '' ?>>Matriculado</option>
                                </select>
                            </div>
                            <br /><br />
                            <input type="hidden" name="mudaStatusSet" value="1" />
                            <textarea placeholder="Obrigatório para os status Indeferido, Pendente e Cancelado"  id="obs" name="1[obs]" style="width: 100%; height: 50px; padding: 5px"><?php echo $dados['obs'] ?></textarea>
                            <br /><br />
                            <input class="btn btn-success"  type="submit" name="deferir"n value="Salvar" />
                            <br /><br /><br />
                            <?php
                        }
                        ?>
                    </form>
                    <?php
                    If ($dados['status'] == 'Matriculado') {
                        ?>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal<?php echo $can ?>">
                            Cancelar Inscrição
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="myModal<?php echo $can ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" style="color: red" id="myModalLabel">Cancelar Inscrição</h4>
                                    </div>
                                    <form method="POST">
                                        <div class="modal-body">

                                            <table class="table table-bordered table-hover table-striped">
                                                <tr>
                                                    <th>
                                                        <b>Antes de Cancelar a Inscrição Favor Verificar:<br />
                                                            - Situação da Criança no SIEB;<br />
                                                            - Situcação da Criança no SED;<br />
                                                            - Verificar se não existe inscrição pendente na Rede.<br />
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Observação:
                                                        <input id = "obs_cancela" type="text" name='obs_cancela' value="<?php echo @$cancela ?>" required placeholder= "Digite aqui"/>
                                                    </td>
                                                </tr>
                                            </table>
                                            <div class="modal-footer">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <button name="salvacan" value="1" type="submit" class="btn btn-success" style="width:150px">
                                                            Salvar
                                                        </button>
                                                        <input type="hidden" name="id_vaga" value="<?php echo $can ?>" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button type="button" class="btn btn-primary" data-dismiss="modal" style="width:150px">Fechar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!-- end modal -->
                        <?php
                    }
                    ?>
                </div>
            </td>
        </tr>
        <?php
    } else {
        ?>
        <tr>
            <td>
                <form id="px" style="text-align: center" method="POST">
                    <?php echo formulario::hidden($sqlkey) ?>
                    <input type="hidden" name="aba" value="2" />
                    <input type="hidden" name="id_vaga" value="<?php echo $dados['id_vaga'] ?>" />
                    <input type="hidden" name="1[id_vaga]" value="<?php echo $dados['id_vaga'] ?>" />
                    <input type="hidden" name='1[seriacao]' value="<?php echo $seriacao ?>" />
                    <input type="hidden" name='1[fk_id_inst]' value="<?php echo tool::id_inst() ?>" />
                    <?php
                    if ($dados['status'] != 'Matriculado') {
                        ?>

                        <br /><br />
                        <input onclick="if (confirm('Tem certeza? Isso colocará este aluno na sua lista.')) {
                                    document.getElementById('px').submit()
                                }
                                ;" class="btn btn-success"  type="button" value="Puxar o Aluno para Minha Escola" />
                        <br /><br /><br />
                        <?php
                    }
                    ?>
                </form>
            </td>
        </tr>
        <?php
    }
    if ($dados['status'] <> 'Matriculado') {
        ?>
        <tr>
            <td style="text-align: center; ">
                Outras Ações
            </td>
        </tr>
        <?php
        if (in_array($dados['status'], $model->statusProtocolo())) {
            ?>
            <tr>
                <td  style="text-align: center; font-weight: bold">
                    <br /><br />
                    <form target="_blank" action="<?php echo HOME_URI ?>/vagas/protocolo" method="POST">
                        <input type="hidden" name="id_vaga" value="<?php echo $dados['id_vaga'] ?>" />
                        <input class="btn btn-warning" name="" style="width: 50%;margin: 0 auto" type="submit" value="Gerar Protocolo" />
                    </form>
                </td>
            </tr>
            <tr>
                <td  style="text-align: center; font-weight: bold">
                    <br /><br />
                    <form target="_blank" action="<?php echo HOME_URI ?>/vagas/resumo" method="POST">
                        <input type="hidden" name="id_vaga" value="<?php echo $dados['id_vaga'] ?>" />
                        <input  class="btn btn-warning" style="width: 50%;margin: 0 auto" type="submit" value="Imprimir Resumo" />
                    </form>
                </td>
            </tr>
            <?php
        }
    }
    ?>

</table>
<br /><br /><br /><br />

