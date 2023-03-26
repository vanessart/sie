<?php
for ($c = 0; $c <= 10; $c += 0.1) {
    @$valores .= "'" . $c . "', ";
    $valores .= "'" . str_replace(".", ",", $c) . "', ";
}
for ($c = 0; $c <= 10; $c++) {
    @$valores .= "'" . $c . ".0', ";
    @$valores .= "'" . $c . ",0', ";
}
$valores = substr($valores, 0, -2);
$id_inst = tool::id_inst();
$sql = " SELECT distinct FUNCIONARIO, MATRICULA FROM funcionarios f "
        . " left join fuc_escola fe on fe.SUB_SECAO_TRABALHO = f.SUB_SECAO_TRABALHO "
        . " left join ge_escolas e on e.cie_escola = fe.cie "
        . " WHERE SIT_ATUAL LIKE '001%' "
        . " and "
        . "(FUNCAO LIKE '%AGENTE DE DESENVOLVIMENTO INFANTI%' "
        . "or FUNCAO LIKE '%ASSISTENTE DE MATERNAL%' )"
        . " and e.fk_id_inst = " . $id_inst
        . " ORDER BY f.`FUNCIONARIO` ASC ";
$query = $model->db->query($sql);
$func = $query->fetchAll();

$sql = "SELECT * FROM `global_nota_adi` WHERE `fk_id_inst` = " . $id_inst;
$query = $model->db->query($sql);
$n = $query->fetchAll();
foreach ($n as $v) {
    $dados[$v['id_rm']] = $v['nota'];
}
?>
<div class="fieldBody">
    <div class="fieldTop">
        Avaliação das AGENTE DE DESENVOLVIMENTO INFANTIL
    </div>
    <br /><br />
    <div class="alert alert-warning" style="font-size: 16px; text-align: left">
        Valores permitidos: valores de 0 a 10 com no máximo uma casa decimal.
    </div>
    <br /><br />

    <div class="row">
        <form method="POST">
            <div class="col-sm-7">
                <table  class="table table-bordered table-hover table-striped">
                    <thead>
                    <th>
                        Matrícula
                    </th>
                    <th>
                        Nome
                    </th>
                    <th style="width: 60px">
                        Nota
                    </th>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($func as $v) {
                            ?>
                            <tr>
                                <td>
                                    <?php echo $v['MATRICULA'] ?>
                                </td>
                                <td>
                                    <?php echo $v['FUNCIONARIO'] ?>
                                </td>
                                <td>
                                    <input type="hidden" name="nome[<?php echo $v['MATRICULA'] ?>]" value="<?php echo $v['FUNCIONARIO'] ?>" />
                                    <input onblur="confMensao(this)" type="text" name="nota[<?php echo $v['MATRICULA'] ?>]" value="<?php echo str_replace(".", ",", @$dados[$v['MATRICULA']]) ?>" />
                                </td>
                            </tr>
                            <?php
                            if ((@$conte++) % 10 == 0 && !empty($testte)) {
                               
                                ?>
                            </tbody>
                        </table>
                        <div class="text-center">
                            <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                            <?php echo DB::hiddenKey('adi') ?>
                            <input class="btn btn-success" type="submit" value="Salvar antes de continuar" />
                        <br /><br />
                        </div>
                    </div>
                </form>
                <form method="POST">
                    <div class="col-sm-7">
                        <table  class="table table-bordered table-hover table-striped">
                            <thead>
                            <th>
                                Matrícula
                            </th>
                            <th>
                                Nome
                            </th>
                            <th style="width: 60px">
                                Nota
                            </th>
                            </thead>
                            <tbody>
                                <?php
                            }
                             $testte=1;
                        }
                        ?>
                    </tbody>
                </table>
                <div class="text-center">
                    <input type="hidden" name="id_inst" value="<?php echo $id_inst ?>" />
                    <?php echo DB::hiddenKey('adi') ?>
                    <input class="btn btn-success" type="submit" value="Salvar antes de continuar" />
                </div>
            </div>
        </form>

    </div>

</div>
<script>
    function confMensao(id) {
        var v = [<?php echo $valores ?>];
        var valor = id.value;
        var confere = null;
        var i;
        for (i = 0; i < v.length; i++) {
            if (v[i] == valor)
            {
                confere = 1;
            }
        }
        if (confere !== 1 && valor !== '') {
            alert("Valor inválido");
            id.style.backgroundColor = "red";
        } else {
            id.style.backgroundColor = "white";
        }
    }
</script>