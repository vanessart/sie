<?php
@$id_gr = $_POST['id_gr'];
?>
<div class="fieldTop">
    Gerenciamento de Grupos e Sistemas
</div>
<div class="field row">
    <div class="col-lg-3 input-group">
        <?php
        $sql = "select id_gr, n_gr from grupo where id_gr != 15";
        $query = $model->db->query($sql);
        $array = $query->fetchAll();
        foreach ($array as $v) {
            @$gr[$v['id_gr']] = $v['n_gr'];
        }

        formulario::select('id_gr', $gr, 'Selecione um Grupo:', @$_POST['id_gr'], 1);
        ?>
    </div>
</div>
<br />
<div class="field" style="min-height: 500px ">
    <?php
    if (!empty($id_gr)) {
        $sql = "SELECT id_ac, id_pessoa, n_pessoa, cpf FROM acesso_pessoa a "
                . "JOIN pessoa p on p.id_pessoa = a.fk_id_pessoa "
                . "WHERE `fk_id_gr` = $id_gr "
                . "order by n_pessoa";
        $query = $model->db->query($sql);
        $user = $query->fetchAll();
        $sqlkey = DB::sqlKey('acesso_pessoa', 'delete');
        foreach ($user as $k => $v) {
            $user[$k]['acesso'] = formulario::submit('Acessar Usuário', NULL, ['user' => $v['id_pessoa']], HOME_URI . '/adm/user');
            $user[$k]['del'] = formulario::submit('Excluir', $sqlkey, ['1[id_ac]' => $v['id_ac'], 'id_gr' => $id_gr]);
        }
        $form['array'] = $user;
        $form['fields'] = [
            'ID' => 'id_pessoa',
            'Nome' => 'n_pessoa',
            'CPF' => 'cpf',
            '||2' => 'del',
            '||1' => 'acesso'
        ];

        tool::relatSimples($form);
    }
    ?>

</div>
