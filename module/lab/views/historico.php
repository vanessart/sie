<?php
if (!defined('ABSPATH'))
    exit;

$id_ch = filter_input(INPUT_POST, 'id_ch', FILTER_SANITIZE_NUMBER_INT);
$pesq = filter_input(INPUT_POST, 'pesq', FILTER_SANITIZE_NUMBER_INT);
$pessoa = filter_input(INPUT_POST, 'pessoa', FILTER_SANITIZE_STRING);
$chromes = $model->chromeRede(['fk_id_cd' == 5, 'limit' => 0, 'tuplas' => 100000], ' id_ch, serial as n_seria ');
$chromes = toolErp::idName($chromes);
?>
<div class="body">
    <div class="fieldTop">
        Histórico
    </div>
    <form method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::input('pessoa', 'Nome, E-mail, CPF ou Matrícula', $pessoa) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::select('id_ch', $chromes, 'Chromebook', $id_ch) ?>
            </div>
            <div class="col">
                <button onclick="document.getElementById('limpar').submit()" type="button" class="btn btn-warning">
                    Limpar
                </button>
            </div>
            <div class="col">
                <?= formErp::hidden(['pesq' => 1]) ?>
                <button class="btn btn-info">
                    Pesquisar
                </button>
            </div>
        </div>
        <br />
    </form>
    <form id="limpar">
    </form>
    <br /><br />
    <?php
    if (!empty($pesq)) {
        $list = $model->histList($pessoa, $id_ch);
        if (empty($list)) {
            ?>
            <div class="alert alert-danger">
                Não há histório
            </div>
            <?php
        } else {
            foreach ($list as $k => $v) {
                $list[$k]['dt'] = data::converteBr($v['time_stamp']);
                $list[$k]['nome'] = '<p>' . $v['n_pessoa'] . '</p><br /><p>CFF: ' . $v['cpf'] . ' - Matr.: ' . $v['rm'] . '</p>';
                if ($v['devolucao'] == 1) {
                    $list[$k]['protRh'] = formErp::submit('Protocolo RH', null, ['id_pessoa' => $v['id_pessoa'], 'sitDev' => 1], HOME_URI . '/lab/protDevRh', 1);
                    $list[$k]['protdev'] = formErp::submit('Protocolo Devolução', null, ['id_hist' => $v['id_hist']], HOME_URI . '/lab/protDevProf', 1);
                }
            }
            $form['array'] = $list;
            $form['fields'] = [
                'Data' => 'dt',
                'Nome/CPF/Matrícula' => 'nome',
                'Observação' => 'obs',
                '||1' => 'protRh',
                '||2' => 'protdev'
            ];
            report::simple($form);
        }
    }
    ?>
</div>
