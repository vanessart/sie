<?php
if (!defined('ABSPATH'))
    exit;

$id_pessoa_aluno = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$bimestre = filter_input(INPUT_POST, 'bimestre', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_turma_AEE = filter_input(INPUT_POST, 'id_turma_AEE', FILTER_SANITIZE_NUMBER_INT);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$id_pdi = filter_input(INPUT_POST, 'id_pdi', FILTER_SANITIZE_NUMBER_INT);
$id_atend = filter_input(INPUT_POST, 'id_atend', FILTER_SANITIZE_NUMBER_INT);
$atalho = filter_input(INPUT_POST, 'atalho', FILTER_SANITIZE_NUMBER_INT);
$activeNav = filter_input(INPUT_POST, 'activeNav', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa_prof = toolErp::id_pessoa();

if (!empty($atalho)) {
    $action = HOME_URI."/apd/doc";
    $atend1 = $model->getAtendDesc($id_pdi,$bimestre,null);

    $bimestres = sql::get('ge_cursos', 'qt_letiva, un_letiva, atual_letiva', ['id_curso' => 1], 'fetch');
    if (!empty($bimestres)) {
        $bimestre = $bimestres['atual_letiva']; 
    }

    if (empty($bimestres['qt_letiva'])) {
        $letiva = [];
    } else {
        foreach (range(1, $bimestres['qt_letiva']) as $v) {
            $letiva[$v] = $v . 'º '.$bimestres['un_letiva'];
        }
    }
}else{
    $action = HOME_URI."/apd/pdi";
}
$presenca = "";
if (!empty($id_atend)) {  
    $atend = sql::get('apd_pdi_atend','acao,dt_atend,justifica,presenca', ['id_atend' => $id_atend], 'fetch');
    $anexos = sql::get('apd_pdi_atend_up', 'id_up,link,n_arquivo', 'WHERE apd_pdi_atend_up.fk_id_atend =' . $id_atend);
    $presenca = $atend['presenca'];
}

if (!empty($atend['dt_atend'])) {
    $data = $atend['dt_atend'];
}else{
    $data = date("Y-m-d");
}
?>
<style type="text/css">
     /* Esconde o input */
    input[type='file'] {
        display: none
    }
    /* Aparência que terá o seletor de arquivo */
    .labelSet {
        background-color: #3498db;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        margin: 0 auto;
        padding: 6px;
        width: 300px;
        text-align: center
    }
    .input-group-text
    {
    display: none;
    }

    .titulo { 
        color: #888;
        font-size: 16px;
        padding-bottom: 5px;
    }
    .tituloG { 
        font-weight: bold;
        color: #888;
        font-size: 18px;
        margin-bottom: 5px;
        text-align: center;
        padding: 10px;
        padding-bottom: 20px;
    }
    .info{
        margin-bottom: 5px;
    }
    textarea {height: 270px !important;}
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
    <div class="tituloG">
        TRABALHO REALIZADO COM O ALUNO DENTRO DA SALA DO AEE
    </div>

    <form id="formEnvia" target="_parent" action="<?= $action ?>" enctype="multipart/form-data" method="POST">
        <div class="row">
            <div class="col">
                <span class="titulo" > DATA DO ATENDIMENTO </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3"> 
                <?= formErp::input('1[dt_atend]', NULL, $data, ' required', null, 'date') ?>
            </div>
                <?php 
                 if (!empty($letiva)) {?>
                    <div class="col">
                        <?= formErp::select('1[atualLetiva]', $letiva, $bimestres['un_letiva'], $bimestre) ?>
                    </div>
                
                    <?php 
                }else{
                    echo formErp::hidden([
                        '1[atualLetiva]' => @$bimestre
                    ]);
            
                } ?>
            <div class="col">
                <span style='font-size: 15px; font-weight: bold'>O Aluno está presente?</span>
            </div>
            <div class="col">
                <div class="form-check">
                    <label for="_empresta1" style='font-size: 15px; color: black; cursor:pointer;'><input type="radio" name="1[presenca]" value="1" <?= (empty($id_atend)||$presenca==1) ? 'checked' : "" ?> class="presenca" id="_empresta1" /> Sim</label>
                </div>
            </div>
            <div class="col">
                <div class="form-check">
                    <label for="_empresta2" style='font-size: 15px; color: black; cursor:pointer;'><input type="radio" name="1[presenca]" value="0" <?= (!empty($id_atend)&&$presenca==0) ? 'checked' : "" ?> class="presenca" id="_empresta2" /> Não</label>
                </div>
            </div>
        </div>
        <br /><br>
        <div class="row viewFaltou" style="display: none;">
            <div class="row">
                <div class="col">
                    <span class="titulo"> JUSTIFICATIVA </span>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?= formErp::textarea('1[justifica]', @$atend['justifica']) ?>
                </div>
            </div>
        </div>
        <div class="row viewPresente"> 
            <div class="row">
                <div class="col">
                    <span class="titulo"> AÇÕES </span>
                    <?= toolErp::tooltip('Elencar as ações realizadas','40vh') ?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?= formErp::textarea('1[acao]', @$atend['acao']) ?>
                </div>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-4">
                <label class="labelSet" id="label" for='selecao-arquivo'>Anexar Documento</label>
                <input class="btn btn-outline-info" type="file" name="arquivo[]" value="" id='selecao-arquivo' multiple />
                <input type="hidden" name="up" id='up' value="0">
            </div>
            <div class="col">
                <div id="mostra-arquivo" class="titulo"></div>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col text-center">
                <?=
                formErp::hidden([
                    '1[fk_id_pdi]' => $id_pdi ,
                    '1[id_atend]' => @$id_atend ,
                    '1[fk_id_pessoa_prof]' => $id_pessoa_prof,
                    'id_pessoa' => $id_pessoa_aluno,
                    'id_turma' => $id_turma,
                    'id_turma_AEE' => $id_turma_AEE,
                    'n_pessoa' => $n_pessoa,
                    'bimestre' => $bimestre,
                    'id_pdi' => $id_pdi ,
                    'activeNav' => 3,

                ])
                . formErp::hiddenToken('PdiAtend')
                . formErp::button('Salvar');
                ?>            
            </div>
        </div>
    </form>
    <br><br>
    <div class="row">
        <div class="col col-form-label">
            <div><strong>ANEXOS</strong></div> 
            <?php if (empty($anexos)) { ?>
                Não há anexos
            <?php } else { ?>
                <table class="table">
                    <tr>
                        <th align="center" style="width:8%">Item</th>
                        <th>Nome</th>
                        <th colspan="2" width="10%">Anexo</th>
                    </tr>
                    <?php 
                    $sqlkey = formErp::token('apd_pdi_atend_up', 'delete');
                    foreach ($anexos as $key => $value) {
                        $hidden = [
                            '1[id_up]' => $value['id_up'],
                            'id_atend' => @$id_atend,
                            'fk_id_pdi' => $id_pdi ,
                            'id_atend' => @$id_atend ,
                            'fk_id_pessoa_prof' => $id_pessoa_prof,
                            'id_pessoa' => $id_pessoa_aluno,
                            'id_turma' => $id_turma,
                            'id_turma_AEE' => $id_turma_AEE,
                            'n_pessoa' => $n_pessoa,
                            'bimestre' => $bimestre,
                            'id_pdi' => $id_pdi ,
                            'atalho' => $atalho ,
                            'activeNav' => 3,
                        ];?> 
                    <tr>
                        <td align="center"><?= $key+1 ?></td>
                        <td><?= $value['n_arquivo'] ?></td>
                        <td>
                            <?= formErp::submit('&#x2718;', $sqlkey, $hidden ,null,null,'Deseja apagar o arquivo '.$value['n_arquivo'],'btn btn-outline-danger'); ?>
                        </td>
                        <td>
                            <?= formErp::submit('&#8681;', null, null, HOME_URI . '/pub/apd/' . $value['link'], 1,null, 'btn btn-outline-info');  ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            <?php } ?>
        </div>
    </div>
    <br><br>
    <?php
    if(!empty($atalho)){
        $model->ViewHistoricoAtend($atend1, "HISTÓRICO DE ATENDIMENTOS DO ".$bimestre."º BIMESTRE");
    }?>
</div>
<script type="text/javascript">
    $( document ).ready(function() {
        <?php 
        if (!empty($id_atend)&&$presenca==0){?>
            $('.viewPresente').hide();
            $('.viewFaltou').show();
            <?php
        }?>
    });
   
    jQuery(function($){
        $('.presenca').click(function(){
            if ($(this).val() == 1){
                document.getElementsByName('1[justifica]')[0].removeAttribute('required');
                $('.viewPresente').show();
                $('.viewFaltou').hide();
            } else {
                document.getElementsByName('1[justifica]')[0].setAttribute('required', '');
                $('.viewPresente').hide();
                $('.viewFaltou').show();
            }
        });

        $('#selecao-arquivo').change(function(){
            var input = document.getElementById("selecao-arquivo");
            var nome;
            if (input.files.length>0) {
                document.getElementById("up").value = 1;
                for (var i = 0 ; i < input.files.length; i++) {
                    if (nome) {
                        nome = input.files[i].name+'<br> '+nome;
                    }else{
                        nome = input.files[i].name;
                    }
                    
                } 
            }else{
                document.getElementById("up").value= 0;
                nome = "Nenhum Arquivo selecionado.";
            }
            
            htm = '<div>'+nome+'</div>';
            htm += '<div><a style="color:red;" href="javascript:void(0)" class="removeUp" onClick="zremoveUp()">REMOVER</a></div>';
            $('#mostra-arquivo').html(htm);
        });

    });

    function zremoveUp(){
            document.getElementById("up").value= 0;
            $('#selecao-arquivo').val('');
            $('#mostra-arquivo').html('Nenhum arquivo selecionado');
        };
</script>