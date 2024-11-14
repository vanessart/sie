<?php
if (!defined('ABSPATH'))
    exit;
$setup = sql::get($model::$sistema . '_setup', '*', null, 'fetch');
@$id_plAt = sql::get($model::$sistema . '_pl', 'id_pl', ['ativo' => 1], 'fetch')['id_pl'];
@$id_plPv = sql::get($model::$sistema . '_pl', 'id_pl', ['ativo' => 2], 'fetch')['id_pl'];
$pls = sql::idNome($model::$sistema . '_pl');
?>
<div class="body">
    <div class="fieldTop">
        Configurações
    </div>
    <div class="border">
        <form method="POST">
            <div class="row">
                <div class="col">
                    <?= formErp::select('id_plAt', $pls, 'Período Letivo Ativo', $id_plAt) ?>
                </div>
                <div class="col">
                    <?= formErp::select('id_plPv', $pls, 'Período Letivo Previsto', $id_plPv) ?>
                </div>
                <div class="col">
                    <?=
                    formErp::hiddenToken('plAtPv')
                    . formErp::button('Salvar')
                    ?>
                </div>
            </div>
            <br />
        </form>
    </div>
    <br /><br />
    <div class="border">
        <form method="POST">
            <div class="row">
                <div class="col-4">
                    <?= formErp::input('1[qt_turma]', 'Quantidade de Alunos por Turma', $setup['qt_turma'], null, null, 'number') ?>
                </div>
                <div class="col-4">
                    <?= formErp::input('1[qt_curso_aluno]', 'Quantidade de Cursos por Aluno', $setup['qt_curso_aluno'], null, null, 'number') ?>
                </div>
                <div class="col">
                    <?=
                    formErp::hidden([
                        '1[id_setup]' => 1
                    ])
                    . formErp::hiddenToken($model::$sistema . '_setup', 'ireplace')
                    . formErp::button('Salvar')
                    ?>
                </div>
            </div>
            <br />
        </form>
    </div>
    <br /><br />
    <div class="row">
        <div class="col">
            <div class="border">
                <form method="POST">
                    <div class="row">
                        <div class="col-8">
                            <?= formErp::select('1[matri]', [1 => 'Sim', 0 => 'Não'], 'Liberar Matrícula', @$setup['matri']) ?>
                        </div>
                        <div class="col">
                            <?=
                            formErp::hidden([
                                '1[id_setup]' => 1
                            ])
                            . formErp::hiddenToken($model::$sistema . '_setup', 'ireplace')
                            . formErp::button('Salvar')
                            ?>
                        </div>
                    </div>
                    <br />
                </form>
            </div>
        </div>
        <div class="col">
            <div class="border">
                <form method="POST">
                    <div class="row">
                        <div class="col-8">
                            <?= formErp::select('1[matri_prev]', [1 => 'Sim', 0 => 'Não'], 'Liberar Matrícula Próximo Período', @$setup['matri_prev']) ?>
                        </div>
                        <div class="col">
                            <?=
                            formErp::hidden([
                                '1[id_setup]' => 1
                            ])
                            . formErp::hiddenToken($model::$sistema . '_setup', 'ireplace')
                            . formErp::button('Salvar')
                            ?>
                        </div>
                    </div>
                    <br />
                </form>
            </div>
        </div>
    </div>
    <br />
</div>