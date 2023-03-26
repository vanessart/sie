<?php
$esco = escolas::idInst();
$sql = "select distinct s.n_sel, p.n_pessoa, p.tel1, p.tel2, p.tel3, p.email, c.cidade, c.bairro, c.id_cad, c.fk_id_inscr, classifica, c.cargos_e "
        . " from cadam_cadastro c "
        . " join pessoa p on p.id_pessoa = c.fk_id_pessoa "
        . " join cadam_seletivas s on s.id_sel = c.fk_id_sel "
        . " where c.tea = 1 "
        . " and c.ativo = 1";
$query = $model->db->query($sql);
$tea = $query->fetchAll();
$carg = sql::get('cadam_cargo');
foreach ($carg as $v) {
    $cargo[$v['id_cargo']] = $v['n_cargo'];
}
?>

<div class="fieldBody">
    <table border=1 cellspacing=0 cellpadding=1 style="width: 100%">
        <tr>
            <td>
                Seletiva
            </td>
            <td>
                Class
            </td>
            <td>
                Nome
            </td>
            <td>
                Telefone
            </td>
            <td>
                Email
            </td>
            <td>
                Cidade
            </td>
            <td>
                Bairro
            </td>
            <td>
                Escola
            </td>
            <td>
                Escola/Aluno 
            </td>
        </tr>
        <?php
        foreach ($tea as $v) {
            ?>
            <tr>
                <td>
                    <?php echo $v['n_sel'] ?>
                </td>
                <td>
                    <?php
                    $sql = "select distinct * from cadam_class cl "
                            . "left join cadam_cargo cc on cc.id_cargo = cl.fk_id_cargo "
                            . " where fk_id_inscr = " . $v['fk_id_inscr']
                            . ' and id_cargo in (3,4) '
                            . ' order by class';
                    $query = $model->db->query($sql);
                    $cl = $query->fetchAll();
                    if (empty($cl)) {
                        echo!empty($v['classifica']) ? $v['classifica'] . '(lanc. Manual)<br />' : '';
                        echo '(' . $cargo[explode('|', $v['cargos_e'])[1]] . ')';
                    } else {
                        foreach ($cl as $ccx) {
                            echo $ccx['class'] . '(' . $ccx['n_cargo'] . ')<br />';
                        }
                    }
                    ?>

                </td>
                <td>
                    <?php echo $v['n_pessoa'] ?>
                </td>
                <td>
                    <?php echo (!empty($v['tel1']) ? $v['tel1'] : '') . (!empty($v['tel2']) ? '<br />' . $v['tel2'] : '') . (!empty($v['tel3']) ? '<br />' . $v['tel3'] : '') ?>
                </td>
                <td>
                    <?php echo $v['email'] ?>
                </td>
                <td>
                    <?php echo $v['cidade'] ?>
                </td>
                <td>
                    <?php echo $v['bairro'] ?>
                </td>
                <td>
                    <?php
                    $sql = "select fk_id_inst from cadam_escola "
                            . " where fk_id_cad = " . $v['id_cad'];
                    $query = $model->db->query($sql);
                    $esc = $query->fetchAll();
                    foreach ($esc as $ve) {
                        echo $esco[$ve['fk_id_inst']] . '<hr>';
                    }
                    ?>
                </td>
                <td>
                    <table border=1 cellspacing=0 cellpadding=1 style="width: 100%">
                        <?php
                        $sql = "select n_pessoa, n_inst from instancia i "
                                . "join ge_turmas t on t.fk_id_inst = i.id_inst "
                                . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                                . " join ge_turma_aluno ta on ta.fk_id_turma = t.id_turma "
                                . "join pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                                . "join cadam_tea te on te.fk_id_pessoa = p.id_pessoa "
                                . "where fk_id_cad = " . $v['id_cad']
                                . " AND at_pl = 1 ";
                        $query = $model->db->query($sql);
                        $array = $query->fetchAll();
                        if (!empty($array)) {
                            foreach ($array as $i) {
                                echo '<tr><td>' . $i['n_pessoa'] . '</td><td>' . $i['n_inst'] . ')</td></tr>';
                            }
                        }
                        ?>
                    </table>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>
