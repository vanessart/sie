<br /><br /><br /><br />
<div class="row">
    <div class="col-sm-6 text-center">
        <?php formulario::select('fk_id_inst', escolas::idInst(), 'Escola', NULL, 1, ['id_cad' => @$id_cad, 'id_inscr' => @$_POST['id_inscr'], 'fk_id_sel' => @$_POST['fk_id_sel'], 'class' => @$_POST['class'], 'activeNav' => 3]) ?>
    </div>
    <div class="col-sm-6 text-center">
        Cadastro TEA
    </div>
</div>
<br /><br />
<?php
if (!empty($_POST['fk_id_inst'])) {
    alunos::AlunosAutocomplete($_POST['fk_id_inst']);
    ?>
    <form method="POST">
        <div class="row">
            <div class="col-md-6">
                <?php formulario::input(NULL, 'Aluno', NULL, NULL, ' id="busca" onkeypress="complete()" ') ?>
            </div>
            <div class="col-md-3">
                <input type="text" name="1[fk_id_pessoa]" id="rse" value="" readonly />
            </div>
            <div class="col-md-3">
                <?php echo DB::hiddenKey('cadam_tea', 'replace') ?>
                <?php echo formulario::hidden(['id_cad' => @$id_cad, 'id_inscr' => @$_POST['id_inscr'], 'fk_id_sel' => @$_POST['fk_id_sel'], 'class' => @$_POST['class'], 'activeNav' => 3]) ?>
                <input type="hidden" name="1[fk_id_cad]" value="<?php echo @$dados['id_cad'] ?>" />
                <input type="hidden" name="1[fk_id_inst]" value="<?php echo $_POST['fk_id_inst'] ?>" />
                <input class="btn btn-success" type="submit" value="Incluir" />
            </div>
        </div>
    </form>
    <?php
}

$array = $model->alunosTea($dados['id_cad']);

$sqlkey = DB::sqlKey('cadam_tea', 'delete');
foreach ($array as $k => $v) {
    $array[$k]['del'] = formulario::submit('Apagar', $sqlkey, ['1[id_tea]' => $v['id_tea'], 'id_cad' => @$id_cad, 'id_inscr' => @$_POST['id_inscr'], 'fk_id_sel' => @$_POST['fk_id_sel'], 'class' => @$_POST['class'], 'activeNav' => 3]);
}
$form['array'] = $array;
$form['fields'] = [
    'Aluno' => 'n_pessoa',
    'Escola' => 'n_inst',
    'Classe' => 'n_turma',
    '||' => 'del'
];
tool::relatSimples($form);

