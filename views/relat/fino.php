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
<table class="field" cellspacing="0" cellpadding="0" border="0" style="width: 50%">
        <tr>
            <?php
            foreach ($form['fields'] as $k => $v) {
                ?>
            <td class="fieldrow5">
                    <?php echo substr($k, 0, 2) != '||' ? $k : '' ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <?php
        foreach ($form['array'] as $kk => $vv) {
            ?>
            <tr>
                <?php
                foreach ($form['fields'] as $k => $v) {
                    ?>
                    <td class="fieldrow4" <?php echo substr($k, 0, 2) != '||' ?'' : 'style="width: 10px; padding: 3px"' ?> >
                        <?php
                        echo data::converteBr(@$vv[$v]);
                        ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
}
?>
