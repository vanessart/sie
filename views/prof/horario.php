<?php

if (user::session('id_nivel') == 14) {
    $id_inst = @$_POST['id_inst'];
    echo formulario::select('id_inst', escolas::idInst(), 'Escola', @$id_inst, 1);
} else {
    $id_inst = tool::id_inst(@$_POST['id_inst']);
}
if (!empty($id_inst)) {
    $esc = new escola($id_inst);
    @$id_turma = $_POST['id_turma'];
    @$id_curso = $_POST['id_curso'];
    if (!empty($id_turma)) {
        $alocado = sql::get('ge_aloca_prof', '*', ['fk_id_turma' => $id_turma]);
        foreach ($alocado as $v) {
            $aloca[$v['iddisc']] = $v['rm'];
        }
    }
    ?>
    <div class="fieldBody">
        <div class="fieldTop">
            Horário
        </div>
        <br />
        <div style="padding-left: 30px">
            <div class="row noprint">

                <?php
                $ensino = $esc->cursos();

                if (count($ensino) > 1) {
                    foreach ($ensino as $e) {
                        $ep[$e['id_curso']] = $e['n_curso'];
                    }
                    ?>
                    <div class="col-md-4">
                        <?php
                        formulario::select('id_curso', $ep, 'Segmento: ', NULL, 1, ['id_inst' => $id_inst]);
                        ?>
                    </div>
                    <?php
                }
                if (count($ensino) == 1 || !empty($id_curso)) {
                    $classes = $esc->turmas(NULL, @$id_curso);

                    if (!empty($classes)) {
                        foreach ($classes as $v) {
                            $options[$v['id_turma']] = $v['codigo'] . ' (' . $v['n_turma'] . ')';
                        }
                        ?>
                        <div class="col-md-4">
                            <?php
                            formulario::select('id_turma', $options, 'Classe: ', NULL, 1, ['id_curso' => $id_curso, 'id_inst' => $id_inst]);
                            ?>
                        </div>
                        <?php
                    } else {
                        tool::alert("Não há Classes nesse segmento");
                    }
                }
                if (!empty($id_turma)) {
                    ?>
                    <div class="col-md-4">  
                        <button name = "horario" value = "Imprimir" onclick="document.getElementById('imprimir').submit()" style="width: 41%;  font-weight: 900" type="submit" class="art-button">
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
            <iframe style="border: none; width: 100%; height: 200vh" src="<?php echo HOME_URI ?>/prof/horarioset?id=<?php echo $id_turma ?>&id_inst=<?php echo $id_inst ?>"></iframe>
            <?php
        }
        ?>
        <form target="_blank" action="<?php echo HOME_URI ?>/prof/horariopdf" id="imprimir" method="POST">
            <input type="hidden" name="turma" value="<?php echo $id_turma ?>" />
            <input type="hidden" name="curso" value="<?php echo $id_curso ?>" />
        </form> 
    </div>
    <?php
}
?>