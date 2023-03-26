<?php
if (@$id_re) {
    $resEdit = sql::get(['gt_retirada', 'tipo_doc'], '*', ['id_re' => $id_re], 'fetch');
    $p = ['Mãe', 'Pai', 'Responsável'];
    if (in_array($resEdit['parente'], $p)) {
        $readonly = 'readonly';
    }
}
?>
<br /><br />
<form method="POST">
    <div class="row">
        <div class="col-sm-6">
            <?php echo form::input('1[n_re]', 'Nome', @$resEdit['n_re'], @$readonly) ?>
        </div>
        <div class="col-sm-3">
            <?php echo form::input('1[parente]', 'Parentesco', @$resEdit['parente'], @$readonly) ?>
        </div>
        <div class="col-sm-3">
            <?php echo form::select('1[ativo]', [1 => 'Sim', 0 => 'Não'], 'Autorizado', 1) ?>
        </div>
    </div>
    <br /><br />    
    <div class="row">
        <div class="col-sm-6">
            <?php echo form::input('1[telefones]', 'Telefones', @$resEdit['telefones']) ?>
        </div>
        <div class="col-sm-3">
            <?php echo form::input('1[doc]', 'Nº Doc.', @$resEdit['doc'], @$readonly) ?>
        </div>
        <div class="col-sm-3">
            <?php
            if (@$readonly) {
                echo form::input(NULL, 'Tipo Doc..', @$resEdit['n_doc'], @$readonly);
            } else {
                echo form::selectDB('tipo_doc', '1[fk_id_doc]', 'Tipo Doc.', @$resEdit['fk_id_doc'], NULL, NULL, NULL, NULL, 'required');
            }
            ?>
        </div>
    </div>
    <br /><br />
    <div style="text-align: center">
        <?php 
        echo DB::hiddenKey('gt_retirada', 'replace');
        echo form::hidden(['1[id_re]' => $id_re, '1[fk_id_pessoa]' => $id_pessoa])
        ?>
        
        <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
        <input type="hidden" name="activeNav" value="6" />
        <input class="btn btn-success" type="submit" value="Salvar" />
    </div>
    <br /><br />
</form>

