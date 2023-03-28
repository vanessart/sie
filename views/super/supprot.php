<?php
ob_start();
?>
<table style="width: 100%">
    <tr>
        <td rowspan="2">
            <img style="width: 100px" src="<?php echo HOME_URI ?>/views/_images/brasao.png"/>
        </td>
        <td style="text-align: center; font-size: 22px; font-weight: bold  ">
            <?= CLI_NOME ?>
            <br />
            Secretaria de Educação
            <br /><br />
        </td>
    </tr>
    <tr>
        <td style="font-weight: bold; text-align: center">
           Suprote à Infoeducação  
        </td>
    </tr>
</table>

<hr>
<div class="fieldTop">
    Registro de Acompanhamento
</div>
<br />
<?php
if (!empty($_REQUEST['id'])) {
    $rastro = $_REQUEST['id'];
    $id = substr($rastro, 7);
} else {
    ?>
    <script>
        window.location.href = "<?php echo HOME_URI ?>/pub/rastro?e=1"
    </script>
    <?php
}
if (!empty($id)) {
    $sql = "select "
            . " local_sup, n_pessoa, tel1, email, id_sup, n_list as servico, status_sup, n_resp, dt_sup, dt_prev_sup, descr_sup, devol_sup, obs_sup, rastro_sup "
            . " from super_suporte_trab su  "
            . "left join super_list_suporte se on se.id_list = su.tipo_sup "
            . "left join super_resp_tec re on re.id_resp = su. resp_sup "
            . " where id_sup = '" . $id . "'";
    $query = pdoSis::getInstance()->query($sql);
        @$campos = $query->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM `super_suport_diag` d "
            . " left join super_suporte_trab t on t.id_sup = d.fk_id_sup"
            . " WHERE `id_sup` = $id ";
    $query = pdoSis::getInstance()->query($sql);
        @$descricao = $query->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($campos) && substr($rastro, 3, 4) == $campos['rastro_sup']) {
        ?>
        <table class="table table-bordered table-striped">
            <tr>
                <td>
                    Solicitante
                </td>
                <td colspan="2">
                    <?php echo @$campos['n_pessoa'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Protocolo
                </td>
                <td>
                    <?php echo @$campos['id_sup'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Serviço
                </td>
                <td>
                    <?php echo @$campos['servico'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Status
                </td>
                <td>
                    <?php echo @$campos['status_sup'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Data Inicial
                </td>
                <td>
                    <?php echo data::converte(@$campos['dt_sup']) ?>
                </td>
            </tr>
            <tr>
                <td>
                    Descrição
                </td>
                <td>
                    <?php echo @$campos['descr_sup'] ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center">
                    Histórico
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br />
                    <?php
                    if (!empty($descricao)) {
                        foreach ($descricao as $d) {
                            ?>
                            <div style="font-style: italic; color: black">
                                <span style="border-radius: 10px"><span style="color: #0B94EF"><?php echo $d['lado'] ?> - <?php echo $d['n_pessoa'] ?> disse: (<?php echo data::converteBr(substr($d['data'], 0, 11)) ?>  <?php echo substr($d['data'], 11) ?>)</span><br /><?php echo $d['descr'] ?></span>
                            </div>
                            <br />
                            <?php
                        }
                    }
                    ?>
                </td>
            </tr>
        </table>
        <br /><br />
        <?php
        tool::pdf();
    } 
} 




