<?php
ob_start();
$escola = new escola();

foreach ($_POST['sel'] as $v) {
    if (!empty($v)) {
        $idalunos[] = $v;
    }
}

$idalunos = implode(",", $idalunos);

$wsql = "SELECT p.id_pessoa, p.n_pessoa, ta.fk_id_turma, ta.chamada, ta.situacao,"
        . " t.codigo as codigo_classe, p.ra, p.rg, t.periodo_letivo, p.sexo"
        . " FROM pessoa p"
        . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa"
        . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma"
        . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
        . " WHERE p.id_pessoa in (" . $idalunos . ") AND pl.at_pl = '1'"
        . " ORDER BY id_turma_aluno desc, ta.chamada ";

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
        ?>
        <br /><br /><br /><br /><br />

        <div style="font-weight:bold; font-size:12pt; text-align: center">
            DECLARAÇÃO DE TRANSFERÊNCIA
        </div>

        <br /><br /><br /><br /><br />

        <div style="text-align:justify"> A direção desta escola declara, para devidos fins, que <b><?php echo addslashes($v['n_pessoa']) ?></b>
            , RG nº. <?php echo $v['rg'] ?>, RA nº. <?php echo $v['ra'] ?>, RSE nº. <?php echo $v['id_pessoa'] ?>
            , solicitou sua transferência nesta data, tendo direito a matricular-se no <?php echo $per[7] . $per[6] ?>
            , sendo que seus documentos serão expedidos no prazo máximo de 15 dias, a contar desta data.
        </div>
        <br /><br />
        <div>Por ser verdade, firmamos o presente.</div>
        <br /><br /><br /><br />
        <div style="text-align: right"><?= CLI_CIDADE ?>, <?php echo date('d') . ' ' . data::mes(date('m')) . ' ' . date('Y') . '.' ?></div>
        <br /><br /><br /><br /><br /><br /><br />
        <div style="text-align: center">_____________________________________</div>
        <div style="text-align:center">Carimbo e Assinatura</div>
        <br /><br /><br /><br /><br /><br /><br /><br />

        <div style="font-weight:bold; font-size:8pt; text-align: center; border: 1px solid">
            Obs. Este documento não apresenta emendas e nem rasuras, e somente é válido com os carimbos da escola e do responsável.
        </div>
        
        <?php
    }
}
?>
<br />
<?php
tool::pdfSemRodape();
?>