<?php
if (!defined('ABSPATH'))
    exit;

$ciclos = '4,5,6,7,8,9, 31';

$id_agrup = 54;

$id_turma = @$_POST['id_turma'];

$id_pl = gtMain::periodoSet(@$_POST['periodoLetivo']);

$turmas = gtTurmas::idNome(tool::id_inst(), $id_pl, $ciclos);

$periodos = gtMain::periodos(1);
?>
<div class="body">
    <div class="fieldTop">
        Folha Ótica Avulsa
        <br />
       Avaliação Externa 2022	
    </div>
    <div class="row">
        <div class="col-sm-3">
            <?php
            echo formErp::select('periodoLetivo', $periodos, 'Período Letivo', @$id_pl, 1);
            ?>
        </div>
        <div class="col-sm-3" style="text-align: center; font-size: 18px">

            <?php
            if ($turmas) {
                ?>
                <?php echo formErp::select('id_turma', $turmas, 'Classe', @$_POST['id_turma'], 1, ['periodoLetivo' => $id_pl]) ?>
                <?php
            } else {
                echo 'Esta escola não tem folha ótica para este período Letivo';
            }
            ?>
        </div>
    </div>
    <br /><br />
    <?php
    if ($id_turma) {
        $turmaDados = sql::get('ge_turmas', 'fk_id_ciclo, codigo', ['id_turma' => $id_turma], 'fetch');
        $id_ciclo = $turmaDados['fk_id_ciclo'];
        $codigo = $turmaDados['codigo'];
        $turma = turmas::classe($id_turma);
        foreach ($turma as $k => $v) {
            $turma[$k]['pdf'] = formErp::submit('Gerar Folha Ótica', null, ['id_grup' => $id_agrup, 'id_pessoa' => $v['id_pessoa'], 'codigo' => $codigo, 'n_pessoa' => $v['n_pessoa'], 'n_inst' => toolErp::n_inst(), 'chamada' => $v['chamada'], 'id_inst' => toolErp::id_inst(), 'id_ciclo' => $id_ciclo, 'id_turma' => $id_turma], 'https://erpeduc.com.br/ass/omr/folhaPdfInd', 1);
        }
        $form['array'] = $turma;
        $form['fields'] = [
            'Chamada' => 'chamada',
            'Nome' => 'n_pessoa',
            'RSE' => 'id_pessoa',
            'Situação' => 'situacao',
            '||1' => 'pdf'
        ];
        report::simple($form);
    }
    ?>
    <form action="https://erpeduc.com.br/ass/omr/folhaPdfInd" method="POST">
        <input type="hidden" name="id_grup" value="<?= $id_agrup ?>" />
    </form>
</div>