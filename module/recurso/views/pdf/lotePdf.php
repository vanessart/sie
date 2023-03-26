<?php
ob_clean();
if (!defined('ABSPATH'))
    exit;
ob_start();
$pdf = new pdf();
$pdf->id_inst = 13;
$pdf->headerAlt = '<img src="' . ABSPATH . '/includes/images/topo.jpg"/>';
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$ids = @$_POST[1];
foreach ($ids as $v) {
    if ($v) {
        $id_chs[] = $v;
    }
}
@$escola = sql::get('instancia', 'n_inst', ['id_inst' => $id_inst], 'fetch')['n_inst'];
?>
<div style="text-align: center; font-weight: bold; font-size: 22px">
    TERMO DE RESPONSABILIDADE PELA GUARDA E USO DE EQUIPAMENTO
    <hr>
</div>
<p style="text-align: justify">
    Declaro que, nesta data, recebi do Município de Barueri, através da Secretaria Municipal de Educação, o equipamento abaixo citado em perfeitas condições de uso, a título de comodato gratuito, para uso exclusivo nas dependências das escolas municipais de Barueri, ou ainda, para a realização de atividades pedagógicas em ambiente externo, comprometendo-me a mantê-lo em perfeito estado de conservação, ficando ciente de que:
</p>
<ul>
    <li>
        Em caso de dano, manutenção, inutilização ou extravio do equipamento deverei requerer uma Ordem de Serviço através do canal próprio e posteriormente entregar o equipamento no Departamento de Informática da Secretaria da Educação;
    </li>
</ul>
<?php
if (!empty($id_chs)) {
    $sql = "select * from recurso_serial c "
            . "join recurso_equipamento m on m.id_equipamento = c.fk_id_equipamento "
            . " where id_serial in (" . implode(',', $id_chs) . ")";
    $query = pdoSis::getInstance()->query($sql);
    $array = $query->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
        <tr>
            <td colspan="3" style="text-align: center; font-weight: bold">
                Relação de Equipamentos - <?= $escola ?>
            </td>
        </tr>
        <tr>
            <td style="width: 5px"></td>
            <td>
                Número de Série
            </td>
            <td>
                Modelo
            </td>
        </tr>
        <?php
        $c = 1;
        foreach ($array as $v) {
            ?>
            <tr>
                <td>
                    <?= $c++ ?>
                </td>
                <td>
                    <?= $v['n_serial'] ?>
                </td>
                <td>
                    <?= $v['n_equipamento'] ?>
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td colspan="3">
                Total de Equipamentos: <?= count($array) ?>
            </td>
        </tr>
    </table>
    <?php
}
?>
<br /><br />
<table style="width: 100%">
    <tr>
        <td style="width: 40%">
            &nbsp;
        </td>
        <td style="text-align: center">
            Barueri, <?= data::porExtenso(date('Y-m-d')) ?>
            <br /><br /><br /><br /><br />
            ______________________________________________
            <br />
            Assinatura do Responsável
        </td>
    </tr>
</table>
<br />
<style>
    .footer {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        background-color: red;
        color: white;
        text-align: center;
    }
</style>

<div class="footer">
    <img style="width: 100%" src="<?= ABSPATH ?>/includes/images/base.png"/>
</div>
<?php
$pdf->exec();
?>
