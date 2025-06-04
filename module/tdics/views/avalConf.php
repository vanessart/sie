<?php
if (!defined('ABSPATH'))
    exit;

$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_ag = filter_input(INPUT_POST, 'id_ag', FILTER_SANITIZE_NUMBER_INT);
if ($model->db->tokenCheck('tdics_avalx')) {
    $in = @$_POST[1];
    $idCursos = @$_POST['id_curso'];
    if (empty($idCursos)) {
        toolErp::alert('precisa selecionar o ciclo');
    } else {
        if ($in) {
            foreach ($idCursos as $k => $v) {
                if (!empty($v)) {
                    $ic[$k]=$k;
                }
            }
            $in['fk_id_curso'] = implode(',', $ic);

            $id_aval = $model->db->ireplace($model::$sistema . '_aval', $in);
        }
    }
}
if (empty($id_aval)) {
    $id_aval = filter_input(INPUT_POST, 'id_aval', FILTER_SANITIZE_NUMBER_INT);
}
if (empty($id_ag) || empty($id_pl)) {
    ?>
    <form action="<?= HOME_URI . '/' . $this->controller_name . '/avalCad' ?>" style="text-align: center; padding: 100px">
        <?= formErp::button('Voltar') ?>
    </form>
    <?php
    die();
}
if ($id_aval) {
    $aval = sqlErp::get($model::$sistema . '_aval', '*', ['id_aval' => $id_aval], 'fetch');
    $desable = 1;
} else {
    $desable = null;
}
$hidden = [
    'id_ag' => $id_ag,
    'id_pl' => $id_pl,
    'id_aval' => $id_aval,
];
?>

<div class="body">
    <div class="fieldTop">
        Cadastro de Avalição
    </div>
    <?php
    $abas[1] = ['nome' => "Dados Gerais", 'ativo' => 1, 'hidden' => $hidden];
    $abas[2] = ['nome' => "Questões", 'ativo' => $desable, 'hidden' => $hidden];
    $abas[3] = ['nome' => "Voltar", 'ativo' => 1, 'hidden' => $hidden, 'link' => HOME_URI . '/' . $this->controller_name . '/avalCad'];
    $aba = report::abas($abas);
    include ABSPATH . "/module/". $this->controller_name ."/views/_avalConf/$aba.php";
    ?>
</div>