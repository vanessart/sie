<?php
if (!defined('ABSPATH'))
    exit;
$part = sql::get('quali_incritos_metadados', '*', ['>' => 'fase']);
foreach ($part as $k => $v) {
    $part[$k]['masculino'] = $v['participantes'] - $v['feminino'];
    if (is_numeric($v['aprovados'])) {
        $part[$k]['reprovados'] = $v['participantes'] - $v['aprovados'];
    } else {
        $part[$k]['reprovados'] = '-';
    }
}
$form['array'] = $part;
$form['fields'] = [
    'Fase' => 'fase',
    'Participantes' => 'participantes',
    'Mulheres' => 'feminino',
    'Homens' => 'masculino',
    'Aprovados' => 'aprovados',
    'Reprovados' => 'reprovados'
];
?>
<div class="body">
    <div class="row">
        <div class="col-8 fieldTop">
            Consolidado de todas as fases
        </div>
        <div class="col-2 text-center">
            <a class="btn btn-primary" target="_blank" href="<?= HOME_URI ?>/quali/def/todos.php">
                Exportar
            </a>
        </div>
        <div class="col-2 text-center">
            <form id="at" method="POST">
                <?= form::hiddenToken('consolidadoPart') ?>
            </form>
            <button class="btn btn-info" onclick="if (confirm('Este script de ser excutado após o termino de cada inscrição. Executar?')) {
                        document.getElementById('at').submit();
                    }">
                Atualizar
            </button>
        </div>
    </div>
    <br />
    <?php
    report::simple($form);
    ?>
</div>