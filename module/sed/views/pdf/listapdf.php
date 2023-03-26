<?php
ob_start();
$escola = new escola(@$_POST['id_inst']);

$cor = '#F5F5F5';
$seq = 0;

$dados = listaencaminhamento($_POST['listaaluno']);
$es = nomeescola($_POST['listaaluno']);
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

<?php
if (!empty($proximaFolha)) {
    ?>
    <div style="page-break-after: always"></div>
    <?php
} else {
    $proximaFolha = 1;
}
?>

<div style="font-weight:bold; font-size:10pt; background-color: #000000; width: 679px; color:#ffffff; text-align: center">
    Lista de Alunos Encaminhados
</div>
<div style="border-style: solid; font-weight:bold; font-size:8pt; background-color: #F5F5F5; width: 679px; color: red; text-align: center">
    Escola Destino: <?php echo $es['n_inst'] ?>
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
            Ano/2023
        </td> 
        <td>
            Telefone
        </td>    
    </tr>    

    <?php
    foreach ($dados as $v) {
        ?>
        <tr>
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $seq += 1; ?>
            </td>  
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['id_pessoa'] ?>
            </td>  
            <td style="background-color: <?php echo $cor ?>">
                <?php echo $v['ra'] ?>
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
tool::pdfescola('P', @$_POST['id_inst']);

function nomeescola($id_inst) {

    $e = sql::get('instancia', 'n_inst', ['id_inst' => $id_inst], 'fetch');

    return $e;
}

function listaencaminhamento($id_esc) {

    $sql = "SELECT DISTINCT p.id_pessoa, p.n_pessoa, p.ra, p.dt_nasc,  GROUP_CONCAT(f.num) tel1, e.ciclo_futuro AS n_ciclo, i.n_inst FROM pessoa p "
            . " JOIN ge_encaminhamento e ON e.fk_id_pessoa = p.id_pessoa "
            . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = e.fk_id_turma AND ta.fk_id_pessoa = e.fk_id_pessoa "
            . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
            . " JOIN instancia i ON i.id_inst = e.escola_origem "
            . " left join telefones f on f.fk_id_pessoa = p.id_pessoa "
            . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl "
            . " WHERE e.escola_destino = '" . $id_esc . "' "
            . " AND i.id_inst = '" . tool::id_inst() . "' "
            . " AND e.status = 1"
            . " AND pl.at_pl = 1 "
            . " group by f.fk_id_pessoa "
            . "  ORDER BY p.n_pessoa ";


    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);

    return $array;
}
