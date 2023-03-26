<?php
if (!defined('ABSPATH'))
    exit;
$msg = sql::get('profe_msg_prof', '*', ' where dt_fim >= \'' . date("Y-m-d") . '\' order by dt_inicio');
if ($msg) {
    foreach ($msg as $k => $v) {
        $msg[$k]['ac'] = '<button class="btn btn-info" onclick="edit(' . $v['id_mp'] . ')">Acessar</button>';
    }
    $form['array'] = $msg;
    $form['fields'] = [
        'ID' => 'id_mp',
        'Título' => 'n_mp',
        '||1' => 'ac'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Avisos para os Professores
    </div>
    <div class="row">
        <div class="col-3 text-center">
            <button class="btn btn-primary" onclick="edit()">
                Novo Aviso
            </button>
        </div>
        <div class="col-9 alert alert-info">
            <p>
                As mensagens cadastradas serão exibidas na página inicial no nível Professor.
            </p>
            <br />
            <p>
                Serão exibidas as mensagens com status Ativo e dentro das datas de início e fim
            </p>
        </div>
    </div>
    <br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form target="frameEdit" id="formEdit" method="POST" action="<?= HOME_URI ?>/profe/def/formMsgProf.php">
    <input id="id_mp" type="hidden" name="id_mp" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe name="frameEdit" style=" height: 80vh; border: none; width: 100%"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            id_mp.value = id;
        } else {
            id_mp.value = '';
        }
        formEdit.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>