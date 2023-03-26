<?php
$res = $model->auxtabela('cv_teste_papel');
?>
<form method="POST">
    <div class="panel panel-body">
        <table style="width: 100%">
            <tr>
                <td style="width: 20%">
                    <div style='border: 2px solid blue; padding: 5px'>
                        <br />  
                        <span style ="font-size: 15px" class="label label-default">&nbsp;Unidade Escolar&nbsp;</span>
                        <br /><br />
                        <span style='color:red; font-size: 15px; font-weight: bold'>Teste de Acuidade Visual</span>
                        <br /><br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[av]" value="Sim" <?php echo ($exame['teste_av'] == "Sim") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[av]" value="Não" <?php echo ($exame['teste_av'] == "Não") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div>
                            </div>
                        </div>
                        <br />
                        <?php echo formulario::input('1[dt_reteste]', 'Data: ', null, data::converteBr($exame['dt_reteste']), 'readonly') ?>
                        <br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Resultado do Teste</span>
                        <br /><br />
                        <span style="font-size: 15px; font-weight: bold">Olho Direito: <?php echo $res[$exame['reteste_direito']] ?></span>
                        <br /><br />
                        <div class="form-check">
                            <label><input type="radio" name="[odpf]" value="Passou" <?php echo ($exame['reteste_direito'] > 9) ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Passou</span></label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label><input type="radio" name="[odpf]" value="Falhou" <?php echo ($exame['reteste_direito'] < 10) ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Falhou</span></label>
                        </div>
                        <br />
                        <?php echo formulario::input('1[obs_olho_d]', 'Obs.: ', null, $exame['obs_olho_d']) ?>
                        <br />
                        <span style="font-size: 15px; font-weight: bold">Olho Esquerdo: <?php echo $res[$exame['reteste_esquerdo']] ?></span> 
                        <br /><br />
                        <div class="form-check">
                            <label><input type="radio" name="[oepf]" value="Passou" <?php echo ($exame['reteste_esquerdo'] > 9) ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Passou</span></label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label><input type="radio" name="[oepf]" value="Falhou" <?php echo ($exame['reteste_esquerdo'] < 10) ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Falhou</span></label>
                        </div>
                        <br />
                        <?php echo formulario::input('1[obs_olho_e]', 'Obs.: ', null, $exame['obs_olho_e']) ?>
                        <br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Encaminhamento para Oftalmologista</span>
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
                        <br />
                        <?php echo formulario::input('1[dt_oftal]', 'Data Encaminhamento: ', null, data::converteBr($exame['dt_oftal']), formulario::dataConf(1), 'Digite Dia, Mês e Ano 01/01/2019') ?>

                        <br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Aguardando consulta</span>

                        <div class="row">
                            <br />
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[con]" value="Sim" <?php echo ($exame['ag_consulta'] == "Sim") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[con]" value="Não" <?php echo ($exame['ag_consulta'] == "Não") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div> 
                            </div>
                        </div>
                        <br />
                        <?php echo formulario::input('1[dt_agendamento]', 'Data Agendamento: ', null, data::converteBr($exame['dt_agendamento']), formulario::dataConf(5), 'Digite Dia, Mês e Ano 01/01/2019') ?>
                    </div>
                </td>
                <td style="width: 2%">
                    &nbsp;
                </td>

                <td style="width: 32%">
                    <div style='border: 2px solid blue; padding: 5px'>
                        <br />
                        <span style ='font-size: 15px' class="label label-default">&nbsp;Secretaria de Saúde/ Particular/ Convênio&nbsp;</span>
                        <br /><br />                    
                        <div>
                            <span style="color:red; font-size: 15px; font-weight: bold">Consulta Oftalmológica</span>
                            <br />
                            <div class="row">
                                <br />
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <label><input type="radio" name="1[cof]" value="Sim" <?php echo ($exame['consulta_oftal'] == "Sim") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <label><input type="radio" name="1[cof]" value="Não" <?php echo ($exame['consulta_oftal'] == "Não") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                    </div>  
                                </div>
                            </div>
                        </div>
                        <br />
                        <div>
                            <label><input type="radio" name="1[loc]" value="UBS / Especialidades" <?php echo ($exame['consulta_local'] == "UBS / Especialidades") ? "checked" : null; ?>/><span style='font-size: 15px; color: black'>UBS / Especialidades</span></label>
                            <br />
                            <label><input type="radio" name="1[loc]" value="Particular" <?php echo ($exame['consulta_local'] == "Particular") ? "checked" : null; ?>/><span style='font-size: 15px; color: black'>Particular</span></label>
                            <br />
                            <label><input type="radio" name="1[loc]" value="Convênio" <?php echo ($exame['consulta_local'] == "Convênio") ? "checked" : null; ?>/><span style='font-size: 15px; color: black'>Convênio</span></label>
                            <br /><br />
                            <?php echo formulario::input('1[dt_consulta]', 'Data: ', 'width: 2px', data::converteBr($exame['dt_consulta']), formulario::dataConf(2), 'Digite Dia, Mês e Ano 01/01/2019') ?>
                        </div>
                        <br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Conduta Oftalmológica</span>
                        <br />
                        <?php echo formulario::input('1[cod_cid_10]', 'CID 10: ', null, $exame['cod_cid_10']) ?>
                        <br />
                        <div class="row">
                            <div class="col-md-6">
                                <span style='color:red; font-size: 15px; font-weight: bold'>Indicação de Óculos</span>
                                <br /><br />
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <label><input type="radio" name="1[io]" value="Sim" <?php echo ($exame['indicacao_oculos'] == "Sim") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <label><input type="radio" name="1[io]" value="Não" <?php echo ($exame['indicacao_oculos'] == "Não") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <span style='color:red; font-size: 15px; font-weight: bold'>Já fazia uso de Óculos</span>
                                <br /><br />
                                <div class="row">
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
                            </div>
                        </div>
                        <br />
                        <span style='color:red; font-size: 15px; font-weight: bold'>Exames Complementares</span>
                        <br /><br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <label><input type="radio" name="1[ex]" value="Sim" <?php echo ($exame['exames_compl'] == "Sim") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <label><input type="radio" name="1[ex]" value="Não" <?php echo ($exame['exames_compl'] == "Não") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <?php echo formulario::input('1[exames_quais]', 'Quais?', null, $exame['exames_quais']) ?>
                            </div>
                        </div>   
                        <br />
                        <?php echo formulario::input('1[nome_medico]', 'Nome do Médico', null, $exame['nome_medico']) ?>
                        <br />
                        <span style='color:red; font-size: 15px; font-weight: bold'>Cartão Preenchimendo pelo Médico</span>
                        <br /><br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[pm]" value="Sim" <?php echo ($exame['preenchimento_medico'] == "Sim") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[pm]" value="Não" <?php echo ($exame['preenchimento_medico'] == "Não") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div>
                            </div>
                        </div>
                        <br />
                        <span style='color:red; font-size: 15px; font-weight: bold'>Informações de documento emitido pelo Médico</span>
                        <br />
                        <span style='color:red; font-size: 15px; font-weight: bold'>(Laudo ou Receituário)</span>
                        <br /><br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[lm]" value="Sim" <?php echo ($exame['laudo_receituario'] == "Sim") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[lm]" value="Não" <?php echo ($exame['laudo_receituario'] == "Não") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td style="width: 2%">
                    &nbsp;
                </td>
                <td style="width: 20%">
                    <div style="border: 2px solid blue; padding: 5px">
                        <br />
                        <span style ="font-size: 15px" class="label label-default">&nbsp;Promoção Social/ Particular&nbsp;</span>                      
                        <br /><br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Aquisição de Óculos</span>
                        <br /><br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[aqo]" value="Sim" <?php echo ($exame['aquisicao_oculos'] == "Sim") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[aqo]" value="Não" <?php echo ($exame['aquisicao_oculos'] == "Não") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div>  
                            </div>
                        </div>
                        <br />
                        <div>
                            <label><input type="radio" name="1[locaq]" value="Promoção Social" <?php echo ($exame['aquisicao_local'] == "Promoção Social") ? "checked" : null; ?>/><span style='font-size: 15px; color: black'>Promoção Social</span></label>
                            <br />
                            <label><input type="radio" name="1[locaq]" value="Particular" <?php echo ($exame['aquisicao_local'] == "Particular") ? "checked" : null; ?>/><span style='font-size: 15px; color: black'>Particular</span></label>
                            <br />
                            <label><input type="radio" name="1[locaq]" value="Outros" <?php echo ($exame['aquisicao_local'] == "Outros") ? "checked" : null; ?>/><span style='font-size: 15px; color: black'>Outros</span></label>
                            <br /><br />
                            <?php echo formulario::input('1[dt_aquisicao]', 'Data: ', null, data::converteBr($exame['dt_aquisicao']), formulario::dataConf(3), 'Digite Dia, Mês e Ano 01/01/2019') ?>
                        </div>
                        <br /><br />
                    </div>
                    <br />
                    <div style="border: 2px solid blue; padding: 5px">
                        <br />
                        <span style ="font-size: 15px" class="label label-default">&nbsp;&nbsp;&nbsp;Família&nbsp;&nbsp;&nbsp;</span>
                        <br /><br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Retirou Cartão</span>
                        <br /><br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[rc]" value="Sim" <?php echo ($exame['retirou_cartao'] == "Sim") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div> 
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[rc]" value="Não" <?php echo ($exame['retirou_cartao'] == "Não") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div>    
                            </div>
                        </div>
                        <br />
                        <?php echo formulario::input('1[dt_entrega_cartao]', 'Retirou Dia: ', null, data::converteBr($exame['dt_entrega_cartao']), formulario::dataConf(6), 'Digite Dia, Mês e Ano 01/01/2019') ?>
                        <br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Cartão no Prontuário</span>
                        <br /><br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[cp]" value="Sim" <?php echo ($exame['cartao_prontuario'] == "Sim") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[cp]" value="Não" <?php echo ($exame['cartao_prontuario'] == "Não") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div>
                            </div>
                        </div>
                        <br />    
                        <span style="color:red; font-size: 15px; font-weight: bold">Família devolveu Cartão</span>
                        <br /><br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[dev]" value="Sim" <?php echo ($exame['devolveu_cartao'] == "Sim") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="1[dev]" value="Não" <?php echo ($exame['devolveu_cartao'] == "Não") ? "checked" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div>
                            </div>
                        </div>
                        <br />    
                        <?php echo formulario::input('1[dt_devolucao_cartao]', 'Data Devolução: ', null, data::converteBr($exame['dt_devolucao_cartao']), formulario::dataConf(7), 'Digite Dia, Mês e Ano 01/01/2019') ?>                    
                        <br /><br />
                    </div>
                </td>
                <td style="width: 2%">
                    &nbsp;
                </td>
            </tr>
        </table>

    </div>
    <div class="row">
        <div style="text-align: center" class="col-md-12">   
            
            <?php echo formulario::hidden(['1[fk_id_pessoa]' => $exame['fk_id_pessoa']]) ?>
            <?php echo DB::hiddenKey('gravaacompanhamento') ?>

            <input type="hidden" name= "codigo" value="<?php echo $codClasse ?>" />
            <input type="hidden" name= "id_visao" value="<?php echo $visao ?>" />
            <input type="hidden" name= "id_pessoa" value="<?php echo $id_pessoa ?>" />
            <input type="hidden" name= "id_turma" value="<?php echo $exame['fk_id_turma'] ?>" />
            <input type="hidden" name= "activeNav" value="<?php echo $_POST['activeNav'] ?>" />

            <input type="submit" style="width: 40%" class ="art-button" value="Salvar" />

        </div>
    </div>

</form>



