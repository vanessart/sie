<?php
ob_start();
$cor = '#F5F5F5';
?>
<head>
    <style>
        .topo{
            font-size: 7pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
            border-width: 1px;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 1px;
            padding-bottom: 1px;
        }
        .quebra { 
            page-break-before: always; 
        }

    </style>
</head>
<?php
$where = " where fk_id_inst = '$id_inst' "
        . " and seriacao IN ('1ª Fase - Maternal','2ª Fase - Maternal', '3ª Fase - Maternal','Berçário')"
        . " and classifica is not NULL "
        . " and status like 'Deferido' "
        . "order by seriacao, classifica";
$crian = sql::get('vagas', 'classifica, id_vaga, dt_aluno, n_aluno, responsavel, seriacao', $where);
foreach ($crian as $v) {
    $list[$v['seriacao']][$v['classifica']] = $v;
}
if (!empty($list)) {
    foreach ($list as $k => $v) {
        ?>
        <div style="text-align: center; font-weight: bold">
            Classificação da Inscrição de vagas - <?php echo date("Y") ?> 
        </div>
        <div style="text-align: center; font-size: 13px">
            Atualizado em  <?php echo data::proximoDia(date("d/m/Y"), -1) ?> 
            <br />
        </div>
        <table style="width: 100%" border=1 cellspacing=0 cellpadding=1>
            <tr>
                <td colspan="5" style="font-weight:bold; font-size:8pt; color: red">
                    <?php echo $k ?>
                </td>
            </tr>
            <tr>
                <td class="topo" style="font-weight:bold">
                    Class.
                </td>
                <td class="topo" style="font-weight:bold">
                    Inscr.
                </td>
                <td class="topo" style="font-weight:bold">
                    Criança
                </td>
                <td class="topo" style="font-weight:bold">
                    Responsável
                </td>
                <td class="topo" style="font-weight:bold">
                    Nasc.
                </td>
            </tr>
            <?php
            foreach ($v as $kk => $vv) {
                ?>
                <tr>
                    <td class="topo" style="background-color: <?php echo $cor ?>" >
                        <?php echo $kk ?>
                    </td>
                    <td class="topo" style="background-color: <?php echo $cor ?>">
                        <?php echo str_pad($vv['id_vaga'], 6, "0", STR_PAD_LEFT) ?>
                    </td>
                    <td class="topo" style=" text-align:left; background-color: <?php echo $cor ?>">
                        <?php echo $vv['n_aluno'] ?>
                    </td>
                    <td class="topo" style="text-align:left; background-color: <?php echo $cor ?>">
                        <?php echo $vv['responsavel'] ?>
                    </td>
                    <td class="topo" style="background-color: <?php echo $cor ?>">
                        <?php
                        echo data::converteBr($vv['dt_aluno']);
                        $cor = ($cor == '#F5F5F5') ? $cor = '#FAFAFA' : $cor = '#F5F5F5';
                        ?>
                    </td>
                </tr>

                <?php
            }
            ?>
        </table>
        <?php
    }
} else {
    ?>
    <div style="text-align: center">
        Não há alunos
    </div>
    <?php
}
tool::pdfEscola();
?>