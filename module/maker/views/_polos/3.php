<?php
if (!defined('ABSPATH'))
    exit;
$sem = [
    2 => 'Segunda',
    3 => 'Terça',
    4 => 'Quarta',
    5 => 'Quinta',
    6 => 'Sexta',
    7 => 'Sábado',
    1 => 'Domingo',
];
$per = [
    'M' => 'Manhã',
    'T' => 'Tarde',
    'N' => 'Noite'
];
if ($id_polo) {
    $t = sql::get('maker_turmas_dia_per', '*', ['id_polo_turmas' => $id_polo], 'fetch');
    if (empty($t)) {
        $ins = [
            'id_polo_turmas' => $id_polo,
            'M2' => 2, 'T2' => 2,
            'M3' => 2, 'T3' => 2,
            'M4' => 2, 'T4' => 2,
            'M5' => 2, 'T5' => 2,
            'M6' => 2, 'T6' => 2,
            'qt_alunos' => 24
        ];
        $model->db->ireplace('maker_turmas_dia_per', $ins, 1);
        $t = sql::get('maker_turmas_dia_per', '*', ['id_polo_turmas' => $id_polo], 'fetch');
    }
    $alunos = 0;
    $alunoPer['M'] = 0;
    $alunoPer['T'] = 0;
    $alunoPer['N'] = 0;
    foreach ($t as $k => $v) {
        if (is_numeric(substr($k, 1))) {
            $alunos += $v;
            $alunoPer[substr($k, 0, 1)] += $v;
        }
    }
}
?>
<br />
<form method="POST">
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <td style="width: 20%">
                Alunos por Turma
            </td>
            <td style="width: 20%">
                Total de Alunos
            </td>
            <td style="width: 20%">
                Total Manhã
            </td>
            <td style="width: 20%">
                Total Tarde
            </td>
            <td style="width: 20%">
                Total Noite
            </td>
        </tr>
        <td>
            <?= formErp::input('1[qt_alunos]', null, @$t['qt_alunos'], null, null, 'number') ?>
        </td>
        <td>
            <?= $alunos * @$t['qt_alunos'] ?>
        </td>
        <td>
            <?= $alunoPer['M'] * @$t['qt_alunos'] ?>
        </td>
        <td>
            <?= $alunoPer['T'] * @$t['qt_alunos'] ?>
        </td>
        <td>
            <?= $alunoPer['N'] * @$t['qt_alunos'] ?>
        </td>
        </tr>
    </table>
    <br /><br />
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <td></td>
            <?php
            foreach ($sem as $k => $v) {
                ?>
                <td>
                    <?= $v ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <?php
        foreach ($per as $kp => $p) {
            ?>
            <tr>
                <td>
                    <?= $p ?>
                </td>
                <?php
                foreach ($sem as $k => $v) {
                    ?>
                    <td>
                        <?= formErp::selectNum('1[' . $kp . $k . ']', [0, 5], null, @$t[$kp . $k]) ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <?php
        }
        ?>
    </table>
    <div style="text-align: center; padding: 30px">
        <?=
        formErp::hidden([
            'activeNav' => 3,
            'id_polo' => $id_polo,
            '1[id_polo_turmas]' => $id_polo
        ])
        . formErp::hiddenToken('maker_turmas_dia_per', 'ireplace')
        . formErp::button('Salvar')
        ?>
    </div>
</form>
