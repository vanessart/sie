<?php
if (!defined('ABSPATH'))
    exit;
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$id_tp_ens = filter_input(INPUT_POST, 'id_tp_ens', FILTER_SANITIZE_NUMBER_INT);
$id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
if ($id_ciclo) {
    $ciclo = sql::get('ge_ciclos', '*', ['id_ciclo' => $id_ciclo], 'fetch');
}
if (!empty($ciclo['dias_semana'])) {
    $diasSemana = explode(',', $ciclo['dias_semana']);
} else {
    $diasSemana = [1, 2, 3, 4, 5];
}
?>
<div class="body">
        <form target="_parent" action="<?= HOME_URI ?>/sed/ensino" method="POST">
        <div class="row">
            <div class="col">
                <?php echo formErp::input('1[n_ciclo]', 'Ciclo', @$ciclo['n_ciclo']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?php echo formErp::input('1[sg_ciclo]', 'Abrev.', @$ciclo['sg_ciclo']) ?>
            </div>
            <div class="col">
                <?php echo formErp::select('1[ativo]', ['Não', 'Sim'], 'Ativo', @$ciclo['ativo']) ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-6">
                <?php echo formErp::selectNum('1[aulas]', [1, 20], 'Aulas por Dia', @$ciclo['aulas']) ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-2">
                Dias da Semana
            </div>
            <div class="col-sm-10">
                <table>
                    <tr>
                        <?php
                        foreach (dataErp::diasDaSemana() as $k => $v) {
                            ?>
                            <td style="padding: 8px">
                                <div class="input-group" style="width: 100%; float: left">
                                    <label  style="width: 100%">
                                        <span class="input-group-addon" style="text-align: left; width: 20px">
                                            <input <?php echo in_array($k, $diasSemana) ? ' checked ' : '' ?> type="checkbox" aria-label="..." name="1[dias_semana][<?php echo $k ?>]" value="<?php echo $k ?>">
                                        </span>
                                        <span class="input-group-addon" style="text-align: left">
                                            <?php echo $v ?>
                                        </span>
                                    </label>
                                </div>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                </table>
            </div>
        </div>
        <br /><br />
        <div class="text-center">
            <?=
            formErp::hiddenToken('ge_ciclosSlava')
            . formErp::hidden([
                'activeNav' => 3,
                '1[id_ciclo]' => $id_ciclo,
                '1[fk_id_curso]' => $id_curso,
                'id_curso' => $id_curso,
                'id_tp_ens' => $id_tp_ens
            ])
            ?>
            <input class="btn btn-success" type="submit" value="Salvar" />
        </div>
    </form>
</div>
