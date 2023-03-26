<?php
if (!defined('ABSPATH'))
    exit;
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_NUMBER_INT);
$n_disc = filter_input(INPUT_POST, 'n_disc', FILTER_SANITIZE_STRING);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$escola = filter_input(INPUT_POST, 'escola', FILTER_SANITIZE_STRING);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$bimestre = filter_input(INPUT_POST, 'bimestre', FILTER_SANITIZE_NUMBER_INT);

$bim = letivas($id_curso, $id_pl);
foreach ($bim as $v) {
    $bimestreSel[$v['atual_letiva']] = $v['atual_letiva'] . 'º';
}
if (empty($bimestre)) {
    $sql = "SELECT `atual_letiva` FROM `ge_cursos` WHERE `id_curso` = $id_curso ";
    $query = pdoSis::getInstance()->query($sql);
    $bimestre = $query->fetch(PDO::FETCH_ASSOC)['atual_letiva'];
}
if ($model->db->tokenCheck('salvarGamb')) {
    $ins = @$_POST[1];
    $sql = "SELECT * FROM `sed_letiva_data` "
            . " WHERE `fk_id_curso` = $id_curso "
            . " AND `fk_id_pl` = $id_pl "
            . " AND `atual_letiva` = $bimestre ";
    $query = pdoSis::getInstance()->query($sql);
    $le = $query->fetch(PDO::FETCH_ASSOC);
    if (($ins['data_registro'] < $le['dt_inicio']) || ($ins['data_registro'] > $le['dt_fim'])) {
        toolErp::alert("Data Fora do Bimestre");
    } else {

        if (empty($ins['id'])) {
            if (in_array($ins['disciplina_id'], [0, '0', 27, 'nc'])) {
                unset($ins['disciplina_id']);
            }
            $model->db->insert('diario_classe`.`frequencias', $ins, 1);
        } else {
            if (in_array($ins['disciplina_id'], [0, '0', 27, 'nc'])) {
                $disciplina_id = 'null';
            } else {
                $disciplina_id = $ins['disciplina_id'];
            }
            $sql = "UPDATE diario_classe.`frequencias` SET "
                    . "`classe_id`='" . $ins['classe_id'] . "', "
                    . "`professor_id`='" . $ins['professor_id'] . "',"
                    . "`periodo_aula`='" . $ins['periodo_aula'] . "',"
                    . "`disciplina_id`=" . $disciplina_id . ","
                    . "`data_registro`='" . $ins['data_registro'] . "',"
                    . "`registro_aula`='" . $ins['registro_aula'] . "',"
                    . "`observacoes`='" . $ins['observacoes'] . "',"
                    . "`apd`='" . $ins['apd'] . "'"
                    . "  WHERE id = '" . $ins['id'] . "' ";
            $query = pdoSis::getInstance()->query($sql);
        }
    }
} elseif ($model->db->tokenCheck('delGamb')) {
    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $sql = "DELETE FROM diario_classe.`frequencias` WHERE `frequencias`.`id` = $id ";
    $query = pdoSis::getInstance()->query($sql);
    if ($query) {
        toolErp::alert('Excluído');
    } else {
        toolErp::alert('Algo deu errado');
    }
}

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
    $periodo = [
        'M' => 'Manhã',
        'T' => 'Tarde',
        'N' => 'Noite',
        'G' => 'Geral',
        'I' => 'Integral'
    ];
    $hidden = ['id_turma' => $id_turma,
        'id_disc' => $id_disc,
        'n_disc' => $n_disc,
        'n_turma' => $n_turma,
        'id_pl' => $id_pl,
        'escola' => $escola,
        'id_curso' => $id_curso];
    ?>
    <style>
        #turma td{
            text-align: center;  
        }
    </style>
    <div class="body">
        <div style="font-weight: bold; font-size: 2.4em; text-align: center; padding: 20px">
            Diário de Classe
        </div>
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td style="font-weight: bold; font-size: 1.4em">
                    Curso
                </td>
                <td>
                    <?= $curso ?>
                </td>
                <td style="width: 50px">
                    <form target="_blank" action="<?= HOME_URI ?>/profe/pdf/diarioCoordPdf.php" method="POST">
                        <?=
                        formErp::hidden([
                            'id_turma' => $id_turma,
                            'id_disc' => $id_disc,
                            'n_disc' => $n_disc,
                            'n_turma' => $n_turma,
                            'id_pl' => $id_pl,
                            'escola' => $escola,
                            'id_curso' => $id_curso,
                        ])
                        ?>
                        <input type="hidden" name="prof" value="<?php echo current($result['professor'])['nome'] ?>">
                        <button style="width: 100%" type="submit" class="btn btn-info">
                            Gerar PDF
                        </button>
                    </form>
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold; font-size: 1.4em">
                    Professor(a)
                </td>
                <td>
                    <?php
                    if (!empty($result['professor'])) {
                        if (count($result['professor']) == 1) {
                            echo current($result['professor'])['nome'];
                        } else {
                            foreach ($result['professor'] as $v) {

                                echo $v['nome'] . ' (de ' . data::converteBr($v['inicio']) . ' à ' . data::converteBr($v['fim']) . '<br><br>';
                            }
                        }
                    } else {
                        ?>
                        <div class="alert alert-danger">
                            Sem Cadastro
                        </div>
                        <?php
                    }
                    ?>
                </td>
                <td>
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold; font-size: 1.4em">
                    Componente Curricular
                </td>
                <td>
                    <?= $n_disc ?>
                </td>
                <td>
                    <form action="<?= HOME_URI ?>/profe/diariotmp" method="POST">
                        <input type="hidden" name="id_turma" value="<?= $id_turma ?>" />
                        <button style="width: 100%" class="btn btn-info">
                            Voltar
                        </button>
                    </form>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table id="turma" style="width: 100%">
                        <tr>
                            <td colspan="3" style="font-weight: bold; font-size: 1.8em">
                                Classe
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight: bold; font-size: 1.4em">
                                Ano Letivo
                            </td>
                            <td style="font-weight: bold; font-size: 1.4em">
                                Turno
                            </td>
                            <td style="font-weight: bold; font-size: 1.4em">
                                Turma
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?= $turma['periodo_letivo'] ?>
                            </td>
                            <td>
                                <?= $periodo[$turma['periodo']] ?>
                            </td>
                            <td>
                                <?= $turma['n_turma'] ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <br /><br />
        <div style="width: 300px; margin: auto">
            <?= formErp::select('bimestre', $bimestreSel, 'Bimestre', $bimestre, 1, $hidden) ?>
        </div>
        <br /><br />
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td style="font-weight: bold; text-align: center; font-size: 1.6em" colspan="5">
                    Alunos
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold; font-size: 1.4em">
                    Nº
                </td>
                <td style="font-weight: bold; font-size: 1.4em">
                    RSE  
                </td>
                <td style="font-weight: bold; font-size: 1.4em">
                    Nome
                </td>
                <td style="font-weight: bold; font-size: 1.4em">
                    Situação
                </td>
                <?php
                if (!empty($notas)) {
                    if (count($disc) == 1) {
                        ?>
                        <td>
                            Nota
                        </td>
                        <td>
                            Frequência
                        </td>
                        <?php
                    } else {
                        foreach ($disc as $v) {
                            ?>
                            <td>
                                <?= $v ?>
                            </td>
                            <?php
                        }
                        ?>
                        <td>
                            Frequência
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
                    <td>
                        <?= $v['chamada'] ?>
                    </td>
                    <td>
                        <?= $v['id_pessoa'] ?>  
                    </td>
                    <td>
                        <?= $v['n_pessoa'] ?>
                    </td>
                    <td>
                        <?= $v['situacao'] ?>
                    </td>
                    <?php
                    if (!empty($notas)) {
                        if (count($disc) == 1) {
                            $media = @$notas[$v['id_pessoa']][$bimestre]['media'];
                            $falta = @$notas[$v['id_pessoa']][$bimestre]['falta'];
                            ?>
                            <td>
                                <?= empty($media) ? 'NL' : $media ?>
                            </td>
                            <td>
                                <?= empty($falta) ? 'NL' : $falta ?>
                            </td>
                            <?php
                        } else {

                            $falta = @$notas[$v['id_pessoa']][$bimestre]['falta_nc'];
                            foreach ($disc as $kd => $d) {
                                $media = @$notas[$v['id_pessoa']][$bimestre]['media_' . $kd];
                                ?>
                                <td>
                                    <?= empty($media) ? 'NL' : $media ?>
                                </td>
                                <?php
                            }
                            ?>
                            <td>
                                <?= empty($falta) ? 'NL' : $falta ?>
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
        <br /><br />

        <div colspan="5" style="font-weight: bold; text-align: center; font-size: 2em">
            Resumo do Conteúdo Programático e das Atividades Desenvolvidas
        </div>
        <br /><br />
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td style="font-weight: bold; text-align: center; font-size: 1.6em" colspan="2">
                    <div class="row">
                        <div class="col-8 text-center">
                            <?= $bimestre ?>º Bimestre
                        </div>
                        <div class="col-4 text-right">
                            <button onclick="reg()" class="btn btn-primary">
                                Novo Registro
                            </button>
                        </div>
                    </div>

                </td>
            </tr>
            <?php
            if (!empty($result['aulas_dadas'])) {
                foreach ($result['aulas_dadas'] as $bim => $dados) {
                    if (substr($bim, 0, 1) == $bimestre) {
                        if (!empty($dados)) {
                            $achou = 1;
                            foreach ($dados as $v) {
                                ?>
                                <tr>
                                    <td rowspan="6" style="width: 160px; font-weight: bold; text-align: center; font-size: 1.4em">
                                        <?= data::converteBr($v['data_registro']) ?>
                                        <br /><br />
                                        <?= $v['dia_semana'] ?>
                                        <br /><br />
                                        <button onclick="reg(<?= $v['id'] ?>)" class="btn btn-primary">
                                            Editar
                                        </button>
                                    </td>
                                    <td style="font-weight: bold; font-size: 1.4em">
                                        Registro de aula
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?= $v['registro_aula'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; font-size: 1.4em">
                                        Observações
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?= $v['observacoes'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; font-size: 1.4em">
                                        Adaptação Curricular
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <?= $v['apd'] ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    }
                }
                ?>
            </table>
            <br /><br />
            <?php
            if (empty($achou)) {
                ?>
                <div class="alert alert-danger text-center">
                    Não há registro de aula para este Bimestre
                </div>
                <?php
            }
        }
        ?>
        <br /><br />

        <div colspan="5" style="font-weight: bold; text-align: center; font-size: 2em">
            Atividades desenvolvidas
        </div>
        <br /><br />
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td colspan="3" style="font-weight: bold; text-align: center; font-size: 1.6em">
                    <?= $bimestre ?>º Bimestre
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold; font-size: 1.4em">
                    Data
                </td>
                <td style="font-weight: bold; font-size: 1.4em">
                    Tipo de Atividade
                </td>
                <td style="font-weight: bold; font-size: 1.4em">
                    Registro
                </td>
            </tr>
            <?php
            if (!empty($atividade)) {
                foreach ($atividade as $bim => $dados) {
                    if (substr($bim, 0, 1) == $bimestre) {
                        if (!empty($dados)) {
                            $achou1 = 1;
                            foreach ($dados as $v) {
                                ?>
                                <tr>
                                    <td>
                                        <?= data::converteBr($v['data_registro']) ?>
                                    </td>
                                    <td>
                                        <?= $v['tipo'] ?>
                                    </td>
                                    <td>
                                        <?= $v['nome'] ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                    }
                }
            }
            ?>
        </table>
        <br /><br />
        <?php
        if (empty($achou)) {
            ?>
            <div class="alert alert-danger text-center">
                Não há registro de Atividades para este Bimestre
            </div>
            <?php
        }
        ?>
    </div>
    <form action="<?= HOME_URI ?>/profe/def/formGamb.php" target="frame" id="form" method="POST">
        <input type="hidden" name="id" id="id" value="" />
        <?=
        formErp::hidden([
            'id_turma' => $id_turma,
            'id_disc' => $id_disc,
            'n_disc' => $n_disc,
            'n_turma' => $n_turma,
            'id_pl' => $id_pl,
            'escola' => $escola,
            'id_curso' => $id_curso,
            'bimestre' => $bimestre
        ])
        ?>
    </form>
    <?php
    toolErp::modalInicio();
    ?>
    <iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
        <?php
        toolErp::modalFim();
        ?>
    <script>
        function reg(id) {
            if (id) {
                document.getElementById('id').value = id;
            } else {
                document.getElementById('id').value = '';
            }
            $('#myModal').modal('show');
            $('.form-class').val('');
            document.getElementById('form').submit();
        }
    </script>
    <?php
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
                $aula['aulas_dadas'][$l['atual_letiva'] . 'º Bimestre'][$v['data_registro'].'_'.$v['professor_id']] = $v;
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
