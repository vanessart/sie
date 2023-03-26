<?php
if (!empty($_POST['id_inst']) || !empty($_POST[1]['id_inst'])) {
    @$id = !empty($_POST['id_inst']) ? $_POST['id_inst'] : $_POST[1]['id_inst'];
    $dados = inst::get($id);
}
@$inst = $_POST['inst'];
?>
<br />
<div class="field row">
    <div class="fieldTop">
        Cadastro de Instâncias
    </div>
    <form method="POST">
        <div class="col-lg-5">
            <div class="input-group">
                <span class="input-group-addon">
                    Instância:
                </span> 
                <input class="form-control" type="text" name="inst" value="<?php echo @$inst ?>"  >
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
                <div class="col-lg-12">
                    <?php echo formulario::input('1[n_inst]', 'Nome', NULL, @$dados['n_inst'], 'required')
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="input-group">
                        <div class="input-group-addon">
                           Nome da Instância no Sistema Autenticador
                        </div>
                        <select name="1[fkid_inst_aut]">
                            <option></option>
                            <?php
                            foreach (autenticador::instancia() as $v) {
                                ?>
                            <option <?php echo $v['id_inst'] == @$dados['fkid_inst_aut']?'selected':'' ?> value="<?php echo $v['id_inst'] ?>"><?php echo $v['n_inst'] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <?php echo formulario::selectDB('tipo_inst', '1[fk_id_tp]', 'Tipo', @$dados['fk_id_tp'], 'required') ?>
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
                    <input type="hidden" name="1[id_inst]" value="<?php echo @$dados['id_inst'] ?>" />
                    <?php
                    echo DB::hiddenKey('instancia', 'replace');
                    echo formulario::button();
                    ?>
                </div>

            </div>
        </div>
        <br />
    </form>

    <?php
}
inst::relat(@$inst);
?>