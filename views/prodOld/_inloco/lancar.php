<?php
$hidden = $dados = $_POST;
unset($hidden['lancaNotas']);
$in = sql::get('prod1_visita', '*', ['rm' => $hidden['rm'], 'aval' => $hidden['aval']], 'fetch');

if (!empty($in)) {
    $dados['id_pv'] = $in['id_pv'];
    $dados['rm'] = $in['rm'];
    $dados['id_inst'] = $in['fk_id_inst'];
    $dados['id_turma'] = $in['fk_id_turma'];
    $dados['fk_id_pa'] = $in['fk_id_pa'];
    $dados['id_pessoa'] = $in['fk_id_pessoa'];
    $dados['aval'] = $in['aval'];
    $dados['iddisc'] = $in['iddisc'];

    $visita = sql::get('prod1_visita', '*', ['id_pv' => $in['id_pv']], 'fetch');
}
$id_inst = empty($_POST['fk_id_inst']) ? $dados['id_inst'] : $_POST['fk_id_inst'];
$aval = sql::get('prod1_aval');
foreach ($aval as $v) {
    $tipoAvalOpt[$v['id_pa']] = $v['n_pa'];
    $ci = explode('|', $v['ciclos']);
    foreach ($ci as $c) {
        $avalSet[$c . '_' . $v['iddisc']] = $v['id_pa'];
    }
}

$tipoAval = empty($dados['fk_id_pa']) ? @$avalSet[@$dados['fk_id_ciclo'] . '_' . @$dados['iddisc']] : @$dados['fk_id_pa'];
?>
<div class="fieldTop">
    <?php echo $dados['n_pessoa'] ?>
    <br /><br />
    <?php echo $dados['aval'] ?>º Avaliação
</div>
<br /><br />
<div class="row">
    <div class="col-sm-12">
        Disciplina/Função: <?php echo $dados['n_disc'] ?>
    </div>
</div>
<br />
<form method="POST">
    <?php echo form::hidden($hidden); ?>
    <div class="row">

        <div class="col-sm-3">
            <?php echo form::select('1[rm]', professores::rm($dados['id_pessoa']), 'Matrícula', $dados['rm']) ?> 
        </div>
        <div class="col-sm-5">
            <?php
            if ($dados['fk_id_ciclo'] == 'adi') {
                echo form::select('1[fk_id_inst]', escolas::idInst(), 'Escola', $id_inst);
            } else {
                echo 'Escola: ' . $dados['n_inst'];
                echo form::hidden(['1[fk_id_inst]' => $id_inst]);
            }
            ?>
        </div>
        <div class="col-sm-4">
            <?php
            if (!empty($dados['id_turma'])) {
                echo 'Classe:' . $dados['n_turma'];
                echo form::hidden(['1[fk_id_turma]' => $dados['id_turma']]);
            }
            ?>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-6">
            <?php echo form::select('1[fk_id_pa]', @$tipoAvalOpt, 'Tipo de Avaliação', @$tipoAval) ?>
        </div>
        <div class="col-sm-2">
            <table class="table table-bordered">
                <tr>
                    <td>
                        Nota
                    </td>
                    <td>
                        <?php echo str_replace('.', ',', @$visita['nota']) ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-sm-4">
            <?php
            $avaliadores = $model->avaliadores();
            echo form::select('1[avaliador_id_pessoa]', $avaliadores, 'Avaliador', empty($visita['avaliador_id_pessoa']) ? tool::id_pessoa() : $visita['avaliador_id_pessoa']);
            $revisores = $model->revisoresIdPessoa();
            
            if(in_array(tool::id_pessoa(), $revisores)){
                echo form::hidden(['1[revisor_id_pessoa]'=> tool::id_pessoa()]);
            }
            ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 text-center">
            <?php
            $hidden1 = [
                '1[aval]' => $dados['aval'],
                '1[fk_id_pessoa]' => $dados['id_pessoa'],
                '1[iddisc]' => @$dados['iddisc'],
                '1[id_pv]' => @$dados['id_pv'],
                'continuar' => 1
            ];
            echo DB::hiddenKey('prod1_visita', 'replace');
            echo form::hidden($hidden1);
            echo form::button('Continuar')
            ?>
        </div>
    </div>
</form>


<?php
if (!empty($_POST['continuar']) || !empty($visita['avaliador_id_pessoa'])) {
    include ABSPATH . '/views/prod/_inloco/aval.php';
}