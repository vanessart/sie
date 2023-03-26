<?php
/**
 * Description of quest
 *
 * @author vanessa
 */
class quest {

    public static function getForm($id_form, $fk_id_pai = null, $table_perguntas , $table_opcoes) {

        if (empty($_perguntas)) {
            $_perguntas = [];
        }

        $P1 = self::getPerguntas($id_form, $fk_id_pai, $table_perguntas);

        if ( empty($P1) ){
            return null;
        }

        foreach ($P1 as $k => $v) {
            $_perguntas[$v['id_pergunta']] = $v;
            $_perguntas[$v['id_pergunta']]['peguntas'] = [];
            $_perguntas[$v['id_pergunta']]['opcoes'] = [];

            $pergs = self::getForm($id_form, $v['id_pergunta'], $table_perguntas , $table_opcoes);
            if (!empty($pergs)) {
                $_perguntas[$v['id_pergunta']]['peguntas'] = $pergs;
            }

            $opcoes = self::getOpcoes($v['id_pergunta'],null, $table_opcoes);
            if (!empty($opcoes)) {
                $_perguntas[$v['id_pergunta']]['opcoes'] = $opcoes;
            }
        }

        return $_perguntas;
    }

    public static function getPerguntas($id_form, $fk_id_pai = null, $table_perguntas) {
        $sqlPai = '';
        if (empty($fk_id_pai)){
            $sqlPai .= " AND p1.fk_id_pai IS NULL ";
        } else {
            $sqlPai .= " AND p1.fk_id_pai = $fk_id_pai ";
        }

        $sql = "SELECT "
            . " id_pergunta, tem_resposta, n_pergunta, ordem, fk_id_pai "
            . " FROM $table_perguntas AS p1 " //tabela perguntas
            . " WHERE fk_id_form = $id_form"
            . $sqlPai
            . " ORDER BY p1.ordem ";

        $query = pdoSis::getInstance()->query($sql);
        $P1 = $query->fetchAll(PDO::FETCH_ASSOC);
        return $P1;
    }

    public static function getOpcoes($fk_id_pergunta, $fk_id_pai = null, $table_opcoes) {
        /*$sqlPai = '';
        if (empty($fk_id_pai)){
            $sqlPai .= " AND p1.fk_id_pai IS NULL ";
        } else {
            $sqlPai .= " AND p1.fk_id_pai = $fk_id_pai ";
        }*/

        $sql = "SELECT "
                . " fk_id_pergunta, id_opcao, n_opcao, tipo, acao, type "
                . " FROM $table_opcoes" //tabela opcoes
                . " WHERE fk_id_pergunta = $fk_id_pergunta"
                . " ORDER BY ordem ";
        $query = pdoSis::getInstance()->query($sql);
        $P1 = $query->fetchAll(PDO::FETCH_ASSOC);
        return $P1;
    }

