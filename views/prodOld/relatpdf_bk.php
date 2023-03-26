<?php
if (!empty($id_inst)) {
    $sql = "select * from prod_main where fk_id_inst = $id_inst ";
} else {
    $sql = "select * from prod_main ";
}
$query = pdoSis::getInstance()->query($sql);
$fun = $query->fetchAll(PDO::FETCH_ASSOC);
ob_start();

$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$escola = new escola($id_inst);

$notaTurma = $model->notaTurmas($id_inst);
?>
<div class="fieldBody">
    <br />
    <div style="text-align: center; font-size: 20px">
        <?php echo $escola->_nome ?>
        - 
        Bonus Produtividade
    </div>
</div>

<br /><br />
<div style="text-align: center; font-size: 20px; font-weight: bold">
    Metodologia de Calculo
</div>
<br /><br />

<table cellspacing="0" border="0" style="margin: 0 auto">


    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="103" align="center" valign=middle><b><font size=5>Servidores</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle>
        <b><font size=4>Unidade Escolar</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle>
        <b><font size=4> Rede</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle><b><font size=4>Nota Classe*</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle><b><font size=4>Avaliação Diretor</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle><b><font size=4>Método</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>ADI - Assistente de Desenvolvimento Infantil</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="2" sdnum="1046;"><b><font size=3>2</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>Assist. Maternal</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="2" sdnum="1046;"><b><font size=3>2</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>Aux. de Ser. Diversos</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="1" sdnum="1046;"><b><font size=3>1</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>Aux. de Ser. Feminino</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="1" sdnum="1046;"><b><font size=3>1</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>Aux. de Ser. Gerais</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="1" sdnum="1046;"><b><font size=3>1</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>Aux. de Ser. Masc</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="1" sdnum="1046;"><b><font size=3>1</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>Auxiliar de Classe</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="1" sdnum="1046;"><b><font size=3>1</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>Inspetor</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="1" sdnum="1046;"><b><font size=3>1</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>Instrutor de Libras</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="1" sdnum="1046;"><b><font size=3>1</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>Instrutor Musical</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="1" sdnum="1046;"><b><font size=3>1</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>Instrutor Musical / Libras - CAP</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="3" sdnum="1046;"><b><font size=3>3</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>PEB I - 1º ao 3º ano</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="4" sdnum="1046;"><b><font size=3>4</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>PEB I - 1º ao 3º ano - CAP</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="3" sdnum="1046;"><b><font size=3>3</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>PEB I - 4º ao 5º ano</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="6" sdnum="1046;"><b><font size=3>6</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>PEB I - 4º ao 5º ano - CAP</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="3" sdnum="1046;"><b><font size=3>3</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>PEB I - ed. Infantil</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="4" sdnum="1046;"><b><font size=3>4</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>PEB I - ed. Infantil - CAP</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="3" sdnum="1046;"><b><font size=3>3</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>PEB II - 1º ao 3º - Inglês e Educação Física</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="4" sdnum="1046;"><b><font size=3>4</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>PEB II - 1º ao 3º e 4º ao 5º - Inglês e Educação Física</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="5" sdnum="1046;"><b><font size=3>5</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>PEB II - 1º ao 3º e Fund2 - Inglês e Educação Física</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="5" sdnum="1046;"><b><font size=3>5</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>PEB II - 4º ao 5º e Fund2</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="6" sdnum="1046;"><b><font size=3>6</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>PEB II - 6º a 9º ano</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="6" sdnum="1046;"><b><font size=3>6</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>PEB II - CAP</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="3" sdnum="1046;"><b><font size=3>3</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>Professor Coordenador</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="1" sdnum="1046;"><b><font size=3>1</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>Professor Diretor</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="1" sdnum="1046;"><b><font size=3>1</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>Professor Orientador</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="1" sdnum="1046;"><b><font size=3>1</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>Supervisor</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="3" sdnum="1046;"><b><font size=3>3</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>Tradutor - Libras</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="1" sdnum="1046;"><b><font size=3>1</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" height="25" align="left" valign=middle><b><font size=3>Vice-Diretor</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3>X</font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=bottom><b><font face="Arial Rounded MT Bold" size=3><br></font></b></td>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" align="center" valign=middle sdval="1" sdnum="1046;"><b><font size=3>1</font></b></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000" colspan=6 height="25" align="center" valign=bottom><font color="#000000">* Avaliação In Loco e/ou Avaliação Externa - Prova</font></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=6 height="25" align="left" valign=middle><font size=4 color="#000000">Método 1 - Seu bônus foi calculado usando a Nota da Unidade Escolar</font></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=6 height="25" align="left" valign=middle><font size=4 color="#000000">Método 2 - Seu bônus foi calculado usando a Média entre a Nota da Unidade Escolar e a Avaliação-Diretor.</font></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=6 height="25" align="left" valign=middle><font size=4 color="#000000">Método 3 - Seu bônus foi calculado usando a Nota da Rede</font></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=6 height="25" align="left" valign=middle><font size=4 color="#000000">Método 4 - Seu bônus foi calculado usando a Média da somatória da Média da(s) Avaliação(ões) das salas de Ciclo Básico/Alfabetização e a Média da(s) Sala(s) que leciona como titular (4ºs a 9ºs anos).</font></td>
