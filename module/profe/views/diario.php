<script src="/gw/dexie.js"></script>
<?php
if (!defined('ABSPATH'))
    exit;
$id_pessoa = toolErp::id_pessoa();
//id_pessoa para teste
$id_pessoa = 1037;
$indexDBTurma = $model->indexDBTurma($id_pessoa);
?>
<script>
<?= pwa::indexDB() ?>
    db.turma.clear();
    db.aluno.clear();
    $(document).ready(function () {
        sincronizarTurmasAlunos();
        userSet();
    });
    const sisOnLine = true;
    function sincronizarTurmasAlunos() {


<?php
if (!empty($indexDBTurma)) {
    foreach ($indexDBTurma as $v) {
        $alunos = $model->indexDBAlunos($v['id_turma'])
        ?>
                db.turma.add({
                    id_t_d: '<?= $v['id_t_d'] ?>',
                    id_turma: '<?= $v['id_turma'] ?>',
                    n_turma: '<?= $v['n_turma'] ?>',
                    id_disc: '<?= $v['iddisc'] ?>',
                    n_disc: '<?= $v['n_disc'] ?>',
                    id_pl: '<?= $v['fk_id_pl'] ?>',
                    abrev_disc: '<?= $v['sg_disc'] ?>',
                    id_inst: '<?= $v['id_inst'] ?>',
                    n_inst: '<?= $v['n_inst'] ?>',
                    id_cur: '<?= $v['id_curso'] ?>',
                    id_ciclo: '<?= $v['id_ciclo'] ?>',
                    un_letiva: '<?= $v['un_letiva'] ?>',
                    qt_letiva: '<?= $v['qt_letiva'] ?>',
                    atual_letiva: '<?= $v['atual_letiva'] ?>',
                    sg_letiva: '<?= $v['sg_letiva'] ?>',
                    aulasDia: <?= json_encode($v['aulasDia']) ?>,
                })
                        .then(() => {
        <?php
        if (!empty($alunos)) {
            foreach ($alunos as $a) {
                ?>
                                    db.aluno.add({
                                        id_ta: '<?= $a['id_turma_aluno'] ?>',
                                        id_pessoa: '<?= $a['id_pessoa'] ?>',
                                        n_pessoa: '<?= tool::abrevia($a['n_pessoa'], 20) ?>',
                                        sexo: '<?= $a['sexo'] ?>',
                                        chamada: '<?= $a['chamada'] ?>',
                                        n_sit: '<?= $a['situacao'] ?>',
                                        id_turma: '<?= $v['id_turma'] ?>'
                                    })
                <?php
            }
        }
        ?>
                        })

        <?php
    }
}
?>
    }

    function userSet() {
<?php
$u = $model->userSet($id_pessoa);
?>
        db.userSet.add({
            id_pessoa: '<?= $id_pessoa ?>',
            n_pessoa: '<?= $u['n_pessoa'] ?>',
            sexo: '<?= $u['sexo'] ?>',
            cpf: '<?= $u['cpf'] ?>',
            email: '<?= $u['emailgoogle'] ?>',
        });
    }

</script>