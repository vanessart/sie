<?php
@$id_gr = $_POST['id_gr'];
?>
<div class="fieldBody">
    <div class="fieldTop">
        Gerenciamento de Grupos e Usuários
    </div>
    <div class="field row">
        <div class="col-lg-3 input-group">
            <?php echo formulario::selectDB('grupo', 'id_gr', 'Selecione um Grupo:', NULL, NULL, 1, NULL, NULL, ['>' => 'n_gr', 'at_gr' => 1]) ?>
        </div>
    </div>
    <br />

    <?php
    if (!empty($id_gr)) {
        ?>
        <div style="text-align: right">
            <form target="_blank" method="POST" action="<?php echo HOME_URI . '/app/excel/doc/expUser.php' ?>">
                <input type="hidden" name="id_gr" value="<?php echo $id_gr ?>" />
                <input class="btn btn-info" type="submit" value="Exportar com Planilha" />
            </form>   
        </div>
        <br />
        <?php
        $grupUser = $model->grupUser($id_gr);

        foreach ($grupUser as $k => $v) {
            $grupUser[$k]['user'] = $v['id_pessoa'];
        }
        $form['array'] = $grupUser;
        $form['fields'] = [
            'ID' => 'id_pessoa',
            'Nome' => 'n_pessoa',
            'CPF' => 'cpf',
            'E-mail'=>'emailgoogle',
            'Instância' => 'n_inst'
        ];

        report::forms($form, NULL, ['id_gr' => $id_gr], HOME_URI . '/adm/user/');
    }
    ?>
</div>