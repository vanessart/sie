<?php
$sql = "SELECT DISTINCT id_inst, n_inst FROM prod.`aa_escolas` "
        . " WHERE id_inst <> 13 "
        . "order by n_inst";
$query = $model->db->query($sql);
$array = $query->fetchAll();
?>
<style>
   #gf input{
        width: 300px;
    }
</style>
<div class="fieldBody">
    <div class="fieldBorder2">

        <form target="_blank" action="<?php echo HOME_URI ?>/global/prodpdf" method="POST">
            <div class="row">
                <div class="col-sm-2">
                    Relatório:
                </div>
                <div class="col-sm-4">
                    <select name="id_inst">
                        <option></option>
                        <?php
                        foreach ($array as $v) {
                            ?>
                            <option value="<?php echo $v['id_inst'] ?>"><?php echo $v['n_inst'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-4">
                    <input class="btn btn-success" type="submit" value="PDF" />
                </div>
            </div>
        </form>
    </div>
    <br /><br />
    <div class="fieldBorder2">

        <form target="_blank" action="<?php echo HOME_URI ?>/global/alupdf" method="POST">
            <div class="row">
                <div class="col-sm-2">
                    Notas dos Alunos
                </div>
                <div class="col-sm-4">
                    <select name="id_inst">
                        <option></option>
                        <?php
                        foreach ($array as $v) {
                            ?>
                            <option value="<?php echo $v['id_inst'] ?>"><?php echo $v['n_inst'] ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-4">
                    <input class="btn btn-success" type="submit" value="PDF" />
                </div>
            </div>
        </form>
    </div>
    <br /><br />
    <div id="gf" class="fieldBorder2">
        <div style="text-align: center; font-size: 20px">
            Gráficos
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-3 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/global/grafesc" method="POST">
                    <input class="btn btn-success" type="submit" name="titulo" value="Todas as escolas"/>
                </form> 
            </div>
            <div class="col-sm-3 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/global/grafesc" method="POST">
                    <input type="hidden" name="tipo" value="infantil" />
                    <input class="btn btn-success" type="submit" name="titulo" value="Segmento Infantil"/>
                </form> 
            </div>
            <div class="col-sm-3 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/global/grafesc" method="POST">
                    <input type="hidden" name="tipo" value="fund" />
                    <input class="btn btn-success" type="submit" name="titulo" value="Segmento Fundamental"/>
                </form> 
            </div>
              <div class="col-sm-3 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/global/grafescc" method="POST">
                    <input class="btn btn-success" type="submit" value="Ciclos"/>
                </form> 
            </div>
        </div>
        <br /><br />        
        <div class="row">
            <div class="col-sm-3 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/global/grafesc" method="POST">
                    <input type="hidden" name="tipo" value="alfabetiza" />
                    <input class="btn btn-success" type="submit" name="titulo" value="Ciclo de Alfabetização"/>
                </form> 
            </div>
            <div class="col-sm-3 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/global/grafesc" method="POST">
                    <input type="hidden" name="tipo" value="maternal" />
                    <input class="btn btn-success" type="submit" name="titulo" value="Maternais"/>
                </form> 
            </div>
            <div class="col-sm-3 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/global/grafesc" method="POST">
                    <input type="hidden" name="tipo" value="pre" />
                    <input class="btn btn-success" type="submit" name="titulo" value="Pré Escola"/>
                </form> 
            </div>
            <div class="col-sm-3 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/global/grafesc" method="POST">
                    <input type="hidden" name="tipo" value="4+" />
                    <input class="btn btn-success" type="submit" name="titulo" value="Avaliados por Prova e Redação"/>
                </form> 
            </div>
        </div>
        <br /><br />
    </div>
</div>
<?php
/**
  $sql = "SELECT * FROM prod.`aa_resultado`";
  $query = $model->db->query($sql);
  $array = $query->fetchAll();
  foreach ($array as $v) {
  if (!empty($v['turmas']) && in_array($v['idmet'], [3, 4, 11, 15])) {
  $t = unserialize($v['turmas']);
  $cont = 0;
  $tt = 0;
  $cal = NULL;
  foreach ($t as $i) {
  if (!empty($i['n_inst'])) {
  $cont++;
  $tt += $i['nota'];
  $cal .= $cont . ' - ' . substr($i['n_inst'] , 0,25). ', ' . $i['n_turma'] . ' = ' . $i['nota'] . ';';
  }
  }
  $cal .= 'Média(' . $tt . '/' . $cont . ') = ' . round($v['nota'], 1);
  $sql = "UPDATE prod.`aa_resultado` SET `calculo` = '$cal' WHERE `aa_resultado`.`id_rm` = " . $v['id_rm'];
  $query = $model->db->query($sql);
  } elseif (!empty($v['turmas']) && $v['idmet'] == 6) {
  $i = unserialize($v['turmas']);
  $cal = NULL;
  $cal .= 'Nota da Escola = ' . $i['escola'] . ';';
  $cal .= 'Nota do Diretor = ' . $i['diretor'] . ';';
  $cal .= 'Média(' . $i['escola'] . ' + ' . $i['diretor'] . '/3) = ' . round($v['nota'], 1);

  $sql = "UPDATE prod.`aa_resultado` SET `calculo` = '$cal' WHERE `aa_resultado`.`id_rm` = " . $v['id_rm'];
  $query = $model->db->query($sql);
  } else {
  $sql = "UPDATE prod.`aa_resultado` SET `calculo` = '' WHERE `aa_resultado`.`id_rm` = " . $v['id_rm'];
  $query = $model->db->query($sql);
  }
  }





















  $set = [151];
  $sql = "SELECT * FROM `prod`.`aa_recebe`";
  $query = $model->db->query($sql);
  $array = $query->fetchAll();
  foreach ($array as $v) {
  ################################### 1 ######################################
  if ($v['metodo'] == 1 && in_array(1, $set)) {
  $sql = "SELECT * FROM `prod`.`aa_escolas` WHERE `id_inst` = '" . $v['fk_id_inst'] . "' ";
  $query = $model->db->query($sql);
  $nota = $query->fetch()['nota'];
  $metodo = "Seu bônus foi calculado a partir da média aritmética das notas das classes de sua escola, por você não ter alocação em classe específica ou por não ser professor.";
  echo '<br /><br />' . $sql = "REPLACE INTO `prod`.`aa_resultado` ("
  . "`id_rm`, `nome`, `funcao`, `fk_id_inst`, "
  . "`n_inst`, `metodo`, `idmet`, `turmas`, `nota`) VALUES ("
  . "'" . $v['MATRICULA'] . "', "
  . "'" . $v['FUNCIONARIO'] . "', "
  . "'" . $v['FUNCAO'] . "', "
  . "'" . $v['fk_id_inst'] . "', "
  . "'" . $v['n_inst'] . "', "
  . "'$metodo', "
  . "'1', "
  . "'', "
  . "'$nota'"
  . ");";
  $query = $model->db->query($sql);
  ################################### 2 ######################################
  } elseif ($v['metodo'] == 2 && in_array(2, $set)) {
  $sql = "SELECT * FROM prod.`aa_escolas` WHERE `id_inst` = '13' ";
  $query = $model->db->query($sql);
  $nota = $query->fetch()['nota'];
  $metodo = "Seu bônus foi calculado a partir da média aritmética das notas de todas as escolas da rede, por estar afastado das atividades do cargo em atendimento à Secretaria de Educação";
  echo '<br /><br />' . $sql = "REPLACE INTO `prod`.`aa_resultado` ("
  . "`id_rm`, `nome`, `funcao`, `fk_id_inst`, "
  . "`n_inst`, `metodo`, `idmet`, `turmas`, `nota`) VALUES ("
  . "'" . $v['MATRICULA'] . "', "
  . "'" . $v['FUNCIONARIO'] . "', "
  . "'" . $v['FUNCAO'] . "', "
  . "'" . $v['fk_id_inst'] . "', "
  . "'" . $v['n_inst'] . "', "
  . "'$metodo', "
  . "'2', "
  . "'', "
  . "'$nota'"
  . ");";
  $query = $model->db->query($sql);
  ################################### 3 ######################################
  } elseif ($v['metodo'] == 3 && in_array(3, $set)) {
  $metodo = "Seu bônus foi calculado a partir de sua nota individual, aferida por um avaliador externo.";
  $sql = "SELECT t.id_turma, i.n_inst,t.n_turma, at.nota FROM prod.`aa_turmas_13` at "
  . " left join ge.ge_turmas t on t.id_turma = at.fk_id_turma "
  . " left join ge.instancia i on i.id_inst = t.fk_id_inst "
  . " WHERE `rm` = '" . $v['MATRICULA'] . "' ";
  $query = $model->db->query($sql);
  $cl = $query->fetchAll();
  $classes = NULL;
  $tnota = 0;
  foreach ($cl as $c) {
  $classes[$c['id_turma']] = $c;
  $tnota += $c['nota'];
  }
  $nota = $tnota / count($cl);
  $classes['total'] = $nota;
  $classesS = serialize($classes);
  $sql = "REPLACE INTO `prod`.`aa_resultado` ("
  . "`id_rm`, `nome`, `funcao`, `fk_id_inst`, "
  . "`n_inst`, `metodo`, `idmet`, `turmas`, `nota`) VALUES ("
  . "'" . $v['MATRICULA'] . "', "
  . "'" . $v['FUNCIONARIO'] . "', "
  . "'" . $v['FUNCAO'] . "', "
  . "'" . $v['fk_id_inst'] . "', "
  . "'', "
  . "'$metodo', "
  . "'3', "
  . "'$classesS', "
  . "'$nota'"
  . ");";
  $query = $model->db->query($sql);
  ################################### 4 ######################################
  } elseif ($v['metodo'] == 4 && in_array(4, $set)) {
  $metodo = "Seu bônus foi calculado a partir da média aritmética das notas das classes nas quais você foi alocado.";
  $sql = "SELECT id_turma, nota, n_inst, n_turma FROM prod.`aa_turmas` t "
  . " join ge.instancia i on i.id_inst = t.fk_id_inst "
  . " join prod.aa_aloca al on al.fk_id_turma = t.id_turma "
  . "where al.rm = " . $v['MATRICULA'];
  $query = $model->db->query($sql);
  $cl = $query->fetchAll();
  $classes = NULL;
  $tnota = 0;
  foreach ($cl as $c) {
  $classes[$c['id_turma']] = $c;
  $tnota += $c['nota'];
  }
  $nota = $tnota / count($cl);
  $classes['total'] = $nota;
  $classesS = serialize($classes);
  $sql = "REPLACE INTO `prod`.`aa_resultado` ("
  . "`id_rm`, `nome`, `funcao`, `fk_id_inst`, "
  . "`n_inst`, `metodo`, `idmet`, `turmas`, `nota`) VALUES ("
  . "'" . $v['MATRICULA'] . "', "
  . "'" . $v['FUNCIONARIO'] . "', "
  . "'" . $v['FUNCAO'] . "', "
  . "'" . $v['fk_id_inst'] . "', "
  . "'', "
  . "'$metodo', "
  . "'4', "
  . "'$classesS', "
  . "'$nota'"
  . ");";
  $query = $model->db->query($sql);
  ################################### 6 ######################################
  } elseif ($v['metodo'] == 6 && in_array(6, $set)) {
  $metodo = "Seu bonus foi calculado a partir da média aritmética das notas das classes de sua escola e de sua nota individual.";
  $sql = "SELECT id_inst, n_inst, nota, nota_escola, nota_diretor FROM prod.aa_adi adi "
  . " JOIN instancia i on i.id_inst = adi.fk_id_inst "
  . " WHERE adi.id_rm = " . $v['MATRICULA'];
  $query = $model->db->query($sql);
  $adi = $query->fetch();
  $cal['diretor'] = $adi['nota_diretor'];
  $cal['escola'] = $adi['nota_escola'];
  $nota = ($cal['diretor'] + $cal['escola']) / 2;
  $cal['nota'] = $nota;
  $calculo = serialize($cal);
  $sql = "REPLACE INTO `prod`.`aa_resultado` ("
  . "`id_rm`, `nome`, `funcao`, `fk_id_inst`, "
  . "`n_inst`, `metodo`, `idmet`, `turmas`, `nota`) VALUES ("
  . "'" . $v['MATRICULA'] . "', "
  . "'" . $v['FUNCIONARIO'] . "', "
  . "'" . $v['FUNCAO'] . "', "
  . "'" . $v['fk_id_inst'] . "', "
  . "'" . $adi['n_inst'] . "', "
  . "'$metodo', "
  . "'6', "
  . "'$calculo', "
  . "'$nota'"
  . ");";
  $query = $model->db->query($sql);
  ################################# 11  ######################################
  } elseif ($v['metodo'] == 11 && in_array(11, $set)) {
  $metodo = "Seu bonus foi calculado a partir da média aritmética das notas das classes de 4.o ao 9.o anos, nas quais você foi alocado e de sua nota individual aferida por um avaliador externo (esta nota foi repetida em todas as classes do ciclo de alfabetização, bem como nas classes do segmento infantil nas quais você foi alocado)";
  $sql = "SELECT id_turma, nota, n_inst, n_turma FROM prod.`aa_turmas` t "
  . " join ge.instancia i on i.id_inst = t.fk_id_inst "
  . " join prod.aa_aloca al on al.fk_id_turma = t.id_turma "
  . "where al.rm = " . $v['MATRICULA'];
  $query = $model->db->query($sql);
  $cl = $query->fetchAll();
  $classes = NULL;
  $tnota = 0;
  foreach ($cl as $c) {
  $classes[$c['id_turma']] = $c;
  $tnota += $c['nota'];
  }
  //nota no ciclo alfabetização
  $sql = "SELECT nota FROM prod.`aa_11` WHERE `id_rm` LIKE '" . $v['MATRICULA'] . "' ";
  $query = $model->db->query($sql);
  $nota123 = $query->fetch()['nota'];
  $sql = "SELECT tu.id_turma, nota, n_inst, n_turma FROM prod.`aa_turmas_13` t "
  . " join ge.instancia i on i.id_inst = t.fk_id_inst "
  . " join prod.aa_aloca_123 al on al.fk_id_turma = t.fk_id_turma "
  . "join prod.ge_turmas tu on tu.id_turma = t.fk_id_turma "
  . "where al.rm = " . $v['MATRICULA'];
  $query = $model->db->query($sql);
  $cl1 = $query->fetchAll();
  foreach ($cl1 as $c) {
  echo '<br />nota = ' . $c['nota'] = $nota123;
  $classes[$c['id_turma']] = $c;
  $tnota += str_replace(',', '.', $nota123);
  }

  $nota = $tnota / count($classes);
  echo '<br />notaFinal = ' . $classes['total'] = $nota;

  $classesS = serialize($classes);
  $sql = "REPLACE INTO `prod`.`aa_resultado` ("
  . "`id_rm`, `nome`, `funcao`, `fk_id_inst`, "
  . "`n_inst`, `metodo`, `idmet`, `turmas`, `nota`) VALUES ("
  . "'" . $v['MATRICULA'] . "', "
  . "'" . $v['FUNCIONARIO'] . "', "
  . "'" . $v['FUNCAO'] . "', "
  . "'" . $v['fk_id_inst'] . "', "
  . "'', "
  . "'$metodo', "
  . "'11', "
  . "'$classesS', "
  . "'$nota'"
  . ");";
  $query = $model->db->query($sql);
  ################################# 15  ######################################
  } elseif ($v['metodo'] == 15 && in_array(15, $set)) {
  $metodo = "Seu bonus foi calculado a partir da média aritmética das notas das classes de 4.o ao 9.o anos, nas quais você foi alocado e de sua nota individual aferida por um avaliador externo (esta nota foi repetida em todas as classes do ciclo de alfabetização, bem como nas classes do segmento infantil nas quais você foi alocado)";
  $sql = "SELECT id_turma, nota, n_inst, n_turma FROM prod.`aa_turmas` t "
  . " join ge.instancia i on i.id_inst = t.fk_id_inst "
  . " join prod.aa_aloca al on al.fk_id_turma = t.id_turma "
  . "where al.rm = " . $v['MATRICULA'];
  $query = $model->db->query($sql);
  $cl = $query->fetchAll();
  $classes = NULL;
  $tnota = 0;
  foreach ($cl as $c) {
  $classes[$c['id_turma']] = $c;
  $tnota += $c['nota'];
  }
  //nota no ciclo alfabetização
  $sql = "SELECT nota FROM prod.`aa_15` WHERE `id_rm` LIKE '" . $v['MATRICULA'] . "' ";
  $query = $model->db->query($sql);
  $nota123 = $query->fetch()['nota'];
  $sql = "SELECT tu.id_turma, nota, n_inst, n_turma FROM prod.`aa_turmas_13` t "
  . " join ge.instancia i on i.id_inst = t.fk_id_inst "
  . " join prod.aa_aloca_123 al on al.fk_id_turma = t.fk_id_turma "
  . "join prod.ge_turmas tu on tu.id_turma = t.fk_id_turma "
  . "where al.rm = " . $v['MATRICULA'];
  $query = $model->db->query($sql);
  $cl1 = $query->fetchAll();
  foreach ($cl1 as $c) {
  echo '<br />nota = ' . $c['nota'] = $nota123;
  $classes[$c['id_turma']] = $c;
  $tnota += str_replace(',', '.', $nota123);
  }

  $nota = $tnota / count($classes);
  echo '<br />notaFinal = ' . $classes['total'] = $nota;

  $classesS = serialize($classes);
  $sql = "REPLACE INTO `prod`.`aa_resultado` ("
  . "`id_rm`, `nome`, `funcao`, `fk_id_inst`, "
  . "`n_inst`, `metodo`, `idmet`, `turmas`, `nota`) VALUES ("
  . "'" . $v['MATRICULA'] . "', "
  . "'" . $v['FUNCIONARIO'] . "', "
  . "'" . $v['FUNCAO'] . "', "
  . "'" . $v['fk_id_inst'] . "', "
  . "'', "
  . "'$metodo', "
  . "'15', "
  . "'$classesS', "
  . "'$nota'"
  . ");";
  $query = $model->db->query($sql);
  }
  }
 * ###################################################################################

  $ciclos = [21, 22, 23, 24, 19, 20, 1, 2, 3, 4, 5, 6, 7, 8, 9];
  if (!empty($_POST['ir'])) {
  foreach ($array as $v) {
  //       foreach ($ciclos as $c) {
  $c = "19,20";
  $sql = "select "
  . " avg(tt.nota) as nota "
  . " FROM prod.ge_turmas t "
  . " JOIN prod.aa_turmas tt on tt.id_turma = t.id_turma "
  . " JOIN prod.aa_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
  . " where t.fk_id_ciclo in  ($c) "
  . " and tt.fk_id_inst = " . $v['id_inst'];
  $query = $model->db->query($sql);
  $a = $query->fetch();
  if (!empty($a['nota'])) {
  echo '<br><br>' .
  $sql = "REPLACE INTO prod.`aaa_graf_tipo` ("
  . "`id_inst`, "
  . "`n_inst`, "
  . "`tipo`, "
  . "`nota`) VALUES ("
  . "'" . $v['id_inst'] . "', "
  . "'" . $v['n_inst'] . "', "
  . "'pre', "
  . "'" . $a['nota'] . "'"
  . ");";
  $query = $model->db->query($sql);
  }
  //       }
  }
  }

 * 
 */
?>