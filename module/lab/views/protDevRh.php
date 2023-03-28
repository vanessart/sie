<?php
if (!defined('ABSPATH'))
    exit;
ob_start();
$pdf = new pdf();
//$_POST = $pdf->autentica(1);
$id_pessoa = intval(@$_POST['id_pessoa']);
$dt_PdfPrint = @$_POST['dt_PdfPrint'];
if (empty($dt_PdfPrint)) {
    $dt_PdfPrint = date("Y-m-d");
}
if ($id_pessoa) {
    $dados = $model->servidor($id_pessoa);
}
if (empty($dados)) {
    echo 'Não tenho a menor ideia do que você esta fazendo aqui :(';
    exit();
}
?>
<style>
    td{
        padding: 3px;
    }
</style>
<div style="text-align: center; font-size: 22px; font-weight: bold">
    D E C L A R A Ç Ã O
</div>
<br /><br /><br />

<div>
    <p style="text-align: justify">
        Declaro para fins de extinção de vínculo funcional que o servidor <span style="font-weight: bold"><?= $dados['n_pessoa'] ?></span>, CPF <span style="font-weight: bold"><?= $dados['cpf'] ?></span>, <span style="font-weight: bold">NÃO</span> tem sob sua responsabilidade equipamento cedido pelo DTTIE.
    </p>
    <p style="text-align: justify">
        O servidor esteve presente no Departamento Técnico de Tecnologia da Informação Educacional nesta data, devendo apresentar este documento à Secretaria de Administração para fins de rescisão.
    </p>
</div>

<div style="text-align: right; padding: 50px">
    <?= CLI_CIDADE ?>, <?= data::porExtenso($dt_PdfPrint) ?>
</div>
<br /><br /><br />
<div style="padding: 35px; width: 65%; margin-left: 35%">
    <hr>
    (assinatura e carimbo do responsável DTTIE ou autenticação digital)
</div>
<br />


<?php
$pdf->exec();
