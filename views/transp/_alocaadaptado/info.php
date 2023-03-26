<?php
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$destino = filter_input(INPUT_POST, 'destino', FILTER_SANITIZE_NUMBER_INT);
$dadosGeral = $model->dadosAluno($id_pessoa, $destino);
$dados = $dadosGeral['aluno'];
$time = current($dadosGeral['destino']);
$escola = filter_input(INPUT_POST, 'n_inst', FILTER_SANITIZE_STRING);
$destinoId = filter_input(INPUT_POST, 'destino', FILTER_SANITIZE_NUMBER_INT);
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$statusMostrar = filter_input(INPUT_POST, 'statusMostrar', FILTER_SANITIZE_STRING);
$id_li = filter_input(INPUT_POST, 'id_li', FILTER_SANITIZE_NUMBER_INT);
?>

<br />
<form target="_parent" action="<?= HOME_URI ?>/transp/alocaadaptado" method="POST">
    <div style="width: 95%; margin: 0 auto">
        <div class="row">
            <div class="col-sm-12">
                <?php echo form::input(null, 'Nome*', $dados['n_pessoa'], ' disabled ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-sm-3">
                <?php echo form::select('1[cadeirante]', [0 => 'Não', 1 => 'Sim'], 'Cadeirante?', $dados['cadeirante']) ?>
            </div>
            <div class="col-sm-3">
                <?php echo form::input(null, 'Idade*', $dados['idade'], ' disabled ') ?>
            </div>
            <div class="col-sm-6">
                <?php echo form::input('1[tp_def]', 'Tipo de Dificiência', $dados['tp_def']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-sm-9">
                <?php echo form::input('1[logradouro]', 'Endereço', $dados['logradouro']) ?>
            </div>
            <div class="col-sm-3">
                <?php echo form::input('1[num]', 'Número', $dados['num']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-sm-4">
                <?php echo form::input('1[cep]', 'CEP', $dados['cep']) ?>
            </div>
            <div class="col-sm-8">
                <?php echo form::input('1[bairro]', 'Bairro', $dados['bairro']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-sm-8">
                <?php echo form::input(NULL, 'Responsável*', $dados['responsavel'], ' disabled') ?>
            </div>
            <div class="col-sm-4">
                <?php echo form::input(NULL, 'Data Nasc.', $dados['dt_nasc'], ' disabled id="dt_nasc" ', null, 'date') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-sm-8">
                <?php echo form::input(NULL, 'CPF Resp.*', $dados['cpf_respons'], ' disabled id="cpf_respons" ') ?>
            </div>
            <div class="col-sm-4">
                <?php echo form::input('1[rg_respons]', 'RG Resp.', $dados['rg_respons'], ' id="rg_respons"') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-sm-6">
                <?php echo form::input('1[tel1]', 'Tel Res.', $dados['tel1'], ' id="tel1" ') ?>
            </div>
            <div class="col-sm-6">
                <?php echo form::input('1[tel2]', 'Celular.', $dados['tel2'], ' id="tel2" ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-sm-12">
                <?php echo form::input(NULL, 'Escola.*', $escola, NULL, '  disabled ') ?>
            </div>
        </div>
        <br />
        <div class="fieldBorder2">
            <table class="table table-bordered table-hover table-responsive table-striped">
                <tr>
                    <td colspan="10">
                        <div class="row">
                            <div class="col-sm-4">
                                <span id="spamStatus<?= $k ?>" >
                                    <?php
                                    $options = sql::idNome('transp_status_aluno');
                                    echo form::select('2[status][' . $time['destino'] . ']', $options, 'Status', $time['fk_id_sa']);
                                    ?>
                                </span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr id="destOcultoA<?php echo $k ?>" >
                    <?php
                    foreach (range(2, 6) as $d) {
                        ?>
                        <td colspan="2" style="text-align: center">
                            <?php echo $d ?>º Feira
                        </td>
                        <?php
                    }
                    ?>
                </tr>
                <tr id="destOcultoB<?php echo $k ?>" >
                    <?php
                    foreach (range(2, 6) as $d) {
                        foreach (['Entrada', 'Saída'] as $ke => $e) {
                            ?>
                            <td>
                                <?php echo $e ?>
                            </td>
                            <?php
                        }
                    }
                    ?> 
                </tr>
                <tr id="destOcultoC<?php echo $k ?>" >
                    <?php
                    foreach (range(2, 6) as $d) {
                        foreach (['e', 's'] as $ke => $e) {
                            ?>
                            <td>
                                <input style="width: 100%" type="time" name="2[desttime][<?php echo $time['destino'] ?>][<?php echo $e . $d ?>]" value="<?php echo $time[$e . $d] ?>" />
                            </td>
                            <?php
                        }
                    }
                    ?> 
                </tr>
            </table>

        </div>
        <br />
        <div style="text-align: center">
            <?= form::hidden(['statusMostrar' => $statusMostrar, 'id_li' => $id_li]) ?>
            <input type="hidden" name="cadAdaptado" value="1" />
            <input type="hidden" name="2[destino][<?= $destinoId ?>]" value="<?= $time['id_at'] ?>" />
            <input type="hidden" name="destino" value="<?= $destinoId ?>" />
            <input type="hidden" name="id_inst" value="<?= $id_inst ?>" />
            <input type="hidden" name="1[id_pessoa]" value="<?= $dados['id_pessoa'] ?>"  />
            <button type="submit" class="btn btn-success">
                Salvar
            </button>
        </div>
    </div>
</form>
<footer>
    * Dados Importados do SIEB/SED
</footer>
