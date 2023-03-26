<?php
$sql = "SELECT SUM(`nota`) as soma, count(nota) as total FROM `global_nota_turma` ";
$query = $model->db->query($sql);
$a = $query->fetch();
$nota = $a['soma'] / $a['total'];
?>

<div class="fieldBody">
    <div class="fieldTop">
        Funcionarios Alocados na Secretaria de Educação
    </div>
    <br /><br />
    <?php
    $sql = "SELECT FUNCIONARIO, MATRICULA, FUNCAO FROM funcionarios f "
            . "left join fuc_escola fe on fe.SUB_SECAO_TRABALHO = f.SUB_SECAO_TRABALHO "
            . "left join ge_escolas e on e.cie_escola = fe.cie "
            . " WHERE fe.cie = 1 "
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
            . " OR `FUNCAO` LIKE '%prof%' "
            . ")"
            . " AND SIT_ATUAL LIKE '001%' "
            . " ORDER BY f.`FUNCIONARIO` ASC ";
    $query = $model->db->query($sql);
    $func = $query->fetchAll();
    ?>
    <table style="width: 100%" class="table table-bordered table-hover table-striped">
        <thead>
            <tr style="background-color: black; color: white; font-weight: bold">
                <th>
                    Matrícula
                </th>
                <th>
                    Nome
                </th>
                <th>
                    Função
                </th>
                <td>
                    Nota
                </td>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($func as $v){
                ?>
            <tr>
                <td>
                    <?php echo $v['MATRICULA'] ?>
                </td>
                 <td>
                    <?php echo $v['FUNCIONARIO'] ?>
                </td>
                 <td>
                    <?php echo $v['FUNCAO'] ?>
                </td>
                 <td>
                    <?php echo str_replace(".", ",", round($nota,1)) ?>
                </td>
            </tr>
                                <?php
            }                        
                                    ?>
        </tbody>
    </table>
</div>