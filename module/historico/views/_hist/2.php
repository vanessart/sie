<?php
if (!defined('ABSPATH'))
    exit;
$histOld = filter_input(INPUT_POST, 'histOld', FILTER_SANITIZE_NUMBER_INT);
if ($histOld) {
    $h = new historico($id_pessoa);
    $hist = $h->_dadosAntigos;
    if (empty($hist['discNota'])) {
        toolErp::alertModal('Não há dados do hitórico antigo.');
    } else {
        $disciplinas = sql::idNome('ge_disciplinas');
        $codDisc = [6, 9, 10, 11, 12, 13, 14, 15, 30];
        $n = $hist['discNota'];
        foreach ($n as $ano => $v) {
            foreach ($v as $ky => $y) {
                /**
                  if (!is_numeric($ky)) {
                  $ky = uniqid();
                  }
                 * 
                 */
                $notaDisc[$ky]['n_' . $ano] = $y;
            }
        }
        $cont = 100;
        foreach ($notaDisc as $id_disc => $v) {
            $continua = null;
            foreach ($v as $ano => $nt) {
                if (!empty($nt)) {
                    $continua = 1;
                }
            }
            if (empty($continua)) {
                continue;
            }
            unset($ins);
            $ins['fk_id_pessoa'] = $id_pessoa;
            $ins['ativo'] = 1;
            $sql = "SELECT * FROM `historico_notas` WHERE `fk_id_disc` LIKE '$id_disc' AND `fk_id_pessoa` = $id_pessoa ORDER BY `fk_id_disc` ASC ";
            $query = pdoSis::getInstance()->query($sql);
            $ar = $query->fetch(PDO::FETCH_ASSOC);

            if ($ar) {
                $ins['id_nota'] = $ar['id_nota'];
                foreach ($v as $ano => $nt) {
                    if (empty($ar[$ano])) {
                        $ins[$ano] = $nt;
                    }
                }
            } else {
                if (array_key_exists($id_disc, $disciplinas)) {
                    $ins['n_disc'] = $disciplinas[$id_disc];
                } else {
                    $ins['n_disc'] = $id_disc;
                }
                $ins['ordem'] = $cont++;
                $ins['fk_id_disc'] = $id_disc;
                if (in_array($id_disc, $codDisc)) {
                    $ins['fk_id_base'] = 1;
                } else {
                    $ins['fk_id_base'] = 2;
                }
                foreach ($v as $ano => $nt) {
                    if (!empty($nt)) {
                        $ins[$ano] = $nt;
                    }
                }
            }

            $model->db->ireplace('historico_notas', $ins, 1);
        }
        toolErp::alertModal('Feito!');
    }
}
$id_ciclo = $periodo = filter_input(INPUT_POST, 'id_ciclo');
$anoCompleto = $periodo = filter_input(INPUT_POST, 'anoCompleto');
if ($anoCompleto) {
    $_SESSION['TMP']['anoCompleto'][$id_pessoa] = $anoCompleto;
} elseif (!empty($_SESSION['TMP']['anoCompleto'][$id_pessoa])) {
    $anoCompleto = $_SESSION['TMP']['anoCompleto'][$id_pessoa];
}
if ($id_ciclo == 'x') {
    unset($_SESSION['TMP']['id_ciclo'][$id_pessoa]);
    unset($_SESSION['TMP']['anoCompleto'][$id_pessoa]);
    $id_ciclo = null;
    $anoCompleto = null;
} elseif ($id_ciclo) {
    $_SESSION['TMP']['id_ciclo'][$id_pessoa] = $id_ciclo;
} elseif (!empty($_SESSION['TMP']['id_ciclo'][$id_pessoa])) {
    $id_ciclo = $_SESSION['TMP']['id_ciclo'][$id_pessoa];
}
$h = sqlErp::get(['historico_dados_gerais', 'historico_tipo_ensino'], '*', ['fk_id_pessoa' => $id_pessoa], 'fetch');
$ensinoCiclos = $ensinoCiclosSet = $model->ensinoCiclos($h['fk_id_hte']);
$ensinoCiclosIniFim = $model->ensinoCiclos($h['fk_id_hte'], 1);

