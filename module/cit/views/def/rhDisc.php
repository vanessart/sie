<?php
if (!defined('ABSPATH'))
    exit;
$id = filter_input(INPUT_POST, 'id_system_disciplina', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'fk_id_disc', FILTER_SANITIZE_NUMBER_INT);
$discs = sqlErp::idNome('ge_disciplinas');
$discs['nc']='Núcleo Comum';
$discRh = sqlErp::get('rh`.`disciplinas', '*', ['id_system_disciplina' => $id]);
$form['array'] = $discRh;
$form['fields'] = [
    'ID' => 'id_system_disciplina',
    'Disciplina (RH)' => 'disciplina',
    'Disciplina' => 'disc'
];
report::simple($form);
?>
<div class="body">
    <form target="_parent" action="<?= HOME_URI ?>/cit/rh" method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::select('1[fk_id_disc]', $discs, 'Disciplina', $id_disc) ?>
            </div>
            <div class="col">
                <?=
                formErp::hidden([
                    '1[id_system_disciplina]' => $id,
                    'activeNav' => 2
                ])
                . formErp::hiddenToken('rh`.`disciplinas', 'ireplace')
                . formErp::button('Salvar')
                ?>
            </div>
        </div>

    </form>
</div>
