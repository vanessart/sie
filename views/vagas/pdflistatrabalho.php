<?php
ob_start();
if (!empty($_POST['inform'])) {
    $id_inst = $_POST['fk_id_inst'];
} else {
    $id_inst = tool::id_inst();
}
?>
<head>
    <style>
        td{
            font-size: 8pt;
            font-weight:bolder;
            text-align: center;
            border-style: solid;
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
        . " and seriacao IN ('1ª Fase - Maternal','2ª Fase - Maternal', '3ª Fase - Maternal','Berçário') "
        . " and classifica is not NULL "
        . " and status like 'Deferido' "
        . "order by seriacao, classifica";
$crian = sql::get('vagas', 'classifica, id_vaga, dt_aluno, n_aluno, responsavel, seriacao, trabalha', $where);
$a_class = $model->aguardaclassificacao($id_inst);

foreach ($crian as $v) {
    $list[$v['seriacao']][$v['trabalha']][$v['classifica']] = $v;
}

$t = [1 => 'Mães que Trabalham', 0 => 'Mães que Não Trabalham'];
?>
<div style="font-weight:bold; font-size:10pt; background-color: #000000; color:#ffffff; text-align: center">
    Lista de Inscritos Critério Trabalho - <?php echo date("Y") ?> 
</div>
<div style="font-weight:bold; font-size:8pt; border-width: 0.5px; border-style: solid;color: red; text-align: center">
    Atualizado em  <?php echo data::proximoDia(date("d/m/Y"), -1) ?> 
</div>

<?php
if (!empty($list)) {
    foreach ($list as $k => $v) {

        for ($c = 1; $c > -1; $c--) {
            ?>
            <div style="font-weight:bolder; font-size:8pt; border-width: 0.5px; border-style: solid; text-align: center">
                <?php echo $k ?> 
            </div>
            <div style="font-weight:bolder; font-size:8pt; border-width: 0.5px; border-style: solid; text-align: left; color: red">
                <?php echo $t[$c]; ?> 
            </div>
            <table class="table tabs-stacked table-bordered">
                <tr>
                    <td>
                        Classificação
                    </td>
                    <td>
                        Inscrição
                    </td>
                    <td>
                        Criança
                    </td>
                    <td>
                        Responsável
                    </td>
                    <td>
                        Data Nasc.
                    </td>
                </tr>
                <?php
                foreach ($list[$k][$c] as $vv) {
                    ?>
                    <tr>
                        <td>
                            <?php echo $vv['classifica'] ?>
                        </td>
                        <td>
                            <?php echo str_pad($vv['id_vaga'], 6, "0", STR_PAD_LEFT) ?>
                        </td>
                        <td style="text-align: left">
                            <?php echo $vv['n_aluno'] ?>
                        </td>
                        <td style="text-align: left">
                            <?php echo $vv['responsavel'] ?>
                        </td>
                        <td>
                            <?php echo data::converteBr($vv['dt_aluno']) ?>
                        </td>
                    </tr>

                    <?php
                }
                ?>
                <tr>
                    <td colspan="5" style="text-align: left">
                        Aguardando Classificação: <?php echo (empty($a_class[$k][$c]) ? 0 :$a_class[$k][$c]) . ' criança(s)' ?>
                    </td>
                </tr>
            </table>

            <?php
        }
    }
} else {
    ?>
    <div style="text-align: center">
        Não há alunos
    </div>
    <?php
}
tool::pdfEscola('L', $id_inst);
?>