<?php
if (empty($_POST['mes'])) {
    $mes_ = date("m");
} else {
    $mes_ = $_POST['mes'];
}

$fk_id_cad = @$_POST['fk_id_cad'];
$id_cargo = @$_POST['fk_id_cargo'];
$rse = @$_POST['rse'];
$periodo = @$_POST['periodo'];
$mes = @$_POST['mes'];
$id_fr = @$_POST['id_fr'];
if (user::session('id_nivel') == 14) {
    $id_inst = @$_POST['id_inst'];
} else {
    $id_inst = tool::id_inst();
}
?>
<div class="fieldBody">
    <?php
    if (user::session('id_nivel') == 14) {
        ?>
        <div class="row">
            <div class="col-sm-12">
                <?php
                formulario::select('id_inst', escolas::idInst(), 'Escola', null, 1);
                ?>
            </div>
        </div>
        <br /><br />
        <?php
    }
    if (!empty($id_inst)) {
        ?>
        <div class="fieldTop">
            Lançamento de Frequência - TEA
        </div>
        <?php
        if (empty($_POST['mesSet'])) {
            $mesSet = date("m");
        } else {
            $mesSet = $_POST['mesSet'];
        }
        ?>
        <br /><br />
        <div class="fieldBorder2">
            <?php formulario::select('mesSet', data::meses(), 'Mês: ', $mesSet, 1, ['id_inst' => $id_inst]) ?>
        </div>
        <br /><br />
        <div class="fieldTop">
            <form method="POST">
                <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                <input class="btn btn-success" name="lanc" type="submit" value="Novo Lançamento - TEA" />
            </form> 
        </div>
        <br /><br />
        <?php
        $freq = $model->frequenciaTea($id_inst, $mesSet, date("Y"));
        $sqlkey = DB::sqlKey('cancelaTea');
        foreach ($freq as $k => $v) {
            if ($v['cancelado'] != 1) {
                $freq[$k]['canc'] = formulario::submit('Cancelar', $sqlkey, $v, NULL, NULL, NULL, 'btn btn-danger');
            } else {
                $freq[$k]['canc'] = '<button type="button" class="btn btn-default">Cancelado</button>';
            }

            $v['p1'] = 1;
            $v['lanc'] = 1;
            $freq[$k]['period'] = gtMain::periodoDoDia($v['periodo']);

        }
        $form['array'] = $freq;
        $form['fields'] = [
            'Cadastro' => 'cad_pmb',
            'Dia' => 'dia',
            'Período' => 'period',
            'Hora(s)' => 'horas',
            'Aluno' => 'aluno',
            'Classe' => 'n_turma',
            'Cadampe' => 'n_pessoa',
            '||2' => 'canc',
            '||1' => 'edit'
        ];
        tool::relatSimples($form);
    }
    if (!empty($_POST['lanc'])) {
        tool::modalInicio();
        include ABSPATH . '/views/cadam/_freqtea/1.php';
        tool::modalFim();
    }
    ?>
</div>

