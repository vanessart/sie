<?php
$id_li = filter_input(INPUT_POST, 'id_li', FILTER_UNSAFE_RAW);
if (empty($id_li)) {
    $id_li = @$_POST['last_id'];
}
$veiculo = transporte::veiculoEmpresa();
?>
<div class="fieldBody">
    <div class="fieldTop">
        Cadastro de Linha
    </div>
    <br /><br />
    <form method="POST">
        <input type="hidden" name="modal" value="1" />
        <input class="btn btn-success" type="submit"  value="Cadastrar Linha" />
    </form>

    <?php
    if (empty($_POST['modal'])) {
        $md = 1;
    } else {
        $md = NULL;
    }
    tool::modalInicio('width: 95%', $md);

    if (empty($_POST['novaInst'])) {
        if (!empty($id_li)) {
            $dados = sql::get('transp_linha', '*', ['id_li' => $id_li], 'fetch');
        }
        ?>
        <form method="POST">
            <div class="row">
                <div class="col-sm-6">
                    <?php echo form::input('1[n_li]', 'Linha', @$dados['n_li']) ?>
                </div>
                <div class="col-sm-3">
                    <?php echo form::checkbox('1[at_nome]', 1, 'Não gerar Nome automaticamente', @$dados['at_nome']) ?>
                </div>
                <div class="col-sm-3">
                    <?php echo form::select('1[fk_id_tv]', $veiculo, 'Veículo', @$dados['fk_id_tv']) ?>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-sm-3">
                    <?php echo form::selectNum('1[viagem]', [1, 10], 'Viagem', @$dados['viagem']) ?>
                </div>
                <div class="col-sm-3">
                    <?php echo form::input('1[saida]', 'Saída', @$dados['saida'], ' id="hora" onkeypress="mascaraHora(\'hora\') " ') ?>
                </div>
                <div class="col-sm-3">
                    <?php
                    for ($c = 5; $c < 200; $duracao[$c] = data::minutoParaHora($c), $c = $c + 5)
                        ;
                    echo form::select('1[duracao]', $duracao, 'Duração', @$dados['duracao']);
                    ?>
                </div>
                <div class="col-sm-3">
                    <?php echo form::select('1[periodo]', ['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite'], 'Período', @$dados['periodo'], NULL, NULL, ' required ') ?>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-sm-6">
                    <?php echo form::input('1[motorista]', 'Motorista', @$dados['motorista']) ?>
                </div>
                <div class="col-sm-6">
                    <?php echo form::input('1[monitor]', 'Monitor', @$dados['monitor']) ?>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-sm-4">
                    <?php echo form::input('1[tel1]', 'Telefone 1', @$dados['tel1']) ?>
                </div>
                <div class="col-sm-4">
                    <?php echo form::input('1[tel2]', 'Telefone 2', @$dados['tel2']) ?>
                </div>
                <div class="col-sm-4">
                    <?php echo form::select('1[ativo]', [1 => 'Sim', 0 => 'Não'], 'Ativo', @$dados['ativo']) ?>
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
                        <?php echo form::input('1[motorista_s]', 'Motorista', @$dados['motorista_s']) ?>
                    </div>
                    <div class="col-sm-6">
                        <?php echo form::input('1[monitor_s]', 'Monitor', @$dados['monitor_s']) ?>
                    </div>
                </div>
                <br /><br />
                <div class="row">
                    <div class="col-sm-4">
                        <?php echo form::input('1[tel1_s]', 'Telefone 1', @$dados['tel1_s']) ?>
                    </div>
                    <div class="col-sm-4">
                        <?php echo form::input('1[tel2_s]', 'Telefone 2', @$dados['tel2_s']) ?>
                    </div>
                    <div class="col-sm-4">
                        <?php echo form::select('1[fk_id_tv_s]', $veiculo, 'Veículo', @$dados['fk_id_tv_s']) ?>
                    </div>
                </div>    
            </div>
            <?php
            echo DB::hiddenKey('transp_linha', 'replace');
            if (empty($id_li)) {
                ?>

                <div class="row">
                    <div class="col-sm-6 text-center">
                        <a class="btn btn-warning" onclick="document.getElementById('lp').submit()" href="#">Limpar</a>
                    </div>
                    <div class="col-sm-6 text-center">
                        <input type="hidden" name="modal" value="1" />
                        <?php
                        echo form::button('Continuar');
                        ?>
                    </div>
                </div>
                <?php
            } else {
                $escolas = transporte::linhaEscolas($id_li);
                echo form::hidden([
                    '1[id_li]' => $id_li
                ]);
                ?>
                <div class="row">
                    <div class="col-sm-2">
                        <input onclick="document.getElementById('ii').submit()" class="btn btn-info" type="button" value="Incluir uma nova Escola" />
                    </div>
                    <div class="col-sm-10">
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
                <div class="row">
                    <div class="col-sm-6 text-center">
                        <a class="btn btn-warning" onclick="document.getElementById('lp').submit()" href="#">Limpar</a>
                    </div>
                    <div class="col-sm-6 text-center">
                        <?php
                        echo form::button('Salvar');
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
        <script>
            function apagar(inst) {
                document.getElementById('id_inst').value = inst;
                document.getElementById('ap').submit();
            }
        </script>
        <?php
    } else {
        $esc = escolas::idInst(NULL, NULL, 1);
        ?>
        <form method="POST">
            <div class="row">
                <div class="col-sm-8">
                    <?php echo form::select('1[fk_id_inst]', $esc, 'Escola') ?>
                </div>
                <div class="col-sm-2">
                    <input type="hidden" name="1[fk_id_li]" value="<?php echo $id_li ?>" />
                    <?php //echo DB::hiddenKey('transp_inst_linha', 'replace')  ?>
                    <!-- Temporario -->
                    <input type="hidden" name="gravalinhaescola" value="<?php echo 'gravaescola' ?>" />
                    <input type="hidden" name="id_li" value="<?php echo $id_li ?>" />
                    <input type="hidden" name="modal" value="1" />
                    <input class="btn btn-success" type="submit" value="Incluir" />
                </div>
                <div class="col-sm-2">
                    <input onclick="document.getElementById('volta').submit()" class="btn btn-warning" type="button" value="Voltar" />
                </div>
            </div>
            <br /><br />

        </form>
        <form id="volta" method="POST">
            <input type="hidden" name="modal" value="1" />
            <input type="hidden" name="id_li" value="<?php echo $id_li ?>" />
        </form>
        <?php
    }
    tool::modalFim();
    ?>
    <br /><br />
    <?php
    $form = $model->listaLinhas();

    tool::relatSimples($form);
    ?>
</div>   
