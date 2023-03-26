<?php
if (!defined('ABSPATH'))
    exit;
$periodos = gt_main::periodosPorAno();
$id_inscr = filter_input(INPUT_POST, 'id_inscr', FILTER_SANITIZE_NUMBER_INT);
$cur = [];
if (!empty($id_inscr)) {
    $i = sql::get('quali_inscr', '*', ['id_inscr' => $id_inscr], 'fetch');
    $cur = explode(',', $i['cursos']);
    $quant = json_decode($i['quant'], true);
}
$cursoSeg = gt_escolas::cursosPorSegmentoFase(tool::id_inst());
?>
<div class="body">
    <form target="_parent" action="<?= HOME_URI ?>/quali/inscrSet" method="POST">
        <div class="row">
            <div class="col">
                <?= form::input('1[n_inscr]', 'Título', @$i['n_inscr'], ' required ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= form::select('1[at_inscr]', [1 => 'Sim', 0 => 'Não'], 'Ativo', @$i['at_inscr']) ?>
            </div>
            <div class="col">
                <?= form::select('1[ver_aprovados]', [0 => 'Não', 1 => 'Sim'], 'Publicar Aprovados', @$i['ver_aprovados']) ?>
            </div>
            <div class="col">
                <?= form::select('1[fk_id_pl]', $periodos, 'Período Letivo', @$i['fk_id_pl']); ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= form::input('1[dt_inicio]', 'Início', @$i['dt_inicio'], null, null, 'date') ?>
            </div>
            <div class="col">
                <?= form::input('1[dt_fim]', 'Término', @$i['dt_fim'], null, null, 'date') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= form::textarea('1[txt_inscr]', @$i['txt_inscr'], 'Subtítulo') ?>
            </div>
        </div>
        <br />
        <table class="border4" style="width: 100%">
            <tr>
                <td colspan="2" style="text-align: center">
                    Cursos Oferecidos
                </td>
            </tr>
            <?php
            foreach ($cursoSeg as $seg => $cursos) {
                ?>
                <tr>
                    <td colspan="2" style="background-color: silver; color: black">
                        <?= $seg ?>
                    </td>
                </tr>
                <?php
                foreach ($cursos as $k => $v) {
                    ?>
                    <tr>
                        <td style="width: 300px">
                            <?= form::input('quant['.$k.']', 'Quantidade de vagas', @$quant[$k], null, null, 'number') ?>
                        </td>
                        <td style="padding-left: 10px">
                            <?= form::checkbox('cur[' . $k . ']', 1, $v, (in_array($k, $cur) ? 1 : null)) ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
        <br />
        <div style="text-align: center">
            <?=
            form::hidden([
                '1[id_inscr]' => $id_inscr,
            ])
            ?>
            <?= form::hiddenToken('quali_inscrSalva') ?>
            <?= form::button('Salvar') ?>
        </div>
    </form>
</div>
