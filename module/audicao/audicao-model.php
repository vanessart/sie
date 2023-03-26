<?php

class audicaoModel extends MainModel {

    public $db;
    private static $ciclosFormSet;
    private static $ciclosAvisosSet;
    private static $id_campanhaSet;
    
/**
  if($this->db->tokenCheck('table')){

  }
     * Construtor para essa classe
     *
     * Configura o DB, o controlador, os parâmetros e dados do usuário.
     *
     * @since 0.1
     * @access public
     * @param object $db Objeto da nossa conexão PDO
     * @param object $controller Objeto do controlador
     */
    public function __construct($db = false, $controller = null) {
        // Configura o DB (PDO)
        $this->db = new crud();
        // Configura o controlador
        $this->controller = $controller;

        // Configura os parâmetros
        $this->parametros = $this->controller->parametros->variavel;

        // Configura os dados do usuário
        $this->userdata = $this->controller->userdata;
        
        //seta o select dinamico
        if ($opt = formErp::jqOption()) {
            $metodo = $opt[0];
            if (in_array($metodo, get_class_methods($this))) {
                $this->$metodo($opt[1]);
            }
        }
        
        $id_campanhaSet = $this->campanha('id_campanha');
        self::$id_campanhaSet = $id_campanhaSet;

        $ciclosForm = $this->getCiclosModel(self::$id_campanhaSet,1);
        self::$ciclosFormSet = $ciclosForm;
        
        $ciclosAvisos = $this->getCiclosModel($id_campanhaSet,2);
        self::$ciclosAvisosSet = $ciclosAvisos;

        if ($this->db->tokenCheck('salvarFormPais', true)) {
            $this->salvarFormPais();
        }elseif ($this->db->tokenCheck('salvarFormProf', true)) {
            $this->salvarFormProf();
        }elseif ($this->db->tokenCheck('ativar_campanha', true)) {
            $this->ativar_campanha();
        }elseif ($this->db->tokenCheck('edita_campanha', true)) {
            $this->edita_campanha();
        }
    }
    
    public function getCiclosModel($id_campanha,$tipo) {
        $sql = "SELECT fk_id_ciclo"
                . " FROM audicao_campanha_ciclo acc "
                . " WHERE acc.tipo = $tipo AND fk_id_campanha = $id_campanha ";
        $query = pdoSis::getInstance()->query($sql);
        $ciclos = $query->fetchAll(PDO::FETCH_ASSOC);
        $ciclosForm = '';
        foreach ($ciclos as $v ) {
            $ciclosForm = empty($ciclosForm)?$v['fk_id_ciclo']:$ciclosForm.','.$v['fk_id_ciclo']; 
        }
        
        return $ciclosForm;
    }

    public function getForm($id_form, $fk_id_pai = null) {

        if (empty($_perguntas)) {
            $_perguntas = [];
        }

        $P1 = $this->getPerguntas($id_form, $fk_id_pai);

        if ( empty($P1) ){
            return null;
        }

        foreach ($P1 as $k => $v) {
            $_perguntas[$v['id_pergunta']] = $v;
            $_perguntas[$v['id_pergunta']]['peguntas'] = [];
            $_perguntas[$v['id_pergunta']]['opcoes'] = [];

            $pergs = $this->getForm($id_form, $v['id_pergunta']);
            if (!empty($pergs)) {
                $_perguntas[$v['id_pergunta']]['peguntas'] = $pergs;
            }

            $opcoes = $this->getOpcoes($v['id_pergunta']);
            if (!empty($opcoes)) {
                $_perguntas[$v['id_pergunta']]['opcoes'] = $opcoes;
            }
        }

        return $_perguntas;
    }

    public function getPerguntas($id_form, $fk_id_pai = null) {
        $sqlPai = '';
        if (empty($fk_id_pai)){
            $sqlPai .= " AND p1.fk_id_pai IS NULL ";
        } else {
            $sqlPai .= " AND p1.fk_id_pai = $fk_id_pai ";
        }

        $sql = "SELECT "
            . " id_pergunta, tem_resposta, n_pergunta, ordem, fk_id_pai "
            . " FROM audicao_form_perguntas AS p1 "
            . " WHERE fk_id_form = $id_form"
            . $sqlPai
            . " ORDER BY p1.ordem ";

        $query = pdoSis::getInstance()->query($sql);
        $P1 = $query->fetchAll(PDO::FETCH_ASSOC);
        return $P1;
    }

    public function getOpcoes($fk_id_pergunta, $fk_id_pai = null) {
        /*$sqlPai = '';
        if (empty($fk_id_pai)){
            $sqlPai .= " AND p1.fk_id_pai IS NULL ";
        } else {
            $sqlPai .= " AND p1.fk_id_pai = $fk_id_pai ";
        }*/

        $sql = "SELECT "
                . " fk_id_pergunta, id_opcao, n_opcao, tipo, acao "
                . " FROM audicao_form_opcoes"
                . " WHERE fk_id_pergunta = $fk_id_pergunta"
                . " ORDER BY ordem ";
        $query = pdoSis::getInstance()->query($sql);
        $P1 = $query->fetchAll(PDO::FETCH_ASSOC);
        return $P1;
    }

