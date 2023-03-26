<div class="fieldBody">
    <div class="fieldTop">
        Abrir/ Fechar Escola
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-6 text-center">
            <form method="POST">
                <input type="hidden" name="fechar" value="1" />
                <input class="btn btn-success" name="abf" type="submit" value="Abrir Todas" />
            </form>
        </div>
         <div class="col-sm-6 text-center">
            <form method="POST">
                <input class="btn btn-danger" name="abf" type="submit" value="Fechar Todas" />
            </form>
        </div>
    </div>
    <br /><br />
    <?php
    $escolas = $model->escolaFechaAbre();

    foreach ($escolas as $k => $v) {
        $v['ab']=1;
        $escolas[$k]['ab'] = formulario::submit($v['professor'] == 1?'Fechado':'Aberto', NULL, $v, NULL, NULL, NULL,'btn btn-'.($v['professor'] == 1?'danger':'success'));
    }
    $form['array'] = $escolas;
    $form['fields'] = [
        'CIE' => 'cie_escola',
        'Escola' => 'n_inst',
        '||'=>'ab'
    ];

    tool::relatSimples($form);
    ?>
</div>