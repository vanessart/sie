<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<?php
$escola = sql::get('instancia', '*', ['id_inst' => tool::id_inst()], 'fetch');
$idescola = $escola['id_inst'];
$sinais = $model->auxtabela('cv_sinais');
$def = $model->auxtabela('cv_deficiencia');
$titulo = ['Oculosac' => 'Usa Óculos', 'Situacaoac' => 'Situação Teste', 'Deficienciaac' => 'Deficiência', 'NecesEspac' => 'Necessidades Especiais', 'Sinaisac' => 'Sinais Teste', 'usouoculosac' => 'Usou Óculos'];

?>

<div class="fieldTop">
    <div class="row">
        <div style="font-size: 14px" class="col-md-10">
            <b><?= CLI_NOME ?><b/> 
                <br />
                <?php echo $escola['n_inst'] ?>
                <br />
                Gráfico de Resultados do Teste no Computador - <?php echo date('Y') ?>
        </div>
        <div class="col-md-2">
            <?php
            if (empty($_REQUEST['i'])) {
                ?>
                <a class="btn btn-success" href="#" onclick="window.open('<?php echo HOME_URI ?>/visao/grafteste?i=1', 'Pagina', 'STATUS=NO, TOOLBAR=NO, LOCATION=NO, DIRECTORIES=NO, RESISABLE=NO, SCROLLBARS=YES, TOP=10, LEFT=5, WIDTH=1000, HEIGHT=600')">Imprimir</a>             
                <?php
            }
            ?>
        </div>
    </div>
</div>

<?php
$dados = $model->resultadotestegrafico();

$c = 0;
foreach ($dados[$idescola] as $k => $v) {
    ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
                                        google.charts.load("current", {packages: ["corechart"]});
                                        google.charts.setOnLoadCallback(drawChart);
                                        function drawChart() {
                                            var data = google.visualization.arrayToDataTable([
                                                ["Aluno", "Situação"],
    <?php
    $c = $c + 1;
    foreach ($v as $kk => $vv) {
        ?>
                                                    ["<?php echo $kk ?>", <?php echo $vv ?>],
        <?php
    }
    ?>
                                            ]);
                                            var view = new google.visualization.DataView(data);
                                            var options = {
                                                title: '<?php echo $titulo[$k] ?>',
                                                is3D: true,
                                                titleTextStyle: {color: 'black', fontName: 'Arial', fontSize: 14},
                                                //pieSliceText: 'value-and-percentage',
                                                pieSliceText: 'percentage',
                                                pieSliceTextStyle: {color: 'black', fontName: 'Arial', fontSize: 14},
                                                charArea: {left: 1, top: 5, width: '100%', height: '90%'}
                                                //  forceIFrame: true


                                            };
                                            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d<?php echo $c ?>'));
                                            chart.draw(data, options);
                                        }
    </script>
    <?php
}

?>

<table style="width: 95%; height: 85%">
    <thead>
        <tr>
            <td style="border-style: solid; width: 25%; height: 40%">              
                <div id="piechart_3d1" style="text-align: left; width: 100%; height: 100%"></div>          
            </td>
            <td style="border-style: solid; width: 40%; height: 40%">          
                <div id="piechart_3d3" style="text-align: left; width: 100%; height: 100%"></div>           
            </td>
            <td style="border-style: solid; width: 35%; height: 40%">
                <div id="piechart_3d2" style="text-align: left; width: 100%; height: 100%"></div>
            </td>             
        </tr>
        <tr>
            <td style="border-style: solid; width: 25%; height: 40%">
                <div id="piechart_3d6" style="text-align: left; width: 100%; height: 100%"></div>
            </td>
            <td style="border-style: solid; width: 40%; height: 40%">
                <div id="piechart_3d5" style="text-align: left; width: 100%; height: 100%"></div>
            </td>
            <td style="border-style: solid; width: 35%; height: 40%">
                <div id="piechart_3d4" style="text-align: left; width: 100%; height: 100%"></div>
            </td>             
        </tr>
    </thead>
    <tfoot>
    <div style="font-size: 14px">
        Total de Alunos Frequentes: <?php echo $dados['Frequenteac'][$idescola] ?>
    </div>
</tfoot>
</table>



