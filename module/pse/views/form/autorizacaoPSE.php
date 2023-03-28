<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$oa = concord::oa($id_pessoa);
$seu = concord::seu($id_pessoa);

$sign = new assinaturaDigital();
$sign->bntClear(true, "Limpar", ['class'=>"btn btn-outline-warning"], 'limpa');
$sign->actChange(true, '__change');
$sign->setAmbiente('LINK EXTERNO');
$sign->setCSSBox([
    'width' => '70vw',
    'height' => '310px',
]);

if (empty($id_pl)) {
   $id_pl = $model->campanha('id_pl'); 
}
if (empty($n_campanha)) {
   $n_campanha = $model->campanha(); 
}
$d = filter_input(INPUT_GET, 'd', FILTER_SANITIZE_STRING);
//$respondido = 0;
$respondido = $model->getFormRespondido($id_pessoa,1,$id_pl); 
$escola = toolErp::n_inst();
if (!empty($d)) {
    $id_pessoa = toolErp::decrypt($d);
    if ($id_pessoa === FALSE) {
        tool::alert("Dados inválidos");
        exit;
    }
    $externo = true;
    $respondido = $model->getFormRespondido($id_pessoa,1,$id_pl); 
    $escola = $model->getEscolaAluno($id_pessoa); 
    if ($respondido == 1) {
       echo toolErp::divAlert('warning', 'Muito obrigado pelas informações!');
    }
} else {
    $externo = false;
}

