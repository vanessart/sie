<?php
if (!defined('ABSPATH'))
    exit;
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$polos = sql::idNome('tdics_polo');
$plsArr = sql::get('tdics_pl', '*', ' where ativo in (1,2)');
foreach ($plsArr as $v) {
    $pls[$v['id_pl']] = $v['n_pl'];
    if (empty($id_pl) && $v['ativo'] == 1) {
        $id_pl = $v['id_pl'];
    }
}
if ($id_polo) {
    $turmas = sql::idNome('tdics_turma', ['fk_id_polo' => $id_polo, 'fk_id_pl'=>$id_pl]);
}
?>
<div class="body">
    <div class="fieldTop">
        Lista Piloto
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_pl', $pls, 'Período Letivo', $id_pl, 1, ['id_polo' => $id_polo]) ?>
        </div>
        <div class="col">
            <?= formErp::select('id_polo', $polos, 'Núcleo', $id_polo, 1, ['id_pl' => $id_pl]) ?>
        </div>
        <div class="col">
            <?php
            if ($id_polo) {
                ?>
                <form target="_blank" action="<?= HOME_URI ?>/tdics/pdf/piloto" method="POST">
                    <?=
                    formErp::hidden([
                        'id_pl' => $id_pl,
                        'id_polo' => $id_polo
                    ])
                    ?>
                    <button type="submit" class="btn btn-warning">
                        Imprimir Todas as turmas
                    </button>
                </form>
                <?php
            }
            ?>
        </div>
    </div>
    <br />
    <?php
    if ($id_polo && $turmas) {
        ?>
        <div class="row">
            <?php
            $c = 1;
            foreach ($turmas as $k => $v) {
                ?>
            <div class="col-3" style="text-align: center">
                    <form target="_blank" action="<?= HOME_URI ?>/tdics/pdf/piloto" method="POST">
                        <?=
                        formErp::hidden([
                            'id_pl' => $id_pl,
                            'id_polo' => $id_polo,
                            'id_turma' => $k
                        ])
                        ?>
                        <button type="submit" class="btn btn-primary" style="width: 200px">
                            <?= $v ?>
                        </button>
                    </form>
                </div>
                <?php
                if ($c++ == 4) {
                    $c = 1;
                    ?>

                </div>
                <br />
                <div class="row">
                    <?php
                }
            }
            ?>
        </div>
        <br />
        <?php
    }
    ?>
</div>