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
</style>
<?php
if (!defined('ABSPATH'))
    exit;
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
if ($id_turma) {
    $arr = $model->ataEdit($id_turma);
    $turma = $arr['turma'];
    $disciplinas = $arr['disciplinas']; // a chave é o id_disc
    $alunos = $arr['alunos'];
    $notaFalta = $arr['notaFalta']; // a chave é o id_pessoa
    $notaFaltaFinal = $arr['notaFaltaFinal']; // a chave é o id_pessoa
    $n_pl = sql::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $turma['id_pl']], 'fetch')['n_pl'];
    $plAtivos = ng_main::periodosAtivos();
    unset($arr);
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
        <div class="fieldTop">
            <p>
                ATA - <?= $turma['n_curso'] . ' - ' . $turma['n_turma'] ?> 
            </p>
            <p>
                Período Letivo: <?= $n_pl ?>  - Período: <?= $model->periodoDia($turma['periodo']) ?>
            </p>
        </div>
        <?php
        foreach ($alunos as $v) {
            if ($v['id_tas'] == 0) {
                $style = null;
            } else {
                $style = 'style="background-color: pink"';
            }
            ?>
            <table class="demotbl border" <?= $style ?> >
                <tr>
                    <td colspan="<?= $col + 1 + $temNc ?>">
                        <div class="row" style="font-weight: bold; font-size: 1.3em">
                            <div class="col-6" style="text-align: left">
                                nº <?= $v['chamada'] ?> - <?= $v['n_pessoa'] ?>
                            </div>
                            <div class="col-3">
                                RSE: <?= $v['id_pessoa'] ?>
                            </div>
                            <div class="col-3">
                                Situação: <?= $v['n_tas'] ?>
                            </div>
                        </div>
                        <br />
                    </td>
                </tr>
                <tr>
                    <td rowspan="2">

                    </td>
                    <?php
                    foreach ($disciplinas as $d) {
                        if ($d['nucleo_comum'] == 1) {
                            ?>
                            <td>
                                <?= $d['n_disc'] ?>
                            </td>
                            <?php
                        }
                    }
                    foreach ($disciplinas as $d) {
                        if ($d['nucleo_comum'] == 0) {
                            ?>
                            <td colspan="2">
                                <?= $d['n_disc'] ?>
                            </td>
                            <?php
                        }
                    }
                    if ($temNc == 1) {
                        ?>
                        <td>
                            Núcleo Comum
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <tr>
                    <?php
                    foreach ($disciplinas as $d) {
                        if ($d['nucleo_comum'] == 1) {
                            ?>
                            <td style="text-align: center">
                                Notas
                            </td>
                            <?php
                        }
                    }
                    foreach ($disciplinas as $d) {
                        if ($d['nucleo_comum'] == 0) {
                            ?>
                            <td>
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
                        <td>
                            <?php
                            if ((in_array($turma['id_pl'], $plAtivos) && $b > $turma['atual_letiva']) || empty($notaFalta[$v['id_pessoa']][$b]['id_nf'])) {
                                ?>
                                <button class="btn btn-secondary">
                                    Alterar <?= $b ?>º <?= $turma['un_letiva'] ?>
                                </button>
                                <?php
                            } else {
                                ?>
                                <button onclick="edit(<?= $v['id_pessoa'] ?>, <?= $b ?>)" class="btn btn-warning">
                                    Alterar <?= $b ?>º <?= $turma['un_letiva'] ?>
                                </button>
                                <?php
                            }
                            ?>

                        </td>
                        <?php
                        foreach ($disciplinas as $id_disc => $d) {
                            if ($d['nucleo_comum'] == 1) {
                                $nota = @$notaFalta[$v['id_pessoa']][$b]['media_' . $id_disc];
                                ?>
                                <td class="topo2" style="color: <?= $model->notaCor($nota) ?>">
                                    <?= $nota ?>
                                </td>
                                <?php
                            }
                        }
                        foreach ($disciplinas as $id_disc => $d) {
                            if ($d['nucleo_comum'] == 0) {
                                $nota = @$notaFalta[$v['id_pessoa']][$b]['media_' . $id_disc];
                                $falta = @$notaFalta[$v['id_pessoa']][$b]['falta_' . $id_disc];
                                ?>
                                <td class="topo2" style="color: <?= $model->notaCor($nota) ?>">
                                    <?= $nota ?>
                                </td>
                                <td class="topo2">
                                    <?= $falta ?>
                                </td>
                                <?php
                            }
                        }
                        if ($temNc == 1) {
                            ?>
                            <td>
                                <?= @$notaFalta[$v['id_pessoa']][$b]['falta_nc'] ?>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                }
                if ($v['id_tas'] == 0) {
                    ?>
                    <tr>
                        <td>
                            conselho
                        </td>
                    </tr>
                    <?php
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
            'id_pl' => $turma['id_pl'],
            'id_curso' => $turma['id_curso']
        ])
        ?>
        <input type="hidden" name="id_pessoa" id="id_pessoa" value="" />
        <input type="hidden" name="bimestre" id="bimestre" value="" />
    </form>
    <?php
    toolErp::modalInicio();
    ?>
    <iframe name="frame" style="width: 100%; border: none; height: 80vh"></iframe>
        <?php
        toolErp::modalFim();
        ?>
    <script>
        function edit(idPessos, bim) {
            bimestre.value = bim;
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
