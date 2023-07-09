<script src="<?php echo HOME_URI; ?>/<?php echo INCLUDE_FOLDER ?>/js/sorttable.js"></script>
<?php
if (!empty(@$form['titulo'])) {
    ?>
    <div class="fieldTop">
        <?php echo @$form['titulo'] ?>
    </div>
    <br />
    <?php
}

if (!empty(@$include)) {
    if (is_array($include)) {
        foreach ($include as $v) {
            include_once $v;
            echo '<br />';
        }
    } else {
        include_once $include;
        echo '<br />';
    }
}
if (!empty($form['array']) && !empty($form['fields'])) {
    ?>
    <div id="divConteudo">
        <br />
        <table style="background-color: white" class="table table-striped table-bordered table-hover sortable" id="tabela">
            <?php
            foreach ($form['fields'] as $k => $v) {
                ?>
                <tr>
                    <td>
                        <?php echo $k ?>
                    </td>
                    <td >
                        <?php
                        echo data::converteBr(@$form['array'][$v]);
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <?php
}
?>
