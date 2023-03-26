<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<br /><br />
<?php
$unico = 1;
if (empty($id_inst)) {
    ?>
    <div style="background-color: tomato; border-radius: 10px; padding: 15px">
        <div style="text-align: center; font-weight: bold; font-size: 22px;color: white; padding: 10px">
            Resultados Gerais
        </div>
        <br />
        <?php
        foreach ($graf as $questao => $vv) {
            ?>
            <div class="fieldBorder2" style="background-color: white" >
                <div style="text-align: center; font-weight: bold; font-size: 22px">  
                    <?php echo $questao ?>
                </div>
                <br /><br />
                <div class="row">
                    <div class="col-sm-4" style="z-index: 999">
                        <table class="table table-bordered table-hover table-striped" style="font-weight: bold; font-size: 18px; ">

                            <?php
                            if ($tipox[$id_agrup] == 'normal') {
                                ?>        

                                <tr style="background-color: wheat">
                                    <td>
                                        Conceitos
                                    </td>
                                    <td align="center">
                                        Qtd. por Conceito
                                    </td>
                                    <td align="center">
                                        % por Conceito
                                    </td>
                                </tr>

                                <?php
                            } else {
                                ?>

                                <tr style="background-color: wheat">
                                    <td>
                                        Alternativas
                                    </td>
                                    <td align="center">
                                        Qtd. por Alternativa
                                    </td>
                                    <td align="center">                                        
                                        % por Alternativa
                                    </td>
                                </tr>

                                <?php
                            }
                            ?>

                            <?php
                            $tt = 0;
                            foreach ($vv as $k => $v) {
                                if (!empty($k)) {
                                    $tt += $v;
                                }
                            }
                            foreach ($vv as $k => $v) {
                                if (!empty($k)) {
                                    ?>
                                    <tr>
                                        <td>
                                            <?php echo $k ?>
                                        </td>
                                        <td align="center">
                                            <?php echo intval($v) ?>
                                        </td>
                                        <td align="center">
                                            <?php
                                            if ($tt > 0) {
                                                echo round(($v / $tt) * 100, 1) . '%';
                                            } else {
                                                echo '0%';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                            <tr style="background-color: wheat">
                                <td>
                                    Total de Avaliados
                                </td>
                                <td align="center">
                                    <?php echo $tt ?>
                                </td>
                                <td align="center"> 
                                    <?php
                                    if (empty($tt)) {

                                        echo '0%';
                                    } else {

                                        echo round(($tt / $tt) * 100, 1) . '%';
                                    }
                                    ?>
                                </td>                                    
                            </tr>
                        </table>
                    </div>
                    <div class="col-sm-8"> 

                        <?php
                        if ($dados['tipo_graf'] == 'setor') {
                            ?>

                            <!--########################################################################-->
                            <script type="text/javascript">
                                google.charts.load('current', {'packages': ['corechart']});
                                google.charts.setOnLoadCallback(drawChart);

                                function drawChart() {

                                    var data = google.visualization.arrayToDataTable([
                                        ['Quest', 'Val'],
            <?php
            foreach ($vv as $k => $v) {
                if (!empty($k)) {
                    ?>
                                                ['<?php echo str_replace('\n', '', $k) ?>', <?php echo $v ?>],
                    <?php
                }
            }
            ?>
                                    ]);

                                    var options = {
                                        title: '',
                                        colors: [<?php echo $dados['color_graf'] ?>]
                                    };

                                    var chart = new google.visualization.PieChart(document.getElementById('piechart<?php echo $unico ?>'));

                                    chart.draw(data, options);
                                }
                            </script>

                            <div id="piechart<?php echo $unico ?>" style="width: 100%; height: 500px;margin-left: -15%"></div>


                            <!--########################################################################-->
                            <?php
                        } else {
                            ?>

                            <!--########################################################################-->
                            <script type="text/javascript">;
                                google.charts.load("current", {packages: ['corechart']});
                                google.charts.setOnLoadCallback(drawChart);
                                function drawChart() {
                                    var data = google.visualization.arrayToDataTable([
                                        ['Quest', 'Val', {role: 'style'}],

            <?php
            $colore = explode(',', $dados['color_graf']);
            $idc = 0;

            foreach ($vv as $k => $v) {
                if (!empty($k)) {
                    ?>
                                                ['<?php echo str_replace('\n', '', $k) ?>', <?php echo $v ?>, <?php echo $colore[$idc++] ?> ],
                    <?php
                }
            }
            ?>
                                    ]);

                                    var view = new google.visualization.DataView(data);
                                    view.setColumns([0, 1,
                                        {calc: "stringify",
                                            sourceColumn: 1,
                                            type: "string",
                                            role: "annotation"},
                                        2]);

                                    var options = {
                                        title: " ",

                                        bar: {groupWidth: "85%"},
                                        legend: {position: "none"},
                                    };
                                    var chart = new google.visualization.ColumnChart(document.getElementById("columnchart<?php echo $unico ?>"));
                                    chart.draw(view, options);
                                }
                            </script>
                            <div id="columnchart<?php echo $unico ?>" style="width: 100%; height: 500px;margin-left: -5%;"></div>


                            <!--########################################################################-->
                            <?php
                        }
                        ?>
                    </div>     
                </div>
            </div>
            <br /><br /><br />

            <div style="page-break-after: always"></div>
            <?php
            $unico++;
        }
        ?>
    </div>
    <?php
} else {

    $unico = 1;
    foreach ($totalGraf as $escola => $questoes) {
        ?>
        <br />    
        <div style="background-color: #0076cb; border-radius: 10px; padding: 15px">
            <div style="text-align: center; font-weight: bold; font-size: 22px;color: white">
                <?php echo $escola ?>
            </div> 
            <br /><br />
            <?php
            foreach ($questoes as $questao => $vv) {
                ?>
                <div class="fieldBorder2" style="background-color: white;">
                    <div style="text-align: center; font-weight: bold; font-size: 22px">  
                        <?php echo $questao ?>
                    </div>
                    <br />
                    <div style="text-align: center; font-weight: bold; font-size: 16px">  
                        <?php echo $escola ?>
                    </div>
                    <br /><br />
                    <div class="row">
                        <div class="col-sm-4" style="z-index: 999">

                            <table class="table table-bordered table-hover table-striped" style="font-weight: bold; font-size: 18px">


                                <?php
                                if ($tipox[$id_agrup] == 'normal') {
                                    ?>        

                                    <tr style="background-color: wheat">
                                        <td>
                                            Conceitos
                                        </td>
                                        <td align="center">
                                            Qtd. por Conceito
                                        </td>
                                        <td align="center">
                                            % por Conceito
                                        </td>
                                    </tr>

                                    <?php
                                } else {
                                    ?>

                                    <tr style="background-color: wheat">
                                        <td>
                                            Alternativas
                                        </td>
                                        <td align="center">
                                            Qtd. por Alternativa
                                        </td>
                                        <td align="center">
                                            % por Alternativa
                                        </td>
                                    </tr>

                                    <?php
                                }
                                ?>

                                <?php
                                $tt = 0;
                                foreach ($vv as $k => $v) {
                                    if (!empty($k)) {
                                        $tt += $v;
                                    }
                                }
                                foreach ($vv as $k => $v) {
                                    if (!empty($k)) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $k ?>
                                            </td>
                                            <td align="center">
                                                <?php echo intval($v) ?>
                                            </td>
                                            <td align="center">
                                                <?php
                                                if ($tt > 0) {
                                                    echo round(($v / $tt) * 100, 1) . '%';
                                                } else {
                                                    echo '0%';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                                <tr style="background-color: wheat">
                                    <td>
                                        Total de Avaliados
                                    </td>
                                    <td align="center">
                                        <?php echo $tt ?>
                                    </td>
                                    <td align="center">

                                        <?php
                                        if (empty($tt)) {

                                            echo '0%';
                                        } else {

                                            echo round(($tt / $tt) * 100, 1) . '%';
                                        }
                                        ?>

                                    </td>                                    
                                </tr>
                            </table>
                        </div>
                        <div class="col-sm-8">

                            <?php
                            if ($dados['tipo_graf'] == 'setor') {
                                ?>                        

                                <!--########################################################################-->
                                <script type="text/javascript">
                                    google.charts.load('current', {'packages': ['corechart']});
                                    google.charts.setOnLoadCallback(drawChart);

                                    function drawChart() {

                                        var data = google.visualization.arrayToDataTable([
                                            ['Quest', 'Val'],
                <?php
                foreach ($vv as $k => $v) {
                    if (!empty($k)) {
                        ?>
                                                    ['<?php echo str_replace('\n', '', $k) ?>', <?php echo!empty($v) ? $v : 0 ?>],
                        <?php
                    }
                }
                ?>

                                        ]);

                                        var options = {
                                            title: '',
                                            colors: [<?php echo $dados['color_graf'] ?>]
                                        };

                                        var chart = new google.visualization.PieChart(document.getElementById('pie<?php echo $unico ?>'));

                                        chart.draw(data, options);
                                    }
                                </script>
                                <div id="pie<?php echo $unico ?>" style="width: 100%; height: 500px;margin-left: -15%"></div>


                                <!--########################################################################-->

                                <?php
                            } else {
                                ?>

                                <!--########################################################################-->
                                <script type="text/javascript">;
                                    google.charts.load("current", {packages: ['corechart']});
                                    google.charts.setOnLoadCallback(drawChart);
                                    function drawChart() {
                                        var data = google.visualization.arrayToDataTable([
                                            ['Quest', 'Val', {role: 'style'}],

                <?php
                $colore = explode(',', $dados['color_graf']);
                $idc = 0;

                foreach ($vv as $k => $v) {
                    if (!empty($k)) {
                        ?>
                                                    ['<?php echo str_replace('\n', '', $k) ?>', <?php echo $v ?>, <?php echo $colore[$idc++] ?> ],
                        <?php
                    }
                }
                ?>
                                        ]);

                                        var view = new google.visualization.DataView(data);
                                        view.setColumns([0, 1,
                                            {calc: "stringify",
                                                sourceColumn: 1,
                                                type: "string",
                                                role: "annotation"},
                                            2]);

                                        var options = {
                                            title: " ",

                                            bar: {groupWidth: "85%"},
                                            legend: {position: "none"},
                                        };
                                        var chart = new google.visualization.ColumnChart(document.getElementById("columnchart<?php echo $unico ?>"));
                                        chart.draw(view, options);
                                    }
                                </script>
                                <div id="columnchart<?php echo $unico ?>" style="width: 100%; height: 500px;margin-left: -5%;"></div>


                                <!--########################################################################-->                                <?php
                            }
                            ?>
                        </div>     
                    </div>
                </div>
                <br /><br /><br />

                <div style="page-break-after: always"></div>
                <?php
                $unico++;
            }
            ?>
        </div>
        <br />
        <?php
    }
}