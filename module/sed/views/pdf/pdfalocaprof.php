<?php
ob_start();
$esc = new escola;
?>
<head>
    <style>   
        .topo{
            height: 5px;
            font-size: 8pt;
            font-weight:bolder;
            text-align: center;
            border: solid;
            border-width: 1px;
            padding-left: 5px; 
            page-break-inside: avoid;
        }
    </style>   
</head>

<?php
if (!empty($proximaFolha)) {
    ?>
    <div style="page-break-after: always"></div>
    <?php
} else {
    $proximaFolha = 1;
}

$sql = "SELECT n_disc, ge_aloca_prof.rm, n_turma, codigo  FROM `ge_aloca_prof` "
        . " join ge_turmas on ge_turmas.id_turma = ge_aloca_prof.fk_id_turma "
        . " join ge_periodo_letivo pl on pl.id_pl = ge_turmas.fk_id_pl and pl.at_pl = 1 "
        . " join ge_disciplinas on ge_disciplinas.id_disc = ge_aloca_prof.iddisc "
        . " WHERE ge_aloca_prof.fk_id_inst = " . tool::id_inst();

$query = $model->db->query($sql);
$disc = $query->fetchAll();

foreach ($disc as $v) {
    $n_disc[$v['rm']][$v['n_turma']][] = $v['n_disc'];
    $codigo[$v['rm']][$v['n_turma']][] = $v['codigo'];
}

 $sql = "SELECT  ge_aloca_prof.rm, n_turma, codigo  FROM `ge_aloca_prof` "
        . "join ge_turmas on ge_turmas.id_turma = ge_aloca_prof.fk_id_turma "
        . " join ge_periodo_letivo pl on pl.id_pl = ge_turmas.fk_id_pl and pl.at_pl = 1 "
        . " WHERE iddisc = 'nc' and ge_aloca_prof.fk_id_inst = " . tool::id_inst()
        . " order by n_turma";

$query = $model->db->query($sql);
$disc = $query->fetchAll();

foreach ($disc as $v) {
    $n_disc[$v['rm']][$v['n_turma']]['nc'] = 'Núcleo Comum';
    $codigo[$v['rm']][$v['n_turma']]['nc'] = $v['codigo'];
}

$sql = "SELECT p.n_pessoa as n_pe, ap.rm "
        . " FROM `ge_prof_esc` ap "
        . " JOIN ge_funcionario f on f.rm = ap.rm "
        . " JOIN pessoa p on p.id_pessoa = fk_id_pessoa  "
        . " WHERE ap.`fk_id_inst` = " . tool::id_inst() . " "
        . " ORDER BY `p`.`n_pessoa` ASC ";
$query = $model->db->query($sql);
$prof = $query->fetchAll();
?>

<header>
    <div style="font-weight:bold; font-size:10pt; background-color: #000000; width: 679px; color:#ffffff; text-align: center">
        Alocação de Professores
    </div>
</header>

<?php
foreach ($prof as $v) {

    if (!empty($n_disc[$v['rm']])) {
        ?>
        <!--<div style="width: 679px; page-break-inside: avoid; padding: 5px" class="topo"> -->
        <div style="padding: 5px" class="topo">
            <div style="width: 679px; text-align: left" class="topo">
                Matrícula: <?php echo $v['rm'] . ' - ' . $v['n_pe'] ?>
            </div>

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <td style="width: 20%; color: red" class="topo">
                            Classe
                        </td>
                        <td style="width: 20%; color: red" class="topo">
                            Código
                        </td>
                        <td style="width: 60%; color: red" class="topo">
                            Disciplina
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($n_disc[$v['rm']] as $k => $i) {
                        foreach ($i as $kk => $vv) {
                            ?>
                            <tr>
                                <td class="topo">
                                    <?php echo @$k ?>
                                </td>
                                <td class="topo">
                                    <?php echo @$codigo[$v['rm']][$k][$kk] ?>
                                </td>
                                <td class="topo">
                                    <?php echo @$vv ?>
                                </td>                          
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
            <table style="width: 100%">
                <tr>
                    <td>
                        <table style="width: 100%">
                            <tr>
                                <td>
                                    ( ) Concordo
                                </td>
                                <td>
                                    ( ) Não Concordo
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <div style="padding-top: 10px; text-align: right; padding-right: 10px">
                            <p>
                                _____________________________________________________________
                            </p>
                            <p>
                                Matrícula: <?php echo $v['rm'] . ' - ' . $v['n_pe'] ?>
                            </p>
                        </div>
                    </td>
                </tr>
            </table>

        </div>
        <br />
        <?php
    }
}
?>
<div style="padding-top: 50px; text-align: right; padding-right: 10px">
    <p>
        _____________________________________________________________
    </p>
    <p>
        Diretor(a) da <?= toolErp::n_inst() ?>
    </p>
</div>
<?php
tool::pdfEscola();
?>



