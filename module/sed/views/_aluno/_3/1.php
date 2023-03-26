<?php
if (!defined('ABSPATH'))
    exit;
$readonly = null;
$siglas = sql::get('estados');
$readonly = 'readonly';
$siglas = toolErp::idName($siglas, 'sigla', 'n_estado');
?>

<form method="post">
    <div class="border">
        <div class="row">
            <div class="col">
                <?= formErp::input("1[cep]", "CEP", @$end['cep'], $readonly) ?>
            </div> 
        </div>
        <br>

        <div class="border">
            <p>
                Endereço Indicativo
            </p>
            <div class="row">
                <div class="col-8">
                    <?= formErp::input("1[logradouro]", "Logradouro", @$end['logradouro'], null) ?>
                </div>
                <div class="col-4">
                    <?= formErp::input("1[num]", "Nº", @$end['num'], null) ?>
                </div>
            </div>
        </div>
        <br>        <div class="row">
            <div class="col-8">
                <?= formErp::input("1[logradouro_gdae]", "Logradouro", @$end['logradouro_gdae'], $readonly) ?>
            </div> 
            <div class="col-4">
                <?= formErp::input("1[num_gdae]", "Nº", @$end['num_gdae'], $readonly) ?>
            </div>
        </div><br>
        <div class="row">
            <div class="col-4">
                <?= formErp::input("1[complemento]", "Comple", @$end['complemento'], $readonly) ?>
            </div>
            <div class="col-8">
                <?= formErp::input("1[bairro]", "Bairro", @$end['bairro'], $readonly) ?>
            </div>
        </div>
        <br>

        <div class="row">
            <div class="col-6">
                <?= formErp::input("1[bloco]", "Bloco", @$end['bloco'], null) ?>
            </div>
            <div class="col-6">
                <?= formErp::input("1[torre]", "Torre", @$end['torre'], null) ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-5">
                <?= formErp::input("1[cidade]", "Cidade", @$end['cidade'], $readonly) ?>
            </div>
            <div class="col-3">
                <?= formErp::select("1[uf]", $siglas, 'UF', @$end['uf'], null, null, ' disabled ') ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-6">
                <?= formErp::input("1[latitude]", "Latitude", @$end['latitude'], $readonly) ?>
            </div>
            <div class="col-6">
                <?= formErp::input("1[longitude]", "Logitude", @$end['longitude'], $readonly) ?>
            </div>
        </div><br>
        <div class="row">
            <div class="col-6">
                <?= formErp::input("1[distancia]", "Distância", @$end['distancia'], $readonly) ?>
            </div>
            <div class="col-6">
                <?= formErp::input("1[tempo]", "Tempo", @$end['tempo'], $readonly) ?>
            </div>
        </div><br> 
        <div class="col">
            <center><?= formErp::button('Enviar') ?></center>
            <?= formErp::hiddenToken('endereco', 'ireplace') ?>
            <?=
            formErp::hidden(['1[id_end]' => @$end['id_end'],
                '1[fk_id_pessoa]' => $id_pessoa,
                'id_pessoa' => $id_pessoa,
                'activeNav' => 3,
                'id_turma' => $id_turma,
                'n_turma' => @$n_turma
            ])
            ?>
        </div>
    </div>
</form>
