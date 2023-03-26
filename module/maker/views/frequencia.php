<?php
if (!defined('ABSPATH'))
    exit;

$id_inst_sieb = filter_input(INPUT_POST, 'id_inst_sieb', FILTER_SANITIZE_NUMBER_INT);
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$periodo = filter_input(INPUT_POST, 'periodo');
$id_mc = filter_input(INPUT_POST, 'id_mc', FILTER_SANITIZE_NUMBER_INT);
$transporte = filter_input(INPUT_POST, 'transporte');
$frequencia = filter_input(INPUT_POST, 'frequencia', FILTER_SANITIZE_NUMBER_INT);
$buscar = filter_input(INPUT_POST, 'buscar', FILTER_SANITIZE_NUMBER_INT);
$escolas = $model->escolasMaker();
$polos = sql::idNome('maker_polo');
if (!empty($buscar)) {
    $dados = $model->relatFerq($id_polo, $id_inst_sieb, $periodo, $id_mc, $transporte, $frequencia);
    if ($dados) {
        $id_polo = $dados['geral']['id_polo'];
        if (!empty($dados['alunos'])) {
            $form['array'] = $dados['alunos'];
            $form['fields'] = [
                'RSE' => 'id_pessoa',
                'Aluno' => 'n_pessoa',
                'Escola' => 'n_inst',
                'Turma' => 'n_turma',
                'Ciclo' => 'n_mc',
                'Transporte' => 'transporte',
                'Frequencia' => 'frenq'
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
            <form target="_blank" action="<?= HOME_URI ?>/maker/pdf/freqPlan" method="POST">
                    <?=
                    formErp::hidden([
                        'id_inst_sieb' => $id_inst_sieb,
                        'id_polo' => $id_polo,
                        'periodo' => $periodo,
                        'id_mc' => $id_mc,
                        'transporte' => $transporte,
                        'frequencia' => $frequencia
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
                <?= formErp::select('id_polo', $polos, 'Polo', $id_polo) ?>
            </div>
            <div class="col">
                <?= formErp::select('id_inst_sieb', $escolas, 'Escola', $id_inst_sieb) ?>
            </div>
            <div class="col">
                <?= formErp::select('transporte', ['s' => 'Sim', 'n' => 'Não'], 'Transporte', $transporte) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('periodo', ['M' => 'Manhã', 'T' => 'Tarde'], 'Período', $periodo) ?>
            </div>
            <div class="col">
                <?= formErp::selectDB('maker_ciclo', 'id_mc', 'Ciclo', $id_mc) ?>
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
        <div style="text-align: center; padding: 20px">
            <button name="buscar" value="1" class="btn btn-success">
                Buscar
            </button>
        </div>
    </form>
    <div>
        <?php
        if (!empty($form)) {
            report::simple($form);
        }
        ?>
    </div>
</div>
