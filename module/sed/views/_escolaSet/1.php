<?php
if (!defined('ABSPATH'))
    exit;
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$escs = ng_main::escolas(null, $id_curso);
$cursos = sql::idNome('ge_cursos');
if ($escs) {
    foreach ($escs as $k => $v) {
        $escs[$k]['ativo'] = toolErp::simnao($v['ativo']);
        $escs[$k]['ac'] = formErp::submit('Acessar', null, ['id_inst' => $v['id_inst'], 'activeNav' => 2]);
    }
    $form['array'] = $escs;
    $form['fields'] = [
        'ID' => 'id_inst',
        'CIE' => 'cie_escola',
        'Nome' => 'n_inst',
        'Ativo' => 'ativo',
        '||1' => 'ac'
    ];
}
echo formErp::select('id_curso', $cursos, ['Curso', 'Todos'], $id_curso, 1);
if (!empty($form)) {
    report::simple($form);
} else {
    ?>
    <div class="alert alert-danger">
        Escolas Não encontradas
    </div>
    <?php
}
