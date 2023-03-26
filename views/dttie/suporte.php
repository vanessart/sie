<?php
funcionarios::autocomplete(NULL, 1);
@$funcionario = funcionarios::Get(tool::id_pessoa(), 'fk_id_pessoa', 'rm, tel1, pessoa.email')[0];

if (tool::id_inst() == 124) {
    $resp = $model->pegarespon(3);
} else {
    $resp = $model->pegarespon(1);
}

// restrição grupo telefonia para retornar ao view suportetel
if (tool::id_nivel() == 47) {
    $retform = "suportetel";
} else {
    $retform = "escolapesq";
}

$tipo_s = $model->pegatiposubstituicao();

if (!empty($_POST['id_sup'])) {
    $id = $_POST['id_sup'];
} elseif (!empty($model->_last_id)) {
    $id = $model->_last_id;
}

if (!empty($id)) {
    @$campos = sql::get('dttie_suporte_trab', '*', ['id_sup' => $id], 'fetch');
} else {
    $campos = NULL;
}

$hiddenKey = DB::hiddenKey('dttie_suporte_trab');

//id do protocolo
$id_sup = $id;
?>
<div class="fieldBody">
    <?php ?>
    <div class="fieldTop">
        Suporte <?php echo!empty(@$campos['id_sup']) ? ' nº ' . @$campos['id_sup'] : '' ?>
    </div>
    <br />
    <form id="prin" action="" method="POST" >
        <div class="fieldBorder2" >
            <div class="row">
                <div class="col-sm-6">

                </div>
                <div class="col-sm-6">
                    <?php
                    //formulario::selectDB('dttie_resp_tec', '1[resp_sup]', ' Responsável pela Execução:', @$campos['resp_sup']);
                    formulario::select('1[resp_sup]', $resp, ' Responsável pela Execução:', @$campos['resp_sup']);
                    ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-sm-3">
                    <?php
                    formulario::selectDB('dttie_list_suporte', '1[tipo_sup]', 'Suporte em:', @$campos['tipo_sup']);
                    ?> 
                </div>
                <div class="col-sm-3">
                    <?php
                    formulario::select('1[priori_sup]', ['Media', 'Alta', 'Baixa'], 'Prioridade:', @$campos['priori_sup']);
                    ?> 
                </div>
                <div class="col-sm-6">
                    <?php
                    formulario::select('1[tipo_cadamp]', $tipo_s, 'Tipo Substituição CADAMPE: ', @$campos['tipo_cadamp']);
                    ?> 
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-sm-4">
                    <?php
                    formulario::input('1[dt_prev_sup]', ' Previsão de Entrega:', NULL, empty(@$campos['dt_prev_sup']) ? date("d/m/Y") : data::converteBr(@$campos['dt_prev_sup']), formulario::dataConf());
                    ?> 
                </div>
                <div class="col-sm-5">
                    <?php echo formulario::input('1[descr_sup]', 'Título', NULL, @$campos['descr_sup'], 'required', "Use poucas Palavras. No CADAMPE somente data") ?> 
                </div>
                <div class="col-sm-3">
                    <?php
                    //echo formulario::select('1[status_sup]', ['Aberto' => 'Aberto', 'Em Espera' => 'Em Espera', 'Em Andamento' => 'Em Andamento', 'Finalizado' => 'Finalizado'], 'Status', @$campos['status_sup']);
                   echo formulario::select('1[status_sup]', ['Atribuído' => 'Atribuído', 'Cancelado' => 'Cancelado', 'Finalizado' => 'Finalizado', 'Não Visualizado' => 'Não Visualizado'], 'Status', @$campos['status_sup']);
                    ?>
                </div>
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
                    <?php
                    //echo $_SESSION['userdata']['n_pessoa'] ;
                    if (tool::id_inst() == 124 && @$campos['tipo_sup'] == 76

                            //|| $_SESSION['userdata']['n_pessoa'] == 'fabio martins'
                            || $_SESSION['userdata']['n_pessoa'] == 'MARIO YOSHINORI TOKUSUMI'
                            //|| $_SESSION['userdata']['n_pessoa'] == 'KARIN DE OLIVEIRA GARRIDO'
                            || $_SESSION['userdata']['n_pessoa'] == 'Edson Alves Pereira'
                            || tool::id_pessoa()==1
                    ) {

                        $arr_tela = cadamp::verifica_tela_bloqueada($id_sup);
                        //var_dump($arr_tela);
                        //if ($arr_tela['tela_bloqueada'] == 'S' && $arr_tela['atendente'] != $_SESSION['userdata']['n_pessoa']) {
                        /*
                          if ($arr_tela['tela_bloqueada'] == 'S' && $arr_tela['atendente'] ) {
                          ?>
                          <input class="btn btn-success" type="button" value="Protocolo: <?php echo $id_sup?> em atendimento" disabled />
                          <?php
                          } else {
                          ?>
                          <input onclick="document.getElementById('convoca').submit()" class="btn btn-success" type="button" value="Convocação CADAMPE" />
                          <?php
                          }
                         */
                        ?>
                        <input onclick="document.getElementById('convoca').submit()" class="btn btn-success" type="button" value="Convocação CADAMPE" />
                        <?php
                    }
                    ?>

                </div>
                <div class="col-sm-3 text-center">
                    <?php if (!empty(@$campos['id_sup'])) { ?>
                        <a onclick="cancela()" class="btn btn-warning">
                            Cancelar Solicitação
                        </a>
                    <?php } ?>
                </div>
                <div class="col-sm-3">
                    <?php if (!empty(@$campos['id_sup'])) { ?>
                        <a href="<?php echo HOME_URI; ?>/dttie/supprot?id=002<?php echo @$campos['rastro_sup'] . @$id ?>&p=1"  target="_blank">
                            <input  style="width: 150px" class="btn btn-primary" type="button" value="Gerar Protocolo" />
                        </a>
                    <?php } ?>
                </div>
                <div class="col-sm-3">
                    <a href="<?php echo HOME_URI; ?>/dttie/<?php echo $retform ?>" >
                        <input  style="width: 150px" class="btn btn-danger" type="button" value="Voltar" />
                    </a>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="row">
                <div class="col-sm-12 text-center">
                    <a href="<?php echo HOME_URI; ?>/dttie/<?php echo $retform ?>" >
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
                    <div class="col-sm-8">
                        <?php formulario::input(NULL, ' Nome:', NULL, @$campos['n_pessoa'], ' readonly ') ?>
                    </div>
                    <div class="col-sm-4">
                        <?php formulario::input('1[rm]', ' Matrícula:', NULL, ucwords(strtolower(@$campos['rm'])), ' readonly ') ?>
                    </div>
                </div>
                <br /><br />
                <div class="row">
                    <div class="col-sm-12">
                        <?php formulario::input(NULL, 'Escola:', NULL, @$campos['local_sup'], ' readonly "') ?>
                    </div>
                </div>
                <br /><br />
                <div class="row">
                    <div class="col-sm-9">
                        <?php formulario::input(NULL, 'E-mail do Local de Trabalho:', NULL, @$campos['email'], ' readonly ') ?>
                    </div>
                    <div class="col-sm-3">
                        <?php formulario::input(NULL, 'Telefone:', NULL, @$campos['tel1']) ?>
                    </div>
                </div>
            </div>
            <br /><br />
        </div>
        <input type="hidden" name="1[ultimo_lado]" value="Suporte" />
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
        <input type="hidden" name="retorno" value="<?php echo $retform ?>" />
    </form>
    <form id="convoca" action="<?php echo HOME_URI ?>/cadam/relatsup" method="POST">
        <input type="hidden" name="prev" value="<?php echo @$campos['dt_prev_sup'] ?>" />
        <input type="hidden" name="id_inst" value="<?php echo @$campos['fk_id_inst'] ?>" />
        <input type="hidden" name="escola" value="<?php echo @$campos['local_sup'] ?>" />
        <input type="hidden" name="id" value="<?php echo @$id ?>" />
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
