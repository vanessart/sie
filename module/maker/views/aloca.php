<?php
if (!defined('ABSPATH'))
    exit;
$polos = $model->poloComEscolas();
$pls = implode(', ', ng_main::periodosAtivos());
if (!in_array(tool::id_pessoa(), [1, 5, 6])) {
    exit();
}

$poloSet = filter_input(INPUT_POST, 'poloSet', FILTER_SANITIZE_NUMBER_INT);
$temAluno = 0;
?>
<form method="POST">
    <div class="row">
        <div class="col">
            <?= formErp::selectNum('poloSet', [1, 40], 'Polo', $poloSet); ?>
        </div>
        <div class="col">
            <input class="btn btn-primary" type="submit" value="ir" />
        </div>
    </div>
    <br />
</form>
<?php
if ($poloSet) {
    foreach ($polos as $polo => $v) {
        if ($polo == $poloSet) {
            echo 'Polo ' . $polo . '<br>';
            $id_inst = $v['fk_id_inst_maker'];
            $sql = "SELECT * FROM `maker_gt_turma` WHERE `fk_id_inst` = $id_inst ORDER BY `letra` DESC ";
            $query = pdoSis::getInstance()->query($sql);
            $turmas = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($turmas as $t) {
                $sql = "select chamada from maker_gt_turma_aluno where fk_id_turma = " . $t['id_turma'] . ' order by chamada desc';
                $query = pdoSis::getInstance()->query($sql);
                $ch = $query->fetch(PDO::FETCH_ASSOC);
                if (empty($ch)) {
                    $chamada = 1;
                } else {
                    $chamada = $ch['chamada'] + 1;
                }

                $sql = "SELECT * FROM `maker_aluno` "
                        . "WHERE `fk_id_polo` = $poloSet  "
                        . " AND `fk_id_as` = 2 "
                        . " AND `fk_id_mc` = " . $t['fk_id_ciclo']
                        . " AND `opt_semana_1` = " . substr($t['codigo'], 2, 1)
                        . " AND `migrado_aluno` != 1 "
                        . " AND `ciclo_maker` like '" . substr($t['codigo'], 4, 1)."' "
                        . " AND periodo like '" . substr($t['codigo'], 1, 1) . "'";
                $query = pdoSis::getInstance()->query($sql);
                $array = $query->fetch(PDO::FETCH_ASSOC);
                if (empty($array)) {
                    $sql = "SELECT * FROM `maker_aluno` "
                            . "WHERE `fk_id_polo` = $poloSet  "
                            . " AND `fk_id_as` = 2 "
                            . " AND `fk_id_mc` = " . $t['fk_id_ciclo']
                            . " AND `opt_semana_2` = " . substr($t['codigo'], 2, 1)
                            . " AND `migrado_aluno` != 1 "
                            . " AND `ciclo_maker` like '" . substr($t['codigo'], 4, 1)."' "
                            . " AND periodo like '" . substr($t['codigo'], 1, 1) . "'";
                    $query = pdoSis::getInstance()->query($sql);
                    $array = $query->fetch(PDO::FETCH_ASSOC);
                } else {
                    echo "<br />Chamada: $chamada Primeira tentativa: " . $sql;
                }
                if (empty($array)) {
                    $sql = "SELECT * FROM `maker_aluno` "
                    . "WHERE `fk_id_polo` = $poloSet  "
                    . " AND `fk_id_as` = 2 "
                    . " AND `fk_id_mc` = " . $t['fk_id_ciclo']
                    . " AND `migrado_aluno` != 1 "
                    . " AND `ciclo_maker` like '" . substr($t['codigo'], 4, 1)."' "
                    . " AND periodo like '" . substr($t['codigo'], 1, 1) . "'";
                    $query = pdoSis::getInstance()->query($sql);
                    $array = $query->fetch(PDO::FETCH_ASSOC);
                } else {
                    echo "<br />Chamada: $chamada segunda tentativa: " . $sql;
                }
                 if (empty($array)) {
                    echo "<br />Chamada: $chamada Quarta tentativa: " . $sql = "SELECT * FROM `maker_aluno` "
                    . "WHERE `fk_id_polo` = $poloSet  "
                    . " AND `fk_id_as` = 2 "
                    . " AND `fk_id_mc` = " . $t['fk_id_ciclo']
                    . " AND `migrado_aluno` != 1 "
                    . " AND periodo like '" . substr($t['codigo'], 1, 1) . "'";
                    $query = pdoSis::getInstance()->query($sql);
                    $array = $query->fetch(PDO::FETCH_ASSOC);
                } else {
                    echo "<br />Chamada: $chamada terceira tentativa: " . $sql;
                }
                if (!empty($array)) {
                    echo ' -- Sucesso';
                    $temAluno++;
                    $aluno = [
                        'fk_id_turma' => $t['id_turma'],
                        'fk_id_pessoa' => $array['fk_id_pessoa'],
                        'fk_id_inst' => $id_inst,
                        'chamada' => $chamada,
                        'fk_id_sit' => 1,
                        'dt_matricula' => date("Y-m-d"),
                    ];
                    $model->db->ireplace('maker_gt_turma_aluno', $aluno, 1);
                    $set = [
                        'id_ma' => $array['id_ma'],
                        'migrado_aluno' => 1,
                        'fk_id_as' => 1
                    ];
                    $model->db->ireplace('maker_aluno', $set, 1);
                } else {
                    echo ' -- Não tem';
                }
            }
            if ($temAluno > 0) {
                echo '<br /><br />' . count($turmas) . ' Turmas';
                echo '<br /> Foram feitos ' . $temAluno . ' Lançamentos';
            } else {
                echo '<br /><br />Não há lançamento';
            }
        }
    }
}



