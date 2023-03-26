<?php
if (!defined('ABSPATH'))
    exit;

$id_hab = filter_input(INPUT_POST, 'id_hab', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_NUMBER_INT);
$id_cur = filter_input(INPUT_POST, 'id_cur', FILTER_SANITIZE_NUMBER_INT);
$id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$id_gh = filter_input(INPUT_POST, 'id_gh', FILTER_SANITIZE_NUMBER_INT);

if ($model->db->tokenCheck('salvHabCod')) {
    $id_hab = $model->salvHabCod();
}
$curso = sql::get('ge_cursos', 'qt_letiva, un_letiva', ['id_curso' => $id_cur], 'fetch');

if (!empty($id_hab)) {
    $dados = sql::get('coord_hab', '*', ['id_hab' => $id_hab], 'fetch');
    $id_disc = $dados['fk_id_disc'];
    $id_cur = $dados['fk_id_cur'];
    $id_gh = $dados['fk_id_gh'];
    $at_hab = $dados['at_hab'];
//unidade tematica
    $u['at_ut'] = 1;
    $u['fk_id_gh'] = $id_gh;
    if (!empty($id_disc)) {
        $u['fk_id_disc'] = $id_disc;
    }
    $ut = coordena::unTematica($u, ' id_ut, n_ut, n_disc ');
    if (!empty($ut)) {
        foreach ($ut as $v) {
            $optUt[$v['id_ut']] = $v['n_ut'] . ' (' . $v['n_disc'] . ')';
        }
    } else {
        $optUt = null;
    }

//Camp. Experiêcia
    $ce = coordena::campExp(['at_ce' => 1, 'fk_id_gh' => $id_gh], 'id_ce, n_ce');
    if (!empty($ce)) {
        $ce = tool::idName($ce);
    }
    $ciclos = ng_main::ciclos($id_cur);
    $hc = sql::get('coord_hab_ciclo', '*', ['fk_id_hab' => $id_hab]);
    $habCiclo = [];
    foreach ($hc as $v) {
        $habCiclo[$v['fk_id_ciclo']] = $v;
    }
    if (!empty($id_hab)) {
        $habDisc = coordena::habDisc($id_disc, $id_gh);
    }
    if (!empty($habDisc)) {
        foreach ($habDisc as $k => $v) {
            $habDisc[$k] = $v['codigo'] . ' - ' . $v['descricao'];
        }
    }
}
?>
<div class="body">
    <table class="table table-bordered table-hover table-responsive">
        <tr>
            <td>
                Código
            </td>
            <td>
                <?= @$dados['codigo'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Disciplina
            </td>
            <td>
                <?php
                $disc = ng_main::disciplinasCurso($id_cur);
                echo @$disc[@$dados['fk_id_disc']];
                ?>
            </td>
        </tr>
        <tr>
            <td>
                Habilidade
            </td>
            <td>
                <?= @$dados['descricao'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Curso(s)
            </td>
            <td>
                <table class="table table-bordered table-hover table-responsive">
                    <tr>
                        <?php
                        foreach ($ciclos as $kci => $ci) {
                            ?>
                            <td>
                                <?= formErp::checkbox(null, 1, $ci, array_key_exists($kci, $habCiclo) ? 1 : null, 'disabled') ?>
                            </td>
                            <?php
                        }
                        ?>  
                    </tr>
                    <?php
                    if (intval($curso['qt_letiva']) > 1) {
                        ?>
                        <tr>
                            <?php
                            foreach ($ciclos as $kci => $ci) {
                                ?>
                                <td>
                                    <?php
                                    for ($c = 1; $c <= $curso['qt_letiva']; $c++) {
                                        ?>
                                        <?= formErp::checkbox(null, 1, $c . 'º ' . $curso['un_letiva'], in_array($c, explode(',', @$habCiclo[$kci]['atual_letiva'])) ? 1 : null, 'disabled') ?>
                                        <br />
                                        <?php
                                    }
                                    ?>
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
            </td>
        </tr>
        <?php
        if (!empty($dados['inicio']) || !empty($dados['fim'])) {
            ?>
            <tr>
                <td>
                    Idade Mínima Prevista (em meses): <?= @$dados['inicio'] ?>
                </td>
                <td>
                    Idade Máxima Prevista (em meses): <?= @$dados['fim'] ?>
                </td>
            </tr>
            <?php
        }
        if (!empty($dados['fk_id_oc'])) {
            ?>
            <tr>
                <td>
                    Objeto de Conhecimento
                </td>
                <td>
                    <?php
                    $oc = coordena::objetoConhecimento(['at_oc' => 1, 'fk_id_gh' => $id_gh], 'id_oc, n_oc');
                    if (!empty($oc)) {
                        $oc = tool::idName($oc);
                    }
                    echo $oc[$dados['fk_id_oc']];
                    ?>
                </td>
            </tr>
            <?php
        }
        if (!empty($dados['fk_id_oc'])) {
            ?>
            <tr>
                <td>
                    Objeto de Conhecimento
                </td>
                <td>
                    <?php
                    $oc = coordena::objetoConhecimento(['at_oc' => 1, 'fk_id_gh' => $id_gh], 'id_oc, n_oc');
                    if (!empty($oc)) {
                        $oc = tool::idName($oc);
                    }
                    echo $oc[$dados['fk_id_oc']];
                    ?>
                </td>
            </tr>
            <?php
        }
        if (!empty($dados['fk_id_ut'])) {
            ?>
            <tr>
                <td>
                    Unidade Temática
                </td>
                <td>
                    <?= $optUt[$dados['fk_id_ut']] ?>
                </td>
            </tr>
            <?php
        }
        if (!empty($dados['fk_id_ce'])) {
            ?>
            <tr>
                <td>
                    Camp. Experiêcia
                </td>
                <td>
                    <?= $ce[$dados['fk_id_ce']] ?>
                </td>
            </tr>
            <?php
        }
        if (!empty($dados['metodologicas'])) {
            ?>
            <tr>
                <td>
                    Sugestões<br>Metodológica
                </td>
                <td>
                    <?= $dados['metodologicas'] ?>
                </td>
            </tr>
            <?php
        }
        if (!empty($dados['verific_aprendizagem'])) {
            ?>
            <tr>
                <td>
                    Sugestões de<br />verificação de<br />aprendizagem
                </td>
                <td>
                    <?= @$dados['verific_aprendizagem'] ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>
