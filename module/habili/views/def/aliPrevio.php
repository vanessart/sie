<?php
if (!defined('ABSPATH'))
    exit;
$id_hab = filter_input(INPUT_POST, 'id_hab', FILTER_SANITIZE_NUMBER_INT);
$sql = "SELECT descricao, codigo FROM coord_hab hab "
        . " JOIN coord_hab_alicerces al on al.fk_id_hab_alicerce = hab.id_hab "
        . " WHERE al.fk_id_hab = $id_hab";
$query = pdoSis::getInstance()->query($sql);
$alicerces = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT descricao, codigo FROM coord_hab hab "
        . " JOIN coord_hab_previas al on al.fk_id_hab_previa = hab.id_hab "
        . " WHERE al.fk_id_hab = $id_hab";
$query = pdoSis::getInstance()->query($sql);
$previa = $query->fetchAll(PDO::FETCH_ASSOC);

$sql = "SELECT `metodologicas`, `verific_aprendizagem` FROM `coord_hab` WHERE `id_hab` = $id_hab";
$query = pdoSis::getInstance()->query($sql);
$hab = $query->fetch(PDO::FETCH_ASSOC);
?>
<div class="body">
    <div class="border">
        <div style="text-align: center; padding: 10px">
            Sugestões metodológicas
        </div>
        <?php
        if (empty($hab['metodologicas'])) {
            ?>
            <div class="alert alert-danger">
                Não há Sugestões metodológicas cadastradas
            </div>
            <?php
        } else {
            ?>
            <div class="alert alert-dark">
                <?= $hab['metodologicas'] ?>
            </div>
            <?php
        }
        ?>

    </div>
    <br /><br />    
    <div class="border">
        <div style="text-align: center; padding: 10px">
            Sugestões de verificação de aprendizagem
        </div>
        <?php
        if (empty($hab['verific_aprendizagem'])) {
            ?>
            <div class="alert alert-danger">
                Não há sugestões de verificação de aprendizagem cadastradas
            </div>
            <?php
        } else {
            ?>
            <div class="alert alert-dark">
                <?= $hab['verific_aprendizagem'] ?>
            </div>
            <?php
        }
        ?>

    </div>
    <br /><br />    
    <div class="border">
        <div style="text-align: center; padding: 10px">
            Habilidades Alicerces
        </div>
        <?php
        if (empty($alicerces)) {
            ?>
            <div class="alert alert-danger">
                Não há Habilidades (alicerces) cadastradas
            </div>
            <?php
        } else {
            foreach ($alicerces as $v) {
                ?>
                <div class="alert alert-dark">
                    <?= $v['codigo'] ?> - <?= $v['descricao'] ?>
                </div>
                <?php
            }
        }
        ?>

    </div>
    <br /><br />
    <div class="border">
        <div style="text-align: center; padding: 10px">
            Conhecimentos Prévios
        </div>
        <?php
        if (empty($previa)) {
            ?>
            <div class="alert alert-danger">
                Não há Habilidades (Conhecimentos prévios) cadastradas
            </div>
            <?php
        } else {
            foreach ($previa as $v) {
                ?>
                <div class="alert alert-dark">
                    <?= $v['codigo'] ?> - <?= $v['descricao'] ?>
                </div>
                <?php
            }
        }
        ?>

    </div>
</div>
