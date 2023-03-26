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
            <?php if (!empty($_POST['id_sel'])) { ?>
                <input class="btn btn-info" type="submit" onclick=" $('#myModal').modal('show');" value="Novo Cargo" />
            <?php } ?>
        </div>
    </div>

    <br /><br /><br />
    <?php
    if (!empty($_POST['id_sel'])) {
        $sql = "SELECT * FROM `sel_cargo` "
                . " WHERE `fk_id_sel` = " . $_POST['id_sel']
                . " order by ordem ";
        $query = $model->db->query($sql);
        $car = $query->fetchAll();
        $sqlkey = DB::sqlKey('sel_cargo', 'delete');
        foreach ($car as $k => $v) {
            $v['form'] = 1;
            $v['id_sel'] = $v['fk_id_sel'];
            $car[$k]['acess'] = formulario::submit('Editar', NULL, $v);
            $car[$k]['del'] = formulario::submit('Apagar', $sqlkey, ['1[id_cargo]' => $v['id_cargo'], 'id_sel'=>$v['fk_id_sel']]);
        }
        $form['array'] = $car;
        $form['fields'] = [
            'Cargo' => 'n_cargo',
            'Sigla' => 'sg_cargo',
            'Ativo' => 'at_cargo',
            'Ordem' => 'ordem',
            //'||1' => 'del',
            '||2' => 'acess'
        ];

        tool::relatSimples($form);
    }
    if (!empty($_POST['form'])) {
        $modal = Null;
    } else {
        $modal = 1;
    }
    tool::modalInicio('width: 95%', $modal);
    ?>
    <br /><br />
    <form method="POST">
        <div class="row">
            <div class="col-md-12">
                <?php formulario::input('1[n_cargo]', 'Cargo') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-4">
                <?php formulario::input('1[sg_cargo]', 'Sigla') ?>
            </div>
            <div class="col-md-4">
                <?php formulario::input('1[ordem]', 'Ordem') ?>
            </div>
            <div class="col-md-4">
                <?php formulario::select('1[at_cargo]', [1=>'Sim',0=> 'Não'], 'Ativo') ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-6 text-center">
                <input class="btn btn-info" type="button" onclick="document.getElementById('form').submit();" value="Limpar" />
            </div>
            <div class="col-md-6 text-center">
                <?php echo DB::hiddenKey('sel_cargo', 'replace') ?>
                <input type="hidden" name="1[id_cargo]" value="<?php echo $_POST['id_cargo'] ?>" />
                <input type="hidden" name="id_sel" value="<?php echo $_POST['id_sel'] ?>" />
                <input type="hidden" name="1[fk_id_sel]" value="<?php echo $_POST['id_sel'] ?>" />
                <input class="btn btn-success" type="submit" value="Salvar" />
            </div>
        </div>
        <br /><br />
    </form>
    <form id="form"  method="POST">
        <input type="hidden" name="id_sel" value="<?php echo $_POST['id_sel'] ?>" />
        <input type="hidden" name="form" value="1" />
    </form>
    <?php
    tool::modalFim();
    ?> 

</div>