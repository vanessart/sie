<?php
if (!defined('ABSPATH'))
    exit;

$id_gh = filter_input(INPUT_POST, 'id_gh', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_gh)) {
    $dados = sql::get('coord_grup_hab', '*', ['id_gh' => $id_gh], 'fetch');
}
?>
<div class="body">
    <form target="_parent" action="<?php echo HOME_URI ?>/habili/grupo" method="POST">
        <div class="row">
            <div class="col">
                <?php echo formErp::input('1[n_gh]', 'Grupo', @$dados['n_gh']) ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col">
                <?php echo formErp::selectDB('ge_tp_ensino', '1[fk_id_seg]', 'Segmento', @$dados['fk_id_seg']); ?>
            </div>
            <div class="col">
                <?php echo formErp::select('1[at_gh]', [1 => 'Sim', 0 => 'Não'], 'Ativo', intval(@$dados['at_gh'])) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col text-center">
                <input type="hidden" name="1[id_gh]" value="<?php echo @$dados['id_gh'] ?>" />
                <?php
                echo formErp::hiddenToken('coord_grup_hab', 'ireplace');
                echo formErp::button('Salvar');
                ?>
            </div>
        </div>
    </form>
</div>
