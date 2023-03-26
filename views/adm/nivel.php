<?php
if (!empty($_POST['id_nivel']) || !empty($_POST[1]['id_nivel'])) {
    @$id = !empty($_POST['id_nivel']) ? $_POST['id_nivel'] : $_POST[1]['id_nivel'];
    $dados = nivel::get($id);
}
@$nivel = $_POST['nivel'];
?>
<br />
<div class="field row">

    <div class="fieldTop">
        Cadastro de Nìveis
    </div>
    <form method="POST">
        <div class="col-lg-5">
            <div class="input-group">
                <span class="input-group-addon">
                    Nível
                </span> 
                <input class="form-control" type="text" name="nivel" value="<?php echo @$nivel ?>"  >
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
                <div class="col-lg-6">
                    <?php echo formulario::input('1[n_nivel]', 'Nome', NULL, @$dados['n_nivel'], 'required')
                    ?>
                </div>
                <div class="col-lg-4">
                    <?php
                    $niveisAut = autenticador::niveis();
                    ?>
                    <div class="input-group">
                        <div class="input-group-addon">
                            Nível no Autenticador
                        </div>
                        <select name="1[fk_id_nivel]">
                            <option value="0"></option>
                            <?php
                            foreach ($niveisAut as $v) {
                                ?>
                                <option <?php echo $v['id_nivel'] == @$dados['fk_id_nivel'] ? 'selected' : '' ?> value="<?php echo $v['id_nivel'] ?>"><?php echo $v['n_nivel'] ?></option>
                                <?php
                            }
                            ?>
                        </select>       
                    </div>
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
                    <input type="hidden" name="1[id_nivel]" value="<?php echo @$dados['id_nivel'] ?>" />
                    <?php
                    echo DB::hiddenKey('nivel', 'replace');
                    echo formulario::button();
                    ?>
                </div>

            </div>
        </div>
        <br />
    </form>

    <?php
}
nivel::relat(@$nivel);
?>