<?php
ob_start();

$esc = sql::get(['ge_escolas', 'instancia'], 'id_inst, n_inst', 'order by n_inst');

$escola = new escola();
//echo $escola->cabecalho();
$seq = 1;
$cor = '#F5F5F5';
?>
<head>
    <style type="text/css">
        .topo{
            font-size: 7pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            padding: 5px; 
        }
        
        </style>
    </head>

    <?php
    foreach ($esc as $w) {
        $es = sql::get(['instancia', 'ge_abrangencia'], 'n_inst, cep, logradouro, bairro, cidade, uf', ['id_inst' => $w['id_inst'], '>' => 'n_inst']);
        if (!empty($es)) {
            $seq = 1;
            ?>
            
            <div style="font-weight:bold; border-style: solid; font-size:10pt; background-color: #000000; width: 679px; color:#ffffff; text-align: center">
                    Ruas de Abrangências
                </div>
                <div style="font-weight:bold; border-style: solid; border: 1px; font-size:10pt; color:#000000; background-color: #FAFAFA; width: 679px; text-align: center">
                    <?php echo $w['n_inst'] ?>
                </div>
                <table class="table tabs-stacked table-bordered">;
                    <thead>
                        <tr>
                            <td class="topo">
                                Seq.
                            </td>
                            <td class="topo">
                                CEP       
                            </td>
                            <td class="topo">
                                Logradouro       
                            </td>
                            <td class="topo">
                                Bairro      
                            </td>       
                            <td class="topo">
                                Cidade/Estado
                            </td>
                        </tr>    
                    </thead>

                    <?php
                    foreach ($es as $v) {
                        ?>
                        <tbody>
                            <tr>
                                <td style="background-color: <?php echo $cor ?>" class="topo" >
                                    <?php echo $seq++ ?>
                                </td>
                                <td style="text-align: center; background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo $v['cep'] ?>
                                </td>
                                <td style="text-align: left; background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo $v['logradouro'] ?>
                                </td>
                                <td style="text-align: center; background-color: <?php echo $cor ?>" class="topo">
                                    <?php echo $v['bairro'] ?>
                                </td>
                                <td style="text-align: center; background-color: <?php echo $cor ?>" class="topo">
                                <?php echo $v['cidade'] . ' - ' . $v['uf'] ?>
                                <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA': $cor = '#F5F5F5'?>
                            </td>      
                        </tr>
                    </tbody>      
                    <?php
                }
                ?>

                <?php ?>
            </table>
            <div style="page-break-after: always"></div>
            <?php
        }
    }

    tool::pdfEscola();
    ?>