<?php
$sql = "SELECT data, COUNT(id_pessoa_ed) ct, apagado FROM ge_emails_desativados GROUP BY data ORDER BY data DESC ";
$query = pdoSis::getInstance()->query($sql);
$ed = $query->fetchAll(PDO::FETCH_ASSOC);
$datas = array_column($ed, 'data');
?>
<div class="row">
    <div class="col">
        <?php
        if (in_array(date("Y-m-d"), $datas)) {
            ?>
            <button class="btn btn-danger" type="button">
                Esta ação só pode ser realizada uma vez por dia
            </button>
            <?php
        } else {
            ?>
            <form method="POST">
                <?=
                formErp::hidden([
                    'activeNav' => 2
                ])
                . formErp::hiddenToken('emailsDesativados')
                ?>
                <button class="btn btn-warning" type="submit">
                    Apagar E-mails Inativos
                </button>
            </form>
            <?php
        }
        ?>
    </div>
</div>
<br />
<table class="table table-bordered table-hover table-responsive">
    <tr>
        <td colspan="3" style="text-align: center; font-weight: bold">
            Exportar relação de e-mails desativados
        </td>
    </tr>
    <tr>
        <td>
            Data
        </td>
        <td>
            quant.
        </td>
        <td></td>
        <td></td>
    </tr>
    <?php
    foreach ($ed as $v) {
        ?>
        <tr>
            <td>
                <?= $v['data'] ?>
            </td>
            <td>
                <?= $v['ct'] ?>
            </td>
            <td>
                <?php
                if ($v['apagado'] == 0) {
                    ?>
                    <form method="POST">
                        <?=
                        formErp::hidden([
                            'data' => $v['data'],
                            'activeNav' => 2,
                            'apagado' => 1
                        ])
                        . formErp::hiddenToken('emailRecicla')
                        ?>
                        <button class="btn btn-danger" type="submit">
                            Enviar os e-mails para reciclagem
                        </button>
                    </form>
                    <?php
                } else {
                    ?>
                    <form method="POST">
                        <?=
                        formErp::hidden([
                            'data' => $v['data'],
                            'activeNav' => 2,
                            'apagado' => 0
                        ])
                        . formErp::hiddenToken('emailRecicla')
                        ?>
                        <button class="btn btn-primary" type="submit">
                            Reverter envio dos e-mails para reciclagem
                        </button>
                    </form>
                    <?php
                }
                ?>
            </td>
            <td>
                <form action="<?= HOME_URI ?>/cit/pdf/emailDel" target="_blank" method="POST">
                    <?=
                    formErp::hidden(['data' => $v['data']])
                    . formErp::button('Exportar')
                    ?>
                </form>
            </td>
        </tr>
        <?php
    }
    ?>
</table>


