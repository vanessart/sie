<?php
if (!defined('ABSPATH'))
    exit;

$cursos = ng_main::cursosDados();
$token = formErp::token('mudaAUL');
foreach ($cursos as $k => $v) {
    $cursos[$k]['qt_letiva'] = $v['qt_letiva'] . ' ' . $v['un_letiva'];
    $cursos[$k]['atual_letiva'] = $v['atual_letiva'] . 'º';
    $v['mudaAUL'] = 'mais';
    $cursos[$k]['mm'] = formErp::submit('+', $token, $v);
    $v['mudaAUL'] = 'menos';
    $cursos[$k]['m'] = formErp::submit('-', $token, $v);
    $v['dataConf'] = 1;
    $cursos[$k]['data'] = formErp::button('Conf. Data', null, " dat('" . $v['id_curso'] . "') ");
}
$form['array'] = $cursos;
$form['fields'] = [
    'Segmento' => 'n_tp_ens',
    'Curso' => 'n_curso',
    'Qt. Letiva' => 'qt_letiva',
    'Unidade Letiva Atual' => 'atual_letiva',
    '||1' => 'mm',
    '||2' => 'm',
    '||3' => 'data'
];
?>
<div class="fieldTop">
    Confuguração das Unidades Letivas
</div>
<?php
report::simple($form);
?>
<form id="form" action="<?= HOME_URI ?>/sed/def/unidLetivaSet.php" target="frame" method="POST">
    <input type="hidden" id="id_curso" name="id_curso" value="" />
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; height: 80vh; border: none" name="frame"></iframe>
<?php
toolErp::modalFim();
?>
<script>
function dat(id) {
    if(id){
        document.getElementById('id_curso').value = id;
        document.getElementById('form').submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
}
</script>