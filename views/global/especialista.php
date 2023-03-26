<?php
$id_disc = @$_POST['id_disc'];
if (!empty($_POST['id_rm'])) {

    foreach ($_POST['id_rm'] as $k => $v) {
        if (!empty($v)) {
            $sql = "INSERT INTO `global_extra` (`id_rm`, `nota`, `id_disc`) VALUES ('$k', '$v', '$id_disc');";
            $query = $model->db->query($sql);
        }
    }
    tool::alert("Lançamento Realizado");
}
?>
<div class="fieldBody">
    <div class="fieldTop">
        Lançamento de Especialistas (Inglês, Ed. Física)
    </div>
    <br /><br />
    <div style="font-size: 20px; color: red">
        Atenção! Esta página só poderá ser acessada por um usuário de cada vez. 
    </div>
    <br /><br />
    <div style="width: 400px">
        <?php formulario::select('id_disc', [11 => 'Educação Física', 15 => 'L.E.Inglês', 28 => 'AEE'], 'Disciplina', $id_disc, 1) ?>
    </div>
    <br /><br />
    <?php
    if (!empty($id_disc)) {

        $sql = "SELECT "
                . " distinct f.rm, p.n_pessoa, n_disc "
                . " FROM `ge_aloca_prof` ap "
                . " join ge_turmas t on t.id_turma = ap.fk_id_turma "
                . " join ge_disciplinas d on d.id_disc = ap.iddisc "
                . " join ge_funcionario f on f.rm = ap.rm "
                . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " WHERE  t.fk_id_ciclo in (1, 2, 3, 32) "
                . " and ap.iddisc = $id_disc "
                . " order by p.n_pessoa ";
        $query = $model->db->query($sql);
        $array = $query->fetchAll();
        $sql = "SELECT * FROM `global_extra` ";
        $query = $model->db->query($sql);
        $l = $query->fetchAll();
        foreach ($l as $v) {
            $lanc[$v['id_rm']] = $v['nota'];
        }
        ?>

        <form method="POST">
            <table border="1" style="width: 100%">
                <tr>
                    <td>
                        <table class="table table-bordered table-hover table-responsive table-striped" style=" font-weight: bold">
                            <tr>
                                <th>
                                    Nome
                                </th>
                                <th>
                                    Matrícula
                                </th>
                                <th>
                                    Disciplina
                                </th>
                                <th style="width: 60px">
                                    Nota
                                </th>
                            </tr> 
                            <?php
                            $t = count($array);
                            $cont = 0;
                            foreach ($array as $v) {
                                if ($cont % ($t / 2) == 0 && $cont <> 0 && empty($pula)) {
                                    $pula = 1;
                                    ?>
                                </table>
                            </td>
                            <td>
                                <table class="table table-bordered table-hover table-responsive table-striped" style=" font-weight: bold">
                                    <tr>
                                        <th>
                                            Nome
                                        </th>
                                        <th>
                                            Matrícula
                                        </th>
                                        <th>
                                            Disciplina
                                        </th>
                                        <th style="width: 60px">
                                            Nota
                                        </th>
                                    </tr>                             <?php
                                }
                                $cont++;
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $v['n_pessoa'] ?>
                                    </td>
                                    <td>
                                        <?php echo $v['rm'] ?>
                                    </td>
                                    <td>
                                        <?php echo $v['n_disc'] ?>
                                    </td>
                                    <td>
                                        <input type="text" name="id_rm[<?php echo $v['rm'] ?>]" value="<?php echo @$lanc[$v['rm']] ?>" />
                                    </td>
                                </tr> 
                                <?php
                            }
                            ?>
                        </table>
                    </td>
                </tr>
            </table>
            <br /><br />
            <div style="text-align: center">
                <input type="hidden" name="id_disc" value="<?php echo $id_disc ?>" />
                <input type="submit" name="extra" value="Salvar" />
            </div>
        </form>
    </div>
    <?php
}
?>