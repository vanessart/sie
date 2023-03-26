<table  class="table table-bordered table-condensed table-responsive table-striped" style="font-weight: bold">
    <tr>
        <td style="text-align: center; ">
            Ações
        </td>
    </tr>
    <?php
     if (NULL) {
//   if ($dados['status'] == 'Deferido') {
        ?>
        <tr>
            <td  style="text-align: center; font-weight: bold">
                <br /><br />
                <form method="POST">
                    <?php echo DB::hiddenKey('matricular') ?>
                    <input type="hidden" name="aba" value="1" />
                    <input type="hidden" name="id_vaga" value="<?php echo $dados['id_vaga'] ?>" />
                    <input class="btn btn-success" style="width: 50%;margin: 0 auto" type="submit" value="Matricular" />
                </form>
            </td>
        </tr>
        <?php
    }
    if ($dados['status'] != 'Deferido' && $dados['status'] != 'Pendente' && tool::id_inst() == $dados['fk_id_inst']) {
        if (in_array($dados['status'], $model->statusEdit())) {
            ?>
            <tr>
                <td  style="text-align: center; font-weight: bold">
                    <br /><br />
                    <form method="POST">
                        <?php echo formulario::hidden($sqlkey) ?>
                        <input type="hidden" name="aba" value="0" />
                        <input type="hidden" name="id_vaga" value="<?php echo $dados['id_vaga'] ?>" />
                        <input type="hidden" name="1[id_vaga]" value="<?php echo $dados['id_vaga'] ?>" />
                        <input type="hidden" name="1[status]" value="Edição" />
                        <input class="btn btn-danger" style="width: 50%;margin: 0 auto" type="submit" value="Editar Inscrição" />
                    </form>
                </td>
            </tr>
            <?php
        } elseif($dados['status'] <> 'Matriculado') {
            ?>
            <tr>
                <td  style="text-align: center; font-weight: bold">
                    <?php
                    if ($valida == 1) {
                        ?>
                        <br /><br />
                        <form method="POST">
                            <?php echo formulario::hidden($sqlkey) ?>
                            <input type="hidden" name="aba" value="1" />
                            <input type="hidden" name="id_vaga" value="<?php echo $dados['id_vaga'] ?>" />
                            <input type="hidden" name="1[id_vaga]" value="<?php echo $dados['id_vaga'] ?>" />
                            <input type="hidden" name="1[status]" value="Aguardando Deferimento" />
                            <input type="hidden" name='1[seriacao]' value="<?php echo $seriacao ?>" />
                            <input class="btn btn-success" style="width: 50%;margin: 0 auto" type="submit" name="concluiu" value="Concluir a Inscrição" />
                        </form>
                        <?php
                    } else {
                        ?>
                        <br /><br />
                        <input class="btn btn-danger" style="width: 50%;margin: 0 auto;" type="button" value="Ainda Não Pode Ser Concluido" />
                        <br /><br />
                        <form method="POST">
                            <input type="hidden" name="aba" value="0" />
                            <input type="hidden" name="id_vaga" value="<?php echo $dados['id_vaga'] ?>" />
                            <input class="btn btn-warning" style="width: 50%;margin: 0 auto" type="submit" value="Editar Inscrição" />
                        </form>
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
    } elseif ($dados['status'] == 'Cancelado') {
        if (in_array($dados['status'], $model->statusEdit())) {
            ?>
            <tr>
                <td  style="text-align: center; font-weight: bold">
                    <br /><br />
                    <form method="POST">
                        <?php echo formulario::hidden($sqlkey) ?>
                        <input type="hidden" name="aba" value="0" />
                        <input type="hidden" name="id_vaga" value="<?php echo $dados['id_vaga'] ?>" />
                        <input type="hidden" name="1[id_vaga]" value="<?php echo $dados['id_vaga'] ?>" />
                        <input type="hidden" name="1[status]" value="Edição" />
                        <input class="btn btn-danger" style="width: 50%;margin: 0 auto" type="submit" value="Editar Inscrição" />
                    </form>
                </td>
            </tr>
            <?php
        }
    }
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
    if (in_array($dados['status'], $model->statusCancelar()) && tool::id_inst() == $dados['fk_id_inst']) {
        ?>
        <tr>
            <td  style="text-align: center; font-weight: bold">
                <br /><br />
                <input  onclick=" $('#myModal').modal('show');"  class="btn btn-danger" style="width: 50%;margin: 0 auto" type="button" value="Cancelar Solicitação" />
            </td>
        </tr>
        <?php
    }
    ?>

</table>
<?php
tool::modalInicio(NULL, 1);
?>
<br /><br />
<form style="text-align: center"  method="POST">
    <?php echo formulario::hidden($sqlkey) ?>
    <input type="hidden" name="aba" value="1" />
    <input type="hidden" name="id_vaga" value="<?php echo $dados['id_vaga'] ?>" />
    <input type="hidden" name="1[id_vaga]" value="<?php echo $dados['id_vaga'] ?>" />
    <div style="text-align: left">
        Motivo do cancelamento:
    </div>
    <br />
    <input type="text" required style="width: 100%" name="1[obs_cancela]" />
    <input type="hidden" name="1[status]" value="Cancelado" />
    <br /><br /><br />  
    <input class="btn btn-danger" type="submit" name="cancela" value="Cancelar Inscrição" />
</form>
<?php
tool::modalFim();
