<?php
set_time_limit(300);
ob_start();
$sql = "SELECT * FROM `sel_seletiva` "
        . "where id_sel = " . $_POST['id_sel'];
$query = $model->db->query($sql);
$sel = $query->fetch();

$sql = "SELECT * FROM ps.`sel_cargo` WHERE `fk_id_sel` = " . $_POST['id_sel'];
$query = $model->db->query($sql);
$array = $query->fetchAll();
foreach ($array as $v) {
    $cargo[$v['id_cargo']] = $v['n_cargo'];
}

if (!empty($_POST['id_sel'])) {
    $sql = "SELECT * FROM `sel_inscricacao` "
            . " WHERE `fk_id_sel` = " . $_POST['id_sel']
            . " and n_predio <> '' "
            . " and sala <> '' "
            . " ORDER BY fk_id_predio, sala, n_insc ASC ";
    $query = $model->db->query($sql);
    $cand = $query->fetchAll();
    foreach ($cand as $v) {
        $list[$v['n_predio']][$v['sala']][] = $v;
        $predio[$v['fk_id_predio']] = $v['n_predio'];
    }
    ?>
    <style>
        td{
            padding: 2px
        }
    </style>
    <?php
    foreach ($list as $k => $v) {
        foreach ($v as $v1) {
            ?>
            <div style="page-break-after: always">
                <div style="width: 100%; text-align: center; font-size: 18px">
                    <?php echo $sel['n_sel'] ?>
                </div>
                <div style="width: 100%; text-align: left; font-size: 14px">
                    <table style="width: 100%">
                        <tr>
                            <td style="font-weight: bold">
                              Local:
                                <?php echo current($v1)['n_predio'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold">
                                Função: <?php echo $cargo[current($v1)['fk_id_cargo']] ?>  
                            </td>
                            <td rowspan="2" style="font-weight: bold;text-align: right">
                                Sala:
                                <?php echo current($v1)['sala'] ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <table style="width: 100%" border=1 cellspacing=0 cellpadding=1>
                    <tr>
                        <td>

                        </td>
                        <td>
                            Nome
                        </td>
                        <td>
                            RG
                        </td>
                        <td>
                            CPF
                        </td>
                        <td style="width: 25%">
                            Assinatura
                        </td>
                    </tr>
                    <?php
                    $cont = 1;
                    foreach ($v1 as $vv) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $cont++; ?>
                            </td>
                            <td>
                                <?php echo strtoupper($vv['n_insc']) ?>
                            </td>
                            <td>
                                <?php echo $vv['rg']  ?>
                            </td>
                            <td>
                                <?php echo $vv['cpf'] ?>
                            </td>
                            <td>

                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </div>
            <?php
        }
    }
}

tool::pdfSecretaria();
?>