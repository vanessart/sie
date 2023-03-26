<?php
if (!defined('ABSPATH'))
    exit;
$ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_NUMBER_INT);
if (!$ano) {
    if (date("m") > 8) {
        $ano = date("Y") + 1;
    } else {
        $ano = date("Y");
    }
}
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$dfc = $model->foraDataCicloPrevisto(toolErp::id_inst(), $ano);
?>
<div class="body">
    <div class="fieldTop">
        Alunos Matriculados Fora da Data Base
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::select('ano', [date("Y") => date("Y"), (date("Y") + 1) => (date("Y") + 1)], 'Ano', $ano, 1) ?>
        </div>
    </div>
    <br />
    <?php
    if (!empty($dfc)) {
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
    } else {
        ?>
        <div class="alert alert-primary" style="font-weight: bold; text-align: center">
            Não foram encontradas ocorrências de Alunos Matriculados Fora da Data Base 
        </div>
        <?php
    }
    ?>
</div>