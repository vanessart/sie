<?php
if (!defined('ABSPATH'))
    exit;
@$end = $esc->endereco();
?>
<form method="POST">
    <div class="row">
        <div class="col">
            <?= formErp::input(NULL, 'ID', $id_inst, ' readonly ') ?>
        </div>
        <div class="col">
            <?= formErp::input('e[cie_escola]', 'CIE', $esc->_cie) ?>
        </div>
        <div class="col">
            <?= formErp::checkbox('i[terceirizada]', 1, 'Terceirizada', $esc->_terceirizada) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('i[n_inst]', 'Nome', $esc->_nome, ' required ') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-8">
            <?= formErp::input('e[ato_cria]', 'Ato de Criação', $esc->_ato_cria) ?>
        </div>
        <div class="col-4">
            <?= formErp::input('e[ato_municipa]', 'Ato de Municipalização', $esc->_ato_municipa) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('e[esc_site]', 'Site', $esc->_site) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('i[email]', 'E-mail', $esc->_email, null, null, 'email') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('e[esc_contato]', 'E-mail de Contato', $esc->_email, null, null, 'email') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-9">
            <?= formErp::input('l[logradouro]', 'Logradouro', @$end['logradouro']) ?>
        </div>
        <div class="col-3">
            <?= formErp::input('l[num]', 'Nº', @$end['num']) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-4">
            <?= formErp::input('l[cep]', 'CEP', @$end['cep']) ?>
        </div>
        <div class="col-8">
            <?= formErp::input('l[bairro]', 'Bairro', @$end['bairro']) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('l[tel1]', 'Telefone', @$end['tel1']) ?>
        </div>
        <div class="col">
            <?= formErp::input('l[tel2]', 'Telefone', @$end['tel2']) ?>
        </div>
        <div class="col">
            <?= formErp::input('l[tel3]', 'Telefone', @$end['tel3']) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::select('i[visualizar]', [1 => 'Sim', 0 => 'Não'], 'Visualizar', $esc->_visualizar) ?>
        </div>
        <div class="col">
            <?= formErp::select('i[manutencao]', [1 => 'Sim', 0 => 'Não'], 'Manutenção', $esc->_manutencao) ?>
        </div>
    </div>
    <br />
    <div style="text-align: center; padding: 20px">
        <?=
        formErp::hidden([
            'l[cidade]' => 'Barueri',
            'l[uf]' => 'SP',
            'l[id_predio]' => @$end['id_predio'],
            'id_inst' => $id_inst,
            'i[id_inst]' => $id_inst,
            'e[fk_id_inst]' => $id_inst,
            'e[id_escola]' => $esc->_id_escola,
            'activeNav' => 2
        ])
        . formErp::hiddenToken('salvaEscInst')
        . formErp::button('Salvar')
        ?>
    </div>
</form>
