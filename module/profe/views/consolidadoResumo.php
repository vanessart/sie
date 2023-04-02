<?php
if (!defined('ABSPATH'))
    exit;

$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'id_disc');
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$n_inst = filter_input(INPUT_POST, 'n_inst', FILTER_UNSAFE_RAW);
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
$sql = "SELECT ap.rm, p.n_pessoa, p.sexo FROM ge_aloca_prof ap JOIN ge_funcionario f on f.rm = ap.rm and `fk_id_turma` = $id_turma AND `iddisc` LIKE '$id_disc' JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa ";
$query = pdoSis::getInstance()->query($sql);
$prof = $query->fetch(PDO::FETCH_ASSOC);
if ($prof) {
    if ($prof['sexo'] == 'F') {
        $profArt = 'a';
    } elseif (!empty($prof['sexo'])) {
        $profArt = null;
    } else {
        $profArt = '(a)';
    }
} else {
    $profArt = ' (a)';
}
$alunos = ng_escola::alunoPorTurma($id_turma);
$alunosIds = array_column($alunos, 'id_pessoa');
$habCont = $model->relatHabCont($id_pl, $id_turma, $id_disc, $id_curso);

$chamada = $model->chamadaDiario($id_pl, $id_turma, $id_disc);
if (in_array($id_curso, [3, 7, 8])) {
    foreach ($chamada['ch'] as $bimInf) {
        foreach ($bimInf as $k => $v) {
            foreach ($v as $kvv => $vv) {
                foreach ($vv as $id_pessoa => $f) {
                    if ($f == 'F') {
                        @$faltaInf[$id_pessoa][$k]++;
                    }
                }
            }
        }
    }
}
$sql = "SELECT * FROM hab.aval_final "
        . " WHERE `fk_id_pessoa` in (" . implode(', ', $alunosIds) . ") "
        . "AND `fk_id_pl` = $id_pl "
        . " AND fk_id_ciclo = " . $turma['fk_id_ciclo'];
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($array as $v) {
    $nf[$v['fk_id_pessoa']] = $v;
}
$meses = [
    '02' => 'fev',
    '03' => 'mar',
    '04' => 'abr',
    '05' => 'maio',
    '06' => 'jun',
    '07' => 'jul',
    '08' => 'ago',
    '09' => 'set',
    '10' => 'out',
    '11' => 'nov',
    '12' => 'dez'
        ]
