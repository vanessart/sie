<?php
if (!defined('ABSPATH'))
    exit;
$lancData = $model->lancData();

unset($_POST);
?>
<div class="row">
    <div class="col-10 topo">
        Controle de Lançamento de Notas
    </div>
    <div class="col-2">
        <form method="POST">
            <button class="btn btn-primary border">
                Voltar
            </button>
        </form>
    </div>
</div>
<br />
<?php
foreach ($lancData as $id_curso => $peridos) {
    ?>
    <br /><br />
    <form method="POST">
        <table class="table table-bordered table-hover table-striped border" style="width: 90%; margin: auto">
            <tr>
                <td colspan="5" style="text-align: center; font-weight: bold">
                    <?= $peridos['n_curso'] ?>
                </td>
                <td rowspan="<?= $peridos['qt_letiva'] + 2 ?>" valign="middle" style="text-align: center">
                    <?=
                    formErp::hidden([
                        'qt_letiva' => $peridos['qt_letiva'],
                        'set' => 1
                    ])
                    . formErp::hiddenToken('contrlLac')
                    . formErp::button('Salvar')
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?= $peridos['un_letiva'] ?>
                </td>
                <td>
                    Data de Início
                </td>
                <td>
                    Data de Término
                </td>
                <td>
                    Abrir para os Professores
                </td>
                <td>
                    Abrir para os Coordenadores
                </td>
            </tr>
            <?php
            foreach (range(1, $peridos['qt_letiva']) as $v) {
                if (!empty($peridos['letiva'][$v])) {
                    $p = $peridos['letiva'][$v];
                } else {
                    $p = $peridos['letiva'][null];
                }
                ?>
                <tr>
                    <td>
                        <?= $v ?>º <?= $peridos['un_letiva'] ?>
                    </td>
                    <td>
                        <?= formErp::input($v . '[dt_inicio]', null, $p['dt_inicio'], null, null, 'date') ?>
                    </td>
                    <td>
                        <?= formErp::input($v . '[dt_fim]', null, $p['dt_fim'], null, null, 'date') ?>
                    </td>
                    <td>
                        <?= formErp::checkbox($v . '[aberto_prof]', 1, null, $p['aberto_prof']) ?>
                    </td>
                    <td>
                        <?= formErp::checkbox($v . '[aberto_coord]', 1, null, $p['aberto_coord']) ?>
                    </td>
                </tr>
                <?php
                echo formErp::hidden([
                    $v . '[un_letiva_lanc]' => $v,
                    $v . '[fk_id_curso]' => $id_curso,
                    $v . '[id_ld]' => $p['id_ld'],
                ]);
            }
            ?>
        </table>
    </form>
    <?php
}
?>