<div class="body">

    <div class="fieldTop">
        Selecionar Aluno(s)
    </div>
    <div style="padding-left: 20px" class="panel panel-default">
        <br />
        <?php
        // $esc_s = sql::get(['pessoa', 'ge_encaminhamento'], '*', ['escola_origem' => tool::id_inst(), 'status' => 1, '>' => 'n_pessoa']);
        $esc_s = wpegaalunocarta();
        ?>

        <div class="row">
            <div class="col-9" style="padding: 5px;">
                <div class="border" style="min-height: 50vh; width: 98%">
                    <form target="_blank" action="<?php echo HOME_URI ?>/gest/encaminhamentopdf" method="POST" id="al"> 
                        <input type="hidden" name="selecao2" value="1" />
                        <table class="table table-striped table-hover" style="font-weight: bold">
                            <tr>
                                <td>
                                    ID
                                </td>
                                <td>
                                    RSE
                                </td>
                                <td>
                                    RA
                                </td>
                                <td>
                                    Nome Aluno
                                </td>
                                <td>
                                    Todos <input type="checkbox" name="chkAll2" onClick="checkAll2(this)" />
                                </td>
                            </tr>
                            <?php
                            foreach ($esc_s as $w) {
                                ?>
                                <tr>
                                    <td style="text-align: left">
                                        <?php echo $w['id_encam'] ?>
                                    </td>
                                    <td style="text-align: left">
                                        <?php echo $w['id_pessoa'] ?>
                                    </td>
                                    <td>
                                        <?php echo $w['ra'] . '-' . $w['ra_dig'] ?>
                                    </td>
                                    <td style="text-align: left">
                                        <?php echo $w['n_pessoa'] ?>
                                    </td>
                                    <td>
                                        <input class="checkatz2" type="checkbox" name="as2[]" value="<?php echo $w['id_encam'] ?>" />
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </form>
                </div>
            </div>
            <div class="col-3" style="padding: 5px">
                <button class="btn btn-primary" name="selecao2" value="Selecao2" onclick="document.getElementById('al').submit()" type="submit"> 
                    <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir    
                </button>
            </div>
        </div>
    </div>
</div>

<script>

    function checkAll2(o) {
        var boxes = document.getElementsByClassName("checkatz2");
        for (var x = 0; x < boxes.length; x++) {
            var obj = boxes[x];
            if (obj.type == "checkbox") {
                if (obj.name != "chkAll2")
                    obj.checked = o.checked;
            }
        }
    }
</script>
<?php

function wpegaalunocarta() {

    $sql = "SELECT p.id_pessoa, p.n_pessoa, p.ra, p.ra_dig, en.id_encam,"
            . " en.status, ta.fk_id_turma FROM pessoa p"
            . " JOIN ge_encaminhamento en ON en.fk_id_pessoa = p.id_pessoa"
            . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = en.fk_id_turma AND ta.fk_id_pessoa = en.fk_id_pessoa"
            . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
            . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
            . " WHERE en.escola_origem = '" . tool::id_inst() . "' AND en.status = 1"
            . " AND pl.at_pl = 1";

    $query = pdoSis::getInstance()->query($sql);
    $a = $query->fetchAll(PDO::FETCH_ASSOC);

    return $a;
}
