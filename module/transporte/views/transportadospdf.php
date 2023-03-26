<?php
ob_start();
if (!defined('ABSPATH'))
    exit;

$cor = '#F5F5F5';
$m = dataErp::meses();
$id_em = filter_input(INPUT_POST, 'id_em', FILTER_SANITIZE_NUMBER_INT);
$empresa = sqlErp::get('transporte_empresa', 'n_em', ['id_em' => $id_em], 'fetch')['n_em'];
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
$dados = transporteErp::alunoFrequencia($_POST['mes'], NULL, NULL, NULL, NULL, $id_em);
//$dados = $model->alunostransportados($_POST['mes']);

$escola = $model->pegaescola();
?>

<table style="font-weight:bold; font-size:8pt; text-align: center; width: 100%">
    <thead>
        <tr>
            <td colspan="6" style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
                Quantidade de Alunos Transportados <?php echo $m[$_POST['mes']] . '/' . date('Y') ?>
            </td>
        </tr>
        <tr>     
            <td class="topo" style="width: 50%">
                Nome Escola
            </td>
            <td class="topo" style="width: 14%">
                Empresa
            </td>
            <td class="topo" style="width: 9%">
                Vagas
            </td>
            <td class="topo" style="width: 9%">
                Manhã
            </td>
            <td class="topo" style="width: 9%">
                Tarde
            </td>
            <td class="topo" style="width: 9%">
                Total
            </td>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($escola as $k => $v) {
            if (!empty($dados[$k])) {
                foreach ($dados[$k] as $kk => $vv) {
                    if (empty($id_em) || (!empty($id_em) && !empty($dados['PeriodoT'][$k]))) {
                        ?>
                        <tr>     
                            <td class="topo" style= "text-align: left; background-color: <?php echo $cor ?>">
                                <?php echo $v ?>
                            </td>
                            <td class="topo" style="background-color: <?php echo $cor ?>">
                                <?php  echo $kk ?>
                            </td>
                            <td class="topo" style="background-color: <?php echo $cor ?>"> 
                                <?php echo (empty($dados['c'][$k][$kk]) ? '-' : array_sum($dados['c'][$k][$kk])) ?>
                            </td>
                            <td class="topo" style="background-color: <?php echo $cor ?>">
                                <?php echo (empty($dados[$k][$kk]['M']) ? '-' : $dados[$k][$kk]['M']) ?>
                            </td>
                            <td class="topo" style="background-color: <?php echo $cor ?>">
                                <?php echo (empty($dados[$k][$kk]['T']) ? '-' : $dados[$k][$kk]['T']) ?>
                            </td>
                            <td class="topo" style="background-color: <?php echo $cor ?>">
                                <?php
                                echo (empty($dados['PeriodoT'][$k][$kk]) ? '-' : $dados['PeriodoT'][$k][$kk]);
                                $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5';
                                ?>
                            </td>            
                        </tr>
                        <?php
                    }
               }
            }
        }
        ?>
    </tbody>
    <footer>
        <tr>    
            <td colspan="2" class="topo2" style="text-alig: left">
                Total
            </td>
            <td class="topo2">
                <?php echo (empty($dados['capaci']) ? 0 : array_sum($dados['capaci'])) ?>
            </td>
            <td class="topo2">
                <?php echo @$dados['Total']['M'] ?>
            </td>
            <td class="topo2">
                <?php echo @$dados['Total']['T'] ?>
            </td>
            <td class="topo2">
                <?php echo @$dados['Total']['M'] + @$dados['Total']['T'] ?>
            </td>     
        </tr>
    </footer>
</table>
<?php
$pdf->exec();
?>
