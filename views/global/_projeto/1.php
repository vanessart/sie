<?php
if (!empty($_POST['print'])) {
    $id_gl = @$_POST['id_gl'];
    $id_turma = @$_POST['id_turma'];
    if ($_SESSION['userdata']['id_nivel'] == 8) {
        $id_inst = tool::id_inst();
    } else {
        $id_inst = @$_POST['id_inst'];
    }
    ?>
    <table style="width: 100%">
        <tr>
            <td>
                <img src="<?php echo HOME_URI ?>/views/_images/brasao.png" width="150" alt="brasao"/>

            </td>
            <td style=" font-weight: bold; font-size: 30px; text-align: center">
                Secretaria de Educação
            </td>
            <td style="text-align: right">
                <img src="<?php echo HOME_URI ?>/views/_images/logo.png" width="350" alt="brasao"/>           
            </td>
        </tr>
    </table>
    <?php
}

$graf = aval::quantHab($id_gl, @$id_turma, @$id_inst);
$aval = aval::getAval($id_gl);
$descr = unserialize($aval['perc']);
$valores = explode(',', $aval['valores']);
$val = unserialize($aval['val']);

$escola = sql::get('instancia', 'n_inst', ['id_inst' => @$id_inst], 'fetch')['n_inst'];
$turma = sql::get('ge_turmas', 'n_turma', ['id_turma' => $id_turma], 'fetch')['n_turma'];
$disc = sql::get('ge_disciplinas', 'n_disc', ['id_disc' => @$aval['fk_id_disc']], 'fetch')['n_disc'];
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<br /><br />
<div style="text-align: center; font-size: 25px; font-weight: bold">
    Gráficos por habilidades
</div>

<br /><br />
<div class="row">
    <?php
    foreach ($graf as $k => $v) {
        ?>
        <div class="col-sm-6">
            <div style=" border: solid #000000 thin; margin: 5px; height: <?php echo $tipoProva == 'tri' ? 780 : 600 ?>px">
                <div style="text-align: center; font-weight: bold">
                    <?php
                    if (!empty($escola)) {
                        echo $escola;
                    } else {
                        echo 'Toda Rede';
                    }
                    ?>
                    <br />
                        <?php
                        if (empty($turma)) {

                            if ($aval['ciclos'] > 9) {
 
                                echo $aval['n_gl'];
                                $disc = '';
                                
                            } else {

                                echo $aval['ciclos'] . 'º Ano';
                            }
                        } else {

                            echo $turma;
                        }
                        ?>
                        <br />
                        <?php echo $disc ?>
                    </div>
                    <div style="margin: 4%">
                        Habilidade <span style=";font-weight: bold"><?php echo $k ?></span>: <?php echo $descr[$k] ?>
                </div>
                <script type="text/javascript">
                    google.charts.load("current", {packages: ['corechart']});
                    google.charts.setOnLoadCallback(drawChart<?php echo $k ?>);
                    function drawChart<?php echo $k ?>() {

                        var data = google.visualization.arrayToDataTable([
                            ["", "Porc.", {role: "style"}],
    <?php
    if ($tipoProva == 'tri') {
        ?>

                                ["G1", <?php echo round(@$v['G1'], 1) ?>, "green"],
                                ["D2", <?php echo round(@$v['D2'], 1) ?>, "yellow"],
                                ["D3", <?php echo round(@$v['D3'], 1) ?>, "orange"],
                                ["D4", <?php echo round(@$v['D4'], 1) ?>, "red"],
                                ["D5", <?php echo round(@$v['D5'], 1) ?>, "blue"]
        <?php
    } else {

        $colore = explode(',', $dados['color_graf']);
        $idc = 0;

        foreach ($valores as $key => $value) {

            ?>

                                    ["<?php echo $value ?>", <?php echo round(@$v[$value], 1) ?>, <?php echo $colore[$idc++] ?> ],

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
                            width: 600,
                            height: 400,
                            bar: {groupWidth: "95%"},
                            legend: {position: "none"},
                            vAxis: {title: 'Porcentagem', maxValue: 100}
                        };
                        var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values<?php echo $k ?>"));
                        chart.draw(view, options);
                    }
                </script>
                <div id="columnchart_values<?php echo $k ?>" style="width: 400px; height: 200px;"></div>


                <?php
                if ($tipoProva == 'tri') {
                    ?>

                    <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /> 
                    <table style="margin: 0 auto" cellspacing=0 cellpadding=1 border="1">
                        <tr>
                            <td>
                                (G)abarito e
                                <br />
                                (D)istratores  
                            </td>
                            <td>
                                Interpretação Pedagógica da Resposta ao Item
                            </td>
                        </tr>
                        <tr>
                            <td>
                                (G1) 
                            </td>
                            <td>
                                Domina a habilidade aferida pelo item.
                            </td>
                        </tr>
                        <tr>
                            <td>
                                (D2)
                            </td>
                            <td>
                                Possui domínio mediano da habilidade aferida pelo item.
                            </td>
                        </tr>
                        <tr>
                            <td>
                                (D3)  
                            </td>
                            <td>
                                Possui conhecimento mínimo da habilidade aferida pelo item.
                            </td>
                        </tr>
                        <tr>
                            <td>
                                (D4) 
                            </td>
                            <td>
                                Não domina a habilidade aferida pelo item.
                            </td>
                        </tr>
                        <tr>
                            <td>
                                (D5) 
                            </td>
                            <td>
                                Erro de preenchimento ou falta de marcação.
                            </td>
                        </tr>
                    </table>
                    <?php
                }
                ?>

            </div>
        </div>
        <div style="page-break-before: always;"> </div>
        <?php
    }
    ?>
</div>

