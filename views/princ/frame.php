<?php
if (!empty($_POST['id_fr']) || !empty($_POST[1]['id_fr'])) {
    @$id = !empty($_POST['id_fr']) ? $_POST['id_fr'] : $_POST[1]['id_fr'];
    $dados = frame::get($id);
}
@$fr = $_POST['fr'];
?>
<div class="fieldBody">
<div class="fieldTop">
    Cadastro de Frameworks
</div>
<div class=" row">
    <form method="POST">
        <div class="col-lg-5">
            <div class="input-group" >
                <span class="input-group-addon">
                    Framework
                </span> 
                <input class="form-control" type="text" name="fr" value="<?php echo @$fr ?>"  >
                <span class="input-group-addon"  >
                    <button type="submit" class="btn btn-link btn-xs">
                        Buscar
                    </button>
                </span>
            </div>
        </div>
        <div class="col-lg-2"></div>
    </form>
    <div class="col-lg-2">
        <form method="POST">
            <button type="submit" name="novo" value="1" class="btn btn-default">
                Novo Cadastro
            </button>
        </form>
    </div>
    <div class="col-lg-3">
        <form method="POST">
            <button type="submit" class="btn btn-default">
                Limpar
            </button>
        </form>
    </div>
</div>
<br />
<?php
if (!empty($_POST['novo']) || !empty($dados)) {
    ?>
    <form id="form" method="POST">
        <div class="field">
            <div class="row">
                <div class="col-lg-12"></div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <?php echo formulario::input('1[n_fr]', 'Nome', NULL, @$dados['n_fr'], 'required')
                    ?>
                </div>
                <div class="col-lg-6">
                    <?php echo formulario::input('1[end_fr]', 'Endereço', NULL, @$dados['end_fr'])
                    ?>
                </div>
                <div class="col-lg-2">
                    <?php formulario::select('1[ativo]', [1 => 'Sim', 0 => 'Não'], 'Ativo', @$dados['ativo'])
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 offset4" style="text-align: center" >
                    <?php
                    formulario::button('Fechar', NULL, 'button', NULL, 'onclick="document.getElementById(\'form\').style.display=\'none\'"');
                    ?>
                </div>
                <div class="col-lg-6 offset4" style="text-align: center">
                    <input type="hidden" name="1[id_fr]" value="<?php echo @$dados['id_fr'] ?>" />
                    <?php
                    echo DB::hiddenKey('framework', 'replace');
                    echo formulario::button();
                    ?>
                </div>

            </div>
        </div>
        <br />
    </form>

    <?php
}
frame::relat(@$fr);
?>
</div>