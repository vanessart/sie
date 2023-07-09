<?php
$pag = intval(filter_input(INPUT_POST, 'pag', FILTER_SANITIZE_NUMBER_INT));
$pagina = intval(filter_input(INPUT_POST, 'pagina', FILTER_SANITIZE_NUMBER_INT));
$pag = $quant * $pagina;

$div = ceil($conta / $quant);
if ($div > 1) {
    ?>
    <table style="margin: 0 auto">
        <tr>
            <td style="padding: 5px; width: 10px">
                <input class="btn btn-default" type="submit" value="<?php echo $conta ?> resultados" />
            </td>
            <?php
            if ($pagina != 0) {
                ?>
                <td style="padding: 5px; width: 10px">
                    <form method="POST">
                        <?php
                        if (!empty($hidden)) {
                            echo form::hidden($hidden);
                        }
                        ?>
                        <input type="hidden" name="pagina" value="0" />
                        <input type="hidden" name="pag" value="<?php echo $pag ?>" />
                        <input class="btn btn-default" type="submit" value="<<" />
                    </form>
                </td>
                <td style="padding: 5px; width: 10px">
                    <form method="POST">
                        <?php
                        if (!empty($hidden)) {
                            echo form::hidden($hidden);
                        }
                        ?>
                        <input type="hidden" name="pagina" value="<?php echo $pagina - 1 ?>" />
                        <input type="hidden" name="pag" value="<?php echo $pag ?>" />
                        <input class="btn btn-default" type="submit" value="<" />
                    </form>
                </td>
                <?php
            } else {
                ?>
                <td style="padding: 5px; width: 10px">
                    <input class="btn btn-default" type="submit" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" />
                </td>
                <td style="padding: 5px; width: 10px">
                    <input class="btn btn-default" type="submit" value="&nbsp;&nbsp;&nbsp;" />
                </td>
                <?php
            }
            $min = ($pagina - (intval($buttons / 2)));
            $min = $min < 0 ? 0 : $min;
            $min = $min > ($div - $buttons) ? ($div - $buttons) : $min;
            for ($c = 0; $c < $div; $c++) {

                if ($c >= $min && $c < $min + $buttons) {
                    if (($pagina) == $c) {
                        $btn = 'warning';
                    } else {
                        $btn = 'default';
                    }
                    ?>
                    <td style="padding: 5px; width: 10px">
                        <form method="POST">
                            <?php
                            if (!empty($hidden)) {
                                echo form::hidden($hidden);
                            }
                            ?>
                            <input type="hidden" name="pagina" value="<?php echo $c ?>" />
                            <input type="hidden" name="pag" value="<?php echo $pag ?>" />
                            <input class="btn btn-<?php echo $btn ?>" type="submit" value="<?php echo $c + 1 ?>" />
                        </form>
                    </td>
                    <?php
                }
            }
            if ($pagina != ($div - 1)) {
                ?>
                <td style="padding: 5px; width: 10px">
                    <form method="POST">
                        <?php
                        if (!empty($hidden)) {
                            echo form::hidden($hidden);
                        }
                        ?>
                        <input type="hidden" name="pagina" value="<?php echo $pagina + 1 ?>" />
                        <input type="hidden" name="pag" value="<?php echo $pag ?>" />
                        <input class="btn btn-default" type="submit" value=">" />
                    </form>
                </td>
                <td style="padding: 5px; width: 10px">
                    <form method="POST">
                        <?php
                        if (!empty($hidden)) {
                            echo form::hidden($hidden);
                        }
                        ?>
                        <input type="hidden" name="pagina" value="<?php echo $div - 1 ?>" />
                        <input type="hidden" name="pag" value="<?php echo $pag ?>" />
                        <input class="btn btn-default" type="submit" value=">>" />
                    </form>
                </td>
                <?php
            } else {
                ?>
                <td style="padding: 5px; width: 10px">
                    <input class="btn btn-default" type="submit" value="&nbsp;&nbsp;&nbsp;" />
                </td>
                <td style="padding: 5px; width: 10px">
                    <input class="btn btn-default" type="submit" value="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" />
                </td>
                <?php
            }
            ?>
        </tr>
    </table>
    <?php
} else {
    ?>
    <div style="text-align: center">
        <input class="btn btn-default" type="submit" value="<?php echo $conta ?> resultado<?= $conta > 1 ? 's' :'' ?>" />
    </div>
    <?php
}
