<?php
if (!defined('ABSPATH'))
    exit;

?>
<div class="body">
    <form id="formEnvia" method="POST" target="_parent" action="<?= HOME_URI ?>/htpc/proporPauta">
        <div class="row">
            <div class="col-4 col-form-label">
                <?= formErp::input('1[dt_inicio]', 'Data Início', $dt_inicio, ' required', null, 'date') ?>
            </div>
            <div class="col-4 col-form-label">
                <?= formErp::input('1[dt_fim]', 'Data Fim', $dt_fim, ' required', null, 'date') ?>
            </div>
        </div>
        <div class="row">  
            <div class="col col-form-label">
                <?php formErp::textarea('1[n_pauta]', $n_pauta, 'Pauta',300) ?>
            </div>
        </div>
        <div class="row">  
            <div class="col col-form-label">
                <div><strong>Cursos</strong></div>
                <div class="row">
                <?php
                $conta = 0;
                $cols = 3;
                foreach ($cursos as $ki=>$vi){
                    if ( in_array($ki, $cursosProposta) ) {
                        $checked = ' checked="checked" ';
                    } else {
                        $checked = '';
                    }
                    if ($conta == $cols ) { ?>
                        </div>
                        <div class="row">
                        <?php
                        $conta = 0;
                     }?>
                    <label class="container col col-3">
                        <span style="font-size: 14px; padding-left: 15px"><?= $vi ?></span>
                        <input type="checkbox" name="1[id_curso][]" value="<?= $ki ?>" required <?= $checked ?>>
                        <span class="checkmark"></span>
                    </label> 
                    <?php
                    $conta++;
                }

                for($i=0; $i < ($cols - $conta); $i++) { ?>
                    <label class="container col col-3"></label>
                <?php } ?>
                </div>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col-3 wpp">
                Visível para o Professor?
            </div>
            <div class="col-2 col-form-label">
                <?= formErp::radio('1[visivel_professor]', 1, 'Sim', @$visivel_professor, ' required') ?>
            </div>
            <div class="col-2 col-form-label">
                <?= formErp::radio('1[visivel_professor]', 0, 'Não', @$visivel_professor, ' required') ?>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col col-form-label text-center">
                <?=
                formErp::hidden([
                    '1[id_pauta_proposta]' => $id_pauta_proposta,
                    '1[fk_id_pessoa]' => toolErp::id_pessoa(),
                    'closeModal' => 1
                ])
                . formErp::hiddenToken('htpc_pauta_proposta', 'ireplace')
                . formErp::button( empty($id_pauta_proposta) ? 'Propor Pauta' : 'Editar Pauta', NULL, 'validate()', null, 'salvar');
                ?>
            </div>
        </div>
    </form>
</div>

<script>
    function closemodal(){
        $('#myModal').modal('hide');
        parent.location.href = '<?= HOME_URI ?>/htpc/proporPauta';
    }

    <?php if (!empty($closeModal)){ ?>
       //closemodal();
    <?php } ?>

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

    function validate(){ 

        data1 = document.getElementsByName('1[dt_inicio]')[0].value;
        data2 = document.getElementsByName('1[dt_fim]')[0].value;
        n_pauta = document.getElementsByName('1[n_pauta]')[0].value;
        
        var data = new Date();
        // Guarda cada pedaço em uma variável
        var dia     = data.getDate();
        var mes     = data.getMonth()+1;
        var ano4    = data.getFullYear();
        var hoje = ano4  + '-' + String(mes).padStart(2,'0') + '-' + String(dia).padStart(2,'0');
        
        if(data1 == ""){
            alert("Informe a Data Inicial.");
            document.getElementsByName('1[dt_inicio]')[0].focus();
            return false;
        }

        if(data2 == ""){
            alert("Informe a Data Final.");
            document.getElementsByName('1[dt_fim]')[0].focus();
            return false;
        }

        if(n_pauta == ""){
            alert("Descreva a Pauta.");
            document.getElementsByName('1[n_pauta]')[0].focus();
            return false;
        }

        var iDataInicio = data1.split("-");
        var iDataInicio = parseInt(iDataInicio[0].toString() + iDataInicio[1].toString() + iDataInicio[2].toString());

        var iDataHoje = hoje.split("-");
        var iDataHoje = parseInt(iDataHoje[0].toString() + iDataHoje[1].toString() + iDataHoje[2].toString());

        <?php if (empty($id_pauta_proposta)) { ?>
        if(iDataInicio < iDataHoje){
            alert("A Data Inicial não pode ser anterior à data de Hoje.");
            document.getElementsByName('1[dt_inicio]')[0].focus();
            return false;
        }
        <?php } ?>

        if(data2 == ""){
            data2 = data1;
        }else{

            var aDataLimite = data2.split("-");
            var iDataLimite = parseInt(aDataLimite[0].toString() + aDataLimite[1].toString() + aDataLimite[2].toString()); 
                
            if(iDataInicio > iDataLimite){
                alert("A data Final não pode ser anterior à data Inicial.");
                document.getElementsByName('1[dt_fim]')[0].focus();
                return false;
            }
        }

        id_curso = document.getElementsByName('1[id_curso][]');
        if(id_curso.length == 0){
            alert("Escolha uma ou mais Cursos.");
            return false;
        }

        valida_curso = false;
        for (var i = 0; i < id_curso.length; i++) {
            if(id_curso[i].checked){
                valida_curso = true;
                break;
            }
        }
        if(!valida_curso){
            alert("Escolha uma ou mais Cursos.");
            return false;
        }

        var el = document.getElementsByName('salvar')[0];
        funcDisableButton(el);
        document.getElementById('formEnvia').submit();
    }
</script>