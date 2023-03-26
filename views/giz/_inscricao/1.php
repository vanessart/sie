<?php
if (!empty($escolas)) {
    ?> 
    <br /><br /><br />
    <div class="row">
        <?php
        if (!empty($_POST['recomeçar'])) {
            unset($id_inst);
            unset($id_mod);
        } elseif (!empty($_POST['continuar'])) {
            $id_mod = @$_POST['id_mod'];
            $id_inst = @$_POST['id_inst'];
        }
        if (empty($id_inst)) {
            ?>
            <form method="POST">
                <div class="col-sm-5">
                    <?php echo formulario::selectDB('giz_modalidade', 'id_mod', 'Modalidade', @$id_mod, (empty($proj['gestor']) ? $disabled : 'disabled') . ' required ', NULL, NULL, NULL, ['fk_id_cate' => $id_cate]) ?>
                </div>
                <div class="col-sm-5">
                    <?php echo formulario::select('id_inst', $escolas, 'Escola', @$id_inst, NULL, ['id_cate' => $id_cate], (empty($proj['gestor']) ? $disabled : 'disabled') . ' required ') ?>
                </div>
                <div class="col-sm-2">
                    <input <?php echo (empty($proj['gestor']) ? $disabled : 'disabled') ?> class="btn btn-success" name="continuar" type="submit" value="Continuar" />
                </div>
            </form>
            <?php
        } else {
            ?>
            <form method="POST">
                <div class="col-sm-5">
                    <?php echo formulario::selectDB('giz_modalidade', 'id_mod', 'Modalidade', $id_mod, ' disabled required ', NULL, NULL, NULL, ['fk_id_cate' => $id_cate]) ?>
                </div>
                <div class="col-sm-5">
                    <?php echo formulario::select('id_inst', $escolas, 'Escola', $id_inst, NULL, ['id_cate' => $id_cate], ' disabled required ') ?>
                </div>
                <div class="col-sm-2">
                    <input <?php echo (empty($proj['gestor']) ? $disabled : 'disabled') ?> class="btn btn-success" type="submit" name="recomeçar" value="Recomeçar Modalidade e Escola" />
                </div>
            </form>
            <?php
        }
        ?>
    </div>
    <br />
    <?php
    if (!empty($id_inst)) {
        ?>
        <form method="POST">
            <div class="row">
                <div class="col-sm-6">
                    <?php
                    $ciclos = sql::get('giz_modalidade', 'ciclos', ['id_mod' => $id_mod], 'fetch')['ciclos'];
                    $turmaOptions = turma::liste($id_inst, NULL, 'fk_id_inst', $ciclos);
                    $sql = "select disciplinas from ge_prof_esc pe "
                            . " join ge_funcionario f on f.rm = pe.rm "
                            . " where fk_id_pessoa = " . tool::id_pessoa();
                    $query = $model->db->query($sql);
                    $d = $query->fetch()['disciplinas'];
                    $sql = "select * from ge_disciplinas where id_disc in ('" . str_replace("|", "','", substr($d, 1, -1)) . "')";
                    $query = $model->db->query($sql);
                    $array = $query->fetchAll();
                    foreach ($array as $v) {
                        $disc[$v['id_disc']] = $v['n_disc'];
                    }
                    $disc[27] = 'Infantil';
                    $disc[1] = 'Polivalente';
                    if ($id_cate == 3) {
                        echo formulario::select('id_disc', $disc, 'Disciplina', $proj['fk_id_disc'], NULL, NULL, 'required ' .$disabled);
                    }
                    ?>
                </div>
            </div>
            <br /><br />
            <?php
            if (!empty($turmaOptions)) {
                ?>
                <div style="text-align: center; font-weight: bold; font-size: 18px">
                    Classes envolvidas no projeto
                </div>
                <br /><br />
                <?php
                $turmas = unserialize($proj['turmas']);
                foreach ($turmaOptions as $v) {
                    ?>
                    <div style="padding: 20px; float: left; width: 20%">
                        <label>
                            <input <?php
                            echo $disabled;
                            echo @in_array($v['id_turma'], $turmas) ? 'checked' : ''
                            ?> type="checkbox" name="turmas[<?php echo $v['id_turma'] ?>]" value="<?php echo $v['id_turma'] ?>" />
                                <?php echo $v['n_turma'] ?>
                        </label> 
                    </div>
                    <?php
                }
                if ($giz['fase'] == 1) {
                    ?>
                    <br /><br />
                    <div class="row">
                        <div class="col-sm-12" style="text-align: center">
                            <input type="hidden" name="activeNav" value="2" />
                            <input type="hidden" name="id_prof" value="<?php echo $proj['id_prof'] ?>" />
                            <input type="hidden" name="id_cate" value="<?php echo $id_cate ?>" />
                            <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                            <input type="hidden" name="id_mod" value="<?php echo $id_mod ?>" />
                            <input type="hidden" name="id_pessoa" value="<?php echo $id_pessoa ?>" />
                            <?php echo DB::hiddenKey('salvaInscr') ?>
                            <input class="btn btn-success" type="submit" value="Continuar" />
                        </div>
                    </div>
                </form>
                <?php
            }
        } else {
            ?>
            <div class="alert alert-danger" style="text-align: center; font-size: 18px; font-weight: bold">
                Não há classes neste segmento.
            </div>
            <?php
        }
    }
} else {
    ?>
    <div class="alert alert-danger" style="text-align: center; font-size: 18px; font-weight: bold">
        Você não está alocado em uma escola.
    </div>
    <?php
}
