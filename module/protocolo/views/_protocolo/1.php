<?php
if (!defined('ABSPATH'))
    exit;
// 1=insert | 2=update | 0-nada
$insertUpdate = 1;
$existeAnexo = 0;
if (!empty($id_protocolo)) {
    $protocolo = $model->getProtocolo($id_protocolo);

    $justificaGerente = sql::get('protocolo_status_pessoa', 'justifica', "WHERE fk_id_protocolo = $id_protocolo ORDER BY id_proto_status_pessoa DESC" , 'fetch');
    if (!empty($justificaGerente['justifica'])) {
       $msg = $justificaGerente['justifica'];
    }
    $anexos = sql::get('protocolo_up', 'id_up,link,n_up', ['fk_id_protocolo' => $id_protocolo]);
    $btnName = 'Atualizar Protocolo';
    $checked = 'checked disabled';
    $disabledAnexo = '';
    $id_status = (int)$protocolo['fk_id_status'];
    $insertUpdate = 2;
} else{
    $protocolo = null;
    $insertUpdate = 1;
    $btnName = 'Abrir Protocolo';
    $disabledAnexo = '';
    $checked = '';
}

switch ($id_status) {
    case 1:
    case 5:
        $disabledAnexo = 'disabled';
        $insertUpdate = 0;
        break;

    case 3:
        if (!empty($msg)) {
           echo toolErp::divAlert('danger',$msg); 
        }
        $insertUpdate = 2;
        $btnName = 'Enviar Documento';
        $disabledAnexo = '';
        $checked = '';
        break;
}

if ($id_pessoa) {
    $aluno = $model->alunoAeeGet($id_pessoa);
    $aluno_matriculadoAEE = $model->alunoAEE($id_pessoa);
}?>
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
    .titulo { 
        color: #888;
        font-size: 16px;
        padding-bottom: 5px;
    }
