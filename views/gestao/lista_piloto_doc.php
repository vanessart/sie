<?php
ob_start();
$escola = new escola(@$_POST['id_inst']);
$cor = '#F5F5F5';
$s = $model->pegasituacaosedlayout();
$sit2 = $model->pegasituacaosedlayout();
?>
<head>
    <style>
        td{            
            font-weight:bolder;
            text-align: center;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 1px;
            padding-bottom: 1px;
            font-size: 7pt;
        }
        .quebra { 
            page-break-before: always; 
        }
    </style>
</head>

<?php
foreach ($_POST['sel'] as $v) {
    if (!empty($v)) {
        $idTumas[] = $v;
    }
}

$lista = $model->wlistapilotodocend($idTumas, 'doc');

$peri = ['M' => 'Manhã', 'T' => 'Tarde', 'G' => 'Geral', 'N' => 'Noite'];

$sit = ['Abandono' => 'AB', 'Evadido' => 'EV', 'Falecido' => 'FA', 'Frequente' => 'FR', 'Não Comparecimento' => 'NC', 'Remanejado' => 'RJ', 'Reclassificado' => 'RC', 'Transferido Escola' => 'TE', 'Outras Situações' => 'OS'];

foreach ($lista as $v) {

    $cla[$v['id_turma']][] = $v;
    @$periodo_letivo[$v['fk_id_turma']] = $v['periodo_letivo'];
    @$periodo[$v['id_turma']] = $peri[$v['periodo']];
    @$sit_ab[$v['id_pessoa']] = $sit[$v['situacao']];
}

foreach ($cla as $kw => $w) {

    $prof = $model->pegaprof($kw);
    $per = explode('|', $model->completadadossala($kw));
    $con = $model->contaaluno($kw);

    if (!empty($proximaFolha)) {
        ?>
        <div style="page-break-after: always"></div>
        <?php
    } else {
        $proximaFolha = 1;
    }
    ?>

    <div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
        Lista Piloto - Documentos
    </div>

    <div style = "border-width: 0.5px; border-style: solid; font-weight:bold; font-size:8pt; text-align: left">
        <?php echo $per[0] . ' - Ano Letivo: ' . $per[1] . ' - Período: ' . $periodo[$kw] . ' - Prof. ' . $prof; ?>
    </div>

    <table class="table tabs-stacked table-bordered">;

        <tr>
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
                INEP
            </td>
            <td>
                RG
            </td>
            <td>
                Data Nasc.
            </td>
            <td>
                Nome Aluno
            </td>
            <td>
                Certidão Nasc.
            </td>
            <td>
                SUS
            </td>
            <td>
                CPF
            </td>
            <!--
            <td>
                Responsável     
            </td>
            -->
            <td>
                Sit.     
            </td>
        </tr>    
        <?php
        foreach ($w as $v) {
            ?>

            <tr>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['chamada'] ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['id_pessoa'] ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['ra'] . '-' . @$v['ra_dig'] ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['inep'] ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo (ltrim($v['rg'],"0")) ?>
                </td>
                <td style="background-color: <?php echo $cor ?>"> 
                    <?php echo data::converteBr($v['dt_nasc'])
                    ?>
                </td>
                <!-- Deixei abrevia caso voltar responsável -->
                <td style="text-align: left; background-color: <?php echo $cor; ?>">
                    <?php echo tool::abrevia($v['n_pessoa'], 60) ?>                 
                </td>  
                <?php
                        
                if (!empty($v['novacert_acervo'])) {
                    $cert[1] = str_pad($v['novacert_cartorio'], 6, '0', STR_PAD_LEFT);
                    $cert[2] = str_pad($v['novacert_acervo'], 2, '0', STR_PAD_LEFT);
                    $cert[3] = str_pad($v['novacert_regcivil'], 2, '0', STR_PAD_LEFT);
                    $cert[4] = str_pad($v['novacert_ano'], 4, '0', STR_PAD_LEFT);
                    $cert[5] = str_pad($v['novacert_tipolivro'], 1, '0', STR_PAD_LEFT);
                    $cert[6] = str_pad($v['novacert_numlivro'], 5, '0', STR_PAD_LEFT);
                    $cert[7] = str_pad($v['novacert_folha'], 3, '0', STR_PAD_LEFT);
                    $cert[8] = str_pad($v['novacert_termo'], 7, '0', STR_PAD_LEFT);
                    $cert[9] = str_pad($v['novacert_controle'], 2, '0', STR_PAD_LEFT);
                    
                    $novacertidao = $cert[1] . '-' . $cert[2] . '-' . $cert[3] . '-' . $cert[4] . '-'
                            . $cert[5] . '-' . $cert[6] . '-' . $cert[7] . '-'
                            . $cert[8] . '-' . $cert[9];
                } else {
                    $novacertidao = $v['certidao'];
                }
                ?>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $novacertidao ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['sus'] ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['cpf'] ?>
                </td>
                <!--
                <td style="text-align: left; background-color: <?php echo $cor; ?>">
                    <?php 
                    echo tool::abrevia($v['responsavel'], 20) 
                    //echo $v['responsavel'];
                    ?>
                </td>  
                -->
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo @$sit_ab[$v['id_pessoa']] ?>
                    <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>
                </td>
            </tr>
            <?php
        }
        ?>

    </table>
    <div style="font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid; width: 100%">
        Resumo Situação
        <table style="font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid; width: 100%">
            <tr>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[2] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[6] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[3] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[1] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[4] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[8] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[5] ?>
                </td>  
                <td style="color: red; font-weight: bolder">
                    <?php echo $s[7] ?>
                </td>
                <td style="color: red; font-weight: bolder">
                    Outras Situações (OS)
                </td>  
                <td style="color: red; font-weight: bolder">
                    Total Alunos Lista
                </td>          
            </tr>
            <tr>
                <td>
                    <?php echo $con[$sit2[2]] ?>
                </td>
                <td>
                    <?php echo $con[$sit2[6]] ?>
                </td>
                <td>
                    <?php echo $con[$sit2[3]] ?>
                </td>
                <td>
                    <?php echo $con[$sit2[1]] ?>
                </td>
                <td>
                    <?php echo $con[$sit2[4]] ?>
                </td>
                <td>
                    <?php echo $con[$sit2[8]] ?>
                </td>
                <td>
                    <?php echo $con[$sit2[5]] ?>
                </td>
                <td>
                    <?php echo $con[$sit2[7]] ?>
                </td>         
                <td>
                    <?php echo ' - ' ?>
                </td>     
                <td>
                    <?php
                    $t = $con[$sit2[1]] + $con[$sit2[2]] + $con[$sit2[3]] + $con[$sit2[4]] + $con[$sit2[5]] + $con[$sit2[6]] + $con[$sit2[7]] + $con[$sit2[8]];
                    echo $t;
                    ?>
                </td>   
            </tr>
        </table>
    </div>

    <?php
}
tool::pdfescola('L', @$_POST['id_inst']);
?>