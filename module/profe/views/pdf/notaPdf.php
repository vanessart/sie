<?php
if (!defined('ABSPATH'))
    exit;

ob_start();
$pdf = new pdf();

$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_STRING);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_STRING);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_STRING);
$bimestreAtual = filter_input(INPUT_POST, 'bimestre', FILTER_SANITIZE_STRING);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$n_disc = filter_input(INPUT_POST, 'n_disc', FILTER_SANITIZE_STRING);
$alunos = ng_escola::alunoPorTurma($id_turma);
$letiva = $model->letivaDados($id_turma);



$instrumentos = $model->retornaInstrumentosAvaliativos($id_pl, $id_turma, $bimestreAtual);

foreach ($instrumentos as $key => $value) {
    foreach ($alunos as $k => $v) {
        if (isset($value->notas)) {
            $notasArr = (array)$value->notas;
            $alunos[$k]['notasAluno'][$value->uniqid] = $notasArr[$v['id_pessoa']];
        }
    }
}
?>
<label>
        <strong><b>Turma: <?= $n_turma ?></b></strong><br>
        <strong><b>Disciplina: <?= $n_disc ?> </b></strong>
</label>
<table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633">
    <td style="font-weight: bold; text-align: center; font-size: 1.6em">
        Alunos
    </td>

    <tr>
        <td style="font-weight: bold; font-size: 1.4em" rowspan="2">
            Nº
        </td>
        <td style="font-weight: bold; font-size: 1.4em" rowspan="2">
            RSE
        </td>
        <td style="font-weight: bold; font-size: 1.4em" rowspan="2">
            Nome
        </td>
        <td style="font-weight: bold; font-size: 1.4em" colspan="3">
            Atividades
        </td>

    </tr>

    <tr>
        <?php foreach ($instrumentos as $key => $value) {
        ?>
            <td>
                <?php if ($value->ativo != 0) { ?>
                    <?= $value->instrumentoNome ?>
                <?php } ?>
            </td>
        <?php } ?>

        <td>
            <b>Media</b>
        </td>
    </tr>
    <?php foreach ($alunos as $key => $value) { ?>
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
            <?php foreach ($instrumentos as $k => $v) {
            ?>
                <?php
                if ($v->ativo == 1) { ?>
                    <td><?= $value['notasAluno'][$v->uniqid] ?></td>
                <?php } ?>
            <?php } ?>



            <td><?php
                $conta = array_sum($value['notasAluno']) / count($instrumentos);
                echo ceil($conta * 2) / 2;
                ?>

            </td>
        </tr>


    <?php } ?>


</table>



<?php $pdf->exec(); ?>