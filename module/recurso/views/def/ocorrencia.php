<?php
if (!defined('ABSPATH'))
    exit;
$id_inst = $model->gerente(1,1);
$gerente = $model->gerente();
$esconde = 0;
//$id_equipamento = filter_input(INPUT_POST, 'id_equipamento', FILTER_SANITIZE_NUMBER_INT);
$id_serial = filter_input(INPUT_POST, 'id_serial', FILTER_SANITIZE_NUMBER_INT);
$hoje = date("Y-m-d");
//$equipamento = $model->getEquipamento($id_inst);

$hidden = [
'id_serial' => $id_serial,
//'id_equipamento' => $id_equipamento,
'id_inst' => $id_inst,
];
$objetoList = $model->objetoGet($id_inst);
if (!empty($objetoList)) {?>
    <div class="body">
            <div class="row">
                    <div class="col">
                        <?= formErp::select('id_serial', $objetoList, 'Número de Série', $id_serial, 1, $hidden,' required ') ?>
                    </div>
                    <br><br>
                    <?php
                    if (!empty($id_serial)) {  
                       $n_situacao = sql::get(['recurso_serial','recurso_situacao'], 'id_situacao,n_situacao', ['id_serial' => $id_serial ], 'fetch');  
                         if (!empty($n_situacao['n_situacao'])) {
                            if ($n_situacao['id_situacao'] == 2) {
                                $esconde = 1;?>
                                <br><br>
                                <div class="alert alert-warning">
                                    Este objeto encontra-se emprestado. É necessário registrar a Ocorrência através do processo de devolução.
                                </div>   
                                <?php
                            }else if($n_situacao['id_situacao'] == 3 && $gerente <> 1) {
                                $esconde = 1;?>
                                <br><br>
                                <div class="alert alert-warning">
                                    Este Objeto está em manutenção. Somente o departamento de manutenção poderá registrar a ocorrência em Movimentação -> Manutenção.
                                </div>   
                                <?php
                            }else{?>
                                <br><br>
                                <div class="alert alert-warning">
                                    Situação atual do Objeto: <?= $n_situacao['n_situacao'] ?>
                                </div>   
                                <?php
                            }
                        }
                    }
                 //}
                 ?>  
            </div>
            <br /><br />
            <div style="text-align: center">
            <form action="<?= HOME_URI ?>/recurso/def/ocorrenciaSalvar.php" method="POST">
                <?php
                echo formErp::hidden([
                    //'id_equipamento' => $id_equipamento,    
                    'id_serial' => $id_serial,    
                ]);

                if (!empty($id_serial) && $esconde<>1) {?>
                    <button type="submit" class=" btn btn-success">
                        Continuar
                    </button> 
                <?php
                }else{?>
                     <div class="alert alert-info" style="padding-top:  10px; padding-bottom: 0">
                        <div class="row" style="padding-bottom: 15px;">
                            <div class="col" style="font-weight: bold; text-align: center;">
                                Selecione <?= $esconde==1 ? 'outro' : 'um' ?> objeto para continuar
                            </div>
                        </div>
                    </div>
                    <?php
                }?>
                      
            </form>
            </div>
        <?php 
        if (!empty($id_serial)) {
            $historico = $model->historicoGet(null,$id_serial);
            if (!empty($historico)) {?>
                <br><br>
                <div class="fieldTop">
                    Histórico de Movimentações
                </div>
                <?php
                report::simple($historico);
            }else{?>
                <br><br>
                 <div class="alert alert-warning" style="padding-top:  10px; padding-bottom: 0">
                    <div class="row" style="padding-bottom: 15px;">
                        <div class="col" style="font-weight: bold; text-align: center;">
                            Não há movimentações para este Objeto.
                        </div>
                    </div>
                </div>
                <?php
            }
        }
}else {
    ?>
    <div class="alert alert-danger">
        Não há equipamentos cadastrados nesta instância
    </div>
    <?php
}
?>