<?php
ob_start();
$esc = new escola();
$turmas = $esc->turmas();

$sql = "SELECT * FROM `ge_aloca_disc` WHERE `nucleo_comum` = 1 ";
$query = $model->db->query($sql);
$array = $query->fetchAll();
foreach ($array as $v) {
    $nc[$v['fk_id_grade']][$v['fk_id_disc']] = 1;
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

<?php

$a = $model->atztabelageprofesc();
        
$sql = "SELECT ge_aloca_prof.fk_id_turma, ge_aloca_prof.rm, n_pe, iddisc  FROM `ge_aloca_prof` "
        . "join ge_prof_esc on ge_prof_esc.rm = ge_aloca_prof.rm "
        . " WHERE ge_aloca_prof.fk_id_inst = " . $esc->_id_inst;
$query = $model->db->query($sql);
$prof = $query->fetchAll();

foreach ($prof as $f) {
    $professor[$f['fk_id_turma']][$f['iddisc']]['rm'] = $f['rm'];
    $professor[$f['fk_id_turma']][$f['iddisc']]['n_pe'] = $f['n_pe'];
}

$id_grade = [];
foreach ($turmas as $v) {
    $id_grade[] = $v['id_grade'];
}
?>
<div style="font-weight:bold; font-size:10pt; background-color: #000000; width: 679px; color:#ffffff; text-align: center">
    Alocação de Professores por Disciplina
</div>

<?php
$sql = "SELECT id_disc, n_disc, nucleo_comum, fk_id_grade FROM ge_disciplinas "
        . "join ge_aloca_disc on ge_aloca_disc.fk_id_disc = ge_disciplinas.id_disc "
        . "where fk_id_grade in (" . implode(',', $id_grade) . ")"
        . " order by n_disc";

$query = $model->db->query($sql);
$disc = $query->fetchAll();

foreach ($disc as $v) {
    if ($v['nucleo_comum'] == 1) {
        @$grade[$v['fk_id_grade']][] = 'nc';
    }
    @$grade[$v['fk_id_grade']][] = $v['id_disc'];
    @$nucleo += $v['nucleo_comum'];
    $disciplinas[$v['id_disc']] = $v['n_disc'];
}
if ($nucleo > 0) {
    $disciplinas['nc'] = 'Núcleo Comum';
}

foreach ($disciplinas as $k => $v) {
    @$nucleo += $v['nucleo_comum'];
    ?>
    <div style="padding: 5px;page-break-before: auto " class="topo">
        <div class="topo">
            <?php echo $v ?>
        </div>
        <table class="table table-striped table-bordered">
            <tr>
                <td style="width: 15%; color: red" class="topo">Classe</td>
                <td style="width: 15%; color: red" class="topo">Código</td>
                <td style="width: 15%; color: red" class="topo">Matrícula</td>
                <td style="width: 55%; color: red" class="topo">Professor</td>
            </tr>
            <?php
            foreach ($turmas as $t) {
                if (in_array($k, $grade[$t['id_grade']])) {
                    if(empty($nc[$t['id_grade']][$k])) {
                        ?>
                        <tr>
                            <td class="topo">
                                <?php echo $t['n_turma'] ?>
                            </td>
                            <td class="topo">
                                <?php echo $t['codigo'] ?>
                            </td>
                            <td class="topo">
                                <?php echo @$professor[$t['id_turma']][$k]['rm'] ?>
                            </td>
                            <td style="text-align: left" class="topo">
                                <?php echo @$professor[$t['id_turma']][$k]['n_pe'] ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
            }
            ?>
        </table>
    </div>
    <?php
}
tool::pdfEscola();
?>

