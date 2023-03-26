    <br /><br />
    <?php
    $sql = " select * from coord_competencia cc "
            . " join coord_grupo cg on cg.id_gr = cc. fk_id_gr "
            . " join ge_disciplinas gd on gd.id_disc = cg.fk_id_disc "
            . " where id_gr = " . $_REQUEST['id_gr']
            . " order by n_comp";
    $query = $model->db->query($sql);
    $array = $query->fetchAll();
    $sqlkey = DB::sqlKey('coord_competencia', 'delete');
    foreach ($array as $k => $v) {
        $v['id_gr']=@$_REQUEST['id_gr'];
        $v['id_tp_ens']= @$_REQUEST['id_tp_ens'];
        $v['id_curso']= @$_REQUEST['id_curso'];
        $v['modal']=1;
        $array[$k]['ac']= formulario::submit('Editar', NULL, $v);
        $array[$k]['del']= formulario::submit('Apagar', $sqlkey, ['1[id_comp]'=> $v['id_comp'],'id_gr'=>@$_REQUEST['id_gr'],'id_curso'=>@$_REQUEST['id_curso']]);
    }
    $form['array'] = $array;
    $form['fields'] = [
        'Competência' => 'n_comp',
        'Código' => 'cod_comp',
        'Grupo' => 'n_gr',
        'Disciplina' => 'n_disc',
        '||2'=>'del',
        '||1'=>'ac'
    ];
    tool::relatSimples($form);
   
