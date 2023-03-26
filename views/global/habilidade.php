<?php
$id_gl = @$_POST['id_gl'];
$aval = sql::get('global_aval', 'id_gl, n_gl', ['ativo' => 1, '>' => 'n_gl']);
if (count($aval) == 1) {
    $id_gl = current($aval)['id_gl'];
}
if (!empty($id_gl)) {
    $dados = sql::get('global_aval', '*', ['id_gl' => $id_gl], 'fetch');
    $es = str_replace('|', ',', substr($dados['escolas'], 1, -1));
    $sql = "select n_inst, id_inst from instancia "
            . " where id_inst in (" . $es . ") "
            . "order by n_inst ";
    $query = $model->db->query($sql);
    $esc = $query->fetchAll();
    foreach ($esc as $v) {
        $escolas[$v['id_inst']] = $v['n_inst'];
    }
}
foreach ($aval as $v) {
    // $dados[$v['id_gl']] = $v;
    $avalia[$v['id_gl']] = $v['n_gl'];
}
$lanc_ = sql::get('global_descritivo', "*", ['aval' => $id_gl]);
foreach ($lanc_ as $v) {
    $lanc[$v['num']] = $v['num'];
}
?>
<div class="fieldBody">
    <div class="fieldTop">
        Lançamento de Notas
    </div>
    <br /><br />
    <div class="row">
        <div class="col-md-12">
<?php
formulario::select('id_gl', $avalia, 'Avaliação', @$id_gl, 1);
$hidden['id_gl'] = @$id_gl;
?>
        </div>
    </div>
    <br /><br />
<?php
if (!empty($id_gl)) {
    $aval = sql::get('global_aval', "*", ['id_gl' => $id_gl], 'fetch');
    $quest = unserialize(tool::serializeCorrector($aval['perc']));
    ?>
        <table class="table table-bordered table-hover table-striped">
        <?php
        foreach ($quest as $k => $v) {
            ?>
                <tr>
                    <td>
        <?php echo $k ?>
                    </td>
                    <td>
        <?php echo $v ?>
                    </td>
                    <td>
        <?php
        if (!empty($lanc)) {
            echo in_array($k, $lanc)?'Lançado':'';
        }
        ?>
                    </td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="quest" value="<?php echo $k ?>" />
                            <input type="hidden" name="id_gl" value="<?php echo $id_gl ?>" />
                            <input name="ac" class="btn btn-success" type="submit" value="Acessar" />
                        </form>
                    </td>
                </tr>
        <?php
    }
    ?>
        </table>

    <?php
    if (!empty($_POST['ac'])) {
        $descr_ = sql::get('global_descritivo', "*", ['aval' => $id_gl, 'num' => $_POST['quest']]);
        foreach ($descr_ as $d) {

            $descr[$d['valorNominal']] = $d['descricao'];
            $id_gd[$d['valorNominal']] = $d['id_gd'];
        }
        $valores = explode(",", $aval['valores']);
        tool::modalInicio();
        ?>
            <br /><br />
            <div style="text-align: center; font-size: 18px">
        <?php echo $_POST['quest'] . ' - ' . $quest[$_POST['quest']] ?>
            </div>
            <br /><br />
            <form method="POST">
                <table class="table table-bordered table-hover table-striped">
                    <tr>
                        <td>
                            Conceito
                        </td>
                        <td>
                            Descrição
                        </td>
                    </tr>
        <?php
        foreach ($valores as $val) {
            ?>
                        <tr>
                            <td>
            <?php echo $val ?>
                            </td>
                            <td>
                                <input type="hidden" name="id_gd[<?php echo $val ?>]" value="<?php echo @$id_gd[$val] ?>" />
                                <input type="text" name="hab[<?php echo $val ?>]" value="<?php echo @$descr[$val] ?>" />
                            </td>
                        </tr>
            <?php
        }
        ?>
                </table>
                <br /><br />
                <div style="text-align: center; font-size: 18px">
        <?php echo DB::hiddenKey('habilidade'); ?>
                    <input type="hidden" name="titulo" value="<?php echo $quest[$_POST['quest']] ?>" />
                    <input type="hidden" name="id_gl" value="<?php echo $id_gl ?>" />
                    <input type="hidden" name="quest" value="<?php echo $_POST['quest'] ?>" />
                    <input class="btn btn-success" type="submit" value="Salvar" />
                </div>
            </form>
        <?php
        tool::modalFim();
    }
}
?>
</div>