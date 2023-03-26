<?php
if (!defined('ABSPATH'))
    exit;
$id_campanha = filter_input(INPUT_POST, 'id_campanha', FILTER_SANITIZE_NUMBER_INT);
$ciclos = $model->getCiclos();
$ciclosForm = $model->getCiclosCampanha($id_campanha,1);
$ciclosAviso = $model->getCiclosCampanha($id_campanha,2);

$campanha = sql::get('audicao_campanha', 'n_campanha, liberar_aviso, liberar_form', 'WHERE id_campanha =' . $id_campanha, 'fetch');
?>
<style type="text/css">
    .input-group-text
    {
        width: 220px;
    }
    fieldset.add-border {
        border: 1px groove #ddd !important;
        padding: 0 1.4em 1.4em 1.4em !important;
        margin: 0 0 1.5em 0 !important;
        -webkit-box-shadow:  0px 0px 0px 0px #000;
                box-shadow:  0px 0px 0px 0px #000;
    }

    legend.add-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:inherit; /* Or auto */
        padding:0 10px; /* To give a bit of padding on the left and right */
        border-bottom:none;
        margin-top: -10px;
        background-color: white;
    }
</style>
<div class="body">
    <div class="fieldTop" style="padding-bottom: 5%;">
        CAMPANHA: <?= $campanha['n_campanha'] ?>
    </div>
    <?= toolErp::divAlert('danger', 'Atenção! Ao liberar o aviso, a escola poderá imprimir o encaminhamento do aluno tão logo for respondido algum questionário e o aluno falhar.<br><br> Caso queira que os avisos sejam liberados após todos os formulários respondidos, deixe a opção NÃO selecionada e mude para SIM apenas no momento desejado.') ?>
    <form name='form' id="form" action="<?= HOME_URI ?>/audicao/campanha" method="POST" target='_parent'>   
        <br><br>
        <fieldset class="add-border">
            <legend class="add-border">Confirgurar Liberação de Questionários</legend>
            <br>
            <div class="row">
                <div class="col">
                    <?= formErp::select('3[liberar_form]',['1' => 'SIM', '0' => 'NÃO' ], ['Liberar','Selecione'], @$campanha['liberar_form']) ?>
                </div>
            </div>
            <br> 
            <div class="row">
                <div class="col">
                    Informe quais ciclos deverão responder aos questionários nesta campanha:
                </div>
            </div>
            <br> 
            <div class="row">  
                <div class="col">
                    <table class="table table-condensed table-responsive table-striped" cellpadding="2" style="width:100%">
                        <tr>
                            <?php
                            foreach ($ciclos as $k => $v){?>
                                <td><?= $k ?></td>
                                <?php 
                            } ?>
                        </tr>
                        <tr>
                                <?php 
                            foreach ($ciclos as $j=>$v){ 
                                ?>
                                <td>
                                    <table>
                                        <tr style="vertical-align: baseline;">
                                            <td>
                                                    <?php
                                                $conta = 1;
                                                foreach ($ciclos[$j] as $ki=>$vi){
                                                    if (($conta % 11) == 0 ) { ?>
                                                        </td>
                                                        <td>
                                                       <?php 
                                                     }
                                                      if (in_array($ki, $ciclosForm)) { 
                                                            $check_form = 'checked';
                                                        }else{
                                                            $check_form = '';
                                                        }    
                                                     ?>
                                                    <label class="container">
                                                        <span style="font-size: 14px; padding-left: 15px"><?= $vi ?></span>
                                                        <input <?= $check_form ?> type="checkbox" name="1[fk_id_ciclo][]" value="<?= $ki ?>" required>
                                                        <span class="checkmark"></span>
                                                    </label> 
                                                        <?php
                                                    $conta++;   
                                                } ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                            
                                <?php 
                            } ?>
                        </tr>
                    </table>
                </div>
            </div>
        </fieldset>
        <br><br>
        <fieldset class="add-border">
            <legend class="add-border">Confirgurar Liberação de Avisos e Encaminhamentos</legend>
            <br>
            <div class="row">
                <div class="col">
                    <?= formErp::select('3[liberar_aviso]',['1' => 'SIM', '0' => 'NÃO' ], ['Liberar','Selecione'], @$campanha['liberar_aviso']) ?>
                </div>
            </div>
            <br> 
            <div class="row">
                <div class="col">
                    Informe quais ciclos deverão receber os encaminhamentos nesta campanha:
                </div>
            </div>
            <br> 
            <div class="row">  
                <div class="col">
                    <table class="table table-condensed table-responsive table-striped" cellpadding="2" style="width:100%">
                        <tr>
                            <?php
                            foreach ($ciclos as $k => $v){?>
                                <td><?= $k ?></td>
                                <?php 
                            } ?>
                        </tr>
                        <tr>
                                <?php 
                            foreach ($ciclos as $j=>$v){ 
                                ?>
                                <td>
                                    <table>
                                        <tr style="vertical-align: baseline;">
                                            <td>
                                                    <?php
                                                $conta = 1;
                                                foreach ($ciclos[$j] as $ki=>$vi){
                                                    if (($conta % 11) == 0 ) { ?>
                                                        </td>
                                                        <td>
                                                       <?php     
                                                     }
                                                    if (in_array($ki, $ciclosAviso)) { 
                                                        $check_aviso = 'checked';
                                                    }else{
                                                        $check_aviso = '';
                                                    }?>
                                                    <label class="container">
                                                        <span style="font-size: 14px; padding-left: 15px"><?= $vi ?></span>
                                                        <input <?= $check_aviso ?> type="checkbox" name="2[fk_id_ciclo][]" value="<?= $ki ?>" required>
                                                        <span class="checkmark"></span>
                                                    </label> 
                                                        <?php
                                                    $conta++;   
                                                } ?>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                                            
                                <?php 
                            } ?>
                        </tr>
                    </table>
                </div>
            </div>
        </fieldset>
        <br><br>
        <div class="row">
            <div class="col text-center">
                <?=
                formErp::hidden([
                    '3[id_campanha]' => @$id_campanha,
                    '3[fk_id_pessoa]' => toolErp::id_pessoa(),
                ])
                . formErp::hiddenToken('edita_campanha')
                . formErp::button('Salvar',null,"salvar()");
                ?>            
            </div>
        </div>     
    </form>
</div>
<script type="text/javascript">
    function salvar(){
        document.getElementById('form').submit();
    }
</script>