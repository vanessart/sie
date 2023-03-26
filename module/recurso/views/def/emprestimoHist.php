<?php
if (!defined('ABSPATH'))
    exit;
$id_move = filter_input(INPUT_POST, 'id_move', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$id_pessoa_rm = filter_input(INPUT_POST, 'id_pessoa_rm', FILTER_SANITIZE_NUMBER_INT);
$historico = $model->historicoGet($id_pessoa);
$movimentacao = $model->movimentacaoGet($id_move);
$itens = $model->itensGet(null,null,$id_move);
?>
<style type="text/css">
    .titulo_anexo{
        color: #888;
        font-weight: bold;
        text-align: center;
        font-size: 30px;
    }
    .sub_anexo{
        font-weight: bold;
        text-align: center;
    }
    .sub2_anexo{
        font-weight: bold;
        text-align: center;
        FONT-SIZE: 14px;

    }s
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
        font-size: 100%;
    }

    .tit_table{ 
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
    .mensagens .mensagemLinha-2{border-left: 5px solid #f9ca6e;}
    .esconde .input-group-text{ display: none; }
    .tituloBox{
        font-size: 17px;
        font-weight: bold;
        text-align: center;
        color: #7ed8f5;
    }
    .tituloBox.box-0{ color: #7ed8f5;}
    .tituloBox.box-1{ color: #f3b4ef;}
    .tituloBox.box-2{ color: #f9ca6e;}
</style>

<div class="body">
    <div class="row">
        <div  class="col">
            <div class=" mensagens">
                <div class="mensagem mensagemLinha-0" >
                    <div class="row">
                        <div  class="col tituloBox">
                            <?= $movimentacao['n_equipamento'] ?>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div  class="col">
                           <label class="dataMensagem">Número de Série:</label> <label class="nomePessoa"><?= $movimentacao['n_serial'] ?></label>
                        </div>
                        <div  class="col">
                           <label class="dataMensagem">Local do Empréstimo:</label> <label class="nomePessoa"><?= $movimentacao['n_inst'] ?></label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div  class="col">
                            <label class="dataMensagem">Período do Empréstimo:</label> <label class="nomePessoa"><?= dataErp::converteBr($movimentacao['dt_inicio']).' a '.dataErp::converteBr($movimentacao['dt_fim']) ?></label>
                        </div>
                        <div  class="col">
                           <label class="dataMensagem">Local de Devolução:</label> <label class="nomePessoa"><?= $movimentacao['local_devolve'] ?></label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div  class="col">
                            <label class="dataMensagem">Itens que acompanham o equipamento:</label> <label class="nomePessoa"><?= !empty($itens)?$itens:"" ?></label>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>

    <div class="fieldTop">
        Histórico de Empréstimos
    </div>
<?php 
if (!empty($historico)) {
    report::simple($historico);
} ?>

</div>