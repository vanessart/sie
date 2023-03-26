<?php
if (!defined('ABSPATH'))
    exit;
$id = filter_input(INPUT_POST, 'id_system_ano_escolar', FILTER_SANITIZE_NUMBER_INT);
$id_ciclo = filter_input(INPUT_POST, 'fk_id_ciclo', FILTER_SANITIZE_NUMBER_INT);
$ciclos_ = sqlErp::get(['ge_ciclos', 'ge_cursos'], "id_ciclo, concat(n_ciclo, ' - ', n_curso) n_ciclo ");
$ciclos = toolErp::idName($ciclos_);
$anos = sqlErp::get('rh`.`ano', '*', ['id_system_ano_escolar' => $id]);
$form['array'] = $anos;
$form['fields'] = [
    'ID' => 'id_system_ano_escolar',
    'Segmento' => 'segmento',
    'Código' => 'codigo',
    'Ciclo (RH)' => 'ano_escolar',
    'Ciclo' => 'ciclo',
];
report::simple($form);
?>
<div class="body">
    <form target="_parent" action="<?= HOME_URI ?>/cit/rh" method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::select('1[fk_id_ciclo]', $ciclos, 'Ciclo', $id_ciclo) ?>
            </div>
            <div class="col">
                <?=
                formErp::hidden([
                    '1[id_system_ano_escolar]' => $id
                ])
                . formErp::hiddenToken('rh`.`ano', 'ireplace')
                . formErp::button('Salvar')
                ?>
            </div>
        </div>

    </form>
</div>
