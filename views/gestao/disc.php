<?php
if (!empty($_POST['id_area'])) {
    $area = sql::get('ge_areas', '*', ['id_area' => $_POST['id_area'], '>' => 'n_area'], 'fetch');
}
if (!empty($_POST['id_disc'])) {
    $disc = sql::get('ge_disciplinas', '*', ['id_disc' => $_POST['id_disc'], '>' => 'n_disc'], 'fetch');
}
if (!empty($_POST['id_hab'])) {
    $hab = sql::get('ge_habilidades', '*', ['id_hab' => $_POST['id_hab'], '>' => 'n_hab'], 'fetch');
}
if (!empty($_POST['id_grade'])) {
    $grade = sql::get(['ge_grades', 'ge_tp_aval'], '*', ['id_grade' => $_POST['id_grade'], '>' => 'n_grade',], 'fetch');
    if (!empty($_POST['id_aloca'])) {
        if ($grade['fk_id_ta'] == 1) {
            $tab = 'ge_aloca_disc';
        } elseif ($grade['fk_id_ta'] == 2) {
            $tab = 'ge_aloca_hab';
        }
        $aloca = sql::get($tab, '*', ['id_aloca' => $_POST['id_aloca']], 'fetch');
    }
}

@$id_grade = $_POST['id_grade'];
$aba = @$_POST['aba']
?>
<div class="fieldBody">
    <div class="fieldTop">
        Disciplinas, Habilidades e Grades Curriculares
    </div>
    <br />
    <?php
    if($aba == 'gd'){
        $_POST['activeNav']=5;
    }
    $abas[1] = ['nome' => "Área de Conhecimento", 'ativo' => 1, 'hidden' => ['aba' => 'area']];
    $abas[2] = ['nome' => "Disciplinas", 'ativo' => 1, 'hidden' => ['aba' => 'disc']];
    $abas[3] = ['nome' => "Habilidades", 'ativo' => 1, 'hidden' => ['aba' => 'hab']];
    $abas[4] = ['nome' => "Grades Curriculares", 'ativo' => 1, 'hidden' => ['aba' => 'grade']];
    $abas[5] = ['nome' => "Alocar Disciplinas", 'ativo' => 0, 'hidden' => ['aba' => 'gd']];
    tool::abas($abas);
    ?>
   
    <br /><br /><br />
    <?php
    if (empty($aba) || @$aba == "area") {
        include ABSPATH . '/views/gestao/disc_area.php';
    } elseif (@$aba == "hab") {
        include ABSPATH . '/views/gestao/disc_hab.php';
    } elseif (@$aba == "grade") {
        include ABSPATH . '/views/gestao/disc_grade.php';
    } elseif (@$aba == "disc") {
        include ABSPATH . '/views/gestao/disc_disc.php';
    } elseif (@$aba == "gd") {
        include ABSPATH . '/views/gestao/disc_gd.php';
    }
    ?>
</div>