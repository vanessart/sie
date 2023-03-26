<?php
if (!defined('ABSPATH'))
    exit;

//$id_move = filter_input(INPUT_POST, 'id_move', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa_list', FILTER_SANITIZE_NUMBER_INT);
//$filtro = filter_input(INPUT_POST, 'filtro', FILTER_SANITIZE_STRING);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_equipamento = filter_input(INPUT_POST, 'id_equipamento', FILTER_SANITIZE_NUMBER_INT);
//$devolucao = filter_input(INPUT_POST, 'devolucao', FILTER_SANITIZE_NUMBER_INT);
$hidden = [
'id_equipamento' => $id_equipamento,
'id_pessoa' => $id_pessoa,
'id_inst' => $id_inst,
];

if (!empty($id_inst)) {
    $alunos = $model->alunoEscola($id_inst);
    $prof = $model->funcionarios($id_inst);
    $pessoas = $alunos + $prof;
    $equipamentoList = $model->equipamentoEscola($id_inst);
}

if (!empty($id_pessoa)) {
    $professor = $model->verificaProf($id_pessoa);
}

if (!empty($pessoas) && !empty($equipamentoList)) {
    ?>
    <div class="body">
        <div class="fieldTop">
            Empréstimo de Equipamento
        </div>
        <div class="row">
            <div class="col">
                <?= formErp::select('id_equipamento', $equipamentoList, 'Modelo/Lote', @$id_equipamento, 1, $hidden, ' required ') ?>
            </div>
            <div class="col">
                <?= formErp::select('id_pessoa_list', $pessoas, 'Aluno/Funcionário', @$id_pessoa, 1, $hidden) ?>
            </div>
        </div>
        <br />
        <?php
        $emprestado = "";
        if (!empty($id_pessoa) && !empty($id_equipamento)) {
            $emprestado = $model->getEmprestimoPessoa($id_pessoa, $id_equipamento);
            if ($emprestado) {?>
            <div class="alert alert-danger text-justify">
                <?= $pessoas[$id_pessoa] ?> emprestou o equipamento N/S <?= $emprestado['n_serial'] ?> retirado na <?= $emprestado['n_inst'] ?> no dia <?= data::porExtenso($emprestado['dt_inicio']) ?>
            </div><?php

            }else{
                $objetoList = $model->objetoEscola($id_inst, $id_equipamento);
                ?>
                <form target="_parent" action="<?= HOME_URI ?>/recurso/empresta" method="POST">
                    <div class="row">
                        <div class="col">
                            <?= formErp::select('1[fk_id_serial]', $objetoList, 'Número de Série', null, null, null,' required ') ?>
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="col">
                            <?= formErp::input('1[dt_inicio]', 'Início', null, ' required ', null, 'date') ?>
                        </div>
                        <div class="col">
                            <?= formErp::input('1[dt_fim]', 'Fim', null,' required ', null, 'date') ?>
                        </div>
                    </div>
                    <br /><br />
                    <div style="text-align: center">
                        <?=
                        formErp::hidden([
                            'id_inst' => $id_inst,
                            'id_equipamento' => $id_equipamento,
                            '1[fk_id_pessoa_aloca]' => toolErp::id_pessoa(),
                            '1[id_move]' => @$id_move,
                            '1[fk_id_pessoa_emprest]' => @$id_pessoa,
                            '1[fk_id_situacao]' => 2,
                            '1[fk_id_inst]' => @$id_inst,
                            '1[professor]' => @$professor
                        ])
                        . formErp::hiddenToken('emprestar')
                        ?>
                        <br /><br />
                        <?php
                        if (empty($id_move)) {
                            ?>
                            <button type="submit" class=" btn btn-success">
                                Salvar
                            </button>
                            <?php
                        }
                        ?>
                    </div>
                </form>
                <?php
            }
        }
        
} else {
    ?>
    <div class="alert alert-danger">
        Não há alunos ou Chromebooks nesta instância
    </div>
    <?php
}
?>