<?php
if (!defined('ABSPATH'))
    exit;
?>
<style>
    .valor{
        font-weight: normal;
    }

    .mensagens {margin: auto 5%;}
    .mensagens .mensagem {
        border: 1px solid #e1e1d1;
        box-shadow: #e1e1d1 5px 5px 14px;
        margin: 20px auto;
        padding: 10px;
        min-height: 80px;
    }
    .mensagens .nomePessoa {text-transform: lowercase;}
    .mensagens .nomePessoa:first-letter { text-transform: uppercase; }
    .mensagens .nomePessoa span { 
        color: #888;
        font-weight: normal;
        font-size: 80%;
    }

    .mensagens .dataMensagem { 
        float: right;
        font-weight: normal;
        color: #888;
        font-size: 80%;
    }
    .mensagens .corpoMensagem {
        display: block;
        margin-top: 10px;
        font-weight: normal;        
        white-space: pre-wrap;
    }
    .mensagens .mensagemLinha-0{border-left: 5px solid #7ed8f5;}
    .mensagens .mensagemLinha-1{border-left: 5px solid #f3b4ef;}
    .mensagens .mensagemLinha-2{border-left: 5px solid #f6866f;}
    .mensagens .mensagemLinha-3{border-left: 5px solid #f9ca6e;}
    .mensagens .mensagemLinha-4{border-left: 5px solid #906ef9;}
    .mensagens .mensagemLinha-5{border-left: 5px solid #6ef972;}
    .mensagens .mensagemLinha-6{border-left: 5px solid #f76ef9;}
</style>

<div class="body">

    <div class="fieldTop">
        PROTOCOLO: <?= $id_pedido ?>
        <br>
        <br>
        Solicitação em Aberto
    </div>

    <div class="border" style="margin:30px">
        <div class="row" style="margin-top:10px;">
            <div class="col">
                <label for="" style="font-size:large">Solicitante:</label>
                <label class="valor"><?= $pedido[0]['n_inst'] ?> </label>

            </div>
            <div class="col">
                <label>Responsável:</label>
                <label class="valor"> <?= $pedido[0]['solicitante'] ?></label>
            </div>
        </div>
        <div class="row" style="margin-top:10px;">
            <div class="col">
                <label>Endereço da Escola:</label>
                <label class="valor"><?= $pedido[0]['logradouro'] ?>, <?= $pedido[0]['num'] ?></label>
            </div>
            <div class="col">
                <label>Telefone:</label>
                <label class="valor"><?= $pedido[0]['tel1'] ?></label>
            </div>
        </div>
        <br>
        <br>

        <label for="" style="font-size:large">Solicitação:</label>

        <div class="row" style="margin-top:10px;">
            <div class="col">
                <label>Professor:</label> 
                <label class="valor"><?= $pedido[0]['professor'] ?></label>
            </div>
            <div class="col">
                <label>Período:</label>
                <label class="valor">De <?= data::converteBr($pedido[0]['dt_inicio']) ?> a <?= data::converteBr($pedido[0]['dt_fim']) ?></label>
            </div>           
        </div>
        <div class="row" style="margin-top:10px;">        
            <div class="col">
                <label>Motivo:</label>
                <label class="valor"><?= $pedido[0]['n_motivo'] ?></label>
            </div>
        </div>
        <br>
            <?php
        foreach ($fim_linha as $k => $v) {?>
            <div class="row" style="margin: 10px;">
                <div class="col-md-12">
                    <label class="valor">Solicito </label>
                    <label><?= $v['n_categoria'] ?></label> 
                    <label class="valor">para os horários vagos, conforme tabela abaixo:</label>
                </div>
            </div>

            <div class="row" style="margin: 10px;">
                <div class="col-md-12">
                    <table class="table table-bordered table-hover table-condensed table-responsive table-striped" cellpadding="2" style="width:100%;font-weight: normal;">
                        <tr>
                            <td width="40%"></td> 
                            <td align="center" classs="col-md-2">S</td>
                            <td align="center" classs="col-md-2">T</td>
                            <td align="center" classs="col-md-2">Q</td>
                            <td align="center" classs="col-md-2">Q</td>
                            <td align="center" classs="col-md-2">S</td>
                        </tr>
                            <?php                
                        foreach ($v['turmas'] as $d) { ?>  
                         <tr>
                            <td  style="vertical-align:middle"><?= $d['n_turma'] ?></td>
                                <?php
                            foreach ($dias as $b) { ?>  
                                <td align="center" <?= (empty($d['aulas'][$b])) ?: $cor;?>>
                                    <?php
                                    if (empty($d['aulas'])){
                                        echo "<td colspan='5'>Turma com Horário Vazio</td>";
                                        break;
                                    } else {
                                        echo (empty($d['aulas'][$b])) ? "" : implode("<br>" , $d['aulas'][$b] );
                                    } ?>
                                        
                                </td>
                                <?php
                            }?>
                        </tr>
                            <?php 
                        }?>
                    </table>
                </div>
            </div>
            <?php
        }

        if (!empty($mensagens)) {?> 
            <label for="" style="font-size:large">MENSAGENS:</label>
            <div class="row" style="margin-top:10px;">
                <div class="col mensagens">
                        <?php
                    foreach ($mensagens as $k => $v) {?>
                        <div class="mensagem mensagemLinha-<?= $v['cor'] ?>" >
                            <div>
                                <label class="nomePessoa"><?= $v['n_pessoa'] ?> <span>disse:</span></label>
                                <label class="dataMensagem"><?= $v['dt_mensagem'] ?></label>
                            </div>
                            <span class="corpoMensagem"><?= $v['mensagem'] ?> </span>
                        </div>
                        <?php
                    }?>
                   
                </div>
            </div>
            <br>
                <?php
        }?>
            <form id='mensagem' action="<?= HOME_URI ?>/cadampe/solicitarRelcall" method="POST">
                <div class="row"  style="margin: auto 5%;">
                    <div class="col-md-10">
                       <?php formErp::textarea('1[mensagem]', NULL, 'Mensagem', '38px') ?>
                    </div>
                    <div class="col-md-2">
                        <?=
                            formErp::hidden([
                                '1[fk_id_cadampe_pedido]' => $id_pedido,
                                'id_cadampe_pedido' => $id_pedido,
                                '1[fk_id_pessoa]' => $id_pessoa_mensagem,
                                'closeModal' => isset($_POST['last_id']),
                                'last_id_mensagem' => '1'
                            ])
                            . formErp::hiddenToken('cadampe_mensagem', 'ireplace',null,null,1)
                            . formErp::button('Enviar',null,null,'btn btn-info');
                            ?>
                    </div>
                </div>
            </form>

            <div class="row" style="margin-top: 5%;">
                <div class="col text-center">
                    <form id='status' target="_parent" action="<?= HOME_URI ?>/cadampe/solicitarRel" method="POST">
                        <div class="row">
                            <div class="col">
                                <?= formErp::selectDB('cadampe_status', '1[fk_id_status]', 'Estado',  $status, 1, ['activeNav' => 2]) ?>
                            </div>
                        </div>
                        <?=
                        formErp::hidden([
                            '1[id_cadampe_pedido]' => $id_pedido,
                            '1[status]' => 2,
                            'closeModal' => isset($_POST['last_id']) //se for um insert na tabela pdido, atualiza a pagina solicitarList
                        ])
                        . formErp::hiddenToken('cadampe_pedido', 'ireplace',null,null,1);
                        ?>
                    </form>            
                </div>
            </div>
    </div>
    <?php
    if(!empty($formCadampe)){
        report::simple($formCadampe);
    }?>
</div>

<script>
    function cancela() {
        if (confirm("Cancelar esta Solicitação? ")) {
            document.getElementById('status').submit();
        }
    }
</script>