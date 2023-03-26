<?php
ob_clean();
ob_start();
$pdf = new pdf();
$pdf->orientation = 'L';
$pdf->mgl = 15;
$pdf->mgr = 15;
$pdf->mgt = 58;
$pdf->mgb = 15;
$pdf->mgh = 20;
$pdf->mgf = 6;
$escola = new escola();
?>
<style>
    td{
        text-align: center;
        font-size: 10px;
    }
    .topo{
        font-size: 10pt;
        font-weight:bolder;
        text-align: center;
        padding: 1px 3px 1px 3px;
        border: 1px solid;
    }
    .topo2{
        font-size: 10pt;
        font-weight: bolder;
        text-align: center;
        padding: 1px;
        border: 1px solid;
        width: 25px;
    }
    .topo4{
        font-size: 8pt;
        font-weight: bolder;
        text-align: center;
        padding: 1px;
        border: 1px solid;
    }
    .topo3{
        font-size: 8pt;
        font-weight: bolder;
        text-align: center;
        padding: 15px 2px 15px 2px;
        border: 1px solid;
    }
    .titulo{
        font-weight:bold;
        font-size:12pt;
        background-color: #000000;
        color:#ffffff;
        text-align: center;
    }
    .quebra{
        page-break-after: always;
    }
</style>
<?php
$sitFinal = sql::idNome('ge_situacao_final');
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
if ($id_turma) {
    $ementa = sql::get('avalia_ementa', 'n_ementa', ['fk_id_turma' => $id_turma]);
    $model->ataFinal($id_turma);
    $notaFalta = $model->_notaFaltaFinal;
    if (empty($notaFalta)) {
        ?>
        <link href="<?php echo HOME_URI; ?>/includes/css/bootstrap5.min.css" rel="stylesheet">
        <br /><br /><br />
        <div class="aler alert-danger" style="font-weight: bold; font-size: 1.4em; text-align: center">
            Realise o conselho antes de abrir a ATA.
        </div>
        <?php
        die();
    }
    $alunos = $model->_alunos;
    $turma = $model->_turma;

    $resumo = $model->resumo();
    $situacao = sql::idNome('ge_turma_aluno_situacao');
    $situacaoMostra = [0, 9, 1, 5, 2, 8, 4];
    @$prof = gtTurmas::professores($id_turma)[1]; // a chave é o id_disc

    $disciplinas = $model->_disciplinas; // a chave é o id_disc
    $n_pl = sql::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $turma['id_pl']], 'fetch')['n_pl'];

    $titulo = "<div style=\"Width: 100%\" class=\"titulo\">"
            . "ATA FINAL - Período Letivo: " . $n_pl
            . "</div>"
            . "<div class=\"topo\">"
            . $turma['n_turma'] . ' - Período: ' . $n_pl
            . "</div>";
    $pdf->headerAlt = $escola->cabecalho() . $titulo;
    ?>
    <table style="width: 100%;" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
        <?php
        topTablela($disciplinas);
        $contAluno = count($alunos);
        $cabeca = array();
        if ($contAluno > 18) {
            if ($contAluno <= 36) {
                $cabeca[] = intval($contAluno / 2);
            } else {
                $cabeca[] = intval($contAluno / 3);
                $cabeca[] = intval(($contAluno / 3) * 2);
            }
        }
        $ct = 1;
        foreach ($alunos as $v) {
            @$frequente[$v['id_tas']]++;
            @$id_sfs[$v['id_sf']]++;
            if (in_array($v['id_tas'], $situacaoMostra)) {
                @$situaçãoQt[$v['id_tas']]++;
            } else {
                @$situaçãoQt['outros']++;
            }
            ?>
            <tr>
                <td>
                    <?= $v['chamada'] ?>
                </td>
                <td>
                    <?= $v['id_pessoa'] ?>
                </td>
                <td style="text-align: left">
                    <?= $v['n_pessoa'] ?>
                </td>
                <?php
                foreach ($disciplinas as $id_disc => $d) {
                    if ($d['nucleo_comum'] == 1) {
                        $nota = $v['id_tas'] != 0 ? null : @$notaFalta[$v['id_pessoa']]['media_' . $id_disc];
                        ?>
                        <td class="topo2" style="color: <?= $model->notaCor($nota) ?>">
                            <?= $nota ?>
                        </td>
                        <?php
                    }
                }
                foreach ($disciplinas as $id_disc => $d) {
                    if ($d['nucleo_comum'] == 0) {
                        $nota = $v['id_tas'] != 0 ? null : @$notaFalta[$v['id_pessoa']]['media_' . $id_disc];
                        $falta = $v['id_tas'] != 0 ? null : @$notaFalta[$v['id_pessoa']]['falta_' . $id_disc];
                        ?>
                        <td class="topo2" style="color: <?= $model->notaCor($nota) ?>">
                            <?= str_replace('.', ',', $nota) ?>
                        </td>
                        <td class="topo2">
                            <?= $falta ?>
                        </td>
                        <?php
                    }
                }
                ?>
                <td class="topo2">
                    <?php
                    $faltaTotal = $v['id_tas'] != 0 ? null : @$notaFalta[$v['id_pessoa']]['falta_nc'];
                    echo $faltaTotal;
                    ?>
                </td>
                <td>
                    <?php
                    if (!empty($v['id_sf'])) {
                        echo $sitFinal[$v['id_sf']];
                    } else {
                        echo $v['n_tas'];
                    }
                    ?>
                </td>
            </tr>
            <?php
            if (in_array($ct, $cabeca)) {
                ?>
            </table>
            <div style="page-break-after: always"></div>
            <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
                <?php
                topTablela($disciplinas);
            }
            $ct++;
        }
        ?>
    </table>
    <div style="page-break-after: always"></div>
    <div class="titulo">
        Resumo: Ata dos Resultados de Avaliação
    </div>
    <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
        <tr>
            <?php
            foreach ($disciplinas as $d) {
                if ($d['nucleo_comum'] == 1) {
                    $temNucleoComum = 1;
                    ?>
                    <td colspan="2" style="text-align: center; width: 100px">
                        <?= $d['n_disc'] ?>
                    </td>
                    <?php
                }
            }
            foreach ($disciplinas as $d) {
                if ($d['nucleo_comum'] == 0) {
                    ?>
                    <td colspan="2" style="text-align: center; width: 100px">
                        <?= $d['n_disc'] ?>
                    </td>
                    <?php
                }
            }
            if (!empty($temNucleoComum)) {
                ?>
                <td rowspan="2">
                    Total Geral de faltas (Horas Aula)
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <?php
            foreach (range(1, count($disciplinas)) as $v) {
                ?>
                <td style="text-align: center">
                    >=5 
                </td>
                <td style="text-align: center; color: red">
                    <5 
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <?php
            foreach ($disciplinas as $id_disc => $d) {
                ?>
                <td style="text-align: center; color: blue">
                    <?= @$resumo[$id_disc]['maiorIgual_5'] ?>
                </td>
                <td style="text-align: center; color: red">
                    <?= @$resumo[$id_disc]['menor_5'] ?>
                </td>
                <?php
            }
            if (!empty($temNucleoComum)) {
                ?>
                <td rowspan="2">
                    <?= @$resumo['faltaAula'] ?>
                </td>
                <?php
            }
            ?>
        </tr>
    </table>
    <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
        <tr>
            <?php
            foreach ($situacaoMostra as $v) {
                ?>
                <td>
                    <?= $situacao[$v] ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <?php
            foreach ($situacaoMostra as $v) {
                ?>
                <td>
                    <?= intval(@$situaçãoQt[$v]) ?>
                </td>
                <?php
            }
            ?>
        </tr>
    </table>
    <div style="width: 100%" class="titulo">
        Situação Final
    </div>
    <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
        <tr>
            <td style="width:12.5%" class="topo2">
                Frequente
            </td>
            <?php
            unset($sitFinal[0]);
            foreach ($sitFinal as $id_sf => $n_sf) {
                ?>
                <td style="width:12.5%" class="topo2">
                    <?= $n_sf ?>
                </td>
                <?php
            }
            ?>
        <tr>
        <tr>
            <td style="width:12.5%" class="topo2">
                <?= intval(@$frequente[0]) ?>
            </td>
            <?php
            foreach ($sitFinal as $id_sf => $n_sf) {
                ?>
                <td style="width:12.5%" class="topo4">
                    <?= intval(@$id_sfs[$id_sf]) ?>
                </td>
                <?php
            }
            ?>
        <tr>
    </table>
    <?php
    if ($ementa) {
        ?>
        <br />
        <div class="titulo">
            Ementas
        </div>
        <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633">
            <?php
            foreach ($ementa as $v) {
                ?>
                <tr>
                    <td style=" text-align: justify;">
                        <?= $v['n_ementa'] ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }
    ?>
    <div style="page-break-after: always"></div>

    <div class="titulo">
        Assinaturas
    </div>
    <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
        <tr>
            <td style="width: 100px" class="topo2">
                Matrícula
            </td>
            <td style="width: 40%" class="topo2">
                Nome
            </td>
            <td style="width: 20%" class="topo2">
                Disciplina
            </td>
            <td style="width: 40%" class="topo2">Assinatura
            </td>
        </tr>
        <?php
        if (!empty($temNucleoComum)) {
            ?>
            <tr>
                <td style="width: 5%" class="topo3">
                    <?= @$prof['nc']['rm'] ?>
                </td>
                <td class="topo3" style="width: 40%; text-align:left" >
                    <?= @$prof['nc']['nome'] ?>
                </td>
                <td style="width: 20%" class="topo3">
                    Núcleo Comum
                </td>
                <td style="width: 40%" class="topo3">

                </td>
            </tr>
            <?php
        }

        foreach ($disciplinas as $id_disc => $d) {
            if ($d['nucleo_comum'] == 0) {
                ?>
                <tr>
                    <td style="width: 5%" class="topo3">
                        <?= @$prof[$id_disc]['rm'] ?>
                    </td>
                    <td class="topo3" style="width: 40%; text-align:left" >
                        <?= @$prof[$id_disc]['nome'] ?>
                    </td>
                    <td style="width: 20%" class="topo3">
                        <?= $d['n_disc'] ?>
                    </td>
                    <td style="width: 40%" class="topo3">

                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
    <?php
} else {
    echo 'Algo errado não está certo ;(';
    exit();
}

function topTablela($disciplinas) {
    ?>
    <tr>
        <td rowspan="2">
            Nº
        </td>
        <td rowspan="2">
            RSE
        </td>
        <td rowspan="2">
            Nome do Aluno
        </td>
        <?php
        foreach ($disciplinas as $v) {
            if ($v['nucleo_comum'] == 1) {
                ?>
                <td>
                    <?= $v['n_disc'] ?>
                </td>
                <?php
            }
        }
        foreach ($disciplinas as $v) {
            if ($v['nucleo_comum'] == 0) {
                ?>
                <td colspan="2">
                    <?= $v['n_disc'] ?>
                </td>
                <?php
            }
        }
        ?>
        <td rowspan="2">
            Total de Faltas
        </td>
        <td rowspan="2">
            Situação
        </td>
    </tr>
    <tr>
        <?php
        foreach ($disciplinas as $v) {
            if ($v['nucleo_comum'] == 1) {
                ?>
                <td style="text-align: center">
                    N
                </td>
                <?php
            }
        }
        foreach ($disciplinas as $v) {
            if ($v['nucleo_comum'] == 0) {
                ?>
                <td>
                    N
                </td>
                <td>
                    F
                </td>
                <?php
            }
        }
        ?> 
    </tr>
    <?php
}

$pdf->exec();
