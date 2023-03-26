<?php
unset($_POST['lanc2']);
tool::modalInicio('width: 60%');
$sql = "select n_pessoa from ge_funcionario f "
        . "join pessoa p on p.id_pessoa = f.fk_id_pessoa "
        . "where f.rm = '" . @$_POST['rm'] . "'";
$query = $model->db->query($sql);
$profe = $query->fetch()['n_pessoa'];
$lancou = sql::get('pessoa', 'n_pessoa', ['id_pessoa' => @$_POST['fk_id_pessoa']], 'fetch')['n_pessoa'];
?>
<table class="table table-bordered table-hover table-striped">
    <tr>
        <td>
            Professor
        </td>
        <td>
            <?php echo @$profe ?> (RM:<?php echo @$_POST['rm'] ?>)
        </td>
    </tr>
    <tr>
        <td>
            Período
        </td>
        <td>
            <?php echo @$per_[$_POST['periodo']] ?>
        </td>
    </tr>
    <tr>
        <td>
            Cargo
        </td>
        <td>
            <?php echo @$_POST['n_cargo'] ?>
        </td>
    </tr>
    <tr>
        <td>
            CADAMPE
        </td>
        <td>
            <?php echo @$_POST['n_pessoa'] ?> (PMB: <?php echo @$_POST['cad_pmb'] ?>)
        </td>
    </tr>
    <tr>
        <td>
            Turma(s)
        </td>
        <td>
            <?php echo @$_POST['turmas'] ?>
        </td>
    </tr>
    <tr>
        <td>
            Horas/Aula
        </td>
        <td>
            <?php echo @$_POST['horas'] ?>
        </td>
    </tr>
    <tr>
        <td>
            Data
        </td>
        <td>
            <?php echo @$_POST['dia'] . '/' . @$_POST['mes'] . '/' . @$_POST['ano'] ?>
        </td>
    </tr>
    <tr>
        <td>
            Data e hora do lançamento
        </td>
        <td>
            <?php
            echo data::converteBr(substr(@$_POST['dt_fr'], 0, 10)) . ' as ' . substr(@$_POST['dt_fr'], 11) . ' horas'
            ?>
        </td>
    </tr>
    <tr>
        <td>
            Quem Lançou
        </td>
        <td>
            <?php echo @$lancou ?>
        </td>
    </tr>
    <tr>
        <td>
            Motivo
        </td>
        <td>
            <form method="POST">
                <?php
                echo DB::hiddenKey('cadam_freq', 'replace');
                echo formulario::hidden($_POST);
                ?>
                <input type="hidden" name="1[id_fr]" value="<?php echo @$_POST['id_fr'] ?>" />
                <select name="1[fk_id_mot]">
                    <?php
                    foreach (sql::idNome('cadam_motivo') as $k => $v) {
                        ?>
                    <option <?php echo @$k == $_POST['fk_id_mot']?'selected':'' ?> value="<?php echo $k ?>"><?php echo $v ?></option>
                        <?php
                    }
                    ?>
                </select>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input class="btn btn-success" type="submit" value="Salvar" />
            </form>
        </td>
    </tr>

</table>
<?php
tool::modalFim();
?>