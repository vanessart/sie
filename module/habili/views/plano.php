<style>
    textArea{
        height: 400px;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
if (toolErp::id_nilvel() == 24) {
    $id_pessoa = tool::id_pessoa();
} else {
    $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
}
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$atualLetivaSet = filter_input(INPUT_POST, 'atualLetivaSet', FILTER_SANITIZE_NUMBER_INT);
$activeNav = filter_input(INPUT_POST, 'activeNav', FILTER_SANITIZE_STRING);
$iddisc = filter_input(INPUT_POST, 'iddisc', FILTER_SANITIZE_STRING);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_STRING);
$rm = filter_input(INPUT_POST, 'rm', FILTER_SANITIZE_STRING);
$n_disc = filter_input(INPUT_POST, 'n_disc', FILTER_SANITIZE_STRING);
$n_ciclo = filter_input(INPUT_POST, 'n_ciclo', FILTER_SANITIZE_STRING);
$id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_STRING);
$n_inst = filter_input(INPUT_POST, 'n_inst', FILTER_SANITIZE_STRING);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_STRING);
$atual_letiva = filter_input(INPUT_POST, 'atual_letiva', FILTER_SANITIZE_STRING);
$qt_letiva = filter_input(INPUT_POST, 'qt_letiva', FILTER_SANITIZE_STRING);
$un_letiva = filter_input(INPUT_POST, 'un_letiva', FILTER_SANITIZE_STRING);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_STRING);
$id_plano = $model->id_plano;
$id_planoOld = filter_input(INPUT_POST, 'id_planoOld', FILTER_SANITIZE_NUMBER_INT);

if (!empty($atualLetivaSet)) {
    $atual_letiva = $atualLetivaSet;
}


if (empty($activeNav)) {
    ?>
    <script>
        window.location.href = "<?= HOME_URI ?>/profe/planoAula";</script>
    <?php
    exit();
}
$sql = "SELECT dt_inicio, dt_fim FROM `sed_letiva_data` WHERE `fk_id_curso` = $id_curso AND `fk_id_pl` = $id_pl AND `atual_letiva` = $atualLetivaSet";
$query = pdoSis::getInstance()->query($sql);
$dt_letiva = $query->fetch(PDO::FETCH_ASSOC);
$cde = [
    'iddisc' => $iddisc,
    'id_disc' => $id_disc,
    'rm' => $rm,
    'n_disc' => $n_disc,
    'n_ciclo' => $n_ciclo,
    'id_ciclo' => $id_ciclo,
    'n_inst' => $n_inst,
    'id_inst' => $id_inst,
    'atual_letiva' => $atual_letiva,
    'qt_letiva' => $qt_letiva,
    'un_letiva' => $un_letiva,
    'id_pl' => $id_pl,
    'id_curso' => $id_curso
];

if ($id_plano) {
    $planoAula = sql::get('coord_plano_aula', '*', ['id_plano' => $id_plano], 'fetch');
    $turmasPlano = array_column(sql::get('coord_plano_aula_turmas', 'fk_id_turma', ['fk_id_plano' => $id_plano]), 'fk_id_turma');
    $status = $model->getStatusProjeto($planoAula["fk_id_projeto_status"]);
    $id_status = $planoAula['fk_id_projeto_status'];
    $coord_vizualizar = $planoAula['coord_vizualizar'];
}

if (toolErp::id_nilvel() == 48) {
    if (empty($coord_vizualizar)) {
        $visualizou['coord_vizualizar'] = 1;
        $visualizou['id_plano'] = $id_plano;

        $model->db->ireplace('coord_plano_aula', $visualizou, 1);
    }
}
$turmas = ng_professor::turmasDiscEsc($id_pessoa, $cde['id_ciclo'], $cde['id_disc'], $cde['id_inst']);

$habilidades = $model->retornaHabilidades($cde['id_ciclo'], $cde['id_disc'], $atualLetivaSet, key($turmas));
$opt = [
    'b' => $cde['un_letiva'],
    'a' => 'Ano Inteiro',
];
foreach ($opt as $k => $v) {
    $diplay[$k] = 'none';
    $btn[$k] = 'btn btn-outline-warning';
}

if (!empty($habilidades['p'])) {
    $btn['p'] = 'btn btn-warning';
    $diplay['p'] = 'block';
} else if (!empty($habilidades['b'])) {
    $btn['b'] = 'btn btn-warning';
    $diplay['b'] = 'block';
} else {
    $btn['a'] = 'btn btn-warning';
    $diplay['a'] = 'block';
}

$sql = "SELECT d.n_disc FROM ge_aloca_disc a "
        . " JOIN ge_ciclos c on c.fk_id_grade = a.fk_id_grade "
        . " JOIN ge_disciplinas d on d.id_disc = a.fk_id_disc "
        . " WHERE c.id_ciclo = 1 "
        . " and nucleo_comum "
        . " order by d.n_disc";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
