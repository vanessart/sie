<?php
/**
 * Description of formDB
 * Funçao para gerar formulários através do banco de dados
 * @author vanessa
 */
class formDB {
    public static function getForm($id_form, $fk_id_pai = null) {

        if (empty($_perguntas)) {
            $_perguntas = [];
        }

        $P1 = self::getPerguntas($id_form, $fk_id_pai);

        if ( empty($P1) ){
            return null;
        }

        foreach ($P1 as $k => $v) {
            $_perguntas[$v['id_pergunta']] = $v;
            $_perguntas[$v['id_pergunta']]['peguntas'] = [];
            $_perguntas[$v['id_pergunta']]['opcoes'] = [];

            $pergs = self::getForm($id_form, $v['id_pergunta']);
            if (!empty($pergs)) {
                $_perguntas[$v['id_pergunta']]['peguntas'] = $pergs;
            }

            $opcoes = self::getOpcoes($v['id_pergunta']);
            if (!empty($opcoes)) {
                $_perguntas[$v['id_pergunta']]['opcoes'] = $opcoes;
            }
        }

        return $_perguntas;
    }

    public static function getPerguntas($id_form, $fk_id_pai = null) {
        $sqlPai = '';
        if (empty($fk_id_pai)){
            $sqlPai .= " AND p1.fk_id_pai IS NULL ";
        } else {
            $sqlPai .= " AND p1.fk_id_pai = $fk_id_pai ";
        }

        $sql = "SELECT "
            . " id_pergunta, tem_resposta, n_pergunta, ordem, fk_id_pai "
            . " FROM form_perguntas AS p1 "
            . " WHERE fk_id_form = $id_form"
            . $sqlPai
            . " ORDER BY p1.ordem ";

        $query = pdoSis::getInstance()->query($sql);
        $P1 = $query->fetchAll(PDO::FETCH_ASSOC);
        return $P1;
    }

    public static function getOpcoes($fk_id_pergunta, $fk_id_pai = null) {
        /*$sqlPai = '';
        if (empty($fk_id_pai)){
            $sqlPai .= " AND p1.fk_id_pai IS NULL ";
        } else {
            $sqlPai .= " AND p1.fk_id_pai = $fk_id_pai ";
        }*/

        $sql = "SELECT "
                . " fk_id_pergunta, id_opcao, n_opcao, tipo, acao "
                . " FROM form_opcoes"
                . " WHERE fk_id_pergunta = $fk_id_pergunta"
                . " ORDER BY ordem ";
        $query = pdoSis::getInstance()->query($sql);
        $P1 = $query->fetchAll(PDO::FETCH_ASSOC);
        return $P1;
    }

