<div class="field" style="height: 130px">
    <div style="float: left">
        <form method="POST">
            CPF, E-mail ou ID:
            <?php echo formOld::input('search') ?>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <input type="submit" value="Buscar" />
        </form>
    </div>
    <div style="float: right;padding-right: 10% ">
        <?php
        if (!empty(@$_POST['redef'] && @$form['array'][0]['ativo'] == 'Não')) {
            ?>
            <form method="POST">
                Senha:
                &nbsp;&nbsp;
                <input required type="text" name="1[user_password]" value="<?php echo $form['array'][0]['cpf'] ?>" />
                <br /><br />
                Tipo:
                <?php
                echo formOld::tipoUser('1[fk_id_tp]',@$form['array'][0]['fk_id_tp'])
                ?>
                Registro
                <input required type="text" name="1[registro]" value="<?php echo $form['array'][0]['registro'] ?>" />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input required type="hidden" name="1[fk_id_pessoa]" value="<?php echo $form['array'][0]['id_pessoa'] ?>" />
                <input required type="hidden" name="1[id_user]" value="<?php echo $form['array'][0]['id_user'] ?>" />
                <input required type="hidden" name="1[ativo]" value="1" />
                <input required type="hidden" name="redef" value="$_POST['redef']" />
                <?php echo $model->db->hiddenKey('users', 'replace'); ?>
                <input type="hidden" name="search" value="<?php echo $_POST['search'] ?>" />
                <input type="submit" value="Ativar" />
            </form>
            <?php
        }
        ?>
    </div>
</div>

