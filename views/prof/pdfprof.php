<?php
ob_start();

$esc = new escola;
$cor = '#F5F5F5';
$seq = 1;
//echo $esc->cabecalho();
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
    Lista de Professores
</div>

<table class="table tabs-stacked table-bordered">
    <thead>
        <tr>
            <th style="width: 10px; height: 15px; color: red" class="topo">Seq.</th>
            <th style="width: 80px; height: 15px; color: red" class="topo">Matrícula</th>
            <th style="width: 320px; height: 15px; color: red" class="topo">Nome Professor</th>
            <th style="width: 100px; height: 15px; color: red" class="topo">Disciplina</th>
            <th style="width: 89px; height: 15px; color: red" class="topo">Hac</th>
            <th style="width: 80px; height: 15px; color: red" class="topo">Período</th>         
        </tr>
    </thead>
    <tbody>
        <?php
        $wsql = "SELECT DISTINCT nao_hac, p.n_pessoa, p.emailgoogle as email, pe.rm, pe.id_pe,"
                . " pe.disciplinas, hac_periodo, hac_dia,fk_id_psc FROM ge_prof_esc pe"
                . " JOIN ge_funcionario f on f.rm = pe.rm"
                . " JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa"
                . " WHERE pe.fk_id_inst = " . tool::id_inst() . " ORDER BY n_pessoa";

        $query = $model->db->query($wsql);
        $prof = $query->fetchAll();

        foreach ($prof as $v) {
            ?>
            <tr>
                <td style="width: 10px; height: 15px; background-color: <?php echo $cor ?>" class="topo">
                    <?php echo $seq++ ?>
                </td>
                <td style="width: 80px; height: 15px; background-color: <?php echo $cor ?>" class="topo">
                    <?php echo $v['rm'] ?>
                </td>
                <td style="width: 320px; height: 15px; text-align: left; background-color: <?php echo $cor ?>" class="topo" >
                    <?php echo $v['n_pessoa'] ?>                 
                </td>
                <td style="width: 100px; height: 15px; background-color: <?php echo $cor ?>" class="topo">
                    <?php
                    
                    echo $model-> pegadisciplinahac($v['disciplinas']);
                    ?>
                </td>
                <td style="width: 89px; height: 15px; background-color: <?php echo $cor ?>" class="topo">
                    <?php
                    if ($v['nao_hac'] == 1) {
                        $dia = 'Outra UE';
                    } else {
                        if ($v['hac_dia'] == 0) {
                            $dia = 'Não Informado';
                        } else {
                            $dia = $v['hac_dia'] . 'ª Feira';
                        }
                    }
                    echo $dia;
                    ?>
                </td>
                <td style="width: 80px; height: 15px; background-color: <?php echo $cor ?>" class="topo">
                    <?php echo $v['hac_periodo'] ?>
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

