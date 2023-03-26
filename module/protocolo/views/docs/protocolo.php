<?php
if (!defined('ABSPATH'))
    exit;

$respondido = 0;
$escola = toolErp::n_inst();
if (!empty($d)) {
    $id_pessoa = toolErp::decrypt($d);
    if ($id_pessoa === FALSE) {
        tool::alert("Dados inválidos");
        exit;
    }
    $externo = true;
    $respondido = quest::getFormRespondido($id_pessoa,1,$id_campanha); 
    $escola = $model->getEscolaAluno($id_pessoa); 
    if ($respondido == 1) {
       echo toolErp::divAlert('warning', 'Muito obrigado pelas informações!');
    }
} else {
    $externo = false;
}

$form = quest::getForm(1);
$dados_aluno = $model->getPessoa($id_pessoa);
?>
<div class="body">
    <div class="fieldTop">
       ANEXO I- ENCAMINHAMENTO PARA A SALA DE ATENDIMENTO EDUCACIONAL ESPECIALIZADO - AEE – Ano <?= date('Y') ?>
    </div>
    <br />
    <div class="row">
        <div  class="col col-sm">
            <div class=" mensagens">
                <div class="mensagem mensagemLinha-0" >
                    <div class="row">
                        <div  class="col col-sm">
                            <label class="dataMensagem">Escola:</label> <label class="nomePessoa"><?= $escola ?></label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div  class="col col-sm">
                            <label class="dataMensagem">Data:</label> <label class="nomePessoa"><?= date('d/m/Y') ?></label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div  class="col-8 col-sm-8">
                           <label class="dataMensagem">Aluno:</label> <label class="nomePessoa"><?= $dados_aluno['n_pessoa'] ?></label>
                        </div>
                    </div>
                    <br>
                     <div class="row">
                        <div  class="col-8 col-sm-8">
                           <label class="dataMensagem">Idade:</label> <label class="nomePessoa"><?= $dados_aluno['n_pessoa'] ?></label>
                        </div>
                        <div  class="col-4 col-sm-4">
                            <label class="dataMensagem">Data de Nascimento:</label> <label class="nomePessoa"><?= dataErp::converteBr($dados_aluno['dt_nasc']) ?></label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div  class="col-8 col-sm-8">
                           <label class="dataMensagem">Naturalidadde:</label> <label class="nomePessoa"><?= $dados_aluno['n_pessoa'] ?></label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div  class="col col-sm">
                            <label class="dataMensagem">Nome dos pais:</label> <label class="nomePessoa"><?= $dados_aluno['mae'] ?></label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div  class="col col-sm">
                            <label class="dataMensagem">Endereço:</label> <label class="nomePessoa"><?= $dados_aluno['endereco'] ?></label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div  class="col col-sm">
                            <label class="dataMensagem">Telefone:</label> <label class="nomePessoa"><?= $dados_aluno['tel'] ?></label>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
    <br><br>
    <form id="form" target="_parent" action="<?= HOME_URI ?>/audicao/<?= !empty($externo) ? 'formPais?d='.$d : 'questionario'; ?>" method="POST">
        <?php
        if ($respondido == 1) {
             quest::getViewPDF($form,$id_pessoa,1,1); 
        }else{
            quest::getView($form,$id_pessoa,1);  
        }?>  
        <br><br>
        <?php 
        if ($respondido == 0) { ?>  
            <div class="row" style="text-align: center">
                <div class="col">
                    <?=
                    formErp::hidden([
                        'id_form' => 1,
                        'id_pessoa' => $id_pessoa,
                        'id_turma' => $id_turma,
                        'id_campanha' => $id_campanha
                    ])
                    . formErp::hiddenToken('salvarFormPais')
                    . formErp::button('Salvar',null,"salvar()");
                    ?> 
                </div>
            </div> 
            <?php 
        }?>      
    </form>
