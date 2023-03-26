<?php
if (empty($_POST['ativo'])) {
    $ativo = 1;
} else {
    $ativo = @$_POST['ativo'];
}
$sql = "select "
        . " id_curso, n_curso, un_letiva, qt_letiva,atual_letiva, n_tp_ens, ativo "
        . " FROM ge_cursos c "
        . " join ge_tp_ensino e on e. id_tp_ens = c.fk_id_tp_ens "
        . " where ativo = $ativo order by n_tp_ens, n_curso ";
$query = $model->db->query($sql);
$cursos = $query->fetchAll();
foreach ($cursos as $k => $v) {
    $v['modal'] = 1;
    $cursos[$k]['qt'] = $v['qt_letiva'] . ' ' . $v['un_letiva'];
    $cursos[$k]['atual'] = $v['atual_letiva'] . 'º ' . $v['un_letiva'];
    $cursos[$k]['ativo'] = curso::periodoLetivoSituacao($v['ativo']);
    $cursos[$k]['ac'] = formulario::submit('Acessar', NULL, $v);
}
?>
<div class="fieldBody">
    <div class="fieldTop">
        Gerenciamento de Cursos
    </div>
    <br /><br />
    <?php
    $form['array'] = $cursos;
    $form['fields'] = [
        'Segmento' => 'n_tp_ens',
        'Curso' => 'n_curso',
        'Situação' => 'ativo',
        'Qt. Letiva' => 'qt',
        'Letiva Atual' => 'atual',
        '||' => 'ac'
    ];
    tool::relatSimples($form);
    ?>
</div>
<?php
if (empty($_POST['modal'])) {
    $modal = 1;
}
tool::modalInicio('width: 95%', $modal);
?>
<div style="height: 80vh">
    <br /><br />
    <?php
    $curso[] = $_POST;
    foreach ($curso as $k => $v) {
        $v['modal'] = 1;
        $curso[$k]['qt'] = $v['qt_letiva'] . ' ' . $v['un_letiva'];
        $curso[$k]['atual'] = $v['atual_letiva'] . 'º ' . $v['un_letiva'];
        $curso[$k]['ativo'] = curso::periodoLetivoSituacao($v['ativo']);
    }
    $form['array'] = $curso;
    $form['fields'] = [
        'Segmento' => 'n_tp_ens',
        'Curso' => 'n_curso',
        'Situação' => 'ativo',
        'Qt. Letiva' => 'qt',
        'Letiva Atual' => 'atual',
        '||' => 'ac'
    ];
    tool::relatSimples($form);
    ?>
    <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <?php formulario::selectNum('1[atual_letiva]', @$_POST['qt_letiva'], @$_POST['un_letiva'] . ' Atual'); ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-6 text-center">
                <?php echo DB::hiddenKey('ge_cursos', 'replace') ?>
                <input type="hidden" name="1[id_curso]" value="<?php echo @$_POST['id_curso'] ?>" />
                <input class="btn btn-success" type="submit" value="Salvar" />
            </div>
        </div>
    </form>
</div>
<?php
tool::modalFim();
