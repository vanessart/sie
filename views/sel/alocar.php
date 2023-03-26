<?php
if (!empty($_POST['alocar'])) {

    $sql = "SELECT id_predio, n_predio FROM `sel_predio` "
            . " WHERE `id_predio` in (" . implode(',', $_POST['id_predio']) . ") "
            . " order by ordem ";
    $query = $model->db->query($sql);
    $predios = $query->fetchAll();

    if (!empty($_POST['cargoSet'])) {
        $cargoSet = "AND fk_id_cargo in (" . implode(",", $_POST['cargoSet']) . ")";
    }
    if (!empty($_POST['ini']) && !empty($_POST['fim'])) {
        foreach (range($_POST['ini'], $_POST['fim']) as $y) {
            @$letra .= " or n_insc like '$y%' ";
        }
        $letra = " and (" . substr($letra, 3) . ') ';
    }
    $sql = "SELECT id_inscr FROM `sel_inscricacao` WHERE 1 "
            . @$cargoSet
            . @$letra
            . " and n_predio = '' "
            . " order by n_insc "
            . "limit 0, " . @$_POST['total'];
    $query = $model->db->query($sql);
    $array = $query->fetchAll();

    $pred = 0;

    $id_predio = $predios[$pred]['id_predio'];
    $n_predio = empty($_POST['n_predio'][$id_predio]) ? $predios[$pred]['n_predio'] : $_POST['n_predio'][$id_predio];
    $sala = empty($_POST['numero'][$id_predio]) ? 1 : $_POST['numero'][$id_predio];
    $totalSalas = @$_POST['sala'][$id_predio];
    $capacidade = @$_POST['capacidade'][$id_predio];

    $conta = 1;
    $contaSala = 1;
    foreach ($array as $v) {

        $sql = "UPDATE `sel_inscricacao` SET `fk_id_predio` = '$id_predio', `n_predio` = '$n_predio', `sala` = '$sala' WHERE `sel_inscricacao`.`id_inscr` = " . $v['id_inscr'];
        $query = $model->db->query($sql);
        if ($conta == $capacidade) {
            $conta = 1;
            if ($contaSala == $totalSalas) {
                if (!empty($predios[$pred + 1])) {
                    $pred++;
                    $id_predio = $predios[$pred]['id_predio'];
                    $n_predio = empty($_POST['n_predio'][$id_predio]) ? $predios[$pred]['n_predio'] : $_POST['n_predio'][$id_predio];
                    $sala = empty($_POST['numero'][$id_predio]) ? 1 : $_POST['numero'][$id_predio];
                    $totalSalas = @$_POST['sala'][$id_predio];
                    $capacidade = @$_POST['capacidade'][$id_predio];
                    $contaSala = 1;
                } else {
                    continue;
                }
            } else {
                $sala++;
                $contaSala++;
            }
        } else {
            $conta++;
        }
    }
    ####################### 
    $p = 0;
    $s = empty($_POST['sl']) ? 1 : $_POST['sl'];
    $c = 1;
    $conta = 1;
    foreach ($array as $v) {
        if (@$_POST['total'] >= $conta) {
            if ($c > @$_POST[2][$_POST[4][$p]]) {
                if (!empty($_POST['sl'])) {
                    $tt = (@$_POST[1][$_POST[4][$p]]) + @$_POST['sl'] - 1;
                } else {
                    $tt = @$_POST[1][$_POST[4][$p]];
                }
                if ($s == $tt) {
                    $p++;
                    $s = empty($_POST['sl']) ? 1 : $_POST['sl'];
                } else {
                    $s++;
                }
                $c = 1;
            }
            if (!empty($_POST[4][$p])) {

                $predio = $_POST[4][$p];
                $predioNome = empty($_POST['nomeEscola']) ? $preIn[$_POST[4][$p]] : $_POST['nomeEscola'];
                $sql = "UPDATE `sel_inscricacao` SET `fk_id_predio` = '$predio', `n_predio` = '$predioNome', `sala` = '$s' WHERE `sel_inscricacao`.`id_inscr` = " . $v['id_inscr'];
                $query = $model->db->query($sql);
                $c++;
            }
            $conta ++;
        }
    }
    #########################################################
}
foreach (range('A', 'Z') as $y) {
    $az[$y] = $y;
}
$sql = "SELECT * FROM `sel_seletiva` ";
$query = $model->db->query($sql);
$sel = $query->fetchAll();
foreach ($sel as $v) {
    $selOption[$v['id_sel']] = $v['n_sel'];
}
?>
<style>
    th{
        background-color: black;
        color: white;
    }
