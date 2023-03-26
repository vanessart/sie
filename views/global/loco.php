<?php
$sql = "SELECT DISTINCT i.id_inst, i.n_inst FROM instancia i "
        . " JOIN ge_turmas t on t.fk_id_inst = i.id_inst "
        . " WHERE t.fk_id_ciclo in (1, 2, 3, 19, 20, 21, 22, 23, 24, 31) ORDER BY i.n_inst ";
$query = $model->db->query($sql);
$array = $query->fetchAll();
foreach ($array as $v) {
    $esc[$v['id_inst']] = $v['n_inst'];
}
?>
<div class="fieldBody">
    <div class="fieldTop">
        Lançamento de nota Classe/Professor
    </div>
    <br /><br />
    <div class="row">
        <div class="col-lg-6">
            <?php formulario::select('id_inst', $esc, 'Escola', @$_POST['id_inst'], 1) ?>
        </div>
    </div>
    <?php
    if (!empty($_POST['id_inst'])) {
        $d = sql::get('global_nota_turma', '*', ['fk_id_inst'=>$_POST['id_inst']]);
        foreach ($d as $v){
            $dados[$v['fk_id_turma'].'_'.$v['rm']]=$v;
        }
        
        $sql = "SELECT distinct"
                . " t.id_turma, t.n_turma, p.n_pessoa, f.rm "
                . " FROM ge_turmas t "
                . " JOIN ge_aloca_prof ap on ap.fk_id_turma = t.id_turma "
                . " JOIN ge_funcionario f on f.rm = ap.rm "
                . " JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " WHERE t.fk_id_ciclo in (1, 2, 3, 19, 20, 21, 22, 23, 24, 31) "
                . " AND ("
                . " ap.iddisc LIKE 'nc' "
                . " OR ap.iddisc = 27 "
                . ")"
                . " AND t.fk_id_inst = " . $_POST['id_inst']
                . " ORDER BY t.fk_id_ciclo, t.letra";
        $query = $model->db->query($sql);
        $classes = $query->fetchAll();
        
        for ($c = 0; $c <= 10; $c += 0.1) {
            @$valores .= "'" . $c . "', ";
            $valores .= "'" . str_replace(".", ",", $c) . "', ";
        }
        for ($c = 0; $c <= 10; $c++) {
            @$valores .= "'" . $c . ".0', ";
            @$valores .= "'" . $c . ",0', ";
        }
        $valores = substr($valores, 0, -2);
        ?>
        <div class="alert alert-warning" style="font-size: 16px; text-align: left">
            Valores permitidos: valores de 0 a 10 com no máximo uma casa decimal.
        </div>
        <form method="POST">
            <table class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>
                            Classe
                        </th>
                        <th>
                            Matrícula
                        </th>
                        <th>
                            Professor Polivalente
                        </th>
                        <th style="width: 60px">
                            Nota
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($classes as $v) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $v['n_turma'] ?>
                            </td>
                            <td>
                                <?php echo $v['rm'] ?>
                            </td>
                            <td>
                                <?php echo $v['n_pessoa'] ?>
                            </td>
                            <td>
                                <input type="hidden" name="id_nt[<?php echo $v['id_turma'].'_'.$v['rm'] ?>]" value="<?php echo @$dados[$v['id_turma'].'_'.$v['rm']]['id_nt'] ?>" />
                                <input type="hidden" name="rm[<?php echo $v['id_turma'].'_'.$v['rm'] ?>]" value="<?php echo $v['rm'] ?>" />
                                <input type="hidden" name="id_turma[<?php echo $v['id_turma'].'_'.$v['rm'] ?>]" value="<?php echo $v['id_turma'] ?>" />
                                <input onblur="confMensao(this)" type="text" name="nota[<?php echo $v['id_turma'].'_'.$v['rm'] ?>]" value="<?php echo str_replace(".", ",", @$dados[$v['id_turma'].'_'.$v['rm']]['nota']) ?>" />
                            </td>
                        </tr>
                        <?php
                    }
                    ?>

                </tbody>
            </table>
            <div class="text-center">
                <input type="hidden" name="id_inst" value="<?php echo $_POST['id_inst'] ?>" />
                <?php echo DB::hiddenKey('notaClasse') ?>
                <input class="btn btn-success" type="submit" value="Salvar" />
            </div>
        </form>
        <?php
    }
    ?>
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