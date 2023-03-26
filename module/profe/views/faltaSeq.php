<?php
if (!defined('ABSPATH'))
    exit;

$escolas = ng_escolas::idEscolas([3, 7, 8]);

$date = new DateTime('-15 day');
$dataInicio = $date->format('Y-m-d');
$id_pl = ng_main::periodoSet();
$faltas = $model->faltaCritInfantil($id_pl);
if (!empty($faltas['freqsPeriodo'])) {
    $faltaTotalProc = intval(($faltas['faltasPeriodo'] / $faltas['freqsPeriodo']) * 100);
    foreach ($faltas['freqsDatas'] as $k => $v) {
        $freqsDatas[$k] = substr(data::converteBr($k), 0, 5);
        foreach ($faltas['freqsDatas'] as $k => $v) {
            if (!empty($faltas['faltasDatas'][$k])) {
                $porc[$k] = intval(($faltas['faltasDatas'][$k] / $faltas['freqsDatas'][$k]) * 100);
            } else {
                $porc[$k] = 0;
            }
        }
    }
    ksort($freqsDatas);
    ksort($porc);
    $fatalPorc['datas'] = $freqsDatas;
    $fatalPorc['porc'] = $porc;

    $faltaCurTotal = array_sum($faltas['faltasCursos']);
}
?>
<div class="body">
    <div class="fieldTop">
        Controle de Faltas do Período de <?= data::porExtenso($dataInicio) ?> à <?= data::porExtenso(date("Y-m-d")) ?>
    </div>
    <link href="<?= HOME_URI ?>/module/profe/views/_faltaSeq/css/sb-admin-2.min.css" rel="stylesheet">
    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column" style="padding-top: 26px">
            <div id="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Frequências Registardas
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $faltas['freqsPeriodo'] ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Faltas Registradas</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $faltas['faltasPeriodo'] ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                Porcentagem de faltas
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr font-weight-bold text-gray-800">
                                                        <?= $faltaTotalProc ?>%
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-danger" role="progressbar"
                                                             style="width: <?= $faltaTotalProc ?>%" aria-valuenow="50" aria-valuemin="0"
                                                             aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Total de Aulas
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $faltas['totalAulas'] ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        Faltas Diárias (Porcentagem)
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        Faltas por Curso
                                    </h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center " style="font-weight: bold">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary">Berçário</i> 
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success">Maternal</i> 
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info">Pré-Escola</i> 
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card shadow">
                                <div class="row card-header py-3">
                                    <div class="col-6">
                                        <h6 class="m-0 font-weight-bold text-primary">Alunos com TRÊS ou mais faltas consecutivas</h6>
                                    </div>
                                    <div class="col-6 text-center " style="font-weight: bold">
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-primary">Berçário</i> 
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-success">Maternal</i> 
                                        </span>
                                        <span class="mr-2">
                                            <i class="fas fa-circle text-info">Pré-Escola</i> 
                                        </span>
                                    </div>
                                </div>
                                <br />
                                <?php
                                foreach ($escolas as $id_inst => $n_inst) {

                                    foreach ([3, 7, 8] as $q) {
                                        if (!empty($faltas['sequencial'][$id_inst][$q])) {
                                            $seqFalta[$q] = count($faltas['sequencial'][$id_inst][$q]);
                                        } else {
                                            $seqFalta[$q] = 0;
                                        }
                                    }
                                    ?>
                                    <div class="card-body">
                                        <h5>
                                            <div class="row">
                                                <div class="col-7">
                                                    <?= $n_inst ?>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <span class="mr-2">
                                                        <i class="fas fa-circle text-primary"><?= $seqFalta[7] ?></i> 
                                                    </span>
                                                    <span class="mr-2">
                                                        <i class="fas fa-circle text-success"><?= $seqFalta[8] ?></i> 
                                                    </span>
                                                    <span class="mr-2">
                                                        <i class="fas fa-circle text-info"><?= $seqFalta[3] ?></i> 
                                                    </span>
                                                </div>
                                                <div class="col-1 text-right">
                                                    <button onclick="acessa(<?= $id_inst ?>)" class="btn btn-outline-primary">
                                                        Acessar
                                                    </button>
                                                </div>
                                            </div>
                                        </h5>
                                        <div class="progress">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $seqFalta[7] / 2 ?>%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $seqFalta[8] / 2 ?>%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                            <div class="progress-bar bg-info" role="progressbar" style="width: <?= $seqFalta[3] / 2 ?>%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="<?= HOME_URI ?>/module/profe/views/_faltaSeq/vendor/chart.js/Chart.min.js"></script>

            </div>
            <form action="<?= HOME_URI ?>/profe/relatFalta" target="frame" id="formRel" method="POST">
                <input type="hidden" name="id_inst" id="id_inst" value="" />
            </form>
            <?php
            toolErp::modalInicio();
            ?>
            <iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
                <?php
                toolErp::modalFim();
                ?>
<script>
        function acessa(id) {
            id_inst.value = id;
            formRel.submit();
            $('#myModal').modal('show');
            $('.form-class').val('');
        }

        // Set new default font family and font color to mimic Bootstrap's default styling
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        function number_format(number, decimals, dec_point, thousands_sep) {
            // *     example: number_format(1234.56, 2, ',', ' ');
            // *     return: '1 234,56'
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                    s = '',
                    toFixedFix = function (n, prec) {
                        var k = Math.pow(10, prec);
                        return '' + Math.round(n * k) / k;
                    };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        // Area Chart Example
        var ctx = document.getElementById("myAreaChart");
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ["<?= implode('", "', $fatalPorc['datas']) ?>"],
                datasets: [{
                        label: "Faltosos",
                        lineTension: 0.3,
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        borderColor: "rgba(78, 115, 223, 1)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointBorderColor: "rgba(78, 115, 223, 1)",
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: [<?= implode(', ', $fatalPorc['porc']) ?>],
                    }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                            time: {
                                unit: 'date'
                            },
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 100
                            }
                        }],
                    yAxes: [{
                            ticks: {
                                maxTicksLimit: 5,
                                padding: 10,
                                // Include a dollar sign in the ticks
                                callback: function (value, index, values) {
                                    return value + '%';
                                }
                            },
                            gridLines: {
                                color: "rgb(234, 236, 244)",
                                zeroLineColor: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function (tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + tooltipItem.yLabel + '%';
                        }
                    }
                }
            }
        });

        // Set new default font family and font color to mimic Bootstrap's default styling
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        // Pie Chart Example
        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Berçário", "Maternal", "Pré-Escola"],
                datasets: [{
                        data: [<?= intval((@$faltas['faltasCursos'][7] / $faltaCurTotal) * 100) ?>, <?= intval((@$faltas['faltasCursos'][8] / $faltaCurTotal) * 100) ?>, <?= intval((@$faltas['faltasCursos'][3] / $faltaCurTotal) * 100) ?>],
                        backgroundColor: ['#4e73df', '#1cc88a', '#5bc0de'],
                        hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });
 </script>
     