<?php
if (!defined('ABSPATH'))
    exit;
ob_start();
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$pdf = new pdf();
$escola = new ng_escola();
$pdf->headerContent = $escola->cabecalho();
?>
<br /><br />
<?php
if ($id_turma) {
    $aluno = turmas::classe($id_turma);
    include ABSPATH . '/module/sed/views/_carometro/pdf_tab.php';
}
$pdf->exec();
