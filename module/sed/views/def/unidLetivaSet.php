<?php
if (!defined('ABSPATH'))
    exit;
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_UNSAFE_RAW);

if ($id_curso) {
    $curso = sql::get('ge_cursos', '*', ['id_curso' => $id_curso], 'fetch');
    $unLetiva = $curso['un_letiva'];
    $qtLetiva = $curso['qt_letiva'];
}
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_pl = ng_main::periodoSet($id_pl);
$periodos = ng_main::periodosPorSituacao([1, 2]);
$dt = ng_main::letivaData($id_curso, $id_pl);
?>
<br />
<div class="alert alert-danger">
    <p>
        Atenção!
    </p>
    <p>
        Verifique o Período Letivo antes de configurar as datas.
    </p>
</div>
<br />
<div class="row">
    <div class="col-sm-6">
        <?= formErp::select('id_pl', $periodos, 'Período Letivo', @$id_pl, 1, ['id_curso' => $id_curso]); ?>
    </div>
</div>
<br /><br />
<div class="Body">
    <form target="_parent" action="<?php echo HOME_URI ?>/sed/unidLetiva" method="POST">
        <table class="table table-bordered table-hover table-striped">
            <tbody>
                <tr>
                    <th>
                        Letiva
                    </th>
                    <th>
                        Data Inicial
                    </th>
                    <th>
                        Data Final
                    </th>
                    <th>
                        Qt. Dias
                    </th>
                </tr>
            </tbody>
            <tbody>
                <?php
                for ($c = 1; $c <= $qtLetiva; $c++) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $c . 'º ' . $unLetiva ?>
                        </td>
                        <td>
                            <?= formErp::input("dt_inicio[$c]", null, @$dt[$c]['dt_inicio'], null, null, 'date') ?>
                        </td>
                        <td>
                            <?= formErp::input("dt_fim[$c]", null, @$dt[$c]['dt_fim'], null, null, 'date') ?>
                        </td>
                        <td>
                            <?= formErp::input("dias[$c]", null, @$dt[$c]['dias'], null, null, 'number') ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <br /><br />
        <div style="text-align: center">
            <?=
            formErp::hidden([
                'id_curso' => $id_curso,
                'id_pl' => $id_pl
            ])
            . formErp::hiddenToken('dataLetiva')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>

</div>
<?php
javaScript::dataMascara();
