<?php
if (!defined('ABSPATH'))
    exit;
$pesquisa = filter_input(INPUT_POST, 'pesquisa', FILTER_SANITIZE_STRING);
$rede = filter_input(INPUT_POST, 'rede', FILTER_SANITIZE_NUMBER_INT);
if ($pesquisa) {
    if ($rede) {
        $id_inst = null;
    } else {
        $id_inst = tool::id_inst();
    }
    $result = ng_main::alunoPesquisa($pesquisa, $id_inst, 130);
    if ($result) {
        if (count($result) >= 99) {
            toolErp::alertModal('Sua busca foi muito ampla. Limitamos o retorno da pesquisa a ' . count($result) . ' Alunos');
        }
        foreach ($result as $k => $v) {
            $result[$k]['turmasList'] = implode('<br>', $v['turmas']);
            $result[$k]['ac'] = formErp::submit('Acessar', null, ['id_pessoa' => $v['id_pessoa']], HOME_URI . '/sed/aluno');
        }
        $form['array'] = $result;
        $form['fields'] = [
            'RSE' => 'id_pessoa',
            'Nome' => 'n_pessoa',
            'Turmas' => 'turmasList',
            '||1' => 'ac'
        ];
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Gerenciar Alunos
    </div>
    <form method="POST">
        <div class="row">
            <div class="col-8">
                <?=
                formErp::input('pesquisa', 'Nome ou RSE', $pesquisa)
                ?>
            </div>
            <div class="col-2">
                <?= formErp::checkbox('rede', 1, 'Na rede', $rede) ?>
            </div>
            <div class="col-2">
                <?= formErp::button('Pesquisar') ?>
            </div>
        </div>
        <br />

    </form>

    <?php
    if (!empty($form)) {
        report::simple($form);
    } elseif ($pesquisa) {
        toolErp::divAlert('info','Aluno não Encontrado');
    }
    ?>
</div>
