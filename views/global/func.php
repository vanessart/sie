<?php
$sql = "SELECT FUNCIONARIO, MATRICULA, FUNCAO, f.SUB_SECAO_TRABALHO, e.fk_id_inst FROM funcionarios f "
        . "left join fuc_escola fe on fe.SUB_SECAO_TRABALHO = f.SUB_SECAO_TRABALHO "
        . "left join ge_escolas e on e.cie_escola = fe.cie "
        . " WHERE fe.cie != 1 "
        . " and ("
        . "`FUNCAO` LIKE '%AGENTE DE DESENVOLVIMENTO INFANTI%' "
        . " OR `FUNCAO` LIKE '%AUXILIAR DE SERVICOS%' "
        . " OR `FUNCAO` LIKE '%ASSISTENTE DE MATERNAL%' "
        . " OR `FUNCAO` LIKE '%INSPETOR DE ALUNOS%' "
        . " OR `FUNCAO` LIKE '%AUXILIAR DE CLASSE%' "
        . " OR `FUNCAO` LIKE '%COORDENADOR%' "
        . " OR `FUNCAO` LIKE '%INSTRUTOR%' "
        . " OR `FUNCAO` LIKE '%INSPETOR %' "
        . " OR `FUNCAO` LIKE '%TRADUTOR%' "
        . " OR `FUNCAO` LIKE '%DIRETOR DE UNIDADE%' "
        . ")"
        . " AND SIT_ATUAL LIKE '001%' "
        . " ORDER BY f.SUB_SECAO_TRABALHO, f.`FUNCIONARIO` ASC ";
$query = $model->db->query($sql);
$func = $query->fetchAll();

$e = sql::get('global_nota_esc');
foreach ($e as $v) {
    $nota[$v['fk_id_inst']] = $v['nota'];
}

$sql = "SELECT * FROM `global_nota_adi` ";
$query = $model->db->query($sql);
$n = $query->fetchAll();
foreach ($n as $v) {
    $adi[$v['id_rm']] = $v['nota'];
}
?>
<div class="fieldBody">
    <div class="fieldTop">
        NOTAS dos Funcionários obtidas pelas médias aritméticas das CLASSES das respectivas ESCOLAS
    </div>
    <br /><br />
    <table style="width: 100%" class="table table-bordered table-hover table-striped">
        <thead>
            <tr style="font-weight: bold;<?php echo empty($_POST['det']) ? ' background-color: black; color: white' : '' ?>">
                <th>
                    Escola
                </th>
                <th>
                    Matricula
                </th>
                <th>
                    Nome
                </th>
                <th>
                    Função
                </th>
                <th>
                    N. Escola
                </th>
                 <th>
                    N. Individual
                </th>
                 <th>
                    Nota
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($func as $v) {
                if (!empty($v['fk_id_inst'])) {
                    ?>
                    <tr style="font-weight: bold;<?php echo empty($_POST['det']) ? '' : ' background-color: black; color: white' ?>">

                        <th>
                            <?php echo $v['SUB_SECAO_TRABALHO'] ?>
                        </th>
                        <th>
                            <?php echo $v['MATRICULA'] ?>
                        </th>
                        <td>
                            <?php echo $v['FUNCIONARIO'] ?>
                        </td>
                        <th>
                            <?php echo $v['FUNCAO'] ?>
                        </th>
                        <th>
                            <?php echo @round($nota[$v['fk_id_inst']], 1) ?>
                        </th>
                         <th>
                            <?php echo @$adi[$v['MATRICULA']] ?>
                        </th>
                        <th>
                            <?php
                            $nnota = NULL;
                            if (!empty($adi[$v['MATRICULA']])) {
                                if (empty($adi[$v['MATRICULA']])) {
                                    echo $nnota = round($nota[$v['fk_id_inst']], 1);
                                } else {
                                    echo $nnota = round(((@$nota[$v['fk_id_inst']] + $adi[$v['MATRICULA']]) / 2), 1);
                                }
                            }
                            ?>
                        </th>
                    </tr>
                    <?php
                    $model->db->replace('global_nota_func', ['escola' => @$v['SUB_SECAO_TRABALHO'], 'id_rm' => @$v['MATRICULA'], 'nome' => @$v['FUNCIONARIO'], 'nota' => $nnota]);
                }
            }
            ?>
        </tbody>
    </table>
</div>
