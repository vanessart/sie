<?php
$arq = filter_input(INPUT_POST, 'nome_arquivo', FILTER_UNSAFE_RAW);

$lista = $model->peganomearquivo();

if (!empty($_POST['geraarquivo'])) {
    $hora = date("H-i-s");
    $arquivo = 'ExportaçãoEmailGoogle_' . $hora;
    $sql = $model->geraarquivoemail($arquivo); 
}

if (!empty($_POST['gravapendencia'])) {
    $d = $model->gravaarquivogoogle($_POST['grava']);
}
?>
<div class="fieldBody">
    <div class="fieldTop">
        Exportação Email Google
    </div>
    <br /><br />
    <div class="row">
        <div class=" col-md-3" >
            <form method="POST">
                <input type="hidden" name="geraarquivo" value="1" />         
                <button type="submit" class="art-button">
                    Gerar Arquivo Exportação
                </button>
            </form>
        </div>
        <div class="col-md-2">
            <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                <?php
                if (!empty($_POST['geraarquivo'])) {
                    if (!empty($sql)) {
                        $lista = $model->peganomearquivo();
                        ?>
                        <input type="hidden" name="tokenSql" value="<?php echo tool::tokenSql() ?>" />
                        <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                        <input type="hidden" name="nomearquivo" value="<?php echo $arquivo ?>" />
                        <button type="submit" class="art-button">
                            Exportar
                        </button>
                        <?php
                    } else {
                        ?>
                        <button type="button">
                            Exportar
                        </button>
                        <?php
                    }
                } else {
                    ?>
                    <button type="button">
                        Exportar
                    </button>
                    <?php
                }
                ?>
            </form>
        </div>
        <div class="col-md-5">
            <?php echo formulario::select('nome_arquivo', @$lista, 'Nome do Arquivo', @$nome_arquivo, 1); ?>
        </div>
        <div class="col-md-2">
            <form method="POST">
                <input type="hidden" name="grava" value="<?php echo @$arq ?>" />
                <input type="hidden" name="gravapendencia" value="<?php echo "1" ?>" />
                <button type="submit" class="art-button">
                    Gravar
                </button>
            </form>
        </div>
    </div>
</div>

