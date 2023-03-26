
<div class="fieldBody">

    <form method="POST">
        <div class="row">
            <div class="col-md-12">
                <?php formulario::input('nome', 'Logradouro') ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-8">
                <?php
                $options = escolas::idInst();
                formulario::select('fk_id_inst', $options, 'Escola', empty($_POST['fk_id_inst']) ? @tool::id_inst() : $_POST['fk_id_inst']);
                ?>
            </div>
            <div class="col-md-2">
                <input name="busc" class="btn btn-success" type="submit" value="Buscar" />
            </div>
    </form>
    <form method="POST">
        <div class="col-md-2">
            <input class="btn btn-warning" type="submit" value="Limpar" />
        </div>
</div>
</form>

<br /><br />
<?php
if (!empty($_POST['busc'])) {
    if (!empty($_POST['fk_id_inst'])) {
        $id_inst = " and e.fk_id_inst = " . $_POST['fk_id_inst'];
    }
    $sql = "select * from ge_abrangencia a "
            . "join instancia i on i.id_inst = a.fk_id_inst "
            . "join ge_escolas e on e.fk_id_inst = i.id_inst "
            . " where logradouro like '%" . $_POST['nome'] . "%' "
            . @$id_inst;
    $query = $model->db->query($sql);
    $array = $query->fetchAll();

    $form['array'] = $array;
    $form['fields'] = [
        'Logradouro' => 'logradouro',
        'Bairro ' => 'bairro',
        'Escola' => 'n_inst',
        'Escola' => 'n_inst',
    ];
    tool::relatSimples($form);
}
?>
</div>