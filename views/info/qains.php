<?php
$sql = "SELECT * FROM `vagas` v "
        . " JOIN instancia i on i.id_inst = v.fk_id_inst "
        . " WHERE `status` LIKE 'Deferido' "
        . " and seriacao in ('Berçário', '1ª Fase - Maternal', '2ª Fase - Maternal', '3ª Fase - Maternal')"
        . " ORDER BY n_inst, seriacao ASC ";
$query = $model->db->query($sql);
$i = $query->fetchAll();
foreach ($i as $v) {
    @$alu[$v['fk_id_inst']][$v['seriacao']] ++;
    @$escT[$v['fk_id_inst']] ++;
    @$serieT[$v['seriacao']] ++;
    $esc[$v['id_inst']] = $v['n_inst'];
}
$ciclos = ['Berçário', '1ª Fase - Maternal', '2ª Fase - Maternal', '3ª Fase - Maternal'];
asort($esc);
?>

<div class="fieldBody">
    <div style="background-color: #E0E0E0">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td colspan="8" style="text-align: center; font-size: 18px; font-weight: bold;">
                        Totalização
                    </td>
                </tr>
                <tr style="background-color: white; font-weight: bold">
                    <?php
                    foreach ($ciclos as $ci) {
                        ?>
                        <td>
                            <?php echo $ci ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td style="background-color: khaki">
                        Total
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                    foreach ($ciclos as $ci) {
                        ?>
                        <td>
                            <?php echo @@$serieT[$ci] ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td style="background-color: khaki">
                        <?php echo count($i) ?>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>  
    <div style="background-color: #E0E0E0">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td colspan="8" style="text-align: center; font-size: 18px; font-weight: bold;">
                        Totais por Escola
                    </td>
                </tr>
                <tr style="background-color: white; font-weight: bold">
                    <td>
                        Escola
                    </td>
                    <?php
                    foreach ($ciclos as $ci) {
                        ?>
                        <td>
                            <?php echo $ci ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td style="background-color: khaki">
                        Total
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($esc as $k => $v) {
                    ?>
                    <tr>
                        <td style="font-weight: bold; border-bottom: solid white 2px">
                            <form target="_blank" action="<?php echo HOME_URI ?>/vagas/pdflistatrabalho" id="idinst" method="POST">
                                <input id="esc" type="hidden" name="fk_id_inst" value="<?php echo $k ?>" />
                                <input id="inform" type="hidden" name="inform" value="<?php echo 'Rede' ?>" />
                                <input class="btn btn-link" type="submit" value="<?php echo $v ?>" />
                            </form>
                        </td>
                        <?php
                        foreach ($ciclos as $ci) {
                            ?>
                            <td style="border-bottom: solid white 2px">
                                <?php echo @$alu[$k][$ci] ?>
                            </td>
                            <?php
                        }
                        ?>
                        <td style="background-color: khaki">
                            <?php echo @$escT[$k] ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div> 
</div>