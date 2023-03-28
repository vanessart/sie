<?php
ob_clean();
ob_start();
try {


    $pdf = new pdf();
    $pdf->mgl = 15;
    $pdf->mgr = 15;
    $pdf->mgt = 1;
    $pdf->mgb = 15;
    $pdf->mgh = 2;
    $pdf->mgf = 6;
    $background_color = '#83295C';
    $code = HOME_URI . '/includes/images/diario/boletim.jpg';
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
            background-color: <?= $background_color ?>;
            color:#ffffff;
            text-align: center;
        }
        .quebra{
            page-break-after: always;
        }
        table{
            font-size: 6px;
        }
        .borda{
            border:  <?= $background_color ?> solid 1p;
        }
    </style>
    <?php
    $sitFinal = sql::idNome('ge_situacao_final');
    $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
    if ($id_turma && toolErp::id_pessoa()) {
        $escola = new escola();
        $ementa = sql::get('avalia_ementa', 'n_ementa', ['fk_id_turma' => $id_turma]);
        $model->ataFinal($id_turma);
        $notaFaltaFinal = $model->_notaFaltaFinal;
        $notaFalta = $model->_notaFalta;
        $alunos = $model->_alunos;
        $turma = $model->_turma;
        if (empty($notaFaltaFinal) && $turma["atual_letiva"] == 4) {
            ?>
            <link href="<?php echo HOME_URI; ?>/includes/css/bootstrap5.min.css" rel="stylesheet">
            <br /><br /><br />
            <div class="aler alert-danger" style="font-weight: bold; font-size: 1.4em; text-align: center">
                Realise o conselho antes de abrir a ATA.
            </div>
            <?php
            die();
        }

        $perDia = ng_main::periodoDoDia($turma['periodo']);
        $situacao = sql::idNome('ge_turma_aluno_situacao');
        $situacaoMostra = [0, 9, 1, 5, 2, 8, 4];
        @$prof = gtTurmas::professores($id_turma)[1]; // a chave é o id_disc

        $disciplinas = $model->_disciplinas; // a chave é o id_disc
        $temNC = 0;
        foreach ($disciplinas as $v) {
            @$colBase[$v['fk_id_adb']]++;
            $temNC += $v['nucleo_comum'];
        }
        $aulasDadasNc = $model->aulasDadasNc();
        $n_pl = sql::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $turma['id_pl']], 'fetch')['n_pl'];

        $titulo = "<div style=\"Width: 100%\" class=\"titulo\">"
                . "ATA FINAL - Período Letivo: " . $n_pl
                . "</div>"
                . "<div class=\"topo\">"
                . $turma['n_turma'] . ' - Período: ' . $n_pl
                . "</div>";
        $pdf->headerSet = null;
        $pdf->headerAlt = $escola->cabecalho() . $titulo;
        $corta = 1;
        foreach ($alunos as $v) {
            if (in_array($v['id_pessoa'], $model->_apd)) {
                continue;
            }
            if (in_array($v['id_tas'], [0, 1])) {
                $faltaDiasNc = 0;
                $faltaDisc = 0;
                $id_pessoa = $v['id_pessoa'];
                ?>
                <table style="width: 100%; border:  <?= $background_color ?> solid 1px">
                    <tr>
                        <td>
                            <table style="width: 100%;">
                                <tr>
                                    <td rowspan="2" style="width: 80px;">
                                        <img style="width: 80px" src="<?= HOME_URI ?>/includes/images/brasao.jpg" alt="Foto"/>
                                    </td>
                                    <td style="padding-left: 20px">
                                        <div style="font-weight: bold; font-size: 15px">
                                            <?= CLI_NOME ?>
                                            <br />
                                            Secretaria de Educação
                                            <br />
                                            <?= $escola->_nome ?>
                                        </div>
                                        <div style="text-align: center; font-size: 11px">
                                            <?= $escola->_email ?>
                                        </div>
                                    </td>
                                    <td style="width: 160px; padding-right: 20px">
                                        <img style="width: 160px" src="<?= HOME_URI ?>/includes/images/logo_relatorio.jpg" alt="Foto"/>
                                    </td>
                                    <td rowspan="2" style="width: 80px">
                                        <img style="height: 110px" src="<?= toolErp::fotoEndereco($id_pessoa) ?>" alt="Foto"/>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" vAlign="bottom">
                                        <?= str_replace('<br />', '&nbsp;&nbsp;&nbsp;&nbsp;', $escola->enderecoEstruturado(1)) ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: <?= $background_color ?>">
                            <div class="titulo">
                                Boletim d<?= toolErp::sexoArt($v['sexo']) ?> alun<?= toolErp::sexoArt($v['sexo']) ?> <?= $v['n_pessoa'] ?> 
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="width: 100%; font-weight: bold">
                                <tr>
                                    <td>
                                        <?= $turma['n_turma'] ?>
                                    </td>
                                    <td>
                                        Período Letivo: <?= $n_pl ?>
                                    </td>
                                    <td>
                                        RSE: <?= $id_pessoa ?>
                                    </td>
                                    <td>
                                        RA: <?= $v['ra'] ?> - <?= $v['ra_uf'] ?>
                                    </td>
                                    <td>
                                        Chamada: <?= $v['chamada'] ?>
                                    </td>
                                    <td>
                                        Período: <?= $perDia ?> 
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="width: 100%;" >
                                <tr>
                                    <td rowspan="2" colspan="2" class="borda">
                                        Disciplinas
                                    </td>
                                    <?php
                                    foreach (range(1, $turma['qt_letiva']) as $bim) {
                                        ?>
                                        <td colspan="2" class="borda">
                                            <?= $bim ?>º <?= $turma['un_letiva'] ?>
                                        </td>
                                        <?php
                                    }
                                    ?>
                                    <td rowspan="2" class="borda">
                                        Nota Conselho
                                    </td>
                                    <td rowspan="2" class="borda">
                                        Média Final
                                    </td>
                                    <td class="borda" rowspan="<?= array_sum($colBase) + 2 + $temNC ?>" style="text-align: center">
                                        <img style="width: 90px;" src="<?= $code ?>" alt="Foto"/>
                                    </td>
                                </tr>
                                <tr>
                                    <?php
                                    foreach (range(1, $turma['qt_letiva']) as $bim) {
                                        ?>
                                        <td class="borda">
                                            Notas
                                        </td>
                                        <td class="borda">
                                            Faltas
                                        </td>
                                        <?php
                                    }
                                    ?> 
                                </tr>
                                <?php
                                $baseTex = [
                                    1 => 'bncc',
                                    2 => 'bd'
                                ];
                                foreach (range(1, 2)as $base) {
                                    if (empty($colBase[$base])) {
                                        continue;
                                    }
                                    $passou = null;
                                    foreach ($disciplinas as $id_disc => $d) {
                                        if ($d['fk_id_adb'] == $base) {
                                            ?>
                                            <tr>
                                                <?php
                                                if (empty($passou)) {
                                                    ?>
                                                    <td class="borda" rowspan="<?= $colBase[$base] ?>"  style="background-color: black; width: 20px">
                                                        <img style="width: 15px" src="<?= HOME_URI ?>/includes/images/diario/<?= $baseTex[$base] ?>.png" alt="BNCC"/>
                                                    </td>
                                                    <?php
                                                    $passou = 1;
                                                }
                                                ?>
                                                <td class="borda" style="text-align: left">
                                                    <?= $d['n_disc'] ?>
                                                </td>
                                                <?php
                                                foreach (range(1, $turma['qt_letiva']) as $bim) {
                                                    $falta = @$notaFalta[$id_pessoa][$bim]['falta_' . $id_disc];
                                                    $faltaDisc += $falta;
                                                    ?>
                                                    <td class="borda">
                                                        <?= @$notaFalta[$id_pessoa][$bim]['media_' . $id_disc] ?>
                                                    </td>
                                                    <td class="borda">
                                                        <?php
                                                        if (empty($d['nucleo_comum'])) {
                                                            echo intval($falta);
                                                        } else {
                                                            echo 'FD*';
                                                        }
                                                        ?>
                                                    </td>
                                                    <?php
                                                }
                                                ?> 
                                                <td class="borda">
                                                    <?= @$notaFaltaFinal[$id_pessoa]['cons_' . $id_disc] ?>
                                                </td>
                                                <td class="borda">
                                                    <?php
                                                    if (empty($notaFaltaFinal[$id_pessoa]['cons_' . $id_disc])) {
                                                        echo @$notaFaltaFinal[$id_pessoa]['media_' . $id_disc];
                                                    } else {
                                                        echo $notaFaltaFinal[$id_pessoa]['cons_' . $id_disc];
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                }
                                if (!empty($temNC)) {
                                    ?>
                                    <tr>
                                        <td class="borda" colspan="2">
                                            *Faltas Dia (FD)
                                        </td>
                                        <?php
                                        foreach (range(1, $turma['qt_letiva']) as $bim) {
                                            $faltanc = @$notaFalta[$id_pessoa][$bim]['falta_nc'];
                                            $faltaDiasNc += $faltanc;
                                            ?>
                                            <td class="borda" colspan="2">
                                                <?= intval($faltanc) ?>
                                            </td>
                                            <?php
                                        }
                                        ?> 
                                        <td class="borda" colspan="2">

                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>

                            </table>
                            <table style="width: 100%" >
                                <tr>
                                    <td style="text-align: left">
                                        Situação: <?= $v['n_tas'] ?>
                                    </td>
                                    <?php
                                    if (!empty($v['id_sf'])) {
                                        ?>
                                        <td style="text-align: left">
                                            Resultado Final: <?= $v['n_sf'] ?>
                                        </td>
                                        <?php
                                    }
                                    if ($temNC) {
                                        ?>
                                        <td style="text-align: left">
                                            Faltas Dia: <?= $faltaDiasNc ?>
                                        </td>
                                        <?php
                                    }
                                    ?>
                                    <td style="text-align: left">
                                        Percentual de Faltas: 
                                        <?php
                                        if ($temNC) {
                                            echo str_replace('.', ',', round((($faltaDiasNc / $aulasDadasNc) * 100), 1)) . '%';
                                        } else {
                                            echo str_replace('.', ',', round(((($faltaDisc / 5) / $aulasDadasNc) * 100), 1)) . '%';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <?php
                if ($corta % 2 == 1) {
                    ?>
                    <div style="text-align: center; padding: 30px">
                        ----------------------------------------------------------------------------------------------------------------------------------------
                    </div>
                    <?php
                } else {
                    ?>
                    <div style="page-break-after: always"></div>
                    <?php
                }
                $corta++;
            }
        }
    } else {
        echo 'Algo errado não está certo ;(';
        exit();
    }

    $pdf->exec();
} catch (Exception $exc) {
    echo $exc->getTraceAsString();
    echo $exc->getMessage();
}