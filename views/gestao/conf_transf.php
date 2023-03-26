<?php
$sql = "SELECT count(id_transf) as ct FROM `ge_transf_aluno` "
        . " WHERE( `cod_inst_d` = " . tool::id_inst()
        . " AND (`status_transf` = 'Ag. Aprovação' or `status_transf` = 'Matricula Liberada')) "
        . " OR "
        . " ( `cod_inst_o` = " . tool::id_inst()
        . " AND `status_transf` = 'Aprovado') ";

$query = $model->db->query($sql);
$tt = $query->fetch();

$sql = "SELECT count(fk_id_inst) as ct FROM `ge_prof_esc` WHERE `fk_id_inst` = " . tool::id_inst() . " AND ( `hac_dia` = 0 or `hac_periodo` = '') and nao_hac <> 1";
$query = $model->db->query($sql);
$hc = $query->fetch();


if ($tt['ct'] > 0 || $hc['ct'] > 0) {
    ?>
    <br />
    <div class="alert fieldWhite" style="font-size: 16px; text-align: center; font-weight: bold">
        <?php
        if ($tt['ct'] > 0) {
            ?>
            <a href="<?php echo HOME_URI ?>/gestao/manuttransf/">
                Há <span style="color: red; font-size: 20px"><?php echo $tt['ct'] ?></span> transferências aguardando uma ação
            </a>
            <?php
        }
        $suporte = [
            6488,
            291852,
            6482
        ];
        if ($hc['ct'] > 0) {
            if (!in_array(tool::id_pessoa(), $suporte)) {
                tool::alert("Há " . $hc['ct'] . " professor" . ($hc['ct'] > 1 ? 'es' : '') . " sem horário e /ou dia de HAC cadastrado.");
            }
            ?>

            <a href="<?php echo HOME_URI ?>/prof/cad/">
                Há <span style="color: red; font-size: 20px"><?php echo $hc['ct'] ?></span> professor<?php echo $hc['ct'] > 1 ? 'es' : '' ?> sem horário e /ou dia de HAC cadastrado. Favor regularizar.
                <br /><br />
                Se o(a) professor(a) faz HAC em outra escola, altere os dados clicando em "Não faz HAC nesta escola ".
            </a>
            <?php
        }
        ?>
    </div>
    <?php
}
?>