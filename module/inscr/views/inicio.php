<?php
if (!defined('ABSPATH'))
    exit;
$sql = "SELECT DISTINCT id_cpf, i.fk_id_vs, i.pis, i.conta_banco FROM inscr_incritos_3 i JOIN inscr_evento_categoria c on i.id_cpf = c.fk_id_cpf WHERE c.fk_id_sit = 3  ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($array as $v) {
    @$r['Total']++;
    if ($v['fk_id_vs'] == 2) {
        @$r['Em Análise']++;
    } elseif ($v['fk_id_vs'] == 3) {
        @$r['Devolvido']++;
    } elseif ($v['fk_id_vs'] == 1) {
        @$r['Validado']++;
    } elseif ($v['fk_id_vs'] == 0 && (!empty($v['conta_banco']) || !empty($v['pis']))) {
        @$r['Iniciou']++;
    } else {
        @$r['Não Entrou']++;
    }
}
?>
<div class="alert alert-primary" style="text-align: center">
    Fase de Entrega
 </div>
<br /><br />
<table class="table table-bordered table-hover table-striped">
        <tr>
            <?php
            foreach ($r as $k => $v) {
                ?>
                <td>
                    <?= $k ?>
                </td>
                <?php
            }
            ?>
        </tr>
        <tr>
            <?php
            foreach ($r as $k => $v) {
                ?>
                <td>
                    <?= $v ?>
                </td>
                <?php
            }
            ?>
        </tr>
    </table>
<script>
$(function() {
    setTimeout(function(){ location.reload(); }, 30000);
});
</script>
<?php
exit;

$sql = "SELECT SUBSTRING(dt_inscr, 1, 10) as id_ct, COUNT(id_ec) n_ct FROM inscr_evento_categoria where fk_id_sit > 1 GROUP BY SUBSTRING(dt_inscr, 1, 10) ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);

$adm1 = toolErp::idName($array);

$sql = "SELECT c.n_cate, COUNT(id_ec) ct, i.fk_id_sit FROM inscr_evento_categoria i "
        . " JOIN inscr_categoria c on c.id_cate = i.fk_id_cate "
        . " where i.fk_id_sit > 1 "
        . " GROUP BY c.n_cate "
        . " order by n_cate";
$query = pdoSis::getInstance()->query($sql);
$func = $query->fetchAll(PDO::FETCH_ASSOC);
$total = 0;

$sql = "SELECT count(id_ec) ct from inscr_evento_categoria c "
        . " join inscr_incritos_3  i  on. i.id_cpf = c.fk_id_cpf "
        . " where fk_id_sit = 1 and nome != ''";
$query = pdoSis::getInstance()->query($sql);
$aberto = $query->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT count(id_ec) ct from inscr_evento_categoria c "
        . " join inscr_incritos_3  i  on. i.id_cpf = c.fk_id_cpf "
        . " where fk_id_sit = 1 and nome = ''";
$query = pdoSis::getInstance()->query($sql);
$desistiu = $query->fetch(PDO::FETCH_ASSOC);
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="body">
    <div style="padding: 20px">
        <div class="row">
            <div class="col-10" style="text-align: center; font-weight: bold; font-size: 1.5em">
                Inscrições
            </div>
            <div class="col-2">
                <form>
                    <button class="btn btn-primary">
                        Atualizar
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php
    $inicio = date('Y-m-d', strtotime('-30 days', strtotime(date("Y-m-d"))));
    $fim = date("Y-m-d");
    $dts = dataErp::datasPeriodo($inicio, $fim);
    ?>
    <script type="text/javascript">
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Dias', 'Acesso Diário'],
<?php
foreach ($dts as $dt) {
    ?>
                    ['<?= substr(dataErp::converteBr($dt), 0, 5) ?>', <?= intval(@$adm1[$dt]) ?>],
    <?php
}
?>
            ]);

            var options = {
                //hAxis: {title: 'Dias', titleTextStyle: {color: '#333'}},
                //vAxis: {minValue: 0}
                legend: {position: 'top'}
            };

            var chart = new google.visualization.AreaChart(document.getElementById('chart_div1'));
            chart.draw(data, options);
        }
    </script>
    <div id="chart_div1" style="width: 100%; height: 200px;"></div>
    <br /><br /><br />
    <?php
    if (!empty($aberto['ct'])) {
        ?>
        <div class="alert alert-primary text-center" style="font-size: 1.3em">
            <?= $aberto['ct'] ?> inscrições em aberto.
        </div>
        <?php
    }
    if (!empty($desistiu['ct'])) {
        ?>
        <div class="alert alert-danger text-center" style="font-size: 1.3em">
            <?= $desistiu['ct'] ?> desistiram da inscrição.
        </div>
        <?php
    }
    ?>
    <br /><br />
    <table class="table table-bordered table-hover table-striped" style="width: 600px; margin: auto">
        <tr>
            <td>
                Função
            </td>
            <td>
                Quant.
            </td>
        </tr>
        <?php
        foreach ($func as $v) {
            ?>
            <tr>
                <td>
                    <?= $v['n_cate'] ?>
                </td>
                <td>
                    <?= $v['ct'] ?>
                </td>
            </tr>
            <?php
            $total += $v['ct'];
        }
        ?>
        <tr>
            <td>
                Total
            </td>
            <td>
                <?= $total ?>
            </td>
        </tr>
    </table>
</div>