$form = $model->getForm(1);
$dados_aluno = $model->getPessoa($id_pessoa);
?>
<style type="text/css">
    .per::first-letter {
    text-transform: uppercase;
}
</style>
<div class="body">
    <div class="fieldTop">
       AUTORIZAÇÃO DE PARTICIPAÇÃO NAS ATIVIDADES PROGRAMA SAÚDE NA ESCOLA <br> <br>PSE - <?= @$n_campanha ?>
    </div>
    <br />
    <?= toolErp::divAlert('info',"O Programa Saúde na Escola está no município de ". CLI_CIDADE ." desde 2013 e tem como objetivo realizar atividades que promovam a saúde e previnam doenças nas Unidades Escolares. A participação d$oa $seu filh$oa é muito importante para nós.") ?>
     <div class="alert alert-info" style="padding-top:  10px; padding-bottom: 0">   
        <div class="row">
            <div  class="col-8 col-sm-8">
               <label class="dataMensagem">Alun<?= $oa ?>:</label> <label class="nomePessoa"><?= $dados_aluno['n_pessoa'] ?></label>
            </div>
            <div  class="col-4 col-sm-4">
                <label class="dataMensagem">Data de Nascimento:</label> <label class="nomePessoa"><?= dataErp::converteBr($dados_aluno['dt_nasc']) ?></label>
            </div>
        </div>
        <br>
        <div class="row">
            <div  class="col col-sm">
                <label class="dataMensagem">Telefone:</label> <label class="nomePessoa"><?= $dados_aluno['tel'] ?></label>
            </div>
        </div>
        <br>
        <div class="row">
            <div  class="col col-sm">
                <label class="dataMensagem">Número do Cartão do Sus:</label> <label class="nomePessoa"><?= $dados_aluno['sus'] ?></label>
            </div>
        </div>
        <br>
    </div>
    <br><br>
    <div class="row">
        <div  class="col col-sm" style="font-weight:bolder;text-transform: uppercase;">
             Dados d<?= $oa ?> alun<?= $oa ?> fornecidos pelos Pais/Responsáveis Legais
        </div>
    </div>
    <br><br>
    <div class="row">
        <div  class="col col-sm" style="font-weight:bolder;">
            HISTÓRICO DE SAÚDE/DOENÇAS: (leia atentamente e informe caso <?= $seu ?> filh<?= $oa ?> possua alguma doença e/ou restrição)
        </div>
    </div>
    <br><br>
    <form id="form" target="_parent" action="<?= HOME_URI ?>/pse/<?= !empty($externo) ? 'autorizacaoPSE?d='.$d : 'questionario'; ?>" method="POST">
        <?php
        if ($respondido == 1) {
            //$dadosForm = formDB::getViewPDF($form,$id_pessoa,1,1);
            $dadosForm = formDB::getViewPDFformatado($form,$id_pessoa,1,$id_pl); 
        }else{
           $dadosForm = formDB::getView($form,$id_pessoa,1,$id_pl);  
        }?>  
        <br><br>
        <div class="row">
            <div  class="col col-sm" style="font-weight:bolder;">
                Atividades serão realizadas e escolhidas de acordo com a faixa etária d<?= $oa ?> <?= $seu ?> filh<?= $oa ?>. São elas:
            </div>
        </div>
        <br><br>
        <div class="row">
            <div  class="col col-sm">
                1) Avaliação de peso e altura (todos os alunos)<br><br>
                2) Avaliação da situação vacinal (todos os alunos)<br><br>
                3) Ações de prevenção Dengue e Covid (todos os alunos)<br><br>
                4) Triagem auditiva e ocular (alunos EMEI e 1o ano do EMEF)<br><br>
                5) Promoção da cultura de paz (todos os alunos)<br><br>
                6) Conscientização dos benefícios das práticas esportivas (todos os alunos)<br><br>
                7) Conscientização dos benefícios da alimentação saudável (todos os alunos)<br><br>
                8) Conscientização dos malefícios do tabagismo, álcool e drogas (alunos do 8o e 9o Ano EMMEF e ensino médio)<br><br>
                9) Ações para prevenção da gravidez na adolescência (alunos do 9o Ano e ensino médio)<br><br>
                10) Ações preventivas da saúde bucal<br><br>
                11) Ações prevenção de acidentes (familiares do EMM)<br><br>
            </div>
        </div>
        <div class="row">
            <div  class="col col-sm" style="font-weight:bolder;">
                Algumas das ações citadas acima serão realizadas através de plataforma digital, de modo que o seu conteúdo poderá ficar disponível para os pais assistirem.
            </div>
        </div>
        <br><br>
        <?php 
        if (!empty($externo) && $respondido == 1) {?>
            <div class="row">
                <div  class="col col-sm">
                    Eu, <?= @$dadosForm['21']?>, RG <?= $dadosForm['20'] ?>, responsável legal por <?= $dados_aluno['n_pessoa'] ?>,  declaro serem verdadeiras as informações acima.
                </div>
            </div>
            <br>
            <?php
        }else{?>
            <div class="row">
                <div  class="col col-sm">
                    Eu, <?= formDB::text(21,@$dadosForm['21']) ?>, RG <?= formDB::text(20,@$dadosForm['20'],200) ?>, responsável legal por <?= $dados_aluno['n_pessoa'] ?>,  declaro serem verdadeiras as informações acima.
                </div>
            </div>
            <br>
            <div class="row">
                <div  class="col col-sm">
                    <?=  formDB::checkbox('A',22,'DECLARO ESTAR CIENTE E DE ACORDO',@$dadosForm['22']) ?>
                </div>
            </div> 
            <br><br>
            <?php
        }?>
        <br>
        <div class="row" style="text-align:right;">
            <div  class="col col-sm">
                <?= CLI_CIDADE ?>, <?= date("d") ?> de <?= data::mes(date("m")) ?> de <?= date("Y") ?>
            </div>
        </div> 
        <br><br>
        <div class="row" style="text-align: center">
            <div class="col">
                <?php
                if ($respondido == 0) {
                    echo formErp::hidden([
                        'id_form' => 1,
                        'id_pessoa' => $id_pessoa,
                        'id_turma' => $id_turma,
                        'id_pl' => $id_pl
                    ])
                    . formErp::hiddenToken('salvarForm');

                    $sign->gerar();
                    echo formErp::button("Capturar Assinatura", null, "salvar()", "success", null, null, null, null, null, ' id="enviar" ');
                } else {
                    $assinatura = formDB::getAssinatura(1,$id_pessoa,$id_pl);
                    echo '<img src="'. $assinatura['assinatura'] .'" style="width:35%" />';
                }
                ?> 
            </div>
        </div> 
    </form>
