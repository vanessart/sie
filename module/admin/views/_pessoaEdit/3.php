<?php
if (!defined('ABSPATH'))
    exit;
$atrib = '';
$end = sqlErp::get('endereco', '*', ['fk_id_pessoa' => $id_pessoa], 'fetch');
?>
<form method="POST">
    <div class="body">
        <div class="row">
            <div class="col-9">
                <?= formErp::input('1[logradouro]', 'Logradouro', @$end['logradouro'], $atrib) ?>
            </div>
            <div class="col-3">
                <?= formErp::input('1[num]', 'Nº', @$end['num'], $atrib) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-5">
                <?= formErp::input('1[bairro]', 'Bairro', @$end['bairro'], $atrib) ?>
            </div>
            <div class="col-5">
                <?= formErp::input('1[cidade]', 'Cidade', @$end['cidade'], $atrib) ?>
            </div>
            <div class="col-2">
                <?= formErp::input('1[uf]', 'UF', @$end['uf'], $atrib) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-3">
                <?= formErp::input('1[cep]', 'CEP', @$end['cep'], $atrib) ?>
            </div>
            <div class="col-9">
                <?= formErp::input('1[complemento]', 'Complemento', @$end['complemento'], $atrib) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::input('1[latitude]', 'Latitude', @$end['latitude'], $atrib) ?>
            </div>
            <div class="col">
                <?= formErp::input('1[longitude]', 'Longitude', @$end['longitude'], $atrib) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::input('1[logradouro_gdae]', 'Logradouro Prodesp', @$end['logradouro_gdae'], $atrib) ?>
            </div>
            <div class="col">
                <?= formErp::input('1[num_gdae]', 'nº Prodesp', @$end['num_gdae'], $atrib) ?>
            </div>
        </div>
        <br />
        <div style="text-align: center; padding: 40px">
            <?=
            formErp::hidden([
                '1[id_end]' => @$end['id_end'],
                '1[fk_id_pessoa]' => $id_pessoa,
                'id_pessoa' => $id_pessoa,
                'activeNav' => 3
            ])
            . formErp::hiddenToken('endereco', 'ireplace')
            . formErp::button('Salvar')
            ?>
        </div>
    </div>
</form>