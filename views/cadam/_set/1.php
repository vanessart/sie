<br /><br />

<form id="inscri"  method="POST" action="#comprovante"
    <div class="fieldWhite">
        <div class="row">
            <div class="col-md-8">
                <?php formulario::input('2[n_pessoa]', 'Nome', NULL, strtoupper(@$dados['n_pessoa']), 'required') ?>
            </div>
            <div class="col-md-4">
                <?php formulario::select('2[sexo]', ['F' => 'Feminino', 'M' => 'Masculino'], 'Sexo', @$dados['sexo'], NULL, NULL, 'required') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?php formulario::input('2[dt_nasc]', 'D. Nasc.', NULL, data::converteBr(@$dados['dt_nasc']), ' required ' . '  ' . formulario::dataConf()) ?>
            </div>
            <div class="col-md-4">
                <?php formulario::input('1[dt_cad]', 'Data do Cadastro', NULL, empty($dados['dt_cad']) ? date("d/m/Y") : data::converteBr($dados['dt_cad']), formulario::dataConf(1) . ' required') ?>
            </div>               
            <div class="col-md-4">
                <?php formulario::input('1[cad_pmb]', 'Nº Cadastro PMB', NULL, @$dados['cad_pmb']) ?>
            </div>               
        </div>
        <br />
        <div class="row">
            <div class="col-md-3">
                <?php formulario::input('1[cep]', 'CEP: ', NULL, empty(@$dados['cep']) ? '' : str_pad(str_replace(' ', '', str_replace('-', '', @$dados['cep'])), 8, "0", STR_PAD_LEFT), ' id="cep" ') ?>
            </div>
            <div class="col-md-7">
                <?php formulario::input('1[logradouro]', 'Logradouro: ', NULL, @$dados['logradouro'], ' id="rua"  required ') ?>
            </div>
            <div class="col-md-2">
                <?php formulario::input('1[num]', 'Nº: ', NULL, @$dados['num'], ' required ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-12">
                <?php formulario::input('1[compl]', 'Complemento: ', NULL, @$dados['compl'], @$readonly) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-5">
                <?php formulario::input('1[bairro]', 'Bairro: ', NULL, @$dados['bairro'], ' id="bairro" required ') ?>
            </div>
            <div class="col-md-5">
                <?php formulario::input('1[cidade]', 'Cidade: ', NULL, @$dados['cidade'], ' id="cidade" required ') ?>
            </div>
            <div class="col-md-2">
                <?php formulario::input('1[uf]', 'UF: ', NULL, @$dados['uf'], ' id="uf" required ') ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-3">
                Telefones
            </div>
            <div class="col-md-3">
                <input id="t1" placeholder="Celular" <?php echo @$readonly ?> type="text" name="2[tel1]" value="<?php echo @$dados['tel1'] ?>"   size="20" />
            </div>
            <div class="col-md-3">
                <input id="t2" placeholder="Residencial" <?php echo @$readonly ?> type="text" name="2[tel2]" value="<?php echo @$dados['tel2'] ?>"   size="20" />
            </div>
            <div class="col-md-3">
                <input id="t3" placeholder="Outro" <?php echo @$readonly ?> type="text" name="2[tel3]" value="<?php echo @$dados['tel3'] ?>"   size="20" />
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-12">
                <?php formulario::input('2[email]', 'E-mail: ', NULL, @$dados['email'], @$readonly . ' id="em" ') ?>
            </div>
        </div>
    </div>
    <br /><br />
    <div class="fieldWhite">
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th style="text-align: center; font-size: 20px">
                        Disciplinas<br /><br />
                    </th>
                    <th></th>
                    <th style="text-align: center; font-size: 20px">
                        Disponibilidade<br /><br />
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="color: black; font-weight: bold; font-size: 18px">
                        <?php
                        //echo '<pre>';
                        //print_r($classDisc);
                        //print_r($cargo);
                        foreach ($cargo as $k => $v) {
                            //foreach ($cargo as $k => $v) {
                            ?>
                            <table style="width: 100%">
                                <tr>
                                    <td style="width: 80%">
                                        <label  style="width: 100%; text-align: left">
                                            <div class="input-group"  style="width: 100%; text-align: left">
                                                <div class="input-group-addon"  style="width: 100%; text-align: left">
                                                    <input <?php echo @in_array($k, $cargoId) ? 'checked' : '' ?> id="<?php echo $k ?>" type="checkbox" name="cargos_e[<?php echo $k ?>]" value="<?php echo $k ?>" />

                                                    <?php echo $v ?>
                                                </div>
                                            </div>
                                        </label>
                                    </td>
                                    <td>
                                        <?php
                                        echo form::input('classDisc[' . $k . ']', 'Class.', @$classDisc[$k], '  style="text-align: center" ');
                                        ?>
                                    </td>
                                </tr>
                            </table>
                            <br />
                            <?php
                        }
                        ?>
                        <br /><br />
                        <?php
                        if (!empty($tea)) {
                            formulario::checkbox('1[tea]', 1, 'INTERESSE EM TEA', @$dados['tea']);
                        }
                        ?>
                    </td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>
                        <table class="table table-bordered" style="background-color: white">
                            <?php
                            $linha = ['', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex'];
                            $col = ['', 'Manhã', 'Tarde', 'Noite'];
                            for ($c = 0; $c < 4; $c++) {
                                ?>
                                <tr>
                                    <?php
                                    for ($cc = 0; $cc < 6; $cc++) {
                                        ?>
                                        <td style="height: 50px; cursor: pointer; background-color: <?php echo in_array($cc . substr($col[$c], 0, 1), $dados['dia']) && $c != 0 ? '0088cc' : '' ?> " onclick="dia(this, '<?php echo $c . '_' . $cc ?>')">
                                            <?php
                                            if ($c == 0) {
                                                echo @$linha[$cc];
                                            } else {
                                                if ($cc == 0) {
                                                    echo $col[$c];
                                                } else {
                                                    ?>
                                                    <input id="<?php echo @$c . '_' . $cc ?>" type="hidden" name="dia[<?php echo @$cc . @substr($col[$c], 0, 1) ?>]" value="<?php echo @in_array(@$cc . @substr($col[$c], 0, 1), @$dados['dia']) && @$c != 0 ? 1 : NULL ?>" />
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </td>
                                        <?php
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <br /><br />
    <div class="fieldWhite">
        <div class="row">
            <div class="col-md-4">
                <?php formulario::input('2[cpf]', 'CPF', NULL, @$dados['cpf'], (!empty($dados) ? ' readonly="readonly" ' : '') . ' required') ?>
            </div>
            <div class="col-md-3">
                <?php formulario::input('2[rg]', 'RG', NULL, @$dados['rg']) ?>
            </div>
            <div class="col-md-2">
                <?php formulario::input('2[rg_oe]', 'O. Exp.', NULL, @$dados['rg_oe']) ?>
            </div>
            <div class="col-md-3">
                <?php formulario::input('1[pis]', 'PIS/PASEP', NULL, @$dados['pis']) ?>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-4">
                <?php formulario::selectDB('banco', '1[banco]', 'Banco', @$dados['banco']) ?>
            </div>
            <div class="col-md-4">  
                <?php formulario::input('1[agencia]', 'Agência', NULL, @$dados['agencia']) ?>
            </div>
            <div class="col-md-4">
                <?php formulario::input('1[cc]', 'Conta Corrente', NULL, @$dados['cc']) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4" style="padding-left: 30px">
                <div style="background-color: white; border-radius: 5px; padding: 5px; font-size: 18px; text-align: left">
                    <div style="text-align: center">
                        Documentos Scaneados 
                    </div>

                    <br /><br />
                    <label>
                        <input <?php echo @in_array('rg', @$doc) ? 'checked' : '' ?> type="checkbox" name="doc[rg]" value="1" />
                        RG
                    </label>
                    <br />
                    <label>
                        <input <?php echo @in_array('cpf', @$doc) ? 'checked' : '' ?> type="checkbox" name="doc[cpf]" value="1" />
                        CPF
                    </label>
                    <br />
                    <label>
                        <input <?php echo @in_array('banco', @$doc) ? 'checked' : '' ?> type="checkbox" name="doc[banco]" value="1" />
                        Banco
                    </label>
                    <br />
                    <label>
                        <input <?php echo @in_array('diploma', @$doc) ? 'checked' : '' ?> type="checkbox" name="doc[diploma]" value="1" />
                        Diploma
                    </label>
                    <br />
                    <label>
                        <input <?php echo @in_array('historico', @$doc) ? 'checked' : '' ?> type="checkbox" name="doc[historico]" value="1" />
                        Histórico Escolar
                    </label>
                    <br />
                    <label>
                        <input <?php echo @in_array('pis', @$doc) ? 'checked' : '' ?> type="checkbox" name="doc[pis]" value="1" />
                        PIS/PASEP
                    </label>
                    <br />
                </div>
            </div>
            <div class="col-md-8">
                <textarea placeholder="Observações" name="1[obs]" style="width: 100%; height: 170px; font-size: 16px"><?php echo @$dados['obs'] ?></textarea>
            </div>
        </div>  
    </div>
    <br /><br />
    <div class="fieldWhite">
        <div class="row">
            <div class="col-md-4">
                <div style="background-color: white; border-radius: 5px; padding: 15px">
                    Situação:
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label onclick="ativa()">
                        <input <?php echo @$dados['ativo'] == 1 || empty($dados['id_cad']) ? 'checked' : '' ?> type="radio" name="1[ativo]" value="1" />
                        Ativo
                    </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label onclick="desativa()">
                        <input <?php echo @$dados['ativo'] == 0 && !empty($dados['id_cad']) ? 'checked' : '' ?> type="radio" name="1[ativo]" value="0" />
                        Desativado
                    </label>
                </div>
            </div>
            <div class="col-md-8">
                <div style="background-color: white; border-radius: 5px; padding: 15px">
                    Recadastramento: 
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                        <input <?php echo @$dados['check_update'] == 1 || empty($dados['id_cad']) ? 'checked' : '' ?> type="radio" name="1[check_update]" value="1" />
                        SIM
                    </label>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                        <input <?php echo @$dados['check_update'] == 2 && !empty($dados['id_cad']) ? 'checked' : '' ?> type="radio" name="1[check_update]" value="2" />
                        NÃO
                    </label>
                </div>
            </div>
            <div class="col-md-8" id="des" style="display: <?php echo @$dados['ativo'] == 0 && !empty($dados['id_cad']) ? '' : 'none' ?>">
                <div style="background-color: white; border-radius: 5px; padding: 15px">
                    <?php formulario::input('1[motivo]', 'Motivo', NULL, @$dados['motivo']) ?>
                </div> 
            </div>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <?php echo DB::hiddenKey('cadCadampe') ?>
        <div class="col-md-12 text-center">
            <input type="hidden" name="1[cargo]" value="<?php echo @$dados['cargo'] ?>" />
            <?php
            if (empty($dados['fk_id_inscr'])) {
                ?>
                <input type="hidden" name="1[fk_id_inscr]" value="<?php echo @$dados['id_inscr'] ?>" />
                <?php
            } else {
                ?>
                <input type = "hidden" name = "1[fk_id_inscr]" value = "<?php echo @$dados['fk_id_inscr'] ?>" />
                <?php
            }
            ?>         
            <input type="hidden" name="fk_id_sel" value="<?php echo $dados['fk_id_sel'] ?>" />
            <input type="hidden" name="2[id_pessoa]" value="<?php echo $dados['fk_id_pessoa'] ?>" />
            <input type="hidden" name="activeNav" value="1" />
            <input type="hidden" name="1[id_cad]" value="<?php echo @$dados['id_cad']; ?>" />
            <input type="hidden" name="id_cad" value="<?php echo @$dados['id_cad']; ?>" />
            <input type="hidden" name="continuar" value="1" />
            <input class="btn btn-success" type="submit" id="btnContinuar" value="Salvar" />
            <br /><br />
        </div>
    </div>

</form>


<script>
    function dia(i, inp) {
        valor = document.getElementById(inp).value;
        if (valor == 1) {
            i.style.backgroundColor = 'white'
            document.getElementById(inp).value = 0;
        } else {
            i.style.backgroundColor = '#0088cc'
            document.getElementById(inp).value = 1;
        }
    }

    function desativa() {
        document.getElementById("des").style.display = '';
    }
    function ativa() {
        document.getElementById("des").style.display = 'none';
    }

</script>

<?php
if ($dados['check_update'] == 1) {
    ?>

    <p>DECLARAÇÃO DE COMPARECIMENTO</p>

    <form target = "_blank" action = "<?php echo HOME_URI ?>/cadam/decl_comp" method = "POST">
        <input type="hidden" name="id_cad" value="<?php echo @$dados['id_cad']; ?>" />
        <button id="comprovante" class = "btn btn-info" id="btnVisualizar">
            Visualizar Declaração de Comparecimento
        </button>
    </form>

    <?php
}
?>

