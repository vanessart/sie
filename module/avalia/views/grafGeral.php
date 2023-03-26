<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<br /><br />
<?php
$id_pl = ng_main::periodoSet();



if (empty($secretaria)) {
    $id_inst = tool::id_inst();
} else {
    $id_inst = @$_REQUEST['id_inst'];
    $n_inst = @$_REQUEST['n_inst'];
}

if (!empty($id_inst)) {
    if (@$id_inst == 1) {
        ini_set('memory_limit', '200M');
    }
    $curso = sqlErp::get('ge_cursos', '*', ['id_curso' => 1], 'fetch');
    if (empty($_REQUEST['unidLetiva'])) {
        $unidLetiva_ = $curso['atual_letiva'];
    } else {
        $unidLetiva_ = $_REQUEST['unidLetiva'];
    }
    if (empty($_REQUEST['i'])) {
        ?>
        <table style="margin: 0 auto">
            <tr>
                <?php
                for ($ul = 1; $ul <= $curso['qt_letiva']; $ul++) {
                    if ($ul <= $curso['atual_letiva']) {
                        ?>
                        <td style="padding-left: 5px; padding-right: 5px; ">
                            <form method="POST">
                                <input type="hidden" name="id_inst" value="<?php echo @$_POST['id_inst'] ?>" />
                                <input type="hidden" name="unidLetiva" value="<?php echo $ul ?>" />
                                <button class="btn btn-<?php echo $ul == $unidLetiva_ ? 'primary' : 'warning' ?>">
                                    <?php echo $ul . 'º ' . $curso['un_letiva'] ?>
                                </button>
                            </form>
                        </td>
                        <?php
                    } else {
                        ?>
                        <td style="padding-left: 5px; padding-right: 5px; ">
                            <button type="button" class="btn btn-outline-warning">
                                <?php echo $ul . 'º ' . $curso['un_letiva'] ?>
                            </button>
                        </td>
                        <?php
                    }
                }
                ?>
            </tr>
        </table>
        <?php
    }
    if (is_numeric($unidLetiva_)) {
        ?>
        <br /><br />
        <div class="fieldTop">
            <div class="row">
                <div class="col-md-10">
                    Gráficos (geral e por Ciclo) - <?php echo $unidLetiva_ ?>º <?php echo $curso['un_letiva'];
        echo!empty($nomeEsc) ? ' - ' . $nomeEsc : ''
        ?>
                </div>
                <div class="col-md-2">
                    <?php
                    if (empty($_REQUEST['i'])) {
                        ?>
                        <a class="btn btn-success" href="#" onclick="window.open('<?php echo HOME_URI ?>/avalia/grafGeral?i=1&unidLetiva=<?php echo $unidLetiva_ ?>&id_inst=<?php echo @$id_inst ?>', 'Pagina', 'STATUS=NO, TOOLBAR=NO, LOCATION=NO, DIRECTORIES=NO, RESISABLE=NO, SCROLLBARS=YES, TOP=10, LEFT=10, WIDTH=770, HEIGHT=400');">Imprimir</a>
                        <?php
                    }
                    ?>
                </div>
            </div>

        </div>

        <br /><br />
        <?php
        $cor = [
            1 => '#0000FF',
            2 => '#4B0082',
            3 => '#4682B4',
            4 => '#FF4500',
            5 => '#006400',
            6 => '#8B4513',
            7 => '#2F4F4F',
            8 => '#FF0000',
            9 => '#7B68EE',
            10 => '#2F4F4F',
            11 => '#0000FF',
            12 => '#4B0082',
            13 => '#4682B4',
            14 => '#FF4500',
            15 => '#006400',
            16 => '#8B4513',
            17 => '#2F4F4F',
            18 => '#FF0000',
            19 => '#7B68EE'
        ];
        $id_curso = 1;
        $ano = date("y");


        if (@$id_inst <> 1) {
            $turmas_ = sql::idNome('ge_turmas', ['fk_id_pl' => $id_pl, 'fk_id_inst' => $id_inst]);
            foreach ($turmas_ as $id_turma => $v) {
                $turmas[] = $id_turma;
                $sql = "SELECT fk_id_disc FROM ge_turmas t "
                        . "JOIN ge_aloca_disc ad on ad.fk_id_grade = t.fk_id_grade "
                        . "WHERE t.id_turma = " . $id_turma;
                $query = autenticador::getInstance()->query($sql);
                $dd = $query->fetchAll(PDO::FETCH_ASSOC);
                foreach ($dd as $d) {
                    $grade[$id_turma][] = $d['fk_id_disc'];
                }
            }

            $id_inst = " and fk_id_turma in (" . implode(',', $turmas) . ")";
        } else {
            $id_inst = NULL;
        }
        $sql = "select * from hab.aval_mf_" . $id_curso . "_" . $id_pl . " a "
                . " where 1 "
                . " $id_inst "
                . " and atual_letiva = $unidLetiva_ ";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {

            foreach ($v as $kk => $vv) {
                if (substr($kk, 0, 6) == 'media_') {
                    if (in_array(substr($kk, 6), $grade[$v['fk_id_turma']])) {
                        @$notas[$v['fk_id_ciclo']][substr($kk, 6)] += str_replace(',', '.', $v['media_' . substr($kk, 6)]);
                        @$notasG[substr($kk, 6)] += str_replace(',', '.', $v['media_' . substr($kk, 6)]);
                        @$valor = $v['media_' . substr($kk, 6)];
                        if (!empty($valor) && $valor <> 0 && $valor <> "") {
                            @$conta[$v['fk_id_ciclo']][substr($kk, 6)]++;
                            @$contaG[substr($kk, 6)]++;
                        }
                    }
                }
            }
        }
        $disc_ = disciplina::disc();
        foreach ($disc_ as $v) {
            $disc[$v['id_disc']] = $v['n_disc'];
        }

        $ciclos_ = sqlErp::get('ge_ciclos');
        foreach ($ciclos_ as $v) {
            $ciclos[$v['id_ciclo']] = $v['n_ciclo'];
        }
        if (!empty($conta)) {
            foreach ($conta as $k => $v) {
                foreach ($v as $kk => $vv) {
                    $result[$ciclos[$k]][$disc[$kk]] = str_replace(',', '.', $notas[$k][$kk]) / $vv;
                }
            }
            foreach ($contaG as $k => $v) {
                $result[' Geral'][$disc[$k]] = str_replace(',', '.', $notasG[$k]) / $v;
            }
            ksort($result);
            $c = 1;
            foreach ($result as $k => $v) {
                ?>
                <script type="text/javascript">
                    google.charts.load("current", {packages: ['corechart']});
                    google.charts.setOnLoadCallback(drawChart);
                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                            ["Element", "Média", {role: "style"}],
                <?php
                $t = count($v);
                $i = 1;
                foreach ($v as $kk => $vv) {
                    ?>
                                ["<?php echo $kk ?>", <?php echo round($vv, 2) ?>, "<?php echo $cor[$i++] ?>"],
                    <?php
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
                            title: "<?php echo $k ?>",
                            width: 1000,
                            height: 400,
                            bar: {groupWidth: "95%"},
                            legend: {position: "none"},
                        };
                        var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values<?php echo $c ?>"));
                        chart.draw(view, options);
                    }
                </script>

                <div id="columnchart_values<?php echo $c++ ?>" style="width: 100%; height: 300px;page-break-after: always"></div>
                <br /><br /><br /><br />
                <br /><br /><br /><br />
                <?php
            }
        } else {
            ?>
            <div class="alert alert-danger text-center">
                Não Há Lançamentos
            </div>
            <?php
        }
        if (!empty($_REQUEST['i'])) {
            ?>
            <script>
                // window.print()
            </script>
            <?php
        }
    }
}
?>