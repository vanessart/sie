<?php
$sql = "select * from ps.sel_tipo_inscr";
$query = $model->db->query($sql);
$array = $query->fetchAll();
foreach ($array as $v) {
    $tipoSel[$v['id_ti']] = $v['n_ti'];
}
$sql = "SELECT * FROM ps.`sel_fase` ";
$query = $model->db->query($sql);
$f = $query->fetchAll();
$fases = tool::idName($f);
?>
<div class="fieldBody">
    <div class="fieldTop">
        Cadastro de Seletiva/Concurso
    </div>
    <div>
        <input type="submit" onclick=" $('#myModal').modal('show');" value="Novo Cadastro" />
    </div>
    <br />
    <?php
    tool::modalInicio('width: 95%', empty($_POST['modal']) ? 1 : 0);
    ?>
    <form method="POST">
        <div class="row">
            <br /><br />
            <div class="col-md-12">
                <?php formulario::input('1[n_sel]', 'Título da Seletiva') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-12">
                <textarea name="1[compl_sel]" style="width: 100%; height: 60px;" placeholder="Complemento"><?php echo @$_POST['compl_sel'] ?></textarea>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-4">
                <?php formulario::input('1[dt_ini_inscr]', 'Início das Inscrições', NULL, data::converteBr(@$_POST['dt_ini_inscr']), 'id="data1" ' . formulario::dataConf(1)) ?>
            </div>
            <div class="col-md-4">
                <?php formulario::input('1[dt_fim_inscr]', 'Fim das Inscrições', NULL, data::converteBr(@$_POST['dt_fim_inscr']), 'id="data2" ' . formulario::dataConf(2)) ?>
            </div>
            <div class="col-md-4">
                <?php formulario::input('1[dt_prova]', 'Dia da Prova', NULL, data::converteBr(@$_POST['dt_prova']), formulario::dataConf(3)) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-3">
                <?php
                formulario::select('1[fk_id_ti]', $tipoSel, 'Tipo de Inscrição', @$_POST['fk_id_ti']);
                ?>
            </div>
            <div class="col-md-3">
                <?php formulario::select('1[at_sel]', ['não', 'Sim'], 'Ativo', @$_POST['at_sel']) ?>
            </div>
            <div class="col-md-3">
                <?php formulario::select('1[fk_id_fase]', $fases, 'Fase', @$_POST['fk_id_fase']) ?>
            </div>
            <div class="col-md-3">
                <?php echo formulario::input('1[taxa]', 'Taxa', NULL, @$_POST['taxa']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-12">
                Obs. Protocolo
                <pre>
                <textarea name="1[protocolo]" style="width: 100%; height: 100px"><?php echo @$_POST['protocolo'] ?></textarea>
                </pre>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-6 text-center">
                <input value="Limpar" type="button" class="btn btn-warning"  onclick="document.getElementById('limpa').submit()"/>                    
            </div>
            <div class="col-md-6 text-center">
                <?php echo DB::hiddenKey('sel_seletiva', 'replace') ?>
                <input type="hidden" name="1[id_sel]" value="<?php echo @$_POST['id_sel'] ?>" />
                <input class="btn btn-success" type="submit" value="Salvar" />
            </div>
        </div>
    </form>
    <form id="limpa" method="POST">
        <input type="hidden" name="modal" value="1" />
    </form>
    <?php
    tool::modalFim();
    $sql = " select * from ps.sel_seletiva s "
            . " join ps.sel_fase f on f.id_fase = s.fk_id_fase "
            . " join ps.sel_tipo_inscr i on i.id_ti = s.fk_id_ti ";
    $query = $model->db->query($sql);
    $inscri = $query->fetchAll();
    $sqlkey = DB::sqlKey('sel_seletiva', 'delete');
    foreach ($inscri as $k => $v) {
        $v['modal'] = 1;
        $inscri[$k]['del'] = formulario::submit('Apagar', $sqlkey, ['1[id_sel]' => $v['id_sel']]);
        $inscri[$k]['edit'] = formulario::submit('Editar', NULL, $v);
        $inscri[$k]['at_sel'] = tool::simnao($v['at_sel']);
    }
    $form['array'] = $inscri;
    $form['fields'] = [
        'ID' => 'id_sel',
        'Nome' => 'n_sel',
        'Tipo Inscrições' => 'n_ti',
        'Fase' => 'n_fase',
        'Início' => 'dt_ini_inscr',
        'Fim' => 'dt_fim_inscr',
        'Prova' => 'dt_prova',
        'Ativo' => 'at_sel',
        //'||1' => 'del',
        '||2' => 'edit'
    ];
    tool::relatSimples($form);
    ?>
</div>