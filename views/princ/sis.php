<?php
if (!empty($_POST['id_sistema']) || !empty($_POST[1]['id_sistema'])) {
    @$id = !empty($_POST['id_sistema']) ? $_POST['id_sistema'] : $_POST[1]['id_sistema'];
    $dados = sistema::get($id);
}
@$sistema = $_POST['sistema'];
?>
<div class="fieldTop">
    Cadastro de Subsistemas
</div>
<div class="field row">
    <form method="POST">
        <div class="col-lg-5">
            <div class="input-group">
                <span class="input-group-addon">
                    Subsistema:
                </span> 
                <input class="form-control" type="text" name="sistema" value="<?php echo @$sistema ?>"  >
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
                    <?php echo formulario::input('1[n_sistema]', 'Subsistema', NULL, @$dados['n_sistema'], 'required')
                    ?>
                </div>
                <div class="col-lg-2">
                    <?php echo formulario::input('1[arquivo]', 'Arquivo', NULL, @$dados['arquivo'])
                    ?>
                </div>
                <div class="col-lg-2">
                    <?php echo formulario::selectDB('framework', '1[fk_id_fr]', 'Framework', @$dados['fk_id_fr'], 'required') ?>
                </div>
                <div class="col-lg-2">
                    <?php echo formulario::input('1[fkid]', 'ID do Subsistema', NULL, @$dados['fkid'], 'required', '0')
                    ?>
                </div>
                <div class="col-lg-2">
                    <?php formulario::select('1[ativo]', [1 => 'Sim', 0 => 'Não'], 'Ativo', @$dados['ativo'])
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php echo formulario::input('1[descr_sistema]', 'Descrição', NULL, @$dados['descr_sistema'])
                    ?>
                </div>
            </div>
            <div class="fieldTop">
                Níveis de Acesso
            </div>
            <div class="row">
                <?php
                $nivelSet = unserialize(@$dados['niveis']);
                $nivel = sql::get('nivel', '*', ['ativo' => 1,'>'=>'n_nivel']);
                foreach ($nivel as $n) {
                    $checked = null;
                    $class = NULL;
                    if (!empty($nivelSet)) {
                        if (in_array($n['id_nivel'], $nivelSet)) {
                            $checked = "checked";
                            $class = "alert-info";
                        }
                    }
                    ?>
                    <div class="col-lg-2">
                        <div class="input-group" style="width: 100%">
                            <label  style="width: 100%">
                                <span class="input-group-addon <?php echo $class ?>" style="text-align: left; width: 20px">
                                    <input <?php echo @$checked ?> type="checkbox" aria-label="..." name="niveis[]" value="<?php echo $n['id_nivel'] ?>">
                                </span>
                                <span class="input-group-addon <?php echo $class ?>" style="text-align: left">
                                    <?php echo $n['n_nivel'] ?>
                                </span>
                            </label>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="row">
                <div class="col-lg-6 offset4" style="text-align: center" >
                    <?php
                    formulario::button('Fechar', NULL, 'button', NULL, 'onclick="document.getElementById(\'form\').style.display=\'none\'"');
                    ?>
                </div>
                <div class="col-lg-6 offset4" style="text-align: center">
                    <input type="hidden" name="1[id_sistema]" value="<?php echo @$dados['id_sistema'] ?>" />
                    <input type="hidden" name="sistemaIns" value="1" />
          <?php
                    echo DB::hiddenKey('sistemaIns');
                    echo formulario::button();
                    ?>
                </div>

            </div>
        </div>
        <br />
    </form>

    <?php
}
sistema::relat(@$sistema);
?>