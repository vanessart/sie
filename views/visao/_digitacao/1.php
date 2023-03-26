<form method="POST">
    <div class="panel panel-body">
        <div class="row">
            <div class="col-md-3">
                <?php echo formulario::input('1[id_visao]', 'Cod.Visão', null, ($exame['id_visao']), 'readonly') ?>
            </div>
            <div class="col-md-3">
                <?php echo formulario::input('1[dt_teste]', 'Data Teste', null, data::converteBr($exame['dt_teste']), formulario::dataConf(), 'Digite Dia, Mês e Ano 01/01/2019') ?>
            </div>
            <div class="col-md-6">
                <?php echo formulario::input('1[situacao_teste]', 'Situação Teste', null, $exame['situacao_teste'], 'id="st", readonly') ?>            </div>

        </div>
        <br />
        <div class="row">
            <div class="col-md-6">
                <?php echo formulario::input('1[observacao]', 'Observação', null, $exame['observacao']) ?>
            </div>     
            <div class="col-md-6">
                <?php echo formulario::input('1[outros_sinais]', 'Outros Sinais', null, $exame['outros_sinais']) ?>
            </div>
        </div>
        <br />
        <table style="width: 100%">
            <tr>
                <td style="width: 18%">
                    <div style='border: 2px solid blue; padding: 5px'>  
                        <br /><br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Com óculos?</span>
                        <div class="row">
                            <br />
                            <div class="col-md-6" >
                                <div class="form-check">
                                    <label><input type="radio" name="1[oc]" value="Sim" <?php echo ($exame['oculos'] == "Sim") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6" >
                                <div class="form-check">
                                    <label><input type="radio" name="1[oc]" value="Não" <?php echo ($exame['oculos'] == "Não") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div>
                            </div>
                        </div>
                        <br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Já Faz <br />Uso de Óculos ou Lentes?</span>
                        <br />
                        <div class="row">
                            <br />
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[ol]" value="Sim" <?php echo ($exame['usouoculoslentes'] == "Sim") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[ol]" value="Não" <?php echo ($exame['usouoculoslentes'] == "Não") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div>
                            </div>
                        </div>
                        <br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Necessidades Especiais?</span>
                        <br />
                        <div class="row">
                            <br />
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[ne]" value="Sim" <?php echo ($exame['necessidade_esp'] == "Sim") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[ne]" value="Não" <?php echo ($exame['necessidade_esp'] == "Não") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div>  
                            </div>
                        </div>
                        <br /><br /><br /><br />
                    </div>
                </td>
                <td style="width: 1%">
                    &nbsp;
                </td>
                <td style="width: 15%">
                    <div style='border: 2px solid blue; padding: 5px'>
                        <br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Deficiência</span>
                        <br /><br />
                        <?php
                        foreach ($def as $df) {
                            $d[$df['id_deficiencia']] = $df['desc_deficiencia'];
                            ?>
                            <div class="form-check">
                                <label><input type="radio" name="1[df]" value="<?php echo $df['id_deficiencia'] ?>" <?php echo ($df['id_deficiencia'] == $exame['fk_id_deficiencia']) ? "checked" : null; ?>/><span style='font-size: 15px; color: black'><?php echo $df['desc_deficiencia'] ?></span></label>
                            </div>
                            <?php
                        }
                        ?>
                        <br /><br /><br /><br /><br /><br />
                    </div>
                </td>
                <td style="width: 1%">
                    &nbsp;
                </td>
                <td style="width: 21%">
                    <div style="border: 2px solid blue; padding: 5px">
                        <br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Sinais de Problemas de Visão</span>
                        <br /><br />
                        <?php
                        foreach ($sin as $si) {
                            $s[$si['id_sinais']] = $si['sinal'];
                            ?>
                            <div class="form-check">
                                <label><input type="radio" name="1[si]" value="<?php echo $si['id_sinais'] ?>" <?php echo ($si['id_sinais'] == $exame['fk_id_sinais']) ? "checked" : null; ?>/><span style='font-size: 15px; color: black'><?php echo $si['sinal'] ?></span></label>
                            </div>
                            <?php
                        }
                        ?>
                        <br /><br /><br /><br />
                    </div>
                </td>
                <td style="width: 1%">
                    &nbsp;
                </td>
                <td style="width: 21%">
                    <div style="border: 2px solid blue; padding: 5px">
                        <br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Olho Direito</span>
                        <br /><br />
                        <?php
                        foreach ($com as $co) {
                            $c[$co['id_teste_comp']] = $co['valor_teste_comp'];
                            ?>
                            <div class="form-check">
                                <label><input onclick="ckeck('<?php echo $co['id_teste_comp'] ?>', 'd')" type="radio" name="1[cod]" value="<?php echo $co['id_teste_comp'] ?>" <?php echo ($co['id_teste_comp'] == $exame['olho_direito']) ? "checked" : null; ?>/><span style='font-size: 15px; color: black'><?php echo $co['valor_teste_comp'] ?></span></label>                    
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </td>
                <td style="width: 1%">
                    &nbsp;
                </td>
                <td style="width: 21%">
                    <div style="border: 2px solid blue; padding: 5px">
                        <br />
                        <span style='color:red; font-size: 15px; font-weight: bold '>Olho Esquerdo</span>
                        <br /><br />
                        <?php
                        foreach ($com as $coe) {
                            $ce[$coe['id_teste_comp']] = $coe['valor_teste_comp'];
                            ?>
                            <div class="form-check">
                                <label><input onclick="ckeck('<?php echo $coe['id_teste_comp'] ?>', 'e')" type="radio" name="1[coe]" value="<?php echo $coe['id_teste_comp'] ?>" <?php echo ($coe['id_teste_comp'] == $exame['olho_esquerdo']) ? "checked" : null; ?>/><span style='font-size: 15px; color: black'><?php echo $coe['valor_teste_comp'] ?></span></label>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <div style="border: 2px solid blue; padding: 5px">
        <span style="color:red; font-size: 15px; font-weight: bold">Autorizado</span>
        <br /><br />
        <div class="row">
            <div class="col-md-1">
                <div class="form-check">
                    <label><input type="radio" name="1[auto]" value="Sim" <?php echo ($exame['autorizacao'] == "Sim") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-check">
                    <label><input type="radio" name="1[auto]" value="Não" <?php echo ($exame['autorizacao'] == "Não") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                </div>
            </div>
            <div class="col-md-10">
            </div>
        </div>
    </div>
    <br />
    <?php echo formulario::input('1[obs_autorizacao]', 'Obs. Autorização', null, $exame['obs_autorizacao']) ?>
    <br />
    <div style="text-align: center">   
        <?php echo formulario::hidden(['1[fk_id_pessoa]' => $exame['fk_id_pessoa']]) ?>
        <?php echo DB::hiddenKey('gravaexame') ?>

        <input type="hidden" name= "codigo" value="<?php echo $codClasse ?>" />
        <input type="hidden" name= "id_visao" value="<?php echo $visao ?>" />
        <input type="hidden" name= "id_pessoa" value="<?php echo $id_pessoa ?>" />
        <input type="hidden" name= "id_turma" value="<?php echo $exame['fk_id_turma'] ?>" />
        <input type="hidden" name= "activeNav" value="<?php echo $_POST['activeNav'] ?>" />

        <input type="submit" style="width: 40%" class ="art-button" value="Salvar" />
    </div>
</form>

<input id="di" type="hidden" name="direito" value="<?php echo $exame['olho_direito'] ?>" />
<input id="es" type="hidden" name="esquerdo" value="<?php echo $exame['olho_esquerdo'] ?>" />

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
        } else if ((direito < 8) || (esquerdo < 8)) {
            sit = 'FALHA';
        } else {
            sit = 'PASSA';
        }

        document.getElementById('st').value = sit;
    }

</script>