</tr>
<tr>
    <td style="border-top: 1px solid #000000; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000" colspan=6 height="55" align="left" valign=middle><font size=4 color="#000000">Método  5 - Seu bônus foi calculado usando a  Média da(s) sala(s) que está alocado como titular.</font></td>
</tr>
</table>
<br /><br />
<table border="1" style="width: 100%" >
    <tr>
        <td style="text-align: center; padding: 8px">
            Nota da Rede: 8
        </td>
        <td style="text-align: center; padding: 8px">
            Nota da Escola: <?php echo ceil($notaTurma['escola'], 0.1) ?>
        </td>
    </tr>
</table>
<br />
<table border="1" style="width: 100%" >
    <tr>
        <td  style="text-align: center; background-color: black; color: white" colspan="2">
            Nota das Classes
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding: 10px">
            <table style="width: 100%" border="1">
                <tr><td style="border: none">&nbsp;</td></tr>
                <tr>
                    <?php
                    $c = 1;
                    foreach ($notaTurma as $k => $v) {
                        if (is_int($k)) {
                            ?>
                            <td style="background-color: white; border: none">&nbsp;&nbsp;</td>
                            <td style="padding: 4px">
                                <?php echo $v['n_turma'] ?>
                            </td>
                            <td style="padding: 4px">
                                <?php echo $v['nota'] ?>
                            </td>
                            <td style="background-color: white; border: none">&nbsp;&nbsp;</td>
                            <?php
                            if ($c > 4) {
                                $c = 1;
                                ?>
                            </tr>
                            <tr>
                                <td colspan="20" style="border: none">
                                    &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <?php
                            } else {
                                $c++;
                            }
                        }
                    }
                    ?>

                </tr>
                <tr><td style="border: none">&nbsp;</td></tr>
            </table>
        </td>
    </tr>
</table>

<br /><br />
<?php
foreach ($fun as $v) {

    if (!empty($v['metodo'])) {
        ?>
<table border=1 cellspacing=0 cellpadding=1 style="width: 800px">
                <tr>
                    <td style="width: 400px; padding: 15px">
                        Nome: <?php echo $v['n_pessoa'] ?>
                        <br /><br />
                        Matrícula:  <?php echo $v['rm'] ?>
                        <br /><br />
                        Nota: <?php echo $v['nota_final'] ?>
                        Porcentagem: <?php echo $v['perc'] ?>
                        <br /><br /><br /><br />
                        <div style="text-align: center">
                            __________________________________
                            <br />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assinatura
                        </div>

                        <?php
                    }
                    ?>
                </td>
                <td style="padding: 20px">
                    <?php echo $v['calculo'] ?>
                </td>
            </tr>
        </table>
    <br /><br />
    <?php
}
tool::pdfEscola();
?>

