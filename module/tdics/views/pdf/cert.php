<?php
if (!defined('ABSPATH'))
    exit;
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$data = filter_input(INPUT_POST, 'data');
$ck = @$_POST['ck'];
$periodo = sql::get($model::$sistema . '_pl', 'n_pl', ['id_pl' => $id_pl], 'fetch')['n_pl'];

foreach ($ck as $k => $v) {
    if (!empty($v)) {
        $ids[$k] = $k;
    }
}
if (!empty($ids)) {
    $sql = "SELECT "
            . " p.n_pessoa, c.n_curso, p.sexo "
            . " FROM " . $model::$sistema . "_turma_aluno ta "
            . " JOIN " . $model::$sistema . "_turma t on t.id_turma = ta.fk_id_turma "
            . " AND ta.fk_id_pessoa in (" . implode(', ', $ids) . ") "
            . " JOIN " . $model::$sistema . "_curso c on c.id_curso = t.fk_id_curso "
            . " AND t.fk_id_pl = $id_pl "
            . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
            . " order by p.n_pessoa";
    $query = pdoSis::getInstance()->query($sql);
    $a = $query->fetchAll(PDO::FETCH_ASSOC);
    require_once ABSPATH . '/vendor/autoload.php';
    $mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
    $mpdf->DeflMargin = 0;
    $mpdf->DefrMargin = 0;
    $mpdf->orig_tMargin = 0;
    $mpdf->tMargin = 0;
    $mpdf->orig_bMargin = 0;
    $mpdf->margin_header = 0;
    $mpdf->margin_footer = 0;
    $mpdf->lMargin = 0;
    $mpdf->orig_lMargin = 0;
    $mpdf->rMargin = 0;
    $mpdf->orig_rMargin = 0;
    $mpdf->OrientationChanges = 'L';
    $ct = 1;

    foreach ($a as $v) {
        ob_start();
        ?>
        <img style="position: absolute;width: 1122px;height: 720px;" src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/<?= $this->controller_name ?>/certificado.jpg">
        <div style="position: absolute;top: 220px;left: 360px; width: 700px;text-align: center;font-size: 22px; line-height: 1.5">
            Declaramos que
            <div style="font-weight: bold; font-style: italic;margin-top: 10px">
                <?= $v['n_pessoa'] ?>
            </div>
            <br />
            Participou do <?= constant('PROJETO_' . strtoupper($model::$sistema)) ?>, no curso 
            <div style="font-weight: bold;margin-top: 10px">
                “<?= $v['n_curso'] ?>”,
            </div>
            no período de <?= $periodo ?>, com carga horária de 200 horas, 
            <br />
            realizado por iniciativa da Secretaria de Educação de 
            <br />
            <?= CLI_CIDADE ?>
            <br /><br />
            <?= CLI_CIDADE ?>, <?= data::porExtenso($data) ?>
        </div>
        <?php
        $mpdf->WriteHTML(ob_get_contents());
        ob_end_clean();
        if ($ct++ < count($a)) {
            $mpdf->AddPage('L');
        }
    }

    $mpdf->Output();
//   $mpdf->Output('Certificado.pdf', true);
} else {
    ?>
    <div class="alert alert-danger">
        Selecione os alunos antes de gerar o PDF 
    </div>
    <?php
}
?>

