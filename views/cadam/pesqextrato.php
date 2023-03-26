<?php
if (empty($_POST['mesSet'])) {
    $mesSet = date("m");
} else {
    $mesSet = $_POST['mesSet'];
}
?>
<div class="fieldBody">
    <div class="fieldBorder2">
        <div style="text-align: center">
            <?php formulario::select('mesSet', data::meses(), 'Mês: ', $mesSet, 1) ?>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-4 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/cadamdia.php" method="POST">
                    <input type="hidden" name="mes" value="<?php echo $mesSet ?>" />
                    <input type="text" name="evento" value="" placeholder="Evento" style="width: 50%" />
                    <br /><br />
                    <input class="btn btn-info" type="submit" value="Relatório Dia" />
                </form>
            </div>
            <div class="col-sm-4 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/cadamhora.php" method="POST">
                    <input type="hidden" name="mes" value="<?php echo $mesSet ?>" />
                    <input type="text" name="evento" value="" placeholder="Evento" style="width: 50%" />
                    <br /><br />
                    <input class="btn btn-info" type="submit" value="Relatório Hora" />
                </form>
            </div>
            <div class="col-sm-4 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/cadamgeral.php" method="POST">
                    <input type="hidden" name="mes" value="<?php echo $mesSet ?>" />
                    <input class="btn btn-info" type="submit" value="Relatório Geral" />
                </form>
            </div>
        </div>
        <br /><br />
    </div>
    <br /><br />
    <div class="fieldBorder2">
        <div class="fieldTop">
            Extrato Individual
        </div>
        <br /><br />
        <div class="row">
            <form method="POST">
                <div class="col-md-4">
                    <?php formulario::select('mesSet1', data::meses(), 'Mês: ', @$_POST['mesSet1']) ?>
                </div>
                <div class="col-md-8">
                    <?php formulario::selectDB('cadam_cargo', 'cargo', 'Cargo', @$_SESSION['tpm']['cargo']) ?>
                </div>
                <div class="col-md-12">
                    <br /><br />
                </div>
                <div class="col-md-10">
                    <?php formulario::input('busca', 'Nome, CPF ou Inscrição', NULL, @$_POST['busca']) ?>
                </div>
                <div class="col-md-2">
                    <input class="btn btn-success" type="submit" name="bc" value="Continuar" />
                </div>
            </form>
        </div>
        <br /><br />
        <?php
        if (!empty($_POST['bc'])) {
            if (!empty($_POST['cargo'])) {
                $cargo = $_POST['cargo'];
            }
            $busca = str_replace(array('.', '-', '/'), '', $_POST['busca']);

            $array = $model->buscar($busca, @$sel, @$cargo);
            $location = HOME_URI . '/cadam/extrato';
            $location1 = HOME_URI . '/cadam/fichapdf';
            foreach ($array as $k => $v) {
                $array[$k]['nome'] = strtoupper($v['n_pessoa']);
                $array[$k]['cpf'] = $v['cpf'];
                $v['mes'] = @$_POST['mesSet1'];
                $array[$k]['ac'] = formulario::submit('Extrato', NULL, $v, $location, 1, NULL, 'btn btn-success');
                $array[$k]['edit'] = formulario::submit('Ficha', NULL, $v, $location1, 1, NULL, 'btn btn-primary');
            }
            $form['array'] = $array;
            $form['fields'] = [
                'Seletiva' => 'n_sel',
                'Inscrição' => 'fk_id_inscr',
                'Nome' => 'nome',
                'CPF' => 'cpf',
                '||' => 'edit',
                '||1' => 'ac'
            ];

            tool::relatSimples($form);
        }
        ?>

    </div>
</div>