<?php
$sql = " select * from coord_grupo cc "
        . " join ge_disciplinas gd on gd.id_disc = cc.fk_id_disc "
        . " order by n_disc, n_gr ";
$query = $model->db->query($sql);
$d = $query->fetchAll();
foreach ($d as $v) {
    $grup[$v['n_disc']][$v['id_gr']] = $v['n_gr'];
}
?>
<div class="fieldBody">
    <div class="fieldTop">
        Habilidades
    </div>
    <br /><br />
    <div class="row">
        <div class="col-md-4">
            <?php
            if (empty($_REQUEST['fk_id_gr'])) {
                ?>
            <div class="input-group">
                <div class="input-group-addon">
                    Grupo
                </div>
                <select style="width: 100%" name="fk_id_gr">
                    <option></option>
                    <?php
                    foreach ($grup as $ki => $i) {
                        ?>
                        <optgroup label="<?php echo $ki ?>">
                            <?php
                            foreach ($i as $k => $v) {
                                ?>
                                <option <?php echo @$_REQUEST['fk_id_gr'] == $k ? 'selected' : '' ?> value="<?php echo $k ?>">
                                    <?php echo $v ?>
                                </option>
                                <?php
                            }
                            ?>
                        </optgroup>
                        <?php
                    }
                    ?>

                </select>
            </div>
                <?php
            } else {
                ?>
                <form method="POST">
                    <input style="width: 100%" class="btn btn-info" type="submit" value="Trocar de Grupo" />
                </form>
                <?php
            }
            ?>
        </div>
        <div class="col-md-2">
            <?php
            if (!empty($_REQUEST['id_comp'])) {
                ?>
                <input style="width: 100%" class="btn btn-primary" type="submit" onclick=" $('#myModal').modal('show');" value="Nova Habilidade" />
                <?php
            } else {
                ?>
                <input class="btn btn-block" style="cursor: default" type="button" value="Nova Habilidade" />
                <?php
            }
            ?>
        </div>
        <div id="cp" class="col-md-4">
            <?php
            if (!empty($_REQUEST['fk_id_gr'])) {
                include ABSPATH . '/views/coord/hab_comp.php';
            }
            ?>
        </div>

    </div>
</div>
<?php
if (empty($_REQUEST['modal'])) {
    $modal = 1;
}
tool::modalInicio('width: 95%', @$modal);
include ABSPATH . '/views/coord/hab_form.php';
tool::modalFim();
javaScript::divDinanmica('fk_id_gr', 'cp', HOME_URI . '/coord/habcomp')
?>