<div class="fieldBody">
    <?php
    javaScript::cep();
    if (!empty($_POST['id_cad'])) {
        $id_cad = $_POST['id_cad'];
    } elseif (!empty($_POST['last_id'])) {
        $id_cad = $_POST['last_id'];
    }
    if (!empty($id_cad)) {
        $dados = sql::get('dtgp_cadampe', '*', ['id_cad' => $id_cad], 'fetch');
    } elseif (!empty($_POST['id_inscr'])) {
        $dados = sql::get('dtgp_sel_1_2017', '*', ['id_inscr' => $_POST['id_inscr'], 'fk_id_sel' => $_POST['fk_id_sel']], 'fetch');
    }

    $cg = sql::get('dtgp_cadampe_cargo');
    foreach ($cg as $v) {
        $cg_[$v['id_cargo']] = $v['n_cargo'];
    }

    if (!empty($dados['cargos_e'])) {
        foreach (explode("|", substr($dados['cargos_e'], 1, -1)) as $v) {
            $cargoId[$v] = $v;
        }
    }

    if (!empty($dados['cargo'])) {
        foreach (explode("|", substr($dados['cargo'], 1, -1)) as $v) {
            if (substr(@$cg_[$v], 0, 6) == 'PEB I ') {
                $tea = 1;
            }
            @$cargo[$v] = $cg_[$v];
        }
    } else {
        foreach ($cg as $v) {
            if (substr($v['n_cargo'], 0, 6) == 'PEB I ') {
                $tea = 1;
            }
            $cargo[$v['id_cargo']] = $v['n_cargo'];
        }
    }
    if (!empty($dados['id_cad']) && $dados['ativo'] == 1) {
        $ativoAtrib = 1;
    }
 if (!empty($dados['tea'])) {
     $ativoAtribTea = 1;
 }
    $abas[1] = ['nome' => "Dados Gerais", 'ativo' => 1, 'hidden' => ['id_cad' => @$id_cad, 'id_inscr' => @$_POST['id_inscr'], 'fk_id_sel' => @$_POST['fk_id_sel'], 'class' => @$_POST['class']], 'link' => ""];
    $abas[2] = ['nome' => "Atribuição", 'ativo' => @$ativoAtrib, 'hidden' => ['id_cad' => @$id_cad, 'id_inscr' => @$_POST['id_inscr'], 'fk_id_sel' => @$_POST['fk_id_sel'], 'class' => @$_POST['class']], 'link' => "",];
    if (!empty($tea)) {
        $abas[4] = ['nome' => "TEA", 'ativo' => @$ativoAtribTea, 'hidden' => ['id_cad' => @$id_cad, 'id_inscr' => @$_POST['id_inscr'], 'fk_id_sel' => @$_POST['fk_id_sel'], 'class' => @$_POST['class']], 'link' => "",];
    }
    $abas[3] = ['nome' => "Impressão", 'ativo' => @$ativoAtrib, 'hidden' => ['id_cad' => @$id_cad, 'id_inscr' => @$_POST['id_inscr'], 'fk_id_sel' => @$_POST['fk_id_sel'], 'class' => @$_POST['class']], 'link' => "",];
    $abas[5] = ['nome' => "Sair", 'ativo' => 4, 'link' => HOME_URI . "/dtgp/cadampecad/",];
    tool::abas($abas);
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
        <br /><br /><br /><br />
        <div class="fieldTop">
            <?php echo $dados['n_insc'] ?> -
            Protocolo <?php echo $dados['id_cad'] ?>
        </div>
        <br />
        <?php
    }
    include ABSPATH . '/views/dtgp/cadampecada_' . $aba . '.php';
    ?>
</div>