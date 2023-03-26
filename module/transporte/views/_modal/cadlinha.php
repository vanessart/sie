<?php
if (!defined('ABSPATH'))
    exit;

$id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_STRING);
if (empty($id_li)) {
    $id_li = @$_POST['last_id'];
}
$veiculo = transporteErp::veiculoEmpresa();
$esc = escolas::idInst(NULL, NULL, 1);

?>
<div class="body">
<?php
if (empty($_POST['novaInst'])) {
    if (!empty($id_li)) {
        $dados = sqlErp::get('transporte_linha', '*', ['id_li' => $id_li], 'fetch');
    }

    if (!isset($dados['ativo'])) {
        $dados['ativo'] = 1;
    }
    ?>
    <form method="POST" target="frame" >
        <div class="row">
            <div class="col-sm-6">
                <?php echo formErp::input('1[n_li]', 'Linha', @$dados['n_li']) ?>
            </div>
            <div class="col-sm-2">
                <?php echo formErp::checkbox('1[at_nome]', 1, 'Não gerar Nome automaticamente', @$dados['at_nome']) ?>
            </div>
            <div class="col-sm-4">
                <?php echo formErp::select('1[fk_id_tv]', $veiculo, 'Veículo', @$dados['fk_id_tv']) ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-3">
                <?php echo formErp::selectNum('1[viagem]', [1, 10], 'Viagem', @$dados['viagem']) ?>
            </div>
            <div class="col-sm-3">
                <?php echo formErp::input('1[saida]', 'Saída', @$dados['saida'], ' id="hora" onkeypress="mascaraHora(\'hora\') " ') ?>
            </div>
            <div class="col-sm-3">
                <?php
                for ($c = 5; $c < 200; $duracao[$c] = dataErp::minutoParaHora($c), $c = $c + 5)
                    ;
                echo formErp::select('1[duracao]', $duracao, 'Duração', @$dados['duracao']);
                ?>
            </div>
            <div class="col-sm-3">
                <?php echo formErp::select('1[periodo]', ['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite'], 'Período', @$dados['periodo'], NULL, NULL, ' required ') ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-6">
                <?php echo formErp::input('1[motorista]', 'Motorista', @$dados['motorista']) ?>
            </div>
            <div class="col-sm-6">
                <?php echo formErp::input('1[monitor]', 'Monitor', @$dados['monitor']) ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-4">
                <?php echo formErp::input('1[tel1]', 'Telefone 1', @$dados['tel1']) ?>
            </div>
            <div class="col-sm-4">
                <?php echo formErp::input('1[tel2]', 'Telefone 2', @$dados['tel2']) ?>
            </div>
            <div class="col-sm-4">
                <?php echo formErp::select('1[ativo]', [1 => 'Sim', 0 => 'Não'], 'Ativo', @$dados['ativo']) ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-sm-12">
                Abrangência (separado com ";")
                <br />
                <textarea name="1[abrangencia]" style="width: 100%"><?php echo @$dados['abrangencia'] ?></textarea>
            </div>
        </div>
        <br /><br />
        <div class="fieldBorder2">
            <div style="text-align: center; font-weight: bold">
                Transporte Secundário
            </div>
            <br />
            <div class="row">
                <div class="col-sm-6">
                    <?php echo formErp::input('1[motorista_s]', 'Motorista', @$dados['motorista_s']) ?>
                </div>
                <div class="col-sm-6">
                    <?php echo formErp::input('1[monitor_s]', 'Monitor', @$dados['monitor_s']) ?>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-sm-4">
                    <?php echo formErp::input('1[tel1_s]', 'Telefone 1', @$dados['tel1_s']) ?>
                </div>
                <div class="col-sm-4">
                    <?php echo formErp::input('1[tel2_s]', 'Telefone 2', @$dados['tel2_s']) ?>
                </div>
                <div class="col-sm-4">
                    <?php echo formErp::select('1[fk_id_tv_s]', $veiculo, 'Veículo', @$dados['fk_id_tv_s']) ?>
                </div>
            </div>    
        </div>
        <br /><br />
        <?php
        echo formErp::hiddenToken('transporte_linha', 'ireplace');
        if (empty($id_li)) {
            ?>
            <div class="row">
                <div class="col-sm-6 text-center">
                    <a class="btn btn-warning" onclick="document.getElementById('lp').submit()" href="#">Limpar</a>
                </div>
                <div class="col-sm-6 text-center">
                    <input type="hidden" name="modal" value="1" />
                    <?php
                    echo formErp::button('Continuar');
                    ?>
                </div>
            </div>
            <?php
        } else {
            $escolas = transporteErp::linhaEscolas($id_li);
            echo formErp::hidden([
                '1[id_li]' => $id_li,
                'id_li' => $id_li,
            ]);
            ?>
            <div class="row">
                <div class="col-sm-12 text-center">
                    <?php
                    echo formErp::button('Salvar');
                    ?>
                </div>
            </div>
            <br /><br />
            <hr>
            <div class="row">
                <div class="col-sm-8">
                    <?php echo formErp::select('fk_id_inst', $esc, 'Escola'); ?>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-info" type="button" onclick="addInst()" style="white-space: nowrap;" >Incluir uma nova Escola</button>
                </div>
            </div>
            <br />
            <br />
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    if (!empty($escolas)) {
                        ?>
                        <div>
                            <?php
                            if (count($escolas) > 1) {
                                ?>
                                Escolas Atendidas:
                                <?php
                            } else {
                                ?>
                                Escola Atendida:
                                <?php
                            }
                            ?>
                        </div>
                        <table class="table table-bordered">
                            <?php
                            foreach ($escolas as $ke => $e) {
                                ?>
                                <tr>
                                    <td style="width: 80px">
                                        <input onclick="if (confirm('Excluir <?php echo $e ?>?')) {
                                                                    apagar('<?php echo $ke ?>')
                                                                }" class="btn btn-danger" type="button" value="Excluir" />
                                    </td>
                                    <td>
                                        <?php echo $e ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        ?>
    </form>
    <form id="lp" method="POST">
        <input type="hidden" name="modal" value="1" />
    </form>
    <form id="ap" method="POST">
        <input id="id_inst" type="hidden" name="fk_id_inst" value="" />
        <input type="hidden" name="id_li" value="<?php echo $id_li ?>" />
        <input type="hidden" name="modal" value="1" />
        <input type="hidden" name="apagarIntsLinha" value="1" />
    </form>
    <form id="ii" method="POST">
        <input type="hidden" name="id_li" value="<?php echo $id_li ?>" />
        <input type="hidden" name="modal" value="1" />
        <input type="hidden" name="novaInst" value="1" />
    </form>
    <form id="addInst" method="POST">
        <input type="hidden" name="1[fk_id_inst]" id="_fk_id_inst" />
        <input type="hidden" name="1[fk_id_li]" value="<?php echo $id_li ?>" />
        <input type="hidden" name="id_li" value="<?php echo $id_li ?>" />
        <input type="hidden" name="modal" value="1" />
        <?php echo formErp::hiddenToken('gravalinhaescola'); ?>
    </form>
    <script>
        function apagar(inst) {
            document.getElementById('id_inst').value = inst;
            document.getElementById('ap').submit();
        }
        function addInst() {
            inst = document.getElementById('fk_id_inst_').value;
            if (inst == ''){
                alert("Informe uma Escola");
                return false;
            }
            document.getElementById('_fk_id_inst').value = inst;
            document.getElementById('addInst').submit();
        }
    </script>
    <?php
}
?>
</div>   
