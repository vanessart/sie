<?php
if (!function_exists('ceiling')) {

    function ceiling($number, $significance = 1) {
        return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number / $significance) * $significance) : false;
    }

}

function porcetagem($nota) {
    if ($nota > 100) {
        return 'Erro';
    } elseif ($nota >= 8.1) {
        return '100%';
    } elseif ($nota >= 6.5) {
        return '70%';
    } elseif ($nota >= 5) {
        return '50%';
    } else {
        return '0%';
    }
}
?>
<div class="fieldBody">
    <div class="row">
        <div class="col-sm-3">
            <form method="POST">
                <input name="criar" type="submit" value="Alimentar Tabela" />
            </form> 
        </div>
        <div class="col-sm-3">
            <form method="POST">
                <input name="adi" type="submit" value="ADI" />
            </form>
        </div>
        <div class="col-sm-3">
            <form method="POST">
                <input name="nota_esc" type="submit" value="Media escola" />
            </form>
        </div>
        <div class="col-sm-3">
            <form method="POST">
                <input name="nota_rede" type="submit" value="Media Rede" />
            </form>  
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-3">
            <form method="POST">
                <input name="aloca" type="submit" value="Alocar Professores" />
            </form> 
        </div>
        <div class="col-sm-3">
            <form method="POST">
                <input name="calcular" type="submit" value="Calcular" />
            </form> 
        </div>
        <div class="col-sm-3">

        </div>
        <div class="col-sm-3">

        </div>
        <div class="col-sm-3">

        </div>
    </div>

    <?php
    if (!empty($_POST['criar'])) {
        $sql = "INSERT INTO `prod_main` (`id_main`, `rm`, `fk_id_pessoa`, `n_pessoa`, `fk_id_inst`, `funcao`, `SUB_SECAO_TRABALHO`) SELECT NULL as id_main, f.MATRICULA as rm, p.id_pessoa as fk_id_pessoa, p.n_pessoa, gf.fk_id_inst as fk_id_inst, f.FUNCAO as funcao, f.SUB_SECAO_TRABALHO FROM funcionarios f JOIN ge_funcionario gf on gf.rm = f.MATRICULA JOIN pessoa p on p.id_pessoa = gf.fk_id_pessoa 
WHERE f.`funcao` LIKE '%prof%' 
OR  f.`funcao` LIKE '%AUXILIAR DE SERVICOS%' 
OR  f.`funcao` LIKE '%AUXILIAR DE CLASSE%' 
OR  f.`funcao` LIKE '%INSPETOR%' 
OR  f.`funcao` LIKE '%INSTRUTOR%' 
OR  f.`funcao` LIKE '%TRADUTOR%' 
OR  f.`funcao` LIKE '%ASSISTENTE%' 
OR  f.`funcao` LIKE '%AGENTE DE DESENVOLVIMENTO%' 
OR  f.`funcao` LIKE '%DIRETOR DE UNIDADE%' ";
        $query = pdoSis::getInstance()->query($sql);
    }
    if (!empty($_POST['adi'])) {
        echo '<br />' . $sql = "UPDATE prod_assist a JOIN prod_main m on m.rm = a.id_rm SET m.nota_assist = a.nota ";
        $query = pdoSis::getInstance()->query($sql);
    }
    if (!empty($_POST['nota_esc'])) {
        $sql = 'SELECT fk_id_inst, AVG(nota) as nota FROM `prod_nota_turma` GROUP by fk_id_inst ';
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            echo '<br />' . $sql = "replace INTO `prod_media` (`id_inst`, `media`) VALUES ('" . $v['fk_id_inst'] . "', '" . $v['nota'] . "');";
            $query = pdoSis::getInstance()->query($sql);
            echo '<br />' . $sql = "UPDATE `prod_main` SET `nota_esc` = '" . $v['nota'] . "' "
            . " where fk_id_inst = " . $v['fk_id_inst'];
            $query = pdoSis::getInstance()->query($sql);
        }
    }
    if (!empty($_POST['nota_rede'])) {
        echo '<br />' . $sql = "SELECT AVG(nota) as nota FROM `prod_nota_turma`";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        echo '<br />' . $sql = "UPDATE `prod_main` SET `nota_rede` = '" . $array['nota'] . "' ;";
        $query = pdoSis::getInstance()->query($sql);
    }
    if (!empty($_POST['aloca'])) {
        $sql = "SELECT * FROM `ge_disciplinas` ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $disc[$v['id_disc']] = $v['n_disc'];
        }
        $disc['nc'] = 'Polivalente';

        $sql = "SELECT "
                . " i.n_inst, i.id_inst, t.id_turma, t.n_turma, a.iddisc, a.rm, t.fk_id_ciclo, n.nota "
                . " FROM ge_aloca_prof a "
                . " JOIN instancia i on i.id_inst = a.fk_id_inst "
                . " JOIN ge_turmas t on t.id_turma = a.fk_id_turma "
                . " left join prod_nota_turma n on n.id_turma_nota = t.id_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            if (in_array($v['fk_id_ciclo'], [1, 2, 3, 4, 5, 6, 7, 8, 9])) {
                if ($v['iddisc'] == 'nc') {
                    $aloca[$v['rm']]['fund1'][$v['id_turma']] = ['id_inst' => $v['id_inst'], 'n_inst' => $v['n_inst'], 'n_turma' => $v['n_turma'], 'nota' => $v['nota'], 'fk_id_ciclo' => $v['fk_id_ciclo']];
                } else {
                    $aloca[$v['rm']]['fund2'][$v['id_turma']] = ['id_inst' => $v['id_inst'], 'n_inst' => $v['n_inst'], 'n_turma' => $v['n_turma'], 'nota' => $v['nota'], 'fk_id_ciclo' => $v['fk_id_ciclo']];
                }
            } elseif (in_array($v['fk_id_ciclo'], [19, 20, 21, 22, 23, 24])) {
                $aloca[$v['rm']]['infa'][$v['id_turma']] = ['id_inst' => $v['id_inst'], 'n_inst' => $v['n_inst'], 'n_turma' => $v['n_turma'], 'nota' => $v['nota'], 'fk_id_ciclo' => $v['fk_id_ciclo']];
            } elseif (in_array($v['fk_id_ciclo'], [25, 026, 27, 28, 29, 30])) {
                $aloca[$v['rm']]['eja'][$v['id_turma']] = ['id_inst' => $v['id_inst'], 'n_inst' => $v['n_inst'], 'n_turma' => $v['n_turma'], 'nota' => $v['nota'], 'fk_id_ciclo' => $v['fk_id_ciclo']];
            } elseif ($v['fk_id_ciclo'] == 32) {
                $aloca[$v['rm']]['aee'][$v['id_turma']] = ['id_inst' => $v['id_inst'], 'n_inst' => $v['n_inst'], 'n_turma' => $v['n_turma'], 'nota' => $v['nota'], 'fk_id_ciclo' => $v['fk_id_ciclo']];
            }
        }
        foreach ($aloca as $k => $v) {
            if (strlen($k) < 10 && is_numeric($k)) {
                $turmas = serialize($v);
                $sql = "UPDATE `prod_main` SET `nota_turma` = '$turmas' WHERE `rm` = $k ";
                $query = pdoSis::getInstance()->query($sql);
            }
        }
    }
    if (!empty($_POST['calcular'])) {

        $func = sql::get('prod_main');

        foreach ($func as $v) {

            if ($v['fk_id_inst'] == 13) {
                $nota_final = ceiling($v['nota_rede'], 0.1);
                $perc = porcetagem($nota_final);
                $calculo = "Seu bônus foi calculado usando a Nota da Rede";
                if (!empty($V['nota_turma'])) {
                    $erro = 1;
                }

                $sql = "UPDATE prod_main SET "
                        . " `metodo` = 3, "
                        . " `nota_final` = '$nota_final', "
                        . " `perc` = '$perc', "
                        . " `calculo` = '$calculo' "
                        . " WHERE `rm` = " . $v['rm'];
                   $query = pdoSis::getInstance()->query($sql);
            } elseif (in_array(substr($v['funcao'], 0, 6), ['AGENTE', 'ASSIST'])) {
                if (!empty($v['nota_esc']) && !empty($v['nota_assist'])) {
                    $nota_final = ceiling((($v['nota_esc'] + $v['nota_assist']) / 2), 0.1);
                    $perc = porcetagem($nota_final);

                    $calculo = "Seu bônus foi calculado usando a Média entre a Nota da Unidade Escolar e a Avaliação-Diretor."
                            . "<br />"
                            . "<table style=\"padding: 15px\" border=1 cellspacing=0 cellpadding=1>
                                    <tr>
                                        <td>
                                            A - Nota da Escola
                                        </td>
                                        <td>
                                            " . round($v['nota_esc'], 1) . "
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            B - Nota da Avaliação-Diretor
                                        </td>
                                        <td>
                                               " . round($v['nota_assist'], 1) . "
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Média ( (A+B)/2 )
                                        </td>
                                        <td>
                                              $nota_final
                                        </td>
                                    </tr>
                              </table>"
                            . "";
                } else {
                    $erro = 2;
                }
                if (!empty($V['nota_turma'])) {
                    $erro = 1;
                }

                $sql = "UPDATE prod_main SET "
                        . " `metodo` = 2, "
                        . " `nota_final` = '$nota_final', "
                        . " `perc` = '$perc', "
                        . " `calculo` = '$calculo' "
                        . " WHERE `rm` = " . $v['rm'];
                  $query = pdoSis::getInstance()->query($sql);
            } elseif (!empty($v['nota_esc']) && empty($v['nota_turma'])) {
                $nota_final = ceiling($v['nota_esc'], 0.1);
                $perc = porcetagem($nota_final);
                $calculo = "Seu bônus foi calculado usando a Nota da Unidade Escolar";

                $sql = "UPDATE prod_main SET "
                        . " `metodo` = 1, "
                        . " `nota_final` = '$nota_final', "
                        . " `perc` = '$perc', "
                        . " `calculo` = '$calculo' "
                        . " WHERE `rm` = " . $v['rm'];
                $query = pdoSis::getInstance()->query($sql); 
            } elseif (!empty($v['nota_turma'])) {
                //if(in_array($v['rm'], [27115, 4068, 3405, 7290]))
                $turmas = unserialize($v['nota_turma']);
                if (!empty($turmas['fund1']) && empty($turmas['fund2'])) {
                    $nota = 0;
                    $conta = 0;
                    $calculo = "Seu bônus foi calculado usando a  Média da(s) sala(s) que está alocado como titular."
                            . "<br /><br />"
                            . "<table style=\"padding: 8px; width: 100%\" border=1 cellspacing=0 cellpadding=1>"
                            . "<tr>"
                            . "<td>"
                            . "Classe"
                            . "</td>"
                            . "<td>"
                            . "Nota"
                            . "</td>"
                            . "</tr>";
                    foreach ($turmas['fund1'] as $c) {
                        $calculo .= ""
                                . "<tr>"
                                . "<td>"
                                . $c['n_turma'] . ' (' . $c['n_inst'] . ')'
                                . "</td>"
                                . "<td>"
                                . $c['nota']
                                . "</td>"
                                . "</tr>";
                        $nota += $c['nota'];
                        $conta++;
                    }
                    if ($conta > 1) {
                        $nota_final = ($nota / $conta);
                        $calculo .= ""
                                . "<tr>"
                                . "<td>"
                                . "Média"
                                . "</td>"
                                . "<td style=\"padding: 8px; width: 10%\">"
                                . ceiling($nota_final, 0.1)
                                . "</td>"
                                . "</tr>";
                    } else {
                        $nota_final = ceiling($nota, 0.1);
                    }
                    $calculo .= "</table>";


                    if (!empty($turmas['eja'])) {
                        $calculo .= "<br />"
                                . "<table style=\"padding: 8px; width: 100%\" border=1 cellspacing=0 cellpadding=1>"
                                . "<tr>"
                                . "<td>"
                                . "EJA (Nota da Escola)"
                                . "</td>"
                                . "<td>"
                                . ceiling($v['nota_esc'], 0.1)
                                . "</td>"
                                . "</tr>"
                                . "<tr>"
                                . "<td>"
                                . "Ciclo Básico/Alfabetização"
                                . "</td>"
                                . "<td>"
                                . round($nota_final, 1)
                                . "</td>"
                                . "</tr>"
                                . "<tr>"
                                . "<td>"
                                . "Média Final"
                                . "</td>"
                                . "<td>"
                                . ceiling(($v['nota_esc'] + $nota_final) / 2, 0.1)
                                . "</td>"
                                . "</tr>";

                        $calculo .= "</table>";
                    }
                    $perc = porcetagem($nota_final);
                    $sql = "UPDATE prod_main SET "
                            . " `metodo` = 5, "
                            . " `nota_final` = '$nota_final', "
                            . " `perc` = '$perc', "
                            . " `calculo` = '$calculo' "
                            . " WHERE `rm` = " . $v['rm'];
                    $query = pdoSis::getInstance()->query($sql);
                } elseif (empty($turmas['fund1']) && !empty($turmas['fund2'])) {
                    $nota = 0;
                    $conta = 0;
                    $calculo = "Seu bônus foi calculado usando a  Média da(s) sala(s) que está alocado como titular."
                            . "<br /><br />"
                            . "<table style=\"padding: 8px; width: 100%\" border=1 cellspacing=0 cellpadding=1>"
                            . "<tr>"
                            . "<td>"
                            . "Classe"
                            . "</td>"
                            . "<td>"
                            . "Nota"
                            . "</td>"
                            . "</tr>";
                    foreach ($turmas['fund2'] as $c) {
                        $calculo .= ""
                                . "<tr>"
                                . "<td>"
                                . $c['n_turma'] . ' (' . $c['n_inst'] . ')'
                                . "</td>"
                                . "<td>"
                                . $c['nota']
                                . "</td>"
                                . "</tr>";
                        $nota += $c['nota'];
                        $conta++;
                    }
                    if ($conta > 1) {
                        $nota_final = ($nota / $conta);
                        $calculo .= ""
                                . "<tr>"
                                . "<td>"
                                . "Média"
                                . "</td>"
                                . "<td style=\"padding: 8px; width: 10%\">"
                                . ceiling($nota_final, 0.1)
                                . "</td>"
                                . "</tr>";
                    } else {
                        $nota_final = ceiling($nota, 0.1);
                    }
                    $calculo .= "</table>";

                    if (!empty($turmas['eja'])) {
                        $calculo .= "<br />"
                                . "<table style=\"padding: 8px; width: 100%\" border=1 cellspacing=0 cellpadding=1>"
                                . "<tr>"
                                . "<td>"
                                . "EJA (Nota da Escola)"
                                . "</td>"
                                . "<td>"
                                . ceiling($v['nota_esc'], 0.1)
                                . "</td>"
                                . "</tr>"
                                . "<tr>"
                                . "<td>"
                                . "4ºs a 9ºs anos"
                                . "</td>"
                                . "<td>"
                                . round($nota_final, 1)
                                . "</td>"
                                . "</tr>"
                                . "<tr>"
                                . "<td>"
                                . "Média Final"
                                . "</td>"
                                . "<td>"
                                . ceiling(($v['nota_esc'] + $nota_final) / 2, 0.1)
                                . "</td>"
                                . "</tr>";

                        $calculo .= "</table>";
                    }

                    $perc = porcetagem($nota_final);
                    $sql = "UPDATE prod_main SET "
                            . " `metodo` = 5, "
                            . " `nota_final` = '$nota_final', "
                            . " `perc` = '$perc', "
                            . " `calculo` = '$calculo' "
                            . " WHERE `rm` = " . $v['rm'];
                    $query = pdoSis::getInstance()->query($sql);
                } elseif (!empty($turmas['fund1']) && !empty($turmas['fund2'])) {
                    $calculo = "Seu bônus foi calculado usando a Média da somatória da Média da(s) Avaliação(ões) das salas de Ciclo Básico/Alfabetização e a Média da(s) Sala(s) que leciona como titular (4ºs a 9ºs anos).";
                    $titulo = [1 => "4ºs a 9ºs anos", 2 => "Ciclo Básico/Alfabetização"];
                    for ($y = 1; $y <= 2; $y++) {
                        $calculo .= "<br />"
                                . "<table style=\"padding: 8px; width: 100%\" border=1 cellspacing=0 cellpadding=1>"
                                . "";
                        $calculo .= "<tr>"
                                . "<td colspan=\"2\" style=\"text-align: center\">"
                                . $titulo[$y]
                                . "</td>"
                                . "</tr>"
                                . "";
                        $nota = array();
                        $nota = array();
                        $conta = array();
                        $conta = array();
                        foreach ($turmas['fund' . $y] as $c) {
                            $calculo .= ""
                                    . "<tr>"
                                    . "<td>"
                                    . $c['n_turma'] . ' (' . $c['n_inst'] . ')'
                                    . "</td>"
                                    . "<td style=\"padding: 8px; width: 10%\">"
                                    . $c['nota']
                                    . "</td>"
                                    . "</tr>";
                            @$nota[$y] += $c['nota'];
                            @$conta[$y] ++;
                        }
                        if (!empty($conta[$y])) {
                            if ($conta[$y] > 1) {
                                $nota_parcial[$y] = ($nota[$y] / $conta[$y]);
                                $calculo .= ""
                                        . "<tr>"
                                        . "<td>"
                                        . "Média"
                                        . "</td>"
                                        . "<td>"
                                        . round($nota_parcial[$y], 1)
                                        . "</td>"
                                        . "</tr>";
                            } else {
                                $nota_parcial[$y] = $nota[$y];
                            }
                        }
                        $calculo .= "</table>";
                    }
                    $calculo .= ""
                            . "<br /><br />"
                            . "<table style=\"padding: 8px; width: 100%\" border=1 cellspacing=0 cellpadding=1>"
                            . "<tr>"
                            . "<td>"
                            . "Ciclo Básico/Alfabetização"
                            . "</td>"
                            . "<td>"
                            . round($nota_parcial[1], 1)
                            . "</td>"
                            . "</tr>"
                            . "<tr>"
                            . "<td>"
                            . "4ºs a 9ºs anos"
                            . "</td>"
                            . "<td>"
                            . round($nota_parcial[2], 1)
                            . "</td>"
                            . "</tr>"
                            . "<tr>"
                            . "<td>"
                            . "Média Final"
                            . "</td>"
                            . "<td>"
                            . ceiling((($nota_parcial[1] + $nota_parcial[2]) / 2), 0.1)
                            . "</td>"
                            . "</tr>"
                            . "</table>";
                } elseif (!empty($turmas['aee'])) {
                    $nota = 0;
                    $conta = 0;
                    $calculo = "Seu bônus foi calculado usando a  Média da(s) sala(s) que está alocado como titular."
                            . "<br /><br />"
                            . "<table style=\"padding: 8px; width: 100%\" border=1 cellspacing=0 cellpadding=1>"
                            . "<tr>"
                            . "<td>"
                            . "Classe"
                            . "</td>"
                            . "<td>"
                            . "Nota"
                            . "</td>"
                            . "</tr>";
                    foreach ($turmas['aee'] as $c) {
                        $calculo .= ""
                                . "<tr>"
                                . "<td>"
                                . $c['n_turma'] . ' (' . $c['n_inst'] . ')'
                                . "</td>"
                                . "<td>"
                                . $c['nota']
                                . "</td>"
                                . "</tr>";
                        $nota += $c['nota'];
                        $conta++;
                    }
                    if ($conta > 1) {
                        $nota_final = ($nota / $conta);
                        $calculo .= ""
                                . "<tr>"
                                . "<td>"
                                . "Média"
                                . "</td>"
                                . "<td style=\"padding: 8px; width: 10%\">"
                                . ceiling($nota_final, 0.1)
                                . "</td>"
                                . "</tr>";
                    } else {
                        $nota_final = ceiling($nota, 0.1);
                    }
                    $calculo .= "</table>";
                    $perc = porcetagem($nota_final);
                    $sql = "UPDATE prod_main SET "
                            . " `metodo` = 5, "
                            . " `nota_final` = '$nota_final', "
                            . " `perc` = '$perc', "
                            . " `calculo` = '$calculo' "
                            . " WHERE `rm` = " . $v['rm'];
                    $query = pdoSis::getInstance()->query($sql);
                } elseif (!empty($turmas['infa'])) {
                    $nota = 0;
                    $conta = 0;
                    $calculo = "Seu bônus foi calculado usando a  Média da(s) sala(s) que está alocado como titular."
                            . "<br /><br />"
                            . "<table style=\"padding: 8px; width: 100%\" border=1 cellspacing=0 cellpadding=1>"
                            . "<tr>"
                            . "<td>"
                            . "Classe"
                            . "</td>"
                            . "<td>"
                            . "Nota"
                            . "</td>"
                            . "</tr>";
                    foreach ($turmas['infa'] as $c) {
                        $calculo .= ""
                                . "<tr>"
                                . "<td>"
                                . $c['n_turma'] . ' (' . $c['n_inst'] . ')'
                                . "</td>"
                                . "<td style=\"padding: 8px; width: 10%\">"
                                . $c['nota']
                                . "</td>"
                                . "</tr>";
                        $nota += $c['nota'];
                        $conta++;
                    }
                    if ($conta > 1) {
                        $nota_final = ($nota / $conta);
                        $calculo .= ""
                                . "<tr>"
                                . "<td>"
                                . "Média"
                                . "</td>"
                                . "<td style=\"padding: 8px; width: 10%\">"
                                . ceiling($nota_final, 0.1)
                                . "</td>"
                                . "</tr>";
                    } else {
                        $nota_final = ceiling($nota, 0.1);
                    }
                    $calculo .= "</table>";
                    $perc = porcetagem($nota_final);
                    $sql = "UPDATE prod_main SET "
                            . " `metodo` = 5, "
                            . " `nota_final` = '$nota_final', "
                            . " `perc` = '$perc', "
                            . " `calculo` = '$calculo' "
                            . " WHERE `rm` = " . $v['rm'];
                    $query = pdoSis::getInstance()->query($sql);
                } elseif (!empty($turmas['eja'])) {
                    $nota_final = ceiling($v['nota_esc'], 0.1);
                    $perc = porcetagem($nota_final);
                    $calculo = "Seu bônus foi calculado usando a Nota da Unidade Escolar";

                    $sql = "UPDATE prod_main SET "
                            . " `metodo` = 1, "
                            . " `nota_final` = '$nota_final', "
                            . " `perc` = '$perc', "
                            . " `calculo` = '$calculo' "
                            . " WHERE `rm` = " . $v['rm'];
                    $query = pdoSis::getInstance()->query($sql); 
                } else {
                    ?>
                    <pre>
                        <?php
                        print_r($v)
                        ?>
                    </pre>
                    <?php
                }
            }
        }
    }
    /**
     * erro 1: turma setado indevidamente
     * erro 2: falta nota assistente ou escola
     * 
     */
    ?>
</div>
