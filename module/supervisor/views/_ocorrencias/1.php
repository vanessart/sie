<?php
if (!defined('ABSPATH'))
    exit;

$eh_coordencacao = false;
$fk_id_pessoa = filter_input(INPUT_POST, 'fk_id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$fk_id_inst = filter_input(INPUT_POST, 'fk_id_inst', FILTER_SANITIZE_NUMBER_INT);
$fk_id_curso = filter_input(INPUT_POST, 'fk_id_curso', FILTER_SANITIZE_NUMBER_INT);
$rede = filter_input(INPUT_POST, 'rede', FILTER_SANITIZE_NUMBER_INT);
// Supervisor
if(toolErp::id_nilvel() == 22) {
    $eh_coordencacao = false;
    $fk_id_pessoa = tool::id_pessoa();
}

$result = $model->getVisitaPorPessoa(null, $fk_id_pessoa, $fk_id_inst, $rede);

$cursoResult = $model->getCurso();
$aCursos = [];
if (!empty($cursoResult)) {
    foreach ($cursoResult as $v) {
        $aCursos[$v['id_curso']] = $v['n_curso'];
    }
    asort($aCursos);
}

$coordResult = $model->getCoordenadores();
$aPessoas = [];
if (!empty($coordResult)) {
    foreach ($coordResult as $v) {
        $aPessoas[$v['id_pessoa']] = $v['n_pessoa'];
    }
    asort($aPessoas);
}

$aInstanciasRede1 = [];
$aInstanciasRede0 = [];
if ($result) {
    foreach ($result as $k => $v) {
        $key = "{$v['rede']}_{$v['fk_id_inst']}";
        if (!@$aInstanciasRede0[$key] && !@$aInstanciasRede1[$key]) {
            ${'aInstanciasRede'.$v['rede']}[$key] = $v['n_inst'];
        }
        $result[$k]['ac'] = formErp::button('Editar', null, 'acesso(' . $v['id_visita'] .')');
        $result[$k]['oc'] = formErp::submit('Ocorrências', null, ['id_visita' => $v['id_visita'],'activeNav' => 2],null, null, null, 'btn btn-info');
    }
    $form['array'] = $result;
    $form['fields'] = [
        'Instância' => 'n_inst',
        'Curso' => 'n_curso',
        'Data visita' => 'data_visita',
        '||ac' => 'ac',
        '||2' => 'oc',
    ];

    if ($eh_coordencacao) {
        $form['fields'] = array('Supervisor' => 'n_pessoa') + $form['fields'];
    }

    $form['fields'] = array('ID' => 'id_visita') + $form['fields'];
}

if (empty($aInstituicoes)) {
    $instResult = $model->getTodasInstancia();
    $aInstituicoes = [];
    if (!empty($instResult)) {
        foreach ($instResult as $v) {
            $aInstituicoes[$v['id_inst']] = $v['n_inst'];
        }
    }
}

asort($aInstanciasRede1);
asort($aInstanciasRede0);
?>
<div class="body">
    <div class="fieldTop">
        Gerenciar Visitas
    </div>
    <div class="row">
        <div class="col-2">
            <button onclick="acesso()" class="btn btn-info">
               Nova Visita
            </button>
        </div>
    </div>
    <br />
    <form method="POST">
        <?php if ($eh_coordencacao): ?>
        <div class="row">
            <div class="col-md-7">
                <?= formErp::select('fk_id_pessoa', $aPessoas, 'Coordenador', $fk_id_pessoa); ?>
            </div>
        </div>
        <br />
        <?php endif  ?>
        <div class="row">
            <div class="col-7">
                <div class="input-group mb-6">
                    <span class="input-group-text" id="basic-addon3">Instituição</span>
                    <select name="instituicao" class="form-select" aria-label="Default select example">
                        <option value="">Selecione</option>
                        <?php if (!empty($aInstanciasRede1)): ?>
                        <optgroup label="Rede">
                            <?php foreach ($aInstanciasRede1 as $k => $v): ?>
                            <option value="<?=$k?>"><?=$v?></option>
                            <?php endforeach ?>
                        </optgroup>
                        <?php endif ?>
                        <?php if (!empty($aInstanciasRede0)): ?>
                        <optgroup label="Particular">
                            <?php foreach ($aInstanciasRede0 as $k => $v): ?>
                            <option value="<?=$k?>"><?=$v?></option>
                            <?php endforeach ?>
                        </optgroup>
                        <?php endif ?>
                    </select>
                </div>
            </div>
            <div class="col-3">
                <?= formErp::select('fk_id_curso', $aCursos, 'Curso', $fk_id_curso); ?>
            </div>
            <div class="col-2">
                <?php
                    $arrFields = [
                        'fk_id_inst' => null,
                        'rede' => null
                    ];
                    echo formErp::hidden($arrFields);
                ?>
                <?= formErp::button('Pesquisar') ?>
            </div>
        </div>
        <br />

    </form>

    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    else {
        ?>
        <div class="alert alert-danger text-center">
            Nenhuma visita encontrada
        </div>
        <?php
    }
    ?>
</div>
<form id="formFrame" target="frame" action="<?= HOME_URI . '/supervisor/supervisorVisitasForm' ?>" method="POST">
    <input type="hidden" id="id_visita" name="id_visita" value="" />
</form>
<form id="ocorrencias" target="frame" action="<?= HOME_URI . '/supervisor/ocorrencias' ?>" method="POST">
    <input type="hidden" id="id_visita" name="id_visita" value="" />
</form>
<?php
toolErp::modalInicio(null,'modal-xl');
?>
<iframe style=" width: 100%; height: 80vh; border: none" name="frame"></iframe>
    <?php
toolErp::modalFim();
?>
<script>
    function acesso(id) {
        if (id) {
            document.getElementById('id_visita').value = id;
            texto = texto = '<div style="text-align: center; color: #7ed8f5;">Atualizar dados da Visita</div>';
        } else {
            document.getElementById('id_visita').value = '';
            texto = '<div style="text-align: center; color: #7ed8f5;">Cadastrar Nova Visita</div>';
        }
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('formFrame').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function ocorrencia(id) {
        if (id) {
            document.getElementById('id_visita').value = id;
            texto = texto = '<div style="text-align: center; color: #7ed8f5;">Atualizar Ocorrências</div>';
        } else {
            document.getElementById('id_visita').value = '';
            texto = '<div style="text-align: center; color: #7ed8f5;">Cadastrar Nova Ocorrência</div>';
        }
        var titulo= document.getElementById('myModalLabel');
        titulo.innerHTML  = texto;
        document.getElementById('ocorrencias').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }

    $('select[name="instituicao"]').change((el) => changeInstituicao());
    function changeInstituicao() {
        var v = $('select[name="instituicao"]').find('option:selected').val();
        var vArr = v.split('_');
        $('input[name="rede"]').val(vArr[0]);
        $('input[name="fk_id_inst"]').val(vArr[1]);
    }
</script>
