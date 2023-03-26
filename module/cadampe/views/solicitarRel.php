<?php
if (!defined('ABSPATH'))
    exit;

if (empty($pedido)) { 
    echo '<font color="red">Em atualização. Por favor, aguarde.</font>';
    ?>
    <script>
        window.open('<?= HOME_URI ?>/cadampe/solicitarList', '_parent');
    </script>
    <?php
    exit;
}
?>
<style>
    .dado {
        margin-left: 23px;
        width: 8%;
    }

    .valor{
        font-weight: normal;
    }
    .wpp{
        padding: 10px !important;
        font-weight: bold;
    }
</style>

<div class="body">
    <?php if ($sem_categoria == 1) {?>
        <div class="alert alert-warning" style="padding: 10px;">
            Pode haver turmas ou professor não alocados. Confirme as informações. 
        </div>
    <?php      
    } ?>

    <?php if (!empty($id_pessoa_prof)) {?>
        <div class="fieldTop">
             <div class="row" style="margin-top:10px;">
                <div class="col" style="color: red;">
                    Protocolo:  <?= $id_pedido ?>
                </div>
                <div class="col" style="color: red;">
                    Situação: <?= $n_status['n_status'] ?>
                </div>
            </div>
        </div>
       <?php      
    } ?>

    <div class="border" style="margin:30px">

        <label for="" style="font-size:large">Solicitante:</label>

        <div class="row" style="margin-top:10px;">
            <div class="col">
                <label class="dado">Nome: </label>
                <label class="valor"><?= $pedido[0]['solicitante'] ?> </label>
            </div>
        </div>

        <div class="row" style="margin-top:10px;">
            <div class="col">
                <label class="dado">Escola:</label>
                <label class="valor"><?= $pedido[0]['n_inst'] ?></label>
            </div>
        </div>

        <div class="row" style="margin-top:10px;">
            <div class="col">
                <label class="dado">Endereço:</label>
                <label class="valor"><?= $pedido[0]['logradouro'] ?>, <?= $pedido[0]['num'] ?></label>
            </div>
        </div>

        <div class="row" style="margin-top:10px;">
            <div class="col">
                <label class="dado">Telefone:</label>
                <label class="valor"><?= $pedido[0]['tel1'] ?></label>
            </div>
        </div>
        <br>
        <br>

        <label for="" style="font-size:large">Solicitação:</label>

        <div class="row" style="margin-top:10px;">
            <div class="col">
                <label class="dado">Professor:</label> 
                <label class="valor"><?= $pedido[0]['professor'] ?></label>
            </div>
        <div class="row" style="margin-top:10px;">
            <div class="col">
                <label class="dado">Motivo:</label>
                <label class="valor"><?= $pedido[0]['n_motivo'] ?></label>
            </div>
        </div>
        <div class="row" style="margin-top:10px;">
            <div class="col">
                <label class="dado">Dias:</label>
                <label class="valor">De <?= data::converteBr($pedido[0]['dt_inicio']) ?> a <?= data::converteBr($pedido[0]['dt_fim']) ?></label>
            </div>
        </div>
        <div class="row" style="margin-top:10px;">
            <div class="col">
                <label class="dado">Período:</label>
                <label class="valor"><?= $periodoProtocolo ?></label>
            </div>
        </div>
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
                        $turmas_ = [];
                        if (!empty($v['turmas'])) {
                            $turmas_ = $v['turmas'];          
                        }                
                        foreach ($turmas_ as $d) { ?>  
                         <tr>
                            <td  style="vertical-align:middle"><?= $d['n_turma'] ?></td>
                                <?php
                            foreach ($dias as $b => $bb) { ?>  
                                <td align="center" <?= (empty($d['aulas'][$b])) ? '' : $cor;?>>
                                    <?php
                                    if (empty($d['aulas'])){
                                        echo "<td colspan='5'>Turma com Horário vazio</td>";
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
        require_once ABSPATH . "/module/cadampe/views/_def/mensagens.php";

        if (toolErp::id_nilvel() == 8) {
        
            if (($status == 1) || ($status == 4)) { ?>

                <div class="row" style="margin-top: 5%;">
                    <div class="col text-center">
                        <form id='cancelar' target="_parent" action="<?= HOME_URI ?>/cadampe/solicitarList" method="POST">
                            <?=
                            formErp::hidden([
                                '1[id_cadampe_pedido]' => $id_pedido,
                                'id_cadampe_pedido' => $id_pedido,
                                '1[fk_id_status]' => 2,
                                'log_cancel' => 1,
                                'closeModal' => isset($_POST['last_id']) //se for um insert na tabela pdido, atualiza a pagina solicitarList
                            ])
                            . formErp::hiddenToken('cadampe_pedido', 'ireplace',null,null,1)
                            . formErp::button('Cancelar Solicitação',null,'cancela()','btn btn-danger');
                            ?>
                        </form>            
                    </div>
                     <div class="col text-center">
                         <form id='fechar' target="_parent" action="<?= HOME_URI ?>/cadampe/solicitarList" method="POST">
                            
                            <?= formErp::button('Concluído',null,'fechaModal()','btn btn-success'); ?>
                        </form>
                    </div>
                </div>
                <?php
            } 
        } ?>
    </div>
</div>

<script>
    function cancela() {
        if (confirm("Cancelar esta Solicitação? ")) {
            document.getElementById('cancelar').submit();
        }
    }

    function fechaModal(){
        $('#myModal').modal('hide');
        document.getElementById("fechar").submit();
    }
</script>