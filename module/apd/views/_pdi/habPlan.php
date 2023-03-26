<?php
if (!defined('ABSPATH'))
    exit;
ob_clean();
$id_pdi_hab = filter_input(INPUT_POST, 'id_pdi_hab', FILTER_SANITIZE_NUMBER_INT);
$bimestre = filter_input(INPUT_POST, 'bimestre', FILTER_SANITIZE_NUMBER_INT);
$id_pdi = filter_input(INPUT_POST, 'id_pdi', FILTER_SANITIZE_NUMBER_INT);
$id_hab = filter_input(INPUT_POST, 'id_hab', FILTER_SANITIZE_NUMBER_INT);
$op = filter_input(INPUT_POST, 'op', FILTER_SANITIZE_STRING);

// if ($op == 'i') {
//     $ins['fk_id_pdi'] = $id_pdi;
//     $ins['id_pdi_hab'] = $id_pdi_hab;
//     $ins['fk_id_hab'] = $id_hab;
//     $ins['atualLetiva'] = $bimestre;
//     $model->db->ireplace('apd_pdi_hab', $ins, 1);
// } elseif ($op == 'del') {
//     $sql = "DELETE FROM `apd_pdi_hab` WHERE `apd_pdi_hab`.`id_pdi_hab` = $id_pdi_hab ";
//     $query = pdoSis::getInstance()->query($sql);
// }
?>
<style>
    .mensagens {}
    .mensagens .mensagem {
        border: 1px solid #e1e1d1;
        box-shadow: #e1e1d1 5px 5px 14px;
        margin: 10px auto;
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
    .mensagens .tituloHab{
        font-weight: bold;
        color: #7ed8f5;
        font-size: 100%; 
    }
    .mensagens .corpoMensagem {
        display: block;
        /*margin-top: 10px;*/
        font-weight: normal;        
        white-space: pre-wrap;
        padding: 5px;
    }
    .mensagens .mensagemLinha-0{border-left: 5px solid #7ed8f5;}
    .tituloBox{
        font-size: 17px;
        font-weight: bold;
    }
    .tituloBox.box-0{ color: #7ed8f5;}
   
</style>
<?php
$array = $model->planoHabil($id_hab);
if ($array) {
    foreach ($array as $v) {
        ?>
        <div class="row">
            <div class="col-md-12 mensagens">
                <div class="mensagem mensagemLinha-0" >
                    <div>
                        <p class="tituloBox box-0">HABILIDADE</p>
                        
                                <label class="dataMensagem"><?= $v['codigo'] ?> - <?= $v['descricao'] ?></label><br><br>
                        <div class="row">
                            <div class="col-2 tituloHab">
                                Disciplina
                            </div>
                            <div class="col-4 tituloHab">
                                Unidade Temática
                            </div>
                            <div class="col-6 tituloHab">
                                Objeto de Conhecimento
                            </div>
                        </div>
                        <div class="row">
                             <div class="col-2 nomePessoa">
                                <?= $v['n_disc'] ?>
                            </div>
                            <div class="col-4 nomePessoa">
                                <?= $v['n_ut'] ?>
                            </div>
                            <div class="col-6 nomePessoa">
                                <?= $v['n_oc'] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <input type="hidden" name="1[fk_id_hab]" id="fk_id_hab" value="<?= $v['id_hab'] ?>" />
        <?php
    }
}
