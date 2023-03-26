<?php
if (!defined('ABSPATH'))
    exit;

$dados = sql::get('pl_passelivre', '*', ['fk_id_pl_status' => 1]);
$es_n = $model->pegaescolas();
?>

<head>
    <style>
        .topo{
            font-size: 12pt;
            font-weight:bold;
            text-align: center;
            border-style: solid;
            border-width: 0.5px;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 2px;
            padding-bottom: 2px;
        }
    </style>
</head>

<br />
<div class="body">
    <div style="font-weight:bolder; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center; padding: 8pt">
        Lançamentos de Lote
    </div>
    <br />
</div>

<?php
if (!empty($dados)) {
    ?>
    <div class="body">
        <form method="POST">
            <table style="width: 100%">
                <thead>
                    <tr>
                        <!--
                        <td class="topo">
                            ID
                        </td>
                        -->
                        <td class="topo">
                            RA
                        </td>
                        <td class="topo">
                            Nome Aluno
                        </td>   
                        <td class="topo">
                            Nome Escola
                        </td> 
                        <td class="topo">
                            Lote
                        </td>
                        <td class="topo">
                            Data de Retirada
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($dados as $v) {
                        ?>
                        <tr>
                            <!--
                            <td class="topo">
                                <?= $v['id_passelivre'] ?>
                            </td>
                            -->
                            <td class="topo">
                                <?= $v['ra'] ?>
                            </td>
                            <td class="topo" style="text-align:left">
                                <?= ($v['acompanhante'] == "Sim") ? '*' . $v['nome'] : $v['nome'] ?>
                            </td>
                            <td class="topo">
                                <?= $es_n[$v['cie']] ?>
                            </td>
                            <td class="topo" style="vertical-align: middle">
                                <input id="<?= $v['id_passelivre'] ?>" type="text" name="<?= $v['id_passelivre'] . '[lote]' ?>" value= "<?= $v['lote'] ?>" style="text-align: center" />
                            </td>
                            <td class="topo">
                                <input id="<?= $v['id_passelivre'] ?>" type="date" name="<?= $v['id_passelivre'] . '[dt_inicio_passe]' ?>" value="<?= $v['dt_inicio_passe'] ?>" style="text-align: center" />  
                                <input id="<?= $v['id_passelivre'] ?>" type="hidden" name="<?= $v['id_passelivre'] . '[id_passelivre]' ?>" value="<?= $v['id_passelivre'] ?>" />
                                <input id="<?= $v['id_passelivre'] ?>" type="hidden" name="<?= $v['id_passelivre'] . '[fk_id_pl_status]'?>" value="7" />
                                
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>                
            </table>
            <!--
            <footer>
                <div style="color: Red; font-size: 8pt; font-weight: bold ">
                    * = Acompanhante
                </div>
            </footer>
            -->
            <br />
            <div style="text-align: center">
                <input type="hidden" name="gravalote" value="1" />
                <input class="btn btn-success"type="submit" value="Salvar" />
            </div>
        </form>
    </div>
    <?php
}
?>
