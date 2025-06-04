<?php
if (!defined('ABSPATH'))
    exit;
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_ag = filter_input(INPUT_POST, 'id_ag', FILTER_SANITIZE_NUMBER_INT);
$per = $model->periodoLetivos(1);
$id_pl = $model->pl($id_pl);
$gr = [];
if ($id_pl) {
    $sql = "SELECT * FROM `{$model::$sistema}_aval_group` WHERE `fk_id_pl` = $id_pl ";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    if ($array) {
        $gr = tool::idName($array);
    }
}
if ($id_ag) {
    $sql = " SELECT a.id_aval, a.n_aval, p.n_pl, g.n_ag, c.n_curso FROM {$model::$sistema}_aval a "
            . " JOIN {$model::$sistema}_aval_group g on g.id_ag = a.fk_id_ag "
            . " and g.id_ag = $id_ag "
            . " JOIN {$model::$sistema}_pl p on p.id_pl = g.fk_id_pl "
            . " JOIN {$model::$sistema}_curso c on c.id_curso = a.fk_id_curso ";
    $query = pdoSis::getInstance()->query($sql);
    $avals = $query->fetchAll(PDO::FETCH_ASSOC);
}
if (!empty($avals)) {
    $hidden = [
        'id_ag' => $id_ag,
        'id_pl' => $id_pl,
    ];
    $token = formErp::token($model::$sistema . '_aval', 'delete');
    foreach ($avals as $k => $v) {
        $hidden['id_aval'] = $v['id_aval'];
        $avals[$k]['ed'] = formErp::submit('Acessar', null, $hidden, HOME_URI . '/' . $this->controller_name . '/avalConf');
        $hidden['1[id_aval]'] = $v['id_aval'];
        $avals[$k]['del'] = formErp::submit('Excluir', $token, $hidden);
    }
    $form['array'] = $avals;
    $form['fields'] = [
        'ID' => 'id_aval',
        'Avaliação' => 'n_aval',
        'Curso' => 'n_curso',
        'Período Letivo' => 'n_pl',
        '||1' => 'del',
        '||2' => 'ed'
    ];
}
?>

<div class="body">
    <div class="fieldTop">
        Cadastro de Avaliações
    </div>
    <div class="row"> 
        <div class="col"> 
            <?= formErp::select('id_pl', $per, 'Período Letivo', $id_pl, 1, null, ' required ') ?>
        </div>
        <div class="col"> 
            <?php
            if ($id_pl) {
                echo formErp::select('id_ag', $gr, 'Agrupamento', $id_ag, 1);
            }
            ?>
        </div>
        <div class="col">
            <?php
            if ($id_ag) {
                ?>
                <form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/avalConf" method="POST">
                    <input type="hidden" name="id_ag" value="<?= $id_ag ?>" />
                    <input type="hidden" name="id_pl"  value="<?= $id_pl ?>" />
                    <input type="hidden" name="id_aval" id="id_aval" value="" />
                    <button type="submit" class="btn btn-success">
                        Nova Avaliação
                    </button>
                </form>  
                <?php
            }
            ?>
        </div>
    </div>
    <br />
    <br />
    <br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
