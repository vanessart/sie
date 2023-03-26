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
    .pautasCopy {
        white-space: pre-wrap;
    }
</style>
<div class="body">
    <?php if (!empty($id_ata)) { ?>
    <div class="row fieldTop"> 
        <div class=" col-2">
        </div>  
        <div class=" col-8">
            Editar Ata
        </div>
        <div class="col-2" style="text-align: right;padding: 10px">
            <form action="<?= HOME_URI ?>/htpc/atas" method="POST">
                <button class="btn btn-warning" style="margin: 0">
                    Voltar
                </button>
            </form>
        </div>
    </div>
    <?php } ?>
    <form id="formEnvia" method="POST" target="_parent" action="<?= HOME_URI ?>/htpc/atas">
        <input type="hidden" name="1[status]" id="status" value="<?= $status ?>" />
        <div class="row">
            <div class="col-4 col-form-label">
                <?= formErp::input('1[dt_ata]', 'Data da ATA', $dt_ata, ' required', null, 'date') ?>
            </div>
            <div class="col-2 col-form-label">
                <?= formErp::select('1[periodo]', $periodoList, ['Período','Selecione>>'], $periodo, null, null,' required') ?>
            </div>
        </div>
        <div class="row">
            <div class="col col-form-label">
                <?php
                if (!empty($pautas)) {?>
                    <br>
                    <div class="row">
                        <div class="dropdown col col-12">
                            <button class="btn btn-outline-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Selecionar Pautas
                            </button>
                            <?php 
                            echo toolErp::tooltip("Escolha uma pauta proposta pela Coordenadoria para compor a Pauta atual.", "300px");

                        if (!empty($pautas)) {?>
                            <div style="height: 480px; width: 100%" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <div style="height: 400px; overflow: auto; width: 100%">
                                    <table width="100%"><?php
                                        foreach ($pautas as $k => $v) {?>
                                            <tr>
                                                <td style="padding: 3px">
                                                    <div style="white-space:pre-wrap; width: 98%; margin: auto; word-break: normal; cursor: pointer; text-align: justify" class="row">
                                                        <div class="col col-2"><?= dataErp::converteBr($v["dt_pauta"]) ?></div>
                                                        <div class="col col-10 alert alert-info" onclick="pautas(<?= $v['id_pauta'] ?>, 'i')"><?= $v["n_pauta"] ?></div>
                                                    </div>
                                                </td>
                                            </tr><?php
                                        }?>
                                    </table>
                                </div>
                            </div><?php
                        } else {?>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <div class="alert alert-info" style="width: 98%; margin: auto;">
                                    Sem Pautas Cadastradas
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
            <div class="col col-form-label">
                <?php if (!empty($pautaATA)) { ?>
                <table data-table='dnd' class="table todasPautas" id="table-order">
                    <thead>
                        <tr>
                            <th style="width:8%">Item</th>
                            <th style="width:8%">Copiar</th>
                            <th>Pauta</th>
                            <th style="width:8%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($pautaATA as $key => $value) { ?>
                        <tr data-id="<?= $value['id_ata_pauta'] ?>" data-order="<?= $value['ordem'] ?>">
                            <td><?= $value['ordem'] ?></td>
                            <td><a href="javascript:void(0)" class="btn btn-outline-info" onclick="copyPauta(<?= $value['id_ata_pauta'] ?>, <?= $value['id_pauta'] ?>, <?= $value['ordem'] ?>)" >Copiar</a></td>
                            <td><span id="cp_n_pauta<?= $value['id_ata_pauta'] ?>" class="pautasCopy" ><?= $value['n_pauta'] ?></span>
                                <div style="padding-top: 10px;">
                                <?php 
                                if (!empty($value['anexos'])) {
                                    foreach ($value['anexos'] as $k => $v) { ?>
                                        <a href="<?= HOME_URI . '/pub/htpc/' . $v['link'] ?>" class="btn-sm btn-warning upload" target="_blank" data-link="<?= $v['link']?>"><?= $v['tipo']?></a>  
                                <?php
                                    }
                                } ?>
                                </div>
                            </td>
                            <td><a href="javascript:void(0)" class="btn btn-outline-danger" onclick="remove(<?= $value['id_ata_pauta'] ?>, <?= $value['id_pauta'] ?>, <?= $value['ordem'] ?>)">Remover</a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <a href="javascript:void(0)" class="btn btn-outline-info" onclick="copiarTodasPautas()">Copiar Todas as Pautas</a>
                <a href="javascript:void(0)" class="btn btn-outline-info" onclick="saveOrder(<?= $id_ata ?>)">Salvar Ordem</a>
                <br><br>
                <?php } ?>
            </div>
        </div>
        <?php if ( !empty($id_ata) ) { ?> 
        <div class="row">
            <div class="col col-3 col-form-label">
                <a href="javascript:void(0)" class="btn btn-outline-info" onclick="presenca(<?= $id_ata ?>)">Lista de Presença</a>
            </div>
        </div>
        <div class="row">
            <div class="col col-3 col-form-label">
                <div class="alert alert-success" id="textCopy" style="display:none;padding: 3px;margin: auto;"></div>
            </div>
        </div>
        <div class="row">
            <div class="col col-form-label">
                <?php formErp::textarea('1[n_ata]', $n_ata, 'ATA',550) ?>
            </div>
        </div>
        <br><br>
        <?php } ?> 
        <div class="row">
            <div class="col"></div>
                <?=
                ((empty($id_ata)) ?
                    formErp::hidden([
                        '1[n_ata]' => $n_ata,
                    ])
                : '')
                . formErp::hidden([
                    '1[id_ata]' => $id_ata,
                    'action' => $action,
                    '1[fk_id_pessoa]' => toolErp::id_pessoa(),
                    '1[fk_id_inst]' => toolErp::id_inst(),
                ])
                //. formErp::hiddenToken('htpc_ata', 'ireplace')
                . formErp::hiddenToken('ireplaceATA');
                ?>
                <?= $btn ?>
                <div class="col"></div>
        </div>
        <br><br>
    </form>
</div>

<form id="formComp" action="<?= HOME_URI ?>/htpc/atasCadastro" method="POST">
    <input type="hidden" name="action" id="action" value="" />
    <input type="hidden" name="id_pauta" id="id_pauta" value="" />
    <input type="hidden" name="id_ata_pauta" id="id_ata_pauta" value="" />
    <input type="hidden" name="id_ata" id="id_ata" value="<?= $id_ata ?>" />
    <input type="hidden" name="activeNav" value="2" />
</form>
<form id="formPresenca" target="framePres" method="POST">
    <input type="hidden" id="id_ata_presenca" name="id_ata">
</form>
<?php toolErp::modalInicio(); ?>
    <iframe name="framePres" id="framePres" style="width: 100%; height: 80vh; border: none"></iframe>
<?php toolErp::modalFim(); ?>
<script src="<?= HOME_URI ?>/includes/js/jquery.tablednd.js"></script>
<script>
function presenca(id_ata){
    document.getElementById('id_ata_presenca').value = id_ata;
    var titulo= document.getElementById('myModalLabel');
    titulo.innerHTML  = "Lista de Presença";
    document.getElementById("formPresenca").action = '<?= HOME_URI ?>/htpc/presenca?'+Date.now();
    document.getElementById("formPresenca").submit();
    $('#myModal').modal('show');
    $('.form-class').val('');
}
function copiarTodasPautas() {
    if(!document.getElementById('copy_all_pautas')) {
        var input = document.createElement("textarea");
        input.setAttribute('id', 'copy_all_pautas');

        // adiciona o novo elemento criado e seu conteúdo ao DOM
        document.body.appendChild(input);
    } else {
        var input = document.getElementById('copy_all_pautas');
    }

    input.value = '';
    $('.todasPautas .pautasCopy').each(function(i, e) {
        newText=$(this).html();
        if (i+1 < $('.todasPautas .pautasCopy').length){
            newText += "\n\n";
        }
        input.value += newText;
    });

    input.setAttribute('style', 'display:block');
    r = copyText('copy_all_pautas', 'value');
    input.setAttribute('style', 'display:none');

    el = document.getElementsByName('1[n_ata]')[0];
    el.selectionStart = el.selectionEnd = el.value.length;
    el.focus();

    $('#textCopy').show('fast');
    $('#textCopy').html(r);
    setTimeout(function() {
        $('#textCopy').hide('slow');
    }, 3000);
}

function copyPauta(id) {
    r = copyText('cp_n_pauta'+id, 'html');
    el = document.getElementsByName('1[n_ata]')[0];
    el.selectionStart = el.selectionEnd = el.value.length;
    el.focus();
    $('#textCopy').show('fast');
    $('#textCopy').html(r);
    setTimeout(function() {
        $('#textCopy').hide('slow');
    }, 3000);
}

function funcDisableButton(el){
    if (!el) return false;

    el.setAttribute('disabled', 'disabled');
    el.classList.add("disabled");

    if (el.tagName == 'BUTTON') {
        el.setAttribute('data-text-old', el.innerText);
        el.innerText = 'Aguarde ... Salvando os dados';
    } else {
        if (el.getAttribute('type') == 'submit'){
            el.setAttribute('data-text-old', el.value);
            el.value = 'Aguarde ... Salvando os dados';
        } else {
            el.setAttribute('data-text-old', el.innerText);
            el.innerText = 'Aguarde ... Salvando os dados';
        }
    }
    return true;
}
function funcEnableButton(el){
    if (!el) return false;

    el.removeAttribute('disabled');
    el.classList.remove("disabled");

    if (el.tagName == 'BUTTON') {
        el.innerText = el.getAttribute('data-text-old');
    } else {
        if (el.getAttribute('type') == 'submit'){
            el.value = el.getAttribute('data-text-old');
        } else {
            el.innerText = el.getAttribute('data-text-old');
        }
    }
    return true;
}
function pautas(id) {
    document.getElementById('id_pauta').value = id;
    document.getElementById('action').value = 'insert';
    document.getElementById("formComp").submit();
}

function remove(id, id_pauta, ordem) {
    if (confirm('Deseja realmente remover a pauta ('+ordem+') da ATA?')) {
        document.getElementById('action').value = 'remove';
        document.getElementById('id_pauta').value = id_pauta;
        document.getElementById('id_ata_pauta').value = id;
        document.getElementById("formComp").submit();
    }
    return false;
}

function validate(status){ 

    dt_ata = document.getElementsByName('1[dt_ata]')[0].value;
    pauta = document.getElementsByName('1[n_ata]')[0].value;
    periodo = document.getElementsByName('1[periodo]')[0].value;
    if (status) {
        document.getElementById('status').value = status;   
    }
    var data = new Date();

    // 15 dias para poder fechar a ATA
    diasTolerancia = 15;
    data.setDate(data.getDate() - diasTolerancia );

    // Guarda cada pedaço em uma variável
    var dia     = data.getDate();
    var mes     = data.getMonth()+1;
    var ano4    = data.getFullYear();
    var hoje = ano4  + '-' + String(mes).padStart(2,'0') + '-' + String(dia).padStart(2,'0');
    
    if(dt_ata == ""){
        alert("Informe a Data da ATA.");
        document.getElementsByName('1[dt_ata]')[0].focus();
        return false;
    }

    if(periodo == ""){
        alert("Informe o Período da ATA.");
        document.getElementsByName('1[periodo]')[0].focus();
        return false;
    }

    if(pauta == ""){
        alert("Descreva a ATA.");
        document.getElementsByName('1[n_ata]')[0].focus();
        return false;
    }

    var idt_ata = dt_ata.split("-");
    var idt_ata = parseInt(idt_ata[0].toString() + idt_ata[1].toString() + idt_ata[2].toString());

    var iDataHoje = hoje.split("-");
    var iDataHoje = parseInt(iDataHoje[0].toString() + iDataHoje[1].toString() + iDataHoje[2].toString());

    if(idt_ata < iDataHoje){
        alert("A Data da ATA não pode ser anterior a "+ diasTolerancia +" dias da data de Hoje.");
        document.getElementsByName('1[dt_inicio]')[0].focus();
        return false;
    }

    var el = document.getElementsByName('salvar')[0];
    funcDisableButton(el);
    document.getElementById('formEnvia').submit();
}

function saveOrder(id){
    if ($("#table-order > tbody > tr").length == 0) {
        alert("Nenhum dado para salvar");
        return false;
    }

    var formComp = document.getElementById("formComp");
    var i = 0;
    $("#table-order > tbody > tr").each(function(index) {
        if ($(this).data('id')){
            var mi = document.createElement("input");
            mi.setAttribute('name', 'itens_id[]');
            mi.setAttribute('type', 'hidden');
            mi.setAttribute('value', $(this).data('id'));

            var mk = document.createElement("input");
            mk.setAttribute('name', 'itens_ordem[]');
            mk.setAttribute('type', 'hidden');
            mk.setAttribute('value', i);

            formComp.appendChild(mi);
            formComp.appendChild(mk);
            i++;
        }
    });

    document.getElementById('action').value = 'order';
    document.getElementById('id_ata').value = id;
    document.getElementById("formComp").submit();
}

<?php if ($action == 'edit') { ?>

    const formToJSON = (elements) =>
    [].reduce.call(
        elements,
        (data, element) => {
            if (element.name != '') {
                data[element.name] = element.value;
                return data;
            } else {
                return data;
            }
        },
        {},
    );

    setInterval(function () {

        var el = document.getElementsByName('salvar')[0];
        funcDisableButton(el);

        const form = document.getElementById('formEnvia');
        const data = new URLSearchParams();
        for (const pair of new FormData(form)) {
            if (pair[0] != "formToken") {
                data.append(pair[0], pair[1]);
            }
        }

        var myInit = {
            method: 'POST',
            body: data
        };

        fetch('<?= HOME_URI ?>/htpc/saveATAPut?id_ata=<?= $id_ata ?>', myInit)
        .then(resp => resp.json())
        .then(resp => { funcEnableButton(el); })
        .catch((error) => { funcEnableButton(el); });
    }, 180000);

    setInterval(function () {
        if ( !$('#myModal').is(':visible') ) {
            return;
        }
        fetch('<?= HOME_URI ?>/htpc/presencaGet?id_ata=<?= $id_ata ?>')
        .then(resp => resp.json())
        .then(resp => {
            for (id in resp) {
                if(document.getElementById(id)){
                    idd = $('#'+id);
                    iddLab = $('#label_'+id);
                    if (parseInt(resp[id]['presente']) != parseInt(idd.data('presente'))) {
                        idd.data('presente', resp[id]['presente']);

                        if (resp[id]['presente'] == '1') {
                            iddLab.html('Presente').removeClass('alert-danger').addClass('alert, alert-success');
                            idd.html('<a href="javascript:void(0)" class="btn btn-outline-danger" onclick="remove(<?= $id_ata ?>, '+ idd.data('id_pessoa')+', \''+idd.data('rm') +'\')">Retirar Presença</a>');
                        } else {
                            iddLab.html('Ausente').removeClass('alert-success').addClass('alert, alert-danger');
                            idd.html('<span style="white-space:pre-wrap">'+ resp[id]['justificativa'] +'</span>');
                        }
                    }
                }
            }
        })
    }, 10000);

<?php } ?>
</script>