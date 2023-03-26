<style>
    .sigla{
        margin: 0; 
        padding: 5px;
        text-align: center;
        width: 30px;
    }
    .sigla1{
        margin: 0; 
        padding: 5px;
        text-align: center;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
$tiposAvaliacao = toolErp::idName($model->tiposAvaliacao());
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_STRING);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_STRING);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_STRING);
$atual_letiva = filter_input(INPUT_POST, 'atual_letiva', FILTER_SANITIZE_STRING);
$n_turma = filter_input(INPUT_POST, 'n_turma', FILTER_SANITIZE_STRING);
$n_disc = filter_input(INPUT_POST, 'n_disc', FILTER_SANITIZE_STRING);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_STRING);
if ($id_disc == 'nc') {
    $disciplina = sql::idNome('ge_disciplinas');
}
$hidden = [
    'id_inst' => $id_inst,
    'id_pl' => $id_pl,
    'id_turma' => $id_turma,
    'id_curso' => $id_curso,
    'n_turma' => $n_turma,
    'n_disc' => $n_disc,
    'id_disc' => $id_disc
];
foreach ($hidden as $v) {
    if (is_null($v)) {
        ?>
        <meta http-equiv="refresh" content="0; URL='<?= HOME_URI ?>/profe/relatorioNotaProf'"/>
        <?php
        exit();
    }
}
$alunos = ng_escola::alunoPorTurma($id_turma);
$letiva = $model->letivaDados($id_turma);
if (empty($atual_letiva)) {
    $atual_letiva = $letiva['atual_letiva'];
}
$instrumentos = $model->retornaInstrumentosAvaliativos($id_pl, $id_turma, $atual_letiva, $id_disc);
foreach ($instrumentos as $key => $value) {
    foreach ($alunos as $k => $v) {
        if (isset($value->notas)) {
            $notasArr = (array) $value->notas;
            $alunos[$k]['notasAluno'][$value->uniqid] = $notasArr[$v['id_pessoa']];
        }
    }
}
?>
<div class="body">
    <div class="row">
        <div class="col-10">
            <?= formErp::selectNum('atual_letiva', [1, $letiva['atual_letiva']], $letiva['un_letiva'], $atual_letiva, 1, $hidden) ?>
        </div>
        <div class="col-2">
            <a href="<?= HOME_URI ?>/profe/relatorioNotaProf" class="btn btn-primary">
                Voltar
            </a>
        </div>
    </div>
    <div style="font-weight: bold; font-size: 2.2em; text-align: center; padding: 20px">
        Relatório de notas do <?= $atual_letiva ?> º <?= $letiva['un_letiva'] ?> <?= date('Y') ?>
    </div>

    <div style="font-weight: bold; font-size: 2.0em; text-align: center; padding: 20px">
        <?= $n_turma ?> - <?= $n_disc ?>
    </div>
    <div class="row">
        <div class="col-4">
            <button class="btn btn-info" onclick="edit()">
                Nova Avaliação
            </button>
        </div>
        <div class="col-6">
            <?php
            if (count($instrumentos) < 3) {
                ?>
                <div class="alert alert-warning" style="font-weight: bold; text-align: center">
                    Lance, no mínimo, três avaliações
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <br />
    <?php
    if (count($instrumentos) > 0) {
        ?>
        <table class="table table-bordered table-hover table-striped border">
            <tr>
                <td colspan="<?= $id_disc == 'nc' ? 8 : 7 ?>" style="text-align: center">
                    Avaliações
                </td>
            </tr>
            <tr>
                <td style="width: 50px">
                    Sigla
                </td>
                <?php
                if ($id_disc == 'nc') {
                    ?>
                    <td>
                        Disciplina
                    </td>
                    <?php
                }
                ?>
                <td>
                    Avaliação
                </td>
                <td>
                    Data
                </td>
                <td>
                    Tipo
                </td>
                <td style="width: 100px">
                    Ativo
                </td>
                <td style="width: 100px">

                </td>
                <td style="width: 100px">

                </td>
            </tr>
            <?php
            foreach ($instrumentos as $v) {
                if ($v->ativo != 1) {
                    $class = 'danger';
                } elseif (!empty($v->notas)) {
                    $class = 'success';
                } else {
                    $class = 'warning';
                }
                ?>
                <tr>
                    <td>
                        <div class="alert alert-<?= $class ?> sigla" >
                            <?= substr($v->instrumentoNome, 0, 2) ?>
                        </div>
                    </td>
                    <?php
                    if ($id_disc == 'nc') {
                        ?>
                        <td>
                            <?= @$disciplina[$v->id_disc_nc] ?>
                        </td>
                        <?php
                    }
                    ?>
                    <td>
                        <?= $v->instrumentoNome ?>
                    </td>
                    <td>
                        <?= data::converteBr($v->dataAvaliacao) ?>
                    </td>
                    <td>
                        <?= $tiposAvaliacao[$v->instrumentoTipo] ?>
                    </td>
                    <td>
                        <?= toolErp::simnao($v->ativo) ?>
                    </td>
                    <td>
                        <button class="btn btn-primary" onclick="lanc('<?= $v->uniqid ?>')">
                            Lançamento
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-info" onclick="edit('<?= $v->uniqid ?>')">
                            Editar
                        </button>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <div class="row">
            <div class="col">
                <div class="alert alert-success sigla1">
                    Ativo e Lançado
                </div>
            </div>
            <div class="col">
                <div class="alert alert-danger sigla1">
                    Inativo
                </div>
            </div>
            <div class="col">
                <div class="alert alert-warning sigla1">
                    Não Lançado 
                </div>
            </div>
        </div>
        <br />
        <table class="table table-bordered table-hover table-striped border">
            <tr>
                <td style="font-weight: bold">
                    Nº
                </td>
                <td style="font-weight: bold">
                    RSE
                </td>
                <td style="font-weight: bold">
                    Nome
                </td>
                <?php
                foreach ($instrumentos as $value) {
                    if ($value->ativo != 1) {
                        $class = 'danger';
                    } elseif (!empty($value->notas)) {
                        $class = 'success';
                    } else {
                        $class = 'warning';
                    }
                    ?>
                    <td>
                        <div class="alert alert-<?= $class ?> sigla">
                            <?= substr($value->instrumentoNome, 0, 2) ?>
                        </div>
                    </td>
                    <?php
                }
                ?>
                <td>
                    <b>Média</b>
                </td>
            </tr>
            <?php
            foreach ($alunos as $key => $value) {
                $total = [];
                ?>
                <tr>
                    <td>

                        <?= $value['chamada'] ?>
                    </td>

                    <td>
                        <?= $value['ra'] ?>
                    </td>
                    <td>
                        <?= $value['n_pessoa'] ?>
                    </td>
                    <?php foreach ($instrumentos as $k => $v) {
                        ?>
                        <?php
                        if ($v->ativo == 1 && !empty($v->notas)) {
                            $total[] = @$value['notasAluno'][$v->uniqid]
                            ?>
                            <td>
                                <?= @$value['notasAluno'][$v->uniqid] ?>
                            </td>
                            <?php
                        } else {
                            ?>
                            <td style="text-align: center">
                                -
                            </td>
                            <?php
                        }
                    }
                    ?>
                    <td>
                        <?php
                        if (!empty($total)) {
                            echo round((array_sum($total) / count($total)), 1);
                        } else {
                            echo '0';
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <?php
    } else {
        ?>
        <div class="alert alert-danger" role="alert">
            Não há instrumentos avaliativos registrado nesse <?= $letiva['un_letiva'] ?>
        </div>
        <?php
    }
    ?>
</div>
<form target="frame" id="form" action="<?= HOME_URI ?>/profe/def/instrumentoAvaliativo.php" method="POST">
    <input type="hidden" id="uniqid" name="uniqid" value="" />
    <?=
    formErp::hidden($hidden)
    . formErp::hidden([
        'origem' => '/profe/notaTabela',
        'atual_letiva' => $atual_letiva
    ])
    ?>
</form>
<form target="frame" id="formAval" action="<?= HOME_URI ?>/profe/def/formAval.php" method="POST">
    <input type="hidden" id="uniqidAval" name="uniqid" value="" />
    <?=
    formErp::hidden($hidden)
    . formErp::hidden([
        'origem' => '/profe/notaTabela',
        'atual_letiva' => $atual_letiva
    ])
    ?>
</form>
<?php
toolErp::modalInicio();
?>
<iframe style="width: 100%; border: none; height: 80vh" name="frame"></iframe>
    <?php
    toolErp::modalFim();
    ?>
<script>
    function edit(id) {
        if (id) {
            uniqid.value = id;
        } else {
            uniqid.value = "";
        }
        form.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
    function lanc(id) {
        uniqidAval.value = id;
        formAval.submit();
        $('#myModal').modal('show');
        $('.form-class').val('');
    }
</script>