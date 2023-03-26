<div class="fieldBody">
    <?php
    if (!empty(@$_POST['id_turma'])) {
        $aluno__ = turmas::classe(@$_POST['id_turma']);
        ?>
        <br /><br />
        <?php
            include  ABSPATH . '/views/hab/carometro_tab.php';
    }
    ?>

</div>