<?php
ob_start();
$dados = sql::get('vagas', '*', ['id_vaga' => $_POST['id_vaga']], 'fetch');
$esc = new escola();
?>
<div style="position: absolute">
    <?php echo $esc->cabecalho(); ?>
</div>
<br /><br /><br /><br />
<?php
include ABSPATH . '/views/vagas/cada_1_dados.php';
tool::pdf();
?>
