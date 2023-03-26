<?php
$aval = filter_input(INPUT_POST, 'aval', FILTER_SANITIZE_NUMBER_INT);

$sql = "SELECT * FROM `prod1_nota_item` WHERE `fk_id_pv` = " . $in['id_pv'];
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($array as $v) {
    $resp[$v['fk_id_item']] = $v;
}

$itemsOrd = $model->eixoItens($tipoAval);
if (!empty($itemsOrd)) {
    ?>
    <br /><br />
    <form method="POST">
        <?php
        $hidden1 = [
            '1[fk_id_pessoa]' => $dados['id_pessoa'],
            '1[iddisc]' => @$dados['iddisc'],
            '1[id_pv]' => @$dados['id_pv'],
            'continuar' => 1
        ];
        unset($hidden[1]);
        unset($hidden['sqlToken']);
        unset($hidden['formToken']);
        echo form::hidden($hidden);
        ?>
        <table class="table table-bordered table-hover table-responsive table-striped">
            <?php
            foreach ($itemsOrd as $eixo => $item) {
                ?>
                <tr>
                    <td colspan="4" style="background-color: #f7e1b5; text-align: center; font-weight: bold">
                        <?php echo $eixo ?>
                    </td>
                </tr>
                <?php
                foreach ($item as $v) {
                    if ($v['valor' . $aval] > 0) {
                        ?>

                        <tr>  
                            <td style="width: 2%">
                                <?php echo $v['ordem_item'] ?>
                            </td>
                            <td style="width: 8%">
                                <label style="white-space: nowrap ">
                                    <input type="radio" <?php echo (@$resp[$v['id_item']]['nota'] > 0 || empty($visita['nota'])) ? 'checked' : '' ?>  name="nota[<?php echo $v['id_item'] ?>]" value="<?php echo $v['valor' . $aval] ?>" />
                                    Satisfatório
                                </label>
                                <br /><br />
                                <label style="white-space: nowrap ">
                                    <input type="radio" <?php echo (@$resp[$v['id_item']]['nota'] > 0 || empty($resp)) ? '' : 'checked' ?> name="nota[<?php echo $v['id_item'] ?>]" value="0" />
                                    Insatisfatório
                                </label>
                            </td>

                            <td style="width: 35%">
                                <?php echo $v['n_item'] ?>
                            </td>
                            <td style="width: 55%">
                                <input type="hidden" name="id_ni[<?php echo $v['id_item'] ?>]" value="<?php echo @$resp[$v['id_item']]['id_ni'] ?>" />
                                <textarea placeholder="Observações" name="obs[<?php echo $v['id_item'] ?>]" style="width: 100%"textarea><?php echo @$resp[$v['id_item']]['obs'] ?></textarea>
                            </td>
                        </tr>

                        <?php
                    }
                }
            }
            ?>
        </table>
        <br /><br />
        <div class="row">
            <div class="col-sm-12 text-center">
                <?php
                echo form::hidden([
                    'lancaNotas' => 1,
                    'last_id' => $in['id_pv'],
                    'aval' => NULL
                ]);
                echo form::button('Salvar');
                ?>
            </div>
        </div>
    </form>
    <?php
}

