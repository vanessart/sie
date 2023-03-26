<?php
ob_start();

$cor = '#F5F5F5';
$seq = 0;

$dados = listaencaminhamentodestino();
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
if ($dados) {
    ?>
    <div style="font-weight:bold; font-size:10pt; background-color: #000000; width: 679px; color:#ffffff; text-align: center">
        Lista de Alunos Recebidos
    </div>
    <?php
} else {
    ?>
    <div style="font-weight:bold; font-size:10pt; background-color: #000000; width: 679px; color:#ffffff; text-align: center">
        Não há Alunos Recebidos 
    </div>
    <?php
    tool::pdfescola('P', @$_POST['id_inst']);

    die();
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
                    <?php echo $v['ra'] . '-' . $v['ra_dig'] ?>
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

function listaencaminhamentodestino() {

    $sql = "SELECT p.id_pessoa, p.n_pessoa, p.ra, p.ra_dig, p.dt_nasc, GROUP_CONCAT(f.num) tel1, e.ciclo_futuro AS n_ciclo, i.n_inst, i.id_inst FROM pessoa p "
            . " JOIN ge_encaminhamento e ON e.fk_id_pessoa = p.id_pessoa "
            . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = e.fk_id_turma AND ta.fk_id_pessoa = e.fk_id_pessoa "
            . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
            . " JOIN instancia i ON i.id_inst = e.escola_origem "
            . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl AND pl.at_pl = 1  "
            . " left join telefones f on f.fk_id_pessoa = p.id_pessoa "
            . " WHERE e.escola_destino = '" . tool::id_inst() . "' AND e.status = 1 "
            . " group by f.fk_id_pessoa ";

    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($array as $v) {
        @$dadosaluno[$v['n_inst']][] = $v;
    }
    return @$dadosaluno;
}
