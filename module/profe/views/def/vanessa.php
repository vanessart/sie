<?php
// /b_start("ob_gzhandler");

if (!defined('ABSPATH'))
    exit;

$n_disc = filter_input(INPUT_POST, 'n_disc', FILTER_SANITIZE_STRING);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_STRING);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$n_inst = filter_input(INPUT_POST, 'n_inst', FILTER_SANITIZE_STRING);
if (empty($n_inst)) {
    $n_inst = toolErp::n_inst();
}
if (!$id_curso || !$id_turma || !$id_disc || !$id_pl) {
    exit();
}

$turma = sql::get('ge_turmas', 'periodo, letra, periodo_letivo, n_turma', ['id_turma' => $id_turma], 'fetch');
$periodo = ['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite', 'G' => 'Geral', 'I' => 'Integral'];
$curso = sql::get('ge_cursos', 'n_curso, un_letiva', ['id_curso' => $id_curso], 'fetch');
$n_curso = $curso['n_curso'];
$un_letiva = $curso['un_letiva'];

$alunos = ng_escola::alunoPorTurma($id_turma);

$habCont = $model->relatHabCont($id_pl, $id_turma, $id_disc, $id_curso);

$chamada = $model->chamadaDiario($id_pl, $id_turma, $id_disc);
?>
<style>
    .sigla{
        margin: 0; 
        padding: 5px;
        text-align: center;
        width: 30px;
    }
    .topo{
        font-size: 8pt;
        font-weight:bold;
        text-align: center;
        border-style: solid;
        border-width: 0.5px;
        padding-left: 5px;
        padding-right: 5px;
        padding-top: 2px;
        padding-bottom: 2px;
    }
    .topo1{
        font-size: 12pt;
        font-weight:bold;
        text-align: center;
        border-style: solid;
        border-width: 0.5px;
        padding-left: 5px;
        padding-right: 5px;
        padding-top: 2px;
        padding-bottom: 2px;
    }
    .topo2{
        font-size: 24px;
        text-align: center;
        font-weight: bold;
        padding: 5px; 
        color: #0e2ab4;
    }
    .topo3{
        font-size: 20px;
        text-align: center;
        font-weight: bold;
        padding: 3px;
        background-color: #0e2ab4;
        color: white;
    }
    .topo4{
        font-size: 20px;
        border-style: solid;
        border-width: 1px; 
        text-align: center;
        font-weight: bold; 
        padding: 3px; 
        color: black;
    }
    .cab{
        font-size: 14px;
        font-weight:bold; 
        font-size:10pt;
        background-color: #000000;
        color:#ffffff; 
        text-align: center;
    }
    .contorno{
        font-weight: bold;
        padding: 3px;
        border: #0e2ab4 solid 4px;
    }
