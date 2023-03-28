<?php
if (!defined('ABSPATH'))
    exit;

$id = @$_GET['id'];
if ($id) {
    $erro = 1;
    $dadosGet = unserialize($id);
    $id_pessoa = $dadosGet[0];
    $token = $dadosGet[1];
    $anoCompleto = $dadosGet[2];
    $id_ciclo = $dadosGet[3];
    $id_inst = $dadosGet[4];
    $dg = sqlErp::get(['historico_dados_gerais', 'historico_tipo_ensino'], '*', ['fk_id_pessoa' => $id_pessoa], 'fetch');
    if (!empty($dg)) {
        if ($dg['token'] == $token) {
            $erro = null;
        }
    }
} else {
    if (empty(toolErp::id_inst()) || empty($id_pessoa)) {
        $erro = null;
    }
    $id_inst = toolErp::id_inst();
    $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
    $anoCompleto = $periodo = filter_input(INPUT_POST, 'anoCompleto');
    $id_ciclo = $periodo = filter_input(INPUT_POST, 'id_ciclo');

    $dg = sqlErp::get(['historico_dados_gerais', 'historico_tipo_ensino'], '*', ['fk_id_pessoa' => $id_pessoa], 'fetch');
    if (empty($dg)) {
        $erro = 1;
    }
}
if ($_SESSION['TMP']['anoCompleto'][$id_pessoa] == 'x' && $_SESSION['TMP']['id_ciclo'][$id_pessoa] == 1) {
    $semNota = true;
}
if (!empty($erro)) {
    ?>
    <div class="btn btn-danger" style="text-align: center">
        <p>
            Não foi possível validar este hitórico.
        </p>
        <p>
            Por favor, contacte a escola de origem
        </p>
    </div>
    <?php
    exit();
}

$ensinoCiclos = $ensinoCiclosSet = $model->ensinoCiclos($dg['fk_id_hte']);
$ensinoCiclosIniFim = $model->ensinoCiclos($dg['fk_id_hte'], 1);
$notas = $model->notas($id_pessoa);
$turma = $model->frequente($id_pessoa, $id_ciclo);
$wCiclo = [
    1 => '1º Ano',
    2 => '2º Ano',
    3 => '3º Ano',
    4 => '4º Ano',
    5 => '5º Ano',
    6 => '6º Ano',
    7 => '7º Ano',
    8 => '8º Ano',
    9 => '9º Ano',
    25 => '1º Seg. Termo 1',
    26 => '1º Seg. Termo 2',
    27 => '2º Seg. Termo 1',
    28 => '2º Seg. Termo 2',
    29 => '2º Seg. Termo 3',
    30 => '2º Seg. Termo 4',
    31 => 'Multisseriada',
    34 => '2º Seg. Termo 4',
    35 => 'Multisseriada 1ª Seg.',
    36 => 'Multisseriada 1ª Seg.',
    37 => 'Multisseriada 1ª Seg.'
];

$wcicloEja2 = [27, 28, 29, 30, 34];

foreach ($notas as $v) {
    if ($v['ativo'] == 1) {
        @$baseQt[$v['fk_id_base']]++;
    }
}
if ($anoCompleto == 'x' || empty($anoCompleto)) {
    $notasParciais = $model->notasParciais($id_pessoa, $id_ciclo);
}

if (empty($notas) && !empty($notasParciais)) {
    $notas = $model->notasSoParciais($id_pessoa);
}
$id_ciclo_cursado = $model->cicloCursando($notas);

if (!empty($notasParciais)) {
    $turma = $model->frequente($id_pessoa, $id_ciclo);
}

if (!empty($id_ciclo_cursado) && !empty($turma) && empty($anoCompleto)) {
    if ($turma['fk_id_ciclo'] <= $id_ciclo_cursado) {
        $naoMostraParcial = 1;
    }
}
$cp_ = sql::get('historico_carga', '*', ['fk_id_pessoa' => $id_pessoa], 'fetch');

if ($cp_) {
    $id_carga = $cp_['id_carga'];
    foreach ($cp_ as $k => $v) {
        $ar = explode('_', $k);
        if (is_numeric($ar[1]) && (empty($id_ciclo) || $id_ciclo >= $ar[1])) {
            $carga[$ar[1]][$ar[0]] = $v;
        } else {
            $carga[$ar[1]][$ar[0]] = null;
        }
    }
}
if (!empty($dg['regime'])) {
    $regime = json_decode($dg['regime'], true);
} else {
    $regime = null;
}
$baseEns = [
    'bncc' => 'Base Nacional Comum Curricular',
    'bd' => 'Base Diversificada',
    'total' => 'Carga Horária Total'
];
$dados['id_pessoa'] = $id_pessoa;

