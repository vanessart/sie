<?php
if (!defined('ABSPATH'))
    exit;
$id_tp_ens = filter_input(INPUT_POST, 'id_tp_ens', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_tp_ens)) {
    $seg = sql::get('ge_tp_ensino', '*', ['id_tp_ens' => $id_tp_ens], 'fetch');
}
?>
<form action="<?= HOME_URI ?>/sed/ensino" target="_parent" method="POST">
    <div class="row">
        <div class="col">
            <?php echo formErp::input('1[n_tp_ens]', 'Segmento', @$seg['n_tp_ens']) ?>
        </div>
        <div class="col">
            <?php echo formErp::input('1[sigla]', 'Abrev.', @$seg['sigla']) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?php echo formErp::input('1[sequencia]', 'Sequência (ex: "1:2")', @$seg['sequencia']) ?>
        </div>
        <div class="col">
            <?php echo formErp::select('1[at_seg]', [1 => 'Sim', 0 => 'Não'], 'Ativo', @$seg['at_seg']) ?>
        </div>
    </div>
    <br />
    <div style="text-align: center; padding: 20px">
        <?=
        formErp::hiddenToken('ge_tp_ensino', 'ireplace')
        . formErp::hidden(['1[id_tp_ens]' => $id_tp_ens])
        ?>
        <input class="btn btn-success" type="submit" value="Salvar" />
    </div>
</form>
