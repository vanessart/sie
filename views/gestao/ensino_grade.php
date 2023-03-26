<div class="container">
    <div class="field row">
        <form method="POST">
            <div class="col-lg-8">
                <?php formulario::selectDB('ge_grades', '1[fk_id_grade]', 'Incluir no Ciclo <b>' . $ciclo['n_ciclo'] . '</b> do Curso <b>' . @$curso['n_curso'] . '</b> a Grade: ', @$grade['fk_id_grade'], 'required', NULL, NULL, NULL, ['fk_id_ta' => $curso['fk_id_ta']]) ?>
            </div>
            <div class="col-lg-3">
                <?php formulario::checkbox('1[padrao]', 1, 'Grade Padrão', @$grade['padrao']) ?>
            </div>
            <div class="col-lg-1">

                <?php
                echo DB::hiddenKey('ge_curso_grade', 'replace');
                ?>
                <input type="hidden" name="aba" value="grade" />
                <input type="hidden" name="1[id_cg]" value="<?php echo @$id_cg ?>" />
                <input type="hidden" name="id_ciclo" value="<?php echo $id_ciclo ?>" />
                <input type="hidden" name="id_curso" value="<?php echo $id_curso ?>" />
                <input type="hidden" name="id_tp_ens" value="<?php echo $id_tp_ens ?>" />
                <input type="hidden" name="1[fk_id_ciclo]" value="<?php echo$id_ciclo ?>" />
                <button class="btn btn-success">
                    <?php echo empty($id_cg)?'Incluir':'Editar' ?>
                </button>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?php $model->listGradeInclui($id_ciclo) ?>
        </div>
    </div>
</div>
