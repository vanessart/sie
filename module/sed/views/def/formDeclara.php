<?php
if (!defined('ABSPATH'))
    exit;

$id_vaga_c = filter_input(INPUT_POST, 'id_vaga_c', FILTER_SANITIZE_NUMBER_INT);
$nome_aluno = filter_input(INPUT_POST, 'nome_aluno', FILTER_UNSAFE_RAW);
$cont = 0;

if ($id_vaga_c) {
    $dados = sqlErp::get('ge_decl_vaga_comp', '*', ['id_vaga_c' => $id_vaga_c], 'fetch');
    $id_pessoa = $dados['rse'];
    $tipodec = $dados['tipodec'];
} else {
    $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
    $tipodec = filter_input(INPUT_POST, 'tipodec', FILTER_SANITIZE_NUMBER_INT);
    $cont = filter_input(INPUT_POST, 'cont', FILTER_SANITIZE_NUMBER_INT);
}
$alunos_ = alunos::alunosGet(toolErp::id_inst(), 'p.id_pessoa, p.n_pessoa, t.codigo, ta.chamada, p.sexo, ci.n_ciclo ');

$declaraTipo = [
    1 => 'Declaração de Vaga',
    2 => 'Declaração de Comparecimento'
];

$ciclos_ = sqlErp::get(['ge_ciclos', 'ge_cursos'], "concat(n_ciclo, ' - ', n_curso) n_ciclo, id_ciclo ", ['>' => 'n_curso, n_ciclo']);
$ciclos = toolErp::idName($ciclos_);

$alunos = [];
$alunosSel = [];
foreach ($alunos_ as $v) {
    $alunos[$v['id_pessoa']] = $v;
    $alunosSel[$v['id_pessoa']] = $v['n_pessoa'];
}
?>
<div class="body">
    <?php
    if ($tipodec && $cont == 1) {
        ?>
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    RSE
                </td>
                <td>
                    Nome
                </td>
                <?php
                if ($tipodec == 2) {
                    ?>
                    <td>
                        Turma
                    </td>
                    <td>
                        Ciclo
                    </td>
                    <td>
                        Sexo
                    </td>
                    <?php
                }
                ?>
            </tr>
            <tr>
                <td>
                    <?= $id_pessoa ?>
                </td>
                <td>
                    <?= ($tipodec == 2 ? $alunos[$id_pessoa]['n_pessoa'] : $nome_aluno) ?>
                </td>

                <?php
                if ($tipodec == 2) {
                    ?>
                    <td>
                        <?= $alunos[$id_pessoa]['n_ciclo'] ?>
                    </td>
                    <td>
                        <?= $alunos[$id_pessoa]['sexo'] ?>
                    </td>
                    <td>
                        <?= $alunos[$id_pessoa]['codigo'] ?>
                    </td>
                    <?php
                }
                ?>
            </tr>
        </table>
        <br />
        <form target="_parent" action="<?= HOME_URI ?>/sed/declaracaovaga" method="POST">
            <?php
            if ($tipodec == 1) {
                ?>
                <div class="row">
                    <div class="col">
                        <?= formErp::select('1[fk_id_ciclo]', $ciclos, 'Ano/Ensino', @$dados['$n_ciclo']) ?>
                    </div>
                    <div class="col">
                        <?= formErp::select('1[sexo_aluno]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo Aluno', @$dados['sexo_aluno']) ?>
                    </div>
                </div>
                <?php
            }
            ?>
            <br />
            <div class="row">
                <div class="col">
                    <?= formErp::input('1[nome_solicitante]', 'Nome Solicitante', @$dados['nome_solicitante'], 'required') ?>
                </div>
                <div class="col">
                    <?= formErp::input('1[rg]', 'R.G', @$dados['rg'], 'required') ?>
                </div>
                <div class="col">
                    <?= formErp::select('1[sexo]', toolErp::sexo(), 'Sexo', @$dados['sexo'], null, null, 'required') ?>
                </div>
            </div>
            <br />
            <hr>
            <?php
            if ($tipodec == 2) {
                ?>
                <div class="row">
                    <div class="col">
                        <?= formErp::input('1[dt_comp]', 'Data Comparecimento', @$dados['dt_comp'], null, null, 'date') ?>
                    </div>
                    <div class="col">
                        <?= formErp::input('1[h_inicio]', 'Hora Início', @$dados['h_inicio'], null, null, 'time') ?>
                    </div>
                    <div class="col">
                        <?= formErp::input('1[h_final]', 'Hora Final', @$dados['h_final'], null, null, 'time') ?> 
                    </div>
                </div>
                <br />
                <?php
            }
            ?>
            <div style="text-align: center; padding: 20px">
                <?php
                $cod = '-';
                if (empty($id_vaga_c)) {
                    if ($tipodec == 2) {
                       
                        echo formErp::hidden([
                            '1[fk_id_inst]' => toolErp::id_inst(),
                            '1[n_ciclo]' => $alunos[$id_pessoa]['n_ciclo']??'',
                            '1[dt_emissao]' => date("Y-m-d"),
                            '1[tipo]' => $declaraTipo[$tipodec],
                            '1[rse]' => $id_pessoa,
                            '1[nome_aluno]' => $alunos[$id_pessoa]['n_pessoa'],
                            '1[codigo]' => $alunos[$id_pessoa]['codigo'],
                            '1[sexo_aluno]' => $alunos[$id_pessoa]['sexo'],
                            '1[tipodec]' => $tipodec
                        ]);
                    } else {
                        echo formErp::hidden([
                            '1[fk_id_inst]' => toolErp::id_inst(),
                            '1[dt_emissao]' => date("Y-m-d"),
                            '1[tipo]' => $declaraTipo[$tipodec],
                            '1[nome_aluno]' => $nome_aluno,
                            '1[codigo]' => @$cod,
                            '1[tipodec]' => $tipodec
                        ]);
                    }
                } else {
                    echo formErp::hidden(['1[id_vaga_c]' => $id_vaga_c]);
                }
                ?>
                <?=
                formErp::hiddenToken('ge_decl_vaga_comp', 'ireplace')
                . formErp::button('Salvar')
                ?>
            </div>
        </form>
    <?php
} else {
    ?>
        <div class="row">
            <div class="col">
    <?= formErp::select('tipodec', $declaraTipo, 'Tipo Declaração', $tipodec, 1) ?>
            </div>
        </div>     
        <br/>
        <form method="POST">
            <div class="row">
                <div class="col-10">
    <?php
    if ($tipodec == 2) {
        echo formErp::select('id_pessoa', $alunosSel, 'Aluno', $id_pessoa);
    } elseif ($tipodec == 1) {
        echo formErp::input('nome_aluno', 'Aluno', $nome_aluno);
    }
    ?>
                </div>
                <div class="col-2">
    <?= formErp::button('Continuar') ?>
                </div>
            </div>
            <br />
            <input type="hidden" name="tipodec" value= "<?= $tipodec ?>" />
            <input type="hidden" name="cont" value= 1 />
        </form>
    <?php
}
?>
</div>