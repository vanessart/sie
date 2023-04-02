<?php
if (!defined('ABSPATH'))
    exit;

ob_start();
$escola = new escola(@$_POST['id_inst']);
$d_inicio = filter_input(INPUT_POST, 'datai', FILTER_UNSAFE_RAW);
$d_fim = filter_input(INPUT_POST, 'dataf', FILTER_UNSAFE_RAW);
$cor = '#F5F5F5';
?>
<head>
    <style>
        td{
            font-size: 7pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 1px;
            padding-bottom: 1px;
        }
        .quebra {
            page-break-before: always;
        }

        table {
            width: 100%;
        }

    </style>
</head>

<?php
if (!empty($d_inicio) && (!empty($d_fim))) {

    $dados = $model->pegarelatorio($d_inicio, $d_fim);

    if (!empty($proximaFolha)) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    } else {
        $proximaFolha = 1;
    }
    if ($dados) {
        ?>
        <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
            Lista Chamado
        </div>
        <table class="table tabs-stacked table-bordered">
            <tr>
                <td >
                    Protocolo
                </td>
                <td >
                    Local Suporte
                </td>
                <td>
                    Status Suporte
                </td>
                <td>
                    Data Solicitação
                </td>
            </tr>
            <?php
            foreach ($dados as $v) {
                ?>
                <tr>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo $v['id_sup'] ?>
                    </td>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo $v['local_sup'] ?>
                    </td>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo $v['status_sup'] ?>
                    </td>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo data::converteBr($v['dt_sup']) ?>
                    </td>
                </tr>
            </table>
            <?php
        }
    } else {
        echo "Não existe dado para Relatório";
    }
} else {
    echo "Favor informar Data de Início e Data Final";
}

tool::pdfsecretaria2('P');
?>
