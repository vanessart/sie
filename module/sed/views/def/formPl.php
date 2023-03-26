<?php
if (!defined('ABSPATH'))
    exit;
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
if ($id_pl) {
    $pl = sql::get('ge_periodo_letivo', '*', ['id_pl' => $id_pl], 'fetch');
} else {
    $pl = null;
}
$si = ['Inativo', 'Ativo', 'Previsto'];
?>
<div class="body">
    <div class="row">
        <div class="col-md-12">
            <form target="_parent" action="<?= HOME_URI ?>/sed/pl" method="POST">
                <div class="row">
                    <div class="col">
                        <?php echo formErp::input('1[n_pl]', 'Descrição', @$pl['n_pl'], ' required ') ?>
                    </div>
                    <div class="col">
                        <?php echo formErp::input('1[ano]', 'Ano', @$pl['ano'], ' required ') ?>
                    </div>
                    <div class="col">
                        <?php echo formErp::selectNum('1[semestre]', [0, 2], 'Semestre', @$pl['semestre']) ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col">
                        <?php echo formErp::select('1[at_pl]', $si, 'Situação', @$pl['at_pl']) ?> 
                    </div>
                    <div class="col">
                        <?php echo formErp::select('1[preferencial]', [0 => 'Não', 1 => 'Sim'], 'Período padrão', @$pl['preferencial']) ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-12 text-center">
                        <?=
                        formErp::hiddenToken('ge_periodo_letivo', 'ireplace')
                        . formErp::hidden(['1[id_pl]' => @$id_pl])
                        . formErp::button('Salvar')
                        ?>
                    </div>
                </div>
            </form>       
        </div>
    </div>
</div>