</div>
<script type="text/javascript">
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
        let checkbox13 = document.getElementById('13');
        if(checkbox13.checked == true) {
            var resp = 0;
            let checkbox15 = document.getElementById('15');
            let checkbox16 = document.getElementById('16');
            let checkbox17 = document.getElementById('17');
            let checkbox18 = document.getElementById('18');
            let input19 = document.getElementById('19');
            if (checkbox15.checked == true) {
                resp = 1;
            }else if(checkbox16.checked == true) {
                 resp = 1;
            }else if (checkbox17.checked == true) {
                 resp = 1;
            }else if (checkbox18.checked == true) {
                 resp = 1;
            }else if (input19.value != '') {
                 resp = 1;
            }
            if (resp == 0) {
                alert('Ops... estão faltando informações na terceira pergunta.\n\nPrecisamos saber qual deficiência <?= $seu ?> filh<?= $oa ?> possui. Use o campo "outros" se necessário. Caso não haja, marque a opção "Não"');
                return false;
            }
            
        }
        let checkbox1 = document.getElementById('1');
        let input3 = document.getElementById('3');
        if((checkbox1.checked == true) && (input3.value == '')) {  
             alert('ops.. você esqueceu de informar qual doença <?= $seu ?> filh<?= $oa ?> possui. \n\nPrencha o campo "Qual?" na primeira! Caso não haja doenças, marque a opção "Não"');
             return false;
        }
        
        let checkbox4 = document.getElementById('4');
        let input6 = document.getElementById('6');
        if((checkbox4.checked == true) && (input6.value == '')) {  
             alert('ops.. você esqueceu de informar qual medicação <?= $seu ?> filh<?= $oa ?> usa. \n\nPrencha o campo "Qual?" na segunda pergunta! Caso não utilize, marque a opção "Não"');
             return false;
        }

        let checkbox7 = document.getElementById('7');
        let input9 = document.getElementById('9');
        if((checkbox7.checked == true) && (input9.value == '')) {  
             alert('ops.. você esqueceu de informar qual a restrição para exercícios d<?= $oa ?> <?= $seu ?> filh<?= $oa ?>. \n\nPrencha o campo "Qual?" na quarta pergunta! Caso não tenha, marque a opção "Não"');
             return false;
        }

        let checkbox10 = document.getElementById('10');
        let input12 = document.getElementById('12');
        if((checkbox10.checked == true) && (input12.value == '')) {  
             alert('ops.. você esqueceu de informar qual a restrição alimentar d<?= $oa ?> <?= $seu ?> filh<?= $oa ?>. \n\nPrencha o campo "Qual?" na quinta pergunta! Caso não tenha, marque a opção "Não"');
             return false;
        }

        let input21 = document.getElementById('21');
        if(input21.value == '') {  
             alert('Informe o nome do responsável pelas informaçoes');
             return false;
        }

        let input20 = document.getElementById('20'); 
        if(input20.value == '') {  
             alert('Informe o RG do responsável pelas informaçoes');
             return false;
        }
        let checkbox22 = document.getElementById('22');
        if(checkbox22.checked == false) {  
             alert('Marque a opçao "Declaro estar ciente e de acordo" para prosseguir');
             return false;
        }
       document.getElementById('form').submit();
    }

    function limpa(e){
        e.preventDefault();
        $('#enviar').attr('disabled', true);
    }

    function __change(){
        $('#enviar').attr('disabled', false);
    }

    jQuery(function(){
        $('#enviar').attr('disabled', true);
    })
</script>