<?php
if (!defined('ABSPATH'))
    exit;
?>
<form method="POST">
    <div class="row">
        <div class="col">
            <?= formErp::input(null, 'CPF', $dados['id_cpf'], ' disabled ') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[rg]', 'RG*', $dados['rg'], ' required ') ?>
        </div>
        <div class="col">
            <?= formErp::input('1[rg_dig]', 'Dig. RG*', $dados['rg_dig'], ' required ') ?>
        </div>
        <div class="col">
            <?= formErp::input('1[rg_oe]', 'O.Exp. RG*', $dados['rg_oe'], ' required ') ?>
        </div>
        <div class="col">
            <?= formErp::input('1[dt_rg]', 'Data RG*', $dados['dt_rg'], ' required ', null, 'date') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[nome]', 'Nome*', strtoupper($dados['nome']), ' required ') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[nome_social]', 'Nome Social', strtoupper($dados['nome_social']), null) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[mae]', 'Nome da Mãe*', strtoupper($dados['mae']), ' required ') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[pai]', 'Nome do Pai', strtoupper($dados['pai']), '  ') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[municipio_nasc]', 'Município de Nascimento*', strtoupper($dados['municipio_nasc']), ' required ') ?>
        </div>
        <div class="col">
            <?= formErp::input('1[estado_nasc]', 'Estado de Nascimento*', strtoupper($dados['estado_nasc']), ' required ') ?>
        </div>
        <div class="col">
            <?= formErp::input('1[pais_nasc]', 'País de Nascimento*', strtoupper($dados['pais_nasc']), ' required ') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-8">
            <?= formErp::input('1[nacionalidade]', 'Nacionalidade*', strtoupper($dados['nacionalidade']), ' required ') ?>
        </div>
        <div class="col-4">
            <?= formErp::selectDB('sed_raca_cor', '1[fk_id_rc]', 'Raça Cor*', $dados['fk_id_rc'], null, null, null, null, ' required ') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::select('1[sexo]', ['F' => 'Feminnino', 'M' => 'Masculino'], 'Sexo*', $dados['sexo'], null, null, ' required ') ?>
        </div>
        <div class="col">
            <?= formErp::input('1[dt_nasc]', 'D. Nasc.*', $dados['dt_nasc'], ' required ', null, 'date') ?>
        </div>
        <div class="col">
            <?= formErp::selectDB('civil', '1[fk_id_civil]', 'Estado Civil*', $dados['fk_id_civil'], null, null, null, null, ' required ') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[email]', 'E-mail', $dados['email'], null, null, 'email') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::selectNum('1[filhos]', [0, 20], 'Quantidade de filhos menores de 18 anos', $dados['filhos'], null, null, null) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[fixo]', 'Fixo com DDD', $dados['fixo'], ' onkeyup="mascara(this, mtel)" maxlength="15" ', null, 'tel') ?>
        </div>
        <div class="col">
            <?= formErp::input('1[celular]', 'Celular com DDD', $dados['celular'], ' onkeyup="mascara(this, mtel)" maxlength="15" ', null, 'tel') ?>
        </div>
        <div class="col">
            <?= formErp::checkbox('1[whatsapp]', 1, 'O celular tem Whatsapp', $dados['whatsapp'], null) ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-4">
            <?= formErp::input('1[cep]', 'CEP*', $dados['cep'], ' onblur="pesquisacep(this.value);" required id="cep"  size="10" maxlength="9" ') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[logradouro]', 'Logradouro*', strtoupper($dados['logradouro']), ' required id="rua" ') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-3">
            <?= formErp::input('1[num]', 'Nº', $dados['num'], ' required id="num" ') ?>
        </div>
        <div class="col-9">
            <?= formErp::input('1[bairro]', 'Bairro*', strtoupper($dados['bairro']), ' required id="bairro" ') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::input('1[complemento]', 'Complemento', strtoupper($dados['complemento']), ' id="complemento" ') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-9">
            <?= formErp::input('1[cidade]', 'Cidade*', strtoupper($dados['cidade']), ' required id="cidade" ') ?>
        </div>
        <div class="col-3">
            <?= formErp::input('1[uf]', 'UF*', strtoupper($dados['uf']), ' required id="uf" ') ?>
        </div>
    </div>
    <br />
    <?php
    if ($dados['filhos'] > 0) {
        ?>
        <div class="border">
            <div style="text-align: justify">
                Certidões de nascimento
            </div>
            <br />
            <?php
            $fu = sql::get('inscr_upload_filhos', '*', ['cpf' => $dados['id_cpf'], 'fk_id_evento' => $model->evento]);
            if ($fu) {
                foreach ($fu as $y) {
                    ?>
                    <div class="row">
                        <div class="col">
                            <table class="table table-bordered table-hover table-striped">
                                <tr>
                                    <td>
                                        <?= $y['nome_origin'] ?>
                                    </td>
                                    <td style="width: 100px">
                                        <?php
                                        if (file_exists(ABSPATH . '/pub/inscrOnline/' . $y['link'])) {
                                            $link = HOME_URI . '/pub/inscrOnline/' . $y['link'];
                                        } else {
                                            $link = HOME_URI . '/pub/inscrOnline2/' . $y['link'];
                                        }
                                        ?>
                                        <button onclick="ver('<?= $link ?>')" type="button" class="btn btn-primary">
                                            Visualizar
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <?php
    }
    ?>
    <div style="text-align: center; padding: 30px; width: 300px; margin: auto">
        <?= formErp::checkbox('1[deferido]', 1, 'Dados Pessoais Deferidos', $dados['deferido']) ?>
    </div>
    <div style="text-align: center; padding: 10px">
        <?=
        formErp::hiddenToken('inscr_incritos_' . $evento, 'ireplace')
        . formErp::hidden([
            'id_ec' => $id_ec,
            '1[id_cpf]' => $dados['id_cpf'],
            'activeNav' => 2
        ])
        . formErp::button('Salvar')
        ?>
    </div>
</form>
<?php
toolErp::modalInicio(null, 'modal-fullscreen');
?>
<iframe style="width: 100%; height: 80vh; border: none" id="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function ver(link) {
        frame.src = link;
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>