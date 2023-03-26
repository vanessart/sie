<?php

if (!empty($_POST['print'])) {
    $id_gl = @$_POST['id_gl'];
    $id_turma = @$_POST['id_turma'];
    if ($_SESSION['userdata']['id_nivel'] == 8) {
        $id_inst = tool::id_inst();
    } else {
        $id_inst = @$_POST['id_inst'];
    }

}

$graf = aval::quantHab($id_gl, @$id_turma, @$id_inst);
$aval = aval::getAval($id_gl);
$descr = unserialize($aval['perc']);
$valores = explode(',', $aval['valores']);
$val = unserialize($aval['val']);

$escola = sql::get('instancia', 'n_inst', ['id_inst' => @$id_inst], 'fetch')['n_inst'];
$turma = sql::get('ge_turmas', 'n_turma', ['id_turma' => $id_turma], 'fetch')['n_turma'];
$disc = sql::get('ge_disciplinas', 'n_disc', ['id_disc' => @$aval['fk_id_disc']], 'fetch')['n_disc'];
include( ABSPATH . "/app/mpdf/mpdf.php");

$mpdf = new mPDF();
$js = file_get_contents('https://www.gstatic.com/charts/loader.js', false, 'Content-Type: text/javascript');
$mpdf->WriteHTML($js);
//$mpdf->SetJS(' type="text/javascript" src="https://www.gstatic.com/charts/loader.js"');
$mpdf->WriteHTML('<br /><br />
<div style="text-align: center; font-size: 25px; font-weight: bold">
    Gráficos por habilidades
</div>
<br /><br />');

if (empty($_POST['print'])) {
    $mpdf->WriteHTML('
    <form style="text-align: center" target="_blank" action="<?php echo HOME_URI ?>/global/projetoa" method="POST">
        <?php echo formulario::hidden($hidden) ?>
        <input class="btn btn-info" name="print" type="submit" value="PDF" /> 
    </form>
    ');
}
$mpdf->WriteHTML('
<br /><br />
<div class="row">');

foreach ($graf as $k => $v) {
    $mpdf->WriteHTML  ('
    <div class="col-sm-6">
        <div style=" border: solid #000000 thin; margin: 5px; height: 780px">
            <div style="text-align: center; font-weight: bold">
                ' . $escola . '
                <br />
                ');
    if (empty($turma)) {
        $mpdf->WriteHTML($aval['ciclos'] . ' Ano');
    } else {
        $mpdf->WriteHTML($turma);
    }
    $mpdf->WriteHTML('<br />');
    $mpdf->WriteHTML('

    </div>
    <div style="margin: 4%">
        Habilidade <span style="font-weight: bold">' . $k . '</span>: ' . $descr[$k] . '
    </div>
    ');
    $mpdf->SetJS('
    google.charts.load("current", {packages: [\'corechart\']});
    google.charts.setOnLoadCallback(drawChart' . $k . ');
    function drawChart' . $k . '() {
    var data = google.visualization.arrayToDataTable([
    ["", "Porc.", {role: "style"}],
    ["G1", ' . intval(@$v['G1']) . ', "green"],
    ["D2", ' . intval(@$v['D2']) . ', "yellow"],
    ["D3", ' . intval(@$v['D3']) . ', "orange"],
    ["D4", ' . intval(@$v['D4']) . ', "red"],
    ["D5", ' . intval(@$v['D5']) . ', "blue"]
    ]);

    var view = new google.visualization.DataView(data);
    view.setColumns([0, 1,
    {calc: "stringify",
    sourceColumn: 1,
    type: "string",
    role: "annotation"},
    2]);

    var options = {
    width: 600,
    height: 400,
    bar: {groupWidth: "95%"},
    legend: {position: "none"},
    vAxis: {title: \'Porcentagem\', maxValue: 100}
    };
    var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values' . $k . '"));
    chart.draw(view, options);
    }');
    $mpdf->WriteHTML('
    <div id="columnchart_values' . $k . '" style="width: 400px; height: 300px;"></div>
    <br /><br /><br /><br /><br /><br /><br /><br />
    <table style="margin: 0 auto" cellspacing=0 cellpadding=1 border="1">
        <tr>
            <td>
                (G)abarito e
                <br />
                (D)istratores  
            </td>
            <td>
                Interpretação Pedagógica da Resposta ao Item
            </td>
        </tr>
        <tr>
            <td>
                (G1) 
            </td>
            <td>
                Domina a habilidade aferida pelo item.
            </td>
        </tr>
        <tr>
            <td>
                (D2)
            </td>
            <td>
                Possui domínio mediano da habilidade aferida pelo item.
            </td>
        </tr>
        <tr>
            <td>
                (D3)  
            </td>
            <td>
                Possui conhecimento mínimo da habilidade aferida pelo item.
            </td>
        </tr>
        <tr>
            <td>
                (D4) 
            </td>
            <td>
                Não domina a habilidade aferida pelo item.
            </td>
        </tr>
        <tr>
            <td>
                (D5) 
            </td>
            <td>
                Erro de preenchimento ou falta de marcação.
            </td>
        </tr>
    </table>
    </div>
    </div>
    <div style="page-break-before: always;"> </div>');
}
$mpdf->WriteHTML('
</div>');
$mpdf->Output();
exit;
