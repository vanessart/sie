<?php
$id_cate = @$_REQUEST['id_cate'];
?>
<div class="fieldBody">
    <div class="fieldTop">
        Avaliação e inscrição - Giz de Ouro <?php echo date("Y") ?>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-8">
            <?php echo formulario::selectDB('giz_categoria', 'id_cate', 'Categoria', NULL, NULL, 1) ?>
        </div>
        <?php
        if (!empty($id_cate)) {
            ?>
            <form target="_blank" action="<?php echo HOME_URI ?>/giz/todos" method="POST">
                <input type="hidden" name="id_cate" value="<?php echo $id_cate ?>" />
                <input type="submit" value="Todos" />
            </form>
            <?php
        }
        ?>
    </div>
    <br /><br />
    <?php
    if (!empty($id_cate)) {
        $giz_notas = sql::get('giz_notas');
        $giz_result = sql::get('giz_result');
        $avaliados = array_column($giz_notas, 'id_prof');
        $result_ = array_column($giz_result, 'id_pessoa');
        $prof = $model->inscricoes($id_cate, 'fk_id_cate', 1);
        foreach ($prof as $k => $v) {
            $v['id_cate'] = $id_cate;
            if ($v['gestor'] == 1) {
                $clas = 'btn btn-warning';
                $prof[$k]['sit'] = 'Aguardando Efetivação';
            } elseif ($v['gestor'] == 2) {
                $clas = 'btn btn-success';
                $prof[$k]['sit'] = 'Inscrição Efetivada';
            } elseif ($v['gestor'] == '') {
                $clas = 'btn btn-danger';
                $prof[$k]['sit'] = 'Aguardando Liberação pelo Professor';
            }
            if (in_array($v['id_pessoa'], $result_)) {
                $prof[$k]['devol'] = formulario::submit('Devolutiva', NULL, $v, HOME_URI . '/giz/devolutiva', 1);
            } else {
                $prof[$k]['devol'] = '<button class="btn btn-default">Devolutiva</button>';
            }
            if (in_array($v['id_prof'], $avaliados)) {
                $prof[$k]['avaliado'] = '<span style="font-weight: bold; font-size: 20px">SIM</span>';
            } else {
                $prof[$k]['avaliado'] = '<span style="font-weight: bold; font-size: 20px">Não</span>';
            }
            $prof[$k]['ac'] = formulario::submit('Acessar', NULL, $v, HOME_URI . '/giz/bancap', 1, NULL, $clas);
            $prof[$k]['av'] = formulario::submit('Avaliar', NULL, $v, HOME_URI . '/giz/aval', NULL, NULL, $clas);
        }
        $form['array'] = $prof;

        $form['fields'] = [
            'Professor(a)' => 'n_pessoa',
            'Categoria' => 'n_cate',
            //'Projeto' => 'titulo',
            'Situação' => 'sit',
            'Avaliado' => 'avaliado',
            '||3' => 'devol',
            '||1' => 'ac',
            '||2' => 'av',
        ];

        tool::relatSimples($form);
    }
    ?>
</div>