exit();
############################ criar turmas  #######################################################
$periodo = 'T';
$conta = 1;
$turmasSet = sql::get('maker_turmas');
foreach ($turmasSet as $turmas) {
    $letra = 'L';
    $dia = 2;
    $aula = 1;
    echo '<br />' . $id_inst = $turmas['id_polo'];
    foreach ($turmas as $nome => $v) {
        if ($nome != 'id_polo' && substr($nome, 1, 1) == $periodo) {
            $nomeArr = explode('*', $nome);
            if ($v) {
                foreach (range(1, $v) as $c) {
                    $nome = $nomeArr[0] . $dia . $aula . $nomeArr[1];
                    if ($aula == 1) {
                        $horario = '13:30';
                    } elseif ($aula == 2) {
                        $horario = '15:40';
                    } else {
                        $horario = null;
                    }
                    $turmasBd = [
                        'codigo' => $nome . $letra,
                        'n_turma' => $nome . $letra,
                        'fk_id_inst' => $id_inst,
                        'fk_id_ciclo' => substr($nome, 0, 1),
                        'fk_id_grade' => 1,
                        'periodo' => $periodo,
                        'fk_id_pl' => 1,
                        'letra' => $letra,
                        'status' => 1,
                        'horario_1' => $horario,
                        'horario_duracao' => 120
                    ];
                    echo '<br />' . $conta++;
                    $model->db->ireplace('maker_gt_turma', $turmasBd, 1)
                    ##################            
                    ?>
                    <pre>   
                        <?php
                        print_r($turmasBd);
                        ?>
                    </pre>
                    <?php
###################

                    $letra++;
                    $dia++;
                    if ($dia == 7 && $aula == 1) {
                        $dia = 2;
                        $aula = 2;
                    } elseif ($dia == 7 && $aula == 2) {
                        break;
                    }
                }
            }
        }
    }
}



exit();
#######################################calcula quantidade de turmas ##################
$poloSet = 20;
$alunoSala = 16;


