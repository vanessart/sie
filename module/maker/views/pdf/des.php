<?php
if (!defined('ABSPATH'))
    exit;
ob_start();
$id_pl = @$_REQUEST['id_pl'];
$n_inst = @$_REQUEST['n_inst'];
$id_inst = @$_REQUEST['id_inst'];
$id_pessoa = @$_REQUEST['id'];
$dataBs = @$_REQUEST['data'];
if($dataBs){
    $data= base64_decode($dataBs);
}
$idPost = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = sql::get('pessoa', 'n_pessoa', ['id_pessoa' => $id_pessoa], 'fetch')['n_pessoa'];
if (!$idPost) {
    $tokenGet = @$_REQUEST['token'];
    if ($n_pessoa != base64_decode($tokenGet)) {
        echo 'Incompatibilidade de Token';
        exit();
    }
}
$sql = "SELECT distinct p.n_pessoa, p.id_pessoa, aut.sim "
        . " FROM  pessoa p "
        . " join ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa "
        . " join ge_turmas t on t.id_turma = ta.fk_id_turma and t.fk_id_ciclo in (1,2,3,4,5,6,7,8,9) and ta.fk_id_inst = $id_inst "
        . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl=1 "
        . " join maker_gt_turma_aluno tta on tta.fk_id_pessoa = ta.fk_id_pessoa "
        . " join maker_gt_turma tt on tt.id_turma = tta.fk_id_turma and tt.fk_id_pl = $id_pl "
        . " left join maker_autoriza aut on aut.id_pessoa_aluno = ta.fk_id_pessoa "
        . " order by p.n_pessoa ";

$query = pdoSis::getInstance()->query($sql);
$al = $query->fetchAll(PDO::FETCH_ASSOC);

$token = base64_encode($n_pessoa);
$pdf = new pdf();
$pdf->mgt = 60;
$pdf->id_inst = $id_inst;
$pdf->headerAlt = '<div style="text-align: center; font-weight: bold; font-size: 1.4em"><img src="' . HOME_URI . '/'. INCLUDE_FOLDER .'/images/maker/img.jpg" width="234" height="94"/><br>PROJETO - SALA MAKER<br>' . $n_inst . '</div>';
?>
<style>
    div{
        line-height: 2.0;
    }
</style>

<table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
    <tr>
        <td colspan="2" style="font-weight: bold; font-size: 1.4em; text-align: center">
            Autorizados 
        </td>
    </tr>
    <?php
    foreach ($al as $v) {
        if ($v['sim'] == 1) {
            ?>
            <tr>
                <td>
                    <?= $v['id_pessoa'] ?>
                </td>
                <td>
                    <?= $v['n_pessoa'] ?>
                </td>
            </tr>
            <?php
        }
    }
    ?>
</table>
<div style="page-break-after: always"></div>
<table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
    <tr>
        <td colspan="2" style="font-weight: bold; font-size: 1.4em; text-align: center">
            Não Autorizados 
        </td>
    </tr>
    <?php
    foreach ($al as $v) {
        if ($v['sim'] != 1) {
            ?>
            <tr>
                <td>
                    <?= $v['id_pessoa'] ?>
                </td>
                <td>
                    <?= $v['n_pessoa'] ?>
                </td>
            </tr>
            <?php
        }
    }
    ?>
</table>

<div style="text-align: justify; padding: 10px;">
    Declaro que a equipe escolar entrou em contato com os(as) responsáveis dos(as) alunos(as) participantes do projeto solicitando a autorização de rematricula, conforme a orientações da Secretaria da Educação:
</div>
<div style="text-align: right; padding-top: 40px">
    <?= CLI_CIDADE ?>, <?= data::porExtenso($data) ?>
</div>
<br /><br />
<?php
$site = 'https://portal.educ.net.br/';
$end = $site . HOME_URI . '/maker/pdf/des?id=' . $id_pessoa . '&n_inst=' . urlencode($n_inst) . '&id_inst=' . $id_inst . '&id_pl=' . $id_pl . '&data='.$dataBs.'&token=' . urlencode($token);
echo '<table style="width: 100%; font-size: 1.4em"><tr><td style="width: 100px">'
 . '<img src="' . HOME_URI . '/app/code/php/qr_img.php?d=' . urlencode($end) . '&.PNG" width="150" height="150"/>'
 . '</td><td style="font-size: 1.2em">'
 . '<p>responsável: ' . @$n_pessoa . '</p>'
 . '<p>Documento autenticado pelo sistema</p>'
 . "<p>Acesso em $end</p>"
 . '</td></tr></table>';

$pdf->exec('Maker.pdf');
