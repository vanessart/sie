<?php
if (!defined('ABSPATH'))
    exit;
if (!empty($id_ce)) {
    $dt_fim = $empretimo['dt_fim'];
    $id_pessoa = $empretimo['id_pessoa'];
} else {
    $empretimo = $model->pessoaFunc($id_pessoa);
}

$mala = sql::get('ge_funcionario', 'rm', ['fk_id_pessoa' => $id_pessoa]);
$mala = array_column($mala, 'rm');
$malaCount = count($mala);
$chrmeAtivo = $model->chrmeAtivo($id_pessoa);
?>
<style>
    .result button{
        width: 300px
    }
</style>
<div class="row result">
    <div class="col">
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    Nome
                </td>
                <td>
                    <?= $empretimo['n_pessoa'] ?> <?= !empty($empretimo['rm']) ? '(' . $empretimo['rm'] . ')' : '' ?>
                </td>
            </tr>
            <tr>
                <td>
                    CPF
                </td>
                <td>
                    <?= $empretimo['cpf'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    E-mail
                </td>
                <td>
                    <?= $empretimo['emailgoogle'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Escola
                </td>
                <td>
                    <?= $empretimo['n_inst'] ?>
                </td>
            </tr>
            <?php
            if (!empty($empretimo['id_ce'])) {
                ?>
                <tr>
                    <td>
                        Número de Série
                    </td>
                    <td>
                        <?= $empretimo['serial'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Modelo
                    </td>
                    <td>
                        <?= $empretimo['n_cm'] ?>
                    </td>
                </tr>
                <!--
                <tr>
                    <td>
                        Equipamento
                    </td>
                    <td>
                        <?= $empretimo['equipamento'] ?>
                    </td>
                </tr>
                -->
                <tr>
                    <td>
                        Carregador
                    </td>
                    <td>
                        <?= $empretimo['carregador'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Data da Entrega
                    </td>
                    <td>
                        <?= data::porExtenso($empretimo['dt_inicio']) ?>
                    </td>
                </tr>
                <?php
                if (!empty($empretimo['dt_fim'])) {
                    ?>
                    <tr>
                        <td>
                            Data da Devolução
                        </td>
                        <td>
                            <?= data::porExtenso($empretimo['dt_fim']) ?>
                        </td>
                    </tr>
                    <?php
                }
                if ($empretimo['obs']) {
                    ?>
                    <tr>
                        <td>
                            Observações
                        </td>
                        <td>
                            <?= $empretimo['obs'] ?>
                        </td>
                    </tr>

                    <?php
                }
            }
            ?>
        </table>
    </div>
    <div class="col">
        <?php
        if (empty($id_ce)) {
            $chromeDisp = $model->chromeDisponivel();
            ?>
            <form method="POST">
                <div class="row">
                    <div class="col">
                        <?= formErp::select('1[id_ch]', $chromeDisp, 'Chromebook', null, null, null, ' required ') ?>
                    </div>
                </div>
                <br />
                <div class="row">
                    <div class="col">
                        <?= formErp::input('1[dt_inicio]', 'Data da Entrega', date("Y-m-d"), ' required ', null, 'date') ?>
                    </div>
                </div>
                <br />
                <?php
                echo formErp::textarea('obs', null, 'Observações');
                ?>
                <br /><br />
                <div style="text-align: center">
                    <?php
                    echo formErp::hidden([
                        '1[fk_id_pessoa]' => $empretimo['id_pessoa'],
                        '1[fk_id_inst]' => null,
                        '1[fk_id_pessoa_lanc]' => toolErp::id_pessoa(),
                        '1[fk_id_cd]' => 2,
                        '1[fk_id_cs]' => 1,
                        '1[recadastro]' => 1,
                        '1[dt_cad]' => date("Y-m-d H:i:s"),
                        '1[dt_inicio]' => date("Y-m-d"),
                        '1[email_google]' => $empretimo['emailgoogle'],
                        'activeNav' => 2
                    ]);

                    echo formErp::hiddenToken('emprestaSalvaRede');
                    ?>
                    <br /><br />
                    <button type="submit" class=" btn btn-success">
                        <?php
                        if (!empty($id_ch)) {
                            echo 'Alterar a Data de Devolução';
                        } else {
                            echo 'Salvar';
                        }
                        ?>
                    </button>
                </div>
            </form>
            <?php
        } else {
            if (empty($dt_fim)) {
                ?>
                <br />
                <div style="text-align: center">
                    <form target="_blank" action="<?= HOME_URI ?>/lab/protProfRede" method="POST">
                        <?=
                        formErp::hidden([
                            'id_ch' => $id_ch,
                            'id_ce' => $id_ce
                        ])
                        ?>
                        <button class="btn btn-success" type="submit">
                            Protocolo de Retirada
                        </button>
                    </form>
                    <br />
                </div>
                <div class="text-center">
                    <button onclick="ocorr()" class="btn btn-warning">
                        Nova Ocorrência
                    </button>
                </div>
                <br />
                <div class="text-center">
                    <button onclick="devolve()" class="btn btn-info">
                        Devolução do Equipamento
                    </button>
                </div>
                <br />
                <div class="text-center">
                    <button onclick="devolveManut()" class="btn btn-primary">
                        Devolução do Equipamento
                        <br />
                        e
                        <br />
                        Envio para manutenção
                    </button>
                </div>
                <br />
                <?php
            } else {
                ?>
                <div class="text-center">
                    <form target="_blank" action="<?= HOME_URI ?>/lab/protDevProf" method="POST">
                        <?=
                        formErp::hidden(['id_ce' => $id_ce])
                        ?>
                        <button class="btn btn-success" type="submit">
                            Protocolo de Devolução
                        </button>
                    </form>
                </div>
                <br />
                <?php
            }
            ?>
            <br />
            <div class="text-center">
                <form method="POST">
                    <?=
                    formErp::hidden(['id_pessoa' => $id_pessoa, 'activeNav' => 2])
                    ?>
                    <button class="btn btn-primary" type="submit">
                        Novo empréstimo
                    </button>
                </form>
            </div>
            <br />
            <?php
            if ($malaCount > 1) {
                ?>
                <div class="border">
                    <div class="text-center">
                        Declaração de Permanência com Equipamento
                    </div>
                    <div class="row">
                        <?php
                        foreach ($mala as $rm) {
                            ?>
                            <div class="col text-center">
                                <form target="_blank" action="<?= HOME_URI ?>/lab/protMalaRh" method="POST">
                                    <?=
                                    formErp::hidden(['id_pessoa' => $id_pessoa, 'rm' => $rm, 'id_ce' => $id_ce])
                                    ?>
                                    <br /><br /><br />
                                    <button class="btn btn-success" type="submit">
                                        Com a matrícula <?= $rm ?>
                                    </button>
                                </form>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <br />
                </div>
                <?php
            }
            ?>
            <form id="form" target="frame" action="<?= HOME_URI ?>/lab/formFim" method="POST">
                <?=
                formErp::hidden([
                    'id_ce' => $id_ce
                ])
                ?>           
            </form>
            <form id="formManut" target="frame" action="<?= HOME_URI ?>/lab/formFimManut" method="POST">
                <?=
                formErp::hidden([
                    'id_ce' => $id_ce,
                    'link' => 2
                ])
                ?>           
            </form>
            <form id="ocorr" target="frame" action="<?= HOME_URI ?>/lab/def/formOcorr.php" method="POST">
                <?=
                formErp::hidden([
                    'id_ce' => $id_ce
                ])
                ?>           
            </form>
            <?php
            toolErp::modalInicio(null, null, 'fim');
            ?>
            <iframe name="frame" style="width: 100%; height: 80vh; border: none"></iframe>
            <?php
            toolErp::modalFim();
        }
        if ($chrmeAtivo < 1) {
            ?>
            <br />
            <div class="text-center">
                <form target="_blank" action="<?= HOME_URI ?>/lab/protDevRh" method="POST">
                    <?=
                    formErp::hidden(['id_pessoa' => $id_pessoa])
                    ?>
                    <button class="btn btn-warning" type="submit">
                        Declaração - RH
                    </button>
                </form>
            </div>
            <?php
        }
        if (!empty($id_ce)) {
            $ocorr = $model->ocorrencia($id_ce);
            if ($ocorr) {
                ?>
                <br />
                <table class="table table-bordered table-hover table-striped">
                    <tr>
                        <td colspan="4" style="text-align: center">
                            Ocorrências
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Dia
                        </td>
                        <td>
                            hora
                        </td>
                        <td>
                            Ocorrência
                        </td>
                        <td>
                            Observações
                        </td>
                    </tr>
                    <?php
                    foreach ($ocorr as $v) {
                        ?>
                        <tr>
                            <td>
                                <?= data::converte(substr($v['time_stamp'], 0, 10)) ?>
                            </td>
                            <td>
                                <?= substr($v['time_stamp'], 11, 5) ?>
                            </td>
                            <td>
                                <?= $v['n_ceot'] ?>
                            </td>
                            <td>
                                <?= $v['obs'] ?>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <?php
            }
        }
        ?>
        <script>
            function devolve() {
                $('#fim').modal('show');
                $('.form-class').val('');
                document.getElementById('form').submit();
            }
            function devolveManut() {
                $('#fim').modal('show');
                $('.form-class').val('');
                document.getElementById('formManut').submit();
            }
            function ocorr() {
                $('#fim').modal('show');
                $('.form-class').val('');
                document.getElementById('ocorr').submit();
            }
        </script>
    </div>
</div>

<?php
if (!empty($id_ce)) {
    $model->historico($empretimo['id_pessoa']);
}
