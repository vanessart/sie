<?php
if (!defined('ABSPATH'))
    exit;
if (toolErp::id_nilvel() == 55) {
    $tpCoord = 1;
} elseif (toolErp::id_nilvel() == 56) {
    $tpCoord = 2;
}
$hoje = date("Y-m-d");
$sql = "SELECT * FROM profe_msg_coord "
        . " WHERE `tp_coord` LIKE '%$tpCoord%' "
        . " AND `dt_inicio` <= '$hoje' AND `dt_fim` >= '$hoje' "
        . " AND `at_mc` = 1 "
        . " order by  dt_inicio desc ";
$query = pdoSis::getInstance()->query($sql);
$msg = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="body">
     <div class="fieldTop">
        Área do Coordenador
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="alert alert-info" >
   <?php
        if (!empty($msg)) {
            foreach ($msg as $v) {
                ?>
                    <div style="text-align: center; font-weight: bold; font-size: 1.2em; padding: 50px">
                        <?= $v['n_mc'] ?>
                    </div>
                    <div style="font-weight: bold; word-wrap: break-word">
                        <?= $v['descr_mc'] ?>
                    </div>
                <?php
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