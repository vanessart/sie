<?php

/**
 * versão proximo ano
 */
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

include '/var/www/html/ge/constantDb.php';

$tm_inicio = microtime( true ); 

function setSerie($data) {
    $data = str_replace('-', '', $data);
    $d_b = ((date("Y") - 1) * 10000) + 331;
    $d_1m = ((date("Y") - 2) * 10000) + 331;
    $d_2m = ((date("Y") - 3) * 10000) + 331;
    $d_3m = ((date("Y") - 4) * 10000) + 331;
    $d_1p = ((date("Y") - 5) * 10000) + 331;
    $d_2p = ((date("Y") - 6) * 10000) + 331;

    if ($data > $d_b) {
        return 'Berçário';
    } elseif ($data > $d_1m) {
        return '1ª Fase - Maternal';
    } elseif ($data > $d_2m) {
        return '2ª Fase - Maternal';
    } elseif ($data > $d_3m) {
        return '3ª Fase - Maternal';
    } elseif ($data > $d_1p) {
        return '1ª Fase - Pré';
    } elseif ($data > $d_2p) {
        return '2ª Fase - Pré';
    } else {
        return 'Acima da Idade';
    }
}

function calcula($data_inicial, $data_final) {

    // Usa a função strtotime() e pega o timestamp das duas datas:
    $time_inicial = strtotime($data_inicial);
    $time_final = strtotime($data_final);

    // Calcula a diferença de segundos entre as duas datas:
    $diferenca = $time_final - $time_inicial; // 19522800 segundos

    $ano = round($diferenca / (60 * 60 * 24 * 365), 1); // 225 dias
    return $ano;
}

function sql() {

    $fields = "criterio1, "
            . "criterio2, "
            . "criterio3, "
            . "criterio4, "
            . "criterio5, "
            . "criterio6, "
            . "criterio7, "
            . "criterio8, "
            . "criterio9, "
            . "criterio10, "
            . "dt_vagas, "
            . "id_vaga, "
            . "dt_aluno, "
            . "fk_id_inst, "
            . "seriacao";
    $sql = "select $fields from vagas "
            . "where status like 'Deferido' "
            . "order by fk_id_inst asc, seriacao asc, pontuacao desc, dt_vagas  asc";

    $query = Conexao::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);

    return $array;
}

$vaga = sql();

foreach ($vaga as $v) {

    $serie = setSerie($v['dt_aluno']);

    $tempo = calcula(substr($v['dt_vagas'], 0, 10), date("Y-m-d"));
    $tempo = $tempo < 0.5 ? 0.5 : ($tempo < 1 ? 1 : 1.5);
    $pontuacao = $v['criterio1'] + $v['criterio2'] + $v['criterio3'] + $v['criterio4'] + $v['criterio5'] + $v['criterio6'] + $v['criterio7'] + $v['criterio8'] + $v['criterio9'] + $v['criterio10'] + $tempo;


    $sql = "UPDATE `vagas` SET `pontuacao` = '$pontuacao', `seriacao` = '$serie'  WHERE `vagas`.`id_vaga` ='" . $v['id_vaga'] . "'";
    $query = Conexao::getInstance()->query($sql);
}

//classificar
$vagaClass = sql();
$c = 1;
foreach ($vagaClass as $v) {
    if ($v['seriacao'] != @$serieOld || $v['fk_id_inst'] != @$cieOld) {
        $c = 1;
    }
    $cieOld = $v['fk_id_inst'];
    $serieOld = $v['seriacao'];
    $sql = "UPDATE `vagas` SET `classifica` = '$c'  WHERE `vagas`.`id_vaga` ='" . $v['id_vaga'] . "'";
    $query = Conexao::getInstance()->query($sql);
    $c++;

    $fp = fopen('/var/www/html/log_classificar_vagas_sql.txt', 'a+');
    fwrite($fp, date('Y/m/d H:i:s'));
    fwrite($fp, ' - ' . $sql . PHP_EOL);
    fclose($fp);
}

class Conexao {

    public static $instance;

    private function __construct() {
        //
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new PDO('mysql:host='.HOSTNAME.';dbname=ge2', DB_USER, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            //self::$instance = new PDO('mysql:host=127.0.0.2;dbname=ge3;port=3309', 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
        }

        return self::$instance;
    }

}


// Armazena  o timestamp apos a execucao do script
$tm_fim = microtime( true );
// Calcula o tempo de execucao do script 
$tempo_execucao = $tm_fim - $tm_inicio;
$horas = (int) ($tempo_execucao/60/60);
$minutos = (int) ($tempo_execucao/60) - $horas * 60;
$segundos = (int) $tempo_execucao - $horas * 60 * 60 - $minutos * 60;
// Exibe o tempo de execucao do script em segundos
$fp = fopen('/var/www/html/log_classificar_vagas_sql.txt', 'r+');
fwrite($fp, PHP_EOL . 'Data de execução: ' . date('Y/m/d H:i:s') . PHP_EOL );
fwrite($fp, "Tempo de execução: Hora:$horas Minutos: $minutos Segundos: $segundos " . PHP_EOL);
fwrite($fp, '##########################################################################' . PHP_EOL);
fwrite($fp, ' ' . PHP_EOL);
fclose($fp);

