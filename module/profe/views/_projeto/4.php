<?php
if (!defined('ABSPATH'))
    exit;

$registro = sql::get('profe_projeto_ava', '*', 'WHERE fk_id_projeto =' . $id_projeto.' AND (fk_id_pessoa = ' .toolErp::id_pessoa().' OR fk_id_pessoa is null) ORDER BY dt_ava');

if ($registro) {

    $token = formErp::token('profe_projeto_ava', 'delete');
    foreach ($registro as $k => $v) {

        $registro[$k]['edit'] = '<button class="btn btn-info" onclick="edit(' . $v['id_ava'] . ')">Editar</button>';
        $registro[$k]['del'] = formErp::submit('Apagar', $token, ['1[id_ava]' => $v['id_ava'], 'activeNav' => 4, 'fk_id_projeto' => $id_projeto, 'fk_id_ciclo' => $id_ciclo, 'fk_id_disc' => $id_disc, 'fk_id_turma' => $id_turma, 'n_projeto' => $n_projeto, 'msg_coord' => $msg_coord]);
        $registro[$k]['autor'] = $model->autores($v['fk_id_pessoa']);
    }

    $form['array'] = $registro;
    $form['fields'] = [
        'Autor' => 'autor',
        'Data' => 'dt_ava',
        'Avaliação' => 'situacao',
        //'||2' => 'del',
        '||1' => 'edit'
    ];
}
?>

<div class="body">
    <div class="alert alert-warning" style="padding-top:  10px; padding-bottom: 0">
        <div class="row">
           <div class="col">
             <p style="font-weight: bold; font-size: 16px">Mensagem do Coordenador:</p>
            <p style=" font-size: 14px"><?= $msg_coord ?></p>
        </div>
        </div>
    </div>
    <button class="btn btn-info" onclick="edit()">
        Novo Avaliação
    </button>
    <br><br>
    <div>
        <form id="form" target="frame" action="<?= HOME_URI ?>/profe/def/projetoAva.php" method="POST">
            <?= formErp::hidden($hidden) ?>
            <input type="hidden" name="id_ava" id="id_ava" value="" />
            <input type="hidden" name="msg_coord" value="<?= $msg_coord ?>" />
            <input type="hidden" name="fk_id_projeto" id="fk_id_projeto" value="<?= $id_projeto ?>" />
            <input type="hidden" name="n_projeto" id="n_projeto" value="<?= $n_projeto ?>" />
        </form>

        <?php
        if (!empty($form)) {
            report::simple($form);
        }

        toolErp::modalInicio();
        ?>
        <iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
            <?php
            toolErp::modalFim();
            ?>
    </div>

    <script>
        $('#myModal').on('hidden.bs.modal', function () {
            document.getElementById("form").action = '<?= HOME_URI ?>/profe/def/projetoAva.php';
        });

        function edit(id) {
            if (id) {
                document.getElementById("id_ava").value = id;
            } else {
                document.getElementById("id_ava").value = "";
            }
            document.getElementById("form").submit();
            $('#myModal').modal('show');
            $('.form-class').val('');
        }

    </script>
