<?php
if (!defined('ABSPATH'))
    exit;
$formulario = $model->_formAdm;
$cursoSeg = gt_escolas::cursosPorSegmentoFase(tool::id_inst());
foreach ($cursoSeg as $c) {
    if ($c) {
        foreach ($c as $k => $v) {
            $curso[$k] = $v;
        }
    }
}

$dash = $model->cursoDashboard($formulario);
$escolaridade = sql::idNome('escolaridade');
//$curso = $model->CursoInscr($formulario);
$emprego = sql::idNome('sit_emprego');
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<div class="body">
    <br />
    <div class="row">
        <div class="col">
            <form action="<?= HOME_URI ?>/quali/defere" method="POST">
                <input type="hidden" name="situacao" value="" />
                <button class="btn btn-outline-primary" style="width: 100%; color: black; font-weight: bold">
                    Inscritos: <?= intval(@$dash['total']) ?>
                </button>
            </form>
        </div>
        <div class="col">
            <form action="<?= HOME_URI ?>/quali/defere" method="POST">
                <input type="hidden" name="situacao" value="X" />
                <button class="btn btn-outline-warning" style="width: 100%; color: black; font-weight: bold">
                    Aguardando Deferimento: <?= intval(@$dash['sit'][null]) ?>
                </button>
            </form>
        </div>
        <div class="col">
            <form action="<?= HOME_URI ?>/quali/defere" method="POST">
                <input type="hidden" name="situacao" value="1" />
                <button class="btn btn-outline-success" style="width: 100%; color: black; font-weight: bold">
                    Deferidos: <?= intval(@$dash['sit'][1]) ?>
                </button>
            </form>
        </div>
        <div class="col">
            <form action="<?= HOME_URI ?>/quali/defere" method="POST">
                <input type="hidden" name="situacao" value="2" />
                <button class="btn btn-outline-danger" style="width: 100%; color: black; font-weight: bold">
                    Indeferidos: <?= intval(@$dash['sit'][2]) ?>
                </button>
            </form>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col">
            <div class="border4">
                <?php
                include ABSPATH . '/module/quali/views/_gerente/1.php';
                ?>
            </div>
        </div>
    </div>
    <br />
    <div class="border4">
        <div style="font-weight: bold; padding: 10px">
            Inscrições
        </div>
        <br />
        <div class="row">
            <div class="col">
                <?php
                if (count($curso) > 1) {
                    ?>
                    <div class="border4" style="width: 95%; margin: 0 auto">
                        <div style="text-align: center; font-weight: bold; padding: 4px">
                            Cursos 
                        </div>
                        <br />
                        <table class="table table-bordered table-hover table-striped">
                            <tr>
                                <td>
                                    Curso
                                </td>
                                <td>
                                    1º Opção
                                </td>
                                <td>
                                    2º Opção
                                </td>
                                <td>
                                    Deferidos
                                </td>
                            </tr>
                            <?php
                            foreach ($curso as $id => $cur) {
                                ?>
                                <tr>
                                    <td>
        <?= $cur ?>
                                    </td>
                                    <td style="text-align: center">
        <?= intval(@$dash['curso'][$id]) ?>
                                    </td>
                                    <td style="text-align: center">
        <?= intval(@$dash['curso2'][$id]) ?>
                                    </td>
                                    <td style="text-align: center">
        <?= intval(@$dash['cursoAprovado'][$id]) ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="col">
                <?php
                if (count($dash['emprego']) > 1) {
                    ?>
                    <div class="border4" style="width: 95%; margin: 0 auto">
                        <div style="text-align: center; font-weight: bold; padding: 4px">
                            Situação Empregatícia
                        </div>
                        <br />
                        <table class="table table-bordered table-hover table-striped">
                            <tr>
                                <td>

                                </td>
                                <td>
                                    Total
                                </td>
                                <td>
                                    Deferidos
                                </td>
                            </tr>
                            <?php
                            foreach ($emprego as $k => $v) {
                                ?>
                                <tr>
                                    <td>
        <?= $v ?>
                                    </td>
                                    <td>
        <?= intval(@$dash['emprego'][$k]) ?>
                                    </td>
                                    <td>
        <?= intval(@$dash['empregoDef'][$k]) ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                    <br />
                    <?php
                }
                if (count($dash['escolaridade']) > 1) {
                    ?>

                    <div class="border4" style="width: 95%; margin: 0 auto">
                        <div style="text-align: center; font-weight: bold; padding: 4px">
                            Escolaridade
                        </div>
                        <br />
                        <table class="table table-bordered table-hover table-striped">
                            <?php
                            foreach ($escolaridade as $k => $v) {
                                ?>
                                <tr>
                                    <td>
        <?= $v ?>
                                    </td>
                                    <td>
        <?= intval(@$dash['escolaridade'][$k]) ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                    <br />
                    <?php
                }
                if (count($dash['conheceu']) > 1) {
                    ?>
                    <div class="border4" style="width: 95%; margin: 0 auto">
                        <div style="text-align: center; font-weight: bold; padding: 4px">
                            Soube do Projeto  
                        </div>
                        <br />
                        <table class="table table-bordered table-hover table-striped">
                            <tr>
                                <td>

                                </td>
                                <td>
                                    Total
                                </td>
                                <td>
                                    Deferidos
                                </td>
                            </tr>
                            <?php
                            foreach (['Internet', 'Facebook', 'Jornal', 'Indicação de amigos', 'Outros'] as $v) {
                                ?>
                                <tr>
                                    <td>
        <?= $v ?>
                                    </td>
                                    <td>
        <?= intval(@$dash['conheceu'][$v]) ?>
                                    </td>
                                    <td>
        <?= intval(@$dash['conheceuDef'][$v]) ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="col">
                <div class="border4" style="width: 95%; margin: 0 auto">
                    <div style="text-align: center; font-weight: bold; padding: 4px">
                        Inscrição por Bairro 
                    </div>
                    <br />
                    <table class="table table-bordered table-hover table-striped">
                        <tr>
                            <td>

                            </td>
                            <td>
                                Total
                            </td>
                            <td>
                                Deferidos
                            </td>
                        </tr>
                        <?php
                        if (!empty($dash)) {
                            ksort($dash['cep']);
                            foreach ($dash['cep'] as $k => $v) {
                                ?>
                                <tr>
                                    <td>
        <?= !empty($k) ? $k : 'Sem CEP' ?>
                                    </td>
                                    <td>
        <?= intval(@$v) ?>
                                    </td>
                                    <td>
        <?= intval(@$dash['cepDef'][$k]) ?>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
        <br />
    </div>
</div>
