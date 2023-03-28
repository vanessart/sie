<?php
ob_start();
$escola = new escola();
$i = 1;

@$evento = @$_POST['id_eve'];
@$tur = @$_POST['id_turma'];
@$aba = @$_POST['tabClass'];
$cic = $model->pegadescricaociclo();

if (@$aba == 1) {

    $aluno = "SELECT DISTINCT ea.id_ea, p.id_pessoa, p.n_pessoa, p.sexo, e.evento, e.dt_evento,"
            . " e.h_inicio, e.h_final, e.local_evento, e.ev_resp, ta.codigo_classe,"
            . " ta.fk_id_turma, t.fk_id_ciclo FROM ge_evento_aluno ea"
            . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = ea.fk_id_pessoa"
            . " JOIN pessoa p on p.id_pessoa = ea.fk_id_pessoa"
            . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
            . " JOIN ge_eventos e on e.id_evento = ea.fk_id_evento"
            . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
            . " WHERE ea.fk_id_evento = '" . $evento . "' AND ta.situacao = '" . 'Frequente' . "'"
            . " AND pl.at_pl = 1 ORDER BY p.n_pessoa";

    $query = $model->db->query($aluno);
    $cla2 = $query->fetchAll();

    foreach ($cla2 as $v) {
        $cla[$v['fk_id_turma']][] = $v;
    }
} else {
    foreach ($_POST['sel'] as $v) {
        if (!empty($v)) {
            $idalunos[] = $v;
        }
    }

    $idalunos = implode(",", $idalunos);

    $wsql = "Select Distinct p.id_pessoa, p.n_pessoa, ta.fk_id_turma, ta.chamada, ta.situacao, t.codigo as codigo_classe,"
            . " e.evento, e.dt_evento, e.h_inicio, e.h_final, e.local_evento, e.ev_resp, p.sexo "
            . " From pessoa p "
            . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa "
            . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
            . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
            . " JOIN ge_eventos e on e.fk_id_inst = t.fk_id_inst"
            . " Where p.id_pessoa in (" . $idalunos . ") and e.id_evento = '"
            . $_POST['id_eve'] . "' AND pl.at_pl = 1"
            . " order by ta.chamada";

    $query = $model->db->query($wsql);
    $lp = $query->fetchAll();

    foreach ($lp as $v) {
        $cla[$v['fk_id_turma']][] = $v;
    }
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
        .quebra{
            page-break-after: always;
        }
    </style> 
</head>

<?php
foreach ($cla as $kw => $w) {
    foreach ($w as $v) {
        echo $escola->cabecalho();
        ?>
        <div style="font-weight:bold; font-size:12pt; text-align: center">
            CONVOCAÇÃO
        </div>
        <div>Sr.(s) Pai(s) e ou Responsável Legal</div>

        <div>Alun<?php echo tool::sexoArt($v['sexo']) ?>: <?php echo addslashes($v['n_pessoa']) ?> </div>
        <div>RSE: <?php echo $v['id_pessoa'] ?> Classe: <?php echo $v['codigo_classe'] ?></div>
        <br />
        <p style="text-align: justify">A <?php echo user::session('n_inst') ?>, vem através do presente solicitar a presença dos pais ou responsável legal pel<?php echo tool::sexoArt($v['sexo']) ?> alun<?php echo tool::sexoArt($v['sexo']) ?> <b><?php echo addslashes($v['n_pessoa']) ?></b> - <?php echo $cic[$v['fk_id_ciclo']] . ' ' . substr($v['codigo_classe'], 4, 1) ?>, nesta unidade escolar para tratar de assuntos referentes:</p>
        <br />
        <div>Assunto: <?php echo $v['evento'] ?></div>
        <div>Falar com: <?php echo $v['ev_resp'] ?></div>
        <div>Dia e Hora: <?php echo data::converteBr($v['dt_evento']) . ' - ' . substr($v['h_inicio'], 0, 5) . ' até ' . substr($v['h_final'], 0, 5) ?> horas </div>
        <?php ?>
        <br />
        <div style="text-align: right"><?= CLI_CIDADE ?>, <?php echo date('d') . ' ' . data::mes(date('m')) . ' ' . date('Y') . '.' ?></div>
        <div style="text-align: center" >Atenciosamente</div>
        <br />
        <div style="text-align: center">_____________________________________</div>
        <div style="text-align: center">Direção</div>

        <?php
        if ($i % 2 == 0) {
            ?>

            <div class="quebra"></div>
            <?php
        } else {
            ?>
            <div style="text-align: center"><?php echo "----------------------------------------------------------------------------------------------------------------------------------------------"; ?></div>
            <?php
        }
        $i++;
    }
}
?>
<br />
<?php
tool::pdfSimpleSemRodape();
?>