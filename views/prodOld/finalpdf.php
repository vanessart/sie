<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$notaFinal = $model->profNotaFinalInLoco();
$prof = $model->prof12Avaliado($id_inst);
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
    Avaliação "in loco"
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
                Tipo de Avaliação
            </td>
            <?php
            if (empty($id_inst)) {
                ?>
                <td>
                    Escola
                </td>
                <?php
            }
            ?>
            <td>
                1° Avaliação
            </td>
            <td>
                2° Avaliação
            </td>
            <td>
                3° Avaliação
            </td>
            <td>
                Nota Final
            </td>
        </tr>
        <?php
        $c = 1;
        foreach ($prof as $v) {
            if (!empty($v['aval_1']) || !empty($v['aval_2']) || !empty($v['aval_3'])) {
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
                        <?= $v['n_pa'] ?>
                    </td>
                    <?php
                    if (empty($id_inst)) {
                        ?>
                        <td>
                            <?= $v['n_inst'] ?>
                        </td>  
                        <?php
                    }
                    ?>

                    <td>
                        <?php
                        $n = round(@$v['aval_1'], 2);
                        echo $n == 0 ? 'Não Avaliado' : $n
                        ?>
                    </td>
                    <td>
                        <?php
                        $n = round(@$v['aval_2'], 2);
                        echo $n == 0 ? 'Não Avaliado' : $n
                        ?>
                    </td>
                    <td>
                        <?php
                         $n = round(@$v['aval_3'], 2);
                        echo $n == 0 ? 'Não Avaliado' : $n
                        ?>
                    </td>
                    <td>
                        <?= round($notaFinal[$v['rm']], 2) ?>
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>
</div>
<?php
if(empty($_REQUEST['n'])){
tool::pdf('L');
}
