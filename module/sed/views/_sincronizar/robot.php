<?php
$situacao = $_REQUEST['sit'];
if (empty($situacao)) {
    $situacao = 1;
}
$inst = escolas::idEscolas();
$pl = gtMain::periodos($situacao);
##################            
?>
<pre>   
    <?php
    print_r($pl);
    ?>
</pre>
<?php
###################
$pls = implode(',', array_keys($pl));
$SERVER_ADDR = $_SERVER['SERVER_ADDR'];
$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
if (!empty($_SESSION['userdata']['id_nivel'])) {
    $id_nivel = $_SESSION['userdata']['id_nivel'];
    $id_sistema = $_SESSION['userdata']['id_sistema'];
} else {
    $id_nivel = 0;
    $id_sistema = 0;
}

if (($REMOTE_ADDR == $SERVER_ADDR) || ($id_nivel == 2 && $id_sistema = 40)) {
    $sql = "SELECT DISTINCT `fk_id_inst` FROM `sed_robot` WHERE `time_stamp` LIKE '" . date("Y-m-d") . "%'";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    $fez = array_column($array, 'fk_id_inst');
    if (empty($fez)) {
        $fez = [];
    }
    logRobot("Inicio Robot");
    foreach ($inst as $id_inst => $nome) {
        if (!in_array($id_inst, $fez) || $situacao == 2) {
            restImport::baixarTurmaAluno(null, $id_inst, $pls, 1);
            echo $nome . '<br />';
            if ($situacao != 2) {
                logRobot($nome, $id_inst);
            }
        }
    }
    $sql = "UPDATE pessoa p JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa JOIN ge_turmas t on t.id_turma = ta.fk_id_turma JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl SET p.emailgoogle = p.id_pessoa WHERE pl.at_pl = 1 AND p.emailgoogle is null ";
    $query = pdoSis::getInstance()->query($sql);
    logRobot("Encerrado");
} else {
    echo '<div style="text-align: center; font-size: 35px"><br><br>Acesso Proibido <br><br> O que você está fazendo aqui?<br><br>Seu IP é ' . $REMOTE_ADDR . '</div>';
}

function logRobot($obs, $id_inst = 0) {
    $sql = "INSERT INTO `sed_robot` ( `obs`, fk_id_inst) VALUES ('$obs', $id_inst);";
    $query = pdoSis::getInstance()->query($sql);
}
