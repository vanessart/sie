<?php
if (!defined('ABSPATH'))
    exit;
$rec = sql::get('inscr_recurso', '*', ['fk_id_ec' => $dados['id_ec']], 'fetch');
$up = sql::get('inscr_recurso_up', '*', ['fk_id_ec' => $dados['id_ec']]);
?>
<div class="alert alert-dark">
    
     <?= @$rec['motivo'] ?>
    <br /><br />
   <?= @$rec['recurso'] ?>
</div>
<br /><br />
<form method="POST">
    <?= formErp::textarea('1[resposta]', @$rec['resposta'], 'Resposta') ?>
    <br /><br />
    <div class="row">
        <div class="col">
            <?= formErp::radio('1[deferido]', 2, 'Indeferido', @$rec['deferido'], ' required ') ?>
        </div>
        <div class="col">
            <?= formErp::radio('1[deferido]', 1, 'Deferido', @$rec['deferido'], ' required ') ?>
        </div>
    </div>
    <br />
    <div style="text-align: center; padding: 20px">
        <?=
        formErp::hidden([
            'id_ec' => $id_ec,
            'activeNav' => 3,
            '1[id_rec]' => @$rec['id_rec']
        ])
        . formErp::hiddenToken('inscr_recurso', 'ireplace')
        . formErp::button('Salvar')
        ?>
    </div>
</form>
<?php
if ($up) {
    ?>
    <br /><br />
    <table class="table table-bordered table-hover table-striped">
        <?php
        foreach ($up as $y) {
             if (file_exists(ABSPATH . '/pub/inscrOnline/' . $y['link'])) {
                                    $link = HOME_URI . '/pub/inscrOnline/' . $y['link'];
                                } else {
                                    $link = HOME_URI . '/pub/inscrOnline2/' . $y['link'];
                                }
            ?>
            <tr>
                <td>
                    <?= $y['nome_origin'] ?>
                </td>
                <td style="width: 100px">
                    <form onclick="$('#mpdf').modal('show');$('.form-class').val('')" target="frame1" action="<?= $link ?>">
                        <button class="btn btn-primary">
                            Visualizar
                        </button>
                    </form>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
}
toolErp::modalInicio(null, null, 'mpdf');
?>
<iframe name="frame1" style="width: 100%; height: 60vh; border: none"></iframe>
<?php
toolErp::modalFim();
