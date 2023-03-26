<?php
$escola = new escola();
javaScript::cep();
$id_inst = tool::id_inst();
$sql = "select * from ge_abrangencia where fk_id_inst = $id_inst";
$query = $model->db->query($sql);
$abrange = $query->fetchAll();
?>
<script>

    function limpa() {
        document.getElementById('cep').value = "";
        document.getElementById('rua').value = "";
        document.getElementById('cidade').value = "";
        document.getElementById('uf').value = "";
        document.getElementById('bairro').value = "";
    }

    function selcep() {
        if (document.getElementById("setc").value == "ON") {
            document.getElementById('ceptmp').value = document.getElementById('cep').value;
            document.getElementById('cep').value = "<?php echo $escola->_cie ?>";
            document.getElementById('cep').readOnly = true;
            document.getElementById('cidade').value = "Barueri";
            document.getElementById('uf').value = "SP";
            document.getElementById("setc").value = ""
        } else {
            document.getElementById('cep').value = document.getElementById('ceptmp').value;
            document.getElementById('cep').readOnly = false;
            document.getElementById("setc").value = "ON"
        }
    }
</script>
<div class="fieldBody">
    <div class="fieldTop">
        Ruas de Abrangência
    </div>
    <br /><br />
    <form method="POST">
        <div class="panel-default">
            <div class="panel-heading">
                Endereço
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2">
                        <?php formulario::input('1[cep]', 'CEP: ', NULL, empty(@$end['cep']) ? '' : str_pad(str_replace(' ', '', str_replace('-', '', @$end['cep'])), 8, "0", STR_PAD_LEFT), ' id="cep" required') ?>
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
                    <div class="col-lg-8">
                        <?php formulario::input('1[logradouro]', 'Logradouro: ', NULL, @$end['logradouro'], ' id="rua"  required') ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5">
                        <?php formulario::input('1[bairro]', 'Bairro: ', NULL, @$end['bairro'], ' id="bairro"  required') ?>
                    </div>
                    <div class="col-lg-5">
                        <?php formulario::input('1[cidade]', 'Cidade: ', NULL, empty($end['cidade']) ? 'Barueri' : $end['cidade'], ' id="cidade"  ') ?>
                    </div>
                    <div class="col-lg-2">
                        <?php formulario::input('1[uf]', 'UF: ', NULL, empty($end['uf']) ? 'SP' : $end['uf'], ' id="uf"  ') ?>
                    </div>
                </div>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-lg-6 text-center">
                <button type="button"  onclick="limpa()" class="btn btn-danger">
                    Limpar
                </button> 
            </div>
            <div class="col-lg-6 text-center">
                <input type="hidden" name="1[fk_id_inst]" value="<?php echo $id_inst ?>" />
                <?php echo DB::hiddenKey('ge_abrangencia', 'replace') ?>
                <button class="btn btn-success">
                    Incluir
                </button>
            </div>
        </div>
    </form>
    <br /><br />
    <div class="row">
        <div class="row">
            <div class="col-lg-6 text-center">
                <form target="_blank" action="<?php echo HOME_URI ?>/app/excel/doc/sql.php" method="POST">
                   <input type="hidden" name="tokenSql" value="<?php echo tool::tokenSql() ?>" />
                 <input type="hidden" name="sql" value="<?php echo $sql ?>" />
                    <button type="submit"   class="btn btn-info">
                        Exportar Excel
                    </button>   
                </form>

            </div>
            <div class="col-lg-6 text-center">
               
                    <a target="_blank" class="btn btn-primary" href="<?php echo HOME_URI ?>/gestao/abrangepdf">
                        Relatório
                    </a>
               
            </div>
        </div>
    </div>
    <br /><br />
    <?php
    $sqlkey = DB::sqlKey('ge_abrangencia', 'delete');
    foreach ($abrange as $k => $v) {
        $abrange[$k]['del'] = formulario::submit('Apagar', $sqlkey, ['1[id_abrange]' => $v['id_abrange']]);
    }
    $form['array'] = $abrange;
    $form['fields'] = [
        'CEP' => 'cep',
        'Logradouro' => 'logradouro',
        'Bairro' => 'bairro',
        '||' => 'del'
    ];
    tool::relatSimples($form);
    ?>

</div>