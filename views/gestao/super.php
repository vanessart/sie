<?php
if (!empty(intval(@$_POST['id_super']))) {
    @$dados = $model->getSuper(@$_POST['id_super']);
}
?>
<div class="fieldBody">
    <?php
    funcionarios::autocomplete();
    ?>
    <div class="fieldTop">
        Cadastro de Setores de Supervisão
    </div>
    <div class="container">
        <div class="row">
            <form method="POST">
                <div class="col-lg-5">
                    <?php echo formulario::input('1[n_super]', 'Nome do Setor:', NULL, @$dados['n_super'], 'required') ?>
                </div>
                <div class="col-lg-7">
                    <?php echo formulario::input('1[rm]', 'Supervisor:', NULL, (empty(@$dados['rm']) ? '' : @$dados['n_pessoa'] . ' - ' . @$dados['rm']), 'id="busca" onkeypress="complete()"') ?>
                </div>
                <div class="col-lg-10">
                    <?php
                    $segmento = explode('|', @$dados['fk_id_tp_ens']);
                    $segList = sql::get('ge_tp_ensino');
                    foreach ($segList as $n) {
                        $checked = null;
                        $class = NULL;
                        foreach ($segmento as $a) {
                            if (($n['id_tp_ens'] == $a)) {
                                $checked = "checked";
                                $class = "alert-info";
                            }
                        }
                        ?>
                        <div class="col-lg-4">
                            <div class="input-group" style="width: 100%">
                                <label  style="width: 100%">
                                    <span class="input-group-addon <?php echo $class ?>" style="text-align: left; width: 20px">
                                        <input <?php echo @$checked ?> type="checkbox" aria-label="..." name="fk_id_tp_ens[]" value="<?php echo $n['id_tp_ens'] ?>">
                                    </span>
                                    <span class="input-group-addon <?php echo $class ?>" style="text-align: left">
                                        <?php echo $n['n_tp_ens'] ?>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-lg-1">
                    <?php
                    echo DB::hiddenKey('supervisao');
                    ?>
                    <input type="hidden" name="1[id_super]" value="<?php echo @$dados['id_super'] ?>" />
                    <button class="btn btn-success">
                        Salvar
                    </button>
                </div>
            </form>
            <div class="col-lg-1">
                <form>
                    <button class="btn btn-primary">
                        Limpar
                    </button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php $model->listSuper() ?>
            </div>
        </div>
    </div>
</div>

