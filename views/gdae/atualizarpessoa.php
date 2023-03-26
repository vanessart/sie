<?php
$tm_inicio = microtime( true );
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Armazena  o timestamp apos a execucao do script
$tm_fim = microtime( true );
// Calcula o tempo de execucao do script 
$tempo_execucao = $tm_fim - $tm_inicio;
$horas = (int) ($tempo_execucao/60/60);
$minutos = (int) ($tempo_execucao/60) - $horas * 60;
$segundos = (int) $tempo_execucao - $horas * 60 * 60 - $minutos * 60;
// Exibe o tempo de execucao do script em segundos
$fp = fopen('/var/www/html/log_atualiza_pessoa.txt', 'r+');
fwrite($fp, PHP_EOL . 'Data de execução: ' . date('Y/m/d H:i:s') . PHP_EOL );
fwrite($fp, "Tempo de execução: Hora:$horas Minutos: $minutos Segundos: $segundos " . PHP_EOL);
fwrite($fp, '##########################################################################' . PHP_EOL);
fwrite($fp, ' ' . PHP_EOL);
fclose($fp);