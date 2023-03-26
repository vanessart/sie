<?php
funcionarios::autocomplete(NULL, 1);

$funcionario = funcionarios::Get(tool::id_pessoa(), 'fk_id_pessoa', 'rm, tel1, ge_funcionario.email')[0];


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
$hiddenKey = DB::hiddenKey('super_suporte_trab', 'replace');
?>
<div class="fieldBody">

    <script>
        function mesmo() {
            if (document.getElementById("mesmo1").checked == true) {
                document.getElementById("busca").value = "<?php echo user::session('n_pessoa') ?>";
                document.getElementById("tel1").value = "<?php echo @$funcionario['tel1'] ?>";
                document.getElementById("email").value = "<?php echo @$funcionario['email'] ?>";
                document.getElementById("id_pessoa").value = "<?php echo tool::id_pessoa() ?>";
            } else {
                document.getElementById("busca").value = "";
                document.getElementById("tel1").value = "";
                document.getElementById("email").value = "";
                document.getElementById("id_pessoa").value = "";
            }
        }

        function conta() {

            f1 = document.getElementById("fqg").value;

            if (document.getElementById("ffv").checked) {
                f1 = Math.ceil(f1 / 2);
            }

            f2 = document.getElementById("fcp").value;

            f3 = f1 * f2;


            document.getElementById("td").value = f3;


        }


        function recebedor() {
            if (document.getElementById("retirar").checked == true) {
                document.getElementById("Recebedor").style.display = "";
            } else {
                document.getElementById("Recebedor").style.display = "none";
            }
        }

    </script>    
    <?php ?>
    <div class="fieldTop">
        Suporte <?php echo!empty(@$campos['id_sup']) ? ' - Serviço nº ' . @$campos['id_sup'] : '' ?>
    </div>
    <br />
    <?php
    $model->logSuporte(@$id);
    ?>
    <br /><br />
    <form action="" method="POST" >
        <div class="panel panel-default" >
            <div class="panel panel-heading">
                Solicitante
            </div>
            <div class="panel panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <?php formulario::input('1[n_pessoa]', ' Nome:', NULL, ucwords(strtolower(user::session('n_pessoa'))), ' readonly ') ?>
                    </div>
                    <div class="col-md-3">
                        <?php formulario::input('1[rm]', ' Matrícula:', NULL, ucwords(strtolower(@$funcionario['rm'])), ' readonly ') ?>
                    </div>
                    <div class="col-md-3">
                        <?php formulario::input('1[tel1]', 'Telefone:', NULL,  !empty(@$campos['tel1'])?@$campos['tel1']:is_array(@$comp[0]['telefones']) ? @implode(';', @$comp[0]['telefones']) :'') ?>
                    </div>
                </div>
            </div>
            <input type="hidden" name="1[local_sup]" value="Secretaria de Educação" />

        </div>
        <br />
        <div class="panel panel-default" >
            <div class="panel panel-heading">
                Trabalho
            </div>
            <div class="panel panel-body">
                <div class="col-md-3">
                    <?php
                    formulario::selectDB('super_list_sup_dep', '1[tipo_sup]', 'Suporte em:', @$campos['tipo_sup'], !empty($id) ? 'disabled' : '');
                    ?> 
                    <input type="hidden" name="1[priori_sup]" value="Media" />
                </div>
                <div class="col-md-5">
                    <?php
                    if (!empty($id)) {
                        echo formulario::input(NULL, 'Status', NULL, @$campos['status_sup'], 'disabled');
                        $campos['resp_sup'];
                    } else {
                        ?>
                        <input type="hidden" name="1[status_sup]" value="Aberto" />
                        <?php
                    }
                    ?> 
                </div>

                <input type="hidden" name="1[dt_sup]" value="<?php echo date("Y-m-d") ?>" />
                <input type="hidden" name="1[dt_prev_sup]" value="<?php echo date("Y-m-d") ?>" />


                <div class="col-md-12">
                    <textarea <?php echo !empty($id) ? 'disabled' : '' ?> name="1[descr_sup]" style="width: 100%; height: 60px" placeholder="Descrição"><?php echo @$campos['descr_sup'] ?></textarea>
                </div>
                <?php
                if(!empty($id)){
                    ?>
                <div class="col-md-12">
                    <textarea disabled name="1[devol_sup]" style="width: 100%; height: 60px" placeholder="Devolutiva"><?php echo @$campos['devol_sup'] ?></textarea>
                </div>
                <div class="col-md-12">
                    <textarea disabled name="1[obs_sup]" style="width: 100%; height: 60px" placeholder="Observações"><?php echo @$campos['obs_sup'] ?></textarea>
                </div>
                                <?php
                }
                if (@$campos['status_sup'] != 'Cancelado') {
                    ?>
                    <div class="col-md-12">
                        <div class="input-group">
                            <div class="input-group-addon">
                                <label style="width: 100%; text-align: left">
                                    <input onclick="recebedor()" id="retirar" <?php echo (!empty($campos['fk_id_pessoa1']) ? 'checked="checked"' : '') ?>  type="checkbox" />
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    Será retirado
                                </label>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <br />
        <div class="panel panel-default"  id="Recebedor" style="display: <?php echo (empty(@$campos['n_pessoa1']) ? 'none' : '') ?>">
            <?php
            if (@$campos['status_sup'] != 'Cancelado') {
                $disabled = NULL;
                ?>
                <div class="panel panel-heading">
                    Recebedor:&nbsp;&nbsp;
                    <label>
                        (&nbsp;
                        <input id="mesmo1" type="checkbox" onclick="mesmo()" value="ON" />
                        &nbsp;
                        O mesmo
                        &nbsp;
                        )
                    </label>
                </div>
                <?php
            } else {
                $disabled = 'disabled';
            }
            ?>
            <div class="panel panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php formulario::input('1[n_pessoa1]', 'Nome:', NULL, @$campos['n_pessoa1'], 'id="busca" onkeypress="complete()"  autocomplete="off" ' . $disabled) ?>       
                        <input type="hidden" name="1[fk_id_pessoa1]" value="<?php echo @$campos['fk_id_pessoa1'] ?>" id="id_pessoa" />
                    </div>
                    <div class="col-md-4">
                        <?php formulario::input('1[tel_ret]', 'Telefone:', NULL, @$campos['tel_ret'], 'id="tel1" ' . $disabled) ?>       
                    </div>
                    <div class="col-md-8">
                        <?php formulario::input('1[email_ret]', 'E-mail:', NULL, @$campos['email_ret'], 'id="email" ' . $disabled) ?>       
                    </div>
                </div>
            </div>
        </div>
        <br />
        <?php
        if (@$campos['status_sup'] != 'Cancelado') {
            $disabled = 'disabled';
            ?>
            <div class="row">
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
                    <input  style="width: 150px" name="salvEscola" class="btn btn-success" type="submit" value="Salvar" />
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
        <input type="hidden" name="1[id_sup]" value="<?php echo @$id ?>" />
        <input type="hidden" name="1[rastro_sup]" value="<?php echo empty(@$campos['rastro_sup']) ? substr(uniqid(), 0, 4) : @$campos['rastro_sup'] ?>" />
        <input type="hidden" name="1[dt_sup]" value="<?php echo empty(@$campos['dt_sup']) ? date("Y-m-d") : @$campos['dt_sup'] ?>" />
        <input id="id_pessoa" style="width: 872px" type="hidden" name="1[fk_id_pessoa]" value="<?php echo tool::id_pessoa() ?>"/>
        <input id="id_inst" style="width: 872px" type="hidden" name="1[fk_id_inst]" value="<?php echo tool::id_inst() ?>"/>
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