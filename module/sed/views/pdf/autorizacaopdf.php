<?php
ob_start();
$escola = new escola();

$es = user::session('n_inst');
$i = 1;
$id_evento = filter_input(INPUT_POST, 'id_evento', FILTER_SANITIZE_NUMBER_INT);
@$tur = @$_POST['id_turma'];
@$aba = @$_POST['tabClass'];

if (@$aba == 2) {
    $aluno = "SELECT DISTINCT ea.id_ea, p.id_pessoa, p.n_pessoa, p.ra, p.rg, p.responsavel,"
            . " p.sexo, e.evento, e.dt_evento, e.h_inicio, e.h_final, e.local_evento, e.ev_resp,"
            . " ta.codigo_classe, ta.fk_id_turma"
            . " FROM ge_evento_aluno ea"
            . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = ea.fk_id_pessoa"
            . " JOIN pessoa p on p.id_pessoa = ea.fk_id_pessoa"
            . " JOIN ge_eventos e on e.id_evento = ea.fk_id_evento"
            . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma"
            . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
            . " WHERE ea.fk_id_evento = '" . $id_evento . "' AND pl.at_pl = 1"
            . " ORDER BY p.n_pessoa";

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

    $wsql = "Select Distinct p.id_pessoa, p.n_pessoa, ta.fk_id_turma, ta.chamada, ta.situacao, t.codigo as codigo_classe, p.ra, p.rg, "
            . " e.evento, e.dt_evento, e.h_inicio, e.h_final, e.local_evento, e.ev_resp, p.sexo, p.responsavel "
            . " From pessoa p "
            . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa "
            . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
            . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
            . " JOIN ge_eventos e on e.fk_id_inst = ta.fk_id_inst "
            . " Where p.id_pessoa in (" . $idalunos . ") and e.id_evento = ' "
            . $id_evento . "' AND pl.at_pl = 1"
            . " order by ta.chamada ";

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

        if (!empty($v['responsaval'])) {
            $resp = $v['responsavel'];
        } else {
            $resp = "_________________________________________________";
        }

        switch (substr($v['codigo_classe'], 0, 2)) {
            case "EF":
                $cod = " do " . substr($v['codigo_classe'], 3, 1) . "º Ano " . substr($v['codigo_classe'], 4, 1);
                break;
            case "EI":
                $cod = " da " . substr($v['codigo_classe'], 3, 1) . "ª Fase Pré " . substr($v['codigo_classe'], 4, 1);
                break;
            case "EM":

                $cod = " da " . substr($v['codigo_classe'], 3, 1) . "ª Fase Maternal " . substr($v['codigo_classe'], 4, 1);
                break;
            case "EB":
                $cod = " do " . substr($v['codigo_classe'], 3, 1) . "Berçário " . substr($v['codigo_classe'], 4, 1);
                break;
            default :
                $cod = "";
                break;
        }
        ?>
        <div>
            <br />
            <div style="font-weight:bold; font-size:12pt; text-align: center">
                AUTORIZAÇÃO
            </div>
            <br /><br />
            <p style="text-align: justify">Eu, <?php echo $resp ?>, autorizo <?php echo $s = ($v['sexo'] == "M") ? 'meu filho' : 'minha filha' ?>
                <?php echo addslashes($v['n_pessoa']) ?>, RA nº.  <?php echo $v['ra'] ?>, RG nº. <?php echo $v['rg'] ?>, RSE nº. <?php echo $v['id_pessoa'] ?>
                <?php echo $cod ?>, a participar do evento abaixo:
            </p>
            <div>Evento: <?php echo $v['evento'] ?></div>
            <div>Dia e Hora: <?php echo data::converteBr($v['dt_evento']) . ' - ' . substr($v['h_inicio'], 0, 5) . ' até ' . substr($v['h_final'], 0, 5) ?> horas </div>
            <div>
                <p>
                    Autorizo também a participar dos preparativos para o evento.
                </p>
            </div>
            <?php
            ?>
            <br />
            <div style="text-align: right">Barueri, <?php echo date('d') . ' ' . data::mes(date('m')) . ' ' . date('Y') . '.' ?></div>
            <br />
            <div style="text-align: center">__________________________________________________</div>
            <div style="text-align: center">Assinatura do Responsável</div>
            <br /><br />
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
            ?>
            <br />
            <?php
        }
    }
    ?>
</div>
<?php
tool::pdfSimpleSemRodape();
?>