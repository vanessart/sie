<?php
if (!defined('ABSPATH'))
    exit;
$id_move = filter_input(INPUT_POST, 'id_move', FILTER_SANITIZE_NUMBER_INT);
$manutencao = filter_input(INPUT_POST, 'manutencao', FILTER_SANITIZE_NUMBER_INT);
$vizualizar = filter_input(INPUT_POST, 'vizualizar', FILTER_SANITIZE_NUMBER_INT);
$id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
$id_situacao = filter_input(INPUT_POST, 'id_situacao', FILTER_SANITIZE_NUMBER_INT);
$id_equipamento = filter_input(INPUT_POST, 'id_equipamento', FILTER_SANITIZE_NUMBER_INT);
$id_serial = filter_input(INPUT_POST, 'id_serial', FILTER_SANITIZE_STRING);
$n_pessoa = filter_input(INPUT_POST, 'n_pessoa', FILTER_SANITIZE_STRING);
$id_pessoa_rm = filter_input(INPUT_POST, 'id_pessoa_rm', FILTER_SANITIZE_NUMBER_INT);
$enviou_form = filter_input(INPUT_POST, 'enviou_form', FILTER_SANITIZE_NUMBER_INT);
$salva_manut = filter_input(INPUT_POST, 'salva_manut', FILTER_SANITIZE_NUMBER_INT);

