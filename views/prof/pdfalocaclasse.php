<?php
ob_start();
$esc = new escola();
$turmas = $esc->turmas();

$sql = "SELECT * FROM `ge_aloca_disc` WHERE `nucleo_comum` = 1 ";
$query = $model->db->query($sql);
$array = $query->fetchAll();

foreach ($array as $v) {
    $nc[$v['fk_id_grade']][$v['fk_id_disc']] = 1;
    @$ncAulas[$v['fk_id_grade']] += $v['aulas'];
}
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
        }
    </style>   
</head>

<div style="font-weight:bold; font-size:10pt; background-color: #000000; width: 679px; color:#ffffff; text-align: center">
    Alocação de Professores por Classe
</div>

<?php
foreach ($turmas as $v) {
    ?>

    <div style="width: 100%">
        <table style="width: 100%">
            <tr>
                <td style="width: 30%; color: red" class="topo">Classe: <?php echo $v['n_turma'] ?></td>
                <td style="width: 30%; color: red" class="topo">Código: <?php echo $v['codigo'] ?></td>
                <td style="width: 40%; color: red" class="topo">Segmento: <?php echo $v['n_curso'] ?></td>
            </tr>
        </table>
    </div>

    <table class="table table-striped table-bordered" style="width: 100%;page-break-inside:avoid">
        <tr>
            <td style="width: 20%" class="topo">Disciplina</td>
            <td style="width: 10%" class="topo">Aulas</td>
            <td style="width: 10%" class="topo">Matrícula</td>
            <td style="width: 60%" class="topo">Professor</td>
        </tr>

        <?php
        $sql = "select ap.iddisc, f.rm , p.n_pessoa as n_pe "
                . " from ge_aloca_prof ap "
                . " join ge_funcionario f on f.rm = ap.rm "
                . " join  pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " where fk_id_turma = " . $v['id_turma'];
        $query = $model->db->query($sql);
        $alocado = $query->fetchAll();
         unset($aloca);
       unset($prof);
        foreach ($alocado as $a) {
            $aloca[$a['iddisc']] = $a['rm'];
            $prof[$a['iddisc']] = $a['n_pe'];
        }
        $disc = turma::disciplinas($v['id_turma']);

        foreach ($disc as $d) {
            if (empty($nc[$v['id_grade']][$d['id_disc']])) {
                ?>
                <tr>
                    <td style="text-align: left" class="topo">
                        <?php echo $d['n_disc'] ?>
                    </td>
                    <td class="topo">
                        <?php echo $d['aulas'] ?>
                    </td>
                    <td class="topo">
                        <?php echo @$aloca[$d['id_disc']] ?>
                    </td>
                    <td style="text-align: left" class="topo">
                        <?php echo @$prof[$d['id_disc']] ?>
                    </td>
                </tr>
                <?php
            }
        }
        if (!empty($nc[$v['id_grade']])) {
            ?>
            <tr>
                <td style="text-align: left" class="topo">
                    Núcleo Comum
                </td>
                <td class="topo">
                    <?php echo $ncAulas[$v['id_grade']] ?>
                </td>
                <td class="topo">
                    <?php echo @$aloca['nc'] ?>
                </td>
                <td style="text-align: left" class="topo">
                    <?php echo @$prof['nc'] ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <div>
    </div>
    <?php
}
tool::pdfEscola();
?>
    