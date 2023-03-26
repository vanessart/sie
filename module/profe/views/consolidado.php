<?php
// /b_start("ob_gzhandler");

if (!defined('ABSPATH'))
    exit;

$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'id_disc');
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$n_inst = filter_input(INPUT_POST, 'n_inst', FILTER_SANITIZE_STRING);
if (empty($n_inst)) {
    $n_inst = toolErp::n_inst();
}
if (!$id_curso || !$id_turma || !$id_disc || !$id_pl) {
    exit();
}

$periodo = ['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite', 'G' => 'Geral', 'I' => 'Integral'];
$curso = sql::get('ge_cursos', 'n_curso, un_letiva', ['id_curso' => $id_curso], 'fetch');
$n_curso = $curso['n_curso'];
$un_letiva = $curso['un_letiva'];
$sql = "SELECT ap.rm, p.n_pessoa, p.id_pessoa FROM ge_aloca_prof ap JOIN ge_funcionario f on f.rm = ap.rm and `fk_id_turma` = $id_turma AND `iddisc` LIKE '$id_disc' JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa ";
$query = pdoSis::getInstance()->query($sql);
$prof = $query->fetch(PDO::FETCH_ASSOC);
if ($prof) {
    $oa = concord::oa($prof['id_pessoa']);
    $n_prof = $prof['n_pessoa'].' (RM: '.$prof['rm'].')';        
} else {
    $oa = ' (a)';
    $n_prof = '<br />_______________________________________________________________';
}
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
        PREFEITURA MUNICIPAL DE BARUERI
        <br />
        COORDENADORIA <?= in_array($id_curso, [3, 7, 8]) ? 'INFANTIL' : 'FUNDAMENTAL' ?>
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
    <br /><br /><br /><br />
    <div class="topo3">
        Professor<?= $oa ?>
    </div>
    <div class="topo4">
        <?= $n_prof ?>
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
        <?php
        foreach ($alunos as $v) {
            ?>
            <tr>
                <td style="font-size: 10px">
                    <?= $v['chamada'] ?>
                </td>
                <td style="font-size: 10px">
                    <?= $v['id_pessoa'] ?>  
                </td>
                <td style="font-size: 10px">
                    <?= $v['n_pessoa'] ?>
                </td>
                <td style="font-size: 10px">
                    <?= $v['situacao'] ?>
                </td>
            </tr>
            <?php
        }
        ?>
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
        foreach ($chamada['dt'] as $bim => $v) {
            $row = count($v) + 1;
            ?>
            <div colspan="5" class="topo3">
                <?= $bim ?>º Bimestre
            </div>
            <br />
            <table style="font-size: 10px; font-weight: bold" class="table table-hover table-striped">
                <tr style="font-weight: bold; font-size: 1.2em">
                    <td style="max-width: 50px;width: 2.32%;">
                        Nº
                    </td>
                    <td  style="max-width: 500px;width: 25.5%;">
                        Nome
                    </td>
                    <td style="width: 2.32%;">
                        Dia
                    </td>
                    <?php
                    for ($dia = 1; $dia <= 31; $dia++) {
                        ?>
                        <td style="text-align: center;width: 2.32%;">
                            <?= $dia ?>
                        </td>
                    <?php }
                    ?>
                </tr>
                <?php
                foreach ($alunos as $vv) {
                    ?>
                    <tr>
                        <td rowspan=" <?= $row ?> ">
                            <?= $vv['chamada'] ?>
                        </td>
                        <td  rowspan=" <?= $row ?> ">
                            <?= $vv['n_pessoa'] ?>
                        </td>

                    </tr>

                    <?php foreach ($v as $mes => $dias) { ?>
                        <tr>
                            <td style="text-align:">
                                <?= substr(data::mes($mes), 0, 3) ?>
                            </td>
                            <?php
                            ksort($dias);
                            for ($day = 1; $day <= 31; $day++) {
                                $data_formatada = $turma['periodo_letivo'] . '-' . $mes . '-' . $day;
                                $dia_semana = date('w', strtotime($data_formatada));
                                if ($dia_semana == 0 || $dia_semana == 6) {
                                    $cinza = 'background-color: #eeeeee;';
                                } else {
                                    $cinza = '';
                                }
                                ?>
                                <td style="text-align: center; border:1px solid !important; border-collapse: collapse;vertical-align:middle; <?= $cinza ?>">
                                    <?php
                                    foreach ($dias as $dia) {

                                        if ($day == $dia) {

                                            if (!empty($chamada['ch'][$bim][$mes][$dia][$vv['id_pessoa']])) {
                                                echo $chamada['ch'][$bim][$mes][$dia][$vv['id_pessoa']];
                                            } else {
                                                echo '*';
                                            }
                                        }
                                    }
                                    ?>
                                </td>
                            <?php }
                            ?>
                        </tr>
                    <?php }
                    ?>

                    <?php
                }
                ?>
            </table>
            <?php
        }
        if (!empty($habCont)) {
            foreach ($habCont as $bim => $conteudo) {
                ?>
                <div colspan="5" class="topo3">
                    Conteúdo Programático e Atividades Desenvolvidas do <?= $bim . 'º ' . $un_letiva ?>
                </div>
                <?php
                ksort($conteudo);
                foreach ($conteudo as $data => $v) {
                    ?>
                    <div style="margin-top: 20px"> 
                        <table class="table table-bordered table-hover table-striped contorno">
                            <tr>
                                <td colspan="2" class="cab">
                                    <?= data::porExtenso($data) ?>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 100px">
                                    Descritivo da Aula
                                </td>
                                <td>
                                    <div style=" max-width: 100%">
                                        <div style="white-space: pre-wrap"> <?= trim($v['descritivo']) ?></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Ocorrências
                                </td>
                                <td>
                                    <?= $v['ocorrencia'] ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Habilidades Desenvolvidas
                                </td>
                                <td>
                                    <?php
                                    if (!empty($v['habilidades'])) {
                                        foreach ($v['habilidades'] as $h) {
                                            ?>
                                            <p>
                                                <?= $h ?>
                                            </p>
                                            <?php
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php
                }
                ?>
                <div style="page-break-after: always"></div>

                <?php
            }
        }
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
                        } else {
                            $alunos[$k]['notasAluno'][$value->uniqid] = '-';
                        }
                    }
                }
            }
            if (count($instrumentos) > 0) {
                ?>
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
                        <?php foreach ($instrumentos as $value) { ?>
                            <td style="text-align: center;width: 2%">
                                <div class="tooltipB">
                                    <span>
                                        <div class="alert alert-success sigla">
                                            <?= substr($value->instrumentoNome, 0, 2) ?>
                                        </div>  
                                    </span>
                                    <span class="tooltipBtext" style="min-width: 100px;"> <?= $value->instrumentoNome ?><?= $id_disc == 'nc' ? '<br>' . @$disciplina[$value->id_disc_nc] : '' ?></span>
                                </div>
                            </td>
                        <?php }
                        ?>
                    </tr>
                    <?php foreach ($alunos as $key => $value) { ?>
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
                                    <td style="text-align: center;font-weight: bold; color: <?= ($nota < 5 ? 'red' : 'blue') ?>  !important ">
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
                            }
                            ?>
                        </tr>
                    <?php } ?>
                </table>
            <?php }
            ?>
            <table class="table table-bordered table-hover table-striped">
                <?php foreach ($instrumentos as $v) { ?>
                    <tr>
                        <td class="sigla">
                            <div class="alert alert-success sigla" >
                                <?= substr($v->instrumentoNome, 0, 2) ?>
                            </div>
                        </td>
                        <td style="vertical-align:middle;">
                            <?= $v->instrumentoNome ?><?= $id_disc == 'nc' ? ' em ' . @$disciplina[$v->id_disc_nc] : '' ?>
                        </td>
                    <tr>
                    <?php }
                    ?>
            </table>
            <div style="page-break-after: always"></div>
            <?php
        }

        //*** LANCAMENTO DE NOTAS ***
        if (!in_array($id_curso, [3, 7, 8])) {

            if ($id_turma) {
                $grade = turma::disciplinas($id_turma);
                foreach ($grade as $k => $v) {
                    if ($v['nucleo_comum'] != 1) {
                        unset($grade[$k]);
                    }
                }
            }
            if ($id_disc == 'nc') {
                foreach ($chamada['dt'] as $bim => $v) {
                    if ($bim) {
                        $idsPessoa = array_column($alunos, 'id_pessoa');
                        $notaFalta = $model->notaFaltaBim($id_curso, $id_pl, $idsPessoa, $bim);
                        $ch = $model->chamadaPorUnidadeLetiva($id_pl, $id_turma, $id_disc, $bim);
                        if (!empty($notaFalta)) {
                            ?>
                            <div colspan="5" class="topo3">
                                Lançamento de Notas do <?= $bim . 'º ' . $un_letiva ?>
                            </div>
                            <table class="table table-bordered table-hover table-striped">
                                <tr>
                                    <td rowspan="2" style="font-weight: bold">
                                        Nº
                                    </td>
                                    <td rowspan="2" style="font-weight: bold">
                                        Nome
                                    </td>
                                    <td rowspan="2" style="width: 50px">
                                        Faltas
                                    </td>
                                    <td colspan="<?= count($grade) ?>" style="text-align: center;width: 50px">
                                        <b>Nota</b>
                                    </td>
                                </tr>
                                <tr>
                                    <?php foreach ($grade as $v) { ?>
                                        <td style="width: 80px">
                                            <?= $v['sg_disc'] ?>
                                        </td>
                                    <?php }
                                    ?>
                                </tr>
                                <?php
                                foreach ($alunos as $key => $value) {
                                    if (!empty($notaFalta[$value['id_pessoa']]['falta'][$id_disc])) {
                                        $falta = $notaFalta[$value['id_pessoa']]['falta'][$id_disc];
                                    } elseif (!empty($ch['F'][$value['id_pessoa']])) {
                                        $falta = $ch['F'][$value['id_pessoa']];
                                    } else {
                                        $falta = '0';
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <?= $value['chamada'] ?>
                                        </td>
                                        <td>
                                            <?= $value['n_pessoa'] ?>
                                        </td>
                                        <td>
                                            <?= $falta ?>
                                        </td>
                                        <?php
                                        foreach ($grade as $v) {
                                            if (!empty($notaFalta[$value['id_pessoa']]['nota'][$v['id_disc']])) {
                                                $nota = $notaFalta[$value['id_pessoa']]['nota'][$v['id_disc']];
                                            } else {
                                                $nota = '-';
                                            }
                                            ?>
                                            <td style="font-weight: bold; color: <?= ($nota < 5 ? 'red' : 'blue') ?>  !important ">
                                                <?= $nota ?>
                                            </td>
                                        <?php }
                                        ?>
                                    </tr>
                                <?php }
                                ?>
                            </table>
                            <div style="page-break-after: always"></div>
                            <?php
                        }
                    }
                }
            } else {
                ?>
                <div colspan="5" class="topo3">
                    Lançamento de Notas
                </div>
                <table class="table table-bordered table-hover table-striped">
                    <tr>
                        <td rowspan="2" style="font-weight: bold;width: 2.5%">
                            Nº
                        </td>
                        <td rowspan="2" style="font-weight: bold">
                            Nome
                        </td>
                        <?php foreach ($chamada['dt'] as $bim => $v) { ?>
                            <td colspan="2" style="text-align: center;width: 50px">
                                <b><?= $bim . 'º ' . $un_letiva ?></b>
                            </td>
                        <?php }
                        ?>
                    </tr>
                    <tr>
                        <?php foreach ($chamada['dt'] as $bim => $v) { ?>
                            <td style="text-align: center">
                                Falta
                            </td>
                            <td style="text-align: center">
                                Nota
                            </td>
                            <?php
                            $ch[$bim] = $model->chamadaPorUnidadeLetiva($id_pl, $id_turma, $id_disc, $bim);
                        }
                        ?>
                    </tr>
                    <?php foreach ($alunos as $key => $value) { ?>
                        <tr>
                            <td style="width: 2.5%">
                                <?= $value['chamada'] ?>
                            </td>
                            <td>
                                <?= $value['n_pessoa'] ?>
                            </td>
                            <?php
                            foreach ($chamada['dt'] as $bim => $v) {
                                if ($bim) {
                                    $idsPessoa = array_column($alunos, 'id_pessoa');
                                    $notaFalta = $model->notaFaltaBim($id_curso, $id_pl, $idsPessoa, $bim);
                                    if (!empty($notaFalta[$value['id_pessoa']]['falta'][$id_disc])) {
                                        $falta = $notaFalta[$value['id_pessoa']]['falta'][$id_disc];
                                    } elseif (!empty($ch[$bim]['F'][$value['id_pessoa']])) {
                                        $falta = $ch[$bim]['F'][$value['id_pessoa']];
                                    } else {
                                        $falta = '0';
                                    }
                                    if (!empty($notaFalta[$value['id_pessoa']]['nota'][$id_disc])) {
                                        $nota = $notaFalta[$value['id_pessoa']]['nota'][$id_disc];
                                    } else {
                                        $nota = '-';
                                    }
                                    ?>
                                    <td style="text-align: center;width: 25px">
                                        <?= $falta ?>
                                    </td>
                                    <td style="text-align: center;font-weight: bold; color: <?= ($nota < 5 ? 'red' : 'blue') ?>  !important ;width: 25px">
                                        <?= $nota ?>
                                    </td>
                                    <?php
                                }
                            }
                            ?>
                        </tr>
                    <?php }
                    ?>
                </table>
                <div style="page-break-after: always"></div>
                <?php
            }
        }
        ?>
        <div class="alert alert-info" style="text-align: justify">
            Considerando o Decreto nº 9.693 de 17 de novembro de 2022, que em caráter excepcional, dispôs que os dias dos Jogos da Seleção Brasileira de Futebol na Copa do Mundo foram Dias sem Expediente e para cumprimento do Ano Letivo de 2022 com mínimo de 200 dias letivos, considera-se também além dos dias cumpridos no Calendário Escolar o Reforço Escolar ofertado aos alunos do Ensino Fundamental em Janeiro de 2022, dos dias 10 a 28, num total de 15 dias. Desta forma, o reforço escolar garante de forma compensatória, o cumprimento do disposto na legislação mediante o registro comprobatório da frequencia dos alunos que compareceram e do registro das atividades realizadas compondo o efetivo trabalho realizado com aluno. 
        </div>
        <?php
    } else {
        echo toolErp::divAlert("warning", "Sem Informações. Verifique se o professor preencheu o Diário de Classe");
    }
    ?>
</div>