$ant = $model->anosAnteriores($dados);

if ($ant) {
    $ultimoAno = end($ant)['ano'];
    $ultimoCiclo = end($ant)['fk_id_ciclo'];

    if ($ultimoAno < date('Y') && $ultimoCiclo == '1') {
        $ultimoIdCiclo = 2;
    } else {
        $ultimoIdCiclo = end($ant)['fk_id_ciclo'];
    }
} else {
    $ultimoIdCiclo = null;
}
if (!empty($dg['ciclos'])) {
    $ciclosSet = json_decode($dg['ciclos'], true);
    foreach ($ensinoCiclos as $k => $v) {
        if (!in_array($k, $ciclosSet)) {
            unset($ensinoCiclos[$k]);
        }
    }
}
if (empty($id_ciclo)) {
    $notas = $model->notas($id_pessoa);
    $id_ciclo = $model->cicloCursando($notas);
}

$faltaNc = $model->faltasNcCalc($id_pessoa, $id_ciclo);
if ($faltaNc) {
    $faltaNcTotal = array_sum($faltaNc);
}

$linha = $model->definevariavel($id_pessoa);
$escola = $model->pegadadosescola($id_inst);
$pandemia = $model->observacaopandemia($cp_['cursou_2020'], $cp_['cursou_2021'], $dg['sexo']);

$titulo = [
    1 => 'Programa Padrão Ensino Fundamental',
    2 => 'Programa Padrão Ensino Eja',
    3 => 'Programa Padrão Ensino Fundamental/Eja'
];
ob_start();
$pdf = new pdf();
$pdf->headerSet = null;
$pdf->footerSet = null;
$pdf->mgt = 0;
?>
<head>
    <style>
        .topo{
            font-size: 8pt;
            font-weight:bolder;
            text-align: left;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 1px;
            padding-bottom: 1px;
            border: 1px solid;
        }
        .topo1{
            font-size: 8pt;
            font-weight:bolder;
            text-align: center;
            padding: 1px;
            border: 1px solid;
            height: auto;
        }
        .topo2{
            font-size: 7pt;
            text-align: center;
            font-weight:bolder;
            padding: 1px;
            border: 1px solid;
            height: auto;

        }
        .titulo{
            font-weight:bold;
            font-size:10pt;
            background-color: #000000;
            color:#ffffff;
            text-align: center;
            width: 100%;
        }
        .textovertical{
            width:1px;
            word-wrap: break-word;
            white-space: pre-wrap;
            font-family: monospace;
            font-size: 7pt;
            font-weight: bold;
            text-align: center;
            padding: 1px;
            border: 1px solid;
        }
        .vert{
            writing-mode: vertical-lr;
            transform: rotate(270deg);
        }
    </style>
</head>

<div style="border: 2px solid">
    <table style="width: 100%" cellspacing=0 cellpadding=2 bordercolor="666633">
        <tr>
            <td  style="width: 10%; text-align: center">
                <img style="width: 100px" src="<?php echo HOME_URI ?>/views/_images/brasao.jpg"/>
            </td>
            <td style="padding-top: 5px; width: 80%; text-align: center">
                <div style="font-size: 22px; font-weight: bold">
                    <?= CLI_NOME ?>
                    <br />
                    SE - Secretaria de Educação
                </div>
                <div style="font-size: 18px">
                    <?= $dg['escola'] ?>
                </div>
                <div style="font-size: 12px">
                    <?= $escola[1] ?>
                </div>
                <div style="font-size: 12px">
                    <?= 'Telefones: ' . $escola[2] . ' Email: ' . $escola[0]['email'] ?>
                </div>
                <div style="font-size: 12px">
                    Ato de Criação:
                    <?= $escola[0]['ato_cria'] ?>
                </div>
                <div style="font-size: 12px; padding-bottom: 5px">
                    Ato de Municipalização:
                    <?= $escola[0]['ato_municipa'] ?>
                </div>
            </td>
            <td style="width: 10%; text-align: center">
                <img src="<?= $model->fotoEnd($id_pessoa) ?>" alt="foto" style="width: 100px; height: 100px"/>
            </td>
        </tr>  
    </table>
</div>
<div class="titulo">
    Histórico Escolar
