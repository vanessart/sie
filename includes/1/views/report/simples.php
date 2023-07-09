<?php
if(empty($form['array'])){
    $form['array']=[];
}
if(count($form['array'])>10){
?>
<script>
    $(document).ready(function () {
        $("#myInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
<div class="row">
    <div class="col-sm-4 offset-8">
        <input class="form-control" id="myInput" type="text" placeholder="Pesquisa..">
    </div>
</div>
<br>
<?php
}
?>
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
        <table style="background-color: white" class="table table-bordered table-hover sortable" id="tabela">
            <thead>
                <tr>
                    <?php
                    $y = 1;
                    foreach ($form['fields'] as $k => $v) {
                        ?>
                    <th class="table-active" style="cursor: pointer">
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
            <tbody id="myTable">

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




