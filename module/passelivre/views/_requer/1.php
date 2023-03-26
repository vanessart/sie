<?php
if (!defined('ABSPATH'))
    exit;
$tipo_cadastro = $model-> tpcadastro();
        
?>

<form method="POST">
    <div class="row">
        
        <div class="col-2">
            <?= formErp::input(null, 'ID', @$req['id_passelivre'], ' readonly ') ?>
        </div>
          <div class="col-3">
            <?= formErp::input('1[ra]', 'RA', @$req['ra'], ' required ') ?>
        </div>
        <div class="col-7">
            <?= formErp::input('1[nome]', 'Nome', @$req['nome'], ' required ') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[cpf]', 'CPF', @$req['cpf'], ' required ' . javaScript::cfpInput(), 'Só Número') ?>
        </div>
        <div class="col">
            <?= formErp::input('1[rg]', 'RG', @$req['rg'], ' required ') ?>
        </div>
        <div class="col">
            <?= formErp::input('1[reg_mun]', 'Registro Municipal', @$req['reg_mun'], ' required ') ?>
        </div>
        
        <div class="col">
            <?= formErp::input('1[dt_nasc]', 'Dt. Nasc.', data::converteBr(@$req['dt_nasc']), ' required ', null, ' date ') ?>
        </div>
        <div class="col">
            <?= formErp::select('1[sexo]', ['FEMININO' => 'FEMININO', 'MASCULINO' => 'MASCULINO'], 'sexo', @$req['sexo'], null, null, ' required ') ?>
        </div>
        <div class="row">
        </div>
    </div>
    <br>
            <!-- Div de cadastro Endereço -->
         <div class="row">   
        <div class="col-7">
            <?= formErp::input('1[logradouro]', 'Logradouro', @$req['logradouro'], ' required ') ?>
        </div>
        <div class="col-2">
            <?= formErp::input('1[num]', 'Número.', @$req['num'], ' required ', null, 'number') ?>
        </div>
        <div class="col-3">
            <?= formErp::input('1[complemento]', 'Complemento', @$req['complemento']) ?>
        </div>
         </div>
            <br>
    <div class="row">   
        <div class="col-7">
            <?= formErp::input('1[bairro]', 'Bairro', @$req['bairro'], ' required ') ?>
        </div>
        <div class="col-2">
            <?= formErp::input('1[municipio]', 'Município', @$req['municipio'], ' required ') ?>
        </div>
        <div class="col-3">
            <?= formErp::input('1[cep]', 'CEP', @$req['cep'],' required ') ?>
        </div>
         </div>
            <br>   <!-- Div Responsaveis -->
    <div class="row">   
        <div class="col-4">
            <?= formErp::input('1[nome_mae]', 'Nome da Mãe', @$req['nome_mae']) ?>
        </div>
        <div class="col-4">
            <?= formErp::input('1[nome_pai]', 'Nome do Pai', @$req['nome_pai'],) ?>
        </div>
        <div class="col-4">
            <?= formErp::input('1[nome_resp]', 'Responsável', @$req['nome_resp'])?>
        </div>
         </div>
            <br>
            <div class="row">   <!-- Integraçãot -->
        <div class="col-3">
            <?= formErp::input('1[qte_pessoa]', 'Quant. Moradores na Casa', @$req['qte_pessoa'], ' required ')?>
        </div>
        <div class="col-3">
            <?= formErp::input('1[renda_familiar]', 'Renda Familiar', @$req['renda_familiar'], ' required ') ?>
        </div>
        <div class="col-3">
            <?= formErp::select('1[util_integracao]', ['SIM' => 'SIM', 'NÃO' => 'NÃO'], 'Utiliza Integração', @$req['util_integracao']) ?>
        </div>
        <div class="col-3">
            <?php  echo formErp::select('1[fk_tipo_cadastro]', $tipo_cadastro, 'Tipo de Cadastro', @$req['tipo_cadastro'])?>
        </div>
         </div>
        <br>
            <div class="row">   <!-- Aluno Especial -->
                <!--
        <div class="col-3">
            <?= formErp::select('1[acompanhante]', ['SIM' => 'SIM', 'NÃO' => 'NÃO'], 'Utiliza Acompanhante', @$req['acompanhante']) ?>
        </div>
        <div class="col-4">
            <?= formErp::input('1[nome_acompanhante]', 'Nome do Acompanhante', @$req['nome_acompanhante']) ?>
        </div>
                -->
        </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[distancia_escola]', 'Distância da Escola em KM', @$req['distancia_escola'], ' required ', 'Só valores. Ex: 2,6') ?>
        </div>
        <div class="col">

        </div>
        <div class="col">

        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[observacao]', 'Observações', @$req['observacao']) ?>
            </div>
    </div>
    <div style="text-align: center; padding: 20px">
        <?=
        formErp::hidden([
            'id_passelivre' => $id_passelivre,
            '1[id_passelivre]' => $id_passelivre,
            '1[cie]' => $cie,
            'cie' => $cie
        ])
        . formErp::hiddenToken('pl_passelivre', 'ireplace')
        . formErp::button('Salvar')
        ?>

    </div>
       
</form>

<?php
javaScript::cep();
?>


