<?php
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$escolas = $model->anos123();
if ($id_inst) {
    $form = $model->prof($id_inst);
}
$cor[1] = 'wheat';
$cor[2] = 'lightskyblue';
?>
<div class="row">
    <div class="col-sm-6">
        <?php formulario::select('id_inst', $escolas, 'Escola', $id_inst, 1, ['activeNav' => $activeNav]) ?>
    </div>
</div>
<br /><br />
<?php
if ($id_inst) {
    ?>
    <form method="POST">
        <table class="table table-bordered table-hover table-striped" style="width: 800px">
            <tr style="background-color: black; color: white">
                <td>
                    Classe
                </td>
                <td>
                    Professores
                </td>
                <td style="width: 50px">
                    Nota
                </td>
            </tr>
            <?php
            $c = 0;
            foreach ($form as $k => $v) {
                ?>
                <tr style="background-color: <?php echo $cor[$c++ % 2] ?>">
                    <td>
                        <?php echo $v['n_turma'] ?>
                    </td>
                    <td>
                        <?php echo $v['n_pessoa'] ?>
                    </td>
                    <td>
                        <input style="width: 50px" type="text" name="n[<?php echo $k ?>]" value="<?php echo str_replace('.', ',', @$v['nota']) ?>" />
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <br /><br />
        <div style="text-align: center">
            <?php echo DB::hiddenKey('nota123') ?>
            <input type="hidden" name="activeNav" value="<?php echo $activeNav ?>" />
            <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
            <input class="btn btn-success" type="submit" value="Salvar" />
        </div>
    </form>
    <?php
}
