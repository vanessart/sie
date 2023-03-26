<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$prof = $model->adi($id_inst, NULL, 1);
ob_start();
?>
<style>
    td{
        padding: 4px;
    }
</style>
<table style="width: 100%">
    <tr>
        <td>
            <img style="width: 150px; height: 45px" src="<?php echo HOME_URI ?>/views/_images/assinco.jpg" width="510" height="127" alt="logo"/>
        </td>
        <td>

        </td>
        <td style="width: 150px">
            <img style="width: 150px; height: 45px" src="<?php echo HOME_URI ?>/views/_images/logo.png" width="510" height="127" alt="logo"/>

        </td>
    </tr>
</table>
<div style="font-weight: bold; text-align: center; font-size: 18px">
    ADIs não avaliadas
    <?php
    if ($id_inst) {
        echo '<br /><br />' . sql::get('instancia', 'n_inst', ['id_inst' => $id_inst], 'fetch')['n_inst'];
    }
    ?>
</div>
<br />
<div class="Body">
    <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
        <tr>
            <td>

            </td>
            <td>
                Funcionário
            </td>
            <td>
                Matrícula
            </td>
            <td>
                Escola
            </td>
        </tr>
        <?php
        $c = 1;
        foreach ($prof as $v) {
                ?>
                <tr>
                    <td>
                        <?= $c++ ?>
                    </td>
                    <td>
                        <?= $v['n_pessoa'] ?>
                    </td>
                    <td>
                        <?= $v['rm'] ?>
                    </td>
                    <td>
                        <?= $v['n_inst'] ?>
                    </td>
                </tr>
                <?php
        }
        ?>
    </table>
</div>
<?php
if (empty($_REQUEST['n'])) {
    tool::pdf('L');
}

