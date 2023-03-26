<form method="POST">
    <br /><br /><br />
    <div class="row">
        <div class="col-lg-12">
            <?php echo formulario::input('1[titulo]', 'Título do Projeto', NULL, @$proj['titulo'], 'required') ?>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-lg-12">
            <?php echo formulario::input('1[tema]', 'Tema', NULL, @$proj['tema'], 'required') ?>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-lg-4">
            <?php echo formulario::input('1[dt_inicio]', 'Início do Projeto', NULL, data::converteBr(@$proj['dt_inicio']), 'required ' . formulario::dataConf(1)) ?>
        </div>
        <div class="col-lg-4">
            <?php echo formulario::input('1[dt_fim]', 'Término do Projeto', NULL, data::converteBr(date('Y') .'-08-30'), ' required readonly' . formulario::dataConf(2)) ?>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-lg-12">
            <?php echo formulario::input('1[recurso]', 'Recursos Necessários', NULL, @$proj['recurso'], 'required') ?>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-lg-12">
            <?php echo formulario::input('1[palavras]', 'Palavras-Chave (separadas por vírgula)', NULL, @$proj['palavras'], 'required') ?>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-lg-12 text-center">
            <input type="hidden" name="activeNav" value="3" />
            <?php echo formulario::hidden($hidden) ?>
            <input type="hidden" name="1[id_prof]" value="<?php echo $proj['id_prof'] ?>" />
            <?php echo DB::hiddenKey('giz_prof', 'replace') ?>
            <input class="btn btn-success" type="submit" value="Continuar" />
        </div>
    </div>
    <br /><br />
</form>


