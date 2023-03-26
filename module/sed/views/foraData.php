<?php
if (!defined('ABSPATH'))
    exit;
$ci = sql::idNome('ge_ciclos', ' where fk_id_curso in (3, 7, 8)');
$ciclos = @$_POST['ciclos'];
$ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_NUMBER_INT);
if (!$ano) {
    $ano = date("Y");
}
if (empty($ciclos)) {
    $ciclos = [];
}
foreach ($ciclos as $k => $v) {
    if ($v == 1) {
        $ciclosSet[] = $k;
    }
}
if (!empty($ciclosSet)) {
    $dfc = $model->foraDataCiclo($ciclosSet, $ano);
}
?>
<div class="body">
    <div class="fieldTop">
        Alunos Matriculados Fora da Data Base
    </div>
    <?php
    if (!empty($ciclosSet)) {
        ?>
        <div class="row">
            <div class="col-9">

            </div>
            <div class="col-3">
                <form action="<?= HOME_URI ?>/sed/pdf/foraData.php" target="_blank" method="POST">
                    <?=
                    formErp::hidden([
                        'idsCiclo' => implode(',', $ciclosSet),
                        'ano' => $ano
                    ]);
                    ?>
                    <button class="btn btn-info">
                        Gerar Planilha
                    </button>
                </form>
            </div>
        </div>
        <br />
        <?php
    }
    ?>
    <form method="POST">
        <div class="row">
            <?php
            foreach ($ci as $k => $v) {
                ?>
                <div class="col">
                    <?= formErp::checkbox('ciclos[' . $k . ']', 1, $v, in_array($k, $ciclos) ? 1 : null) ?>
                </div>
                <?php
            }
            ?>

        </div>
        <br />
        <div style="width: 300px; margin: auto">
            <?= formErp::select('ano', [date("Y") => date("Y"), (date("Y") + 1) => (date("Y") + 1)], 'Ano', $ano) ?>
        </div>
        <br />
        <div style="text-align: center; padding: 20px">
            <?=
            formErp::button('pesquisar');
            ?>
        </div>
    </form>
    <?php
    if (!empty($ciclosSet)) {
        foreach ($dfc as $k => $escolas) {
            ?>
            <table class="table table-bordered table-hover table-responsive">
                <tr>
                    <td colspan="9" style="text-align: center; font-weight: bold">
                        <?= $k ?>
                    </td>
                </tr>
                <?php
                foreach ($escolas as $n_inst => $v) {
                    ?>
                    <tr>
                        <td rowspan="<?= count($v) + 1 ?>" style="width: 300px" >
                            <?= $n_inst ?> 
                        </td>
                        <td>
                            Dt. Nasc.
                        </td>
                        <td>
                            Idade
                        </td>
                        <td>
                            Situação
                        </td>
                        <td>
                            Turma
                        </td>
                        <td>
                            Nome
                        </td>
                        <td>
                            RSE
                        </td>
                        <td>
                            RA
                        </td>
                    </tr>
                    <?php
                    foreach ($v as $y) {
                        if ($y['dif'] < 0) {
                            $color = 'red';
                            $compl = " mais novo";
                        } else {
                            $color = 'blue';
                            $compl = " mais Velho";
                        }
                        ?>
                        <tr>
                            <td>
                                <?= data::converteBr($y['dt_nasc']) ?>
                            </td>
                            <td>
                                <?= $y['idade'] ?>
                            </td>
                            <td>
                                <span style="font-weight: bold; color: <?= $color ?>">
                                    <?php
                                    if ($y['dif'] != 0) {
                                        echo $y['dif'] . ($y['dif'] > 1 ? ' Meses' : ' Mês');
                                    } else {
                                        echo $y['dias'] . ($y['dias'] > 1 ? ' Dias' : ' Dia');
                                    }
                                    echo $compl;
                                    ?>
                                </span>
                            </td>
                            <td>
                                <?= $y['n_turma'] ?>
                            </td>
                            <td>
                                <?= $y['n_pessoa'] ?>
                            </td>
                            <td>
                                <?= $y['id_pessoa'] ?>
                            </td>
                            <td>
                                <?= $y['ra'] ?> - <?= $y['ra_dig'] ?> - <?= $y['ra_uf'] ?>
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
    </div>
    <?php
}