</style>
<?php 
if (!empty($aluno)) {
    if (!empty($protocolo) && !empty($protocolo['n_inst_aee']) ) { ?>
        <div class="alert alert-warning" style="padding-top: 10px; padding-bottom: 0">
            <div class="row" style="padding-bottom: 15px;">
                <div class="col">
                    <?php
                    $_OA = concord::oa($aluno['id_pessoa'], 1);
                    $_oa = concord::oa($aluno['id_pessoa']);
                    ?>
                   <?php echo $_OA ?> alun<?php echo $_oa ?> <?= $aluno['n_pessoa'] ?> foi encaminhad<?php echo $_oa ?> para o polo AEE <?= $protocolo['n_inst_aee'] ?> na turma <?= $protocolo['n_turma_aee'] ?>
                </div>
            </div>
        </div>
        <?php 
    } 
    if (!empty($aluno_matriculadoAEE)) {
       echo toolErp::divAlert('warning', 'Informação Importante: este aluno já está matriculado em uma Turma AEE'); 
    }?>
    <div class="alert alert-info" style="padding-top: 10px; padding-bottom: 0">
        <div class="row" style="padding-bottom: 15px;">
            <div class="col">
               <b>Alun<?php echo concord::oa($aluno['id_pessoa']) ?>:</b> <?= $aluno['n_pessoa'] ?>
            </div>
            <div class="col">
              <b>RSE:</b> <?= $aluno['id_pessoa'] ?>
            </div>
        </div>
        <div class="row" style="padding-bottom: 15px;">
            <div class="col">
               <b>Deficiência:</b> <?= $aluno['def'] ?>
            </div>
            <div class="col">
               <b>Turma:</b> <?= $aluno['n_turma'] ?> - <?= $aluno['n_inst'] ?>
            </div>
        </div>
    </div>
    <br><br>
    <?php
    if (!empty($id_protocolo)) {?> 
        <?= formErp::submit('Imprimir Protocolo', null, ['id_protocolo'=>$id_protocolo,'n_pessoa'=>$aluno['n_pessoa'],'id_pessoa'=>$aluno['id_pessoa']], HOME_URI . '/protocolo/def/imprProtocolo.php', 1,null, 'btn btn-outline-info');  ?>
        <?php 
    }
}?> 
<br><br>
<form id="form" enctype="multipart/form-data" action="<?= HOME_URI ?>/apd/protocolo" method="POST">
    <div class="card p-4">
    <div class="row">
        <div class="col">
            <?= formErp::selectDB('ge_necessidades_especiais', '1[fk_id_ne]', 'Deficiência', @$protocolo['fk_id_ne'],null,null,null,null,$disabledAnexo) ?>
        </div>
    
            <div class="col-4">
                <label class="labelSet" id="label" for='selecao-arquivo' style="font-size: 18px;height: 38px;">Anexar Documento</label>
                <input class="btn btn-outline-info" type="file" name="__arquivo" value="" id='selecao-arquivo' multiple <?= $disabledAnexo ?>/>
                <input type="hidden" name="up" id='up' value="0">
            </div>
            <div class="col">
                <input type="hidden" id="_file_names">
                <div id="_input_files"></div>
                <div id="mostra-arquivo" class="titulo"></div>
            </div>
        </div>
    <br><br>
    <?php if ($insertUpdate == 2 || $id_status == 3) { ?>
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
                        $sqlkey = formErp::token('protocolo_up', 'delete');
                        foreach ($anexos as $key => $value) {
                            $existeAnexo = 1;
                            $hiddenUp = [
                                '1[id_up]' => $value['id_up'],
                                'activeNav' => 1,
                            ];?>
                        <tr>
                            <td align="center"><?= $key+1 ?></td>
                            <td><?= $value['n_up'] ?></td>
                            <td>
                            <a href="<?= HOME_URI . '/pub/protocoloDoc/' . $value['link'] ?>" target="_blank" class="btn btn-outline-info">
                                &#8681;
                            </a>
                        </td>
                        </tr>
                        <?php } ?>
                    </table>
                <?php } ?>
            </div>
            <br><br>
        <?php } ?>
        <div class="row">
            <div><strong>DESCRIÇÃO</strong> (opcional)</div> 
            <br><br>
            <div class="col">
                <?php 
                if ($insertUpdate == 0) { 
                    echo $protocolo['descricao'];
                }else{
                    echo formErp::textarea('1[descricao]', @$protocolo['descricao']);
                }?> 
            </div> 
        </div>
        <br><br>
    </div>
</div>

    <br><br>
    <div class="card p-4">
    <div class="row">
        <div class="col">
            <span class="titulo"> <?= toolErp::n_pessoa() ?>, visando garantir a eficiência do processo é de extrema importância que as informações que você preencher estejam corretas e completas. Portanto confirme as seguintes exigências: </span>
        </div>
    </div>
    <br><br>
    <div class="row">
        <div class="col">
            <label class="container">
                <span class="titulo">Eu, <?= toolErp::n_pessoa() ?>, conferi que o laudo em anexo contém o carimbo do médico.</span>
                <input <?= $checked ?>  type="checkbox" id="check1" name="check1" value="1" onclick="checkBotao()" >
                <span class="checkmark"></span>
            </label> 
        </div>
    </div>
    <div class="row">
        <div class="col">
            <label class="container">
                <span class="titulo">Eu, <?= toolErp::n_pessoa() ?>, conferi que o laudo em anexo contém o CID.</span>
                <input <?= $checked ?>  type="checkbox" id="check2" name="check2" value="1" onclick="checkBotao()"  >
                <span class="checkmark"></span>
            </label> 
        </div>
    </div>
    <div class="row">
        <div class="col">
            <label class="container">
                <span class="titulo">Eu, <?= toolErp::n_pessoa() ?>, conferi que a deficiência informada no campo "Deficiência" é a mesma que consta no laudo em anexo.</span>
                <input <?= $checked ?> type="checkbox" id="check3" name="check3" value="1" onclick="checkBotao()" >
                <span class="checkmark"></span>
            </label> 
        </div>
    </div>
