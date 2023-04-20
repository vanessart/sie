<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
if(empty($id_pessoa)){
    $id_pessoa = $model->_id_pessoa;
}
if ($id_pessoa) {
    $pess = $model->pessoa($id_pessoa);
    $active = 1;
} else {
    $active = null;
}

$hidden = [
    'id_pessoa' => $id_pessoa,
];
?>
<div class="body">
    <div class="fieldTop">
        <?= !empty($pess['n_pessoa']) ? $pess['n_pessoa'] : 'Novo Cadastro' ?>
    </div>
    <?php
    $abas[1] = ['nome' => "Geral", 'ativo' => 1, 'hidden' => $hidden];
    $abas[2] = ['nome' => "Telefones", 'ativo' => $active, 'hidden' => $hidden];
    $abas[3] = ['nome' => "Endereço", 'ativo' => $active, 'hidden' => $hidden];
    $abas[4] = ['nome' => "Funcionário", 'ativo' => $active, 'hidden' => $hidden];
    $abas[5] = ['nome' => "Acesso", 'ativo' => $active, 'hidden' => $hidden];
    $aba = report::abas($abas);
    include ABSPATH . "/module/admin/views/_pessoaEdit/$aba.php";
    ?>
</div>
