<?php
$dados = $model->getPagina(@$_POST['id_pag']);
?>
<div class="field">
    <form method="POST">
        <table style="width: 100%">
            <tr>
                <td>
                    <input type="hidden" name="fk_id_sistema" value="<?php echo @$dados['fk_id_sistema'] ?>" />
                    <label style="white-space: nowrap">
                        Sistema:
                        <?php
                        echo $model->selectSistemas('fk_id_sistema', NULL, @$dados['fk_id_sistema']);
                        ?>
                    </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label style="white-space: nowrap">
                        Menu
                        <input style="width: 400px; max-width: 60%" type="text" name="n_pag" value="<?php echo @$dados['n_pag'] ?>" />
                    </label>
                    <br /><br />
                    <label style="white-space: nowrap">
                        Veiw:
                        <?php echo $model->formModelo(@$dados['view']) ?>
                    </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label style="white-space: nowrap">
                        Ordem:
                        <?php echo formOld::selectNum('ord_pag', 20, @$dados['ord_pag']) ?>
                    </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label style="white-space: nowrap">
                        Ativo:
                        <?php
                        echo formOld::select('ativo', [1 => 'Sim', 2 => 'Não'], 'required', NULL, NULL, $dados['ativo'])
                        ?>
                    </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label style="white-space: nowrap">
                        <?php echo formOld::checkbox('target', 1) ?>
                        Target
                    </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label style="white-space: nowrap">
                        <?php echo formOld::checkbox('posi_pag', 'L', $dados['posi_pag']) ?>
                        Paisagem
                    </label>
                    <br /><br />
                    <?php echo $model->db->hiddenKey('lacarPaginaForm') ?>
                    <div style="float: bottom; padding-right: 10px; white-space: nowrap">
                        Descrição:
                        <input style="width: 80%" type="text" name="descr_page" value="<?php echo @$dados['descr_page'] ?>" />
                        <input type="hidden" name="id_pag" value="<?php echo @$dados['id_pag'] ?>" />
                    </div>
                </td>
                <td style="width: 10px; text-align: right">
                    <input style="width: 80px;height: 80px" type="submit" value="Salvar" />

                </td>
            </tr>
        </table>
    </form> 
</div>