</style>
<div class="fieldBody">
    <div class="row">
        <div class="col-md-6">
            <?php
            formulario::select('id_sel', $selOption, 'Selecione o Processo Seletivo', @$_POST['id_sel'], 1);
            ?>  
        </div>
        <div class="col-md-3">
            <?php
            if (!empty($_POST['id_sel'])) {
                if (!empty($_POST['realoca'])) {
                    $sql = "UPDATE `sel_inscricacao` SET `fk_id_predio` = '0', `sala` = '', `n_predio` = ''  WHERE `fk_id_sel` = " . $_POST['id_sel'];
                    $query = $model->db->query($sql);
                }
                ?>
                <form method="POST">
                    <input type="hidden" name="id_sel" value="<?php echo $_POST['id_sel'] ?>" />
                    <input class="btn btn-danger" type="submit" name="realoca" value="Reiniciar Alocação" />
                </form>
                <?php
            }
            ?>
        </div>
        <div class="col-md-3">
            <form method="POST">
                <input type="hidden" name="id_sel" value="<?php echo $_POST['id_sel'] ?>" />
                <input type="submit" value="Limpar" />
            </form>
        </div>
    </div>

    <br /><br /><br />
    <?php
    if (!empty($_POST['id_sel'])) {
        $sql = "SELECT * FROM `sel_cargo` WHERE `fk_id_sel` = " . $_POST['id_sel'];
        $query = $model->db->query($sql);
        $car = $query->fetchAll();
        foreach ($car as $v) {
            $cargos[$v['id_cargo']] = $v['n_cargo'];
        }
        $sql = "SELECT * FROM `sel_inscricacao` "
                . " WHERE `fk_id_sel` =  " . $_POST['id_sel']
                . " and n_predio = '' ";
        $query = $model->db->query($sql);
        $i = $query->fetchAll();
        foreach ($i as $v) {
            @$total++;
            @$cargo[$v['fk_id_cargo']] ++;
        }
        ?>
        <div class="fieldTop">
            <?php echo intval(@$total) ?> Candidatos
        </div>
        <br /><br />
        <form method="POST">
            <?php
            if (!empty($cargos)) {
                ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>

                            </th>
                            <th>
                                Inscritos
                            </th>
                            <th>
                                Cargo
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($cargos as $k => $v) {
                            ?>
                            <tr>
                                <td>
                                    <input <?php echo!empty($_POST['cargoSet'][$k]) ? 'checked' : '' ?> type="checkbox" name="cargoSet[<?php echo $k ?>]" value="<?php echo $k ?>" />
                                </td>
                                <td>
                                    <?php echo intval(@$cargo[$k]) ?>
                                </td>
                                <td>
                                    <?php echo $v ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table> 
                <br /><br />
                <div class="row">
                    <div class="col-md-4">
                        Candidatos de 
                        <select name="ini">
                            <option></option>
                            <?php
                            foreach ($az as $v) {
                                ?>
                                <option <?php echo @$_POST['ini'] == $v ? 'selected' : '' ?>><?php echo $v ?></option>
                                <?php
                            }
                            ?>

                        </select>
                        a
                        <select name="fim">,
                            <option></option>
                            <?php
                            foreach ($az as $v) {
                                ?>
                                <option <?php echo @$_POST['ini'] == $v ? 'selected' : '' ?>><?php echo $v ?></option>
                                <?php
                            }
                            ?>

                        </select>
                    </div>
                </div>
                <br /><br />
                <table class="table table-striped" style="font-size: 16px">
                    <thead>
                    <th></th>
                    <th>
                        Prédio 
                    </th>
                    <th>
                        salas
                    </th>
                    <th>
                        Candidatos
                    </th>
                    <th>
                        Total
                    </th>
                    <th style="width: 5%">
                        sala inicial
                    </th>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM `sel_predio` "
                                . " WHERE `fk_id_sel` = " . $_POST['id_sel']
                                . " order by ordem ";
                        $query = $model->db->query($sql);
                        $pre = $query->fetchAll();
                        foreach ($pre as $v) {
                            ?>
                            <tr>
                                <td style="width: 10px">
                                    <input <?php echo @$_POST['id_predio'][$v['id_predio']] == $v['id_predio'] ? 'checked' : '' ?> type="checkbox" name="id_predio[<?php echo $v['id_predio'] ?>]" value="<?php echo $v['id_predio'] ?>" />
                                </td>
                                <td>
                                    <input type="text" name="n_predio[<?php echo $v['id_predio'] ?>]" value="<?php echo empty($_POST['n_predio'][$v['id_predio']]) ? $v['n_predio'] : $_POST['n_predio'][$v['id_predio']] ?>" />

                                </td>
                                <td style="width: 50px">
                                    <?php
                                    $sala = empty($_POST['sala'][$v['id_predio']]) ? $v['qt_salas'] : $_POST['sala'][$v['id_predio']];
                                    ?>
                                    <input style="text-align: center" type="text" name="sala[<?php echo $v['id_predio'] ?>]" value="<?php echo @$sala ?>" id="s<?php echo $v['id_predio'] ?>" onkeyup="cal('<?php echo $v['id_predio'] ?>', 's<?php echo $v['id_predio'] ?>', 'c<?php echo $v['id_predio'] ?>')" />
                                </td>
                                <td style="width: 50px">
                                    <?php
                                    $capacidade = empty($_POST['capacidade'][$v['id_predio']]) ? $v['capacidade'] : $_POST['capacidade'][$v['id_predio']];
                                    ?>
                                    <input style="text-align: center" type="text" name="capacidade[<?php echo $v['id_predio'] ?>]" value="<?php echo @$capacidade ?>" id="c<?php echo $v['id_predio'] ?>" onkeyup="cal('<?php echo $v['id_predio'] ?>', 's<?php echo $v['id_predio'] ?>', 'c<?php echo $v['id_predio'] ?>')" />
                                </td>
                                <td style="width: 50px">
                                    <?php
                                    $total = empty($_POST['tt'][$v['id_predio']]) ? $sala * $capacidade : $_POST['tt'][$v['id_predio']];
                                    @$tt += $total;
                                    ?>
                                    <input style="text-align: center" type="text" name="tt[<?php echo $v['id_predio'] ?>]" id="<?php echo $v['id_predio'] ?>" value="<?php echo @$total ?>" />
                                </td>
                                <td>
                                    <input type="text" name="numero[<?php echo $v['id_predio'] ?>]" value="<?php echo @$_POST['numero'][$v['id_predio']] ?>" />
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <br /><br />
                <div style="width: 100px;margin: 0 auto; text-align: center;">
                    Total
                    <br /><br />
                    <input name="total" style="text-align: center" id="tt" type="text"  value="<?php echo $tt ?>" />
                </div>
                <br />
                <br /><br />
                <div class="row">
                    <div class="col-md-12 text-center">
                        <input type="hidden" name="id_sel" value="<?php echo $_POST['id_sel'] ?>" />
                        <input class="btn btn-success" name="alocar" type="submit" value="Alocar" />
                    </div>
                </div>
            </form>
            <?php
        }
        /**
          $sql = "SELECT `id_inscr` , `n_insc`, `cpf`, `n_predio`, `sala` FROM `sel_inscricacao` order by n_insc";
          $query = $model->db->query($sql);
          $array = $query->fetchAll();
          ?>
          <br /><br />
          <table class="table table-striped table-bordered">
          <?php
          $c = 1;
          foreach ($array as $v) {
          if (@$old != $v['n_predio']) {
          $cc = 1;
          }
          @$old = $v['n_predio'];
          ?>
          <tr>
          <td>
          <?php echo $c++ ?>
          </td>
          <td>
          <?php echo $v['n_insc'] ?>
          </td>
          <td>
          <?php echo $v['cpf'] ?>
          </td>
          <td>
          <?php echo $v['n_predio'] ?>
          </td>
          <td>
          <?php echo $v['sala'] ?>
          </td>
          <td>
          <?php
          if (!empty($v['sala'])) {
          echo $cc++;
          }
          ?>
          </td>
          </tr>
          <?php
          }
          ?>
          </table>
          <?php

         * 
         */
        ?>
    </div>
    <script>
        function cal(id, c, s) {
            document.getElementById(id).value = document.getElementById(s).value * document.getElementById(c).value;
            tt = 0;
    <?php
    foreach ($pre as $v) {
        ?>
                tt = tt + parseInt(document.getElementById(<?php echo $v['id_predio'] ?>).value);
        <?php
    }
    ?>
            document.getElementById("tt").value = tt;
        }
    </script>
    <?php
}
?>