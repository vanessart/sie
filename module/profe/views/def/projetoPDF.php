<?php
if (!defined('ABSPATH'))
    exit;
$id_projeto = filter_input(INPUT_POST, 'id_projeto', FILTER_SANITIZE_NUMBER_INT);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_UNSAFE_RAW);
if (!empty($id_projeto)) {
    $reg = $model->getProjetoReg($id_projeto);
    $aval = $model->getProjetoAval($id_projeto);
    $fotos = $model->getProjetoFotos($id_projeto);
    $projHab = $model->getProjetoPDF($id_projeto);
    $projeto = sql::get('profe_projeto', 'fk_id_turma,id_projeto, n_projeto, dt_inicio, dt_fim, habilidade, justifica, situacao, recurso, resultado, fonte, autores, avaliacao, devolutiva, fk_id_projeto_status, coord_vizualizar , msg_coord', 'WHERE id_projeto =' . $id_projeto, 'fetch', 'left');
    $n_projeto = $projeto['n_projeto'];
    $titulo = 'Projeto: ' . $n_projeto;
    $id_status = $projeto['fk_id_projeto_status'];
    if (!empty($projeto['dt_fim'])) {
        $data = ' - '.dataErp::converteBr($projeto['dt_inicio']).' a '.dataErp::converteBr($projeto['dt_fim']);
    }else{
        $data = ' - '.dataErp::converteBr($projeto['dt_inicio']);
    }
    $n_inst = escolas::n_inst_turma($projeto['fk_id_turma']);
}else{
    ?>
    <script>
        window.location.href = "<?php echo HOME_URI ?>/profe/index"
    </script>
    <?php
}
?>
<style type="text/css">
     .input-group-text{
        display: none;
    }
    .mensagens {}
    .mensagens .mensagem {
        /*border: 1px solid #e1e1d1;*/
        margin: 10px auto;
        padding: 4px;
        /*min-height: 80px;*/
    }

    .nomePessoa {
        /*text-transform: capitalize;*/
        color: #888;
        font-weight: normal;
        font-size: 100%;
    }


    .descricaoCE {
        /*text-transform: capitalize;*/
        color: #888;
        font-weight: normal;
        font-size: 100%;
        padding: 8px;
    }

    .mensagens .dataMensagem { 
        font-weight: bold;
        color: #888;
        font-size: 100%;
    }
    .mensagens .corpoMensagem {
        /*display: block;
        margin-top: 10px;*/
        font-weight: normal;        
        white-space: pre-wrap !important;
        /*padding: 5px;*/
        /*word-break: break-all; /* webkit */*/
        word-wrap: normal;
        white-space: pre-wrap;
    }
    .mensagens .mensagemLinha-0{border-left: 5px solid #7ed8f5;}
    .mensagens .mensagemLinha-1{border-left: 5px solid #f3b4ef;}
    .mensagens .mensagemLinha-2{border-left: 5px solid #f6866f;}
    .mensagens .mensagemLinha-3{border-left: 5px solid #f9ca6e;}
    .mensagens .mensagemLinha-4{border-left: 5px solid #906ef9;}
    .mensagens .mensagemLinha-5{border-left: 5px solid #6ef972;}
    .mensagens .mensagemLinha-6{border-left: 5px solid #f76ef9;}
    .tituloBox{
        font-size: 17px;
        font-weight: bold;
        padding-top:20px;
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
    <?= toolErp::cabecalhoSimples($n_inst) ?>
    <br /><br />
    <div class="row">
        <div class="col-md-12" style="font-weight: bold; text-align: left;">
            <p>
                <div style=" font-size: 24px"><?= str_replace('.', '', $titulo) ?></div>
                <div style=" font-size: 20px"><?= $n_turma ?><?= @$data ?></div>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="font-weight: bold;">
            <p style=" font-size: 16px;">Autores: <?= $model->autores($projeto['autores']) ?></p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem mensagemLinha-0" >
                <div>
                    <p class="tituloBox box-0">JUSTIFICATIVA</p>
                    <?php if (!empty($projeto['justifica'])) { ?>

                        <span class="corpoMensagem"><?= $projeto['justifica'] ?> </span>
                        <?php
                    } else {
                        echo '<span class="corpoMensagem"><strong>Sem Registro </strong></span>';
                    }?>
                    <p class="tituloBox box-0">HABILIDADE</p>
                    <?php if (!empty($projeto['habilidade'])) { ?>

                        <span class="corpoMensagem"><?= $projeto['habilidade'] ?> </span>
                        <?php
                    }?>
                    <?php 
                    if (!empty($projHab)) { 
                        foreach ($projHab as $k => $v) { 
                            $id_ce = '';
                            $br = '<br>';
                            foreach ($v["hab"] as $kk => $vv) {?>
                                <span class="nomePessoa"><strong></strong><?= $vv["id_ce"]<>$id_ce ? $br .$vv["n_ce"] : ""?></span></span>
                                <br>
                                <span class="descricaoCE"> - <?= $vv["codigo"] ?> - <?= $vv["descricao"] ?></span>
                              <?php 
                              $id_ce =  $vv["id_ce"]; 
                            }
                        }
                    }else{
                        echo '<span class="corpoMensagem"><strong>Sem Registro </strong></span>';
                    }?>
                    <p class="tituloBox box-0">RECURSO</p>
                    <?php if (!empty(nl2br($projeto['recurso']))) { ?>

                        <span class="corpoMensagem"><?= $projeto['recurso'] ?> </span>
                        <?php
                    } else {
                        echo '<span class="corpoMensagem"><strong>Sem Registro </strong></span>';
                    }?>
                     <p class="tituloBox box-0">RESULTADO</p>
                    <?php if (!empty(nl2br($projeto['resultado']))) { ?>

                        <span class="corpoMensagem"><?= $projeto['resultado'] ?> </span>
                        <?php
                    } else {
                        echo '<span class="corpoMensagem"><strong>Sem Registro </strong></span>';
                    }?>
                     <p class="tituloBox box-0">FONTES DE PESQUISA</p>
                    <?php if (!empty($projeto['fonte'])) { ?>

                        <span class="corpoMensagem"><?= nl2br($projeto['fonte'] )?> </span>
                        <?php
                    } else {
                        echo '<span class="corpoMensagem"><strong>Sem Registro </strong></span>';
                    }?>
                </div>
            </div>
        </div>
    </div>
    <br>
    <p style="page-break-before: always;"></p>
    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem mensagemLinha-1" >
                <div>
                    <p class="tituloBox box-1">REGISTRO QUINZENAL</p>
                    <?php 
                    if (!empty($reg)) {  
                        foreach ($reg as $k => $v) { 
                            $data = "Professor não informou data" ;
                            if (!empty($v['dt_fim'])) {
                                $data = "De ". data::converteBr($v["dt_inicio"])." a ". data::converteBr($v["dt_fim"]);
                            }else{
                                $data = dataErp::converteBr($v['dt_inicio']);
                            }
                            ?>
                            <span class=" dataMensagem" ><?= @$data ?></span><br>
                            <?php 
                                $id_ce = '';
                                $br = '<br>';
                                foreach ($v["hab"] as $kk => $vv) {?>
                                    <?= $vv["id_ce"]<>$id_ce ? $br  : ""?>
                                    <span class="nomePessoa"><strong></strong><?= $vv["id_ce"]<>$id_ce ? $vv["n_ce"] : ""?></span></span>
                                    <br>
                                    <span class="descricaoCE"> - <?= $vv["codigo"] ?> - <?= $vv["descricao"] ?></span>
                                  <?php 
                                  $id_ce =  $vv["id_ce"]; 
                                }?>
                                <br><br>
                                <span class="corpoMensagem"><strong>Situação de Aprendizagem<?=  !empty($v["dt_inicio"]) ?  " por ".toolErp::n_pessoa($v["fk_id_pessoa"]) : "" ?> : </strong>
                                    <br><?=  nl2br($v["situacao"]) ?>
                                </span>
                                <br><br>
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
    <br>
    <p style="page-break-before: always;"></p>
    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem mensagemLinha-0" >
                <div>
                    <p class="tituloBox box-0">AVALIAÇÃO DIÁRIA</p>
                    <?php 
                    if (!empty($aval)) { 
                        $data = "Professor não informou data" ;
                        foreach ($aval as $k => $v) { 
                            if (!empty($v["dt_fim"])) {
                               $data = "De ". data::converteBr($v["dt_inicio"])." a ". data::converteBr($v["dt_fim"]);
                            }else{
                                $data = data::converteBr($v["dt_inicio"]);
                            }
                            ?>
                            <span class="dataMensagem"><?=  $data ?></span><br>
                            <span class="nomePessoa">Professor<?= concord::oa($v["id_pessoa"]) ?>: <?= $v["n_pessoa"] ?></span>
                             <br>
                            <span class="corpoMensagem"><strong>Avaliação: </strong><?= nl2br($v["situacao"]) ?> </span>
                            <br><br>
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
    <p style="page-break-before: always;"></p>
    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem mensagemLinha-2" >
                <div>
                    <p class="tituloBox box-2">AVALIAÇÃO FINAL</p>
                    <?php if (!empty($projeto['avaliacao'])) { ?>

                        <span class="corpoMensagem"><?= nl2br($projeto['avaliacao']) ?> </span>
                        <?php
                    } else {
                        echo '<span class="corpoMensagem"><strong>Sem Registro </strong></span>';
                    }?>
                </div>
            </div>
        </div>
    </div>

    <p style="page-break-before: always;"></p>
    <div class="row">
        <div class="col mensagens">
            <div class="mensagem mensagemLinha-3" >
                <div>
                    <p class="tituloBox box-3">REGISTRO DE IMAGENS</p>
                    <div class="row">
                        <?php
                        $count = 0;
                        if (!empty($fotos)) {?>
                            <div class="col" style="padding-left: 2%; padding-right:2%;">
                                <table class="table table-bordered border" align="center">
                                    <?php
                                    foreach ($fotos as $v) {?>
                                            <table class="table table-bordered border">
                                                <tr>
                                                    <td style="text-align: left; font-weight: bold">
                                                        <?= data::converteBr($v['dt_pf']) ?><?= !empty($v['n_pessoa']) ? " - Autor".concord::oa($v["fk_id_pessoa"]).": ".$v['n_pessoa'] : "" ?> - <?= $v['n_pf'] ?>
                                                    </td>
                                                </tr>
                                                <tr style="text-align: center;">
                                                    <td  style="text-align: center; ">
                                                         <?php
                                                        if (empty($v['link_video'])) {
                                                            if (file_exists(ABSPATH . '/pub/infantilProj/' . $v['link'])) {?>
                                                                <a onclick="$('#myModal').modal('show');$('.form-class').val('');" target="frame" href="<?= HOME_URI . '/pub/infantilProj/' . $v['link'] ?>">

                                                                    <img style=" max-height: 250px" src="<?= HOME_URI . '/pub/infantilProj/' . $v['link'] ?>" alt="foto"/>
                                                                </a>
                                                                <?php
                                                            }
                                                        }else{
                                                            ?>
                                                            Use a câmera do celular para acessar o link do video <br>
                                                            <img src="<?= HOME_URI ?>/app/code/php/qr_img.php?d=<?= urlencode($v['link_video']) ?>&.PNG" width="120" height="120"/>
                                                            <?php
                                                        }?>
                                                        
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <?= nl2br($v['descr_pf']) ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                        <?php 
                                        $count++;
                                        if ($count % 3 == 0) {?>
                                            </table>
                                            <p style="page-break-before: always;"></p>
                                            <table class="table table-bordered border" align="center">
                                            <?php
                                        }
                                    }?>
                                </table>
                            </div>
                            <?php
                        }else{
                        echo '<span class="corpoMensagem" style="padding-left: 15px;"><strong>Sem Registro </strong></span>';
                    } ?>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    window.onload = function() {
        this.print();
    }
</script>