    public static function getView($form,$id_pessoa,$id_form,$id_pl){  
        $respostas = self::getRespostas($id_form,$id_pessoa,$id_pl);
        $dadosForm = self::getDadosForm($id_form,$id_pessoa,$id_pl);
        if (empty($form)) return '';
        $oa = concord::oa($id_pessoa);
        $seu = concord::seu($id_pessoa);
        foreach ($form as $k => $v) { ?>
            <div class="row pergunta_<?= $v['id_pergunta'] ?>">
                <div class="col col-sm per" style="<?= !empty($v['fk_id_pai']) ? 'padding-left: 30px' : 'font-weight:bold' ?>">
                    <?php
                    $data_template = array( 'oa'=>$oa,'seu'=>$seu);
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
                                echo formErp::input('1[fk_id_pergunta][text]['.$j['id_opcao'].']',$j['n_opcao'],$n_resposta,$classe.'  data-id-pergunta="'.$v['id_pergunta'].'" id='.$j['id_opcao'].' '.$j['acao']);?>
                            </div>
                            <?php
                        }
                    }?>
                </div>
                <?php
            }
            if(!empty($v['peguntas'])){
                self::getView($v['peguntas'],$id_pessoa,$id_form);
            }
            ?>
            <br>
            <div style="border-bottom: 3px solid #e7e1e1"></div>
            <br>
            <?php
        }
        return $dadosForm;
    }

    public static function getViewPDF($form,$id_pessoa,$id_form,$id_pl,$respostas=null){  
        $respostas = self::getRespostas($id_form,$id_pessoa,$id_pl);
        $dadosForm = self::getDadosForm($id_form,$id_pessoa,$id_pl);
        $oa = concord::oa($id_pessoa);
        $seu = ucfirst(concord::seu($id_pessoa));
        
        if (empty($form)) return '';

        foreach ($form as $k => $v) {?>
             <table style="font-size: 12px; border: 0px;padding:0; margin-top:<?= empty($v['fk_id_pai']) ? '20px' : "0" ?>; border-collapse: collapse;">
                <tr>
                    <td width="78%" <?= empty($v['fk_id_pai']) ? 'style="font-weight:bold"' : "" ?>>
                        <?php
                        $data_template = array( 'oa'=>$oa,'seu'=>$seu);
                        $n_pergunta = parser::parse_string($v['n_pergunta'],$data_template,true);
                        echo $n_pergunta.'<br>';?>
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
                    <?php self::getViewPDF($v['peguntas'],$id_pessoa,$id_form); ?>
                </div>
                
                <?php
            }
            ?>
            
            <?php
        }
    }
    /*Retorna respostas não geradas automaticamente*/
    public static function getDadosForm($id_form,$id_pessoa,$id_pl) {
        if (!empty($id_pessoa) && !empty($id_form)){
            $sql = "SELECT "
                . " fo.tipo, fo.id_opcao, fr.n_resposta"
                . " FROM form_resposta fr"
                . " JOIN form_opcoes fo  ON fo.id_opcao = fr.fk_id_opcao"
                . " WHERE fr.fk_id_form = $id_form"
                . " AND fr.fk_id_pessoa = $id_pessoa"
                . " AND fr.fk_id_pl = $id_pl";
            $query = pdoSis::getInstance()->query($sql);
            $P1 = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($P1 as $k => $v) {
                if ($v["tipo"]== -1) {
                    $resp[$v["id_opcao"]] = $v["n_resposta"];
                }
            }
            if (!empty($resp)) {
               return $resp; 
            }   
        }
    }

    public static function getViewPDFformatado($form,$id_pessoa,$id_form,$id_pl,$respostas=null){  
        $respostas = self::getRespostas($id_form,$id_pessoa,$id_pl);
        $dadosForm = self::getDadosForm($id_form,$id_pessoa,$id_pl);
        $oa = concord::oa($id_pessoa);
        $seu = ucfirst(concord::seu($id_pessoa));
        
        if (empty($form)) return '';

        foreach ($form as $k => $v) {?>
             <table style="font-size: 12px; border: 0px;padding:0; margin-top:<?= empty($v['fk_id_pai']) ? '20px' : "0" ?>; border-collapse: collapse;">
                <tr>
                    <td width="78%" <?= empty($v['fk_id_pai']) ? 'style="font-weight:bold"' : "" ?>>
                        <?php
                        $data_template = array( 'oa'=>$oa,'seu'=>$seu);
                        $n_pergunta = parser::parse_string($v['n_pergunta'],$data_template,true);
                        echo $n_pergunta.'<br>';?>
                    </td>
                </tr>
            </table>
            <?php
            if(!empty($v['opcoes'])){?>
                <div class="row"  style="padding-left: 30px; font-size: 12px;">
                    <div class="col">
                        <?php
                        if(!empty($v['opcoes'])){
                            foreach ($v['opcoes'] as $i => $j) {
                                if ($j['tipo']==2) {
                                    if (!empty($respostas)) {
                                       if (array_key_exists($j['id_opcao'], $respostas)) {
                                            echo $j['n_opcao'].'.';
                                        }
                                    }
                                }
                            }
                        }?>
                        <?php
                        $sp = '&nbsp;&nbsp;&nbsp;&nbsp;';
                        $vg = '';
                        foreach ($v['opcoes'] as $i => $j) {
                            if ($j['tipo']==1) {
                                if (array_key_exists($j['id_opcao'], $respostas)) {
                                    echo  $vg . $j['n_opcao'];
                                    $sp = '';
                                    $vg = ', ';
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="row"  style="padding-left: 30px; font-size: 12px;">
                    <div class="col">
                        <?php
                        foreach ($v['opcoes'] as $i => $j) {
                            if ($j['tipo']==0) {
                                $n_resposta = '';
                                if (!empty($respostas)) {
                                    if (array_key_exists($j['id_opcao'], $respostas)) {
                                        $n_resposta = $respostas[$j['id_opcao']];
                                    }
                                }

                                if (!empty($n_resposta)) {
                                   echo '<b>'.$j['n_opcao'].'</b> '.$n_resposta; 
                                } 
                            }
                        }?>
                    </div>
                </div>
                <?php
            }
            if(!empty($v['peguntas'])){?>
                <div class="row"  style="padding-left: 30px;">
                    <?php self::getViewPDF($v['peguntas'],$id_pessoa,$id_form); ?>
                </div>
                
                <?php
            }
        }
        return $dadosForm;
    }
    public static function getRespostas($id_form,$id_pessoa,$id_pl) {
        if (!empty($id_pessoa) && !empty($id_form)){
            $sql = "SELECT "
                . " fk_id_opcao, n_resposta"
                . " FROM form_resposta "
                . " WHERE fk_id_form = $id_form"
                . " AND fk_id_pessoa = $id_pessoa"
                . " AND fk_id_pl = $id_pl";
            $query = pdoSis::getInstance()->query($sql);
            $P1 = $query->fetchAll(PDO::FETCH_ASSOC);


            foreach ($P1 as $k => $v) {
                $resp[$v["fk_id_opcao"]] = $v["n_resposta"];
            }

            if (!empty($resp)) {
               return $resp; 
            }
             
        } else {
            $lista_alunos = toolErp::divAlert('warning', 'Escolha um aluno e um questionário');
            die();
        }
    }

    public static function salvarForm($respostas,$id_form,$id_pessoa,$id_pl) {
        $sql = "DELETE FROM form_resposta WHERE  fk_id_form =  $id_form AND fk_id_pessoa = $id_pessoa AND fk_id_pl = $id_pl";
        $query = pdoSis::getInstance()->query($sql);
        $db = new DB();
        if (!empty(toolErp::id_pessoa())) {
            $id_pessoa_responde = toolErp::id_pessoa();
        }else{
            $id_pessoa_responde = -1;//respondido pelos pais no celular
        }

        foreach ($respostas['fk_id_pergunta']['checkbox'] as $k => $v) { //checkbox
            foreach ($v as $i => $j) { 
                
                if (!empty($j)) {
                    $ins = [
                        'fk_id_form'=> $id_form,
                        'fk_id_pessoa'=> $id_pessoa,
                        'fk_id_opcao'=> $j,
                        'fk_id_pl' => $id_pl,
                        'fk_id_pessoa_responde' => $id_pessoa_responde
                    ];
                    $db->ireplace('form_resposta', $ins,1);
                }
            }
            
        }
        
        foreach ($respostas['fk_id_pergunta'] as $k => $v) {   
            if (!empty($respostas['fk_id_pergunta'])) {
                foreach ($respostas['fk_id_pergunta'] as $k => $v) {
                    if ($k == 'text') { //input
                        foreach ($v as $kk => $vv) {
                            if (!empty($vv)) {
                                $ins = [
                                    'fk_id_form'=> $id_form,
                                    'fk_id_pessoa'=> $id_pessoa,
                                    'fk_id_opcao'=> $kk,
                                    'n_resposta'=> $vv,
                                    'fk_id_pl' => $id_pl,
                                    'fk_id_pessoa_responde' => $id_pessoa_responde
                                ];
                                $db->ireplace('form_resposta', $ins,1); 
                                $respondido = 1;
                            }
                        }
                    } else { //radio
                        if (!empty($v)) { 
                             $ins = [
                                'fk_id_form'=> $id_form,
                                'fk_id_pessoa'=> $id_pessoa,
                                'fk_id_opcao'=> $v,
                                'fk_id_pl' => $id_pl,
                                'n_resposta'=> null,
                                'fk_id_pessoa_responde' => $id_pessoa_responde
                            ];
                            $db->ireplace('form_resposta', $ins,1); 
                            $respondido = 1;
                        }
                    }      
                }
            }
        }
        if (!empty($respondido)) {
            $ins = [
                'fk_id_form'=> $id_form,
                'fk_id_pessoa'=> $id_pessoa,
                'fk_id_pessoa_responde'=> $id_pessoa_responde,
                'fk_id_pl' => $id_pl,
                'respondido'=> 1
            ];   
            $db->ireplace('form_pessoa', $ins);  
        }
        
    }

    public static function text($id,$value=null,$width=null){
        if (empty($width)) {
           $width =  '400px';
        }
        ?>
        <input style="width: <?= $width ?>;" type="text" name="1[fk_id_pergunta][text][<?= $id ?>]" id="<?= $id ?>" value="<?= $value ?>">
        <?php
    }

    public static function checkbox($id_pergunta,$id_opcao,$texto,$value,$acao=null){
        echo formErp::checkbox('1[fk_id_pergunta][checkbox]['.$id_pergunta.'][]',$value,$texto,$id_opcao,'" id='.$id_opcao.' '.$acao);
    }

    public static function getAssinatura($id_form,$id_pessoa,$id_pl) {
        if (!empty($id_pessoa) && !empty($id_form)){
            $sql = "SELECT "
                . " ass.assinatura, ass.IP, ass.dt_update"
                . " FROM form_pessoa fp"
                . " JOIN asd_assinatura ass ON ass.id_assinatura = fp.fk_id_assinatura "
                . " WHERE fp.fk_id_form = $id_form"
                . " AND fp.fk_id_pessoa = $id_pessoa"
                . " AND fp.fk_id_pl = $id_pl";
            $query = pdoSis::getInstance()->query($sql);
            $P1 = $query->fetch(PDO::FETCH_ASSOC);
            return $P1;   
        }
    }

}