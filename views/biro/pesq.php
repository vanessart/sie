<div class="fieldBody">
    <form method="POST">
        <div class="fieldWhite">
            <div class="row">
                <div class="col-sm-4">
                    <?php formulario::input('id_trab', 'Protocolo') ?>
                </div>
                <div class="col-sm-4">
                    <?php formulario::selectDB('biro_list', 'tipo', 'Serviço', @$campos['tipo']); ?>
                </div>
                <div class="col-sm-4">
                    <?php formulario::select('item', ['Não', 'Sim'], 'Vinculado à Contrato', @$campos['item']); ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-sm-4">
                    <?php formulario::input('n_pessoa', 'Nome') ?>
                </div>
                <div class="col-sm-4">
                    <?php formulario::input('local', 'Escola/Depto') ?>
                </div>
                <div class="col-sm-3">
                    <?php
                    unset($options);
                    $options = [
                        'Aberto' => 'Aberto',
                        'Em Espera' => 'Em Espera',
                        'Em Execução' => 'Em Execução',
                        'Concluído' => 'Concluído',
                        'Recusado' => 'Recusado',
                        'Cancelado' => 'Cancelado',
                    ];
                    formulario::select('1[status_biro]', $options, 'Status:', @$campos['status_biro']);
                    ?>
                </div>
                <div class="col-lg-1">
                    <input class="btn btn-success" type="submit" name="buscar" value="Buscar" />

                </div>
            </div>
        </div>
    </form>
    <br /><br />
    <?php
    if (empty($_POST['id_trab'])) {
        if (!empty($_POST['n_pessoa'])) {
            $n_pessoa = " and n_pessoa like '%" . $_POST['n_pessoa'] . "%' ";
        }
        if (!empty($_POST['local'])) {
            $local = " and  local  '%" . $_POST['local'] . "%'";
        }
        if (!empty($_POST['tipo'])) {
            $tipo = " and tipo = " . $_POST['tipo'];
        }
        if (!empty($_POST['status_biro'])) {
            $status_biro = " and status_biro like '%" . $_POST['status_biro'] . "%'";
        } else {
            $status_biro = NULL;
        }
        if (!empty($_POST['prioridade'])) {
            $prioridade = " and prioridade like '%" . $_POST['prioridade'] . "%'";
        }
        if (@$_POST['item'] == 1) {
            $item = " and fk_id_item is not NULL ";
        } else {
            $item = " and fk_id_item is NULL ";
        }
    } else {
        $id_trab = " and id_trab = " . $_POST['id_trab'];
    }
    $sql = "SELECT * FROM biro_trab "
            . "WHERE 1 "
            . " " . @$id_trab . ' '
            . "  " . @$n_pessoa . ' '
            . "  " . @$local . ' '
            . "  " . @$tipo . ' '
            . "  " . @$status_biro . ' '
            . "  " . @$prioridade . ' '
            . "  " . @$item . ' '
            . "order by dt_prev desc limit 0, 500 ";


    $query = $model->db->query($sql);
    $array = $query->fetchAll();
    if (!empty($array)) {
        $ser = sql::get('biro_list', 'id_list, n_list');
        foreach ($ser as $v) {
            $servico[$v['id_list']] = $v['n_list'];
        }

        foreach ($array as $k => $v) {
            @$array[$k]['contrato'] = empty($v['fk_id_item'])?'Não':'Sim';
            @$array[$k]['dt_prev'] = $v['dt_prev'] < date("Y-m-d") ? '<div style="color: red"> ' . data::converte($v['dt_prev']) . '</div>' : data::converte($v['dt_prev']);
            @$array[$k]['tipo'] = $servico[$v['tipo']];
            @$array[$k]['ac'] = formulario::submit('Acessar', NULL, ['id_trab' => $v['id_trab']], HOME_URI . '/biro/trab');
        }
        $form['array'] = $array;
        $form['fields'] = [
            'Protocolo' => 'id_trab',
            'Tem Contrato' => 'contrato',
            'Previsão' => 'dt_prev',
            'Nome' => 'n_pessoa',
            'Escola/Depto' => 'local',
            'Serviço' => 'tipo',
            'Status' => 'status_biro',
            '||1' => 'ac'
        ];
        tool::relatSimples($form);
    }
    ?>
</div>
