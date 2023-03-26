<style>
    textArea{
        height: 400px;
    }

    p button.btn.btn-primary, p button.btn.btn-danger {
        display: none;
    }

    .formataTexto td{
        white-space: pre-line;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
$sistema = $model->getSistema('22','48,2,18,53,54,55,24,56');
$atualLetivaSet = filter_input(INPUT_POST, 'atualLetivaSet', FILTER_SANITIZE_NUMBER_INT);
$activeNav = filter_input(INPUT_POST, 'activeNav', FILTER_SANITIZE_STRING);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$atualLetivaSet = filter_input(INPUT_POST, 'atualLetivaSet', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_STRING);
$id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$voltar = filter_input(INPUT_POST, 'voltar', FILTER_SANITIZE_NUMBER_INT);
$un_letiva = filter_input(INPUT_POST, 'un_letiva', FILTER_SANITIZE_STRING);
$id_plano = $model->id_plano;

if (empty($id_disc) OR empty($id_ciclo)) {
    ?>
    <script>
        window.location.href = "<?= HOME_URI ?>/<?= $sistema ?>/planoTurma";
    </script>
    <?php
    exit();
}

$n_disc = $model->getDisc($id_disc);
$n_ciclo = $model->getCiclo($id_ciclo);
if ($voltar == 1) {
    $pagina = "planoAula";
} else {
    $pagina = "planoTurma";
}

$n_turmas = array_column($model->getTurmaPlano($id_plano), 'n_turma');
$turmas = toolErp::virgulaE($n_turmas);

$cde = [
    'id_disc' => $id_disc,
    'n_disc' => $n_disc,
    'n_ciclo' => $n_ciclo,
    'id_ciclo' => $id_ciclo,
    'id_inst' => $id_inst,
    'un_letiva' => $un_letiva,
    'id_curso' => $id_curso
];

if ($id_plano) {
    $planoAula = sql::get('coord_plano_aula', '*', ['id_plano' => $id_plano], 'fetch');
    $turmasPlano = array_column(sql::get('coord_plano_aula_turmas', 'fk_id_turma', ['fk_id_plano' => $id_plano]), 'fk_id_turma');
    $status = $model->getStatusProjeto($planoAula["fk_id_projeto_status"]);
    $id_status = $planoAula['fk_id_projeto_status'];
    $coord_vizualizar = $planoAula['coord_vizualizar'];
}

if (toolErp::id_nilvel() == 48 && empty($coord_vizualizar) && $id_status == 2) {
    $visualizou['coord_vizualizar'] = 1;
    $visualizou['id_plano'] = $id_plano;
    $model->db->ireplace('coord_plano_aula', $visualizou, 1);
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
<div class="body">
    <div class="alert alert-info" style="padding-bottom: 0">
        <div class="row">
            <div class="col-10" style="font-weight: bold; text-align: center;font-size: 20px">
                <label style=" font-size: 24px">
                    Plano de Aula
                </label>
            </div>
            <div class="col-2" style="text-align: right;">
                <form action="<?= HOME_URI ?>/<?= $sistema ?>/<?= $pagina ?>" method="POST">
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


    <table class="table table-bordered table-hover table-striped">
        <tr>
            <td>
                Turmas: <?= $turmas ?>
            </td>
            <td>
                Disciplina:  <?= $cde['n_disc'] ?>
            </td>
            <td>
                Unidade Letiva: <?= $atualLetivaSet ?>º <?= $cde['un_letiva'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Período: De <?= data::converteBr($planoAula['dt_inicio']) ?> a <?= data::converteBr($planoAula['dt_fim']) ?>
            </td>
            <td>
                <?= $planoAula['qt_aulas'] ?> aulas
            </td>
            <td>
                Situação: <?= @$status ?> 
            </td>
        </tr>
    </table>
    <br />
    <div id="habPlan"></div>
    <br />
    <table class="table table-bordered table-hover table-striped formataTexto">
        <tr>
            <td style="width: 17%">
                Descrição das Atividades com Recursos Utilizados
            </td>
            <td>
                <?= @$planoAula['recursos'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Atividades de Estudo
            </td>
            <td>
                <?= @$planoAula['metodologia'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Instrumentos de Avaliação
            </td>
            <td>
                <?= @$planoAula['avaliacao'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Adaptação Curricular
            </td>
            <td>
                <?= @$planoAula['adapta_curriculo'] ?>
            </td>
        </tr>
        <tr>
            <td>
                Reflexão da Prática
            </td>
            <td>
                <?= @$planoAula['reflexao'] ?>
            </td>
        </tr>
    </table>
    <br /><br />
    <?php  
    if (toolErp::id_nilvel()<>22) {?>
        <form name="salvar_plano" action="<?= HOME_URI ?>/profe/planoTurma" method="POST">
            <input type="hidden" name="1[fk_id_projeto_status]" id="id_status" value="">
            <input type="hidden" name="1[coord_vizualizar]" id="coord_vizualizar" value=""> 
            <?=
            formErp::hidden($cde)
            . formErp::hidden([
                'id_pessoa' => $id_pessoa,
                'atualLetivaSet' => $atualLetivaSet,
                '1[id_plano]' => $id_plano
            ])
            ?>

            <div class="input-group">
                <span class="input-group-text" style="display: block; width: 200px">
                    Devolutiva
                </span>
                <textarea class="form-control" name="1[devolutiva]" ><?= @$planoAula['devolutiva'] ?></textarea>
            </div>
            <br /><br />
            <?= formErp::hiddenToken('coord_plano_aula', 'ireplace') ?>
            <div style="text-align: center; padding: 13px">
                <?php if ($id_status == 2 && toolErp::id_nilvel() != 18 && toolErp::id_nilvel() != 2) { ?>
                    <button class=" btn btn-outline-info" onclick="salvar(2, 1)">
                        Apenas Salvar
                    </button>
                    <button class=" btn btn-outline-info" onclick="salvar(3, 0)">
                        Salvar e Aprovar
                    </button>
                    <button class=" btn btn-outline-info" onclick="salvar(4, 0)">
                        Salvar e Devolver ao Professor
                    </button>
                <?php } ?>    
            </div>
        </form>
        <?php
    } ?>
</div>
<?php
if ($id_plano) {
    ?>

    <script>
        $(document).ready(function () {
            habilidadeSet(0, 0);
        });

        function salvar(id_status, vizualizar) {
            document.getElementById("id_status").value = id_status;
            document.getElementById("coord_vizualizar").value = vizualizar;
            document.getElementById('salvar_plano').submit();
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
    </script>
    <?php
}
?>
<div id="load" style="position: fixed; top: 20%; left: 30%; display: none">
    <img src="<?= HOME_URI ?>/includes/images/loading.gif" alt="alt"/>
</div>
