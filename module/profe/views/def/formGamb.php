<?php
if (!defined('ABSPATH'))
    exit;
$bimestre = filter_input(INPUT_POST, 'bimestre', FILTER_SANITIZE_NUMBER_INT);
$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_disc = filter_input(INPUT_POST, 'id_disc', FILTER_SANITIZE_NUMBER_INT);
$n_disc = filter_input(INPUT_POST, 'n_disc', FILTER_UNSAFE_RAW);
$nome_turma = filter_input(INPUT_POST, 'n_turma', FILTER_UNSAFE_RAW);
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$escola = filter_input(INPUT_POST, 'escola', FILTER_UNSAFE_RAW);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$prof_ = professores::listar(tool::id_inst());

if (!empty($prof_)) {
    foreach ($prof_ as $v) {
        $prof[$v['fk_id_pessoa']] = $v['n_pessoa'];
    }
}
if ($id) {
    $sql = "SELECT * FROM diario_classe.`frequencias` WHERE `id` = $id";
    $query = pdoSis::getInstance()->query($sql);
    $dados = $query->fetch(PDO::FETCH_ASSOC);
}
?>
<div class="body">
    <form action="<?= HOME_URI ?>/profe/diarioCoord" target="_parent" method="POST">
        <div class="row">
            <div class="col">
                <?= formErp::input('1[data_registro]', 'Data', @$dados['data_registro'], ' required ', null, 'date') ?>
            </div>
            <div class="col">
                <?= formErp::select('1[professor_id]', $prof, 'Professor', @$dados['professor_id']) ?>
            </div>
        </div>
        <br />
        <div style="font-weight: bold; padding: 5px">
            Registro de Aula
        </div>
        <?= formErp::textarea('1[registro_aula]', @$dados['registro_aula']) ?>
        <br /><br />
        <div style="font-weight: bold; padding: 5px">
            Ocorrências
        </div>
        <?= formErp::textarea('1[observacoes]', @$dados['observacoes']) ?>
        <br /><br />
        <div style="font-weight: bold; padding: 5px">
            Adaptação Curricular
        </div>
        <?= formErp::textarea('1[apd]', @$dados['apd']) ?>
        <br /><br />
        <div style="text-align: center">
            <?=
            formErp::hidden([
                '1[id]' => $id,
                '1[classe_id]' => $id_turma,
                '1[periodo_aula]' => 1,
                '1[disciplina_id]' => $id_disc,
                'id_turma' => $id_turma,
                'id_disc' => $id_disc,
                'n_disc' => $n_disc,
                'id_pl' => $id_pl,
                'escola' => $escola,
                'id_curso' => $id_curso,
                'bimestre' => $bimestre
            ])
            . formErp::hiddenToken('salvarGamb')
            . formErp::button('Salvar')
            ?>
        </div>
    </form>
    <?php
    if ($id) {
        ?>
        <br /><br />
        <div class="border alert alert-danger">
            <div class="fieldTop">
                ATENÇÂO! Esta ação é irreversível
                <br /><br />
                <form id="del" action="<?= HOME_URI ?>/profe/diarioCoord" target="_parent" method="POST">
                    <?=
                    formErp::hidden([
                        'id' => $id,
                        'id_turma' => $id_turma,
                        'id_disc' => $id_disc,
                        'n_disc' => $n_disc,
                        'id_pl' => $id_pl,
                        'escola' => $escola,
                        'id_curso' => $id_curso,
                        'bimestre' => $bimestre
                    ])
                    . formErp::hiddenToken('delGamb')
                    ?>
                </form>  

                <button onclick="if (confirm('Deseja realmente excluir este Registro?')) {
                                document.getElementById('del').submit();
                            }" class="btn btn-danger">
                    Excluir Registro
                </button>

            </div>
        </div>
        <?php
    }
    ?>
</div>
