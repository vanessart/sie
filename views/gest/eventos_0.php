<br /><br />
<div class="row">
    <div class="col-md-12">
        <form name="id_turmaForm" method="POST">
            <input type="hidden" name="id_eve" value="<?php echo $id_eve ?>" />
            <input type="hidden" name="tabClass" value="<?php echo $tabClass ?>" />

            <?php formulario::select('id_turma', turmas::option(), 'Selecione uma Classe:') ?>
        </form>
    </div>
</div>
<?php
if (!empty($id_turma)) {
    ?>
    <br /><br />
    <div class="row">
        <div class="col-md-6" style="padding: 5px;">
            <div class="rowField" style="min-height: 50vh; width: 98%">
                <table class="table table-striped table-hover" style="font-weight: bold">

                    <?php
                    if(!empty($_POST[1])){
                        $model->db->ireplace('ge_envento_aluno', $_POST[1], 1);
                    }
                    
                    @$classe = turmas::classe($id_turma);
                    foreach ($classe as $v) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $v['id_pessoa'] ?>
                            </td>
                            <td>
                                <?php echo $v['n_pessoa'] ?>
                            </td>
                            <td style="text-align: center">
                                <form method="POST">
                                    <input type="hidden" name="1[fk_id_pessoa]" value="<?php echo $v['id_pessoa'] ?>" />
                                    <input type="hidden" name="1[fk_id_eve]" value="<?php echo $id_eve ?>" />
                                    <input type="hidden" name="id_eve" value="<?php echo $id_eve ?>" />
                                    <input type="hidden" name="tabClass" value="<?php echo $tabClass ?>" />
                                    <input type="hidden" name="id_turma" value="<?php echo @$id_turma ?>" />
                                    <button type="submit">
                                        <span class="glyphicon glyphicon-forward" aria-hidden="true"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>

            </div>
        </div>
        <div class="col-md-6" style="padding: 5px;">
            <div class="rowField" style="min-height: 50vh; width: 98%">
               $dentro
            </div>
        </div>
    </div>
    <?php
}
?>
