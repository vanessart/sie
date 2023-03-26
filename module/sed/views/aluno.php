<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
if ($id_turma) {

    $turmaSet = sql::get('ge_turmas', 'n_turma, fk_id_pl', ['id_turma' => $id_turma, 'fk_id_inst' => toolErp::id_inst()], 'fetch');
    $id_pl = $turmaSet['fk_id_pl'];
    $n_turma = $turmaSet['n_turma'];
}

$hidden = [
    'id_pessoa' => $id_pessoa,
    'id_turma' => $id_turma,
    'n_turma' => @$n_turma,
    'id_pl' => @$id_pl
];
if ($id_pessoa) {
    $aluno = new ng_aluno($id_pessoa);
    $abaAtv = 1;
} else {
    $abaAtv = null;
}
if ($id_pessoa) {
    ?>
    <div class="body">
        <div class="fieldTop">
            <?php
            if (!empty($aluno->dadosPessoais['n_pessoa'])) {
                echo $aluno->dadosPessoais['n_pessoa'] . ' - ' . $aluno->id_pessoa;
            } else {
                echo 'Cadastro de Aluno';
            }
            ?>
        </div>
        <?php
        $abas[1] = ['nome' => "Geral", 'ativo' => 1, 'hidden' => $hidden];
        $abas[2] = ['nome' => "Contatos", 'ativo' => $abaAtv, 'hidden' => $hidden];
        $abas[3] = ['nome' => "Endereço", 'ativo' => $abaAtv, 'hidden' => $hidden];
        $abas[4] = ['nome' => "Vida Escolar", 'ativo' => $abaAtv, 'hidden' => $hidden];
        $abas[5] = ['nome' => "Prontuário", 'ativo' => $abaAtv, 'hidden' => $hidden];
        $abas[6] = ['nome' => "Autorização", 'ativo' => $abaAtv, 'hidden' => $hidden];
        //$abas[7] = ['nome' => "saúde", 'ativo' => $abaAtv, 'hidden' => $hidden];
        if (!empty($n_turma)) {
            $abas[9] = ['nome' => $n_turma, 'ativo' => 1, 'hidden' => $hidden, 'link' => HOME_URI . "/sed/turmas",];
        }
        $aba = report::abas($abas);
        ?>
        <div class="borderAba">

            <?php
            include ABSPATH . "/module/sed/views/_aluno/$aba.php";
            ?>
        </div>
    </div>
    <?php
} else {
    ?>
    <div class="alert alert-danger text-center">
        <p>
            Não foi possível acessar as informações do aluno.
        </p>
        <p>
            Por favor, reinicie o processo.
        </p>
    </div>
    <?php
}