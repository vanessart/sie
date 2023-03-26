<?php
if (!defined('ABSPATH'))
    exit;
?>

<div class="body">
    <div class="fieldTop">
        Anexos da Pauta
    </div>
    <?php if ( empty($model::isProfessor()) ) { ?>
    <form action="<?= HOME_URI ?>/htpc/pautas" target="_parent" method="POST" enctype="multipart/form-data" name="formapdup" id="formapdup">
        <div class="row">
            <div class="col">
                <?= formErp::input('tipo', 'Tipo de Documento', null, ' required ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <input class="btn btn-primary" required="required" type="file" name="arquivo" value="" id='selecao-arquivo' />
            </div>
            <div class="col">
                <button class="btn btn-success">
                    Enviar Arquivo
                </button>
            </div>
        </div>
        <br />
        <?=
        formErp::hidden(['fk_id_pauta' => $fk_id_pauta])
        . formErp::hiddenToken('uploadDoc');
        ?>
    </form>
    <br><br>
    <?php } ?>
    <div class="row">
        <div class="col">
            <table class="table">
                <tr>
                    <th>DATA</th>
                    <th>AUTOR</th>
                    <th>TIPO DE DOC.</th>
                    <th>ARQUIVO</th>
                    <th style="text-align:center;">ANEXO</th>
                </tr>
                <?php if (!empty($anexos)) { 
                    foreach ($anexos as $key => $value) { ?>
                    <tr>
                        <td><?= $value['dt_update'] ?></td>
                        <td><?= $value['n_pessoa'] ?></td>
                        <td><?= $value['tipo'] ?></td>
                        <td><?= $value['n_up'] ?></td>
                        <td style="text-align:center;"><?= $value['docx'] ?></td>
                    </tr>
                    <?php }
                } else { ?>
                <tr>
                    <td colspan="5">Não há anexos para esta pauta</td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>