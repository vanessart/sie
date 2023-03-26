<?php
if (!defined('ABSPATH'))
    exit;

$tm_inicio = microtime( true );

$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$ano = filter_input(INPUT_POST, 'ano', FILTER_SANITIZE_NUMBER_INT);
?>
<div class="fieldBody">
    <fieldset>
        <legend>
            Importar classes
        </legend>
        <form method="POST">
            <div class="row">
                <div class="col-sm-6 ">
                    <?php
                    $esc = escolas::idEscolas();
                    echo form::select('id_inst', $esc, 'Escola', $id_inst);
                    ?>
                </div>
                <div class="col-sm-4">
                    <?php echo form::selectNum('ano', [2000, (date("Y") + 1)], 'Ano', $ano) ?>
                </div>
                <div class="col-sm-2">
                    <input class="btn btn-success" type="submit" value="Continuar" />
                </div>
            </div>
        </form>
    </fieldset>
    <br /><br />
    <?php
    if (!empty($id_inst) && !empty($ano)) {
        ?>
        <form method="POST">
            <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
            <input type="hidden" name="ano" value="<?php echo $ano ?>" />
            <input type="hidden" name="classesGdae" value="1" />
            <div style="text-align: center">
                <?php echo form::button('Importar') ?>
            </div>
        </form>
        <br /><br />
        <?php
        $model->listTurmaGdae($id_inst, $ano);
    }
    ?>
</div>
<?php
// Armazena  o timestamp apos a execucao do script
$tm_fim = microtime( true );
// Calcula o tempo de execucao do script 
$tempo_execucao = $tm_fim - $tm_inicio;
$horas = (int) ($tempo_execucao/60/60);
$minutos = (int) ($tempo_execucao/60) - $horas * 60;
$segundos = (int) $tempo_execucao - $horas * 60 * 60 - $minutos * 60;

// Exibe o tempo de execucao do script em segundos
$fp = fopen('/var/www/html/log_import_classe.txt', 'r+');
fwrite($fp, PHP_EOL . 'Data de execução: ' . date('Y/m/d H:i:s') . PHP_EOL );
fwrite($fp, "Tempo de execução: Hora:$horas Minutos: $minutos Segundos: $segundos " . PHP_EOL);
fwrite($fp, '##########################################################################' . PHP_EOL);
fwrite($fp, ' ' . PHP_EOL);
fclose($fp);

?>