<style>
    .novo{
        color: blue;
        font-weight: bold;
    }
    .sit{
        color: red;
        font-weight: bold;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
if ($inf = $model->db->tokenCheck('importarTurma')) {
    $dados = $model->importarTurma($inf);
}
$id_inst = tool::id_inst();
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_pl = ng_main::periodoSet($id_pl);
$periodos = ng_main::periodosPorSituacao();
$turmas = gtTurmas::idNome($id_inst, $id_pl);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$oldSet = $_POST['oldSet']??'';
$oldSet = json_decode(str_replace("''", '"', $oldSet), true);
if (empty($oldSet)) {
    $oldSet = [];
}

$alunos = array();
if ($id_turma) {
    $alunos = ng_escola::alunoPorTurma($id_turma);
    $dt_gdae = sql::get('ge_turmas', 'dt_gdae', ['id_turma' => $id_turma], 'fetch')['dt_gdae'];
}

if ($alunos) {
    foreach ($alunos as $k => $value) {
        $old[$value['chamada']] = $value['fk_id_tas'];
        if (!empty($dados)) {
            if (!in_array($value['chamada'], array_keys($oldSet))) {
                $alunos[$k]['n_pessoa'] = '<span class="novo">' . $value['n_pessoa'] . ' (NOVO)</span>';
            } elseif (intval($value['fk_id_tas']) != @$oldSet[$value['chamada']]) {
                $alunos[$k]['situacao'] = '<span class="sit">' . $value['situacao'] . '</span>';
            }
        }
        $alunos[$k]['ra'] = $value['ra'].' - '. $value['ra_dig'];
        $alunos[$k]['botao'] = formErp::submit("Acessar", null, ['id_pessoa' => $value['id_pessoa'], 'id_turma' => $id_turma, 'n_turma' => $turmas[$id_turma]], HOME_URI . '/sed/aluno');
    }
    $oldJson = json_encode($old);
} else {
    $oldJson = null;
}
$form['array'] = $alunos;
$form['fields'] = [
    'Chamada' => 'chamada',
    'RSE' => 'id_pessoa',
    'Nome' => 'n_pessoa',
    'Situação' => 'situacao',
    'RA' => 'ra',
    '||2' => 'botao'
];
?>
<div class="body">
    <div class="fieldTop"> 
        Gerenciamento de Turmas
    </div>

    <?php
    if (!empty($dt_gdae)) {
        ?>
        <div class="alert alert-info text-center">
            Atualizado em <?= data::porExtenso($dt_gdae) ?>
        </div>
        <?php
    }
    ?>
    <div class="row">
        <div class="col">
            <?= formErp::select('id_pl', $periodos, 'Período Letivo', $id_pl, 1) ?>
        </div>
        <div class="col">
            <?= formErp::select('id_turma', $turmas, 'Turmas', $id_turma, 1, ["id_pl" => $id_pl]) ?>
        </div>
        <div class="col">
            <form id="sinc" method="POST">
                <?=
                formErp::hidden([
                    'id_pl' => $id_pl,
                    'id_turma' => $id_turma,
                    'oldSet' => $oldJson
                ])
                . formErp::hiddenToken('importarTurma')
                ?>
            </form>
            <?php
            if (!empty($id_turma)) {
                echo formErp::button(
                        'Sincronizar com a Prodesp',
                        null,
                        'if(confirm(\'Sincronizar?\')){document.getElementById(\'sinc\').submit()}',
                        'primary'
                );
            }
            ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-12">
            <?php
            if (!empty($form)) {
                report::simple($form);
            } else {
                echo "<script> alert('Não à turmas nesse periodo') </script>";
            }
            ?>

        </div>
    </div>
    <br />
</div>