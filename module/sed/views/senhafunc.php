<?php
if (!defined('ABSPATH'))
    exit;
?>
<style>
    .bbtt{
        width: 350px;
    }
</style>
<div class="fieldBody">
    <div class="fieldTop">
        Redefinir Senhas dos Funcionários
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php
            $sql = "select "
                    . "distinct p.n_pessoa, p.id_pessoa, p.sexo, p.emailgoogle as email, f.rm "
                    . "from ge_funcionario f "
                    . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                    . " where f.fk_id_inst = " . tool::id_inst()
                    . " order by p.n_pessoa ";
            $query = $model->db->query($sql);
            $pf = $query->fetchAll();
            foreach ($pf as $k => $v) {
                $pf[$k]['senha'] = '<button class="btn btn-success" style="white-space: nowrap;" onclick="senha(' . $v['id_pessoa'] . ')">Redefinir Senha</button>';
            }
            $form['array'] = $pf;
            $form['fields'] = [
                'Professor' => 'n_pessoa',
                'Matrícula' => 'rm',
                '||2' => 'senha'
            ];
            report::simple($form);
            ?>
        </div>
    </div>
</div>
<form action="<?= HOME_URI ?>/sed/def/formSenha.php" id="formSenha" target="frame" method="POST">
    <input type="hidden" name="id_pessoa" id="id_pessoa_senha" value="" />
</form>
<form action="<?= HOME_URI ?>/sed/def/formEmail.php" id="formEmail" target="frame" method="POST">
    <input type="hidden" name="id_pessoa" id="id_pessoa_email" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame" ></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function senha(id) {
        if (id) {
            id_pessoa_senha.value = id;
        } else {
            id_pessoa_senha.value = "";
        }
        formSenha.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    
    function email(id) {
        if (id) {
            id_pessoa_email.value = id;
        } else {
            id_pessoa_email.value = "";
        }
        formEmail.submit();
        $('#myModal').modal('show');
        $('.form-class').val('formEmail');
    }
</script>
