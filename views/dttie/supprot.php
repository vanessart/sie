<?php
ob_start();
?>
<table style="width: 100%">
    <tr>
        <td rowspan="2">
            <img style="width: 100px" src="<?php echo HOME_URI ?>/views/_images/brasao.jpg"/>
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

$ter = $model->pegaterceirizada();

if (!empty($id)) {
    $sql = "select "
            . " local_sup, n_pessoa, tel1, email, id_sup, n_list as servico, status_sup, su.resp_sup, n_resp, dt_sup, dt_prev_sup, descr_sup, devol_sup, obs_sup, rastro_sup "
            . " from dttie_suporte_trab su  "
            . "left join dttie_list_suporte se on se.id_list = su.tipo_sup "
            . "left join dttie_resp_tec re on re.id_resp = su. resp_sup "
            . " where id_sup = '" . $id . "'";
    if (in_array(tool::id_pessoa(), [1, 5])) {
        echo '<br />' . $sql;
    }

    $query = $model->db->query($sql);
    @$campos = $query->fetch();

    $sql = "SELECT * FROM `dttie_suport_diag` WHERE `fk_id_sup` = $id limit 0,1 ";
    $query = $model->db->query($sql);
    @$descricao = $query->fetch()['descr'];
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
                    <?php
                    
                    if (in_array($campos['resp_sup'], ['001', '002', '003', '004', '005','006', '007'])){
                        echo $ter[@$campos['resp_sup']];    
                    } else {
                        echo @$campos['n_resp'];
                    }
                    ?>
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
                    - 
                    <?php echo @$descricao ?>
                    <br /><br />
                    <?php
                    $descr = sql::get(['dttie_suport_diag', 'pessoa'], 'n_pessoa, lado, descr, data', ['fk_id_sup' => @$campos['id_sup'], '>' => 'data']);
                    ?>
                    <div>
                        <?php
                        foreach ($descr as $d) {
                            ?>
                            <div>
                                <span><?php echo $d['lado'] ?> - <?php echo $d['n_pessoa'] ?> disse: (<?php echo data::converteBr(substr($d['data'], 0, 11)) ?>  <?php echo substr($d['data'], 11) ?>)</span><br /><?php echo $d['descr'] ?>
                            </div>
                            <br />
                            <?php
                        }
                        ?>
                    </div>
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




