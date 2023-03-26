<?php
if (!defined('ABSPATH'))
    exit;
@$fk_id_sistema = $_POST['fk_id_sistema'];
@$fk_id_nivel = $_POST['fk_id_nivel'];
?>
<div class="fieldBody">
    <div class="fieldTop">
        Configuração dos Menus
    </div>
    <table>
        <tr>
            <td>
                <?php
                formulario::selectDB('sistema', 'fk_id_sistema', 'Selecione um Sistema: ', NULL, NULL, 1, NULL, NULL, ['ativo' => 1,'>'=>'n_sistema'])
                ?>
            </td>
            <td style="padding-left: 20px;">
                <?php
                if (!empty($_POST['fk_id_sistema'])) {
                    ?> 
                    <div style="float: left; padding-left: 10px">
                        <?php
                        $options = sistema::niveis($fk_id_sistema);
                        formulario::select('fk_id_nivel', $options, ' Selecione um Nível de Acesso:', NULL, 1, ['fk_id_sistema' => $fk_id_sistema]);
                    }
                    ?>
                </div>
            </td>
        </tr>
    </table>
    <br /><br />
    <?php
    if (!empty(@$fk_id_nivel)) {
        $form = $model->formPagNivel(@$fk_id_sistema, @$fk_id_nivel);

        include_once ABSPATH . '/views/relat/table.php';
    }
    ?>
</div>
