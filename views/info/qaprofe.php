<?php
$disc = disciplina::discId();
$ci = sql::get('ge_ciclos');

foreach ($ci as $c) {
    $ciclo[$c['id_ciclo']] = $c['n_ciclo'] . ($c['fk_id_curso'] == 5 ? '(1º S.)' : ($c['fk_id_curso'] == 9 ? '(2º S.)' : ''));
}

$sql = "SELECT pe.id_pessoa, p.fk_id_inst, iddisc, fk_id_ciclo,f.rm, pe.n_pessoa FROM ge_aloca_prof p  "
        . "join ge_turmas t on t.id_turma = p.fk_id_turma "
        . "join ge_funcionario f on f.rm = p.rm "
        . " join pessoa pe on pe.id_pessoa = f.fk_id_pessoa "
        . "order by p.fk_id_inst, n_pessoa";
$query = $model->db->query($sql);
$array = $query->fetchAll();
foreach ($array as $v) {
    if (!in_array($v['fk_id_ciclo'], [19, 20, 21, 22, 23, 24])) {
        $dados[$v['id_pessoa']]['id_inst'] = $v['fk_id_inst'];
        $dados[$v['id_pessoa']]['n_pessoa'] = $v['n_pessoa'];
        $dados[$v['id_pessoa']]['iddisc'] = $v['iddisc'];
        @$dados[$v['id_pessoa']][$v['iddisc']]['ciclo'][$v['fk_id_ciclo']] = $ciclo[$v['fk_id_ciclo']];
    }
}
foreach ($dados as $k => $v) {
    $da[$v['id_inst']][$disc[$v['iddisc']]][$k]['n_pessoa'] = $dados[$k]['n_pessoa'];
    foreach ($v[$v['iddisc']]['ciclo'] as $key => $y) {
        $da[$v['id_inst']][$disc[$v['iddisc']]][$k]['ciclos'][$key] = $key;
    }
}
$sql = "select n_inst, id_inst from instancia i "
        . " join ge_escolas e on e.fk_id_inst = i.id_inst "
        . " where fk_id_tp_ens like '%|4|%' "
        . " or fk_id_tp_ens like '%|5|%' "
        . "order by n_inst";
$query = $model->db->query($sql);
$esc = $query->fetchAll();
?>
<br />
<?php
if (empty($_POST['impr'])) {
    ?>
    <form target="_blank" style="text-align: right;width: 90%; margin-right: 100px" method="POST">
        <input class="btn btn-info" type="submit" value="Imprimir" name="impr" />
    </form>
    <?php
}
?>
<div style="text-align: center; font-size: 18px">
    Relação Professor/Disciplina/Ciclo
