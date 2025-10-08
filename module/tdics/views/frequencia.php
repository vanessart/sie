<?php
if (!defined('ABSPATH'))
    exit;
if (toolErp::id_nilvel() == 8) {
    $id_inst_sieb = toolErp::id_inst();
} else {
    $id_inst_sieb = filter_input(INPUT_POST, 'id_inst_sieb', FILTER_SANITIZE_NUMBER_INT);
}
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$periodo = filter_input(INPUT_POST, 'periodo');
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$frequencia = filter_input(INPUT_POST, 'frequencia', FILTER_SANITIZE_NUMBER_INT);
$buscar = filter_input(INPUT_POST, 'buscar', FILTER_SANITIZE_NUMBER_INT);
$dataIni = filter_input(INPUT_POST, 'dataIni');
$dataFim = filter_input(INPUT_POST, 'dataFim');
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
if (empty($id_pl)) {
    $id_pl = $model->pl();
}
$escolas = $model->escolaTdics($id_pl);
$pls = $model->periodoLetivos();
$polos = sql::idNome($model::$sistema . '_polo');
if (!empty($buscar)) {
    $dados = $model->relatFerq($id_polo, $id_inst_sieb, $periodo, $id_curso, $frequencia, null, $dataIni, $dataFim, $id_pl);
    if ($dados) {
        $id_polo = $dados['geral']['id_polo'];
        if (!empty($dados['alunos'])) {
            $form['array'] = $dados['alunos'];
            $form['fields'] = [
                'Matrícula' => 'id_pessoa',
                'Aluno' => 'n_pessoa',
                'Escola' => 'n_inst',
                'Turma' => 'n_turma',
                'Ciclo' => 'n_mc',
                'Frequencia' => 'frenq',
                'Telefones' => 'tel'
            ];
        }
    }
}
?>
<div class="body">
    <br />
    <div class="row">
        <div class="col-9" style="font-weight: bold; font-size: 1.2em;text-align: center">
            Controle de Frequência
        </div>
        <div class="col-3">
            <?php
            if (!empty($dados['alunos'])) {
                ?>
                <form target="_blank" action="<?= HOME_URI ?>/<?= $this->controller_name ?>/pdf/freqPlan" method="POST">
                    <?=
                    formErp::hidden([
                        'id_inst_sieb' => $id_inst_sieb,
                        'id_polo' => $id_polo,
                        'periodo' => $periodo,
                        'dataIni' => $dataIni,
                        'dataFim' => $dataFim,
                        'id_curso' => $id_curso,
                        'frequencia' => $frequencia,
                        'id_pl' => $id_pl,
                    ])
                    . formErp::button('Exportar')
                    ?>
                </form>
                <?php
            }
            ?>
        </div>
    </div>
    <br />
    <form method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::select('id_pl', $pls, 'Período Letivo', $id_pl) ?>
            </div>
            <div class="col">
                <?= formErp::select('id_polo', $polos, 'Núcleo', $id_polo) ?>
            </div>
            <div class="col">
                <?php
                if (toolErp::id_nilvel() != 8) {
                    echo formErp::select('id_inst_sieb', $escolas, 'Escola', $id_inst_sieb);
                }
                ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('periodo', ['M' => 'Manhã', 'T' => 'Tarde'], 'Período', $periodo) ?>
            </div>
            <div class="col">
                <?= formErp::selectDB($model::$sistema . '_curso', 'id_curso', 'Curso', $id_curso) ?>
            </div>
            <div class="col">
                <?php
                foreach (range(5, 9) as $v) {
                    $fl[$v] = $v . '0 ou mais';
                }
                echo formErp::select('frequencia', $fl, 'Frequência', $frequencia);
                ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md">
                <?= formErp::input('dataIni', 'Data Inicial', $dataIni, NULL, NULL, 'date') ?>
            </div>
            <div class="col-md">
                <?= formErp::input('dataFim', 'Data Final', $dataFim, NULL, NULL, 'date') ?>
            </div>
            <div class="col-md">
                <button name="buscar" value="1" class="btn btn-success">
                    Buscar
                </button>
            </div>
        </div>
    </form>
    <div class="mt-2">
        <?php
        if (!empty($form)) {
            report::simple($form);
        }
        ?>
    </div>
</div>
