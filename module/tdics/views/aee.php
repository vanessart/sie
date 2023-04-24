<?php
if (!defined('ABSPATH'))
    exit;
$sql = "SELECT "
        . " distinct tt.n_turma as n_turma_tdics, p.id_pessoa, p.n_pessoa, t.n_turma, i.n_inst, po.n_polo, np.n_porte "
        . " FROM tdics_turma_aluno tat "
        . " JOIN tdics_turma tt on tt.id_turma = tat.fk_id_turma "
        . " JOIN tdics_polo po on po.id_polo = tt.fk_id_polo "
        . " JOIN pessoa p on p.id_pessoa = tat.fk_id_pessoa "
        . " JOIN ge_aluno_necessidades_especiais ne on ne.fk_id_pessoa = tat.fk_id_pessoa "
        . " JOIN ge_aluno_necessidades_especiais_porte np on np.id_porte = ne.fk_id_porte "
        . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = tat.fk_id_pessoa AND ta.fk_id_tas = 0 "
        . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma AND t.fk_id_ciclo not in (32) "
        . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
        . " JOIN instancia i on i.id_inst = t.fk_id_inst "
        . " order by p.n_pessoa ";
$query = pdoSis::getInstance()->query($sql);
$alunos = $query->fetchAll(PDO::FETCH_ASSOC);
if ($alunos) {
    $ids = array_column($alunos, 'id_pessoa');
    $sql = "SELECT ane.fk_id_pessoa, ne.n_ne FROM ge_aluno_necessidades_especiais ane "
            . " join  ge_necessidades_especiais ne on ne.id_ne = ane.fk_id_ne "
            . " WHERE `fk_id_pessoa` IN (" . implode(', ', $ids) . ") ";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach ($array as $v) {
        $def[$v['fk_id_pessoa']][]=$v['n_ne'];
    }
    
    if ($alunos) {
        foreach ($alunos as $k => $v){
            $alunos[$k]['def']= implode(', ', $def[$v['id_pessoa']]);
        }
        $form['array'] = $alunos;
        $form['fields'] = [
            'Matrícula' => 'id_pessoa',
            'Nome' => 'n_pessoa',
            'Núcleo' => 'n_polo',
            'Turma TDICS' => 'n_turma_tdics',
            'Escola de Origem' => 'n_inst',
            'Turma de Origem' => 'n_turma',
            'Deficiência' => 'def',
            'Porte' => 'n_porte'
        ];
    }
}
?>
<div class="body">
    <div class="fieldTop">
        Alunos AEE
    </div>
    <?php
    if (!empty($form)) {
        report::simple($form);
    }
    ?>
</div>

