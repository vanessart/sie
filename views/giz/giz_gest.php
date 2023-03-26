<?php
$id_inst = tool::id_inst(@$_POST['id_inst']);
$prof = $model->inscricoes($id_inst, 'fk_id_inst');
?>
<div class="fieldBody">
    <div class="fieldTop">
        Avaliação e inscrição - Giz de Ouro <?php echo date("Y") ?>
    </div>
    <br /><br />
    <?php
    if (!empty($prof)) {
        foreach ($prof as $k => $v) {
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
            $prof[$k]['ac'] = formulario::submit('Acessar', NULL, $v, HOME_URI . '/giz/gest', NULL,NULL, $clas);
        }
        $form['array'] = $prof;

        $form['fields'] = [
            'Professor(a)' => 'n_pessoa',
            'Categoria' => 'n_cate',
            'Projeto' => 'titulo',
            'Situação' => 'sit',
            '||' => 'ac'
        ];

        tool::relatSimples($form);
    } else {
        ?>
        <div class="alert alert-warning" style="text-align: center; font-size: 18px">
            Esta escola não tem inscritos
        </div>
        <?php
    }
    ?>
</div>