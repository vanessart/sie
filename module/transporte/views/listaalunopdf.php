<?php
ob_start();
if (!defined('ABSPATH'))
    exit;

$pdf = new pdf();
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
$mes = filter_input(INPUT_POST, 'mes', FILTER_SANITIZE_STRING);
$m = dataErp::meses();
$status = [1 => 'Freq.', 6 => 'Encerrado'];

//Iniciando a variavel
$rel = 0;

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
        $cab[$v['id_li']] = $v;
        $lista[$v['id_li']][$v['id_pessoa']] = $v;
    }
    foreach ($cab as $w => $v) {
        if (!empty($proximaFolha)) {
            ?>
            <div style="page-break-after: always"></div>
            <?php
        } else {
            $proximaFolha = 1;
        }
        ?>
        <table style="font-weight:bold; font-size:8pt; text-align: center; width: 100%">
            <thead>
                <tr>
                    <td colspan="7" style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
                        Lista de Alunos <?php echo $m[$mes] . '/' . date('Y') ?>
                    </td>
                </tr>
                <tr>     
                    <td colspan="7" class="topo" style="text-align:left; color: red; background-color: beige">
                        <?php echo $v['n_inst'] ?>
                    </td>
                </tr>
                <tr>
                    <td class="topo" style="width: 20%">
                        Empresa
                    </td>
                    <td class="topo" style="width: 15%">
                        Linha
                    </td>
                    <td class="topo" style="width: 15%">
                        Acessibilidade
                    </td>
                    <td class="topo" style="width: 15%">
                        Capacidade
                    </td>
                    <td class="topo" style="width: 15%">
                        Período
                    </td>
                    <td class="topo" style="width: 10%">
                        Nº Veículo
                    </td> 
                    <td class="topo" style="width: 10%">
                        Telefone
                    </td> 
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="topo" style="width: 20%">
                        <?php echo $v['n_em'] ?>
                    </td>
                    <td class="topo" style="width: 20%">
                        <?php echo $v['n_li'] ?>
                    </td>
                    <td class="topo" style="width: 15%">
                        <?php echo $v['acessibilidade'] ?>
                    </td>
                    <td class="topo" style="width: 15%">
                        <?php echo $v['capacidade'] ?>
                    </td>
                    <td class="topo" style="width: 10%">
                        <?php echo $v['periodo'] ?>
                    </td>
                    <td class="topo" style="width: 10%">
                        <?php echo $v['n_tv'] ?>
                    </td>
                    <td class="topo" style="width: 10%">
                        <?php echo $v['tel1'] ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="font-weight:bold; font-size:8pt; text-align: center; width: 100%">
            <thead>
                <tr>
                    <td class="topo" style="width: 52%">
                        Nome Motorista
                    </td>
                    <td class="topo" style="width: 48%">
                        Nome Monitor
                    </td>                   
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="topo" style="width: 50%">
                        <?php echo $v['motorista'] ?>
                    </td>
                    <td class="topo" style="width: 50%">
                        <?php echo $v['monitor'] ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <div>
            <table style="font-weight:bold; font-size:8pt; text-align: center; width: 100%">
                <thead>
                    <tr>
                        <td class="topo2">
                            Seq.
                        </td>
                        <td class="topo2">
                            RA
                        </td>
                        <td class="topo2">
                            Nome Aluno
                        </td>
                        <td class="topo2">
                            Cód.Classe
                        </td>
                        <td class="topo2">
                            Data Nasc.
                        </td>
                        <td class="topo2">
                            Status
                        </td>
                    </tr> 
                </thead>
                <?php
                $seq = 1;
                foreach ($lista[$w] as $d) {
                    ?>
                    <tbody>
                        <tr>
                            <td class="topo" style="background-color: <?php echo $cor ?>">
                                <?php echo $seq++ ?>
                            </td>
                            <td class="topo" style="background-color: <?php echo $cor ?>">
                                <?php echo $d['ra'] . "-" . $d['ra_dig'] ?>
                            </td>                        
                            <td class="topo" style="text-align:left; background-color: <?php echo $cor ?>">
                                <?php echo addslashes($d['n_pessoa']) ?>
                            </td>
                            <td class="topo" style="background-color: <?php echo $cor ?>">
                                <?php echo $d['codigo'] ?>
                            </td>
                            <td class="topo" style="background-color: <?php echo $cor ?>">
                                <?php echo dataErp::converteBr($d['dt_nasc']) ?>
                            </td>
                            <td class="topo" style="background-color: <?php echo $cor ?>">
                                <?php
                                echo $status[$d['fk_id_sa']];
                                $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5';
                                ?>
                            </td>
                        </tr> 
                    </tbody>
                    <?php
                }
                ?>
            </table>
        </div>
        <?php
    }
} else {
    echo "Não existe dados para relatório";
}

$pdf->exec();
?>