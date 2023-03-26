<?php
if (!defined('ABSPATH'))
    exit;
$set = sqlErp::get('ge_setup', '*', null, 'fetch');
$diferenca = data::calcula($set['libera_nota_aluno'], date("Y-m-d"), 'dia');
?>
<div class="row">
    <div class="col-10 topo">
        Liberar as Notas para os alunos
    </div>
    <div class="col-2">
        <form method="POST">
            <button class="btn btn-primary border">
                Voltar
            </button>
        </form>
    </div>
</div>
<br />
<?php
if ($diferenca > 10) {
    ?>
    <div class="alert alert-info" style="font-weight: bold; text-align: center; font-size: 1.2em">
        <p>
            Esta ação irá atualizar a base de dados do APP do Aluno diariamente pelos próximos 10 dias.
        </p>
    </div>
    <form method="POST">
        <?=
        formErp::hidden([
            'set' => 2,
            '1[id_set]' => 1,
            '1[libera_nota_aluno]' => date("Y-m-d"),
        ])
        . formErp::hiddenToken('ge_setup', 'ireplace')
        ?>
        <div class="row">
            <div class="col text-center">
                <button class="btn btn-success" type="submit">
                    Atualizar diariamente as Notas do APP do Aluno por 10 dias
                </button>
            </div>
        </div>
        <br />
    </form>
    <?php
} else {
    $diaFinal = date('d/m/Y', strtotime('+10 days', strtotime(date("d-m-Y"))));
    ?>
    <div class="alert alert-primary" style="font-weight: bold; text-align: center; font-size: 1.2em">
        A base de dados do APP do Aluno será atualizada diarimente até o dia <?= $diaFinal ?>
    </div>
    <form method="POST">
        <?=
        formErp::hidden([
            'set' => 2,
            '1[id_set]' => 1,
            '1[libera_nota_aluno]' => '0000-00-00',
        ])
        . formErp::hiddenToken('ge_setup', 'ireplace')
        ?>
        <div class="row">
            <div class="col text-center">
                <button class="btn btn-warning" type="submit">
                    Cancelar a Atualização diária das Notas do APP do Aluno
                </button>
            </div>
        </div>
        <br />
    </form>
    <?php
}
?>