    public static function getView($form,$id_pessoa,$id_form,$table_respostas,$id_protocolo=null){  
        $respostas = self::getRespostas($id_form,$id_protocolo,$table_respostas);
        if (empty($form)) return '';
        $oa = concord::oa($id_pessoa);
        $OA = concord::oa($id_pessoa,1);//maiusculo
        $seu = concord::seu($id_pessoa);
        $a_ao = concord::a_ao($id_pessoa);
        foreach ($form as $k => $v) {?>
            <div class="row pergunta_<?= $v['id_pergunta'] ?>">
                <div class="col col-sm" style="<?= !empty($v['fk_id_pai']) ? 'padding-left: 30px' : 'font-weight:bold' ?>">
                    <?php
                    $data_template = array( 'oa'=>$oa,'OA'=>$OA,'seu'=>$seu,'a_ao'=>$a_ao);
                    $n_pergunta = parser::parse_string($v['n_pergunta'],$data_template,true);
                    echo $n_pergunta.'<br>';?>
                </div>
            </div>
            <br>
            <?php
            if(!empty($v['opcoes'])){?>
                <div class="row" style="padding-left: 30px;">
                    <?php
                    foreach ($v['opcoes'] as $i => $j) {
                        if (strpos($j['acao'], 'required') !== false) {
                            $classe = 'class="opcoes"';
                        }else{
                            $classe = '';
                        }
                        $id_opcao = 'null';
                        if ($j['tipo']==2) {

                            if (!empty($respostas)) {
                               if (array_key_exists($j['id_opcao'], $respostas)) {
                                    $id_opcao = $j['id_opcao'];
                                }else{
                                    $id_opcao = 'null';
                                } 
                            }?>
                            <div class="col col-sm" id="c_<?= $j['id_opcao'] ?>">
                                <?php
                                echo formErp::radio('1[fk_id_pergunta]['.$v['id_pergunta'].']',$j['id_opcao'],$j['n_opcao'],$id_opcao,$classe.' data-id-pergunta="'.$v['id_pergunta'].'" id='.$j['id_opcao'].' '.$j['acao']);?>
                            </div>
                            <?php
                        }
                    }?>
                </div>
                <div class="row"  style="padding-left: 30px;" data-id-pergunta="87" class="opcoes">
                    <div class="col">
                        <div class="row">
                            <?php
                            foreach ($v['opcoes'] as $i => $j) {
                                if (strpos($j['acao'], 'required') !== false) {
                                    $classe = 'class="opcoes"';
                                }else{
                                    $classe = '';
                                }
                                if ($j['tipo']==1) {
                                    $id_opcao = 'null';
                                    if (!empty($respostas)) {
                                        if (array_key_exists($j['id_opcao'], $respostas)) {
                                            $id_opcao = $j['id_opcao'];
                                        }else{
                                            $id_opcao = 'null';
                                        }
                                    }
                                    if (substr_count($j['n_opcao'],' ')>2){?>
                                        </div>
                                        <div class="row">
                                        <?php
                                    }
                                    ?>
                                    <div class="col col-sm" style="padding-top: 10px;" id="c_<?= $j['id_opcao'] ?>">
                                        <?php
                                        echo formErp::checkbox('1[fk_id_pergunta][checkbox]['.$v['id_pergunta'].'][]',$j['id_opcao'],$j['n_opcao'],$id_opcao,$classe.' data-id-pergunta="'.$v['id_pergunta'].'" id='.$j['id_opcao'].' '.$j['acao']);?>
                                    </div>
                                    <?php
                                    if (substr_count($j['n_opcao'],' ')>2){?>
                                        </div>
                                        <div class="row">
                                        <?php
                                    }
                                    
                                }
                            }?>
                        </div>
                    </div>
                </div>
                <div class="row"  style="padding-left: 30px;">
                    <?php
                    foreach ($v['opcoes'] as $i => $j) {
                        if (strpos($j['acao'], 'required') !== false) {
                            $classe = 'class="opcoes"';
                        }else{
                            $classe = '';
                        }
                        if ($j['tipo']==0) {
                            $n_resposta = null;
                            if ($j['type'] == 1) {
                                $type = 'date';
                            }else{
                                $type = 'text';
                            }
                            if (!empty($respostas)) {
                                if (array_key_exists($j['id_opcao'], $respostas)) {
                                    $n_resposta = $respostas[$j['id_opcao']];
                                }else{
                                    $n_resposta = null;
                                }
                            }
                            ?>
                            <div class="col col-sm"  style="padding-top: 10px;"  id="c_<?= $j['id_opcao'] ?>">
                                <?php
                                echo formErp::input('1[fk_id_pergunta][text]['.$j['id_opcao'].']',$j['n_opcao'],$n_resposta,$classe.'  data-id-pergunta="'.$v['id_pergunta'].'" id='.$j['id_opcao'].' '.$j['acao'],null,$type); ?>
                            </div>
                            <?php
                        }
                    }?>
                </div>
                <?php
            }
            if(!empty($v['peguntas'])){
                self::getView($v['peguntas'],$id_pessoa,$id_form,$table_respostas,$id_protocolo);
            }
            ?>
            <br>
            <div style="border-bottom: 3px solid #e7e1e1"></div>
            <br>
            <?php
        }
    }

