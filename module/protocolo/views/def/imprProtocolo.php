<?php
if (!defined('ABSPATH'))
    exit;
$id_protocolo = filter_input(INPUT_POST, 'id_protocolo', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$sexo = toolErp::sexo_pessoa($id_pessoa);
if ($sexo == 'F') {
    $oa = 'a';
}else{
    $oa = 'o';
}
if (!empty($id_protocolo)) {
    $n_inst = escolas::n_inst(toolErp::id_inst());  
    $titulo = 'Encaminhamento para Atendimento em Sala AEE';  
}else{
    ?>
    <script>
        window.location.href = "<?php echo HOME_URI ?>/apd/index"
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
        <div class="col-md-12" style="font-weight: bold; text-align: center;">
            <p>
                <div style=" font-size: 24px"><?= str_replace('.', '', $titulo) ?></div>
                <br>
                <div style=" font-size: 20px">Protocolo <?= $id_protocolo ?></div>
            </p>
        </div>
    </div>
    <br><br>
    <div class="row">
        <div class="col-md-12">
            <p style=" font-size: 16px;">Informamos que <?= $oa ?> alun<?= $oa ?> <?= $n_pessoa ?>, RA <?= $id_pessoa ?> , foi encaminhad<?= $oa ?> para pedido de Atendimento em Sala de Educação Especial. Após deferimento da Secretaria de Educação de Barueri, a escola convocará o responsável via telefone.</p>
        </div>
    </div>
    <br>
    <br>
    <br>
    <table style="width: 100%; border-collapse: collapse;" border=0 cellspacing=0 cellpadding=2>
        <tr >
            <td  style="text-align:center;">
                ____________________________________________
            </td>
        </tr>
        <tr>
            <td  style="text-align:center;">
                Responsável pelas informações
            </td>
        </tr>
    </table>
    <br><br><br>
    <div class="row" style="text-align:right;">
        <div class="col tit_table">Barueri, <?= date("d") ?> de <?= data::mes(date("m")) ?>  de <?= date("Y") ?></div>
    </div>
</div>
<script type="text/javascript">
    window.onload = function() {
        this.print();
    }
</script>
