<?php
if (!defined('ABSPATH'))
    exit;

$bimestre = filter_input(INPUT_POST, 'bimestre', FILTER_SANITIZE_NUMBER_INT);
$ciclo = sql::get('ge_turmas', 'fk_id_ciclo', ['id_turma' => $id_turma], 'fetch');
$id_ciclo = $ciclo['fk_id_ciclo'];
$bimestres = sql::get(['ge_cursos', 'ge_ciclos'], 'qt_letiva, un_letiva, atual_letiva', ['id_ciclo' => $id_ciclo], 'fetch');
if (empty($bimestre) && !empty($bimestres)) {
    $bimestre = $bimestres['atual_letiva']; 
}
$pdi = $model->getPDI($fk_id_pessoa); 
//sql::get('apd_pdi', 'id_pdi', ['fk_id_pessoa' =>  $fk_id_pessoa, 'fk_id_turma' => $id_turma], 'fetch');
if (!empty($bimestres)) {?>
    <div class="row">
            <?php
        for ($i=1; $i <= $bimestres["qt_letiva"]; $i++) {
            $outline = "-outline";
            if ($i == $bimestre) {
                $outline = "";
            }
            ?>
                <div class="col">
                    <?= formErp::submit($i.'º Bimestre', null, ['id_aluno_adaptacao' => $id_aluno_adaptacao,'fk_id_pessoa' => $fk_id_pessoa,'bimestre' => $i, 'activeNav' => 4,'n_pessoa' => $n_pessoa, 'id_turma' => $id_turma,'id_porte'=>$id_porte],null,null,null, 'btn btn'.$outline.'-info');?>
                </div>
            <?php 
        }?>
    </div> 
    <br><br>
        <?php
}
if (!empty($pdi) && !empty($bimestre)) {  
    $id_pdi = $pdi['id_pdi'];
    $hab = sql::get('apd_pdi_hab', 'id_pdi_hab, fk_id_hab, recursos, didatica, obs,habilidade', ['fk_id_pdi' => $id_pdi, 'atualLetiva' => $bimestre ]);

    $descr = sql::get('apd_pdi_descritiva','id_descritiva, fk_id_pdi,descri,trabalho,avaliacao,obs, fk_id_pessoa_prof', ['fk_id_pdi' => $id_pdi, 'atualLetiva' => $bimestre], 'fetch');

    $qtd_atend = 0;
    $atend = $model->getAtend($id_pdi,$bimestre,null);
    if (!empty($atend)) {
        $qtd_atend = count($atend );
    }
    $prof_turma = $model->profeSala($fk_id_pessoa);

        $turma = sql::get('ge_turmas', 'fk_id_inst', ['id_turma' => $id_turma], 'fetch');
        $escola = toolErp::n_inst($turma['fk_id_inst']);
        $poloAEE = toolErp::n_inst();

        $aluno = sql::get('pessoa', 'dt_nasc', ['id_pessoa' => $fk_id_pessoa],'fetch');
        $entre = $model->getEntre($fk_id_pessoa); 
        //$entre = sql::get('apd_doc_entrevista','medicamento,terapia,cid', ['fk_id_pessoa' =>  $fk_id_pessoa, 'fk_id_turma' => $id_turma ], 'fetch');

        $infoInical = sql::get('apd_pdi','id_pdi,coordenador,duracao_plano,area_priori,aspectos,comunic,asp_cognit,asp_fisico,expressao,conhecimento,novas_tec,autonomia,descr_hab,barreira,fk_id_pessoa_prof', ['id_pdi' => $id_pdi], 'fetch'); 
        if ($infoInical) { 
            $prof_AEE = toolErp::n_pessoa($infoInical["fk_id_pessoa_prof"]);
        }
    ?>

    <style type="text/css">
        .titulo_anexo{
            color: #16baed;
            font-weight: bold;
            text-align: center;
        }
        .sub_anexo{
            font-weight: bold;
            text-align: center;
        }
        .sub2_anexo{
            font-weight: bold;
            text-align: center;
            FONT-SIZE: 14px;

        }s
        .mensagens {}
        .mensagens .mensagem {
            border: 1px solid #e1e1d1;
            box-shadow: #e1e1d1 5px 5px 14px;
           /* margin: 10px auto;*/
            padding: 4px;
            /*min-height: 80px;*/
        }
        .mensagens .nomePessoa {text-transform: lowercase;}
        .mensagens .nomePessoa:first-letter { text-transform: uppercase; }
        .mensagens .nomePessoa { 
            color: #888;
            font-weight: normal;
            font-size: 100%;
        }

        .mensagens .dataMensagem { 
            font-weight: bold;
            color: #888;
            font-size: 18px;
        }

        .tit_table{ 
            font-weight: bold;
            color: #888;
            font-size: 18px;
        }

        .mensagens .tituloHab{
            font-weight: bold;
            color: #7ed8f5;
            font-size: 100%; 
        }
        .mensagens .corpoMensagem {
            display: block;
            margin-bottom: 10px;
            font-weight: normal;        
            white-space: pre-wrap;
            padding: 5px;
            color: #888;
        }
        .mensagens .mensagemLinha-0{border-left: 5px solid #7ed8f5;}
        .tituloBox{
            font-size: 17px;
            font-weight: bold;
        }
        .tituloBox.box-0{ color: #7ed8f5;}
    </style>

    <div class="row">
            <div class="col titulo_anexo">DEPARTAMENTO EDUCACIONAL ESPECIALIZADO</div>
        </div>
        <div class="row">
            <div class="col sub_anexo">ANEXO II</div>
        </div>
        <br>

    <table style="width: 100%; border-collapse: collapse; background-color: #e7ffcb;" border=1 cellspacing=0 cellpadding=2>
        <tr>
            <td style="font-weight: bold; text-align: center;">
                <div class="row">
                    <div class="col" >PDI - PLANO DE DESENVOLVIMENTO INDIVIDUAL</div>
                </div>
                <div class="row">
                    <div class="col" style="font-weight: bold; text-align: center;FONT-SIZE: 14px;">PARTE I - INFORMAÇÕES E AVALIAÇÃO DO ALUNO</div>
                </div>
            </td>
        </tr>
    </table>
    <br>

    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem mensagemLinha-0" >
                <div>
                    <p class="tituloBox box-0">INFORMAÇÕES </p>
                    <table style="width: 100%; border-collapse: collapse; " border=0 cellspacing=0 cellpadding=2>
                        <tr>
                            <td>
                                <table style="width: 100%; border-collapse: collapse; " border=0 cellspacing=0 cellpadding=2>
                                    <tr>
                                        <td colspan="3" align="center">
                                            <?php
                                            if (file_exists(ABSPATH . '/pub/fotos/' . $fk_id_pessoa . '.jpg')) {
                                                ?>  

                                                <img src="<?= HOME_URI ?>/pub/fotos/<?= $fk_id_pessoa ?>.jpg" width="100" height="120">

                                                <?php
                                            } else {
                                                ?>
                                                <img src="<?= HOME_URI ?>/includes/images/anonimo.jpg" width="100" height="120"/>
                                                
                                                <?php
                                            }
                                            ?> 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                           <span class="tit_table">Nome do aluno: </span> 
                                           <span class="nomePessoa"><?= @$n_pessoa ?> </span>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td>
                                           <span class="tit_table">Data de Nascimento: </span> 
                                           <span class="nomePessoa"><?= dataErp::converteBr(@$aluno['dt_nasc']) ?></span>  
                                        </td>
                                        <td>
                                           <span class="tit_table"> Ano/Turma:</span>
                                           <span class="nomePessoa"><?= @$n_turma ?></span>  
                                        </td>
                                        <td>
                                           <span class="tit_table">CID: </span>
                                           <span class="nomePessoa"><?= @$entre['cid'] ?></span>  
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                           <span class="tit_table">Medicamentos: </span>
                                           <span class="nomePessoa"><?= @$entre['medicamento'] ?></span>  
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                           <span class="tit_table">Terapias: </span>
                                           <span class="nomePessoa"><?= @$entre['terapia'] ?></span>  
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                           <span class="tit_table">Pólo do AEE: </span>
                                           <span class="nomePessoa"><?= @$poloAEE ?></span>  
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <span class="tit_table">U.E de Origem: </span>
                                           <span class="nomePessoa"><?= @$escola ?></span>  
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                           <span class="tit_table">Prof.(a) AEE: </span>
                                           <span class="nomePessoa"><?= @$prof_AEE ?></span>  
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                           <span class="tit_table">Professores da Sala Regular: </span>
                                           <span class="nomePessoa"><?= @$prof_turma ?></span>  
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                           <span class="tit_table">Coordenador Pedagógico: </span>
                                           <span class="nomePessoa"><?= @$infoInical['coordenador'] ?></span>  
                                        </td>
                                    </tr>
                                    <tr>
                                         <td colspan="2">
                                           <span class="tit_table">Total de Atendimentos no bimestre: </span>
                                           <span class="nomePessoa"><?= $qtd_atend ?></span>  
                                        </td>
                                        <td>
                                           <span class="tit_table">Duração do Plano: </span>
                                           <span class="nomePessoa"><?= @$infoInical['duracao_plano'] ?></span>  
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                           <span class="tit_table">Áreas a serem priorizadas: </span>
                                           <span class="nomePessoa"><?= @$infoInical['area_priori'] ?></span>  
                                        </td>
                                    </tr>
                                    
                                </table>
                            </td>
                        </tr>
                    </table>
        <br>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem mensagemLinha-0" >
                <div>
                    <p class="tituloBox box-0">DESCRIÇÃO DAS POTENCIALIDADES QUE O ALUNO JÁ POSSUI</p>
                    
                            <label class="dataMensagem">
                                Aspectos Socioafetivos / Socialização / Comportamentos
                            </label>
                             <span class="corpoMensagem">
                               <?= @$infoInical['aspectos'] ?>
                            </span>
                       
                            <label class="dataMensagem">
                                Comunicação - Linguagem Oral/escrita ou Gestual
                            </label>
                            <span class="corpoMensagem">
                               <?= @$infoInical['comunic'] ?>
                            </span>
                        
                            <label class="dataMensagem">
                                Aspectos Cognitivos / Raciocínio Lógico 
                            </label>
                            <span class="corpoMensagem">
                               <?= @$infoInical['asp_cognit'] ?>
                            </span>
                        
                            <label class="dataMensagem">
                                Aspectos Físicos / Motricidade 
                            </label>
                            <span class="corpoMensagem">
                               <?= @$infoInical['asp_fisico'] ?>
                            </span>
                       
                            <label class="dataMensagem">
                                Expressão Artística 
                            </label>
                            <span class="corpoMensagem">
                               <?= @$infoInical['expressao'] ?>
                            </span>
                        
                            <label class="dataMensagem">
                                Conhecimento do Mundo 
                            </label>
                            <span class="corpoMensagem">
                               <?= @$infoInical['conhecimento'] ?>
                            </span>
                        
                            <label class="dataMensagem">
                                Novas Tecnologias
                            </label>
                            <span class="corpoMensagem">
                               <?= @$infoInical['novas_tec'] ?>
                            </span>
                        
                            <label class="dataMensagem">
                                Autonomia e Vida Diária
                            </label>
                            <span class="corpoMensagem">
                               <?= @$infoInical['autonomia'] ?>
                            </span>
                        
                    <br>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem mensagemLinha-0" >
                <div>
                    <p class="tituloBox box-0">DESCRIÇÃO DAS HABILIDADES A SEREM DESENVOLVIDAS PELO ALUNO</p>
                    
                    <label class="nomePessoa"><?= @$infoInical['descr_hab'] ?></label><br><br>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem mensagemLinha-0" >
                <div>
                    <p class="tituloBox box-0">BARREIRAS</p>
                    
                    <label class="nomePessoa"><?= @$infoInical['barreira'] ?></label><br><br>
                </div>
            </div>
        </div>
    </div>
    <br>

    <table style="width: 100%; border-collapse: collapse; background-color: #e7ffcb;" border=1 cellspacing=0 cellpadding=2>
        <tr>
            <td style="font-weight: bold; text-align: center;">
                <div class="row">
                    <div class="col" >PDI - PLANO DE DESENVOLVIMENTO INDIVIDUAL</div>
                </div>
                <div class="row">
                    <div class="col" style="font-weight: bold; text-align: center;FONT-SIZE: 14px;">PARTE II - PLANO PEDAGÓGICO ESPECIALIZADO</div>
                </div>

                <div class="row">
                    <div class="col" style="font-weight: bold; text-align: center;FONT-SIZE: 14px;">Período <?= $bimestre ?>º Bimestre - Data:<?= date("d/m/Y")?></div>
                </div>
            </td>
        </tr>
    </table>
    <br>

    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem mensagemLinha-0" >
                <div>
                    <p class="tituloBox box-0">HABILIDADES</p>
                        <?php
                        if ($hab) {
                            
                            foreach ($hab as $v) {?>
                                    <label class="dataMensagem"><span><?= $v['habilidade'] ?></span></label>
                                    <span class="corpoMensagem"><strong>RECURSOS: </strong><?= $v["recursos"] ?> </span><br>
                                    <span class="corpoMensagem"><strong>SITUAÇÃO / SEQUÊNCIA DIDÁTICA: </strong><?= $v["didatica"] ?> </span><br>
                                    <span class="corpoMensagem"><strong>OBSERVAÇÕES: </strong><?= $v["obs"] ?> </span><br>
                                <?php
                            }
                        } ?>  
                    <br>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem mensagemLinha-0" >
                <div>
                    <p class="tituloBox box-0">TRABALHO REALIZADO COM O ALUNO DENTRO DA SALA DO AEE</p>   
                    <?php
                    foreach ($atend as $k => $v) {?>
                        <span class="dataMensagem"><?= dataErp::converteBr($v['dt_atend']) ?><br>
                        <span class="corpoMensagem"><strong>AÇÕES EM ATENDIMENTO: </strong><?= $v["acao"] ?> </span><br>
                        <?php
                    } ?> 
                   <br>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem mensagemLinha-0" >
                <div>
                    <p class="tituloBox box-0">DESCRIÇÃO DO TRABALHO REALIZADO COM O ALUNO EM PARCERIA COM OS PROFESSORES DA SALA REGULAR E FAMÍLIA</p>
                    
                    <label class="nomePessoa"><?= @$descr['descri'] ?></label><br><br>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem mensagemLinha-0" >
                <div>
                    <p class="tituloBox box-0">TRABALHO COLABORATIVO COM OS DEMAIS PROFISSIONAIS DA ESCOLA, POSSIBILITANDO UM OLHAR INCLUSIVO DE TODA A EQUIPE ESCOLAR</p>
                    
                    <label class="nomePessoa"><?= @$descr['trabalho'] ?></label><br><br>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem mensagemLinha-0" >
                <div>
                    <p class="tituloBox box-0">AVALIAÇÃO PROCESSUAL</p>
                    
                    <label class="nomePessoa"><?= @$descr['avaliacao'] ?></label><br><br>
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-12 mensagens">
            <div class="mensagem mensagemLinha-0" >
                <div>
                    <p class="tituloBox box-0">OBSERVAÇÕES RELEVANTES</p>
                    
                    <label class="nomePessoa"><?= @$descr['obs'] ?></label><br><br>
                </div>
            </div>
        </div>
    </div>
    <br>

    <?php
}else{?>
    <div class="alert alert-warning" style="padding-top:  10px; padding-bottom: 0">
        <div class="row">
            <div class="col" style="font-weight: bold; text-align: center;">
                <p> Não há PDI preenchido pelo professor AEE</p>
                
            </div>
        </div>
    </div>

<?php
}
