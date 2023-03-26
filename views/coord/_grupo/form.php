<?php
if (!empty($_REQUEST['id_curso'])) {
    $sql = " SELECT id_disc, n_disc FROM `ge_ciclos` ci "
            . " join ge_curso_grade cg on cg.fk_id_ciclo = ci.id_ciclo "
            . " join ge_aloca_disc ad on  ad.fk_id_grade = cg.fk_id_grade "
            . " join ge_disciplinas d on d.id_disc = ad.fk_id_disc "
            . " where ci.fk_id_curso = " . $_REQUEST['id_curso']
            . " order by n_disc ";
    $query = $model->db->query($sql);
    $d = $query->fetchAll();
    foreach ($d as $v) {
        $disc[$v['id_disc']] = $v['n_disc'];
    }
    if (!empty($disc)) {
        ?>   
        <br /><br />
        <form method = "POST">

            <div class="row">
                <div class="col-md-5">
                    <?php formulario::input('1[n_gr]', 'Grupo') ?>
                </div>
                <div class="col-md-3">
                    <?php formulario::input('1[cod_gr]', 'Código') ?>
                </div>
                <div class="col-m4">
                    <?php
                    formulario::select('1[fk_id_disc]', $disc, 'Disciplina');
                    ?>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-md-12">
                    <textarea name="1[obs_gr]" style="width: 100%" placeholder="Descrição"></textarea>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-md-6 text-center">
                    <input onclick="$('#limp').submit()" class="btn btn-warning" type="button" value="Limpar" />
                </div>
                <div class="col-md-6 text-center">
                    <?php echo DB::hiddenKey('coord_grupo', 'replace') ?>
                    <input type="hidden" name="1[id_gr]" value="<?php echo @$_REQUEST['id_gr'] ?>" />
                    <input type="hidden" name="1[fk_id_curso]" value="<?php echo @$_REQUEST['id_curso'] ?>" />
                    <input type="hidden" name="id_tp_ens" value="<?php echo @$_REQUEST['id_tp_ens'] ?>" />
                    <input type="hidden" name="id_curso" value="<?php echo @$_REQUEST['id_curso'] ?>" />
                    <input class="btn btn-success" type="submit" value="Salvar" />
                </div>
            </div>
        </form >
        <form id="limp" method="POST">
            <div class="col-md-6 text-center">
                <input type="hidden" name="modal" value="1" />
                <input type="hidden" name="id_tp_ens" value="<?php echo @$_REQUEST['id_tp_ens'] ?>" />
                <input type="hidden" name="id_curso" value="<?php echo @$_REQUEST['id_curso'] ?>" />
            </div>
        </form>
        <?php
    } else {
        ?>
        <div class="alert alert-warning">
            Não há disciplinas alocadas neste curso. Cadastre ao menos uma para continuar
        </div>
        <?php
    }
}