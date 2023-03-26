<?php
if (!defined('ABSPATH'))
    exit;
$dt_fim = filter_input(INPUT_POST, 'dt_fim', FILTER_SANITIZE_STRING);
$devolucao = filter_input(INPUT_POST, 'devolucao', FILTER_SANITIZE_NUMBER_INT);
$id_ch = filter_input(INPUT_POST, 'id_ch', FILTER_SANITIZE_NUMBER_INT);
if (empty($id_ch)) {
    $id_ch = $model->emprestaSalvaRede();
}
$search = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
?>
<div class="body">
    <div class="fieldTop">
        Empréstimo de Chromebook - Rede
    </div>
    <?php
    if (!empty($search)) {
        $busca = $model->emprestProf($search);
        if (empty($busca)) {
            ?>
            <div class="alert alert-danger">
                Não encontramos "<?= $search ?>" em nossa base de dados
            </div>
            <?php
            $search = null;
        } elseif (count($busca) == 1) {
            $id_pessoa = current($busca)['id_pessoa'];
        } else {
            ?>
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <td>
                        Nome   
                    </td>
                    <td>
                        Matrícula
                    </td>
                    <td>
                        CPF
                    </td>
                    <td>

                    </td>
                </tr>
                <?php
                foreach ($busca as $v) {
                    ?>
                    <tr>
                        <td>
                            <?= $v['n_pessoa'] ?>   
                        </td>
                        <td>
                            <?= !empty($v['rm']) ? $v['rm'] : 'Não é funcionário' ?> 
                        </td>
                        <td>
                            <?= $v['cpf'] ?> 
                        </td>
                        <td>
                            <form method="POST">
                                <?= formErp::hidden(['id_pessoa' => $v['id_pessoa']]) ?>
                                <button class="btn btn-info">
                                    Selecionar
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td colspan="4" style="text-align: center">
                        <form method="POST">
                            <button class="btn btn-warning">
                                Reiniciar Pesquisa
                            </button>
                        </form>
                    </td>
                </tr>
            </table>
            <?php
        }
    }
    if (!empty($devolucao)) {
        if (!empty($model->_id_hist)) {
            $id_hist = $model->_id_hist;
        } else {

            @$id_hist = sql::get('lab_chrome_hist', 'id_hist', ['fk_id_ch' => $id_ch, '<' => 'id_hist'], 'fetch')['id_hist'];
        }
        ?>
        <div class="fieldTop">
            Devolução Cadastrada
        </div>
        <br /><br />
        <div class="text-center">
            <form target="_blank" action="<?= HOME_URI ?>/lab/protDevProf" method="POST">
                <?=
                formErp::hidden(['id_hist' => $id_hist])
                ?>
                <button class="btn btn-success" type="submit">
                    protocolo
                </button>
            </form>
        </div>
        <br /><br /><br />
        <div class="text-center border" >
            <div style="text-align: center">
                Declaração - RH
            </div>
            <br /><br />
            <form target="_blank" action="<?= HOME_URI ?>/lab/protDevRh" method="POST">
                <div style="text-align: left">
                    <p>
                        <?= formErp::radio('sitDev', 1, 'Devolveu o chromebook a este departamento.', null, ' required ') ?>
                    </p>
                    <p>
                        <?= formErp::radio('sitDev', 2, '<span style="font-weight: bold">Não</span> recebeu por parte da Secretaria de Educação o referido equipamento.', null, ' required ') ?>
                    </p>
                    <div class="border5">
                        <p>
                            <?= formErp::radio('sitDev', 3, '<span style="font-weight: bold">Não</span> necessita devolver o EQUIPAMENTO, pois ainda se encontra <span style="font-weight: bold">ativo</span> Com outra matrícula ', null, ' required ') ?>
                        </p>
                        <p>
                            <?= formErp::input('outraMatricula', 'Insira a outra matrícula do servidor') ?>
                        </p>
                    </div>
                </div>
                <?=
                formErp::hidden(['id_pessoa' => $id_pessoa, 'dt_fim' => $dt_fim])
                ?>
                <br /><br /><br />
                <button class="btn btn-success" type="submit">
                    Imprimir
                </button>
            </form>
        </div>
        <br /><br /><br />
        <div class="text-center">
            <button class="btn btn-warning" onclick="if (confirm('Esta ação fechará este diálogo. Tem certeza?')) {
                        document.getElementById('fechar').submit();
                    }">
                Fechar e atualizar a página principal
            </button>
            <form id="fechar" target="_parent" action="<?= HOME_URI ?>/lab/emprestRede" method="POST">
            </form>
        </div>
        <br />
        <?php
    } elseif (!empty($id_ch) || !empty($id_pessoa)) {
        if (!empty($id_ch)) {
            $ch = $model->pessoaCh($id_ch);
            $id_pessoa = $ch['id_pessoa'];
        } else {
            $ch = $model->pessoaFunc($id_pessoa);
            $chromes = $model->chromeRede(['fk_id_cd' == 5, 'limit' => 0, 'tuplas' => 100000], ' id_ch, serial as n_seria ');
            $chromes = toolErp::idName($chromes);
        }
        ?>
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    Nome
                </td>
                <td>
                    <?= $ch['n_pessoa'] ?> <?= !empty($ch['rm']) ? '(' . $ch['rm'] . ')' : '' ?>
                </td>
            </tr>
            <tr>
                <td>
                    CPF
                </td>
                <td>
                    <?= $ch['cpf'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    E-mail
                </td>
                <td>
                    <?= $ch['emailgoogle'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Escola
                </td>
                <td>
                    <?= $ch['n_inst'] ?>
                </td>
            </tr>
            <?php
            if (!empty($id_ch)) {
                ?>
                <tr>
                    <td>
                        Número de Série
                    </td>
                    <td>
                        <?= $ch['serial'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Situação
                    </td>
                    <td>
                        <?= @$ch['n_cs'] ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        Data da Entrega
                    </td>
                    <td>
                        <?= data::porExtenso($ch['dt_inicio']) ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <br />
        <?php
        if (empty($id_ch)) {
            ?>
            <form method="POST">
                <div class="row">
                    <div class="col">
                        <?= formErp::select('1[id_ch]', $chromes, 'Chromebook', null, null, null, ' required ') ?>
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
                        '1[fk_id_pessoa]' => @$ch['fk_id_pessoa'],
                        '1[fk_id_inst]' => null,
                        '1[fk_id_pessoa_lanc]' => toolErp::id_pessoa(),
                        '1[fk_id_cd]' => 2,
                        '1[fk_id_cs]' => 1,
                        '1[recadastro]' => 1,
                        '1[dt_cad]' => date("Y-m-d H:i:s"),
                        '1[dt_inicio]' => date("Y-m-d"),
                        '1[email_google]' => $ch['emailgoogle'],
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
            $model->historico($ch['fk_id_pessoa']);
        } else {
            ?>
            <br /><br />
            <div style="text-align: center">
                <form target="_blank" action="<?= HOME_URI ?>/lab/protProf" method="POST">
                    <?=
                    formErp::hidden([
                        'id_ch' => $id_ch
                    ])
                    ?>
                    <button class="btn btn-success" type="submit">
                        protocolo
                    </button>
                </form>
                <br /><br />
            </div>
            <div class="row">
                <div class="col text-center">
                    <button onclick="$('#myModal').modal('show');
                            $('.form-class').val('')" class="btn btn-info">
                        Devolução do Chromebbok
                    </button>
                </div>
                <div class="col text-center">
                    <button onclick="$('#id_cs').modal('show');
                            $('.form-class').val('')" class="btn btn-warning">
                        Alterar Situação
                    </button>
                </div>
            </div>
            <br />
            <?php
            $model->historico($ch['fk_id_pessoa']);
        }
    } elseif (empty($id_ch) && empty($search)) {
        ?>
        <form method="POST">
            <div class="fieldTop">
                Nome, E-mail, CPF ou Matrícula
            </div>
            <div class="row">
                <div class="col-10">
                    <?= formErp::input('search') ?>
                </div>
                <div class="col-2">
                    <button class="btn btn-info" type="submit">
                        Buscar
                    </button>
                </div>
            </div>
        </form>
        <?php
    }
    ?>
</div>
<?php
toolErp::modalInicio();
?>
<form method="POST">
    <div class="fieldTop">
        Encerrar processo de Empréstimo
    </div>
    <div class="row">
        <div class="col text-center">
            <?= formErp::input('dt_fim', 'Data da Devolução', date("Y-m-d"), null, null, 'date') ?>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <?= formErp::textarea('obs', null, 'Observações') ?>
        </div>
    </div>
    <br /><br />
    <div style="text-align: center">
        <?=
        formErp::hidden([
            '1[fk_id_pessoa_lanc]' => toolErp::id_pessoa(),
            '1[fk_id_cd]' => 5,
            'dt_inicio' => $ch['dt_inicio'],
            '1[fk_id_cs]' => 1,
            '1[fk_id_pessoa]' => null,
            'id_pessoa' => $ch['fk_id_pessoa'],
            '1[email_google]' => null,
            '1[id_ch]' => @$ch['id_ch'],
            '1[recadastro]' => 0,
            'devolucao' => 1,
            'id_ch' => $id_ch
        ])
        . formErp::button('Enviar')
        . formErp::hiddenToken('finalEmprestimoRede')
        ?>
    </div>
    <br /><br />
</form>
<?php
toolErp::modalFim();
toolErp::modalInicio(0, null, 'id_cs');
?>
<form action="<?= HOME_URI ?>/lab/emprestRede" target="_parent" method="POST">
    <?= formErp::select('1[fk_id_cs]', [1 => 'Regular', 4 => 'Quebrado (enviado para manutenção)'], 'Situação', $ch['fk_id_cs']) ?>
    <br /><br />
    <div style="text-align: center">
        <?=
        formErp::hidden([
            '1[fk_id_pessoa_lanc]' => toolErp::id_pessoa(),
            'id_pessoa' => $ch['fk_id_pessoa'],
            '1[id_ch]' => $id_ch,
            'id_ch' => $id_ch
        ])
        . formErp::button('Salvar')
        . formErp::hiddenToken('lab_chrome', 'ireplace')
        ?>
    </div>
    <br /><br />
</form>
<?php
toolErp::modalFim();

