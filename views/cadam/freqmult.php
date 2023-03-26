<div class="fieldBody">
    <div class="fieldTop">
        Reserva de Período
    </div>
    <br />
    <form method="POST">
        <input type="hidden" name="acessar" value="1" />
        <input class="btn btn-info" type="submit" value="Novo Cadastro" />
    </form>
    <br /><br />
    <?php
    $sql = "SELECT n_cargo, id_pr, dt_inicio, dt_fim, n_pessoa, n_inst, cp.turmas FROM cadam_prolongado cp "
            . " join cadam_cadastro cc on cp.fk_id_cad = cc.id_cad "
            . " join pessoa p on p.id_pessoa = cc.fk_id_pessoa "
            . " join instancia i on i.id_inst = cp.fk_id_inst "
            . " join cadam_cargo cg on cg.id_cargo = cp.fk_id_cargo"
            . " where dt_fim >=  '" . date("Y-m-d") . "' ";
    $query = $model->db->query($sql);
    $array = $query->fetchAll();
    $sqlkey = DB::sqlKey('cadam_prolongado', 'delete');
    foreach ($array as $k => $v) {
        $v['acessar'] = 1;
        $array[$k]['acc'] = formulario::submit('Acessar', NULL, $v, HOME_URI . '/cadam/freqmultform', 'target="filho" onclick=" $(\'#form\').modal(\'show\');"');
        $array[$k]['del'] = formulario::submit('Apagar', $sqlkey, ['1[id_pr]' => $v['id_pr']]);
    }
    $form['array'] = $array;
    $form['fields'] = [
        'Cadampe' => 'n_pessoa',
        'Escola' => 'n_inst',
        'Cargo' => 'n_cargo',
        'Início' => 'dt_inicio',
        'Final' => 'dt_fim',
        '||2' => 'del',
        '||1' => 'acc'
    ];
    tool::relatSimples($form);

    if (!empty($_POST['acessar'])) {
        $ac = NULL;
    } else {
        $ac = 1;
    }
    tool::modalInicio('width: 90%', $ac, 'form');
    ?>
    <iframe name="filho" id="filho" style="border: none; width: 100%; height: 82vh" src="<?php echo HOME_URI ?>/cadam/freqmultform?id_cad=<?php echo @$_POST['id_cad'] ?>" ></iframe>
        <?php
        tool::modalFim();
        ?>    
</div>