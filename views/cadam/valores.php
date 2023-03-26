<?php
$dados = sql::get('cadam_valores', '*', NULL, 'fetch');
?>
<div class="fieldBody">
    <div class="fieldTop">
        Configuração de valores unitários para pagamentos
    </div>
    <br /><br />
    <form method="POST">
        <div class="row">
            <div class="col-sm-4">
                <?php echo formulario::input('1[hora]', 'Valor por Hora', NULL, $dados['hora']) ?>
            </div>
            <div class="col-sm-4">
                <?php echo formulario::input('1[dia]', 'Valor por Dia', NULL, $dados['dia']) ?>
            </div>
            <div class="col-sm-4">
                <?php echo DB::hiddenKey('cadam_valores', 'replace') ?>
                <input type="hidden" name="1[id_val]" value="1" />
                <input class="btn btn-success" type="submit" value="salvar" />
            </div>
        </div>
    </form>
</div>