<?php
$sql = "SELECT * FROM `sel_seletiva`";
$query = $model->db->query($sql);
$sel = $query->fetchAll();
foreach ($sel as $v) {
    $selOption[$v['id_sel']] = $v['n_sel'];
}
?>
<div class="fieldBody">
    <div class="row">
        <div class="col-md-6">
            <?php
            formulario::select('id_sel', $selOption, 'Selecione o Processo Seletivo', @$_POST['id_sel'], 1);
            ?>  
        </div>
        <div class="col-md-6">

        </div>
    </div>
    <?php
    if (!empty($_POST['id_sel'])) {
        ?>
        <br /><br /><br />
        <div class="row">
            <div class="col-md-6 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/sel/listaparede" method="POST">
                    <input type="hidden" name="id_sel" value="<?php echo $_POST['id_sel'] ?>" />
                    <input class="btn btn-info" type="submit" value="Lista de Parede" />
                </form>
            </div>
            <div class="col-md-6 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/sel/listaassina" method="POST">
                    <input type="hidden" name="id_sel" value="<?php echo $_POST['id_sel'] ?>" />
                    <input class="btn btn-info" type="submit" value="Lista de Assinaturas" />
                </form>
            </div>
        </div>
        <?php
    }
    ?>
</div>