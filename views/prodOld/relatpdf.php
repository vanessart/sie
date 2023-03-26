<?php
if (!function_exists('ceiling')) {

    function ceiling($number, $significance = 1) {
        return ( is_numeric($number) && is_numeric($significance) ) ? (ceil($number / $significance) * $significance) : false;
    }

}
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
if (!empty($id_inst)) {
    $sql = "select * from prod_main where fk_id_inst = $id_inst ";
} else {
    $sql = "select * from prod_main ";
}
$query = pdoSis::getInstance()->query($sql);
$fun = $query->fetchAll(PDO::FETCH_ASSOC);
ob_start();

$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$escola = new escola($id_inst);

$notaTurma = $model->notaTurmas($id_inst);
?>
<div class="fieldBody">
    <br />
    <div style="text-align: center; font-size: 20px">
        <?php echo $escola->_nome ?>
        - 
        Bonus Produtividade
    </div>
    <br />
    <table border=1 cellspacing=0 cellpadding=1 style="width: 800px">
        <tr>
            <td style="text-align: center">
                Média da Escola
            </td>
        </tr>
        <tr>
            <td style="text-align: center">
                <?php
                $ne = sql::get('prod_nota_esc', 'nota', ['id_inst' => $id_inst], 'fetch')['nota'];
                echo ceiling($ne, 0.1);
                ?>
            </td>
        </tr>
    </table>
    <br /><br />
    <table border=1 cellspacing=0 cellpadding=1 style="width: 800px">
        <tr>
            <?php
            $sql = "SELECT t.n_turma, n.nota FROM prod_nota_turma n "
                    . " JOIN ge_turmas t on t.id_turma = n.id_turma_nota "
                    . " WHERE t.fk_id_inst = $id_inst "
                    . " order by n_turma ";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            $c = 1;
            foreach ($array as $v) {
                ?>
                <td style="text-align: center; width: 160px">
                    <?php echo $v['n_turma'] . '<br /><br />' . ceiling($v['nota'], 0.1) ?>
                </td>
                <?php
                if ($c % 5 == 0) {
                    ?>
                </tr>
                <tr>
                    <?php
                }
                $c++;
            }
            ?>
        </tr>
    </table>
    <br /><br />
    <style>
        td{
            padding: 8px;
        }
    </style>
    <?php
    foreach ($fun as $v) {

        if (!empty($v['metodo'])) {
            ?>
            <table border=1 cellspacing=0 cellpadding=1 style="width: 800px">
                <tr>
                    <td style="width: 400px; padding: 15px">
                        Nome: <?php echo tool::abrevia($v['n_pessoa']) ?>
                        <br /><br />
                        Matrícula:  <?php echo $v['rm'] ?>
                        <br /><br />
                        Nota: <?php echo $v['nota_final'] ?>
                        <br /><br />
                        Porcentagem: <?php echo $v['perc'] ?>
                        <!--
                        <br /><br />
                        Confirma e ratifica as informações ao lado?
                        <br /><br />
                        (&nbsp;&nbsp;&nbsp;) Sim
                        <br /><br />
                        (&nbsp;&nbsp;&nbsp;) Não
                        <br /><br /><br /><br />
                        <div style="text-align: center">
                            __________________________________
                            <br />
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Assinatura
                        </div>
                        -->
                        <?php
                    }
                    ?>
                </td>
                <td style="padding: 20px">
                    <?php
                    echo $cal = $v['calculo'];
                    //echo $cal = str_replace(' foi ', ' será ', $cal);
                    // $cal = str_replace('Nota', '', $cal);
                    ?>
                </td>
            </tr>
        </table>
        <br /><br />
    </div>

    <?php
}
tool::pdfEscola();
?>

