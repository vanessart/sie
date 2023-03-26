<?php
$aluno = new aluno($id);
$aluno->vidaEscolar(NULL, tool::id_inst());
$id_trans = filter_input(INPUT_POST, 'id_trans', FILTER_SANITIZE_NUMBER_INT);
if (empty($id_trans)) {
    $transporte = sql::get('ge_transp_aluno', '*', ['id_turma_aluno' => $aluno->_id_turma_aluno], 'fetch');
    $id_trans = $transporte['fk_id_transp'];
}
$hidden['novo'] = 1;
$hidden['aba'] = "transp";
$hidden['id_pessoa'] = $id;

$sql = "SELECT * FROM ge_transp t "
        . " JOIN ge_veiculo v on v.id_veic = t.fk_id_veículo "
        . " JOIN ge_transp_esc te on te.fk_id_transp = t.id_transp "
        . " WHERE te.fk_id_inst = " . tool::id_inst()
        . " order by n_transp ";
$query = pdoSis::getInstance()->query($sql);
$transp_ = $query->fetchAll(PDO::FETCH_ASSOC);
foreach ($transp_ as $v) {
    $transp[$v['id_transp']] = $v;
    $opt[$v['id_transp']] = $v['n_transp'];
}
?>
<div class="fieldBody">
    <div class="alert alert-danger" style=" text-align: center; font-size: 20px">
        Em Desenvolvimento. Aguarde.
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?php echo formulario::select('id_trans', $opt, 'Transporte', $id_trans, 1, $hidden) ?>
        </div>
        <div class="col-sm-6">
            <?php
            if (!empty($transporte)) {
                $hidden['1[id_turma_aluno]'] = $aluno->_id_turma_aluno;
                echo formulario::submit('Excluir Transporte', DB::sqlKey('ge_transp_aluno', 'delete'), $hidden, NULL, NULL, NULL, 'btn btn-danger');
            }
            ?>
        </div>
    </div>
    <br /><br />
    <?php
    if (!empty($id_trans)) {
        ?>
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <td>
                    Transporte
                </td>
                <td>
                    <?php echo $transp[$id_trans]['n_transp'] ?> 
                </td>
            </tr>
            <tr>
                <td>
                    Motorista
                </td>
                <td>
                    <?php echo $transp[$id_trans]['motorista'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Monitor
                </td>
                <td>
                    <?php echo $transp[$id_trans]['monitor'] ?>
                </td>
            </tr>
            <tr>
                <td>
                    Veículo
                </td>
                <td>
                    <?php echo $transp[$id_trans]['modelo'] . ' (placa: ' . $transp[$id_trans]['placa'] . ')' ?>
                </td>
            </tr>
            <tr>
                <td>
                    Itinerário
                </td>
                <td>
                    <?php echo $transp[$id_trans]['itinerario'] ?>
                </td>
            </tr>
        </table>
        <br /><br />
        <form method="POST">
            <div style="text-align: center; width: 100%">
                <input type="hidden" name="1[fk_id_transp]" value="<?php echo @$id_trans ?>" />
                <input type="hidden" name="1[id_turma_aluno]" value="<?php echo @$aluno->_id_turma_aluno ?>" />
                <?php
                echo formulario::hidden($hidden);
                echo DB::hiddenKey('ge_transp_aluno', 'replace');
                ?>
                <button type="submit" class="btn btn-success">
                    Salvar
                </button>
            </div>
        </form>
        <?php
    }
    ?>
</div>
