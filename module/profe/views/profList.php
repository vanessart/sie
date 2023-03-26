<?php
if (!defined('ABSPATH'))
    exit;
$sistema = $model->getSistema('22','48,2,18,53,54,55,24,56');
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
if (toolErp::id_nilvel() == 22) {
    $escolas = ng_escolas::idEscolasSupervisor(tool::id_pessoa());
}else{
    $escolas = ng_escolas::idEscolas();
}
if (toolErp::id_nilvel() == 18) {
    $ciclos = [35, 36, 37, 31, 25, 26, 19, 20, 21, 22, 23, 24, 1, 2, 32];
} else {
    $ciclos = null;
}

if ($id_inst) {
    $esc = new ng_escola($id_inst);
    $prof = $esc->alocaProfInst($ciclos);
    if ($prof) {
        foreach ($prof as $k => $v) {
            $teste = null;
            foreach ($v['ciclos'] as $y) {
                if (in_array($y, [1, 2, 3, 4, 5, 6, 7, 8, 9, 27, 28, 29, 30])) {
                    $teste = 1;
                }
            }
            if ($teste) {
                $prof[$k]['ac'] = formErp::submit('Planos de Aula', null, ['id_instCoord' => $id_inst,'id_pessoa' => $v['id_pessoa']], HOME_URI . '/'.$sistema.'/planoAula');
            } else {
                $prof[$k]['ac'] = formErp::submit('Projetos', null, ['id_inst' => $id_inst, 'id_turma'=> key($v['id_turma'])], HOME_URI . '/'.$sistema.'/projetoCoord');
            } 
        }
        $form['array'] = $prof;
        $form['fields'] = [
            'Matrícula' => 'rm',
            'Nome' => 'nome',
            'Turmas' => 'turmas',
            '||1' => 'ac'
        ];
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Relação de professores
    </div>
    <div>
        <?= formErp::select('id_inst', $escolas, 'Escola', $id_inst, 1) ?>
    </div>
    <?php
    if (!empty($form)) {
        ?>
        <div class="fieldTop">
            Professores alocados
        </div>
        <?php
        report::simple($form);
    }
    ?>
</div>
