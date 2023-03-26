<?php
$id_pa = filter_input(INPUT_POST, 'fk_id_pa', FILTER_SANITIZE_NUMBER_INT);
$hidden = ['fk_id_pa' => $id_pa];
$dados = $_POST;
$activeNav = filter_input(INPUT_POST, 'activeNav', FILTER_SANITIZE_NUMBER_INT);
$aval = sql::get('prod1_aval', '*', ['ano' => date("Y"), '>' => 'n_pa']);
$aval = tool::idName($aval);
?>
<br /><br />
<div class="row">
    <div class="col-sm-8">
        <?php
        echo form::select('fk_id_pa', $aval, 'Tipo de Avaliação', $id_pa, 1)
        ?>
    </div>
    <div class="col-sm-4">
        <?php
        if (!empty($id_pa)) {
            echo form::submit('Exportar Item', NULL, ['fk_id_pa'=>$id_pa], HOME_URI . '/prod/itenspdf', 1);
        }
        ?>
    </div>
</div>
<br /><br />
<?php
if (!empty($id_pa)) {
    $abas[1] = ['nome' => 'Eixo (' . $aval[$id_pa] . ')', 'ativo' => 1, 'hidden' => $hidden];
    $abas[2] = ['nome' => 'Item (' . $aval[$id_pa] . ')', 'ativo' => 1, 'hidden' => $hidden];
    $aba = tool::abas($abas);
    include ABSPATH . '/views/prod/_inlococad/' . $aba . '.php';
}
?>
