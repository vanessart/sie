<?php
if (!defined('ABSPATH'))
    exit;
$tipo = filter_input(INPUT_POST, 'tipo');
if (!$tipo) {
    $tipo = 'polo';
}
$periodo = filter_input(INPUT_POST, 'periodo');
$perTex = [
    null => ' Todo o Período ',
    'M' => 'Período da Manha',
    'T' => 'Período da Tarde'
];
?>
<div class="body">
    <br />
    <div class="row">
        <div class="col text-center">
            <form method="POST">
                <?= formErp::hidden(['periodo' => $periodo, 'tipo' => 'polo']) ?>
                <button class="btn btn-<?= $tipo !='polo'?'outline-':'' ?>primary" type="submit">
                    Por Núcleo
                </button>
            </form>
        </div>
        <div class="col text-center">
            <form method="POST">
                <?= formErp::hidden(['periodo' => $periodo, 'tipo' => 'curso']) ?>
                <button class="btn btn-<?= $tipo !='curso'?'outline-':'' ?>success" type="submit">
                    Por Curso
                </button>
            </form>
        </div>
    </div>
    <br />
    <br />
    <div class="border">
        <div class="row">
            <div class="col text-center">
                <form method="POST">
                    <?= formErp::hidden(['tipo' => $tipo]) ?>
                    <button class="btn btn-<?= !empty($periodo)?'outline-':'' ?>success" type="submit">
                        Todo o Período
                    </button>
                </form>
            </div>
            <div class="col text-center">
                <form method="POST">
                    <?= formErp::hidden(['periodo' => 'M', 'tipo' => $tipo]) ?>
                    <button class="btn btn-<?= $periodo !='M'?'outline-':'' ?>info" type="submit">
                        Período da Manha
                    </button>
                </form>
            </div>
            <div class="col text-center">
                <form method="POST">
                    <?= formErp::hidden(['periodo' => 'T', 'tipo' => $tipo]) ?>
                    <button class="btn btn-<?= $periodo !='T'
        . ''?'outline-':'' ?>warning" type="submit">
                        Período da Tarde
                    </button>
                </form>
            </div>
        </div>
        <br />
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <?php
        if ($tipo == 'curso') {
            $frenq = $model->freqCurGraf($periodo);
            $c = sql::get('tdics_curso');
            $cursos = toolErp::idName($c, 'abrev', 'n_curso');
            $cursoList = "'" . implode("', '", $cursos) . "'";
            ?>
            <script type="text/javascript">
                google.charts.load('current', {'packages': ['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                        ['Year', <?= $cursoList ?>],
    <?php
    foreach ($frenq as $k => $v) {
        foreach ($cursos as $sigla => $p) {
            $ff[$sigla] = @$v[$sigla];
        }
        $fff = implode(', ', $ff);
        ?>
                            ['<?= substr($k, 8, 2) . '/' . substr($k, 5, 2) ?>', <?= $fff ?>],
        <?php
    }
    ?>
                    ]);

                    var options = {
                        title: 'Frequêcia de Alunos por Curso em <?= $perTex[$periodo] ?> (PORCENTAGEM)',
                        curveType: 'function',
                        legend: {position: 'bottom'}
                    };

                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                    chart.draw(data, options);
                }
            </script>
            <div id="curve_chart" style="width: 100%; height: 500px"></div>
            <?php
        } else {
            $polos = sql::idNome('tdics_polo');
            $poloList = "'" . implode("', '", $polos) . "'";
            $frenq = $model->freqGraf($periodo);
            ?>

            <script type="text/javascript">
                google.charts.load('current', {'packages': ['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                        ['Year', <?= $poloList ?>],
    <?php
    foreach ($frenq as $k => $v) {
        foreach ($polos as $id_polo => $p) {
            $ff[$id_polo] = @$v[$id_polo];
        }
        $fff = implode(', ', $ff);
        ?>
                            ['<?= substr($k, 8, 2) . '/' . substr($k, 5, 2) ?>', <?= $fff ?>],
        <?php
    }
    ?>
                    ]);

                    var options = {
                        title: 'Frequêcia de Alunos por Núcleo em <?= $perTex[$periodo] ?> (PORCENTAGEM)',
                        curveType: 'function',
                        legend: {position: 'bottom'}
                    };

                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                    chart.draw(data, options);
                }
            </script>
            <div id="curve_chart" style="width: 100%; height: 500px"></div>
            <?php
        }
        ?>
    </div>
</div>

