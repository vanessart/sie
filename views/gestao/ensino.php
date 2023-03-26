<?php
if (!empty(intval(@$_POST['id_tp_ens']))) {
    @$seg = sql::get('ge_tp_ensino', '*', ['id_tp_ens' => $_POST['id_tp_ens']], 'fetch');
    $curso['fk_id_tp_ens'] = $_POST['id_tp_ens'];
}
if (!empty($_POST['id_curso'])) {
    $curso = sql::get('ge_cursos', '*', ['id_curso' => $_POST['id_curso']], 'fetch');
}
if (!empty($_POST['id_ciclo'])) {
    $ciclo = sql::get('ge_ciclos', '*', ['id_ciclo' => $_POST['id_ciclo']], 'fetch');
}
if (!empty($_POST['id_cg'])) {
    $grade = sql::get('ge_curso_grade', '*', ['id_cg' => $_POST['id_cg']], 'fetch');
}
@$id_cg = $_POST['id_cg'];
@$id_ciclo = $_POST['id_ciclo'];
$id_curso = @$_POST['id_curso'];
$id_tp_ens = @$_POST['id_tp_ens'];
$aba = @$_POST['aba'];
?>

<div class="fieldBody">
    <div class="fieldTop">
        Cadastro de Segmentos, Cursos e Ciclos
    </div>
    <br />
    <?php
    $abas[1] = ['nome' => "Seguimentos", 'ativo' => 1, 'hidden' => ['aba' => 'segmento']];
    if (!empty($id_tp_ens)) {
        if ($aba == "cursos") {
            $_POST['activeNav'] = 2;
        }
        $abas[2] = [
            'nome' => "Cursos (" . $seg['n_tp_ens'] . ")",
            'ativo' => 1,
            'hidden' => ['aba' => 'cursos', 'id_tp_ens' => @$_POST['id_tp_ens']]
        ];
    } else {
        $abas[2] = ['nome' => "Cursos", 'ativo' => 0,];
    }
    if (!empty($id_curso) && @$aba != "cursos") {
        if ($aba == "ciclos") {
            $_POST['activeNav'] = 3;
        }
        $abas[3] = [
            'nome' => "Ciclos (" . $curso['n_curso'] . ")",
            'ativo' => 1,
            'hidden' => ['aba' => 'ciclos', 'id_tp_ens' => @$_POST['id_tp_ens'], 'id_curso' => $id_curso]
        ];
    } else {
        $abas[3] = ['nome' => "Ciclos", 'ativo' => 0,];
    }
     if (@$aba == "grade") {
               if ($aba == "grade") {
            $_POST['activeNav'] = 4;
        }
        $abas[4] = [
            'nome' => "Ciclo/Grade (" . $ciclo['n_ciclo'] . ")",
            'ativo' => 1,
            'hidden' => ['aba' => 'grade', 'id_tp_ens' => @$_POST['id_tp_ens'], 'id_curso' => $id_curso, 'id_ciclo' => $id_ciclo]
        ];  
     } else {
         $abas[4] = ['nome' => "Ciclos/Grades", 'ativo' => 0,]; 
     }
   

    tool::abas($abas);
    ?>
    <br /><br /><br />
    <?php
    if (empty(@$aba) || $aba == 'segmento') {
        include ABSPATH . '/views/gestao/ensino_seg.php';
    } elseif ($aba == 'cursos') {
        include ABSPATH . '/views/gestao/ensino_cursos.php';
    } elseif ($aba == 'ciclos') {
        include ABSPATH . '/views/gestao/ensino_ciclos.php';
    } elseif ($aba == 'grade') {
        include ABSPATH . '/views/gestao/ensino_grade.php';
    }
    ?>
</div>

