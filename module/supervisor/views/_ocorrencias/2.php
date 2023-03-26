<?php
if (!defined('ABSPATH'))
    exit;

$eh_coordencacao = true;
$id_visita = filter_input(INPUT_POST, 'id_visita', FILTER_SANITIZE_NUMBER_INT);
$fk_id_curso = filter_input(INPUT_POST, 'fk_id_curso', FILTER_SANITIZE_NUMBER_INT);

if (@$_REQUEST['fk_id_visita']) {
    $id_visita = filter_var($_REQUEST['fk_id_visita'], FILTER_SANITIZE_STRING);
}

if (@$_REQUEST[1] && @$_REQUEST[1]['id_visita']) {
    $id_visita = filter_var($_REQUEST[1]['id_visita'], FILTER_SANITIZE_STRING);
}

if (@$_REQUEST[1] && @$_REQUEST[1]['fk_id_visita']) {
    $id_visita = filter_var($_REQUEST[1]['fk_id_visita'], FILTER_SANITIZE_STRING);
}

// Inserção de status do item
if (@$_REQUEST[2]) {
    $status = filter_var($_REQUEST[2]['status'], FILTER_SANITIZE_STRING);
    $comentario = filter_var($_REQUEST[2]['comentario'], FILTER_SANITIZE_STRING);
    $fk_id_visita_item = filter_var($_REQUEST[2]['fk_id_visita_item'], FILTER_SANITIZE_STRING);
    $model->addStatus($fk_id_visita_item, tool::id_pessoa(), $comentario, $status);
}

$resultVisita = !empty($id_visita) ? current($model->getVisitaPorPessoa($id_visita)) : [];

// Supervisor
if(toolErp::id_nilvel() == 22) {
    $eh_coordencacao = false;
    $fk_id_pessoa = tool::id_pessoa();
    // Segurança para 1 supervisor não conseguir acessar visita de outro
    if (!empty($resultVisita) && @$resultVisita['fk_id_pessoa'] != $fk_id_pessoa) {
        die('Você não tem permissão para acessar esta área!');
    }
}

$key_inst = null;
if (!empty($resultVisita)) {
    $key_inst = "{$resultVisita['rede']}_{$resultVisita['fk_id_inst']}";
}

