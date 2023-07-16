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
        <table style="background-color: white" class="table table-striped table-bordered table-hover" >
            <thead>
                <tr>
                    <?php
                    $y = 1;
                    foreach ($form['fields'] as $k => $v) {
                        if (substr($k, -1) == '?') {
                            $k = substr($k, 0, -1);
                        }
                        ?>
                        <th class="fieldrow">
                            <?php
                            if (substr($k, 0, 2) != '||') {
                                echo $k;
                            }
                            ?>

                        </th>
                        <?php
                        $y++;
                    }
                    ?>
                </tr>

            </thead>
            <tbody>

                <?php
                foreach ($form['array'] as $kk => $vv) {
                    ?>
                    <tr>
                        <?php
                        foreach ($form['fields'] as $k => $v) {
                            ?>
                            <td <?php echo substr($k, 0, 2) != '||' ? '' : 'style="width: 10px; padding: 3px"' ?> >
                                <?php
                                if (!(substr($k, -1) == '?')) {
                                    echo data::converteBr(@$vv[$v]);
                                } else {
                                    echo tool::simnao(@$vv[$v]);
                                }
                                ?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}
