<?php
if (!defined('ABSPATH'))
    exit;
$n_turma = filter_input(INPUT_POST, 'n_turma');
$ano_letivo = filter_input(INPUT_POST, 'ano');
$n_disc = filter_input(INPUT_POST, 'n_disc');
$id_disc = filter_input(INPUT_POST, 'id_disc');
$id_pl = filter_input(INPUT_POST, 'id_pl');
$id_turma = filter_input(INPUT_POST, 'id_turma');
$letiva = $model->letivaDados($id_turma);
$alunos = ng_escola::alunoPorTurma($id_turma);
$dados = $model->chamadaPorAluno($id_pl, $id_turma, $id_disc);

$n_pl = sql::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $id_pl], 'fetch')['n_pl'];

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


<div class="body">

    <div class="row">
        <div class="col-10">
            <div style="font-weight: bold; font-size: 2.4em; text-align: center; padding: 20px">
                Relatório de chamada
            </div>

        </div>
        <div class="col-2">
            <form action="<?= HOME_URI ?>/profe/<?= (toolErp::id_nilvel() == 48) ? 'relatorioChamada' : 'relatorioChamadaProf' ?>" method="POST">
                <button class="btn btn-primary">Voltar</button>
                <input type="hidden" name="id_turma" value="<?= $id_turma ?>" />
            </form>
        </div>
    </div>


    <table class="table table-bordered table-hover table-striped">
        <table style="width: 100%">

            <tr>
                <td style="font-weight: bold; font-size: 30px">
                    Periodo Letivo
                </td>
                <td style="font-weight: bold; font-size: 30px">
                    Disciplina
                </td>
                <td style="font-weight: bold; font-size: 30px; margin-right:80px">
                    Turma
                </td>
                <td>
                    <form target="_blank" action="<?= HOME_URI ?>/profe/pdf/chamadaPdf.php" method="post">
                        <?=
                        formErp::hidden([
                            'id_pl' => $id_pl,
                            'n_disc' => $n_disc,
                            'id_turma' => $id_turma,
                            'letiva' => $letiva,
                            'alunos' => $alunos,
                            'dados' => $dados,
                            'n_turma' => $n_turma,
                            'id_disc' => $id_disc
                        ])
                        ?>
                        <button type="submit" class="btn btn-success">Gerar PDF</button>

                    </form>
                </td>
            </tr>
            <tr>
                <td>
                    <label style="font-weight: bold; font-size: 20px">
                        <?= $n_pl ?>
                    </label>
                </td>
                <td>
                    <label style="font-weight: bold; font-size: 20px">

                        <?= $n_disc ?>
                    </label>

                </td>
                <td>
                    <label style="font-weight: bold; font-size: 20px">
                        <?= $n_turma ?>
                    </label>
                </td>
            </tr>
        </table>
    </table>
    <br>
    <?php if (empty($dados['faltaPresenca'])) { ?>

        <div class="alert alert-danger" role="alert">
            Não há registros de chamada para turma
        </div>
    <?php 
    
    } else { ?>
    <br />
        <table class="table table-bordered table-hover table-striped">
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
                <td style="font-weight: bold; font-size: 1.4em" rowspan="2">
                    Situação
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
                    <?php
                }
                ?>
            </tr>
            <?php
            foreach ($alunos as $key => $value) {
                $total = 0;
                $cont = 0;
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
                    <td>
                        <?= $value['n_tas'] ?>
                    </td>

                    <?php
                    foreach (range(1, $letiva['qt_letiva']) as $k) {
                        if (!empty($value['faltaPresenca'][$k]['T'])) {
                            $total += $value['faltaPresenca'][$k]['T'];
                        }
                        ?>
                        <td>
                            <?php
                            if ($value['fk_id_tas'] == 0) {
                                if (!empty($value['faltaPresenca'][$k]['P'])) {
                                    echo $value['faltaPresenca'][$k]['P'];
                                    $cont += $value['faltaPresenca'][$k]['P'];
                                } else {
                                    echo 0;
                                }
                            } else {
                                echo '-'; 
                            }
                            ?>
                        </td>

                        <td>
                            <?php
                            if ($value['fk_id_tas'] == 0) {
                                if (isset($value['faltaPresenca'][$k]['F'])) {
                                    echo $value['faltaPresenca'][$k]['F'];
                                } else {
                                    echo 0;
                                }
                            } else {
                                echo '-'; 
                            }
                            ?>
                        </td>

                        <?php
                    }
                    ?>
                    <td>
                        <?php
                        if ($value['fk_id_tas'] == 0) {
                            echo $cont;
                        } else {
                                echo '-'; 
                            }
                        ?>
                    </td>
                    <?php
                    if (!empty($total)) {
                        $result = ($cont / $total) * 100;
                    }
                    ?>
                    <td style="color:<?= ($result < 75) ? 'red' : 'blue' ?>">
                        <?php
                        if ($value['fk_id_tas'] == 0) {
                            echo round($result) . "%";
                        } else {
                                echo '-'; 
                            }
                        ?>
                    </td>


                </tr>
        <?php
    }
    ?>



        </table>
    <?php
}
?>
</div>