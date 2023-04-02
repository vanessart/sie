<?php
if (!defined('ABSPATH'))
    exit;

$data = filter_input(INPUT_POST, 'data', FILTER_UNSAFE_RAW);
$robot = filter_input(INPUT_POST, 'robot', FILTER_SANITIZE_NUMBER_INT);
if (empty($data)) {
    $data = date("Y-m-d");
}

$logs = $model->robotLogsErro($data);
if ($logs) {
    foreach ($logs as $k => $v) {
        $logs[$k]['dia'] = dataErp::converteBr($v['time_stamp']);
        $logs[$k]['hora'] = substr($v['time_stamp'], 11);
    }
    $form['array'] = $logs;
    $form['fields'] = [
        'Dia' => 'dia',
        'Hora' => 'hora',
        'Erro' => 'erro',
    ];
}

$logs1 = $model->robotLogs($data);

if ($logs1) {
    foreach ($logs1 as $k => $v) {
        $logs1[$k]['dia'] = dataErp::converteBr($v['time_stamp']);
        $logs1[$k]['hora'] = substr($v['time_stamp'], 11);
    }
    $form1['array'] = $logs1;
    $form1['fields'] = [
        'Dia' => 'dia',
        'Hora' => 'hora',
        'Atualizada' => 'obs',
    ];
}
?>
<div class="row">
    <div class="col-3">
        <form id="data" method="POST">
            <?=
            formErp::input('data', 'Data', $data, '  onchange="document.getElementById(\'data\').submit();" ', null, 'date')
            . formErp::hidden(['activeNav' => 3])
            ?>
        </form>
    </div>
    <div class="col-9" style="text-align: right; padding-right: 50px">
        <form method="POST">
            <?php
            if (empty($robot)) {
                ?>
                <?=
                formErp::hidden([
                    'activeNav' => 3,
                    'robot' => 1
                ])
                ?>
                <button class="btn btn-primary">
                    Acionar o Robot Agora
                </button>
                <?php
            } else {
                ?>
                <?=
                formErp::hidden([
                    'activeNav' => 3,
                ])
                ?>
                <button class="btn btn-warning">
                    Retornar aos logs
                </button>
                <?php
            }
            ?>
        </form>
    </div>
</div>
<br />
<?php
if ($robot) {
    include ABSPATH . '/module/sed/views/_sincronizar/robot.php';
}
?>
<div class="border">
    <?php
    if (empty($form1)) {
        ?>
        <div class="fieldTop">
            Não há Atividades referente a esta data.
        </div>
        <?php
    } else {
        ?>

        <div class="fieldTop">
            Atividades em <?= dataErp::porExtenso($data) ?>
        </div>
        <?php
        report::simple($form1);
    }
    ?>
</div>
<br /><br />
<div class="border">
    <?php
    if (empty($form)) {
        ?>
        <div class="fieldTop">
            Não há logs referente a esta data.
        </div>
        <?php
    } else {
        ?>
        <div class="fieldTop">
            Erros ocorridos em <?= dataErp::porExtenso($data) ?>
        </div>
        <?php
        report::simple($form);
    }
    ?>
</div>
<br />

