<style>
    .grd button{
        width: 100%;
    }
</style>
<?php
if (!defined('ABSPATH'))
    exit;
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = toolErp::id_pessoa();
$turmas = ng_professor::profTurma($id_pessoa);
?>
<div class="body">
    <div class="fieldTop">
        Relatórios por Turma/Professoa
    </div>
    <div class="row">
        <div class="col">
            <?php
            if (!empty($turmas)) {
                echo formErp::select('id_turma', $turmas, 'Turma', $id_turma, 1);
            } else {
                ?>
                <div class="alert alert-danger">
                    Sem Turmas Alocadas
                </div>
                <?php
            }
            ?>
        </div>
        <div class="col">

        </div>
        <div class="col">

        </div>
    </div>
    <br /> <br /> <br />
    <?php
    if ($id_turma) {
        if (!is_array($id_turma)) {
            $id_turma = [$id_turma];
        }
        include ABSPATH . "/module/sed/views/_turmaRelat/turma.php";
    }
    ?>
</div>
