<?php
if (!empty($_POST['id_sel'])) {
    $modal = NULL;
} else {
    $modal = 1;
}
?>
<div class="fieldBody">
    <div class="fieldTop">
        Processos Seletivos
    </div>
    <br /><br />
    <input class="btn btn-info" type="submit" onclick=" $('#myModal').modal('show');" value="Novo Processos Seletivo" />
    <br /><br />
    <?php
    tool::modalInicio('width: 95%', $modal);
    ?>
    <form method="POST">
        <div class="row">
            <div class="col-md-8">
                <?php formulario::input('1[n_sel]', 'Processos Seletivo: ') ?>
            </div>
            <div class="col-md-2">
                <?php formulario::select('1[ativo]', ['não', 'Sim'], 'Ativo') ?>
            </div>
            <div class="col-md-2">
                <?php echo DB::hiddenKey('dtpg_seletivas', 'replace') ?>
                <input class="btn btn-success" type="submit" value="Salvar" />
            </div>
        </div>
    </form>
    <br /><br />
    <?php
    tool::modalFim();
    $sel = sql::get('dtpg_seletivas', '*', ['>' => 'ativo, n_sel']);
    $sqlkey = DB::sqlKey('dtpg_seletivas', 'delete');
    foreach ($sel as $k => $v) {
        $sel[$k]['ativo'] = tool::simnao($v['ativo']);
        $sel[$k]['ac'] = formulario::submit('Editar', NULL, $v);
        $sel[$k]['del'] = formulario::submit('Apagar', $sqlkey, ['1[id_sel]' => $v['id_sel']]);
    }
    $form['array'] = $sel;
    $form['fields'] = [
        'ID' => 'id_sel',
        'Seletiva' => 'n_sel',
        'Ativo' => 'ativo',
        '||2' => 'del',
        '||1' => 'ac'
    ];

    tool::relatSimples($form);
    ?>
</div>