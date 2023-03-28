<?php
if (!defined('ABSPATH'))
    exit;
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$data = filter_input(INPUT_POST, 'data');
$ck = @$_POST['ck'];
$periodo = sql::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $id_pl], 'fetch')['n_pl'];

foreach ($ck as $k => $v) {
    if (!empty($v)) {
        $ids[$k] = $k;
    }
}
if (!empty($ids)) {
    $sql = " SELECT p.n_pessoa, po.n_polo, ci.n_mc FROM maker_gt_turma t "
            . " JOIN maker_gt_turma_aluno ta on ta.fk_id_turma = t.id_turma "
            . " AND `fk_id_pl` = $id_pl "
            . " AND ta.fk_id_pessoa in (" . implode(', ', $ids) . ") "
            . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
            . " JOIN maker_polo po on po.fk_id_inst_maker = t.fk_id_inst "
            . " JOIN maker_ciclo ci on ci.id_mc = t.fk_id_ciclo ";
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
        <img style="position: absolute;width: 1122px;height: 720px;" src="<?= HOME_URI ?>/includes/images/maker/A5_MAKER_DECLARACAO.png">
        <div style="position: absolute;top: 220px;left: 261px; width: 600px;text-align: center;font-size: 22px">
            Declaramos que
            <div style="font-weight: bold; font-style: italic;margin-top: 10px">
                <?= $v['n_pessoa'] ?>
            </div>
            <br />
            Participou do Projeto Sala Maker, no <?= $periodo ?>, no polo <?= $v['n_polo'] ?>, nível <?= $v['n_mc'] ?>, com carga horária de 25h, realizada pela Assinco Maker por iniciativa da Secretaria de Educação de <?= CLI_CIDADE ?>.
            <br /><br /><br /><br />
            <?= CLI_CIDADE ?>, <?= data::porExtenso($data) ?>
        </div>
        <?php
        $mpdf->WriteHTML(ob_get_contents());
        ob_end_clean();
        if ($ct++ < count($a)) {
            $mpdf->AddPage('L');
        }
    }

    $mpdf->Output('Certificado.pdf', true);
} else {
   ?>
        <div class="alert alert-danger">
            Selecione os alunos antes de gerar o PDF 
        </div>
                                    <?php
}
?>

