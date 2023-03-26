<div>
    <?php
    if (!defined('ABSPATH'))
        exit;
    $up = $model->cateUp($dados['id_cate']);
    $upCate = $model->inscrUp($dados['id_cpf'], $dados['id_cate']);

    foreach ($up as $k => $v) {
        if (!empty($upCate[$v['id_up']])) {
            ?>
            <div class="border alert alert-primary" style="padding: 10px; margin-bottom: 20px; ">
                <div style="text-align: center; font-size: 25px;">
                    <?= $v['descr_up'] ?> 
                    <br /><br />
                    <div class="row">
                        <div class="col-12">
                            <iframe src="<?= HOME_URI ?>/inscr/def/formCertificado.php?id_up=<?= $v['id_up'] ?>&id_ec=<?= $id_ec ?>" style="width: 100%; height: 280px"></iframe>
                        </div>
                        <!--
                        <div class="col-2"  onclick="mostra('a<?= $k ?>')">
                            <button class="btn btn-warning">
                                Uploads
                            </button>
                        </div>
                        -->
                    </div>

                </div>
                <br /><br />
                <div id="a<?= $k ?>" >
                    <!--
        <div id="a<?= $k ?>" style="display: none">
                    -->
                    <?php
                    foreach ($upCate[$v['id_up']] as $ky => $y) {
                        ?>
                        <div class="alert alert-warning">
                            <div class="row">
                                <div class="col-11">
                                    <iframe name="pdf" src="<?= HOME_URI ?>/inscr/def/verPdf.php?id_iu=<?= $y['id_iu'] ?>" style="width: 100%; height: 260px"></iframe>
                                </div>
                                <div class="col-1">
                                    <input class="btn btn-primary" type="submit" onclick=" $('#pdf<?= $y['id_iu'] ?>').modal('show');$('.form-class').val('')" value="Abrir PDF" />
                                    <?php
                                    toolErp::modalInicio(null, 'modal-fullscreen', 'pdf' . $y['id_iu']);
                                    ?>
                                    <iframe name="pdf"  src="<?= HOME_URI ?>/pub/inscrOnline/<?= $y['link'] ?>" style="width: 100%; height: 80vh"></iframe>
                                        <?php
                                        toolErp::modalFim();
                                        ?>
                                </div>
                            </div>
                        </div> 
                        <br /><br />
                        <?php
                    }
                    ?>
                </div>
            </div>
            <br />
            <?php
        }
    }
    ?>

</div>

<script>
    function mostra(id) {
        if (document.getElementById(id).style.display == 'block') {
            document.getElementById(id).style.display = 'none'
        } else {
<?php
foreach ($up as $k => $v) {
    if (!empty($upCate[$v['id_up']])) {
        ?>
                    a<?= $k ?>.style.display = 'none';
        <?php
    }
}
?>
            document.getElementById(id).style.display = 'block';
        }
    }
</script>