<?php
if (!empty($_POST['last_id'])) {
    @$id_pl = $_POST['last_id'];
} else {
    @$id_pl = $_POST['id_pl'];
}
if (empty($_POST['todos'])) {
    $sit = " WHERE `at_pl` IN (1,2)";
}
if (empty($_POST['novo'])) {
    $modal = 1;
}
$si = ['Inativo', 'Ativo', 'Previsto'];
$sql = "SELECT * FROM `ge_periodo_letivo` "
        . @$sit;
$query = $model->db->query($sql);
$periodos = $query->fetchAll();
?>
<style>
    .th{
        background-color: black;
        color: white;
    }
</style>
<div class="fieldBody">
    <div class="row">
        <div class="col-md-6 text-center">
            <input type="submit" onclick=" $('#myModal').modal('show');" value="Novo Peŕiodo Letivo" />
        </div>
        <div class="col-md-6 text-center">
            <form method="POST">
                <input type="submit" name="todos" value="Visualizar Períodos Inativos" />
            </form>
        </div>
    </div>
    <br /><br />
    <?php
    tool::modalInicio('width: 95%', @$modal);
    ?>
    <br /><br />
    <div class="row">
        <div class="col-md-11">
            <form method="POST">
                <div class="row">
                    <div class="col-md-9">
                        <?php formulario::input('1[n_pl]', 'Descrição') ?>
                    </div>
                    <div class="col-md-3">
                        <?php formulario::select('1[at_pl]', $si, 'Situação') ?>
                    </div>
                </div>
                <br />
                <table class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th colspan="3" style="text-align: center">
                                Ciclos/Período
                            </th>
                        </tr>
                        <tr class="th">
                            <th>
                                Segmento
                            </th>
                            <th>
                                Curso
                            </th>
                            <th>
                                Ciclos
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cursos = sql::get(['ge_cursos', 'ge_tp_ensino'], 'id_curso, n_curso, n_tp_ens', ['ativo' => 1, '>' => 'n_tp_ens, n_curso']);

                        foreach ($cursos as $v) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $v['n_tp_ens'] ?>
                                </td>
                                <td>
                                    <?php echo $v['n_curso'] ?>
                                </td>
                                <td>
                                    <?php
                                    $ciclos = curso::ciclos($v['id_curso']);
                                    ?>
                                    <?php
                                    foreach ($ciclos as $c) {
                                        $checked = null;
                                        $class = NULL;
                                        $pc = sql::get('ge_periodo_ciclo', '*', ['fk_id_pl' => $id_pl]);
                                        
                                        foreach ($pc as $a) {
                                            if (($a['fk_id_ciclo'] == $c['id_ciclo'])) {
                                                $checked = "checked";
                                                $class = "alert-info";
                                            }
                                        }
                                        ?>
                                        <div class="col-lg-3">
                                            <div class="input-group" style="width: 100%">
                                                <label  style="width: 100%">
                                                    <span class="input-group-addon <?php echo $class ?>" style="text-align: left; width: 20px">
                                                        <input <?php echo @$checked ?> type="checkbox" aria-label="..." name="2[<?php echo $c['id_ciclo'] ?>]" value="<?php echo $c['id_ciclo'] ?>">
                                                    </span>
                                                    <span class="input-group-addon <?php echo $class ?>" style="text-align: left">
                                                        <?php echo $c['n_ciclo'] ?>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <br />
                <div class="row">
                    <div class="col-md-12 text-center">
                        <?php echo DB::hiddenKey('periodo_letivo') ?>
                        <input type="hidden" name="1[id_pl]" value="<?php echo @$id_pl ?>" />
                        <input class="btn btn-success" type="submit" value="Salvar" />
                    </div>
                </div>
            </form>       
        </div>
        <div class="col-md-1 text-center">
            <form method="POST">
                <input name="novo" class="btn btn-warning" type="submit" value="Limpar" />
            </form>
        </div>
    </div>
    <br /><br />
    <?php
    tool::modalFim();

    $sqlkey = DB::sqlKey('ge_periodo_letivo', 'delete');
    foreach ($periodos as $k => $v) {
        $v['novo'] = 1;
        $periodos[$k]['edit'] = formulario::submit('Editar', NULL, $v);
        $periodos[$k]['del'] = formulario::submit('Apagar', $sqlkey, ['1[id_pl]' => $v['id_pl']]);
        $periodos[$k]['sit'] = $si[$v['at_pl']];
    }
    $form['array'] = $periodos;
    $form['fields'] = [
        'ID' => 'id_pl',
        'Descrição' => 'n_pl',
        'Situação' => 'sit',
        '||2' => 'del',
        '||1' => 'edit'
    ];

    tool::relatSimples($form);
    ?>
</div>