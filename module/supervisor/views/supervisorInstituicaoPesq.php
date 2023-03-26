<?php
if (!defined('ABSPATH'))
    exit;

#toolErp::id_nilvel() == 22 // Supervisor
$instituicao = null;
$curso = null;

$eh_coordencacao = false;
$resultSetor = current($model->getSetorAtribuicaoEscola(null, null, null, tool::id_pessoa()));

if(!empty($resultSetor)) {
    $resultSetorInstancias = $model->getSetorPorInstancia(null, $resultSetor['id_setor']);
}

$cursoResult = $model->getCurso();
$aCursos = [];
if (!empty($cursoResult)) {
    foreach ($cursoResult as $v) {
        $aCursos[$v['id_curso']] = $v['n_curso'];
    }
    asort($aCursos);
}

$aInstituicoes = [];
if (!empty($resultSetorInstancias)) {
    foreach ($resultSetorInstancias as $k => $v) {
        $key = "{$v['rede']}_{$v['fk_id_inst']}";
        if (!@$aInstituicoes[$key]) {
            $aInstituicoes[$key] = $v['n_inst'];
        }

        $params = [
            'id_setor_instancia' => $v['id_setor_instancia'],
            'rede' => $v['rede'],
            'fk_id_inst' => $v['fk_id_inst'],
            'fk_id_curso' => $v['fk_id_curso'],
        ];
        // $resultSetorInstancias[$k]['turmasList'] = implode('<br>', $v['turmas']);
        $resultSetorInstancias[$k]['v'] = formErp::submit('Visitas', null, $params, HOME_URI . '/supervisor/supervisorVisitasPesq');
    }
    asort($aInstituicoes);
    $form['array'] = $resultSetorInstancias;
    $form['fields'] = [
        'Setor' => 'n_setor',
        'Instância' => 'n_inst',
        'Curso' => 'n_curso',
        'Atualização' => 'dt_update',
        '||v' => 'v',
    ];

    if ($eh_coordencacao) {
        $form['fields'] = array('Supervisor' => 'n_pessoa') + $form['fields'];
    }

    $form['fields'] = array('ID' => 'id_setor_instancia') + $form['fields'];
}
?>
<div class="body">
    <div class="fieldTop">
        Gerenciar Visitas
    </div>
    <form method="POST">
        <div class="row">
            <div class="col-7">
                <?= formErp::select('1[fk_id_curso]', $aInstituicoes, 'Instituição', $instituicao); ?>
            </div>
            <div class="col-3">
                <?= formErp::select('1[fk_id_curso]', $aCursos, 'Curso', $curso); ?>
            </div>
            <div class="col-2">
                <?= formErp::button('Pesquisar') ?>
            </div>
        </div>
        <br />

    </form>

    <?php
    if (!empty($form)) {
        report::simple($form);
    } elseif (!empty($pesquisa)) {
        ?>
        <div class="alert alert-dark text-center">
            Área não encontrada
        </div>
        <?php
    }
    ?>
</div>