</div>
<table style="width: 100%" cellspacing=0 cellpadding=2 bordercolor="666633"> 
    <tr>
        <td class="topo" colspan="4">
            <?= ($dg['sexo'] == 'M' ? 'Nome do Aluno: ' : 'Nome da Aluna: ') ?> <span style="font-weight: bold"><?= $dg['nome'] ?></span>
        </td>
    </tr>
    <?php
    if (!empty($dg['n_social'])) {
        ?>
        <tr>
            <td class="topo" colspan="4">
                Nome Social(Decr. 55588/10): <span style="font-weight: bold"><?= $dg['n_social'] ?></span>
            </td>
        </tr>
        <?php
    }
    ?>
    <tr>
        <td class="topo" style="width: 25%">
            RSE nº. <?= $id_pessoa ?>
        </td>
        <td class="topo" style="width: 25%">
            RA nº. <?= $dg['ra'] ?>
        </td>
        <td class="topo" style="width: 25%">
            <!--
            <?= $dg['tp_doc'] . ' nº. ' . $dg['rg'] . '-' . $dg['rg_dig'] . ' - ' . $dg['rg_uf'] . ' - ' . $dg['rg_oe'] ?>
            -->
            <?= $dg['tp_doc'] . ' nº. ' . $dg['rg'] . '-' . $dg['rg_dig'] ?>
        </td>
        <td class="topo" style="width: 25%">
            Data Nasc.: <?= data::converteBr($dg['dt_nasc']) ?>
        </td>
    </tr>
    <tr>
        <td class="topo" style="width: 25%">
            Sexo: <?= tool::sexo($dg['sexo']) ?>
        </td>
        <td class="topo" style="width: 50%" colspan="2">
            Naturalidade: <?= $dg['cidade_uf_nasc'] ?>
        </td>
        <td class="topo" style="width: 25%">
            Nacionalidade: <?= $dg['nacionalidade'] ?>
    </tr>
    <tr>
        <td class="topo" colspan="4">
            Cert. Nascimento (antiga:Termo-Livro-Folha): <?= $dg['certidao'] ?>
        </td>
    </tr>
</table>
<div class="titulo" >
    <?= $dg['n_hte'] ?>
</div> 
<?php

if ($dg['fk_id_hte'] == 1) {
    $wcoluna = 5;
} elseif ($dg['fk_id_hte'] == 2) {
    $wcoluna = 2;
} elseif ($dg['fk_id_hte'] == 3) {
    if ($regime[4] == 1) {
        $wcoluna = 4;
    } else {
        $wcoluna = 5;
    }
}
?>
<table style="width: 100%" cellspacing=0 cellpadding=0 bordercolor="666633"> 
    <tr>
        <td style="border: 1px solid" rowspan="<?= $linha[99] ?>">
            <img src="<?php echo HOME_URI ?>/views/_images/historico1.jpg" alt="Lei" style="width: 30px"/>
        </td>
        <td style="width: 80px" class= "topo1" rowspan="4" colspan="2">
            Componentes
            <br />
            Curriculares
        </td>
        <td class="topo1" colspan="14">
<?= $titulo[$dg['id_hte']] ?>
        </td>
    </tr>
    <tr>
        <td class="topo1" rowspan="2" colspan= "<?= $wcoluna ?>">
            Anos Iniciais
        </td>
        <td class="topo1" rowspan="2" colspan="4">
            Anos Finais
        </td>
        <td class="topo1" colspan="5">
            Ano Letivo Atual
        </td>
    </tr>
    <tr>
        <td class="topo1" colspan="5">
<?php
if (!empty($turma) && empty($naoMostraParcial)) {
    echo $turma['n_turma'] . ' -' . $turma['ano'];
} else {
    if (!empty($naoMostraParcial)) {
        echo $wCiclo[$id_ciclo] . '-' . $ultimoAno;
    } else {
        echo '-';
    }
}
?>
        </td>
    </tr>
    <tr>
<?php
foreach ($ensinoCiclos as $k => $v) {
    ?>
            <td class="topo2">
            <?php
            if (@$regime[$k] == 1) {
                echo str_replace('Ano', 'Série', $v);
            } else {
                echo $v;
            }
            ?>
            </td>
                <?php
            }
            ?>
        <td class="topo2">
            1º Bim
        </td>
        <td class="topo2">
            2º Bim
        </td>
