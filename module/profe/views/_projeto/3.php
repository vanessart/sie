<?php
if (!defined('ABSPATH'))
    exit;

$fotos = sql::get('profe_projeto_foto', '*', ['fk_id_projeto' => $id_projeto, '>' => 'dt_pf']);
?>
<div class="alert alert-warning" style="padding-top:  10px; padding-bottom: 0">
    <div class="row">
        <div class="col">
             <p style="font-weight: bold; font-size: 16px">Mensagem do Coordenador:</p>
            <p style=" font-size: 14px"><?= $msg_coord ?></p>
        </div>
    </div>
</div>
<button class=" btn btn-info" onclick="edit()">
    Inserir Imagem
</button>
<br /><br />
<div class="row">
    <?php
    if (!empty($fotos)) {
        foreach ($fotos as $v) {
            ?>
            <div class="col-4">
                <table class="table border">
                    <tr>
                        <td colspan="3" style="width: 10%; text-align: center; font-weight: bold">
                            <?= data::converteBr($v['dt_pf']) ?>: <?= !empty($v['fk_id_pessoa']) ? toolErp::n_pessoa($v['fk_id_pessoa']) : "" ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: center;">
                            <?php
                            if (empty($v['link_video'])) {
                                if (file_exists(ABSPATH . '/pub/infantilProj/' . $v['link'])) {?>
                                    <a onclick="$('#myModal').modal('show');$('.form-class').val('');" target="frame" href="<?= HOME_URI . '/pub/infantilProj/' . $v['link'] ?>">

                                        <img style="width: 100%; float: left;width:  430px;height: 215px;object-fit: cover;" src="<?= HOME_URI . '/pub/infantilProj/' . $v['link'] ?>" alt="foto"/>
                                    </a>
                                    <?php
                                }
                            }else{?>
                                <a  target="_blank" href="<?= $v['link_video'] ?>">

                                        <img style="width: 50%" src="<?= HOME_URI . '/'. INCLUDE_FOLDER .'/images/play.png' ?>" alt="foto"/>
                                    </a>
                                <?php
                            }?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align: center; font-weight: bold">
                            <?= $v['n_pf'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <?= $v['descr_pf'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center">
                            <button style="width: 100%" onclick="edit(<?= $v['id_pf'] ?>)" type="button" class="btn btn-info">
                                Editar
                            </button>
                        </td>
                         <td style="text-align: center">
                            <form id="excl<?= $v['id_pf'] ?>" method="POST">
                                <?= formErp::hidden([
                                     'activeNav' => 3,
                                     'fk_id_projeto' => $id_projeto,
                                     'n_projeto' => $n_projeto,
                                     '1[id_pf]' => $v['id_pf']
                                 ])
                                 . formErp::hidden($hidden)
                                 . formErp::hiddenToken('profe_projeto_foto', 'delete')
                                 ?>
                                <button style="width: 100%" class="btn btn-danger" onclick="if (confirm('Excluir')) {
                                                    excl<?= $v['id_pf'] ?>.submit()
                                                }" type="button">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                </table>
                <br /><br />
            </div>
            <?php
        }
    }
    ?>
</div>
<form action="<?= HOME_URI ?>/profe/def/projFoto.php" target="frame" id="form" method="POST">
    <input type="hidden" name="id_pf" id="id_pf" />
    <input type="hidden" name="msg_coord" value="<?= $msg_coord ?>" />
    <?=
    formErp::hidden([
    'id_projeto' => @$id_projeto,
    'n_projeto' => @$n_projeto
    ])
    .formErp::hidden($hidden)
    ?>
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 80vh;border: none; text-align:center;" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            id_pf.value = id;
        } else {
            id_pf.value = '';
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>