$resultInstancias = $model->getSetorPorInstancia(null, null, $fk_id_pessoa);
$aInstanciasRede1 = [];
$aInstanciasRede0 = [];
if (!empty($resultInstancias)) {
    foreach ($resultInstancias as $v) {
        ${'aInstanciasRede'.$v['rede']}["{$v['rede']}_{$v['fk_id_inst']}"] = $v['n_inst'];
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

$areasResult = $model->getAreas();
$aAreas = [];
if (!empty($areasResult)) {
    foreach ($areasResult as $v) {
        $aAreas[$v['id_area']] = $v['n_area'];
    }
}

asort($aPessoas);
asort($aAreas);

$result = [
    'id_visita' => @$resultVisita['id_visita'] ?: null,
    'fk_id_inst' => $key_inst,
    'fk_id_pessoa' => $fk_id_pessoa,
    'fk_id_curso' => @$resultVisita['fk_id_curso'] ?: null,
    'rede' => @$resultVisita['rede'] ?: null,
    'periodo_inicial' => @$resultVisita['periodo_inicial'] ?: null,
    'periodo_final' => @$resultVisita['periodo_final'] ?: null,
    'data_visita' => @$resultVisita['data_visita'] ?: null,
    'at_visita' => @$resultVisita['at_visita'] ?: null,
    // 'id_pessoa' => $resultVisita['fk_id_pessoa'],
];

$resultVisitaItem = [
    'id_visita_item' => null,
    'fk_id_area' => null,
    'fk_id_item_ocorrencia' => null,
    'n_visita_item' => null,
    'at_visita_item' => 1,
];

$visitaItens = $model->getItensPorVisita(null, $id_visita);
if ($visitaItens) {
    foreach ($visitaItens as $k => $v) {
        $visitaItens[$k]['n_visita_item'] = '<label style="white-space: pre-wrap">'.$v['n_visita_item'].'</label>';
        $visitaItens[$k]['1'] = ' <button onclick="openModalComentario('.$v['id_visita_item'].')" class="btn btn-info">Status</button>';
        $visitaItens[$k]['2'] = ' <button onclick="openModalHistorico('.$v['id_visita_item'].')" class="btn btn-primary">Histórico</button>';
        $visitaItens[$k]['3'] = ' <button onclick="openModalUp('.$v['id_visita_item'].')" class="btn btn-primary">Fotos</button>';
    }
}
$form['array'] = $visitaItens;
$form['fields'] = [
    'ID' => 'id_visita_item',
    'Área' => 'n_area',
    'Item Ocorrencia' => 'n_item_ocorrencia',
    'Descrição' => 'n_visita_item',
    'Status' => 'at_visita_item',
    '||1' => '1',
    '||2' => '2',
    '||3' => '3',
];

$visitaDocumentos = $model->getDocumentosPorVisita(null, $id_visita);
if ($visitaDocumentos) {
    foreach ($visitaDocumentos as $k => $v) {
        $visitaDocumentos[$k]['1'] = ' <button onclick="openModalDocumento(\''.$model->path_upload_documentos.$v['n_visita_documento_disco'].'\',\''.$v['n_visita_documento'].'\')" class="btn btn-info">Visualizar</button>';
    }
}
$formDocumentos['array'] = $visitaDocumentos;
$formDocumentos['fields'] = [
    'ID' => 'id_visita_documento',
    'Nome' => 'n_visita_documento',
    'Data Atualização' => 'dt_update',
    '||1' => '1',
];
?>
<style type="text/css">
details > summary {
    list-style-image: url('/includes/images/arrow-right-square.svg');
}

details summary > h5 {
    display: inline-block;
}

details[open] > summary {
    list-style-image: url('/includes/images/arrow-down-square.svg');
}

details {
    /*border: 1px solid gray;
    border-radius: 0.2rem;*/
    padding: 0.5rem;
}

details[open] > summary {
    margin-bottom: 0.5rem;
}

</style>
<div class="body content">
    <div class="row my-3">
        <div class="col-6">
            <button onclick="openModalComentario(0)" class="btn btn-info">Cadastrar novo</button>
        </div>
    </div>
    <?php if (empty($visitaItens)) { ?>
        <div class="row my-6">
            <div class="col-12 text-center">
                <div class="alert alert-info">Não há ocorrências para esta visita</div>
            </div>
        </div>
    <?php } else { ?>
        <div class="row my-6">
            <div class="col-12 text-strong"><h5><strong>Ocorrências da Visita</strong></h5></div>
        </div>
        <div>
            <?php report::simple($form);?>
        </div>
    <?php }


    $formA['array'] = [
        [
            'id' => 1,
            'nome' => 'Ocorrencia 1',
            'dt_update' => '10/07/2022',
            'act' => '<button class="btn btn-outline-info">Visualizar</button>',
        ],
        [
            'id' => 2,
            'nome' => 'Ocorrencia 2',
            'dt_update' => '13/07/2022',
            'act' => '<button class="btn btn-outline-info">Visualizar</button>',
        ],
        [
            'id' => 3,
            'nome' => 'Ocorrencia 3',
            'dt_update' => '17/07/2022',
            'act' => '<button class="btn btn-outline-info">Visualizar</button>',
        ],
        [
            'id' => 4,
            'nome' => 'Ocorrencia 4',
            'dt_update' => '21/07/2022',
            'act' => '<button class="btn btn-outline-info">Visualizar</button>',
        ],
    ];
    $formA['fields'] = [
        'ID' => 'id',
        'Nome' => 'nome',
        'Data' => 'dt_update',
        '||1' => 'act',
    ];

    $formB['array'] = [
        [
            'id' => 17,
            'nome' => 'Ocorrencia 1',
            'dt_update' => '10/07/2022',
        ],
        [
            'id' => 20,
            'nome' => 'Ocorrencia 2',
            'dt_update' => '13/07/2022',
        ],
    ];
    $formB['fields'] = [
        'ID' => 'id',
        'Nome' => 'nome',
        'Data' => 'dt_update',
    ];

    ?>

    <br>
    <br>
    <div class="row my-6">
        <div class="col-12 text-strong"><h5><strong>Ocorrências Pendentes - Dentro do Prazo</strong></h5></div>
    </div>
    <div>
        <?php report::simple($formA);?>
    </div>

    <br>
    <br>
    <div class="row my-6">
        <div class="col-12 text-strong">
        <details>
            <summary>
                <h5><strong>Ocorrências Pendentes - Fora do Prazo</strong></h5>
            </summary>
            <div>
                    <?php report::simple($formB);?>
            </div>
        </details>
        </div>
    </div>

        <!--form method="POST" enctype="multipart/form-data">
            <div class="row my-3">
                <div class="col-6">
                    <div class="custom-file">
                        <input type="file" class="form-control" name="arquivo" />
                    </div>
                </div>
                <div class="col-6">
                <?php /*
                    echo formErp::hidden([
                        '1[id_visita_documento]' => null,
                        '1[fk_id_visita]' => $result['id_visita'],
                    ])
                    . formErp::hiddenToken('supervisorFotoSalvar')
                    . formErp::button('Enviar Arquivo')
                    */
                ?>
                </div>
            </div>
        </form>
        <div>
            <?php //report::simple($formDocumentos);?>
        </div-->
</div>

<form target="frame" id="form" action="<?= HOME_URI ?>/supervisor/supervisorVisitasModalHistorico" method="POST">
    <input type="hidden" name="fk_id_visita_item" id="fk_id_visita_item" />
    <input type="hidden" name="fk_id_visita" value="<?=$result['id_visita']?>" />
</form>

<?php
toolErp::modalInicio(0, 'modal-lg', null, 'Histórico');
?>
<iframe style="width: 100%; min-height: 80vh; border: none" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>

<form target="frameComentario" id="formComentario" action="<?= HOME_URI ?>/supervisor/supervisorVisitasModalComentario" method="POST">
    <input type="hidden" name="fk_id_visita" value="<?=$result['id_visita']?>" />
    <input type="hidden" name="fk_id_visita_item" id="fk_id_visita_item" />
</form>

<form target="frame" id="formUp" action="<?= HOME_URI ?>/supervisor/supervisorVisitasModalUpload" method="POST">
    <input type="hidden" name="fk_id_visita" value="<?=$result['id_visita']?>" />
    <input type="hidden" name="fk_id_visita_item" id="fk_id_visita_item" />
</form>

<div class="modal fade" id="modalComentario" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Novo Comentário</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe style="width: 100%; min-height: 30vh; border: none" name="frameComentario"></iframe>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDocumento" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Visualizar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <figure style="text-align: center;">
                    <img src="" class="img-fluid" alt="Responsive image">
                </figure>
            </div>
        </div>
    </div>
</div>

<script>
    var instanciasCursos = JSON.parse('<?= json_encode($resultInstancias) ?>');
    var itens = JSON.parse('<?= json_encode($model->getItensOcorrencia()) ?>');
    var hash = window.location.hash.replace('#','');
    var idCursoOriginal = <?= $result['fk_id_curso'] ?: 'null' ?>;

    $('legend').addClass('mt-2');
    $('.danger button').removeClass('btn-info');
    $('.danger button').addClass('btn-danger');

    if (hash) {
        activeTab($('.step a[aria-current="'+hash+'"]'))
    }

    $('select[name="instituicao"]').change((el) => changeInstituicao());

    function changeInstituicao() {
        var v = $('select[name="instituicao"]').find('option:selected').val();
        if (v) {
            var vArr = v.split('_');
            $('input[name="1[rede]"]').val(vArr[0]);
            $('input[name="1[fk_id_inst]"]').val(vArr[1]);

            var idInstituicao = $('input[name="1[fk_id_inst]"]').val();
            $('select[name="1[fk_id_curso]"]')
                .find('option')
                .remove()
                .end()
                .append('<option value="">Selecione</option>')
                .val('');

            instanciasCursos.map((item) => {
                if (item.fk_id_inst === idInstituicao) {
                    $('select[name="1[fk_id_curso]"]')
                        .append($('<option>', {
                            value: item.id_curso,
                            text: item.n_curso
                        }));
                }
            });
        }
    }

    function openModalHistorico(id) {
        $('#form').find('input[name="fk_id_visita_item"]').val(id);
        $('#form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }

    function openModalComentario(id) {
        $('#formComentario').find('input[name="fk_id_visita_item"]').val(id);
        if (id) {
            $('#modalComentario').find('iframe').css('minHeight', '80vh');
        }
        $('#formComentario').submit();
        $('#modalComentario').modal('show');
        $('.form-class').val('');
    }

    function openModalUp(id) {
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = 'Upload';
        $('#formUp').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }

    function openModalDocumento(file, name) {
        var startPosition = file.search('<?= HOME_URI ?>');
        var capturingRegex = /(jpg|jpeg|png)/i;
        var found = file.match(capturingRegex);

        if (found) {
            $('#modalDocumento').find('img').attr('src', file.substr(startPosition));
            $('#modalDocumento').modal('show');
            $('.form-class').val('');
        }
        else {
            var link = document.createElement("a");
            // If you don't know the name or want to use
            // the webserver default set name = ''
            link.setAttribute('download', name);
            link.href = file.substr(startPosition);
            document.body.appendChild(link);
            link.click();
            link.remove();
        }
    }

    function back(){
        parent.location.href = '<?= HOME_URI ?>/supervisor/supervisorVisitasPesq';
    }

    function activeTab(el) {
        $('.nav-link').removeClass('active');
        $('.page-step').removeClass('active');
        $(el).addClass('active');
        $('div[aria-page="' + $(el).attr('aria-current') + '"]').addClass('active');
        $('#atr').attr('action', '#' + $(el).attr('aria-current'));
    }

    changeInstituicao();
    if (idCursoOriginal) {
        $('select[name="1[fk_id_curso]"]').val(idCursoOriginal).change();
    }

    <?php if (!empty(@$_REQUEST['back'])){ ?>
        back();
    <?php } ?>
</script>

<style>
    .step legend {
        font-size: .9rem;
    }
    .step .nav-link.active {
        border-radius: 20px;
        width: 40px !important;
        margin: auto;
        padding: 0 !important;
        font-size: 30pt;
    }
    .step .nav-link {
        background-color: #e9ecef;
        border-radius: 20px;
        width: 40px !important;
        margin: auto;
        padding: 0 !important;
        font-size: 30pt;
    }
    .page-step {
        display: none;
    }
    .page-step.active {
        display: block;
    }
</style>
