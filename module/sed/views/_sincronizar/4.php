<?php
if (!defined('ABSPATH'))
    exit;
if (!empty($_POST['limpar'])) {
    $sql = "update sed_baixar_aluno_lote set baixado = 0";
    $query = pdoSis::getInstance()->query($sql);
}
if (!empty($_POST['baixado'])) {
    $baixado = $_POST['baixado'];
    session_write_close();
    foreach ($baixado as $k => $v) {
        echo '<br />' . $k . ' - ' . $v;
        if ($v == 1) {
            $al = sql::get(['ge_turma_aluno', 'pessoa'], 'ra, ra_uf, id_pessoa', ['fk_id_inst' => $k, 'periodo_letivo' => '2022']);
            foreach ($al as $a) {
                $id = restImport::alunoNovoRede($a['ra'], $a['ra_uf'], null, $a['id_pessoa']);
                echo '<br />' . $id['id_pessoa'];
            }
            echo '<br />' . $sql = "replace into ge2.sed_baixar_aluno_lote(id_inst, baixado) VALUES ($k, 1)";
            $query = pdoSis::getInstance()->query($sql);
        }
    }
}
$sql = " SELECT "
        . " i.id_inst, i.n_inst, b.dt_bal, b.baixado "
        . " FROM ge2.instancia i "
        . " left join ge2.sed_baixar_aluno_lote b on b.id_inst = i.id_inst "
        . "  where i.fk_id_tp = 1 "
        . "  and i.ativo = 1 "
        . " order by baixado, n_inst ";
$query = pdoSis::getInstance()->query($sql);
$array = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($array as $v) {
    if(empty($v['baixado'])){
    @$ctx[1]++;
    } else {
        @$ctx[2]++;
    }
}
?>
<div class="body">
    <?= formErp::submit('limpar', null, ['activeNav' => 4, 'limpar' => 1]) ?>
<div class="row">
    <div class="col">
Baixados <?= $ctx[2] ?>
    </div>
    <div class="col">
Não Baixados <?= $ctx[1] ?>
    </div>
</div>
<br />
    <form method="POST">
        <div style="text-align: center; padding: 10px">
            <?= formErp::button('Enviar') ?>
        </div>
        <br />
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    Baixado
                </td>
                <td></td>
                <td>
                    Escola
                </td>
                <td></td>
            </tr>
            <?php
            foreach ($array as $v) {
                ?>
                <tr>
                    <td>
                        <?= toolErp::simnao($v['baixado']) ?>
                    </td>
                    <td>
                        <?= formErp::checkbox('baixado[' . $v['id_inst'] . ']', 1) ?>
                    </td>
                    <td>
                        <?= $v['n_inst'] ?>
                    </td>
                    <td>
                        <?= $v['dt_bal'] ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
        <div style="text-align: center; padding: 30px">
            <?=
            formErp::hidden(['activeNav' => 4])
            . formErp::button('Enviar')
            ?>
        </div>
    </form>
</div>