$notas = $model->notas($id_pessoa);

if ($anoCompleto == 'x' || empty($anoCompleto)) {
    $notasParciais = $model->notasParciais($id_pessoa, $id_ciclo);
}
if (empty($notas) && !empty($notasParciais)) {
    $notas = $model->notasSoParciais($id_pessoa);
}

$id_ciclo_cursado = $model->cicloCursando($notas);
if (!empty($notasParciais)) {
    $turma = $model->frequente($id_pessoa, $id_ciclo);
}
if (!empty($id_ciclo_cursado) && !empty($turma) && empty($anoCompleto)) {
    if ($turma['fk_id_ciclo'] <= $id_ciclo_cursado) {
        $naoMostraParcial = 1;
    }
}
if (!empty($h['ciclos'])) {
    $ciclosSet = json_decode($h['ciclos'], true);
    foreach ($ensinoCiclos as $k => $v) {
        if (!in_array($k, $ciclosSet)) {
            unset($ensinoCiclos[$k]);
        }
    }
    foreach ($ensinoCiclosIniFim as $ky => $y) {
        foreach ($y as $k => $v) {
            if (!in_array($k, $ciclosSet)) {
                unset($ensinoCiclosIniFim[$ky][$k]);
            }
        }
    }
    foreach ($ensinoCiclosIniFim as $ky => $y) {
        if (empty($y)) {
            unset($ensinoCiclosIniFim[$ky]);
        }
    }
}
if (empty($h['regime'])) {
    $ant = $model->anosAnteriores($dados->dadosPessoais);
    if ($ant) {
        foreach ($ant as $k => $v) {
            $regi[$v['fk_id_ciclo']] = $v['regime'];
        }
    }
    if (!empty($regi)) {
        $insDados['id_dg'] = $h['id_dg'];
        $insDados['regime'] = json_encode($regi);
        $model->db->ireplace('historico_dados_gerais', $insDados, 1);
    }
}
if (!empty($h['regime'])) {
    $regime = json_decode($h['regime'], true);
}