<?php
if (in_array($id_ciclo, $wcicloEja2)) {
    $wrange = 2;
} else {
    $wrange = 4;
    ?>
            <td class="topo2">
                3º Bim
            </td>
            <td class="topo2">
                4º Bim
            </td>
    <?php
}
?>
        <td class="topo2">
            Faltas Aula
        </td>
    </tr>
<?php
$contBase = 1;
foreach ([1 => 'BNCC', 2 => 'BD'] AS $base => $nBase) {
    $mb = 1;
    foreach ($notas as $n) {
        if ($n['fk_id_base'] == $base && $n['ativo'] == 1) {
            ?>
                <tr>
                <?php
                if ($mb) {
                    ?>
                        <td class="topo2" rowspan="<?= $baseQt[$base] ?>" style="width: 5px">
                        <?= $nBase ?>
                        </td>
                            <?php
                            $mb = null;
                        }
                        ?>
                    <td class="topo1">
                    <?= $n['n_disc'] ?>
                    </td>
                        <?php
                        foreach ($ensinoCiclos as $k => $v) {
                            ?>
                        <td class="topo1">
                        <?php
                        if (empty($id_ciclo)) {
                            echo str_replace('.', ',', $n['n_' . $k]);
                        } elseif (!empty($semNota)) {
                            echo '-';
                        } elseif ($k <= $id_ciclo) {
                            echo str_replace('.', ',', $n['n_' . $k]);
                        } else {
                            echo '-';
                        }
                        ?>
                        </td>
                            <?php
                        }
                        foreach (range(1, $wrange) as $b) {
                            ?>
                        <td class="topo1">
                        <?php
                        if (empty($naoMostraParcial)) {
                            echo str_replace('.', ',', @$notasParciais[@$n['fk_id_disc']][$b]);
                        }
                        ?>
                        </td>
                            <?php
                        }
                        ?>
                    <td class="topo1">
                    <?php
                    if (empty($naoMostraParcial)) {
                        echo @$notasParciais[@$n['fk_id_disc']]['faltas'];
                    }
                    ?>
                    </td>
                </tr>
            <?php
        }
    }
}
?>
    <tr>
        <td colspan="17" class="titulo" >
            Carga Horária         
        </td>
    </tr>
<?php
$fcn = 1;
foreach ($baseEns as $campo => $base) {
    ?>
        <tr>
            <td colspan="3" class="topo1" style="text-align: left">
    <?= $base ?>
            </td>
                <?php
                foreach ($ensinoCiclos as $k => $v) {
                    ?>
                <td class="topo1">
                <?php
                if ($ultimoIdCiclo == 1) {
                    echo '-';
                } else {
                    echo (@$carga[$k][$campo] == 0 ? '-' : (@$carga[$k][$campo] == -1 ? '-' : @$carga[$k][$campo]));
                }
                ?>
                </td>
                    <?php
                }
                if ($fcn) {
                    ?> 
                <td colspan="5" rowspan="3" class="topo1">
                <?php
                // if duplicado soh para fazer o quadrado
                if ($fcn) {
                    if (!empty($faltaNcTotal) && !empty($turma) && empty($naoMostraParcial)) {
                        ?>
                            Falta Dia: <?= ($faltaNcTotal == 0 ? "-" : $faltaNcTotal) ?>
                            <?php
                        } else {
                            echo "-";
                        }
                    }
                    ?>
                </td>
                    <?php
                    $fcn = null;
                }
                ?>
        </tr>
            <?php
        }
        ?>
</table>
<div style="text-align: left; font-size: 9pt; padding: 1px; border: 1px solid">
    <div style="display:<?php echo $pandemia[4] ?>">
<?php
if ($pandemia[3] != '-') {
    ?>
            <span style="text-align: left; font-weight: bold; font-size: 8pt; color: red "><?= $pandemia[3] ?></span>
            <br />
            <span style="text-align: justify; font-weight: bolder"> 
    <?= $pandemia[0] ?> 
            </span>
            <br />
    <?php
}
?>
    </div>
        <?= $pandemia[1] ?>
    <br />
    <?= $pandemia[2] ?>
</div>
<div style="text-align: right; color: red; font-size: 10px">
    Vide verso
</div>
<div style="page-break-after: always"></div>
<br /> <br />
<table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
    <tr>
        <td colspan="6" class="titulo" >
