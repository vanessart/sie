<?php
$sql = "SELECT * FROM `sel_seletiva`";
$query = $model->db->query($sql);
$sel = $query->fetchAll();
foreach ($sel as $v) {
    $selOption[$v['id_sel']] = $v['n_sel'];
}
?>
<div class="fieldBody">
    <div class="row">
        <div class="col-lg-8">
            <?php
            formulario::select('id_sel', $selOption, 'Selecione o Processo Seletivo', @$_POST['id_sel'], 1);
            if (!empty($_POST['id_sel'])) {
                $sql = "SELECT * FROM `sel_cargo` WHERE `fk_id_sel` = " . $_POST['id_sel'];
                $query = $model->db->query($sql);
                $car = $query->fetchAll();
                foreach ($car as $v) {
                    $cargos[$v['id_cargo']] = $v['n_cargo'];
                }
                $sql = "SELECT * FROM `sel_inscricacao` "
                        . " WHERE `fk_id_sel` =  " . $_POST['id_sel']
                        . " order by dt_inscr";
                $query = $model->db->query($sql);
                $i = $query->fetchAll();
                foreach ($i as $v) {
                    @$total++;
                    @$totalData[data::converteBr($v['dt_inscr'])] ++;
                    @$cargo[$v['fk_id_cargo']] ++;
                }
                ?>
            </div>
            <div class="col-lg-4">
                <form method="POST">
                    <input type="hidden" name="id_sel" value="<?php echo @$_POST['id_sel'] ?>" />
                    <button class="btn btn-info" type="submit">
                        <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
                        Atualizar
                    </button>
                </form>
            </div>
        </div>

        <br /><br />
        <div class="row">
            <div class="col-lg-6">
                <table class="table table-striped fieldBorder2">
                    <thead>
                        <tr>
                            <th colspan="2" style="text-align: center">
                                Inscritos por Data
                            </th>
                        </tr>
                        <tr>
                            <th>
                                Data
                            </th>
                            <th>
                                Inscrições
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach (@$totalData as $k => $v) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $k ?>
                                </td>
                                <td>
                                    <?php
                                    echo intval(@$v);
                                    @$totalG += $v;
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <td>
                                Total
                            </td>
                            <td>
                                <?php echo intval(@$totalG) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-6">
                <table class="table fieldBorder2 table-striped">
                    <thead>
                        <tr>
                            <th colspan="2" style="text-align: center">
                                Inscrições por Cargo
                            </th>
                        </tr>
                        <tr>
                            <th>
                                Cargo
                            </th>
                            <th>
                                Inscrições
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach (@$cargos as $k => $v) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $cargos[$k]; ?>
                                </td>
                                <td>
                                    <?php
                                    echo intval(@$cargo[$k]);
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>

                    </tbody>
                </table>
            </div>

        </div>
        <br /><br />
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load('current', {'packages': ['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['dia', 'Inscrições'],
    <?php
    foreach (@$totalData as $k => $v) {
        ?>
                        ['<?php echo $k ?>', <?php echo $v ?>],
        <?php
    }
    ?>

                ]);

                var options = {
                    title: 'Inscrições Diárias',
                    curveType: 'function',
                    legend: {position: 'none'}
                };

                var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

                chart.draw(data, options);
            }
        </script>
        <div class="fieldBorder2">
            <div id="curve_chart" style="width: 100%; height: 500px"></div>
        </div>
        <?php
    }
    ?>
</div>
