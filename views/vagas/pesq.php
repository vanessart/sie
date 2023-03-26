<?php
if (empty($_POST['pesq'])) {
    $id_inst = tool::id_inst();
} else {
    $id_inst = $_POST['id_inst'];
    $status = $_POST['status'];
    $seriacao = $_POST['seriacao'];
    @$n_aluno = $_POST['n_aluno'];
    @$trab = $_POST['trab'];
}
?>

<div>
    <div class="fieldTop">
        <br />
        Pesquisa
        <br />
    </div>
</div>
<div class="fieldWhite">
    <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <?php
                $options = escolas::idInst('%3%', 'fk_id_tp_ens');
                $options1[''] = 'Todos';
                foreach ($options as $kk => $vv) {
                    $options1[$kk] = $vv;
                }
                echo formulario::select('id_inst', $options1, 'Escola', @$id_inst)
                ?>
            </div>
            <div class="col-md-3">
                <?php
                echo formulario::select('status', $model->status(), 'Status', @$status);
                ?>
            </div>
            <div class="col-md-3">
                <?php echo formulario::select('seriacao', $model->seriacao(), 'Seriação', @$seriacao) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <?php echo formulario::input('n_aluno', 'Nome ou Inscrição', NULL, @$n_aluno) ?>
            </div>
            <div class="col-md-3">
                <?php echo formulario::checkbox('trab', 1, 'Resp. Trabalha', @$trab) ?>
            </div>
            <div class="col-md-2 text-center">
                <a href="">
                    <button name="pesq2" type="button" class="btn btn-danger" style="width:150px">
                        Limpar
                    </button>
                </a>
            </div>
            <div class="col-md-2 text-center">
                <button name="pesq" value="1" type="submit" class="btn btn-success" style="width:150px">
                    Pesquisar
                </button>
            </div>
            <div class="col-md-2 text-center">
                <?php
                if (!empty($_POST['pesq'])) {
                    ?>
                    <input onclick="document.getElementById('sql').submit();" class="btn btn-info" style="width:150px" type="button" value="Exportar" />
                    <?php
                } else {
                    ?>
                    <button class="btn btn-default" style="width:150px">
                        Exportar
                    </button>
                    <?php
                }
                ?>
            </div>
        </div>
    </form>
    <div class="fieldWhite">
        <?php
        $sql = $model->pesq(@$id_inst, @$status, @$seriacao, @$n_aluno, @$trab);
        ?>
    </div>
    <form id="sql" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
        <input type="hidden" name="sql" value="<?php echo $sql ?>" />
        <input type="hidden" name="tokenSql" value="<?php echo substr((date("yhdm") / 3.5288 * 68), 0, 20); ?>" />
    </form>
</div>
