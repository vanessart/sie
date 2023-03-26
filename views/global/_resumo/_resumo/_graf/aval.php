<style>
    td{
        padding: 6px;
        font-weight: bold
    }
</style>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<br /><br />
<?php
$unico = 1;
if (empty($id_inst)) {
    foreach ($graf as $questao => $vv) {
        foreach ($vv as $k => $v) {
            $tit[$k] = $k;
        }
    }
    ?>
    <div style="background-color: tomato; border-radius: 10px; padding: 15px; width: 60%; margin: 0 auto; font-weight: bold">
        <div style="text-align: center; font-weight: bold; font-size: 22px;color: white; padding: 10px">
            Resultados Gerais
            <br />
            <?php echo @$avalia[@$_POST['id_gl']] ?>
        </div>
        <div style="background-color: #DBDBDB">
            <table class="table table-bordered table-hover table-striped" style="width: 100%; background-color: white" border=1 cellspacing=0 cellpadding=1>
                <tr>
                    <td>

                    </td>
                    <?php
                    foreach ($tit as $kti => $ti) {
                        ?>
                        <td>
                            <?php echo!empty($kti) ? $kti : 'Brancos/Nulos' ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
                foreach ($graf as $questao => $vv) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $questao ?>
                        </td>
                        <?php
                        $tt = 0;
                        foreach ($vv as $k => $v) {
                            $tt += $v;
                        }
                        foreach ($tit as $kti => $v) {
                            $style = '';
                            if (!empty($vv[$kti])) {
                                if ($kti == 'Insatisfatório' && (($vv[$kti] / $tt) * 100) > 50) {
                                    $style = 'style="color: red; font-weight: bold"';
                                }
                            }
                            ?>
                            <td <?php echo $style ?>>
                                <?php
                                if (!empty($vv[$kti])) {
                                    echo round(($vv[$kti] / $tt) * 100, 1) . '%';
                                } else {
                                    echo '0%';
                                }
                                ?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </div>
    <div style="page-break-after: always"></div>
    <?php
}
$unico = 1;
foreach ($totalGraf as $escola => $questoes) {
    foreach ($questoes as $questao => $vv) {
        foreach ($vv as $k => $v) {
            $tit[$k] = $k;
        }
    }
    ?>
    <br />    
    <div style="background-color: #0076cb; border-radius: 10px; padding: 15px; width: 60%; margin: 0 auto; font-weight: bold">
        <div style="text-align: center; font-weight: bold; font-size: 22px;color: white">
            <?php echo $escola ?>
            <br />
            <?php echo @$avalia[@$_POST['id_gl']] ?>
            <?php
            if (!empty($id_turma)) {
                ?>
                <br />
                <?php echo @$p[0]['n_turma']; ?>            
                <?php
            }
            ?>



        </div> 
        <br /><br />
        <div style="background-color: #DBDBDB">
            <table class="table table-bordered table-hover table-striped" style="width: 100%; background-color: white" border=1 cellspacing=0 cellpadding=1>
                <tr>
                    <td>

                    </td>
                    <?php
                    foreach ($tit as $kti => $v) {
                        ?>
                        <td>
                            <?php echo!empty($kti) ? $kti : 'Brancos/Nulos' ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
                foreach ($questoes as $questao => $vv) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $questao ?>
                        </td>
                        <?php
                        $tt = 0;
                        foreach ($vv as $k => $v) {
                            if ($k != '') {
                                $tt += $v;
                            }
                        }
                        foreach ($tit as $kti => $v) {
                            $style = '';
                            if (!empty($vv[$kti])) {
                                if ($kti == 'Insatisfatório' && (($vv[$kti] / $tt) * 100) > 50) {
                                    $style = 'style="color: red; font-weight: bold"';
                                }
                            }
                            ?>
                            <td <?php echo $style ?>>
                                <?php
                                if (!empty($vv[$kti])) {
                                    echo round(($vv[$kti] / $tt) * 100, 1) . '%';
                                } else {
                                    echo '0%';
                                }
                                ?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                    $unico++;
                }
                ?>
            </table>
        </div>
    </div>
    <br />
    <div style="page-break-after: always"></div>

    <?php
}

