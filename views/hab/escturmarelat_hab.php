<div class="fieldBody">

    <?php
    if (!empty($_POST['id_turma'])) {
               include ABSPATH . '/views/hab/carometro_rel.php';
 
    } else {
        if (!empty($id_inst)) {
            if ($turmas = hab::turmas($id_inst)) { //está configurado no constructor
                
                ?>
                <br />
                <div class="fieldBorder2" role="alert" style="text-align: center; font-weight: bold; font-size: 16px; padding: 25px; width: 80%; margin: 0 auto">
                    <?php // echo $turma['escola']  ?>
                    <br /><br />
                    <div class="row">
                        <div class="col-md-3">
                            <?php
                            $turmas = hab::turmas($id_inst);
                            foreach ($turmas as $v) {
                                $options[$v['id_turma']] = $v['n_turma'];
                            }
                            formulario::select('id_turma', $options, 'Turma', @$_POST['id_turma'], 1, ['id_turma' => $v['id_turma'], 'id_inst' => $id_inst, 'rel' => 'relturma_hab_total']);
                            ?> 

                        </div>
                    </div>
                </div>
                <?php
            }
        }
    }
    ?>
</div>