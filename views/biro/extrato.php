<?php
$fk_id_con = filter_input(INPUT_POST, 'fk_id_con', FILTER_SANITIZE_NUMBER_INT);
$iniciop = @$_POST['inicio'];
$inicio = data::converteUS($iniciop);
$fimp = @$_POST['fim'];
$fim = data::converteUS($fimp);
if (!empty($fk_id_con) && !empty($inicio) && !empty($fim)) {
    $sql = "SELECT "
            . " i.lote, i.num, i.n_item, "
            . " SUM(t.quant_item) as quant, i.preco, "
            . " round(( REPLACE( i.preco, ',','.') * SUM(t.quant_item)),2) as total "
            . " FROM biro_trab t "
            . " JOIN biro_item i on i.id_item = t.fk_id_item "
            . " WHERE t.status_biro LIKE 'Concluído' "
            . " and t.dt_biro BETWEEN '$inicio' AND '$fim' "
            . " AND i.fk_id_con = $fk_id_con "
            . " GROUP BY i.id_item "
            . " ORDER BY i.lote, i.num ";
    $query = $model->db->query($sql);
    $array = $query->fetchAll();
    foreach ($array as $v) {
        @$soma += $v['total'];
    }
    $form['array'] = $array;
    $form['fields'] = [
        'lote' => 'lote',
        'Item' => 'num',
        'Descrição' => 'n_item',
        'Quant. Período' => 'quant',
        'Preço Unitário' => 'preco',
        'Preço Total' => 'total'
    ];
    ?>
    <div class="fieldBody">
        <div class="fieldTop">
            Estrato do período entre <?php echo $iniciop ?> e <?php echo $fimp ?> referente ao Contrado <?php echo sql::get('biro_contrato', 'n_con', ['id_con' => $fk_id_con], 'fetch')['n_con'] ?>
        </div>  
        <br /><br />
        <div class="row">
            <div class="col-sm-6 text-center" style="font-weight: bold">
                Total: R$ <?php echo @$soma ?> 
            </div>
            <div class="col-sm-6 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                    <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                    <input type="hidden" name="tokenSql" value="<?php echo substr((date("yhdm") / 3.5288 * 68), 0, 20); ?>" />
                    <input class="btn btn-warning" type="submit" value="Exportar" />
                </form>  
            </div>
        </div>

        <br /><br />
        <?php
        tool::relatSimples($form);
        ?>
        <br /><br />
        <div class="fieldTop">
            Serviços prestados no período entre <?php echo $iniciop ?> e <?php echo $fimp ?> referente ao Contrado <?php echo sql::get('biro_contrato', 'n_con', ['id_con' => $fk_id_con], 'fetch')['n_con'] ?>
        </div>  
        <br /><br />

        <?php
        $sql = "SELECT * FROM biro_trab t "
                . " JOIN biro_item i on i.id_item = t.fk_id_item "
                . " WHERE t.status_biro LIKE 'Concluído' "
                . " and t.dt_biro BETWEEN '$inicio' AND '$fim' "
                . " AND i.fk_id_con = $fk_id_con  "
                . "ORDER BY i.lote, i.num, t.dt_biro ";
        $query = $model->db->query($sql);
        $array = $query->fetchAll();
        foreach ($array as $k => $v) {
            $array[$k]['total'] = 'R$ ' . number_format(($v['quant_item'] * str_replace(',', '.', $v['preco'])), 2);
        }
        $form['array'] = $array;
        $form['fields'] = [
            'Protocolo' => 'id_trab',
            'Solicitante' => 'n_pessoa',
            'Matrícula' => 'rm',
            'Instância' => 'local',
            'Data' => 'dt_biro',
            'Lote' => 'lote',
            'Item' => 'num',
            'Quant. Serv.' => 'quant_item',
            'V. Unit.' => 'preco',
            'V. Total' => 'total'
        ];
        ?>
        <div class="row">
            <div class="col-sm-6 text-center" style="font-weight: bold">
            </div>
            <div class="col-sm-6 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                    <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                    <input type="hidden" name="tokenSql" value="<?php echo substr((date("yhdm") / 3.5288 * 68), 0, 20); ?>" />
                    <input class="btn btn-warning" type="submit" value="Exportar" />
                </form>  
            </div>
        </div>
        <br /><br />
        <?php
        tool::relatSimples($form);
        ?>
    </div>
    <?php
} else {
    echo 'Preencha todos os campos';
}
?>

