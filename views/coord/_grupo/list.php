    <br /><br />
    <?php
    $sql = " select * from coord_grupo cg "
            . " join ge_disciplinas gd on gd.id_disc = cg.fk_id_disc "
            . " where fk_id_curso = " . $_REQUEST['id_curso']
            . " order by n_gr";
    $query = $model->db->query($sql);
    $array = $query->fetchAll();
    $sqlkey = DB::sqlKey('coord_grupo', 'delete');
    foreach ($array as $k => $v) {
        $v['id_tp_ens']= @$_REQUEST['id_tp_ens'];
        $v['id_curso']= @$_REQUEST['id_curso'];
        $v['modal']=1;
        $array[$k]['ac']= formulario::submit('Editar', NULL, $v);
        $array[$k]['del']= formulario::submit('Apagar', $sqlkey, ['1[id_gr]'=> $v['id_gr'],'id_tp_ens'=>@$_REQUEST['id_tp_ens'],'id_curso'=>@$_REQUEST['id_curso']]);
    }
    $form['array'] = $array;
    $form['fields'] = [
        'Grupo' => 'n_gr',
        'Código' => 'cod_gr',
        'Disciplina' => 'n_disc',
        '||2'=>'del',
        '||1'=>'ac'
    ];
    tool::relatSimples($form);
