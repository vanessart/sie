<?php
$path = ABSPATH . "/template/";
$dir = scandir($path);
?>
<div class="fieldBody">
    <div class="fieldTop">
        Gerenciamento de Tema
    </div>
    <table style="width: 50%">

        <?php
        foreach ($dir as $v) {
            if (substr($v, 0, 1) != '.') {
                ?>
                <tr>
                    <td>
                        <img style="width: 80%" src="<?php echo HOME_URI ?>/template/<?php echo $v ?>/images/template_preview.png"/>
                    </td>
                    <td class="fieldrow4" style="width: 20%; text-align: center">
                        <?php echo $v ?>
                        <br /><br />
                        <?php
                        if ($v == sql::get('tema_default', 'n_td', NULL, 'fetch')['n_td']) {
                            ?>
                            <img style="width: 60%" src = "<?php echo HOME_URI ?>/views/_images/y.png"/>
                            <?php
                        }
                        ?>
                        <br /><br />
                        <form method="POST">
                            <?php echo DB::hiddenKey('tema_default', 'replace') ?>
                            <?php echo formulario::hidden(['1[id_td]'=>1,'1[n_td]'=>$v]) ?>
                            <?php echo formulario::button('Alterar') ?>
                        </form>
                        <br /><br />
                        É Necessário reiniciar o Subsistema para efetuar a mudança.
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr style="height: 3px">
                    </td>
                </tr>
                <?php
            }
        }
        ?>
    </table>

</div>