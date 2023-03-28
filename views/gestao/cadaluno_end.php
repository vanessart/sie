<?php
$escola = new escola();
@$end = sql::get('endereco', '*', ['fk_id_pessoa' => @$dados['id_pessoa']], 'fetch');
javaScript::cep();
?>
<script>
    function selcep() {
        if (document.getElementById("setc").value == "ON") {
            document.getElementById('ceptmp').value = document.getElementById('cep').value;
            document.getElementById('cep').value = "<?php echo $escola->_cie ?>";
            document.getElementById('cep').readOnly = true;
            document.getElementById('cidade').value = "<?= CLI_CIDADE ?>";
            document.getElementById('uf').value = "<?= CLI_UF ?>";
            document.getElementById("setc").value = ""
        } else {
            document.getElementById('cep').value = document.getElementById('ceptmp').value;
            document.getElementById('cep').readOnly = false;
            document.getElementById("setc").value = "ON"
        }
    }
</script>
<form method="POST">
    <div class="panel-default">
        <div class="panel-heading">
            Endereço
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-2">
                    <?php formulario::input('a[cep]', 'CEP: ', NULL, empty(@$end['cep']) ? '' : str_pad(str_replace(' ', '', str_replace('-', '', @$end['cep'])), 8, "0", STR_PAD_LEFT), ' id="cep" ') ?>
                </div>
                <div class="col-lg-2">
                    <div class="input-group">
                        <label>
                            <div class="input-group-addon">
                                <input id="setc" onclick="selcep()" type="checkbox"  value="ON" />
                            </div>
                            <div class="input-group-addon">
                                Sem CEP
                            </div>
                        </label>
                        <input id="ceptmp" type="hidden" value="" />
                    </div>
                </div>
                <div class="col-lg-6">
                    <?php formulario::input('a[logradouro]', 'Logradouro: ', NULL, @$end['logradouro'], ' id="rua"  ') ?>
                </div>
                <div class="col-lg-2">
                    <?php formulario::input('a[num]', 'Nº: ', NULL, @$end['num']) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-lg-12">
                    <?php formulario::input('a[complemento]', 'Complemento: ', NULL, @$end['complemento']) ?>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-lg-3">
                    <?php formulario::input('a[bairro]', 'Bairro: ', NULL, @$end['bairro'], ' id="bairro"  ') ?>
                </div>
                <div class="col-lg-3">
                    <?php formulario::input('a[cidade]', 'Cidade: ', NULL, @$end['cidade'], ' id="cidade"  ') ?>
                </div>
                <div class="col-lg-2">
                    <?php formulario::input('a[uf]', 'UF: ', NULL, @$end['uf'], ' id="uf"  ') ?>
                </div>
                <div class="col-lg-4">
                    <?php echo formulario::input('a[dt_barueri]', 'Mora em '.CLI_NOME.' desde', NULL, data::converteBr(@$end['dt_barueri']), formulario::dataConf()) ?>
                </div>
            </div>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-lg-12 text-center">
            <?php echo formulario::hidden(['a[fk_id_pessoa]' => @$dados['id_pessoa'], 'a[fk_id_tp]' => 1, 'a[id_end]' => @$end['id_end']]) ?>
            <input type="hidden" name="novo" value="1" />
            <input type="hidden" name="aba" value="foto" />
            <input type="hidden" name="id_pessoa" value="<?php echo @$id ?>" />
            <?php echo DB::hiddenKey('endereco', 'replace') ?>
            <button class="btn btn-success">
                Salvar
            </button>
        </div>
    </div>
</form>
<br />
