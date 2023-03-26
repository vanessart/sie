<?php
if (!defined('ABSPATH'))
    exit;

$dt_fim = filter_input(INPUT_POST, 'dt_fim', FILTER_SANITIZE_STRING);
$search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);
//buscar novo usuario
if (!empty($search)) {
    $busca = $model->emprestProf($search);
    if (empty($busca)) {
        ?>
        <div class="alert alert-danger">
            Não encontramos "<?= $search ?>" em nossa base de dados
        </div>
        <?php
        $search = null;
    } elseif (count($busca) == 1) {
        $id_pessoa = current($busca)['id_pessoa'];
    } else {
        //lista do resultado
        include ABSPATH . '/module/lab/views/_emprestRede/_2/listResultados.php';
    }
}


if (!empty($id_ce) || !empty($id_pessoa)) {
    //mostra os dados do emprestimo
    include ABSPATH . '/module/lab/views/_emprestRede/_2/resultado.php';
} elseif (empty($id_ch) && empty($search)) {
    //buscar emprestimo
    include ABSPATH . '/module/lab/views/_emprestRede/_2/buscar.php';
}
?>
<?php
/**
toolErp::modalInicio(0, null, 'id_cs');
?>
<form action="<?= HOME_URI ?>/lab/emprestRede" target="_parent" method="POST">
    <?= formErp::select('1[fk_id_cs]', [1 => 'Regular', 4 => 'Quebrado (enviado para manutenção)'], 'Situação', $ch['fk_id_cs']) ?>
    <br /><br />
    <div style="text-align: center">
        <?=
        formErp::hidden([
            '1[fk_id_pessoa_lanc]' => toolErp::id_pessoa(),
            'id_pessoa' => $ch['fk_id_pessoa'],
            '1[id_ch]' => $id_ch,
            'id_ch' => $id_ch,
            'activeNav' => 2
        ])
        . formErp::button('Salvar')
        . formErp::hiddenToken('lab_chrome', 'ireplace')
        ?>
    </div>
    <br /><br />
</form>
<?php
toolErp::modalFim();
**/
