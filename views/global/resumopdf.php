<?php
ob_start();
ini_set('memory_limit', '2000M');
if (empty($_POST['periodoLetivo'])) {
    $periodoLetivo = sql::get('ge_setup', 'fk_id_pl', ['id_set' => 1], 'fetch')['fk_id_pl'];
} else {
    $periodoLetivo = $_POST['periodoLetivo'];
}
@$id_agrup = @$_POST['id_agrup'];
$hidden['id_agrup'] = @$id_agrup;

if ($_SESSION['userdata']['id_nivel'] == 8) {
    $id_inst = tool::id_inst();
} else {
    $id_inst = @$_POST['id_inst'];
}
$hidden['id_inst'] = $id_inst;

$id_gl = @$_POST['id_gl'];
$hidden['id_gl'] = $id_gl;
if (!empty(@$id_agrup)) {

    $aval = sql::get('global_aval', 'id_gl, n_gl', ['fk_id_agrup' => $id_agrup, '>' => 'n_gl']);
    if (count($aval) == 1) {
        $id_gl = current($aval)['id_gl'];
    }
    foreach ($aval as $v) {
        $avalia[$v['id_gl']] = $v['n_gl'];
    }
}


$id_turma = @$_POST['id_turma'];
if (!empty($id_gl)) {
    $dados = sql::get('global_aval', '*', ['id_gl' => $id_gl], 'fetch');
    $es = str_replace('|', ',', substr($dados['escolas'], 1, -1));
    $sql = "select n_inst, id_inst from instancia "
            . " where id_inst in (" . $es . ") "
            . "order by n_inst ";
    $query = $model->db->query($sql);
    $esc = $query->fetchAll();
    foreach ($esc as $v) {
        $escolas[$v['id_inst']] = $v['n_inst'];
    }
}
?>
<div class="fieldBody">
    <div style="text-align: center; font-size: 25px">
        Avaliação Diagnóstica
    </div>
    <?php
    if (!empty($_POST['acessarGrafico'])) {
        if (!empty($id_gl) && empty($id_inst)) {
            include ABSPATH . '/views/global/_resumo/graf.php';
        } else {
            include ABSPATH . '/views/global/_resumo/grafesc.php';
        }
    }
    ?>
</div>
<?php
    tool::pdfSecretaria('L');
