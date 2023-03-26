<?php
@$funcionario = funcionarios::Get(tool::id_pessoa(), 'fk_id_pessoa', 'rm, tel1, pessoa.email')[0];


if (!empty($_POST['id_sup'])) {
    $id = $_POST['id_sup'];
} elseif (!empty($model->_last_id)) {
    $id = $model->_last_id;
}

if (!empty($id)) {
    @$campos = sql::get('super_suporte_trab', '*', ['id_sup' => $id], 'fetch');
} else {
    $campos = NULL;
}
$hiddenKey = DB::hiddenKey('super_suporte_trab');
?>
<div class="fieldBody">

    <?php ?>
    <div class="fieldTop">
        Solicitação <?php echo!empty(@$campos['id_sup']) ? ' - Serviço nº ' . @$campos['id_sup'] : '' ?>
    </div>
    <br /><br />
    <form id="prin" action="" method="POST" >
        <br /><br />
        <div class="fieldBorder2" >

            <div class="row">
                <div class="col-sm-6">
                    <?php formulario::input(NULL, ' Nome:', NULL, @$campos['n_pessoa'], ' readonly ') ?>
                </div>
                <div class="col-sm-6">
                    <?php echo formulario::select('1[fk_id_inst]', escolas::idInst(), 'Escola', @$campos['fk_id_inst'], NULL, NULL, !empty($campos['fk_id_inst']) ? 'disabled' : '') ?>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-sm-4">
                    <?php
                    formulario::selectDB('super_list_suporte', '1[tipo_sup]', 'Suporte em:', @$campos['tipo_sup']);
                    ?> 
                </div>
                <div class="col-sm-4">
                    <?php echo formulario::select('1[priori_sup]', ['Critica' => 'Critica', 'Alta' => 'Alta', 'Média' => 'Média'], 'Prioridade', @$campos['priori_sup']) ?>
                </div>
                <div class="col-sm-4">
                    <?php
                    echo formulario::select('1[status_sup]', ['Aberto' => 'Aberto', 'Em Espera' => 'Em Espera', 'Em Andamento' => 'Em Andamento', 'Finalizado' => 'Finalizado'], 'Status', @$campos['status_sup']);
                    ?>
                </div>
            </div>
            <br /><br />
            <?php
            if (@$campos['tipo_sup'] == 1) {
                ?>
                <div class="row">
                    <div class="col-sm-6">

                        <?php
                        $optC = $model->chromeBooks(@$campos['fk_id_inst']);
                        if (!empty($optC)) {
                            echo form::select('1[fk_id_ch]', $optC, 'ChromeBook', @$campos['fk_id_ch']);
                        } else {
                            echo "Esta Escola não possui ChromeBooks";
                        }
                        ?>
                    </div>
                    <div class="col-sm-6">
                        <?php echo form::select('1[fk_id_cd]', $model->chromeModel(), 'Modelo', @$campos['fk_id_cd']) ?>
                    </div>
                </div>
                <br />
                <?php
            }
            ?>
            <div class="row">
                <div class="col-sm-3">
                    <?php
                    formulario::input('1[dt_sup]', ' Data Inicial:', NULL, empty(@$campos['dt_prev_sup']) ? date("d/m/Y") : data::converteBr(@$campos['dt_prev_sup']), formulario::dataConf());
                    ?> 
                </div>
                <div class="col-sm-9">
                    <?php echo formulario::input('1[descr_sup]', 'Título', NULL, @$campos['descr_sup'], 'required', "Use poucas Palavras") ?> 
                </div>
            </div>
            <br /><br />
            <?php
            $descr = sql::get(['super_suport_diag', 'pessoa'], 'n_pessoa, lado, descr, data', ['fk_id_sup' => @$id, '>' => 'data']);
            ?>
            <div class="alert alert-warning">
                <?php
                foreach ($descr as $d) {
                    ?>
                    <br />
                    <div style="font-style: italic; color: black">
                        <pre style="border-radius: 10px"><span style="color: #0B94EF"><?php echo $d['lado'] ?> - <?php echo $d['n_pessoa'] ?> disse: (<?php echo data::converteBr(substr($d['data'], 0, 11)) ?>  <?php echo substr($d['data'], 11) ?>)</span><br /><?php echo $d['descr'] ?></pre>
                    </div>
                    <br />
                    <?php
                }
                ?>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-sm-11">
                    <input type="hidden" name="1[dt_sup]" value="<?php echo date("Y-m-d") ?>" />
                    <input type="hidden" name="1[dt_prev_sup]" value="<?php echo date("Y-m-d") ?>" />
                    <textarea name="descr" style="width: 100%; height: 60px" placeholder="Escreva Aqui"></textarea>
                </div>
                <div class="col-sm-1 text-center">
                    <input  style="width: 60px; height: 60px" name="salvEscola" class="btn btn-success" type="submit" value="Enviar" />
                </div>
            </div>
            <br /><br />
        </div>
        <br /><br />
        <?php
        if (@$campos['status_sup'] != 'Cancelado') {
            $disabled = 'disabled';
            ?>
            <div class="row">
                <div class="col-sm-3 text-center">
                    <!--
                    <?php if (!empty(@$campos['id_sup'])) { ?>
                                                    <input onclick="document.getElementById('statu').value = 'Finalizado'; document.getElementById('prin').submit();" class="btn btn-primary" type="submit" value="Encerrar o Chamado" />
                    <?php } ?>
                    -->
                </div>
                <div class="col-sm-3 text-center">
                    <?php if (!empty(@$campos['id_sup'])) { ?>
                        <a onclick="cancela()" class="btn btn-warning" >
                            Cancelar Solicitação
                        </a>
                    <?php } ?>
                </div>
                <div class="col-sm-3">
                    <?php if (!empty(@$campos['id_sup'])) { ?>
                        <a href="<?php echo HOME_URI; ?>/super/supprot?id=002<?php echo @$campos['rastro_sup'] . @$id ?>&p=1"  target="_blank">
                            <input  style="width: 150px" class="btn btn-primary" type="button" value="Gerar Protocolo" />
                        </a>
                    <?php } ?>
                </div>
                <div class="col-sm-3">
                    <a href="<?php echo HOME_URI; ?>/super/escolapesq" >
                        <input  style="width: 150px" class="btn btn-danger" type="button" value="Voltar" />
                    </a>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="row">
                <div class="col-sm-12 text-center">
                    <a href="<?php echo HOME_URI; ?>/super/escolapesq" >
                        <input  style="width: 150px" class="btn btn-danger" type="button" value="Voltar" />
                    </a>
                </div>
            </div>
            <?php
        }
        echo $hiddenKey;
        ?>

        <input type="hidden" name="1[ultimo_lado]" value="Secretária" />
        <input type="hidden" name="1[id_sup]" value="<?php echo @$id ?>" />
        <input type="hidden" name="1[rastro_sup]" value="<?php echo empty(@$campos['rastro_sup']) ? substr(uniqid(), 0, 4) : @$campos['rastro_sup'] ?>" />
        <input type="hidden" name="1[dt_sup]" value="<?php echo empty(@$campos['dt_sup']) ? date("Y-m-d") : @$campos['dt_sup'] ?>" />
        <input id="id_pessoa" style="width: 872px" type="hidden" name="1[fk_id_pessoa]" value="<?php echo tool::id_pessoa() ?>"/>
        <input id="id_inst" style="width: 872px" type="hidden" name="1[fk_id_inst]" value="<?php echo @$campos['fk_id_inst'] ?>"/>
        <input type="hidden" name="id_sup" value="<?php echo @$id ?>" />
    </form>
    <form id="canc" method="POST">
        <?php echo $hiddenKey ?>
        <input type="hidden" name="1[status_sup]" value="Cancelado" />
        <input type="hidden" name="1[id_sup]" value="<?php echo @$id ?>" />
        <input type="hidden" name="id_sup" value="<?php echo @$id ?>" />
        <input type="hidden" name="salvEscola" value="1" />
    </form>

</div>
<script>
    function cancela() {
        if (confirm("Cancelar esta Solicitação? ")) {
            document.getElementById('canc').submit();
        }
    }
    window.onload = conta();


</script>