<div class="container">
    <div id="nvseg" class="row" style="display: <?php echo (!empty(@$_POST['id_tp_ens']) || !empty($_POST['limp'])) ? 'none' : '' ?>">
        <div class="col-lg-12">
            <div class="text-center">
                <button class="btn btn-info" onclick="document.getElementById('seg').style.display = '';document.getElementById('nvseg').style.display = 'none';">
                    Novo Segmento
                </button>
            </div>
        </div>
    </div>
    <br />
    <div id="seg" class="row" style="display: <?php echo (!empty(@$_POST['id_tp_ens']) || !empty($_POST['limp'])) ? '' : 'none' ?>">
        <form method="POST">
            <div class="col-lg-4">
                <?php echo formulario::input('1[n_tp_ens]', 'Segmeto:', NULL, @$seg['n_tp_ens'], 'required') ?>
            </div>
            <div class="col-lg-2">
                <?php echo formulario::input('1[sigla]', 'Sigla:', NULL, @$seg['sigla'], 'required') ?>
            </div>
            <div class="col-lg-1">
                <?php
                echo DB::hiddenKey('ge_tp_ensino', 'replace');
                ?>
                <input type="hidden" name="1[id_tp_ens]" value="<?php echo @$seg['id_tp_ens'] ?>" />
                <button class="btn btn-success">
                    Salvar
                </button>
            </div>
        </form>
        <div class="col-lg-1">
            <form method="POST">
                <input type="hidden" name="limp" value="1" />
                <button class="btn btn-primary">
                    Limpar
                </button>
            </form>
        </div>
        <div class="col-lg-1">
            <button class="btn btn-danger" onclick="document.getElementById('seg').style.display = 'none';document.getElementById('nvseg').style.display = '';">
                Fechar
            </button>               
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php $model->listTpEnsino() ?>
        </div>
    </div>
</div>