    public static function getProtocoloStatus($id_protocolo, $table_status, $tudo=null) {
        $sql = "SELECT "
                . " fk_id_proto_status, n_pessoa, dt_update, n_status, justifica"
                . " FROM $table_status s " // tabela respostas
                . " JOIN pessoa p ON p.id_pessoa = s.fk_id_pessoa"
                . " JOIN protocolo_status ps ON ps.id_proto_status = s.fk_id_proto_status"
                . " WHERE fk_id_protocolo = $id_protocolo"
                . " ORDER BY dt_update DESC";
        $query = pdoSis::getInstance()->query($sql);

        if (!empty($tudo)) {
            $array = $query->fetchAll(PDO::FETCH_ASSOC); 
        }else{
            $array = $query->fetch(PDO::FETCH_ASSOC);
        }
        if (!empty($array)) {
            $status = $array ;
        }else{
            $status['fk_id_proto_status'] = 0;
        }

        return $status; 
    }

    public static function getRespostas($id_form,$id_protocolo,$table_respostas) {
        if (!empty($id_protocolo) && !empty($id_form)){
            $sql = "SELECT "
                . " fk_id_opcao, n_resposta"
                . " FROM $table_respostas " // tabela respostas
                . " WHERE fk_id_form = $id_form"
                . " AND fk_id_protocolo = $id_protocolo";
            $query = pdoSis::getInstance()->query($sql);
            $P1 = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($P1 as $k => $v) {
                $resp[$v["fk_id_opcao"]] = $v["n_resposta"];
            }

            if (!empty($resp)) {
               return $resp; 
            }
             
        } else {
            $lista_alunos = toolErp::divAlert('warning', 'Questionário não encontrado');
            die();
        }
    }

    public static function getViewPDF($form,$id_protocolo,$id_form,$respostas=null,$table_respostas){  
        $respostas = self::getRespostas($id_form,$id_protocolo,$table_respostas);
        if (empty($form)) return '';

        foreach ($form as $k => $v) {?>
             <table style="font-size: 12px; border: 0px;padding:0; margin-top:<?= empty($v['fk_id_pai']) ? '20px' : "0" ?>; border-collapse: collapse;">
                <tr>
                    <td width="78%" <?= empty($v['fk_id_pai']) ? 'style="font-weight:bold"' : "" ?>>
                        <?php
                        echo $v['n_pergunta'];?>
                    </td>
                    <td>
                        <?php
                        if(!empty($v['opcoes'])){
                            foreach ($v['opcoes'] as $i => $j) {
                                $respX = ' ';
                                if ($j['tipo']==2) {

                                    if (!empty($respostas)) {
                                       if (array_key_exists($j['id_opcao'], $respostas)) {
                                            $respX = '<b>X</b>';
                                        }else{
                                            $respX = ' ';
                                        } 
                                    }
                                        echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$j['n_opcao'].' ('.$respX.')';
                                }
                            }
                        }?>
                    </td>
                </tr>
            </table>
            <?php
            if(!empty($v['opcoes'])){?>
                <div class="row"  style="padding-left: 30px; font-size: 12px;">
                    <div class="col">
                        <?php
                        foreach ($v['opcoes'] as $i => $j) {
                            if ($j['tipo']==1) {
                                $respX = ' ';
                                if (!empty($respostas)) {
                                    if (array_key_exists($j['id_opcao'], $respostas)) {
                                        $respX = '<b>X</b>';
                                    }else{
                                        $respX = ' ';
                                    }
                                } 
                                echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$j['n_opcao'].' ('.$respX.')';
                            }
                        }?>
                    </div>
                </div>
                <div class="row"  style="padding-left: 30px; font-size: 12px;">
                    <div class="col">
                        <?php
                        foreach ($v['opcoes'] as $i => $j) {
                            if ($j['tipo']==0) {
                                $n_resposta = '<br>&nbsp;&nbsp;&nbsp;&nbsp;_____________________________________________________________';
                                if (!empty($respostas)) {
                                    if (array_key_exists($j['id_opcao'], $respostas)) {
                                        $n_resposta = '<b>'.$respostas[$j['id_opcao']].'</b>';
                                    }
                                }
                                echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$j['n_opcao'].' '.$n_resposta;
                            }
                        }?>
                    </div>
                </div>
                <?php
            }
            if(!empty($v['peguntas'])){?>
                <div class="row"  style="padding-left: 30px;">
                    <?php self::getViewPDF($v['peguntas'],$id_protocolo,$id_form,1,'protocolo_form_resposta'); ?>
                </div>
                
                <?php
            }
            ?>
            
            <?php
        }
    }
}