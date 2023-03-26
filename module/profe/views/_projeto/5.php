<?php
if (!defined('ABSPATH'))
    exit;

$flex = sql::get(['profe_projeto_flex'], 'id_flex, flex, fk_id_pessoa_prof, fk_id_pessoa, dt_inicio, dt_fim ', 'WHERE fk_id_projeto =' . $id_projeto." ORDER BY dt_inicio");

if ($flex) {

    $token = formErp::token('profe_projeto_flex', 'delete');
    foreach ($flex as $k => $v) {

        $flex[$k]['edit'] = '<button class="btn btn-info" onclick="edit(' . $v['id_flex'] . ')">Editar</button>';
        $flex[$k]['n_pessoa'] = toolErp::n_pessoa($flex[$k]['fk_id_pessoa']);
        $flex[$k]['prof'] = toolErp::n_pessoa($flex[$k]['fk_id_pessoa_prof']);
        $flex[$k]['descricao'] = nl2br($v['flex']);
        $flex[$k]['del'] = formErp::submit('Apagar', $token, ['1[id_flex]' => $v['id_flex'], 'activeNav' => 5, 'fk_id_projeto' => $id_projeto, 'fk_id_ciclo' => $id_ciclo, 'fk_id_disc' => $id_disc, 'fk_id_turma' => $id_turma, 'n_projeto' => $n_projeto]);
        $flex[$k]['pdf'] = '<button class="btn btn-outline-info" onclick="pdf(' . $v['id_flex'] . ')">Impressão</button>';
    }

    $form['array'] = $flex;
    $form['fields'] = [
        'Início' => 'dt_inicio',
        'Fim' => 'dt_fim',
        'Aluno' => 'n_pessoa',
        'Professor' => 'prof',
        'Flexibilização Curricular' => 'descricao',
        //'||2' => 'del',
        //'||3' => 'pdf',
        '||1' => 'edit',
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
    <br>
    <?= toolErp::divAlert('info','Flexibilização Curricular para alunos com deficiência. Ajuste <b>SE NECESSÁRIO</b>.') ?>
    <button class="btn btn-info" onclick="edit()">
        Novo
    </button>
    <br><br>
    <div>
        <form id="form" target="frame" action="<?= HOME_URI ?>/profe/flex" method="POST">
            <?= formErp::hidden($hidden) ?>
            <input type="hidden" name="id_flex" id="id_flex" value="" />
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
    <form id="formPDF" method="POST" target="_blank" action="<?= HOME_URI ?>/profe/pdf/flexPDF.php">
        <input type="hidden" name="id_projeto" id="id_projetoPDF" value="<?= $id_projeto ?>" />
        <input type="hidden" name="n_turma" id="n_turmaPDF" value="<?= $n_turma ?>" />
        <input type="hidden" name="id_flex" id="id_flexPDF" value="" />
    </form>
    <script>
        $('#myModal').on('hidden.bs.modal', function () {
            document.getElementById("form").action = '<?= HOME_URI ?>/profe/flex';
        });

        function edit(id) {
            if (id) {
                document.getElementById("id_flex").value = id;
            } else {
                document.getElementById("id_flex").value = "";
            }
            document.getElementById("form").submit();
            $('#myModal').modal('show');
            $('.form-class').val('');
        }
        function pdf(id_flex){
            if (id_flex){
                document.getElementById("id_flexPDF").value = id_flex;
            }
            document.getElementById("formPDF").submit();
        }

    </script>
