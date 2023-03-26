<?php
if (!defined('ABSPATH'))
    exit;
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$n_turma = sql::idNome('ge_turmas', ['id_turma' => $id_turma]);
$alunos = ng_escola::alunoPorTurma($id_turma);
$d = sql::get('aa_nono_opt', '*', ['fk_id_turma' => $id_turma]);
foreach ($d as $v) {
    $dados[$v['id_pessoa_opt']] = $v;
}
foreach ($alunos as $k => $v) {
    if ($v['fk_id_tas'] != 0) {
        unset($alunos[$k]);
    } else {
        $alunos[$k]['t4'] = radio('tp_ensino[' . $v['id_pessoa'] . ']', 4, 'Nenhuma das Opções', @$dados[$v['id_pessoa']]['tp_ensino']);
        $alunos[$k]['t6'] = radio('tp_ensino[' . $v['id_pessoa'] . ']', 6, 'Não Preencheu', @$dados[$v['id_pessoa']]['tp_ensino']);
        $alunos[$k]['t1'] = radio('tp_ensino[' . $v['id_pessoa'] . ']', 1, 'Turno Integral', @$dados[$v['id_pessoa']]['tp_ensino']);
        $alunos[$k]['t2'] = radio('tp_ensino[' . $v['id_pessoa'] . ']', 2, 'Noturno', @$dados[$v['id_pessoa']]['tp_ensino']);
        $alunos[$k]['t3'] = chk('espanhol[' . $v['id_pessoa'] . ']', 3, 'Espanhol', @$dados[$v['id_pessoa']]['espanhol']);
        $alunos[$k]['nome'] = $v['n_pessoa'] . '<br>Nº ' . $v['chamada'] . ' RSE: ' . $v['id_pessoa'];
    }
}
$form['array'] = $alunos;
$form['fields'] = [
    '||3' => 'nome',
    '||6' => 't6',
    '||5' => 't4',
    '||1' => 't1',
    '||2' => 't2',
    '||4' => 't3'
];
?>
<div class="body">
    <div class="fieldTop">
        <?= current($n_turma) ?>
    </div>
    <br /><br />
    <form target="_parent" action="<?= HOME_URI ?>/sed" method="POST">
        <?php
        report::simple($form);
        ?>
        <div style="text-align: center; padding: 20px">
            <?=
            formErp::hidden(['id_turma' => $id_turma, 'id_inst' => toolErp::id_inst()])
            . formErp::hiddenToken('nono')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
</div>
<?php

function radio($name, $value, $titulo = NULL, $post = NULL, $atrib = NULL, $style = NULL) {
    if (empty($post)) {
        $post = formErp::extratPost($name);
    }
    return '<label class="container">'
            . '<span style="font-size: 14px; padding-left: 15px;">' . $titulo . '</span> '
            . '<input ' . ($post == $value ? "checked" : '') . ' type="radio" name="' . $name . '" value="' . $value . '" ' . $atrib . ' />'
            . '<span class="checkmark"></span>'
            . '</label>';
}

function chk($name, $value, $titulo = NULL, $post = NULL, $atrib = NULL, $style = NULL) {
    if (empty($post)) {
        $post = formErp::extratPost($name);
    }
    return '<label class="container">'
            . '<span style="font-size: 14px; padding-left: 15px;">' . $titulo . '</span> '
            . '<input ' . ($post == $value ? "checked" : '') . ' type="checkbox" name="' . $name . '" value="' . $value . '" ' . $atrib . ' />'
            . '<span class="checkmark"></span>'
            . '</label>';
}
