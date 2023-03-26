<?php

class protocoloModel extends MainModel {

    public $db;

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

        if ($this->db->tokenCheck('protocoloCad',true)) {
            $this->protocoloCad();
        }elseif ($this->db->tokenCheck('salvarForm',true)) {
            $this->salvarForm();
        }elseif ($this->db->tokenCheck('protocoloAtualizar',true)) {
            $this->protocoloAtualizar();
        }elseif ($this->db->tokenCheck('addCidAluno',true)) {
            $this->addCidAluno();
        }elseif ($this->db->tokenCheck('delCidAluno',true)) {
            $this->delCidAluno();
        }elseif ($this->db->tokenCheck('protTermo', false)) {
            $this->protTermo();
        }elseif ($this->db->tokenCheck('protTermoAssinar', true)) {
            $this->protTermoAssinar();
        }
    }

    public function protocoloGet($id_area,$id_inst=null,$id_status=null,$page=null,$id_turma=null,$prof=null) {
        if (user::session('id_nivel') == 8) {
            $id_inst = toolErp::id_inst();
            $page = 'protocolo';
        }
        $WHERE = '';
        if (self::isProfessor() && empty($prof)) {
            $WHERE .= " AND (pct.fk_id_protocolo_status NOT IN (8,9) OR pct.fk_id_protocolo_status IS NULL) ";
            $WHERE .= " AND pc.fk_id_turma_AEE IN(". self::getTurmasProfSQL($id_turma) .") ";
            if (!empty($id_inst)) {
               $WHERE .= " AND pc.fk_id_inst_AEE = ".$id_inst; 
            }
        } elseif (!empty($id_inst)) {
           $WHERE .= " AND pc.fk_id_inst = ".$id_inst; 
        }
        if (user::session('id_nivel') == 10) {
            $page = 'protocoloGerente';
        }

        if (!empty($id_status)) {
            $id_status = " AND psp.fk_id_proto_status = ".$id_status;
        }
        $sql = "SELECT "
            . " pct.fk_id_protocolo_status AS statusContato, pct.obs AS obs, t.id_turma, i.n_inst, p.id_pessoa, p.n_pessoa, pc.id_protocolo, pc.fk_id_pessoa, pc.dt_update, ps.n_status, ps.id_proto_status "
            . " FROM protocolo_cadastro pc"
            . " JOIN protocolo_status_pessoa psp ON psp.id_proto_status_pessoa = ("
                . " SELECT max(id_proto_status_pessoa) FROM protocolo_status_pessoa WHERE fk_id_protocolo = pc.id_protocolo) "
            . " JOIN protocolo_status ps ON ps.id_proto_status = psp.fk_id_proto_status ".$id_status
            . " LEFT JOIN protocolo_contactar pct ON pct.id_contato = ("
                . " SELECT ppct.id_contato FROM protocolo_contactar ppct WHERE ppct.fk_id_protocolo = pc.id_protocolo ORDER BY ppct.id_contato DESC LIMIT 1 )"
            . " JOIN instancia i ON i.id_inst = pc.fk_id_inst"
            . " JOIN pessoa p ON p.id_pessoa = pc.fk_id_pessoa"
            . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa AND (ta.fk_id_tas = 0 OR ta.fk_id_tas IS NULL)"
            . " LEFT JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND t.fk_id_ciclo != 32"
            . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl AND pl.at_pl = 1"
            . " WHERE fk_id_area = $id_area "
            . $WHERE
            . " ORDER BY psp.dt_update DESC ";
        $query = pdoSis::getInstance()->query($sql);
        $protocolo = $query->fetchAll(PDO::FETCH_ASSOC);
        @$entr['fk_id_pessoa'] = 1;
        if (!empty($protocolo)) {
            foreach ($protocolo as $k => $v) {

                if (!empty($prof) && $v['id_proto_status'] == 9) {
                    $protocoloProf[$k]['acProf'] = formErp::submit('Recusa', null, ['id_protocolo'=>$v['id_protocolo'],'id_pessoa'=>$v['fk_id_pessoa'],'id_area'=>$id_area], HOME_URI.'/apd/termoRecusa',1,null,'btn btn-outline-danger');
                    $protocoloProf[$k]['proto'] = $v['id_protocolo'];
                    $protocoloProf[$k]['aluno'] = $v['n_pessoa']; 
                }else{
                
                    if (user::session('id_nivel') == 24) {
                        $entr = $this->getEntre($v['fk_id_pessoa']);
                        $protocolo[$k]['entr'] = '<button class="btn btn-outline-info" onclick="entr(' . $v['fk_id_pessoa'] . ',\'' . $v['n_pessoa'] . '\' ,' . $v['id_turma'] . ',\'' . @$entr['id_entre'] . '\')">Entrevista Familiar</button>';
                        $btn = $v['statusContato'] <= 6 ? 'btn btn-outline-info' : 'btn btn-success';
                        $btnName = $v['statusContato'] <= 6 ? 'Contatar Aluno' : 'Aluno Contatado';
                        $protocolo[$k]['cont'] = '<button class="'.$btn.'" onclick="contactaALuno(' . $v['fk_id_pessoa'] . ',\'' . $v['n_pessoa'] . '\' ,' . $v['id_protocolo'] . ')">'.$btnName.'</button>';
                        $protocolo[$k]['termo'] = '<button class="btn btn-outline-info" onclick="termo('.$v['id_protocolo'].', ' . $v['fk_id_pessoa'] . ',\'' . $v['n_pessoa'] . '\' ,' . $v['id_turma'] . ',\'' . @$entr['id_entre'] . '\')">Termo Aceite</button>';
                        $protocolo[$k]['recusa'] = '<button class="btn btn-outline-danger" onclick="recusa('.$v['id_protocolo'].', ' . $v['fk_id_pessoa'] . ',\'' . $v['n_pessoa'] . '\' ,' . $v['id_turma'] . ',\'' . @$entr['id_entre'] . '\')">Termo Recusa</button>';
                    }
                    if ($v['id_proto_status'] == 9) {
                        $protocolo[$k]['ac'] = formErp::submit('Recusa', null, ['id_protocolo'=>$v['id_protocolo'],'id_pessoa'=>$v['fk_id_pessoa'],'id_area'=>$id_area], HOME_URI.'/apd/termoRecusa',1,null,'btn btn-outline-danger');
                    }else{
                        $protocolo[$k]['ac'] = formErp::submit('Acessar', null, ['id_protocolo'=>$v['id_protocolo'],'id_pessoa'=>$v['fk_id_pessoa'],'id_area'=>$id_area], HOME_URI.'/apd/'.$page);
                    }
                }
            }
            $form['array'] = $protocolo;
            $form['fields'] = [
                'Protocolo' => 'id_protocolo',
                'RSE' => 'id_pessoa',
                'Aluno' => 'n_pessoa',
                'Atualização' => 'dt_update',
                'Situação' => 'n_status',
                '||3' => 'ac',
            ];

            if (!empty($prof)) {
            $form['array'] = $protocoloProf;
               $form['fields'] = [
                    'Protocolo' => 'proto',
                    'RSE' => 'id_pessoa',
                    'Aluno' => 'aluno',
                    '||3' => 'acProf',
                ]; 
            }else{
                if (user::session('id_nivel') == 10) {
                    $form['fields'] = [
                        'Protocolo' => 'id_protocolo',
                        'RSE' => 'id_pessoa',
                        'Aluno' => 'n_pessoa',
                        'Atualização' => 'dt_update',
                        'Situação' => 'n_status',
                        'Escola' => 'n_inst',
                        '||3' => 'ac',
                    ];
                }elseif(user::session('id_nivel') == 24){
                   $form['fields'] = [
                    'Protocolo' => 'id_protocolo',
                    'RSE' => 'id_pessoa',
                    'Atualização' => 'dt_update',
                    'Aluno' => 'n_pessoa',
                    'Obs do Contato' => 'obs',
                    '||3' => 'cont',
                    '||2' => 'entr',
                    '||1' => 'termo',
                    '||4' => 'recusa',
                    ]; 
                }
            }
            
            
        }else{
            $form = null;
        }
        return $form;
    }

    public function areaGet($id_area) {
        $sql = "SELECT "
            . " n_area "
            . " FROM protocolo_area "
            . " WHERE id_proto_area = $id_area ";

        $query = pdoSis::getInstance()->query($sql);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
            $area = $result['n_area'];
        }else{
            $area = null;
        }
        return $area;
    }

    public function alunosGet($pesquisa,$rede=null,$id_area) {
        $form = null;
        if ($pesquisa) {
            $id_inst = tool::id_inst();
            $result = ng_main::alunoPesquisa($pesquisa, $id_inst, 130);
            if ($result) {
                if (count($result) >= 99) {
                    toolErp::alertModal('Sua busca foi muito ampla. Limitamos o retorno da pesquisa a ' . count($result) . ' Alunos');
                }
                foreach ($result as $k => $v) {
                    $token = formErp::token('protocoloCad');
                    $result[$k]['turmasList'] = implode('<br>', $v['turmas']);
                    $result[$k]['ac'] = formErp::submit('Abrir Protocolo', null, ['id_pessoa' => $v['id_pessoa'],'id_area' => $id_area], HOME_URI . '/apd/protocolo');
                    $result[$k]['tr'] = formErp::submit('Termo Recusa', $token, ['termoRecusa' => true, '1[fk_id_pessoa]' => $v['id_pessoa'], 'id_pessoa' => $v['id_pessoa'], '1[fk_id_area]' => $id_area], HOME_URI . '/protocolo/termoRecusaRedirect', 1, 'ATENÇÃO!!! Esta ação irá gerar um protocolo. Deseja Continuar', 'btn btn-outline-danger', 'white-space: normal;');
                }
                $form['array'] = $result;
                $form['fields'] = [
                    'RSE' => 'id_pessoa',
                    'Nome' => 'n_pessoa',
                    'Turmas' => 'turmasList',
                    '||1' => 'ac',
                    '||2' => 'tr',
                ];
                if(user::session('id_nivel') == 24){
                    $form['fields'] = [
                        'RSE' => 'id_pessoa',
                        'Nome' => 'n_pessoa',
                        'Turmas' => 'turmasList',
                        '||2' => 'tr',
                    ];
                }
            }
        }
        return $form;
    }

    public function apdUpload() {
        $id_aluno = filter_input(INPUT_POST, 'fk_id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);
        if (!empty($_FILES['arquivo'])) {
            for ($i = 0; $i < count($_FILES['arquivo']['name']); $i++) {
                if ($_FILES['arquivo']['size'][$i] > 5000000) {
                    toolErp::alert('O limite é 5 megabytes');
                    return;
                }
                @$exten = end(explode('.', $_FILES['arquivo']['name'][$i]));
                /*
                  if (!in_array($exten, ['pdf', 'PDF'])) {
                  toolErp::alert('Só é permitido anexar PDF');
                  return;
                  }
                 */
                $nome_origin = $_FILES['arquivo']['name'][$i];
                $file = ABSPATH . '/pub/apd/';
                $up = new upload($file, $id_aluno, 5000000/* , ['pdf', 'PDF'] */);
                $end = $up->upMultiple($i);
                if ($end) {
                    $ins['n_up'] = toolErp::escapaAspa($nome_origin);
                    $ins['fk_id_pessoa'] = $id_aluno;
                    $ins['link'] = $end;
                    $ins['tipo'] = $tipo;
                    $this->db->ireplace('apd_up', $ins);
                } else {
                    toolErp::alert('Erro ao enviar. Tente novamente');
                }
            }
        } else {
            toolErp::alert('Erro ao enviar. Tente novamente');
        }
    }


    public static function alunoAeeGet($id_pessoa = NULL, $fields = " p.id_pessoa, p.n_pessoa, t.id_turma, t.n_turma, i.id_inst, i.n_inst, n.n_ne, n.id_ne ") {
        $sql = "SELECT DISTINCT"
                . " $fields "
                . " FROM pessoa p "
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa AND (ta.fk_id_tas = 0 OR ta.fk_id_tas IS NULL)"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND t.fk_id_ciclo != 32 "
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN instancia i ON i.id_inst = t.fk_id_inst "
                . " LEFT JOIN ge_aluno_necessidades_especiais ae ON p.id_pessoa = ae.fk_id_pessoa "
                . " LEFT JOIN ge_necessidades_especiais n ON n.id_ne = ae.fk_id_ne "
                . " WHERE p.id_pessoa = $id_pessoa;";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $alu = [];
        if ($array) {
            foreach ($array as $v) {
                if (empty($v['n_ne'])) {
                    $v['n_ne'] = 'Sem Informação no SED';
                }
                if (!empty($alu['def'])) {
                    $def = $alu['def'];
                }
                $alu = $v;
                if (empty($def)) {
                    $alu['def'] = $v['n_ne'];
                } else {
                    $alu['def'] = $def . ';' . $v['n_ne'];
                }
            }
        }
        return $alu;
    }

    public function protocoloCad() {
        $ins = @$_POST[1];
        if (!empty($ins)) {
            $id_protocolo = $this->protocoloAbrir($ins);
            if (!empty($id_protocolo)) {
                $_POST['id_protocolo'] = $id_protocolo;
                $_POST['id_pessoa'] = $ins['fk_id_pessoa'];
                if (!empty($_FILES['arquivo']) && $_POST['up']==1) {
                    //$this -> protocoloUp();
                    $file = ABSPATH . '/pub/protocoloDoc/';
                    $up = new upload($file, $id_protocolo.'_'.time(), 15000000);
                    $end = $up->upMultiple();
                    if (!empty($end)) {
                        foreach ($end as $v) {
                            $n_arquivo = toolErp::escapaAspa($v['nome_original']);
                            $insUP['link'] = $v['link'];
                            $insUP['fk_id_protocolo'] = $id_protocolo;
                            $insUP['fk_id_pessoa_anexa'] = toolErp::id_pessoa();
                            $insUP['n_up'] = $n_arquivo;
                            $this->db->ireplace('protocolo_up', $insUP,1);
                        } 
                    }
                }
            } else{
                toolErp::alertModal('Lamento! Algo de errado :( ');
            } 
        }else{
           toolErp::alertModal('Lamento! Algo de errado :( '); 
        }

    }

    public function protocoloAbrir($array) {
        if (!empty($array)) {
            $pl = gtMain::periodoLetivoAtual();
            $array['fk_id_pl'] = $pl['id_pl'];
            if (toolErp::id_nilvel()<>10) {
                $array['fk_id_status'] = 4;
                $array['fk_id_inst'] = toolErp::id_inst();
                $array['fk_id_pessoa_cadastra'] = toolErp::id_pessoa();
            }
            $id_protocolo = $this->db->ireplace('protocolo_cadastro', $array, (!empty($_POST['termoRecusa']) ? 1 : null) ); 
        }
        if (!empty($id_protocolo) && toolErp::id_nilvel()<>10) {
            $ins['fk_id_pessoa'] = toolErp::id_pessoa();
            $ins['fk_id_protocolo'] = $id_protocolo;
            $ins['fk_id_proto_status'] = $array['fk_id_status'];
            if (toolErp::id_nilvel()<>10) {
                $ins['fk_id_proto_status'] = 4;
            }

            if (!empty($array['descricao'])) {
                $ins['justifica'] = $array['descricao']; 
            }

            $this->db->ireplace('protocolo_status_pessoa', $ins, 1); 
        }
        return (int) $id_protocolo;  
    }

    public function protocoloAtualizar() {
        $ins = @$_POST[1];
        if (!empty($ins)) {
            if ($ins['fk_id_proto_status']==1) {
                $this -> protocoloDefere($ins);
            }else{ 
                $ins1['fk_id_status'] =  $ins['fk_id_proto_status'];
                $ins1['id_protocolo'] = $ins['id_protocolo'];
                $this->db->ireplace('protocolo_cadastro', $ins1); 
            
                $ins2['fk_id_pessoa'] = toolErp::id_pessoa();
                $ins2['fk_id_proto_status'] = $ins['fk_id_proto_status'];
                $ins2['fk_id_protocolo'] = $ins['id_protocolo'];
                $ins2['justifica'] = $ins['justifica'];
                $this->db->ireplace('protocolo_status_pessoa', $ins2, 1); 
            }
        }

    }
    public function protocoloDefere($array) {

        $ins1['fk_id_status'] = 1;
        $ins1['id_protocolo'] = $array['id_protocolo'];
        $ins1['fk_id_turma_AEE'] = $array['fk_id_turma_AEE'];
        $ins1['fk_id_inst_AEE'] = $array['fk_id_inst_AEE'];
        //$array['fk_id_pessoa_cadastra'] = toolErp::id_pessoa();
        $this->db->ireplace('protocolo_cadastro', $ins1); 
    
        $ins['fk_id_pessoa'] = toolErp::id_pessoa();
        $ins['fk_id_proto_status'] = 1;
        $ins['fk_id_protocolo'] = $array['id_protocolo'];
        $this->db->ireplace('protocolo_status_pessoa', $ins, 1); 
    }

    public function protocoloUp($array) {
        $file = ABSPATH . '/pub/fotoApd/';
        $up = new upload($file, $id_atend.'_'.time(), 15000000);
        $end = $up->upMultiple();

        if (!empty($end)) {
           foreach ($end as $v) {
                $n_arquivo = $v['nome_original'];
                $link = $v['link'];
                $insUP['link'] = $link;
                $insUP['fk_id_atend'] = $id_atend;
                $insUP['fk_id_pessoa_aluno'] = $_POST['id_pessoa'];
                $insUP['fk_id_pessoa_up'] = $_POST[1]['fk_id_pessoa_prof'];
                $insUP['n_arquivo'] = toolErp::escapaAspa($n_arquivo);
                $this->db->ireplace('apd_pdi_atend_up', $insUP,1);
            } 
        }
    }

    public function getPessoa($id_pessoa){

        $sql = "SELECT "
                . " id_pessoa,n_pessoa,dt_nasc,mae,pai,responsavel,cidade_nasc,uf_nasc,logradouro,num,complemento,bairro,tel1,tel2,tel3,ddd1,ddd2,ddd3,sexo"
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

           $respons = (!empty($dados['responsavel'])&&($dados['responsavel']<>$dados['pai']&&$dados['responsavel']<>$dados['mae']) ? $dados['responsavel'] : '');
           $pais = [$dados['mae'],$dados['pai'],$respons];
           $dados['pais'] = toolErp::virgulaE($pais);
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

    public function salvarForm() {
        $respostas = @$_POST[1];
        $id_form = filter_input(INPUT_POST, 'id_form', FILTER_SANITIZE_NUMBER_INT);
        $id_protocolo = filter_input(INPUT_POST, 'id_protocolo', FILTER_SANITIZE_NUMBER_INT);

        $sql = "DELETE FROM protocolo_form_resposta WHERE  fk_id_form =  $id_form AND fk_id_protocolo = $id_protocolo";
        $query = $this->db->query($sql);

        if (!empty($respostas['fk_id_pergunta']['checkbox'])) {
            foreach ($respostas['fk_id_pergunta']['checkbox'] as $k => $v) { //checkbox
                foreach ($v as $i => $j) {
                    if (!empty($j)) {
                        $ins = [
                            'fk_id_form'=> $id_form,
                            'fk_id_protocolo'=> $id_protocolo,
                            'fk_id_pessoa'=> toolErp::id_pessoa(),
                            'fk_id_opcao'=> $j
                        ];
                        $this->db->ireplace('protocolo_form_resposta', $ins,1);
                    }
                }  
            } 
            $respondido = 1;
        }
        
        if (!empty($respostas['fk_id_pergunta'])) {
            foreach ($respostas['fk_id_pergunta'] as $k => $v) {
                if ($k == 'text') { //input
                    foreach ($v as $kk => $vv) {
                        if (!empty($vv)) {
                            $ins = [
                                'fk_id_form'=> $id_form,
                                'fk_id_protocolo'=> $id_protocolo,
                                'fk_id_opcao'=> $kk,
                                'fk_id_pessoa'=> toolErp::id_pessoa(),
                                'n_resposta'=> $vv
                            ];
                            $this->db->ireplace('protocolo_form_resposta', $ins,1); 
                            $respondido = 1;
                        }
                    }
                } else { //radio
                    if (!empty($v)) { 
                         $ins = [
                            'fk_id_form'=> $id_form,
                            'fk_id_protocolo'=> $id_protocolo,
                            'fk_id_pessoa'=> toolErp::id_pessoa(),
                            'fk_id_opcao'=> $v,
                            'n_resposta'=> null
                        ];
                        $this->db->ireplace('protocolo_form_resposta', $ins,1); 
                        $respondido = 1;
                    }
                }      
            }
        }
        if (!empty($respondido)) {
            if (toolErp::id_nilvel()<>10) {//gerente nao envia para deferimento
                $this->enviaProtocoloDeferimento($id_protocolo);  
            }
        }else{
            toolErp::alertModal('Estão faltando Informações, por isso o documento não foi enviado para deferimento');
        }  
    }

    public function enviaProtocoloDeferimento($id_protocolo) {
        $ins['fk_id_pessoa'] = toolErp::id_pessoa();
        $ins['fk_id_protocolo'] = $id_protocolo;
        $ins['fk_id_proto_status'] = 5;
        $this->db->ireplace('protocolo_status_pessoa', $ins, 1); 
        $ins1['fk_id_status'] = 5;
        $ins1['id_protocolo'] = $id_protocolo;
        $this->db->ireplace('protocolo_cadastro', $ins1); 
        return (int) $id_protocolo;      
    }  

    public function historicoGet($id_protocolo) {
        $status = quest::getProtocoloStatus($id_protocolo,'protocolo_status_pessoa',1); 
        if (!empty($status)) {
            foreach ($status as $k => $v) {
                $status[$k]['data'] = dataErp::converteBr($v['dt_update']);
            }
            $form['array'] = $status;
            $form['fields'] = [
                'Responsável' => 'n_pessoa',
                'Data' => 'data',
                'Estado' => 'n_status',
                'Observações' => 'justifica'
            ];
        }
        return $form;
    }

    public function idEscolas() {
        $sql = "SELECT i.id_inst, i.n_inst FROM instancia i "
                . " JOIN ge_escolas ON ge_escolas.fk_id_inst = i.id_inst "
                . " JOIN ge_turmas t on t.fk_id_inst = i.id_inst AND fk_id_ciclo = 32"
                . " JOIN ge_periodo_letivo plr ON plr.id_pl = t.fk_id_pl AND plr.at_pl = 1"
                . " ORDER BY i.n_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return tool::idName($array);
    }

    public function idTurmas($id_inst) {
        $sql = "SELECT t.id_turma, t.n_turma FROM ge_turmas t "
                . " JOIN ge_periodo_letivo plr ON plr.id_pl = t.fk_id_pl AND plr.at_pl = 1"
                . " WHERE t.fk_id_ciclo = 32 AND t.fk_id_inst =".$id_inst
                . " ORDER BY t.n_turma LIMIT 300";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return tool::idName($array);
    }

    public function cidGet($pesquisa,$hidden) {
        $form = null;
        if ($pesquisa) {
            $sql = "SELECT id_cid, id, descricao, versao FROM cid_sub_categoria "
                    . " WHERE (id LIKE '$pesquisa%' OR descricao LIKE '%$pesquisa%') "
                    . " ORDER BY id LIMIT 300";
            $query = pdoSis::getInstance()->query($sql);
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            
            if ($result) {
                if (count($result) >= 300) {
                    toolErp::alertModal('Sua busca foi muito ampla. Limitamos o retorno da pesquisa a ' . count($result) . ' linhas');
                }
                foreach ($result as $k => $v) {
                    $cidPessoa = sql::get('cid_pessoa', 'fk_id_cid', ['fk_id_pessoa' => $hidden['id_pessoa'],'fk_id_cid' => $v['id_cid']],'fetch');

                    if (!empty($cidPessoa)) {
                        $result[$k]['ad'] = '<button class="btn btn-outline-secondary" disabled>CID já Associado</button>';
                    }else{
                        $token = formErp::token('addCidAluno');
                        $result[$k]['ad'] = formErp::submit('Adicionar CID', $token, $hidden+[ 'id_cid' => $v['id_cid'],'activeNav'=>4], HOME_URI . '/apd/protocoloGerente','_parent'); 
                    }

                    if ($result[$k]['versao'] == '11') {
                        $result[$k]['versao'] = '11 <span class="alert-success">versão beta em Espanhol</span>';
                    }
                    
                }
                $form['array'] = $result;
                $form['fields'] = [
                    'Código' => 'id',
                    'Descrição' => 'descricao',
                    'Versão' => 'versao',
                    '||1' => 'ad'
                ];
            }
        }
        return $form;
    }

    public function addCidAluno() {
        $id_cid = filter_input(INPUT_POST, 'id_cid', FILTER_SANITIZE_STRING);
        $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $ins['fk_id_pessoa'] = $id_pessoa;
        $ins['fk_id_cid'] = $id_cid;
        $cid = $this->db->ireplace('cid_pessoa', $ins);   
        if (!empty($cid)) {
            log::logSet("Adcionou um CID à pessoa " . toolErp::n_pessoa($id_pessoa));  
        }else{
            echo toolErp::divAlert('danger','Ops... Desculpe, não foi possível salvar. Tente novamente!');
        } 
    }

    public function cidGetPessoa($hidden) {
        $form = null;
        $id_pessoa = $hidden['id_pessoa'];
        if ($id_pessoa) {
            $sql = "SELECT cp.id_cid_pessoa, csc.id_cid, csc.id, csc.descricao, csc.versao FROM cid_sub_categoria csc"
                    . " JOIN cid_pessoa cp on cp.fk_id_cid = csc.id_cid AND cp.fk_id_pessoa = $id_pessoa"
                    . " ORDER BY csc.id ";
            $query = pdoSis::getInstance()->query($sql);
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($result) {
                foreach ($result as $k => $v) {
                    $token = formErp::token('delCidAluno');
                    $result[$k]['ad'] = formErp::submit('&#x2718;', $token, $hidden+[ 'id_cid_pessoa' => $v['id_cid_pessoa']], HOME_URI . '/apd/protocoloGerente',null,'Deseja remover este CID desta pessoa?','btn btn-outline-danger');

                    if ($result[$k]['versao'] == '11') {
                        $result[$k]['versao'] = '11 <span class="alert-success">versão beta em Espanhol</span>';
                    }
                }
                $form['array'] = $result;
                $form['fields'] = [
                    'Código' => 'id',
                    'Descrição' => 'descricao',
                    'Versão' => 'versao',
                    '||1' => 'ad'
                ];
            }
        }
        return $form;
    }

    public function delCidAluno() {
        $id_cid_pessoa = filter_input(INPUT_POST, 'id_cid_pessoa', FILTER_SANITIZE_NUMBER_INT);
         $id_pessoa = filter_input(INPUT_POST, 'id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $cid = $this->db->delete('cid_pessoa','id_cid_pessoa', $id_cid_pessoa); 
        if (!empty($cid)) {
            log::logSet("Removeu o CID da pessoa " . toolErp::n_pessoa($id_pessoa));  
        }else{
            echo toolErp::divAlert('danger','Ops... Desculpe, não foi possível apagar. Tente novamente!');
        } 
    }

    public static function idade($data, $secondDate = null) {
        if (empty($secondDate)) {
            $d = date('d');
            $m = date('m');
            $a = date('Y');
        } else {
           if (substr($secondDate, 2, 1) == '/') {
                // Separa em dia, mês e ano
                list($d, $m, $a) = explode('/', $secondDate);
            } else {
                list($a, $m, $d) = explode('-', $secondDate);
            }
        }

        if (substr($data, 2, 1) == '/') {
            // Separa em dia, mês e ano
            list($dia, $mes, $ano) = explode('/', $data);
        } else {
            list($ano, $mes, $dia) = explode('-', $data);
        }

        // Descobre que dia é hoje e retorna a unix timestamp
        $hoje = mktime(0, 0, 0, $m, $d, $a);
        // Descobre a unix timestamp da data de nascimento do fulano
        $nascimento = mktime(0, 0, 0, $mes, $dia, $ano);

        // Depois apenas fazemos o cálculo já citado :)
        $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);

        return $idade;
    }

    public function getEntre($id_pessoa){
        $sql = "SELECT en.id_entre"
                . " FROM apd_doc_entrevista en"
                . " JOIN ge_turmas t on t.id_turma= en.fk_id_turma"
                . " JOIN ge_periodo_letivo plr ON plr.id_pl = t.fk_id_pl AND plr.at_pl = 1"
                . " WHERE en.fk_id_pessoa = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getProtocolo($id_protocolo) {
        $sql = "SELECT "
            . " t.id_turma, t.n_turma, t_aee.n_turma AS n_turma_aee, i.n_inst, i_aee.n_inst AS n_inst_aee, p.n_pessoa, pc.id_protocolo, date_format(pc.dt_update,'%Y') AS dt_protocolo, pc.fk_id_pessoa, pc.dt_update, ps.n_status, pc.fk_id_status, pc.fk_id_ne, pc.descricao, pc.dt_resp_form1, pc.fk_id_pessoa_cadastra, pc.fk_id_turma_AEE, pc.fk_id_inst_AEE "
            . " FROM protocolo_cadastro pc"
            . " JOIN protocolo_status_pessoa psp ON psp.id_proto_status_pessoa = ("
                . " SELECT max(id_proto_status_pessoa) FROM protocolo_status_pessoa WHERE fk_id_protocolo = pc.id_protocolo) "
            . " JOIN protocolo_status ps ON ps.id_proto_status = psp.fk_id_proto_status"
            . " JOIN instancia i ON i.id_inst = pc.fk_id_inst"
            . " LEFT JOIN instancia i_aee ON i_aee.id_inst = pc.fk_id_inst_AEE"
            . " JOIN pessoa p ON p.id_pessoa = pc.fk_id_pessoa"
            . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa AND (ta.fk_id_tas = 0 OR ta.fk_id_tas IS NULL)"
            . " LEFT JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND t.fk_id_ciclo != 32"
            . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl AND pl.at_pl = 1"
            . " LEFT JOIN ge_turmas t_aee ON t_aee.id_turma = pc.fk_id_turma_AEE"
            . " WHERE pc.id_protocolo = ". $id_protocolo;
        $query = pdoSis::getInstance()->query($sql);
        $protocolo = $query->fetch(PDO::FETCH_ASSOC);
        
        return $protocolo;
    }

    public function getTermo($id_protocolo, $tipo = 'A') {
        $sql = "SELECT "
            . " pta.id_protocolo_termo, pc.id_protocolo, p.id_pessoa, p.n_pessoa, pta.nome, pta.rg, pta.dias, pta.horarios, pta.autorizado, pta.autorizado_img, pta.conduzido_por, pta.motivo, pta.dt_update, ass.assinatura, ass.IP, ass.dt_update AS dt_assinatura "
            . " FROM protocolo_termo pta "
            . " JOIN protocolo_cadastro pc ON pta.fk_id_protocolo = pc.id_protocolo "
            . " LEFT JOIN pessoa p ON p.id_pessoa = pta.fk_id_pessoa "
            . " LEFT JOIN asd_assinatura ass ON ass.id_assinatura = pta.fk_id_assinatura "
            . " WHERE pc.id_protocolo = ". $id_protocolo ." AND pta.tipo = '$tipo' AND pta.ativo = 1 ";
        $query = pdoSis::getInstance()->query($sql);
        $protocolo = $query->fetch(PDO::FETCH_ASSOC);

        return $protocolo;
    }

    public function getTermoAdesao($id_protocolo) {
        return $this->getTermo($id_protocolo, 'A');
    }

    public function getTermoRecusa($id_protocolo) {
        return $this->getTermo($id_protocolo, 'R');
    }

    public function getTel($id_pessoa){
        $tel = sql::get('pessoa', 'tel1, tel2, tel3, email', ['id_pessoa' => $id_pessoa]);
        $tels = ng_pessoa::telefone($id_pessoa);
        if (!empty($tels)){
            $c=1;
            if (empty($tel)) {
                $t = [];
            }
            foreach ($tels as $key => $value) {
                $t = (!empty($value['ddd']) ? $value['ddd'] ." " : "") . $value['num'];
                $tel[0]["tel$c"] = $t;
            }
        }
        return $tel;
    }

    public static function getHistoricoContato($id_protocolo) {
        $sql = "SELECT pc.tel1, pc.tel2, pc.tel3, tel1Obs, tel2Obs, tel3Obs, dt_contactar, n_pessoa, pc.obs, n_status FROM protocolo_contactar pc"
                . " JOIN protocolo_status ps ON ps.id_proto_status = pc.fk_id_protocolo_status"
                . " LEFT JOIN pessoa p ON p.id_pessoa = pc.fk_id_pessoa_cadastra"
                . " WHERE pc.fk_id_protocolo= " . $id_protocolo
                . " ORDER BY pc.dt_contactar DESC, pc.id_contato DESC";
        $query = pdoSis::getInstance()->query($sql);
        $historico = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($historico) {
            foreach ($historico as $k => $v) {
                $obs1 = "";
                $obs2 = "";
                $obs3 = "";
                $obs1 = (!empty($v['tel1Obs'])) ? ": " . $v['tel1Obs'] : "";
                $obs2 = (!empty($v['tel2Obs'])) ? ": " . $v['tel2Obs'] : "";
                $obs3 = (!empty($v['tel3Obs'])) ? ": " . $v['tel3Obs'] : "";
                $historico[$k]['dt_contactar'] = $v['dt_contactar'];
                $historico[$k]['dt_horario'] = substr($v['dt_contactar'], -8);
                $historico[$k]['n_pessoa_call'] = $v['n_pessoa'];
                $historico[$k]['n_status'] = $v['n_status'];
                $historico[$k]['obs'] = $v['obs'];
                $historico[$k]['tel1'] = $v['tel1'] . $obs1;
                $historico[$k]['tel2'] = $v['tel2'] . $obs2;
                $historico[$k]['tel3'] = $v['tel3'] . $obs3;
            }
        }
        $formhist['array'] = $historico;
        $formhist['fields'] = [
            'Data' => 'dt_contactar',
            'Hora' => 'dt_horario',
            'Atendente' => 'n_pessoa_call',
            'Situação' => 'n_status',
            'Telefone 1' => 'tel1',
            'Telefone 2' => 'tel2',
            'Tel 3' => 'tel3',
            'Obs' => 'obs'
        ];
        if (!empty($historico)) {
           return $formhist; 
        }else{
            return null;
        }
    }

    public function protocoloDeferidoGet($id_area,$id_inst=null,$id_status=null,$page=null) {
        if (user::session('id_nivel') == 8) {
            $id_inst = toolErp::id_inst();
            $page = 'protocolo';
        }
        $WHERE = '';
        if (user::session('id_nivel') == 10) {
            $page = 'protocoloGerente';
        }
        if (!empty($id_inst)) {
           $WHERE = $WHERE." AND pc.fk_id_inst = ".$id_inst; 
        }
        if (!empty($id_status)) {
            $WHERE = $WHERE." AND pct.fk_id_protocolo_status = ".$id_status; 
        }
        $sql = "SELECT "
            . " psC.n_status, pct.fk_id_protocolo_status AS statusContato, pct.obs AS obs, pct.dt_contactar, t.id_turma, i.n_inst, p.n_pessoa, pc.id_protocolo, pc.fk_id_pessoa, pc.dt_update, ps.id_proto_status "
            . " FROM protocolo_cadastro pc"
            . " JOIN protocolo_status ps ON ps.id_proto_status = pc.fk_id_status AND fk_id_status = 1 "
            . " LEFT JOIN protocolo_contactar pct ON pct.id_contato = ("
                . " SELECT ppct.id_contato FROM protocolo_contactar ppct WHERE ppct.fk_id_protocolo = pc.id_protocolo ORDER BY ppct.id_contato DESC LIMIT 1 )"
            . " LEFT JOIN protocolo_status psC ON psC.id_proto_status = pct.fk_id_protocolo_status "
            . " JOIN instancia i ON i.id_inst = pc.fk_id_inst"
            . " JOIN pessoa p ON p.id_pessoa = pc.fk_id_pessoa"
            . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa AND (ta.fk_id_tas = 0 OR ta.fk_id_tas IS NULL)"
            . " LEFT JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND t.fk_id_ciclo != 32"
            . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl AND pl.at_pl = 1"
            . " WHERE fk_id_area = $id_area "
            . $WHERE
            . " ORDER BY IFNULL(pct.dt_contactar, '1900-01-01') DESC, pc.id_protocolo ";
        $query = pdoSis::getInstance()->query($sql);
        $protocolo = $query->fetchAll(PDO::FETCH_ASSOC);
        @$entr['fk_id_pessoa'] = 1;
        if (!empty($protocolo)) {
            foreach ($protocolo as $k => $v) {
                if (user::session('id_nivel') == 24) {
                    $entr = $this->getEntre($v['fk_id_pessoa']);
                    $protocolo[$k]['entr'] = '<button class="btn btn-outline-info" onclick="entr(' . $v['fk_id_pessoa'] . ',\'' . $v['n_pessoa'] . '\' ,' . $v['id_turma'] . ',' . @$entr['id_entre'] . ')">Entrevista Familiar</button>';
                    $btn = $v['statusContato'] <= 6 ? 'btn btn-outline-info' : 'btn btn-success';
                    $btnName = $v['statusContato'] <= 6 ? 'Contatar Aluno' : 'Aluno Contatado';
                    $protocolo[$k]['cont'] = '<button class="'.$btn.'" onclick="contactaALuno(' . $v['fk_id_pessoa'] . ',\'' . $v['n_pessoa'] . '\' ,' . $v['id_protocolo'] . ')">'.$btnName.'</button>';
                    $protocolo[$k]['termo'] = '<button class="btn btn-outline-info" onclick="termo(' . $v['fk_id_pessoa'] . ',\'' . $v['n_pessoa'] . '\' ,' . $v['id_turma'] . ',' . @$entr['id_entre'] . ')">Termo Aceite</button>';
                }
                $protocolo[$k]['cont'] = '<button class="btn btn-outline-info" onclick="contactaALuno(' . $v['fk_id_pessoa'] . ',\'' . $v['n_pessoa'] . '\' ,' . $v['id_protocolo'] . ')">Visualizar Contato</button>';

                if (!empty($v['n_status'])) {
                   $protocolo[$k]['status'] = $v['n_status'];
                }else{
                    $protocolo[$k]['status'] = 'Aguardando Contato do Professor';
                }  
            }
            $form['array'] = $protocolo;
            $form['fields'] = [
                'Protocolo' => 'id_protocolo',
                'Aluno' => 'n_pessoa',
                'Atualização' => 'status',
                'Situação' => 'n_status',
                '||3' => 'ac',
            ];
            if (user::session('id_nivel') == 10) {
                $form['fields'] = [
                    'Protocolo' => 'id_protocolo',
                    'Aluno' => 'n_pessoa',
                    'Atualização' => 'dt_contactar',
                    'Situação' => 'status',
                    'Escola' => 'n_inst',
                    '||3' => 'cont',
                ];
            }elseif(user::session('id_nivel') == 24){
               $form['fields'] = [
                'st' => 'statusContato',
                'Protocolo' => 'id_protocolo',
                'Atualização' => 'dt_update',
                'Aluno' => 'n_pessoa',
                'Obs do Contato' => 'obs',
                '||3' => 'cont',
                '||2' => 'entr',
                '||1' => 'termo',
                ]; 
            }elseif(user::session('id_nivel') == 48){
               $form['fields'] = [
                'Protocolo' => 'id_protocolo',
                    'Aluno' => 'n_pessoa',
                    'Atualização' => 'dt_contactar',
                    'Situação' => 'status',
                    '||3' => 'cont',
                ]; 
            }
        }else{
            $form = null;
        }
        return $form;
    }

    public function protTermo() {
        $ins = @$_POST[1];
        if (!empty($ins)) {

            //verifica se já existe um termo
            $tp = $this->getTermo($ins['fk_id_protocolo'], $ins['tipo']);
            if (!empty($tp)){
                $ins['id_protocolo_termo'] = $tp['id_protocolo_termo'];
            }
            
            $this->db->ireplace('protocolo_termo', $ins,1);

        }else{
           toolErp::alertModal('Nenhuma Informação enviada'); 
        }
    }

    public function getCIDpessoa($id_pessoa) {
        if (empty($id_pessoa)) {
            return '';
        }

        $sql = "SELECT cp.id_cid_pessoa, csc.id, csc.descricao FROM cid_sub_categoria csc"
                . " JOIN cid_pessoa cp on cp.fk_id_cid = csc.id_cid AND cp.fk_id_pessoa = $id_pessoa"
                . " ORDER BY csc.id ";
        $query = pdoSis::getInstance()->query($sql);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getCidProto($id_protocolo)
    {
        $sql = "SELECT fr.n_resposta "
            . " FROM protocolo_form_resposta fr "
            . " JOIN protocolo_cadastro pc ON fr.fk_id_protocolo = pc.id_protocolo "
            . " WHERE pc.id_protocolo = $id_protocolo AND fr.fk_id_opcao = 159";

        $query = pdoSis::getInstance()->query($sql);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getTermos($id_inst = null, $id_pessoa = null, $tipo = 'A', $id_turma = null) {
        $sqlAux = '';
        if (!empty($id_inst)) {
            if (self::isProfessor()) {
                $sqlAux .= " AND pc.fk_id_inst_AEE = ". $id_inst;
            } else {
                $sqlAux .= " AND pc.fk_id_inst = ". $id_inst;
            }
        }
        if (!empty($id_pessoa)) {
            $sqlAux .= " AND pc.fk_id_pessoa = ". $id_pessoa;
        }
        if (self::isProfessor()) {
            $sqlAux .= " AND pc.fk_id_turma_AEE IN(". self::getTurmasProfSQL($id_turma) .") ";
        }

        $sql = "SELECT "
            . " pc.id_protocolo, p.id_pessoa, p.n_pessoa, pta.nome, pta.rg, pta.dias, pta.horarios, pta.autorizado, pta.conduzido_por, pta.motivo, pta.dt_update, ass.assinatura, ass.IP, ass.dt_update AS dt_assinatura, pc.fk_id_pessoa, ps.n_status, pa.n_pessoa AS n_aluno "
            . " FROM protocolo_termo pta "
            . " JOIN protocolo_cadastro pc ON pta.fk_id_protocolo = pc.id_protocolo "
            . " JOIN pessoa p ON p.id_pessoa = pta.fk_id_pessoa "
            . " JOIN asd_assinatura ass ON ass.id_assinatura = pta.fk_id_assinatura "
            . " JOIN protocolo_status_pessoa psp ON psp.id_proto_status_pessoa = ("
                . " SELECT max(id_proto_status_pessoa) FROM protocolo_status_pessoa WHERE fk_id_protocolo = pc.id_protocolo) "
            . " JOIN protocolo_status ps ON ps.id_proto_status = psp.fk_id_proto_status "
            . " JOIN pessoa pa ON pa.id_pessoa = pc.fk_id_pessoa "
            . " WHERE pta.tipo = '$tipo' AND pta.ativo = 1 "
            . $sqlAux;
        $query = pdoSis::getInstance()->query($sql);
        $protocolo = $query->fetchAll(PDO::FETCH_ASSOC);

        return $protocolo;
    }

    public function formTermos($id_inst, $tipo = 'A', $id_turma = null)
    {
        $termos = $this->getTermos($id_inst, null, $tipo, $id_turma);
        if (empty($termos)) {
            return $termos;
        }

        if ($tipo == 'A') {
            $t = 'termo';
            $c = 'info';
        } else {
            $t = 'recusa';
            $c = 'danger';
        }
        foreach ($termos as $k => $v) {
            $termos[$k]['vs'] = '<button class="btn btn-outline-'.$c.'" onclick="'.$t.'('.$v['id_protocolo'].', ' . $v['fk_id_pessoa'] . ')">Visualizar</button>';
            $termos[$k]['n_pessoa'] = $v['n_aluno'];
        }
        $form['array'] = $termos;
        $form['fields'] = [
            'Protocolo' => 'id_protocolo',
            'Aluno' => 'n_pessoa',
            'Atualização' => 'dt_update',
            'Situação' => 'n_status',
            '||1' => 'vs',
        ];

        return $form;
    }

    public static function isProfessor() {
        if (user::session('id_nivel') == 24) {
            $is = toolErp::id_pessoa();
        } else {
            $is = 0;
        }
        return $is;
    }

    public function getTurmasProf($id_turma=null) {
        $sql = "SELECT tAEE.id_turma, tAEE.n_turma, i.n_inst "
                . " FROM ge_turmas tAEE "
                . " JOIN instancia i ON i.id_inst = tAEE.fk_id_inst "
                . " WHERE tAEE.id_turma IN(". self::getTurmasProfSQL($id_turma) .") ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        //$r = [];
        // if (!empty($array)) {
        //     foreach ($array as $v){
        //         $r[$v['id_turma']]= $v['n_turma'];//turma AEE
        //     }
        // }
        return $array;
    }

    public static function getTurmasProfSQL($id_turma=null) {
        $id_pessoa = toolErp::id_pessoa();
        $sqlAux = '';
        if (!empty($id_turma)) {
            $sqlAux .= " AND tAEE.id_turma = $id_turma ";
        }
        $sql = "SELECT tAEE.id_turma "
                . " FROM ge_aloca_prof a "
                . " JOIN ge_turmas tAEE on tAEE.id_turma = a.fk_id_turma AND tAEE.fk_id_ciclo = 32 "
                . " JOIN instancia i ON i.id_inst = tAEE.fk_id_inst "
                . " JOIN ge_funcionario f on f.rm = a.rm"
                . " WHERE f.fk_id_pessoa = ".$id_pessoa . $sqlAux;
        return $sql;
    }

    public function alunoAEE($id_pessoa) {
        $sql = "SELECT tax.fk_id_pessoa FROM ge_turma_aluno tax"
            . " JOIN ge_turmas tx ON tx.id_turma = tax.fk_id_turma AND tx.fk_id_ciclo = 32 "
            . " JOIN ge_periodo_letivo plx ON plx.id_pl = tx.fk_id_pl AND plx.at_pl = 1 "
            . " WHERE tax.fk_id_pessoa = $id_pessoa AND (tax.fk_id_tas = 0 OR tax.fk_id_tas IS NULL) ";
        $query = pdoSis::getInstance()->query($sql);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($result)) {
            $alunoAEE = 1;
        }else{
            $alunoAEE = null;
        }
        return $alunoAEE;
    }

    public function termoAssinadoGet() {
        $id_protocolo = @$_REQUEST['id_protocolo'];
        $recusa = @$_REQUEST['recusa'];
        if ($recusa == 1) {
            $termo = $this->getTermoRecusa($id_protocolo);
        }else{
            $termo = $this->getTermoAdesao($id_protocolo);
        }
        ob_clean();
        if (!empty($termo['assinatura'])) {
            echo '1';
        }else{
            echo '0';
        }
        exit();
    }

    public function protTermoAssinar() {
        $ins = @$_POST[1];
        if (!empty($ins)) {

            $id = assinaturaDigital::salvarAssinatura();
            if (empty($id)) {
                toolErp::alertModal('Falha ao salvar a assinatura digital');
                return;
            }
            $ins['fk_id_assinatura'] = $id;

            //verifica se já existe um termo
            $tp = $this->getTermo($ins['fk_id_protocolo'], $ins['tipo']);
            if (!empty($tp)){
                $ins['id_protocolo_termo'] = $tp['id_protocolo_termo'];
            }
            
            $this->db->ireplace('protocolo_termo', $ins);

            $insContact = [
                'fk_id_protocolo' => $ins['fk_id_protocolo'],
                'fk_id_pessoa' => $ins['fk_id_pessoa_aluno'],
                'fk_id_pessoa_cadastra' => $ins['fk_id_pessoa'],
                'fk_id_protocolo_status' => $_POST['status'],
            ];

            $this->db->ireplace('protocolo_contactar', $insContact, 1);

            if ($ins['tipo'] == 'R') {
                // TODO: o cadastro só será alterado se não estiver deferido
                $a['fk_id_status'] = 9;
                $this->db->ireplace('protocolo_cadastro', $a, 1);

                $b['fk_id_pessoa'] = $ins['fk_id_pessoa'];
                $b['fk_id_protocolo'] = $ins['fk_id_protocolo'];
                $b['fk_id_proto_status'] = $a['fk_id_status'];
                $this->db->ireplace('protocolo_status_pessoa', $b, 1);
            }

        }else{
           toolErp::alertModal('Nenhuma Informação enviada'); 
        }
    }

    public function getTurmaSelect($turmas, $id_turma) {
        if (empty($id_turma)) {
           foreach ($turmas as $v) {
                $id_turma = $v["id_turma"];
                if ($id_turma) {
                    break;
                }
            } 
        }
        return $id_turma;
    }
}
