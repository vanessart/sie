<?php
$sql = "SELECT * FROM instancia_predio p "
        . "JOIN salas s on s.fk_id_predio = p.id_ip "
        . " join tipo_sala t on t.id_ts = s.fk_id_ts "
        . "WHERE p.fk_id_inst = " . tool::id_inst();
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
ob_start();
?>
<style>
    td{
        padding: 3px;
    }
</style>
<br /><br />
<div style="text-align: center; font-size: 20px">
    Espaços Físicos
</div>
<br /><br />
<table border=1 cellspacing=0 cellpadding=1 style="width: 100%">
    <tr>
        <td>
            Sala
        </td>
        <td>
            Finalidade
        </td>
        <td>
            Piso
        </td>
        <td>
            Acessibilidade
        </td>
        <td>
            Capacidade Física
        </td>
        <td>
            Razao Aluno/m²
        </td>
        <td>
            Metragem
        </td>
    </tr>
    <?php
    foreach ($array as $v) {
        @$v['tm'] = round(($v[largura] * $v['comprimento']), 2) . ' m²';
        @$v['razao'] = round((@$v['tm'] / $v['alunos_sala']), 2) . ' m²';
        @$v['cadeirante'] = tool::simnao($v['cadeirante']);
        ?>
        <tr>
            <td>
                <?php echo $v['n_sala'] ?>
            </td>
            <td>
                <?php echo $v['n_ts'] ?>
            </td>
            <td>
                <?php echo $v['piso'] ?>
            </td>
            <td>
                <?php echo $v['cadeirante'] ?>
            </td>
            <td>
                <?php echo $v['alunos_sala'] ?>
            </td>
            <td>
                <?php echo $v['razao'] ?>
            </td>
            <td>
                <?php echo $v['tm'] ?>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
<br /><br />
<br /><br />
<br /><br />
<div style="text-align: right">
    _________________________________
    <br /><br />
    Diretor Da Unidade Escolar
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
<?php
tool::pdfEscola('P');

