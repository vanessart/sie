<?php
 $aba = @$_REQUEST['aba'];

if (!empty(@$_POST['id_inst'])) {
    $inst = inst::get($_POST['id_inst']);
    @$escola = escolas::get($inst['id_inst'], 'id_inst');
}
?>
<div class="fieldBody" >
    <div class="fieldTop">
        Cadastro de Escola
    </div>
    <br />
    <?php
    if (!empty(@$inst)) {
        $at = 1;
        $esc_ = substr($escola['n_inst'], 0, 20) . '...';
        if(@$_REQUEST['aba']=='sala'){
            $_POST['activeNav'] = 4;
        }
    } else {
        $at = 0;
        $esc_ = '&nbsp;';
    }
    $abas[1] = [
        'nome' => 'Escola',
        'ativo' => 1,
        'link' => HOME_URI . "/gestao/cadescola"
    ];
    $abas[2] = [
        'nome' => 'Controle',
        'ativo' => $at,
        'hidden' => ['id_inst' => @$_POST['id_inst'], 'aba' => 'escola'],
        'link' => HOME_URI . "/gestao/cadescola",
    ];
    $abas[3] = [
        'nome' => 'Prédio',
        'ativo' => $at,
        'hidden' => ['id_inst' => @$_POST['id_inst'], 'aba' => 'predio'],
    ];
    $abas[4] = [
        'nome' => 'salas',
        'ativo' => 0,
    ];
    $abas[5] = [
        'nome' => @$esc_,
        'ativo' => 0
    ];

    tool::abas($abas);
    ?>
    <br /><br />
    <?php
    if (empty($aba)) {
        include ABSPATH . '/views/gestao/cadescola_inst.php';
    } elseif (@$aba == "escola") {
        include ABSPATH . '/views/gestao/cadescola_esc.php';
    } elseif (@$aba == "predio") {
        include ABSPATH . '/views/gestao/cadescola_pre.php';
    } elseif (@$aba == "sala") {
        include ABSPATH . '/views/gestao/cadescola_sala.php';
    }
    ?>
</div>
