<?php
ob_start();
$escola = new escola();

$cor = '#F5F5F5';
$seq = 1;
?>
<head>
    <style>
        td{
            font-size: 6pt;
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
$wsql = "SELECT p.id_pessoa, p.n_pessoa, p.dt_nasc, p.mae, p.pai, t.codigo,"
        . " e.cep, e.logradouro, e.num, e.complemento, e.bairro,"
        . " e.cidade, e.uf, p.tel1, p.responsavel FROM pessoa p"
        . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
        . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
        . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
        . " LEFT JOIN endereco e ON e.fk_id_pessoa = p.id_pessoa"
        . " WHERE p.pai = 'SPE' AND ta.situacao = 'Frequente'"
        . " AND ta.fk_id_inst = '" . tool::id_inst() . "'"
        . " AND pl.at_pl = 1 Order by t.codigo, p.n_pessoa";
        
$query = $model->db->query($wsql);
$lista = $query->fetchAll();
if(!empty($lista)){
if (!empty($proximaFolha)) {
    ?>
    <div style="page-break-after: always"></div>
    <?php
} else {
    $proximaFolha = 1;
}
?>

<div style="font-weight:bold; border: 1px solid; font-size:10pt; color:red; text-align: center">
    Relação de Alunos Sem Paternidade Estabelecida (Ausência de Pai na Certidão de Nascimento)
</div>
<table class="table tabs-stacked table-bordered">;
    <thead>
        <tr>
            <td>
                Seq.       
            </td>
            <td>
                RSE       
            </td> 
            <td>
                Cod.Classe      
            </td>           
            <td>
                Nome do Aluno
            </td>
            <td>
                Data Nasc.
            </td>
            <td>
                Nome Responsável Legal   
            </td>
            <td>
                Parentesco
            </td>
            <td>
                Telefone
            </td>
            <td>
                CEP
            </td>
            <td>
                Endereço
            </td>       
        </tr>
    </thead>
    <?php
    foreach ($lista as $v) {
        ?>
        <tbody>
            <tr>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $seq++ ?>
                </td>
                <td style="background-color:<?php echo $cor ?>">
                    <?php echo $v['id_pessoa'] ?>
                </td>
                <td style="background-color:<?php echo $cor ?>">
                    <?php echo $v['codigo'] ?>
                </td>
                <td style="text-align: left;background-color: <?php echo $cor ?>">
                    <?php echo addslashes($v['n_pessoa']) ?>
                </td>   
                <td style="text-align: left;background-color: <?php echo $cor ?>">
                    <?php echo data::converteBr($v['dt_nasc']) ?>
                </td>
                <td style="text-align: left;background-color: <?php echo $cor ?>">
                    <?php echo addslashes($v['responsavel']) ?>
                </td>
                <td style="text-align: left;background-color: <?php echo $cor ?>">
                    <?php echo $a=($v['mae']==$v['responsavel'])? 'Mãe': 'Outros' ?>
                </td>
                <td style="background-color:<?php echo $cor ?>">
                    <?php echo $v['tel1'] ?>
                </td>
                <td style="background-color:<?php echo $cor ?>">
                    <?php echo $v['cep'] ?>
                </td>
                <td style="font-size: 6px; text-align: left; background-color:<?php echo $cor ?>">
                    <?php echo addslashes($v['logradouro'] . ' nº.' . $v['num'] . ' ' . $v['complemento'] . ' ' . $v['bairro'] . '-' . $v['cidade'] . '-' . $v['uf']) ?>
                    <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>
                </td>
            </tr>
        </tbody>
        <?php
    }
    ?>       
</table>

<div style="font-size: 7pt; text-align: center">Barueri, <?php echo date('d') . ' ' . data::mes(date('m')) . ' ' . date('Y') . '.' ?></div>
<br /><br /><br /><br />
<div style="font-size: 7pt; text-align: center">_____________________________________________</div>
<div style="font-size: 7pt; text-align:center">Carimbo e Assinatura do Diretor</div>

<?php
} else {
    ?>
<div class="fieldTop">
   Dados não encontrados
   
</div>
                                <?php
}
tool::pdfEscola('L');
?>