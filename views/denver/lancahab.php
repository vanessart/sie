<div class="fieldBody">
    <div class="fieldTop">
        Lançamento Habilidades
    </div>
    <?php
    if (!empty($id_pessoa)) {
        $classe = $model->pegaclasseprof($id_pessoa);
        if (!empty($classe)) {
            foreach ($classe as $v) {
                ?>
                <br />
                <div class="fieldBorder2" role="alert" style="text-align: center; font-weight: bold; font-size: 16px; padding: 25px; width: 80%; margin: 0 auto">
                    <div class="row">
                        <div class="col-md-4">
                            <?php echo $v['n_inst'] ?>
                        </div>
                        <form action="<?php echo HOME_URI ?>/denver/lancahabdig/" method="POST">
                            <div class="col-md-4">
                                <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" />
                                <input  style="width: 150px" class="btn btn-success" type="submit" value="<?php echo $v['codigo'] ?>" />                           
                            </div>
                        </form>

                        <form action="<?php echo HOME_URI; ?>/denver/lancahabclasse/" method="POST">
                            <div class="col-md-4 text-center">
                                <input type="hidden" name="id_turma" value="<?php echo $v['id_turma'] ?>" />
                                <input  style="width: 150px" class="btn btn-info" type="submit" value="Visualizar Ficha" />
                            </div>
                        </form>
                    </div>
                </div>             
                <?php
            }
        } else {
            ?>
            <div>
                <span>
                    Você não tem classes alocadas. Para alocar, procure a secretaria de sua escola.
                </span>
            </div>
            <?php
        }
    }
    ?>
</div>


