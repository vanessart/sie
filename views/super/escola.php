<?php
if (user::session('id_nivel') == 8) {
    $id_inst = tool::id_inst();
} else {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
}
################################################################################
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

################################################################################
if (!empty($campos['tipo_sup'])) {
    $sol = $campos['tipo_sup'];
} else {
    $sol = filter_input(INPUT_POST, 'sol', FILTER_SANITIZE_NUMBER_INT);
}

$solicita = sql::idNome('super_list_suporte', ['ACT_LIST' => 1, '>' => 'n_list']);

if (empty($sol)) {
    ?>
    <div class="fieldBody">
        <div class="fieldTop">
            O que Você deseja Solicitar?
        </div>
        <br /><br />
        <?php
        foreach ($solicita as $k => $v) {
            ?>
            <form method="POST">
                <input type="hidden" name="sol" value="<?php echo $k ?>" />
                <div style="width: 100%; text-align: center">
                    <button type="submit" class="fieldBorder2" style="width: 60%;">
                        <?php echo $v ?>
                    </button>
                </div>
            </form>
            <br />
            <?php
        }
    } else {
        ?>
        <br />
        <div class="fieldTop">
            <?php echo $solicita[$sol] ?>
        </div>
        <div class="fieldBody">
            <br />
            <form id="prin" action="" method="POST" >
                <div class="fieldBorder2" >
                    <div class="row">
                        <div class="col-md-6">
                            <?php echo formulario::input('1[descr_sup]', 'Título', NULL, @$campos['descr_sup'], 'required ' . $campos['descr_sup'], "Use poucas Palavras") ?> 
                        </div>

                        <div class="col-md-4">
                            <?php
                            if (!empty($id)) {
                                ?>
                                Protocolo: <?php
                                echo!empty(@$campos['id_sup']) ? ' - Serviço nº ' . @$campos['id_sup'] : '';
                            }
                            ?>

                        </div>
                        <?php
                        if (!empty($id)) {
                            ?>
                            <div class="col-md-2">
                                <?php
                                echo formulario::input(NULL, 'Status', NULL, @$campos['status_sup'], 'disabled');
                                $campos['resp_sup'];
                                ?>
                                <input id="statu" type="hidden" name="1[status_sup]" value="<?php echo @$campos['status_sup'] ?>" />
                            </div>
                            <?php
                        } else {
                            ?>
                            <input type="hidden" name="1[status_sup]" value="Aberto" />
                            <?php
                        }
                        ?> 
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-md-5">
                            Usuário: <?php echo ucwords(strtolower(user::session('n_pessoa'))) ?>
                        </div>
                        <div class="col-md-5">
                            Escola:  <?php echo tool::n_inst() ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo formulario::select('1[priori_sup]', ['Critica' => 'Critica', 'Alta' => 'Alta', 'Média' => 'Média'], 'Prioridade', @$campos['priori_sup'], NULL, NULL, !empty($campos['priori_sup']) ? 'disabled' : '') ?>
                        </div>
                    </div>

                    <br />

                    <?php
                    include ABSPATH . "/views/super/_escola/$sol.php";
                    ?>
                    <br />
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
                        <div class="col-md-11">
                            <input type="hidden" name="1[dt_sup]" value="<?php echo date("Y-m-d") ?>" />
                            <input type="hidden" name="1[dt_prev_sup]" value="<?php echo date("Y-m-d") ?>" />
                            <textarea name="descr" style="width: 100%; height: 60px" placeholder="Escreva Aqui"></textarea>
                        </div>
                        <div class="col-md-1 text-center">
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
                        <div class="col-md-3 text-center">
                            <?php if (!empty(@$campos['id_sup'])) { ?>
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
                                <a href="<?php echo HOME_URI; ?>/super/supprot?id=002<?php echo @$campos['rastro_sup'] . @$id ?>&p=1"  target="_blank">
                                    <input  style="width: 150px" class="btn btn-primary" type="button" value="Gerar Protocolo" />
                                </a>
                            <?php } ?>
                        </div>
                        <div class="col-md-3">
                            <a href="<?php echo HOME_URI; ?>/super/escolapesq" >
                                <input  style="width: 150px" class="btn btn-danger" type="button" value="Voltar" />
                            </a>
                        </div>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <a href="<?php echo HOME_URI; ?>/super/escolapesq" >
                                <input  style="width: 150px" class="btn btn-danger" type="button" value="Voltar" />
                            </a>
                        </div>
                    </div>
                    <?php
                }
                echo $hiddenKey;
                ?>
                <br /><br />
                <input type="hidden" name="1[n_pessoa]" value="<?php echo ucwords(strtolower(user::session('n_pessoa'))) ?>" />
                <input type="hidden" name="1[tipo_sup]" value="<?php echo $sol ?>" />
                <input type="hidden" name="1[rm]" value="<?php echo ucwords(strtolower(@$funcionario['rm'])) ?>" />
                <input type="hidden" name="1[ultimo_lado]" value="Escola" />
                <input type="hidden" name="1[id_sup]" value="<?php echo @$id ?>" />
                <input type="hidden" name="1[fk_id_inst]" value="<?php echo @$id_inst ?>" />
                <input type="hidden" name="1[rastro_sup]" value="<?php echo empty(@$campos['rastro_sup']) ? substr(uniqid(), 0, 4) : @$campos['rastro_sup'] ?>" />
                <input type="hidden" name="1[dt_sup]" value="<?php echo empty(@$campos['dt_sup']) ? date("Y-m-d") : @$campos['dt_sup'] ?>" />
                <input id="id_pessoa" style="width: 872px" type="hidden" name="1[fk_id_pessoa]" value="<?php echo tool::id_pessoa() ?>"/>
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
    </div>
    <?php
}