<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = tool::id_inst();
$turmas = ng_escola::turmasSegAtiva($id_inst);

$esc = new ng_escola($id_inst);
@$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_turma)) {
    $id_curso = sql::get(['ge_turmas', 'ge_ciclos'], 'fk_id_curso', ['id_turma' => $id_turma], 'fetch')['fk_id_curso'];
    $alocado = sql::get('ge_aloca_prof', '*', ['fk_id_turma' => $id_turma]);
    foreach ($alocado as $v) {
        $aloca[$v['iddisc']] = $v['rm'];
    }
}
?>
<div class="fieldBody">

    <div class="col fieldTop">
        Horário
    </div>
    <div style="padding-left: 30px">
        <div class="row noprint">
            <div class="col">
                <?= formErp::select('id_turma', $turmas, 'Turma', $id_turma, 1) ?>
            </div>
            <?php
            if (!empty($id_turma)) {
                ?>
                <div class="col-md-4">  
                    <button name = "horario" value = "Imprimir" onclick="document.getElementById('imprimir').submit()" style="width: 41%;  font-weight: 900" type="submit" class="btn btn-info">
                        Imprimir
                    </button>              
                </div>
                <?php
            }
            ?>

        </div>
    </div>
    <br /><br />
    <?php
    // $id_turma;
    if (!empty($id_turma)) {
        ?>
        <iframe style="border: none; width: 100%; height: 200vh" src="<?php echo HOME_URI ?>/sed/horarioset?id=<?php echo $id_turma ?>&id_inst=<?php echo $id_inst ?>"></iframe>
        <?php
    }
    ?>
    <form target="_blank" action="<?php echo HOME_URI ?>/sed/pdf/horariopdf.php" id="imprimir" method="POST">
        <input type="hidden" name="id_turma" value="<?php echo $id_turma ?>" />
    </form> 
</div>
