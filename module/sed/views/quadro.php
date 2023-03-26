<?php
if (!defined('ABSPATH'))
    exit;
$edit = filter_input(INPUT_POST, 'edit', FILTER_SANITIZE_NUMBER_INT);
$id_q = filter_input(INPUT_POST, 'id_q', FILTER_SANITIZE_NUMBER_INT);
$quadro = $model->quadroAviso();
foreach ($quadro as $k => $v) {
    $quadro[$k]['at'] = toolErp::simnao($v['at_q']);
    $quadro[$k]['edit'] = 1;
}
$form['array'] = $quadro;

$form['fields'] = [
    'ID' => 'id_q',
    'Título' => 'n_q',
    'Ativo' => 'at',
    'Início' => 'dt_ini',
    'Final' => 'dt_fim'
];
?>
<div class="body">
    <div class="fieldTop">
        Quadro de avisos
    </div>
    <div class="row">
        <div class="col-3 text-center">
            <?= formErp::submit('Novo Aviso', null, ['edit' => 1]) ?>
        </div>
        <div class="col-9 alert alert-info">
            <p>
                As mensagens cadastradas serão exibidas na página inicial no nível Escola.
            </p>
            <br />
            <p>
                Serão exibidas as mensagens com status Ativo e dentro das datas de início e fim
            </p>
        </div>
    </div>
    <br />
    <?php
    report::forms($form, 'sed_quadro');
    ?>
</div>

<?php
if (!empty($edit)) {
    ?>
    <form target="frameEdit" id="formEdit" method="POST" action="<?= HOME_URI ?>/sed/def/formQuadro.php">
        <input id="id_q" type="hidden" name="id_q" value="<?= $id_q ?>" />
    </form>
    <?php
    toolErp::modalInicio(1);
    ?>
    <iframe name="frameEdit" class="modal-lg" style=" height: 80vh; border: none; width: 100%"></iframe>
        <?php
        toolErp::modalFim();
        ?>
    <script>
        $(document).ready(function () {
            document.getElementById('formEdit').submit();

        });
    </script>
    <?php
}
