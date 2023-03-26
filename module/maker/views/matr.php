<?php
if (!defined('ABSPATH'))
    exit;
if (toolErp::id_nilvel() == 8) {
    $id_inst_ = toolErp::id_inst();
} else {
    $id_inst_ = filter_input(INPUT_POST, 'id_inst_', FILTER_SANITIZE_NUMBER_INT);
    $escolas = $model->escolasMaker();
}
if ($id_inst_) {
    $sql = "SELECT "
            . " p.fk_id_inst_maker, n_polo, id_polo "
            . " FROM maker_escola e "
            . " JOIN maker_polo p on p.id_polo = e.fk_id_polo "
            . " WHERE fk_id_inst = $id_inst_ ";
    $query = pdoSis::getInstance()->query($sql);
    $polo = $query->fetch(PDO::FETCH_ASSOC);
    if ($polo) {
        $id_inst = $polo['fk_id_inst_maker'];
        $nPolo = $polo['n_polo'];
        $id_polo = $polo['id_polo'];
    } else {
        ?>
        <div class="alert alert-danger">
            Sua escola não está configurada para participar das Salas Maker
        </div>
        <?php
    }
}

$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);

$periodo = null;


$diaSem = filter_input(INPUT_POST, 'diaSem', FILTER_SANITIZE_NUMBER_INT);
$setup = sql::get(['maker_setup', 'ge_periodo_letivo'], '*', null, 'fetch');
$id_as = $setup['libera_matr'];
$polos = sql::idNome('maker_polo');

if (!empty($id_polo)) {

    $alunos = $model->alunosEscMatricula($id_inst_, $id_as, null, $periodo, $diaSem);
    if ($alunos) {
        foreach ($alunos as $k => $v) {
            $alunos[$k]['matr'] = '<button onclick="matr(' . $v['id_ma'] . ', ' . $v['id_mc'] . ')" class="btn btn-warning" >Matricular</button>';
            $alunos[$k]['data'] = data::converteBr($v['times_stamp']) . ' às ' . substr($v['times_stamp'], 11, 5);
            $alunos[$k]['dia'] = $v['dia'] . 'ª-feira';
            $alunos[$k]['periodo'] = $v['periodo'] == 'T' ? 'Tarde' : 'Manhã';
            $alunos[$k]['transpe'] = toolErp::simnao($v['transporte']);
        }
        $form['array'] = $alunos;
        $form['fields'] = [
            'RSE' => 'id_pessoa',
            'Nome' => 'n_pessoa',
            'Dt. Inscr' => 'data',
           // 'Ciclo' => 'n_mc',
            'Período' => 'periodo',
            'Transporte' => 'transpe',
            '||1' => 'matr',
        ];
    }
}
?>
<div class="body">
    <div class="row">
        <?php
        if (toolErp::id_nilvel() != 8) {
            ?>
            <div class="col">
                <?= formErp::select('id_inst_', $escolas, 'Escola', $id_inst_, 1) ?>
            </div>
            <?php
        }
        if (!empty($id_polo)) {
            if (!empty($id_inst)) {
                ?>
                <div class="col" style="font-weight: bold; font-size: 1.3em; text-align: center">
                    Polo: <?= $nPolo ?>
                </div>
                <?php
            }
            ?>
        </div>
        <br /><br />
        <?php
        if ($id_polo) {
            ?>
            <div>
                <div class="fieldTop">

                    <?php
                    $sit = [3 => 'Fila de Espera', 1 => 'Rematrícula'];
                    foreach (explode(',', $id_as) as $i) {
                        if (!empty($i)) {
                            $sitt[] = $sit[$i];
                        }
                    }
                    if (empty($sitt)) {
                        ?>
                        Matrículas Fechadas
                        <br />
                        ATENÇÃO: O sistema está temporiamente indisponível para lançamentos, devido à implementação de melhorias na navegabilidade. Por favor, volte dentro de alguns minutos.
                        <?php
                    } else {
                        ?>
                        Matrículas: <?= toolErp::virgulaE($sitt) ?>
                        <?php
                    }
                    ?>
                </div>
                <?php
                if (!empty($form)) {
                    report::simple($form);
                }
            }
            ?>
        </div>
        <form action="<?= HOME_URI ?>/maker/def/formAlunoMatrEsc" target="frame" id="formMatr" method="POST">
            <?=
            formErp::hidden([
                'id_polo' => $id_polo,
                'periodo' => $periodo,
                'id_as' => $id_as,
                'diaSem' => $diaSem,
                'id_inst_' => $id_inst_
            ])
            ?>
            <input type="hidden" name="id_ma" id="maid" value="" />
            <input type="hidden" name="id_mc" id="id_mc" value="" />
        </form>
        <?php
        toolErp::modalInicio();
        ?>
        <iframe style="width: 100%; border: none; height: 80vh" name="frame"></iframe>
            <?php
            toolErp::modalFim();
            ?>
        <script>
            function matr(id, mc) {
                if (id) {
                    maid.value = id;
                    id_mc.value = mc;
                } else {
                    maid.value = '';
                    id_mc.value = '';
                }
                formMatr.submit();
                $('#myModal').modal('show');
                $('.form-class').val('');
            }
        </script>
        <?php
    }