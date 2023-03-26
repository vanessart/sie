<?php
if (!defined('ABSPATH'))
    exit;
$h = sqlErp::get('historico_dados_gerais', '*', ['fk_id_pessoa' => $id_pessoa], 'fetch');

if (empty($h)) {
    if (!empty($dp['certidao'])) {
        $certidaoArr = explode('-', $dp['certidao']);
        foreach ($certidaoArr as $v) {
            if (is_numeric(trim(substr($v, 1, -1)))) {
                $ar[] = $v;
            }
        }
        if (!empty($ar)) {
            $certidao = implode('.', $ar);
        } else {
            $certidao = null;
        }
    } elseif (!empty($dp['novacert_cartorio'])) {
        $certidao = $dp['novacert_cartorio'] . '.' . $dp['novacert_acervo'] . '.' . $dp['novacert_regcivil'] . '.' . $dp['novacert_ano'] . '.' . $dp['novacert_tipolivro'] . '.' . $dp['novacert_numlivro'] . '.' . $dp['novacert_folha'] . '.' . $dp['novacert_termo'] . '-' . $dp['novacert_controle'];
    } else {
        $certidao = null;
    }
    $ins['fk_id_hte'] = 1;
    $ins['escola'] = toolErp::n_inst();
    if (empty($dp['n_social'])) {
        $ins['nome'] = $dp['n_pessoa'];
    } else {
        $ins['n_social'] = $dp['n_pessoa'];
        $ins['nome'] = $dp['n_social'];
    }
    $ins['tp_doc'] = 'RG';
    $ins['rg'] = !empty($dp['rg']) ? ltrim($dp['rg'], 0) : null;
    $ins['rg_oe'] = !empty($dp['rg_oe']) ? ltrim($dp['rg_oe'], 0) : null;
    $ins['rg_dig'] = !empty($dp['rg_dig']) ? ltrim($dp['rg_dig'], 0) : null;
    $ins['rg_uf'] = !empty($dp['rg_uf']) ? ltrim($dp['rg_uf'], 0) : null;
    $ins['dt_nasc'] = $dp['dt_nasc'];
    $ins['certidao'] = $certidao;
    $ins['sexo'] = $dp['sexo'];
    $ins['cidade_uf_nasc'] = $dp['cidade_nasc'] . ' - ' . $dp['uf_nasc'];
    $ins['nacionalidade'] = $dp['nacionalidade'];
    $ins['fk_id_pessoa'] = $dp['id_pessoa'];
    $ins['ra'] = $dp['ra'] . '-' . $dp['ra_dig'] . ' ' . $dp['ra_uf'];
    $ins['cpf'] = $dp['cpf'];
    $ins['token'] = substr(uniqid(), 0, 6);
    $model->db->insert('historico_dados_gerais', $ins, 1);
    $h = sqlErp::get('historico_dados_gerais', '*', ['fk_id_pessoa' => $id_pessoa], 'fetch');
    if (empty($h)) {
        ?>
        <div class="alert alert-danger">
            Algo deu errado, procure o Depto de Informática
        </div>
        <?php
        exit();
    }
}
?>
<form method="POST">
    <div class="row">
        <div class="col-4">
            <?= formErp::selectDB('historico_tipo_ensino', '1[fk_id_hte]', 'Tipo de Ensino', $h['fk_id_hte'], null, null, null, ['at_hte' => 1]) ?>
        </div>
        <div class="col-4" style="text-align: right">
            <button onclick="vida.submit()" type="button" class="btn btn-info">
                Voltar para a Vida Escolar
            </button>     
        </div>
        <div class="col-4" style="text-align: right">
            <button type="button" class="btn btn-danger" onclick="restaura()">
                Restaurar Dados Originais
            </button>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[escola]', 'Escola', $h['escola']) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[nome]', 'Nome do Aluno', $h['nome']) ?>
        </div>
    </div>
    <br />
    <?php
    if (!empty($h['n_social'])) {
        ?>
        <div class="row">
            <div class="col">
                <?= formErp::input('1[n_social]', 'Nome Social do Aluno', $h['n_social']) ?>
            </div>
        </div>
        <br />
        <?php
    }
    ?>
    <div class="row">
        <div class="col-4">
            <?= formErp::input(null, 'RSE', $dp['id_pessoa'], ' readonly ') ?>
        </div>
        <div class="col-4">
            <?= formErp::input('1[ra]', 'RA', $h['ra']) ?>
        </div>
        <div class="col-4">
            <?= formErp::input('1[cpf]', 'CPF', $h['cpf']) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-3">
            <table>
                <tr>
                    <td>
                        <?= formErp::select('1[tp_doc]', ['RG' => 'RG', 'RNE' => 'RNE'], null, $h['tp_doc']) ?>
                    </td>
                    <td>
                        <?= formErp::input('1[rg]', null, $h['rg']) ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-3">
            <?= formErp::input('1[rg_dig]', 'RG Digito', $h['rg_dig']) ?>
        </div>
        <div class="col-3">
            <?= formErp::input('1[rg_oe]', 'RG O. E.', $h['rg_oe']) ?>
        </div>
        <div class="col-3">
            <?= formErp::input('1[rg_uf]', 'RG UF', $h['rg_uf']) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-4">
            <?= formErp::input('1[dt_nasc]', 'Dt. Nascimento', $h['dt_nasc'], null, null, 'date') ?>
        </div>
        <div class="col-8">
            <?= formErp::input('1[certidao]', 'Certidão de Nascimento', $h['certidao']) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-2">
            <?= formErp::select('1[sexo]', toolErp::sexo(), 'Sexo', $h['sexo']) ?>
        </div>
        <div class="col">
            <?= formErp::input('1[cidade_uf_nasc]', 'Cidade/Uf Nasc.', $h['cidade_uf_nasc']) ?>
        </div>
        <div class="col">
            <?= formErp::input('1[nacionalidade]', 'Nacionalidade', $h['nacionalidade']) ?>
        </div>
    </div>
    <br />

    <div style="text-align: center; padding: 30px">
        <?=
        formErp::hidden([
            'id_pessoa' => $id_pessoa,
            '1[fk_id_pessoa]' => $id_pessoa,
            '1[id_dg]' => $h['id_dg']
        ])
        . formErp::hiddenToken('historico_dados_gerais', 'ireplace')
        . formErp::button('Salvar')
        ?>
    </div>
</form>
<form id="restaurar" method="POST">
    <?=
    formErp::hidden([
        '1[id_dg]' => $h['id_dg'],
        'id_pessoa' => $id_pessoa,
    ])
    . formErp::hiddenToken('historico_dados_gerais', 'delete')
    ?>
</form>
<form id="vida" action="<?php echo HOME_URI ?>/sed/aluno" name="perfil" method="POST">
    <input type="hidden" name="id_pessoa" value="<?= $id_pessoa ?>" />
    <input type="hidden" name="activeNav" value="4" />             
</form>
<script>
    function restaura() {
        if (confirm("Esta ação irá sobrescrever os dados gerais. Continuar?")) {
            restaurar.submit();
        }
    }
</script>
