<?php
ob_start();
$cor = '#F5F5F5';
?>

<head>
    <style>
        td{
            font-weight:bolder;
            text-align: center;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 1px;
            padding-bottom: 1px;
        }
        .topo{
            font-size: 8pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            border-width: 0.5px;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 1px;
            padding-bottom: 1px;
        }
        .topo2{
            font-size: 8pt;
            font-weight:bold;
            text-align: center;
            border-style: solid;
            border-width: 0.5px;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 1px;
            padding-bottom: 1px;
            background-color: #000000; 
            color: #ffffff;

        }
    </style>
</head>

<?php
$mes = filter_input(INPUT_POST, 'mes', FILTER_UNSAFE_RAW);
$m = data::meses();
$contafrota = 6;

$dados = transporte::relatoriosrede($mes);

if (!empty($dados)) {
    foreach ($dados['escola'] as $k => $d) {
        $dd[$k] = $d;
    }

    foreach ($dados['nrveiculo'] as $kk => $nr) {
        foreach ($dados['nrveiculo'][$kk] as $key => $n) {
            $prefixo[$kk][] = $n;
        }
        $contaf = count($prefixo[$kk]);
        if ($contaf > $contafrota) {
            $contafrota = $contaf;
        }
    }
    $esc = array_column($dd, 'id_inst', 'id_inst');

    if (!empty($proximaFolha)) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    } else {
        $proximaFolha = 1;
    }
    ?>

    <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
        Resumo Geral - <?php echo $m[$mes] . '/' . date('Y') ?>
    </div>

    <table class="table tabs-stacked table-bordered">
        <thead>
            <tr>
                <td rowspan="2" class="topo" style="width: 34%">
                    Nome Escola       
                </td> 
                <td colspan ="5" class="topo" style="width: 25%">
                    Quantidade de Alunos/Veículos     
                </td> 
                <td colspan="6" rowspan="2" class="topo" style="width: 40%">
                    Numeração dos Veículos     
                </td>
            </tr>
            <tr>
                <td class="topo" style="width: 5%">
                    Manhã
                </td>          
                <td class="topo" style="width: 5%">
                    Tarde     
                </td>
                <td class="topo" style="width: 5%">
                    Ativos     
                </td>
                <td class="topo" style="width: 5%">
                    Inativos     
                </td>
                <td class="topo" style="width: 6%">
                    Veículos     
                </td>
            </tr>       
        </thead>
        <?php
        foreach ($esc as $v) {
            ?>
            <tbody>
                <tr>
                    <td class="topo" style="width: 34%; text-align: left">
                        <?php echo $dd[$v]['n_inst'] ?>
                    </td>
                    <td class="topo" style="width: 5%">
                        <?php echo (empty($dd['M'][$v]) ? 0 : $dd['M'][$v]) ?>
                    </td>
                    <td class="topo" style="width: 5%">
                        <?php echo (empty($dd['T'][$v]) ? 0 : $dd['T'][$v]) ?>
                    </td>
                    <td class="topo" style="width: 5%">
                        <?php echo (empty($dd['Ativos'][$v]) ? 0 : $dd['Ativos'][$v]) ?>
                    </td>
                    <td class="topo" style="width: 5%">
                        <?php echo (empty($dd['Inativos'][$v]) ? 0 : $dd['Inativos'][$v]) ?>
                    </td>
                    <td class="topo" style="width: 6%">
                        <?php echo (empty($dados['contaveiculo'][$v]) ? 0 : array_sum($dados['contaveiculo'][$v])) ?>
                    </td>
                    <?php
                    for ($x = 0; $x < $contafrota; $x++) {
                        ?>
                        <td class="topo">
                            <?php echo (empty($prefixo[$v][$x]) ? '-' : $prefixo[$v][$x]) ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
            </tbody>
            <?php
        }
        ?>
        <tfoot>
            <tr>
                <td class="topo" style="background-color: #000000; color: #ffffff; text-align: right" >
                    Total
                </td>
                <td class="topo" style="background-color: #000000; color: #ffffff" >
                    <?php echo (empty($dados['escola']['total']['M']) ? 0 : $dados['escola']['total']['M']) ?>
                </td>
                <td class="topo" style="background-color: #000000; color: #ffffff" >
                    <?php echo (empty($dados['escola']['total']['T']) ? 0 : $dados['escola']['total']['T']) ?>
                </td>
                <td class="topo" style="background-color: #000000; color: #ffffff" >
                    <?php echo (empty($dados['escola']['total']['Ativos']) ? 0 : $dados['escola']['total']['Ativos']) ?>
                </td>
                <td class="topo" style="background-color: #000000; color: #ffffff" >
                    <?php echo (empty($dados['escola']['total']['Inativos']) ? 0 : $dados['escola']['total']['Inativos']) ?>
                </td>
                <td class="topo" style="background-color: #000000; color: #ffffff" >
                    <?php echo (empty($dados['contaveiculo']['totalv']) ? 0 : array_sum($dados['contaveiculo']['totalv'])) ?>        
                </td>
                <td colspan="6" class="topo" style="background-color: #000000; color: #ffffff" >
                    <?php echo (empty($dados['contaveiculotipo']['1']) ? 'Total de Ônibus = 0' : 'Total de Ônibus = ' . array_sum($dados['contaveiculotipo']['1'])) ?>   
            </tr>
        </tfoot>
    </table>

    <?php
} else {
    echo "Não existe dados para relatório";
}
tool::pdfsecretaria2('L');
?>