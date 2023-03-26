<?php
if (!defined('ABSPATH'))
    exit;
$id_ch = filter_input(INPUT_POST, 'id_ch', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
if ($id_ch && $id_pessoa) {
    $sql = "INSERT INTO `lab_chrome_desbloq_google` (`fk_id_ch`, `fk_id_pessoa`) VALUES ('$id_ch', '$id_pessoa');";
    try {
        $query = pdoSis::getInstance()->query($sql);
    } catch (Exception $exc) {
        
    }
    if (!empty($query)) {
        toolErp::alert("Cadastrado");
    }
}
$sql = "SELECT "
        . "  e.dt_inicio, p.id_pessoa, p.n_pessoa, p.sexo, p.cpf, p.emailgoogle, c.id_ch, c.serial, i.n_inst "
        . " FROM lab_chrome_emprestimo e "
        . " JOIN pessoa p on p.id_pessoa = e.fk_id_pessoa "
        . " left join ge_funcionario f on f.fk_id_pessoa = e.fk_id_pessoa "
        . " left join instancia i on i.id_inst = f.fk_id_inst "
        . " JOIN lab_chrome c on c.id_ch = e.fk_id_ch "
        . " WHERE `time_stamp` >= '2021-08-16' "
        . " AND e.fk_id_pessoa = c.fk_id_pessoa_lanc "
        . " AND c.id_ch NOT IN (SELECT `fk_id_ch` FROM `lab_chrome_desbloq_google`)"
        . " UNION "
        . " SELECT "
        . " SUBSTRING(h.time_stamp, 1, 10) as dt_inicio, p.id_pessoa, p.n_pessoa, p.sexo, p.cpf, p.emailgoogle, c.id_ch, c.serial, i.n_inst  "
        . " FROM lab_chrome_hist h "
        . " JOIN pessoa p on p.id_pessoa = h.fk_id_pessoa "
        . " left join ge_funcionario f on f.fk_id_pessoa = h.fk_id_pessoa "
        . " left join instancia i on i.id_inst = f.fk_id_inst "
        . " JOIN lab_chrome c on c.id_ch = h.fk_id_ch "
        . " WHERE h.time_stamp >= '2021-08-13' "
        . " AND h.fk_id_pessoa = h.fk_id_pessoa_lanc "
        . " AND c.id_ch NOT IN (SELECT `fk_id_ch` FROM `lab_chrome_desbloq_google`)"
        . " AND h.fk_id_pessoa <> 1 "
        . " ORDER BY dt_inicio ASC ";
$query = pdoSis::getInstance()->query($sql);
$array11 = $query->fetchAll(PDO::FETCH_ASSOC);
if ($array11) {
    foreach ($array11 as  $v) {
        $array[$v['id_pessoa'].'_'.$v['id_ch']] = $v;
    }

      foreach ($array as $k => $v) {
        $array[$k]['ac'] = formErp::submit('Marcar como Desbloqueado', null, $v);
    }
    $form['array'] = $array;
    $form['fields'] = [
        'Início' => 'dt_inicio',
        'IDP' => 'id_pessoa',
        'IDC' => 'id_ch',
        'Nome' => 'n_pessoa',
        'Escola' => 'n_inst',
        'CPF' => 'cpf',
        'E-mail' => 'emailgoogle',
        'Serial' => 'serial',
        '||1' => 'ac'
    ];
}
$sql = "SELECT "
        . " p.id_pessoa, p.n_pessoa, p.sexo, p.cpf, p.emailgoogle, c.id_ch, c.serial, i.n_inst "
        . " FROM lab_chrome_desbloq_google d "
        . " left join ge_funcionario f on f.fk_id_pessoa = d.fk_id_pessoa "
        . " left join instancia i on i.id_inst = f.fk_id_inst "
        . " JOIN pessoa p on p.id_pessoa = d.fk_id_pessoa "
        . " JOIN lab_chrome c on c.id_ch = d.fk_id_ch ";
$query = pdoSis::getInstance()->query($sql);
$array12 = $query->fetchAll(PDO::FETCH_ASSOC);
if ($array12) {
    foreach ($array12 as  $v) {
        $array1[$v['id_pessoa'].'_'.$v['id_ch']] = $v;
    }
}
if ($array1) {
    $form1['array'] = $array1;
    $form1['fields'] = [
        'IDP' => 'id_pessoa',
        'IDC' => 'id_ch',
        'Nome' => 'n_pessoa',
        'CPF' => 'cpf',
        'Escola' => 'n_inst',
        'E-mail' => 'emailgoogle',
        'Serial' => 'serial',
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Últimos Cadastros
        <br /><br />
        <?php
        if (!empty($array)) {
            $cont = count($array);
        } else {
            $cont = 0;
        }
        echo $cont;
        ?>
        Regitros
    </div>
    <br /><br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
    <div class="fieldTop">
        Marcados como Desbloqueados
        <br /><br />
        <?php
        if (!empty($array1)) {
            $cont = count($array1);
        } else {
            $cont = 0;
        }
        echo $cont;
        ?>
        Regitros
    </div>
    <?php
    if (!empty($form1)) {
        report::simple($form1);
    }
    ?>
</div>
