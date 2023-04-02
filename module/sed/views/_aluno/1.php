<style>
    input{
        text-transform: uppercase;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
$refresh = filter_input(INPUT_POST, 'refresh', FILTER_SANITIZE_NUMBER_INT);
$dt_gdae = filter_input(INPUT_POST, 'dt_gdae', FILTER_UNSAFE_RAW);
$geral = $aluno->dadosPessoais;

foreach ($geral as $k => $v) {
    if ($k != 'obs') {
        $js[] = $k . '=' . $v;
    }
}
$readonly = 'readonly';
?>
<div id="sincN" class="alert alert-<?= empty($refresh) ? 'warning' : 'primary' ?> text-center">
    Atualizado com o SED no dia <?= data::porExtenso($geral['dt_gdae']) ?>
</div>
<div id="sinc" style="padding: 8px; display: none; text-align: center">
    <form method="POST" >
        <?=
        formErp::hidden([
            'id_pessoa' => $id_pessoa,
            'id_turma' => $id_turma,
            'refresh' => 1
        ])
        . formErp::button(
                'Baixamos novas informações da Prodesp. Clique aqui para atualizar',
                null,
                null,
                'danger'
        )
        ?>
    </form>
</div>
<br>
<form method="post">

    <div class="border">
        <p>Dados Gerais</p>

        <div class="row">
            <div class="col-3">
                <?= formErp::input('1[id_pessoa]', 'RSE', $geral['id_pessoa'], $readonly) ?>
            </div>

            <div class="col-9">
                <?= formErp::input('1[n_pessoa]', 'Nome', $geral['n_pessoa'], $readonly) ?>
            </div>
        </div> 
        <br>

        <div class="row">
            <div class="col-2">
                <?= formErp::select('1[sexo]', ['F' => "Feminino", 'M' => 'Masculino'], 'Sexo', $geral['sexo'], null, null, ' disabled ') ?>
            </div>

            <div class="col-5">
                <?= formErp::input('1[cpf]', 'CPF', $geral['cpf'], javaScript::cfpInput()) ?>
            </div>

            <div class="col-5">
                <?= formErp::input('1[sus]', 'SUS', $geral['sus'], 'maxlength="15"', null, 'number') ?><br>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <?= formErp::input('1[dt_nasc]', 'Data de nasc', $geral['dt_nasc'], $readonly, null, 'date') ?>
            </div>
            <div class="col-3">
                <?= formErp::input('1[cor_pele]', 'Cor de Pele', $geral['cor_pele'], $readonly) ?>
            </div>
        </div>  
        <br>
        <div class="row">
            <div class="col-6">
                <table style="width: 100%">
                    <tr>
                        <td style="width: 50px">
                            <?= formErp::selectDB('ge_rg_tipo', '1[fk_id_rt]', null, !empty($geral['fk_id_rt']) ? $geral['fk_id_rt'] : 1) ?>
                        </td>
                        <td>
                            <?= formErp::input('1[rg]', null, $geral['rg']) ?>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="r1" class="col-2" style="display: <?= @$geral['fk_id_rt'] == 1 ? '' : 'none' ?>">
                <?= formErp::input('1[rg_dig]', 'RG_DIG', $geral['rg_dig']) ?>
            </div>
            <div id="r2" class="col-2" style="display: <?= @$geral['fk_id_rt'] == 1 ? '' : 'none' ?>">
                <?= formErp::input('1[rg_oe]', 'RG_OE', $geral['rg_oe']) ?>
            </div>
            <div id="r3" class="col-2" style="display: <?= @$geral['fk_id_rt'] == 1 ? '' : 'none' ?>">
                <?= formErp::input('1[rg_uf]', 'RG_UF', $geral['rg_uf']) ?>
            </div>
        </div>
        <br />
    </div>
    <br>

    <div class="border">
        <p>Dados Escolares</p>

        <div class="row">
            <div class="col">
                <?= formErp::input('1[ra]', 'RA', $geral['ra'], (empty($geral['ra']) ? '' : $readonly)) ?>
            </div>

            <div class="col-2">
                <?= formErp::input('1[ra_dig]', 'RA DIGITO', $geral['ra_dig'], (empty($geral['ra']) ? '' : $readonly)) ?>
            </div>

            <div class="col">
                <?= formErp::input('1[ra_uf]', 'RA UF', $geral['ra_uf'], (empty($geral['ra']) ? '' : $readonly)) ?>
            </div>

            <div class="col">
                <?= formErp::input('1[inep]', 'INEP', $geral['inep'], null) ?>
            </div>

            <div class="col">
                <?= formErp::input('1[nis]', 'NIS', $geral['nis']) ?>
            </div>

            <br/>
        </div>
    </div> 
    <br/>
    <?php
    if (!empty($geral['certidao']) && substr($geral['certidao'], 0, 3) != '---') {
        ?>
        <div class="border">
            <p>Certidão de Nascimento</p>

            <div class="row">

                <div class="col-5">
                    <?= formErp::input('1[certidao]', 'Certidão', $geral['certidao'], $readonly) ?>
                </div>

                <div class="col">
                    <?= formErp::input('1[nacionalidade]', 'Nacionalidade', $geral['nacionalidade'], $readonly) ?>
                </div>

                <div class="col-2">
                    <?= formErp::input('1[uf_nasc]', 'UF', $geral['uf_nasc'], $readonly) ?>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-5">
                    <?= formErp::input('1[cidade_nasc]', 'Cidade Nasci', $geral['cidade_nasc'], $readonly) ?>
                </div>
            </div>
            <br>
        </div>
        <br>
        <?php
    } else {
        ?>
        <div class="border">
            <p>Certidão de Nascimento (nova) </p>
            <div class="row">

                <div class="col">
                    <?= formErp::input('1[novacert_cartorio]', 'Certidão', $geral['novacert_cartorio'], null) ?>
                </div>

                <div class="col">
                    <?= formErp::input('1[novacert_acervo]', 'Acervo', $geral['novacert_acervo'], null) ?>
                </div>

                <div class="col">
                    <?= formErp::input('1[novacert_regcivil]', 'Civil', $geral['novacert_regcivil'], null) ?>
                </div>

                <div class="col">
                    <?= formErp::input('1[novacert_ano]', 'Ano', $geral['novacert_ano'], null) ?>
                </div>
            </div><br>

            <div class="row">
                <div class="col">
                    <?= formErp::input('1[novacert_tipolivro]', 'Tipo Livro', $geral['novacert_tipolivro'], null) ?>
                </div>

                <div class="col">
                    <?= formErp::input('1[novacert_numlivro]', 'Num Livro', $geral['novacert_numlivro'], null) ?>
                </div>

                <div class="col">
                    <?= formErp::input('1[novacert_folha]', 'Folha', $geral['novacert_folha'], null) ?>
                </div>

                <div class="col">
                    <?= formErp::input('1[novacert_termo]', 'Termo', $geral['novacert_termo'], null) ?>
                </div>

                <div class="col">
                    <?= formErp::input('1[novacert_controle]', 'Controle', $geral['novacert_controle'], null) ?>
                </div>
            </div><br>
        </div> <br>
        <?php
    }
    if (is_numeric(@$geral['deficiencia'])) {
        $nec = sql::get('ge_necessidades_especiais', '*', ['id_ne' => $geral['deficiencia']], 'fetch')['n_ne']
        ?>
        <div class="border">
            <p> </p>
            <div class="row">
                <div class="col">
                    Necessidade Especial: <?= $nec ?>
                </div>
            </div>
            <br />
        </div>
        <br />
        <?php
    }
    ?>
    <div class="row">
        <div class="col" >
            <?= formErp::textarea('1[obs]', $geral['obs'], 'Observações') ?><br>
        </div>
    </div>
    <br />
    <div style="text-align: center">
        <?=
        formErp::hiddenToken('pessoa', 'ireplace')
        . formErp::hidden(['id_pessoa' => $id_pessoa,
            '1[id_pessoa]' => $id_pessoa,
            'activeNav' => 1,
            'id_turma' => $id_turma,
            'n_turma' => @$n_turma
        ])
        . formErp::button('Enviar')
        ?>
    </div>

    <?php javaScript::cpf(); ?>
</form>
<?php
if (empty($refresh)) {
    ?>
    <script>
        const data = "<?= implode('&', $js) ?>";
        fetch('<?= HOME_URI ?>/sed/alunoimport', {
            method: 'POST',
            body: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        })
                .then(resp => resp.text())
                .then(resp => {
                    console.log(resp);
                    if (resp == 1) {
                        document.getElementById("sinc").style.display = "";
                        document.getElementById("sincN").style.display = "none";
                    } else if (resp == 'error') {
                        document.getElementById("sincN").classList.remove('alert-warning');
                        document.getElementById("sincN").classList.add('alert-danger');
                        document.getElementById("sincN").innerHTML = "Não foi possível verificar se há atualizações disponíveis da Prodesp"
                    } else {
                        document.getElementById("sincN").classList.remove('alert-warning');
                        document.getElementById("sincN").classList.add('alert-primary');
                        document.getElementById("sincN").innerHTML = "Atualizado com o SED no dia " + resp;
                    }
                    console.log(resp);
                });


        const element = document.getElementById("fk_id_rt_");
        element.addEventListener("change", myFunction);

        function myFunction() {
            id = element.value;
            if (id == 1) {
                r1.style.display = '';
                r2.style.display = '';
                r3.style.display = '';
            } else {
                r1.style.display = 'none';
                r2.style.display = 'none';
                r3.style.display = 'none';
            }
        }
    </script>
    <?php
}
