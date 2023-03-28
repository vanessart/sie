<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<?php

if (!empty($_REQUEST['periodo'])) {
    $per = $_REQUEST['periodo'];
} else {
   $per = date('Y');
    
}

$atztabela = $model->situacaoescolarede($per);
$res = $model->wresumo('grafico', $per);
$res2 = $model->wresumo('relatorio', $per);

?>

<div class="fieldTop">
    <div class="row">
        <div style="font-size: 16px" class="col-md-10">
            <b><?= CLI_NOME ?><b/> 
                <br />
                CAMPANHA DA VISÃO - "ALÉM DO OLHAR"
                <br />
                Gráfico de Resultados do Teste e Reteste - <?php echo  $per?>
        </div>
        <div class="col-md-2">
            <?php
            
            if (empty($_REQUEST['i'])) {
                ?>
                <a class="btn btn-success" href="#" onclick="window.open('<?php echo HOME_URI ?>/visao/graficorede?i=1&periodo=<?php echo $_POST['periodo'] ?>', 'Pagina', 'STATUS=NO,TOOLBAR=NO,LOCATION=NO,DIRECTORIES=NO,RESISABLE=NO,SCROLLBARS=YES,TOP=10,LEFT=5,WIDTH=1000,HEIGHT=600')">Imprimir</a>
                <?php
            }

            ?>

        </div>
    </div>
</div>

<?php
$c = 0;

foreach ($res as $k => $v) {
    ?>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
                  google.charts.load("current", {packages: ["corechart"]});
                  google.charts.setOnLoadCallback(drawChart);
                  function drawChart() {
                      var data = google.visualization.arrayToDataTable([
                          ["Rede", "Situação"],

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
                          title: '<?php echo $k ?>',
                          is3D: true,
                          titleTextStyle: {color: 'black', fontName: 'Arial', fontSize: 14},
                          pieSliceText: 'percentage',
                          pieSliceTextStyle: {color: 'black', fontName: 'Arial', fontSize: 14},
                          charArea: {left: 1, top: 5, width: '100%', height: '90%'}

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
            <td style="border-style: solid; width: 50%; height: 40%">              
                <div id="piechart_3d1" style="text-align: left; width: 100%; height: 100%"></div>          
            </td>
            <td style="border-style: solid; width: 50%; height: 40%">          
                <div id="piechart_3d2" style="text-align: left; width: 100%; height: 100%"></div>           
            </td>
        </tr>
        <tr>
            <td style="border-style: solid; width: 50%; height: 40%">          
                <div id="piechart_3d3" style="text-align: left; width: 100%; height: 100%"></div>           
            </td>
            <td style="border-style: solid; width: 50%; height: 40%">          
                <div style="font-weight:bolder; font-size: 14px; text-align: center">
                    <?php
                        $to = intval(($res2['tp']) + intval($res2['tf'])+ intval($res2['tnr']));
                    ?>
                    Total de Alunos Frequentes:  <?php echo $to ?><br /><br />
                    Alunos Teste <br /><br />
                    PASSA =  <?php echo intval($res2['tp']) ?><br />
                    FALHA =  <?php echo intval($res2['tf']) ?><br />
                    N.S. =  <?php echo intval($res2['tnr']) ?><br /><br />
                    Alunos Reteste <br /><br />
                    PASSA = <?php echo intval($res2['rtp']) ?><br />
                    FALHA = <?php echo intval($res2['rtf']) ?><br />
                    N.S. = <?php echo intval($res2['rtnr']) ?><br />
                    TOTAL: <?php echo intval($res2['rtp'] + $res2['rtf'] + $res2['rtnr']) ?><br /><br />
                    Encaminhamento Oftalmologista: <?php echo intval($res2['es']) ?>
                </div>    
            </td>   
        </tr>
    </thead>
    <tfoot>
    </tfoot>
</table>



