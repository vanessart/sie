<?php if (!defined('ABSPATH')) exit; ?>
<div class="field">
    <table>
        <tr>
            <td style="width: 200px">
                Selecione um Sistema:
            </td>
            <td>
                <?php
                echo $model->selectSistemas('fk_id_sistema');
                ?>
            </td>
            <td style="padding-left: 20px;">
                <?php
                if (!empty($_POST['fk_id_sistema'])) {
                    ?> 
                    <div style="float: left">
                        Selecione um Nível de Acesso: 
                    </div>
                    <div style="float: left; padding-left: 10px">
                        <?php
                        echo $model->selectNivel('fk_id_nivel');
                    }
                    ?>
                </div>
            </td>

        </tr>
    </table>
</div>
