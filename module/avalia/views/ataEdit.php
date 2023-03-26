<style>
    /*Using CSS for table*/
    .demotbl {
        border-collapse: collapse;
        border: 1px solid #69899F;
        width: 100%;
    }
    .demotbl th{
        border: 2px solid #69899F;
        color: #3E5260;
        padding:5px;
    }
    .demotbl td{
        text-align: center;
        border: 1px dotted black;
        color: #002F5E;
        padding:8px;
        width:100px;

    }
    .nota{
        background-color: ghostwhite;
    }
    .disc{
        background-color: green;
        color: white !important;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
$set = sql::get('ge_setup', '*', ['id_set' => 1], 'fetch');
$data = date("Y-m-d");
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
if ($id_turma) {
    $model->ataEdit($id_turma);
    $turma = $model->_turma;
    $disciplinas = $model->_disciplinas; // a chave é o id_disc
    $alunos = $model->_alunos;
    if (!$alunos) {
        ?>
        <div class="alert alert-danger">
            Não encontramos alunos nesta turma ;(
        </div>
        <?php
        die();
    }
    $apds = $model->_apd;
    $n_pl = sql::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $turma['id_pl']], 'fetch')['n_pl'];
    $plAtivos = ng_main::periodosAtivos();
    $temNc = 0;
    foreach ($disciplinas as $d) {
        if ($d['nucleo_comum'] == 1) {
            $temNc = 1;
            @$col++;
        } else {
            @$col += 2;
        }
    }
    ?>
    <div class="body">
        <div class="row">
            <div class="col-10">
                <div class="fieldTop">
                    <p>
                        ATA - <?= $turma['n_curso'] . ' - ' . $turma['n_turma'] ?> 
                    </p>
                    <p>
                        Período Letivo: <?= $n_pl ?>  - Período: <?= $model->periodoDia($turma['periodo']) ?>
                    </p>
                </div>
            </div>
            <div class="col-2">
                <form action="<?= HOME_URI ?>/avalia/atas" method="POST">
                    <?= formErp::hidden(['id_pl' => $turma['id_pl']]) ?>
                    <button class="btn btn-primary">
                        Voltar
                    </button>
                </form>
            </div>
        </div>
        <br />
        <div class="alert alert-danger" style="font-weight: bold;
             font-size: 1.3em">
            <p>
                <?= explode(' ', toolErp::n_pessoa())[0] ?>, é importante ressaltar que esta página tem como objetivo registrar as realizações dos Conselhos bimestrais e de fim de ano, além de permitir correções nos lançamentos. 
            </p>
            <p>
                Para lançamento de notas, é necessário utilizar o subsistema <strong>Diário de Classe</strong>. 
            </p>
            <p>
                Todas as alterações realizadas serão registradas e estarão sujeitas a auditoria.
            </p>
        </div>
        <br />
        <?php
        foreach ($alunos as $v) {
            if (empty($v['id_tas'])) {
                $frequenciaFinal = $model->faltaPorcFinal($v['id_pessoa']);
                $freqFinal = $model->faltaPorcFinal($v['id_pessoa']);
                $CorFreqFinal = $freqFinal <= 25 ? '' : 'style="color: red "';
                $Corsit = $v['id_sf'] == 0 ? 'style="color: red "' : '';
                $SitFinal = $model->SitFinal($v['id_pessoa'], $freqFinal);
            }

            @$notaFalta = $model->_notaFalta[$v['id_pessoa']];
            @$notaFaltaFinal = $model->_notaFaltaFinal[$v['id_pessoa']];
            $dica = null;
            if ($v['id_tas'] == 0) {
                $style = null;
            } else {
                $style = 'style="background-color: pink"';
            }
            ?>
            <table class="demotbl border" <?= $style ?> >
                <tr>
                    <td colspan="<?= $col + $temNc + 2 ?>">
                        <div class="row" style="font-weight: bold;
                             font-size: 1.3em">
                            <div class="col-1" style="text-align: left">
                                <?php
                                if (!empty($notaFalta)) {
                                    ?>
                                    <button onclick="edit(<?= $v['id_pessoa'] ?>)" class="btn btn-warning">
                                        Alterar
                                    </button>
                                    <?php
                                } else {
                                    ?>
                                    <button class="btn btn-secondary">
                                        Alterar
                                    </button>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="col-6" style="text-align: left;
                                 padding-top: 8px">
                                nº <?= $v['chamada'] ?> - <?= $v['n_pessoa'] ?>
                            </div>
                            <div class="col-2" style="padding-top: 8px">
                                RSE: <?= $v['id_pessoa'] ?>
                            </div>
                            <div class="col-3" style="padding-top: 8px">
                                Situação: <?= $v['n_tas'] ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="disc">
                        Disciplinas
                    </td>
                    <?php
                    foreach ($disciplinas as $d) {
                        if ($d['nucleo_comum'] == 1) {
                            ?>
                            <td class="disc">
                                <?= $d['n_disc'] ?>
                            </td>
                            <?php
                        }
                    }
                    foreach ($disciplinas as $d) {
                        if ($d['nucleo_comum'] == 0) {
                            ?>
                            <td class="disc" colspan="2">
                                <?= $d['n_disc'] ?>
                            </td>
                            <?php
                        }
                    }
                    if ($temNc == 1) {
                        ?>
                        <td class="disc">
                            Núcleo Comum
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <tr>
                    <td colspan="2">

                    </td>
                    <?php
                    foreach ($disciplinas as $d) {
                        if ($d['nucleo_comum'] == 1) {
                            ?>
                            <td class="nota" style="text-align: center">
                                Notas
                            </td>
                            <?php
                        }
                    }
                    foreach ($disciplinas as $d) {
                        if ($d['nucleo_comum'] == 0) {
                            ?>
                            <td class="nota">
                                Notas
                            </td>
                            <td>
                                Faltas
                            </td>
                            <?php
                        }
                    }
                    if ($temNc == 1) {
                        ?>
                        <td>
                            Faltas
                        </td>
                        <?php
                    }
                    ?> 
                </tr>
                <?php
                foreach (range(1, $turma['qt_letiva']) as $b) {
                    ?>
                    <tr>
                        <?php
                        if ($b == 1) {
                            ?>
                            <td rowspan="<?= $turma['qt_letiva'] ?>" style="background-color: blue;
                                writing-mode: vertical-rl;
                                color: white;
                                width: 5px;
                                transform: rotateZ(180deg)">
                                Notas Bimestrais 
                            </td>
                            <?php
                        }
                        ?>
                        <td>
                            <?= $b ?>º <?= $turma['un_letiva'] ?>
                        </td>
                        <?php
                        foreach ($disciplinas as $id_disc => $d) {
                            if ($d['nucleo_comum'] == 1) {
                                $nota = @$notaFalta[$b]['media_' . $id_disc];
                                if (empty($nota)) {
                                    $nota = 'NL*<sup>4</sup>';
                                    $dica = 1;
                                } elseif ($nota == 'i') {
                                    $nota .= ' *<sup>3</sup>';
                                    $dica = 1;
                                } elseif ($nota == 'APD') {
                                    $nota .= ' *<sup>1</sup>';
                                    $dica = 1;
                                }
                                ?>
                                <td class="topo2 nota" style="color: <?= $model->notaCor($nota) ?>">
                                    <?= str_replace('.', ',', $nota) ?>
                                </td>
                                <?php
                            }
                        }
                        foreach ($disciplinas as $id_disc => $d) {
                            if ($d['nucleo_comum'] == 0) {
                                $nota = @$notaFalta[$b]['media_' . $id_disc];
                                if (empty($nota)) {
                                    $nota = 'NL*<sup>4</sup>';
                                    $dica = 1;
                                } elseif ($nota == 'i') {
                                    $nota .= ' *<sup>3</sup>';
                                    $dica = 1;
                                } elseif ($nota == 'APD') {
                                    $nota .= ' *<sup>1</sup>';
                                    $dica = 1;
                                }
                                $falta = @$notaFalta[$b]['falta_' . $id_disc];
                                $porc = $model->faltasPorc($falta, $id_disc, $b);
                                ?>
                                <td class="topo2 nota" style="color: <?= $model->notaCor($nota) ?>">
                                    <?= str_replace('.', ',', $nota) ?>
                                </td>
                                <td class="topo2">
                                    <?php
                                    if ($porc < 25) {
                                        ?>
                                        <?= intval($falta) ?> (<?= intval($porc) ?>%)
                                        <?php
                                    } else {
                                        ?>
                                        <span style="color: red"><?= intval($falta) ?> (<?= intval($porc) ?>%)</span>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <?php
                            }
                        }
                        if ($temNc == 1) {
                            $falta = @$notaFalta[$b]['falta_nc'];
                            $porc = $model->faltasPorc($falta, 'nc', $b);
                            ?>
                            <td>
                                <?php
                                if ($porc < 25) {
                                    ?>
                                    <?= intval($falta) ?> (<?= intval($porc) ?>%)
                                    <?php
                                } else {
                                    ?>
                                    <span style="color: red"><?= intval($falta) ?> (<?= intval($porc) ?>%)</span>
                                    <?php
                                }
                                ?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                }
                if (empty($v['id_tas'])) {
                    ?>
                    <tr>

                        <td rowspan="3" style="background-color: red;
                            writing-mode: vertical-rl;
                            transform: rotateZ(180deg);
                            color: white;
                            width: 5px">
                            Conselho
                        </td>
                        <td>
                            Média Final
                        </td>
                        <?php
                        foreach ($disciplinas as $id_disc => $d) {
                            if ($d['nucleo_comum'] == 1) {
                                if (empty($notaFaltaFinal['id_final'])) {
                                    ?>
                                    <td class="topo2 nota">
                                        ...
                                    </td>
                                    <?php
                                } else {
                                    $notaCons = @$notaFaltaFinal['cons_' . $id_disc];
                                    $nota = @$notaFaltaFinal['media_' . $id_disc];
                                    if (in_array($v['id_pessoa'], $apds)) {
                                        $nota = ' APD*<sup>1</sup>';
                                        $dica = 1;
                                    } elseif ($notaCons) {
                                        $dica = 1;
                                        $nota = $notaCons . ' (<span style="color: red">' . intval($nota) . '</span>) *<sup>2</sup>';
                                    }
                                    ?>
                                    <td class="topo2 nota" style="color: <?= $model->notaCor($nota) ?>">
                                        <?= str_replace('.', ',', $nota) ?>
                                    </td>
                                    <?php
                                }
                            }
                        }
                        foreach ($disciplinas as $id_disc => $d) {
                            if ($d['nucleo_comum'] == 0) {
                                $notaCons = @$notaFaltaFinal['cons_' . $id_disc];
                                $nota = @$notaFaltaFinal['media_' . $id_disc];
                                if (in_array($v['id_pessoa'], $apds)) {
                                    $nota = ' APD*<sup>1</sup>';
                                    $dica = 1;
                                } elseif ($notaCons) {
                                    $dica = 1;
                                    $nota = $notaCons . ' (<span style="color: red">' . intval($nota) . '</span>) *<sup>2</sup>';
                                }
                                $falta = @$notaFaltaFinal['falta_' . $id_disc];
                                $porc = $model->faltasPorc($falta, $id_disc);
                                if (empty($notaFaltaFinal['id_final'])) {
                                    ?>
                                    <td class="topo2 nota">
                                        ...
                                    </td>
                                    <?php
                                } else {
                                    ?>
                                    <td class="topo2 nota" style="color: <?= $model->notaCor($nota) ?>">
                                        <?= str_replace('.', ',', $nota) ?>
                                    </td>
                                    <?php
                                }
                                ?>
                                <td class="topo2">
                                    <?php
                                    if (empty($notaFaltaFinal['id_final'])) {
                                        echo '...';
                                    } elseif ($porc < 25) {
                                        ?>
                                        <?= intval($falta) ?> (<?= intval($porc) ?>%)
                                        <?php
                                    } else {
                                        ?>
                                        <span style="color: red"><?= intval($falta) ?> (<?= intval($porc) ?>%)</span>
                                        <?php
                                    }
                                    ?>
                                </td>
                                <?php
                            }
                        }
                        if ($temNc == 1) {
                            $falta = @$notaFaltaFinal['falta_nc'];
                            $porc = $model->faltasPorc($falta, 'nc');
                            ?>
                            <td>
                                <?php
                                if (empty($notaFaltaFinal['id_final'])) {
                                    echo '...';
                                } elseif ($porc < 25) {
                                    ?>
                                    <?= intval($falta) ?> (<?= intval($porc) ?>%)
                                    <?php
                                } else {
                                    ?>
                                    <span style="color: red"><?= intval($falta) ?> (<?= intval($porc) ?>%)</span>
                                    <?php
                                }
                                ?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    <tr>
                        <td colspan="<?= $col + $temNc + 1 ?>">
                            <?php
                            include ABSPATH . '/module/avalia/views/_ataEdit/conselho.php';
                            ?>
                        </td>
                    </tr>
                    <?php
                    if (!empty($dica)) {
                        ?>
                        <tr>
                            <td colspan="<?= $col + $temNc + 2 ?>" style="padding: 0px; text-align: left">
                                *<sup>1</sup> Aluno com deficiência de grande porte
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                *<sup>2</sup> Nota alterada para 5 pelo conselho. Nota anterior está entre aspas.
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                *<sup>3</sup> "i": Ignora o <?= $turma['un_letiva'] ?> no calculo da média.
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                *<sup>4</sup> * NL = Não Lançado.
                            </td>
                        </tr>
                        <?php
                    }
                }
                ?>
            </table>
            <br /><br />
            <?php
        }
        ?>
    </div>
    <form target="frame" id="bimEdit" action="<?= HOME_URI ?>/avalia/def/bimEdit" method="POST">
        <?=
        formErp::hidden([
            'id_turma' => $id_turma,
        ])
        ?>
        <input type="hidden" name="id_pessoa" id="id_pessoa" value="" />
    </form>
    <?php
    toolErp::modalInicio();
    ?>
    <iframe name="frame" style="width: 100%;
            border: none;
            height: 80vh"></iframe>
        <?php
        toolErp::modalFim();
        ?>
    <script>
        function edit(idPessos) {
            id_pessoa.value = idPessos;
            bimEdit.submit();
            $('#myModal').modal('show');
            $('.form-class').val('');
        }
    </script>
    <?php
} else {
    echo 'Algo errado não está certo ;(';
    exit();
}
