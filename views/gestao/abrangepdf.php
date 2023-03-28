<?php
ob_start();
$abrange = sql::get('ge_abrangencia', '*', ['fk_id_inst' => tool::id_inst()]);
$escola = new escola();

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
<div>
</div>
<table class="table tabs-stacked table-bordered">;
    <thead>
        <tr>
            <td colspan="5" style="font-weight:bold; font-size:10pt; background-color: #000000; width: 679px; color:#ffffff; text-align: center">           
                Ruas de Abrangência              
            </td>
        </tr>
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
    foreach ($abrange as $v) {
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
                    <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>
                </td>      
            </tr>
        </tbody>      
        <?php
    }
    ?>
        
</table>
<div>
    <br />
    <div style="text-align: right"><?= CLI_CIDADE ?>, <?php echo date('d') . ' ' . data::mes(date('m')) . ' ' . date('Y') . '.' ?></div>
    <br /><br /><br /><br />
    <div style="text-align: center">_____________________________________</div>
    <div style="text-align:center">Carimbo e Assinatura</div>
</div>
<!--'P', 'c','A4', $default_font_size = 0, $default_font = '', $mgl = 15, $mgr = 15, $mgt = 35, $mgb = 20, $mgh = 9, $mgf = 9) 
'P','c','A4',0,'',15,10,35,20,9,30
-->
<?php
tool::pdfEscola('P','c','A4',0,'',15,10,35,20,9,3);
?>