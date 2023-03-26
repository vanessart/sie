<form method="POST">
    <div class="panel panel-body">
        <div class="row">
            <div class="col-md-3">
                <?php echo formulario::input('1[id_visao]', 'Cod.Visão', null, ($exame['id_visao']), 'readonly') ?>
            </div>
            <div class="col-md-3">
                <?php echo formulario::input('1[dt_reteste]', 'Data Reteste', null, data::converteBr($exame['dt_reteste']), formulario::dataConf(), 'Digite Dia, Mês e Ano 01/01/2018') ?>
            </div>
            <div class="col-md-6">
                <?php echo formulario::input('1[reteste_sit]', 'Situação Reteste', null, $exame['reteste_sit'], 'id="st", readonly') ?>
            </div>

        </div>
        <br />
        <div class="row">
            <div class="col-md-6">
                <?php echo formulario::input('1[observacao]', 'Observação', null, $exame['observacao']) ?>
            </div> 
            <div class="col-md-6">
                <?php echo formulario::input('1[reteste_outros]', 'Outros Sinais Reteste', null, $exame['reteste_outros']) ?>
            </div>
        </div>
        <br />
        <table style="width: 100%">
            <tr>
                <td style="width: 30%">
                    <div style="border: 2px solid grey; padding: 5px">
                        <br /><br />             
                        <span style="color:red; font-size: 15px; font-weight: bold">Com Óculos Reteste?</span>
                        <div class="row">
                            <br />
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[ocr]" value="Sim" <?php echo ($exame['reteste_oculos'] == "Sim") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[ocr]" value="Não" <?php echo ($exame['reteste_oculos'] == "Não") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div>
                            </div>
                        </div>
                        <br /><br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Encaminhamento para Oftalmologista?</span>
                        <br />
                        <div class="row">
                            <br />
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[of]" value="Sim" <?php echo ($exame['encam_oftalmologista'] == "Sim") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[of]" value="Não" <?php echo ($exame['encam_oftalmologista'] == "Não") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div> 
                            </div>
                        </div>               
                        <br /><br />
                        <span style='color:red; font-size: 15px; font-weight: bold'>Encaminhamento entregue aos Pais (Cartão)?</span>
                        <br />
                        <div class="row">
                            <br />
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[ca]" value="Sim" <?php echo ($exame['cartao'] == "Sim") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[ca]" value="Não" <?php echo ($exame['cartao'] == "Não") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div> 
                            </div>
                        </div>
                        <br /><br /><br /><br /><br /><br />
                    </div>
                </td>
                <td style="width: 2%">
                    &nbsp;
                </td>

                <td style="width: 21%">
                    <div style='border: 2px solid grey; padding: 5px'>
                        <br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Sinais de Problemas de Visão</span>
                        <br /><br />
                        <?php
                        foreach ($sin as $si) {
                            $s[$si['id_sinais']] = $si['sinal'];
                            ?>
                            <div class="form-check">
                                <label><input type="radio" name="1[sir]" value="<?php echo $si['id_sinais'] ?>" <?php echo ($si['id_sinais'] == $exame['reteste_sinais']) ? "checked" : null; ?>/><span style='font-size: 15px; color: black'><?php echo $si['sinal'] ?></span></label>
                            </div>
                            <?php
                        }
                        ?>
                        <br /><br /><br /><br /><br /><br /><br />
                    </div>
                </td>
                <td style="width: 2%">
                    &nbsp;
                </td>
                <td style="width: 21%">
                    <div style='border: 2px solid grey; padding: 5px'>
                        <br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Olho Direito Reteste</span>
                        <br /><br />
                        <?php
                        foreach ($pap as $pa) {
                            $p[$pa['id_teste_papel']] = $pa['valor_papel'];
                            ?>
                            <div class="form-check">
                                <label><input onclick="ckeck('<?php echo $pa['id_teste_papel'] ?>', 'd')" type="radio" name="1[pap]" value="<?php echo $pa['id_teste_papel'] ?>" <?php echo ($pa['id_teste_papel'] == $exame['reteste_direito']) ? "checked" : null; ?>/><span style='font-size: 15px; color: black'><?php echo $pa['valor_papel'] ?></span></label>                    
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </td>
                <td style="width: 2%">
                    &nbsp;
                </td>
                <td style="width: 21%">
                    <div style='border: 2px solid grey; padding: 5px'>
                        <br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Olho Esquerdo Reteste</span>
                        <br /><br />
                        <?php
                        foreach ($pap as $pae) {
                            $pa[$pae['id_teste_papel']] = $pae['valor_papel'];
                            ?>
                            <div class="form-check">
                                <label><input onclick="ckeck('<?php echo $pae['id_teste_papel'] ?>', 'e')" type="radio" name="1[pae]" value="<?php echo $pae['id_teste_papel'] ?>" <?php echo ($pae['id_teste_papel'] == $exame['reteste_esquerdo']) ? "checked" : null; ?>/><span style='font-size: 15px; color: black'><?php echo $pae['valor_papel'] ?></span></label>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </td>
            </tr>
        </table>

    </div>
    <div class="row">
        <div style="text-align: center" class="col-md-12">   
            <?php echo formulario::hidden(['1[fk_id_pessoa]' => $exame['fk_id_pessoa']]) ?>
            <?php echo DB::hiddenKey('gravaexamereteste') ?>

            <input type="hidden" name= "codigo" value="<?php echo $codClasse ?>" />
            <input type="hidden" name= "id_visao" value="<?php echo $visao ?>" />
            <input type="hidden" name= "id_pessoa" value="<?php echo $id_pessoa ?>" />
            <input type="hidden" name= "id_turma" value="<?php echo $exame['fk_id_turma'] ?>" />
            <input type="hidden" name= "activeNav" value="<?php echo $_POST['activeNav'] ?>" />

            <input type="submit" style="width: 40%" class ="art-button" value="Salvar" />
        </div>

    </div>

</form>

<input id="di" type="hidden" name="direito" value="<?php echo $exame['reteste_direito'] ?>" />
<input id="es" type="hidden" name="esquerdo" value="<?php echo $exame['reteste_esquerdo'] ?>" />

<script>

    function ckeck(valor, olho) {

        if (olho == 'd') {
            document.getElementById('di').value = valor;
        } else if (olho == 'e') {
            document.getElementById('es').value = valor;
        }

        direito = document.getElementById('di').value;
        esquerdo = document.getElementById('es').value;

        if ((direito == 1) || (esquerdo == 1)) {
            sit = 'Não Submetido';
        } else if ((direito < 10) || (esquerdo < 10)) {
            sit = 'FALHA';
        } else {
            sit = 'PASSA';
        }

        document.getElementById('st').value = sit;
    }

</script>