$hidden = [
    'id_pessoa' => $id_pessoa,
    'activeNav' => 2,
    'id_ciclo' => $id_ciclo,
    'id_hte' => $h['fk_id_hte'],
    'anoCompleto' => $anoCompleto
];
if (in_array($id_ciclo, [1, 2, 3, 4, 5]) && !empty($turma) && empty($naoMostraParcial)) {
    $faltasNcCalc = $model->faltasNcCalc($id_pessoa, $id_ciclo);
    $faltaNc = sql::get('historico_faltas_nc', '*', ['fk_id_pessoa' => $id_pessoa, 'fk_id_ciclo' => $id_ciclo], 'fetch');
    if (empty($faltaNc)) {
        $model->faltaNc($id_ciclo, $id_pessoa, $faltasNcCalc);
    }
    $faltaNc = sql::get('historico_faltas_nc', '*', ['fk_id_pessoa' => $id_pessoa, 'fk_id_ciclo' => $id_ciclo], 'fetch');
}
?>
<div class="body">
    <div class="border">
        <form method="POST">
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <?php
                    foreach ($ensinoCiclosSet as $k => $v) {
                        ?>
                        <td>
                            <?php
                            if (in_array($k, [1, 2, 3, 4, 5, 6, 7, 8])) {
                                formErp::select('regime[' . $k . ']', [0 => $v, 1 => (str_replace('Ano', 'Série', $v))], null, @$regime[$k]);
                            } else {
                                echo $v;
                            }
                            ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <tr>
                    <?php
                    foreach ($ensinoCiclosSet as $k => $v) {
                        ?>
                        <td>
                            <?= formErp::checkbox('ciclos[' . $k . ']', $k, null, (empty($h['ciclos']) ? $k : (in_array($k, $ciclosSet) ? $k : null))) ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
            </table>
            <div style="text-align: center; padding: 10px">
                <?=
                formErp::hidden($hidden)
                . formErp::hiddenToken('ciclosSet')
                . formErp::button('Salvar')
                ?>
            </div>
        </form>
    </div>
    <br /><br />
    <div class="border">
        <form method="POST">
            <?= formErp::hidden($hidden) ?>
            <div class="row">
                <div class="col">
                    <?= formErp::select('id_ciclo', ['' => ['x' => 'Limpar Filtro']] + $ensinoCiclosIniFim, 'Histórico Até', $id_ciclo, null, null, ' required ') ?>
                </div>
                <div class="col">
                    <?= formErp::select('anoCompleto', ['x' => 'Não', 1 => 'Sim'], 'Ano Completo?', $anoCompleto, null, null, ' required ') ?>
                </div>
                <div class="col">
                    <?= formErp::button('Alterar') ?>
                </div>
            </div>
        </form>
    </div>
    <br />
    <div class="row">
        <div class="col" style="padding-top: 30px">
            <button class="btn btn-primary" onclick="editar()">
                Nova Disciplina
            </button>
        </div>
        <div class="col">
                <form method="POST">
                    <div style="text-align: center; padding: 10px">
                        <?=
                        formErp::hidden($hidden)
                        . formErp::hidden(['histOld' => 1])
                        . formErp::button('Importar dados do histórico Antigo')
                        ?>
                    </div>
                </form>
        </div>
        <div class="col">
            <?php
            if (in_array($id_ciclo, [1, 2, 3, 4, 5]) && !empty($turma) && empty($naoMostraParcial)) {
                ?>
                <form method="POST">
                    <table class="table table-bordered table-hover table-striped border">
                        <tr>
                            <td colspan="6" style="font-weight: bold; font-size: 1.4em; text-align: center">
                                Faltas - Núcleo Comum
                            </td>
                        </tr>
                        <tr>
                            <td>
                                1º b
                            </td>
                            <td>
                                2º b
                            </td>
                            <td>
                                3º b
                            </td>
                            <td>
                                4º b
                            </td>
                            <td>
                                Total
                            </td>
                            <td rowspan="2">
                                <?=
                                formErp::hidden($hidden)
                                . formErp::hidden(['id_fn' => $faltaNc['id_fn']])
                                . formErp::hiddenToken('faltaNc')
                                . formErp::button('Salvar')
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?= formErp::input('f[1]', null, $faltaNc['b_1'], ' min="0" ', null, 'number') ?>
                            </td>
                            <td>
                                <?= formErp::input('f[2]', null, $faltaNc['b_2'], ' min="0" ', null, 'number') ?>
                            </td>
                            <td>
                                <?= formErp::input('f[3]', null, $faltaNc['b_3'], ' min="0" ', null, 'number') ?>
                            </td>
                            <td>
                                <?= formErp::input('f[4]', null, $faltaNc['b_3'], ' min="0" ', null, 'number') ?>
                            </td>
                            <td>
                                <?= formErp::input(null, null, $faltaNc['b_1'] + $faltaNc['b_2'] + $faltaNc['b_3'] + $faltaNc['b_4'], ' readonly ', null, 'number') ?>
                            </td>
                        </tr>
                    </table>
                </form>
                <?php
            }
            ?>
        </div>
        <div class="col" style="text-align: right; padding-right: 30px; padding-top: 30px">
            <button class="btn btn-danger" onclick="apaga()">
                Restaurar Notas
            </button>
        </div>
    </div>
    <br />
    <?php
    if (!empty($notas)) {
        ?>
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td rowspan="2">

                </td>
                <td rowspan="2">
                    Base
                </td>
                <td rowspan="2">
                    Disciplina
                </td>
                <?php
                foreach ($ensinoCiclosIniFim as $k => $v) {
                    ?>
                    <td colspan="<?= count($v) ?>" style="text-align: center">
                        <?= $k ?>
                    </td>
                    <?php
                }
                ?>
                <td colspan="5" style="text-align: center">
                    <?php
                    if (!empty($turma) && empty($naoMostraParcial)) {
                        echo $turma['n_turma'];
                    } else {
                        echo 'Ano letivo atual';
                    }
                    ?>

                </td>
            </tr>
            <tr>
                <?php
                foreach ($ensinoCiclos as $k => $v) {
                    ?>
                    <td style="text-align: center">
                        <?php
                        if (@$regime[$k] == 1) {
                            echo str_replace('Ano', 'Série', $v);
                        } else {
                            echo $v;
                        }
                        ?>
                    </td>
                    <?php
                }
                ?>
                <td>
                    1º b
                </td>
                <td>
                    2º b
                </td>
                <td>
                    3º b
                </td>
                <td>
                    4º b
                </td>
                <td>
                    Faltas Aula
                </td>
            </tr>
            <?php
            if ($notas) {
                foreach ($notas as $v) {
                    ?>
                    <tr>
                        <td>
                            <button class="btn btn-<?= $v['ativo'] == 1 ? 'primary' : 'secondary' ?>" onclick="editar(<?= $v['id_nota'] ?>)">
                                Editar
                            </button>
                        </td>
                        <td>
                            <?= $v['n_base'] ?>
                        </td>
                        <td>
                            <?= $v['n_disc'] ?>
                        </td>
                        <?php
                        $continue = true;
                        foreach ($ensinoCiclos as $kci => $ci) {
                            if ($id_ciclo == $kci && $id_ciclo && $anoCompleto == 'x') {
                                $continue = false;
                            }
                            ?>
                            <td style="text-align: center">
                                <?php
                                if (!empty($v['n_' . $kci]) && $continue) {
                                    echo $v['n_' . $kci];
                                }
                                ?>
                            </td>
                            <?php
                            if ($id_ciclo == $kci && $id_ciclo) {
                                $continue = false;
                            }
                        }
                        foreach (range(1, 4) as $b) {
                            ?>
                            <td>
                                <?php
                                if (empty($naoMostraParcial)) {
                                    echo @$notasParciais[@$v['fk_id_disc']][$b];
                                }
                                ?>
                            </td>
                            <?php
                        }
                        ?>
                        <td>
                            <?php
                            if (empty($naoMostraParcial)) {
                                echo @$notasParciais[@$v['fk_id_disc']]['faltas'];
                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>
        <?php
    } else {
        $grades = sql::idNome('ge_grades');
        ?>
        <div class="alert alert-danger" >
            <p style="font-weight: bold; text-align: center; font-size: 1.5em">
                Não encontramos registros de disciplinas para esta aluno.
            </p>
            <p style="font-weight: bold; text-align: center; font-size: 1.2em">
                Selecione abaixo a grade para este aluno
            </p>
        </div>
        <form method="POST">
            <div class="row">
                <div class="col">
                    <div style="margin-left: 50%">
                        <?= formErp::select('id_grade', $grades, 'Grades') ?>
                    </div>
                </div>
                <div class="col">
                    <?=
                    formErp::hidden($hidden)
                    . formErp::hiddenToken('gradeSet')
                    . formErp::button("Criar Disciplinas")
                    ?>
                </div>
            </div>
            <br />
        </form>
        <?php
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/historico/def/formNota" target="frame" id="formNota" method="POST">
    <input type="hidden" name="id_nota" id="id_nota" value="" />
    <?= formErp::hidden($hidden) ?>
</form>
<form id="excl" method="POST">
    <?=
    formErp::hidden($hidden)
    . formErp::hiddenToken('excluirDados');
    ?>
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim()
    ?>
<script>
    function apaga() {
        if (confirm("Está ação excluirá todas as alterações realizadas. Continuar?")) {
            excl.submit();
        }
    }

    function editar(id) {
        if (id) {
            id_nota.value = id;
        } else {
            id_nota.value = '';
        }
        formNota.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>
<?php
//291125
//// este ano 475314	493813
#######