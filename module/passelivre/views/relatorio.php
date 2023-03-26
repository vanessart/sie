<?php
if (!defined('ABSPATH'))
    exit;

$escolasGeral = $model->escolasGeral();
$escolasRede = $model->escolasRede();
$st = $model->pegastatus();
@$id_pl_status = filter_input(INPUT_POST, 'id_pl_status', FILTER_SANITIZE_NUMBER_INT);

if (tool::id_nivel() == '10') {
    $cie = filter_input(INPUT_POST, 'cie', FILTER_SANITIZE_NUMBER_INT);
    if ($cie) {
        $model->cie = $cie;
        $model->escola = $escolasGeral[$cie];
    }
} elseif (tool::id_nivel() == '8') {
    $cie = $model->cie;
}
?>
<div class="body">
    <div class="row">
        <div class="col-6">
            <?php
            if (tool::id_nivel() == '10') {
                echo formErp::select('cie', $escolasGeral, ['Escola', 'Rede'], $cie, 1);
            }
            ?>
        </div> 
        <div class="col-6">
            <?= formErp::select('id_pl_status', $st, ['Status', 'Todas'], $id_pl_status, 1, ["cie" => $cie]) ?>
        </div>
    </div>        
    <br /><br /><br />
    <div class="row">
        <div class="col-4">
            <form target="_blank" action="<?= HOME_URI ?>/passelivre/pdf/pdflista.php" method="POST">
                <?php
                $criterio = "WHERE 1 ORDER BY cie, nome ";
                if ((!empty($cie)) && (!empty($id_pl_status))) {
                    $criterio = "WHERE cie = $cie AND fk_id_pl_status = $id_pl_status ORDER BY cie, nome ";
                } else if (!empty($cie) && (empty($id_pl_status))) {
                    $criterio = "WHERE cie = $cie ORDER BY cie, nome ";
                } else if ($id_pl_status) {
                    $criterio = "WHERE fk_id_pl_status = $id_pl_status ORDER BY cie, nome ";
                }
                ?>
                <input type="hidden" name="criterio" value="<?= $criterio ?>" />
                <button class="btn btn-info" style="width: 70%">
                    Visualizar
                </button>         
            </form>
        </div>
        <?php
        if (tool::id_nivel() == '10') {
            ?>
            <div class="col-4">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                    <?php
                    $sql = $model->geraarquivoexcel($criterio, "R");
                    ?>
                    <input type="hidden" name="tokenSql" value="<?php echo tool::tokenSql() ?>" />
                    <input type="hidden" name="sql" value="<?php echo $sql ?>" />  
                    <button type="submit" class="btn btn-info" style="width: 70%">
                        Excel - Rede
                    </button>  
                </form>      
            </div>
            <div class="col-4">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                    <?php
                    $sql = $model->geraarquivoexcel($criterio, "E");
                    ?>
                    <input type="hidden" name="tokenSql" value="<?php echo tool::tokenSql() ?>" />
                    <input type="hidden" name="sql" value="<?php echo $sql ?>" />  
                    <button type="submit" class="btn btn-info" style="width: 70%">
                        Excel - Externa
                    </button>  
                </form>      
            </div>
        <?php
        } elseif (in_array($cie, $escolasRede)) {
            ?>
            <div class="col-4">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                    <?php
                    $sql = $model->geraarquivoexcel($criterio, "R");
                    ?>
                    <input type="hidden" name="tokenSql" value="<?php echo tool::tokenSql() ?>" />
                    <input type="hidden" name="sql" value="<?php echo $sql ?>" />  
                    <button type="submit" class="btn btn-info" style="width: 70%">
                        Excel - Rede
                    </button>  
                </form>      
            </div>
            <?php
        } else {
            ?>
            <div class="col-4">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                    <?php
                    $sql = $model->geraarquivoexcel($criterio, "E");
                    ?>
                    <input type="hidden" name="tokenSql" value="<?php echo tool::tokenSql() ?>" />
                    <input type="hidden" name="sql" value="<?php echo $sql ?>" />  
                    <button type="submit" class="btn btn-info" style="width: 70%">
                        Excel - Externa
                    </button>  
                </form>      
            </div>
            <?php
        }
        ?>
    </div>
</div>
<br />
<?php ?>
