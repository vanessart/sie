<?php
if (!defined('ABSPATH')) {
    exit;
}
$sql = "select t.n_turma, ci.fk_id_curso, ci.id_ciclo, i.n_inst, t.fk_id_pl, ci.aulas from ge_turmas t "
        . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
        . " join instancia i on i.id_inst = t.fk_id_inst "
        . " where t.id_turma = " . $dados['fk_id_turma'];
$query = pdoSis::getInstance()->query($sql);
$extra = $query->fetch(PDO::FETCH_ASSOC);
$hlo = filter_input(INPUT_POST, 'hlo', FILTER_SANITIZE_NUMBER_INT);
$hlo = empty($hlo) ? 1 : $hlo;
$id_inst = $dados['fk_id_inst'];
$nome_turma = $extra['n_turma'];
$nome_disc = $dados['iddisc'] == 'nc' ? 'Núcleo Comum' : sql::get('ge_disciplinas', 'n_disc', ['id_disc' => $dados['iddisc']], 'fetch')['n_disc'];
$id_pessoa = 3;
$id_curso = $extra['fk_id_curso'];
$id_ciclo = $extra['id_ciclo'];
$id_turma = $dados['fk_id_turma'];
$id_disc = $dados['iddisc'];
$escola = $extra['n_inst'];
$id_pl = $extra['fk_id_pl'];
$dataDB = $dados['dt_dt'];
$aulas = $extra['aulas'];
$dataFiltro = $model->dataFiltro($dataDB, $id_curso, $id_pl);
$data = $dataFiltro['data'];
if (empty($dataFiltro['erro'])) {
    $diaDaSemana = date('N', strtotime($data));
    $aulasPorDia = $model->aulasPorDia($diaDaSemana, $id_disc, $id_turma);
    if (empty(current($aulasPorDia))) {
        $erro = "Não há aulas cadastradas para esta turma neste dia da semana.";
    }
} else {
    $aulasPorDia = [];
    $erro = $dataFiltro['erro'];
}
$alunos = ng_escola::alunoPorTurma($id_turma);
$model->calendarioJS();
$hidden = [
    'nome_turma' => $nome_turma,
    'nome_disc' => $nome_disc,
    'id_turma' => $id_turma,
    'id_disc' => $id_disc,
    'id_pl' => $id_pl,
    'escola' => $escola,
    'id_curso' => $id_curso,
    'id_ciclo' => $id_ciclo,
    'aulas' => $aulas
];
?>

<!-- CSS -->

<style>
    .rounded-button{
        height: 40px;
        width: 40px;
        border-radius: 50%;
        margin-left: 5px;
    }

    .rounded-button_min{
        height: 30px;
        width: 30px;
        border-radius: 50%;
    }

    .nome_aluno{
        padding-top: 10px;
        padding-left: 10px;
        font-size: 1.2em;
        font-weight: bold;
    }

    .lbl_transf{
        color:white;
        font-weight:bold;
        margin-left:80px;
    }

    .calendario{
        margin-right: 50px;
        margin-left:170px;
    }

    .num_chamada{
        display:block;
        height: 50px;
        width: 50px;
        border-radius: 50%;
        border: 1px solid black;
        background:darkblue;
        color:white;
        font-weight: bold;

    }

    .border-aluno{
        margin: 20px;
    }

    #semAula{
        color:white;
        font-size:20px;
        margin-left:590px;
    }
    #border-info{
        margin-top:100px;
    }
    #borderSemAula{
        margin:63px; 
        background:red;
    }
    .geral{
        margin: 0 auto;
        max-width:800px;
    }




</style>

<div class="geral">
    <div class="border"  >
        <div class="text-center">
            <label ><?= $escola ?></label>
        </div>
        <div class="text-center">
            <label ><?= $nome_turma ?> - <?= $nome_disc ?></label>
        </div>
        <br />
        <div style="width: 300px; margin: 0 auto" >
            <?php
            if (!empty($dataFiltro['atual_letiva'])) {
                echo formErp::input(null, $dataFiltro['atual_letiva'] . "º " . $dataFiltro['un_letiva'], data::converteBr($dataDB), ' readonly ')
                . formErp::hidden($hidden + ['hlo' => $hlo]);
            }
            ?>
        </div>
    </div>
    <br />
    <?php
    if ($id_disc != 27) {
        ?>
        <div class="row" style="width: 98%; margin: auto">
            <div class="col">
                <?php
                if ($hlo == 1) {
                    ?>
                    <button class="btn btn-success border" id="presence-button" value="P" style="width: 100%">
                        Presença para Todos
                    </button>
                    <?php
                } elseif ($hlo == 3) {
                    $instrumentos = $model->retornaInstrumentosAvaliativos($id_pl, $id_turma, $dataFiltro['atual_letiva'], $id_disc);
                    if (count($instrumentos) < 10) {
                        ?>
                        <div class="row">
                            <form action="<?= HOME_URI ?>/profe/def/instrumentoAvaliativo.php" target="instrumentosAva" method="post">
                                <div class="col-2">

                                    <button type="submit" id="c_instrumento" class="btn btn-success border" style="margin-bottom:20px; width:200px">Cadastrar instrumento</button>
                                    <?=
                                    formErp::hidden([
                                        "id_pl" => $id_pl,
                                        "id_ciclo" => $id_ciclo,
                                        "id_pessoa" => $id_pessoa,
                                        "id_turma" => $id_turma,
                                        "id_inst" => $id_inst,
                                        'atual_letiva' => $dataFiltro['atual_letiva'],
                                        'id_curso' => $id_curso,
                                        'id_disc' => $id_disc,
                                        "nome_disc" => $nome_disc,
                                        "nome_turma" => $nome_turma,
                                        "escola" => $escola,
                                        'origem'=>'/profe/cadam?token='.$token
                                    ])
                                    ?>
                                </div>
                            </form>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <div class="col">
                <?php
                if (empty(current($aulasPorDia))) {
                    $options = [1 => 'Chamada', 3 => 'Instrumentos de avaliação'];
                } else {
                    $options = [1 => 'Chamada', 2 => 'Habilidades', 3 => 'Instrumentos de avaliação'];
                }
                ?>
                <?= formErp::dropDownList('hlo', $options, null, $hlo, 1, $hidden + ['data' => $data]) ?>
            </div>
        </div>
        <?php
    }
    include ABSPATH . "/module/profe/views/_chamada/$hlo.php";
    ?>
</div>

<script>
    hlo_.classList.add("border");
    $('[type="date"]').on("keydown", function () {
        event.preventDefault();
        return false;
    });
</script>


