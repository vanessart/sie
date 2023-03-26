<?php
if (!defined('ABSPATH'))
    exit;
$id_plano = filter_input(INPUT_POST, 'id_plano', FILTER_SANITIZE_NUMBER_INT);
if ($model->db->tokenCheck('criaPlan')) {
    $id_plano = criaPlan($model);
}

function criaPlan($model) {
    extract($_POST);
    if (empty($dt_inicio) || empty($dt_fim) || empty($qt_aulas)) {
        toolErp::alertModal("Preencha todos os campos");
        return;
    }
    if ($qt_aulas < 0) {
        $qt_aulas *= (-1);
    }
    $ins = [
        'fk_id_pessoa' => $id_pessoa,
        'fk_id_inst' => $id_inst,
        'fk_id_ciclo' => $id_ciclo,
        'iddisc' => $id_disc,
        'fk_id_pl' => $id_pl,
        'atualLetiva' => $atual_letiva,
        'qt_aulas' => ($qt_aulas),
        'dt_inicio' => $dt_inicio,
        'dt_fim' => $dt_fim,
    ];
    $id = $model->db->insert('coord_plano_aula', $ins);
    $turmasSet['fk_id_plano'] = $id;
    foreach ($turmas as $v) {
        if ($v) {
            $turmasSet['fk_id_turma'] = $v;
            $model->db->insert('coord_plano_aula_turmas', $turmasSet, 1);
        }
    }
    
    return $id;
}

if ($id_plano) {
    $planoAula = sql::get('coord_plano_aula', '*', ['id_plano' => $id_plano], 'fetch');
    $turmas = array_column(sql::get('coord_plano_aula_turmas', 'fk_id_turma', ['fk_id_plano' => $id_plano]), 'fk_id_turma');
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
?>
<script>
    $(document).ready(function () {
        $("#myInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
<div class="fieldTop">
    <?= $cde['n_inst'] ?>
</div>
<?php
if (empty($id_plano)) {
    ?>
    <form method="POST">
        <?=
        formErp::hidden($cde)
        . formErp::hidden([
            'atualLetivaSet' => $atualLetivaSet,
            'id_pessoa' => $id_pessoa,
            'plano' => 1
        ])
        ?>
        <table class="table table-bordered table-hover table-striped">
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
            <tr>
                <td>
                    <?= formErp::input('dt_inicio', 'Data de Início', @$planoAula['dt_inicio'], ' required ', null, 'date') ?>
                </td>
                <td>
                    <?= formErp::input('dt_fim', 'Data de Final', @$planoAula['dt_fim'], ' required ', null, 'date') ?>
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
                                ?>
                                <div class="col">
                                    <?= formErp::checkbox('turmas[' . $k . ']', $k, $v) ?>
                                </div>
                                <?php
                            }
                        } else {
                            foreach ($turmas as $k => $v) {
                                ?>
                                <div class="col">
                                    <?= formErp::hidden(['turmas[' . $k . ']' => $k]) . $v ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center">
                    <?= formErp::hiddenToken('criaPlan') ?>
                    <button class=" btn btn-primary">
                        Continuar
                    </button>
                </td>
            </tr>
        </table>
    </form>
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
                    <div style="height: 400px; overflow: auto; width: 100%" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <input class="form-control" id="myInput" type="search" placeholder="Pesquisa..">
                        <br />
                        <table id="myTable">
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


    <div style="text-align: center; padding: 20px">

    </div>
    <?php
}
?>
<script>
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
        alert(id)
    }
</script>
<?php
##################            
?>
<pre>   
    <?php
    print_r($_REQUEST);
    ?>
</pre>
<?php
###################
?>