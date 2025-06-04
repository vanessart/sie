<?php
if (!defined('ABSPATH'))
    exit;
if ($id_turma) {
    $sql = "SELECT "
            . " p.id_pessoa, p.n_pessoa "
            . " FROM tdics_turma_aluno ta "
            . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
            . " WHERE `fk_id_turma` = $id_turma "
            . " ORDER by p.n_pessoa ";
    $query = pdoSis::getInstance()->query($sql);
    $aluno = $query->fetchAll(PDO::FETCH_ASSOC);
}
if (!empty($aluno)) {
    $sql = "SELECT fk_id_pessoa FROM `tdics_aval_resp` WHERE `fk_id_pessoa` IN (" . (implode(', ', array_column($aluno, 'id_pessoa'))) . ") ";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    if ($array) {
        $fez = array_column($array, 'fk_id_pessoa');
    } else {
        $fez = [];
    }
    foreach ($aluno as $k => $v) {
        if (in_array($v['id_pessoa'], $fez)) {
            $aluno[$k]['ac'] = formErp::submit('Acessar', null, $v, HOME_URI . '/' . $this->controller_name . '/avalAluno', 1);
            $aluno[$k]['qr'] = formErp::submit('QR Code', null, $v, HOME_URI . '/' . $this->controller_name . '/avalAlunoQr', 1);
        } else {
            $aluno[$k]['ac'] = '<button class="btn btn-danger">Não Avaliado</button>';
        }
    }
    $form['array'] = $aluno;
    $form['fields'] = [
        'RSE' => 'id_pessoa',
        'Aluno' => 'n_pessoa',
        '||2' => 'qr',
        '||1' => 'ac',
    ];
}
?>
<br /><br />
<div class="row">
    <div class="col">
        <?= formErp::select('id_polo', $polos, 'Polo', $id_polo, 1) ?>
    </div>
    <div class="col">
        <?php
        if ($id_polo) {
            $turmas = $model->turmasPolo($id_polo);
            if ($turmas) {
                echo formErp::select('id_turma', $turmas, 'Turma', $id_turma, 1, ['id_polo' => $id_polo]);
            } else {
                echo 'Não há turmas';
            }
        }
        ?>
    </div>
    <div class="col">
        <?php
        if (!empty($fez)) {
            ?>
            <form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/avalAlunoQr" target="_blank" method="POST">
                <?=
                formErp::hidden(['id_pessoa' => implode(', ', $fez)])
                . formErp::button('Gerar todos os QR Code')
                ?>
            </form>
            <?php
        } elseif ($id_polo) {
            ?>
            <form action="<?= HOME_URI ?>/<?= $this->controller_name ?>/avalAlunoQrGeral" target="_blank" method="POST">
                <?=
                formErp::hidden(['id_polo' => $id_polo])
                . formErp::button('Gerar todos os QR Code')
                ?>
            </form>
            <?php
        }
        ?>
    </div>
</div>
<br />
<div style="width: 1000px; margin: auto">
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>