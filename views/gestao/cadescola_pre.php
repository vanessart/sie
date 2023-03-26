<?php

if (!empty($_POST['id_predio'])) {
    $predio = sql::get(['predio', 'instancia_predio'], '*', ['id_predio' => $_POST['id_predio']], 'fetch', 'left');
}

formulario::telefonesScript(@$_POST['id_predio'],['id_predio'=>@$_POST['id_predio'], 'aba'=>'predio', 'id_inst'=>$_POST['id_inst']]);
?>
<div class="fieldBody">
    <div id="busc" class="field" style="display: <?php echo!empty($_POST['id_predio']) ? 'none' : '' ?>">
        <div class="row">
            <form method="POST">
                <div class="col-lg-8">
                    <?php echo formulario::input('search', 'Buscar um prédio não relacionado a esta escola:') ?>
                </div>
                <input type="hidden" name="id_inst" value="<?php echo $_POST['id_inst'] ?>" />
                <input type="hidden" name="aba" value="predio" />
                <div class="col-lg-2">
                    <button class="btn btn-success">
                        Buscar
                    </button>
                </div>
            </form>
            <div class="col-lg-2">
                <button onclick="document.getElementById('novo').style.display = '';document.getElementById('busc').style.display = 'none';" class="btn btn-info">
                    Incluir Novo Predio
                </button>
            </div>
        </div>
    </div>
    <form method="POST">
        <div class="field" id="novo" style="display: <?php echo empty($_POST['id_predio']) ? 'none' : '' ?>">
            <div class="row">
                <div class="col-lg-8">
                    <?php formulario::input('1[n_predio]', 'Nome do Predio:', NULL, (empty($predio['n_predio']) ? 'SEDE - ' . $inst['n_inst'] : @$predio['n_predio']), 'required') ?>
                </div>
                <div class="col-lg-2">
                    <?php formulario::input('1[sigla]', 'Sigla:', NULL, @$predio['sigla']) ?>
                </div>
                <div class="col-lg-2">
                    <?php formulario::select('1[ativo]', ['Não', 'Sim'], 'Ativo:', @$predio['ativo'], 'required') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php formulario::input('1[descricao]', 'Descrição:', NULL, @$predio['descricao']) ?>
                </div>            
            </div>
            <div class="row">
                <div class="col-lg-2">
                    <?php formulario::input('1[cep]', 'CEP:', NULL, @$predio['cep']) ?>
                </div>
                <div class="col-lg-8">
                    <?php formulario::input('1[logradouro]', 'Logradouro:', NULL, @$predio['logradouro']) ?>
                </div>
                <div class="col-lg-2">
                    <?php formulario::input('1[num]', 'Nº:', NULL, @$predio['num']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <?php formulario::input('1[complemento]', 'Complemento:', NULL, @$predio['complemento']) ?>
                </div>            
            </div>
            <div class="row">
                <div class="col-lg-5">
                    <?php formulario::input('1[bairro]', 'Bairro:', NULL, @$predio['bairro']) ?>
                </div>
                <div class="col-lg-5">
                    <?php formulario::input('1[cidade]', 'Cidade:', NULL, @$predio['cidade']) ?>
                </div>
                <div class="col-lg-2">
                    <?php formulario::input('1[uf]', 'UF:', NULL, @$predio['uf']) ?>
                </div>
            </div>


            <div class="row" style="margin-top: 10px">
                <div class="col-md-12 art-button">
                    <span style="color: white" aria-hidden="true">
                        Telefones
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <input type="hidden" name="novo" value="1" />
                    <?php
                    if (!empty($_POST['id_predio'])) {
                        $tel = sql::get('telefones', '*', ['fkid ' => $_POST['id_predio'], 'fk_id_tp' => 1]);
                    }
                    formulario::telefones(@$tel);
                    ?>
                </div>
            </div>          
            <div class="row" style="margin-top: 10px">
                <div class="col-md-12 art-button"></div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <label>
                        <input <?php echo!empty($predio['id_ip']) ? 'checked' : '' ?> checked type="checkbox" name="setPredio" value="1" />
                        &nbsp;&nbsp;
                        A <?php echo $inst['n_inst'] ?> utiliza este <strong>PRÉDIO</strong>
                    </label>
                </div>
                <div class="col-lg-6">
                    <label>
                        <input type="hidden" name="2[id_ip]" value="<?php echo @$predio['id_ip'] ?>" />
                        <input type="hidden" name="2[fk_id_inst]" value="<?php echo $_POST['id_inst'] ?>" />
                        <input type="hidden" name="2[fk_id_predio]" value="<?php echo @$predio['id_predio'] ?>" />
                        <input type="hidden" name="2[sede]" value="0" />
                        <input <?php echo @$predio['sede'] == 1 ? 'checked' : '' ?> type="checkbox" name="2[sede]" value="1" />
                        &nbsp;&nbsp;
                        Este <strong>PRÉDIO</strong> é a SEDE da <?php echo $inst['n_inst'] ?>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 text-center">
                    <button type="button" onclick="document.getElementById('novo').style.display = 'none';document.getElementById('busc').style.display = '';" class="btn btn-danger">
                        Fechar
                    </button>
                </div>
                <div class="col-lg-6 text-center">
                    <input type="hidden" name="1[id_predio]" value="<?php echo @$predio['id_predio'] ?>" />
                    <?php echo DB::hiddenKey('setPred') ?>
                    <input type="hidden" name="id_inst" value="<?php echo $_POST['id_inst'] ?>" />
                    <input type="hidden" name="aba" value="predio" />
                    <button class="btn btn-success">
                        Salvar
                    </button>
                </div>
            </div>
        </div>
    </form>
    <br />
    <?php
    $model->listPredio($_POST['id_inst'], @$_POST['search']);
    ?>
</div>
