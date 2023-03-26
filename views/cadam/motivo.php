<div class="fieldBody">
    <div class="fieldTop">
        Cadastro de Motivos
    </div>
    <br /><br />
    <form method="POST">
        <div class="row">
            <div class="col-sm-8">
                <?php echo DB::hiddenKey('cadam_motivo', 'replace') ?>
                <?php echo formulario::input('1[n_mot]', 'Motivo: ') ?>
                <input type="hidden" name="1[id_mot]" value="<?php echo @$_POST['id_mot'] ?>" />
            </div>
            <div class="col-sm-4">
                <input type="submit" value="Salvar" />
            </div>
        </div>
    </form>
    <br /><br />
    <?php
    $mot = sql::get('cadam_motivo');
    foreach ($mot as $k => $v){
        $mot[$k]['edit']= formulario::submit('Editar', NULL, $v);
    }
    $form['array'] = $mot;
    $form['fields'] = [
        'ID' => 'id_mot',
        'Motivo' => 'n_mot',
        '||2' => 'edit'
    ];
    tool::relatSimples($form);
    ?>
</div>