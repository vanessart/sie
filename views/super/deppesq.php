<div class="fieldBody">
    <form method="POST">
        <div class="row">
            <div class="col-lg-2">
                <?php echo formulario::input('id_sup', 'Protocolo') ?>
            </div>
            <div class="col-lg-3">
                <?php echo formulario::input('n_pessoa', 'Solicitante') ?> 
            </div>
            <div class="col-lg-3">
                <?php echo formulario::selectDB('super_list_suporte', 'tipo_sup', 'Suporte em:', @$campos['tipo_sup']); ?>
            </div>
            <div class="col-lg-2">
                <?php
                $options = [
                    'Aberto' => 'Aberto',
                    'Em Espera' => 'Em Espera',
                    'Em Execução' => 'Em Execução',
                    'Em Produção' => 'Em Produção',
                    'Finalizado' => 'Finalizado',
                    'Retirado' => 'Retirado',
                    'Recusado' => 'Recusado',
                    'Cancelado' => 'Cancelado'
                ];
                echo formulario::select('status_sup', $options, 'Status');
                ?>
            </div>
            <div class="col-lg-2">
                <input class="btn btn-success" type="submit" name="buscar" value="Buscar" />
            </div>
        </div>

    </form>
    <br /><br />
    <?php
    if (!empty($_POST['id_sup'])) {
        $id_sup = " and id_sup = " . $_POST['id_sup'];
    } else {
        if (!empty($_POST['n_pessoa'])) {
            $n_pessoa = " and n_pessoa like '%" . $_POST['n_pessoa'] . "%' ";
        }
        if (!empty($_POST['tipo_sup'])) {
            $tipo_sup = " and tipo_sup = " . $_POST['tipo_sup'];
        }
        if (empty($_POST['id_sup'])) {
            if (!empty($_POST['status_sup'])) {
                $status_sup = " and status_sup like '%" . $_POST['status_sup'] . "%'";
            } else {
                $status_sup = " and `status_sup` in ('Aberto','Em Espera','Em Execução','Em Produção')";
            }
        }
    }

    $sql = "SELECT * FROM `super_suporte_trab` "
            . "WHERE 1 "
            . "  " . @$id_sup . ' '
            . "  " . @$n_pessoa . ' '
            . "  " . @$tipo_sup . ' '
            . "  " . @$status_sup . ' '
            . " and fk_id_pessoa = ".tool::id_pessoa()
            . " order by dt_sup limit 0, 500 ";


    $query = $model->db->query($sql);
    $array = $query->fetchAll();
    if (!empty($array)) {
        $ser = sql::get('super_list_sup_dep', 'id_list, n_list');
        foreach ($ser as $v) {
            $servico[$v['id_list']] = $v['n_list'];
        }

        foreach ($array as $k => $v) {
            @$array[$k]['data'] = str_replace('-', '', $v['dt_sup']) < date("Ymd", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y"))) ? '<div style="color: red"> ' . data::converte($v['dt_sup']) . '</div>' : data::converte($v['dt_sup']);
            @$array[$k]['tipo_sup'] = $servico[$v['tipo_sup']];
            @$array[$k]['ac'] = formulario::submit('Acessar', NULL, ['id_sup' => $v['id_sup']], HOME_URI . '/super/depto');
        }
        $form['array'] = $array;
        $form['fields'] = [
            'Protocolo' => 'id_sup',
            'Data' => 'data',
            'Nome' => 'n_pessoa',
            'Escola/Depto' => 'local_sup',
            'Serviço' => 'tipo_sup',
            'Status' => 'status_sup',
            '||1' => 'ac'
        ];
        tool::relatSimples($form);
    }
    ?>
</div>
