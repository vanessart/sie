<?php
if (!defined('ABSPATH'))
    exit;
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$bimestre = filter_input(INPUT_POST, 'bimestre', FILTER_SANITIZE_NUMBER_INT);
$id_pdi = filter_input(INPUT_POST, 'id_pdi', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa_prof = toolErp::id_pessoa();
$id_pl_atual = curso::id_pl_atual()['id_pl'];
$fotos = $model->getRegFoto($id_pessoa,$bimestre,$id_pl);
?>
<style type="text/css">
    .titulo_anexo{
        color: #16baed;
        font-weight: bold;
        text-align: center;
    }
    .sub_anexo{
        font-weight: bold;
        text-align: center;
    }
    .sub2_anexo{
        font-weight: bold;
        text-align: center;
        FONT-SIZE: 14px;

    }
    .comp{
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        color: red;
    }
    .tit_table{
        font-weight: bold;
    }
    .tit_linha{
        font-weight: bold;
        font-size: 12px;
        width: 30%;
    }
    .tabela{
        width: 100%;
        border: 1;
        font-size: 12px;
        cellspacing: 0;
        cellpadding: 2;
    }
    .tabela td{
        padding: 4px;
    }
</style>
<div class="body">
    <?= toolErp::cabecalhoSimples() ?>
    <br />

<div class="row">
    <div class="col sub_anexo">REGISTRO FOTOGRÁFICO - <?= $bimestre ?>º BIMESTRE</div>
    <div class="col sub_anexo"><?= toolErp::n_pessoa($id_pessoa) ?></div>
</div>
<br>
<br>

<?php
if (!empty($fotos)) {
    foreach ($fotos as $v) {
        ?>
            <div style="height: 90vh; width: 90vw;">
            <table class="table table-bordered border" align="center" style="height: 90vh; width: 90vw; break-inside: avoid;">
                <tr>
                    <td colspan="3" style="text-align: center; font-weight: bold">
                        <?= $v['n_foto'] ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center;">
                        <?php
                        if (file_exists(ABSPATH . '/pub/fotoApd/' . $v['link'])) {
                            ?>
                                <img style="max-height: 85vh; max-width: 85vw;" src="<?= HOME_URI . '/pub/fotoApd/' . $v['link'] ?>" alt="foto"/>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" >
                        <?= data::converteBr($v['dt_foto']) ?><?= !empty($v['fk_id_pessoa_prof']) ? " - Autor: ".toolErp::n_pessoa($v['fk_id_pessoa_prof']) : "" ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <?= $v['descricao'] ?>
                    </td>
                </tr>
            </table>
            </div>
            <br /><br />
        
        <?php
    }
}else{
echo '<span class="corpoMensagem" style="padding-left: 15px;"><strong>Sem Registro </strong></span>';
} ?>

<br>
</div>
<script type="text/javascript">
    <?php 
    if ($id_pl == $id_pl_atual) {?>
        window.onload = function() {
             this.print();
        }
       <?php 
    }?>
</script>
