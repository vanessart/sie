<style>
    ._borda-msg{
        border: 1px solid transparent;
        border-radius: 0.25rem;
        border-color: #0dcaf0;
        padding:  10px;
        margin-bottom: 10px;
        box-shadow: #e1e1d1 5px 5px 14px;
    }
    .mensagens {}
    .mensagens .mensagem {
        border: 1px solid #e1e1d1;
        border-radius: 0.60rem;
        box-shadow: #e1e1d1 1px 1px 4px;
        margin: 10px auto;
        padding: 4px;
        /*min-height: 80px;*/
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
        /*margin-top: 10px;*/
        font-weight: normal;        
        /*white-space: pre-wrap;*/
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
</style>
<div class="_borda-msg" style="">
<label style="font-size:large;margin-top: 20px;"><?= !empty($titulo)?$titulo:'MENSAGENS:'?></label>
<br><br>
    <?php
    if (!empty($arrayMensagem)) {?> 
        <div class="row">
            <div class="col-md-12 mensagens">
                <?php
                foreach ($arrayMensagem as $k => $v) {?>
                    <div class="mensagem mensagemLinha-<?= $v['cor'] ?>" >
                        <div>
                            <label class="nomePessoa"><span><?= ($v['fk_id_pessoa'] == $id_pessoa) ? "Você" : $v['n_pessoa'] ?> disse:</span></label>
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
    <form id='_mensagem' method="POST">
        <div class="row" id="mensss" >
            <div class="col esconde">
               <div class="input-group">
                <textarea style="height:100px" id="_msg" name="1[mensagem]" class="form-control" aria-label="With textarea" placeholder= "Tecle enter para enviar a Mensagem"></textarea>
                <?php 
                if (!empty($hidden)) {
                    echo formErp::hidden($hidden);
                }?>
                <?=
                formErp::hidden([
                    '1[fk_id_chat]' => $id_chat,
                    '1[fk_id_pessoa_aluno]' => $id_pessoa_aluno,
                    '1[fk_id_pessoa]' => $id_pessoa,
                ])
                . formErp::hiddenToken('ge_mensagem', 'ireplace',null,null,1);
                ?>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
const inputEle = document.getElementById('_msg');
    inputEle.addEventListener('keyup', function(e){
        var key = e.which || e.keyCode;
        if (key == 13) { 
            document.getElementById('_mensagem').submit();
        }
    });
</script>