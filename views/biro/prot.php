<?php
ob_start();
?>
<table style="width: 100%">
    <tr>
        <td rowspan="2">
            <img style="width: 100px" src="<?php echo HOME_URI ?>/views/_images/brasao.png"/>
        </td>
        <td style="text-align: center; font-size: 22px; font-weight: bold  ">
            PrefeituraMunicipal de Barueri
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
            . " local, n_pessoa, tel1, email, id_trab, se.n_list as servico, status_biro, dt_biro, dt_prev, "
            . "descr_biro, obs_biro, rastro, qt_caderno,total_cp, qt_folha, f.n_list as formato, frente_verso_biro, cor_biro, acabamento_biro "
            . " from biro_trab su  "
            . "left join biro_list se on se.id_list = su.tipo "
            . "left join biro_formato f on f.id_list = su.formato "
            . " where id_trab = '" . $id . "'";
    $query = $model->db->query($sql);
    @$campos = $query->fetch();
    if (!empty($campos) && substr($rastro, 3, 4) == $campos['rastro']) {
        ?>
        <table class="table table-bordered table-striped">
            <tr>
                <td style="width: 120px">
                    Setor
                </td>
                <td colspan="2">
                    <?php echo @$campos['local'] ?>
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
                    $code = HOME_URI . '/app/code/php/qr_img.php?d=https://portal.educ.net.br/ge/dttie/supprot?id=' . '002' . @$campos['rastro'] . @$campos['id_trab'] . '&.PNG';
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
                    <?php echo @$campos['id_trab'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Status
                </td>
                <td>
                    <?php echo @$campos['status_biro'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Data Inicial
                </td>
                <td>
                    <?php echo @$campos['dt_sup'] ?>
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
            <?php
            if (!empty(@$campos['qt_caderno'])) {
                ?>
                <tr>
                    <td>
                        Quant. Cadernos
                    </td>
                    <td colspan="2">
                        <?php echo @$campos['qt_caderno'] ?>
                    </td>
                </tr>
                <?php
            }
            if (!empty(@$campos['total_cp'])) {
                ?>
                <tr>
                    <td>
                        Quant. Cópias
                    </td>
                    <td colspan="2">
                        <?php echo @$campos['total_cp'] .(empty($campos['frente_verso_biro'])?'':'&nbsp;&nbsp;&nbsp;&nbsp;Frente e Verso').(empty($campos['cor_biro'])?'':'&nbsp;&nbsp;&nbsp;&nbsp;Coloridas')?>
                    </td>
                </tr>
                <?php
            }
            if (!empty(@$campos['qt_folha'])) {
                ?>
                <tr>
                    <td>
                        Quant. Folhas
                    </td>
                    <td colspan="2">
                        <?php echo @$campos['qt_folha'].' ('.@$campos['formato'].')' ?>
                    </td>
                </tr>
                <?php
            }
            if (!empty(@$campos['acabamento_biro'])) {
                ?>
                <tr>
                    <td>
                        Acabamento
                    </td>
                    <td colspan="2">
                        <?php echo @$campos['acabamento_biro'] ?>
                    </td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td>
                    Prev. de Entrega
                </td>
                <td colspan="2">
                    <?php echo @$campos['dt_prev'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Descrição
                </td>
                <td colspan="2">
                    <?php echo @$campos['descr_biro'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Observações
                </td>
                <td colspan="2">
                    <?php echo @$campos['obs_biro'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Código do Rastreio
                </td>
                <td colspan="2">
                    003<?php echo @$campos['rastro'] . @$campos['id_trab'] ?>(Rastreie seu pedido no site: portal.educ.net.br)
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

            $model->logbiro(@$id);
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




