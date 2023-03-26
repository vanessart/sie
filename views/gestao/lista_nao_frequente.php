<?php
ob_start();
$escola = new escola(@$_POST['id_inst']);
$cor = '#F5F5F5';
$seq = 1;
?>
<head>
    <style>
        td{
            font-size: 7pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            padding-left: 3px;
            padding-right: 3px;
            padding-top: 1px;
            padding-bottom: 1px;
        }
        .quebra { 
            page-break-before: always; 
        }

    </style>
</head>

<?php
        
 $wsql = "SELECT p.id_pessoa, p.ra, p.rg, p.sexo, p.n_pessoa, p.dt_nasc, ta.codigo_classe, ta.chamada,"
        . " ta.situacao, ta.dt_matricula, ta.dt_transferencia, ta.destino_escola, ta.destino_escola_cidade FROM pessoa p"
        . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = id_pessoa"
        . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
        . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
        . " WHERE ta.situacao <> 'Frequente'"
        . " AND ta.fk_id_inst = '" . tool::id_inst() . "' AND pl.at_pl = 1 ORDER BY ta.situacao, ta.codigo_classe";

$query = $model->db->query($wsql);
$cla = $query->fetchAll();



if (!empty($proximaFolha)) {
    ?>
    <div style="page-break-after: always"></div>
    <?php
} else {
    $proximaFolha = 1;
}
?>

<div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
    Alunos Transferidos
</div>
<table class="table tabs-stacked table-bordered">;

    <tr>
        <td>
            Seq.
        </td>
        <td >
            Ch       
        </td>
        <td >
            RSE       
        </td>
        <td>
            RA       
        </td>  
        <td>
            RG       
        </td>
        <td>
            Cód.Classe      
        </td>
        <td>
            Nome do Aluno
        </td>
        <td>
            Data Nasc.
        </td>
        <td>
            D. Matrícula
        </td>
        <td>
            D. Transferência
        </td>
        <td>
            Situação
        </td>
        <td>
            Destino Escola
        </td>
    </tr>    
    <?php
    foreach ($cla as $v) {
        ?>

        <tr>
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $seq ?>
            </td>
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['chamada'] ?>
            </td>
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['id_pessoa'] ?>
            </td>
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['ra'] ?>
            </td>
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['rg'] ?>
            </td>  
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['codigo_classe'] ?>
            </td> 
            <td style="text-align: left; background-color: <?php echo $cor; ?>">
                <?php echo addslashes($v['n_pessoa']) ?>
            </td>      
            <td style="background-color: <?php echo $cor ?>"> 
                <?php echo data::converteBr($v['dt_nasc']) ?>
            </td>
            <td style="background-color: <?php echo $cor ?>"> 
                <?php echo data::converteBr($v['dt_matricula']) ?>
            </td>
            <td style="background-color: <?php echo $cor ?>"> 
                <?php echo data::converteBr($v['dt_transferencia']) ?>
            </td>
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['situacao'] ?>
            </td>
            <td style="text-align: left; background-color: <?php echo $cor ?>">
                <?php echo $v['destino_escola'] . ' ' . $v['destino_escola_cidade']?>
                <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>
            </td>
        </tr>
        <?php
        $seq++;
    }
    ?>

</table>

<?php
tool::pdfescola('L', @$_POST['id_inst']);
?>