<?php

if (!in_array(tool::id_pessoa(), [1, 6])) {
    exit();
}


exit();

$polos = sql::get('maker_polo');
$instPolo = toolErp::idName($polos, 'id_polo', 'fk_id_inst_maker');
foreach (range(1, 20) as $polo) {
    $inst = $instPolo[$polo];
    $letra = "A";
    foreach (range(2, 4) as $ciclo) {
        foreach (['M', 'T'] as $per) {
            $nTurma = $ciclo . $per . '22' . str_pad($polo, 2, "0", STR_PAD_LEFT);
            echo '<br>' . $sql = "INSERT INTO `maker_gt_turma` "
            . " (`n_turma`, `codigo`, `fk_id_inst`, `fk_id_ciclo`, `fk_id_grade`, `periodo`, `fk_id_pl`, `letra`) "
            . "VALUES "
            . "('$nTurma', '$nTurma', '$inst', '$ciclo', '1', '$per', '111', '$letra');";
            //    $query = pdoSis::getInstance()->query($sql);
            $letra++;
        }
    }
}


exit();
$mongo = new mongoCrude('Diario');
$filter = ['data' => ['$regex' => '-10-'], 'timeStamp' => ['$regex' => '-12-']];
$option = ['limit' => 1000, 'sort' => ['_id' => -1]];
$dados = $mongo->query('Aula.87', $filter);
foreach ($dados as $v) {
    $v = (array) $v;
    $ins['id_turma'] = $v['id_turma'];
    $ins['id_pessoa'] = $v['id_pessoa'];
    $ins['data'] = $v['data'];
    $ins['data_lanc'] = $v['timeStamp'];
    $ins['id_disc'] = $v['id_disc'];
    $ins['tabela'] = 'Conteúdo';
    $model->db->insert('aaa_diario', $ins, 1);
}