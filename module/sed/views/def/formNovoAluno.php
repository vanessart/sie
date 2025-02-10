<?php
if (!defined('ABSPATH'))
    exit;
$id_pl = filter_input(INPUT_POST, 'id_pl', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_curso = filter_input(INPUT_POST, 'id_curso', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$n_turma = filter_input(INPUT_POST, 'n_turma');
$n_curso = filter_input(INPUT_POST, 'n_curso');
$n_inst = filter_input(INPUT_POST, 'n_inst');
$titulo_periodo = filter_input(INPUT_POST, 'titulo_periodo');
$periodo = filter_input(INPUT_POST, 'periodo');
$diaSemanaNumero = filter_input(INPUT_POST, 'diaSemanaNumero');
$dia_hora = filter_input(INPUT_POST, 'dia_hora');
$idade_de = filter_input(INPUT_POST, 'idade_de');
$idade_ate = filter_input(INPUT_POST, 'idade_ate');
$pesquisa = filter_input(INPUT_POST, 'pesquisa');
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa');
$id_aluno = filter_input(INPUT_POST, 'id_aluno');

$n_polos = "Escola";
$n_cursos = "Curso";
$qt_curso_aluno = 1;

if($id_aluno){
    $pesquisa = $id_aluno;
}
$hidden2 = [
    'id_pl' => $id_pl,
    'id_turma' => $id_turma,
    'id_curso' => $id_curso,
    'id_inst' => $id_inst,
    'n_turma' => $n_turma,
    'n_curso' => $n_curso,
    'n_inst' => $n_inst,
    'titulo_periodo' => $titulo_periodo,
    'periodo' => $periodo,
    'diaSemanaNumero' => $diaSemanaNumero,
    'dia_hora' => $dia_hora,
    'idade_de' => $idade_de,
    'idade_ate' => $idade_ate,
    'pesquisa' => $pesquisa,
];

if(isset($diaSemanaNumero)){
    $diaSemanaArray = explode(',', $diaSemanaNumero);
}

$periodoCurso = $periodo;
$idadeIn = $idade_de;
$idadeFim = $idade_ate;

if (!is_null($idadeFim)) {
    $anos = $idadeFim == 1 ? 'ano' : 'anos';
    $idadeOficina = $idadeIn . ' a ' . $idadeFim . ' ' . $anos;
} else {
    $idadeOficina = 'Sem limitação de Idade';
}

$tem = sql::get(['ge_turma_aluno'], 'fk_id_pessoa', ['fk_id_turma' => $id_turma], 'fetchAll');
$temPessoas = array_column($tem, 'fk_id_pessoa');

$hidden = [
    'id_pl' => $id_pl,
    'id_inst' => $id_inst,
    'id_turma' => $id_turma,
    'n_turma' => $n_turma,
    'id_curso' => @$vagas['id_curso']
];

if ($pesquisa) {
    if (is_numeric($pesquisa)) {
        $where = " WHERE id_pessoa = $pesquisa";
    } else {
        $where = " WHERE n_pessoa like '$pesquisa%'";
    }
    $sql = " SELECT p.id_pessoa, p.n_pessoa, TIMESTAMPDIFF(YEAR,  p.dt_nasc, CURDATE()) AS idade "
        . " FROM pessoa p "
        . $where
        . " ORDER BY p.n_pessoa ";

    
    $query = pdoSis::getInstance()->query($sql);
    $result = $query->fetchAll(PDO::FETCH_ASSOC);
    
    $hide = 0;
    $token = form::token('novoAluno');
    if ($result) {
        if (count($result) >= 99) {
            tool::alertModal('Sua busca foi muito ampla. Limitamos o retorno da pesquisa a ' . count($result) . ' Alunos', null, 1);
        }
        foreach ($result as $k => $v) {
            $estaCadastrada = sql::get(['ge_turma_aluno'], 'fk_id_turma', ['fk_id_pessoa' => $v['id_pessoa'], 'fk_id_turma' => $id_turma, 'fk_id_tas' => '0 '], 'fetch');

            $detalhesPessoa = $model->temPessoa($v['id_pessoa']);
            $sexo = $detalhesPessoa['sexo'];
            $qt_turmas = $detalhesPessoa['qt_turmas'];
            $n_pessoa = $detalhesPessoa['n_pessoa'];
            
            if($estaCadastrada){
                $result[$k]['ac'] = '<button class="btn btn-outline-info" disabled>Alun'. tool::sexoArt($sexo) .' já cadastrad'. tool::sexoArt($sexo) .' nesta turma</button>';
            }elseif (($qt_turmas > $qt_curso_aluno) || ($qt_turmas == $qt_curso_aluno)){
                $result[$k]['ac'] = '<button class="btn btn-outline-info" disabled>Já está matriculad' . tool::sexoArt($sexo) . ' em '.$qt_turmas.' '.$n_cursos.'s</button>';
            }else{
                $result[$k]['ac'] = form::submit('Matricular', $token, [
                    'id_pessoa' => $v['id_pessoa'], 
                    'id_pl' => $id_pl,
                    'id_inst' => $id_inst,
                    'id_turma' => $id_turma,
                    'n_turma' => $n_turma,
                    'id_curso' => $id_curso
                ], HOME_URI."/sed/alocaAlu", "target='_parent'");
            }
        }
        $form['array'] = $result;
        $form['fields'] = [
            'RSE' => 'id_pessoa',
            'Nome' => 'n_pessoa',
            'Idade' => 'idade',
            '||1' => 'ac'
        ];
    }
}

?>
<div class="body">
    <div class="fieldTop">
        Nova Matrícula
    </div>
    <table class="table table-bordered table-hover table-responsive">
        <tr>
            <td>
                <?= $n_polos ?>
            </td>
            <td>
                <?= $n_inst ?>
            </td>
        </tr>
        <tr>
            <td>
                <?= $n_cursos ?>
            </td>
            <td>
                <?= $n_curso ?>
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
        
    </table>
    <form method="POST">
        <div class="col-3">
            <?=
            form::hidden($hidden2)
            ?>
        </div>
        <?php
        if(empty($id_aluno)){
        ?>
        <div class="row">
            <div class="col-8">
                <?=
                form::input('pesquisa', 'Nome ou RSE', $pesquisa)
                ?>
            </div>
            <div class="col-2">
                <?= form::button('Pesquisar') ?>
            </div>
        </div>
        <?php } ?>
        <br />
    </form>
    <!-- <form action="<?= HOME_URI ?>/sed/alocaAlu" target="_parent" method="POST"> -->
        <div class="row">
            <div class="col-3">
                <?=
                form::hidden($hidden)
                . form::hiddenToken('novoAluno')
                ?>
            </div>
        </div>
        <br />
        <?php
        if (!empty($form)) {
            report::simple($form);
        } elseif ($pesquisa) {
            tool::divAlert('info', 'Aluno não Encontrado');
        }
        ?>
    <!-- </form> -->
</div>
