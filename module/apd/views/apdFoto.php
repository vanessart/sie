<?php
if (!defined('ABSPATH'))
    exit;

$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$bimestre = filter_input(INPUT_POST, 'bimestre', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa_prof = toolErp::id_pessoa();
$id_foto = filter_input(INPUT_POST, 'id_foto', FILTER_SANITIZE_NUMBER_INT);
$fotos = $model->getApdFotos($id_turma, $id_pessoa, $bimestre);
$checked_foto = 'checked';
$display_video = 'none';
    $display_foto = 'true';
if ($id_foto) {
    $dados = sql::get('apd_foto', '*', ['id_foto' => $id_foto], 'fetch');
    echo $id_foto;

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

<style type="text/css">
    .mensagens {}
    .mensagens .mensagem {
        border: 1px solid #e1e1d1;
        box-shadow: #e1e1d1 5px 5px 14px;
       /* margin: 10px auto;*/
        padding: 4px;
        /*min-height: 80px;*/
    }
    .mensagens .nomePessoa {text-transform: lowercase;}
    .mensagens .nomePessoa:first-letter { text-transform: uppercase; }
    .mensagens .nomePessoa { 
        color: #888;
        font-weight: normal;
        font-size: 100%;
    }

    .mensagens .dataMensagem { 
        font-weight: bold;
        color: #888;
        font-size: 18px;
    }

    .mensagens .tituloHab{
        font-weight: bold;
        color: #7ed8f5;
        font-size: 100%; 
    }
    .mensagens .corpoMensagem {
        display: block;
        margin-bottom: 10px;
        font-weight: normal;        
        white-space: pre-wrap;
        padding: 5px;
        color: #888;
    }
    .mensagens .mensagemLinha-0{border-left: 5px solid #7ed8f5;}
    .mensagens .mensagemLinha-1{border-left: 5px solid #f3b4ef;}
    .mensagens .mensagemLinha-3{border-left: 5px solid #f9ca6e;}
    .esconde .input-group-text{ display: none; }
    .tituloBox{
        font-size: 17px;
        font-weight: bold;
        text-align: center;
    }
    .tituloBox.box-0{ color: #7ed8f5;}
    .tituloBox.box-1{ color: #f3b4ef;}
    .tituloBox.box-3{ color: #f9ca6e;}
</style>

<div class="body">
    <form action="<?= HOME_URI ?>/apd/doc" target="_parent" method="POST" enctype="multipart/form-data">
        <div class="row">
             <div class="col-3">
                <?= formErp::input('1[dt_foto]', 'Data', (empty($dados['dt_foto']) ? date("Y-m-d") : $dados['dt_foto']), null, null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::input('1[n_foto]', 'Título', @$dados['n_foto'], 'required') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <div class="input-group">
                    <span class="input-group-text">Descrição</span>
                    <textarea style="height: 200px" name="1[descricao]" class="form-control" required placeholder="Descrever a situação didática e o desenvolvimento do aluno na atividade realizada" aria-label="With textarea"><?= @$dados['descricao'] ?></textarea>
                </div>
                <?php //formErp::textarea('1[descricao]', @$dados['descricao'], 'Descrição', 'required placeholder="Descrever a situação didática e o desenvolvimento do aluno na atividade realizada"') ?>
            </div>
        </div>
        <br /><br />
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
                <input id="foto" class="btn btn-outline-primary" type="file" accept=".jpg,.jpeg,.png" name="arquivo" style="width: 80%" />
            </div>
        </div>
        <div class="row viewVideo" style="display: <?= @$display_video ?>;">
            <div class="col">
                <?= formErp::input('1[link_video]', 'Link do Vídeo', @$dados['link_video'], 'id="video"') ?>
            </div>
        </div>
        <div style="text-align: center; padding: 20px">
            
            <?=
            formErp::hidden([
                '1[id_foto]' => $id_foto,
                '1[fk_id_pessoa_prof]' => $id_pessoa_prof,
                '1[fk_id_turma_AEE]' => $id_turma,
                'id_turma' => $id_turma,
                '1[bimestre]' => $bimestre,
                '1[fk_id_pessoa]' => $id_pessoa,
            ])
            . formErp::hiddenToken('apdFotoSalvar')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
    <br /><br /><br />
    <div class="viewVideo" style="text-align: center; display: none">
        <video style="width: 50%" controls>
            <source src="<?= HOME_URI ?>/pub/vd/gerar_videos.mp4" type="video/mp4">
        </video>
    </div>
    <br /><br /><br />
    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem mensagemLinha-3" >
                <div>
                <div class="row">
                    <div class="col-3">
                        <button class="btn btn-outline-warning" onclick="impr()">IMPRIMIR</button>
                    </div>
                </div>
                <div class="row">
                    <p class="tituloBox box-3">REGISTRO FOTOGRÁFICO</p>
                        <?php
                        if (!empty($fotos)) {
                            foreach ($fotos as $v) {
                                ?>
                                <div class="col-4" style="padding-left: 2%; padding-right:2%;">
                                    <table class="table table-bordered border" style="height: 90%;">
                                        <tr>
                                            <td colspan="3" style="text-align: left; font-weight: bold">
                                                <?= data::converteBr($v['dt_foto']) ?><?= !empty($v['fk_id_pessoa_prof']) ? " - Autor: ".toolErp::n_pessoa($v['fk_id_pessoa_prof']) : "" ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="text-align: center;">
                                                <?php
                                                if (empty($v['link_video'])) {
                                                    if (file_exists(ABSPATH . '/pub/fotoApd/' . $v['link'])) {?>
                                                        <a onclick="$('#myModal').modal('show');$('.form-class').val('');" target="frame" href="<?= HOME_URI . '/pub/fotoApd/' . $v['link'] ?>">
                                                            <img style="width: 100%; max-height: 280px;" src="<?= HOME_URI . '/pub/fotoApd/' . $v['link'] ?>" alt="foto"/>
                                                        </a>
                                                        <?php
                                                    }
                                                }else{?>
                                                    <a  target="_blank" href="<?= $v['link_video'] ?>">
                                                        <img style="width: 50%" src="<?= HOME_URI . '/includes/images/play.png' ?>" alt="foto"/>
                                                    </a>
                                                    <?php
                                                }?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="text-align: center; font-weight: bold">
                                                <?= $v['n_foto'] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <?= $v['descricao'] ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center">
                                                <button style="width: 100%" onclick='foto(<?= $v['id_foto'] ?>)' type="button" class="btn btn-outline-info">
                                                    Editar
                                                </button>
                                            </td>
                                            <td style="text-align: center">
                                                <form id="excl<?= $v['id_foto'] ?>" method="POST">
                                                    <?=
                                                    formErp::hidden([
                                                        '1[id_foto]' => $v['id_foto'],
                                                        'id_pessoa' => $id_pessoa,
                                                        'id_turma' => $id_turma,
                                                        'bimestre' => $bimestre,
                                                    ])
                                                    . formErp::hiddenToken('apd_foto', 'delete')
                                                    ?>
                                                    <button style="width: 100%" class="btn btn-outline-danger" onclick="if (confirm('Excluir')) {
                                                                    excl<?= $v['id_foto'] ?>.submit()
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
                        }else{
                        echo '<span class="corpoMensagem" style="padding-left: 15px;"><strong>Sem Registro </strong></span>';
                    } ?>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

<form id="form" action="" method="POST">
    <input type="hidden" name="id_foto" id="id_foto" value="" />
    <?=
    formErp::hidden([
        'bimestre' => $bimestre,
        'id_turma' => $id_turma,
        'id_pessoa' => $id_pessoa,
    ]);
    ?>  
</form>

<form id="formPDF" method="POST" target="_blank" action="<?= HOME_URI ?>/apd/fotosPDF">
    <?=
    formErp::hidden([
        'bimestre' => $bimestre,
        'id_turma' => $id_turma,
        'id_pessoa' => $id_pessoa,
    ]);
    ?>   
</form>

<script>
    function edit(id) {
        if (id) {
            id_foto.value = id;
        } else {
            id_foto.value = '';
        }
        form.submit();
    }

    function foto(id_foto){
        document.getElementById("id_foto").value = id_foto;
        var titulo= document.getElementById('myModalLabel');
        //titulo.innerHTML  = n_pessoa;
        document.getElementById("form").action = '<?= HOME_URI ?>/apd/apdFoto';
        document.getElementById("form").submit();
    }
    function impr(bimestre){
        document.getElementById("formPDF").submit();
    }
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
