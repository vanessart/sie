<div style="text-align: center">
    QUADRO POR ESCOLA
</div>
<br /><br />
<?php
$cargo = sql::get('cadam_cargo');

foreach ($cargo as $cg) {
    unset($escolas);
    unset($usado);
    $dis = sql::get(['_cadam_escola', 'instancia'], 'fk_id_inst, m, n, t', ['fk_id_cargo' => @$cg['id_cargo']]);
    foreach ($dis as $v) {
        @$usado[$v['fk_id_inst']]['m'] += str_replace('X', 1, $v['m']);
        @$usado[$v['fk_id_inst']]['n'] += str_replace('X', 1, $v['n']);
        @$usado[$v['fk_id_inst']]['t'] += str_replace('X', 1, $v['t']);
    }

    $sql = " select n_inst, id_inst, bairro, m, t, n from instancia i "
            . " join ge_escolas e on e.fk_id_inst = i.id_inst "
            . " left join instancia_predio ip on ip.fk_id_inst = i.id_inst "
            . " left join predio p on p.id_predio = ip.fk_id_predio "
            . " left join cadam_esc_vaga cv on cv.fk_id_inst = i.id_inst and cv.fk_id_cargo = " . @$cg['id_cargo'] . ' '
            . " order by n_inst";
    $query = $model->db->query($sql);
    $escolas = $query->fetchAll();
    $terceirizadas = [130, 121, 123, 105, 122, 128, 76, 129];
    foreach ($escolas as $k => $v) {
        $escolas[$k]['pm'] = $v['m'];
        $escolas[$k]['pn'] = $v['n'];
        $escolas[$k]['pt'] = $v['t'];
        $escolas[$k]['um'] = @$usado[$v['id_inst']]['m'];
        $escolas[$k]['un'] = @$usado[$v['id_inst']]['n'];
        $escolas[$k]['ut'] = @$usado[$v['id_inst']]['t'];
        $escolas[$k]['m'] = $v['m'] - @$usado[$v['id_inst']]['m'];
        $escolas[$k]['n'] = $v['n'] - @$usado[$v['id_inst']]['n'];
        $escolas[$k]['t'] = $v['t'] - @$usado[$v['id_inst']]['t'];
        if (in_array($v['id_inst'], $terceirizadas)) {
            unset($escolas[$k]);
        }
    }
    ?>
    <table border=1 cellspacing=0 cellpadding=1 style="width: 100%">
        <tr>
            <td colspan="10" style="text-align: left; background-color: black; color: white">
                <?php echo $cg['n_cargo'] ?>
            </td>
        </tr>
        <tr>
            <td rowspan="2">
                Escola
            </td>
            <td colspan="3">
                Manhã
            </td>
            <td colspan="3">
                Tarde
            </td>
            <td colspan="3">
                Noite
            </td>
        </tr>
        <tr>
            <td>
                Previstas
            </td>
            <td>
                Usadas
            </td>
            <td>
                Disponível
            </td>
            <td>
                Previstas
            </td>
            <td>
                Usadas
            </td>
            <td>
                Disponível
            </td>
            <td>
                Previstas
            </td>
            <td>
                Usadas
            </td>
            <td>
                Disponível
            </td>
        </tr>
        <?php
          unset($tpm);
            
             unset($tum);
           unset($tm);
           
            unset($tpt);
            unset($tut);
            unset($tt);
            unset($tpn);
            unset($tun);
            unset($tn);
            unset($tpt);
            unset($tut);
            unset($tpm);
            unset($tpm);
        foreach ($escolas as $e) {
            ?>

            <tr>
                <td>
                    <?php echo @$e['n_inst'] ?>
                </td>
                <td>
                    <?php
                    echo @$e['pm'];
                    @$tpm += @$e['pm'];
                    ?>
                </td>
                <td>
                    <?php
                    echo @$e['um'];
                    @$tum += @$e['um'];
                    ?>
                </td>
                <td>
                    <?php
                    echo @$e['m'];
                    @$tm += @$e['m'];
                    ?>
                </td>
                <td>
        <?php
        echo @$e['pt'];
        @$tpt += @$e['pt']
        ?>
                </td>
                <td>
                    <?php
                    echo @$e['ut'];
                    @$tut += @$e['ut'];
                    ?>
                </td>
                <td>
                    <?php
                    echo @$e['t'];
                    @$tt += @$e['t']
                    ?>
                </td>
                <td>
                    <?php
                    echo @$e['pn'];
                    @$tpn += @$e['pn']
                    ?>
                </td>
                <td>
                    <?php
                    echo @$e['un'];
                    @$tun += @$e['un']
                    ?>
                </td>
                <td>
        <?php
        echo @$e['n'];
        @$tn += @$e['n']
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
                <?php echo @$tpm ?>
            </td>
            <td>
                <?php echo @$tum ?>
            </td>
            <td>
                <?php echo @$tm ?>
            </td>
            <td>
                <?php echo @$tpt ?>
            </td>
            <td>
                <?php echo @$tut ?>
            </td>
            <td>
                <?php echo @$tt ?>
            </td>
            <td>
    <?php echo @$tpn ?>
            </td>
            <td>
    <?php echo @$tun ?>
            </td>
            <td>
    <?php echo @$tn ?>
            </td>
    </table>
    <div style="page-break-after: always"></div>
    <?php
}
?>

