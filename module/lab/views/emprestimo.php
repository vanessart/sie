<?php
if (!defined('ABSPATH'))
    exit;

if (in_array(user::session('id_nivel'), [10])) {
    $escola = $model->escolasOpt();
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
} else {
    $id_inst = tool::id_inst();
}
if (!empty($id_inst)) {
    $empretaAluno = $model->emprestaAluno($id_inst);
}
if (!empty($empretaAluno)) {
    foreach ($empretaAluno as $k => $v) {
        if (!empty($v['serial'])) {
            if (!empty($v['rm'])) {
                $empretaAluno[$k]['n_turma'] = 'Funcionário';
                $empretaAluno[$k]['id_pessoa'] = $v['rm'];
            }
            $empretaAluno[$k]['prot'] = formErp::submit('Protocolo', null, ['id_inst' => $id_inst, 'id_pessoa' => $v['id_pessoa'], 'serial' => $v['serial'], 'id_modem' => $v['fk_id_modem']], HOME_URI . '/lab/protAluno', 1);
            $empretaAluno[$k]['edit'] = formErp::button('Acessar', null, 'acesso(' . $v['id_ce'] . ')', 'primary');
            $empretaAluno[$k]['modem'] = (empty($v['fk_id_modem']) ? 'Não' : 'Sim');
        } else {
            unset($empretaAluno[$k]); 
        }
    }
    $form['array'] = $empretaAluno;
    $form['fields'] = [
        'Matrícula' => 'id_pessoa',
        'Nome' => 'n_pessoa',
        'Número de Série' => 'serial',
        'Modem' => 'modem',
        'E-mail' => 'email_google',
        'Turma' => 'n_turma',
        '||1' => 'prot',
        '||2' => 'edit'
    ];
}
?>
<div class="fieldTop">
    <div class="fieldTop">
        Empréstimo de Chromebooks
    </div>
    <div class="row">
        <div class="col-8 text-center">
            <?php
            if (!empty($escola)) {
                echo formErp::select('id_inst', $escola, 'Escola', $id_inst, 1);
            }
            ?>
        </div>
        <div class="col-4 text-center">
            <?php
            if (!empty($id_inst)) {
                ?>
                <button onclick="acesso()" class="btn btn-warning">
                    Novo empréstimo
                </button>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<br />
<?php
if (!empty($id_inst)) {
    if (!empty($empretaAluno)) {
        report::simple($form);
    }
}
?>
<form id="formFrame" target="frame" action="<?= HOME_URI ?>/lab/def/formChromeNovo.php" method="POST">
    <input type="hidden" id="id_ce" name="id_ce" value="" />
    <?=
    formErp::hidden(['id_inst' => $id_inst])
    ?>
</form>
<?php
toolErp::modalInicio();
?>
<iframe style=" width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function acesso(id) {
        if (id) {
            document.getElementById('id_ce').value = id;
        } else {
            document.getElementById('id_ce').value = '';
        }
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>