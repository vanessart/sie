<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = toolErp::id_inst();
$antigo = filter_input(INPUT_POST, 'antigo', FILTER_SANITIZE_NUMBER_INT);
$mural = $model->mural($id_inst, $antigo);

if ($mural) {
    foreach ($mural as $k => $v) {
        if (empty($v['fk_id_turma']) && empty($v['fk_id_gr'])) {
            $mural[$k]['destino'] = 'Escola';
        } elseif (!empty($v['fk_id_turma'])) {
            $mural[$k]['destino'] = $v['n_turma'];
        } elseif (!empty($v['fk_id_gr'])) {
            $mural[$k]['destino'] = $v['n_gr'];
        }

        $mural[$k]['at_mural'] = toolErp::simnao($v['at_mural']);
        $mural[$k]['ac'] = '<button class="btn btn-info" onclick="np(' . $v['id_mural'] . ')">Acessar</button>';
    }
    $form['array'] = $mural;
    $form['fields'] = [
        'Título' => 'n_mural',
        'Início' => 'dt_inicio',
        'final' => 'dt_fim',
        'Público' => 'destino',
        'Ativo' => 'at_mural',
        '||1' => 'ac'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Mural de Recados
    </div>
    <div class="row">
        <div class="col text-center">
            <button class="btn btn-info" onclick="np()">
                Nova Postagem
            </button>
        </div>
        <div class="col text-center">
            <form method="POST">
                <?php
                if (empty($antigo)) {
                    ?>
                    <input type="hidden" name="antigo" value="1" />
                    <button type="submit" class="btn btn-warning">
                        Acessar Postagens Antigas
                    </button>
                    <?php
                } else {
                    ?>
                    <button type="submit" class="btn btn-primary">
                        Acessar Postagens Vigentes
                    </button>
                    <?php
                }
                ?>
            </form>
        </div>
    </div>
    <br />

    <br /><br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form target="frame" id="form" action="<?= HOME_URI ?>/sed/def/formPost.php" method="POST">
    <input id="id_mural" type="hidden" name="id_mural" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; border: none; height: 80vh" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function np(id) {
        if (id) {
            document.getElementById('id_mural').value = id;
        } else {
            document.getElementById('id_mural').value = '';
        }
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>