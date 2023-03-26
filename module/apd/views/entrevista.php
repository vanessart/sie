<?php
if (!defined('ABSPATH'))
    exit;
$id_turma_AEE = filter_input(INPUT_POST, 'id_turma_AEE', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa_aluno = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_entre = filter_input(INPUT_POST, 'id_entre', FILTER_SANITIZE_NUMBER_INT);
$id_pl_entre = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
$id_pl = curso::id_pl_atual()['id_pl'];
$n_pl = curso::id_pl_atual()['n_pl'];
$atualizaHist = $model->getAtualizacaoEntre($id_entre);
$atualiza = $model->getAtualizacaoEntre($id_entre,$id_pl);
if (!empty($atualiza[0]['id_entre_atual'])) {
   $id_entre_atual = $atualiza[0]['id_entre_atual']; 
}else{
    $id_entre_atual = null;
}

if (!empty($action)) {
   $action =  $action;
}else{
    $action = 'doc';
}
$id_pessoa_prof = toolErp::id_pessoa();

$entre = sql::get('apd_doc_entrevista','sens_1, sens_2,desenvol_1,desenvol_2,desenvol_3,lingu_1,lingu_2,fisico_1,fisico_2,fisico_3,hist_saude_1,hist_saude_2,hist_saude_3,hist_saude_4,hist_saude_5,hist_saude_6,medicamento,terapia,pessoas_casa,gestacao,intercorrencia,cognitivo,vida_esc,convivio,aliment,considera,desenvol,lingu,fisico,sens,hist_saude,habitos,outras_info,resp_info,dt_entrevista,cid', ['id_entre' => $id_entre], 'fetch');
$desenvol = [];
$fisico = [];
$lingu = [];
$sens = [];
$hist_saude = [];

if (!empty($entre)) {
   $desenvol = explode(",", $entre['desenvol']);
   $fisico = explode(",", $entre['fisico']);
   $lingu = explode(",", $entre['lingu']);
   $sens = explode(",", $entre['sens']);
   $hist_saude = explode(",", $entre['hist_saude']);
}

if (!empty($entre['dt_entrevista'])) {
    $data_entre = $entre['dt_entrevista'];
}else{
    $data_entre = date("Y-m-d");
}
?>
<style type="text/css">
    .input-group-text
    {
    display: none;
    }

    .titulo { 
        color: #888;
        font-size: 16px;
        margin-bottom: 5px;
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
</style>
<div class="body">
    <div class="tituloG">
        ENTREVISTA FAMILIAR
    </div>
    <?php 
    $salvar_entrevista = 1;
    $disabled = '';
    if (($id_pl <> $id_pl_entre) && !empty($id_pl_entre) && !empty($entre['pessoas_casa'])) {
        $salvar_entrevista = 0;
        $disabled = 'disabled';
        echo toolErp::divAlert('warning','Essa entrevista já foi preenchida, preencha apenas o campo Atualização '.$n_pl);
    }
    ?>
    <form id="formEnvia" target="_parent" action="<?= HOME_URI ?>/apd/<?= $action ?>" method="POST">

        <div class="row">
            <div class="col">
                <span class="titulo"> RESPONSÁVEL PELAS INFORMAÇÕES </span>
            </div>
            <div class="col">
                <span class="titulo"> DATA</span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['resp_info']; 
                }else{
                    echo formErp::input('1[resp_info]', null, @$entre['resp_info']);
                }?>
               
            </div>
            <div class="col"> 
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo dataErp::converteBr($entre['dt_entrevista']); 
                }else{
                    echo formErp::input('1[dt_entrevista]', 'Data de Início', $data_entre, ' required', null, 'date');
                }?>
            </div>
        </div>
        <br><br>


        <div class="row">
            <div class="col">
                <span class="titulo"> CID </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['cid']; 
                }else{
                    echo formErp::input('1[cid]', null, @$entre['cid']);
                }?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> MEDICAMENTOS </span>
                <?= toolErp::tooltip("Quais?") ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['medicamento']; 
                }else{
                    echo formErp::textarea('1[medicamento]', @$entre['medicamento']);
                }?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> TERAPIAS </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['terapia']; 
                }else{
                    echo formErp::textarea('1[terapia]', @$entre['terapia']);
                }?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> PESSOAS QUE MORAM NA CASA </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['pessoas_casa']; 
                }else{
                    echo formErp::textarea('1[pessoas_casa]', @$entre['pessoas_casa']);
                }?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> A GESTAÇÃO FOI PLANEJADA? </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['gestacao']; 
                }else{
                    echo formErp::input('1[gestacao]', null, @$entre['gestacao']);
                }?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> HOUVE INTERCORRÊNCIA NEONATAL? DESCREVA </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['intercorrencia']; 
                }else{
                    echo formErp::textarea('1[intercorrencia]', @$entre['intercorrencia']);
                }?>
            </div>
        </div>
        <br><br>
   
        <div class="row">
            <div class="col">
                <span class="titulo"> SENSORIAL </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Visão </span>
                    <input  type="checkbox" name="sens_" value="1" <?= array_search('1', $sens) !== false ? 'checked ' : '' ?> <?= $disabled ?>>
                    <span class="checkmark"></span>
                </label> 
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['sens_1']; 
                }else{
                    echo formErp::input('1[sens_1]', null, @$entre['sens_1']);
                }?>
            </div>
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Audição</span>
                    <input  type="checkbox" name="sens_" value="2" <?= array_search('2', $sens) !== false ? 'checked ' : '' ?> <?= $disabled ?>>
                    <span class="checkmark"></span>
                </label> 
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['sens_2']; 
                }else{
                    echo formErp::input('1[sens_2]', null, @$entre['sens_2']);
                }?>
            </div>
        </div>
        <br><br>
      
        <div class="row">
            <div class="col">
                <span class="titulo"> LINGUAGEM </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Balbusiar</span>
                    <input  type="checkbox" name="lingu_" value="1" <?= array_search('1', $lingu) !== false ? 'checked ' : '' ?> <?= $disabled ?>>
                    <span class="checkmark"></span>
                </label> 
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['lingu_1']; 
                }else{
                    echo formErp::input('1[lingu_1]', null, @$entre['lingu_1']);
                }?>
            </div>
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Falar</span>
                    <input  type="checkbox" name="lingu_" value="2" <?= array_search('2', $lingu) !== false ? 'checked ' : '' ?> <?= $disabled ?>>
                    <span class="checkmark"></span>
                </label> 
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['lingu_2']; 
                }else{
                    echo formErp::input('1[lingu_2]', null, @$entre['lingu_2']);
                }?>
            </div>
        </div>
        <br><br>
      
        <div class="row">
            <div class="col">
                <span class="titulo"> DESENVOLVIMENTO PSICOMOTOR </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Sentar</span>
                    <input  type="checkbox" name="desenvol_" value="1" <?= array_search('1', $desenvol) !== false ? 'checked ' : '' ?> <?= $disabled ?>>
                    <span class="checkmark"></span>
                </label> 
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['desenvol_1']; 
                }else{
                    echo formErp::input('1[desenvol_1]', null, @$entre['desenvol_1']);
                }?>
            </div>
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Engatinhar</span>
                    <input  type="checkbox" name="desenvol_" value="2" <?= array_search('2', $desenvol) !== false ? 'checked ' : '' ?> <?= $disabled ?>>
                    <span class="checkmark"></span>
                </label> 
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['desenvol_2']; 
                }else{
                    echo formErp::input('1[desenvol_2]', null, @$entre['desenvol_2']);
                }?>
            </div>
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Andar</span>
                    <input  type="checkbox" name="desenvol_" value="3" <?= array_search('3', $desenvol) !== false ? 'checked ' : '' ?> <?= $disabled ?>>
                    <span class="checkmark"></span>
                </label> 
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['desenvol_3']; 
                }else{
                    echo formErp::input('1[desenvol_3]', null, @$entre['desenvol_3']);
                }?>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col">
                <span class="titulo"> FÍSICO </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Enurese</span>
                    <input  type="checkbox" name="fisico_" value="2" <?= array_search('2', $fisico) !== false ? 'checked ' : '' ?> <?= $disabled ?>>
                    <span class="checkmark"></span>
                </label> 
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['fisico_1']; 
                }else{
                    echo formErp::input('1[fisico_1]', null, @$entre['fisico_1']);
                }?>
            </div>
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Encoprese</span>
                    <input  type="checkbox" name="fisico_" value="3" <?= array_search('3', $fisico) !== false ? 'checked ' : '' ?> <?= $disabled ?>>
                    <span class="checkmark"></span>
                </label> 
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['fisico_2']; 
                }else{
                    echo formErp::input('1[fisico_2]', null, @$entre['fisico_2']);
                }?>
            </div>
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Controle Esfincteriano</span>
                    <input  type="checkbox" name="fisico_" value="1" <?= array_search('1', $fisico) !== false ? 'checked ' : '' ?> <?= $disabled ?>>
                    <span class="checkmark"></span>
                </label> 
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['fisico_3']; 
                }else{
                    echo formErp::input('1[fisico_3]', null, @$entre['fisico_3']);
                }?>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col">
                <span class="titulo"> HISTÓRICO DE SAÚDE </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Doenças</span>
                    <input  type="checkbox" name="hist_saude_" value="1" <?= array_search('1', $hist_saude) !== false ? 'checked ' : '' ?> <?= $disabled ?>>
                    <span class="checkmark"></span>
                </label> 
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['hist_saude_1']; 
                }else{
                    echo formErp::input('1[hist_saude_1]', null, @$entre['hist_saude_1']);
                }?>
            </div>
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Cirurgias</span>
                    <input  type="checkbox" name="hist_saude_" value="2" <?= array_search('2', $hist_saude) !== false ? 'checked ' : '' ?> <?= $disabled ?>>
                    <span class="checkmark"></span>
                </label> 
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['hist_saude_2']; 
                }else{
                    echo formErp::input('1[hist_saude_2]', null, @$entre['hist_saude_2']);
                }?>
            </div>
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Quedas</span>
                    <input  type="checkbox" name="hist_saude_" value="3" <?= array_search('3', $hist_saude) !== false ? 'checked ' : '' ?> <?= $disabled ?>>
                    <span class="checkmark"></span>
                </label> 
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['hist_saude_3']; 
                }else{
                    echo formErp::input('1[hist_saude_3]', null, @$entre['hist_saude_3']);
                }?>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Desmaios</span>
                    <input  type="checkbox" name="hist_saude_" value="4" <?= array_search('4', $hist_saude) !== false ? 'checked ' : '' ?> <?= $disabled ?>>
                    <span class="checkmark"></span>
                </label> 
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['hist_saude_4']; 
                }else{
                    echo formErp::input('1[hist_saude_4]', null, @$entre['hist_saude_4']);
                }?>
            </div>
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Convulsões</span>
                    <input  type="checkbox" name="hist_saude_" value="5" <?= array_search('5', $hist_saude) !== false ? 'checked ' : '' ?> <?= $disabled ?>>
                    <span class="checkmark"></span>
                </label> 
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['hist_saude_5']; 
                }else{
                    echo formErp::input('1[hist_saude_5]', null, @$entre['hist_saude_5']);
                }?>
            </div>
            <div class="col">
                <label class="container">
                    <span style="font-size: 14px">Outros</span>
                    <input  type="checkbox" name="hist_saude_" value="6" <?= array_search('6', $hist_saude) !== false ? 'checked ' : '' ?> <?= $disabled ?>>
                    <span class="checkmark"></span>
                </label> 
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['hist_saude_6']; 
                }else{
                    echo formErp::input('1[hist_saude_6]', null, @$entre['hist_saude_6']);
                }?>
            </div>
        </div>
        <br><br>
        
        <div class="row">
            <div class="col">
                <span class="titulo"> COGNITIVO </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['cognitivo']; 
                }else{
                    echo formErp::textarea('1[cognitivo]', @$entre['cognitivo']);
                }?>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col">
                <span class="titulo"> VIDA ESCOLAR </span>
                <?= toolErp::tooltip("Breve relato da vida escolar do aluno","60vh") ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['vida_esc']; 
                }else{
                    echo formErp::textarea('1[vida_esc]', @$entre['vida_esc']);
                }?>
            </div>
        </div>
        <br><br>
   
        <div class="row">
            <div class="col">
                <span class="titulo"> CONVÍVIO SOCIAL </span>
                <?= toolErp::tooltip("Amizade, autocorreção, brincadeiras, adaptação, liderança, agressividade, antisocial, curiosidades, etc","130vh") ?>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['convivio']; 
                }else{
                    echo formErp::textarea('1[convivio]', @$entre['convivio']);
                }?>
            </div>
        </div>
        <br><br>
        
        <div class="row">
            <div class="col">
                <span class="titulo"> ALIMENTAÇÃO E CUIDADOS PESSOAIS </span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['aliment']; 
                }else{
                    echo formErp::textarea('1[aliment]', @$entre['aliment']);
                }?>
            </div>
        </div>
        <br><br>
          
        <div class="row">
            <div class="col">
                <span class="titulo"> COMO VOCÊ CONSIDERA O CONVÍVIO FAMILIAR</span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['considera']; 
                }else{
                    echo formErp::textarea('1[considera]', @$entre['considera']);
                }?>
            </div>
        </div>
        <br><br>
          
        <div class="row">
            <div class="col">
                <span class="titulo"> HÁBITOS TÍPICOS E ATÍPICOS</span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['habitos']; 
                }else{
                    echo formErp::textarea('1[habitos]', @$entre['habitos']);
                }?>
            </div>
        </div>
        <br><br>
               
        <div class="row">
            <div class="col">
                <span class="titulo"> OUTRAS INFORMAÇÕES QUE JULGAR NECESSÁRIAS</span>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php 
                if ($salvar_entrevista <> 1) {
                   echo $entre['outras_info']; 
                }else{
                    echo formErp::textarea('1[outras_info]', @$entre['outras_info']);
                }?>
            </div>
        </div>
        <br><br>
        <?php 
        if ($salvar_entrevista == 1) {?>
            <div class="row">
                <div class="col text-center">
                    <input type="hidden" name="1[sens]" id="sens" value="" />
                    <input type="hidden" name="1[lingu]" id="lingu" value="" />
                    <input type="hidden" name="1[desenvol]" id="desenvol" value="" />
                    <input type="hidden" name="1[fisico]" id="fisico" value="" />
                    <input type="hidden" name="1[hist_saude]" id="hist_saude" value="" />
                    <?=
                    formErp::hidden([
                        '1[id_entre]' => $id_entre ,
                        '1[fk_id_turma]' => $id_turma ,
                        '1[fk_id_pessoa_prof]' => $id_pessoa_prof,
                        '1[fk_id_pessoa]' => $id_pessoa_aluno,
                        'id_turma' => $id_turma_AEE,
                    ])
                    . formErp::hiddenToken('apd_doc_entrevista', 'ireplace')
                    . formErp::button('Salvar', null, 'salvarCheck()');
                    ?>            
                </div>
            </div>
             <?php
        }?>
    </form>
    <?php 
    foreach ($atualizaHist as $v) {
        if ($id_pl <> $v['id_pl']) {?>    
            <div class="row">
                <div class="col">
                    <span class="titulo"> ATUALIZAÇÃ0 <?= $v['n_pl'] ?></span>
                </div>
            </div>
            <br>
             <div class="row">
                <div class="col">
                    <?= $v['atualizacao'] ?>
                </div>
            </div>
            <br><br>
            <?php
        }
    }
    if ($salvar_entrevista == 0) {?>
        <form id="formAtual" target="_parent" action="<?= HOME_URI ?>/apd/<?= $action ?>" method="POST">
            <div class="row">
                <div class="col">
                    <span class="titulo"> ATUALIZAÇÃ0 <?= $n_pl ?></span>
                </div>
            </div>
             <div class="row">
                <div class="col">
                    <?= formErp::textarea('1[atualizacao]', @$atualiza[0]['atualizacao']); ?> 
                </div>
            </div>
            <br><br>
            <div class="row">
                <div class="col text-center">
                    <?=
                    formErp::hidden([
                        '1[fk_id_entre]' => $id_entre ,
                        '1[id_entre_atual]' =>  $id_entre_atual,
                        '1[fk_id_pessoa_prof]' => toolErp::id_pessoa(),
                        '1[fk_id_pl]' => $id_pl,
                        'id_turma' => $id_turma_AEE,
                    ])
                    . formErp::hiddenToken('apd_doc_entrevista_atualiza', 'ireplace')
                    . formErp::button('Salvar');
                    ?>            
                </div>
            </div>
        </form>      
        <?php
    }?>
    
</div>
<script type="text/javascript">
    function salvarCheck(){
        var campo = ['sens_','lingu_','desenvol_','fisico_','hist_saude_'];
        var campo2 = ['sens','lingu','desenvol','fisico','hist_saude'];
        for (var j = 0; j < campo.length; j++) {
            //alert(campo[j]);
            const sens = document.getElementsByName(campo[j]);
            var strSens = '';
            var virg = '';
            for (var i = 0; i < sens.length; i++) {
                if(sens[i].checked){
                    strSens += virg + sens[i].value;
                    virg = ',';
                }
            }
            document.getElementById(campo2[j]).value = strSens;
        }
         document.getElementById('formEnvia').submit();
    }
</script>