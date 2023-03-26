<?php
if (!defined('ABSPATH'))
    exit;
$escola = $model->getEscolaAluno($id_pessoa);
$form = quest::getForm(1,null,'protocolo_form_perguntas', 'protocolo_form_opcoes');
$dados_aluno = $model->getPessoa($id_pessoa);
$oa = concord::oa($id_pessoa);
$seu = concord::seu($id_pessoa);
if (!empty($id_protocolo)) {
    $id_status = $protocolo['fk_id_status'];
    if (!empty($protocolo['dt_resp_form1'])) {
        $data = $protocolo['dt_resp_form1']; 
    }else{
        $data = date('d/m/Y');
    }
}else{
    $data = date('d/m/Y');
}
if ($id_pessoa) {
    $aluno = $model->alunoAeeGet($id_pessoa);
}
if (!empty($dados_aluno)) {
   $idade = $model->idade($dados_aluno['dt_nasc']); 
}else{
    $idade = null;
}
if ($id_status <> 4 && $id_status <> 3) {
    $disabled = 'disabled checked';
}else{
    $disabled = '';
}
if ($id_status==3) {
    $justificaGerente = sql::get('protocolo_status_pessoa', 'justifica', "WHERE fk_id_protocolo = $id_protocolo ORDER BY id_proto_status_pessoa DESC" , 'fetch');
    if (!empty($justificaGerente['justifica'])) {
       $msg = $justificaGerente['justifica'];
    }
    if (!empty($msg)) {
       echo toolErp::divAlert('danger',$msg); 
    }
}
?>
<style type="text/css">
     /* Esconde o input */
    /*input[type='file'] {
        display: none
    }*/
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
<div class="body">
    <div class="fieldTop">
       ANEXO I- ENCAMINHAMENTO PARA A SALA DE ATENDIMENTO EDUCACIONAL ESPECIALIZADO - AEE – Ano <?= date('Y') ?>
    </div>
    <br />
    <?php
    $hora = substr($data, 11, 20);
    $data = dataErp::converteBr($data);
    if ($id_status <> 4 && $id_status <> 3) {
       echo toolErp::divAlert('warning', "Questionário referente ao Protocolo $n_protocolo<br><br>Assinado por: $pessoa_status<br><br>Data da assinatura: $data às $hora");
    }

    if (!empty($protocolo) && !empty($protocolo['n_inst_aee']) ) { ?>
    <div class="alert alert-warning" style="padding-top: 10px; padding-bottom: 0">
        <div class="row" style="padding-bottom: 15px;">
            <div class="col">
                <?php
                $_OA = concord::oa($id_pessoa, 1);
                $_oa = concord::oa($id_pessoa);
                ?>
               <?php echo $_OA ?> alun<?php echo $_oa ?> <?= $dados_aluno['n_pessoa'] ?> foi encaminhad<?php echo $_oa ?> para o polo AEE <?= $protocolo['n_inst_aee'] ?> na turma <?= $protocolo['n_turma_aee'] ?>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="alert alert-info" style="padding-top:  10px; padding-bottom: 0">
        <div class="row">
            <div  class="col col-sm">
                <label class="dataMensagem">Escola:</label> <label class="nomePessoa"><?= $escola ?></label>
            </div><div  class="col col-sm">
                <label class="dataMensagem">Fase/Ano:</label> <label class="nomePessoa"><?= $aluno['n_turma'] ?></label>
            </div>
            <div  class="col col-sm">
                <label class="dataMensagem">Data:</label> <label class="nomePessoa"><?= date('d/m/Y') ?></label>
            </div>
        </div>
        <br>
        <div class="row">
            <div  class="col col-sm">
                <label class="dataMensagem"><?= ng_professor::profeSalaAluno($id_pessoa,1) ?>:</label> <label class="nomePessoa"><?= ng_professor::profeSalaAluno($id_pessoa) ?></label>
            </div>
        </div>
        <br>
    </div>
    <div class="alert alert-info" style="padding-top:  10px; padding-bottom: 0">
        <div class="row">
            <div  class="col-8 col-sm-8">
               <label class="dataMensagem">Alun<?php echo concord::oa($id_pessoa) ?>:</label> <label class="nomePessoa"><?= $dados_aluno['n_pessoa'] ?></label>
            </div>
        </div>
        <br>
         <div class="row">
            <div  class="col">
               <label class="dataMensagem">Idade:</label> <label class="nomePessoa"><?= $idade ?></label>
            </div>
            <div  class="col">
                <label class="dataMensagem">Data de Nascimento:</label> <label class="nomePessoa"><?= dataErp::converteBr($dados_aluno['dt_nasc']) ?></label>
            </div>
            <div  class="col">
               <label class="dataMensagem">Naturalidade:</label> <label class="nomePessoa"><?= $dados_aluno['cidade_nasc'] ?> - <?= $dados_aluno['uf_nasc'] ?></label>
            </div>
        </div>
        <br>
        <div class="row">
            <div  class="col col-sm">
                <label class="dataMensagem">Nome dos pais:</label> <label class="nomePessoa"><?= $dados_aluno['pais'] ?></label>
            </div>
        </div>
        <br>
        <div class="row">
            <div  class="col col-sm">
                <label class="dataMensagem">Endereço:</label> <label class="nomePessoa"><?= $dados_aluno['endereco'] ?></label>
            </div>
            <div  class="col col-sm">
                <label class="dataMensagem">Telefone:</label> <label class="nomePessoa"><?= $dados_aluno['tel'] ?></label>
            </div>
        </div> 
        <br>
    </div>
    <br><br>
    <form id="form" action="<?= HOME_URI ?>/apd/protocolo" method="POST">
        <?php
       // if ($id_status == 0 || $id_status == 4 || $id_status == 3) {
            quest::getView($form,$id_pessoa,1,'protocolo_form_resposta',$id_protocolo);
        // }else{
        //     quest::getViewPDF($form,$id_protocolo,1,1,'protocolo_form_resposta'); 
        // }?>
        <br><br>
        <?php  
        if ($id_status == 4){?>
            <div class="row">
                <div class="col">
                    <label class="container">
                        <span class="titulo">Eu, <?= toolErp::n_pessoa() ?>, conferi que todas informações contidas nesse questionário foram coletadas juntamente com o responsável pel<?= $oa ?> Alun<?= $oa ?> <?= $dados_aluno['n_pessoa'] ?>.</span>
                        <input  type="checkbox" id="check1" name="check1" value="1" onclick="checkBotao()" <?= $disabled ?>>
                        <span class="checkmark"></span>
                    </label> 
                </div>
            </div> 
            <br>
            <div class="row">
                <div class="col">
                    <label class="container">
                        <span class="titulo">Eu, <?= toolErp::n_pessoa() ?>, estou ciente que ao clicar em "Assinar Questionário" estarei assinando esse documento de forma digital e enviando ao DEE para análise de deferimento do protocolo.</span>
                        <input  type="checkbox" id="check2" name="check2" value="1" onclick="checkBotao()" <?= $disabled ?>>
                        <span class="checkmark"></span>
                    </label> 
                </div>
            </div> 
            <br>
        <?php 
        }
        if ($id_status == 4 || $id_status == 3) { ?>  
            <div class="row" style="text-align: center">
                <div class="col">
                    <?=
                    formErp::hidden($hidden)
                    .formErp::hidden([
                        'id_form' => 1,
                        'activeNav' => 2,
                        'id_pessoa' => $id_pessoa,
                        '1[fk_id_protocolo]' => $id_protocolo,
                        'id_protocolo' => $id_protocolo,
                    ])
                    . formErp::hiddenToken('salvarForm');
                    ?> 
                    <button id='botao' type="button" onclick="salvar()" class="btn btn-lg btn-success" <?= $id_status <> 3 ? 'disabled' : '' ?>>Salvar</button> 
                </div>
            </div> 
            <?php 
        }?>      
    </form>
</div>
<script type="text/javascript">
    let checkbox1 = document.getElementById('check1');
    let checkbox2 = document.getElementById('check2');
    let input159 = document.getElementById('159');
    let input1 = document.getElementById('1');
    function checkBotao(){
        resp = 1;
        if (checkbox1.checked != true) {
            resp = 0;
        }
        if(checkbox2.checked != true) {
             resp = 0;
        }
        if (resp == 1) {
            document.getElementById('botao').disabled = false;
        }else{
            document.getElementById('botao').disabled = true;
        }
    }
    function salvar(){
        const perguntas = [];
        const ids_pergunta = [];
        $('.opcoes').each(function(v, k){
            id = $(this).data('id-pergunta');
            if(!perguntas[id]) {
                perguntas[id] = [];
                ids_pergunta.push(id);
            }
            perguntas[id].push($(this));
        });

        for (var i = 0; i < perguntas.length; i++) {
            if(perguntas[ids_pergunta[i]]){
                for (var j = 0; j < perguntas[ids_pergunta[i]].length; j++) { 
                    if (perguntas[ids_pergunta[i]][j][0].checked) {
                        break;
                    }
                    
                    if (j == perguntas[ids_pergunta[i]].length-1) {
                        item = $('.pergunta_'+ids_pergunta[i])
                        str = $(' div.col', item).text();
                        str = str.replace(/\s\s+/g, ' ');

                        alert("Ops... parece que você se esqueceu dessa pergunta:\n\n"+ str);
                        perguntas[ids_pergunta[i]][j][0].focus();
                        return false;
                    }
                }
            }
            
        }
        let checkbox2 = document.getElementById('2');
        let input4 = document.getElementById('4');

        if (input1.value == '') {
            alert("Por favor, informe com quem mora!");
            checkbox2.focus();
            return false;
        }
        if (input159.value == '') {
            alert("Por favor, informe o CID para prosseguir!");
            checkbox2.focus();
            return false;
        }
        
        if(checkbox2.checked == true && (input4.value == '')) {
            alert("Por favor, informe qual a medicação");
            checkbox2.focus();
            return false;
        }
        let checkbox5 = document.getElementById('5');
        let input7 = document.getElementById('7');
        if(checkbox5.checked == true && (input7.value == '')) {
            alert("Por favor, informe a data");
            checkbox5.focus();
            return false;
        }
        let checkbox17 = document.getElementById('17');
        let input160 = document.getElementById('160');
        if(checkbox17.checked == true && (input160.value == '')) {
            alert("Por favor, ao marcar 'Às vezes' é preciso justificar a resposta da pergunta 'Problemas de comportamento'");
            checkbox17.focus();
            return false;
        }
        let checkbox20 = document.getElementById('20');
        let input161 = document.getElementById('161');
        if(checkbox20.checked == true && (input161.value == '')) {
            alert("Por favor, ao marcar 'Às vezes' é preciso justificar a resposta da pergunta 'Bom rendimento escolar'");
            checkbox20.focus();
            return false;
        }
        let checkbox23 = document.getElementById('23');
        let input162 = document.getElementById('162');
        if(checkbox23.checked == true && (input162.value == '')) {
            alert("Por favor, ao marcar 'Às vezes' é preciso justificar a resposta da pergunta 'Problemas de comportamento'");
            checkbox23.focus();
            return false;
        }
        let checkbox26 = document.getElementById('26');
        let input163 = document.getElementById('163');
        if(checkbox26.checked == true && (input163.value == '')) {
            alert("Por favor, ao marcar 'Às vezes' é preciso justificar a resposta da pergunta 'Dificuldades motoras'");
            checkbox26.focus();
            return false;
        }
        let checkbox29 = document.getElementById('29');
        let input164 = document.getElementById('164');
        if(checkbox29.checked == true && (input164.value == '')) {
            alert("Por favor, ao marcar 'Às vezes' é preciso justificar a resposta da pergunta 'Dificuldades Cognitivas'");
            checkbox29.focus();
            return false;
        }
        let checkbox32 = document.getElementById('32');
        let input165 = document.getElementById('165');
        if(checkbox32.checked == true && (input165.value == '')) {
            alert("Por favor, ao marcar 'Às vezes' é preciso justificar a resposta da pergunta 'Dificuldades na socialização'");
            checkbox32.focus();
            return false;
        }
        let checkbox35 = document.getElementById('35');
        let input166 = document.getElementById('166');
        if(checkbox35.checked == true && (input166.value == '')) {
            alert("Por favor, ao marcar 'Às vezes' é preciso justificar a resposta da pergunta 'Dificuldades na Linguagem'");
            checkbox35.focus();
            return false;
        }
        let checkbox38 = document.getElementById('38');
        let input167 = document.getElementById('167');
        if(checkbox38.checked == true && (input167.value == '')) {
            alert("Por favor, ao marcar 'Às vezes' é preciso justificar a resposta da pergunta 'Dificuldades na Visão'");
            checkbox38.focus();
            return false;
        }
        let checkbox41 = document.getElementById('41');
        let input168 = document.getElementById('168');
        if(checkbox41.checked == true && (input168.value == '')) {
            alert("Por favor, ao marcar 'Às vezes' é preciso justificar a resposta da pergunta 'Dificuldades auditivas'");
            checkbox41.focus();
            return false;
        }
        let checkbox44 = document.getElementById('44');
        let input169 = document.getElementById('169');
        if(checkbox44.checked == true && (input169.value == '')) {
            alert("Por favor, ao marcar 'Às vezes' é preciso justificar a resposta da pergunta 'Problemas emocionais'");
            checkbox44.focus();
            return false;
        }
        let checkbox47 = document.getElementById('47');
        let input170 = document.getElementById('170');
        if(checkbox47.checked == true && (input170.value == '')) {
            alert("Por favor, ao marcar 'Às vezes' é preciso justificar a resposta da pergunta 'Dificuldades na linguagem'");
            checkbox47.focus();
            return false;
        }
        let checkbox50 = document.getElementById('50');
        let input171 = document.getElementById('171');
        if(checkbox50.checked == true && (input171.value == '')) {
            alert("Por favor, ao marcar 'Às vezes' é preciso justificar a resposta da pergunta 'Suspeita de superdotação'");
            checkbox50.focus();
            return false;
        }
        let checkbox53 = document.getElementById('53');
        let input172 = document.getElementById('172');
        if(checkbox53.checked == true && (input172.value == '')) {
            alert("Por favor, ao marcar 'Às vezes' é preciso justificar a resposta da pergunta 'TEA: Transtorno do Espectro Autista'");
            checkbox53.focus();
            return false;
        }
        let checkbox67 = document.getElementById('67');
        let input70 = document.getElementById('70');
        if(checkbox67.checked == true && (input70.value == '')) {
            alert("Por favor, ao marcar 'Sim' é preciso justificar a resposta da pergunta 'Utiliza adequação de horário'");
            checkbox67.focus();
            return false;
        }
        let checkbox186 = document.getElementById('186');
        let input188 = document.getElementById('188');
        if(checkbox186.checked == true && (input188.value == '')) {
            alert("Por favor, ao marcar 'Não' é preciso justificar a resposta da pergunta 'Se alimenta com a merenda fornecida pela escola.'");
            checkbox186.focus();
            return false;
        }

        //enviado para deferimento
        if ( !confirm("Após a alteração do status para ENVIADO PARA DEFERIMENTO não será possível realizar alterações.\n\nDeseja continuar mesmo assim?") ) {
            return false;
        }

        document.getElementById('form').submit();
    }
</script>