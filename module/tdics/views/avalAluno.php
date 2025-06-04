<?php
if (!defined('ABSPATH'))
    exit;
ob_clean();
ob_start();
$token = @$_REQUEST['token'];

if (!empty($_REQUEST['id'])) {
    $id_pessoa = $_REQUEST['id'];
    if ($token != substr((md5($id_pessoa)), 13)) {
        echo 'Incompatibilidade de Token';
        exit();
    }
}

if (empty($id_pessoa)) {
    $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
}

if (empty($id_pessoa)) {
    exit();
}
$sql = "SELECT "
        . " p.id_pessoa, p.n_pessoa, t.n_turma, po.n_polo "
        . " FROM {$model::$sistema}_turma_aluno ta "
        . " JOIN {$model::$sistema}_turma t on t.id_turma = ta.fk_id_turma "
        . " JOIN {$model::$sistema}_polo po on po.id_polo = t.fk_id_polo"
        . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
        . " WHERE p.id_pessoa in ( $id_pessoa) "
        . " ORDER BY p.n_pessoa ";
$query = pdoSis::getInstance()->query($sql);
$a = $query->fetch(PDO::FETCH_ASSOC);
$n_pessoa = $a['n_pessoa'];
$n_turma = $a['n_turma'];
$n_polo = $a['n_polo'];
$pdf = new pdf();
$pdf->headerSet = null;
$pdf->mgt = 0;
$pdf->orientation = 'L';
$sql = " SELECT a.n_aval, a.id_aval, g.dt_ag, r.* FROM {$model::$sistema}_aval_resp r "
        . " JOIN {$model::$sistema}_aval a on a.id_aval = r.fk_id_aval "
        . " JOIN {$model::$sistema}_aval_group g on g.id_ag = a.fk_id_ag "
        . " WHERE `fk_id_pessoa` = $id_pessoa "
        . " ORDER by a.id_aval ";
$query = pdoSis::getInstance()->query($sql);
$resp = $query->fetchAll(PDO::FETCH_ASSOC);

if (empty($resp)) {
    echo 'Aluno não avaliado';
    exit();
} else {
    foreach ($resp as $v) {
        if (!empty($v['fk_id_pessoa_prof'])) {
            $aval[$v['id_aval']] = $v['id_aval'];
            $prof[$v['fk_id_pessoa_prof']] = $v['fk_id_pessoa_prof'];
        }
    }
    $sql = "SELECT * FROM `{$model::$sistema}_aval_quest` WHERE `fk_id_aval` IN (" . (implode(', ', $aval)) . ") ORDER BY `ordem` DESC ";
    $query = pdoSis::getInstance()->query($sql);
    $r = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($r as $v) {
        $respostas[$v['id_quest']] = $v;
    }
    $sql = "select n_pessoa from pessoa where id_pessoa in (" . (implode(', ', $prof)) . ")";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    $profe = array_column($array, 'n_pessoa');
    $professores = toolErp::virgulaE($profe);
}
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
<table style="width: 100%">
    <tr>
        <td style="width: 150px">
            <img style="height: 140px" src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/<?= $this->sistema ?>/<?= $this->sistema ?>.png" alt="<?= $this->sistema ?>"/>
        </td>
        <td>
            <table style="color: #f19015; font-family: sans-serif; font-weight: bold; font-size: 1.2em">
                <tr>
                    <td>
                        Aluno:  
                    </td>
                    <td>
                        <?= $n_pessoa ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        RSE:  
                    </td>
                    <td>
                        <?= $id_pessoa ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Polo:  
                    </td>
                    <td>
                        <?= $n_polo ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Turma:    
                    </td>
                    <td>
                        <?= $n_turma ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Professor<?= count($profe) > 1 ? 'es' : '' ?>:   
                    </td>
                    <td>
                        <?= @$professores ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<?php
foreach ($resp as $v) {
    ?>
    <table id="resp">
        <tr>
            <td colspan="3" style="text-align: center; font-weight: bold; font-size: 1.5em">
                <?= $v['n_aval'] ?> (<?= dataErp::porExtenso($v['dt_ag']) ?>)
            </td>
        </tr>
        <tr id="topo">
            <td style="height: 80px; text-align: center; width: 200px">
                Momento
            </td>
            <td style="height: 80px; text-align: center">
                Quesitos para Análise
            </td>
            <td style="height: 80px; text-align: center">
                Análise
            </td>
        </tr>

        <?php
        $rj = json_decode($v['respostas'], true);
        foreach ($rj as $k => $y) {
            $p = $respostas[$k]
            ?>
            <tr>
                <td style="text-align: center">
                    <?= $p['momento'] ?>
                </td>
                <td style="text-align: center">
                    <?= $p['n_quest'] ?>
                </td>
                <td style="text-align: center">
                    <?php
                    foreach (range(1, $p['valor_' . $y]) as $i) {
                        ?>
                        <img style="height: 40px" src="<?= HOME_URI ?>/<?= INCLUDE_FOLDER ?>/images/<?= $this->sistema ?>/icone.png" alt="Maker"/>
                        <?php
                    }
                    ?>
                    <br />
                    <?= $p['resp_' . $y] ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <div style="page-break-after: always"></div>
    <?php
}
?>





















<?php
$pdf->exec('Avaliacao.pdf');
