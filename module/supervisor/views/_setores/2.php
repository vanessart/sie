<?php
if (!defined('ABSPATH'))
    exit;

$back = filter_input(INPUT_POST, 'back', FILTER_SANITIZE_STRING);
$id = filter_input(INPUT_POST, 'id_setor', FILTER_SANITIZE_STRING);
$fk_id_setor = filter_input(INPUT_POST, 'fk_id_setor', FILTER_SANITIZE_STRING);
if ( !empty($_REQUEST[1]['fk_id_setor']) ) {
    $id = filter_var($_REQUEST[1]['fk_id_setor'], FILTER_SANITIZE_STRING);
}

$id = $fk_id_setor ?? $id;

$instResult = $model->getTodasInstancia();
$aInstanciasRede1 = [];
$aInstanciasRede0 = [];
if (!empty($instResult)) {
    foreach ($instResult as $v) {
        ${'aInstanciasRede'.$v['rede']}["{$v['rede']}_{$v['id_inst']}"] = $v['n_inst'];
    }
    asort($aInstanciasRede1);
    asort($aInstanciasRede0);
}

$coordResult = $model->getCoordenadores();
$aPessoas = [];
if (!empty($coordResult)) {
    foreach ($coordResult as $v) {
        $aPessoas[$v['id_pessoa']] = $v['n_pessoa'];
    }
    asort($aPessoas);
}

$cursoResult = $model->getCurso();
$aCursos = [];
if (!empty($cursoResult)) {
    foreach ($cursoResult as $v) {
        $aCursos[$v['id_curso']] = $v['n_curso'];
    }
    asort($aCursos);
}

$resultAssoc = [
    'id_setor_instancia' => null,
    'fk_id_setor' => null,
    'fk_id_inst' => null,
    'fk_id_curso' => null,
    'at_setor_instancia' => 1,
];

$result = current($model->getSetorAtribuicaoEscola($id));
if (empty($result)) {
    // TODO: Implementar
    die('TRATAR DEPOIS!!!');
}

$resultPorInstancia = $model->getSetorPorInstancia(null, $id);
if(!empty($resultPorInstancia)) {
    foreach ($resultPorInstancia as $k => $v) {
        $hidden = [   
            '1[id_setor_instancia]' => $v['id_setor_instancia'],
            'fk_id_setor' => $result['id_setor'],
            'activeNav' => 2,
            //'ajax' => '1',
            'back' => 1,
            ];
        $token = formErp::token('vis_setor_instancia', 'delete',null,null,1);
        $resultPorInstancia[$k]['1'] = ' <button class="btn btn-outline-danger"> &#x2718; </button>';
        $resultPorInstancia[$k]['1'] = formErp::submit('&#x2718; ', $token, $hidden,null,null,'Deseja Apagar','btn btn-outline-danger');
        $resultPorInstancia[$k]['rede'] = $resultPorInstancia[$k]['rede'] ? 'Particular' : 'Publica';
    }
}
else {
    $resultPorInstancia[] = $resultAssoc;
}

$form['array'] = $resultPorInstancia;
$form['fields'] = [
    'ID' => 'id_setor_instancia',
    'Setor' => 'n_setor',
    'Instância' => 'n_inst',
    'Curso' => 'n_curso',
    'Rede' => 'rede',
    'Atualização' => 'dt_update',
    '||1' => '1',
];
?>

<div class="body">
    <div class="content">
        <div class="alert alert-info">
            <div class="row" >
                <div class="col">
                   <strong> Setor:</strong> <?= $result['n_setor']?> 
                </div>
                <div class="col">
                   <strong>  Supervisor:</strong> <?= $result['n_pessoa']?>
                </div>
            </div>
        </div>
        <br />
        <br />
        <form id="atr" method="POST">
            <fieldset class="add-border">
                <legend class="add-border">Adicionar nova instituição</legend>
                <div class="row mt-2">
                    <div class="col-md-7" id="descricao">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon3">Instituição</span>
                            <select name="instituicao" class="form-select" aria-label="Default select example">
                                <option value="">Selecione</option>
                                <optgroup label="Rede">
                                    <?php foreach ($aInstanciasRede0 as $k => $v): ?>
                                    <option value="<?=$k?>" <?= $resultAssoc['fk_id_inst'] == $k ? 'selected' : ''?>><?=$v?></option>
                                    <?php endforeach ?>
                                </optgroup>
                                <optgroup label="Particular">
                                    <?php foreach ($aInstanciasRede1 as $k => $v): ?>
                                    <option value="<?=$k?>" <?= $resultAssoc['fk_id_inst'] == $k ? 'selected' : ''?>><?=$v?></option>
                                    <?php endforeach ?>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3" id="coordenador">
                        <?= formErp::select('1[fk_id_curso]', $aCursos, 'Curso', $resultAssoc['fk_id_curso']); ?>
                    </div>
                    <div class="col-md-2">
                        <?= formErp::hidden([$hidden]) ?>
                        <?php
                            echo formErp::hidden([
                                '1[id_setor_instancia]' => $resultAssoc['id_setor_instancia'],
                                '1[fk_id_setor]' => $result['id_setor'],
                                '1[fk_id_inst]' => null,
                                '1[rede]' => null,
                                'ajax' => '1',
                                'activeNav' => 2,
                                'back' => 1, //se for um insert, atualiza a pagina
                            ]);
                        ?>
                        <?= formErp::hiddenToken('vis_setor_instancia','ireplace',null,null,1) ?>
                        <?= formErp::button('Adicionar',null,null,'btn btn-success'); ?>
                        <!-- <button type="button" class="btn btn-secondary" id="goback">Voltar</button> -->
                    </div>
                </div>
            </fieldset>
        </form>
        <div>
            <?php report::simple($form);?>
        </div>
    </div>
