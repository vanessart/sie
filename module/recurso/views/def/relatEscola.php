<?php
ob_clean();
if (!defined('ABSPATH'))
    exit;
ob_start();
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$equipamentos = $model->equipamentoGet(null,$id_inst);
$n_local = sql::idNome('recurso_local');
?>
<div style="text-align: center; font-weight: bold; font-size: 22px">
    Relatório de Equipamentos
</div>
<br /><br />
<?php
if (!empty($equipamentos)) {
    foreach ($equipamentos as $num => $local) {?>
        <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
            <tr>
                <td style="text-align: center" colspan="4">
                     <?php
                    if ($num<1) {
                        ?>
                        Não Alocado
                        <?php
                    } else {
                        ?>
                        <?=$n_local[$num] ?>
                        <?php
                    }?>
                </td>
            </tr>
            <tr>
                <td>
                    Modelo/Lote
                </td>
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
            foreach ($local as $v) {
                ?>
                <tr>
                    <td>
                        <?= $v['n_equipamento'] ?>
                    </td>
                    <td>
                        <?= $v['n_serial'] ?>
                    </td>
                    <td>
                        <?= $v['n_situacao'] ?>
                    </td>
                    <td>
                        <?php
                        if ($v['id_situacao'] == 2) {
                            echo $v['n_pessoa'] . ' (' . $v['id_pessoa'] . ')';
                        } else if($v['fk_id_inst']==13) {
                            echo 'Secretaria de Educação';
                        }else{
                            echo "Escola";
                        }?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <?php
    }?>
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
    <?php
}else{
   echo "Não há equipamentos nesta instância.";
}?>
<div style="page-break-after: always"></div>
<?php
tool::pdfEscola('L', $id_inst);
