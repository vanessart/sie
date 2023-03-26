<script src="<?php echo HOME_URI; ?>/views/_js/sorttable.js"></script>
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
        <table style="background-color: white" class="table table-striped table-bordered table-hover sortable" id="tabela">
            <thead>
                <tr>
                    <?php
                    $y = 1;
                    foreach ($form['fields'] as $k => $v) {
                        ?>
                        <th class="fieldrow5">
                            <?php
                            if (substr($k, 0, 2) != '||') {
                                if (strripos($k, 'input') === false) {
                                    ?>
                                    <input style="text-align: center; display: none; width: 80%; padding: 0px" type="text" id="txtColuna<?php echo $y ?>" placeholder='<?php echo $k ?>'/> 
                                    <a id="titulo<?php echo $y ?>" style="text-decoration:none; color: white; font-weight: bold; cursor: pointer">
                                        <span style="color: black;" class="glyphicon glyphicon-sort" aria-hidden="true"></span>
                                        <?php
                                        echo $k;
                                        ?>
                                    </a>
                                    <span onclick="busca(this, '<?php echo $y ?>')" style="cursor: pointer;text-align: right; " class="glyphicon glyphicon-search buscaGIS" aria-hidden="true"></span>
                                    <?php
                                } else {
                                    echo $k;
                                }
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
            </tbody>
        </table>
    </div>
    <?php
}
?>
<script>
    $(function () {
        $("#tabela input").keyup(function () {

            var index = $(this).parent().index();
            var nth = "#tabela td:nth-child(" + (index + 1).toString() + ")";
            var valor = $(this).val().toUpperCase();
            $("#tabela tbody tr").show();
            $(nth).each(function () {
                if ($(this).text().toUpperCase().indexOf(valor) < 0) {
                    $(this).parent().hide();
                }
            });
        });

        $("#tabela input").blur(function () {
            $(this).val("");
        });
    });

    function busca(gly, y) {
        $('.buscaGIS.glyphicon-remove')
                .removeClass('glyphicon-remove')
                .addClass('glyphicon-search');
        if (document.getElementById('txtColuna' + y).style.display == 'none') {
            $("th input ").hide();
            $("th a ").show();
            document.getElementById('txtColuna' + y).style.display = '';
            document.getElementById('txtColuna' + y).focus();
            $(gly)
                    .removeClass('glyphicon-search')
                    .addClass('glyphicon-remove')
            document.getElementById('titulo' + y).style.display = 'none';
            $("#tabela tbody tr").show();

        } else {
            $("#tabela tbody tr").show();

            gly.className = 'glyphicon glyphicon-search';
<?php
$y = 1;
foreach ($form['fields'] as $v) {
    ?>

                document.getElementById('txtColuna<?php echo $y ?>').style.display = 'none';
                document.getElementById('titulo<?php echo $y ?>').style.display = '';
    <?php
    $y++;
}
?>
        }
    }
</script>