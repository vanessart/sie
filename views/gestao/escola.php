<?php
    $inst = inst::get(tool::id_inst());
    @$escola = escolas::get($inst['id_inst'], 'id_inst');

?>

<div class="field">
    <div class="row">
        <div class="col-lg-6">
            <?php formulario::input(NULL, 'Escola:', NULL, $inst['n_inst'], 'disabled') ?>
        </div>
        <div class="col-lg-6">
            <?php formulario::input(NULL, 'E-mail:', NULL, $inst['email'], 'disabled') ?>
        </div>
    </div>
    <br />

    <div id="cie" class="row fieldWhite">

        <form method="POST">
            <div class="row">
                <div class="col-lg-12">
                    <?php formulario::input('1[ato_cria]', 'Ato de Criação: ', NULL, @$escola['ato_cria']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php formulario::input('1[ato_municipa]', 'Ato de Municipalização:', NULL, @$escola['ato_municipa']) ?>
                </div>
            </div>

            <div class="col-lg-12 text-center">
                <input type="hidden" name="1[id_escola]" value="<?php echo @$escola['id_escola'] ?>" />
                <input type="hidden" name="1[fk_id_inst]" value="<?php echo tool::id_inst() ?>" />
                <?php echo DB::hiddenKey('ge_escolas', 'replace') ?>
                <button class="btn btn-success">
                    Salvar
                </button>

            </div>
        </form>

    </div>

</div>

