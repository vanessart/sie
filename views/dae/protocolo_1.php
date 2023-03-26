<?php
ob_start();
?>
<table style="width: 100%">
    <tr>
        <td rowspan="2">
            <img style="width: 100px" src="<?php echo HOME_URI ?>/views/_images/brasao.png"/>
        </td>
        <td style="text-align: center; font-size: 22px; font-weight: bold  ">
            Prefeitura Municipal de Barueri
            <br />
            Secretaria de Educação
            <br /><br />
        </td>
    </tr>
    <tr>
        <td style="font-weight: bold; text-align: center">
            Depto. Técnico da Tecnologia de Informação Educacional - DTTIE 
        </td>
    </tr>
</table>

<hr>
<div class="fieldTop">
    Registro de Acompanhamento de Serviços
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
            . " from dttie_suporte_trab su  "
            . "left join dttie_list_suporte se on se.id_list = su.tipo_sup "
            . "left join dttie_resp_tec re on re.id_resp = su. resp_sup "
            . " where id_sup = '" . $id . "'";
    $query = $model->db->query($sql);
    @$campos = $query->fetch();
    if (!empty($campos) && substr($rastro, 3, 4) == $campos['rastro_sup']) {
        ?>
        <table class="table table-bordered table-striped">
            <tr>
                <td style="width: 120px">
                    Setor
                </td>
                <td colspan="2">
                    <?php echo @$campos['local_sup'] ?>
                </td>
            </tr>
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
                    Telefone
                </td>
                <td>
                    <?php echo @$campos['tel1'] . '&nbsp;&nbsp;&nbsp;' . @$campos['tel2'] ?>
                </td>
                <td rowspan="6" style="width: 160px">
                    <?php
                    $code = HOME_URI . '/app/code/php/qr_img.php?d=https://portal.educ.net.br/ge/dttie/webprot?id=' . '002' . @$campos['rastro_sup'] . @$campos['id_sup'] . '&.PNG';
                    ?>
                    <img src = "<?php echo $code ?>"/>
                </td>
            </tr>
            <tr>
                <td>
                    E-mail
                </td>
                <td>
                    <?php echo @$campos['email'] ?>
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
                    Resp. Técnico
                </td>
                <td>
                    <?php echo @$campos['n_resp'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Data Inicial
                </td>
                <td colspan="2">
                    <?php echo data::converte(@$campos['dt_sup']) ?>
                </td>
            </tr>
            <tr>
                <td>
                    Descrição
                </td>
                <td colspan="2">
                    <?php echo @$campos['descr_sup'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Devolutiva
                </td>
                <td colspan="2">
                    <?php echo @$campos['devol_sup'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Observações
                </td>
                <td colspan="2">
                    <?php echo @$campos['obs_sup'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Código do Rastreio
                </td>
                <td colspan="2">
                    002<?php echo @$campos['rastro_sup'] . @$campos['id_sup'] ?>(Rastreie seu pedido no site: http://educacao.barueri.sp.gov.br/)
                </td>
            </tr>
        </table>
        <br /><br />
        <?php
        if (!empty($_REQUEST['p'])) {
            ?>
            <div class="text-right">
                Barueri, <?php echo date("d") ?> de <?php echo data::mes(date("m")) ?> de <?php echo date("Y") ?>
            </div>
            <br /><br /><br /><br /><br /><br /><br /><br /><br />
            <div style="width: 200px; float: left;text-align: center">
                _______________________
                <br />
                Responsável Técnico
            </div>
            <div style="width: 200px; float: right; text-align: center">
                _______________________
                <br />
                Responsável pelo Setor
            </div>
            <?php
        } else {
            
            $model->logSuporte(@$id);
        }
        tool::pdf();
    } else {
        ?>
        <script>
            window.location.href = "<?php echo HOME_URI ?>/pub/rastro?e=1"
        </script>
        <?php
    }
} else {
    ?>
    <script>
        window.location.href = "<?php echo HOME_URI ?>/pub/rastro?e=1"
    </script>
    <?php
}




