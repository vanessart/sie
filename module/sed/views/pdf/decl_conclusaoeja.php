<?php
ob_start();
$escola = new escola();
?>
<head>
    <style>
        .topo{
            font-size: 8pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            padding: 5px;
        }
    </style> 
</head>

<?php
foreach ($_POST['sel'] as $v) {
    if (!empty($v)) {
        $idalunos[] = $v;
    }
}

$idalunos = implode(",", $idalunos);

$wsql = "Select p.id_pessoa, p.n_pessoa, ta.fk_id_turma, ta.chamada, ta.situacao,"
        . " t.codigo as codigo_classe,"
        . " p.ra, p.ra_dig, p.rg, rg_oe, p.rg_uf, t.periodo_letivo, p.sexo"
        . " From pessoa p"
        . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa"
        . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
        . " Where p.id_pessoa in (" . $idalunos . ") "
        . " And ta.situacao_final IN(1,2,7) "
        . " And t.fk_id_ciclo = '" . '30' . "'"
        . " Order By ta.chamada";

$query = $model->db->query($wsql);
$lp = $query->fetchAll();

if (!empty($lp)) {
    foreach ($lp as $v) {
        $cla[$v['fk_id_turma']][] = $v;
    }
    ?>

    <?php
    foreach ($cla as $kw => $w) {
        foreach ($w as $v) {
            $per = explode('|', completadadossala($kw));
            if (!empty($proximaFolha)) {
                ?>
                <div style="page-break-after: always"></div>
                <?php
            } else {
                $proximaFolha = 1;
            }
            ?>

            <div style="font-weight:bold; font-size:10pt; text-align: center">
                <br /><br /><br /><br /><br />         
                <b>DECLARAÇÃO DE CONCLUSÃO</b>
            </div>
            <br /><br /><br /><br /><br /><br /><br />
            <p style="text-align: justify">A direção desta escola declara, que <b><?php echo $v['n_pessoa'] ?> </b>
                RSE: nº <?php echo addslashes($v['id_pessoa']) ?> RG: nº
                <?php echo $v['rg'] ?>-<?php echo $v['rg_oe'] ?>-<?php echo $v['rg_uf'] ?>,
                RA: nº <?php echo $v['ra'] ?>-<?php echo $v['ra_dig'] ?>, concluiu o Termo IV do Ensino Fundamental EJA – Educação de Jovens e Adultos no Ano
                Letivo de <?php echo substr($v['periodo_letivo'], 9, 4) ?>(<?php echo substr($v['periodo_letivo'], 0, 7) ?>estre), estando 
                <?php echo ($v['sexo'] == "M") ? 'apto' : 'apta' ?> ao prosseguimento dos estudos na 1ª Série do Ensino Médio.
            </p>
            <p>Por ser verdade, firmamos o presente.</p>
            <?php
            ?>
            <br /><br /><br /><br /><br />
            <div style="text-align: right"><?= CLI_CIDADE ?>, <?php echo date('d') . ' ' . data::mes(date('m')) . ' ' . date('Y') . '.' ?></div>
            <br /><br /><br /><br />
            <div style="text-align: center">_____________________________________</div>
            <div style="text-align:center">Carimbo e Assinatura</div>
            <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />

            <div style="font-weight:bold; font-size:7pt; text-align: center; border: 1px solid">
                Obs. Este documento não apresenta emendas e nem rasuras, e somente é válido com os carimbos da escola e do responsável.
            </div>
            <?php
        }
    }
} else {
    ?>
    <div style="font-weight:bold; font-size:8pt; text-align: center; border: 1px solid">
        Não existem dados a serem impressos
    </div>
    <?php
}
tool::pdfSemRodape();
function completadadossala($idturma) {

        $cab = sql::get(['ge_turmas', 'ge_ciclos'], '*', ['id_turma' => $idturma]);

        foreach ($cab as $b) {

            @$prof = $b['professor'];
            @$cod = $b['codigo'];
            @$periodo = $b['periodo_letivo'];
            @$desc = $b['n_turma'];
            @$dtr = $b['n_ciclo'];

            switch ($b['periodo']) {
                case "M":
                    $p = "Manhã";
                    $pp = "Matutino";
                    break;
                case "T":
                    $p = "Tarde";
                    $pp = "Vespertino";
                    break;
                case "I":
                    $p = "Integral";
                    $pp = "Integral";
                    break;
                case "G":
                    $p = "Geral";
                    $pp = "Integral";
                    break;
                case "N":
                    $p = "Noite";
                    $pp = "Noturno";
                    break;
            }

            switch (substr($b['codigo'], 0, 2)) {
                case "EB":
                case "EM":
                case "EI":
                    $ed = " da Educação Infantil";
                    break;
                case "EF":
                    $ed = " do Ensino Fundamental";
                    break;
                case "EJ":
                    $ed = " Eja do Ensino Fundamental";
                    break;
                case "AE":
                    $ed = " Ensino Fundamental";
                    break;
            }
        }

        $ppp = $cod . '|' . $periodo . '|' . $p . '|' . @$prof . '|' . @$pp . '|' . @$desc . '|' . $ed . '|' . $dtr;

        return $ppp;
    }