?>
<style>
    td{
        border: #000000 2px solid;
    }
    .fonte{
        font-size: 11px;
    }
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
        <?= CLI_NOME ?>
        <br />
        SECRETARIA DE EDUCAÇÃO

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
        <?= in_array($id_curso, [3, 7, 8]) ? 'EDUCAÇÃO INFANTIL' : 'ENSINO FUNDAMENTAL' ?>
    </div>
    <br /><br /><br /><br />
    <div class="topo3">
        COMPONENTE CURRICULAR
    </div>
    <div class="topo4">
        <?= in_array($id_curso, [3, 7, 8]) ? $n_curso : $n_disc ?>
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
        Professor<?= $profArt ?>
    </div>
    <div class="topo4">
        <?php
        if ($prof) {
            echo $prof['n_pessoa']
            ?> (RM: <?=
            $prof['rm'] . ')';
        } else {

            echo '<br />_______________________________________________________';
        }
        ?>
    </div>
    <br /><br /><br /><br />
</div>
<div style="page-break-after: always"></div>
<!-- Lista de Alunos -->
<table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
    <tr>
        <td colspan="5" class="cab">
            <div class="topo3">
                Lista dos Alunos
            </div>
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
            <td class="fonte">
                <?= $v['chamada'] ?>
            </td>
            <td class="fonte">
                <?= $v['id_pessoa'] ?>  
            </td>
            <td class="fonte">
                <?= $v['n_pessoa'] ?>
            </td>
            <td class="fonte">
                <?= $v['situacao'] ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
<div style="page-break-after: always"></div>

<?php
if (!empty($chamada['dt'])) {
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
                            Notas e Frequências do <?= $bim . 'º ' . $un_letiva ?>
                        </div>
                        <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
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
                                    <td class="fonte">
                                        <?= $value['chamada'] ?>
                                    </td>
                                    <td class="fonte">
                                        <?= $value['n_pessoa'] ?>
                                    </td>
                                    <td class="fonte">
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
                                        <td class="fonte" style="font-weight: bold; color: <?= ($nota < 5 ? 'red' : 'blue') ?>  !important ">
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
            ?>
            <div colspan="5" class="topo3">
                Médias e Frequências Finais
            </div>
            <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
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
                    ?>
                    <tr>
                        <td class="fonte">
                            <?= $value['chamada'] ?>
                        </td>
                        <td class="fonte">
                            <?= $value['n_pessoa'] ?>
                        </td>
                        <td class="fonte">
                            <?= intval(@$nf[$value['id_pessoa']]['falta_nc']) ?>
                        </td>
                        <?php
                        foreach ($grade as $v) {
                            if (!empty($nf[$value['id_pessoa']]['cons_' . $v['id_disc']])) {
                                $notaF = $nf[$value['id_pessoa']]['cons_' . $v['id_disc']];
                            } else {
                                $notaF = @$nf[$value['id_pessoa']]['media_' . $v['id_disc']];
                            }
                            ?>
                            <td class="fonte" style="font-weight: bold; color: <?= ($notaF < 5 ? 'red' : 'blue') ?>  !important ">
                                <?= $notaF ?>
                            </td>
                        <?php }
                        ?>
                    </tr>
                <?php }
                ?>
            </table>
            <div style="page-break-after: always"></div>
            <?php
        } else {
            ?>
            <div colspan="5" class="topo3">
                Notas, Frequências e Médias
            </div>
            <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
                <tr>
                    <td rowspan="2" style="font-weight: bold;width: 2.5%">
                        Nº
                    </td>
                    <td rowspan="2" style="font-weight: bold">
                        Nome
                    </td>
                    <?php
                    foreach ($chamada['dt'] as $bim => $v) {
                        if ($bim) {
                            ?>
                            <td colspan="2" style="text-align: center;width: 50px">
                                <b><?= $bim . 'º ' . $un_letiva ?></b>
                            </td>
                            <?php
                        }
                    }
                    ?>
                    <td colspan="2" style="text-align: center;width: 50px">
                        <b>Final</b>
                    </td>
                </tr>
                <tr>
                    <?php
                    foreach ($chamada['dt'] as $bim => $v) {
                        if ($bim) {
                            ?>
                            <td style="text-align: center">
                                Falta
                            </td>
                            <td style="text-align: center">
                                Nota
                            </td>
                            <?php
                            $ch[$bim] = $model->chamadaPorUnidadeLetiva($id_pl, $id_turma, $id_disc, $bim);
                        }
                    }
                    ?>
                    <td style="text-align: center">
                        Falta
                    </td>
                    <td style="text-align: center">
                        Média
                    </td>
                </tr>
                <?php
                foreach ($alunos as $key => $value) {
                    ?>
                    <tr>
                        <td class="fonte" style="width: 2.5%">
                            <?= $value['chamada'] ?>
                        </td>
                        <td class="fonte">
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
                                <td class="fonte" style="text-align: center;width: 25px">
                                    <?= $falta ?>
                                </td>
                                <td class="fonte" style="text-align: center;font-weight: bold; color: <?= ($nota < 5 ? 'red' : 'blue') ?>  !important ;width: 25px">
                                    <?= $nota ?>
                                </td>
                                <?php
                            }
                        }
                        ?>
                        <td class="fonte" style="text-align: center;width: 25px">
                            <?= intval(@$nf[$value['id_pessoa']]['falta_' . $id_disc]) ?>
                        </td>
                        <?php
                        if (!empty($nf[$value['id_pessoa']]['cons_' . $id_disc])) {
                            $notaF = $nf[$value['id_pessoa']]['cons_' . $id_disc];
                        } else {
                            $notaF = @$nf[$value['id_pessoa']]['media_' . $id_disc];
                        }
                        ?>
                        <td class="fonte" style="text-align: center;font-weight: bold; color: <?= ($notaF < 5 ? 'red' : 'blue') ?>  !important ;width: 25px">
                            <?= $notaF ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <div style="page-break-after: always"></div>
            <?php
        }
    } else {
        ?>
        <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
            <tr>
                <td colspan="14" class="cab">
                    <div class="topo3">
                        Faltas
                    </div>
                </td>
            </tr>
            <tr>
                <td rowspan="2" style="font-weight: bold; font-size: 12px">
                    Nº
                </td>
                <td rowspan="2" style="font-weight: bold; font-size: 12px">
                    Nome
                </td>
                <td colspan="12" style="text-align: center">
                    Meses
                </td>
            </tr>
            <tr>
                <?php
                foreach ($meses as $k => $v) {
                    ?>
                    <td class="fonte">
                        <?= $v ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
            <?php
            foreach ($alunos as $v) {
                ?>
                <tr>
                    <td class="fonte">
                        <?= $v['chamada'] ?>
                    </td>
                    <td class="fonte">
                        <?= $v['n_pessoa'] ?>
                    </td>
                    <?php
                    foreach ($meses as $k => $x) {
                        ?>
                        <td>
                            <?= intval(@$faltaInf[$v['id_pessoa']][$k]) ?>
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
            ?>
        </table>
        <div style="page-break-after: always"></div>
        <?php
    }
    ?>
    <br /><br /><br /><br /><br />
    <div class="row">
        <div style="text-align: center;" class="col">
            <div style="text-align: justify">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php
                if (!in_array($id_curso, [3, 7, 8])) {
                    ?>
                    No presente Diário de Classe foram registrados os Dias Letivos, a Frequência dos Alunos, as Habilidades trabalhadas na(s) disciplina(s) específica(s) do Ensino Fundamental de acordo com o Calendário Escolar homologado em consonância com a Legislação em vigor.
                    <?php
                } else {
                    ?>
                    No presente Diário de Classe foram registrados os Dias Letivos, a Frequência dos Alunos, Projetos de Trabalho, Avaliação Processual (Diária) e Avaliação Final de acordo com o Calendário Escolar homologado em consonância com a Legislação em vigor.                    <?php
                }
                ?>
            </div>
            <br /><br /><br /><br />
            <div style="text-align: right; padding: 60px">
                <?= CLI_CIDADE ?>, <?= data::porExtenso(date("Y-m-") . '19') ?>
            </div>
            <br /><br /><br /><br /><br />
            ____________________________________________________
            <br />
            Assinatura do(a) Professor(a):_____________________________
        </div>
        <div style="text-align: center;" class="col">
            <br /><br /><br /><br /><br />
            ____________________________________________________
            <br />
            Assinatura do(a) Coordenador(a):_____________________________
        </div>
    </div>
    <?php
} else {
    echo toolErp::divAlert("warning", "Sem Informações. Verifique se o professor preencheu o Diário de Classe");
}
?>
<script>
    $(document).ready(function () {
        window.print();
    });
</script>
