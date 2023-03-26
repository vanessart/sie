<?php
if (!defined('ABSPATH')) {
    exit;
}

$atual_letiva = filter_input(INPUT_POST, 'atual_letiva', FILTER_SANITIZE_NUMBER_INT);
$hlo = filter_input(INPUT_POST, 'hlo', FILTER_SANITIZE_NUMBER_INT);
$hlo = empty($hlo) ? 1 : $hlo;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);;
$nome_turma = filter_input(INPUT_POST, 'nome_turma', FILTER_SANITIZE_STRING);
$nome_disc = filter_input(INPUT_POST, 'nome_disc', FILTER_SANITIZE_STRING);
$id_pessoa = toolErp::id_pessoa();
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$id_ciclo = filter_input(INPUT_POST, 'id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_STRING);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_STRING);
$escola = filter_input(INPUT_POST, 'escola', FILTER_SANITIZE_STRING);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$data = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING);
$aulas = filter_input(INPUT_POST, 'aulas', FILTER_SANITIZE_NUMBER_INT);
if (empty($id_turma) || empty($id_pl) || empty($id_disc)) {
    ?>
    <script>
        window.location.href = "<?= HOME_URI ?>/profe";
    </script>
    <?php
} else {
    $dataFiltro = $model->dataFiltro($data, $id_curso, $id_pl, $atual_letiva);
    $set = sqlErp::get('ge_setup', '*', null, 'fetch');
    if (!empty($set['lanc_bim'])) {
        $bims_ = explode(',', $set['lanc_bim']);
        foreach ($bims_ as $v) {
            $bims[$v] = $v . 'º ' . $dataFiltro['un_letiva'];
        }
    } else {
        $bims = null;
    }
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
        'aulas' => $aulas,
        'id_inst'=>$id_inst
    ];
}

$sondagem = $model->sondagens($id_pl, $id_curso);
if ($sondagem) {
    $sondaQuant = $sondagem['quant'];
} else {
    $sondaQuant = 0;
}
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
        <div style="width: 300px; margin: 0 auto" >
            <?php
            if (!empty($dataFiltro['atual_letiva'])) {
                ?>
                <table>
                    <tr>
                        <td>
                            <?php
                            if ($bims) {
                                echo formErp::select('atual_letiva', $bims, null, $dataFiltro['atual_letiva'], 1, ($hidden + ['hlo' => $hlo] + ['data' => $data]));
                            } else {
                                ?>
                                <div style="padding-right: 20px; margin-top: -14px; font-weight: bold;white-space: nowrap">
                                    <?= $dataFiltro['atual_letiva'] . 'º ' . $dataFiltro['un_letiva'] ?>
                                </div>
                                <?php
                            }
                            ?>
                        </td>
                        <td>
                            <form method="post" id="form_geral">
                                <?=
                                formErp::input("data", null, $data, ' id="calendario" onchange="Calendario()" ', null, 'date')
                                . formErp::hidden($hidden + ['hlo' => $hlo] + ['atual_letiva' => $atual_letiva])
                                ?>
                            </form>
                        </td>
                    </tr>
                </table>
                <?php
//                   echo formErp::input("data", $dataFiltro['atual_letiva'] . "º " . $dataFiltro['un_letiva'], $data, ' id="calendario" onchange="Calendario()" ', null, 'date')
                //. formErp::hidden($hidden + ['hlo' => $hlo]);
            }
            ?>
        </div>
    </div>
    <br />
    <?php
    $hidden['atual_letiva'] = $atual_letiva;
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
                    if ($id_disc == 'nc') {
                        $totalInstrumentos = 60;
                    } else {
                        $totalInstrumentos = 10;
                    }
                    if (count($instrumentos) < $totalInstrumentos) {
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
                                        'data' => $data
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
                $options = [1 => 'Chamada', 2 => 'Habilidades', 3 => 'Instrumentos de avaliação', 4 => 'Ver Plano de Aula'];
                echo formErp::dropDownList('hlo', $options, null, $hlo, 1, $hidden + ['data' => $data])
                ?>
            </div>
        </div>
        <?php
    } else {
        ?>

        <div class="row" style="width: 98%; margin: auto">
            <div class="col"></div>
            <div class="col">

                <?php
                if ($sondaQuant > 0) {
                    $options = [1 => 'Chamada', 5 => 'Ocorrências', 6 => 'Pauta de Observação (por Aluno)', 7 => 'Pauta de Observação (por Habilidade)'];
                } else {
                    $options = [1 => 'Chamada', 5 => 'Ocorrências'];
                }
                echo formErp::hidden([
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
                ]);
                echo formErp::dropDownList('hlo', $options, null, $hlo, 1, $hidden + ['data' => $data]);
                ?>
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