if (!empty($array)) {
    $preEdit = '';
    foreach ($array as $v) {
        $preEdit .= $v['n_disc'] . ":\n\n";
    }
} else {
    $preEdit = null;
}

if ($id_disc == 'nc' && empty($planoAula['recursos'])) {
    $planoAula['recursos'] = $preEdit;
}

if ($id_disc == 'nc' && empty($planoAula['metodologia'])) {
    $planoAula['metodologia'] = $preEdit;
}

if ($id_disc == 'nc' && empty($planoAula['avaliacao'])) {
    $planoAula['avaliacao'] = $preEdit;
}

if ($id_disc == 'nc' && empty($planoAula['adapta_curriculo'])) {
    $planoAula['adapta_curriculo'] = $preEdit;
}
?>
<script>
    $(document).ready(function () {
        $("#myInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });</script>
<div class="body">
    <div class="alert alert-info" style="padding-top:  10px; padding-bottom: 0">
        <div class="row">
            <div class="col-2">
                <?php
                if (!empty($id_planoOld) && !empty($id_plano)) {
                    $planOld = sqlErp::get('coord_plano_aula', '*', ['id_plano' => $id_planoOld], 'fetch', null . 1);
                    ?>
                    <form id="formOld" method="POST">
                        <?= formErp::hidden($cde) 
                        .formErp::hidden([
                            'id_curso' => $id_curso,
                            'atualLetivaSet' => $atualLetivaSet,
                            'activeNav' => $activeNav,
                            'id_plano' => $id_plano,
                            'id_planoOld' => $id_planoOld
                        ])
                            .formErp::hiddenToken('clonarPlano')
                        ?>
                    </form>
                    <button onclick="if (confirm('Está ação apagará as alterações realizadas neste plano de Aula. Continuar?')) {
                                formOld.submit()
                            }" class="btn btn-primary">
                        Clonar do Plano de Aula anterior
                    </button>
    <?php
}
?>
            </div>
            <div class="col-8" style="font-weight: bold; text-align: center;font-size: 20px">
                <p style=" font-size: 24px">
                    Plano de Aula
                </p>
                    <?= $cde['n_inst'] ?>
            </div>
            <div class="col-2" style="text-align: right;padding: 10px">
                <form action="<?= HOME_URI ?>/profe/planoAula" method="POST">
                    <?=
                    formErp::hidden($cde)
                    . formErp::hidden([
                        'activeNav' => $activeNav,
                        'id_pessoa' => $id_pessoa,
                        'atualLetivaSet' => $atualLetivaSet
                    ])
                    ?>
                    <button class="btn btn-info" style="margin: 0">
                        Voltar
                    </button>
                </form>
            </div>
        </div>
        <br />
    </div>
        <?php
        if (empty($id_plano)) {
            ?>
        <form method="POST">
                <?php
            } else {
                ?>
            <form name="salvar_plano" action="<?= HOME_URI ?>/profe/planoAula" method="POST">
                <?php
            }
            ?>
            <?=
            formErp::hidden($cde)
            . formErp::hidden([
                'atualLetivaSet' => $atualLetivaSet,
                'id_pessoa' => $id_pessoa,
                'activeNav' => $activeNav,
                'id_planoOld'=>$id_planoOld
            ])
            ?>
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <td colspan="3"> Situação: <?= @$status ?> </td>
                </tr>
                <tr>
                    <td>
                        Ciclo
                    </td>
                    <td>
                        Disciplina
                    </td>
                    <td>
                        Unidade Letiva
                    </td>
                </tr>
                <tr>
                    <td>
<?= $cde['n_ciclo'] ?>
                    </td>
                    <td>
<?= $cde['n_disc'] ?>
                    </td>
                    <td>
                <?= $atualLetivaSet ?>º <?= $cde['un_letiva'] ?>
                    </td>
                </tr>
<?php
if ($dt_letiva) {
    ?>
                    <tr>
                        <td colspan="3" style="font-weight: bold; text-align: center">
                            Inserir datas entre <?= data::porExtenso($dt_letiva['dt_inicio']) ?> e <?= data::porExtenso($dt_letiva['dt_fim']) ?> 
                        </td>
                    </tr>
                            <?php
                        }
                        ?>
                <tr>
                    <td>
<?= formErp::input('dt_inicio', 'Data de Início', @$planoAula['dt_inicio'], ' required ' . (empty($dt_letiva['dt_inicio']) ? '' : ' min="' . $dt_letiva['dt_inicio'] . '" max="' . $dt_letiva['dt_fim'] . '" '), null, 'date') ?>
                    </td>
                    <td>
<?= formErp::input('dt_fim', 'Data de Final', @$planoAula['dt_fim'], ' required ' . (empty($dt_letiva['dt_inicio']) ? '' : ' min="' . $dt_letiva['dt_inicio'] . '" max="' . $dt_letiva['dt_fim'] . '" '), null, 'date') ?>
                    </td>
                    <td>
<?= formErp::input('qt_aulas', 'Nº de aulas', @$planoAula['qt_aulas'], ' required ', null, 'number') ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <div class="row">
                            <div class="col">
                                Turmas:
                            </div>
                            <?php
                            if (count($turmas) > 1) {
                                foreach ($turmas as $k => $v) {
                                    if (!empty($turmasPlano)) {
                                        if (in_array($k, $turmasPlano)) {
                                            $post = $k;
                                        } else {
                                            $post = null;
                                        }
                                    } else {
                                        $post = $k;
                                    }
                                    ?>
                                    <div class="col" style="font-weight: bold">
                                    <?= formErp::checkbox('turmas[' . $k . ']', $k, $v, $post) ?>
                                    </div>
                                    <?php
                                }
                            } else {
                                foreach ($turmas as $k => $v) {
                                    ?>
                                    <div class="col" style="font-weight: bold">
                                    <?= formErp::hidden(['turmas[' . $k . ']' => $k]) . $v ?>
                                    </div>
        <?php
    }
}
?>
                        </div>
                    </td>
                </tr>
            </table>
<?php
if (empty($id_plano)) {
    ?>
                <div style="text-align: center; padding: 13px">
                    <button class=" btn btn-primary">
                        Continuar
                    </button>
                </div>
    <?php
} else {
    if (!empty($planoAula['devolutiva'])) {
        ?>
                    <div class="alert alert-warning" style="padding:  10px;">
                        <div class="row">
                            <div class="col" style="font-size: 20px">          
                                <strong>Devolutiva: </strong> <?= @$planoAula['devolutiva'] ?>
                            </div>
                        </div>
                    </div>
                    <?php }
                    ?>

                <div class="row">
    <?php
    if (!empty($habilidades['b'])) {
        foreach ($opt as $k => $v) {
            ?>
                            <div class="col" style=" text-align: center; font-weight: bold">
                                <label style="white-space: nowrap;" onclick="habilidades('<?= $k ?>')">
                                    <button style="margin: 10px" type="button" id="btn_<?= $k ?>" class="<?= $btn[$k] ?> rounded-button_min border"></button>
                            <?= $v ?>
                                </label>
                            </div>
                        <?php
                    }
                }
                ?>
                </div>
    <?php
    foreach ($opt as $k => $v) {
        ?>
                    <div style="display: <?= $diplay[$k] ?>" id="<?= $k ?>">
                        <div class="dropdown">
                            <button class="btn btn-outline-dark dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Habilidades - <?= $v ?>
                            </button>
        <?php
        if (!empty($habilidades[$k])) {
            ?>
                                <div style="height: 480px; width: 100%" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <div style="padding: 10px">
                                        <input class="form-control" id="myInput<?= $k ?>" type="search" placeholder="Pesquisa..">
                                    </div>
                                    <div style="height: 400px; overflow: auto; width: 100%">
                                        <table id="myTable<?= $k ?>">
            <?php
            foreach ($habilidades[$k] as $kh => $h) {
                ?>
                                                <tr>
                                                    <td style="padding: 3px">
                                                        <div class="alert alert-dark" onclick="habilidadeSet(<?= $kh ?>, 'i')" style="width: 98%; margin: auto; word-break: normal; cursor: pointer; text-align: justify">
                                                <?= $h ?>   
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
        } else {
            ?>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <div class="alert alert-dark" style="width: 98%; margin: auto;">
                                        Sem Cadastro de Habilidades
                                    </div>
                                </div>
                        <?php
                    }
                    ?>
                        </div>
                    </div>
        <?php
    }
    ?>
                <br />
                <div id="habPlan"></div>
                <br /><br />
                <div class="input-group">
                    <span class="input-group-text" style="display: block; width: 200px">
                        Descrição das Atividades<br />com Recursos Utilizados
                    </span>
                    <textarea class="form-control" name="recursos" ><?= @$planoAula['recursos'] ?></textarea>
                </div>
                <br /><br />
                <div class="input-group">
                    <span class="input-group-text" style="display: block; width: 200px">
                        Atividades de Estudo
                    </span>
                    <textarea class="form-control" name="metodologia" ><?= @$planoAula['metodologia'] ?></textarea>
                </div>
                <br /><br />
                <div class="input-group">
                    <span class="input-group-text" style="display: block; width: 200px">
                        Instrumentos<br />de Avaliação
                    </span>
                    <textarea class="form-control" name="avaliacao" ><?= @$planoAula['avaliacao'] ?></textarea>
                </div>
                <br /><br />
                <div class="input-group">
                    <span class="input-group-text" style="display: block; width: 200px">
                        Adaptação<br />Curricular
                    </span>
                    <textarea class="form-control" name="adapta_curriculo" ><?= @$planoAula['adapta_curriculo'] ?></textarea>
                </div>
                <br /><br />
                <div class="input-group">
                    <span class="input-group-text" style="display: block; width: 200px">
                        Reflexão <br />da Prática
                    </span>
                    <textarea class="form-control" name="reflexao" ><?= @$planoAula['reflexao'] ?></textarea>
                </div>
                <br /><br />
                <div style="text-align: center; padding: 13px">
                    <?= formErp::hidden(['id_plano' => $id_plano]) ?>
                    <input type="hidden" name="fk_id_projeto_status" id="id_status" value="">
                    <input type="hidden" name="coord_vizualizar" id="coord_vizualizar" value="">
                    <?php
                    $novamente = "";
                    if ($id_status <> 1) {
                        $novamente = "novamente";
                    }
                    if ($coord_vizualizar == 1 AND $id_status == 2) {
                        ?> 
                        <label style="font-weight: bold; text-align: center;font-size: 20px">Este projeto está em análise. Aguarde o retorno do Coordenador para realizar alterações. </label>
        <?php
    } else {
        if ($id_status <> 2) {
            ?>
                            <button class=" btn btn-outline-info" onclick="salvar(1)">
                                Apenas Salvar
                            </button>
                            <button class=" btn btn-outline-info" onclick="salvar(2)">
                                Salvar e enviar <?= $novamente ?> ao Coordenador
                            </button>
        <?php } else {
            ?>
                            <button class=" btn btn-outline-info" onclick="salvar(2)">
                                Apenas Salvar
                            </button>
                            <button class=" btn btn-outline-info" onclick="salvar(1)">
                                Salvar e voltar a situação para 'Não Enviado'
                            </button>
            <?php
        }
    }
    ?>



                </div>
                <?php
            }
            ?>

<?= formErp::hiddenToken('criaPlan') ?>
        </form>
        <form target="frame2" id="form2" action="<?= HOME_URI ?>/habili/def/aliPrevio.php" method="POST">
            <input type="hidden" name="id_hab" id="id_hab" value="" />
        </form>
</div>
<?php
if ($id_plano) {
    toolErp::modalInicio(null, null, 'mais');
    ?>
    <iframe name="frame2" style="width: 100%; height: 80vh; border: none"></iframe>
    <?php
    toolErp::modalFim();
    ?>
    <script>
        $(document).ready(function () {
            habilidadeSet(0, 0);
    <?php
    foreach ($opt as $k => $v) {
        ?>
                $("#myInput<?= $k ?>").on("keyup", function () {
                    var value = $(this).val().toLowerCase();
                    $("#myTable<?= $k ?> tr").filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
        <?php
    }
    ?>
        });
        function salvar(id_status) {
            document.getElementById("id_status").value = id_status;
            document.getElementById('salvar_plano').submit();
        }
        function habilidades(id) {
            document.getElementById('btn_b').classList.remove('btn-warning');
            document.getElementById('btn_a').classList.remove('btn-warning');
            document.getElementById('btn_b').classList.add('btn-outline-warning');
            document.getElementById('btn_a').classList.add('btn-outline-warning');
            document.getElementById('btn_' + id).classList.remove('btn-outline-warning');
            document.getElementById('btn_' + id).classList.add('btn-warning');
            document.getElementById('b').style.display = 'none';
            document.getElementById('a').style.display = 'none';
            document.getElementById(id).style.display = 'block';
        }
        function habilidadeSet(id, op) {
            if (op === 'del') {
                if (confirm("Excluir Habilidade?")) {
                    exec = true;
                    dados = 'id_plano=<?= $id_plano ?>&id_pah=' + id + '&op=' + op;
                } else {
                    exec = false;
                }
            } else {
                dados = 'id_plano=<?= $id_plano ?>&id_hab=' + id + '&op=' + op;
                exec = true;
            }
            if (exec) {
                // document.getElementById('load').style.display = "";
                fetch('<?= HOME_URI ?>/habili/def/habPlan.php', {
                    method: "POST",
                    body: dados,
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                })
                        .then(resp => resp.text())
                        .then(resp => {
                            document.getElementById('habPlan').innerHTML = resp;
                            document.getElementById('load').style.display = "none";
                        });
            }
        }
        function mais(id) {
            document.getElementById("id_hab").value = id;
            document.getElementById('form2').submit();
            $('#mais').modal('show');
            $('.form-class').val('');
        }
    </script>
    <?php
}
?>
<div id="load" style="position: fixed; top: 20%; left: 30%; display: none">
    <img src="<?= HOME_URI ?>/includes/images/loading.gif" alt="alt"/>
</div>
