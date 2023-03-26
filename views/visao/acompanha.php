<?php

$visao = $_POST['id_visao'];
$turma = $_POST['fk_id_turma'];
$periodo = $_POST['periodo_letivo'];
$idescola = $_POST['fk_id_inst'];
$tipoteste = '2';

$exame = sql::get('cv_visao_aluno', '*', ['id_visao' => $visao], 'fetch');

$res = $model->auxtabela('cv_teste_papel');
?>
<br />
<div style="font-weight:bold; font-size:12pt; background-color: #000000; color:#ffffff; text-align: center">
    <?php echo $_POST['n_pessoa'] ?>
</div>
<form>
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
                                    <label><input type="radio" name="[av]" value="Sim" <?php echo ($exame['teste_av'] == "Sim") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="[av]" value="Não" <?php echo ($exame['teste_av'] == "Não") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div>
                            </div>
                        </div>
                        <br />
                        <?php echo formulario::input('[dt_reteste]', 'Data: ', null, data::converteBr($exame['dt_reteste']), 'readonly') ?>
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
                        <?php echo formulario::input('[obs_olho_d]', 'Obs.: ', null, $exame['obs_olho_d'],'readonly') ?>
                        <br />
                        <span style="font-size: 15px; font-weight: bold">Olho Esquerdo: <?php echo $res[$exame['reteste_esquerdo']] ?></span> 
                        <br /><br />
                        <div class="form-check">
                            <label><input type="radio" name="[oepf]" value="Passou" <?php echo ($exame['reteste_esquerdo'] > 9) ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Passou</span></label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label><input type="radio" name="[oepf]" value="Falhou" <?php echo ($exame['reteste_esquerdo'] < 10) ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Falhou</span></label>
                        </div>
                        <br />
                        <?php echo formulario::input('[obs_olho_e]', 'Obs.: ', null, $exame['obs_olho_e'],'readonly') ?>
                        <br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Encaminhamento para Oftalmologista</span>
                        <br />
                        <div class="row">
                            <br />
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="[of]" value="Sim" <?php echo ($exame['encam_oftalmologista'] == "Sim") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="[of]" value="Não" <?php echo ($exame['encam_oftalmologista'] == "Não") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div> 
                            </div>
                        </div>
                        <br />
                        <?php echo formulario::input('[dt_oftal]', 'Data Encaminhamento: ', null, data::converteBr($exame['dt_oftal']), 'readonly') ?>

                        <br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Aguardando consulta</span>

                        <div class="row">
                            <br />
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="[con]" value="Sim" <?php echo ($exame['ag_consulta'] == "Sim") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="[con]" value="Não" <?php echo ($exame['ag_consulta'] == "Não") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div> 
                            </div>
                        </div>
                        <br />
                        <?php echo formulario::input('[dt_agendamento]', 'Data Agendamento: ', null, data::converteBr($exame['dt_agendamento']), 'readonly') ?>
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
                                        <label><input type="radio" name="[cof]" value="Sim" <?php echo ($exame['consulta_oftal'] == "Sim") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <label><input type="radio" name="[cof]" value="Não" <?php echo ($exame['consulta_oftal'] == "Não") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                    </div>  
                                </div>
                            </div>
                        </div>
                        <br />
                        <div>
                            <label><input type="radio" name="[loc]" value="UBS / Especialidades" <?php echo ($exame['consulta_local'] == "UBS / Especialidades") ? "checked" : null, "disabled"; ?>/><span style='font-size: 15px; color: black'>UBS / Especialidades</span></label>
                            <br />
                            <label><input type="radio" name="[loc]" value="Particular" <?php echo ($exame['consulta_local'] == "Particular") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black'>Particular</span></label>
                            <br />
                            <label><input type="radio" name="[loc]" value="Convênio" <?php echo ($exame['consulta_local'] == "Convênio") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black'>Convênio</span></label>
                            <br /><br />
                            <?php echo formulario::input('[dt_consulta]', 'Data: ', null, data::converteBr($exame['dt_consulta']), 'readonly') ?>
                        </div>
                        <br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Conduta Oftalmológica</span>
                        <br />
                        <?php echo formulario::input('[cod_cid_10]', 'CID 10: ', null, $exame['cod_cid_10'], 'readonly') ?>
                        <br />
                        <div class="row">
                            <div class="col-md-6">
                                <span style='color:red; font-size: 15px; font-weight: bold'>Indicação de Óculos</span>
                                <br /><br />
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <label><input type="radio" name="[io]" value="Sim" <?php echo ($exame['indicacao_oculos'] == "Sim") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <label><input type="radio" name="[io]" value="Não" <?php echo ($exame['indicacao_oculos'] == "Não") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
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
                                            <label><input type="radio" name="[ol]" value="Sim" <?php echo ($exame['usouoculoslentes'] == "Sim") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <label><input type="radio" name="[ol]" value="Não" <?php echo ($exame['usouoculoslentes'] == "Não") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
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
                                            <label><input type="radio" name="[ex]" value="Sim" <?php echo ($exame['exames_compl'] == "Sim") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <label><input type="radio" name="[ex]" value="Não" <?php echo ($exame['exames_compl'] == "Não") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <?php echo formulario::input('[exames_quais]', 'Quais?', null, $exame['exames_quais'], 'readonly') ?>
                            </div>
                        </div>   
                        <br />
                        <?php echo formulario::input('[nome_medico]', 'Nome do Médico', null, $exame['nome_medico'], 'readonly') ?>
                        <br />
                        <span style='color:red; font-size: 15px; font-weight: bold'>Cartão Preenchimendo pelo Médico</span>
                        <br /><br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="[pm]" value="Sim" <?php echo ($exame['preenchimento_medico'] == "Sim") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="[pm]" value="Não" <?php echo ($exame['preenchimento_medico'] == "Não") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
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
                                    <label><input type="radio" name="[lm]" value="Sim" <?php echo ($exame['laudo_receituario'] == "Sim") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="[lm]" value="Não" <?php echo ($exame['laudo_receituario'] == "Não") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
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
                                    <label><input type="radio" name="[aqo]" value="Sim" <?php echo ($exame['aquisicao_oculos'] == "Sim") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="[aqo]" value="Não" <?php echo ($exame['aquisicao_oculos'] == "Não") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div>  
                            </div>
                        </div>
                        <br />
                        <div>
                            <label><input type="radio" name="[locaq]" value="Promoção Social" <?php echo ($exame['aquisicao_local'] == "Promoção Social") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black'>Promoção Social</span></label>
                            <br />
                            <label><input type="radio" name="[locaq]" value="Particular" <?php echo ($exame['aquisicao_local'] == "Particular") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black'>Particular</span></label>
                            <br />
                            <label><input type="radio" name="[locaq]" value="Outros" <?php echo ($exame['aquisicao_local'] == "Outros") ? "checked" : null, "disabled"; ?>/><span style='font-size: 15px; color: black'>Outros</span></label>
                            <br /><br />
                            <?php echo formulario::input('[dt_aquisicao]', 'Data: ', null, data::converteBr($exame['dt_aquisicao']), 'readonly') ?>
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
                                    <label><input type="radio" name="[rc]" value="Sim" <?php echo ($exame['retirou_cartao'] == "Sim") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div> 
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="[rc]" value="Não" <?php echo ($exame['retirou_cartao'] == "Não") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div>    
                            </div>
                        </div>
                        <br />
                        <?php echo formulario::input('[dt_entrega_cartao]', 'Retirou Dia: ', null, data::converteBr($exame['dt_entrega_cartao']), 'readonly') ?>
                        <br />
                        <span style="color:red; font-size: 15px; font-weight: bold">Cartão no Prontuário</span>
                        <br /><br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="[cp]" value="Sim" <?php echo ($exame['cartao_prontuario'] == "Sim") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="[cp]" value="Não" <?php echo ($exame['cartao_prontuario'] == "Não") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div>
                            </div>
                        </div>
                        <br />    
                        <span style="color:red; font-size: 15px; font-weight: bold">Família devolveu Cartão</span>
                        <br /><br />
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="[dev]" value="Sim" <?php echo ($exame['devolveu_cartao'] == "Sim") ? "checked disabled" : null, "disabled"; ?>/><span style='font-size: 15px; color: black' class = 'label label-warning'>Sim</span></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label><input type="radio" name="[dev]" value="Não" <?php echo ($exame['devolveu_cartao'] == "Não") ? "checked disabled" : null; ?>/><span style='font-size: 15px; color: black' class = 'label label-success'>Não</span></label>
                                </div>
                            </div>
                        </div>
                        <br />    
                        <?php echo formulario::input('[dt_devolucao_cartao]', 'Data Devolução: ', null, data::converteBr($exame['dt_devolucao_cartao']), 'readonly') ?>                    
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
            <button type="button" onclick="$('#fecha').submit()" style="width: 40%" class="art-button">
                Retornar
            </button>  
        </div>
    </div>

</form>
<form id = "fecha" action="<?php echo HOME_URI ?>/visao/anosanteriores" method="POST">  
    <input type="hidden" name= "periodo_letivo" value="<?php echo $periodo ?>" />
    <input type="hidden" name= "id_turma" value="<?php echo $turma ?>" />
    <input type="hidden" name= "fk_id_inst" value="<?php echo $idescola ?>" />
    <input type="hidden" name= "teste" value="<?php echo $tipoteste ?>" />
 
</form>


