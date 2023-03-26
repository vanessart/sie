<div class="fieldBody">
    <div class="fieldTop">
        Entrega de Documentos
    </div>
    <br /><br />
    <?php
    $id_cate = @$_REQUEST['id_cate'];
    ?>
    <div class="row">
        <div class="col-sm-8">
            <?php echo formulario::selectDB('giz_categoria', 'id_cate', 'Categoria', NULL, NULL, 1) ?>
        </div>
        <div class="col-sm-4 text-center">
            <?php
            if (!empty($id_cate)) {
                ?>
                <form method="POST">
                    <input type="hidden" name="id_cate" value="<?php echo $id_cate ?>" />
                    <input class="btn btn-warning" type="submit" value="Atualizar" />
                </form>
                <?php
            }
            ?>
        </div>
    </div>
    <br /><br />
    <?php
    if (!empty($id_cate)) {
        $prof = $model->inscricoes($id_cate, 'fk_id_cate', 1, 'p.n_pessoa, p.id_pessoa, pf.entr_proj, pf.entr_port, pf.entr_midia, entr_prot, entr_hist, entr_gest ');
        ?>

        <?php
        foreach ($prof as $k => $v) {
            ?>
            <form target="_blank" action="<?php echo HOME_URI ?>/giz/protentrega" method="POST">
                <table class="table table-bordered table-hover table-striped">
                    <tr>
                        <td style="width: 40%">
                            <?php echo $v['n_pessoa'] ?>
                        </td>
                        <td <?php echo $v['entr_hist'] == 1 ? 'style="background-color: greenyellow "' : '' ?>>
                            <label>
                                <input type="hidden" name="entr_hist" value="" />
                                <input <?php echo $v['entr_hist'] == 1 ? 'checked' : '' ?> type="checkbox" name="entr_hist" value="1" />
                                Histórico/Ausência
                            </label>
                        </td>
                         <td <?php echo $v['entr_gest'] == 1 ? 'style="background-color: greenyellow "' : '' ?>>
                            <label>
                                <input type="hidden" name="entr_gest" value="" />
                                <input <?php echo $v['entr_gest'] == 1 ? 'checked' : '' ?> type="checkbox" name="entr_gest" value="1" />
                                Acompanhamento/Gestão
                            </label>
                        </td>
                         <td <?php echo $v['entr_prot'] == 1 ? 'style="background-color: greenyellow "' : '' ?>>
                            <label>
                                <input type="hidden" name="entr_prot" value="" />
                                <input <?php echo $v['entr_prot'] == 1 ? 'checked' : '' ?> type="checkbox" name="entr_prot" value="1" />
                                Protocolo
                            </label>
                        </td>
                         <td <?php echo $v['entr_proj'] == 1 ? 'style="background-color: greenyellow "' : '' ?>>
                            <label>
                                <input type="hidden" name="entr_proj" value="" />
                                <input <?php echo $v['entr_proj'] == 1 ? 'checked' : '' ?> type="checkbox" name="entr_proj" value="1" />
                                Escopo/projeto
                            </label>
                        </td>
                        <td <?php echo $v['entr_port'] == 1 ? 'style="background-color: greenyellow "' : '' ?>>
                            <label>
                                <input type="hidden" name="entr_port" value="" />
                                <input <?php echo $v['entr_port'] == 1 ? 'checked' : '' ?> type="checkbox" name="entr_port" value="1" />
                                Portfolio
                            </label>
                        </td>
                        <td <?php echo $v['entr_midia'] == 1 ? 'style="background-color: greenyellow "' : '' ?>>
                            <label>
                                <input type="hidden" name="entr_midia" value="" />
                                <input <?php echo $v['entr_midia'] == 1 ? 'checked' : '' ?> type="checkbox" name="entr_midia" value="1" />
                                Mídia
                            </label>
                        </td>
                        <td>
                            <input type="hidden" name="id_pessoa" value="<?php echo $v['id_pessoa'] ?>" />
                            <input class="btn btn-info" type="submit" value="Protocolo" />
                        </td>
                    </tr>
                </table>
            </form>

            <?php
        }
        ?>

        <?php
    }
    ?>
</div>

