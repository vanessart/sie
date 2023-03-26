<?php
if (!defined('ABSPATH'))
    exit;

ob_start();

$local2 = filter_input(INPUT_POST, 'lo', FILTER_SANITIZE_STRING);
$resp2 = filter_input(INPUT_POST, 'rp', FILTER_SANITIZE_STRING);
$st2 = filter_input(INPUT_POST, 'sta', FILTER_SANITIZE_STRING);
$tipo2 = filter_input(INPUT_POST, 'ti', FILTER_SANITIZE_STRING);
$data_ci = filter_input(INPUT_POST, 'di', FILTER_SANITIZE_STRING);
$data_cf = filter_input(INPUT_POST, 'df', FILTER_SANITIZE_STRING);

$tipo = $model->pegalista();
$dados = $model->pegadadosconsulta($local2, $resp2, $tipo2, $st2, $data_ci, $data_cf);
$conta = $model->contachamado();
?>
<table style="width: 100%">
    <tr>
        <td rowspan="2">
            <img style="width: 100px" src="<?php echo HOME_URI ?>/views/_images/brasao.jpg"/>
        </td>
        <td style="text-align: center; font-size: 22px; font-weight: bold  ">
            Prefeitura Municipal de Barueri
            <br />
            Secretaria de Educação
            <br /><br />
        </td>
    </tr>
    <tr>
        <td style="font-weight: bold; text-align: center">
            Depto. Técnico da Tecnologia de Informação Educacional - DTTIE 
        </td>
    </tr>
</table>

<head>
    <style>
        .topo{
            font-size: 10px;
            font-weight:bold;
            text-align: center;
            border-style: solid;
            border-width: 0.5px;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 2px;
            padding-bottom: 2px;
        }
    </style>
</head>
<div class="fieldBody">
    <div class="topo">
        Status Chamado
    </div>
    <table style="width: 100%">
        <thead>
            <tr>
                <td class="topo">
                    Nulo
                </td>
                <td class="topo">
                    Não Visualizado
                </td>
                <td class="topo">
                    Atribuído
                </td> 
                <td class="topo">
                    Cancelado
                </td> 
                <td class="topo">
                    Finalizado
                </td>
                <td class="topo">
                    Recusado
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="topo" style="color: red">
                    <?php echo $conta['NUll'] ?>
                </td>
                <td class="topo" style="color: red">
                    <?php echo $conta['Não Visualizado'] ?>
                </td>
                <td class="topo" style="color: red">
                    <?php echo $conta['Atribuído'] ?>
                </td> 
                <td class="topo" style="color: red">
                    <?php echo $conta['Cancelado'] ?>
                </td> 
                <td class="topo" style="color: red">
                    <?php echo $conta['Finalizado'] ?>
                </td>
                <td class="topo" style="color: red">
                    <?php echo $conta['Recusado'] ?>
                </td>
            </tr>
        </tbody>
    </table>
    <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
        Lista Chamado Critério:  <?php echo (!empty($data_ci) ? ' De ' . $data_ci . 'Até: ' . $data_cf : 'Sem critério de data') ?>
    </div>
    <?php
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
                            Data
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
    ?>
</div>

<?php
tool::pdf('L');
?>