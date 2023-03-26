<?php
$clas = sql::get('dtpg_class');
$sel_ = sql::get('dtpg_seletivas');
foreach ($sel_ as $s) {
    $seletivas_[$s['id_sel']] = $s['n_sel'];
}
foreach ($clas as $v) {
    @$class[$v['fk_id_inscr']][$v['fk_id_cargo']] = $v['class'];
}
if (!empty($_POST['bc'])) {
    $_SESSION['tpm']['sel'] = @$_POST['sel'];
    $_SESSION['tpm']['cargo'] = @$_POST['cargo'];
}
?>
<div class="fieldBody">
    <div class="fieldTop">
        Cadastro Municipal de Professor Eventual - CADAMPE
    </div>
    <br /><br />
    <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <?php formulario::selectDB('dtpg_seletivas', 'sel', 'Seletiva', @$_SESSION['tpm']['sel']) ?>
            </div>
            <div class="col-md-6">
                <?php formulario::selectDB('dtgp_cadampe_cargo', 'cargo', 'Cargo', @$_SESSION['tpm']['cargo']) ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-10">
                <?php formulario::input('busca', 'Nome, CPF ou Inscrição', NULL, @$_POST['busca']) ?>
            </div>
            <div class="col-md-2">
                <input class="btn btn-success" type="submit" name="bc" value="Continuar" />
            </div>
        </div>

    </form>
    <br /><br />
    <form style="display: none" action="<?php echo HOME_URI ?>/dtgp/cadampecada" method="POST">
        <div style="text-align: center">
            <input class="btn btn-warning" type="submit" name="novo" value="Novo Cadastro" />
        </div>
    </form>
    <br /><br />
    <?php
    if (!empty($_POST['bc'])) {

        if (!empty($_POST['sel'])) {
            $sel = " and c.fk_id_sel = " . $_POST['sel'] . " ";
        }
        if (!empty($_POST['cargo'])) {
            $cargo = " and c.cargo like '%|" . $_POST['cargo'] . "%' ";
        }
        $busca = str_replace(array('.', '-', '/'), '', $_POST['busca']);
        if (!empty($_POST['sel'])) {
            $sel = " and fk_id_sel = " . $_POST['sel'] . " ";
        }
        if (!empty($_POST['cargo'])) {
            $cargo = " and cargos_e like '%|" . $_POST['cargo'] . "%' ";
        }
         $sql = "select "
        . " n_insc, fk_id_inscr, id_cad, cpf, classifica, nota, fk_id_sel "
        . " from dtgp_cadampe "
        . "where ("
        . "n_insc like '%$busca%' "
        . "or cpf like '$busca' "
        . "or fk_id_inscr like '$busca' "
        . ") "
        . @$sel
        . @$cargo
        . " order by n_insc";
        $query = $model->db->query($sql);
        $array = $query->fetchAll();
        //$array = array_merge($array1, $array);
        foreach ($array as $k => $v) {
            unset($del);

            if (empty($del)) {

                $array[$k]['nome'] = strtoupper($v['n_insc']);
                $array[$k]['cpf'] = $v['cpf'];
                $array[$k]['n_insc'] = $v['n_insc'];
                $array[$k]['seletiva'] = @$seletivas_[$v['fk_id_sel']];
                $array[$k]['ac'] = formulario::submit('Acessar', NULL, $v, HOME_URI . '/dtgp/cadampecada');
            } else {
                unset($array[$k]);
            }
        }
        $form['array'] = $array;
        $form['fields'] = [
            'Seletiva' => 'seletiva',
            'Inscrição' => 'fk_id_inscr',
            'Nome' => 'nome',
            'CPF' => 'cpf',
            '||' => 'ac'
        ];

        tool::relatSimples($form);
    }
    ?>

</div>
