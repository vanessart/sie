
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
                <?php // echo formulario::selectDB('dttie_list_suporte', 'tipo_sup', 'Suporte em:', @$campos['tipo_sup']); ?>
            </div>
            <div class="col-lg-2">
                <?php
                $options = [
                    'Aberto' => 'Aberto',
                    'Finalizado' => 'Finalizado',
                    'Em Espera' => 'Em Espera',
                    'Em Andamento' => 'Em Andamento',
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
        if (!empty($_POST['resp_sup'])) {
            $resp_sup = " and resp_sup = " . $_POST['resp_sup'];
        }
        if (!empty($_POST['n_pessoa'])) {
            $n_pessoa = " and n_pessoa like '%" . $_POST['n_pessoa'] . "%' ";
        }
        if (!empty($_POST['local_sup'])) {
            $local_sup = " and  local_sup  '%" . $_POST['local_sup'] . "%'";
        }
        if (!empty($_POST['tipo_sup'])) {
            $tipo_sup = " and tipo_sup = " . $_POST['tipo_sup'];
        }
        if (empty($_POST['id_sup'])) {
            if (!empty($_POST['status_sup'])) {
                $status_sup = " and status_sup like '%" . $_POST['status_sup'] . "%'";
            } else {
                $status_sup = " and `status_sup` in ('Aberto','Em Espera','Em Execução','Em Produção', 'Em Andamento')";
            }
        }
        if (!empty($_POST['priori_sup'])) {
            $priori_sup = " and priori_sup like '%" . $_POST['priori_sup'] . "%'";
        }

        if (tool::id_nivel() == 47) {
            $and = " and tipo_sup IN (5) ";
        }

        $ordem = ' dt_sup desc ';
    }

     $sql = "SELECT * FROM `dttie_suporte_trab` t"
    . " left join dttie_resp_tec r on r.id_resp = t.resp_sup "
    . "WHERE 1 "
    . "  " . @$resp_sup . ' '
    . "  " . @$id_sup . ' '
    . "  " . @$n_pessoa . ' '
    . "  " . @$local_sup . ' '
    . "  " . @$tipo_sup . ' '
    . "  " . @$status_sup . ' '
    . "  " . @$priori_sup . ' '
    . @$and
    . (!empty($ordem) ? "order by $ordem limit 500 " : '');
    ?>
    <?php
    $query = $model->db->query($sql);
    $array = $query->fetchAll();
    if (!empty($array)) {
        $ser = sql::get('dttie_list_suporte', 'id_list, n_list');
        foreach ($ser as $v) {
            $servico[$v['id_list']] = $v['n_list'];
        }
        if (tool::id_pessoa() == 1) {
            $novaPagina = 1;
        }
        foreach ($array as $k => $v) {
            @$array[$k]['descr_sup'] = substr($v['descr_sup'], 0, 50);
            if ($v['ultimo_lado'] == "Suporte") {
                $edit = 'Ver';
                $class = 'btn btn-warning';
            } else {
                $edit = 'Responder';
                $class = 'btn btn-primary';
            }
            @$array[$k]['diag'] = NULL;
            $sql = "SELECT * FROM `dttie_suport_diag` "
                    . " WHERE `fk_id_sup` = " . $v['id_sup']
                    . " ORDER BY `data` ASC, fk_id_sup ASC ";
            $query = $model->db->query($sql);
            $diag1 = $query->fetchAll();

            foreach ($diag1 as $dd) {
                @$array[$k]['diag'] .= $dd['lado'] . ': ' . $dd['descr'] . '<br /><br />';
            }

            @$array[$k]['data'] = str_replace('-', '', $v['dt_sup']) < date("Ymd", mktime(0, 0, 0, date("m"), date("d") - 15, date("Y"))) ? '<div style="color: red"> ' . data::converte($v['dt_sup']) . '</div>' : data::converte($v['dt_sup']);
            @$array[$k]['tipo_sup'] = $servico[$v['tipo_sup']];
            @$array[$k]['ac'] = formulario::submit($edit, NULL, ['id_sup' => $v['id_sup']], HOME_URI . '/dttie/suporte', @$novaPagina, NULL, $class);
        }

        $form['array'] = $array;
        $form['fields'] = [
            'Protocolo' => 'id_sup',
            'Data' => 'data',
            'Para dia' => 'dt_prev_sup',
            'Usuário' => 'n_pessoa',
            'Escola/Depto' => 'local_sup',
            'Serviço' => 'tipo_sup',
            'Assunto' => 'descr_sup',
            'Dialogo' => 'diag',
            'Técnico' => 'n_resp',
            'Status' => 'status_sup',
            '||1' => 'ac'
        ];
        tool::relatSimples($form);
    }
    ?>
</div>