<?= str_replace('Resultados dos ', '', $dg['n_hte']) ?>
        </td>
    </tr>
    <tr>
        <td style="width: 12%" class="topo1">
            Série/Ano
        </td>
        <td style="width: 7%" class="topo1">
            Regime
        </td>
        <td style="width: 7%" class="topo1">
            Ano
        </td>
        <td style="width: 40%" class="topo1">
            Estabelecimento
        </td>
        <td style="width: 26%" class="topo1">
            Município
        </td>
        <td style="width: 8%" class="topo1">
            Estado
        </td>
    </tr>
<?php
foreach ($ensinoCiclos as $k => $v) {
    if (!empty($id_ciclo) && $id_ciclo == $k && $anoCompleto == 'x') {
        break;
    }
    ?>
        <tr>

            <td class="topo2">
    <?php
    if (@$regime[$k] == 1 || !empty($ant[$k]['regime'])) {
        echo ($ultimoIdCiclo == 1 ? '-' : str_replace('Ano', 'Série', $v));
    } else {
        echo ($ultimoIdCiclo == 1 ? '-' : $v);
    }
    ?>
            </td>
            <td class="topo2">
    <?php
    if ($ultimoIdCiclo == 1) {
        echo '-';
    } else {
        echo empty($ant[$k]['regime']) ? 'EF9' : 'EF8';
    }
    ?>
            </td>
            <td class="topo2">
    <?= ($ultimoIdCiclo == 1 ? '-' : @$ant[$k]['ano']) ?>
            </td>
            <td class="topo2">
    <?= ($ultimoIdCiclo == 1 ? '-' : @$ant[$k]['escola']) ?>
            </td>
            <td class="topo2">
    <?= ($ultimoIdCiclo == 1 ? '-' : @$ant[$k]['cidade']) ?>
            </td>
            <td class="topo2">
    <?= ($ultimoIdCiclo == 1 ? '-' : @$ant[$k]['uf']) ?>
            </td>
        </tr>
    <?php
    if (@$ant[$k]['ano'] == date("Y")) {
        break;
    }
    if ($k == $id_ciclo && $id_ciclo) {
        break;
    }
}
?>
</table>
<br />
<table style="width: 100%">
    <thead>
        <tr>
            <td class="titulo">
                Observações
            </td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="topo1" style=" text-align: justify"> 
<?= $dg['obs'] ?>
            </td>
        </tr>
    </tbody>
</table>
<br />
<table style="width: 100%">
    <thead>
        <tr>
            <td class="titulo" >
                Certificado
            </td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="topo1" style=" text-align: justify"> 
<?= $dg['certificado'] ?>
            </td>
        </tr>
    </tbody>
</table>

<?php
if ($dg['diario_oficial']) {
    ?>
    <table style="width: 100%">
        <thead>
            <tr>
                <td class="titulo" >
                    Publicação
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="topo1" style=" text-align: justify"> 
    <?= $dg['diario_oficial'] ?>
                </td>
            </tr>
        </tbody>
    </table> 
    <?php
}
?>
<div style="font-weight:bold; font-size:8pt; text-align: center; border: 1px solid">
    <br /><br />
    <div style="text-align: right; font-size: 10px">
        Barueri, <?php echo date("d") ?> de <?php echo data::mes(date('m')) ?> de <?php echo date("Y") . '.' ?>
    </div>
    <br /><br /><br /><br />
    <div style="float: left; width: 50%; text-align: center; font-size:10px">
        ___________________________________________________
        <br />
        Secretário(a) da Escola RG(Carimbo)
    </div>
    <div style="float: left; width: 50%; text-align: center; font-size:10px">
        ___________________________________________________ 
        <br />
        Diretor(a) RG(Carimbo)
    </div>
</div>
<?php
$token = $dg['token'];
?>
<br /><br />
<table style="width: 100%; font-weight:bold; font-size:8pt; text-align: center; border: 1px solid">
    <tr>
        <td style="text-align: center">
            Última Atualização: 
<?php echo data::porExtenso(substr($dg['times_stamp'], 0, 10)) ?>
        </td>
        <td style="text-align: right">
<?php
$get = urlencode(serialize([$id_pessoa, $token, $anoCompleto, $id_ciclo, $id_inst]));
$code = HOME_URI . '/app/code/php/qr_img.php?d=https://portal.educ.net.br/ge/historico/pdf/historico?id=' . $get . '&.PNG';
?>
            <img src = "<?php echo $code ?>"/>
        </td>
    </tr>
</table>
<div class="titulo" style="font-size: 10px; padding: 3px">
    Obs. Este documento não apresenta emendas e nem rasuras, e somente é válido com os carimbos da escola e do responsável.
</div>
<?php
$pdf->exec();
