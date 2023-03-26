<?php
ob_start();
$escola = new escola(@$_POST['id_inst']);
$cor = '#F5F5F5';

if (!empty($_POST['mes'])) {

    switch ($_POST['mes']) {
        case 1:
        case 2:
        case 3:
        case 4:
        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
        case 10:
        case 11:
        case 12:
            $ms = $_POST['mes'];
            break;
        default:
            $ms = date('n');
    }
} else {
    $ms = date('n');
}
if (!empty($_POST['ano'])) {
    $ano = $_POST['ano'];
} else {
    $ano = date("Y");
}

//$semana = explode('|', $model->pegasemanaDesenv($ms));
$semana = explode('|', data::pegasemana($ms, $ano));

foreach ($_POST['sel'] as $v) {
    if (!empty($v)) {
        $idTumas[] = $v;
    }
}

$idTumas = implode(",", $idTumas);
$wsql = "Select p.id_pessoa, p.n_pessoa, ta.fk_id_turma, ta.chamada, ta.situacao, "
        . " t.codigo as codigo_classe "
        . " From pessoa p "
        . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa "
        . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
        . " Where ta.fk_id_turma  in (" . $idTumas . ") "
        . "order by ta.chamada";

$query = $model->db->query($wsql);
$listapiloto = $query->fetchAll();

foreach ($listapiloto as $v) {
    $cla[$v['fk_id_turma']][] = $v;
}
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
foreach ($cla as $kw => $w) {

    $prof = $model->pegaprof($kw);
    $per = explode('|', $model->completadadossala($kw));
    $con = $model->contaaluno($kw);
    $s = $model->pegasituacaosedlayout();
    $sit = $model->pegasituacaosedlayout();
    if (!empty($proximaFolha)) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    } else {
        $proximaFolha = 1;
    }
    ?>

    <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
        Lista de Chamada <?php echo " - " . $semana[4] . "/" . $per[1] ?>
    </div>

    <div style = "border-width: 0.5px; border-style: solid; font-weight:bold; font-size:8pt; text-align: left">
        <?php echo $per[0] . ' - Ano Letivo: ' . $per[1] . ' - Período: ' . $per[2] . ' - Prof. ' . $prof ?>
    </div>

    <table class="table tabs-stacked table-bordered">;
        <thead>
            <tr>
                <td rowspan="2" >
                    Ch.       
                </td> 
                <td rowspan="2">
                    RSE      
                </td> 
                <td rowspan="2">
                    Nome do Aluno
                </td>          
                <td rowspan="2">
                    Situação     
                </td>
                <?php
                $y = 0;
                $x = 1;

                While ($x <= $semana[3]) {
                    ?>
                    <td>
                        <?php echo substr(($semana[0]), $x - 1, 1); ?>
                    </td>
                    <?php
                    $x++;
                    $y = $y + 2;
                }
                ?>  
                <td rowspan="2">
                    Total de Faltas
                </td>
            </tr>
            <tr>

                <?php
                $y = 0;
                $x = 1;

                While ($x <= $semana[3]) {
                    ?>
                    <td>
                        <?php echo substr(($semana[1]), $y, 2); ?>
                    </td>
                    <?php
                    $x++;
                    $y = $y + 2;
                }
                ?>              
            </tr>
        </thead>

        <?php
        foreach ($w as $v) {
            ?>
            <tbody>
                <tr>
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo $v['chamada'] ?>
                    </td> 
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo $v['id_pessoa'] ?>
                    </td>
                    <td style="text-align: left;background-color: <?php echo $cor ?>">
                        <?php echo $v['n_pessoa'] ?>
                    </td>                      
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo $v['situacao'] ?>
                    </td> 
                    <?php
                    $x1 = 1;
                    $x2 = 0;
                    While ($x1 <= $semana[3]) {
                        ?>
                        <td style="background-color: <?php echo $cor ?>">
                            <?php echo $model->wpegaferiados($ms, substr($semana[1], $x2, 2), $per[0]) ?>
                        </td>
                        <?php
                        $x1++;
                        $x2 = $x2 + 2;
                    }
                    ?>  
                    <td style="background-color: <?php echo $cor ?>">
                        <?php echo " " ?>
                        <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>
                    </td>
                </tr>              
            </tbody>
            <?php
        }
        ?>

    </table>

    <div style="font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid; padding-left: 80px">
        Resumo Situação
        <table style="font-weight: bolder; border-width: 0.5px; border-style: solid">
            <tr>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[2] . ' = ' . $con[$sit[2]] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[6] . ' = ' . $con[$sit[6]] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[3] . ' = ' . $con[$sit[3]] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[1] . ' = ' . $con[$sit[1]] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[4] . ' = ' . $con[$sit[4]] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[8] . ' = ' . $con[$sit[8]] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[5] . ' = ' . $con[$sit[5]] ?>
                </td>  
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[7] . ' = ' . $con[$sit[7]] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    Outras Situações = -
                </td>  
                <td style="color: red; font-weight: bolder">
                    <?php
                    $t = $con[$sit[1]] + $con[$sit[2]] + $con[$sit[3]] + $con[$sit[4]] + $con[$sit[5]] + $con[$sit[6]] + $con[$sit[7]] + $con[$sit[8]];
                    ?>
                    Total Alunos Lista = <?php echo $t ?>
                </td>          
            </tr>
        </table>
    </div>

    <?php
}
tool::pdfescola('L', @$_POST['id_inst']);
?>