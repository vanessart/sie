<?php
if (!defined('ABSPATH'))
    exit;
@$id_pl = sql::get($model::$sistema . '_pl', 'id_pl', ['ativo' => 1], 'fetch')['id_pl'];
$polos = sql::idNome($model::$sistema . '_polo');
$cursos = sql::idNome($model::$sistema . '_curso');
$escolas = ng_escolas::idEscolas([1]);
if (toolErp::id_nilvel() != 8) {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
} else {
    $id_inst = toolErp::id_inst();
}
$a = $model->alunoEsc($id_pl, $id_inst);
if ($a && $id_inst) {
    foreach ($a as $k => $v) {
        $a[$k]['diaSem'] = $model->diaSemana($v['dia_sem']);
        $a[$k]['periodoSet'] = $v['periodo'] == 'M' ? 'Manhã' : 'Tarde';
        $a[$k]['horarioSet'] = $v['horario'] . 'º Horário';
        $v['id_pl'] = $v['fk_id_pl'];
        $v['id_polo'] = $v['fk_id_polo'];
        $v['id_curso'] = $v['fk_id_curso'];
        $v['dia'] = $model->diaSemana($v['dia_sem']);
        $v['n_polo'] = $polos[$v['fk_id_polo']];
        $v['n_curso'] = $cursos[$v['fk_id_curso']];
        $a[$k]['termo'] = formErp::submit('Termo de Matrícula', null, $v, HOME_URI . '/'. $this->controller_name .'/pdf/termo', 1);
    }
    $form['array'] = $a;
    $form['fields'] = [
        'Matrícula' => 'id_pessoa',
        'Nome' => 'n_pessoa',
        'Turma' => 'n_turma',
        'Dia' => 'diaSem',
        'Período' => 'periodoSet',
        'Horário' => 'horarioSet',
        '||1' => 'termo'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        <?php
        if (toolErp::id_nilvel() != 8) {
            echo formErp::select('id_inst', $escolas, 'Escola', $id_inst, 1) . '<br>';
        }
        ?> 
        <div class="row">
            <div class="col-9 text-center">
                Termo de Matrícula
            </div>
            <div class="col-3">
                <?php
                if ($id_inst) {
                    ?>
                    <form action="<?= HOME_URI . '/'. $this->controller_name .'/pdf/termoList' ?>" target="_blank" method="POST">
                        <button class="btn btn-info" type="submit">
                            Gerar todos os termos
                        </button>
                    </form>
                    <?php
                }
                ?>
            </div>
        </div>
        <br />

    </div>
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>