    public function getView($form,$id_pessoa,$id_form){  
        $respostas = $this->getRespostas($id_form,$id_pessoa);
        if (empty($form)) return '';

        foreach ($form as $k => $v) { 

            if (!empty($v)) {?>

               <?php 
            }
            
            ?>
            <div class="row pergunta_<?= $v['id_pergunta'] ?>">
                <div class="col col-sm" style="<?= !empty($v['fk_id_pai']) ? 'padding-left: 30px' : 'font-weight:bold' ?>">
                    <?php
                    echo $v['n_pergunta'].'<br>';?>
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
                                echo formErp::input('1[fk_id_pergunta]['.$j['id_opcao'].']',$j['n_opcao'],$n_resposta,$classe.'  data-id-pergunta="'.$v['id_pergunta'].'" id='.$j['id_opcao'].' '.$j['acao']);?>
                            </div>
                            <?php
                        }
                    }?>
                </div>
                <?php
            }
            if(!empty($v['peguntas'])){
                $this->getView($v['peguntas'],$id_pessoa,$id_form);
            }
            ?>
            <br>
            <div style="border-bottom: 3px solid #e7e1e1"></div>
            <br>
            <?php
        }
    }

    public function getTurmas($id_pessoa, $id_inst = null, $tipo = null) {
        $ciclos = '';
        $primeiro_acesso = 0;
        if ($tipo == 1) {
            $ciclos = self::$ciclosFormSet;
        }elseif ($tipo == 2) {
            $ciclos = self::$ciclosAvisosSet;
        }
        if (toolErp::id_nilvel() == 24) {
            $sql = "SELECT DISTINCT t.id_turma, t.n_turma "
                . " FROM ge_aloca_prof prof "
                . " JOIN ge_turmas t on t.id_turma = prof.fk_id_turma "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN ge_funcionario f on f.rm = prof.rm "
                . " WHERE f.fk_id_pessoa = $id_pessoa"
                . " AND t.fk_id_ciclo IN ($ciclos)"
                . " ORDER BY n_turma";
        }else{

            if (empty($id_inst)) {
                $id_inst = toolErp::id_inst();
                $primeiro_acesso = 1;
            }
            $sql = "SELECT DISTINCT t.id_turma, t.n_turma "
                . " FROM ge_turmas t "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " WHERE t.fk_id_inst = ".$id_inst
                . " AND t.fk_id_ciclo IN ($ciclos)"
                . " ORDER BY n_turma";
        }
        
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (empty($array) && $primeiro_acesso == 0) {
            toolErp::divAlert('warning', 'Não há turmas disponíveis');
            die();
        }else{

            if (!empty($array)) {
            
                foreach ($array as $k => $v) {
                    $turmas[$v['id_turma']] = $v['n_turma'];
                }
            }else{
                $turmas = [];
            }
            return $turmas;
        } 
    }

    public function formPais($id_turma){
        $professor = $this->professor();
        if ($professor <> 1) {
           $textProf = ' Professor';
           $textAoProf = ' ao Professor';
        }
        if (!empty($id_turma)) {
            $alunos = $this->getAlunos($id_turma);
            foreach ($alunos as $k => $v) {
                    if ($v['Profresp']==1) {
                        $btnProf ='success';
                    }else{
                        $btnProf ='outline-info';
                    }
                    if ($v['Paisresp']==1) {
                        $btnPais ='success';
                    }else{
                        $btnPais ='outline-info';
                    }
                    $alunos[$k]['pais'] = '<button class="btn btn-' . $btnPais . '" onclick="acesso(\'formPais\',\' - Questionário aos Pais\',' . $v['id_pessoa'] . ',\'' . $v['n_pessoa'] . '\' )">Questionário aos Pais</button>';
                    $alunos[$k]['prof'] = '<button class="btn btn-' . $btnProf . '" onclick="acesso(\'formProf\',\' - Questionário ao Professor\',' . $v['id_pessoa'] . ',\'' . $v['n_pessoa'] . '\' )">Questionário' . @$textAoProf . '</button>';
                    $alunos[$k]['QRpais'] = '<button class="btn btn-info" style="color:white;" onclick="pdf(\'formPaisPDF\',' . $v['id_pessoa'] . ',null,1 )">Imprimir QrCode</button>';
                    $alunos[$k]['pdf'] = '<button class="btn btn-info" style="color:white;" onclick="pdf(\'formPaisPDF\',' . $v['id_pessoa'] . ' )">Imprimir Pais</button>';
                    $alunos[$k]['pdf2'] = '<button class="btn btn-info" style="color:white;"  onclick="pdf(\'formProfPDF\',' . $v['id_pessoa'] . ' )">Imprimir' . @$textProf . '</button>';
            }
            $form['array'] = $alunos;
            if ($professor<> 1) {
                $form['fields'] = [
                    'RSE' => 'id_pessoa',
                    'Aluno' => 'n_pessoa',
                    '||5' => 'QRpais',
                    '||3' => 'pdf',
                    '||4' => 'pdf2',
                    '||2' => 'pais',
                    '||1' => 'prof',
                ];
            }else{
                $form['fields'] = [
                    'RSE' => 'id_pessoa',
                    'Aluno' => 'n_pessoa',
                    '||4' => 'pdf2',
                    '||1' => 'prof',
                ];
            }
            
        }
        if (!empty($alunos)) {
            $lista_alunos = report::simple($form);
        }else{
            $lista_alunos = toolErp::divAlert('warning', 'Verifique se há alunos alocados nesta turma');
        }

        return $lista_alunos;
    }

    public function getAlunos($id_turma, $resp = null, $id_form = null){
        $sqlAux = '';
        $id_campanha = self::$id_campanhaSet;
        if (!empty($resp) && !empty($id_form)){
            if ($resp == 2) {
                $sIn = ' NOT ';
            } else {
                $sIn = '';
            }
            $sqlAux .= " AND p.id_pessoa $sIn IN("
                . "SELECT fk_id_pessoa "
                . " FROM audicao_form_resposta "
                . " WHERE fk_id_form = $id_form "
                . " AND fk_id_pessoa = p.id_pessoa "
                . " AND fk_id_campanha = $id_campanha "
                . " )";
        }

        $sql = "SELECT DISTINCT p.id_pessoa, p.n_pessoa, fp.respondido AS Paisresp, fprof.respondido AS Profresp "
                    . " FROM ge_turma_aluno ta "
                    . " JOIN pessoa p on p.id_pessoa = ta.fk_id_pessoa "
                    . " LEFT JOIN audicao_form_pessoa fp on fp.fk_id_pessoa = ta.fk_id_pessoa AND fp.fk_id_form = 1 AND fp.fk_id_campanha = $id_campanha"
                    . " LEFT JOIN audicao_form_pessoa fprof on fprof.fk_id_pessoa = ta.fk_id_pessoa AND fprof.fk_id_form = 2 AND fprof.fk_id_campanha = $id_campanha"
                    . " WHERE ta.fk_id_turma = $id_turma"
                    . $sqlAux
                    . " AND (ta.fk_id_tas = 0 or ta.fk_id_tas is null)"
                    . " ORDER BY p.n_pessoa";
            $query = pdoSis::getInstance()->query($sql);
            $alunos = $query->fetchAll(PDO::FETCH_ASSOC);
            return $alunos;
    }

    public function getId_turma($turmas,$id_turma){
        if (!empty($id_turma)) {
           return $id_turma; 
        }else{
           return array_key_first($turmas); 
        }
    }

    public function getRespostas($id_form,$id_pessoa) {
        $id_campanha = self::$id_campanhaSet;
        if (!empty($id_pessoa) && !empty($id_form)){
            $sql = "SELECT "
                . " fk_id_opcao, n_resposta"
                . " FROM audicao_form_resposta "
                . " WHERE fk_id_form = $id_form"
                . " AND fk_id_pessoa = $id_pessoa"
                . " AND fk_id_campanha = $id_campanha";
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

    public function salvarFormPais() {
        $respostas = @$_POST[1];
        
        $id_form = filter_input(INPUT_POST, 'id_form', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $id_campanha = filter_input(INPUT_POST, 'id_campanha', FILTER_SANITIZE_NUMBER_INT);

        $sql = "DELETE FROM audicao_form_resposta WHERE  fk_id_form =  $id_form AND fk_id_pessoa = $id_pessoa AND fk_id_campanha = $id_campanha";
        $query = $this->db->query($sql);

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
                        'fk_id_campanha' => $id_campanha
                    ];
                    $this->db->ireplace('audicao_form_resposta', $ins,1);
                }
            }
            
        }
        
        foreach ($respostas['fk_id_pergunta'] as $k => $v) {   
            
            if (!empty($v)) {
                if ($k == 52 || $k == 17) {//input
                    $ins = [
                        'fk_id_form'=> $id_form,
                        'fk_id_pessoa'=> $id_pessoa,
                        'fk_id_opcao'=> $k,
                        'n_resposta'=> $v,
                        'fk_id_campanha' => $id_campanha
                    ];
                }else{//radio
                     $ins = [
                        'fk_id_form'=> $id_form,
                        'fk_id_pessoa'=> $id_pessoa,
                        'fk_id_opcao'=> $v,
                        'fk_id_campanha' => $id_campanha
                    ];   
                }
               
                $this->db->ireplace('audicao_form_resposta', $ins,1); 
                $respondido = 1;
            }      
        }

        if (!empty($respondido)) {
            $ins = [
                'fk_id_form'=> $id_form,
                'fk_id_pessoa'=> $id_pessoa,
                'fk_id_pessoa_responde'=> $id_pessoa_responde,
                'fk_id_campanha' => $id_campanha,
                'respondido'=> 1
            ];   
            $this->db->ireplace('audicao_form_pessoa', $ins);  
        }
        
    }

    public function getPessoa($id_pessoa){
        $sql = "SELECT "
                . " rg, n_pessoa, dt_nasc, mae, logradouro, num, complemento, bairro, tel1,tel2,tel3,ddd1,ddd2,ddd3"
                . " FROM pessoa p "
                . " LEFT JOIN endereco e ON e.fk_id_pessoa = p.id_pessoa"
                . " WHERE p.id_pessoa = $id_pessoa";

        $query = pdoSis::getInstance()->query($sql);
        $dados = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($dados)) {
           $tel1 = (!empty($dados['ddd1'])&&!empty($dados['tel1']) ? $dados['ddd1'].'-'.$dados['tel1'] : $dados['tel1']);
           $tel2 = (!empty($dados['ddd2'])&&!empty($dados['tel2']) ? $dados['ddd2'].'-'.$dados['tel2'] : $dados['tel2']);
           $tel3 = (!empty($dados['ddd3'])&&!empty($dados['tel3']) ? $dados['ddd3'].'-'.$dados['tel3'] : $dados['tel3']);
           $tels = [$tel1,$tel2,$tel3];
           foreach ($tels as $v) {
                if (!empty($v)) {
                   $tel = (!empty($tel) ? $tel.", ".$v : $v); 
                }
           }

           if (!empty($tel)) {
                $dados['tel'] = $tel; 
           }else{
                $dados['tel'] = "Sem telefone cadastrado, atualize os dados na Secretaria da Escola.";
           }

           if (!empty($dados['logradouro']) && !empty($dados['num'])) {
                $dados['endereco'] = $dados['logradouro'].', '.$dados['num'].' '.$dados['complemento'].' - '.$dados['bairro']; 
           }else{
                $dados['endereco'] = "Sem endereço cadastrado, atualize os dados na Secretaria da Escola.";
           }
        }
        return $dados;
    }

    public function salvarFormProf() {
        $respostas = @$_POST[1];
        
        $id_form = filter_input(INPUT_POST, 'id_form', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $id_campanha = filter_input(INPUT_POST, 'id_campanha', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa_responde = filter_input(INPUT_POST, 'id_pessoa_responde', FILTER_SANITIZE_NUMBER_INT);

        $sql = "DELETE FROM audicao_form_resposta WHERE  fk_id_form =  $id_form AND fk_id_pessoa = $id_pessoa AND fk_id_campanha = $id_campanha";
        $query = $this->db->query($sql);

        foreach ($respostas['fk_id_pergunta']['checkbox'] as $k => $v) { //checkbox
            foreach ($v as $i => $j) { 
                
                if (!empty($j)) {
                    $ins = [
                        'fk_id_form'=> $id_form,
                        'fk_id_pessoa'=> $id_pessoa,
                        'fk_id_pessoa_responde'=> $id_pessoa_responde,
                        'fk_id_campanha' => $id_campanha,
                        'fk_id_opcao'=> $j,
                    ];
                    $this->db->ireplace('audicao_form_resposta', $ins,1);
                }
            }
            
        }
        
        foreach ($respostas['fk_id_pergunta'] as $k => $v) {   
            
            if (!empty($v)) {
                if ($k == 55) {//input
                    $ins = [
                        'fk_id_form'=> $id_form,
                        'fk_id_pessoa'=> $id_pessoa,
                        'fk_id_pessoa_responde'=> $id_pessoa_responde,
                        'fk_id_opcao'=> $k,
                         'fk_id_campanha' => $id_campanha,
                        'n_resposta'=> $v,
                    ];
                }else{//radio
                     $ins = [
                        'fk_id_form'=> $id_form,
                        'fk_id_pessoa'=> $id_pessoa,
                        'fk_id_pessoa_responde'=> $id_pessoa_responde,
                         'fk_id_campanha' => $id_campanha,
                        'fk_id_opcao'=> $v,
                    ];   
                }
               
                $this->db->ireplace('audicao_form_resposta', $ins,1); 
                $respondido = 1;
            }      
        }
        if (!empty($respondido)) {
            $ins = [
                'fk_id_form'=> $id_form,
                'fk_id_pessoa'=> $id_pessoa,
                 'fk_id_campanha' => $id_campanha,
                'respondido'=> 1
            ];   
            $this->db->ireplace('audicao_form_pessoa', $ins);  
        }
    }

     public function getViewPDF($form,$id_pessoa,$id_form,$result=false){  

        if (empty($form)) return '';

        $respostas = $this->getRespostas($id_form,$id_pessoa);
        $r = [];

        if ($result) {
            $r = $this->getRespostaByQuestoes($id_pessoa, null, null, null, $id_form, null);
        }

        foreach ($form as $k => $v) {
            $found = toolErp::searchForId($v['id_pergunta'], $r, 'id_pergunta');
        ?>
             <table style="font-size: 12px; border: 0px;padding:0; margin-top:<?= empty($v['fk_id_pai']) ? '20px' : "0" ?>; border-collapse: collapse;">
                <tr>
                    <td width="58%" <?= empty($v['fk_id_pai']) ? 'style="font-weight:bold"' : "" ?> <?= (!is_null($found)) ? 'class="resultDest"' : '' ?> >
                        <?php echo $v['n_pergunta'];?>
                    </td>
                    <td>
                        <?php
                        if(!empty($v['opcoes'])){
                            foreach ($v['opcoes'] as $i => $j) {
                                $respX = ' ';
                                if ($j['tipo']==2) {
                                    $fnd = null;
                                    if (!empty($respostas)) {
                                       if (array_key_exists($j['id_opcao'], $respostas)) {
                                            $respX = '<b>X</b>';
                                            if (!is_null($found)) {
                                                $fnd = toolErp::searchForId($j['id_opcao'], $r, 'fk_id_opcao');
                                            }
                                        }else{
                                            $respX = ' ';
                                        } 
                                    }
                                    echo '<span '. (!is_null($fnd) ? 'class="resultDest"' : '') .'>&nbsp;&nbsp;&nbsp;&nbsp;'.$j['n_opcao'].' ('.$respX.')</span>';
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
                    <?php $this->getViewPDF($v['peguntas'],$id_pessoa,$id_form,$result); ?>
                </div>
                
                <?php
            }
            ?>
            
            <?php
        }
    }

     public function alunoSala($id_pessoa) {
        $sql = "SELECT DISTINCT prof.rm, p.n_pessoa, t.n_turma, t.codigo, t.periodo "
        . " FROM ge_turma_aluno ta "
        . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
        . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
        . " JOIN ge_aloca_prof prof on prof.fk_id_turma = t.id_turma "
        . " JOIN ge_funcionario f on f.rm = prof.rm "
        . " JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa "
        . " WHERE ta.fk_id_pessoa = $id_pessoa AND (ta.fk_id_tas = 0 or ta.fk_id_tas = null)";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($array)) {
            $vir = "";
            $prof = "";
            foreach ($array as $k => $v) {
                $prof = $prof." ".$vir.$v['n_pessoa'] . ' (' . $v['rm'] . ')';
                $vir = ",";
            }
            foreach ($array as $k => $v) {
                if ($v['periodo'] == 'M') {
                    $alunoSala['periodo'] = 'Manhã';
                }elseif ($v['periodo'] == 'T') {
                    $alunoSala['periodo'] = 'Tarde';
                }else{
                    $alunoSala['periodo'] = 'Integral';
                }
                 $alunoSala['rm'] = $v['rm'];
                 $alunoSala['n_pessoa'] = $v['n_pessoa'];
                 $alunoSala['n_turma'] = $v['n_turma'];
                 $alunoSala['codigo'] = $v['codigo'];
            } 
        }
        if (!empty($prof)) {
           $alunoSala['prof'] = $prof; 
        }else{
            $alunoSala['prof'] = '';
        }
        return $alunoSala;
    }

    public function getProf($id_pessoa) {
        $sql = " SELECT f.rm, p.n_pessoa "
            ." FROM pessoa p "
            ." JOIN ge_funcionario f on f.fk_id_pessoa = p.id_pessoa "
            ." WHERE p.id_pessoa = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $arrProf = $query->fetch(PDO::FETCH_ASSOC);

        return $arrProf;
    }

    public function getFormRespondido($id_pessoa,$id_form,$id_campanha) {
        $form = sql::get('audicao_form_pessoa', 'respondido', ['fk_id_pessoa' => $id_pessoa,'fk_id_form' => $id_form,'fk_id_campanha' => $id_campanha], 'fetch');
        if (!empty($form)) {
            if ($form['respondido']==1) {
               $respondido = 1;
            }else{
                $respondido = 0;
            }
        }else{
            $respondido = 0;
        }
        return $respondido; 
    }

    public function getEscolaAluno($id_pessoa) {
        $sql = "SELECT DISTINCT i.n_inst "
        . " FROM ge_turma_aluno ta "
        . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
        . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
        . " JOIN instancia i on ta.fk_id_inst = i.id_inst "
        . " WHERE ta.fk_id_pessoa = $id_pessoa AND ta.fk_id_tas = 0";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        $escola = '';
        if (!empty($array)) {   
           $escola = $array['n_inst'];
        }
        return $escola;
    }

    public function dashGet($id_inst=null,$id_campanha) {

        $totais['datas'] = '';
        $totais['qtdResp'] = '';
        $totais['formPais'] = $this->respTotalGet(1,null,$id_campanha);
        $totais['formPaisQr'] = $this->respTotalGet(1,-1,$id_campanha);
        $totais['formProf'] = $this->respTotalGet(2,null,$id_campanha);
        $totais['porDiaPais'] = $this->respDiaGet(1,null,$id_campanha);
        $totais['porDiaProf'] = $this->respDiaGet(2,null,$id_campanha);
        $totais['porDiaTotal'] = $totais['porDiaPais']+$totais['porDiaProf'];
        $totais['totalALunos'] = $this->totalAlunosGet();
        $totais['totalRespPais'] = $totais['formPais'];
        $totais['respPais100'] = intval(($totais['totalRespPais']/ $totais['totalALunos']) * 100);
        $totais['respProf100'] = intval(($totais['formProf']/ $totais['totalALunos']) * 100);
        $totais['totalResp'] = $totais['formPais']+$totais['formProf'];
        $totais['totalRespf100'] = number_format(( $totais['totalResp'] / ($totais['totalALunos']*2)) * 100, 2);//2 formularios por aluno
        if ($totais['totalRespf100']>100) {
            $totais['totalRespf100'] = 100;
        }
        $totais['totSemana'] = $this->respGetSemana('1,2',null, $id_campanha);
        $totais['porInst'] = $this->respInstGet(null,null, $id_campanha);
        $totais['porInst100'] = $this->respInstGet(null,null, $id_campanha,1);
        $totais['totalRespPaisSemqr'] = $totais['formPais'] - $totais['formPaisQr'];

        // if (toolErp::id_pessoa() == 289494) {
           
        //    echo "<pre>";
           
        //    print_r($totais['totalALunos']);
           
        //    echo "</pre>";
           
        // }

        foreach ($totais['porDiaPais'] as $k => $v) {
            $datas = (!empty($datas) ? $datas.",".$v : $v);
            $qtdResp = (!empty($qtdResp) ? $qtdResp.",".$k : $k);
        }
        foreach ($totais['porDiaProf'] as $k => $v) {
            $datas = (!empty($datas) ? $datas.",".$v : $v);
            $qtdResp = (!empty($qtdResp) ? $qtdResp.",".$k : $k);
        }
        foreach ($totais['totSemana'] as $k => $v) {
            $dataSemana = (!empty($dataSemana) ? $dataSemana.",".$k : $k);
            $qtdRespSemana = (isset($qtdRespSemana) ? $qtdRespSemana.",".$v : $v);
        }
        $escola = '';
        foreach ($totais['porInst'] as $k => $v) {
            $escola = (!empty($escola) ? $escola.",".$k : $k);
            $qtdEsc = (isset($qtdEsc) ? $qtdEsc.",".$v : $v);
        }

        if (!empty($datas)) {
           $dataArray = explode(',', $datas);
            $datasStr = '"'.implode('","', $dataArray).'"'; 
        }
        
        if (!empty($datas)) {
            $totais['datas'] = $datasStr;
            $totais['qtdResp'] = $qtdResp;
        }

        $dataSemanaArray = explode(',', $dataSemana);
        $dataSemanaStr = '"'.implode('","', $dataSemanaArray).'"';
        $totais['dataSemana'] = $dataSemanaStr;
        $totais['qtdRespSemana'] = $qtdRespSemana;
    
        $escolaArray = explode(',', $escola);
        $escolaStr = '"'.implode('","', $escolaArray).'"';

        $totais['escola'] = $escolaStr;

        if (!empty($qtdEsc)) {
           $totais['qtdEsc'] = $qtdEsc; 
        }else{
           $totais['qtdEsc'] = 0; 
        }
        
        return $totais;

    }

    public function respGetSemana($id_form=null,$id_pessoa_responde=null, $id_campanha) {
        $gerente = $this->gerente();
        if ($gerente <> 1) {
           $id_inst =  " AND t.fk_id_inst = ".toolErp::id_inst();
        }
        
        if ($id_pessoa_responde == -1) {
            $sql = " SELECT COUNT(id_form_pessoa) AS respQrCode, date_format(dt_update,'%w') as dia_semana     "
                . " FROM audicao_form_pessoa afp "
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = afp.fk_id_pessoa AND (ta.fk_id_tas = 0 or ta.fk_id_tas = null) "
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND fk_id_ciclo IN (". self::$ciclosFormSet .")"
                . $id_inst
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " WHERE fk_id_pessoa_responde = -1 AND fk_id_form IN ($id_form) AND fk_id_campanha = $id_campanha"
                . " GROUP BY date_format(dt_update,'%w') ";
        }else{
            $sql = " SELECT COUNT(id_form_pessoa) AS respQrCode, date_format(dt_update,'%w') as dia_semana     "
                . " FROM audicao_form_pessoa afp "
                 . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = afp.fk_id_pessoa AND (ta.fk_id_tas = 0 or ta.fk_id_tas = null) "
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND fk_id_ciclo IN (". self::$ciclosFormSet .") "
                . @$id_inst
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " WHERE fk_id_form IN ($id_form) AND fk_id_campanha = $id_campanha"
                . " GROUP BY date_format(dt_update,'%w') "
                . " ORDER BY date_format(dt_update,'%w') ";
        }
        
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $resp = [];
        $resp2 = [];
        $dia_semana = $this->diaSemanaGet();
        
        foreach ($array as $v) {
            $resp[$dia_semana[$v['dia_semana']]] = $v['respQrCode'];
        }
        for ($i=0; $i < 7 ; $i++) { 
            if (!isset($resp[$dia_semana[$i]])) {
               $resp2[$dia_semana[$i]]=0;
            }else{
               $resp2[$dia_semana[$i]]=$resp[$dia_semana[$i]];

            }
        }
        
        return $resp2;
    }

    public function respTotalGet($id_form,$id_pessoa_responde=null,$id_campanha) {
        $gerente = $this->gerente();

        if (!empty($id_pessoa_responde)) {
           $id_pessoa_responde = ' AND fk_id_pessoa_responde = -1 ';
        }
        if ($gerente <> 1) {
           $id_inst =  "  AND t.fk_id_inst = ".toolErp::id_inst();
        }
        
        $sql = " SELECT COUNT(id_form_pessoa) AS respQrCode"
            . " FROM audicao_form_pessoa afp "
            . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = afp.fk_id_pessoa AND (ta.fk_id_tas = 0 or ta.fk_id_tas = null) "
            . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND fk_id_ciclo IN (". self::$ciclosFormSet .")"
            . @$id_inst
            . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1"
            . " WHERE fk_id_form IN ($id_form) AND fk_id_campanha = $id_campanha"
            . $id_pessoa_responde;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($array)) {
           $resp = $array['respQrCode'];
        }else{
            $resp = 0;
        }
        return $resp;
    }

    public function respDiaGet($id_form,$id_pessoa_responde=null,$id_campanha) {
        $gerente = $this->gerente();

        if (!empty($id_pessoa_responde)) {//qr_code
           $id_pessoa_responde = ' AND fk_id_pessoa_responde = -1 ';
        }
        if ($gerente <> 1) {
           $id_inst =  "  AND t.fk_id_inst = ".toolErp::id_inst();
        }
        
        $sql = " SELECT COUNT(id_form_pessoa) AS respQrCode, date_format(dt_update,'%d/%m/%Y') as dia"
            . " FROM audicao_form_pessoa afp "
            . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = afp.fk_id_pessoa AND (ta.fk_id_tas = 0 or ta.fk_id_tas = null) "
            . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND fk_id_ciclo IN (". self::$ciclosFormSet .")"
            . @$id_inst
            . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1"
            . " WHERE fk_id_form = $id_form AND fk_id_campanha = $id_campanha "
            . $id_pessoa_responde
            . " GROUP BY  date_format(dt_update,'%d/%m/%Y')";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $resp = [];
        foreach ($array as $k => $v) {
            $resp[$v['respQrCode']] = $v['dia'];
        }
        return $resp;
    }

    public static function diaSemanaGet(){
       $dias =  [
            '0' => 'Domingo',
            '1' => 'Segunda-Feira',
            '2' => 'Terça-Feira',
            '3' => 'Quarta-Feira',
            '4' => 'Quinta-Feira',
            '5' => 'Sexta-Feira',
            '6' => 'Sábado'
        ];

        return $dias;
    }

    public function divDash_totais($texto,$numero,$class) {?>
            
        <div class="card border-left-<?= $class ?> shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-<?= $class ?> text-uppercase mb-1">
                            <?= $texto ?>
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $numero  ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
           
        <?php
    }

    public function totalAlunosGet($id_inst=null,$ids=null){
        
        $gerente = $this->gerente();
        if ($gerente <> 1) {
           $id_inst =  " AND ta.fk_id_inst = ".toolErp::id_inst();
        }elseif ($gerente == 1 && !empty($id_inst)) {
           $id_inst =  " AND ta.fk_id_inst = ".$id_inst;
        }else{
            $id_inst = '';
        }
        if (!empty($ids)) {
           $id_pessoa = 'DISTINCT p.id_pessoa';
        }else{
           $id_pessoa = 'COUNT(DISTINCT fk_id_pessoa) AS total'; 
        }
        
        $sql = "SELECT $id_pessoa"
            . " FROM ge_turma_aluno ta  "
            . " WHERE ta.fk_id_turma IN  "
                . " (SELECT DISTINCT t.id_turma "
                . " FROM ge_turmas t "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " WHERE t.fk_id_ciclo IN (". self::$ciclosFormSet .")) "
            . $id_inst
            . " AND (ta.fk_id_tas = 0 or ta.fk_id_tas is null)";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($array)) {
           $resp = $array['total'];
        }else{
            $resp = 0;
        }
        return $resp;

    }

    public function divDash_percent($texto,$percent,$class) {?> 
        <div class="card border-left-<?= $class ?> shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-<?= $class ?> text-uppercase mb-1">
                            <?= $texto ?>
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr font-weight-bold text-gray-800">
                                   <?= $percent ?>%
                                </div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-<?= $class ?>" role="progressbar"
                                         style="width: <?= $percent ?>%" aria-valuenow="50" aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function divDash_chart($texto,$id) {?>
        <div class="card shadow mb-4">
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <?= $texto ?>
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="<?= $id ?>"></canvas>
                </div>
            </div>
        </div>   
        <?php
    }

    public static function professor() {
        if (in_array(user::session('id_nivel'), [24])) {
            $professor = 1;
        } else {
            $professor = 0;
        }

        return $professor;
    }

    public function divDash_pie($texto,$id) {?>
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                  <?=  $texto ?> 
                </h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="<?= $id ?>"></canvas>
                </div>
                <div class="mt-4 text-center " style="font-weight: bold">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary">Sem QrCOde</i> 
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success">Com QrCOde</i> 
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-danger">Professores</i> 
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function script_chart($titulo, $dados, $id) {?>
        var ctx = document.getElementById("<?= $id ?>");
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [<?= $titulo ?>],
                datasets: [{
                        label: "Respondido",
                        lineTension: 0.3,
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        borderColor: "rgba(78, 115, 223, 1)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointBorderColor: "rgba(78, 115, 223, 1)",
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: [<?= $dados ?>],
                    }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                            time: {
                                unit: 'date'
                            },
                            gridLines: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                maxTicksLimit: 100
                            }
                        }],
                    yAxes: [{
                            ticks: {
                                maxTicksLimit: 5,
                                padding: 10,
                                // Include a dollar sign in the ticks
                                callback: function (value, index, values) {
                                    return value;
                                }
                            },
                            gridLines: {
                                color: "rgb(234, 236, 244)",
                                zeroLineColor: "rgb(234, 236, 244)",
                                drawBorder: false,
                                borderDash: [2],
                                zeroLineBorderDash: [2]
                            }
                        }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function (tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + tooltipItem.yLabel;
                        }
                    }
                }
            }
        });
        <?php
    }

    public static function gerente() {
        if (in_array(user::session('id_nivel'), [10])) {
            $gerente = 1;
        } else {
            $gerente = 0;
        }

        return $gerente;
    }

    public function ativar_campanha() {
        $id_campanha = filter_input(INPUT_POST, 'id_campanha', FILTER_SANITIZE_NUMBER_INT);
        $id_pessoa = toolErp::id_pessoa();
        $n_pessoa = toolErp::n_pessoa();
        if (empty($id_campanha)) {
           toolErp::alert("Escolha uma Campanha"); 
           return;
        }
        $sql = "UPDATE audicao_campanha SET at_campanha = 0";
        $query = $this->db->query($sql);
        $ins = [
            'at_campanha'=> 1,
            'id_campanha'=> $id_campanha,
            'fk_id_pessoa'=> $id_pessoa,
        ];
        $this->db->ireplace('audicao_campanha',$ins);
        log::logSet("Ativou a Campanha " . $id_campanha); 
    }

    public static function campanha($id_campanha = null) {
        $campanha = sql::get('audicao_campanha','id_campanha,n_campanha',['at_campanha'=>1],'fetch');
        if (!empty($campanha)) {
           $n_campanha = $campanha['n_campanha'];
           $_SESSION['userdata']['n_campanha'] = $n_campanha; 
        }

        if (empty($id_campanha)) {
           return $n_campanha; 
        }else{
            return $campanha['id_campanha'];
        }  
    }

    public function respInstGet($id_form,$id_pessoa_responde=null,$id_campanha,$total100=null) {
        $gerente = $this->gerente();

        if (!empty($id_pessoa_responde)) {//qrcode
           $id_pessoa_responde = ' AND fk_id_pessoa_responde = -1 ';
        }
        if ($gerente <> 1) {
           $id_inst =  " AND t.fk_id_inst = ".toolErp::id_inst();
        }
        
        $sql = " SELECT COUNT(id_form_pessoa) as qtd, n_inst, id_inst FROM audicao_form_pessoa afp"
            . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = afp.fk_id_pessoa AND (ta.fk_id_tas = 0 or ta.fk_id_tas = null) "
            . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND fk_id_ciclo IN (". self::$ciclosFormSet .") "
            . @$id_inst
            . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
            . " JOIN instancia i ON t.fk_id_inst = i.id_inst "
            . " WHERE  fk_id_campanha = $id_campanha "
            . $id_pessoa_responde
            . " GROUP BY n_inst ORDER BY n_inst";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $resp = [];
        if (!empty($total100)) {
            $id_inst_S = '';
            foreach ($array as $k => $v) {
                if (!empty($v['n_inst'])) {
                   $totalAlunos = $this->totalAlunosGet($v['id_inst']); 
                   $id_inst_S = !empty($id_inst_S) ? $id_inst_S.','.$v['id_inst'] : $v['id_inst'];
                   $result = intval(($v['qtd']/ ($totalAlunos*2)) * 100);//dois formularios
                   if ($result > 100) {
                       $result = 100;
                   }
                    $resp[$v['n_inst']] = [
                    'total100' => $result
                    ]; 
                }
            } 
            $AND = '';
            if (!empty($id_inst_S)) {
               $AND = " AND id_inst NOT IN ($id_inst_S) "; 
            }
            $sql = "SELECT n_inst FROM instancia i "
                    . " JOIN ge_escolas ON ge_escolas.fk_id_inst = i.id_inst "
                    . " JOIN ge_turmas t ON t.fk_id_inst = i.id_inst AND fk_id_ciclo IN (". self::$ciclosFormSet .")"
                    . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                    . " WHERE i.ativo = 1".$AND
                    . " GROUP BY n_inst ORDER BY n_inst";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchALL(PDO::FETCH_ASSOC);
            foreach ($array as $k => $v) {
                $resp[$v['n_inst']] = [
                    'total100' => 0
                    ];
            }
        }else{
           foreach ($array as $k => $v) {
                $resp[$v['n_inst']] = $v['qtd'];
            } 
        }
        
        return $resp;
    }

    public static function aviso() {
        $campanha = sql::get('audicao_campanha','liberar_aviso',['at_campanha'=>1],'fetch');
        return $campanha['liberar_aviso']; 
    }

    public static function questionario() {
        $campanha = sql::get('audicao_campanha','liberar_form',['at_campanha'=>1],'fetch');
        return $campanha['liberar_form']; 
    }

    private function getSQLResposta($id_pessoa = null, $id_turma = null, $id_inst = null, $resp = null, $id_form = null, $destino = null) {
        $sqlAux = '';
        if (!empty($id_pessoa)) {
            $sqlAux .= " AND afr.fk_id_pessoa = $id_pessoa ";
        }
        if (!empty($id_turma)) {
            $sqlAux .= " AND ta.fk_id_turma = $id_turma ";
        }
        if (!empty($id_inst)) {
            $sqlAux .= " AND ta.fk_id_inst = $id_inst ";
        }
        // if (!empty($id_form)) {
        //     $sqlAux .= " AND afr.fk_id_form = $id_form ";
        // }
        if (!empty($destino)) {
            $sqlAux .= " AND afs.destino = $destino ";
        }

        /*if (!empty($resp) && !empty($id_form)){
            if ($resp == 2) {
                $sIn = ' NOT ';
            } else {
                $sIn = '';
            }
            $sqlAux .= " AND p.id_pessoa $sIn IN("
                . "SELECT fk_id_pessoa "
                . " FROM audicao_form_resposta "
                . " WHERE fk_id_form = $id_form "
                . " AND fk_id_pessoa = p.id_pessoa "
                . " AND fk_id_campanha = $id_campanha "
                . " )";
        }*/

        $id_campanha = self::$id_campanhaSet;
        $sql = "
            FROM (
                SELECT z.id_inst, z.n_inst, z.fk_id_pessoa, z.n_pessoa, z.selecao, count(z.selecao) q_ss, s.q_s 
                FROM (  
                    SELECT i.id_inst, i.n_inst, afr.fk_id_pessoa, p.n_pessoa, afs.selecao, afs.fk_id_form, count(afs.fk_id_opcao) qtde_r
                    FROM audicao_form_resposta afr 
                    JOIN pessoa p ON afr.fk_id_pessoa = p.id_pessoa 
                    JOIN ge_turma_aluno ta ON p.id_pessoa = ta.fk_id_pessoa 
                    JOIN audicao_form_selecao afs ON afs.fk_id_opcao = afr.fk_id_opcao AND afr.fk_id_form = afs.fk_id_form
                    JOIN audicao_form_pessoa afps ON afps.fk_id_pessoa = afr.fk_id_pessoa AND afps.fk_id_form = afr.fk_id_form AND afps.fk_id_campanha = $id_campanha
                    JOIN instancia i ON ta.fk_id_inst = i.id_inst
                    JOIN ge_turmas t ON ta.fk_id_turma = t.id_turma
                    JOIN ge_periodo_letivo pl ON t.fk_id_pl = pl.id_pl AND pl.at_pl = 1
                    WHERE 1 $sqlAux
                        AND t.fk_id_ciclo IN(". self::$ciclosFormSet .")
                        AND (ta.fk_id_tas = 0 or ta.fk_id_tas is null)
                    GROUP BY afr.fk_id_pessoa, p.n_pessoa, afs.selecao, afs.fk_id_form
                ) z
                LEFT JOIN (
                    SELECT sx.selecao, sx.fk_id_form, sum(sx.qtde) q_o, (SELECT count(distinct fk_id_form) FROM audicao_form_selecao WHERE selecao = sx.selecao) q_s
                    FROM audicao_form_selecao sx
                    GROUP BY sx.selecao, sx.fk_id_form
                ) s ON z.selecao = s.selecao AND z.fk_id_form = s.fk_id_form
                WHERE z.qtde_r >= s.q_o
                GROUP BY z.id_inst, z.n_inst, z.fk_id_pessoa, z.n_pessoa, z.selecao, s.q_s
                HAVING count(z.selecao) >= s.q_s
            ) y ";
        return $sql;
    }

    public function getRespostaByQuestoes($id_pessoa = null, $id_turma = null, $id_inst = null, $resp = null, $id_form = null, $destino = null){
        $sqlAux = '';
        if (!empty($id_form)) {
            $sqlAux .= " AND afr.fk_id_form = $id_form ";
        }
        $sql = "SELECT y.fk_id_pessoa, y.n_pessoa, afs.destino, afp.id_pergunta, afp.n_pergunta, afr.fk_id_opcao "
            . $this->getSQLResposta($id_pessoa, $id_turma, $id_inst, $resp, $id_form, $destino)
            . "JOIN audicao_form_resposta afr ON y.fk_id_pessoa = afr.fk_id_pessoa "
            . "JOIN audicao_form_selecao afs ON afs.selecao = y.selecao AND afr.fk_id_form = afs.fk_id_form AND afr.fk_id_opcao = afs.fk_id_opcao "
            . "JOIN audicao_form_opcoes afo ON afr.fk_id_opcao = afo.id_opcao "
            . "JOIN audicao_form_perguntas afp ON afo.fk_id_pergunta = afp.id_pergunta "
            . "WHERE 1 $sqlAux "
            . "ORDER BY y.n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $alunos = $query->fetchAll(PDO::FETCH_ASSOC);
        return $alunos;
    }

    public function getRespostaByEncaminhamento($id_pessoa = null, $id_turma = null, $id_inst = null, $resp = null, $id_form = null, $destino = null){
        $sql = "SELECT DISTINCT y.fk_id_pessoa AS id_pessoa, y.n_pessoa, afs.destino, afs.fk_id_form"
            . $this->getSQLResposta($id_pessoa, $id_turma, $id_inst, $resp, $id_form, $destino)
            . "JOIN audicao_form_resposta afr ON y.fk_id_pessoa = afr.fk_id_pessoa "
            . "JOIN audicao_form_selecao afs ON afs.selecao = y.selecao AND afr.fk_id_form = afs.fk_id_form AND afr.fk_id_opcao = afs.fk_id_opcao "
            . "JOIN audicao_form_opcoes afo ON afr.fk_id_opcao = afo.id_opcao "
            . "JOIN audicao_form_perguntas afp ON afo.fk_id_pergunta = afp.id_pergunta "
            . "ORDER BY y.n_pessoa ";            
        $query = pdoSis::getInstance()->query($sql);
        $alunos = $query->fetchAll(PDO::FETCH_ASSOC);

        $r = [];
        if (!empty($alunos)){
            foreach ($alunos as $k => $v) {
                if (!isset($r[ $v['id_pessoa'] ])){
                    $r[ $v['id_pessoa'] ] = [
                        'id_pessoa' => $v['id_pessoa'],
                        'n_pessoa' => $v['n_pessoa'],
                        'encaminhamento' => [],
                        'form' => [],
                    ];
                }

                $r[ $v['id_pessoa'] ]['encaminhamento'][$v['destino']] = true;
                $r[ $v['id_pessoa'] ]['form'][$v['fk_id_form']] = true;
            }
        }
        return $r;
    }

    public function getRespostaByAluno($id_pessoa = null, $id_turma = null, $id_inst = null, $resp = null, $id_form = null, $destino = null){
        $sql = "SELECT DISTINCT y.fk_id_pessoa AS id_pessoa, y.n_pessoa"
            . $this->getSQLResposta($id_pessoa, $id_turma, $id_inst, $resp, $id_form, $destino);
        $query = pdoSis::getInstance()->query($sql);
        $alunos = $query->fetchAll(PDO::FETCH_ASSOC);
        return $alunos;
    }

    public function formRespostas($id_inst, $id_turma){
        if (!empty($id_turma)) {
            $alunos = $this->getRespostaByEncaminhamento(null, $id_turma, $id_inst);
            foreach ($alunos as $k => $v) {
                // Triagem
                if (isset($v['form'][2])) {
                    $btnProf ='success';
                }else{
                    $btnProf ='outline-info';
                }
                // Queixa Vestibular
                if (isset($v['form'][1])) {
                    $btnPais ='success';
                }else{
                    $btnPais ='outline-info';
                }

                if (isset($v['encaminhamento'][1]) && isset($v['encaminhamento'][2])) {
                    $alunos[$k]['ouro'] = '<div class="btn btn-warning">Padrão Ouro</div>';
                } elseif (isset($v['encaminhamento'][1])) {
                    $alunos[$k]['ouro'] = '<div class="btn btn-outline-warning">Triagem</div>';
                } else {
                    $alunos[$k]['ouro'] = '<div class="btn btn-outline-warning">Queixa Vestibular</div>';
                }

                $alunos[$k]['pais'] = '<button class="btn btn-' . $btnPais . '" onclick="acesso(\'formPaisResult\',\' - Questionário aos Pais\',' . $v['id_pessoa'] . ',\'' . $v['n_pessoa'] . '\' )">Questionário aos Pais</button>';
                $alunos[$k]['prof'] = '<button class="btn btn-' . $btnProf . '" onclick="acesso(\'formProfResult\',\' - Questionário ao Professor\',' . $v['id_pessoa'] . ',\'' . $v['n_pessoa'] . '\' )">Questionário ao Professor</button>';
                $alunos[$k]['hist'] = '<button class="btn btn-info" style="color:white;" onclick="pdf(\'formPaisPDF\',' . $v['id_pessoa'] . ',null,1 )">Visualizar Histórico</button>';
                $alunos[$k]['encaminha'] = '<button class="btn btn-outline-info" onclick="pdf(\'encaminhamentoPed\',' . $v['id_pessoa'] . ')">Imprimir Encaminhamento</button>';
            }
            $form['array'] = $alunos;
            $form['fields'] = [
                'RSE' => 'id_pessoa',
                'Aluno' => 'n_pessoa',
                '||4' => 'ouro',
                //'||3' => 'hist',
                '||2' => 'pais',
                '||1' => 'prof',
                '||3' => 'encaminha',
            ];
    
        }
        if (!empty($alunos)) {
            $lista_alunos = report::simple($form);
        }elseif (empty($id_turma)){
            $lista_alunos = toolErp::divAlert('warning', 'Escolha uma turma'); 
        }else{
            $lista_alunos = toolErp::divAlert('warning', 'Todos os Alunos desta Turma passaram e estão dispensados');
        }

        return $lista_alunos;
    }

    public function formAvisos($id_turma){
        $gerente = $this->gerente();
        if (!empty($id_turma)) {
            $alunos = $this->getRespostaByEncaminhamento(null, $id_turma);
            foreach ($alunos as $k => $v) {
                // Triagem
                if (isset($v['encaminhamento'][1])) {
                    $alunos[$k]['encaminhamento'] = '<button class="btn btn-outline-info" onclick="pdf(\'avisoTriagem\',' . $v['id_pessoa'] . ')">Imprimir Aviso</button>';
                }else{
                    $alunos[$k]['encaminhamento'] = null;
                } 
                // Queixa Vestibular
                if (isset($v['encaminhamento'][2])) {
                    $alunos[$k]['aviso'] = '<button class="btn btn-outline-info" onclick="pdf(\'encaminhamentoPed\',' . $v['id_pessoa'] . ')">Imprimir Encaminhamento</button>';
                }else{
                    $alunos[$k]['aviso'] = null;
                } 
                    
            }
            if ($gerente ==1) {
                $form['array'] = $alunos;
                $form['fields'] = [
                    'RSE' => 'id_pessoa',
                    'Aluno' => 'n_pessoa',
                    '||2' => 'aviso',
                    '||1' => 'encaminhamento',
                ];
            }else{
                $form['array'] = $alunos;
                $form['fields'] = [
                    'RSE' => 'id_pessoa',
                    'Aluno' => 'n_pessoa',
                    '||1' => 'encaminhamento',
                ];
            }
        }
        if (!empty($alunos)) {
            $lista_alunos = report::simple($form);
        }elseif (empty($id_turma)){
            $lista_alunos = toolErp::divAlert('warning', 'Escolha uma turma'); 
        }else{
            $lista_alunos = toolErp::divAlert('warning', 'Não há Encaminhamentos ou Avisos para esta turma');
        }

        return $lista_alunos;
    }

    public function dashGetResult($id_inst = null,$id_campanha) {
        $instancias = ng_escolas::gets();
        $triagem = 0;
        $queixa = 0;
        $totalOuro = 0;
        $totalAluno = 0;
        $totais = [];

        if (empty($instancias)) {
            return $totais;
        }

        $Escola = $this->getRespostaByEncaminhamentoEscola(null, null,$id_inst);
        foreach ($instancias as $value) {
            $triagem = 0;
            $queixa = 0;
            $totalOuro = 0;
            $totalAluno = 0;

            if (isset($Escola[$value['id_inst']]))
            {
                foreach ($Escola[$value['id_inst']] as $k => $v)
                {
                    // Ouro
                    if (isset($v['encaminhamento'][1]) && isset($v['encaminhamento'][2])) {
                        $totalOuro++;

                    // Triagem
                    } elseif (isset($v['encaminhamento'][1])) {
                        $triagem++;

                    // Queixa Vestibular
                    } elseif (isset($v['encaminhamento'][2])) {
                        $queixa++;
                    }

                    $totalAluno++;
                }
            }

            $totais[] = [
                'id_inst' => $value['id_inst'],
                'n_inst' => $value['n_inst'],
                'queixa' => $queixa,
                'triagem' => $triagem,
                'ouro' => $totalOuro,
                'alunos' => $totalAluno,
            ];
        }
        return $totais;
    }

    public function getRespostaByEncaminhamentoEscola($id_pessoa = null, $id_turma = null, $id_inst = null, $resp = null, $id_form = null, $destino = null){
        $sql = "SELECT y.id_inst, y.n_inst, y.fk_id_pessoa AS id_pessoa, afs.destino"
            . $this->getSQLResposta($id_pessoa, $id_turma, $id_inst, $resp, $id_form, $destino)
            . "JOIN audicao_form_resposta afr ON y.fk_id_pessoa = afr.fk_id_pessoa "
            . "JOIN audicao_form_selecao afs ON afs.selecao = y.selecao AND afr.fk_id_form = afs.fk_id_form AND afr.fk_id_opcao = afs.fk_id_opcao "
            . "JOIN audicao_form_opcoes afo ON afr.fk_id_opcao = afo.id_opcao "
            . "JOIN audicao_form_perguntas afp ON afo.fk_id_pergunta = afp.id_pergunta "
            . "GROUP BY y.id_inst, y.n_inst, y.fk_id_pessoa, afs.destino "
            . "ORDER BY y.n_inst ";
            // echo $sql;die();
        $query = pdoSis::getInstance()->query($sql);
        $alunos = $query->fetchAll(PDO::FETCH_ASSOC);

        $r = [];
        if (!empty($alunos)){
            foreach ($alunos as $k => $v) {
                if (!isset($r[ $v['id_inst'] ][ $v['id_pessoa'] ])){
                    $r[ $v['id_inst'] ][ $v['id_pessoa'] ] = [
                        'id_inst' => $v['id_inst'],
                        'n_inst' => $v['n_inst'],
                        'encaminhamento' => [],
                    ];
                }

                if (!isset($r[ $v['id_inst'] ][ $v['id_pessoa'] ]['encaminhamento'][$v['destino']])) {
                    $r[ $v['id_inst'] ][ $v['id_pessoa'] ]['encaminhamento'][$v['destino']] = 0;
                }
                $r[ $v['id_inst'] ][ $v['id_pessoa'] ]['encaminhamento'][$v['destino']]++;
            }
        }
        return $r;
    }

    public function getCiclos(){
        $sql = "SELECT id_ciclo, id_curso, n_ciclo, n_curso "
                . " FROM ge_ciclos cc "
                . " JOIN ge_cursos c ON c.id_curso = cc.fk_id_curso "
                . " WHERE cc.ativo = 1 "
                . " ORDER BY c.n_curso, cc.n_ciclo ";
        $query = pdoSis::getInstance()->query($sql);
        $ciclos = $query->fetchAll(PDO::FETCH_ASSOC);
        $arrayCiclos = [];
        if (!empty($ciclos)) {
            foreach ($ciclos as $k => $v) {
                if (!isset($arrayCiclos[$k])) {
                    $arrayCiclos[$v['n_curso']][$v['id_ciclo']] = $v['n_ciclo'];
                }
            }
        }
        return $arrayCiclos;
    }

    public function edita_campanha(){
        $ins = $_POST[3];
        $id_campanha = $_POST[3]['id_campanha'];
        $this->db->ireplace('audicao_campanha', $ins);
        $sql = "DELETE FROM `audicao_campanha_ciclo` WHERE `fk_id_campanha` = $id_campanha ";
        $query = pdoSis::getInstance()->query($sql);
        if (!empty($_POST[1]['fk_id_ciclo'])) {
            $arrayCiclos = $_POST[1]['fk_id_ciclo'];
            foreach ($arrayCiclos as $key => $value) {
                $insert1['fk_id_campanha'] = $id_campanha;
                $insert1['fk_id_ciclo'] = $value;
                $insert1['fk_id_pessoa'] = toolErp::id_pessoa();
                $insert1['tipo'] = 1;
                $this->db->ireplace('audicao_campanha_ciclo', $insert1, 1);
            }
        }
        if (!empty($_POST[2]['fk_id_ciclo'])) {
            $arrayCiclos = $_POST[2]['fk_id_ciclo'];
            foreach ($arrayCiclos as $key => $value) {
                $insert2['fk_id_campanha'] = $id_campanha;
                $insert2['fk_id_ciclo'] = $value;
                $insert2['fk_id_pessoa'] = toolErp::id_pessoa();
                $insert2['tipo'] = 2;
                $this->db->ireplace('audicao_campanha_ciclo', $insert2, 1);
            }
        }
        log::logSet("Configurou a Campanha id:" . $id_campanha); 
    }

    public function getCiclosCampanha($id_campanha,$tipo){
        $sql = "SELECT fk_id_ciclo"
                . " FROM audicao_campanha_ciclo acc "
                . " WHERE acc.tipo = $tipo AND fk_id_campanha = $id_campanha ";
        $query = pdoSis::getInstance()->query($sql);
        $ciclos = $query->fetchAll(PDO::FETCH_ASSOC);
        $ciclosArr = [];
        foreach ($ciclos as $v){
            $ciclosArr[$v['fk_id_ciclo']]=$v['fk_id_ciclo'];
        }
        return $ciclosArr;
    }

}
