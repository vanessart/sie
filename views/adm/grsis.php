<?php
@$id_gr = $_POST['id_gr'];
?>
<div class="fieldTop">
    Gerenciamento de Grupos e Sistemas
</div>
<div class="field row">
    <div class="col-lg-3 input-group">
        <?php
        if (toolErp::id_pessoa() == 1) {
            $grh = ['>' => 'n_gr'];
        } else {
            $grh = ['>' => 'n_gr', 'at_gr' => 1];
        }
        ?>
        <?php echo formulario::selectDB('grupo', 'id_gr', 'Selecione um Grupo:', NULL, NULL, 1, NULL, NULL, $grh) ?>
    </div>
</div>
<br />
<div class="field" style="min-height: 500px ">
    <form method="POST">
        <table class=" table table-striped table-bordered table-hover">
            <thead>
                <tr class="fieldrow5">
                    <th style="min-width: 20%">Sistema</th>
                    <th style="min-width: 20%">Framework</th>
                    <th>Níveis de Acesso</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($id_gr)) {
                    foreach (sql::get(['sistema', 'framework'], '*', ['sistema.ativo' => 1, '>' => 'n_sistema']) as $v) {
                        $nivelSet = unserialize(@$v['niveis']);
                        $nivelSet = implode(',', $nivelSet);
                        $sql = "select * from nivel where id_nivel in ($nivelSet) and ativo = 1 order by n_nivel ";
                        $query = $model->db->query($sql);
                        $nivel = $query->fetchAll();


                        $acesso = sql::get('acesso_gr', '*', ['fk_id_gr' => $id_gr, 'fk_id_sistema' => $v['id_sistema']]);
                        ?>
                        <tr>
                            <td style="font-size: 22px">
                                <?php
                                echo $v['n_sistema'];
                                ?>
                            </td>
                            <td style="font-size: 22px">
                                <?php
                                echo $v['n_fr'];
                                ?>
                            </td>
                            <td>
                                <div class="row">
                                    <?php
                                    foreach ($nivel as $n) {
                                        $checked = null;
                                        $class = NULL;
                                        foreach ($acesso as $a) {
                                            if (($n['id_nivel'] == $a['fk_id_nivel'])) {
                                                $checked = "checked";
                                                $class = "alert-info";
                                            }
                                        }
                                        ?>
                                        <div class="col-lg-3">
                                            <div class="input-group" style="width: 100%">
                                                <label  style="width: 100%">
                                                    <span class="input-group-addon <?php echo $class ?>" style="text-align: left; width: 20px">
                                                        <input <?php echo @$checked ?> type="checkbox" aria-label="..." name="<?php echo $v['id_sistema'] ?>[<?php echo $n['id_nivel'] ?>]" value="1">
                                                    </span>
                                                    <span class="input-group-addon <?php echo $class ?>" style="text-align: left">
                                                        <?php echo $n['n_nivel'] ?>
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <div class="row">
                <div class="col-lg-120 text-center">
                    <?php echo DB::hiddenKey('insAcesso') ?>
                    <input type="hidden" name="id_gr" value="<?php echo $id_gr ?>" />
                    <button class="btn btn-large">
                        alterar
                    </button>
                </div>
            </div>
        </form>

        <?php
    }
    ?>

</div>
