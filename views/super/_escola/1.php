
            <div class="row">
                <div class="col-sm-6">

                    <?php
                    $optC = $model->chromeBooks($id_inst);
                    if (!empty($optC)) {
                        echo form::select('1[fk_id_ch]', $optC, 'ChromeBook', @$campos['fk_id_ch']);
                    } else {
                        echo "Esta Escola não possui ChromeBooks";
                    }
                    ?>
                </div>
                <div class="col-sm-6">
                    <?php echo form::select('1[fk_id_cd]', $model->chromeModel(), 'Modelo', @$campos['fk_id_cd']) ?>
                </div>
            </div>
         