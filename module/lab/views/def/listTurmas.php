<?php
if (!defined('ABSPATH'))
    exit;
$disc = sql::idNome('ge_disciplinas');
$disc['nc']='Polivalente';

$rm = filter_input(INPUT_POST, 'rm', FILTER_SANITIZE_NUMBER_INT);
$sql = "SELECT t.n_turma, i.n_inst, id_turma, ap.iddisc FROM ge_aloca_prof ap "
        . " JOIN ge_turmas t on t.id_turma = ap.fk_id_turma "
        . " join instancia i on i.id_inst = t.fk_id_inst "
        . " WHERE `rm` LIKE '$rm' order by n_inst, n_turma";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($array as $v) {
    $esc[$v['n_inst']][$v['id_turma']] = ['n_turma'=>$v['n_turma'], 'iddisc'=>$v['iddisc']];
}
?>
<div class="body">
    <?php
    if (!empty($esc)) {
        foreach ($esc as $escola => $turmas) {
            ?>
            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <td>
                        <?= $escola ?>
                    </td>
                </tr>
                <?php
                foreach ($turmas as $v) {
                    ?>
                    <tr>
                        <td>
                            <?= $v['n_turma'] ?> (<?= @$disc[$v['iddisc']] ?>)
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <br /><br />
            <?php
        }
    } else {
    ?>
            <div class="alert alert-danger text-center">
                Sem classe atribuída nesta Matrícula
            </div>
                                    <?php    
    }
    ?>
</div>
