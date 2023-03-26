<?php

ini_set('memory_limit', '2000M');

$sql = "SELECT logradouro,num, complemento, ta.fk_id_pessoa FROM endereco end "
        . "join ge_turma_aluno ta on ta.fk_id_pessoa = end.fk_id_pessoa "
        . "WHERE `fk_id_tp` = 1 "
        . "and complemento  NOT LIKE '%ap%' "
        . "and complemento  NOT LIKE '%tor%' "
        . "and complemento  NOT LIKE '%casa%' "
        . "and ta.situacao like 'Frequente' "
        . "and logradouro != ''";
$query = $model->db->query($sql);
$array = $query->fetchAll();
foreach ($array as $v) {
    @$teste[$v['logradouro'] . ', ' . $v['num']] ++;
    if (@$teste[$v['logradouro'] . ', ' . $v['num']] > 3) {
        @$teste1['logr'][$v['logradouro'] . ', ' . $v['num']] = @$teste[$v['logradouro'] . ', ' . $v['num']];
        @$teste1['id'][$v['logradouro'] . ', ' . $v['num']][] = $v['fk_id_pessoa'];
    }
}
?>
<div class="fieldBody">
    <div class="fieldTop">
        Controle de alunos por Moradia
    </div>
    <div class="row">
        <div class="col-md-4" style="height: 500; overflow: auto">
            <?php
            arsort($teste1['logr']);
            foreach ($teste1['logr'] as $k => $v) {
                ?>
            <form action="<?php echo HOME_URI ?>/info/lotacaoaluno" target="ms" method="POST">
                <input type="hidden" name="id_pessoa" value="<?php echo implode(', ', $teste1['id'][$k]) ?>" />
                    <input style="width: 100%; text-align: left" class="btn btn-success" type="submit" value="<?php echo $k.' - '.$v.' Alunos' ?>" />
                </form>
            <br />
                <?php
            }
            ?>
        </div>
        <div class="col-md-8">
            <iframe style="width: 100%; height: 500px" name="ms"></iframe>
        </div>
    </div>
</div>