foreach ($polos as $polo => $v) {
    if ($polo == $poloSet) {
        foreach ($v['escolas'] as $e) {
            $sql = "SELECT "
                    . " p.n_pessoa, p.id_pessoa, p.dt_nasc, p.sexo, p.ra, p.ra_uf, p.emailgoogle, "
                    . " t.fk_id_ciclo, i.n_inst, "
                    . " m.* "
                    . " FROM ge2.maker_aluno m "
                    . " join pessoa p on p.id_pessoa = m.fk_id_pessoa "
                    . " join ge_turma_aluno ta on ta.fk_id_pessoa = m.fk_id_pessoa and ta.fk_id_tas = 0 "
                    . " join ge_turmas t on t.id_turma = ta.fk_id_turma and t.fk_id_pl in ($pls) "
                    . " join instancia i on i.id_inst = m.fk_id_inst "
                    . " where m.fk_id_inst = " . $e['id_inst']
                    //          . " and (fk_id_as = 3)";
                    . " and (fk_id_as = 2 or (fk_id_as = 3 and fk_id_mc = 2))";
            $query = pdoSis::getInstance()->query($sql);
            $alu = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($alu as $a) {
                if (in_array($a['fk_id_ciclo'], [4, 5, 6])) {
                    $tan = 'P';
                } else {
                    $tan = 'G';
                }
                @$turmasPorPolo[$e['fk_id_inst_maker']][$a['fk_id_mc'] . $a['periodo'] . '*' . $tan]++;
                @$turmasPorPoloDia[$e['fk_id_inst_maker']][$a['fk_id_mc'] . $a['periodo'] . $a['opt_semana_1'] . $tan]++;
                @$total++;
                @$totalPolo[$e['fk_id_inst_maker']]++;
                @$totalEsc[$a['n_inst']][$a['periodo']]++;
                @$totalPer[$a['periodo']]++;
            }
        }
    }
}
//criar turmas
$maior = 0;
foreach (@$turmasPorPolo as $kp => $p) {
    foreach (['M', 'T'] as $w) {

        foreach ($p as $k => $v) {
            if (substr($k, 1, 1) == $w) {
                if ($v) {
                    $tur = round(($v / $alunoSala));

                    if ($tur > 0) {
                        $AlunoPorTurmas = $v / $tur;
                    } else {
                        $AlunoPorTurmas = 0;
                    }
                    if ($v > 0 && $tur == 0) {
                        $tur = 1;
                        $AlunoPorTurmas = $v;
                    }
                    $turmasDiv[$kp][$k] = [
                        'Alunos' => $v,
                        'turmas' => $tur,
                        'AlunoPorTurmas' => $AlunoPorTurmas
                    ];
                    @$totalTurmas[$kp][substr($k, 1, 1)] += $tur;
                }
            }
        }
    }
    foreach (@$totalTurmas[$kp] as $kt => $vt) {
        echo '<br />' . $kt . ': ' . $vt;
    }
}

//criar tabela turma
foreach ($turmasDiv as $k => $v) {
    $letra = 'A';
    foreach (['M', 'T'] as $w) {
        $dia = 2;
        $aula = 1;
        foreach ($v as $n_t => $y) {
            if (substr($n_t, 1, 1) == $w) {
                if ($aula == 1) {
                    $horario = '13:30';
                } elseif ($aula == 2) {
                    $horario = '15:40';
                } else {
                    $horario = null;
                }
                $tarr = explode('*', $n_t);
                $nome = $tarr[0] . $dia . $aula . $tarr[1];
                foreach (range(1, $y['turmas'])as $t) {
                    $turmasSet[$k][$letra] = [
                        'codigo' => $nome . $letra,
                        'n_turma' => $nome . $letra,
                        'fk_id_inst' => $k,
                        'fk_id_ciclo' => substr($n_t, 0, 1),
                        'fk_id_grade' => 1,
                        'periodo' => $w,
                        'fk_id_pl' => 1,
                        'letra' => $letra,
                        'status' => 1,
                        'horario_1' => $horario,
                        'horario_duracao' => 120
                    ];

                    $letra++;
                    $dia++;
                    if ($dia == 6 && $aula == 1) {
                        $dia = 2;
                        $aula = 2;
                    } elseif ($dia == 6 && $aula == 2) {
                        break;
                    }
                }
            }
        }
    }
}
##################            
?>
<pre>   
    <?php
    print_r($turmasDiv);
    ?>
</pre>
<?php
###################
?>
<div class="body">
    aloca
</div>
