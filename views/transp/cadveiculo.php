<?php
$id_tv = filter_input(INPUT_POST, 'id_tv', FILTER_SANITIZE_NUMBER_INT);
?>
<div class="fieldBody">
    <div class="fieldTop">
        Cadastro de Veículo
    </div>
    <br /><br />
        <form method="POST">
        <input type="hidden" name="modal" value="1" />
        <input class="btn btn-success" type="submit"  value="Cadastrar Veículo" />
    </form>

    <?php
    if (empty($_POST['modal'])) {
        $md = 1;
    } else {
        $md = NULL;
        $dados = sql::get('transp_veiculo', '*', ['id_tv' => $id_tv], 'fetch');
    }
    tool::modalInicio('width: 95%', $md);
    ?>
    <br /><br />
    <form method="POST">
        <div class="row">
            <div class="col-sm-3">
                <?php echo form::input('1[n_tv]', 'Nome do Veículo', @$dados['n_tv']) ?>
            </div>
            <div class="col-sm-3">
                <?php echo form::input('1[placa]', 'Placa', @$dados['placa']) ?>
            </div>
            <div class="col-sm-3">
                <?php echo form::selectNum('1[capacidade]', [1, 50], 'Capacidade', @$dados['capacidade']) ?>
            </div>
             <div class="col-sm-3">
                 <?php echo form::select('1[cadeirante]', [0 => 'Não', 1 => 'Sim'], 'Cadeirante', @$dados['cadeirante']); ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-3">
                <?php echo form::select('1[acessibilidade]', [0 => 'Não', 1 => 'Sim'], 'Necessidades Especiais', @$dados['acessibilidade']); ?>
            </div>
            <div class="col-sm-3">
                <?php echo form::selectDB('transp_tipo_veiculo', '1[fk_id_tiv]', 'Tipo', @$dados['fk_id_tiv']) ?>
            </div>
            <div class="col-sm-3">
                <?php echo form::selectDB('transp_status_veiculo', '1[fk_id_sv]', 'Situação', @$dados['fk_id_sv']) ?>
            </div>
            <div class="col-sm-3">
                <?php echo form::selectDB('transp_empresa', '1[fk_id_em]', 'Empresa', @$dados['fk_id_em']) ?>
            </div>
        </div>
        <br /><br />
        <?php
        echo form::hidden([
            '1[id_tv]' => @$dados['id_tv']
        ]);
      //  echo DB::hiddenKey('transp_veiculo', 'replace');
        ?>
        <div class="row">
            <div class="col-sm-6 text-center">
                <?php
                echo form::button('Salvar');
                ?>
                <input type="hidden" name="cadastraveiculo" value="cadastraveiculo" />
            </div>
            <div class="col-sm-6 text-center">
                <a class="btn btn-warning" onclick="document.getElementById('lp').submit()" href="#">Limpar</a>
            </div>
        </div>
    </form>
    <form id="lp" method="POST">
        <input type="hidden" name="modal" value="1" />
    </form>
    <?php
    tool::modalFim();
    ?>
    <br /><br />
    <?php
    $form = $model->listaVeiculos();

    tool::relatSimples($form);
    ?>
</div>