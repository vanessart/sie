<?php
$fk_id_con = filter_input(INPUT_POST, 'fk_id_con', FILTER_SANITIZE_NUMBER_INT);
if (!empty($fk_id_con)) {
    $sql = "SELECT * FROM `biro_item` WHERE `fk_id_con` = $fk_id_con  ORDER BY lote,num ASC";
    $query = $model->db->query($sql);
    $array = $query->fetchAll();

    $sql = "SELECT i.id_item, SUM(t.quant_item) as quant "
            . " FROM biro_trab t "
            . " JOIN biro_item i on i.id_item = t.fk_id_item "
            . " WHERE t.status_biro LIKE 'Concluído' "
            . " AND i.fk_id_con = $fk_id_con "
            . " GROUP BY i.id_item ";
    $query = $model->db->query($sql);
    $gasto_ = $query->fetchAll();
    foreach ($gasto_ as $v) {
        $gasto[$v['id_item']] = $v['quant'];
    }
    foreach ($array as $k => $v) {
        $array[$k]['utilizado'] = @$gasto[$v['id_item']];
        $array[$k]['saldo'] = $v['quant'] - @$gasto[$v['id_item']];
        $array[$k]['saldoDin'] = 'R$ ' . number_format(($array[$k]['saldo'] * str_replace(',', '.', $v['preco'])), 2);

        @$tt['utilizado'] += @$gasto[$v['id_item']];
        @$tt['saldo'] += ($v['quant'] - @$gasto[$v['id_item']]);
        @$tt['quant'] += $v['quant'];
        @$tt['saldoDin'] += ($array[$k]['saldo'] * str_replace(',', '.', $v['preco']));
    }

    $t['id_item'] = '';
    $t['n_item'] = '<strong>Total</strong>';
    $t['lote'] = '';
    $t['num'] = '';
    $t['quant'] = '<strong>'.@$tt['quant'].'</strong>';
    $t['unidade'] = '';
    $t['preco'] = '';
    $t['fk_id_con'] = '';
    $t['ativo'] = '';
    $t['utilizado'] = '<strong>'.@$tt['utilizado'].'</strong>';
    $t['saldo'] = '<strong>'.@$tt['saldo'].'</strong>';
    $t['saldoDin'] = '<strong>R$ ' . number_format(@$tt['saldoDin'],2).'</strong>';
    $array += ['t'=>$t];
    $form['array'] = $array;
    $form['fields'] = [
        'Lote' => 'lote',
        'Item' => 'num',
        'Descrição' => 'n_item',
        'Quant. Contratada' => 'quant',
        'Quant. Utilizada' => 'utilizado',
        'Quant. Saldo' => 'saldo',
        'Saldo em Reais' => 'saldoDin'
    ];
    ?>
    <div class="fieldBody">
        <div class="fieldTop">
            Saldo referente ao Contrado <?php echo sql::get('biro_contrato', 'n_con', ['id_con' => $fk_id_con], 'fetch')['n_con'] ?>
        </div>  
        <br /><br />
        <?php
        tool::relatSimples($form);
    } else {
        echo 'Preencha todos os campos';
    }
    ?>
</div>
