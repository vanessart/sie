<div class="container">
    <div class="field row">
        <form method="POST">
            <div class="col-md-6">
                <?php
                if ($grade['id_ta'] == 1) {
                    formulario::selectDB('ge_disciplinas', '1[fk_id_disc]', 'Alocar em <b>' . $grade['n_grade'] . '</b>: ', @$aloca['fk_id_disc'], ' style="width: 100%" ');
                    ?>
                    <input type="hidden" name="1[fk_id_grade]" value="<?php echo $grade['id_grade'] ?>" />
                    <?php
                    echo DB::hiddenKey('ge_aloca_disc', 'replace');
                } elseif ($grade['id_ta'] == 2) {
                    formulario::selectDB('ge_habilidades', '1[fk_id_hab]', 'Alocar em <b>' . $grade['n_grade'] . '</b>: ', @$aloca['fk_id_hab'], ' style="width: 100%" ');
                    ?>
                    <input type="hidden" name="1[fk_id_grade]" value="<?php echo $grade['id_grade'] ?>" />
                    <?php
                    echo DB::hiddenKey('ge_aloca_hab', 'replace');
                }
                ?>
            </div>
             <div class="col-md-6">
                 <?php formulario::checkbox('1[nucleo_comum]', 1, "Núcleo Comum", @$aloca['nucleo_comum']);  ?>
             </div>
            <br /><br />
            <?php if ($grade['id_ta'] == 1) { ?>
                <div class="col-md-6">
                    <?php echo formulario::selectNum('1[aulas]', 50, 'Aulas: ', @$aloca['aulas']) ?>
                </div>
            <?php } ?>
            <div class="col-md-6">
                <?php echo formulario::selectNum('1[ordem]', 50, 'Ordem: ', @$aloca['ordem']) ?>
            </div>
            <br /><br />
            <div class="col-md-6 text-center">
                <input type="hidden" name="1[id_aloca]" value="<?php echo @$aloca['id_aloca'] ?>" />
                <input type="hidden" name="id_grade" value="<?php echo $_POST['id_grade'] ?>" />
                <input type="hidden" name="aba" value="gd" />
                <button class="btn btn-success">
                    Alocar
                </button>
            </div>
        </form>
        <div class="col-md-6 text-center">
            <form method="POST">
                <input type="hidden" name="id_grade" value="<?php echo $_POST['id_grade'] ?>" />
                <input type="hidden" name="aba" value="gd" />
                <button class="btn btn-primary">
                    Limpar
                </button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php $model->listAloca(@$id_grade) ?>
        </div>
    </div>
</div>
