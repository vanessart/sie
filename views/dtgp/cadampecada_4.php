
<div class="fieldBody">
    <div class="fieldTop">
        Cadastro TEA
    </div>
    <br /><br />
    <div style="text-align: center; width: 400px; margin: 0 auto">
        <?php formulario::select('fk_id_inst', escolas::idInst(), 'Escola', NULL, 1, ['id_cad' => @$id_cad, 'id_inscr' => @$_POST['id_inscr'], 'fk_id_sel' => @$_POST['fk_id_sel'], 'class' => @$_POST['class'], 'activeNav'=>4]) ?>
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
                     <?php echo DB::hiddenKey('dtgp_tea', 'replace') ?>
                     <?php echo formulario::hidden(['id_cad' => @$id_cad, 'id_inscr' => @$_POST['id_inscr'], 'fk_id_sel' => @$_POST['fk_id_sel'], 'class' => @$_POST['class'], 'activeNav'=>4]) ?>
                     <input type="hidden" name="1[fk_id_cad]" value="<?php echo @$dados['id_cad'] ?>" />
                     <input type="hidden" name="1[fk_id_inst]" value="<?php echo $_POST['fk_id_inst'] ?>" />
                     <input class="btn btn-success" type="submit" value="Incluir" />
                 </div>
            </div>
        </form>
        <?php
    }
    $sql = "select n_pessoa, id_pessoa, n_inst, n_turma, id_tea from instancia i "
            . "join ge_turmas t on t.fk_id_inst = i.id_inst "
            . " join ge_turma_aluno ta on ta.fk_id_turma = t.id_turma "
            . "join pessoa p on p.id_pessoa = ta.fk_id_pessoa "
            . "join dtgp_tea te on te.fk_id_pessoa = p.id_pessoa "
            . "where fk_id_cad = ".$dados['id_cad'];
    $query = $model->db->query($sql);
            $array = $query->fetchAll();
            
           $sqlkey = DB::sqlKey('dtgp_tea', 'delete');
           foreach ($array as $k => $v){
               $array[$k]['del']= formulario::submit('Apagar', $sqlkey, ['1[id_tea]'=>$v['id_tea'],'id_cad' => @$id_cad, 'id_inscr' => @$_POST['id_inscr'], 'fk_id_sel' => @$_POST['fk_id_sel'], 'class' => @$_POST['class'], 'activeNav'=>4]);
           }
           $form['array']= $array;
           $form['fields']= [
               'Aluno'=>'n_pessoa',
               'Escola' => 'n_inst',
               'Classe' => 'n_turma',
               '||' => 'del'
               ];
               tool::relatSimples($form);
    ?>

</div>
