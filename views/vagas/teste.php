<?php
$tm_inicio = microtime( true );

$fp = fopen('/var/www/html/data2.txt', 'w+');
fwrite($fp, 'ok ');
fwrite($fp, date('Y/m/d H:i:s'));
fclose($fp);


// Armazena  o timestamp apos a execucao do script
$tm_fim = microtime( true );

// Calcula o tempo de execucao do script 
$tempo_execucao = $tm_fim - $tm_inicio;

// Exibe o tempo de execucao do script em segundos
echo '<b>Tempo de Execucao:</b> '.$tempo_execucao.' Segs';

$fp = fopen('/var/www/html/data2.txt', 'w+');
fwrite($fp, 'ok ');
fwrite($fp, date('Y/m/d H:i:s'));
fwrite($fp, '\n<b>Tempo de Execucao:</b> '.$tempo_execucao.' Segs');
fclose($fp);



$fp = fopen('/var/www/html/robots/log_classificar_vagas_teste.txt', 'w+');
fwrite($fp, date('Y/m/d H:i:s'));
fwrite($fp, ' - Tempo de Execucao:</b> '.$tempo_execucao.' Segs');
fclose($fp);