<?php
if (!defined('ABSPATH'))
    exit;
$pessoa = filter_input(INPUT_POST, 'pessoa');
if ($pessoa) {
    $sql = "SELECT "
            . " p.id_pessoa, p.n_pessoa, t.n_turma, po.n_polo "
            . " FROM tdics_turma_aluno ta "
            . " join tdics_turma t on t.id_turma = ta.fk_id_turma "
            . " join tdics_polo po on po.id_polo = t.fk_id_polo"
            . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
            . " WHERE (p.id_pessoa = '$pessoa' or p.n_pessoa like '$pessoa%') "
            . " ORDER by p.n_pessoa,  t.id_turma";
    $query = pdoSis::getInstance()->query($sql);
    $alunox = $query->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($alunox)) {
        foreach ($alunox as $v) {
            $aluno[$v['id_pessoa']] = $v;
        }
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
            'Polo' => 'n_polo',
            'Turma' => 'n_turma',
            '||2' => 'qr',
            '||1' => 'ac',
        ];
    }
}
?>

<form method="POST">
    <div class="row"> 
        <div class="col-9"> 
            <?= formErp::input('pessoa', 'Nome ou RSE', $pessoa, ' required ') ?>
        </div>
        <div class="col-3"> 
            <?=
            formErp::hidden($hidden)
            . formErp::hidden(['activeNav' => 2])
            . formErp::button('Pesquisar')
            ?>
        </div>
    </div>
    <br />
</form> 
<br /><br />
<?php
if (!empty($form)) {
    report::simple($form);
}