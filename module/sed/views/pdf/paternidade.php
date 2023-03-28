<?php
if (!defined('ABSPATH'))
    exit;

$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$pls = [81, 84, 87, 110];

$pat = $model->paternidade($pls, $id_inst);

if ($pat) {
    $ids = array_column($pat, 'id_pessoa');
    $sql = "SELECT `ddd`, `num`, fk_id_pessoa FROM `telefones` WHERE `fk_id_pessoa` IN (" . implode(', ', $ids) . ") ";
    $query = pdoSis::getInstance()->query($sql);
    $t = $query->fetchAll(PDO::FETCH_ASSOC);
}

foreach ($t as $v) {
    if (is_numeric($v['num']) && empty($tel[$v['fk_id_pessoa']])) {
        $tel[$v['fk_id_pessoa']] = '(' . $v['ddd'] . ') ' . $v['num'];
    }
}

foreach ($pat as $k => $v) {
    $pat[$k]['telefone'] = @$tel[$v['id_pessoa']];
}

ob_clean();
ob_start();
$pdf = new pdf();
$pdf->orientation = 'L';
$pdf->mgt = 26;
$pdf->mgh = 10;
$pdf->mgr = 5;
$pdf->mgl = 10;
$pdf->mgf = 2;
$cor = '#F5F5F5';
$seq = 1;
?>
<head>
    <style>
        .topo{
            font-size: 6pt;
            font-weight:bold;
            text-align: center;
            border-style: solid;
            border-width: 0.5px;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 2px;
            padding-bottom: 2px;
        }
    </style>
</head>

<div style="font-weight:bold; border: 1px solid; font-size:10pt; color:red; text-align: center">
    Projeto Paternidade Responsável <br />
    Relação de Alunos Sem Paternidade Estabelecida (Ausência de Pai na Certidão de Nascimento)
</div>
<?php

if (!empty($pat)) {
    if (!empty($proximaFolha)) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    } else {
        $proximaFolha = 1;
    }
    ?>
    <table style="width: 100%" class="table tabs-stacked table-bordered">;
        <thead>
            <tr>
                <td style="width: 3%" class="topo">
                    Seq.       
                </td>
                <td style="width: 4%" class="topo">
                    Ingresso       
                </td>
                <td style="width: 6%" class="topo"> 
                    RA       
                </td>            
                <td style="width: 20%" class="topo">
                    Nome do Aluno
                </td>
                <td style="width: 3%" class="topo">
                    Sexo
                </td>
                <td style="width: 6%" class="topo">
                    Data Nasc.
                </td>
                <td style="width: 20%" class="topo">
                    Nome da Mãe ou Responsável Legal   
                </td>
                <td style="width: 26%" class="topo">
                    Endereço
                </td>
                <td style="width: 6%" class="topo">
                    CEP
                </td>    
                <td style="width: 6%" class="topo">
                    Telefone
                </td>
            </tr>
        </thead>
        <?php
        foreach ($pat as $v) {
            ?>
            <tbody>
                <tr>
                    <td style="background-color: <?= $cor ?>" class="topo">
                        <?= str_pad($seq++, 2, '0', STR_PAD_LEFT) ?>
                    </td>
                    <td style="background-color:<?= $cor ?>" class="topo">
                        <?= $v['periodo_letivo'] ?>
                    </td>
                    <td style="text-align: left; background-color:<?= $cor ?>" class="topo">
                        <?= $v['ra'] . '-' . $v['ra_uf'] ?>
                    </td>
                    <td style="text-align: left; background-color:<?= $cor ?>" class="topo">
                        <?= addslashes($v['n_pessoa']) ?>
                    </td>
                    <td style="background-color:<?= $cor ?>" class="topo">
                        <?= $v['sexo'] ?>
                    </td>
                    <td style="background-color: <?= $cor ?>" class="topo">                       
                        <?= data::converteBr($v['dt_nasc']) ?>
                    </td>   
                    <td style="text-align: left; background-color: <?= $cor ?>" class="topo">
                        <?= addslashes($v['mae']) ?>
                    </td>
                    <td style="font-size: 6px; text-align: left; background-color: <?= $cor ?>" class="topo">
                        <?= addslashes($v['logradouro']) . ' Nº. ' . $v['num'] . (empty($v['complemento']) ? ' ' : ' ' . $v['complemento']) . ' ' . $v['bairro'] . ' ' . $v['cidade'] . '-SP' ?>
                    </td>
                    <td style="background-color:<?php echo $cor ?>" class="topo">
                        <?php echo $v['cep'] ?>
                    </td>
                    <td style="font-size: 6px; background-color:<?php echo $cor ?>" class="topo">
                        <?= $v['telefone'] ?>
                        <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>
                    </td>
                </tr>
            </tbody> 
            <?php
        }
        ?>
    </table>

    <br /><br /><br /><br />  
    <div style="font-size: 8pt; text-align: justify; font-weight:bold">
        Declaro para os fins legais que a presente relação nominal tem origem na consulta do documento original de Certidão de Nascimento dos alunos, em atendimento ao Projeto Paternidade Responsável do Poder Judiciário do Estado de São Paulo, Foro de <?= CLI_CIDADE ?>, 4a. Vara Cível.
    </div>
    <br /><br />
    <div style="font-size: 8pt; text-align: center; font-weight:bold">
        <?= CLI_CIDADE ?>, <?php echo date('d') . ' ' . data::mes(date('m')) . ' ' . date('Y') . '.' ?>
    </div>
    <br />
    <div style="font-size: 8pt; text-align: center; font-weight:bold">
        ____________________________________________
    </div>
    <div style="font-size: 8pt; text-align:center; font-weight:bold">
        Carimbo e Assinatura do Diretor
    </div>
    <?php
} else {
    ?>
    <div style="font-weight:bold; border: 1px solid; font-size:10pt; color:red; text-align: center">
        <br /><br />
        Não existe dados para relatório
        <br /><br /><br /><br /><br /><br />
    </div>  
    <br /><br />
    <div style="font-size: 8pt; text-align: center; font-weight:bold">
        <?= CLI_CIDADE ?>, <?php echo date('d') . ' ' . data::mes(date('m')) . ' ' . date('Y') . '.' ?>
    </div>
    <br />
    <div style="font-size: 8pt; text-align: center; font-weight:bold">
        ____________________________________________
    </div>
    <div style="font-size: 8pt; text-align:center; font-weight:bold">
        Carimbo e Assinatura do Diretor
    </div>
    <?php
}

$pdf->exec();
