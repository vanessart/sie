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
$bim = filter_input(INPUT_POST, 'bim', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$ementa = sql::get('avalia_ementa', 'n_ementa', ['fk_id_turma' => $id_turma]);
if ($bim && $id_turma) {
    $situacao = sql::idNome('ge_turma_aluno_situacao');

    $situacaoMostra = [0, 9, 1, 5, 2, 8, 4];
    $model->boletimBim($id_turma, $bim);
    $turma = $model->_turma;
    $disciplinas = $model->_disciplinas; // a chave é o id_disc
    $alunos = $model->_alunos;
    $notaFalta = $model->_notaFalta; // a chave é o id_pessoa

    $n_pl = sql::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $turma['id_pl']], 'fetch')['n_pl'];
    @$prof = gtTurmas::professores($id_turma)[1]; // a chave é o id_disc
    $titulo = "<div style=\"Width: 100%\" class=\"titulo\">"
            . "ATA - Período Letivo: " . $n_pl . " - " . $bim . 'º ' . $turma['un_letiva']
            . "</div>"
            . "<div class=\"topo\">"
            . $turma['n_curso'] . ' - ' . $turma['n_turma'] . ' - Período: ' . $model->periodoDia($turma['periodo'])
            . "</div>";
    $esc = new escola($turma['id_inst']);
    $pdf->headerAlt = $esc->cabecalho() . $titulo
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
                $cabeca[] = intval(($contAluno / 3)*2);
            }
        }
        $ct = 1;
        foreach ($alunos as $v) {
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
                            <?= $nota ?>
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
                    $faltaTotal = $v['id_tas'] != 0 ? null : @$notaFalta[$v['id_pessoa']]['falta_total'];
                    echo $faltaTotal;
                    ?>
                </td>
                <td>
                    <?= $v['n_tas'] ?>
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
    <div class="quebra"></div>
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
                    <td colspan="2" style="text-align: center">
                        <?= $d['n_disc'] ?>
                    </td>
                    <?php
                }
            }
            foreach ($disciplinas as $d) {
                if ($d['nucleo_comum'] == 0) {
                    ?>
                    <td colspan="2" style="text-align: center">
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
                    <?= @$notaFalta['resumo'][$id_disc]['maiorIgual_5'] ?>
                </td>
                <td style="text-align: center; color: red">
                    <?= @$notaFalta['resumo'][$id_disc]['menor_5'] ?>
                </td>
                <?php
            }
            if (!empty($temNucleoComum)) {
                ?>
                <td rowspan="2">
                    <?= @$notaFalta['resumo']['faltaAula'] ?>
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
    <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
        <tr>
            <td colspan="<?= count($disciplinas) ?>" style="text-align: center">
                Quantidade de notas 0 (zero) e ou notas não digitadas
            </td>
        </tr>
        <tr>
            <?php
            foreach ($disciplinas as $id_disc => $d) {
                ?>
                <td style="text-align: center">
                    <?= $d['n_disc'] ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <?php
            foreach ($disciplinas as $id_disc => $d) {
                ?>
                <td style="text-align: center">
                    <?= @$notaFalta['resumo'][$id_disc]['zero'] ?>
                </td>
                <?php
            }
            ?>
        </tr>
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
    <br />
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
