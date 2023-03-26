<?php

$id_pessoa = @$_POST['id_pessoa'];
if (empty($id_pessoa)) {
    $id_pessoa = @$_POST['last_id'];
}
if (!empty($id_pessoa)) {
    $aluno = new aluno($id_pessoa);
    $ativoAba = 1;
} else {
    $ativoAba = NULL;
}
if ($model->_gdaeSet == 1) {
    $disable = 'disabled';
} else {
    $disable = NULL;
}
?>

<div class="fieldBody">
    <div class="fieldTop">
        <?php echo @$aluno->_nome ?> - RSE: <?php echo @$aluno->_rse ?>
    </div>
    <br />
    <?php
    $hidden = ['id_pessoa' => $id_pessoa];
    $abas[1] = ['nome' => "Dados Gerais", 'ativo' => 1, 'hidden' => $hidden, 'link' => "",];
    $abas[2] = ['nome' => "Endereço", 'ativo' => $ativoAba, 'hidden' => $hidden, 'link' => "",];
    $abas[3] = ['nome' => "Foto", 'ativo' => $ativoAba, 'hidden' => $hidden, 'link' => "",];
    $abas[4] = ['nome' => "Vida Escolar", 'ativo' => $ativoAba, 'hidden' => $hidden, 'link' => "",];
    $abas[5] = ['nome' => "Prontuário", 'ativo' => $ativoAba, 'hidden' => $hidden, 'link' => "",];
    $abas[6] = ['nome' => "Autorização", 'ativo' => $ativoAba, 'hidden' => $hidden, 'link' => "",];
    //  $abas[7] = ['nome' => "Transporte", 'ativo' => $ativoAba, 'hidden' => $hidden, 'link' => "",];
    if ($model->wverificamaternal()) {
        $abas[8] = ['nome' => "Família", 'ativo' => $ativoAba, 'hidden' => $hidden, 'link' => "",];
    }
    $activeNav = tool::abas($abas);
    include ABSPATH . "/views/gt/_aluno/$activeNav.php";
    ?>
</div>
