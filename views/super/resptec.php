<?php
funcionarios::autocomplete(NULL, 1);
?>

<div class="fieldBody">
    <br /><br />
    <div class="row fieldWhite">
        <form  method="POST">
            <div class="col-md-6">
                <?php echo formulario::input('1[n_resp]', 'Responsável Técnico: ', NULL, NULL, ' required id="busca"  onkeypress="complete()" ') ?>
            </div>
            <div class="col-md-2">
                <?php echo formulario::input(NULL, 'Matricula: ', NULL, NULL, ' readonly id="rm" ') ?>
            </div>
            <div class="col-md-2">
                <?php echo DB::hiddenKey('super_resp_tec', 'replace') ?>
                <input type="hidden" id="id_pessoa" name="1[fk_id_pessoa]" value="" />
                <button type="submit" class="btn btn-success">
                    Incluir
                </button>
            </div>
        </form>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger" onclick="document.getElementById('busca').value = '';document.getElementById('rm').value = '';">
                Limpar
            </button>
        </div>

    </div>
    <br /><br />
    <?php
    $res = sql::get('super_resp_tec');
    $sqlkey = DB::sqlKey('super_resp_tec', 'replace');
    foreach ($res as $k => $v) {
        $res[$k]['del'] = formulario::submit(tool::simnao($v['ativo']), $sqlkey, ['1[id_resp]' => $v['id_resp'], '1[ativo]'=>($v['ativo']==1?0:1)], NULL, NULL, ($v['ativo']==1?'Desativar':'Ativar'), 'btn btn-'.($v['ativo']==1?'success':'danger'));
    }

    $form['array'] = $res;
    $form['fields'] = [
        'ID' => 'fk_id_pessoa',
        'Nome' => 'n_resp',
        'Ativo'=> 'del'
    ];

    tool::relatSimples($form);
    ?>

</div>
