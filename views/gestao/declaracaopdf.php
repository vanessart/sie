<?php
ob_start();
$escola = new escola();

foreach ($_POST['sel'] as $v) {
    if (!empty($v)) {
        $idalunos[] = $v;
    }
}

$idalunos = implode(",", $idalunos);

$wsql = "Select p.id_pessoa, p.n_pessoa, ta.fk_id_turma, ta.chamada, ta.situacao, t.codigo as codigo_classe, p.ra, p.rg, t.periodo_letivo, p.sexo"
        . " From pessoa p"
        . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa"
        . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
        . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
        . " Where p.id_pessoa in (" . $idalunos . ") "
        . " and ta.situacao = 'Frequente' "
        . " and pl.at_pl = 1 "
        . " order by ta.chamada";

$query = $model->db->query($wsql);
$lp = $query->fetchAll();

foreach ($lp as $v) {
    $cla[$v['fk_id_turma']][] = $v;
}
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
foreach ($cla as $kw => $w) {
    foreach ($w as $v) {
        $per = explode('|', $model->completadadossala($kw));
        if (!empty($proximaFolha)) {
            ?>
            <div style="page-break-after: always"></div>
            <?php
        } else {
            $proximaFolha = 1;
        }
        //    echo $escola->cabecalho();
        ?>
        <br /><br /><br />
        <div style="font-weight:bold; font-size:10pt; text-align: center">
            <b>DECLARAÇÃO DE ESCOLARIDADE</b>
        </div>
        <br /><br /><br /><br /><br />
        <p style="text-align: justify">A direção desta escola declara, para fins de comprovação de escolaridade, que <b><?php echo $v['n_pessoa'] ?> </b>RSE: nº <?php echo addslashes($v['id_pessoa']) ?> RG: nº
            <?php echo $v['rg'] ?>, RA: nº <?php echo $v['ra'] ?> é alun<?php echo tool::sexoArt($v['sexo']) ?> regularmente matriculad<?php echo tool::sexoArt($v['sexo']) ?> no <?php echo $per[5] . $per[6] ?> no Ano Letivo de <?php echo $v['periodo_letivo'] ?>, nesta Unidade Escolar, frequentando as aulas de segunda a sexta-feira, no período <?php echo $per[4] ?>.</p>
        <p>Por ser verdade, firmamos o presente.</p>
        <?php
        ?>
        <br /><br /><br /><br /><br />
        <div style="text-align: right">Barueri, <?php echo date('d') . ' ' . data::mes(date('m')) . ' ' . date('Y') . '.' ?></div>
        <br /><br /><br /><br />
        <div style="text-align: center">_____________________________________</div>
        <div style="text-align:center">Carimbo e Assinatura</div>
        <br /><br /><br /><br /><br /><br /><br /><br /><br />

        <div style="font-weight:bold; font-size:8pt; text-align: center; border: 1px solid">
            Obs. Este documento não apresenta emendas e nem rasuras, e somente é válido com os carimbos da escola e do responsável.
        </div>
        
        <?php
    }
}
tool::pdfSemRodape();
?>