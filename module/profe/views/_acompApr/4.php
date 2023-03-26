<?php
if (!defined('ABSPATH'))
    exit;
$semestre = filter_input(INPUT_POST, 'semestre', FILTER_SANITIZE_NUMBER_INT);
if (empty($semestre)) {
    if (date("m") > 6) {
        $semestre = 2;
    } else {
        $semestre = 1;
    }
}
$id_inst_turma = filter_input(INPUT_POST, 'id_inst_turma', FILTER_SANITIZE_NUMBER_INT);
$dados = sqlErp::get('profe_acompanhamento', '*', ['fk_id_pessoa_alu' => $id_pessoaAlu, 'ano' => date("Y"), 'semestre' => $semestre], 'fetch');
$alu = new ng_aluno($id_pessoaAlu);
if (!empty($dados['fk_id_pessoa_prof'])) {
    $id_pessoa = $dados['fk_id_pessoa_prof'];
} elseif (empty($id_pessoa)) {
    if (toolErp::id_nilvel() == 24) {
        $id_pessoa = toolErp::id_pessoa();
    }else{
        $id_pessoa = null;
    }
}
?>
<div class="row">
    <div class="col-3">
        <?= formErp::select('semestre', [1 => '1º Semestre', 2 => '2º Semestre'], 'Semestre', $semestre, 1, $hidden + ['activeNav' => 4]) ?>
    </div>
    <div class="col-7">
        <div class="fieldTop">
            <p>
                Acompanhamento Semestral do Desenvolvimento e da Aprendizagem
            </p>
        </div>
    </div>
    <div class="col-2">
        <?php
        if (!empty($dados['id_acomp'])) {
            ?>
            <form target="_blank" action="<?= HOME_URI ?>/profe/pdf/acompAprend.php" method="POST">
                <?=
                formErp::hidden($hidden)
                . formErp::hidden([
                    'semestre' => $semestre,
                    'nome' => $alu->dadosPessoais['n_pessoa'] . ' (RSE: ' . $id_pessoaAlu . ')',
                    'ano' => $dados['ano']
                ])
                . formErp::button("Gerar PDF")
                ?>   

            </form>
            <?php
        }
        ?>
    </div>
</div>
<br />
<table class="table table-bordered table-hover table-striped"  style="text-align: justify; font-weight: bold; font-size: 1.3em">
    <tr>
        <td>
            Escola
        </td>
        <td>
            <?= $escola ?>
        </td>
    </tr>
    <tr>
        <td>
            Nome
        </td>
        <td>
            <?= $alu->dadosPessoais['n_pessoa'] ?> (RSE: <?= $id_pessoaAlu ?>)
        </td>
    </tr>
    <tr>
        <td>
            Turma
        </td>
        <td>
            <?= $n_turma ?>
        </td>
    </tr>
    <?php if (!empty($id_pessoa)) {?>
        <tr>
            <td>
                <?php
                if (empty($id_pessoa)) {
                    $pos = 'a';
                } elseif (toolErp::sexo_pessoa($id_pessoa) == 'F') {
                    $pos = 'a';
                } else {
                    $pos = '';
                }
                ?>
                Professor<?= $pos ?>
            </td>
            <td>
                <?= !empty($id_pessoa) ? toolErp::n_pessoa($id_pessoa) : '' ?>
            </td>
        </tr>
    <?php } ?>
    <tr>
        <td>
            Período
        </td>
        <td>
            <?= $semestre ?>º Semestre de <?= date("Y") ?>
        </td>
    </tr>
    <tr>
        <td>
            Situação
        </td>
        <td>
            <?php
            if (empty(@$dados['situacao'])) {
                echo "Rascunho";
            } elseif (@$dados['situacao'] == 1) {
                echo "Enviado para o(a) coordenador(a)";
            } elseif (@$dados['situacao'] == 2) {
                echo "Aprovado pelo coordenador";
            } elseif (@$dados['situacao'] == 3) {
                echo "Devolvido para acertos";
            }
            ?>
        </td>
    </tr>
</table>
<br />
<div class="alert alert-info" style="text-align: justify; font-weight: bold; font-size: 1.3em">
    <p>Relate, de acordo com suas observações, o desenvolvimento da criança dentro do seu percurso de aprendizagem, utilizando os seguintes instrumentos metodológicos:</p>
    <p>
        Habilidades do conteúdo curricular de Barueri desenvolvidas
    </p>
    <p>
        Registros de acompanhamento realizados sobre os projetos executados, bem como anotações pessoais que retratem o processo de desenvolvimento da criança;
    </p>
    <p>
        Pauta de observação do desenvolvimento.
    </p>
</div>
<br />
<form id="envia" method="POST">
    <?php
    if (toolErp::id_nilvel() == 24) {
        ?>
        <div class="alert alert-primary" style="font-weight: bold; text-align: justify">
            <p>
                Recado do coordenador(a)
            </p>
            <?= @$dados['recado'] ?>
        </div>
        <br />
        <?php
    } else {
        ?>
        <?= formErp::textarea('1[recado]', @$dados['recado'], 'Recado do coordenador(a)') ?>
        <br />
        <?php
    }
    ?>
    <?= formErp::textarea('1[parecer]', @$dados['parecer'], 'Parecer <br />Descritivo', 500,'parecer') ?>
    <?=
    formErp::hidden($hidden)
    . formErp::hidden([
        'activeNav' => 4,
        'semestre' => $semestre,
        '1[id_acomp]' => empty($dados['id_acomp']) ? null : $dados['id_acomp'],
        '1[fk_id_pessoa_alu]' => $id_pessoaAlu,
        '1[semestre]' => $semestre,
        '1[fk_id_turma]' => $id_turma,
        '1[ano]' => date("Y"),
        '1[fk_id_inst]' => $id_inst_turma
    ])
    . formErp::hiddenToken('profe_acompanhamento', 'ireplace')
    ?>
    <?php
    if (toolErp::id_nilvel() == 24) {
        ?>
        <input type="hidden" name="1[situacao]" id="situacao" value="0" />
        <input type="hidden" name="1[fk_id_pessoa_prof]" value="<?= $id_pessoa ?>" />
        <div class="row">
            <div class="col" style="text-align: center; padding: 30px">
                <?= formErp::button('Salvar Rascunho') ?>
            </div>
            <div class="col" style="text-align: center; padding: 30px">
                <button class="btn btn-primary" onclick="coord()" type="button">
                    Salvar e enviar para o coordenador
                </button>
            </div>
        </div>
        <?php
    } else {
        ?>
        <input type="hidden" name="1[situacao]" id="situacao" value="3" />
        <div class="row">
            <div class="col" style="text-align: center; padding: 30px">
                <?= formErp::button('Devolver Para o professor(a) realizar acertos') ?>
            </div>
            <div class="col" style="text-align: center; padding: 30px">
                <button class="btn btn-primary" onclick="coordAprov()" type="button">
                    Aprovar Acompanhamento
                </button>
            </div>
        </div>
        <?php
    }
    ?>
    <br />
</form>
<script>
    function coord() {
        situacao.value = 1;
        envia.submit();
    }
    function coordAprov() {
        situacao.value = 2;
        envia.submit();
    }
</script>
