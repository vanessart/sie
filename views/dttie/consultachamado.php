<?php
if (!defined('ABSPATH'))
    exit;

$local2 = filter_input(INPUT_POST, 'local_sup', FILTER_UNSAFE_RAW);
$resp2 = filter_input(INPUT_POST, 'id_resp', FILTER_UNSAFE_RAW);
$st2 = filter_input(INPUT_POST, 'status_sup', FILTER_UNSAFE_RAW);
$tipo2 = filter_input(INPUT_POST, 'tipo_sup', FILTER_UNSAFE_RAW);
$data_ci = filter_input(INPUT_POST, 'datai', FILTER_UNSAFE_RAW);
$data_cf = filter_input(INPUT_POST, 'dataf', FILTER_UNSAFE_RAW);

$local = $model->pegalocalsup();
$resp = $model->pegarespon(1);
$st = $model->pegastatus();
$tipo = $model->pegalista();
$conta = $model->contachamado();
?>
<head>
    <style>
        .topo{
            font-size: 10px;
            font-weight:bold;
            text-align: center;
            border-style: solid;
            border-width: 0.5px;
            border-color: black;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 2px;
            padding-bottom: 2px;

        }
    </style>
</head>
<div class="fieldBody">
    <table style="width: 100%">
        <thead>
            <tr>
                <td class="topo" style="font-size: 15px">
                    Nulo
                </td>
                <td class="topo" style="font-size: 15px">
                    Não Visualizado
                </td>
                <td class="topo" style="font-size: 15px">
                    Atribuído
                </td> 
                <td class="topo" style="font-size: 15px">
                    Cancelado
                </td> 
                <td class="topo" style="font-size: 15px">
                    Finalizado
                </td>
                <td class="topo" style="font-size: 15px">
                    Recusado
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="topo" style="color: red; font-size: 15px">
                    <?php echo $conta['NUll'] ?>
                </td>
                <td class="topo" style="color: red; font-size: 15px">
                    <?php echo $conta['Não Visualizado'] ?>
                </td>
                <td class="topo" style="color: red; font-size: 15px">
                    <?php echo $conta['Atribuído'] ?>
                </td> 
                <td class="topo" style="color: red; font-size: 15px">
                    <?php echo $conta['Cancelado'] ?>
                </td> 
                <td class="topo" style="color: red; font-size: 15px">
                    <?php echo $conta['Finalizado'] ?>
                </td>
                <td class="topo" style="color: red; font-size: 15px">
                    <?php echo $conta['Recusado'] ?>
                </td>
            </tr>
        </tbody>
    </table>
    <br />
    <form method="POST">        
        <div class="row">
            <div class="col-lg-4">
                <?php echo formulario::select('local_sup', $local, 'Escola/Depto', @$local_sup) ?>
            </div>
            <div class="col-lg-4">
                <?php echo formulario::select('id_resp', $resp, 'Atribuído a:', @$id_resp) ?>
            </div>
            <div class="col-lg-4">
                <?php echo formulario::select('tipo_sup', $tipo, 'Suporte em:', @$campos['tipo_sup']) ?>
            </div>
        </div>
        <br />
        <div class="row">        
            <div class="col-lg-4">
                <?php echo formulario::select('status_sup', $st, 'Status') ?>
            </div>
            <div class="col-lg-4">
                <label style="font-size: 15px">
                    Data Início
                </label>
                <input id = "data1"<?php echo formulario::dataConf(1) ?>type="text" name="datai" value="" />
            </div>
            <div class="col-lg-4">
                <label style="font-size: 15px">
                    Data Fim
                </label>
                <input id = "data2"<?php echo formulario::dataConf(2) ?>type="text" name="dataf" value="" />
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-lg-3" style="text-align: left">
                <input id = "di2" type="hidden" name="datai" value="" />  
                <input id = "df2" type="hidden" name= "dataf" value="" />  
                <button onmouseover="pegadata('di2', 'df2')" class="btn btn-success" type="submit"  style="width: 300px" name="pesquisar" value="Pesquisar">                
                    Pesquisar
                </button>
            </div> 
            <div class="col-lg-3" style="text-align: right">                     
                <?php
                if (!empty($_POST['pesquisar'])) {
                    ?>
                    <button type="button" onclick="rel()" class="btn btn-success" style="width: 300px">
                        Imprimir
                    </button>
                    <?php
                } else {
                    ?>
                    <button type="button" style="width: 300px">
                        Imprimir
                    </button>
                    <?php
                }
                ?>
            </div>        
            <div class="col-lg-3" style="text-align: right">
                <button type="button" style="width: 300px" onclick="document.getElementById('excel').submit();" class="btn btn-success" >
                    Excel
                </button>
            </div>           
            <div class="col-lg-3" style="text-align: right">
                <a class="btn btn-success" style="width: 300px" href="">
                    Limpar
                </a>
            </div>
        </div>
    </form>
    <br />
    <?php
    if (!empty($_POST['pesquisar'])) {
        $dados = $model->pegadadosconsulta($local2, $resp2, $tipo2, $st2, $data_ci, $data_cf);
        if (!empty($dados)) {
            ?>

            <div class="body">

                <table style="width: 100%">
                    <thead>
                        <tr>
                            <td class="topo" style="width: 10%">
                                Protocolo
                            </td>
                            <td class="topo" style="width: 20%">
                                Local Suporte
                            </td>
                            <td class="topo" style="width: 15%">
                                Tipo Suporte
                            </td>
                            <td class="topo" style="width: 15%">
                                Atribuído a
                            </td>
                            <td class="topo" style="width: 20%">
                                Solicitação
                            </td>
                            <td class="topo" style="width: 10%">
                                Status
                            </td>
                            <td class="topo" style="width: 10%">
                                Data Suporte
                            </td> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($dados as $v) {
                            ?>
                            <tr>
                                <td class="topo" style="text-align:center; width: 10%">
                                    <?= $v['id_sup'] ?>
                                </td>
                                <td class="topo" style="text-align:left; width: 20%">
                                    <?= $v['local_sup'] ?>
                                </td>
                                <td class="topo" style="text-align:left; width: 15%">
                                    <?= @$tipo[$v['tipo_sup']] ?>
                                </td>
                                <td class="topo" style="text-align:left; width: 15%">
                                    <?= $v['n_resp'] ?>
                                </td>
                                <td class="topo" style="text-align:left; width: 20%">
                                    <?= $model->pegadialogo($v['id_sup']) ?>
                                </td>

                                <td class="topo" style="text-align:center; width: 10%">
                                    <?= $v['status_sup'] ?>
                                </td>
                                <td class="topo" style="text-align:center; width: 10%">
                                    <?= data::converteBr($v['dt_sup']) ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
        } else {
            echo "Não existe dado para relatório";
        }
    }
    ?>
</div>

<form id="excel" target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
    <?php
 //   $sql = $model->pegarelatoriogerencial($local2, $resp2, $tipo2, $st2, $data_ci, $data_cf);
    ?>
    <input type="hidden" name="tokenSql" value="<?php echo tool::tokenSql() ?>" />
    <input type="hidden" name="sql" value="<?php echo $sql ?>" />

</form> 

<form id="relatorio" target="_blank" action="<?= HOME_URI ?>/dttie/pdfchamado" method="POST">
    <input type="hidden" id="lo" name="lo" value="<?php echo $local2 ?>"/>
    <input type="hidden" id="rp" name="rp" value="<?php echo $resp2 ?>"/>
    <input type="hidden" id="sta" name="sta" value="<?php echo $st2 ?>"/>
    <input type="hidden" id="ti" name="ti" value="<?php echo $tipo2 ?>"/>
    <input type="hidden" id="di" name="di" value="<?php echo $data_ci ?>"/>
    <input type="hidden" id="df" name="df" value="<?php echo $data_cf ?>"/>
</form>

<script>
    function pegadata(datai, dataf) {
        document.getElementById(datai).value = document.getElementById('data1').value;
        document.getElementById(dataf).value = document.getElementById('data2').value;
    }

    function rel() {
        document.getElementById('relatorio').submit();
    }
</script>