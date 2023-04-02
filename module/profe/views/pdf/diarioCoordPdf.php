<?php
if (!defined('ABSPATH'))
    exit;

ob_start();

$pdf = new pdf ();
$pdf->orientation = 'R';
$pdf->headerAlt = 'Coordenadoria de Ensino Fundamental - Diário de Classe - ' . tool::n_inst();
$pdf->mgt = 14;
$pdf->mgh = 8;
$pdf->mgr = 5;
$pdf->mgl = 10;
$pdf->mgf = 2;

/*
  public $mgl = 15;
  public $mgr = 15;
  public $mgt = 38;
  public $mgb = 20;
  public $mgh = 9;
  public $mgf = 9;

 */

$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_NUMBER_INT);
$n_disc = filter_input(INPUT_POST, 'n_disc', FILTER_UNSAFE_RAW);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_UNSAFE_RAW);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$escola = filter_input(INPUT_POST, 'escola', FILTER_UNSAFE_RAW);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$prof_no = filter_input(INPUT_POST, 'prof', FILTER_UNSAFE_RAW);

if ($id_turma) {
    if (empty($id_disc)) {
        $fields = "fk_id_pessoa, atual_letiva, falta_nc";
        $disc = turmas::disciplinasAbrev($id_turma)['nucleComum'];
        foreach ($disc as $k => $v) {
            $fields .= ", media_$k";
        }
    } else {
        $disc = [$id_disc => $n_disc];
        $fields = "fk_id_pessoa, atual_letiva, media_$id_disc media, falta_$id_disc falta";
    }
    $sql = "SELECT $fields FROM `aval_mf_" . $id_curso . "_" . $id_pl . "` "
            . " WHERE `fk_id_turma` = $id_turma";
    try {
        $query = pdoHab::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $notas[$v['fk_id_pessoa']][$v['atual_letiva']] = $v;
        }
    } catch (Exception $exc) {
        $notas = null;
    }


    $alunos = ng_escola::alunoPorTurma($id_turma);
    $curso = sql::get('ge_cursos', 'n_curso', ['id_curso' => $id_curso], 'fetch')['n_curso'];
    $turma = sql::get('ge_turmas', 'periodo, letra, periodo_letivo, n_turma', ['id_turma' => $id_turma], 'fetch');
    $result = aulas($id_turma, $id_curso, $id_pl, $id_disc);
    $atividade = atividades($id_turma, $id_curso, $id_pl, $id_disc);
    $periodo = ['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite', 'G' => 'Geral', 'I' => 'Integral'];
    ?>
    <head>
        <style>
            .topo{
                font-size: 8pt;
                font-weight:bold;
                text-align: center;
                border-style: solid;
                border-width: 0.5px;
                padding-left: 5px;
                padding-right: 5px;
                padding-top: 2px;
                padding-bottom: 2px;
            }
            .topo1{
                font-size: 12pt;
                font-weight:bold;
                text-align: center;
                border-style: solid;
                border-width: 0.5px;
                padding-left: 5px;
                padding-right: 5px;
                padding-top: 2px;
                padding-bottom: 2px;
            }
            .topo2{
                font-size: 24px;
                text-align: center;
                font-weight: bold;
                padding: 5px; 
                color: #0e2ab4;
            }
            .topo3{
                font-size: 20px;
                text-align: center;
                font-weight: bold;
                padding: 3px;
                background-color: #0e2ab4;
                color: white;
            }
            .topo4{
                font-size: 20px;
                border-style: solid;
                border-width: 1px; 
                text-align: center;
                font-weight: bold; 
                padding: 3px; 
                color: black;
            }
            .cab{
                font-size: 14px;
                font-weight:bold; 
                font-size:10pt;
                background-color: #000000;
                color:#ffffff; 
                text-align: center;
            }
        </style>
    </head>
    <div class="body" style="border-style: solid; border-width: 2px; width: 100%">
        <div style="width: 100%; padding: 5px; text-align: center" >
            <img style="width: 100px" src="<?php echo HOME_URI ?>/views/_images/brasao.jpg"/>
        </div>
        <br /><br /><br />
        <!-- Capa -->
        <div class="topo2">
            <?= CLI_NOME ?>
            <br />
            COORDENADORIA DE ENSINO FUNDAMENTAL
        </div>
        <br /><br /><br />
        <div class="topo3">
            IDENTIFICAÇÃO DA ESCOLA
        </div>
        <div class="topo4">
            <?= $escola ?>
        </div>
        <br /><br /><br /><br />
        <div class="topo2">
            Diário de Classe
        </div>
        <br /><br /><br /><br />
        <div class="topo3">
            CURSO
        </div>
        <div class="topo4">
            <?= $curso ?>
        </div>
        <br /><br /><br /><br />
        <div class="topo3">
            COMPONENTE CURRICULAR
        </div>
        <div class="topo4">
            <?= $n_disc ?>
        </div>
        <br /><br /><br /><br />
        <div class="topo3">
            CLASSE
        </div>
        <div> 
            <table style="width: 100%">
                <tr>
                    <td class="topo">
                        Ano Letivo
                    </td>
                    <td class="topo">
                        Turno
                    </td>
                    <td class="topo">
                        Turma
                    </td>
                </tr>
                <tr>
                    <td class="topo">
                        <?= $turma['periodo_letivo'] ?>
                    </td>
                    <td class="topo">
                        <?= $periodo[$turma['periodo']] ?>
                    </td>
                    <td class="topo">
                        <?= $turma['n_turma'] ?>
                    </td>
                </tr>
            </table>
        </div>

        <div style="page-break-after: always"></div>
        <!-- Lista de Alunos -->
        <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633">
            <tr>
                <td colspan="5" class="cab">
                    Lista dos Alunos
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold; font-size: 12px">
                    Nº
                </td>
                <td style="font-weight: bold; font-size: 12px">
                    RSE  
                </td>
                <td style="font-weight: bold; font-size: 12px">
                    Nome
                </td>
                <td style="font-weight: bold; font-size: 12px">
                    Situação
                </td>
            </tr>
            <?php
            foreach ($alunos as $v) {
                ?>
                <tr>
                    <td style="font-size: 10px">
                        <?= $v['chamada'] ?>
                    </td>
                    <td style="font-size: 10px">
                        <?= $v['id_pessoa'] ?>  
                    </td>
                    <td style="font-size: 10px">
                        <?= $v['n_pessoa'] ?>
                    </td>
                    <td style="font-size: 10px">
                        <?= $v['situacao'] ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <br /><br />
        <div style="page-break-after: always"></div>

        <!-- ######################################################################### -->

        <div colspan="5" class="cab">
            Resumo do Conteúdo Programático e das Atividades Desenvolvidas
        </div>
        <?php
        if (!empty($result['aulas_dadas'])) {
            foreach ($result['aulas_dadas'] as $bimestre => $dados) {
                ?>
                <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633">
                    <tr>
                        <td style="font-weight: bold; text-align: center; font-size: 12px; color: red" colspan="2">
                            <?= $bimestre ?>      
                        </td>
                    </tr>
                    <?php
                    foreach ($dados as $v) {
                        ?>
                        <tr>
                            <td rowspan="6" style="width: 160px; font-weight: bold; text-align: center; font-size: 12px">
                                <?= data::converteBr($v['data_registro']) ?>
                                <br /><br />
                                <?= $v['dia_semana'] ?>
                            </td>
                            <td style="font-weight: bold; font-size: 10px">
                                Resgistro de aula
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:justify; font-size:10px">
                                <?= quebra(htmlspecialchars(str_replace(['<', '>'], ['', ''], $v['registro_aula']))) ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; font-size: 10px">
                                Observações
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:justify; font-size:10px">
                                <?= quebra($v['observacoes']) ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; font-size: 10px">
                                Adaptação Curricular
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:justify; font-size:10px">
                                <?= quebra($v['apd']) ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <br /><br />
                <?php
            }
        }
        ?>
        <br /><br /><br />
        <div style="float: left; width: 50%; text-align: center; font-size:10px">
            __________________________________________________ 
            <br /><br />
            <?php
            //echo $prof_no;
            // echo '<br />';
            echo 'Assinatura';
            ?>
        </div>
        <div style="float: left; width: 50%; text-align: center; font-size:10px">
            __________________________________________________ 
            <br /><br />
            Carimbo e Assinatura do Gestor
        </div>
        <div style="page-break-after: always"></div>
        <?php
        if (!empty($atividade)) {
            ?>
            <div colspan="5" class="cab">
                Atividades desenvolvidas
            </div>
            <?php
            foreach ($atividade as $bimeste => $dados) {
                ?>
                <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633">
                    <tr>
                        <td colspan="3" style="font-weight: bold; text-align: center; font-size: 12px; color: red">
                            <?= $bimeste ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; font-size: 12px">
                            Data
                        </td>
                        <td style="font-weight: bold; font-size: 12px">
                            Tipo de Atividade
                        </td>
                        <td style="font-weight: bold; font-size: 12px">
                            Registro
                        </td>
                    </tr>
                    <?php
                    foreach ($dados as $v) {
                        ?>
                        <tr>
                            <td style="font-size: 10px">
                                <?= data::converteBr($v['data_registro']) ?>
                            </td>
                            <td style="font-size: 10px">
                                <?= $v['tipo'] ?>
                            </td>
                            <td style="font-size: 10px">
                                <?= $v['nome'] ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <?php
            }
            ?>
            <br /><br /><br />
            <div style="float: left; width: 50%; text-align: center; font-size:10px">
                __________________________________________________ 
                <br /><br />
                <?php
                //echo $prof_no;
                //echo '<br />';
                echo 'Assinatura';
                ?>
            </div>
            <div style="float: left; width: 50%; text-align: center; font-size:10px">
                __________________________________________________ 
                <br /><br />
                Carimbo e Assinatura do Gestor
            </div>
            <div style="page-break-after: always"></div>
            <?php
        }

        if (!empty($notas)) {
            ?>
            <div class="cab">
                Avaliação
            </div>

            <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633">
                <tr>
                    <td rowspan="2" class="topo">
                        Nº
                    </td>
                    <td rowspan="2" class="topo">
                        RSE  
                    </td>
                    <?php
                    if (count($disc) == 1) {
                        foreach (range(1, 4) as $v) {
                            ?>
                            <td colspan="2" class="topo">
                                <?= $v ?>º Bimestre
                            </td>
                            <?php
                        }
                    } else {
                        foreach (range(1, 4) as $v) {
                            ?>
                            <td colspan="<?= count($disc) + 1 ?>" class="topo">
                                <?= $v ?>º Bimestre
                            </td>
                            <?php
                        }
                    }
                    ?>
                </tr>
                <tr>
                    <?php
                    if (count($disc) == 1) {
                        foreach (range(1, 4) as $v) {
                            ?>
                            <td class="topo">
                                Nota
                            </td>
                            <td class="topo">
                                Falta
                            </td>
                            <?php
                        }
                    } else {
                        foreach (range(1, 4) as $v) {
                            foreach ($disc as $d) {
                                ?>
                                <td class="topo">
                                    <?= $d ?>
                                </td>
                                <?php
                            }
                            ?>
                            <td class="topo">
                                Falta
                            </td>
                            <?php
                        }
                    }
                    ?>
                </tr>
                <?php
                foreach ($alunos as $v) {
                    ?>
                    <tr>
                        <td class="topo">
                            <?= $v['chamada'] ?>
                        </td>
                        <td class="topo">
                            <?= $v['id_pessoa'] ?>  
                        </td>
                        <?php
                        if (count($disc) == 1) {
                            foreach (range(1, 4) as $b) {
                                $media = @$notas[$v['id_pessoa']][$b]['media'];
                                $falta = @$notas[$v['id_pessoa']][$b]['falta'];
                                $cor = ($media < 5) ? $cor = 'Red' : $cor = 'Blue';
                                $cor = (empty($media)) ? $cor = 'Black' : $cor = $cor;
                                ?>
                                <td class="topo" style="color: <?= $cor ?>">
                                    <?= empty($media) ? 'NL' : $media ?>
                                </td>
                                <td class="topo">
                                    <?= empty($falta) ? 'NL' : $falta ?>
                                </td>
                                <?php
                            }
                        } else {
                            foreach (range(1, 4) as $b) {
                                $falta = @$notas[$v['id_pessoa']][$b]['falta_nc'];
                                foreach ($disc as $kd => $d) {
                                    $media = @$notas[$v['id_pessoa']][$b]['media_' . $kd];
                                    $cor = ($media < 5) ? $cor = 'Red' : $cor = 'Blue';
                                    $cor = (empty($media)) ? $cor = 'Black' : $cor = $cor;
                                    ?>
                                    <td class="topo" style="color: <?= $cor ?>">
                                        <?= empty($media) ? 'NL' : $media ?>
                                    </td>
                                    <?php
                                }
                                ?>
                                <td class="topo">
                                    <?= empty($falta) ? '-' : $falta ?>
                                </td>
                                <?php
                            }
                        }
                        ?>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <br /><br /><br />
            <div style="float: left; width: 50%; text-align: center; font-size:10px">
                __________________________________________________ 
                <br /><br />
                <?php
                //echo $prof_no;
                //echo '<br />';
                echo 'Assinatura';
                ?>
            </div>
            <div style="float: left; width: 50%; text-align: center; font-size:10px">
                __________________________________________________ 
                <br /><br />
                Carimbo e Assinatura do Gestor
            </div>
        </div>
        <?php
    }
}

function letivas($id_curso, $id_pl) {
    $sql = "SELECT atual_letiva, dt_fim, dt_inicio FROM `sed_letiva_data` "
            . " WHERE `fk_id_curso` = $id_curso "
            . " AND `fk_id_pl` = $id_pl";
    $query = pdoSis::getInstance()->query($sql);
    $letivas = $query->fetchAll(PDO::FETCH_ASSOC);

    return $letivas;
}

function aulas($id_turma, $id_curso, $id_pl, $id_disc = null) {
    $letivas = letivas($id_curso, $id_pl);

    $semana = [1 => 'Segunda', 2 => 'Terça', 3 => 'Quarta', 4 => 'Quinta', 5 => 'Sexta', 6 => 'Sábado', 0 => 'Domingo'];
    if ($id_disc && $id_disc != 27) {
        $id_disc = " AND disciplina_id = $id_disc ";
    } else {
        $id_disc = " AND disciplina_id is null ";
    }
    $profOld = null;

    foreach ($letivas as $l) {
        $sql = "SELECT "
                . " id, professor_id, disciplina_id, data_registro, registro_aula, observacoes, apd "
                . " FROM diario_classe.frequencias f "
                . " WHERE f.classe_id = $id_turma "
                . $id_disc
                . " and (data_registro BETWEEN '" . $l['dt_inicio'] . "' AND '" . $l['dt_fim'] . "') "
                . " order by data_registro ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($array) {
            foreach ($array as $v) {
                if ($profOld != $v['professor_id']) {
                    $prof[$v['professor_id']]['inicio'] = $v['data_registro'];
                    if (!empty($profOld)) {
                        $prof[$profOld]['fim'] = $dataOld;
                    }
                }
                $profOld = $v['professor_id'];
                $dataOld = $v['data_registro'];
                $rm[$v['professor_id']] = $v['professor_id'];
                if (empty($aula['aulas_dadas'][$l['atual_letiva'] . ' Bimestre'][$v['data_registro']])) {
                    $v['aulas'] = 1;
                } else {
                    $v['aulas'] = $aula['aulas_dadas'][$l['atual_letiva'] . ' Bimestre'][$v['data_registro']]['aulas']++;
                }
                $v['dia_semana'] = $semana[date('w', strtotime($v['data_registro']))];
                $aula['aulas_dadas'][$l['atual_letiva'] . 'º Bimestre'][$v['data_registro'] . '_' . $v['professor_id']] = $v;
            }
            $prof[$v['professor_id']]['fim'] = $v['data_registro'];
        }
    }
    if (!empty($rm)) {
        foreach ($rm as $v) {
            $aula['professor'][$v] = $prof[$v];
            $sql = "select n_pessoa from pessoa where id_pessoa = $v";
            try {
                $query = pdoSis::getInstance()->query($sql);
                @$aula['professor'][$v]['nome'] = $query->fetch(PDO::FETCH_ASSOC)['n_pessoa'];
            } catch (Exception $exc) {
                
            }
        }
    }
    if (!empty($aula)) {
        return $aula;
    }
}

function atividades($id_tuma, $id_curso, $id_pl, $id_disc) {
    $sql = "SELECT atual_letiva, dt_fim, dt_inicio FROM `sed_letiva_data` "
            . " WHERE `fk_id_curso` = $id_curso "
            . " AND `fk_id_pl` = $id_pl";
    $query = pdoSis::getInstance()->query($sql);
    $letivas = $query->fetchAll(PDO::FETCH_ASSOC);
    if ($id_disc) {
        $id_disc = " and disciplina_id = $id_disc";
    } else {
        $id_disc = "and disciplina_id in (6,9,10,12,13,14)";
    }
    foreach ($letivas as $l) {
        $sql = "SELECT "
                . " a.nome, a.data_registro, a.bimestre, t.nome as tipo "
                . " FROM diario_classe.atividades a "
                . " JOIN diario_classe.tipo_atividades t on t.id = a.tipo "
                . " WHERE a.classe_id = $id_tuma "
                . $id_disc
                . " and (data_registro BETWEEN '" . $l['dt_inicio'] . "' AND '" . $l['dt_fim'] . "') "
                . " ORDER BY a.bimestre, data_registro ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $atv[$l['atual_letiva'] . 'º Bimestre'][$v['data_registro']] = $v;
        }
    }
    if (!empty($atv)) {
        return $atv;
    }
}

function quebra($texto = null) {
    if ($texto) {
        $tex = explode(' ', $texto);
        $textQuebrado = '';
        foreach ($tex as $v) {
            if (strlen($v) < 50) {
                $textQuebrado .= $v . ' ';
            } else {
                $textQuebrado .= wordwrap($v, 50, ' ', true);
            }
        }

        return $textQuebrado;
    }
}

if (in_array(tool::id_pessoa(), [1, 5])) {
    
} else {
    $pdf->exec();
}
    