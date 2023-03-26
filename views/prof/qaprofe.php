<?php
ob_start();

$cor = '#F5F5F5';
$seq = 1;
$linha = 20;
foreach (disciplina::discId() as $kk => $vv) {
    $disc = $kk;
    ?>

    <head>
        <style>   
            .topo{
                height: 5px;
                font-size: 9pt;
                font-weight:bolder;
                text-align: center;
                border: solid;
                border-width: 2px;
                padding-left: 3px;
                padding-right: 3px;
            }
        </style>   
    </head>
    <div style="text-align: center;font-size: 16">
        <?php
        $escola = sql::get('instancia', 'n_inst', ['id_inst' => @$_POST['id_inst']], 'fetch')['n_inst'];
        echo $escola . ' - ' . $vv
        ?>
    </div>
    <br /><br />
    <?php
    $t = $model->geraquadroprof(@$_POST['id_inst']);

    foreach ($t as $v) {
        if ($v['id_ciclo'] <= 9) {
            @$totalc[$v['periodo']][$v['n_ciclo']] = $v['totalp'];
        } else {
            switch ($v['id_ciclo']) {
                case 25:
                    @$totaleja[$v['periodo']]["1º Segmento Termo I"] = $v['totalp'];
                    break;
                case 26:
                    @$totaleja[$v['periodo']]["1º Segmento Termo II"] = $v['totalp'];
                    break;
                case 27:
                    @$totaleja[$v['periodo']]["2º Segmento Termo I"] = $v['totalp'];
                    break;
                case 28:
                    @$totaleja[$v['periodo']]["2º Segmento Termo II"] = $v['totalp'];
                    break;
                case 29:
                    @$totaleja[$v['periodo']]["2º Segmento Termo III"] = $v['totalp'];
                    break;
                case 30:
                    @$totaleja[$v['periodo']]["2º Segmento Termo IV"] = $v['totalp'];
                    break;
            }
        }
    }
    if (!empty($totalc)) {
        foreach ($totalc as $v) {
            foreach ($v as $k => $vv) {
                $coluna[$k] = $k;
            }
        }
    }
    ?>

    <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
        Quantidade de Classes
    </div>

    <table class="table tabs-stacked table-bordered">
        <thead>
            <tr>
                <td style="border: solid; border-width: 1px; color:red; background-color: <?php echo $cor ?>" class="topo">
                    Período
                </td>
                <?php
                foreach ($coluna as $v) {
                    ?>
                    <td style="border: solid; border-width: 1px; color:red; background-color: <?php echo $cor ?>" class="topo"> 
                        <?php echo $v ?>                 
                    </td>   
                    <?php
                }
                ?>
            </tr>
        </thead>

        <tbody>
            <?php
            foreach ($totalc as $kk => $vv) {
                ?>
                <tr>
                    <td style="border: solid; border-width: 1px; background-color: <?php echo $cor ?>" class="topo">
                        <?php echo $model->pegadescricaop($kk) ?>
                    </td>
                    <?php
                    foreach ($coluna as $v) {
                        ?>
                        <td style="border: solid; border-width: 1px; background-color: <?php echo $cor ?>" class="topo">
                            <?php echo $a = (empty($vv[$v]) ? "-" : $vv[$v]) ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
    <?php
    if (!empty($totaleja)) {
        $linha = 25;
        foreach (@$totaleja as $w) {
            foreach ($w as $ke => $ww) {
                $coluna2[$ke] = $ke;
            }
        }
        ?>
        <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
            Quantidade de Classes EJA
        </div>

        <table class="table tabs-stacked table-bordered">
            <thead>
                <tr>
                    <td style="border: solid; border-width: 1px; color:red; background-color: <?php echo $cor ?>" class="topo">
                        Período
                    </td>
                    <?php
                    foreach ($coluna2 as $W) {
                        ?>
                        <td style="border: solid; border-width: 1px; color:red; background-color: <?php echo $cor ?>" class="topo"> 
                            <?php echo $W ?>                 
                        </td>   
                        <?php
                    }
                    ?>
                </tr>
            </thead>

            <tbody>
                <?php
                foreach ($totaleja as $kkk => $vvv) {
                    ?>
                    <tr>
                        <td style="border: solid; border-width: 1px; background-color: <?php echo $cor ?>" class="topo">
                            <?php echo $model->pegadescricaop($kkk) ?>
                        </td>
                        <?php
                        foreach ($coluna2 as $w) {
                            ?>
                            <td style="border: solid; border-width: 1px; background-color: <?php echo $cor ?>" class="topo">
                                <?php echo $b = (empty($vvv[$w]) ? "-" : $vvv[$w]) ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </tbody>
        </table>
        <?php
    }
    ?>
    <div style="font-weight:bold; font-size:10pt; text-align: left; color: red">
        <?php echo "Disciplina: " . $disc ?>
    </div>
    <?php ?>
    <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
        CADAMPE
    </div>
    <table class="table tabs-stacked table-bordered">
        <thead>
            <tr>
                <td style="width: 5%; border: solid; border-width: 1px; text-align: center" class="topo">
                    Nº Classif.
                </td>
                <td style="width: 10%; border: solid; border-width: 1px; text-align: center" class="topo">
                    Tipo Processo
                </td>
                <td style="width: 45%; border: solid; border-width: 1px; text-align: center" class="topo">
                    Nome Professor
                </td>

                <td style="width: 10%; border: solid; border-width: 1px; text-align: center" class="topo">
                    Telefone
                </td>
                <td style="width: 5%; border: solid; border-width: 1px; text-align: center" class="topo">
                    M
                </td>
                <td style="width: 5%; border: solid; border-width: 1px; text-align: center" class="topo">
                    T
                </td>
                <td style="width: 5%; border: solid; border-width: 1px; text-align: center" class="topo">
                    N
                </td>
            </tr>
        </thead>
        <tbody>
            <?php
            for ($x = 1; $x < $linha; $x++) {
                ?>
                <tr>
                    <td style="height: 30px;  border: solid; border-width: 1px; text-align: center" class="topo">
                        <?php echo ' ' ?>
                    </td>
                    <td style="height: 30px;  border: solid; border-width: 1px; text-align: center" class="topo">
                        <?php echo ' ' ?>
                    </td>
                    <td style="height: 30px;  border: solid; border-width: 1px; text-align: center" class="topo">
                        <?php echo ' ' ?>
                    </td>
                    <td style="height: 30px;  border: solid; border-width: 1px; text-align: center" class="topo">
                        <?php echo ' ' ?>
                    </td>
                    <td style="height: 30px;  border: solid; border-width: 1px; text-align: center" class="topo">
                        <?php echo ' ' ?>
                    </td> 

                    <<td style="height: 30px;  border: solid; border-width: 1px; text-align: center" class="topo">
                        <?php echo ' ' ?>
                    </td> 
                    <td style="height: 30px;  border: solid; border-width: 1px; text-align: center" class="topo">
                        <?php echo ' ' ?>
                    </td> 
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <div style="page-break-after: always"></div>
    <?php
}
tool::pdfSecretaria();
?>
