<div class="row">
    <div class="col-lg-4">
        <form method="POST">
            <table style="width: 100%">
                <tr>
                    <td>
                        <?php formulario::select('a[tipo]', ['Fixo' => 'Fixo: ', 'Celular' => 'Celular'], 'Tipo', @$dados['tipo']) ?>
                    </td>
                    <td>
                        <?php formulario::input('a[num]', 'Número: ') ?>
                    </td>
                    <td>
                        <?php echo formulario::hidden(['a[fkid]' => @$dados['id_pessoa'], 'a[fk_id_tp]' => 1]) ?>
                        <input type="hidden" name="novo" value="1" />
                        <input type="hidden" name="aba" value="tel" />
                        <input type="hidden" name="id_pessoa" value="<?php echo @$_POST['id_pessoa'] ?>" />
                        <?php echo DB::hiddenKey('telefones', 'replace') ?>
                        <button class="btn btn-success">
                            Salvar
                        </button>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<br />
<div class="row">
    <?php
    $tel = sql::get('telefones', '*', ['fk_id_tp' => 1, 'fkid' => @$dados['id_pessoa']]);
    foreach ($tel as $t) {
        ?>
        <div class="col-lg-2">
            <form id="dtel<?php echo $t['id_tel'] ?>" method="POST">
                <?php echo formulario::hidden(['a[id_tel]' => $t['id_tel']]) ?>
                <?php echo DB::hiddenKey('telefones', 'delete') ?>
                <input type="hidden" name="novo" value="1" />
                <input type="hidden" name="aba" value="tel" />
                <input type="hidden" name="id_pessoa" value="<?php echo @$_POST['id_pessoa'] ?>" />
            </form>
            <div class="input-group">
                <span class="input-group-addon btn btn-info" id="basic-addon1">
                    <?php echo $t['tipo'] ?>: 
                </span>
                <input readonly type="text" name="<?php echo $name[2] ?>" value=" <?php echo $t['num'] ?>" class="form-control text-center"  aria-describedby="basic-addon1" >
                <span onclick="document.getElementById('dtel<?php echo $t['id_tel'] ?>').submit()" class="input-group-addon btn btn-danger" id="basic-addon1" style="color: white">
                    Apagar
                </span>
            </div>
        </div>
        <?php
    }
    ?>
</div>
