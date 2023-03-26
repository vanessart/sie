<?php
if (user::session('id_nivel') != 14) {
   $id_inst = tool::id_inst(@$_POST['id_inst']);
   @$fechado = cadamp::escolaFechaAbre($id_inst)['professor'];
    if ($fechado == 1) {
        $disabled = 'disabled';
        ?>
        <div class="alert alert-danger noprint" style="text-align: center; font-size: 18px">
            Alocação Fechada
        </div>
        <?php
    }
} else {
$id_inst = @$_POST['id_inst'];
}
if(!empty($id_inst)){
$id_turma = $_REQUEST['id'];

$horario = gtTurmas::horario($id_turma);
$reforco = gtTurmas::reforco($id_turma);
$turma = sql::get('ge_turmas', 'n_turma, fk_id_ciclo, periodo, fk_id_grade', ['id_turma' => $id_turma], 'fetch');
$grade = gtMain::discGrade($turma['fk_id_grade']);
$profDisc = gtTurmas::professores($id_turma);
$semana = [1 => 'Segunda', 2 => 'Terça', 3 => 'Quarta', 4 => 'Quinta', 5 => 'Sexta'];
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
                <th colspan="<?php echo count($semana)+1 ?>" style="text-align: center; font-weight: bold; font-size: 30px">
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
            for ($aula = 1; $aula <= 5; $aula++) {
                ?>
                <tr>
                    <td style="font-weight: bold; text-align: center; padding: 2px;">
                        <div class="fieldBorder2" style="height: 150px">
                            <?php echo $aula ?>ª Aula
                        </div>
                    </td>
                    <?php
                    for ($dia = 1; $dia <= 5; $dia++) {
                        ?>
                        <td   style="width: 18%; padding: 2px;">
                            <div class="fieldBorder2" style=" height: 150px">
                                <select <?php echo @$disabled ?> style="width: 100%" name="aula[<?php echo $dia ?>][<?php echo $aula ?>]" onchange="prof(this, '<?php echo $dia ?>x<?php echo $aula ?>', '<?php echo $dia ?>x<?php echo $aula ?>x');">
                                    <option></option>
                                    <?php
                                    foreach ($grade as $k => $v) {
                                        ?>
                                        <option <?php echo @$horario[$dia][$aula] == $k ? 'selected' : '' ?> style=" width: 100px" value="<?php echo $k ?>"><?php echo $v['n_disc'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <br /><br />
                                <div id="<?php echo $dia ?>x<?php echo $aula ?>" >
                                    <?php echo @$profDisc[1][@$horario[$dia][$aula]]['abrev']; ?>
                                </div>
                                <br />
                                <div id="<?php echo $dia ?>x<?php echo $aula ?>x" >
                                    <?php echo @$profDisc[2][@$horario[$dia][$aula]]['abrev']; ?>
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
                        Reforço <?php echo $turma['periodo'] == 'T' ? 'Pré' : 'Pós' ?>-Aula
                    </td>
                </tr>
                <tr>
                    <td style="font-weight: bold"></td>
                    <?php
                    foreach ($semana as $dia => $v) {
                        ?>
                        <td style="padding: 2px">
                            <div class="fieldBorder2">
                                <label>
                                    <input type="hidden" name="reforco[<?php echo $dia ?>]" value="" />
                                    <input <?php echo @in_array($dia, $reforco) ? 'checked' : '' ?> <?php echo @$disabled ?> onclick="reforc(this)" id="ref<?php echo $dia ?>" type="checkbox" name="reforco[<?php echo $dia ?>]" value="<?php echo $dia ?>" />
                                    Reforço na <?php echo $v ?>
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
       <?php echo DB::hiddenKey('alocahorario') ?>
        <input type="hidden" name="id_grade" value="<?php echo $turma['fk_id_grade'] ?>" />
        <input type="hidden" name="id" value="<?php echo $id_turma ?>" />
        <input  <?php echo @$disabled ?> class="btn btn-success noprint" type="submit" value="Salvar" />
    </div>
</form>
<script>
    function reforc(id) {
        c = 0;
<?php
foreach ($semana as $dia => $v) {
    ?>
            if (document.getElementById('ref<?php echo $dia ?>').checked == true) {
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
                pro['<?php echo @$k ?>'] = '<?php echo @$v['abrev'] ?>';
        <?php
    }
    ?>

            return pro[id];
        }
    <?php
}
if (!empty($profDisc[2])) {
    ?>
        function profList1(id) {
            pro = new Array();

    <?php
    foreach ($profDisc[2] as $k => $v) {
        ?>
                pro['<?php echo @$k ?>'] = '<?php echo @$v['abrev'] ?>';
        <?php
    }
    ?>

            return pro[id];
        }
    <?php
}
?>

    function prof(id, d, dx) {
<?php
if (!empty($profDisc[1])) {
    ?>
            document.getElementById(d).innerHTML = profList(id.value) == undefined ? "" : profList(id.value);
    <?php
}
if (!empty($profDisc[2])) {
    ?>
            document.getElementById(dx).innerHTML = profList1(id.value) == undefined ? "" : profList1(id.value);

    <?php
}
?>
    }


</script>
<?php
}