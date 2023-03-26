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
    <?php
    if (!empty($id_hab)) {
        ?>
        <form target="_parent" action="<?php echo HOME_URI ?>/habili/habil" method="POST">
            <?php
        } else {
            ?>
            <form method="POST">
                <?php
            }
            /**
              if (!empty($id_hab)) {
              ?>
              <div style="font-weight: bold" class="row">
              <div class="col-5">
              <?= formErp::input('1[codigo]', 'Código', @$dados['codigo'], ' readonly ') ?>
              </div>
              <div class="col-7">
              <?php
              $disc = ng_main::disciplinasCurso($id_cur);
              ?>
              <?=
              formErp::input(null, 'Disciplina', @$disc[$dados['fk_id_disc']], ' readonly ')
              . formErp::hidden(['1[fk_id_disc]' => @$dados['fk_id_disc']])
              ?>

              </div>
              </div>
              <br />
              <?php
              } else {
             * 
             */
            ?>
            <div class="row">
                <div class="col">
                    <?php echo formErp::input('1[codigo]', 'Código', @$dados['codigo']) ?>
                </div>
                <div class="col">
                    <?php
                    $disc = ng_main::disciplinasCurso($id_cur);
                    echo formErp::select('1[fk_id_disc]', $disc, 'Disciplina', @$dados['fk_id_disc'], null, null, ' required ');
                    ?>
                </div>
            </div>
            <br />
            <?php
            //}
            if (empty($id_hab)) {
                ?>
                <div style="text-align: center">
                    <?php
                    echo formErp::hidden([
                        '1[fk_id_gh]' => $id_gh,
                        '1[fk_id_cur]' => $id_cur,
                        '1[peso]' => 1,
                        'id_gh' => $id_gh,
                        'id_cur' => $id_cur,
                        'id_ciclo' => $id_ciclo,
                        'at_hab' => 1,
                    ]);
                    echo formErp::hiddenToken('salvHabCod');
                    echo formErp::button('Continuar');
                    ?>

                </div>
            </form>
            <?php
        } else {
            ?>
            <div class="row">
                <div class="col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Habilidade</span>
                        </div>
                        <textarea name="1[descricao]" class="form-control" aria-label="With textarea"><?= @$dados['descricao'] ?></textarea>
                    </div>
                </div>
            </div>
            <br />
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <?php
                    foreach ($ciclos as $kci => $ci) {
                        ?>
                        <td>
                            <label>
                                <input onclick="checkLetiva(this, <?= $kci ?>)" id="ciclo_<?= $kci ?>" <?php echo!empty($id_hab) ? (array_key_exists($kci, $habCiclo) ? 'checked' : null) : null ?> type="checkbox" name="cicl[<?php echo $kci ?>]" value="<?php echo @$habCiclo[$kci]['id_hc'] ?>" />
                                <?php echo $ci ?>
                            </label>
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
                                    <label>
                                        <input id="letiva_<?= $kci ?>_<?= $c ?>" onclick="checkCiclo(this, <?= $kci ?>)" <?php echo!empty($id_hab) ? (in_array($c, explode(',', @$habCiclo[$kci]['atual_letiva'])) ? 'checked' : null) : null ?> type="checkbox" name="letiva[<?php echo $kci ?>][<?php echo $c ?>]" value="<?php echo $c ?>" />
                                        <?php echo $c . 'º ' . $curso['un_letiva'] ?>
                                    </label>
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
            <br />
            <div class="row">
                <div class="col">
                    <?php echo formErp::input('1[inicio]', 'Idade Mínima (em meses)', @$dados['inicio'], null, null, 'number') ?>
                </div>
                <div class="col">
                    <?php echo formErp::input('1[fim]', 'Idade Máxima (em meses)', @$dados['fim'], null, null, 'number') ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col">
                    <?php
                    $oc = coordena::objetoConhecimento(['at_oc' => 1, 'fk_id_gh' => $id_gh], 'id_oc, n_oc', [0, 10000]);
                    if (!empty($oc)) {
                        $oc = tool::idName($oc);
                    }
                    echo formErp::select('1[fk_id_oc]', $oc, 'Objeto de Conhecimento', @$dados['fk_id_oc']);
                    ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col">
                    <?php
                    echo formErp::select('1[fk_id_ut]', $optUt, 'Unidade Temática', @$dados['fk_id_ut']);
                    ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col">
                    <?php
                    echo formErp::select('1[fk_id_ce]', $ce, 'Camp. Experiêcia', @$dados['fk_id_ce']);
                    ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Sugestões<br />metodológicas</span>
                        </div>
                        <textarea name="1[metodologicas]" class="form-control" aria-label="With textarea"><?= @$dados['metodologicas'] ?></textarea>
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Sugestões de<br />verificação de<br />aprendizagem</span>
                        </div>
                        <textarea name="1[verific_aprendizagem]" class="form-control" aria-label="With textarea"><?= @$dados['verific_aprendizagem'] ?></textarea>
                    </div>
                </div>
            </div>
            <br />
            <div class="border">
                <div style="text-align: center">
                    Habilidades /(*) Habilidades alicerces
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Habilidades alicerces
                    </button>
                    <br /><br />
                    <?php
                    if (!empty($habDisc)) {
                        ?>
                        <div style="height: 480px; width: 100%" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <div style="padding: 10px">
                                <input class="form-control" id="myInput" type="search" placeholder="Pesquisa..">
                            </div>
                            <div style="height: 400px; overflow: auto; width: 100%">
                                <table id="myTable">
                                    <?php
                                    foreach ($habDisc as $k => $v) {
                                        ?>
                                        <tr>
                                            <td style="padding: 3px">
                                                <div class="alert alert-dark" onclick="alicerceSet(<?= $k ?>, 'i')" style="width: 98%; margin: auto; word-break: normal; cursor: pointer; text-align: justify">
                                                    <?= $v ?>   
                                                </div>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </table>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div id="alicerces"></div>
            </div>
            <br />
            <div class="border">
                <div style="text-align: center">
                    Conhecimentos prévios
                </div>
                <br />
                <div class="row">
                    <div class="col">
                        <?php
                        $disc = ng_main::disciplinasCurso($id_cur);
                        echo formErp::select('prevDisc', $disc, 'Disciplina', @$dados['fk_id_disc'], null, null, null, null, 1);
                        ?>
                    </div>
                    <div class="col" id="prevHab">
                        <?php
                        foreach ($disc as $kd => $d) {
                            $habDisc = coordena::habDisc($kd, $id_gh);
                            if (!empty($habDisc)) {
                                $discJs[$kd] = $d;
                                foreach ($habDisc as $k => $v) {
                                    $habDisc[$k] = $v['codigo'] . ' - ' . $v['descricao'];
                                }
                                ?>
                                <div class="dropdown" id="hp<?= $kd ?>" <?= $id_disc != $kd ? ' style="display: none"' : '' ?>> 
                                    <button class="btn btn-outline-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Habilidades prévias de <?= $d ?>
                                    </button>
                                    <br /><br />
                                    <?php
                                    if (!empty($habDisc)) {
                                        ?>
                                        <div style="height: 400px; overflow: auto; width: 100%" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <div style="padding: 10px">
                                                <input class="form-control" id="myInput<?= $kd ?>" type="search" placeholder="Pesquisa..">
                                            </div>
                                            <div style="height: 400px; overflow: auto; width: 100%">
                                                <table id="myTable<?= $kd ?>">
                                                    <?php
                                                    foreach ($habDisc as $k => $v) {
                                                        ?>
                                                        <tr>
                                                            <td style="padding: 3px">
                                                                <div class="alert alert-dark" onclick="previaSet(<?= $k ?>, 'i')" style="width: 98%; margin: auto; word-break: normal; cursor: pointer; text-align: justify">
                                                                    <?= $v ?>   
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                    ?>
                                                </table>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <br />
                <div id="habPrevia"></div>
            </div>
            <br /><br />
            <div style="text-align: center">
                <?php
                echo formErp::hidden([
                    '1[id_hab]' => $id_hab,
                    '1[fk_id_gh]' => $id_gh,
                    '1[fk_id_cur]' => $id_cur,
                    '1[peso]' => 1,
                    'id_gh' => $id_gh,
                    'id_cur' => $id_cur,
                    'id_disc' => $id_disc,
                    'id_ciclo' => $id_ciclo,
                    'at_hab' => $at_hab,
                ]);
                echo formErp::hiddenToken('salvHab');
                echo formErp::button('Salvar');
                ?>

            </div>
        </form>
    </div>
    <script>
        $(document).ready(function () {
            previaSet(0, 0)
            alicerceSet(0, 0)
            $("#myInput").on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("#myTable tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
    <?php
    foreach ($discJs as $kd => $d) {
        ?>
                $("#myInput<?= $kd ?>").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#myTable<?= $kd ?> tr").filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
        <?php
    }
    ?>
        });
        function checkCiclo(letiva, ciclo) {
            if (letiva.checked === true) {
                document.getElementById('ciclo_' + ciclo).checked = true;
            }
        }

        function checkLetiva(ciclo, id) {
            if (ciclo.checked === false) {
    <?php
    for ($c = 1; $c <= $curso['qt_letiva']; $c++) {
        ?>
                    document.getElementById('letiva_' + id + '_<?= $c ?>').checked = false;
        <?php
    }
    ?>
            }
        }
        function alicerceSet(id_hab, acao) {
            if (acao === 'del') {
                if (confirm("Remover está habilidade alicerce?")) {
                    exec = true;
                } else {
                    exec = false;
                }
            } else {
                exec = true;
            }
            if (exec === true) {
                const dados = 'id_hab_alicerce=' + id_hab + '&id_hab=<?= $id_hab ?>&acao=' + acao;
                fetch('<?= HOME_URI ?>/habili/def/alicerces.php', {
                    method: "POST",
                    body: dados,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                        .then(resp => resp.text())
                        .then(resp => {
                            document.getElementById('alicerces').innerHTML = resp;
                        });
            }
        }
        function prevDisc(id) {
    <?php
    foreach ($discJs as $kd => $d) {
        ?>
                document.getElementById("hp<?= $kd ?>").style.display = "none";
        <?php
    }
    ?>
            document.getElementById("hp" + id).style.display = "";
        }

        function previaSet(id_hab, acao) {
            if (acao === 'del') {
                if (confirm("Remover está habilidade Prévia?")) {
                    exec = true;
                } else {
                    exec = false;
                }
            } else {
                exec = true;
            }
            if (exec === true) {
                const dados = 'id_hab_previa=' + id_hab + '&id_hab=<?= $id_hab ?>&acao=' + acao;
                fetch('<?= HOME_URI ?>/habili/def/prevHab.php', {
                    method: "POST",
                    body: dados,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                        .then(resp => resp.text())
                        .then(resp => {
                            document.getElementById('habPrevia').innerHTML = resp;
                        });
            }
        }
    </script>
    <?php
}
?>