<?php
if (!defined('ABSPATH'))
    exit;

ob_start();
$pdf = new pdf();

$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$n_disc = filter_input(INPUT_POST, 'n_disc', FILTER_SANITIZE_STRING);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_STRING);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_STRING);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_STRING);
$letiva = $model->letivaDados($id_turma);
$alunos = ng_escola::alunoPorTurma($id_turma);
$dados = $model->chamadaPorAluno($id_pl, $id_turma, $id_disc);


if (!empty($dados['faltaPresenca'])) {
    foreach ($alunos as $key => $value) {
        foreach ($dados['faltaPresenca'] as $k => $v) {
            if (isset($v[$value['id_pessoa']])) {
                $alunos[$key]['faltaPresenca'][$k] = $v[$value['id_pessoa']];
            }
        }
    }
}
?>

<label>
    <strong><b>Turma: <?= $n_turma ?></b></strong><br>
    <strong><b>Disciplina: <?= $n_disc ?> </b></strong>
</label>


<table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633">
    <td style="font-weight: bold; text-align: center; font-size: 1.6em" colspan="<?= 5 + $letiva['qt_unidade'] ?>">
        Alunos
    </td>
    <tr>
        <td style="font-weight: bold; font-size: 12px" rowspan="2">
            Nº
        </td>
        <td style="font-weight: bold; font-size: 12px" rowspan="2">
            RSE
        </td>
        <td style="font-weight: bold; font-size: 12px" rowspan="2">
            Nome
        </td>

        <?php foreach (range(1, $letiva['qt_letiva']) as $k) { ?>
            <td colspan="2">
                <?= $k . 'º ' . $letiva['un_letiva'] ?>
            </td>

        <?php } ?>

        <td rowspan="2">
            Total
        </td>

        <td rowspan="2">
            %
        </td>
    </tr>
    <tr>
        <?php foreach (range(1, $letiva['qt_letiva']) as $k) { ?>
            <td>
                P
            </td>

            <td>
                F
            </td>

        <?php } ?>
    </tr>
    <?php
    foreach ($alunos as $key => $value) {
        $cont = 0;
        $total = 0;
        ?>

        <tr>
            <td>
                <?= $value['chamada'] ?>
            </td>
            <td>
                <?= $value['ra'] ?>
            </td>
            <td>
                <?= $value['n_pessoa'] ?>
            </td>

            <?php
            foreach (range(1, $letiva['qt_letiva']) as $k) {
                if (!empty($value['faltaPresenca'][$k]['T'])) {
                    $total += $value['faltaPresenca'][$k]['T'];
                }
                ?>
                <td>
                    <?php
                    if (isset($value['faltaPresenca'][$k]['P'])) {
                        echo $value['faltaPresenca'][$k]['P'];
                        $cont += $value['faltaPresenca'][$k]['P'];
                    } else {
                        echo 0;
                    }
                    ?>
                </td>

                <td>
                    <?php
                    if (isset($value['faltaPresenca'][$k]['F'])) {
                        echo $value['faltaPresenca'][$k]['F'];
                    } else {
                        echo 0;
                    }
                    ?>
                </td>
                </td>


            <?php } ?>
            <td>
                <?= $cont ?>
            </td>
            <?php
            if (!empty($total)) {
                $result = ($cont / $total) * 100;
            }
            ?>
            <td style="color:<?= ($result < 75) ? 'red' : 'blue' ?>">
                <?= round($result, 2) . "%" ?>
            </td>


        </tr>
        <?php
    }
    ?>
</table>



<?php
$pdf->exec();
?>