<?php
if (!defined('ABSPATH'))
    exit;

$id_projeto = filter_input(INPUT_POST, 'id_projeto', FILTER_SANITIZE_NUMBER_INT);
$n_projeto = filter_input(INPUT_POST, 'n_projeto', FILTER_SANITIZE_STRING);
$msg_coord = filter_input(INPUT_POST, 'msg_coord', FILTER_SANITIZE_STRING);
$autores = filter_input(INPUT_POST, 'autores', FILTER_SANITIZE_STRING);
$dataProjeto = filter_input(INPUT_POST, 'data', FILTER_SANITIZE_STRING);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
//$hidden = filter_input(INPUT_POST, 'hidden', FILTER_REQUIRE_ARRAY);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = toolErp::id_pessoa();

if (empty($msg_coord)) {
    $msg_coord = @$_POST[1]['msg_coord'];
}

$reg = $model->getProjetoReg($id_projeto);
$aval = $model->getProjetoAval($id_projeto);
$fotos = $model->getProjetoFotos($id_projeto);
?>
<style>
    .input-group-text{
        display: none;
    }
    .mensagens {}
    .mensagens .mensagem {
        border: 1px solid #e1e1d1;
        box-shadow: #e1e1d1 5px 5px 14px;
        margin: 10px auto;
        padding: 4px;
        /*min-height: 80px;*/
    }
