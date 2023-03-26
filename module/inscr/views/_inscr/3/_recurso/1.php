<?php
if (!defined('ABSPATH'))
    exit;
?>
<br /><br />
<?php
if (!empty($up)) {
    foreach ($up as $v) {
        $up2[$v['fk_id_up']][] = $v;
    }
}
$totalPontos = 0;
foreach ($cert as $v) {

    $totalPontos += $v['pontos'];
    if ($v['deferido'] == 1) {
        $def = 'Deferido';
        $color = 'blue';
    } else {
        $def = 'Indeferido';
        $color = 'red';
    }
    ?>
    <table class="table table-bordered table-hover table-striped border">
        <tr>
            <td colspan="3"  style="text-align: center; font-weight: bold">
                <?= $v['descr_up'] ?> <?= $v['obrigatorio'] == 1 ? '<span style="font-weight: bold; color: red">(Obrigatório)</span>' : '' ?>
            </td>
        </tr>
        <tr>
            <td>
                Situação
            </td>
            <td>
                Observacões
            </td>
            <td style="width: 200px">
                Pontos
            </td>
        </tr>
        <tr>
            <td style="color: <?= $color ?>; font-size: 1.2em">
                <?= $def ?>
            </td>
            <td>
                <?= $v['obs_cd'] ?>
            </td>
            <td style="width: 200px">
                <?= $v['pontos'] ?>
            </td>
        </tr>
        <?php
        if (!empty($up2[$v['fk_id_up']])) {
            ?>
            <tr>
                <td colspan="3" style="text-align: center; font-weight: bold; color: red">
                    <?php
                    if (count($up) > 1) {
                        ?>
                        Documentos Indeferidos
                        <?php
                    } else {
                        ?>
                        Documento Indeferido
                        <?php
                    }
                    ?>
                </td>
            </tr>
            <?php
            foreach ($up2[$v['fk_id_up']] as $y) {
                ?>
                <tr>
                    <td colspan="2">
                        <p>
                            Nome do Documento: <?= $y['nome_origin'] ?>
                        </p>
                        <p>
                            Motivo: <?= $y['n_mot'] ?>. <?= $y['obs'] ?>
                        </p>
                    </td>
                    <td>
                        <form target="_blank" action="<?= HOME_URI ?>/pub/inscrOnline/<?= $y['link'] ?>">
                            <button class="btn btn-danger">
                                Baixar Arquivo
                            </button>
                        </form>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
    <?php
}
?>
<table class="table table-bordered table-hover table-striped border">
    <tr>
        <td>
            Total de Pontos
        </td>
        <td  style="width: 200px">
            <?= $totalPontos ?>
        </td>
    </tr>
</table>
<?php
if (!empty($up && null)) {
    ?>
    <div class="fieldTop">
        <?php
        if (count($up) > 1) {
            ?>
            Documentos Indeferidos
            <?php
        } else {
            ?>
            Documento Indeferido
            <?php
        }
        ?>
    </div>
    <?php
    foreach ($up as $v) {
        ?>
        <div class="border">
            <div class="row">
                <div class="col-10">
                    Nome do Documento: <?= $v['nome_origin'] ?>
                </div>
                <div class="col-2">
                    <form target="_blank" action="<?= HOME_URI ?>/pub/inscrOnline/<?= $v['link'] ?>">
                        <button class="btn btn-danger">
                            Baixar Arquivo
                        </button>
                    </form>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col">
                    Motivo: <?= $v['n_mot'] ?>. <?= $v['obs'] ?>
                </div>
            </div>
        </div>
        <br />
        <?php
    }
}
   