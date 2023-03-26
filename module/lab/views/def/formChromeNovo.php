<?php
if (!defined('ABSPATH'))
    exit;

$id_ce = filter_input(INPUT_POST, 'id_ce', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$filtro = filter_input(INPUT_POST, 'filtro', FILTER_SANITIZE_STRING);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_ch = filter_input(INPUT_POST, 'id_ch', FILTER_SANITIZE_NUMBER_INT);
$devolucao = filter_input(INPUT_POST, 'devolucao', FILTER_SANITIZE_NUMBER_INT);
$modemSel = $model->modemSel($id_inst);
if (!empty($id_ce)) {
    $ch = sql::get(['lab_chrome_emprestimo', 'lab_chrome'], '*, lab_chrome_emprestimo.fk_id_pessoa fk_id_pessoa, lab_chrome_emprestimo.dt_fim dt_fim', ['id_ce' => $id_ce], 'fetch');
if(!empty($ch)){
    $id_ch = $ch['id_ch'];
    $id_pessoa = $ch['fk_id_pessoa'];
}
    $disabled = 'disabled';
} elseif (!empty($id_pessoa)) {
    $disabled = null;
} else {
    $disabled = null;
}

if (!empty($id_inst)) {
    $alunos = $model->alunoEscola($id_inst);
    $prof = $model->funcionarios($id_inst);
    $alunos = $alunos + $prof;
    $chromes = $model->chromesEscola($id_inst, $filtro);
    if (!empty($chromes)) {
        foreach ($chromes as $num => $carrinho) {
            foreach ($carrinho as $v) {
                if (empty($v['fk_id_pessoa']) || $v['fk_id_pessoa'] == @$ch['fk_id_pessoa']) {
                    $filtroList[$v['id_ch']] = $v['serial'] . (!empty($v['n_pessoa']) ? ' - ' . $v['n_pessoa'] . ' (' . $v['fk_id_pessoa'] . ')' : '');
                }
            }
        }
    }
}

if ((!empty($devolucao) && !empty($model->_id_hist)) || (!empty($ch) && empty($ch['fk_id_pessoa'])) || !empty($ch['dt_fim'])) {
    if (!empty($model->_id_hist)) {
        $id_hist = $model->_id_hist;
    } else {
        $id_hist = sql::get('lab_chrome_hist', 'id_hist', ['fk_id_ch' => $id_ch, '<' => 'id_hist'], 'fetch')['id_hist'];
    }
    ?>
    <div class="fieldTop">
        Devolução Cadastrada
    </div>
    <div style="text-align: center">
        <form target="_blank" action="<?= HOME_URI ?>/lab/protDevEsc" method="POST">
            <?=
            formErp::hidden(['id_hist' => $id_hist])
            ?>
            <button class="btn btn-success" type="submit">
                Imprimir Protocolo de Devolução
            </button>
        </form>
    </div>
    <?php
} elseif (!empty($alunos) && !empty($filtroList) && empty ($ch['dt_fim'])) {
    ?>
    <div class="body">
        <div class="fieldTop">
            Cadastro de ChromeBook
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::select('id_pessoa', $alunos, 'Aluno/Funcionário', @$id_pessoa, 1, ['id_inst' => $id_inst], $disabled) ?>
            </div>
        </div>
        <br />
        <?php
        if ($id_pessoa) {
            $sql = "SELECT c.serial, i.n_inst, e.dt_inicio FROM `lab_chrome_emprestimo` e "
                    . " join lab_chrome c on c.id_ch = e.fk_id_ch "
                    . " join instancia i on i.id_inst = c.fk_id_inst "
                    . " WHERE e.`fk_id_pessoa` = $id_pessoa AND e.`dt_fim` IS NULL";
            $query = pdoSis::getInstance()->query($sql);
            $temChrome = $query->fetch(PDO::FETCH_ASSOC);
            if ($temChrome) {
                ?>
                <div class="alert alert-danger text-justify">
                    <?= $alunos[$id_pessoa] ?> emprestou o chromebook N/S <?= $temChrome['serial'] ?> retirado na <?= $temChrome['n_inst'] ?> no dia <?= data::porExtenso($temChrome['dt_inicio']) ?>
                </div>
                <?php
            } else {
                ?>
                <form target="_parent" action="<?= HOME_URI ?>/lab/emprestimo" method="POST">
                    <div class="row">
                        <div class="col">
                            <?= formErp::select('1[id_ch]', $filtroList, 'Chromebook', @$ch['id_ch'], null, null, $disabled . ' required ') ?>
                        </div>
                    </div>
                    <br />
                    <?php
                    if ($modemSel) {
                        ?>
                        <div class="row">
                            <div class="col">
                                <?= formErp::select('1[fk_id_modem]', $modemSel, ['Modem', 'Sem Modem'], @$ch['fk_id_modem']) ?>
                            </div>
                        </div>
                        <br />
                        <?php
                    }
                    ?>
                    <div class="row">
                        <div class="col">
                            <?= formErp::input('1[dt_inicio]', 'Início', (empty($ch['dt_inicio']) ? date("Y-m-d") : $ch['dt_inicio']), $disabled . ' required ', null, 'date') ?>
                        </div>
                        <div class="col">

                        </div>
                    </div>
                    <br /><br />
                    <div style="text-align: center">
                        <?=
                        formErp::hidden([
                            'id_inst' => $id_inst,
                            '1[fk_id_pessoa_lanc]' => toolErp::id_pessoa(),
                            '1[fk_id_cd]' => 3,
                            '1[fk_id_cs]' => 3,
                            '1[fk_id_pessoa]' => @$id_pessoa,
                            '1[fk_id_inst]' => @$id_inst
                        ])
                        . formErp::hiddenToken('emprestaSalva')
                        ?>
                        <br /><br />
                        <?php
                        if (empty($id_ce)) {
                            ?>
                            <button type="submit" class=" btn btn-success">
                                Salvar
                            </button>
                            <?php
                        }
                        ?>
                    </div>
                </form>
                <?php
            }
        }
        if (!empty($id_ce)) {
            ?>
            <br /><br />
            <div style="text-align: center">
                <button onclick="$('#myModal').modal('show');$('.form-class').val('')" class="btn btn-info">
                    Devolução do Chromebbok
                </button>
            </div>
            <?php
        }
        if (!empty($id_pessoa)) {
            $hist = $model->historico($id_pessoa);
        }
        ?>
    </div>
    <?php
    toolErp::modalInicio();
    ?>
    <form method="POST">
        <div class="fieldTop">
            Encerrar processo de Empréstimo
        </div>
        <p>
            <?= formErp::checkbox('1[carregador]', 1, 'Entregou o Carregador', 1) ?>
        </p>
        <?php
        if (!empty($ch['fk_id_modem'])) {
            ?>
            <p>
                <?= formErp::checkbox('modemDevol', 1, 'Entregou o modem ' . $modemSel[$ch['fk_id_modem']], 1) ?>
            </p>
            <?php
        }
        ?>
        <?= formErp::textarea('obs', null, 'Observações') ?>
        <br /><br />
        <div style="text-align: center">
            <?=
            formErp::hidden([
                'id_inst' => $id_inst,
                '1[fk_id_inst]' => $id_inst,
                '1[fk_id_pessoa_lanc]' => toolErp::id_pessoa(),
                '1[fk_id_cd]' => 1,
                'dt_inicio' => $ch['dt_inicio'],
                'dt_fim' => $ch['dt_fim'],
                '1[fk_id_modem]' => $ch['fk_id_modem'],
                '1[fk_id_cs]' => 1,
                '1[fk_id_pessoa]' => null,
                'id_pessoa' => $ch['fk_id_pessoa'],
                '1[email_google]' => null,
                '1[id_ch]' => @$ch['id_ch'],
                'devolucao' => 1,
                'id_ce' => $id_ce
            ])
            . formErp::button('Enviar')
            . formErp::hiddenToken('finalEmprestimo')
            ?>
        </div>
        <br /><br />
    </form>
    <?php
    toolErp::modalFim();
} else {
    ?>
    <div class="alert alert-danger">
        Não há alunos ou Chromebooks nesta instância
    </div>
    <?php
}
?>