<?php
ob_start();
$escola = new escola();

$cor = '#F5F5F5';
$seq = 1;
?>
<head>
    <style>
        td{
            font-size: 8pt;
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
/*
  echo 'Favor Aguardar nova versão!!!';
  exit;
 */

$wsql = "SELECT * FROM mrv_beneficiado WHERE cie_ben = '" . tool::cie() . "'"
        . " AND fk_id_pl = 87 AND status_ben = '" . 'Deferida' . "' AND categoria IN(1)"
        . " ORDER BY classificacao_escola, media_final_ben Desc";

//      . " ORDER BY classificacao_geral, categoria, media_final_ben Desc";

$query = $model->db->query($wsql);
$lista = $query->fetchAll();

if (!empty($lista)) {
    if (!empty($proximaFolha)) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    } else {
        $proximaFolha = 1;
    }
    ?>

    <div style="font-weight:bold; border: 1px solid; font-size:10pt; color:red; text-align: center">
        Relação de Alunos Classificados do 9º Ano - Reserva de Vagas ITB 2022/2023 - Final
        <!-- Relação de Alunos Classificados- consulta -->
    </div>
    <table class="table tabs-stacked table-bordered">;
        <thead>
            <tr>
                <td rowspan="2">
                    Seq.       
                </td>
                <td rowspan="2">
                    Classificação
                    <br />
                    Rede
                    <!-- Chamada -->
                </td>
                <td rowspan="2">
                    Classificação
                    <br />
                    Escola       
                </td>
                <td rowspan="2">
                    RSE       
                </td> 
                <td rowspan="2">
                    Cod.Classe      
                </td>           
                <td rowspan="2">
                    Nome do Aluno
                </td>
                <td rowspan="2">
                    Data Nasc.
                </td>
                <td colspan="5">
                    Média
                </td>
            </tr>
            <tr>
                <td>
                    6º Ano   
                </td>
                <td>
                    7º Ano
                </td>
                <td>
                    8º Ano
                </td>
                <td>
                    9º Ano
                </td>
                <td>
                    Média Geral
                </td>       
            </tr>
        </thead>
        <?php
        $seq = 0;

        foreach ($lista as $v) {
            $seq += 1;
            ?>
            <tbody>
                <tr>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo $seq ?>                  
                    </td>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo $v['classificacao_geral'] ?>
                        <?php // echo '-' ?>
                    </td>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo $v['classificacao_escola'] ?>
                    </td>
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo $v['id_pessoa'] ?>
                    </td>
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo $v['turma_ben'] ?>
                    </td>
                    <td style="text-align: left; background-color: <?php echo $cor ?>">
                        <?php echo addslashes($v['n_pessoa']) ?>
                    </td>   
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo data::converteBr($v['dt_nasc']) ?>
                    </td>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo ($v['media6_ben'] == 0 ? '*' . $v['media6_ben'] : $v['media6_ben']) ?>
                    </td>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo ($v['media7_ben'] == 0 ? '*'. $v['media7_ben']: $v['media7_ben']) ?>
                    </td>
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo ($v['media8_ben'] == 0 ? '*' . $v['media8_ben'] : $v['media8_ben']) ?>
                    </td>
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo ($v['media9_ben'] == 0 ? '*' . $v['media9_ben'] : $v['media9_ben']) ?>
                    </td>
                    <td style="background-color:<?php echo $cor ?>">
                        <?php echo $v['media_final_ben'] ?>
                        <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>
                    </td>
                </tr>
            </tbody>
            <?php
        }
        ?>       
    </table>

    <div style="font-size: 8pt; text-align: center"><?= CLI_CIDADE ?>, <?php echo date('d') . ' ' . data::mes(date('m')) . ' ' . date('Y') . '.' ?></div>
    <br /><br /><br /><br />
    <div style="font-size: 8pt; text-align: center">_____________________________________________</div>
    <div style="font-size: 8pt; text-align:center">Carimbo e Assinatura do Diretor</div>

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