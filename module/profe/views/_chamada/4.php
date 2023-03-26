<?php
if (!defined('ABSPATH'))
    exit;
$sql = "SELECT * FROM coord_plano_aula p "
        . " JOIN coord_plano_aula_turmas t on t.fk_id_plano = p.id_plano "
        . " WHERE t.fk_id_turma = $id_turma "
        . " AND p.dt_inicio <= '$data' "
        . " AND p.dt_fim >= '$data' "
        . " AND p.iddisc like '$id_disc'";
$query = pdoSis::getInstance()->query($sql);
$planoAula = $query->fetch(PDO::FETCH_ASSOC);
?>
<?php
if (empty($planoAula)) {
    ?>
    <div class="alert alert-danger">
        Não há Plano de Aula referente a esta data.
    </div>
    <?php
} else {
    $sql = " SELECT "
            . " hab.descricao, hab.codigo "
            . " FROM coord_plano_aula_hab pah "
            . " JOIN coord_hab hab on hab.id_hab = pah.fk_id_hab "
            . " WHERE pah.fk_id_plano = " . $planoAula['id_plano']
            . " order by hab.codigo";
    $query = pdoSis::getInstance()->query($sql);
    $hab = $query->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <table class="table table-bordered table-hover table-striped border">
        <tr>
            <td style="text-align: center">
                Descrição das Atividades com Recursos Utilizados
            </td>
        </tr>
        <tr>
            <td>
                <?= @$planoAula['recursos'] ?>
            </td>
        </tr>
    </table>
    <br />
    <table class="table table-bordered table-hover table-striped border">
        <tr>
            <td style="text-align: center">
                Atividades de Estudo
            </td>
        </tr>
        <tr>
            <td>
                <?= @$planoAula['metodologia'] ?>
            </td>
        </tr>
    </table>
    <br />
    <table class="table table-bordered table-hover table-striped border">
        <tr>
            <td style="text-align: center">
                Instrumentos de Avaliação
            </td>
        </tr>
        <tr>
            <td>
                <?= @$planoAula['avaliacao'] ?>
            </td>
        </tr>
    </table>
    <br />
    <table class="table table-bordered table-hover table-striped border">
        <tr>
            <td style="text-align: center">
                Adaptação Curricular
            </td>
        </tr>
        <tr>
            <td>
                <?= @$planoAula['adapta_curriculo'] ?>
            </td>
        </tr>
    </table>
    <?php
    if (!empty($hab)) {
        ?>
        <table class="table table-bordered table-hover table-striped border">
            <tr>
                <td style="text-align: center">
                    Habilidades
                </td>
            </tr>
            <?php
            foreach ($hab as $v) {
                ?>
                <tr>
                    <td>
                        <?= $v['codigo'] ?> - <?= $v['descricao'] ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }
}
?>
