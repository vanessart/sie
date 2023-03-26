<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_campanha = filter_input(INPUT_POST, 'id_campanha', FILTER_SANITIZE_NUMBER_INT);
$form = $model->getForm(2);
$dados_aluno = $model->getPessoa($id_pessoa);
?>
<div class="body">
    <div class="fieldTop">
       Protocolo de Investigação de Saúde Auditiva - Campanha da Audição – Caminhos do Som – Ano <?= date('Y') ?>
    </div>
    <br />
    <div class="row">
        <div  class="col">
            <div class=" mensagens">
                <div class="mensagem mensagemLinha-0" >
                    <div class="row">
                        <div  class="col-8">
                           <label class="dataMensagem">Aluno:</label> <label class="nomePessoa"><?= $dados_aluno['n_pessoa'] ?></label>
                        </div>
                        <div  class="col-4">
                            <label class="dataMensagem">Data de Nascimento:</label> <label class="nomePessoa"><?= dataErp::converteBr($dados_aluno['dt_nasc']) ?></label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div  class="col">
                            <label class="dataMensagem">Nome da Mãe:</label> <label class="nomePessoa"><?= $dados_aluno['mae'] ?></label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div  class="col">
                            <label class="dataMensagem">Endreço:</label> <label class="nomePessoa"><?= $dados_aluno['endereco'] ?></label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div  class="col">
                            <label class="dataMensagem">Escola:</label> <label class="nomePessoa"><?= toolErp::n_inst() ?></label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div  class="col">
                            <label class="dataMensagem">Telefone:</label> <label class="nomePessoa"><?= $dados_aluno['tel'] ?></label>
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>
    <br><br>
    <form id="form" target="_parent" action="<?= HOME_URI ?>/audicao/questionario" method="POST">
        <?php $model->getView($form,$id_pessoa,2); ?>  
        <br><br>  
        <div class="row" style="text-align: center">
            <div class="col">
                <?=
                formErp::hidden([
                    'id_form' => 2,
                    'id_pessoa' => $id_pessoa,
                    'id_turma' => $id_turma,
                    'id_campanha' => $id_campanha,
                    'id_pessoa_responde' => toolErp::id_pessoa()
                ])
                . formErp::hiddenToken('salvarFormProf')
                . formErp::button('Salvar',null,"salvar()");
                ?> 
            </div>
        </div>       
    </form>
</div>
<script type="text/javascript">
    let checkbox73 = document.getElementById('73');
    let checkbox72 = document.getElementById('72');
    let checkbox70 = document.getElementById('70');
    let checkbox71 = document.getElementById('71');
    let checkbox67 = document.getElementById('67');
    let checkbox68 = document.getElementById('68');
    let checkbox69 = document.getElementById('69');
    function desabilitar(){
        if (document.getElementById('c_57').style.display == 'none' || document.getElementById('c_56').style.display == 'none'){
            document.getElementById('c_57').style.display = '';
            document.getElementById('c_56').style.display = ''; 
        }else{
            document.getElementById('c_57').style.display = 'none';
            document.getElementById('c_56').style.display = 'none'; 
            let checkbox57 = document.getElementById('57').checked = false;
            let checkbox56 = document.getElementById('56').checked = false;
       } 
        
    }
    function desabilitar5(){
        if (checkbox73.checked){
            checkbox67.checked = false;
            checkbox68.checked = false;
            checkbox69.checked = false;
            document.getElementById('c_67').style.display = 'none';
            document.getElementById('c_68').style.display = 'none'; 
            document.getElementById('c_69').style.display = 'none'; 
        }else{
            document.getElementById('c_67').style.display = '';
            document.getElementById('c_68').style.display = ''; 
            document.getElementById('c_69').style.display = '';  
       }
       if (checkbox71.checked){
            document.getElementById('c_67').style.display = '';
            document.getElementById('c_68').style.display = ''; 
            document.getElementById('c_69').style.display = '';  
        }
        if (checkbox70.checked){
            document.getElementById('c_67').style.display = '';
            document.getElementById('c_68').style.display = ''; 
            document.getElementById('c_69').style.display = '';  
        }
        if (checkbox72.checked){
            document.getElementById('c_67').style.display = '';
            document.getElementById('c_68').style.display = ''; 
            document.getElementById('c_69').style.display = '';  
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
                        return false;
                    }
                }
            }
            
        }
        if ( document.getElementById('55').value == '' ) {
            alert("Ops... parece que você se esqueceu dessa pergunta:\n\nA criança tem alguma deficiência? Qual?");
            return false;
        }
        
        if(checkbox70.checked == true || checkbox71.checked == true || checkbox72.checked == true) {
            var resp = 0;
            if (checkbox67.checked == true) {
                resp = 1;
            }else if(checkbox68.checked == true) {
                 resp = 1;
            }else if (checkbox69.checked == true) {
                 resp = 1;
            }
            if (resp == 0) {
                alert("Ops... estão faltando informações na pergunta 5.\n\nPrecisamos saber quais sintomas o aluno sente.");
                return false;
            }
            
        }
       document.getElementById('form').submit();
    }
</script>
