<?php
ob_start();
$escola = new escola(@$_POST['id_inst']);
$cor = '#F5F5F5';
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

        table {
            width: 100%;
        }

    </style>
</head>

<?php
if (!empty($_POST['aloca'])) {
    $idTumas[] = $_POST['turma'];
} else {
    foreach ($_POST['sel'] as $v) {
        if (!empty($v)) {
            $idTumas[] = $v;
        }
    }
}

$idTumas = implode(",", $idTumas);

$wsql = "Select p.id_pessoa, p.n_pessoa, p.dt_nasc, p.ra, p.ra_dig, p.tel1, fk_id_turma, chamada, dt_matricula, ge_turma_aluno.situacao, "
        . " dt_transferencia, codigo_classe, ge_turmas.periodo_letivo, ge_turmas.periodo, dt_matricula_fim "
        . " From ge_turma_aluno "
        . " JOIN pessoa p on ge_turma_aluno.fk_id_pessoa = p.id_pessoa "
        . " join ge_turmas on ge_turmas.id_turma = ge_turma_aluno.fk_id_turma "
        . " Where ge_turma_aluno.fk_id_turma  in (" . $idTumas . ") "
        . "order by chamada";

$query = $model->db->query($wsql);
$listapiloto = $query->fetchAll();
$peri = ['M' => 'Manhã', 'T' => 'Tarde', 'G' => 'Geral', 'N' => 'Noite'];

$s = $model->pegasituacaosedlayout();
$sit = $model->pegasituacaosedlayout();
$telefone = $model->pegatelefone($idTumas);

foreach ($listapiloto as $v) {
    $cla[$v['fk_id_turma']][] = $v;
    $periodo_letivo[$v['fk_id_turma']] = $v['periodo_letivo'];
    @$periodo[$v['fk_id_turma']] = $peri[$v['periodo']];
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
        Lista Piloto
    </div>

    <div style = "border-width: 0.5px; border-style: solid; font-weight:bold; font-size:8pt; text-align: left">
        <?php echo $per[0] . ' - Ano Letivo: ' . $per[1] . ' - Período: ' . $periodo[$kw] . ' - Prof. ' . $prof; ?>
    </div>

    <table class="table tabs-stacked table-bordered">;

        <tr>
            <td >
                nº Ch
            </td>
            <td >
                RSE
            </td>
            <td>
                RA
            </td>
            <td>
                Nome do Aluno
            </td>
            <td>
                Data Nasc.
            </td>
            <td>
                Data Matrícula
            </td>
            <td>
                Telefone
            </td>
            <td>
                Situação
            </td>
            <td>
                Data Transf.
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
                <td style="text-align: left; font-size: 6pt; background-color: <?php echo $cor; ?>">
                    <?php echo addslashes($v['n_pessoa']) ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo data::converteBr($v['dt_nasc']) ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo data::converteBr($v['dt_matricula']) ?>
                </td>
                <td style="font-size: 6pt;background-color: <?php echo $cor ?>">
                    <?php echo $telefone[$v['id_pessoa']][1] . (($telefone[$v['id_pessoa']][2] == NULL) ? '':" - " . $telefone[$v['id_pessoa']][2])  ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php echo $v['situacao'] ?>
                </td>
                <td style="background-color: <?php echo $cor ?>">
                    <?php
                    if ($v['situacao'] == 'Frequente') {
                        echo ' ';
                    } else {
                       //  echo $v['dt_matricula_fim'] != '0000-00-00' ? data::converteBr($v['dt_matricula_fim']) : '';
                       echo $v['dt_transferencia'] != '0000-00-00' ? data::converteBr($v['dt_transferencia']) : '';
                    }
                    $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5';
                    ?>

                </td>
            </tr>
            <?php
        }
        ?>

    </table>

    <div style="font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid">
        Resumo Situação
        <table style="font-size: 8pt; font-weight: bolder; border-width: 0.5px; border-style: solid">
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
                    Outras Situações
                </td>
                <td style="color: red; font-weight: bolder">
                    Total Alunos Lista
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo $con[$sit[2]] ?>
                </td>
                <td>
                    <?php echo $con[$sit[6]] ?>
                </td>
                <td>
                    <?php echo $con[$sit[3]] ?>
                </td>
                <td>
                    <?php echo $con[$sit[1]] ?>
                </td>
                <td>
                    <?php echo $con[$sit[4]] ?>
                </td>
                <td>
                    <?php echo $con[$sit[8]] ?>
                </td>
                <td>
                    <?php echo $con[$sit[5]] ?>
                </td>
                <td>
                    <?php echo $con[$sit[7]] ?>
                </td>
                <td>
                    <?php echo ' - ' ?>
                </td>
                <td>
                    <?php
                    $t = $con[$sit[1]] + $con[$sit[2]] + $con[$sit[3]] + $con[$sit[4]] + $con[$sit[5]] + $con[$sit[6]] + $con[$sit[7]] + $con[$sit[8]];
                    echo $t;
                    ?>
                </td>
            </tr>
        </table>
    </div>

    <?php
}
tool::pdfescola('P', @$_POST['id_inst']);
?>
