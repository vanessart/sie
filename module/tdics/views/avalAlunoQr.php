<?php
if (!defined('ABSPATH'))
    exit;
ob_clean();
ob_start();
?>
<style>
    #resp{
        font-family: sans-serif;
        font-weight: bold;
        font-size: 1.2em;
        width: 100%;
    }
    #resp td{
        border: #f19015 medium solid;
    }
    #topo{
        color: white;
        background-color: #f19015;
    }

</style>
<?php
$pdf = new pdf();
$pdf->headerSet = null;
$pdf->mgt = 0;

$id_pessoa = filter_input(INPUT_POST, 'id_pessoa');
if (empty($id_pessoa)) {
    exit();
}
$sql = "SELECT "
        . " p.id_pessoa, p.n_pessoa, t.n_turma, po.n_polo "
        . " FROM tdics_turma_aluno ta "
        . " join tdics_turma t on t.id_turma = ta.fk_id_turma "
        . " join tdics_polo po on po.id_polo = t.fk_id_polo"
        . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
        . " WHERE p.id_pessoa in ( $id_pessoa) "
        . " ORDER BY p.n_pessoa ";
$query = pdoSis::getInstance()->query($sql);
$aluno = $query->fetchAll(PDO::FETCH_ASSOC);
if (!empty($aluno)) {
    $sql = "SELECT fk_id_pessoa FROM `tdics_aval_resp` WHERE `fk_id_pessoa` IN (" . (implode(', ', array_column($aluno, 'id_pessoa'))) . ") ";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    if ($array) {
        $fez = array_column($array, 'fk_id_pessoa');
    } else {
        $fez = [];
    }
    foreach ($aluno as $k => $v) {
        if (in_array($v['id_pessoa'], $fez)) {
            $lista[$v['id_pessoa']] = $v;
        }
    }
}

if (empty($lista)) {
    echo 'Nenhum dos alunos relacionados foram avaliados';
} else {
    foreach ($lista as $k => $v) {
        ?>
        <table style=" width: 100%" id="resp">
            <tr>
                <td colspan="3" style="text-align: center">
                    TODAS AS AVALIAÇÕES ACUMULADAS - <?= date("Y") ?>
                </td>
            </tr>
            <tr>
                <td style="width: 10px">
                    <img style="height: 140px" src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/<?= $this->sistema ?>/<?= $this->sistema ?>.png" alt="<?= $this->sistema ?>"/>
                </td>
                <td>
                    <table id="dados">
                        <tr>
                            <td>
                                Aluno:  
                            </td>
                            <td>
                                <?= toolErp::abrevia($v['n_pessoa'], 20) ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                RSE:  
                            </td>
                            <td>
                                <?= $v['id_pessoa'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Polo:  
                            </td>
                            <td>
                                <?= $v['n_polo'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Turma:    
                            </td>
                            <td>
                                <?= $v['n_turma'] ?>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 10px">
                    <?php
                    $token = substr((md5($k)), 13);
                    $end = 'https://portal.educ.net.br/' . HOME_URI . '/' . $this->controller_name . '/avalAluno?id=' . $k . '&token=' . urlencode($token);
                    echo '<img src="' . HOME_URI . '/app/code/php/qr_img.php?d=' . urlencode($end) . '&.PNG" width="150" height="150"/>';
                    ?>
                </td>
            </tr>

        </table>
        <br /><br />
        <?php
    }
}
$pdf->exec('qrcode.pdf');
