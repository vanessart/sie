<?php
if (user::session('id_nivel') != 14) {
    $id_inst = tool::id_inst(@$_POST['id_inst']);
} else {
    $id_inst = @$_POST['id_inst'];
}
if (!empty($id_inst)) {
    $id_turma = $_REQUEST['id'];

    $horario = gtTurmas::horario($id_turma);
    $reforco = gtTurmas::reforco($id_turma);
    $turma = sql::get('ge_turmas', 'n_turma, fk_id_ciclo, periodo, fk_id_grade', ['id_turma' => $id_turma], 'fetch');
    $grade = gtMain::discGrade($turma['fk_id_grade']);
    $profDisc = gtTurmas::professores($id_turma);

    $ciclo = sql::get('ge_ciclos', 'aulas, dias_semana', ['id_ciclo' => $turma['fk_id_ciclo']], 'fetch');
    if (!empty($ciclo['dias_semana'])) {
        $semanaNome = [1 => 'Segunda', 2 => 'Terça', 3 => 'Quarta', 4 => 'Quinta', 5 => 'Sexta', 6 => 'Sábado', 0 => 'Domingo'];
        foreach (explode(',', $ciclo['dias_semana']) as $v) {
            $semana[$v] = $semanaNome[$v];
        }
    } else {
        $semana = [1 => 'Segunda', 2 => 'Terça', 3 => 'Quarta', 4 => 'Quinta', 5 => 'Sexta'];
    }
    if (!empty($ciclo['aulas'])) {
        $aulas = $ciclo['aulas'];
    } else {
        $aulas = 5;
    }
    ?>
    <style>
        dt{
            width: 18%;
            height: 50px;
        }
    </style>
    <form method="POST">
        <table style="width: 100%">
            <thead>
                <tr>
                    <th colspan="<?= count($semana) + 1 ?>" style="text-align: center; font-weight: bold; font-size: 30px">
                        <?= $turma['n_turma'] ?> - <?= gtMain::periodoDoDia($turma['periodo']); ?>
                    </th>
                </tr>
                <tr>
                    <th></th>
                    <?php
                    foreach ($semana as $v) {
                        ?>
                        <th style="text-align: center; font-size: 18px; padding: 5px">
                            <?= $v ?>
                        </th>
                        <?php
                    }
                    ?>

                </tr>
            </thead>
            <tbody>
                <?php
                for ($aula = 1; $aula <= $aulas; $aula++) {
                    ?>
                    <tr>
                        <td style="font-weight: bold; text-align: center; padding: 2px;">
                            <div class="border" style="height: 150px">
                                <?= $aula ?>ª Aula
                            </div>
                        </td>
                        <?php
                        foreach ($semana as $dia => $sem) {
                            ?>
                            <td   style="width: 18%; padding: 2px;">
                                <div class="border" style=" min-height: 150px">
                                    <select style="width: 100%" name="aula[<?= $dia ?>][<?= $aula ?>]" onchange="prof(this, '<?= $dia ?>x<?= $aula ?>', '<?= $dia ?>x<?= $aula ?>x');">
                                        <option></option>
                                        <?php
                                        foreach ($grade as $k => $v) {
                                            ?>
                                            <option <?= @$horario[$dia][$aula] == $k ? 'selected' : '' ?> style=" width: 100px" value="<?= $k ?>"><?= $v['n_disc'] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <br /><br />
                                    <div id="<?= $dia ?>x<?= $aula ?>" >
                                        <?= @$profDisc[1][@$horario[$dia][$aula]]['abrev']; ?>
                                    </div>
                                    <div id="<?= $dia ?>x<?= $aula ?>x" >
                                        <?php
                                        foreach (range(2, 15) as $p2) {
                                            if (!empty($profDisc[$p2][@$horario[$dia][$aula]]['abrev'])) {
                                                echo $profDisc[$p2][@$horario[$dia][$aula]]['abrev'] . '<br>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                }
                if (in_array($turma['fk_id_ciclo'], [1, 2, 3])) {
                    ?>
                    <tr>
                        <td colspan="6" style="text-align: center; font-weight: bold; padding: 15px; font-size: 18px">
                            <br /><br />
                            Reforço <?= $turma['periodo'] == 'T' ? 'Pré' : 'Pós' ?>-Aula
                        </td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold"></td>
                        <?php
                        foreach ($semana as $dia => $v) {
                            ?>
                            <td style="padding: 2px">
                                <div class="border">
                                    <label>
                                        <input type="hidden" name="reforco[<?= $dia ?>]" value="" />
                                        <input <?= @in_array($dia, $reforco) ? 'checked' : '' ?> onclick="reforc(this)" id="ref<?= $dia ?>" type="checkbox" name="reforco[<?= $dia ?>]" value="<?= $dia ?>" />
                                        Reforço na <?= $v ?>
                                    </label>
                                </div>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <div class="noprint" style="text-align: center">
            <br /><br />
            <?= formErp::hiddenToken('alocahorario') ?>
            <input type="hidden" name="id_grade" value="<?= $turma['fk_id_grade'] ?>" />
            <input type="hidden" name="id" value="<?= $id_turma ?>" />
            <input class="btn btn-success noprint" type="submit" value="Salvar" />
        </div>
    </form>
    <script>
        function reforc(id) {
            c = 0;
    <?php
    foreach ($semana as $dia => $v) {
        ?>
                if (document.getElementById('ref<?= $dia ?>').checked == true) {
                    c = c + 1;
                }
        <?php
    }
    ?>
            if (c > 3) {
                id.checked = false;
                alert("São apenas três reforços por semana");
            }
        }
    <?php
    if (!empty($profDisc[1])) {
        ?>
            function profList(id) {
                pro = new Array();

        <?php
        foreach ($profDisc[1] as $k => $v) {
            ?>
                    pro['<?= @$k ?>'] = '<?= @$v['abrev'] ?>';
            <?php
        }
        ?>

                return pro[id];
            }
        <?php
    }
    ?>
        function profList1(id) {
            pro = new Array();

    <?php
    foreach (range(2, 9) as $p2) {
        if (!empty($profDisc[$p2])) {
            foreach ($profDisc[$p2] as $k => $v) {
                if (!empty($v['abrev'])) {
                    ?>
                            if (pro['<?= @$k ?>'] == undefined) {
                                pro['<?= @$k ?>'] = '<?= @$v['abrev'] ?>';
                            } else {
                                pro['<?= @$k ?>'] = pro['<?= @$k ?>'] + '<br>' + '<?= @$v['abrev'] ?>';
                            }
                    <?php
                }
            }
        }
    }
    ?>

            return pro[id];
        }


        function prof(id, d, dx) {
    <?php
    if (!empty($profDisc[1])) {
        ?>
                document.getElementById(d).innerHTML = profList(id.value) == undefined ? "" : profList(id.value);
        <?php
    }
    if (!empty($profDisc[2]) || !empty($profDisc[3]) || !empty($profDisc[4]) || !empty($profDisc[5]) || !empty($profDisc[6]) || !empty($profDisc[7]) || !empty($profDisc[8]) || !empty($profDisc[9])) {
        ?>
                document.getElementById(dx).innerHTML = profList1(id.value) == undefined ? "" : profList1(id.value);

        <?php
    }
    ?>
        }


    </script>
    <?php
}