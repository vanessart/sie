<?php
$id_inst = toolErp::id_inst();
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);

$horario = gtTurmas::horario($id_turma);
$reforco = gtTurmas::reforco($id_turma);
$turma = sql::get(['ge_turmas', 'ge_ciclos'], 'ge_turmas.n_turma, ge_turmas.fk_id_ciclo, ge_turmas.periodo, ge_turmas.fk_id_grade, ge_ciclos.aulas', ['id_turma' => $id_turma], 'fetch');
$grade = gtMain::discGrade($turma['fk_id_grade']);
$profDisc = gtTurmas::professores($id_turma);
$semana = [1 => 'Segunda', 2 => 'Terça', 3 => 'Quarta', 4 => 'Quinta', 5 => 'Sexta'];
ob_start();
$pdf = new pdf();
?>
<style>
    dt{
        width: 18%;
        height: 50px;
    }
</style>
<table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
    <thead>
        <tr>
            <th colspan="<?php echo count($semana) + 1 ?>" style="text-align: center; font-weight: bold; font-size: 30px">
                <?php echo $turma['n_turma'] ?>
            </th>
        </tr>
        <tr>
            <th></th>
            <?php
            foreach ($semana as $v) {
                ?>
                <th style="text-align: center; font-size: 18px; padding: 5px">
                    <?php echo $v ?>
                </th>
                <?php
            }
            ?>

        </tr>
    </thead>
    <tbody>
        <?php
        for ($aula = 1; $aula <= $turma['aulas']; $aula++) {
            ?>
            <tr>
                <td style="font-weight: bold; text-align: center; padding: 2px;">
                    <div class="border" style="height: 150px">
                        <?php echo $aula ?>ª Aula
                    </div>
                </td>
                <?php
                for ($dia = 1; $dia <= 5; $dia++) {
                    ?>
                    <td   style="width: 18%; padding: 2px;">
                        <div class="border" style=" height: 150px">
                            <?= @$grade[@$horario[$dia][$aula]]['n_disc'] ?>
                            <br /><br />
                            <?php
                            foreach (range(2, 15) as $p2) {
                                if (!empty($profDisc[$p2][@$horario[$dia][$aula]]['abrev'])) {
                                    ?>
                                    <div id="<?php echo $dia ?>x<?php echo $aula ?>" >
                                        <?php echo @$profDisc[$p2][@$horario[$dia][$aula]]['abrev']; ?>
                                    </div>
                                    <br />
                                    <?php
                                }
                            }
                            ?>


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
<?php
$pdf->exec();
