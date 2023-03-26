<?php
if (!defined('ABSPATH'))
    exit;
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'fk_id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_ciclo = filter_input(INPUT_POST, 'fk_id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'fk_id_disc', FILTER_SANITIZE_NUMBER_INT);
$msg_coord = filter_input(INPUT_POST, 'msg_coord', FILTER_SANITIZE_STRING);
$titulo = 'Cadastro de Projeto';
$id_pessoa = toolErp::id_pessoa();
$id_pf = filter_input(INPUT_POST, 'id_pf', FILTER_SANITIZE_NUMBER_INT);
$id_projeto = filter_input(INPUT_POST, 'id_projeto', FILTER_SANITIZE_NUMBER_INT);
$n_projeto = filter_input(INPUT_POST, 'n_projeto', FILTER_SANITIZE_STRING);
$checked_foto = 'checked';
$display_video = 'none';
if (!empty($id_pf)) {
    $dados = sql::get('profe_projeto_foto', '*', ['id_pf' => $id_pf], 'fetch');
    $temImagem = 1;
    if (!empty($dados['link'])) {
        $checked_foto = 'checked';
        $disabled_video = 'disabled';
        $display_video = 'none';
        $display_foto = 'true';
    } else {
        $checked_video = 'checked';
        $disabled_foto = 'disabled';
        $display_foto = 'none';
        $display_video = 'true';
    }
}
?>
<div class="body">
    <form action="<?= HOME_URI ?>/profe/projeto" target="_parent" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col">
                <?= formErp::input('1[n_pf]', 'Título', @$dados['n_pf']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::textarea('1[descr_pf]', @$dados['descr_pf'], 'Descrição') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-3">
                <?= formErp::input('1[dt_pf]', 'Data', (empty($dados['dt_pf']) ? date("Y-m-d") : $dados['dt_pf']), null, null, 'date') ?>
            </div>
        </div>
        <br /><br />
        <?php 
        if (!empty( $dados['link'])) {?>
            <div class="row">
                <div class="col" style="text-align:center;">
                    <img style="width: 100%;width:  430px;height: 215px;object-fit: cover;" src="<?= HOME_URI . '/pub/infantilProj/' . $dados['link'] ?>" alt="foto"/> 
                </div>
            </div>
           <?php 
        } elseif (!empty($dados['link_video'])) {?>
           <div class="row">
                <div class="col" style="text-align:center;">
                    <a  target="_blank" href="<?= $dados['link_video'] ?>">
                      <img style="width: 10%" src="<?= HOME_URI . '/includes/images/play.png' ?>" alt="foto"/>
                    </a>
                </div>
            </div>
            <?php 
        }else{?>
            <div class="row">
                <div class="col-md-4">
                    <span style='font-size: 15px; font-weight: bold'>Anexar Imagem</span>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <label for="_empresta1" style='font-size: 15px; color: black; cursor:pointer;'><input type="radio" <?= @$checked_foto ?> <?= @$disabled_foto ?> name="img" class="foto" id="_empresta1" /> Foto</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-check">
                        <label for="_empresta2" style='font-size: 15px; color: black; cursor:pointer;'><input  <?= @$checked_video ?> <?= @$disabled_video ?> name="img" type="radio"  class="video" id="_empresta2" /> Vídeo</label>
                    </div>
                </div>
            </div>
            <br /><br />
            <div class="row viewFoto" style="display: <?= @$display_foto ?>;">
            <div class="col text-center">
                <input id="foto" class="btn btn-primary" type="file" accept=".jpg,.jpeg,.png" name="arquivo" style="width: 80%" />
            </div>
            </div>
            <div class="row viewVideo" style="display: <?= @$display_video ?>;">
                <div class="col">
                    <?= formErp::input('1[link_video]', 'Link do Vídeo', @$dados['link_video'], 'id="video"') ?>
                </div>
            </div>
            <?php 
        }?>
        <div style="text-align: center; padding: 20px">
            <br /><br /><br /><br />
            <?=
            formErp::hidden([
                '1[fk_id_projeto]' => $id_projeto,
                '1[id_pf]' => $id_pf,
                'fk_id_projeto' => $id_projeto,
                '1[fk_id_pessoa]' => $id_pessoa,
                'n_projeto' => $n_projeto,
                'msg_coord' => $msg_coord,
                'activeNav' => 3,
                'fk_id_turma' => $id_turma,
                'fk_id_disc' => $id_disc,
                'fk_id_ciclo' => $id_ciclo,
                'n_turma' => $n_turma,
                'msg_coord' => $msg_coord,
                'id_inst' => $id_inst,
                'temImagem' => @$temImagem,
            ])
            . formErp::hiddenToken('projFotoSalvar')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
    <br /><br />
    <div class="viewVideo" style="text-align: center; display: none">
        <video style="width: 100%" controls>
            <source src="<?= HOME_URI ?>/pub/vd/gerar_videos.mp4" type="video/mp4">
        </video>
    </div>
</div>
<script type="text/javascript">
    jQuery(function ($) {
        $('.foto').click(function () {
            $('.viewFoto').show();
            $('.viewVideo').hide();
            $("#video").val("");
        });
        $('.video').click(function () {
            $('.viewVideo').show();
            $('.viewFoto').hide();
            $("#foto").val("");
        });
    });
</script>
