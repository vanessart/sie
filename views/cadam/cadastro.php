<?php
$timezone = new DateTimeZone('America/Sao_Paulo');

//controla a exibição do modal no recadastramento
//não remover
unset($_SESSION['key_time']);

?>
<div class="fieldBody">
    <div class="fieldTop">
        Cadastro Municipal de Professor Eventual - CADAMPE
    </div>
    <br /><br />
    <form method="POST">
        <div class="row">
            <div class="col-md-4">
                <?php formulario::selectDB('cadam_seletivas', 'sel', 'Seletiva', @$_SESSION['tpm']['sel'], null, null, null, null, 'order by ordem') ?>
            </div>
            <div class="col-md-4">
                <?php formulario::selectDB('cadam_cargo', 'cargo', 'Cargo', @$_SESSION['tpm']['cargo']) ?>
            </div>
            <div class="col-md-2">
                <?php echo formulario::select('ativo', [1 => 'Sim', 2 => 'Não'], 'Ativo') ?>
            </div>
            <div class="col-md-2">
                <?php echo formulario::select('check_update', [1 => 'Sim', 2 => 'Não'], 'Recadastrado') ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-7">
                <?php formulario::input('busca', 'Nome, CPF ou Inscrição', NULL, @$_POST['busca']) ?>
            </div>
            <div class="col-md-2">
                <input class="btn btn-success" type="submit" name="bc" value="Continuar" />
            </div>
            <div class="col-md-2">
                <!--
                <input class="btn btn-success" type="submit" name="cl" value="Classificar" />
                -->
            </div>
        </div>

    </form>
    <br /><br />
    <?php
    if (!empty($_POST['bc'])) {

        $ativo = @$_POST['ativo'];
        $check_update = @$_POST['check_update'];
        
        if (!empty($_POST['sel'])) {
            $sel = " and c.fk_id_sel = " . $_POST['sel'] . " ";
        }
       
        $busca = str_replace(array('.', '-', '/'), '', $_POST['busca']);

        $array = $model->buscar($busca, @$sel, @$_POST['cargo'], @$ativo, $check_update);
        
        foreach ($array as $k => $v) {

            //remove do array cadamp_class sem inscricao
            $cadam_class = sql::get('cadam_class', '*', ['fk_id_inscr'=>$v['fk_id_inscr'], 'fk_id_sel'=>$v['fk_id_sel']]);
            if(count($cadam_class)==0){
                unset($array[$k]);
                continue;
            }

            $pr = explode('|', $v['dia']);
            unset($pr_);
            foreach ($pr as $p) {
                $pr_[substr($p, 1)] = substr($p, 1);
            }
            $array[$k]['pr'] = implode(' ', $pr_);
            $array[$k]['nome'] = strtoupper($v['n_pessoa']);
            $array[$k]['cpf'] = $v['cpf'];
            $array[$k]['tel'] = $v['tel1'] . '; ' . $v['tel2'] . '; ' . $v['tel3'];
            $array[$k]['ac'] = formulario::submit('Acessar', NULL, $v, HOME_URI . '/cadam/set');
            $array[$k]['check_update'] = $v['check_update'] == 2 ? 'NÃO' : 'SIM'; 

            $data_atualizacao = new DateTime($v['update_at'], $timezone );
            if(is_null($v['update_at'])){
                $array[$k]['update_at'] = null;
            } else {
                $array[$k]['update_at'] = $data_atualizacao->format('d/m/Y H:i:s');
            }

            if ($v['check_update'] == 1){
                $array[$k]['dec'] = formulario::submit('Imprimir', NULL, $v, HOME_URI . '/cadam/decl_comp', 1);
            }else{
               $array[$k]['dec'] = '<button class="btn btn-default" type="button" disabled>Imprimir</button>'; 
            }

        }
        $form['array'] = $array;
        $form['fields'] = [
            'Seletiva' => 'n_sel',
            //'Cad. PMB' => 'cad_pmb',
            'Nome' => 'nome',
            'CPF' => 'cpf',
            'Tel.' => 'tel',
            'Disp.' => 'pr',
            'Recadastrado' => 'check_update',
            'Última Atualização: ' => 'update_at',
            '||' => 'ac',
            '||2' => 'dec'
        ];

        tool::relatSimplesNovo($form);
    }
    ?>

</div>