<?php
ob_start();
if (!defined('ABSPATH'))
    exit;

$cor = '#F5F5F5';
$pdf = new pdf();
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
            font-size: 7pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            border-width: 0.5px;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 1px;
            padding-bottom: 1px;
            white-space: nowrap;
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
$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_STRING);
$m = dataErp::meses();
$semana = explode('|', dataErp::pegasemana($mes));

//Iniciando a variavel
$rel = 0;
$larg = (62.5 - $semana[3] * 2.5) + 9.5;
//$col1 = intdiv(($semana[3] + 6), 4);
$col1 = (intval(($semana[3] + 6) / 4));
$col2 = fmod(($semana[3] + 6), 4) + $col1;

if (!empty($_POST['sel'])) {
    foreach ($_POST['sel'] as $v) {
        if (!empty($v)) {
            $idlinha[] = $v;
            $rel = 1;
        }
    }
}
if ($rel == 1) {
    $idlinha = implode(",", $idlinha);
    $dados = transporteErp::alunoFrequencia($mes, $idlinha);
} else {
    if ($_POST['relatorio'] == 'Gerente') {
        $dados = transporteErp::alunoFrequencia($mes);
    }
}

if (!empty($dados)) {
    foreach ($dados['aluno'] as $k => $v) {
        $d[$v['id_li']] = $v;
        $lista[$v['id_li']][$v['id_alu']] = $v;
    }

    foreach ($d as $w => $wa) {

        if (!empty($proximaFolha)) {
            ?>
            <div style="page-break-after: always"></div>
            <?php
        } else {
            $proximaFolha = 1;
        }
        ?>

        <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
            Controle de Frequência - Transporte Escolar - <?php echo $m[$mes] . '/' . date('Y') ?>
        </div>

        <div style = "border-width: 0.5px; border-style: solid; font-weight:bold; font-size:8pt; text-align: left; color: red">
            <?php echo $d[$w]['n_inst'] ?>
        </div>

        <table class="table tabs-stacked table-bordered">;
            <thead>
                <tr>
                    <td class="topo" style="width: 12%">
                        Linha       
                    </td> 
                    <td class="topo" style="width: 8%">
                        Veículo      
                    </td> 
                    <td class="topo" style="width: 7%">
                        Placa
                    </td>          
                    <td class="topo" style="width: 7%">
                        Prefixo     
                    </td>
                    <td class="topo" style="width: 7%">
                        Período     
                    </td>
                    <td class="topo" style="width: 7%">
                        Capacidade     
                    </td>
                    <td class="topo" style="width: 7%">
                        Empresa     
                    </td>
                    <td class="topo" style="width: 7%">
                        Telefone     
                    </td>
                    <td class="topo" style="width: 19%">
                        Motorista     
                    </td>
                    <td class="topo" style="width: 19%">
                        Monitor     
                    </td>
                </tr>       
            </thead>
            <tbody>
                <tr>
                    <td class="topo" style="width: 12%">
                        <?php echo $d[$w]['n_li'] ?>       
                    </td> 
                    <td class="topo" style="width: 8%">
                        <?php echo $d[$w]['n_tiv'] ?>     
                    </td> 
                    <td class="topo" style="width: 7%">
                        <?php echo $d[$w]['placa'] ?>
                    </td>          
                    <td class="topo" style="width: 7%">
                        <?php echo $d[$w]['n_tv'] ?>     
                    </td>
                    <td class="topo" style="width: 7%">
                        <?php echo $d[$w]['periodo'] ?>     
                    </td>
                    <td class="topo" style="width: 7%">
                        <?php echo $d[$w]['capacidade'] ?>     
                    </td>                  
                    <td class="topo" style="width: 7%">
                        <?php echo $d[$w]['n_em'] ?>     
                    </td>
                    <td class="topo" style="width: 7%">
                        <?php echo $d[$w]['tel1'] ?>     
                    </td>
                    <td class="topo" style="width: 19%">
                        <?php echo $d[$w]['motorista'] ?>    
                    </td>
                    <td class="topo" style="width: 19%">
                        <?php echo $d[$w]['monitor'] ?>    
                    </td>
                </tr>  
            </tbody>
        </table>

        <table class="table tabs-stacked table-bordered">;
            <thead>
                <tr>
                    <td rowspan="2" class="topo" style="width: 3%" >
                        Seq.       
                    </td> 
                    <td rowspan="2" class="topo" style="width: <?php echo $larg . '%' ?>">
                        Nome Aluno      
                    </td> 
                    <td rowspan="2" class="topo" style="width: 8%">
                        RA
                    </td>          
                    <td rowspan="2" class="topo" style="width: 7%">
                        Cód.Classe     
                    </td>
                    <?php
                    $y = 0;
                    $x = 1;

                    While ($x <= $semana[3]) {
                        ?>
                        <td class="topo" style="width: 2.5%">
                            <?php echo substr(($semana[0]), $x - 1, 1); ?>
                        </td>
                        <?php
                        $x++;
                        $y = $y + 2;
                    }
                    ?>  
                    <td rowspan="2" class="topo" style="width: 5%">
                        Falta
                    </td>
                    <td rowspan="2" class="topo" style="width: 5%">
                        Presente
                    </td>
                </tr>
                <tr>
                    <?php
                    $y = 0;
                    $x = 1;

                    While ($x <= $semana[3]) {
                        ?>
                        <td class="topo">
                            <?php echo substr(($semana[1]), $y, 2); ?>
                        </td>
                        <?php
                        $x++;
                        $y = $y + 2;
                    }
                    ?>              
                </tr>
            </thead>
            <?php
            $seq = 1;

            foreach ($lista[$w] as $list) {
                ?>
                <tbody>
                    <tr>
                        <td class="topo" style= "width: 3%; background-color: <?php echo $cor ?>">
                            <?php echo $seq++ ?>
                        </td> 
                        <td class="topo" style="width: <?php echo $larg . '%' ?>; text-align: left; fonte-size:7px; background-color: <?php echo $cor ?>">
                            <?php echo toolErp::abrevia($list['n_pessoa']) ?>        
                        </td>                      
                        <td class="topo" style=" width: 8%; background-color: <?php echo $cor ?>">
                            <?php echo $list['ra'] . '-' . $list['ra_dig'] ?>
                        </td> 
                        <td class="topo" style="width: 7%; background-color: <?php echo $cor ?>">
                            <?php echo $list['codigo'] ?>
                        </td>
                        <?php
                        foreach ($list['frequencia'] as $kk => $vv) {
                            if ($vv == 'F') {
                                ?>
                                <td class="topo" style="width: 2.5%">
                                    <?php
                                    echo '';
                            } else {
                                    ?>
                                <td class="topo" style="width: 2.5%">
                                    <?php echo '' ?>
                                </td>
                                <?php
                            }
                        }
                        ?>
                        <td class="topo" style="width: 5%; background-color: <?php echo $cor ?>">
                            <?php echo '' ?>
                        </td>    
                        <td class="topo" style="width: 5%; background-color: <?php echo $cor ?>">
                            <?php echo '' ?>
                        </td>
                </tbody>
                <?php
            }
            ?>
            <tfoot>
                <tr>
                    <td colspan="4" class="topo" style="text-align: right; background-color: #000000; color: #ffffff">
                        Total de Alunos Transportados
                    </td> 
                    <?php
                    $y = 0;
                    $x = 1;

                    While ($x <= $semana[3]) {
                        ?>
                        <td class="topo" style="background-color: #000000; color: #ffffff">
                            <?php echo intval(@$dados['linha'][$w][substr(($semana[1]), $y, 2)]) ?>
                        </td>
                        <?php
                        $x++;
                        $y = $y + 2;
                    }
                    ?>  
                    <td class="topo" style="background-color: #000000; color: #ffffff">
                        <?php echo '' ?>                  
                    </td>
                    <td class="topo" style="background-color: #000000; color: #ffffff">
                        <?php echo '' ?>                  
                    </td>
                </tr>
                <tr>
                    <td colspan="<?php echo $semana[3] + 6 ?>" style="font-weight:bold; font-size:8pt; color: red; text-align: center">
                        Resumo - Totais
                    </td>          
                </tr>
                <tr>
                    <td colspan="<?php echo $semana[3] + 6 ?>" style="font-weight:bold; font-size:8pt; text-align: left">
                        <?php echo $d[$w]['n_inst'] . ' ' . $m[$mes] . '/' . date('Y') ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="<?php echo $col1 ?>" class="topo" style="width: 25%">
                        Linha
                    </td>
                    <td colspan="<?php echo $col1 ?>" class="topo" style="width: 25%">
                        Dias Úteis
                    </td>
                    <td colspan="<?php echo $col1 ?>" class="topo" style="width: 25%">
                        Alunos
                    </td>
                    <td colspan="<?php echo $col2 ?>" class="topo" style="width: 25%">
                        Alunos Transportados
                    </td>
                </tr>
                <tr>
                    <td colspan="<?php echo $col1 ?>" class="topo" style="width: 25%">
                        <?php echo $d[$w]['n_li'] ?>
                    </td>
                    <td colspan="<?php echo $col1 ?>" class="topo" style="width: 25%">
                        <?php echo $dados['totaldia'] - $dados['totalferiado'] ?>
                    </td>
                    <td colspan="<?php echo $col1 ?>" class="topo" style="width: 25%">
                        <?php echo $dados['linha'][$w]['totalAlunos'] ?>
                    </td>
                    <td colspan="<?php echo $col2 ?>" class="topo" style="width: 25%">
                        <?php echo '' ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="<?php echo $semana[3] + 6 ?>" class="topo" style="text-align: left">
                        Legenda: <b>P</b>= Presente(Transportado) <b>F</b> = Falta <b>N</b> = Dia Não Lançado <b>R</b> = Recesso <b>-</b> = Feriado <b>*</b> = Aluno Não Alocado no Dia
                    </td>          
                </tr>
            </tfoot>    
        </table>
        <table>
            <thead>
                <tr>
                    <td colspan="3" style="font-size: 8pt; text-align: right; width: 100%">
                        <?= CLI_CIDADE ?>, <?php echo date('d') . ' ' . dataErp::meses(date('m')) . ' ' . date('Y') . '.' ?>
                    </td>
                </tr>
                <br /><br /><br /><br /><br />
                <tr>
                    <td style="font-size: 8pt; text-align: center; width: 33%">
                        <div style="font-size: 8pt; text-align: center">_____________________________________</div>
                        <div style="font-size: 8pt; text-align:center">Assinatura Responsável(Preenchimento)</div>
                    </td>
                    <td style="font-size: 8pt; text-align: center; width: 34%">
                        <div style="font-size: 8pt; text-align: center">_____________________________________</div>
                        <div style="font-size: 8pt; text-align:center">Assinatura do Diretor UE</div>
                    </td>
                    <td style="font-size: 8pt; text-align: center; width: 34%">
                        <div style="font-size: 8pt; text-align: center">_____________________________________</div>
                        <div style="font-size: 8pt; text-align:center">Assinatura do Coordenador Técnico</div>
                    </td>
                </tr>
            </thead>
        </table>

        <?php
    }
} else {
    echo "Não existe dados para relatório";
}
$pdf->orientation = 'L';
$pdf->exec();
?>