/*    .mensagens .nomePessoa {text-transform: lowercase;}*/
    .mensagens .nomePessoa:first-letter { text-transform: uppercase; }
    .mensagens .nomePessoa span { 
        color: #888;
        font-weight: normal;
        font-size: 100%;
    }

    .mensagens .dataMensagem { 
        font-weight: bold;
        color: #888;
        font-size: 100%;
    }
    .mensagens .corpoMensagem {
        display: block;
        /*margin-top: 10px;*/
        font-weight: normal;        
        white-space: pre-wrap;
        padding: 5px;
    }
    .mensagens .mensagemLinha-0{border-left: 5px solid #7ed8f5;}
    .mensagens .mensagemLinha-1{border-left: 5px solid #f3b4ef;}
    .mensagens .mensagemLinha-2{border-left: 5px solid #f6866f;}
    .mensagens .mensagemLinha-3{border-left: 5px solid #f9ca6e;}
    .mensagens .mensagemLinha-4{border-left: 5px solid #906ef9;}
    .mensagens .mensagemLinha-5{border-left: 5px solid #6ef972;}
    .mensagens .mensagemLinha-6{border-left: 5px solid #f76ef9;}
    .esconde .input-group-text{ display: none; }
    .tituloBox{
        font-size: 17px;
        font-weight: bold;
    }
    .tituloBox.box-0{ color: #7ed8f5;}
    .tituloBox.box-1{ color: #f3b4ef;}
    .tituloBox.box-2{ color: #f6866f;}
    .tituloBox.box-3{ color: #f9ca6e;}
    .tituloBox.box-4{ color: #906ef9;}
    .tituloBox.box-5{ color: #6ef972;}
    .tituloBox.box-6{ color: #f76ef9;}
</style>

<div class="body">
<div class="alert alert-info" style="padding-top:  10px; padding-bottom: 0">
    <div class="row">
        <div class="col" style="font-weight: bold; text-align: center;">
            <p style=" font-size: 24px"><?=  str_replace('.', '', $n_projeto )?></p>
            <p style=" font-size: 20px"><?= $n_turma ?> - <?= $dataProjeto ?></p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3" >
        <button style="width: 100%;" class="btn btn-outline-info" onclick="proj(<?= $id_projeto ?>)"> Escopo do Projeto e Devolutiva</button>
    </div>
</div>
<br><br><br>
<?php if (toolErp::id_nilvel() == 56) {?>
    <div class="row">
        <p class="tituloBox box-0">MENSAGEM AO PROFESSOR</p>
        <form method="POST" target='_parent'> 
            <div class="row">
                <div class="col">
                    <?= formErp::textarea('1[msg_coord]',$msg_coord, 'Mensagem para o Professor', '300px') ?>
                </div>
            </div>
            <br>
            <div class="row" style="text-align:center;">
                <div class="col">
                    <?= formErp::hidden($hidden) ?>
                    <?=
                    formErp::hidden([
                        'activeNav' => 2,
                        '1[id_projeto]' => $id_projeto,
                    ])
                     . formErp::hiddenToken('profe_projeto', 'ireplace')
                    . formErp::button('Enviar');
                    ?>      
                </div>
            </div>
        </form>  
    </div>
    <?php 
}else { 
    toolErp::divAlert('warning', 'Mensagem do Coordenador ao Professor: '.$msg_coord);
}?>
<br>      
<div class="row">
    <div class="col-md-12 mensagens">
        <div class="mensagem mensagemLinha-1" >
            <div>
                <p class="tituloBox box-1">REGISTRO QUINZENAL</p>
                <?php 
                if (!empty($reg)) {  
                    foreach ($reg as $k => $v) { ?>
                        <label class="dataMensagem"><?= ($v["dt_inicio"] != '0000-00-00') ? data::converteBr($v["dt_inicio"]) : data::converteBr($v["dt_update"]) ?> <?= ($v["dt_inicio"] != '0000-00-00') ? "a ".data::converteBr($v["dt_fim"]) : "" ?></label><br>
                        <?php 
                        foreach ($v["hab"] as $kk => $vv) {?>
                            <label class="nomePessoa"><span><?= $vv["codigo"] ?> - <?= $vv["descricao"] ?></span></label>
                          <?php   
                        }?>
                        <span class="corpoMensagem"><strong>Situação de Aprendizagem<?=  !empty($v["dt_inicio"]) ?  " por ".toolErp::n_pessoa($v["fk_id_pessoa"]) : "" ?> : </strong><?= $v["situacao"] ?> </span><br>
                        <?php   
                    }
                }else{
                    echo '<span class="corpoMensagem"><strong>Sem Registro </strong></span>';
                } ?>
            </div>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12 mensagens">
        <div class="mensagem mensagemLinha-0" >
            <div>
                <p class="tituloBox box-0">AVALIAÇÃO DIÁRIA</p>
                <?php 
                if (!empty($aval)) { 
                    $data = "Professor nao inseriu a data" ;
                    foreach ($aval as $k => $v) { 
                        if (!empty($v["dt_fim"])) {
                           $data = "De ". data::converteBr($v["dt_inicio"])." a ". data::converteBr($v["dt_fim"]);
                        }else{
                            $data = $v["dt_inicio"];
                        }
                        ?>

                        <label class="dataMensagem"><?=  $data ?></label><br>
                        <label class="nomePessoa"><span>Professor<?= concord::oa($v["id_pessoa"]) ?>: <?= $v["n_pessoa"] ?></span></label>
                        <span class="corpoMensagem"><strong>Avaliação: </strong><?= $v["situacao"] ?> </span><br>
                        <?php
                    }   
                }else{
                    echo '<span class="corpoMensagem"><strong>Sem Registro </strong></span>';
                } ?>
            </div>
        </div>
    </div>
</div>
<br>

<div class="row">
    <div class="col-md-12 mensagens">
        <div class="mensagem mensagemLinha-3" >
            <div>
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
                                            <?= data::converteBr($v['dt_pf']) ?><?= !empty($v['fk_id_pessoa']) ? " - Autor: ".toolErp::n_pessoa($v['fk_id_pessoa']) : "" ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-align: center;">
                                            <?php
                                            if (file_exists(ABSPATH . '/pub/infantilProj/' . $v['link'])) {
                                                ?>
                                                <a onclick="$('#myModal').modal('show');$('.form-class').val('');" target="frame" href="<?= HOME_URI . '/pub/infantilProj/' . $v['link'] ?>">

                                                    <img style="width: 100%; max-height: 280px;" src="<?= HOME_URI . '/pub/infantilProj/' . $v['link'] ?>" alt="foto"/>
                                                </a>
                                                <?php
                                            }
                                            ?>
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
<br>
</div>

<form id="form" target="frame" method="POST">
    <input type="hidden" name="id_projeto" id="id_projeto" value="" />
    <input type="hidden" name="id_turma" id="id_projeto" value="<?= $id_turma ?>" />
    <input type="hidden" name="data" id="data" value="<?= $dataProjeto ?>" />
    <input type="hidden" name="n_turma" id="n_turma" value="<?= $n_turma ?>" />
    <input type="hidden" name="autores" id="autores" value="<?= $autores ?>" />
    <input type="hidden" name="autores" id="autores" value="<?= $autores ?>" />
    <input type="hidden" name="fk_id_ciclo" id="fk_id_ciclo" value="<?= $id_ciclo ?>" />
    <input type="hidden" name="fk_id_disc" id="fk_id_disc" value="<?= $id_disc ?>" />
    <input type="hidden" name="msg_coord" id="msg_coord" value="<?= $msg_coord ?>" />
    <?php foreach ($hidden as $key => $value) {?>
       <input type="hidden" name="hidden[<?= $key ?>]" id="hidden" value="<?= $value ?>" />
       <?php
    } ?>
    
</form>

<?php toolErp::modalInicio(); ?>
<iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
<?php toolErp::modalFim(); ?>

<script>
    function proj(id) {
        document.getElementById("id_projeto").value = id;
        document.getElementById("form").action = '<?= HOME_URI ?>/profe/def/projetoCoord.php';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }

    function aval(id) {
        document.getElementById("id_projeto").value = id;
        document.getElementById("form").action = '<?= HOME_URI ?>/profe/def/projetoAval.php';
        document.getElementById("form").submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>

    
