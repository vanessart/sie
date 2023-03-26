<?php
if (!empty($_REQUEST['id_curso'])) {


    if (!empty($grup)) {
        ?>   
        <br /><br />
        <form method = "POST">

            <div class="row">
                <div class="col-md-12">
                    <?php formulario::input('1[n_comp]', 'Competência') ?>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-md-3">
                    <?php formulario::input('1[cod_comp]', 'Código') ?>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-md-12">
                    <textarea name="1[obs_comp]" style="width: 100%" placeholder="Descrição"></textarea>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-md-6 text-center">
                    <input onclick="$('#limp').submit()" class="btn btn-warning" type="button" value="Limpar" />
                </div>
                <div class="col-md-6 text-center">
                    <?php echo DB::hiddenKey('coord_competencia', 'replace') ?>
                    <input type="hidden" name="1[fk_id_gr]" value="<?php echo @$_REQUEST['id_gr'] ?>" />
                    <input type="hidden" name="id_gr" value="<?php echo @$_REQUEST['id_gr'] ?>" />
                    <input type="hidden" name="1[id_comp]" value="<?php echo @$_REQUEST['id_comp'] ?>" />
                    <input type="hidden" name="id_tp_ens" value="<?php echo @$_REQUEST['id_tp_ens'] ?>" />
                    <input type="hidden" name="id_curso" value="<?php echo @$_REQUEST['id_curso'] ?>" />
                    <input class="btn btn-success" type="submit" value="Salvar" />
                </div>
            </div>
        </form >
        <form id="limp" method="POST">
            <div class="col-md-6 text-center">
                <input type="hidden" name="modal" value="1" />
                <input type="hidden" name="id_gr" value="<?php echo @$_REQUEST['id_gr'] ?>" />
                <input type="hidden" name="id_tp_ens" value="<?php echo @$_REQUEST['id_tp_ens'] ?>" />
                <input type="hidden" name="id_curso" value="<?php echo @$_REQUEST['id_curso'] ?>" />
            </div>
        </form>
        <?php
    } else {
        ?>
        <div class="alert alert-warning">
            Não há disciplinas alocadas neste curso. Cadastre ao menos uma para continuar
        </div>
        <?php
    }
}