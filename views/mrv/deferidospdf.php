<?php
ob_start();
$escola = new escola(@$_POST['id_inst']);
$cor = '#F5F5F5';
?>
<head>
    <style>
        td{
            font-size: 7pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            padding: 5px;

        }
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
$i = 'Deferida';
$lista = $model->situacaoalunosfinal($i);
?>

<div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
    Lista de Alunos Deferidos
</div>

<table class="table tabs-stacked table-bordered">;

    <tr>
        <td >
            nº Ch       
        </td>
        <td >
            Cód. Turma       
        </td>
        <td >
            RSE       
        </td>
        <td>
            RA       
        </td>       
        <td>
            Data Nasc.
        </td>
        <td>
            Nome Aluno
        </td>
        <td>
            Situação
        </td>
        <td>
            Categoria     
        </td>  
        <td>
            Média Final     
        </td>
        
    </tr>    
    <?php
    foreach ($lista as $v) {
        ?>
        <tr>
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['num_chamada_ben'] ?>
            </td>
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['turma_ben'] ?>
            </td>
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['id_pessoa'] ?>
            </td>
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['ra_ben'] ?>
            </td>
            <td style="background-color: <?php echo $cor ?>"> 
                <?php echo data::converteBr($v['dt_nasc']) ?>
            </td>
            <td style="text-align: left; background-color: <?php echo $cor; ?>">
                <?php echo addslashes($v['n_pessoa']) ?>
            </td>      
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['status_ben'] ?>
            </td>
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['categoria'] ?>
            </td>
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['media_final_ben'] ?>
                <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>
            </td>        
        </tr>
        <?php
    }
    ?>
</table>
<div style="page-break-after: always"></div>

<?php
tool::pdfescola('P', @$_POST['id_inst']);
?>