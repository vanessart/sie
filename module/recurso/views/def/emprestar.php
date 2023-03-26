<?php
if (!defined('ABSPATH'))
    exit;
$novo = filter_input(INPUT_POST, 'novo', FILTER_SANITIZE_NUMBER_INT);
$enviou_form = filter_input(INPUT_POST, 'enviou_form', FILTER_SANITIZE_NUMBER_INT);
$last_id = @$_POST['last_id'];
if (empty($last_id) && !empty($enviou_form)) {
    echo toolErp::divAlert('danger', 'Não foi possível realizar esta operaçao. Tente Novamente');
    echo formErp::hidden(['closeModal' => 1]);
}elseif (!empty($last_id) && !empty($enviou_form)) {
    echo toolErp::divAlert('success', 'Empréstimo realizado com Sucesso');
    echo formErp::hidden(['closeModal' => 1]);
}else{

    $gerente = $model->gerente();
    if ($novo == 1) {
        $id_pessoa = null;
        $id_inst = null;
        $id_serial = null;
    }
    $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
    $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
    $id_serial = filter_input(INPUT_POST, 'id_serial', FILTER_SANITIZE_STRING);
    $hidden = [
    'id_pessoa' => $id_pessoa,
    'id_serial' => $id_serial,
    'id_inst' => $id_inst,
    ];
    $data = date("Y-m-d");
    if (!empty($id_inst)) {
        $alunos = $model->alunoEscola($id_inst);
        $prof = $model->funcionarios($id_inst,$gerente);
        if (!empty($alunos) && !empty($prof)) {
            $pessoas = $alunos + $prof;
        }else if(!empty($alunos)){
            $pessoas = $alunos;
        }else{
            $pessoas = $prof;
        }
    }
    if (!empty($id_pessoa)) {
        $professor = $model->verificaProf($id_pessoa);
        $rm = $model->verificaProf($id_pessoa,1);
    }
    $objetoList = $model->objetoEscola($id_inst);
    if(!empty($id_pessoa)){
        $historico = $model->historicoGet($id_pessoa,null);
    }
    if (!empty($pessoas) && !empty($objetoList)) {?>
        <style type="text/css">
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
                font-size: 18px;
            }

            .mensagens .dataMensagem { 
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
            .mensagens .mensagemLinha-0{border-left: 5px solid #7ed8f5;padding-bottom: 30px;}
            .mensagens .mensagemLinha-1{border-left: 5px solid #f3b4ef;}
            .mensagens .mensagemLinha-2{border-left: 5px solid #f9ca6e;}
            .esconde .input-group-text{ display: none; }
            .tituloBox{
                font-size: 18px;
                font-weight: bold;
                text-align: center;
                color: #7ed8f5;
            }
            .tituloBox.box-0{ color: #7ed8f5;}
            .tituloBox.box-1{ color: #f3b4ef;}
            .tituloBox.box-2{ color: #f9ca6e;}
        </style>
        <div class="body">
            <div class="fieldTop">
                Empréstimo de Equipamento
            </div>
            <div class="row">
                <div class="col">
                    <?= formErp::select('id_pessoa', $pessoas, 'Aluno/Funcionário', @$id_pessoa, 1, $hidden) ?>
                </div>
            </div>
            <br />
            <?php
            $emprestado = "";
            if (!empty($id_pessoa) && !empty($objetoList)) {?>
                <div class="row">
                    <div class="col">
                        <?= formErp::select('id_serial', $objetoList, 'Número de Série', @$id_serial, 1, $hidden,' required ') ?>
                    </div>
                </div>
                <br>
                <form method="POST">
                    <?php
                    if (!empty($id_serial)) {
                        $GETequipamento = sql::get(['recurso_serial','recurso_equipamento'], 'id_equipamento,n_equipamento,prazo_max', ['id_serial' => $id_serial], 'fetch');
                        $id_equipamento = $GETequipamento['id_equipamento'] ;
                        $n_equipamento = $GETequipamento['n_equipamento'] ;
                        $emprestado = $model->getEmprestimoPessoa($id_pessoa, $id_equipamento);

                        //add meses ($GETequipamento['prazo_max']) à data de hoje, limitando ao ano atual
                        $data_fim = new DateTime($data);
                        $prazo_max = "P".$GETequipamento['prazo_max']."M";
                        $data_fim = $data_fim->add(new DateInterval($prazo_max));
                        $ano_fim = $data_fim->format('Y');
                        $data_fim = $data_fim->format('Y-m-d');
                        $ano_atual = date("Y");
                        if ($ano_fim > $ano_atual) {
                           $data_fim = $ano_atual."-12-31"; 
                        }

                        if (!empty($emprestado)) {?>
                            <div class="alert alert-<?= $gerente == 1 ? 'warning' : 'danger' ?> text-justify">
                                <?= $pessoas[$id_pessoa] ?> emprestou: <br>
                                <?php 
                                foreach ($emprestado as $v) {?>
                                    o equipamento N/S <?= $v['n_serial'] ?> retirado na <?= $v['n_inst'] ?> no dia <?= data::porExtenso($v['dt_inicio']) ?>
                                    <?php
                                } ?>
                                
                            </div><?php
                        }
                        if (empty($emprestado) || $gerente == 1){
                            $itens = $model->itensGet($id_equipamento,1);
                            $local = sql::get(['recurso_serial','recurso_local'], 'n_local, id_local', ['id_serial' => $id_serial], 'fetch');
                            if (!empty($local['id_local'])) {
                                $id_local = $local['id_local'];
                            }
                            if (array_key_exists($id_pessoa, $prof)) {?>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-check">
                                            <label for="_empresta1" style='font-size: 15px; color: black; cursor:pointer;'><input type="radio" name="1[comodato]" value="1" class="emprestEquip" id="_empresta1" /> Empréstimo em Comodato</label>
                                        </div>
                                        <div id='alert1' style="display: none;" class="alert alert-warning">
                                            Este equipamento permanece vinculado ao registro de matrícula do funcionário por tempo indeterminado até sua devolução nos termos legais.
                                        </div>

                                    </div>
                                    <div class="col">
                                        <div class="form-check">
                                            <label for="_empresta2" style='font-size: 15px; color: black; cursor:pointer;'><input type="radio" name="1[comodato]" value="0" checked class="emprestEquip" id="_empresta2" /> Empréstimo com Prazo</label>
                                        </div>
                                        <div id='alert2' class="alert alert-warning">
                                            Este equipamento permanece no inventário da escola sob responsabilidade do emprestante, o qual se compromete a efetuar a devolução até a data determinada.
                                        </div>
                                    </div>
                                </div>
                                <?php 
                            }else{
                                echo formErp::hidden(['1[comodato]' => 0]);
                            }?>
                            <div class="row viewEmprestaEquip">
                                <div class="col">
                                    <?= formErp::input('1[dt_inicio]', 'Início', $data, ' required ', null, 'date') ?>
                                </div>
                                <div class="col">
                                    <?= formErp::input('1[dt_fim]', 'Fim', $data_fim,' required ', null, 'date') ?>
                                </div>
                            </div>
                            <br /><br />
                            <div class="row">
                                <div  class="col">
                                    <div class=" mensagens">
                                        <div class="mensagem mensagemLinha-0" >
                                            <div class="row">
                                                <div  class="col tituloBox">
                                                    <?= $n_equipamento ?>
                                                </div>
                                            </div>
                                            <br>
                                            <div>
                                                <label class="tituloBox box-0">Marque os Itens que acompanham o equipamento</label><br><br>  
                                                <label class="dataMensagem">
                                                    <?= $itens ?>
                                                </label>
                                            </div>
                                            <?php   
                                            if (!empty($local['n_local'])) {?>
                                                <br><br> 
                                                <div>
                                                    <label class="tituloBox box-0">Local de Armazenamento</label><br><br>  
                                                    <label class="dataMensagem">
                                                        <?= $local['n_local'] ?>
                                                    </label>
                                                </div>
                                            <?php   
                                            }?>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>  
                            <br />
                            <div style="text-align: center">
                                <?=
                                formErp::hidden([
                                    'id_inst' => $id_inst,
                                    'enviou_form' => 1,
                                    'closeModal' => 1,
                                    'id_equipamento' => $id_equipamento,
                                    '1[fk_id_pessoa_aloca]' => toolErp::id_pessoa(),
                                    '1[id_move]' => @$id_move,
                                    '1[rm]' => @$rm,
                                    '1[fk_id_pessoa_emprest]' => @$id_pessoa,
                                    '1[fk_id_situacao]' => 2,
                                    '1[fk_id_serial]' => @$id_serial,
                                    '1[fk_id_inst]' => @$id_inst,
                                    '1[fk_id_local]' => @$id_local,
                                    '1[professor]' => @$professor
                                ])
                                . formErp::hiddenToken('emprestar')
                                ?>
                                <br /><br />
                                <?php
                                if (empty($id_move)) {
                                    ?>
                                    <button type="submit" class=" btn btn-success">
                                        Salvar
                                    </button>
                                    <?php
                                }
                                ?>
                            </div>
                            <?php
                        }
                    }?>
                </form>
                <?php
            }?>
            <?php 
            if (!empty($id_pessoa)) {
                if (!empty($historico)) {?>
                    <br><br>
                    <div class="fieldTop">
                        Histórico de Movimentação
                    </div>
                    <?php 
                    report::simple($historico);
                }else{?>
                    <br><br>
                     <div class="alert alert-warning" style="padding-top:  10px; padding-bottom: 0">
                        <div class="row" style="padding-bottom: 15px;">
                            <div class="col" style="font-weight: bold; text-align: center;">
                                Não há movimentações.
                            </div>
                        </div>
                    </div>
                    <?php
                } 
            }        
    } else {?>
        <div class="alert alert-danger">
            Não há Objetos ou pessoas elegíveis para empréstimo nesta instância.
        </div>
        <?php
    }?>
    </div>
    <?php
}?>
<script type="text/javascript">
    jQuery(function($){
        $('.emprestEquip').click(function(){
            if ($(this).val() == 1){
                $('.viewEmprestaEquip').hide();
                $('#alert1').show();
                $('#alert2').hide();
            } else {
                $('.viewEmprestaEquip').show();
                $('#alert1').hide();
                $('#alert2').show();
            }
        });
    });
</script>