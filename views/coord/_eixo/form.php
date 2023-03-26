<br /><br />
<form method = "POST">

    <div class="row">
        <div class="col-md-9">
            <?php formulario::input('1[n_ei]', 'Eixo') ?>
        </div>
        <div class="col-md-3">
            <?php formulario::input('1[cod_ei]', 'Código') ?>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-md-12">
            <textarea name="1[obs_ei]" style="width: 100%" placeholder="Descrição"></textarea>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-md-6 text-center">
            <input onclick="$('#limp').submit()" class="btn btn-warning" type="button" value="Limpar" />
        </div>
        <div class="col-md-6 text-center">
            <?php echo DB::hiddenKey('coord_eixo', 'replace') ?>
            <input type="hidden" name="1[id_ei]" value="<?php echo @$_REQUEST['id_ei'] ?>" />
            <input type="hidden" name="1[fk_id_curso]" value="<?php echo @$_REQUEST['id_curso'] ?>" />
            <input type="hidden" name="id_curso" value="<?php echo @$_REQUEST['id_curso'] ?>" />
            <input class="btn btn-success" type="submit" value="Salvar" />
        </div>
    </div>
</form >
<form id="limp" method="POST">
    <input type="hidden" name="id_curso" value="<?php echo @$_REQUEST['id_curso'] ?>" />
    <input type="hidden" name="modal" value="1" />
</form>
