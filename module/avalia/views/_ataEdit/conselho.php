<div class="row">
    <div class="col-2" style="text-align: left">
        <img style="width: 140px" src="<?= toolErp::fotoEndereco($v['id_pessoa']) ?>" alt = "Foto do aluno"/>
    </div>
    <?php
    if (!empty($notaFaltaFinal['id_final'])) {
        ?>
        <div class="col-10" style="text-align: left">
            <div class="row" style="padding-top: 10px; font-weight: bold; font-size: 1.6em">
                <div class="col">
                    <div style="padding-top: 15px">
                        <p <?= $CorFreqFinal ?>>
                            Percentual de Faltas: <?= $freqFinal ?>%
                        </p>
                        <p>
                            Situação Final: <?= $SitFinal[0] ?>
                        </p>
                        <?php
                        if (in_array($SitFinal[1], [2, 5])) {
                            if (($data >= $set['dt_inicio_conselho'] && $data <= $set['dt_fim_conselho']) || $set['at_conselho'] == 1) {
                                if ($SitFinal[1] == 2) {
                                    $id_sf = 5;
                                    $texto = 'Mudar a situação d' . toolErp::sexoArt($v['sexo']) . ' alun' . toolErp::sexoArt($v['sexo']) . ' ' . $v['n_pessoa'] . ' para Retido p/ Conselho?';
                                    ?>
                                    <button onclick="if (confirm('<?= $texto ?>')) {
                                                sf_<?= $v['id_turma_aluno'] ?>.submit();
                                            }" type="button" class="btn btn-outline-danger">
                                        Alterar a situação para Retido p/ Conselho
                                    </button>
                                    <?php
                                } elseif ($SitFinal[1] == 5) {
                                    $id_sf = 2;
                                    $texto = 'Mudar a situação d' . toolErp::sexoArt($v['sexo']) . ' alun' . toolErp::sexoArt($v['sexo']) . ' ' . $v['n_pessoa'] . ' para Promovido p/ Conselho?';
                                    ?>
                                    <button onclick="if (confirm('<?= $texto ?>')) {
                                                sf_<?= $v['id_turma_aluno'] ?>.submit();
                                            }" type="button" class="btn btn-outline-success">
                                        Alterar a situação para Promovido p/ Conselho
                                    </button>
                                    <?php
                                }
                                ?>
                                <form id="sf_<?= $v['id_turma_aluno'] ?>" method="POST">
                                    <?=
                                    formErp::hidden([
                                        '1[situacao_final]' => $id_sf,
                                        '1[fk_id_sf]' => $id_sf,
                                        'id_turma' => $id_turma,
                                        '1[id_turma_aluno]' => $v['id_turma_aluno']
                                    ])
                                    . formErp::hiddenToken('ge_turma_aluno', 'ireplace');
                                    ?>
                                </form>
                                <?php
                            } else {
                                if ($SitFinal[1] == 2) {
                                    $id_sf = 5;
                                    ?>
                                    <button onclick=" $('#myModal').modal('show');
                                            $('.form-class').val('');
                                            sf_<?= $v['id_turma_aluno'] ?>.submit();" type="button" class="btn btn-outline-danger">
                                        Alterar a situação para Retido p/ Conselho
                                    </button>
                                    <?php
                                } elseif ($SitFinal[1] == 5) {
                                    $id_sf = 2;
                                    ?>
                                    <button onclick=" $('#myModal').modal('show');$('.form-class').val('');sf_<?= $v['id_turma_aluno'] ?>.submit();" type="button" class="btn btn-outline-success">
                                        Alterar a situação para Promovido p/ Conselho
                                    </button>
                                    <?php
                                }
                                ?>
                                <form target="frame" action="<?= HOME_URI ?>/avalia/def/peloConselho" id="sf_<?= $v['id_turma_aluno'] ?>" method="POST">
                                    <?=
                                    formErp::hidden([
                                        '1[situacao_final]' => $id_sf,
                                        '1[fk_id_sf]' => $id_sf,
                                        'id_turma' => $id_turma,
                                        '1[id_turma_aluno]' => $v['id_turma_aluno'],
                                        'id_pessoa' => $v['id_pessoa'],
                                        'id_pl' => $turma['id_pl'],
                                        'n_turma' => $turma['n_turma'],
                                        'id_curso' => $turma['id_curso']
                                    ])
                                    ?>
                                </form>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="col" style="text-align: left">

                </div>
            </div>
        <?php
    } else {
        ?>
            <div class="col">
                <div class="alert alert-warning" style="padding: 50px">
            Conselho Fechado 
            <br />
            ou
            <br />
            Falta algum lançamentos de nota ou falta.
                </div>
            </div>
        <?php
    }
    ?>
        </div>
</div>