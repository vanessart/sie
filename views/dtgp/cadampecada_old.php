<?php

if (!empty($_POST['fk_id_inscr'])) {
    $dados = sql::get('dtgp_cadampe', '*', ['fk_id_inscr' => $_POST['fk_id_inscr']], 'fetch');
}
if (empty($dados['cpf'])) {
    $dados = @$_POST;
} else {

}


?>
<br /><br />
<?php
if(!empty($dados['id_inscr'])){
    $ativoEsc = 1;
}
$abas[1] = ['nome' => "Dados Gerais", 'ativo' => 1, 'hidden' => ['cpf' => @$_POST['cpf']], 'link' => "",];
$abas[2] = ['nome' => "Atribuição", 'ativo' => @$ativoEsc, 'hidden' => ['cpf' => @$_POST['cpf']], 'link' => "",];
tool::abas($abas);
if(empty($_POST['activeNav'])){
    $aba = 1;
} else {
    $aba = $_POST['activeNav'];
}
include ABSPATH . '/views/dtgp/cadampecada_'.$aba.'.php';
?>
