<?php
if (!defined('ABSPATH'))
    exit;

$erro = false;
if (toolErp::id_nilvel() == 44) {
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
} else {
    $id_inst = toolErp::id_inst();
}



$pls = [81, 84, 87, 110];
$pat = $model->paternidade($pls, $id_inst);
if ($pat) {
    $token = formErp::token('atualizarPat');
    foreach ($pat as $k => $v) {
        if (empty($v['pai']) || empty($v['mae'])) {
            $erro = true;
        }
        $pat[$k]['paiSet'] = ($v['pai'] == 'SPE' ? '<span style="font-weight: bold;">Sem Paternidade Estabelecida</span>' : '<span style="font-weight: bold; color: red">Indefinido</span>');
        $pat[$k]['maeSet'] = (!empty($v['mae']) ? $v['mae'] : '<span style="font-weight: bold; color: red">Indefinida</span>');
        if (empty($v['pai'])) {
            $pat[$k]['ac'] = '<button style="white-space: nowrap" class="btn btn-warning" onclick="editx(' . $v['id_pessoa'] . ')">Marcar como "SPE"</button>';
        } elseif ($v['pai'] == 'SPE') {
            $pat[$k]['ac'] = '<button style="white-space: nowrap" class="btn btn-danger" onclick="edity(' . $v['id_pessoa'] . ')">Desmarcar como "SPE"</button>';
        }
        if ($v['pai'] != 'SPE' || empty($v['mae'])) {
            $pat[$k]['sed'] = formErp::submit('Sincronizar com o SED', $token, ['ra' => $v['ra'], 'ra_uf' => $v['ra_uf'], 'id_pessoa' => $v['id_pessoa']]);
        }
    }
    $form['array'] = $pat;
    $form['fields'] = [
        'Ingresso' => 'periodo_letivo',
        'RSE' => 'id_pessoa',
        'Nome' => 'n_pessoa',
        'Dt. Nasc' => 'dt_nasc',
        'Mãe' => 'maeSet',
        'pai' => 'paiSet',
        '||1' => 'ac',
        '||2' => 'sed'
    ];
}
?>
<div class="body">
    <div class="fieldTop">
        Projeto Paternidade Responsável
    </div>
    <?php
    if (toolErp::id_nilvel() == 44) {
        echo formErp::select('id_inst', escolas::idEscolas(), 'Escola', $id_inst, 1);
    }
    ?>
    <div class="alert alert-info" style="font-weight: bold">
        <p>
            Para gerar o PDF com os alunos sem a paternidade estabelecida, é necessário resolver todos os casos de pai ou mãe indefinidos.
        </p>
        <p>
            O botão “Gerar PDF” aparecerá quando todos os alunos da lista estiverem definidos como “Sem Paternidade Estabelecida”.
        </p>
        <p>
            Os casos definidos como “Sem Paternidade Estabelecida” são os alunos que não constam o nome do pai na certidão de nascimento.
        </p>
        <p>
            Nos casos dos pais “Indefinido” que não se enquadra em “Sem Paternidade Estabelecida” devem ser cadastrados no SED (não adianta cadastrar no SIEB) e, após o cadastro, clicar em  “Sincronizar com o SED”.
        </p>
        <p>
            Nos casos das mães “Indefinida” devem ser cadastradas no SED (não adianta cadastrar no SIEB) e, após o cadastro, clicar em  “Sincronizar com o SED”.
        </p>
        <p>
            Nos casos em que há pai, sincronizado, o aluno desaparecerá da lista.
        </p>
        <p>
            A presente relação nominal terá origem na consulta do documento original de Certidão de Nascimento dos alunos, em atendimento ao Projeto Paternidade Responsável do Poder Judiciário do Estado de São Paulo, Foro de <?= CLI_CIDADE ?>, 4a. Vara Cível.
        </p>
    </div>
    <br /><br />
    <div style="text-align: center">
        <button id="ver" class="btn btn-info" onclick="vd.style.display = '';nver.style.display = '';ver.style.display = 'none'; vid.play()">
            Dúvidas sobre o preenchimento? Clique Aqui.
        </button>
    </div>
    <div style="text-align: center">
        <button id="nver" style="display: none" class="btn btn-danger" onclick="vd.style.display = 'none';nver.style.display = 'none';ver.style.display = ''; vid.pause()">
            Fechar o vídeo
        </button>
    </div>
    <br />
    <div class="viewVideo" style="text-align: center; display: none" id="vd">
        <video id="vid" style="width: 100%" controls>
            <source src="<?= HOME_URI ?>/pub/vd/Paternidade.mp4" type="video/mp4">
        </video>
    </div>
    <div style="text-align: right; padding-right: 50px">
        <?php
        if ($erro) {
            ?>
            <button class="btn btn-danger">
                O PDF será liberado quando as pendências forem resolvidas
            </button>
            <?php
        } else {
            ?>
            <form action="<?= HOME_URI ?>/sed/pdf/paternidade.php" target="_blank" method="POST">
                <?=
                formErp::hidden([
                    'id_inst' => $id_inst
                ])
                ?>
                <button class="btn btn-primary">
                    Gerar PDF
                </button>
            </form>
            <?php
        }
        ?>
    </div>
    <br /><br />
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>
<form target="frame" id="form" method="POST">
    <input type="hidden" name="1[id_pessoa]" id="id_pessoa" value="" />
    <input type="hidden" name="1[pai]" value="SPE" />
    <input type="text" name="id_inst" value="<?= $id_inst ?>" />
    <?= formErp::hiddenToken('pessoa', 'ireplace') ?>
</form>
<form target="frame" id="formy" method="POST">
    <input type="hidden" name="1[id_pessoa]" id="id_pessoay" value="" />
    <input type="hidden" name="1[pai]" value="" />
    <input type="text" name="id_inst" value="<?= $id_inst ?>" />
    <?= formErp::hiddenToken('pessoa', 'ireplace') ?>
</form>
<script>
    function editx(id) {
        id_pessoa.value = id;
        if (confirm("Marcar como Sem Paternidade Estabelecida?")) {
            form.submit();
        }
    }
    function edity(id) {
        id_pessoay.value = id;
        if (confirm("Desmarcar como Sem Paternidade Estabelecida?")) {
            formy.submit();
        }
    }
</script>