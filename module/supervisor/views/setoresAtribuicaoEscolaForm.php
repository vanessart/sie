<?php
if (!defined('ABSPATH'))
    exit;

$id = filter_input(INPUT_POST, 'id_setor', FILTER_SANITIZE_STRING);
$back = filter_input(INPUT_POST, 'back', FILTER_SANITIZE_STRING);

$coordResult = $model->getCoordenadores();
$aPessoas = [];
if (!empty($coordResult)) {
    foreach ($coordResult as $v) {
        $aPessoas[$v['id_pessoa']] = $v['n_pessoa'];
    }
    asort($aPessoas);
}

if(empty($id)) {
    $result = [
        'id_setor' => null,
        'fk_id_pessoa' => null,
        'n_setor' => null,
        'at_setor' => 1
    ];
}
else {
    $result = current($model->getSetorAtribuicaoEscola($id));
}
?>

<div class="body">
    <form id="atr" method="POST">
        <div class="row" >
            <div class="col-md-12">
                <?= formErp::input('1[n_setor]', 'Descrição do setor', $result['n_setor'], ' required') ?>
            </div>
        </div>
        <br>
        <div class="row" >
            <div class="col">
                <?= formErp::select('1[fk_id_pessoa]', $aPessoas, 'Supervisor', $result['fk_id_pessoa'], null, null, ' required'); ?>
            </div>
            <div class="col">
                <?= formErp::select('1[at_setor]', ['1' => 'Ativo', '0' => 'Inativo'], 'Status', $result['at_setor'], null, null, ' required') ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12 text-center">
                <?=
                    formErp::hidden([
                        '1[id_setor]' => $result['id_setor'],
                        'back' => 1, //se for um insert, atualiza a pagina
                    ])
                    . formErp::hiddenToken('vis_setor','ireplace',null,null,1)
                    . formErp::button('Salvar',null,null,'btn btn-success');
                ?>
            </div>
        </div>
    </form>
</div>

<script>
    function back(){
        parent.location.href = '<?= HOME_URI ?>/supervisor/setoresAtribuicaoEscolaPesq';
    }
    <?php if (!empty($back)){ ?>
        back();
    <?php } ?>
</script>