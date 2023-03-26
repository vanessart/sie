<?php
if (!defined('ABSPATH'))
    exit;

$result = $model->getRelatorios($relatorioName, @$_REQUEST[1]);
$fields = [];
$name = '';
switch ($relatorioName) {
    case 'relatorioSemAgenda':
        $name = 'Relatorio para saber quem nao fez agenda';
        break;
    case 'relatorioJustificativaPorAusencia':
        $name = 'Cruzamento para validar visitas versus justificativa de ausencia';
        break;
    case 'relatorioPoucasVisitas':
        $name = 'Escolas menos visitadas';
        $fields = [
            'ID Instituição' => 'fk_id_inst',
            'Nome Instituição' => 'n_inst',
            'Rede' => 'rede',
            'Total' => 'total',
        ];
        break;
    case 'relatorioDepartamentosMaisRequisitados':
        $name = 'Departamentos mais requisitados';
        $fields = [
            'ID Departamento' => 'fk_id_area',
            'Departamento' => 'n_area',
            'Total' => 'total',
        ];
        break;
    case 'relatorioDepartamentosBaixaResolutiva':
        $name = 'Departamentos com mais ocorrências não resolvidas';
        $fields = [
            'ID Departamento' => 'fk_id_area',
            'Departamento' => 'n_area',
            'Total' => 'total',
        ];
        break;
    case 'relatorioDepartamentoMaiorSLA':
        $name = 'Departamentos com maior tempo de resolução';
        $fields = [
            'ID Departamento' => 'fk_id_area',
            'Departamento' => 'n_area',
            'Media' => 'media',
        ];
        break;
    default:
        die('Você não tem permissão para acessar esta área!');
}

// $pesquisa = filter_input(INPUT_POST, 'pesquisa', FILTER_SANITIZE_STRING);
// $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_NUMBER_INT);

if ($result) {
    $form['array'] = $result;
    $form['fields'] = $fields;
}
?>
<div class="body">
<div class="fieldTop">
        Relatórios
    </div>
    <div class="row">
        <div class="col-12 text-center"><h2><?= $name ?></h2></div>
    </div>
    <br />
    <form method="POST" class="mb-4">
<?php if($relatorioName === 'relatorioDepartamentosMaisRequisitados'): ?>
        <div class="row">
            <div class="col-3">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon3">Data Inicial</span>
                    <input type="date" name="1[data_inicial]" class="form-control" value="<?= $result['data_inicial'] ?>" />
                </div>
            </div>
            <div class="col-3">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon3">Data Final</span>
                    <input type="date" name="1[data_final]" class="form-control" value="<?= $result['data_final'] ?>" />
                </div>
            </div>
            <div class="col-2 offset-md-4">
                <?= formErp::button('Pesquisar') ?>
            </div>
        </div>
<?php elseif ($relatorioName === 'relatorioPoucasVisitas'):?>
        <div class="row">
            <div class="col-3">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon3">Data Inicial</span>
                    <input type="date" name="1[data_inicial]" class="form-control" value="<?= $result['data_inicial'] ?>" />
                </div>
            </div>
            <div class="col-3">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon3">Data Final</span>
                    <input type="date" name="1[data_final]" class="form-control" value="<?= $result['data_final'] ?>" />
                </div>
            </div>
            <div class="col-2 offset-md-4">
                <?= formErp::button('Pesquisar') ?>
            </div>
        </div>
<?php elseif ($relatorioName === 'relatorioDepartamentosBaixaResolutiva'):?>
        <div class="row">
            <div class="col-3">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon3">Data Inicial</span>
                    <input type="date" name="1[data_inicial]" class="form-control" value="<?= $result['data_inicial'] ?>" />
                </div>
            </div>
            <div class="col-3">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon3">Data Final</span>
                    <input type="date" name="1[data_final]" class="form-control" value="<?= $result['data_final'] ?>" />
                </div>
            </div>
            <div class="col-2 offset-md-4">
                <?= formErp::button('Pesquisar') ?>
            </div>
        </div>
<?php elseif ($relatorioName === 'relatorioDepartamentoMaiorSLA'):?>
        <div class="row">
            <div class="col-3">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon3">Data Inicial</span>
                    <input type="date" name="1[data_inicial]" class="form-control" value="<?= $result['data_inicial'] ?>" />
                </div>
            </div>
            <div class="col-3">
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-addon3">Data Final</span>
                    <input type="date" name="1[data_final]" class="form-control" value="<?= $result['data_final'] ?>" />
                </div>
            </div>
            <div class="col-2 offset-md-4">
                <?= formErp::button('Pesquisar') ?>
            </div>
        </div>
<?php else: ?>
        <div class="row">
            <p>Sem acesso!</p>
        </div>
<?php endif ?>
    </form>

    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
