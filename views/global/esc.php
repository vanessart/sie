<?php
ini_set('memory_limit', '1000M');
?>
<div class="fieldBody">
    <div class="row">
        <div class="col-lg-8">
            NOTAS das ESCOLAS obtida pela média aritmética das CLASSES
        </div>
        <div class="col-lg-4 text-center">
            <form method="POST">
                <input class="btn btn-info" name="<?php echo empty($_POST['det']) ? 'det' : '' ?>" type="submit" value="<?php echo empty($_POST['det']) ? 'Visão Detalhada' : 'Visão Resumida' ?>" />
            </form>
        </div>
    </div>
    <br /><br />
    <?php
    $esc = escolas::idInst();
    $sql = "select * from global_nota_turma ";
    $query = $model->db->query($sql);
    $array = $query->fetchAll();
    foreach ($array as $v) {
        $notaTurma[$v['fk_id_turma']] = $v['nota'];
        @$soma[$v['fk_id_inst']] += $v['nota'];
        @$conta[$v['fk_id_inst']] ++;
    }
    foreach ($soma as $k => $v) {
        $result[$k] = $v / $conta[$k];
    }
    ?>
    <table style="width: 100%" class="table table-bordered table-hover table-striped">
        <?php
        foreach ($esc as $k => $v) {
            $model->db->replace('global_nota_esc',['fk_id_inst'=>$k, 'nota'=> round(@$result[$k],1),'escola'=>$v]);
            ?>
            <tr style="font-weight: bold;<?php echo empty($_POST['det']) ? '' : ' background-color: black; color: white' ?>">
                <td colspan="3">
                    <?php echo $v ?>
                </td>
                <td>
                    Nota da Escola: <?php echo str_replace(".", ",", round(@$result[$k],1))  ?>
                </td>
            </tr>
            <?php
            if (!empty($_POST['det'])) {
                ?>
                <tr>
                    <td>
                        Segmento
                    </td>
                    <td>
                        Classe
                    </td>
                    <td>
                        Código
                    </td>
                    <td>
                        Nota por Classe
                    </td>
                </tr>
                <?php
                $cla = turmas::Listar(NULL, NULL, NULL, NULL, NULL, $k);
                if (!empty($cla)) {
                    foreach ($cla as $cl) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $cl['n_curso'] ?>
                            </td>
                            <td>
                                <?php echo $cl['n_turma'] ?>
                            </td>
                            <td>
                                <?php echo $cl['codigo'] ?>
                            </td>
                            <td>
                                <?php echo str_replace(".", ",", round(@$notaTurma[$cl['id_turma']],1))  ?>
                            </td>
                        </tr>
                        <?php
                    }
                }
            }
        }
        ?>
    </table>
</div>

