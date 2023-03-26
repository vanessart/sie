<?php
if (!defined('ABSPATH'))
    exit;

$id_situacao = filter_input(INPUT_POST, 'id_situacao', FILTER_SANITIZE_NUMBER_INT);
$id_serial = filter_input(INPUT_POST, 'id_serial', FILTER_SANITIZE_STRING);
$hidden = [
'id_serial' => $id_serial,
'id_situacao' => $id_situacao,
//'id_equipamento' => $id_equipamento,
];
$historico = $model->historicoGet(null,$id_serial);
$gerente = $model->gerente();
$action = 'ocorrencia';
$texto_dan = "";
$equipamento = sql::get(['recurso_equipamento','recurso_serial'], 'n_serial,n_equipamento,id_equipamento, recurso_serial.fk_id_inst', ['id_serial' => $id_serial], 'fetch');
$itens = $model->itensGet($equipamento['id_equipamento'],null);
$n_inst = sql::get('instancia', 'n_inst', ['id_inst' => $equipamento['fk_id_inst'] ], 'fetch');
if ($gerente==1) {
    $situacao = sql::idNome('recurso_situacao', 'WHERE id_situacao NOT IN (3,2,5,8)');
}else{
    $situacao = sql::idNome('recurso_situacao', 'WHERE id_situacao NOT IN (3,2,5,8,7)');
}
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
                            <?= $equipamento['n_equipamento'] ?>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div  class="col">
                           <label class="dataMensagem">Número de Série:</label> <label class="nomePessoa"><?= $equipamento['n_serial'] ?></label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div  class="col">
                            <label class="dataMensagem">Itens que acompanham o equipamento:</label> <label class="nomePessoa"><?= $itens ?></label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div  class="col">
                            <label class="dataMensagem">Local do Objeto:</label> <label class="nomePessoa"><?= $n_inst['n_inst'] ?></label>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
    <br>
<div class="row">
    <div class="col">
        <?= formErp::select('id_situacao', $situacao, 'Situação', @$id_situacao, 1, $hidden, ' required ') ?>
    </div>
</div>
<br>
<?php
$texto_itens = ".";
if (($id_situacao == 7 || $id_situacao == 3) && $gerente == 1) {
    $btn = "Registrar Ocorrência e enviar para Manutenção";
}else if ($id_situacao == 1){
    $btn = "Registrar Ocorrência";

   
    if (!empty($$itens)) {
        $texto_itens = " e contendo todos os itens: ".$itens.".";
    }
    ?>
     <div class="alert alert-danger" style="padding-top:  10px; padding-bottom: 0">
        <div class="row" style="padding-bottom: 15px;">
            <div class="col" style="font-weight: bold; text-align: center;">
                Antes de finalizar, certifique-se de que o equipamento esteja em perfeitas condições<?= $texto_itens ?> Caso contrário, utilize outra opção no campo "Situação" e registre a ocorrência.
            </div>
        </div>
    </div>
    <?php
}else{
    $btn = "Registrar Ocorrência";
}?>
<form id="salvar" enctype="multipart/form-data" target="_parent" action="<?= HOME_URI ?>/recurso/<?= $action ?>" method="POST">
   <div class="row">
        <div class="col">
            <?= formErp::textarea('1[obs]', @$movimentacao['obs'], 'Ocorrência') ?>
        </div>
    </div>
    <br>
    <div class="border">
        <div style="text-align: center; padding: 8px">
            Uploads
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::input('n_doc', 'Documento') ?>
            </div>
            <div class="col">
                <?= formErp::input('arquivo', 'Upload', null, null, null, 'file') ?>
            </div>
        </div>
        <br />
    </div>
    <br /><br />
    <div class="row">
        <div class="col text-center">
            <?php
                 echo formErp::hiddenToken('ocorrenciaSalvar')
                . formErp::hidden([
                    '1[fk_id_pessoa_aloca]' => toolErp::id_pessoa(),
                    '1[fk_id_situacao]' => @$id_situacao,
                    '1[fk_id_serial]' => @$id_serial,
                    '1[fk_id_equipamento]' => @$id_equipamento,
                    '1[fk_id_inst]' => @$equipamento['fk_id_inst'],
                    'id_inst' => @$equipamento['fk_id_inst'],
                ])
                . formErp::button($btn,null,'salvar()');  
            ?>
        </div>
    </div>
</form>
<br><br>
<div class="fieldTop">
    Histórico de Movimentação
</div>
<?php 
if (!empty($historico)) {
    report::simple($historico);
}else{?>
    <br><br>
     <div class="alert alert-warning" style="padding-top:  10px; padding-bottom: 0">
        <div class="row" style="padding-bottom: 15px;">
            <div class="col" style="font-weight: bold; text-align: center;">
                Não há movimentações para este Objeto.
            </div>
        </div>
    </div>
    <?php
} ?>

<script type="text/javascript">
    function salvar(){ 
        situacao = document.getElementsByName('id_situacao')[0].value;
        if(!situacao){
            alert("Informe a situação do Equipamento.");
            return false;
        }
        document.getElementById('salvar').submit();
    }
</script>
