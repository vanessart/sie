<?php
@$id_sistema = $_POST['id_sistema'];
?>
<div class="fieldBody">
    <div class="fieldTop">
        Gerenciamento de Grupos e Usuários
    </div>
    <div class="field row">
        <div class="col-lg-3 input-group">
            <?php echo formulario::selectDB('sistema', 'id_sistema', 'Selecione um Subsistema:', NULL, NULL, 1, NULL, NULL, ['>' => 'n_sistema', 'ativo' => 1]) ?>
        </div>
    </div>
    <br />

    <?php
    if (!empty($id_sistema)) {
        ?>
        <div style="text-align: right">
            <form target="_blank" method="POST" action="<?php echo HOME_URI . '/app/excel/doc/expUserSis.php' ?>">
                <input type="hidden" name="id_sistema" value="<?php echo $id_sistema ?>" />
                <input class="btn btn-info" type="submit" value="Exportar com Planilha" />
            </form>   
        </div>
        <br />
        <?php
        $sisUser= $model->sisUser($id_sistema);

        foreach ($sisUser as $k => $v) {
            $sisUser[$k]['user'] = $v['id_pessoa'];
        }
        $form['array'] = $sisUser;
        $form['fields'] = [
            'ID' => 'id_pessoa',
            'Nome' => 'n_pessoa',
            'CPF' => 'cpf',
            'E-mail'=>'emailgoogle',
            'Grupo'=>'n_gr',
            'Instância' => 'n_inst'
        ];

        report::forms($form, NULL, ['id_sistema' => $id_sistema], HOME_URI . '/adm/user/');
    }
    ?>
</div>