</div>
<script type="text/javascript">
    let checkbox77 = document.getElementById('77');
    let checkbox8 = document.getElementById('8');
    let checkbox9 = document.getElementById('9');
    let checkbox10 = document.getElementById('10');
    function desabilitar9(){
        if (checkbox77.checked){
            checkbox8.checked = false;
            checkbox9.checked = false;
            checkbox10.checked = false;
        }
    }

    let checkbox80 = document.getElementById('80');
    let checkbox5 = document.getElementById('5');
    let checkbox6 = document.getElementById('6');
    let checkbox7 = document.getElementById('7');
    function desabilitar7(){
        if (checkbox80.checked){
            checkbox5.checked = false;
            checkbox6.checked = false;
            checkbox7.checked = false;
        }
    }
    function desabilitar(){
        let checkbox4 = document.getElementById('4');
        if (checkbox4.checked){
            let checkbox1 = document.getElementById('1').checked = false;
            let checkbox2 = document.getElementById('2').checked = false;
            let checkbox3 = document.getElementById('3').checked = false;
            document.getElementById('c_1').style.display = 'none';
            document.getElementById('c_2').style.display = 'none'; 
            document.getElementById('c_3').style.display = 'none'; 
        }else{
            document.getElementById('c_1').style.display = '';
            document.getElementById('c_2').style.display = ''; 
            document.getElementById('c_3').style.display = '';  
       }
        
    }
    function desab(){
        let checkbox14 = document.getElementById('14').checked = false;
        let checkbox15 = document.getElementById('15').checked = false;
        let checkbox16 = document.getElementById('16').checked = false;
        let input17 = document.getElementById('17');
        input17.value = '';
        document.getElementById('c_14').style.display = 'none';
        document.getElementById('c_15').style.display = 'none';
        document.getElementById('c_16').style.display = 'none';
        document.getElementById('c_17').style.display = 'none';
    }
    function hab(){
        document.getElementById('c_14').style.display = '';
        document.getElementById('c_15').style.display = '';
        document.getElementById('c_16').style.display = '';
        document.getElementById('c_17').style.display = '';
    }
    function desab5(){
        let checkbox49 = document.getElementById('49');
        let checkbox50 = document.getElementById('50');
        let checkbox51 = document.getElementById('51');
        let input52 = document.getElementById('52');
        checkbox49.checked = false;
        checkbox50.checked = false;
        checkbox51.checked = false;
        input52.value = '';
        document.getElementById('c_49').style.display = 'none';
        document.getElementById('c_50').style.display = 'none';
        document.getElementById('c_51').style.display = 'none';
        document.getElementById('c_52').style.display = 'none';
    }

    function hab5(){
        document.getElementById('c_49').style.display = '';
        document.getElementById('c_50').style.display = '';
        document.getElementById('c_51').style.display = '';
        document.getElementById('c_52').style.display = '';
    }
    function desab10(){
        let checkbox13 = document.getElementById('13');
        let checkbox12 = document.getElementById('12');
        let checkbox11 = document.getElementById('11');
        if (checkbox13.checked){
            checkbox12.checked = false;
            checkbox11.checked = false;
            document.getElementById('c_12').style.display = 'none';
            document.getElementById('c_11').style.display = 'none';
        }else{
            document.getElementById('c_12').style.display = '';
            document.getElementById('c_11').style.display = '';
        }
    }
    function hab10(){
        document.getElementById('c_12').style.display = '';
        document.getElementById('c_11').style.display = '';
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
                        return false;
                    }
                }
            }
            
        }
        let checkbox20 = document.getElementById('20');
        if(checkbox20.checked == true) {
            var resp = 0;
            let checkbox49 = document.getElementById('49');
            let checkbox50 = document.getElementById('50');
            let checkbox51 = document.getElementById('51');
            let input52 = document.getElementById('52');
            if (checkbox49.checked == true) {
                resp = 1;
            }else if(checkbox50.checked == true) {
                 resp = 1;
            }else if (checkbox51.checked == true) {
                 resp = 1;
            }else if (input52.value != '') {
                 resp = 1;
            }
            if (resp == 0) {
                alert("Ops... estão faltando informações na pergunta 5.\n\nPrecisamos saber o grau de parentesco da criança com o parente surdo. Use o campo 'outros' se necessário.");
                return false;
            }
            
        }
        let checkbox22 = document.getElementById('22');
        if(checkbox22.checked == true) {
            var resp = 0;
            let checkbox14 = document.getElementById('14');
            let checkbox15 = document.getElementById('15');
            let checkbox16 = document.getElementById('16');
            let input17 = document.getElementById('17');
            if (checkbox14.checked == true) {
                resp = 1;
            }else if(checkbox15.checked == true) {
                 resp = 1;
            }else if (checkbox16.checked == true) {
                 resp = 1;
            }
            if (resp == 0) {
                alert("Ops... você assinalou SIM na pergunta 6, mas esqueceu de informar qual a doença.\n\nVolte à pergunta 6 e complete as informações.");
                return false;
            }
            if ((resp == 1) && (input17.value == '')) {
                 alert('ops.. você esqueceu de informar qual medicação utilizada na pergunta 6!');
                 return false;
            }
            
        }
        let checkbox74 = document.getElementById('74');
        let checkbox75 = document.getElementById('75');
        let checkbox76 = document.getElementById('76');
        if((checkbox74.checked == true) || (checkbox75.checked == true) || (checkbox76.checked == true)) {
            var resp = 0;
            let checkbox8 = document.getElementById('8');
            let checkbox9 = document.getElementById('9');
            let checkbox10 = document.getElementById('10');
            if (checkbox8.checked == true) {
                resp = 1;
            }else if(checkbox9.checked == true) {
                 resp = 1;
            }else if (checkbox10.checked == true) {
                 resp = 1;
            }
            if (resp == 0) {
                alert("Ops... estão faltando informações na pergunta 9.\n\nPrecisamos saber quais sintomas seu filho sente.");
                return false;
            }
            
        }
        let checkbox78 = document.getElementById('78');
        let checkbox79 = document.getElementById('79');
        if((checkbox78.checked == true) || (checkbox79.checked == true)) {
            var resp = 0;
            if (checkbox5.checked == true) {
                resp = 1;
            }else if(checkbox6.checked == true) {
                 resp = 1;
            }else if (checkbox7.checked == true) {
                 resp = 1;
            }
            if (resp == 0) {
                alert("Ops... estão faltando informações na pergunta 7.\n\nPrecisamos saber quais sintomas seu filho teve.");
                return false;
            }
            
        }
       document.getElementById('form').submit();
    }
</script>