</div>

<script>

    var descricaoOriginal;
    var coordenador;

    $('select[name="instituicao"]').change((el) => {
        var v = $('select[name="instituicao"]').find('option:selected').val();
        var vArr = v.split('_');
        $('input[name="1[rede]"]').val(vArr[0]);
        $('input[name="1[fk_id_inst]"]').val(vArr[1]);
    })

    $('button#goback').click(() => parent.location.href = '<?= HOME_URI ?>/supervisor/setoresAtribuicaoEscolaPesq');

    // $('form').find('[type="submit"]').attr('disabled','disabled');
    // setTimeout(function(){
    //     descricaoOriginal = $('#descricao').clone();
    //     coordenadorOriginal = $('#coordenador').clone();
    //     $('form').find('[type="submit"]').removeAttr('disabled');
    // }, 3000);

    function back(){
        parent.location.href = '<?= HOME_URI ?>/supervisor/setoresAtribuicaoEscolaPesq';
    }
    function remove(id) {
        console.log('remove', id);
    }

    // this is the id of the form
    // $("form#atr").submit(function(e) {
    //     e.preventDefault(); // avoid to execute the actual submit of the form.

    //     var form = $(this);
    //     var actionUrl = form.attr('action');
    //     $.ajax({
    //         type: "POST",
    //         url: '/supervisor/setoresAtribuicaoEscolaModal',
    //         data: form.serialize(), // serializes the form's elements.
    //         success: function(data)
    //         {
    //             var dtJson = getJsonContent(data);
    //             if (dtJson) {
    //                 mountTable(dtJson);
    //             }
    //             // emptyElements();
    //         }
    //     });
    // });

    function getJsonContent(val) {
        var mark = '=== CONTENT - PARTIAL ===';
        var content = val.split(mark);

        if (content.length === 1) {
            return null;
        }

        return JSON.parse(content[1]);
    }

    function mountTable(jsonContent) {
        $('#tabela').find('tbody').empty();
        jsonContent.map(item => {
            var line = $('<tr>');
            line.append($('<td>').text(item.id_setor_instancia));
            line.append($('<td>').text(item.n_setor));
            line.append($('<td>').text(item.n_inst));
            line.append($('<td>').text(item.n_curso));
            line.append($('<td>').text(dataAtualFormatada(new Date(item.dt_update))));
            line.append($('<td>').append(item['1']));
            $('#tabela').find('tbody').append(line);
        });
    }

    function emptyElements() {
        $('#descricao').replaceWith(descricaoOriginal);
        $('#coordenador').replaceWith(coordenadorOriginal);
    }

    function dataAtualFormatada(data){
        var dia  = data.getDate().toString(),
            diaF = (dia.length == 1) ? '0'+dia : dia,
            mes  = (data.getMonth()+1).toString(), //+1 pois no getMonth Janeiro começa com zero.
            mesF = (mes.length == 1) ? '0'+mes : mes,
            anoF = data.getFullYear();
        return diaF+"/"+mesF+"/"+anoF;
    }

    <?php if (!empty($back)){ ?>
        // back();
    <?php } ?>
</script>

<style>
    input.form-control-plaintext {
        background-color: white !important;
        margin-left: 5px !important;
    }

    .input-group, .input-group table {
        width: 100%;
    }

    fieldset.add-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
                box-shadow:  0px 0px 0px 0px #000;
    }

    legend.add-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:inherit; /* Or auto */
        padding:0 10px; /* To give a bit of padding on the left and right */
        border-bottom:none;
        margin-top: -10px;
        background-color: white;
    }
</style>
