<?php
if (!defined('ABSPATH'))
    exit;
$n_campanha = $model->campanha();
$id_campanha = $model->campanha('id_campanha');
$id_inst = toolErp::id_inst();
$gerente = $model->gerente();
$ciclos = $model->getCiclosModel($id_campanha,1);
if (empty($ciclos )) {
   echo toolErp::divAlert('warning','Não há ciclos configurados para esta campanha');
}else{
    $instancias = $model->dashGetResult(null,$id_campanha);
    $totais = [
        'queixa' => 0,
        'triagem' => 0,
        'ouro' => 0,
        'alunos' => 0,
    ];

    foreach ($instancias as $key => $value) {
        if ($gerente ==  1) {
           $totais['queixa'] += $value['queixa'];
           $totais['triagem'] += $value['triagem'];
           $totais['ouro'] += $value['ouro'];
           $totais['alunos'] += $value['alunos'];
        }else{
            if ($value['id_inst'] == $id_inst ) {
                $totais['queixa'] = $value['queixa'];
                $totais['triagem'] = $value['triagem'];
                $totais['ouro'] = $value['ouro'];
                $totais['alunos'] = $value['alunos'];
                break;
            }
        } 
    }


    ?>
    <div class="body">
        <div class="fieldTop"><?= $n_campanha ?></div>
    <link href="<?= HOME_URI ?>/module/profe/views/_faltaSeq/css/sb-admin-2.min.css" rel="stylesheet">
    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column" style="padding-top: 26px; background-color: #fff;">
            <div id="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                           <?php $model->divDash_totais('Queixa Vestibular',$totais['queixa'], 'info') ?>
                        </div>
                        <div class="col">
                           <?php $model->divDash_totais('Triagem Auditiva',$totais['triagem'], 'success') ?>
                        </div>
                        <div class="col">
                           <?php $model->divDash_totais('Padrão Ouro (Queixa + Triagem)',$totais['ouro'], 'warning') ?>
                        </div>
                        <div class="col">
                           <?php $model->divDash_totais('Total de Alunos Selecionados',$totais['alunos'], 'danger') ?>
                        </div>
                    </div>
                    <br>
                    <?php 
                    if ($gerente == 1) {
                        foreach ($instancias as $v) {
                            ?>
                            <div class="row">
                                <div class="col">
                                   <?= $v['n_inst'] ?> 
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                   <?php $model->divDash_totais('Queixa Vestibular',$v['queixa'], 'info') ?>
                                </div>
                                <div class="col">
                                   <?php $model->divDash_totais('Triagem Auditiva',$v['triagem'], 'success') ?>
                                </div>
                                <div class="col">
                                   <?php $model->divDash_totais('Padrão Ouro (Queixa + Triagem)',$v['ouro'], 'warning') ?>
                                </div>
                                <div class="col">
                                   <?php $model->divDash_totais('Total de Alunos Selecionados',$v['alunos'], 'danger') ?>
                                </div>
                            </div>
                            <br>
                            <?php
                        } 
                    } ?>
                <script src="<?= HOME_URI ?>/module/profe/views/_faltaSeq/vendor/chart.js/Chart.min.js"></script>

            </div>
        </div>
    </div>
    <?php 
}?>
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
            labels: ["Respondido Pais - SEM QrCode", "Respondido Pais - COM QrCod", "Respondido Professores"],
            datasets: [{
                    data: [ <?=  $totais['queixa'] ?>, <?= $totais['triagem']  ?>,<?= $totais['ouro']  ?>],
                    backgroundColor: ['#4e73df', '#1cc88a', '#dd7369'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#e74a3b'],
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

    // Area Chart Example
    
    <?php dashBoard::script_bar(1000,$totais['qtdEsc'], 'myAreaBar',$totais['escola']) ?>
    <?php $model->script_chart($totais['dataSemana'],$totais['qtdRespSemana'], 'myAreaChart1') ?>

</script>

