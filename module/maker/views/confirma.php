<?php
if (!defined('ABSPATH'))
    exit;
if (toolErp::id_nilvel() == 8) {
    $id_inst = toolErp::id_inst();
} else {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    $escolas = $model->escolasMaker();
}
$confirma = [
    'MC' => 'Situação de Matrícula indefinida',
    'MRC' => 'Matricula Confirmada - O aluno continuará no curso',
    'MNC' => 'Matricula NÃO Confirmada - O aluno NÃO continuará no curso'
];
$setup = sql::get(['maker_setup', 'ge_periodo_letivo'], 'n_pl, id_pl', null, 'fetch');
$id_pl = $setup['id_pl'];
if ($id_inst) {
    $maker = $model->escolaPolo($id_inst);
    $id_polo = $maker['id_polo'];
    $alunos = $model->alunosEscola($id_inst, $id_pl);
    $falta2 = $model->faltou2Aulas($id_polo, $id_pl, $id_inst);
    if ($falta2) {
        foreach ($falta2 as $k => $v) {
            if ($v['confirma'] == 'MC') {
                $clas = 'warning';
            } elseif ($v['confirma'] == 'MNC') {
                $clas = 'danger';
            } elseif ($v['confirma'] == 'MRC') {
                $clas = 'success';
            }
            $sel = '<select class="btn btn-' . $clas . '" onchange="this.options[this.selectedIndex].onclick()">';
            foreach ($confirma as $kc => $c) {
                if ($kc == $v['confirma']) {
                    $selected = ' selected ';
                } else {
                    $selected = null;
                }
                $sel .= '<option ' . $selected . ' onclick="conf(\'' . $kc . '\', \'' . $v['id_ta'] . '\')" >' . $c . '</option>';
            }
            $sel .= '</select>';
            $falta2[$k]['confirma'] = $sel;
        }
        $form['array'] = $falta2;
        $form['fields'] = [
            'RSE' => 'id_pessoa',
            'Nome' => 'n_pessoa',
            'Turma Maker' => 'n_turma',
            'Turma de Origem' => 'turmaOrigem',
            'Confirmação' => 'confirma'
        ];
    }
}
?>

<div class="body">
    <div class="fieldTop">
        Confirmação de matrícula
        <br /><br />
        <div class="alert alert-danger" style="text-align: center; font-weight: bold;">
            Atenção, Equipe de Gestão: manter contato com os responsáveis dos alunos faltosos abaixo relacionados, com o intuito de confirmar a continuidade da matricula dos mesmos. Esta lista deverá ser constantemente revisada e atualizada.
        </div>
    </div>
    <?php
    if (toolErp::id_nilvel() != 8) {
        ?>
        <div class="row">
            <div class="col">
                <?= formErp::select('id_inst', $escolas, 'Escola', $id_inst, 1) ?>
            </div>
        </div>
        <br />
        <?php
    }
    ?>
    <br /><br />
    <?php
    if (empty($maker)) {
        ?>
        <div class="alert alert-danger">
            Sua escola não está configurada para participar das Salas Macker
        </div>
        <?php
    } elseif ($id_inst && !empty($form)) {
        report::simple($form);
    } elseif ($id_inst) {
        ?>
        <div class="alert alert-success">
            Não há alunos Faltosos
        </div>
        <?php
    }
    ?>
</div>
<?php
if ($id_inst) {
    ?>
    <form id="formConf" method="POST">
        <input type="hidden" name="1[id_ta]" id="id_ta" />
        <input type="hidden" name="1[confirma]" id="confirma" />
        <?=
        formErp::hidden(['id_inst' => $id_inst])
        . formErp::hiddenToken('maker_gt_turma_aluno', 'ireplace')
        ?>
    </form>
    <script>
        function conf(id, idTa) {
            confirma.value = id;
            id_ta.value = idTa;
            formConf.submit();
        }
    </script>
    <?php
}
?>