$last_id = @$_POST['last_id'];
if (empty($last_id) && !empty($enviou_form)) {
    echo toolErp::divAlert('danger', 'Não foi possível realizar esta operaçao. Tente Novamente');
    echo formErp::hidden(['closeModal'=>1]);
}elseif (!empty($last_id) && !empty($enviou_form)) {
    if (!empty($salva_manut)) {
        echo toolErp::divAlert('success', 'Salvo com Sucesso');
    }else{
        echo toolErp::divAlert('success', 'Empréstimo finalizado com Sucesso');
    }
    if ($id_situacao == 6 || $id_situacao == 4) {
        echo toolErp::divAlert('warning', 'Foi gerada uma ocorrência para este empréstimo. Verifique em Movimentação -> Ocorrência');
    }elseif ($id_situacao == 7) {
        echo toolErp::divAlert('warning', 'Foi gerada uma Manutenção para este empréstimo. Verifique em Movimentação -> Manutenção');
    }elseif ($id_situacao == 8) {
        echo toolErp::divAlert('success', 'Objeto reparado e encontra-se disponível para empréstimo');
    }elseif ($id_situacao == 5) {
        echo toolErp::divAlert('warning', 'Objeto inservível e indisponível para empréstimo');
    }
        echo formErp::hidden(['closeModal'=>1]);
}else{
    $movimentacao = $model->movimentacaoGet($id_move);
    $itens = $model->itensGet(null,1,$id_move);
    $ids_ocorrencia = [3,4,5,6,7,8];//fk_id_situacao
    $attr = 'required';
    $hidden = [
    'id_move' => $id_move,
    'manutencao' => $manutencao,
    'id_pessoa' => $id_pessoa,
    'id_serial' => $id_serial,
    'id_situacao' => $id_situacao,
    'n_pessoa' => $n_pessoa,
    'id_pessoa_rm' => $id_pessoa_rm,
    ];
    if ($manutencao == 1) {
        $situacao = sql::idNome('recurso_situacao', 'WHERE id_situacao IN (3,5,8)');
        $historico = $model->historicoGet(null,$id_serial);
        $action = 'manutencao';
    }else if(!empty($id_pessoa)){
        $historico = $model->historicoGet($id_pessoa,null);
        $action = 'empresta';
        if (in_array(user::session('id_nivel'), [10])) {
            $situacao = sql::idNome('recurso_situacao', 'WHERE id_situacao IN (1,4,6,7)');
        }else{
            $situacao = sql::idNome('recurso_situacao', 'WHERE id_situacao IN (1,4,6)');
        }
    }
    if ($vizualizar == 1) {
        $attr = 'disabled';
        $situacao = sql::idNome('recurso_situacao', 'WHERE id_situacao ='.$id_situacao);
        $historico = $model->historicoGet(null,$id_serial);?>
        <script>
            document.getElementsByName('1[obs]')[0].setAttribute("disabled", "disabled") ;
        </script>
        <?php
    }
    ?>
    <style type="text/css">
        .titulo_anexo{
            color: #888;
            font-weight: bold;
            text-align: center;
            font-size: 30px;
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
            font-size: 100%;
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
        .mensagens .mensagemLinha-1{border-left: 5px solid #f3b4ef;}
        .mensagens .mensagemLinha-2{border-left: 5px solid #f9ca6e;}
        .esconde .input-group-text{ display: none; }
        .tituloBox{
            font-size: 17px;
            font-weight: bold;
            text-align: center;
            color: #7ed8f5;
        }
        .tituloBox.box-0{ color: #7ed8f5;}
        .tituloBox.box-1{ color: #f3b4ef;}
        .tituloBox.box-2{ color: #f9ca6e;}
    </style>

    <div class="body">
        
        <div class="row">
            <div  class="col">
                <div class=" mensagens">
                    <div class="mensagem mensagemLinha-0" >
                        <div class="row">
                            <div  class="col tituloBox">
                                <?= $movimentacao['n_equipamento'] ?>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div  class="col">
                               <label class="dataMensagem">Número de Série:</label> <label class="nomePessoa"><?= $movimentacao['n_serial'] ?></label>
                            </div>
                            <?php 
                            if ($id_situacao == 2 || $id_situacao == 6) { ?>
                                <div  class="col">
                                   <label class="dataMensagem">Local do Empréstimo:</label> <label class="nomePessoa"><?= $movimentacao['n_inst'] ?> <?= !empty($movimentacao['n_local'])? " - ".$movimentacao['n_local']:"" ?></label>
                                </div>
                                <?php 
                            }?>
                        </div>
                        <br>
                        <?php 
                        if ($id_situacao == 2 || $id_situacao == 6) { ?>
                            <div class="row">
                                <div  class="col">
                                    <label class="dataMensagem">Período do Empréstimo:</label> <label class="nomePessoa"><?= dataErp::converteBr($movimentacao['dt_inicio']).' a '.dataErp::converteBr($movimentacao['dt_fim']) ?></label>
                                </div>
                                <div  class="col">
                                   <label class="dataMensagem">Local de Devolução:</label> <label class="nomePessoa"><?= $movimentacao['local_devolve'] ?></label>
                                </div>
                            </div>
                            <br>
                            <?php 
                        }?>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col">
                <?= formErp::select('id_situacao', @$situacao, ['Situação','Selecione >>>'], $id_situacao, 1, $hidden, 'required') ?>
            </div>
        </div>
        <br>
        <?php
        if ($id_situacao == 7) {
            $btn = "Encerrar e enviar para Manutenção";
            $texto_devolve = " junto com o Equipamento";
        }else if ($id_situacao == 1){
            $btn = "Encerrar processo de Empréstimo";
            $texto_devolve = " junto com o Equipamento";
            ?>
             <div class="alert alert-warning" style="padding-top:  10px; padding-bottom: 0">
                <div class="row" style="padding-bottom: 15px;">
                    <div class="col" style="font-weight: bold; text-align: center;">
                        Antes de encerrar, certifique-se de que o equipamento esteja em perfeitas condições. Caso contrário, utilize outra opção no campo "Situação" e registre a ocorrência.
                    </div>
                </div>
            </div>
            <?php
        }else{
            $btn = "Salvar Alterações";
        }

        if (!empty($id_situacao)) {
                if (in_array($id_situacao, $ids_ocorrencia)) { ?>
                <form enctype="multipart/form-data"  method="POST">
                    <?php if ($manutencao <> 1) {?>
                       <div class="row tit_table">
                            <div class="col">
                                <?php if ($vizualizar <> 1) {
                                    $texto_marque = "Marque os ";
                                }else{
                                  $itens = $model->itensGet(null,null,$id_move);//itens sem checkbox 
                                } ?>
                                <?= @$texto_marque ?>Itens devolvidos <?= @$texto_devolve ?>
                            </div>
                        </div>
                        <br>
                        <?php
                        if (!empty($itens)) {
                            echo $itens;
                        }    
                    } ?>
                    <br> <br>
                    <div class="row">
                        <div class="col">
                            <?= formErp::textarea('1[obs]', @$movimentacao['obs'], 'Ocorrência') ?>
                        </div>
                    </div>
                    <br>
                    <div class="border">
                        <div style="text-align: center; padding: 8px">
                            Uploads
                        </div>
                        <?php
                        if ($vizualizar<>1) {?>
                            <div class="row">
                                <div class="col">
                                    <?= formErp::input('n_doc', 'Documento') ?>
                                </div>
                                <div class="col">
                                    <?= formErp::input('arquivo', 'Upload', null, null, null, 'file') ?>
                                </div>
                            </div>
                            <br />
                            <?php  
                         }?>
                        
                        <?php
                        $doc = sql::get('recurso_doc', 'n_doc, end, id_doc', ['fk_id_move' => $id_move]);
                        if ($doc) {
                            ?>
                            <div class="row">
                                <?php
                                foreach ($doc as $v) {
                                    ?>
                                    <div class="col">
                                        <table>
                                            <td>
                                                <a href="javascript:" class="btn btn-outline-info" onclick="viewDoc('<?= str_replace("'", "\'", $v['end']) ?>')">
                                                    <?= empty($v['n_doc']) ? 'Documento' : $v['n_doc'] ?>
                                                </a>
                                            </td>
                                            <?php
                                            if ($vizualizar<>1) {?>
                                                <td>
                                                    <a href="javascript:" class="btn btn-outline-danger" onclick="delDoc(<?= $v['id_doc'] ?>)">
                                                        X
                                                    </a>
                                                </td>
                                                <?php  
                                            }?>
                                        </table>

                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <br /><br />
                            <?php
                        }else{
                            echo "Não há documentos anexados";
                        }?>
                    </div>
                    <br /><br />
                    <div class="row">
                        <div class="col text-center">
                            <?php
                            if ($manutencao == 1  && ($vizualizar<>1)) {
                                echo formErp::hiddenToken('manutencaoSalvar')
                                . formErp::hidden([
                                    'id_equipamento' => @$movimentacao['id_equipamento'],
                                    '1[fk_id_pessoa_aloca]' => toolErp::id_pessoa(),
                                    'id_move' => @$id_move,
                                    '1[fk_id_situacao]' => @$id_situacao,
                                    'id_situacao' => @$id_situacao,
                                    '1[fk_id_serial]' => @$movimentacao['id_serial'],
                                    'enviou_form' => 1,
                                    'salva_manut' => 1,
                                ])
                                . formErp::button($btn);
                            }else if(($vizualizar<>1)){
                                echo formErp::hiddenToken('ocorrenciaSalvar')
                                . formErp::hidden([
                                    'fk_id_inst_devolve_geral' => @$movimentacao['fk_id_inst_devolve_geral'],
                                    'fk_id_inst_devolve_prof' => @$movimentacao['fk_id_inst_devolve_prof'],
                                    'professor' => @$movimentacao['professor'],
                                    'comodato' => @$movimentacao['comodato'],
                                    '1[fk_id_pessoa_aloca]' => toolErp::id_pessoa(),
                                    '1[id_move]' => @$id_move,
                                    '1[fk_id_situacao]' => @$id_situacao,
                                    'id_situacao' => @$id_situacao,
                                    '1[fk_id_inst]' => @$movimentacao['fk_id_inst'],
                                    '1[fk_id_serial]' => @$movimentacao['id_serial'],
                                    'enviou_form' => 1,
                                ])
                                . formErp::button($btn); 
                            }
                            ?>
                        </div>
                    </div>
                </form>
                <form target="_blank" id="viewDoc"></form>
                <form method="POST" id="delDoc">
                    <input type="hidden" name="1[id_doc]" id="idDoc">
                    <?=
                    formErp::hidden($hidden)
                    . formErp::hiddenToken('recurso_doc', 'delete')
                    ?>
                </form>
            <?php
            }else{?>
                <form method="POST">
                    <div class="row tit_table">
                        <div class="col">
                            Marque os Itens devolvidos <?= @$texto_devolve ?>
                        </div>
                    </div>
                    <br>
                    <?php
                    if (!empty($itens)) {
                        echo $itens;
                    }?>
                    <br> <br>
                    <div class="row">
                        <div class="col">
                            <?= formErp::textarea('1[obs]', @$movimentacao['obs'], 'Observações') ?>
                        </div>
                    </div>
                    <br> <br>
                    <div class="row">
                        <div class="col text-center">
                            <?=
                            formErp::hiddenToken('emprestimoFim')
                            . formErp::hidden([
                                'id_equipamento' => @$movimentacao['id_equipamento'],
                                'fk_id_inst_devolve_geral' => @$movimentacao['fk_id_inst_devolve_geral'],
                                'fk_id_inst_devolve_prof' => @$movimentacao['fk_id_inst_devolve_prof'],
                                'professor' => @$movimentacao['professor'],
                                'comodato' => @$movimentacao['comodato'],
                                'id_emprestimo' => 1,
                                '1[fk_id_pessoa_aloca]' => toolErp::id_pessoa(),
                                '1[id_move]' => @$id_move,
                                'id_situacao' => @$id_situacao,
                                '1[fk_id_situacao]' => @$id_situacao,
                                '1[fk_id_serial]' => @$movimentacao['id_serial'],
                                'enviou_form' => 1,
                            ])
                            . formErp::button($btn)
                            ?>
                        </div>
                    </div>
                </form>
                <?php
            }?>
            <br><br>
            <?php
        }?>
        <div class="fieldTop">
            Histórico de Movimentação
        </div>
        <?php 
        if (!empty($historico)) {
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
        } ?>

    </div>
    <?php
}?>
<script>
    <?php 
if (!empty($closeModal)){ ?>
        //parent.closemodal();
    <?php 
}?>
    <?php
if ($vizualizar == 1) {?>
        document.getElementsByName('1[obs]')[0].setAttribute("disabled", "disabled") ;
    <?php
} ?>

function viewDoc(end){
    document.getElementById('viewDoc').action = '<?= HOME_URI ?>/pub/labDoc/'+ end;
    document.getElementById('viewDoc').submit();
    return false;
}

function delDoc(idDoc){
    document.getElementById('idDoc').value = idDoc;
    document.getElementById('delDoc').submit();
}
</script>