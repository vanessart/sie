<?php
if (!defined('ABSPATH'))
    exit;

$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);

// $totais = $model->getInscricoes($id_polo, $id_curso);
$totais = $model->getVagas($id_polo, $id_curso);
if (empty($totais)) {
   echo toolErp::divAlert('warning','Nenhuma inscrição realizada até o momento');
}else{

    $vagas = 0;
    $inscritos = 0;
    $cursos = $polos = [];
    foreach ($totais as $k => $v) {
        $vagas += $v['vagas'];
        $inscritos += $v['inscritos'];
        if (!isset($cursos[$v['id_curso']])) {
            $cursos[$v['id_curso']] = [
                'n_curso' => $v['n_curso'],
                'qtde' => 0,
            ];
        }
        if (!isset($polos[$v['id_polo']])) {
            $polos[$v['id_polo']] = [
                'n_polo' => $v['n_polo'],
                'qtde' => 0,
            ];
        }
        $cursos[$v['id_curso']]['qtde'] += $v['inscritos'];
        $polos[$v['id_polo']]['qtde'] += $v['inscritos'];
    }

    $tChartC = (!empty($cursos)) ? $vagas/count($cursos) : 1;
    $tChartP = (!empty($polos)) ? $vagas/count($polos) : 1;

    $percentualinscritos = ($inscritos/$vagas)*100;
    $percentualinscritos = number_format($percentualinscritos, 2);

    $tDias = [];
    $dias = dataErp::diasDaSemana();
    foreach ($dias as $k => $v) {
        $tDias[$k] = 0;
    }

    $inscPorDia = $model->getInscrByDia($id_polo, $id_curso);
    foreach ($inscPorDia as $k => $v) {
        $d = date_create_from_format('d/m/Y', $v['data_inscricao'])->format('w');
        $tDias[$d] += $v['qtde'];
    }

    $qtdeAviseMe = 0;
    $aviseme = $model->getAviseMe($id_polo, $id_curso);
    if (!empty($aviseme)) {
        $qtdeAviseMe = number_format(count($aviseme), 0, ',', '.');
    }

    ?>
    <link href="<?= HOME_URI ?>/module/profe/views/_faltaSeq/css/sb-admin-2.min.css" rel="stylesheet">
    <style type="text/css">
    .chart-area {
        height: 17.3rem !important;
    }
    </style>
    <div class="body">
        <div class="fieldTop">Relatório de Inscrições</div>
    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column" style="padding-top: 26px; background-color: #fff;">
            <div id="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <?php dashBoard::divDash_percent('Percentual de Inscrições', $percentualinscritos, 'success') ?>
                        </div>
                        <div class="col-md-3">
                            <?php dashBoard::divDash_totais('Total de Inscritos', number_format($inscritos, 0, ',', '.'), 'info') ?>
                        </div>
                        <div class="col-md-3">
                            <?php dashBoard::divDash_totais('Total de Vagas', number_format($vagas, 0, ',', '.'), 'primary') ?>
                        </div>
                        <div class="col-md-3">
                            <?php dashBoard::divDash_totais('Solicitações de Avise-me', $qtdeAviseMe, 'warning') ?>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-4">
                            <?php dashBoard::divDash_pie('Procura por Cursos','myPieChartC') ?>
                        </div>
                        <div class="col-md-4">
                            <?php dashBoard::divDash_pie('Procura por Polos','myPieChartP') ?>
                        </div>
                        <div class="col-md-4">
                            <?php dashBoard::divDash_chart('Inscrições por Dia da Semana','myAreaChart1') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">  
                            <?php dashBoard::divDash_bar('Inscrições por Curso','myAreaBarC') ?>
                        </div>
                        <div class="col-md-6">  
                            <?php dashBoard::divDash_bar('Inscrições por Polo','myAreaBarP') ?>
                        </div>
                    </div>

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

    // Pie Chart Cursos
    <?php dashBoard::script_pie("'#2e59d9', '#17a673', '#e74a3b', '#e38b0b', '#870d3d', '#404242'", "'#4e73df', '#1cc88a', '#dd7369', '#f5a127', '#ba255f', '#606463' ", "'". toolErp::implodeMultiple($cursos, 'n_curso', "', '") ."'", toolErp::implodeMultiple($cursos, 'qtde', ", "), "myPieChartC", true) ?>

    // Pie Chart Polos
    <?php dashBoard::script_pie("'#2e59d9', '#17a673', '#e74a3b', '#e38b0b', '#870d3d', '#404242'", "'#4e73df', '#1cc88a', '#dd7369', '#f5a127', '#ba255f', '#606463' ", "'". toolErp::implodeMultiple($polos, 'n_polo', "', '") ."'", toolErp::implodeMultiple($polos, 'qtde', ", "), "myPieChartP", true) ?>

    // Area Chart Dias
    <?php dashBoard::script_chart("'".implode("', '", $dias)."'", implode(', ', $tDias), 'myAreaChart1', 'Inscrições') ?>

    // Area Chart Cursos
    <?php dashBoard::script_bar($tChartC, toolErp::implodeMultiple($cursos, 'qtde', ", "), 'myAreaBarC', "'". toolErp::implodeMultiple($cursos, 'n_curso', "', '") ."'") ?>

    // Area Chart Polos
    <?php dashBoard::script_bar($tChartC, toolErp::implodeMultiple($polos, 'qtde', ", "), 'myAreaBarP', "'". toolErp::implodeMultiple($polos, 'n_polo', "', '") ."'") ?>

</script>
