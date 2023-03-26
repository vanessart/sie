<?php
funcionarios::autocomplete(NULL, 1);
$escola = new escola();
$comp = (array) $escola->endereco();
@$funcionario = funcionarios::Get(tool::id_pessoa(), 'fk_id_pessoa', 'rm, tel1, pessoa.email')[0];
$tipo_sup = filter_input(INPUT_POST, 'tipo_sup', FILTER_SANITIZE_NUMBER_INT);

$resp = $model->pegarespon();

if (!empty($_POST['id_sup'])) {
    $id = $_POST['id_sup'];
} elseif (!empty($model->_last_id)) {
    $id = $model->_last_id;
}

if (!empty($id)) {
    @$campos = sql::get('dttie_suporte_trab', '*', ['id_sup' => $id], 'fetch');
    $tipo_sup = $campos['tipo_sup'];
} else {
    $campos = NULL;
}

$hiddenKey = DB::hiddenKey('dttie_suporte_trab');
?>
<div class="fieldBody">
    <?php ?>
    <div class="fieldTop">
        <div class="alert alert-danger" style="text-align: center; font-weight: bold; font-size: 1.2em">
            A  solicitação de professor CADAMPE deve ser realizada pelo SUBSISTEMA CADAMPE
        </div>
        Suporte <?php echo!empty(@$campos['id_sup']) ? ' - Serviço nº ' . @$campos['id_sup'] : '' ?>
    </div>
    <br />
    <div class="row">
        <div class="col-md-7">
        </div>
        <div class="col-md-5">
            <?php
            $suporteEsc = sql::idNome('dttie_list_suporte');
            unset($suporteEsc[76]);
            echo form::select('tipo_sup', $suporteEsc, 'Suporte em:', $tipo_sup, 1, null, (!empty($id) ? 'disabled' : '') . ' required');
            ?>
        </div>
    </div>
    <br />
    <?php
    if ($tipo_sup) {
        ?>
        <form id="prin" action="" method="POST" >
            <div>
                <?php
                echo form::hidden([
                    '1[tipo_sup]' => $tipo_sup
                ]);
                // formulario::selectDB('dttie_list_suporte', '1[tipo_sup]', 'Suporte em:', @$campos['tipo_sup'], (!empty($id) ? 'disabled' : '') . ' required');
                ?> 
                <input type="hidden" name="1[priori_sup]" value="Media" />
            </div>
            <div class="fieldBorder2" >
                <div class="row">
                    <?php
                    if ($tipo_sup == 76) {
                        ?>
                        <div class="col-md-5">
                            <?php
                            form::select('1[tipo_cadamp]', $model->pegatiposubstituicao(), 'Tipo Substituição CADAMPE', @$campos['tipo_cadamp'], null, null, ' required');
                            ?> 
                        </div>
                        <?php
                    } else {
                        ?>
                        <input type="hidden" name="1[tipo_cadamp]" value= 15 />
                        <?php
                    }
                    ?>
                    <div class="col-md-7">
                        <?php echo formulario::input('1[descr_sup]', 'Título', NULL, @$campos['descr_sup'], 'required', "Use poucas Palavras") ?> 
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col-md-4">
                        <?php
                        formulario::input("1[dt_prev_sup]", 'Solicitação para o dia ', NULL, data::converteBr(empty(@$campos['dt_prev_sup']) ? date("Y-m-d") : @$campos['dt_prev_sup']), formulario::dataConf(10) . ' id="10"');
                        ?>
                    </div>
                    <?php
                    if (!empty($id)) {
                        if (!empty($campos['resp_sup'])) {
                            ?>
                            <div class="col-md-5" style="border: 2px solid buttonshadow; height: 30px">
                                <?php echo 'Atribuído a: ' . $resp[$campos['resp_sup']] ?>
                            </div>  
                            <?php
                        } else {
                            ?>
                            <div class="col-md-5" style="border: 2px solid buttonshadow; height: 30px">
                                <?php echo 'Atribuído a: - ' ?>
                            </div> 
                            <?php
                        }
                        ?>
                        <div class="col-md-3">
                            <?php echo formulario::select('1[status_sup]', ['Não Visualizado' => 'Não Visualizado', 'Finalizado' => 'Finalizado', 'Cancelado' => 'Cancelado'], 'Status', @$campos['status_sup']) ?>
                            <!--
                            echo formulario::input(NULL, 'Status', NULL, @$campos['status_sup'], 'disabled');
                            $campos['resp_sup'];
                            <input id="statu" type="hidden" name="1[status_sup]" value="<?php echo @$campos['status_sup'] ?>" />
                            -->
                        </div>
                        <?php
                    } else {
                        ?>
                        <input type="hidden" name="1[status_sup]" value="Não Visualizado" />
                        <?php
                    }
                    ?> 
                </div>
                <br /><br />
                <?php
                $descr = sql::get(['dttie_suport_diag', 'pessoa'], 'n_pessoa, lado, descr, data', ['fk_id_sup' => @$id, '>' => 'data']);
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
                    <div class="col-md-11">
                        <input type="hidden" name="1[dt_sup]" value="<?php echo date("Y-m-d") ?>" />
                        <textarea name="descr" style="width: 100%; height: 60px" placeholder="Escreva Aqui"></textarea>
                    </div>
                    <div class="col-md-1 text-center">
                        <?php
                        //     if (@$campos['status_sup'] != 'Cancelado' && @$campos['status_sup'] != 'Finalizado') {
                        if (@$campos['status_sup'] != 'Cancelado') {
                            ?>
                            <input  style="width: 60px; height: 60px" name="salvEscola" class="btn btn-success" type="submit" value="Enviar" />
                            <?php
                        }
                        ?>
                    </div>
                </div>
                <br /><br />
            </div>
            <br /><br />
            <?php
            if (@$campos['status_sup'] != 'Cancelado' && @$campos['status_sup'] != 'Finalizado') {
                $disabled = 'disabled';
                ?>
                <div class="row">
                    <div class="col-md-3 text-center">
                        <?php if (!empty(@$campos['id_sup']) && count(@$descr) > 1) { ?>
                            <input onclick="document.getElementById('statu').value = 'Finalizado'; document.getElementById('prin').submit();" class="btn btn-primary" type="submit" value="Encerrar o Chamado" />
                        <?php } ?>
                    </div>
                    <div class="col-md-3 text-center">
                        <?php if (!empty(@$campos['id_sup'])) { ?>
                            <a onclick="cancela()" class="btn btn-warning" >
                                Cancelar Solicitação
                            </a>
                        <?php } ?>
                    </div>
                    <div class="col-md-3">
                        <?php if (!empty(@$campos['id_sup'])) { ?>
                            <a href="<?php echo HOME_URI; ?>/dttie/supprot?id=002<?php echo @$campos['rastro_sup'] . @$id ?>&p=1"  target="_blank">
                                <input  style="width: 150px" class="btn btn-primary" type="button" value="Gerar Protocolo" />
                            </a>
                        <?php } ?>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo HOME_URI; ?>/dttie/escolapesq" >
                            <input  style="width: 150px" class="btn btn-danger" type="button" value="Voltar" />
                        </a>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <a href="<?php echo HOME_URI; ?>/dttie/escolapesq" >
                            <input  style="width: 150px" class="btn btn-danger" type="button" value="Voltar" />
                        </a>
                    </div>
                </div>
                <?php
            }
            echo $hiddenKey;
            ?>
            <br /><br />
            <div class="fieldBorder2" >
                <div style="text-align: center; font-weight: bold">
                    Solicitante
                </div>
                <br /><br />
                <div>
                    <div class="row">
                        <div class="col-md-8">
                            <?php formulario::input('1[n_pessoa]', ' Nome:', NULL, ucwords(strtolower(user::session('n_pessoa'))), ' readonly ') ?>
                        </div>
                        <div class="col-md-4">
                            <?php formulario::input('1[rm]', ' Matrícula:', NULL, ucwords(strtolower(@$funcionario['rm'])), ' readonly ') ?>
                        </div>
                    </div>
                    <br /><br />
                    <div class="row">
                        <div class="col-md-12">
                            <?php formulario::input('1[local_sup]', 'Escola:', NULL, @$escola->_nome, ' readonly "') ?>
                        </div>
                    </div>
                    <br /><br />
                    <div class="row">
                        <div class="col-md-9">
                            <?php formulario::input('1[email]', 'E-mail do Local de Trabalho:', NULL, @$escola->_email, ' readonly ') ?>
                        </div>
                        <div class="col-md-3">
                            <?php
                            if (!empty($campos['tel1'])) {
                                $tel = $campos['tel1'];
                            } elseif (is_array(@$comp[0]['telefones'])) {
                                $tel = implode(';', @$comp[0]['telefones']);
                            }
                            ?>
                            <?php formulario::input('1[tel1]', 'Telefone:', NULL, $tel) ?>
                        </div>
                    </div>
                </div>
                <br /><br />
            </div>
            <input type="hidden" name="1[ultimo_lado]" value="Usuário" />
            <input type="hidden" name="1[id_sup]" value="<?php echo @$id ?>" />
            <input type="hidden" name="1[rastro_sup]" value="<?php echo empty(@$campos['rastro_sup']) ? substr(uniqid(), 0, 4) : @$campos['rastro_sup'] ?>" />
            <input id="id_pessoa" style="width: 872px" type="hidden" name="1[fk_id_pessoa]" value="<?php echo tool::id_pessoa() ?>"/>
            <input id="id_inst" style="width: 872px" type="hidden" name="1[fk_id_inst]" value="<?php echo tool::id_inst() ?>"/>
            <input type="hidden" name="id_sup" value="<?php echo @$id ?>" />
            <?php
            if (empty($campos['status_sup'])) {
                ?>
                <input type="hidden" name="1[status_sup]" value="Não Visualizado" />
                <?php
            }
            ?>
        </form>
        <?php
    }
    ?>
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