<?php
if (!defined('ABSPATH'))
    exit;

$id_aval = filter_input(INPUT_POST, 'id_aval', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_ag = filter_input(INPUT_POST, 'id_ag', FILTER_SANITIZE_NUMBER_INT);
$id_quest = filter_input(INPUT_POST, 'id_quest', FILTER_SANITIZE_NUMBER_INT);
if ($id_quest) {
    $qt = sqlErp::get('tdics_aval_quest', '*', ['id_quest' => $id_quest], 'fetch');
}
$hidden = [
    'id_ag' => $id_ag,
    'id_pl' => $id_pl,
    'id_aval' => $id_aval,
];
?>

<div class="body">
    <form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/avalconf" target="_parent" method="POST">
        <div class="row"> 
            <div class="col-2"> 
                <?= formErp::input(null, 'ID', $id_quest, ' readonly') ?>
            </div>
            <div class="col-3"> 
                <?= formErp::selectNum('1[ordem]', [1, 50], 'Ordem', @$qt['ordem'], null, null, ' required ') ?>
            </div>
            <div class="col-7"> 
                <?= formErp::input('1[momento]', 'Momento', @$qt['momento'], ' required') ?>
            </div>
        </div>
        <br />
        <div class="row"> 
            <div class="col"> 
                <?= formErp::textarea('1[n_quest]', @$qt['n_quest'], 'Requesitos para Análise', 60) ?>
            </div>
        </div>
        <br />
        <div class=" border">
            <div class="fieldTop">
                Alternativas
            </div>
            <?php
            foreach (range(1, 5) as $v) {
                ?>
                <div class="row"> 
                    <div class="col-9"> 
                        <?= formErp::textarea('1[resp_' . $v . ']', @$qt['resp_' . $v], $v . 'ª Opção', 60) ?>
                    </div>
                    <div class="col-3"> 
                        <?= formErp::input('1[valor_' . $v . ']', 'valor', @$qt['valor_' . $v], null, null, 'number') ?>
                    </div>
                </div>
                <br />
                <?php
            }
            ?>
        </div>
        <div style="text-align: center; padding: 50px">
            <?=
            formErp::hidden([
                'activeNav' => 2,
                '1[fk_id_aval]' => $id_aval,
                '1[id_quest]' => $id_quest
            ])
            . formErp::hidden($hidden)
            . formErp::hiddenToken('tdics_aval_quest', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>