<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$escola = new escola($id_inst);
$nomeEsc = $escola->_nome;
$chromes = $model->chromesEscola($id_inst);
ob_start();
?>
<div style="text-align: center; font-weight: bold; font-size: 22px">
    Relatório de Chromebooks
</div>
<br /><br />
<?php
foreach ($chromes as $carrinho => $ch) {
    ?>
    <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
        <tr>
            <td style="text-align: center" colspan="3">
                <?php
                if (empty($carrinho)) {
                    echo 'Fora do Carrinho';
                } else {
                    echo 'Carrinho ' . $carrinho;
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>
                Serial
            </td>
            <td>
                Situação
            </td>
            <td>
                Responsável
            </td>
        </tr>
        <?php
        foreach ($ch as $v) {
            ?>
            <tr>
                <td>
                    <?= $v['serial'] ?>
                </td>
                <td>
                    <?= $v['n_cs'] ?>
                </td>
                <td>
                    <?php
                    if (!empty($v['n_cs'])) {
                        if (empty($v['n_pessoa'])) {
                            echo 'Escola';
                        } else {
                            echo $v['n_pessoa'] . ' (' . $v['fk_id_pessoa'] . ')';
                        }
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <br /><br />
    <table style="width: 100%">
        <tr>
            <td style="width: 40%">
                &nbsp;
            </td>
            <td style="text-align: center">
                <?= CLI_CIDADE ?>, <?= data::porExtenso(date('Y-m-d')) ?>
                <br /><br /><br /><br /><br />
                ______________________________________________
                <br />
                Diretor(a)
            </td>
        </tr>
    </table>
    <br />
    <div style="page-break-after: always"></div>
    <?php
}
?>


<?php
tool::pdfEscola('L');