<?php
ob_start() ;
$fk_id_pa = filter_input(INPUT_POST, 'fk_id_pa', FILTER_SANITIZE_NUMBER_INT);
$sql = "SELECT i.*, e.n_eixo FROM prod1_eixo e "
        . " JOIN prod1_item i on i.fk_id_eixo = e.id_eixo "
        . " WHERE i.fk_id_pa =  $fk_id_pa "
        . " ORDER BY e.ordem_eixo, i.ordem_item";
$query = pdoSis::getInstance()->query($sql);
$items = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($items as $v) {
    $itemsOrd[$v['n_eixo']][$v['id_item']] = $v;
}
?>
<div style="text-align: center; font-size: 22px">
    <?php echo sql::get('prod1_aval', 'n_pa', ['id_pa' => $fk_id_pa], 'fetch')['n_pa'] ?>
</div>
<br /><br />
<table style="width: 100%" border="1" cellspacing="1" cellpadding="0" bgcolor="#CCCCCC">
    <?php
    foreach ($itemsOrd as $eixo => $item) {
        ?>
        <tr>
            <td colspan="2" style="background-color: #f7e1b5; text-align: center; font-weight: bold">
                <?php echo $eixo ?>
            </td>
        </tr>
        <?php
        foreach ($item as $v) {
            ?>

            <tr>  
                <td style="width: 2%">
                    <?php echo $v['ordem_item'] ?>
                </td>
                <td style="width: 35%">
                    <?php echo $v['n_item'] ?>
                </td>
            </tr>

            <?php
        }
    }
    ?>
</table>
<?php
    tool::pdf();