</div>
<br /><br />
<table border=1 cellspacing=0 cellpadding=1 style="width: 100%">
    <?php
    foreach ($esc as $v) {
        ?>
        <tr>
            <td colspan="16" style="background-color: black; color: white">
                <?php echo $v['n_inst'] ?>
            </td>
        </tr>
        <?php
        if (!empty($da[$v['id_inst']])) {
            foreach ($da[$v['id_inst']] as $kk => $vv) {
                ?>
                <tr>
                    <td colspan="16" style="background-color: #f5e79e">
                        <?php echo $kk ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Nome
                    </td>
                    <td>
                        1º Ano
                    </td>
                    <td>
                        2º Ano
                    </td>
                    <td>
                        3º Ano
                    </td>
                    <td>
                        4º Ano
                    </td>
                    <td>
                        5º Ano
                    </td>
                    <td>
                        6º Ano
                    </td>
                    <td>
                        7º Ano
                    </td>
                    <td>
                        8º Ano
                    </td>
                    <td>
                        9º Ano
                    </td>
                    <td>
                        1º ter 1º seg
                    </td>
                    <td>
                        2º ter 1º seg
                    </td>
                    <td>
                        1º ter 2º seg
                    </td>
                    <td>
                        2º ter 2º seg
                    </td>
                    <td>
                        3º ter 2º seg
                    </td>
                    <td>
                        4º ter 2º seg
                    </td>
                </tr>
                <?php
                foreach ($vv as $kkk => $vvv) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $vvv['n_pessoa'] ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($vvv['ciclos'][1])) {
                                echo 'X';
                                @$conta[$v['id_inst']][$kk][1] ++;
                                @$contaDiscCiclo[$kk][1] ++;
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($vvv['ciclos'][2])) {
                                echo 'X';
                                @$conta[$v['id_inst']][$kk][2] ++;
                                @$contaDiscCiclo[$kk][2] ++;
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($vvv['ciclos'][3])) {
                                echo 'X';
                                @$conta[$v['id_inst']][$kk][3] ++;
                                @$contaDiscCiclo[$kk][3] ++;
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($vvv['ciclos'][4])) {
                                echo 'X';
                                @$conta[$v['id_inst']][$kk][4] ++;
                                @$contaDiscCiclo[$kk][4] ++;
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($vvv['ciclos'][5])) {
                                echo 'X';
                                @$conta[$v['id_inst']][$kk][5] ++;
                                @$contaDiscCiclo[$kk][5] ++;
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($vvv['ciclos'][6])) {
                                echo 'X';
                                @$conta[$v['id_inst']][$kk][6] ++;
                                @$contaDiscCiclo[$kk][6] ++;
                            }
                            ?>
                        <td>
                            <?php
                            if (!empty($vvv['ciclos'][7])) {
                                echo 'X';
                                @$conta[$v['id_inst']][$kk][7] ++;
                                @$contaDiscCiclo[$kk][7] ++;
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($vvv['ciclos'][8])) {
                                echo 'X';
                                @$conta[$v['id_inst']][$kk][8] ++;
                                @$contaDiscCiclo[$kk][8] ++;
                            }
                            ?>
                        <td>
                            <?php
                            if (!empty($vvv['ciclos'][9])) {
                                echo 'X';
                                @$conta[$v['id_inst']][$kk][9] ++;
                                @$contaDiscCiclo[$kk][9] ++;
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($vvv['ciclos'][25])) {
                                echo 'X';
                                @$conta[$v['id_inst']][$kk][25] ++;
                                @$contaDiscCiclo[$kk][25] ++;
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($vvv['ciclos'][26])) {
                                echo 'X';
                                @$conta[$v['id_inst']][$kk][26] ++;
                                @$contaDiscCiclo[$kk][26] ++;
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($vvv['ciclos'][27])) {
                                echo 'X';
                                @$conta[$v['id_inst']][$kk][27] ++;
                                @$contaDiscCiclo[$kk][27] ++;
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($vvv['ciclos'][28])) {
                                echo 'X';
                                @$conta[$v['id_inst']][$kk][28] ++;
                                @$contaDiscCiclo[$kk][28] ++;
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($vvv['ciclos'][29])) {
                                echo 'X';
                                @$conta[$v['id_inst']][$kk][29] ++;
                                @$contaDiscCiclo[$kk][29] ++;
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if (!empty($vvv['ciclos'][30])) {
                                echo 'X';
                                @$conta[$v['id_inst']][$kk][30] ++;
                                @$contaDiscCiclo[$kk][30] ++;
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td>
                        Total - <?php echo $kk ?> - <?php echo $v['n_inst'] ?>
                    </td>
                    <td>
                        <?php echo intval(@$conta[$v['id_inst']][$kk][1]) ?>
                    </td>
                    <td>
                        <?php echo intval(@$conta[$v['id_inst']][$kk][2]) ?>
                    </td>
                    <td>
                        <?php echo intval(@$conta[$v['id_inst']][$kk][3]) ?>
                    </td>
                    <td>
                        <?php echo intval(@$conta[$v['id_inst']][$kk][4]) ?>
                    </td>
                    <td>
                        <?php echo intval(@$conta[$v['id_inst']][$kk][5]) ?>
                    </td>
                    <td>
                        <?php echo intval(@$conta[$v['id_inst']][$kk][6]) ?>
                    </td>
                    <td>
                        <?php echo intval(@$conta[$v['id_inst']][$kk][7]) ?>
                    </td>
                    <td>
                        <?php echo intval(@$conta[$v['id_inst']][$kk][8]) ?>
                    </td>
                    <td>
                        <?php echo intval(@$conta[$v['id_inst']][$kk][9]) ?>
                    </td>
                    <td>
                        <?php echo intval(@$conta[$v['id_inst']][$kk][25]) ?>
                    </td>
                    <td>
                        <?php echo intval(@$conta[$v['id_inst']][$kk][26]) ?>
                    </td>
                    <td>
                        <?php echo intval(@$conta[$v['id_inst']][$kk][27]) ?>
                    </td>
                    <td>
                        <?php echo intval(@$conta[$v['id_inst']][$kk][28]) ?>
                    </td>
                    <td>
                        <?php echo intval(@$conta[$v['id_inst']][$kk][29]) ?>
                    </td>
                    <td>
                        <?php echo intval(@$conta[$v['id_inst']][$kk][30]) ?>
                    </td>
                </tr>
                <?php
            }
        }
    }
    ?>
</table>

<br /><br />

<table border=1 cellspacing=0 cellpadding=1 style="width: 100%">
    <tr>
        <td colspan="17" style="background-color: black; color: white">
            Totais Por Disciplina e Ciclo
        </td>
    </tr>
    <tr style="background-color: #f5e79e">
        <td>
            Disciplina
        </td>
        <td>
            1º Ano
        </td>
        <td>
            2º Ano
        </td>
        <td>
            3º Ano
        </td>
        <td>
            4º Ano
        </td>
        <td>
            5º Ano
        </td>
        <td>
            6º Ano
        </td>
        <td>
            7º Ano
        </td>
        <td>
            8º Ano
        </td>
        <td>
            9º Ano
        </td>
        <td>
            1º ter 1º seg
        </td>
        <td>
            2º ter 1º seg
        </td>
        <td>
            1º ter 2º seg
        </td>
        <td>
            2º ter 2º seg
        </td>
        <td>
            3º ter 2º seg
        </td>
        <td>
            4º ter 2º seg
        </td>
        <td>
            Total
        </td>
    </tr>
    <?php
    $t=0;
    foreach ($contaDiscCiclo as $k => $v) {
        ?>
        <tr style="border-bottom: solid #000000 1px">
            <td>
                <?php echo $k ?>
            </td>
            <td>
                <?php echo intval(@$v[1]); intval(@$t+=$v[1])  ?>
            </td>
            <td>
                <?php echo intval(@$v[2]); intval(@$t+=$v[2]) ?>
            </td>
            <td>
                <?php echo intval(@$v[3]); intval(@$t+=$v[3]) ?>
            </td>
            <td>
                <?php echo intval(@$v[4]); intval(@$t+=$v[4]) ?>
            </td>
            <td>
                <?php echo intval(@$v[5]); intval(@$t+=$v[5]) ?>
            </td>
            <td>
                <?php echo intval(@$v[6]); intval(@$t+=$v[6]) ?>
            </td>
            <td>
                <?php echo intval(@$v[7]); intval(@$t+=$v[7]) ?>
            </td>
            <td>
                <?php echo intval(@$v[8]); intval(@$t+=$v[8]) ?>
            </td>
            <td>
                <?php echo intval(@$v[9]); intval(@$t+=$v[9]) ?>
            </td>
            <td>
                <?php echo intval(@$v[25]); intval(@$t+=$v[25]) ?>
            </td>
            <td>
                <?php echo intval(@$v[26]);  intval(@$t+=$v[26])?>
            </td>
            <td>
                <?php echo intval(@$v[27]) ; intval(@$t+=$v[27])?>
            </td>
            <td>
                <?php echo intval(@$v[28]); intval(@$t+=$v[28]) ?>
            </td>
            <td>
                <?php echo intval(@$v[29]);  intval(@$t+=$v[29])?>
            </td>
            <td>
                <?php echo intval(@$v[30]) ; intval(@$t+=$v[30])?>
            </td>
            <td>
                <?php echo $t ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
