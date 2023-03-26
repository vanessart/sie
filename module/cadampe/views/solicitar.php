<?php
if (!defined('ABSPATH'))
    exit;
$periodo = [];
?>
<style type="text/css">
    .input-group-text
    {
    display: none;
    }

    .titulo { 
        color: #888;
        font-size: 16px;
        margin-bottom: 7px;
        padding-left: 4px;
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
    <div class="row">  
        <div class="col">
            <div class="titulo"> Professor </div>
            <?= formErp::select('fk_id_pessoa_prof', $prof_array, ['Professor', 'Selecione'], $id_pessoa_prof, 1) ?>
        </div>
        <div class="col">
            <?php
            if ($id_pessoa_prof == -1 AND !empty($disc_)) {?>
                <div class="titulo"> Disciplina </div>
                <?php
                formErp::select('iddisc', $disc_, 'Disciplina', @$iddisc, 1, ['fk_id_pessoa_prof' => '-1', 'fk_id_cadampe_motivo' => '42', 'mostra_linha' => '1']);
            }?>
        </div>
    </div>
    <br />

    <br />
    <form id="formEnvia" action="<?= HOME_URI ?>/cadampe/solicitarRel" method="POST">
            <?php
        if (!empty($mostra_linha)) {
            ?>
            <div class="row">
                <div class="col">
                    <div class="titulo"> Motivo </div>
                    <?= formErp::selectDB('cadampe_motivo', '1[fk_id_cadampe_motivo]', 'Motivo', @$motivo, NULL, NULL, NULL, NULL, 'required') ?>
                </div>
                <div class="col">
                    <div class="titulo"> Início </div>
                    <?= formErp::input('1[dt_inicio]', 'Início', date("Y-m-d"), ' required', null, 'date') ?>
                </div>
                <div class="col">
                     <div class="titulo"> Término </div>
                    <?= formErp::input('1[dt_fim]', 'Término', null,  ' required', null, 'date') ?>
                </div>
            </div>
            <br /><br />

            <div class="row">
                    <div class="titulo" style="padding-left: 13px;"> Período </div>
                <div class="col">
                    <label class="container">
                        <span style="font-size: 14px">Manhã</span>
                        <input  type="checkbox" id='M' name="periodo_" value="M" <?= array_search('1', @$periodo) !== false ? 'checked' : '' ?>>
                        <span class="checkmark"></span>
                    </label> 
                </div>
                <div class="col">
                    <label class="container">
                        <span style="font-size: 14px">Tarde</span>
                        <input  type="checkbox" name="periodo_" id='T' value="T" <?= array_search('2', @$periodo) !== false ? 'checked' : '' ?>>
                        <span class="checkmark"></span>
                    </label> 
                </div>
                <div class="col">
                    <label class="container">
                        <span style="font-size: 14px">Noite</span>
                        <input  type="checkbox" name="periodo_" id='N' value="N" <?= array_search('2', @$periodo) !== false ? 'checked' : '' ?>>
                        <span class="checkmark"></span>
                    </label> 
                </div>
            </div>
            <br><br>
            <?= toolErp::divAlert('warning','Atenção! A data final deve ser informada no campo "Término". <br><br> Informar uma data diferente no campo mensagem (abaixo) não modifica a data de término ou o período deste protocolo.') ?>
            <br>
            <div class="row">
                <div class="col">
                    <div class="titulo">Caso necessário, utilize este campo para mensagens:</div>
                   <?php formErp::textarea('mensagem', NULL, 'Mensagem') ?>
                </div>
            </div>
            <br>
            <div class="row">  
                <div class="col">
                        <?php
                    if ($id_pessoa_prof == -1 AND !empty($iddisc)) {
                        if (empty($arrayTurmas)) {
                            echo "Não há turmas para esta a disciplina.";
                        } else {
                        ?>
                        <table class="table table-bordered table-condensed table-responsive table-striped" cellpadding="2" style="width:100%">
                            <tr>
                                    <?php
                                foreach ($arrayTurmas as $k=>$v){ ?>
                                    <td><?= $v ?></td>
                                    <?php 
                                } ?>
                            </tr>
                            <tr>
                                    <?php 
                                foreach ($arrayTurmas as $j=>$v){ 
                                    ?>
                                    <td>
                                        <table>
                                            <tr style="vertical-align: baseline;">
                                                <td>
                                                        <?php
                                                    $conta = 1;
                                                    foreach ($turmas[$j] as $ki=>$vi){
                                                        if (($conta % 10) == 0 ) { ?>
                                                            </td>
                                                            <td>
                                                           <?php     
                                                         }?>
                                                        <label class="container">
                                                            <span style="font-size: 14px; padding-left: 15px"><?= $vi ?></span>
                                                            <input  type="checkbox" name="1[fk_id_turma][]" value="<?= $ki ?>" required>
                                                            <span class="checkmark"></span>
                                                        </label> 
                                                            <?php
                                                        $conta++;   
                                                    } ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                                
                                    <?php 
                                } ?>
                            </tr>
                        </table>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            <br>
            <div class="row">
                <div class="col text-center">
                    <input type="hidden" name="1[periodo]" id='periodo' value="">
                    <?=
                    formErp::hidden([
                        //'1[fk_id_turma]' => $id_turma,
                        '1[fk_id_inst]' => $id_inst,
                        '1[fk_id_pessoa_prof]' => $id_pessoa_prof,
                        'fk_id_pessoa_prof' => $id_pessoa_prof,
                        '1[fk_id_pessoa_solic]' => $id_pessoa_solic,
                        'fk_id_pessoa' => $id_pessoa_solic,
                        '1[iddisc]' => $iddisc,
                    ])
                    . formErp::hiddenToken('cadampe_pedido', 'ireplace', null, null, 1)
                    . formErp::button('Salvar', null, 'data()', null, 'salvar');
                    ?>            
                </div>
            </div>
            <?php 
        }?>
    </form>     
</div>

<script type="text/javascript">
    function funcDisableButton(el){
        if (!el) return false;

        el.setAttribute('disabled', 'disabled');
        el.classList.add("disabled");
        if (el.getAttribute('type') == 'submit'){
            el.value = 'Aguarde ...';
        } else {
            el.innerText = 'Aguarde ...';
        }
        return true;
    }
  
    function data(){ 

        data1 = document.getElementsByName('1[dt_inicio]')[0].value;
        data2 = document.getElementsByName('1[dt_fim]')[0].value;
        motivo = document.getElementsByName('1[fk_id_cadampe_motivo]')[0].value;
        var resp = 0;
        var periodo = '';
        let checkboxM = document.getElementById('M');
        let checkboxT = document.getElementById('T');
        let checkboxN = document.getElementById('N');
        if (checkboxM.checked == true) {
             resp = 1;
             periodo = periodo+'M';
        }
        if(checkboxT.checked == true) {
             resp = 1;
             periodo = periodo+'T';
        } 
        if (checkboxN.checked == true) {
            resp = 1;
            periodo = periodo+'N';
        } 
        if (resp == 0) {
            alert("É obrigatório informar no mínimo um período (Manhã, Tarde, Noite)");
            return false;
        }else{
            document.getElementById('periodo').value = periodo;
        }
        
        var data = new Date();
        // Guarda cada pedaço em uma variável
        var dia     = data.getDate();
        var mes     = data.getMonth()+1;
        var ano4    = data.getFullYear();
        var hoje = ano4  + '-' + String(mes).padStart(2,'0') + '-' + String(dia).padStart(2,'0');
        
        if(data1 == ""){
            alert("Informe a Data Inicial.");
            return false;
        }

        if(data2 == ""){
            alert("Informe a Data de término.");
            return false;
        }

        if(motivo == ""){
            alert("Informe um motivo.");
            return false;
        }

        var iDataInicio = data1.split("-");
        var iDataInicio = parseInt(iDataInicio[0].toString() + iDataInicio[1].toString() + iDataInicio[2].toString());

        var iDataHoje = hoje.split("-");
        var iDataHoje = parseInt(iDataHoje[0].toString() + iDataHoje[1].toString() + iDataHoje[2].toString());

        if(iDataInicio < iDataHoje){
            alert("A Data Inicial não pode ser anterior à data de Hoje.");
            return false;
        }

        if(data2 == ""){
            data2 = data1;
        }else{

            var aDataLimite = data2.split("-");
            var iDataLimite = parseInt(aDataLimite[0].toString() + aDataLimite[1].toString() + aDataLimite[2].toString()); 
                
            if(iDataInicio > iDataLimite){
                alert("A data Final não pode ser anterior à data Inicial.");
                return false;
            }
        }

        fk_id_turma = document.getElementsByName('1[fk_id_turma][]');
        if(fk_id_turma.length > 0){
            valida_turma = false;
            for (var i = 0; i < fk_id_turma.length; i++) {
                if(fk_id_turma[i].checked){
                    valida_turma = true;
                    break;
                }
            }
            if(!valida_turma){
                alert("Escolha uma ou mais turmas.");
                return false;
            }
        }
        var el = document.getElementsByName('salvar')[0];
        funcDisableButton(el);
        document.getElementById('formEnvia').submit();
    }
</script>