</style>
<div class="body" style="border-style: solid; border-width: 2px; width: 100%">
    <div style="width: 100%; padding: 5px; text-align: center" >
        <img style="width: 100px" src="<?php echo HOME_URI ?>/views/_images/brasao.jpg"/>
    </div>
    <br /><br /><br />
    <!-- Capa -->
    <div class="topo2">
        <?= CLI_NOME ?>yyyyy
        k
        k
        k
        k
        k
        k
        
        <br />
        COORDENADORIA - <?= strtoupper($n_curso) ?>
    </div>
    <br /><br /><br />
    <div class="topo3">
        IDENTIFICAÇÃO DA ESCOLA
    </div>
    <div class="topo4">
        <?= $n_inst ?>
    </div>
    <br /><br /><br /><br />
    <div class="topo2">
        Diário de Classe
    </div>
    <br /><br /><br /><br />
    <div class="topo3">
        CURSO
    </div>
    <div class="topo4">
        <?= $n_curso ?>
    </div>
    <br /><br /><br /><br />
    <div class="topo3">
        COMPONENTE CURRICULAR
    </div>
    <div class="topo4">
        <?= $n_disc ?>
    </div>
    <br /><br /><br /><br />
    <div class="topo3">
        CLASSE
    </div>
    <div> 
        <table style="width: 100%">
            <tr>
                <td class="topo1">
                    Ano Letivo
                </td>
                <td class="topo1">
                    Turno
                </td>
                <td class="topo1">
                    Turma
                </td>
            </tr>
            <tr>
                <td class="topo1">
                    <?= $turma['periodo_letivo'] ?>
                </td>
                <td class="topo1">
                    <?= $periodo[$turma['periodo']] ?>
                </td>
                <td class="topo1">
                    <?= $turma['n_turma'] ?>
                </td>
            </tr>
        </table>
    </div>
    <div style="page-break-after: always"></div>
    <!-- Lista de Alunos -->
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <td colspan="5" class="cab">
                Lista dos Alunos
            </td>
        </tr>
        <tr>
            <td style="font-weight: bold; font-size: 12px">
                Nº
            </td>
            <td style="font-weight: bold; font-size: 12px">
                RSE  
            </td>
            <td style="font-weight: bold; font-size: 12px">
                Nome
            </td>
            <td style="font-weight: bold; font-size: 12px">
                Situação
            </td>
        </tr>
        
    </table>
    <br /><br />
    <div style="page-break-after: always"></div>


    <div style="page-break-after: always"></div>
    <div colspan="5" class="topo3">
        Frequência dos Alunos
    </div>
    <br />
    <?php

    if (!empty($chamada['dt'])) {
        
        
         //*** INSTRUMENTOS AVALIATIVOS ***
        $disciplina = sql::idNome('ge_disciplinas');
        foreach ($chamada['dt'] as $bim => $v) {
            $instrumentos = $model->retornaInstrumentosAvaliativos($id_pl, $id_turma, $bim, $id_disc);
            foreach ($instrumentos as $key => $value) {
                foreach ($alunos as $k => $v) {
                    if (isset($value->notas)) {
                        $notasArr = (array) $value->notas;
                        if (isset($notasArr[$v['id_pessoa']])) {
                           $alunos[$k]['notasAluno'][$value->uniqid] = $notasArr[$v['id_pessoa']];
                        }else{
                            $alunos[$k]['notasAluno'][$value->uniqid] = '-';
                        }
                    }
                }
            }
            if (count($instrumentos) > 0) {?>
            <div colspan="5" class="topo3">
                Instrumentos Avaliativos do <?= $bim . 'º ' . $un_letiva ?>
            </div>
            <table class="table table-bordered table-hover table-striped">
                    <tr>
                        <td style="font-weight: bold;width: 2%;">
                            Nº
                        </td>
                        <td style="font-weight: bold;width: 25.5%;">
                            Nome
                        </td>
                        <?php
                        foreach ($instrumentos as $value) {?>
                            <td style="text-align: center;width: 2%">
                                <div class="tooltipB">
                                    <span>
                                        <div class="alert alert-success sigla">
                                            <?= substr($value->instrumentoNome, 0, 2) ?>
                                        </div>  
                                    </span>
                                    <span class="tooltipBtext" style="min-width: 100px;"> <?= $value->instrumentoNome ?><?= $id_disc == 'nc' ? '<br>'.@$disciplina[$value->id_disc_nc] : '' ?></span>
                                </div>
                            </td>
                            <?php
                        }?>
                    </tr>
                    <?php
                    foreach ($alunos as $key => $value) { ?>
                        <tr>
                            <td>
                                <?= $value['chamada'] ?>
                            </td>
                            <td>
                                <?= $value['n_pessoa'] ?>
                            </td>
                            <?php foreach ($instrumentos as $k => $v) {
                                ?>
                                <?php
                                if ($v->ativo == 1 && !empty($v->notas)) {
                                     if (isset($value['notasAluno'][$v->uniqid])) {
                                        $nota = $value['notasAluno'][$v->uniqid];
                                    } else {
                                        $nota = '-';
                                    }
                                    ?>
                                    <td style="text-align: center;font-weight: bold; color: <?= ($nota < 5 ? 'red' : 'blue')  ?>  !important ">
                                        <?= $nota ?>
                                    </td>
                                    <?php
                                } else {
                                    ?>
                                    <td style="text-align: center">
                                        -
                                    </td>
                                    <?php
                                }
                            }?>
                        </tr>
                    <?php } ?>
                </table>
                <?php
            }?>
            <table class="table table-bordered table-hover table-striped">
                <?php
                foreach ($instrumentos as $v) {?>
                    <tr>
                        <td class="sigla">
                            <div class="alert alert-success sigla" >
                                <?= substr($v->instrumentoNome, 0, 2) ?>
                            </div>
                        </td>
                        <td style="vertical-align:middle;">
                            <?= $v->instrumentoNome ?><?= $id_disc == 'nc' ? ' em '.@$disciplina[$v->id_disc_nc] : '' ?>
                        </td>
                    <tr>
                    <?php
                }?>
            </table>
            <div style="page-break-after: always"></div>
            <?php
        }

        //*** LANCAMENTO DE NOTAS ***

        if ($id_turma) {
            $grade = turma::disciplinas($id_turma);
            foreach ($grade as $k => $v) {
                if ($v['nucleo_comum'] != 1) {
                    unset($grade[$k]);
                }
            }
        }
        ?>
        <div colspan="5" class="topo3">
                Lançamento de Notas
            </div>
            <table class="table table-bordered table-striped">
                <tr>
                    <td rowspan="2" style="font-weight: bold;width: 2.5%">
                        Nº
                    </td>
                    <td rowspan="2" style="font-weight: bold">
                        Nome
                    </td>
                   <?php
                    foreach ($chamada['dt'] as $bim => $v) {?>
                        <td colspan="2" style="text-align: center;width: 50px">
                            <b><?= $bim . 'º ' . $un_letiva ?></b>
                        </td>
                        <?php
                        $ch[$bim] = $model->chamadaPorUnidadeLetiva($id_pl, $id_turma, $id_disc, $bim); 
                    }?>
                </tr>
                <tr>
                    <?php
                    foreach ($chamada['dt'] as $bim => $v) {?>
                        <td style="text-align: center">
                            Falta
                        </td>
                        <td style="text-align: center">
                            Nota
                        </td>
                        <?php 
                    }?>
                </tr>
                <?php 
                foreach ($alunos as $key => $value) {?>
                    <tr>
                        <td style="width: 2.5%">
                            <?= $value['chamada'] ?>
                        </td>
                        <td>
                            <?= $value['n_pessoa'] ?>
                        </td>
                        <?php
                         foreach ($chamada['dt'] as $bim => $v) {
                            $idsPessoa = array_column($alunos, 'id_pessoa');
                            $notaFalta = $model->notaFaltaBim($id_curso, $id_pl, $idsPessoa, $bim);
                            // if(!empty($notaFalta[$value['id_pessoa']]['falta'][$id_disc])) {
                            //     $falta = $notaFalta[$value['id_pessoa']]['falta'][$id_disc];
                            // }elseif (!empty($ch['F'][$value['id_pessoa']])) {
                            //     $falta = $ch['F'][$value['id_pessoa']];
                            // }else {
                            //     $falta = '0';
                            // }

                            if(!empty($notaFalta[$value['id_pessoa']]['falta'][$id_disc])) {
                                $falta = $notaFalta[$value['id_pessoa']]['falta'][$id_disc];
                            }else{
                                // $ch = $model->chamadaPorUnidadeLetiva($id_pl, $id_turma, $id_disc, $bim);
                                if (!empty($ch[$bim]['F'][$value['id_pessoa']])) {
                                    $falta = $ch[$bim]['F'][$value['id_pessoa']];
                                } else {
                                    $falta = '0';
                                }
                            }

                            if (!empty($notaFalta[$value['id_pessoa']]['nota'][$id_disc])) {
                                $nota = $notaFalta[$value['id_pessoa']]['nota'][$id_disc];
                            } else {
                                $nota = '-';
                            }?>
                            <td style="text-align: center;width: 25px">
                                <?= @$falta ?>
                            </td>
                            <td style="text-align: center;font-weight: bold; color: <?= (@$nota < 5 ? 'red' : 'blue')  ?>  !important ;width: 25px">
                                <?= @$nota ?>
                            </td>
                            <?php
                        }?>
                    </tr>
                    <?php 
                }?>
            </table>
            <div style="page-break-after: always"></div>
        <?php
    }else{
        echo toolErp::divAlert("warning","Sem Informações. Verifique se o professor preencheu o Diário de Classe");
    }?>
</div>
