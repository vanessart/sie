<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = toolErp::id_inst();
$id_gr = filter_input(INPUT_POST, 'id_gr', FILTER_SANITIZE_NUMBER_INT);
if ($id_gr) {
    $gr = sql::get('sed_grupo', '*', ['id_gr' => $id_gr], 'fetch');
} else {
    $gr = null;
}
?>
<div class="body">
    <form action="<?= HOME_URI ?>/sed/grupo" target="_parent" method="POST">
        <div class="row">
            <div class="col-2">
                <?= formErp::input('1[id_gr]', 'ID', @$gr['id_gr'], ' readonly ') ?>
            </div>
            <div class="col">
                <?= formErp::input('1[n_gr]', 'Nome', @$gr['n_gr'], ' required ') ?>
            </div>
        </div>
        <br />
        <?= formErp::textarea('1[descr_gr]', @$gr['descr_gr'], 'Descrição sobre o Grupo') ?>
        <br /><br />
        <div class="row">
            <div class="col">
                <?= formErp::select('1[at_gr]', [1 => 'Sim', 0 => 'Não'], 'Ativo', empty($id_gr) ? 1 : @$gr['at_gr']) ?>
            </div>
            <div class="col">
                <label>
                    <?= formErp::input('1[cor]', 'Cor do grupo', (!empty($gr['cor']) ? $gr['cor'] : '#FFFF00'), ' id="cores" list="arcoIris"  style="width: 100px"', null, 'color') ?>
                    <datalist id="arcoIris">
                        <option value="#FFFF00">Amarelo</option>
                        <option value="#FF0000">Vermelho</option>
                        <option value="#FFA500">Laranja</option>
                        <option value="#008000">Verde</option>
                        <option value="#0000FF">Azul</option>
                        <option value="#4B0082">Indigo</option>
                        <option value="#EE82EE">Violeta</option>
                    </datalist>
                </label>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 20px">
            <?=
            formErp::hiddenToken('sed_grupo', 'ireplace')
            . formErp::hidden([
                '1[fk_id_inst]' => $id_inst
            ])
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
