<div class="fieldBody">
    <div class="row">
        <div class="col-lg-8">
            <div class="alert alert-info" style="height: 35vh;">
                <?php
                if ($sup = gtMain::suporteCheck()) {
                    ?>

                    <a href="<?php echo HOME_URI; ?>/dttie/escolapesq">
                        <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
                        Você tem <span style="color: red; font-size: 20px"><?php echo count($sup) ?></span> mensage<?php echo count($sup) == 1 ? 'm' : 'ns' ?>
                    </a>
                    <?php
                }
                if(user::session('id_sistema')== 13){
                         $sql = "SELECT count(id_transf) as ct FROM `ge_transf_aluno` "
        . " WHERE( `cod_inst_d` = " . tool::id_inst()
        . " AND (`status_transf` = 'Ag. Aprovação' or `status_transf` = 'Matricula Liberada')) "
        . " OR "
        . " ( `cod_inst_o` = " . tool::id_inst()
        . " AND `status_transf` = 'Aprovado') ";

$query = pdoSis::getInstance()->query($sql);
        $tt = $query->fetch(PDO::FETCH_ASSOC);

$sql = "SELECT count(fk_id_inst) as ct FROM `ge_prof_esc` WHERE `fk_id_inst` = " . tool::id_inst() . " AND ( `hac_dia` = 0 or `hac_periodo` = '') and nao_hac <> 1";
$query = pdoSis::getInstance()->query($sql);
        $hc = $query->fetch(PDO::FETCH_ASSOC);


if ($tt['ct'] > 0 || $hc['ct'] > 0) {
    ?> <br /><br /> <?php
    if ($tt['ct'] > 0) {
        ?>
        <a href="<?php echo HOME_URI ?>/gestao/manuttransf/">
            <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
            Há <span style="color: red; font-size: 20px"><?php echo $tt['ct'] ?></span> transferências aguardando uma ação
        </a>
        <?php
    }

    if ($hc['ct'] > 0) {
        ?>

        <a href="<?php echo HOME_URI ?>/prof/cad/">
            <span class="glyphicon glyphicon-check" aria-hidden="true"></span>
            Há <span style="color: red; font-size: 20px"><?php echo $hc['ct'] ?></span> professor<?php echo $hc['ct'] > 1 ? 'es' : '' ?> sem horário e /ou dia de HAC cadastrado. Favor regularizar.
        </a>
        <?php
    }
}
                }
                ?>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="alert alert-warning" style="min-height: 35vh;">
                <table class="table table-bordered table-responsive table-striped" style="font-weight: bold">
                        <tr>
                            <td style="min-width: 25%">
                                Nome
                            </td>
                            <td>
                                <?php echo user::session('n_pessoa') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                E-mail
                            </td>
                            <td>
                                <?php echo $_SESSION['userdata']['email'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Subsistema 
                            </td>
                            <td>
                                <?php echo $_SESSION['userdata']['n_sistema'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Nível de Acesso 
                            </td>
                            <td>
                                <?php echo $_SESSION['userdata']['n_nivel'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Instância
                            </td>
                            <td>
                                <?php echo $_SESSION['userdata']['n_inst'] ?>
                            </td>
                        </tr>

                    </table>
                </div>
            </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    Suas últimas interações com o Sistema
                </div>
                <div class="panel-body">
                    <div style=" overflow: auto;height: 25vh">                     <?php
                        $form['array'] = log::logGet(@$_SESSION['userdata']['id_pessoa']);
                        $form['fields'] = [
                            'Data' => 'data',
                            'Hora' => 'hora',
                            'Descrição' => 'descricacao',
                            'Sistema' => 'n_sistema',
                            'Instância' => 'n_inst'
                        ];
                        tool::relatSimples($form);
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>



</div>