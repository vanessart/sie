<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of dashBoard
 *
 * @author vanessa
 */
class dashBoard {

    public static function divInicio() {?>
        <link href="<?= HOME_URI ?>/module/profe/views/_faltaSeq/css/sb-admin-2.min.css" rel="stylesheet">
        <div id="wrapper">
            <div id="content-wrapper" class="d-flex flex-column" style="padding-top: 26px; background-color: #fff;">
                <div id="content">
                    <div class="container-fluid">
         <?php
    }

    public static function divFim() {?>
                <script src="<?= HOME_URI ?>/module/profe/views/_faltaSeq/vendor/chart.js/Chart.min.js"></script>

                </div>
            </div>
        </div>
        <?php
    }

    public static function scriptInicio() {?>
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
        <?php
    }

    public static function divDash_totais($texto,$numero,$class) {?>
            
        <div class="card border-left-<?= $class ?> shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-<?= $class ?> text-uppercase mb-1">
                            <?= $texto ?>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $numero  ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div> 
        <?php
    }

    public static function divDash_percent($texto,$percent,$class) {?> 
        <div class="card border-left-<?= $class ?> shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-<?= $class ?> text-uppercase mb-1">
                            <?= $texto ?>
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr font-weight-bold text-gray-800">
                                   <?= $percent ?>%
                                </div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-<?= $class ?>" role="progressbar"
                                         style="width: <?= $percent ?>%" aria-valuenow="50" aria-valuemin="0"
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
        <?php
    }

    public static function divDash_chart($texto,$id) {?>
        <div class="card shadow mb-4">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <?= $texto ?>
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="<?= $id ?>"></canvas>
                </div>
            </div>
        </div>   
        <?php
    }

    public static function divDash_bar($texto,$id) {?>
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                     <?= $texto ?>
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="<?= $id ?>"></canvas>
                </div>
            </div>
        </div>
        <?php
    }

    public static function divDash_pie($texto,$id) {?>
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                  <?=  $texto ?> 
                </h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="<?= $id ?>"></canvas>
                </div>
            </div>
        </div>
        <?php
    }
    public static function script_pie($coresMouse, $cores, $legendas, $dados, $id, $mostra_legendas = false) {?>
        var ctx = document.getElementById("<?= $id ?>");
        var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [<?= $legendas ?>],
                datasets: [{
                        data: [<?= $dados ?>],
                        backgroundColor: [<?= $cores ?>],
                        hoverBackgroundColor: [<?= $coresMouse ?>],
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
                    display: <?php echo !empty($mostra_legendas) ? "true" : "false" ?>
                },
                cutoutPercentage: 80,
            },
        });
        <?php
    }

    public static function script_chart($titulo, $dados, $id, $legendas) {?>
        var ctx = document.getElementById("<?= $id ?>");
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [<?= $titulo ?>],
                datasets: [{
                    label: "<?= $legendas ?>",
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
                    data: [<?= $dados ?>],
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
                                return value;
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
                            return datasetLabel + ': ' + tooltipItem.yLabel;
                        }
                    }
                }
            }
        });
        <?php
    }

    public static function script_bar($totalResp, $dados, $id, $legendas) {?>
        var ctx = document.getElementById("<?= $id ?>");
        var myBarChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: [<?= $legendas ?>],
            datasets: [{
              label: "Quantidade",
              backgroundColor: "#5bc0de",
              hoverBackgroundColor: "#2e59d9",
              borderColor: "#4e73df",
              data: [<?= $dados ?>],
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
                  unit: 'month'
                },
                gridLines: {
                  display: false,
                  drawBorder: false
                },
                ticks: {
                  maxTicksLimit: 6
                },
                maxBarThickness: 25,
              }],
              yAxes: [{
                ticks: {
                  min: 0,
                  max: <?= $totalResp ?>,
                  maxTicksLimit: 5,
                  padding: 10,
                  callback: function(value, index, values) {
                    return number_format(value);
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
              titleMarginBottom: 10,
              titleFontColor: '#6e707e',
              titleFontSize: 14,
              backgroundColor: "rgb(255,255,255)",
              bodyFontColor: "#858796",
              borderColor: '#dddfeb',
              borderWidth: 1,
              xPadding: 15,
              yPadding: 15,
              displayColors: false,
              caretPadding: 10,
              callbacks: {
                label: function(tooltipItem, chart) {
                  var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                  return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                }
              }
            },
          }
        });
    <?php
    }
}