</div>
    <br><br>
    <div class="row">
        <div class="col text-center">
            <?=
            formErp::hidden([
                'id_area' => $id_area,
                '1[fk_id_area]' => $id_area,
                'id_pessoa' => $id_pessoa,
                '1[fk_id_pessoa]' => $id_pessoa,
                'id_protocolo' => $id_protocolo,
                '1[id_protocolo]' => $id_protocolo,
            ])
            . formErp::hiddenToken('protocoloCad');

            if ($insertUpdate != 0){ ?>
                <button id='botao' type="button" onclick="salvar()" class="btn btn-lg btn-success" disabled><?= $btnName ?></button>
            <?php } ?>
        </div>
    </div>
</form> 
<script>
let checkbox1 = document.getElementById('check1');
let checkbox2 = document.getElementById('check2');
let checkbox3 = document.getElementById('check3');

jQuery(function($){
    checkBotao();

    $('#selecao-arquivo').change(function(){
        var input = document.getElementById("selecao-arquivo");
        var nome, _file_names = document.getElementById('_file_names').value;
        if (input.files.length>0) {
            document.getElementById("up").value = 1;
            let exists = false;

            for (var i = 0 ; i < input.files.length; i++) {
                if (nome) {
                    nome = input.files[i].name+'<br> '+nome;
                }else{
                    nome = input.files[i].name;
                }
            }

            if (!exists) {
                let i = $("#_input_files input[type='file']").length;
                let d = $(this).clone();
                d.attr({'name': 'arquivo[]', 'id': '_f'+i});
                d.appendTo( $( "#_input_files" ) );;
            }
            document.getElementById("_file_names").value += nome.replace(/<br>/g, "|") +'|';
        } else {
            if (document.getElementById("_file_names").value.length == 0) {
                document.getElementById("up").value= 0;
                nome = "Nenhum Arquivo selecionado.";
                document.getElementById("_file_names").value = '';
            }
        }
        htm = '<div>'+document.getElementById("_file_names").value.replace(/\|/g, '<br>')+'</div>';
        htm += '<div><a style="color:red;" href="javascript:void(0)" class="removeUp" onClick="zremoveUp()">REMOVER</a></div>';
        $('#mostra-arquivo').html(htm);
    });
});

function zremoveUp(){
    document.getElementById("up").value= 0;
    document.getElementById("_file_names").value = '';
    document.getElementById("_input_files").innerHTML = '';
    $('#selecao-arquivo').val('');
    $('#mostra-arquivo').html('Nenhum arquivo selecionado');
};
function checkBotao(){
    resp = 1;
    if (checkbox1.checked != true) {
        resp = 0;
    }
    if(checkbox2.checked != true) {
         resp = 0;
    }
    if (checkbox3.checked != true) {
         resp = 0;
    }
    if (resp == 1) {
        document.getElementById('botao').disabled = false;
    }else{
        document.getElementById('botao').disabled = true;
    }
}
function salvar(){
    let fk_id_ne = document.getElementById('fk_id_ne_');
    let up = document.getElementById("up");
    resp = 1;
    if (checkbox1.checked != true) {
        resp = 0;
    }
    if(checkbox2.checked != true) {
         resp = 0;
    }
    if (checkbox3.checked != true) {
         resp = 0;
    }
    if (resp == 0) {
        alert("Para abrir o protocolo é preciso confirmar todas as exigencias.\n\nCheque todos os campos.");
        return false;
    }
    if (fk_id_ne.value == '') {
         alert('Para abrir o protocolo é preciso informar a Deficiência conforme consta no laudo do aluno.');
         return false;
    }
    <?php if (empty($id_protocolo) ) {?>
        if (up.value == 0) {
             alert('Para abrir o protocolo é preciso anexar o laudo do aluno.');
             return false;
        }
        <?php
    }?>
    document.getElementById('form').submit();
}
</script>