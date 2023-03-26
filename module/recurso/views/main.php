<?php
if (!defined('ABSPATH'))
    exit;
$gerente = $model->gerente();
$id_inst = $model->gerente(1,1);
$categorias = sql::idNome('recurso_cate_equipamento', ['at_cate' => 1]);
$cate_usuario = sql::get('recurso_cate_usuario', 'id_cate_usuario,fk_id_cate', ['fk_id_pessoa' => toolErp::id_pessoa() ], 'fetch');
if (!empty($cate_usuario)) {
   $id_cate_usuario = $cate_usuario['id_cate_usuario'];
}
$hdAlert = null;
$id_cate = @$_POST[1]['fk_id_cate'];
if (empty($id_cate)) {
    if (!empty($cate_usuario)) {
        $id_cate = $cate_usuario['fk_id_cate'];
        $hdAlert = 1;
    } elseif ( !empty($categorias) && count($categorias) == 1) {
        $id_cate = array_key_first($categorias);
        $hdAlert = 1;
    }
}
if (!empty($id_cate)) {
    $ins= [
        'fk_id_cate'=> $id_cate,
        'fk_id_pessoa'=> toolErp::id_pessoa(),
        'id_cate_usuario'=> @$id_cate_usuario,
    ];
    $model->db->ireplace('recurso_cate_usuario', $ins, $hdAlert);
}

$totais = $model->dashGet(@$id_cate,@$id_inst);

if (!empty($id_cate) && empty($_SESSION['userdata']['id_cate'])) {
    $_SESSION['userdata']['id_cate'] = $id_cate; 
 }

if (empty($_SESSION['userdata']['id_cate'])) {?>   
    <div class="alert alert-danger">
        Escolha uma Categoria para acessar as demais páginas.
    </div>
    <?php
}else{
    $n_cate = sql::get('recurso_cate_equipamento', 'n_cate', ['id_cate' => $_SESSION['userdata']['id_cate'] ], 'fetch');
    $_SESSION['userdata']['n_categoria'] = $n_cate['n_cate'];
}
?>
<link href="<?= HOME_URI ?>/module/profe/views/_faltaSeq/css/sb-admin-2.min.css" rel="stylesheet">  
<div class="body">
    <div class="row">
        <div class="col-9 alert alert-warning">
            Se deseja alterar a Categoria Padrão do Sistema de Gestão de Recursos, escolha outra opção na lista "Categoria".
        </div>
        <div class="col-3">
            <?= formErp::select('1[fk_id_cate]', $categorias, ['Categoria','>>>>>'], @$id_cate, 1, null,' required ') ?>
        </div>
    </div>    
    <div class="row">
        <div class="col">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Equipamentos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"> <?= @$totais['serial'] ?> </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Emprestado</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= @$totais['emprestado'] ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Porcentagem Emprestado
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr font-weight-bold text-gray-800">
                                        <?= @$totais['emprestado100'] ?>%
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-danger" role="progressbar"
                                             style="width: <?= @$totais['emprestado100'] ?>%" aria-valuenow="50" aria-valuemin="0"
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
        <?php if ($gerente == 1) {
            $totaisEquip = $model->dashGetEquip(@$id_cate);
            ?>
            <div class="col">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total de Equipamentos na Secretaria
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totaisEquip ?></div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }?>
    </div>
    <br>
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Quantidade de Equipamento por Situação
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="mybarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex  align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Porcentagem de Equipamento por Situação
                    </h6>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center " style="font-weight: bold">
                        <span class="mr-2">
                            <i class="fas fa-circle" style="color: #4e73df;">Emprestado</i> 
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle" style="color: #1cc88a;">Regular</i> 
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle" style="color: #5bc0de;">Em Manutencao</i> 
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle" style="color: #dd7369;">Extraviado</i> 
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle" style="color: #f9d578;">Inservível</i> 
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle" style="color: #d9a0e7;">Não Devolvido</i> 
                        </span>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= HOME_URI ?>/module/profe/views/_faltaSeq/vendor/chart.js/Chart.min.js"></script>
<script>
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

    // Pie Chart Example
    var ctx = document.getElementById("myPieChart");
    var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ["Emprestado", "Regular",  "Em Manutencao", "Extraviado", "Inservivel", "Não Devolvido"],
            datasets: [{
                    data: [ <?= @$totais['emprestado100'] ?>, <?= @$totais['regular100'] ?>, <?= @$totais['manutencao100'] ?>, <?= @$totais['extraviado100'] ?>, <?= @$totais['inservivel100'] ?>, <?= @$totais['n_devolvido100'] ?>],
                    backgroundColor: ['#4e73df', '#1cc88a', '#5bc0de','#dd7369','#f9d578', '#d9a0e7'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf','#e74a3b','#f6c23e','#9c27b0'],
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

    // Bar Chart Example
    var ctx = document.getElementById("mybarChart");
    var myBarChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ["Regular", "Emprestado", "Em Manutencao", "Extraviado", "Inservivel", "Não Devolvido"],
        datasets: [{
          label: "Quantidade",
          backgroundColor: "#5bc0de",
          hoverBackgroundColor: "#2e59d9",
          borderColor: "#4e73df",
          data: [<?= @$totais['regular'] ?>, <?= @$totais['emprestado'] ?>, <?= @$totais['manutencao'] ?>, <?= @$totais['extraviado'] ?>, <?= @$totais['inservivel'] ?>, <?= @$totais['n_devolvido'] ?>],
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
              max: <?= $totais['serial'] ?>,
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
</script>

