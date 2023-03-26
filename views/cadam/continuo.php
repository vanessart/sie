<div style="text-align: center; font-size: 20px; font-weight: bold">
    Reserva de Período
</div>
<br /><br />
<?php
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
    <div style="text-align: center; font-size: 20px; font-weight: bold">
        <form method="POST">
            <input type="hidden" name="mesSet" value="<?php echo $mesSet ?>" />
            <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
            <input class="btn btn-success" name="lanc" type="submit" value="Novo Lançamento" />
        </form>
    </div>
    <br /><br /><br />
    <?php
    $sqlkey = DB::sqlKey('cancelaRes');
    $reserva = cadamp::reserva($id_inst, $mesSet, 1);
    foreach ($reserva as $k => $v) {
        $v['mesSet'] = $mesSet;
        $v['id_inst'] = $id_inst;
        $reserva[$k]['ini'] = $v['dia_ini'] . '/' . $mesSet . '/' . date("Y");
        $reserva[$k]['fim'] = $v['dia_fim'] . '/' . $mesSet . '/' . date("Y");
        $reserva[$k]['periodo'] = str_replace(['M', 'T', 'N'], ['Manhã ', 'Tarde ', 'Noite '], $v['periodo']);
        if ($v['cancelado'] != 1) {
            $reserva[$k]['canc'] = formulario::submit('Cancelar', $sqlkey, $v, NULL, NULL, NULL, 'btn btn-danger');
        } else {
            $reserva[$k]['canc'] = '<button type="button" class="btn btn-default">Cancelado</button>';
        }
        if ($v['cancelado'] != 1) {
            $v['editar'] = 1;
            $reserva[$k]['editar'] = formulario::submit('Alterar', NULL, $v, NULL, NULL, NULL, 'btn btn-success');
        }
    }
    $form['array'] = $reserva;
    $form['fields'] = [
        'CAD PMB' => 'cad_pmb',
        'CADAMPE' => 'cad',
        'Professor(a)' => 'prof',
        'Início' => 'ini',
        'Fim' => 'fim',
        'Cargo' => 'n_cargo',
        'Período' => 'periodo',
        '||1' => 'canc',
        '||' => 'editar'
    ];

    tool::relatSimples($form);
    if (!empty($_POST['lanc'])) {
        tool::modalInicio();
        ?>
        <iframe style="border: none; width: 100%; height: 90vh" src="<?php echo HOME_URI ?>/cadam/continuoa?mesSet=<?php echo $mesSet ?>&id_inst=<?php echo $id_inst ?>"></iframe>
        <?php
        tool::modalFim();
    } elseif (!empty($_POST['editar'])) {
        tool::modalInicio();
        $du = data::diasUteis($mesSet, date('Y'));
        foreach ($du as $k => $v) {
            $diasUteis[$v] = str_pad($v, 2, "0", STR_PAD_LEFT) . '/' . str_pad($mesSet, 2, "0", STR_PAD_LEFT) . '/' . date("Y");
        }
        ?>
        <br /><br />
        <form method="POST">
            <table class="table table-bordered">
                <tr style="background-color: grey; color: white">
                    <th>
                        Cadampe
                    </th>
                    <th>
                        Professor
                    </th>
                </tr>
                <tr>
                    <td>
                        <?php echo @$_POST['cad'] ?>
                    </td>
                    <td>
                        <?php echo @$_POST['prof'] ?>
                    </td>
                </tr>
            </table>
            <br /><br />
            <div class="row">
                <div class="col-sm-3">  
                    <?php
                    echo formulario::select('1[dia_ini]', $diasUteis, 'Data Inicial', @$_POST['dia_ini'], NULL, NULL, @$disabled . ' required');
                    ?>
                </div>
                <div class="col-sm-3">  
                    <?php
                    echo formulario::select('1[dia_fim]', $diasUteis, 'Dia Final', @$_POST['dia_fim'], NULL, NULL, @$disabled . ' required');
                    ?>
                </div>
                <div class="col-sm-4 text-center">
                    <input type="hidden" name="1[fk_id_pessoa]" value="<?php echo tool::id_pessoa() ?>" />
                    <input type="hidden" name="1[id_res]" value="<?php echo $_POST['id_res'] ?>" />
                    <input type="hidden" name="tarde" value="<?php echo $t ?>" />
                    <input type="hidden" name="noite" value="<?php echo $n ?>" />
                    <?php echo DB::hiddenKey('cadam_reserva', 'replace') ?>
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
