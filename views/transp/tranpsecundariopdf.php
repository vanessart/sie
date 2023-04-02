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
            color: red;

        }
    </style>
</head>

<?php
$mes = filter_input(INPUT_POST, 'mes', FILTER_UNSAFE_RAW);
$m = data::meses();
$dados = transporte::alunoFrequencia($_POST['mes']);
$pp = ['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite'];
$status_a = sql::get('transp_status_aluno', '*');
foreach ($status_a as $v){
    $st[$v['id_sa']]=$v['n_sa'];
}

if (!empty($dados)) {
    foreach ($dados['aluno'] as $k => $v) {
        $lista[$v['id_alu']] = $v;
    }

    if (!empty($proximaFolha)) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    } else {
        $proximaFolha = 1;
    }
    ?>
    <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
        Lista Geral de Alunos <?php echo $m[$mes] . '/' . date('Y') ?>
    </div>

    <div>
        <table style="font-weight:bold; font-size:8pt; text-align: center; width: 100%">
            <thead>
                <tr>
                    <td style="width: 4%" class="topo2">
                        Seq.
                    </td>
                    <td style="width: 6%" class="topo2">
                        Id Aluno
                    </td>
                    <td style="width: 24%" class="topo2">
                        Nome Escola
                    </td>             
                    <td style="width: 24%" class="topo2">
                        Nome Aluno
                    </td>
                    <td style="width: 5%" class="topo2">
                        Veículo
                    </td>
                    <td style="width: 5%" class="topo2">
                        Período
                    </td>
                    <td style="width: 8%" class="topo2">
                        RA
                    </td>
                    <td style="width: 7%" class="topo2">
                        Cód.Classe
                    </td>
                    <td style="width: 8%" class="topo2">
                        Status
                    </td>
                    <td style="width: 9%" class="topo2">
                        Data Início
                    </td>
                </tr> 
            </thead>
            <?php
            $seq = 1;
           
            foreach ($lista as $d) {
                ?>
                <tbody>
                    <tr>
                        <td class="topo" style="background-color: <?php echo $cor ?>">
                            <?php echo $seq++ ?>
                        </td>
                        <td class="topo" style="background-color: <?php echo $cor ?>">
                            <?php echo $d['id_alu'] ?>
                        </td>
                        <td class="topo" style="fonte-size: 7px; text-align:left; background-color: <?php echo $cor ?>">
                            <?php echo $d['n_inst'] ?>
                        </td>                       
                        <td class="topo" style="fonte-size: 7px; text-align:left; background-color: <?php echo $cor ?>">
                            <?php echo tool::abrevia($d['n_pessoa']) ?>      
                        </td>
                        <td class="topo" style="background-color: <?php echo $cor ?>">
                            <?php echo $d['n_tv'] ?>
                        </td> 
                        <td class="topo" style="background-color: <?php echo $cor ?>">
                            <?php echo $pp[$d['periodo']]?>
                        </td> 
                        <td class="topo" style="background-color: <?php echo $cor ?>">
                            <?php echo $d['ra'] . "-" . $d['ra_dig'] ?>
                        </td> 
                        <td class="topo" style="background-color: <?php echo $cor ?>">
                            <?php echo $d['codigo'] ?>
                        </td>
                        <td class="topo" style="background-color: <?php echo $cor ?>">
                            <?php echo $st[$d['fk_id_sa']] ?>
                        </td> 
                        <td class="topo" style="background-color: <?php echo $cor ?>">
                            <?php
                            echo data::converteBr($d['dt_inicio']);
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
} else {
    echo "Não existe dados para relatório";
}
tool::pdfsecretaria2('L');
?>