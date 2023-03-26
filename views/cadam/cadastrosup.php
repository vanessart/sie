
<div class="fieldBody">
    <div class="fieldTop">
        Cadastro Municipal de Professor Eventual - CADAMPE
    </div>
    <br /><br />
    <form method="POST">
        <div class="row">
            <div class="col-md-5">
                <?php formulario::selectDB('cadam_cargo', 'cargo', 'Cargo', @$_SESSION['tpm']['cargo']) ?>
            </div>
            <div class="col-md-5">
                <?php formulario::input('busca', 'Nome, CPF ou Inscrição', NULL, @$_POST['busca']) ?>
            </div>
            <div class="col-md-2">
                <input class="btn btn-success" type="submit" name="bc" value="Continuar" />
            </div>
        </div>

    </form>
    <br /><br />
    <?php
    if (!empty($_POST['bc'])) {
        $ativo = @$_POST['ativo'];
        if (!empty($_POST['sel'])) {
            $sel = " and c.fk_id_sel = " . $_POST['sel'] . " ";
        }
       
        $busca = str_replace(array('.', '-', '/'), '', $_POST['busca']);

        $array = $model->buscar($busca, NULL, @$_POST['cargo'], 1);

        foreach ($array as $k => $v) {
            $pr = explode('|', $v['dia']);
            unset($pr_);
            foreach ($pr as $p) {
                $pr_[substr($p, 1)] = substr($p, 1);
            }
            $array[$k]['pr'] = implode(' ', $pr_);
            $array[$k]['nome'] = strtoupper($v['n_pessoa']);
            $array[$k]['cpf'] = $v['cpf'];
            $array[$k]['tel'] = $v['tel1'] . '; ' . $v['tel2'] . '; ' . $v['tel3'];
            $array[$k]['tea'] = tool::simnao($v['tea']);
        }
        $form['array'] = $array;
        $form['fields'] = [
            'Seletiva' => 'n_sel',
            'Cad. PMB' => 'cad_pmb',
            'Nome' => 'nome',
            'CPF' => 'cpf',
            'Tel.' => 'tel',
            'Tea' => 'tea',
            'Disp.' => 'pr',
        ];

        tool::relatSimples($form);
    }
    ?>

</div>