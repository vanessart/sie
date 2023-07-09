<?php
/**
 * $form[] = [[4,'Nome do Aluno:','teste']];
  $form[] = [[1,'RSE:','teste'],[3,'RA','teste']];
 * $form[]= NULL;  Fecha uma tabela, quebra página e abre outra
 * total somatória máxima é 8  
 */
$colspan = [1 => 1, 2 => 3, 3 => 5, 4 => 7, 5 => 9, 6 => 11, 7 => 13, 8 => 15];
$width = [1 => 11, 2 => 24, 3 => 37, 4 => 49, 5 => 61, 6 => 74, 7 => 87, 8 => 100];
?>
<style>
    td.item {
        border-bottom: 1px solid black;
        border-left:  1px solid black;
        padding-left:  10px;
        font-size: 10px;
        font-weight: bold;
        height: 30px;
    }
    td.inter{
        width: 1%;
    }
</style>

<table style="width: 100%;"  >
    <?php
    foreach ($form as $lin) {
        if (!empty($lin)) {
            if (is_array($lin)) {
                ?>
                <tr>
                    <?php
                    $ttCol = 0;
                    foreach ($lin as $col) {
                        $qtCol = $colspan[$col[0]];
                        if ($ttCol < 15) {
                            $ttCol += $qtCol + 1;
                        }
                        ?>
                        <td colspan="<?php echo $qtCol ?>" class="item" style="width: <?php echo $width[$col[0]] ?>%" valign="top">
                            <?php
                            echo $col[1];
                            if (!empty($col[2])) {
                                ?>
                                <br />
                                <?php
                                echo $col[2];
                            }
                            ?>
                        </td>
                        <?php
                        if ($ttCol < 15) {
                            ?>
                            <td class="inter"></td>
                            <?php
                        }
                    }
                    if ($ttCol < 15) {
                        ?>
                        <td colspan="<?php echo 15 - $ttCol ?>"></td>
                        <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td colspan="15" style="font-size: 5px">
                        &nbsp;
                    </td>
                </tr>
                <?php
            } else {
                ?>
                <tr>
                    <td colspan="15" style="text-align: center; font-weight: bold;border: 1px solid black;">
                        <?php echo $lin ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="15" style="font-size: 5px">
                        &nbsp;
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
        </table>
        <div style="page-break-after: always"></div>
        <table style="width: 100%;"  >
            <?php
        }
    }
    ?>
</table>
