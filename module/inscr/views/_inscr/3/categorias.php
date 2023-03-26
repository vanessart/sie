<?php
if (!defined('ABSPATH'))
    exit;
$cate = sql::get('inscr_categoria', '*', ['fk_id_evento' => $form, '>' => 'ordem']);
if ($cate) {
    ?>
    <div class="row">
        <?php
        foreach ($cate as $v) {
            ?>
            <div class="col-sm-4" style="padding: 10px">
                <div class="border" style="width: 100%">
                    <div style="text-align: center; font-weight: bold; font-size: 20px" class="alert alert-success">
                        <?= $v['n_cate'] ?>
                    </div>
                    <br />
                    <div class="alert alert-success" style="min-height: 100px">
                        <?= $v['descr_cate'] ?>
                    </div>
                    <br />
                    <form method="POST">
                        <div style="text-align: center">
                            <?=
                            form::hidden([
                                'id_cate' => $v['id_cate'],
                                'form' => $form
                            ])
                            ?>
                            <button class="btn btn-success">
                                Selecionar
                            </button>
                        </div>
                    </form>
                    <br />
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
?>