<?php
if (!defined('ABSPATH'))
    exit;

?>
<style>
    .wpp {
        padding: 10px !important;
        padding-top: 15px !important;
        font-weight: bold;
        font-size: 16px;
    }
</style>
<div class="body">
    <form id="formEnvia" method="POST" target="_parent" action="<?= HOME_URI ?>/htpc/pautas">
        <div class="row">
            <div class="col-4 col-form-label">
                <?= formErp::input('1[dt_pauta]', 'Data da Pauta', $dt_pauta, ' required', null, 'date') ?>
            </div>
        </div>
        <div class="row">
            <div class="col col-form-label">
                <?php if (empty($PropostaPauta)) { ?>
                    <span class="text-muted">Não há proposta de pauta</span>
                <?php } else {?>
                    <div class="row">
                        <div class="dropdown col col-12">
                            <button class="btn btn-outline-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Copiar Pautas Propostas
                            </button>
                            <?php
                            echo toolErp::tooltip("Escolha uma pauta proposta pela Coordenadoria para compor a Pauta atual.", "300px");

                        if (!empty($PropostaPauta)) {?>
                            <div style="height: 480px; width: 100%" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <div style="height: 400px; overflow: auto; width: 100%">
                                    <table width="100%"><?php
                                        foreach ($PropostaPauta as $k => $v) {
                                            if ($v["disponivel"]==1) {?>
                                                <tr>
                                                    <td style="padding: 3px">
                                                        <div id="cp_n_pauta<?= $v['id_pauta_proposta'] ?>" class="alert alert-info" onclick="PropostaPauta(<?= $v['id_pauta_proposta'] ?>, 'i')" style="white-space:pre-wrap; width: 98%; margin: auto; word-break: normal; cursor: pointer; text-align: justify"><?= $v["n_pauta"] ?></div>
                                                    </td>
                                                </tr><?php
                                            }
                                        }?>
                                    </table>
                                </div>
                            </div><?php
                        } else {?>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <div class="alert alert-info" style="width: 98%; margin: auto;">
                                    Sem Propostas de Pauta
                                </div>
                            </div><?php
                            }?>
                        </div>
                    </div>
                    <?php
                }?>
            </div>
        </div>
        <div class="row">  
            <div class="col col-3 col-form-label">
                <div class="alert alert-success" id="textCopy" style="display:none;padding: 3px;margin: auto;"></div>
            </div>
        </div>
        <div class="row">  
            <div class="col col-form-label">
                <?php formErp::textarea('1[n_pauta]', $n_pauta, 'Itens da Pauta',300) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-3 wpp">
                Visível para o Professor?
            </div>
            <div class="col-2 col-form-label">
                <?= formErp::radio('1[visivel_professor]', 1, 'Sim', $visivel_professor, ' required') ?>
            </div>
            <div class="col-2 col-form-label">
                <?= formErp::radio('1[visivel_professor]', 0, 'Não', $visivel_professor, ' required') ?>
            </div>
        </div>
        <br><br>
        <div class="row">
            <div class="col col-form-label text-center">
                <?php 
                if (!empty($id_pauta)) {
                    echo formErp::hidden(['1[id_pauta]' => $id_pauta]);
                }
                echo formErp::hidden([
                    '1[fk_id_pessoa]' => toolErp::id_pessoa(),
                    '1[fk_id_inst]' => toolErp::id_inst(),
                    'closeModal' => 1
                ])
                . formErp::hiddenToken('htpc_pauta', 'ireplace')
                . formErp::button('Salvar Pauta', NULL, 'validate()');
                ?>
            </div>
        </div>
    </form>
</div>

<form id="formComp" target="frame" action="<?= HOME_URI ?>/htpc/pautasCadastro" method="POST">
    <input type="hidden" name="id_pauta" id="id_pauta" value="<?= $id_pauta ?>" />
    <input type="hidden" name="id_pauta_proposta" id="id_pauta_proposta" value="" />
</form>

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

    function PropostaPauta(id) {
        r = copyText('cp_n_pauta'+id, 'html');
        el = document.getElementsByName('1[n_pauta]')[0];
        el.selectionStart = el.selectionEnd = el.value.length;
        el.focus();
        $('#textCopy').show('fast');
        $('#textCopy').html(r);
        setTimeout(function() {
            $('#textCopy').hide('slow');
        }, 3000);
    }

    function validate(){ 

        dt_pauta = document.getElementsByName('1[dt_pauta]')[0].value;
        pauta = document.getElementsByName('1[n_pauta]')[0].value;
        
        var data = new Date();
        // Guarda cada pedaço em uma variável
        var dia     = data.getDate();
        var mes     = data.getMonth()+1;
        var ano4    = data.getFullYear();
        var hoje = ano4  + '-' + String(mes).padStart(2,'0') + '-' + String(dia).padStart(2,'0');
        
        if(dt_pauta == ""){
            alert("Informe a Data da Pauta.");
            document.getElementsByName('1[dt_pauta]')[0].focus();
            return false;
        }

        if(pauta == ""){
            alert("Descreva a Pauta.");
            document.getElementsByName('1[n_pauta]')[0].focus();
            return false;
        }

        var idt_pauta = dt_pauta.split("-");
        var idt_pauta = parseInt(idt_pauta[0].toString() + idt_pauta[1].toString() + idt_pauta[2].toString());

        var iDataHoje = hoje.split("-");
        var iDataHoje = parseInt(iDataHoje[0].toString() + iDataHoje[1].toString() + iDataHoje[2].toString());

        if(idt_pauta < iDataHoje){
            alert("A Data da Pauta não pode ser anterior à data de Hoje.");
            document.getElementsByName('1[dt_inicio]')[0].focus();
            return false;
        }

        var el = document.getElementsByName('salvar')[0];
        funcDisableButton(el);
        document.getElementById('formEnvia').submit();
    }
</script>