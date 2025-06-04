<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa');
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_polo = filter_input(INPUT_POST, 'id_polo', FILTER_SANITIZE_NUMBER_INT);
$id_ag = filter_input(INPUT_POST, 'id_ag', FILTER_SANITIZE_NUMBER_INT);
$id_aval = filter_input(INPUT_POST, 'id_aval', FILTER_SANITIZE_NUMBER_INT);
$aval = sql::get('tdics_aval', '*', ['id_aval' => $id_aval], 'fetch');
$quest = sql::get('tdics_aval_quest', '*', ['fk_id_aval' => $id_aval, '>' => 'ordem']);
$sql = "SELECT * FROM `tdics_aval_resp` WHERE `fk_id_turma` = $id_turma and fk_id_pessoa = $id_pessoa and fk_id_aval = $id_aval ";
$query = pdoSis::getInstance()->query($sql);
$ar = $query->fetch(PDO::FETCH_ASSOC);
if ($ar) {
    $resp = json_decode($ar['respostas'], true);
}
?>
<style>
    body{
        background-color: oldlace
    }
</style>
<div class="body">
    <div class="fieldTop">
        <?= $n_pessoa ?> (RSE: <?= $id_pessoa ?>)
    </div>
    <table class="table table-bordered table-responsive" style="width: 60%; margin: auto">
        <tr>
            <td style="text-align: center">
                Avaliação: <?= $aval['n_aval'] ?>
            </td>
        </tr>
        <?php
        if ($aval['descri']) {
            ?>
            <tr>
                <td style="text-align: center">
                    <?= $aval['descri'] ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
    if ($quest) {
        if (empty($resp)) {
            ?>
            <div class="alert alert-danger" style="font-weight: bold; font-size: 1.4em; text-align: center">
                Avaliação NÃO Salva
            </div>
            <?php
        } else {
            ?>
            <div class="alert alert-success" style="font-weight: bold; font-size: 1.4em; text-align: center">
                Avaliação Salva
            </div>
            <?php
        }
        ?>
        <form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/aval" target="_parent" method="POST">
            <?php
            foreach ($quest as $v) {
                ?>
                <table class="border" style="padding: 10px; font-weight: normal; font-size: 1.3em; background-color: whitesmoke; width: 100%">
                    <tr>
                        <td style="width: 10%; border: #048CAD solid 1px; padding-left: 15px">
                            <?= $v['ordem'] ?>ª Questão  
                        </td>
                        <td style="width: 40%; border: #048CAD solid 1px; padding-left: 15px">
                            <span style="font-weight: bold">Momento</span>:   <?= $v['momento'] ?>
                        </td>
                        <td rowspan="2">
                            <?php
                            $selected = 0;
                            foreach (range(1, 5) as $y) {
                                if (!empty($v['resp_' . $y])) {
                                    if ($v['valor_' . $y] > $selected) {
                                        $selected = $v['valor_' . $y];
                                    }
                                }
                            }
                            foreach (range(1, 5) as $y) {
                                if (!empty($v['resp_' . $y])) {
                                    if (!empty($resp[$v['id_quest']])) {
                                        $post = $resp[$v['id_quest']];
                                    } elseif ($v['valor_' . $y] == $selected) {
                                        $post = $y;
                                    } else {
                                        $post = null;
                                    }
                                    ?>
                                    <div class="border5" style="background-color: white">
                                        <table style="font-weight: normal">
                                            <tr>
                                                <td style="width: 140px">
                                                    <span style="font-weight: bold">
                                                        <?= formErp::radio('resp[' . $v['id_quest'] . ']', $y, ($v['valor_' . $y]) . ' Ponto' . ($v['valor_' . $y] > 1 ? 's' : ''), $post) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?= $v['resp_' . $y] ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="vertical-align: top; border: #048CAD solid 1px; padding: 15px">
                            <span style="font-weight: bold">Quesitos para Análise</span>:
                            <br /><br />
                            <?= $v['n_quest'] ?>
                        </td>
                    </tr>
                </table>
                <br />
                <?php
            }
            ?>
            <div style="text-align: center; padding: 20px">
                <?=
                formErp::hidden([
                    'id_polo' => $id_polo,
                    'id_ag' => $id_ag,
                    'id_aval' => $id_aval,
                    'id_turma' => $id_turma,
                    'id_pessoa' => $id_pessoa,
                    'id_ar' => @$ar['id_ar']
                ])
                . formErp::hiddenToken('registraNota')
                . formErp::button('Salvar')
                ?>
            </div>
        </form>
        <?php
    }
    ?>
</div>