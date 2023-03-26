<?php
$sql = "SELECT * FROM `ge_prontuario` where fk_id_pessoa = " . @$id_pessoa;
$query = $model->db->query($sql);
$prontu = $query->fetch();

?>
<br /><br /><br />
<div class="panel panel-default">
    <div class="panel panel-heading">
        Entrega de Documentos
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <form method="POST">
                    <table class="table table-bordered table-striped table-hover" style="font-size: 16px" >
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-md-3">
                                        Histórico
                                    </div>
                                    <div class="col-md-2">
                                        <label>
                                            <input <?php echo @$prontu['historico'] == 1 ? 'checked' : '' ?> type="radio" name="1[historico]" value="1" />
                                            Sim
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label>
                                            <input <?php echo @$prontu['historico'] == 0 ? 'checked' : '' ?> type="radio" name="1[historico]" value="" />
                                            Não
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-md-3">
                                        Cert. Nasc.
                                    </div>
                                    <div class="col-md-2">
                                        <label>
                                            <input <?php echo @$prontu['certidao'] == 1 ? 'checked' : '' ?> type="radio" name="1[certidao]" value="1" />
                                            Sim
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label>
                                            <input <?php echo @$prontu['certidao'] == 0 ? 'checked' : '' ?> type="radio" name="1[certidao]" value="" />
                                            Não
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-md-3">
                                        RG
                                    </div>
                                    <div class="col-md-2">
                                        <label>
                                            <input <?php echo @$prontu['rg'] == 1 ? 'checked' : '' ?> type="radio" name="1[rg]" value="1" />
                                            Sim
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label>
                                            <input <?php echo @$prontu['rg'] == 0 ? 'checked' : '' ?> type="radio" name="1[rg]" value="" />
                                            Não
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-md-3">
                                        Endereço
                                    </div>
                                    <div class="col-md-2">
                                        <label>
                                            <input <?php echo @$prontu['endereco'] == 1 ? 'checked' : '' ?> type="radio" name="1[endereco]" value="1" />
                                            Sim
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label>
                                            <input <?php echo @$prontu['endereco'] == 0 ? 'checked' : '' ?> type="radio" name="1[endereco]" value="" />
                                            Não
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-md-3">
                                        Cart. Vacina
                                    </div>
                                    <div class="col-md-2">
                                        <label>
                                            <input <?php echo @$prontu['vacina'] == 1 ? 'checked' : '' ?> type="radio" name="1[vacina]" value="1" />
                                            Sim
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label>
                                            <input <?php echo @$prontu['vacina'] == 0 ? 'checked' : '' ?> type="radio" name="1[vacina]" value="" />
                                            Não
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-md-3">
                                        Doc. Tutela
                                    </div>
                                    <div class="col-md-2">
                                        <label>
                                            <input <?php echo @$prontu['tutela'] == 1 ? 'checked' : '' ?> type="radio" name="1[tutela]" value="1" />
                                            Sim
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label>
                                            <input <?php echo @$prontu['tutela'] == 0 ? 'checked' : '' ?> type="radio" name="1[tutela]" value="" />
                                            Não
                                        </label>
                                    </div>
                                    <div class="col-md-5">
                                        <label>
                                            <input <?php echo @$prontu['tutela'] == 2 ? 'checked' : '' ?> type="radio" name="1[tutela]" value="2" />
                                            Não se aplica
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="col-md-3">
                                        Compr. Trabalho da Mãe
                                    </div>
                                    <div class="col-md-2">
                                        <label>
                                            <input <?php echo @$prontu['mae_trabalha'] == 1 ? 'checked' : '' ?> type="radio" name="1[mae_trabalha]" value="1" />
                                            Sim
                                        </label>
                                    </div>
                                    <div class="col-md-2">
                                        <label>
                                            <input <?php echo @$prontu['mae_trabalha'] == 0 ? 'checked' : '' ?> type="radio" name="1[mae_trabalha]" value="" />
                                            Não
                                        </label>
                                    </div>
                                    <div class="col-md-5">
                                        <label>
                                            <input <?php echo @$prontu['mae_trabalha'] == 2 ? 'checked' : '' ?> type="radio" name="1[mae_trabalha]" value="2" />
                                            Não se aplica
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <div style="text-align: center; width: 100%">

                        <input type="hidden" name="aba" value="pront" />
                        <input type="hidden" name="activeNav" value="5" />

                        <input type="hidden" name="id_pessoa" value="<?php echo @$id_pessoa ?>" />
                        <input type="hidden" name="1[id_pront]" value="<?php echo @$prontu['id_pront'] ?>" />
                        <input type="hidden" name="1[fk_id_pessoa]" value="<?php echo @$id_pessoa ?>" />
                        <?php echo DB::hiddenKey('ge_prontuario', 'replace') ?>
                        <button type="submit" class="btn btn-success">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
            </div>
        </div>

    </div>
</div>
