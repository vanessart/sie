<?php
if (!defined('ABSPATH'))
    exit;
$atrib = '';
?>
<form method="POST">
    <br />
    <div class="row">
        <div class="col-3">
            <?= formErp::input(null, 'ID', @$pess['id_pessoa'], ' readonly ' . $atrib) ?>
        </div>
        <div class="col-9">
            <?= formErp::input('1[n_pessoa]', 'Nome', @$pess['n_pessoa'], $atrib) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[n_social]', 'Nome Social', @$pess['n_social'], $atrib) ?>
        </div>
        <div class="col">
            <?= formErp::input('1[cpf]', 'CPF', @$pess['cpf'], $atrib) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[emailgoogle]', 'E-mail Google', @$pess['emailgoogle'], $atrib) ?>
        </div>
        <div class="col">
            <?= formErp::input('1[email]', 'E-mail Pessoal', @$pess['email'], $atrib) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[dt_nasc]', 'Data Nasc.', @$pess['dt_nasc'], $atrib, null, 'date') ?>
        </div>
        <div class="col">
            <?= formErp::select('1[sexo]', ['M'=>'Masculino', 'F'=>'Feminino'], 'Sexo', @$pess['sexo']) ?>
        </div>
        <div class="col">
            <?= formErp::input('1[cor_pele]', 'Cor da Pele', @$pess['cor_pele'], $atrib) ?>
        </div>
        <div class="col">
            <?= formErp::input('1[nacionalidade]', 'Nacionalidade', @$pess['nacionalidade'], $atrib) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-5">
            <?= formErp::input('1[rg]', 'RG', @$pess['rg'], $atrib) ?>
        </div>
        <div class="col-2">
            <?= formErp::input('1[rg_oe]', 'RG OE', @$pess['rg_oe'], $atrib) ?>
        </div>
        <div class="col-2">
            <?= formErp::input('1[rg_uf]', 'RG UF', @$pess['rg_uf'], $atrib) ?>
        </div>
        <div class="col-3">
            <?= formErp::input('1[dt_rg]', 'Data RG.', @$pess['dt_rg'], $atrib, null, 'date') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-4">
            <?= formErp::input('1[ra]', 'RA', @$pess['ra'], $atrib) ?>
        </div>
        <div class="col-2">
            <?= formErp::input('1[ra_dig]', 'RA Dig.', @$pess['ra_dig'], $atrib) ?>
        </div>
        <div class="col-2">
            <?= formErp::input('1[ra_uf]', 'RA UF', @$pess['ra_uf'], $atrib) ?>
        </div>
        <div class="col-4">
            <?= formErp::input('1[inep]', 'Inep', @$pess['inep'], $atrib) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[sus]', 'SUS', @$pess['sus'], $atrib) ?>
        </div>
        <div class="col">
            <?= formErp::input('1[nis]', 'NIS', @$pess['nis'], $atrib) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-4">
            <?= formErp::input('1[cidade_nasc]', 'Cidade Nasc.', @$pess['cidade_nasc'], $atrib) ?>
        </div>
        <div class="col-3">
            <?= formErp::input('1[uf_nasc]', 'UF Nasc.', @$pess['uf_nasc'], $atrib) ?>
        </div>
        <div class="col-5">
            <?= formErp::input('1[google_user_id]', 'ID de segurança do APP dos pais', @$pess['google_user_id'], $atrib) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[certidao]', 'Certidão Antiga', @$pess['certidao'], $atrib) ?>
        </div>
    </div>
    <br />
    <div class="border">
        <div class="fieldTop">
            Certidão Nova
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::input('1[novacert_cartorio]', 'Cartório', @$pess['novacert_cartorio'], $atrib) ?>
            </div>
            <div class="col">
                <?= formErp::input('1[novacert_acervo]', 'Acervo', @$pess['novacert_acervo'], $atrib) ?>
            </div>
            <div class="col">
                <?= formErp::input('1[novacert_regcivil]', 'Registro Civil', @$pess['novacert_regcivil'], $atrib) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::input('1[novacert_ano]', 'Ano', @$pess['novacert_ano'], $atrib) ?>
            </div>
            <div class="col">
                <?= formErp::input('1[novacert_tipolivro]', 'Tipo do Livro', @$pess['novacert_tipolivro'], $atrib) ?>
            </div>
            <div class="col">
                <?= formErp::input('1[novacert_numlivro]', 'Nº do Livro', @$pess['novacert_numlivro'], $atrib) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?= formErp::input('1[novacert_folha]', 'Folha', @$pess['novacert_folha'], $atrib) ?>
            </div>
            <div class="col">
                <?= formErp::input('1[novacert_termo]', 'Termo', @$pess['novacert_termo'], $atrib) ?>
            </div>
            <div class="col">
                <?= formErp::input('1[novacert_controle]', 'Controle', @$pess['novacert_controle'], $atrib) ?>
            </div>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::textarea('1[obs]', @$pess['obs'], 'Observações') ?>
        </div>
    </div>
    <br />
    <div style="text-align: center; padding: 40px">
        <?=
        formErp::hidden([
            '1[id_pessoa]' => $id_pessoa,
            'id_pessoa' => $id_pessoa,
        ])
        . formErp::hiddenToken('pessoaSet')
        . formErp::button('Salvar')
        ?>
    </div>
</form>
