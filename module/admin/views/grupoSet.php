<?php
if (!defined('ABSPATH'))
    exit;
$id_gr = filter_input(INPUT_POST, 'id_gr', FILTER_SANITIZE_NUMBER_INT);
if (toolErp::id_pessoa() == 1) {
    $grh = ['>' => 'n_gr'];
} else {
    $grh = ['>' => 'n_gr', 'at_gr' => 1];
}
?>
<div class="body">
    <div class="fieldTop">
        Gerenciamento de Grupos e Sistemas 
    </div>
    <div class="row">
        <div class="col">
            <?= formErp::selectDB('grupo', 'id_gr', 'Grupo', $id_gr, 1, null, null, $grh) ?>
        </div>
    </div>
    <br />
    <form method="POST">
        <table class="table table-bordered table-hover table-striped">
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
                        <td>
                            <div class="row">
                                <?php
                                foreach ($nivel as $n) {
                                    $checked = 0;
                                    $class = NULL;
                                    foreach ($acesso as $a) {
                                        if (($n['id_nivel'] == $a['fk_id_nivel'])) {
                                            $checked = 1;
                                        }
                                    }
                                    ?>
                                    <div class="col-3">
                                        <?= formErp::checkbox($v['id_sistema'].'['.$n['id_nivel'].']', 1, $n['n_nivel'], $checked) ?>
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
            </table>
            <div class="row">
                <div style="text-align: center; padding: 40px">
                    <?= formErp::hiddenToken('acessoSet') ?>
                    <input type="hidden" name="id_gr" value="<?php echo $id_gr ?>" />
                    <button class="btn btn-primary">
                        alterar
                    </button>
                </div>
            </div>
        </form>
        <?php
    }
    ?>
</div>
