<?php
if (!defined('ABSPATH'))
    exit;
$id_planoOld = null;
$sistema = $model->getSistema('22', '48,2,18,53,54,55,24,56');
$id_instCoord = null;
$coordenador = 0;
if (toolErp::id_nilvel() == 24) {
    $id_pessoa = tool::id_pessoa();
} elseif (in_array(toolErp::id_nilvel(), [48, 55])) {
    $coordenador = 1;
    $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
    $id_instCoord = toolErp::id_inst();
} elseif (in_array(toolErp::id_nilvel(), [2, 18, 53, 54, 22])) {
    $coordenador = 1;
    $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
    $id_instCoord = filter_input(INPUT_POST, 'id_instCoord', FILTER_SANITIZE_NUMBER_INT);
    if (toolErp::id_nilvel() == 22) {
        $escolas = ng_escolas::idEscolasSupervisor(tool::id_pessoa(), [1]);
    } else {
        $escolas = ng_escolas::idEscolas([1]);
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Plano de aula
    </div>

    <?php
    if (in_array(toolErp::id_nilvel(), [2, 18, 53, 54, 22])) {
        echo formErp::select('id_instCoord', $escolas, 'Escola', $id_instCoord, 1) . '<br>';
    }
    if (in_array(toolErp::id_nilvel(), [48, 2, 18, 53, 54, 55, 22]) && $id_instCoord) {
        $prof_ = professores::listar($id_instCoord);
        if (!empty($prof_)) {
            $prof = toolErp::idName($prof_, 'fk_id_pessoa', 'n_pessoa');
            asort($prof);
            echo formErp::select('id_pessoa', $prof, 'Professor', $id_pessoa, 1, ['id_instCoord' => $id_instCoord]) . '<br /><br />';
        } else {
            ?>
            <div class="alert alert-danger" style="text-align: center; font-size: 20px">
                Cadastre e aloque os professores
            </div>
            <?php
        }
    }
    if (!empty($id_pessoa)) {
        $plano = filter_input(INPUT_POST, 'plano', FILTER_SANITIZE_NUMBER_INT);
        $atualLetivaSet = filter_input(INPUT_POST, 'atualLetivaSet', FILTER_SANITIZE_NUMBER_INT);
        $activeNav = filter_input(INPUT_POST, 'activeNav', FILTER_SANITIZE_STRING);
        $id_pls = ng_main::periodosAtivos();
        $cicloDiscEsc = ng_professor::ciclosDisc($id_pessoa, $id_instCoord, NULL, $id_pls);
        if ($cicloDiscEsc) {
            foreach ($cicloDiscEsc as $v) {
                if (in_array($v['id_curso'], [1, 5, 9])) {
                    ?>
                    <form id="<?= $v['id_ciclo'] . '_' . $v['iddisc'] . '_' . $v['id_inst'] ?>" action=" " method="POST">
                        <?=
                        formErp::hidden([
                            'activeNav' => $v['id_ciclo'] . '_' . $v['iddisc'] . '_' . $v['id_inst'],
                            'id_pessoa' => $id_pessoa,
                            'id_instCoord' => $id_instCoord
                        ])
                        ?>
                    </form>
                    <?php
                    $v['atualLetivaSet'] = $atualLetivaSet;
                    $v['id_pessoa'] = $id_pessoa;
                    $hiddenAba[$v['id_ciclo'] . '_' . $v['iddisc'] . '_' . $v['id_inst']] = $v;
                }
            }
            if ($activeNav) {
                $cde = $hiddenAba[$activeNav];
            }

            if (!empty($cde)) {
                foreach (range(1, (empty($cde['qt_letiva']) ? 1 : $cde['qt_letiva'])) as $v) {
                    $al[$v] = $v . 'º ' . $cde['un_letiva'];
                }
                $atualLetivaSet = (empty($atualLetivaSet) ? $cde['atual_letiva'] : $atualLetivaSet);

                $n_turmas = ng_professor::turmasDiscEsc($id_pessoa, $cde['id_ciclo'], $cde['id_disc'], $cde['id_inst']);
                $id_turmas = array_keys($n_turmas);
                $sql = " SELECT * FROM coord_plano_aula pa "
                        . " JOIN coord_plano_aula_turmas pt on pt.fk_id_plano = pa.id_plano "
                        . " JOIN profe_projeto_status ps on ps.id_projeto_status = pa.fk_id_projeto_status "
                        . " WHERE pt.fk_id_turma in (" . (implode(',', $id_turmas)) . ") "
                        . " AND pa.iddisc LIKE '" . ($cde['id_disc']) . "' "
                        . " AND pa.atualLetiva = $atualLetivaSet ";
                $query = pdoSis::getInstance()->query($sql);
                $plans = $query->fetchAll(PDO::FETCH_ASSOC);

                if ($plans) {
                    foreach ($plans as $v) {
                        $t[$v['id_plano']][$v['fk_id_turma']] = $n_turmas[$v['fk_id_turma']];
                        $v['turmas'] = $t[$v['id_plano']];
                        $planos[$v['id_plano']] = $v;
                    }

                    if ($coordenador == 1) {//se for coordenador, muda botoes
                        foreach ($planos as $k => $v) {
                            $planos[$k]['turmas'] = toolErp::virgulaE($v['turmas']);
                            if ($v['fk_id_projeto_status'] == 1) {
                                $btn_ver = "outline-secondary";
                            } else {
                                $btn_ver = ($v['fk_id_projeto_status'] <> 2) ? 'warning' : 'info';
                            }
                            $planos[$k]['ac'] = '<button onclick="planCoord(' . $v['id_plano'] . ', ' . $v['fk_id_pl'] . ',' . $v['atualLetiva'] . ',' . $v['fk_id_pessoa'] . ')" class="btn btn-' . $btn_ver . '">Acessar</button>';
                        }
                    } else {
                        $id_planoOld = null;
                        foreach ($planos as $k => $v) {
                            $planos[$k]['turmas'] = toolErp::virgulaE($v['turmas']);
                            $btn_ver = ($v['coord_vizualizar'] == 1) ? 'warning' : 'info';
                            $planos[$k]['ac'] = '<button onclick="plan(' . $v['id_plano'] . ', ' . $id_planoOld . ')" class="btn btn-' . $btn_ver . '">Acessar</button>';
                            $id_planoOld = $v['id_plano'];
                        }
                    }


                    $form['array'] = $planos;
                    $form['fields'] = [
                        'ID' => 'id_plano',
                        'Início' => 'dt_inicio',
                        'Término' => 'dt_fim',
                        'Turmas' => 'turmas',
                        'Aulas' => 'qt_aulas',
                        'Situação' => 'n_projeto_status',
                        '||1' => 'ac'
                    ];
                }
            }

            if (!empty($cicloDiscEsc)) {
                ?>
                <div class="row">
                    <div class="col">
                        <div class="input-group">
                            <table>
                                <tr>
                                    <td>
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="sel">Ciclo/Disciplina/Escola</label>
                                        </div>
                                    </td>
                                    <td>
                                        <select id="sel" class="selectpicker" data-width="fit" data-style="btn-outline-info" onchange="this.options[this.selectedIndex].onclick()">
                                            <option></option>
                                            <?php
                                            foreach ($cicloDiscEsc as $v) {
                                                if (in_array($v['id_curso'], [1, 5, 9])) {
                                                    ?>
                                                    <option <?= $activeNav == $v['id_ciclo'] . '_' . $v['iddisc'] . '_' . $v['id_inst'] ? 'selected' : '' ?> onclick="trocaCiclo('<?= $v['id_ciclo'] . '_' . $v['iddisc'] . '_' . $v['id_inst'] ?>')">
                                                        <?= $v['n_ciclo'] . ' - ' . $v['n_disc'] . ' - ' . $v['n_inst'] ?>
                                                    </option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col">
                        <?php if (!empty($cde)) { ?>
                            <div>
                                <?php
                                if ($cicloDiscEsc) {
                                    echo formErp::select('atualLetivaSet', $al, 'Unidade Letiva', $atualLetivaSet, 1, $cde + ['activeNav' => $activeNav]);
                                }
                                ?>
                            </div> 
                        <?php } ?>
                    </div>
                    <div class="col">
                        <div class="col" style="text-align: right; padding-right: 20px">
                            <button onclick="cal(<?= date("m") ?>)" type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Calendário
                            </button>
                        </div>
                    </div>
                </div>
                <br />
                <?php
            } else {
                ?>
                <div class="alert alert-danger">
                    Não há turma alocada
                </div>
                <?php
            }

            if (!empty($cde)) {
                if ($coordenador <> 1) {
                    ?>
                    <div class="row">
                        <div class="col">
                            <button onclick="plan('', '<?= $id_planoOld ?>')" class="btn btn-primary">
                                Novo Plano de Aula
                            </button>
                        </div>
                    </div>
                    <?php
                }
                if (toolErp::id_nilvel() == 22) {
                    $coord = '(Coordenador)';
                } else {
                    $coord = '';
                }
                ?>
                <div class="row">
                    <div class="col-md-5" style="text-align:right; width: 100%; margin-bottom:5px;">
                        <button  class="btn btn-info" style="width: 20px; height: 20px;"></button> Disponível para Alterações <?= $coord ?>
                        <button  class="btn btn-warning" style="width: 20px; height: 20px;"></button> Indisponível para Alterações <?= $coord ?>
                        <?php if ($coordenador == 1) { ?>
                            <button  class="btn btn-outline-secondary" style="width: 20px; height: 20px;"></button> Bloqueado
                        <?php }
                        ?>
                    </div>
                </div>
                <?php
                if (!empty($form)) {
                    report::simple($form);
                }
                ?>
                <form id="form"  action="<?= HOME_URI ?>/<?= $sistema ?>/plano" method="POST">
                    <?=
                    formErp::hidden($cde)
                    . formErp::hidden([
                        'atualLetivaSet' => $atualLetivaSet,
                        'id_pessoa' => $id_pessoa,
                        'activeNav' => $activeNav,
                        'id_instCoord' => $id_instCoord
                    ])
                    ?>
                    <input type="hidden" name="id_plano" id="id_plano" />
                    <input type="hidden" name="id_planoOld" id="id_planoOld"/>
                </form>
                <form id="formCoord"  action="<?= HOME_URI ?>/<?= $sistema ?>/planoCoord" method="POST">
                    <input type="hidden" name="id_plano" id="id_planoCoord" />
                    <input type="hidden" name="id_pl" id="id_pl" />
                    <input type="hidden" name="atualLetivaSet" id="atualLetivaSet" />
                    <input type="hidden" name="id_pessoa" id="id_pessoa" />
                    <?=
                    formErp::hidden($cde)
                    . formErp::hidden([
                        'id_curso' => $cde['id_curso'],
                        'id_disc' => $cde['id_disc'],
                        'id_ciclo' => $cde['id_ciclo'],
                        'id_inst' => $id_instCoord,
                        'un_letiva' => $cde['un_letiva'],
                        'voltar' => 1,
                        'atualLetivaSet' => $atualLetivaSet,
                        'id_pessoa' => $id_pessoa,
                        'activeNav' => $activeNav
                    ])
                    ?>
                </form>
                <?php
            }
        }
        ?>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Calendário</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <?php
                        $ano = str_pad(date("Y"), 2, "0", STR_PAD_LEFT);
                        $mes = str_pad(date("m"), 2, "0", STR_PAD_LEFT);
                        ?>
                        <div class="row">
                            <div class="col">
                                <?= formErp::select('mes', data::meses(), 'Mês', $mes, null, null, null, null, 1) ?>
                            </div>
                        </div>
                        <br /><br />
                        <div id="cal"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <script>
        function cal(mes) {
            dados = 'id_pessoa=<?= $id_pessoa ?>&mes=' + mes;
            fetch('<?= HOME_URI ?>/habili/def/calendarioPlan.php', {
                method: "POST",
                body: dados,
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            })
                    .then(resp => resp.text())
                    .then(resp => {
                        document.getElementById('cal').innerHTML = resp;
                    });
        }
        function plan(id, idOld) {
            if (id) {
                document.getElementById("id_plano").value = id;
            } else {
                document.getElementById("id_plano").value = "";
            }
            if (idOld) {
                document.getElementById("id_planoOld").value = idOld;
            } else {
                document.getElementById("id_planoOld").value = "";
            }
            document.getElementById('form').submit();
            if (document.getElementById('cadModal')) {
                var myModal = new bootstrap.Modal(document.getElementById('cadModal'), {
                    keyboard: true
                });
                myModal.show();
            }
        }
        function planCoord(id, id_pl, atualLetiva, id_pessoa) {
            document.getElementById("id_planoCoord").value = id;
            document.getElementById("id_pessoa").value = id_pessoa;
            document.getElementById("id_pl").value = id_pl;
            document.getElementById("atualLetivaSet").value = atualLetiva;
            document.getElementById('formCoord').submit();
            if (document.getElementById('cadModal')) {
                var myModal = new bootstrap.Modal(document.getElementById('cadModal'), {
                    keyboard: true
                });
                myModal.show();
            }
        }
        function mes(id) {
            cal(id);
        }
        function trocaCiclo(id) {
            document.getElementById(id).submit();
        }
    </script>
