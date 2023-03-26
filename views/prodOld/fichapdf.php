<?php
ob_start();
$visita = filter_input(INPUT_POST, 'visita', FILTER_SANITIZE_NUMBER_INT);
$id_pa = filter_input(INPUT_POST, 'id_pa', FILTER_SANITIZE_NUMBER_INT);
if (empty($visita) || empty($id_pa)) {
    echo 'Preencha todos os campos';
    exit();
}
$aval = sql::get('prod1_aval', '*', ['id_pa' => $id_pa], 'fetch');
$items = $model->eixoItens($id_pa, $visita);

if ($id_pa == 8 && $visita == 2) {
    $diretor = 1;
}
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
    <?php
    if (empty($diretor)) {
        ?>
        Avaliação "in loco" dos <?php echo $aval['n_pa'] ?> - <?php echo $visita ?>ª Visita
        <?php
    } else {
        ?>
        Avaliação dos <?php echo $aval['n_pa'] ?> pelo Diretor da Unidade Escolar
        <?php
    }
    ?>

</div>
<br />
<table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
    <tr>
        <td style="width: 180px">
            Nome da Escola
        </td>
        <td>

        </td>
    </tr>
    <tr>
        <td>
            Nome do(a) Funcionário(a)
        </td>
        <td>

        </td>
    </tr>
</table>
<?php
if (empty($diretor)) {
    ?>
    <table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
        <tr>
            <td style="width: 50%">
                Turma(s)
            </td>
            <td>
                Horário (Ínicio)
            </td>
            <td>
                Horário (Termino)
            </td>
            <td>
                Nota
            </td>
        </tr>
        <tr>
            <td>
                &nbsp;
            </td>
            <td>

            </td>
            <td>

            </td>
            <td>

            </td>
        </tr>
    </table>
    <?php
}
?>
<span style="font-weight: bold">S</span> = Satisfatório&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-weight: bold">NS</span> = Não Satisfatório
<table style="width: 100%" border=1 cellspacing=0 cellpadding=2 bordercolor="666633"> 
    <tr>
        <td style="font-weight: bold; text-align: center">
            Critérios 
        </td>
        <td style="width: 50px; font-weight: bold; text-align: center">
            S 
        </td>
        <td style="width: 50px; font-weight: bold; text-align: center">
            NS
        </td>
    </tr>
    <?php
    foreach ($items as $eixo => $itens) {
        if (empty($diretor)) {
            ?>
            <tr>
                <td colspan="3" style="text-align: center; background-color: papayawhip">
                    <?php echo $eixo ?>
                </td>
            </tr>
            <?php
        }
        foreach ($itens as $v) {
            ?>
            <tr>
                <td>
                    <?php echo @$v['ordem_item'] . '. ' . @$v['n_item'] ?>
                </td>
                <td>

                </td>
                <td>

                </td>
            </tr>
            <?php
            if (empty($diretor)) {
                ?>
                <tr>
                    <td colspan="3">
                        Obs:
                    </td>
                </tr>
                <?php
            }
        }
    }
    if (!empty($diretor)) {
        ?>
        <tr>
            <td colspan="3">
                Obs:
            </td>
        </tr>
        <?php
    }
    ?>
</table>
<table style="width: 100%">
    <tr>
        <td style="text-align: center">
            _____ de _______________________ de <?php echo date("Y") ?>
        </td>
        <td style="width: 400px; padding-right: 50px">
            <br /><br /><br /><br />
            Assinatura:___________________________________
            <br /><br />
            Nome:_______________________________________
            <br /><br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <?php
            if (empty($diretor)) {
                ?>
                Avaliadora Externa
                <?php
            } else {
                ?>
                Diretor da Unidade Escolar
                <?php
            }
            ?>
        </td>
    </tr>
</table>
<?php
tool::pdf('L');
