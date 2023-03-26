<?php
ob_start();

$esc = new escola;
//echo $esc->cabecalho();
$cor = '#F5F5F5';
$seq = 1;
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
    Lista de Professores Não Alocados
</div>

<table class="table tabs-stacked table-bordered">
    <thead>
        <tr>
            <th style="width: 10px; height: 15px; color: red" class="topo">Seq.</th>
            <th style="width: 100px; height: 15px; color: red" class="topo">Matrícula</th>
            <th style="width: 569px; height: 15px; color: red" class="topo">Nome Professor</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT ge_prof_esc.rm, n_pe, iddisc FROM `ge_prof_esc` "
                . "left join ge_aloca_prof on ge_aloca_prof.fk_id_inst = ge_prof_esc.fk_id_inst and ge_aloca_prof.rm = ge_prof_esc.rm "
              . " join ge_funcionario f on f.rm = ge_prof_esc.rm "
                . "WHERE ge_prof_esc.fk_id_inst = " . tool::id_inst()
                . " and iddisc is null "
                . " ORDER BY `n_pe` ASC ";

        $query = $model->db->query($sql);
        $profno = $query->fetchAll();

        foreach ($profno as $v) {
            ?>
            <tr>
                <td style="width: 10px; height: 15px; background-color: <?php echo $cor ?>" class="topo">
                    <?php echo $seq++ ?>
                </td>
                <td style="width: 100px; height: 15px; background-color: <?php echo $cor ?>" class="topo">
                    <?php echo $v['rm'] ?>
                </td>              
                <td style="width: 569px; height: 15px; text-align: left; background-color: <?php echo $cor ?>" class="topo" >
                    <?php echo $v['n_pe'] ?>
                    <?php $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5' ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>

<?php
tool::pdfEscola();
?>






