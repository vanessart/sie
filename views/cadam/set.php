<?php
$id_cad = @$_POST['id_cad'];
javaScript::cep();
if (!empty(@$id_cad)) {

    $dados = $model->getCadampe(@$id_cad);
    //print_r($dados);


    $clss = sql::get('cadam_class', '*', ['fk_id_inscr'=>$dados['fk_id_inscr'], 'fk_id_sel'=>$dados['fk_id_sel']]);
    
    //print_r($clss);
    
    foreach ($clss as $v){
        $classDisc[$v['fk_id_cargo']]=$v['class'];
    }
    //echo '<br>';
    //print_r($classDisc);
    
    $cg = sql::idNome('cadam_cargo');

    if (!empty($dados['cargos_e'])) {
        foreach (explode("|", substr($dados['cargos_e'], 1, -1)) as $v) {
             $cargoId[$v] = $v;
             $cargo_e[$v] =  $cg[$v];
        }
        
    }

    //print_r($cargo_e);
    //print_r($dados['cargo']);
    //echo ' DEBUG ...<br>';

    if (!empty($dados['cargo'])) {
        //echo 'AQUI A';
        foreach (explode("|", substr($dados['cargo'], 1, -1)) as $v) {
            if (substr(@$cg[$v], 0, 6) == 'PEB I ') {
                $tea = 1;
            }
            @$cargo[$v] = $cg[$v];
        }
    } else {
        //echo 'AQUI B';
        /*foreach ($cg as $k => $v) {
            if (substr($v, 0, 6) == 'PEB I ') {
                $tea = 1;
            }
            $cargo[$k] = $v;
        }*/
        $cargo = $cargo_e;
    }
    //print_r($cargo);

    if (!empty($dados['id_cad']) && $dados['ativo'] == 1) {
        $ativoAtrib = 1;
    }
    if (!empty($dados['tea'])) {
        $ativoAtribTea = 1;
    }
    $abas[1] = ['nome' => "Dados Gerais", 'ativo' => 1, 'hidden' => ['id_cad' => @$id_cad, 'id_inscr' => @$_POST['id_inscr'], 'fk_id_sel' => @$_POST['fk_id_sel'], 'class' => @$_POST['class']], 'link' => ""];
    $abas[2] = ['nome' => "Atribuição", 'ativo' => @$ativoAtrib, 'hidden' => ['id_cad' => @$id_cad, 'id_inscr' => @$_POST['id_inscr'], 'fk_id_sel' => @$_POST['fk_id_sel'], 'class' => @$_POST['class']], 'link' => "",];
    if (!empty($tea)) {
        $abas[3] = ['nome' => "TEA", 'ativo' => @$ativoAtribTea, 'hidden' => ['id_cad' => @$id_cad, 'id_inscr' => @$_POST['id_inscr'], 'fk_id_sel' => @$_POST['fk_id_sel'], 'class' => @$_POST['class']], 'link' => "",];
    }
    $abas[4] = ['nome' => "Impressão", 'ativo' => @$ativoAtrib, 'hidden' => ['id_cad' => @$id_cad, 'id_inscr' => @$_POST['id_inscr'], 'fk_id_sel' => @$_POST['fk_id_sel'], 'class' => @$_POST['class']], 'link' => "",];
    $abas[5] = ['nome' => "Sair", 'ativo' => 4, 'link' => HOME_URI . "/cadam/cadastro",];
    if (empty($_POST['activeNav'])) {
        $aba = 1;
    } else {
        $aba = $_POST['activeNav'];
    }
    if (!empty($dados['dia'])) {
        $dados['dia'] = explode('|', $dados['dia']);
    }
    if (!empty($dados['doc'])) {
        $doc = explode('|', $dados['doc']);
    }
    if (!empty($dados['id_cad'])) {
        ?>

        <div class="fieldBody">
            <div class="fieldTop">
                <div class="fieldTop">
                    <?php echo $dados['n_pessoa'] ?> -
                    Protocolo <?php echo $dados['id_cad'] ?> - Seletiva: <?php echo $dados['n_sel'] ?>
                </div>
                <br />
                <?php
                tool::abas($abas);
            }
            include ABSPATH . '/views/cadam/_set/' . $aba . '.php';
            ?>
        </div>
    </div>
    <?php
}
?>
