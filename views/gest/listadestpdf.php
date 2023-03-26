<?php
ob_start();

$cor = '#F5F5F5';
$seq = 0;

$dados = $model->listaencaminhamentodestino();
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
    </style>
</head>

<div style="font-weight:bold; font-size:10pt; background-color: #000000; width: 679px; color:#ffffff; text-align: center">
    Lista de Alunos Recebidos
</div>

<?php
if(empty($dados)){
    exit();
}
foreach ($dados as $k => $e) {

    if (!empty($proximaFolha)) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    } else {
        $proximaFolha = 1;
    }
    ?>

    <div style="border-style: solid; font-weight:bold; font-size:8pt; background-color: #F5F5F5; width: 679px; color: red; text-align: center">
        Escola Origem: <?php echo $k ?>
    </div>

    <table class="table tabs-stacked table-bordered">;
        <tr>
            <td>
                Seq.       
            </td>    
            <td>
                RSE      
            </td>
            <td>
                RA       
            </td>
            <td>
                Data Nasc.       
            </td>   
            <td>
                Nome do Aluno
            </td>
            <td>
                Ano/2022
            </td> 
            <td>
                Telefone
            </td>    
        </tr>    

    <?php
    foreach ($e as $v) {
        ?>
            <tr>
                <td style="background-color: <?php echo $cor ?>">
            <?php echo $seq += 1; ?>
                </td>  
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['id_pessoa'] ?>
                </td>  
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['ra'] .'-'.$v['ra_dig']?>
                </td>  
                <td style="background-color: <?php echo $cor ?>"> 
                    <?php echo data::converteBr($v['dt_nasc']) ?>
                </td>  
                <td style="text-align: left; background-color: <?php echo $cor ?>">
                    <?php echo addslashes($v['n_pessoa']) ?>
                </td>    
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['n_ciclo'] ?>
                </td>  
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['tel1'] ?>
        <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>
                </td>              
            </tr>
                    <?php
                }
                ?>
    </table>
        <?php
    }

    tool::pdfescola('P', @$_POST['id_inst']);
    ?>