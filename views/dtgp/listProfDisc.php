<?php
ob_start();
$clas = sql::get('dtpg_class');

foreach ($clas as $v) {
    @$class[$v['fk_id_inscr']][$v['fk_id_cargo']] = $v['class'];
}

$id_inst = @$_POST['id_inst'];
echo $sql = "SELECT "
        . " c.id_cad, c.dia, c.classifica, s.n_sel, c.n_insc, c.tel1, c.tel2, c.tel3, c.email, ce.m, ce.t, ce.n, c.cargos_e, c.fk_id_sel, c.fk_id_inscr,  ce.fk_id_cargo, c.tea  "
        . " FROM dtgp_cadampe_esc ce "
        . " join dtgp_cadampe c on c.id_cad = ce.fk_id_cad "
        . " join dtpg_seletivas s on s.id_sel = c.fk_id_sel "
        . " WHERE `fk_id_inst` = " . $id_inst
        . " and c.ativo = 1 "
        . " and c.antigo = 1 ";
$query = $model->db->query($sql);
$da = $query->fetchAll();

foreach ($da as $v) {
    unset($pe);
    if (!empty($v['m'])) {
        $pe[] = 'M';
    }
    if (!empty($v['n'])) {
        $pe[] = 'N';
    }
    if (!empty($v['t'])) {
        $pe[] = 'T';
    }
    if (!empty($v['cargos_e'])) {
        $carg = explode('|', $v['cargos_e']);
        foreach ($carg as $cc) {
            if (!empty($cc)) {
                if (substr($v['n_sel'], 0, 1) == 'C') {
                    $pos = 1000000000000 + $v['classifica'];
                } else {
                    $pos = 2000000000000 + $class[$v['fk_id_inscr']][$cc];
                    @$v['classifica'] = $class[$v['fk_id_inscr']][$cc];
                }
                unset($dia);
                $dia_ = explode('|', $v['dia']);
                foreach ($dia_ as $di) {
                    if (in_array(substr($di, 1), $pe)) {
                        @$dia[substr($di, 0, 1)] .= substr($di, 1) . ' ';
                    }
                }
                $v['dias'] = $dia;
                if ($v['fk_id_cargo'] == $cc)
                    $dados[$cc][$pos] = $v;
            }
        }
    }
}
?>
<div style="text-align: center; font-size: 16px">
    Listagem por Disciplina com dados do CADAMPE
    - 
    <?php echo sql::get('instancia', 'n_inst', ['id_inst' => $id_inst], 'fetch')['n_inst'] ?>
</div>
<br />
<div style="text-align: center; font-size: 16px">
    Departamento Técnico de Gestão de Pessoal
</div>
<br /><br />
<div style="text-align: left; font-size: 12px">
    M = Manhã; T = Tarde; N = Noite
</div>
<style>
    th{
        background: black;
        color: white;
    }
    td{
        padding: 5px;
    }
</style>
<table style="width: 100%" border=1 cellspacing=0 cellpadding=1>
    <?php
    $cg = sql::get('dtgp_cadampe_cargo', 'id_cargo, n_cargo', ['>' => 'ordem']);
    foreach ($cg as $v) {
        ?>
        <tr>
            <th colspan="5" style="text-align: left">
                <?php echo $v['n_cargo'] ?>
            </th>
            <th colspan="5" style="text-align: center">
                Período
            </th>
        </tr>
        <tr>
            <td>
                Class.
            </td>
            <td>
                Tipo Processo
            </td>
            <td>
                Nome CADAMPE
            </td>
            <td>
                Telefones
            </td>
            <td>
                E-mail
            </td>
            <td>
                TEA
            </td>
            <td>
                Seg
            </td>
            <td>
                Ter
            </td>
            <td>
                Qua
            </td>
            <td>
                Qui
            </td>
            <td>
                Sex
            </td>
        </tr>
        <?php
        if (!empty(@$dados[$v['id_cargo']])) {
            ksort($dados[$v['id_cargo']]);
            foreach ($dados[$v['id_cargo']] as $i) {
                ?>
                <tr>
                    <td>
                        <?php echo @$i['classifica'] ?>
                    </td>
                    <td>
                        <?php echo @$i['n_sel'] ?>
                    </td>
                    <td>
                        <?php echo @$i['n_insc'] ?>
                    </td>
                    <td>
                        <?php echo (!empty($i['tel1']) ? $i['tel1'] : '') . (!empty($i['tel2']) ? '<br />' . $i['tel2'] : '') . (!empty($i['tel3']) ? '<br />' . $i['tel3'] : '') ?>
                    </td>
                    <td>
                        <?php echo @$i['email'] ?>
                    </td>
                    <td style="text-align: center; white-space: nowrap">
                        <?php
                        if (!empty(@$i['tea'])) {
                            ?>
                            Sim
                            <?php
                        }
                        ?>
                    </td>
                    <td style="text-align: center; white-space: nowrap">
                        <?php echo @$i['dias'][1] ?>
                    </td>
                    <td style="text-align: center; white-space: nowrap">
                        <?php echo @$i['dias'][2] ?>
                    </td>
                    <td style="text-align: center; white-space: nowrap">
                        <?php echo @$i['dias'][3] ?>
                    </td>
                    <td style="text-align: center; white-space: nowrap">
                        <?php echo @$i['dias'][4] ?>
                    </td>
                    <td style="text-align: center; white-space: nowrap">
                        <?php echo @$i['dias'][5] ?>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
        <?php
    }
    ?> 
</table>
<?php
tool::pdfSecretaria('L');
?>