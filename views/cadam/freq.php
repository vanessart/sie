<br /><br />
<div style="text-align: center; font-size: 20px; font-weight: bold">
    Lançamento de Frequência
</div>
<br /><br />
<?php
$per_ = ['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite'];
if (empty($_POST['mesSet'])) {
    $mesSet = date("m");
} else {
    $mesSet = $_POST['mesSet'];
}
if (user::session('id_nivel') == 14) {
    $id_inst = @$_POST['id_inst'];
    echo formulario::select('id_inst', escolas::idInst(), 'Escola', @$id_inst, 1);
} else {
    $id_inst = tool::id_inst(@$_POST['id_inst']);
}
if (!empty($id_inst)) {
    ?>
    <br /><br />
    <div class="fieldBorder2">
        <?php formulario::select('mesSet', [(date("m") + 1) => data::mes((date("m") + 1)), date("m") => data::mes(date("m")), (date("m") - 1) => data::mes((date("m") - 1))], 'Mês: ', $mesSet, 1, ['id_inst' => $id_inst]) ?>
    </div>
    <br /><br />
    <div class="row" style="font-size: 20px; font-weight: bold">
        <div class="col-sm-12" style="text-align: center">
            <form method="POST">
                <input type="hidden" name="mesSet" value="<?php echo $mesSet ?>" />
                <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                <input class="btn btn-success" name="lanc" type="submit" value="Novo Lançamento" />
            </form> 
        </div>
    </div>
    <br /><br /><br />
    <?php
    $sqlkey = DB::sqlKey('cancela');
    $frequente = cadamp::frequencia($mesSet, 1, $id_inst);
    foreach ($frequente as $k => $v) {
        $v['mesSet'] = $mesSet;
        $v['id_inst'] = $id_inst;
        if ($v['cancelado'] != 1) {
            $frequente[$k]['canc'] = formulario::submit('Cancelar', $sqlkey, $v, NULL, NULL, NULL, 'btn btn-danger');
        } else {
            $frequente[$k]['canc'] = '<button type="button" class="btn btn-default">Cancelado</button>';
        }
        $frequente[$k]['data'] = (data::converteBr(substr($v['dt_fr'], 0, 10))) . ' as ' . (substr($v['dt_fr'], 10));
        if ($v['cancelado'] != 1) {
            $v['editar'] = 1;
            $frequente[$k]['editar'] = formulario::submit('Alterar Motivo', NULL, $v, NULL, NULL, NULL, 'btn btn-success');
        }
    }
    $form['array'] = $frequente;
    $form['fields'] = [
        'Dia' => 'dia',
        'Cargo' => 'n_cargo',
        'CAD PMB' => 'cad_pmb',
        'ID' => 'id_cad',
        'CADAMPE' => 'n_pessoa',
        'Horas' => 'horas',
        'Turmas' => 'turmas',
        'Per.' => 'periodo',
        'Motivo' => 'n_mot',
        //     'Lançamento' => 'data',
        '||1' => 'canc',
        '||' => 'editar'
    ];

    tool::relatSimples($form);
    if (!empty($_POST['lanc'])) {
        tool::modalInicio();
        ?>
        <iframe style="border: none; width: 100%; height: 90vh" src="<?php echo HOME_URI ?>/cadam/freqa?mesSet=<?php echo $mesSet ?>&id_inst=<?php echo $id_inst ?>"></iframe>
        <?php
        tool::modalFim();
    } elseif (!empty($_POST['editar'])) {
        tool::modalInicio();
        $func = funcionarios::Get(@$_POST['rm'])[0];
        ?>
        <br /><br />
        <div class="fieldTop">
            CADAMPE <?php echo @$_POST['n_pessoa'] ?> substituindo 
            <?php echo tool::sexoArt($func['sexo']) ?> professor<?php echo $func['sexo'] == 'F' ? 'a' : '' ?> <?php echo $func['n_pessoa'] ?>

        </div>
        <br /><br />
        <form method="POST">
            <div class="row">
                <div class="col-sm-8">
                    <?php echo formulario::selectDB('cadam_motivo', '1[fk_id_mot]', 'Motivo da Falta', $_POST['fk_id_mot']) ?>
                </div>
                <div class="col-sm-4 text-center">
                    <input type="hidden" name="1[fk_id_pessoa]" value="<?php echo tool::id_pessoa() ?>" />
                    <input type="hidden" name="dt_fr" value="<?php echo date("Y-m-d H:i:s") ?>" />
                    <input type="hidden" name="1[id_fr]" value="<?php echo $_POST['id_fr'] ?>" />
                    <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                    <input type="hidden" name="mesSet" value="<?php echo $mesSet ?>" />
                    <?php echo DB::hiddenKey('cadam_freq', 'replace') ?>
                    <input class="btn btn-success" type="submit" value="Alterar" />
                </div>
            </div>
            <br /><br /><br /><br />
        </form>
        <?php
        tool::modalFim();
    }
}
?>