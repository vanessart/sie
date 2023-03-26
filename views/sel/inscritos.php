<?php
$sql = "SELECT * FROM `sel_seletiva`";
$query = $model->db->query($sql);
$sel = $query->fetchAll();
foreach ($sel as $v) {
    $selOption[$v['id_sel']] = $v['n_sel'];
}
?>
<div class="fieldBody">
    <?php
    formulario::select('id_sel', $selOption, 'Selecione o Processo Seletivo', @$_POST['id_sel'], 1);
    if (!empty($_POST['id_sel'])) {
        $sql = "SELECT * FROM `sel_cargo` WHERE `fk_id_sel` = " . $_POST['id_sel'];
        $query = $model->db->query($sql);
        $car = $query->fetchAll();
        foreach ($car as $v) {
            $cargos[$v['id_cargo']] = $v['n_cargo'];
        }
        ?>
        <br /><br />
        <div class="fieldWhite">
            <form method="POST">
                <div class="row">
                    <div class="col-md-4">
                        <?php formulario::select('id_cargo', @$cargos, 'Cargo: ') ?>
                    </div>
                    <div class="col-md-5">
                        <?php formulario::input('nome', 'Nome ou CPF') ?>
                    </div>
                    <div class="col-md-1">
                        <input type="hidden" name="id_sel" value="<?php echo $_POST['id_sel'] ?>" />
                        <input class="btn btn-success" type="submit" name="pesq" value="Pesquisar" />
                    </div>
                    <?php
                    if (!empty($_POST['pesq'])) {
                        ?>
                        <div class="col-md-1 text-right">
                            <input onclick="document.getElementById('plan').submit();" class="btn btn-info" type="button" value="Exportar" />
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </form>
        </div>
        <br /><br />
        <?php
        if (!empty($_POST['pesq'])) {
            if (!empty($_POST['id_cargo'])) {
                $cargo = " AND `fk_id_cargo` = " . $_POST['id_cargo'];
            }
            if (!empty($_POST['nome'])) {
                if (is_numeric($_POST['nome'])) {
                    $nome = " and `cpf` LIKE '" . $_POST['nome'] . "' ";
                } else {
                    $nome = " AND `n_insc` LIKE '%" . $_POST['nome'] . "%' ";
                }
            }
            $sql1 = "SELECT * FROM ps.`sel_inscricacao` i "
                    . "join ps.sel_cargo c on c.id_cargo = i.fk_id_cargo "
                    . " join omr_respostas o on o.inscricao = i.id_inscr "
                    . " WHERE 1 "
                    . @$nome
                    . @$cargo
                    . " ORDER BY `n_insc` DESC ";
            $query = $model->db->query($sql1);
            $array = $query->fetchAll();
            foreach ($array as $k => $v) {
                $v['pesq'] = 1;
                $v['id_sel'] = $_POST['id_sel'];
                @$v['nome'] = $_POST['nome'];
                $array[$k]['tel'] = NULL;
                $array[$k]['tel'] = @$v['tel1'];
                $array[$k]['tel'] .= '<br />' . @$v['tel2'];
                $array[$k]['tel'] .= '<br />' . @$v['tel3'];
                $array[$k]['edit'] = formulario::submit('Acessar', NULL, $v);
                $array[$k]['fl'] = formulario::submit('Folha Ótica', NULL, NULL, HOME_URI . str_replace('/var/www/html/otica/views/omr/folhas/M003/', '/pub/seletiva/', $v['original']), 1, NULL, 'btn btn-warning');
            }
            $form['array'] = $array;
            $form['fields'] = [
                'Nome' => 'n_insc',
                'Class.' => 'classifica',
                'CPF' => 'cpf',
                'Telefones' => 'tel',
                'Cargo' => 'n_cargo',
                '||1' => 'edit',
                '||2' => 'fl'
            ];

            tool::relatSimples($form);
        }
    }
    if (!empty($_POST['id_inscr'])) {
        tool::modalInicio();
        include ABSPATH . '/views/sel/dados.php';
        tool::modalFim();
    }
    ?>
</div>
<form target="_blank" id="plan" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
    <input type="hidden" name="tokenSql" value="<?php echo tool::tokenSql() ?>" />
    <input type="hidden" name="sql" value="<?php echo $sql1 ?>" />
</form>