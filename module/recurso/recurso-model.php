<?php

class recursoModel extends MainModel {

    public $db;
    public $mensagens = "";

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
        if (empty($_SESSION['userdata']['id_cate'])) {
            if ( !empty($controller->title) && $controller->title != "Home" ) {
                if ( !empty($controller->parametros->acao) || $controller->parametros->acao != "index") {
                    header('Location: '.HOME_URI.'/recurso/index');
                }
            }
        }
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

        if ($this->db->tokenCheck('alocar')) {
            $this->alocar();
        } elseif ($this->db->tokenCheck('emprestar', true)) {
            $this->emprestar();
        }elseif ($this->db->tokenCheck('ocorrenciaSalvar',true)) {
            $this->ocorrenciaSalvar();
        }elseif ($this->db->tokenCheck('emprestimoFim',true)) {
            $this->emprestimoFim();
        }elseif ($this->db->tokenCheck('manutencaoSalvar',true)) {
            $this->manutencaoSalvar();
        }elseif ($this->db->tokenCheck('delCate',true)) {
            $this->delCate();
        }elseif ($this->db->tokenCheck('delLocal',true)) {
            $this->delLocal();
        }
    }

    // Crie seus próprios métodos daqui em diante

    public function delLocal() {
        $id_local = filter_input(INPUT_POST, 'id_local', FILTER_SANITIZE_NUMBER_INT);
        $serial = sql::get('recurso_serial', 'n_serial', ['fk_id_local' => $id_local]);

        if (!empty($serial)) {
             toolErp::alert("Não foi possível excluir!");
             foreach ($serial as $v) {
                 $serials = (!empty($serials) ? $serials.", ".$v['n_serial'] : $v['n_serial']);
             }
             echo '<div class="alert alert-danger">
                Antes de excuir esse local, é preciso trocar o local dos seguintes Números de Série: '.$serials.'
             </div> ';    
        }else{
            $nome = sql::get('recurso_local', 'n_local', ['id_local' => $id_local], 'fetch');
            $this->db->delete('recurso_local','id_local', $id_local);  
            if (!empty($nome)) {
                log::logSet("Apagou o Local " . $nome['n_local']); 
            }
        }
    }

    public function delCate() {
        $id_cate = filter_input(INPUT_POST, 'id_cate', FILTER_SANITIZE_NUMBER_INT);
        $equipamento = sql::get('recurso_equipamento', 'n_equipamento', ['fk_id_cate' => $id_cate]);

        if (!empty($equipamento)) {
             toolErp::alert("Não foi possível excluir!");
             foreach ($equipamento as $v) {
                 $equipamentos = (!empty($equipamentos) ? $equipamentos.", ".$v['n_equipamento'] : $v['n_equipamento']);
             }
             echo '<div class="alert alert-danger">
                Antes de excuir essa categoria, é preciso trocar a categoria dos seguintes modelos: '.$equipamentos.'
             </div> ';    
        }else{
            $nome = sql::get('recurso_cate_equipamento', 'n_cate', ['id_cate' => $id_cate], 'fetch');
            $this->db->delete('recurso_cate_equipamento','id_cate', $id_cate);
            if (!empty($nome)) {
                log::logSet("Apagou a Categoria " . $nome['n_cate']);  
            }
        }   
    }

    public function manutencaoSalvar() {
        $id_move = filter_input(INPUT_POST, 'id_move', FILTER_SANITIZE_NUMBER_INT);
        $n_doc = filter_input(INPUT_POST, 'n_doc', FILTER_SANITIZE_STRING);
        $id_inst = toolErp::id_inst();
        $data = date("Y-m-d H:i:s");
        $ins = @$_POST[1];
        $ins['fk_id_inst'] = 13;
        $ins['dt_update'] = $data;
        if (!empty($n_doc)) {
            $file = ABSPATH . '/pub/labDoc/';
            $up = new upload($file, $id_move, 21000000000000000000000);
            $end = $up->up();
            if ($end) {
                $this->db->ireplace('recurso_doc', ['fk_id_move' => $id_move, 'end' => $end, 'n_doc'=>$n_doc], 1);
            }
        }
        if ($ins['fk_id_situacao'] == 3){
            $ins['id_move'] = $id_move;
            $ins['dt_update'] = $data;
            $ins['fk_id_local'] = -1;
        }else{
            if ($ins['fk_id_situacao'] == 8) { // se situacao for REPARADO, atualiza o serial para REGULAR
                $id_situacao = 1;
            }else{
                $id_situacao = $ins['fk_id_situacao'];
            }
            $unico_id = uniqid();
            $ins['unico_id'] = $unico_id;
            $insSerial = [
                'id_serial'=> $ins['fk_id_serial'],
                'fk_id_situacao'=> $id_situacao,
                'fk_id_inst'=> 13,
                'fk_id_local'=> -1,
                'dt_update'=> $data,
                'unico_id'=> @$unico_id,
            ];
            $nome = sql::get('recurso_serial', 'n_serial', ['id_serial' => $ins['fk_id_serial']], 'fetch');
            $situacao = sql::get('recurso_situacao', 'n_situacao', ['id_situacao' => $id_situacao], 'fetch');
            $this->db->ireplace('recurso_serial', $insSerial,1);
            if (!empty($nome && !empty($situacao))) {
               log::logSet("Alterou a situação do N/S " . $nome['n_serial']." para ".$situacao['n_situacao']);
            }
        }
        $this->db->ireplace('recurso_movimentacao', $ins);
    }

    public function emprestimoFim() {
        $itens = @$_POST['itens'];
        $id_equipamento = filter_input(INPUT_POST, 'id_equipamento', FILTER_SANITIZE_NUMBER_INT);
        $professor = filter_input(INPUT_POST, 'professor', FILTER_SANITIZE_NUMBER_INT);
        $comodato = filter_input(INPUT_POST, 'comodato', FILTER_SANITIZE_NUMBER_INT);
        $fk_id_inst_devolve_geral = filter_input(INPUT_POST, 'fk_id_inst_devolve_geral', FILTER_SANITIZE_NUMBER_INT);
        $fk_id_inst_devolve_prof = filter_input(INPUT_POST, 'fk_id_inst_devolve_prof', FILTER_SANITIZE_NUMBER_INT);
        $id_inst = toolErp::id_inst();
        $data = date("Y-m-d H:i:s");
        $ins = @$_POST[1];
        $ins['dt_devolucao'] = $this->dataDevolucao($ins['fk_id_situacao']);
        $ins['dt_update'] = $data;
        $texto = "";
        $gerente = $this->gerente();

        if (empty($gerente)) {
        
            if ($comodato == 1) {
                if ( $fk_id_inst_devolve_prof <> -1 && $fk_id_inst_devolve_prof <> $id_inst) {// -1 = pega e devolve na mesma instancia
                    $instancia = sql::get('instancia', 'n_inst', ['id_inst' => $fk_id_inst_devolve_prof ], 'fetch');
                    if (!empty($instancia['n_inst'])) {
                        $texto = " Este equipamento deve ser devolvido no local: ".$instancia['n_inst'];
                    }
                    toolErp::alert('Não foi possível finalizar.'.$texto );
                    return;
                }  
            }else{
                if ( $fk_id_inst_devolve_geral <> -1 && $fk_id_inst_devolve_geral <> $id_inst) {
                    $instancia = sql::get('instancia', 'n_inst', ['id_inst' => $fk_id_inst_devolve_geral ], 'fetch');
                    if (!empty($instancia['n_inst'])) {
                        $texto = " Este equipamento deve ser devolvido no local: ".$instancia['n_inst'];
                    }
                    toolErp::alert('Não foi possível finalizar.'.$texto );
                    return;
                }

            }
        }

        $insSerial = [
            'id_serial'=> $ins['fk_id_serial'],
            'dt_update'=> $data,
            'fk_id_situacao'=> $ins['fk_id_situacao'],
            'fk_id_local'=> -1,
            'fk_id_pessoa_aloca' => toolErp::id_pessoa(),
        ];
        $nome = sql::get('recurso_serial', 'n_serial', ['id_serial' => $ins['fk_id_serial']], 'fetch');
        $situacao = sql::get('recurso_situacao', 'n_situacao', ['id_situacao' => $ins['fk_id_situacao']], 'fetch');
        $this->db->ireplace('recurso_serial', $insSerial);
        $this->db->ireplace('recurso_movimentacao', $ins,1);
        $this->itensInsert($itens,$ins['id_move'],$ins['fk_id_situacao']);
        if (!empty($nome && !empty($situacao))) {
           log::logSet("Alterou a situação do N/S " . $nome['n_serial']." para ".$situacao['n_situacao']);
        }
    }

    public function ocorrenciaSalvar() {
        $itens = @$_POST['itens'];
        $fk_id_inst_devolve_geral = filter_input(INPUT_POST, 'fk_id_inst_devolve_geral', FILTER_SANITIZE_NUMBER_INT);
        $fk_id_inst_devolve_prof = filter_input(INPUT_POST, 'fk_id_inst_devolve_prof', FILTER_SANITIZE_NUMBER_INT);
        $id_inst = toolErp::id_inst();
        $professor = filter_input(INPUT_POST, 'professor', FILTER_SANITIZE_NUMBER_INT);
        $comodato = filter_input(INPUT_POST, 'comodato', FILTER_SANITIZE_NUMBER_INT);
        $n_doc = filter_input(INPUT_POST, 'n_doc', FILTER_SANITIZE_STRING);
        $unico_id = uniqid(); //relacionar movimentação de entrega com movimentação de manutenção
        $data = date("Y-m-d H:i:s");
        $ids_sem_manutencao = [1,2,6,4]; //fk_id_situacao
        $ins = @$_POST[1];
        $ins['unico_id'] = $unico_id;
        $ins['dt_devolucao'] = $this->dataDevolucao($ins['fk_id_situacao']);
        $ins['dt_update'] = $data;
        $texto = "";
        $gerente = $this->gerente();

        if (empty($gerente)) {
            if (!empty($fk_id_inst_devolve_geral) && !empty($fk_id_inst_devolve_prof)) {
                if ($comodato == 1) {
                    if ( $fk_id_inst_devolve_prof <> -1 && $fk_id_inst_devolve_prof <> $id_inst) {// -1 = pega e devolve na mesma instancia
                        $instancia = sql::get('instancia', 'n_inst', ['id_inst' => $fk_id_inst_devolve_prof ], 'fetch');
                        if (!empty($instancia['n_inst'])) {
                            $texto = " Este equipamento deve ser devolvido no local: ".$instancia['n_inst'];
                        }
                        toolErp::alert('Não foi possível finalizar.'.$texto );
                        return;
                    }  
                }else{
                    if ( $fk_id_inst_devolve_geral <> -1 && $fk_id_inst_devolve_geral <> $id_inst) {
                        $instancia = sql::get('instancia', 'n_inst', ['id_inst' => $fk_id_inst_devolve_geral ], 'fetch');
                        if (!empty($instancia['n_inst'])) {
                            $texto = " Este equipamento deve ser devolvido no local: ".$instancia['n_inst'];
                        }
                        toolErp::alert('Não foi possível finalizar.'.$texto );
                        return;
                    }
                } 
            }
        }
        $nome = sql::get('recurso_serial', 'n_serial', ['id_serial' => $ins['fk_id_serial']], 'fetch');
        $situacao = sql::get('recurso_situacao', 'n_situacao', ['id_situacao' => $ins['fk_id_situacao']], 'fetch');
        $id_move = $this->db->ireplace('recurso_movimentacao', $ins, 1);//registro de ocorrencia
        log::logSet("Ocorrência: alterou a situação do N/S " . $nome['n_serial']." para ".$situacao['n_situacao']);
        $this->itensInsert($itens,$id_move,$ins['fk_id_situacao']);
        if (!empty($n_doc)) {
            $file = ABSPATH . '/pub/labDoc/';
            $up = new upload($file, $id_move, 21000000000000000000000);
            $end = $up->up();
            if ($end) {
                $this->db->ireplace('recurso_doc', ['fk_id_move' => $id_move, 'end' => $end, 'n_doc'=>$n_doc, 'unico_id' => $unico_id], 1);
            }
        }
        if (in_array($ins['fk_id_situacao'], $ids_sem_manutencao) || $gerente <> 1){
            $id_situacao = $ins['fk_id_situacao'];   
        }else{
            $id_situacao = 3;
            $id_inst = 13;
            $insSerial['fk_id_inst'] = 13;
            $insManu = [
                'obs'=> $ins['obs'],
                'fk_id_serial'=> $ins['fk_id_serial'],
                'fk_id_situacao'=> 3,
                'fk_id_inst'=> 13,
                'fk_id_local'=> -1,
                'dt_update'=> $data,
                'fk_id_pessoa_aloca' => toolErp::id_pessoa(),
                'unico_id' => $unico_id,
            ];
            $this->db->ireplace('recurso_movimentacao', $insManu,1);//registro de manutenção
            log::logSet("Alterou a situação do N/S " . $nome['n_serial']." para Em Manutenção");
        }
        $insSerial['id_serial'] = $ins['fk_id_serial'];
        $insSerial['fk_id_situacao'] = $id_situacao;
        $insSerial['fk_id_local'] = -1;
        $insSerial['fk_id_pessoa_aloca'] = toolErp::id_pessoa();
        $insSerial['unico_id'] = $unico_id;
        $this->db->ireplace('recurso_serial', $insSerial);//atualiza o serial
    }

    public function emprestar() {
        $ins = @$_POST[1];
        $itens = @$_POST['itens'];
        $ano_atual = date("Y");
        $id_equipamento = filter_input(INPUT_POST, 'id_equipamento', FILTER_SANITIZE_NUMBER_INT);
        if (!empty($ins)) {
            if (!empty($id_equipamento)) {
                $equipamento = sql::get('recurso_equipamento', 'fk_id_inst_devolve_prof,fk_id_inst_devolve_geral,prazo_max', ['id_equipamento' => $id_equipamento], 'fetch');
                $data = new DateTime($ins['dt_inicio']);
                $prazo_max = "P".$equipamento['prazo_max']."M";
                $data->add(new DateInterval($prazo_max));
                $ano_fim = new DateTime($ins['dt_fim']);
                $ano_fim = $ano_fim->format('Y');
                if ($ins['comodato']==0) {
                    if (strtotime($ins['dt_fim'])>strtotime($data->format('Y-m-d')) || $ano_fim > $ano_atual) {
                       tool::alert('Este equipamento deve ser emprestado por no máximo '.$equipamento['prazo_max'].' Meses, não podendo ultrapassar o ano corrente.');
                       return; 
                         
                    }
                }
                if (!empty($equipamento)) {
                    if (!empty($ins['comodato']==1)) {
                        if ($equipamento['fk_id_inst_devolve_prof'] <> -1) {
                            $local_inventario = $equipamento['fk_id_inst_devolve_prof'];
                         }else{
                            $local_inventario = $ins['fk_id_inst'];
                         }
                    }else{
                        if ($equipamento['fk_id_inst_devolve_geral'] <> -1) {
                            $local_inventario = $equipamento['fk_id_inst_devolve_geral'];
                         }else{
                            $local_inventario = $ins['fk_id_inst'];
                         }
                    }   
                }  
                $nome = sql::get('recurso_serial', 'n_serial', ['id_serial' => $ins['fk_id_serial']], 'fetch');
                $id_movi = $this->db->ireplace('recurso_movimentacao', $ins);
                log::logSet("Emprestou N/S " . $nome['n_serial']." para ".toolErp::n_pessoa($ins['fk_id_pessoa_emprest'])."-".$ins['fk_id_pessoa_emprest']);
                $this->itensInsert($itens,$id_movi,2);
                $insSerial = [
                    'id_serial'=> $ins['fk_id_serial'],
                    'fk_id_situacao'=> 2,
                    'fk_id_local'=> -1,
                    'fk_id_inst'=> $local_inventario,
                    'fk_id_pessoa_aloca' => toolErp::id_pessoa(),
                ];
                $this->db->ireplace('recurso_serial', $insSerial,1);
            }
        }

    }

    public function dataDevolucao($id_situacao){
        if ($id_situacao) {
            $data = null; 
        }else{
            $data = date("Y-m-d H:i:s");   
        }
        return $data;
    }

    public function itensInsert($itens,$id_movi,$id_situacao) {
        if (!empty($itens) && !empty($id_movi)) {
            foreach ($itens as $k => $v) {
                if ($v == 1) {
                    $insItens = [
                        'fk_id_movi'=> $id_movi,
                        'fk_id_item'=> $k,
                        'fk_id_situacao'=> $id_situacao,
                    ];
                    $this->db->ireplace('recurso_movi_item', $insItens,1);  
                }
            }  
        } 
    }

    public function alocar() {
        $msg = 0;
        foreach (@$_POST[1]['serial'] as $v) {
            $ins['fk_id_local'] = @$_POST['fk_id_local'];
            $ins['fk_id_pessoa_aloca'] = @$_POST['fk_id_pessoa_aloca'];
            $ins['dt_update'] = @$_POST['dt_update'];
            $ins['id_serial'] = $v;
            $msg = $msg+1;
            $id = $this->db->ireplace('recurso_serial', $ins, 1);
            if ($id) {
                $this->mensagens = $msg;
            }
        }
    }

    public function escolasOpt() {
        $sql = "SELECT id_inst, n_inst FROM instancia "
                . " WHERE id_inst in ( "
                . " SELECT DISTINCT `fk_id_inst` FROM `recurso_serial` rs)";
        $query = pdoSis::getInstance()->query($sql);
        $esc = null;
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $esc[$v['id_inst']] = $v['n_inst'];
        }

        return $esc;
    }

    public function emprestimoGet($id_inst, $search = null,$emprestado=null, $conta = null, $limit=null) {
        $sqlAuxP = '';
        $sqlAuxWhere = '';
        $getProfessor = 0;
        if (!empty($search)){
            $sqlAuxP .= " AND (p.id_pessoa = '$search' OR p.n_pessoa LIKE '$search%' OR p.cpf = '$search' OR p.emailgoogle LIKE '$search%') ";
        }
        if (!empty($emprestado)) {
            if ($emprestado == 2) {
                $emprestado = " AND rm.fk_id_situacao = 2";
            }else{
                $emprestado = " AND rm.fk_id_situacao <> 2 AND rm.comodato IS NOT NULL";
            }
        }else{
           $emprestado = " AND rm.fk_id_situacao = 2"; 
        }
        //exibe os emprestimos dos professores que não sao exibidos na SQL acima
        //pois o equipamento fica na secretaria
        /*
        escola
            - normal (devolve)
            - professores da escola na secretaria
        gerente - secretaria
            - normal (devolve)
        gerente - escola
            - normal (devolve)
            - professores da escola na secretaria (devolve)

        */
        $idS_inst = $id_inst;
        if ( !(in_array(user::session('id_nivel'), [10]) && $id_inst == 13) ) {
            $sqlAuxWhere .= " AND IF(rs.fk_id_inst = 13, f.fk_id_inst = $id_inst AND rm.comodato = 1, 1=1) ";
            $idS_inst = "$id_inst, 13";
        }
        if ( !(in_array(user::session('id_nivel'), [10]))){
            $Movi_id_inst = "AND rm.fk_id_inst = $id_inst";
        }else{
            $Movi_id_inst = '';
        }
        if ($conta==1) {
            $fields = " count(rm.id_move) as ct ";
        }else{
            $fields = " re.prazo_max, rm.comodato, rs.id_serial, rm.id_move, rm.professor, p.emailgoogle,p.n_pessoa, p.sexo, p.id_pessoa, re.n_equipamento, rs.n_serial, rm.id_move, rm.dt_inicio, rm.dt_fim, f.rm, t.n_turma ";
        }

        $sql = "SELECT "
            . $fields
            . " FROM recurso_movimentacao rm "
            . " JOIN recurso_serial rs ON rs.id_serial = rm.fk_id_serial  "
            . " JOIN recurso_equipamento re ON re.id_equipamento = rs.fk_id_equipamento "
            . " JOIN pessoa p ON p.id_pessoa = rm.fk_id_pessoa_emprest "
            . $sqlAuxP
            . " LEFT JOIN ge_funcionario f on f.fk_id_pessoa = rm.fk_id_pessoa_emprest AND f.rm = rm.rm"
            . " LEFT JOIN ge_turmas t ON t.id_turma = ( "
            . "     SELECT t.id_turma FROM ge_turma_aluno ta "
            . "     JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
            . "     JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
            . "     WHERE ta.fk_id_pessoa = p.id_pessoa AND ta.fk_id_tas = 0 LIMIT 1 "
            . " ) "
            . " WHERE rs.fk_id_inst IN($idS_inst)  "
            . $Movi_id_inst
            . $emprestado
            . " ORDER BY rm.dt_update DESC "
            . formErp::limit($limit);
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($conta<>1) {
            $alu = [];
            if ($array) {
                foreach ($array as $v) {
                    $itens = $this->itensGet(null,null,$v['id_move']);
                    $v['itens'] = $itens;
                    $v['devolver'] = (!in_array(user::session('id_nivel'), [10]) && $v['comodato'] == 1) ? false : true;
                    $alu[] = $v;
                }
            }
        }else{
            $alu = $array;
        }
        return $alu;
    }

    public function alunoEscola($id_inst) {
        $sql = " SELECT "
                . " p.n_pessoa, p.id_pessoa, p.sexo, t.n_turma "
                . " FROM pessoa p "
                . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa AND ta.fk_id_tas = 0"
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma and t.fk_id_inst = $id_inst "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1 "
                . " ORDER by p.n_pessoa ";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $alu[$v['id_pessoa']] = $v['n_pessoa'] . ' (' . $v['id_pessoa'] . ') - ' . $v['n_turma'];
        }

        return @$alu;
    }

    public function verificaProf($id_pessoa,$getRM=null){
        $prof = 0;
        $sql = "SELECT "
                . " f.rm "
                . " FROM ge_funcionario f "
                . " JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa and f.funcao like 'prof%' "
                . " WHERE `id_pessoa` = $id_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if (!empty($array)) {
            if (!empty($array['rm'])) {
                $prof = 1;
                if (!empty($getRM)) {
                    $prof = $array['rm']; 
                }
            }
        }
        return $prof;
    }

    public function funcionarios($id_inst,$gerente = null) {
        $WHERE = "";
        // if ($gerente <> 1) {
        //     $WHERE =  " AND `fk_id_inst` = $id_inst ";
        // }
        $sql = "SELECT "
                . " p.n_pessoa, p.id_pessoa, f.rm "
                . " FROM ge_funcionario f "
                . " JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa "
                . " WHERE 1 = 1 "
                . $WHERE
                . " order by p.n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $prof[$v['id_pessoa']] = $v['n_pessoa'] . ' (' . $v['rm'] . ') - Funcionário';
        }
        return @$prof;
    }

    public function objetoEscola($id_inst, $id_equipamento = null) {
        $WHEREid_equipamento = "";
        if (!empty($id_equipamento)) {
            $WHEREid_equipamento = " and fk_id_equipamento = $id_equipamento ";
        }
        $sql = "SELECT rs.id_serial, n_serial FROM `recurso_serial` rs "
                . " JOIN recurso_equipamento re ON re.id_equipamento = rs.fk_id_equipamento "
                . " WHERE rs.`fk_id_inst` = $id_inst AND fk_id_situacao = 1 AND re.empresta = 1"
                . $WHEREid_equipamento
                . " ORDER BY `n_serial` ASC";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($array)) {
            foreach ($array as $v) {
                $objetos[$v['id_serial']] = $v['n_serial'];
            }

            return $objetos;
        } else {
            return;
        }
    }

    public function objetoGet($id_inst, $id_equipamento = null, $n_serial = null) {
        $sqlAux = '';
        if (!empty($id_equipamento)) {
           $sqlAux .= " AND rs.`fk_id_equipamento` = $id_equipamento ";
        }
        if (!empty($id_inst)) {
            $sqlAux .= " AND rs.`fk_id_inst` = $id_inst ";
        }
        if (!empty($n_serial)){
            $sqlAux .= " AND rs.n_serial LIKE '$n_serial%' ";
        }
        $sql = "SELECT rs.id_serial, rs.n_serial, n_equipamento FROM `recurso_serial` rs "
                . " JOIN recurso_equipamento re ON re.id_equipamento = rs.fk_id_equipamento "
                . " WHERE 1=1"
                . $sqlAux
                . " ORDER BY rs.`n_serial` ASC";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($array)) {
            foreach ($array as $v) {
                $objetos[$v['id_serial']] = $v['n_serial'];
            }

            return $objetos;
        } else {
            return;
        }
    }

    public function ocorrenciasGet($id_inst, $id_equipamento = null) {
        $WHEREid_inst = "";
        if (!empty($id_inst)) {
            $WHEREid_inst = " and rm.`fk_id_inst` = $id_inst ";
        }
        $sql = "SELECT n_equipamento,id_serial,id_situacao,n_serial,rs.unico_id,rm.id_move,rm.dt_update,rst.n_situacao FROM `recurso_movimentacao` rm "
                . " LEFT JOIN recurso_serial rs ON rm.fk_id_serial = rs.id_serial "
                . " LEFT JOIN recurso_equipamento re ON re.id_equipamento = rs.fk_id_equipamento "
                . " LEFT JOIN recurso_situacao rst ON rm.fk_id_situacao = rst.id_situacao "
                . " WHERE rm.fk_id_situacao IN (4,6,7)"
                . $WHEREid_inst
                . " ORDER BY `id_move` DESC";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function equipamentoEscola($id_inst) {

        $sql = "SELECT re.id_equipamento, re.n_equipamento, prazo_max, empresta_mais, fk_id_inst_devolve_prof, fk_id_inst_devolve_geral FROM `recurso_equipamento` re "
                . " JOIN recurso_serial rs ON rs.fk_id_equipamento = re.id_equipamento AND rs.fk_id_inst = $id_inst"
                . " WHERE re.at_equipamento = 1 AND re.empresta = 1"
                . " ORDER BY `n_equipamento` ASC";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($array)) {
            foreach ($array as $v) {
                $objetos[$v['id_equipamento']] = $v['n_equipamento'];
            }

            return $objetos;
        } else {
            return;
        }
    }

    public function getEmprestimoPessoa($id_pessoa,$id_equipamento) {

        $sql = " SELECT * FROM ("
                    . " SELECT rs.n_serial, i.n_inst,rm.dt_inicio,rm.fk_id_situacao FROM `recurso_movimentacao` rm "
                    . " JOIN recurso_serial rs ON rs.id_serial = rm.fk_id_serial "
                    . " LEFT JOIN recurso_equipamento re ON re.id_equipamento = rs.fk_id_equipamento "
                    . " LEFT JOIN instancia i ON i.id_inst = rm.fk_id_inst "
                    . " WHERE rm.`fk_id_pessoa_emprest` = $id_pessoa AND rs.fk_id_equipamento =".$id_equipamento
                    . " ORDER BY rm.id_move DESC LIMIT 1 "
                    . " ) ultima_movimentacao "
                    . " WHERE ultima_movimentacao.fk_id_situacao = 2 ";
        $query = pdoSis::getInstance()->query($sql);
        $emprestado = $query->fetchAll(PDO::FETCH_ASSOC);
        return $emprestado;
    }

    public function historicoGet($id_pessoa= null,$id_serial = null) {
        if (!empty($id_serial)) {
            $sql = "SELECT "
                    . " i.n_inst,rm.dt_update, rm.comodato, re.n_equipamento, rst.n_situacao, re.prazo_max, p.emailgoogle,p.n_pessoa, p.sexo, p.id_pessoa, re.n_equipamento, rs.n_serial, rm.id_move, rm.dt_inicio, rm.dt_fim, t.n_turma, rm.dt_devolucao, rm.fk_id_situacao  "
                    . " FROM recurso_movimentacao rm "
                    . " JOIN recurso_serial rs ON rs.id_serial = rm.fk_id_serial "
                    . " JOIN recurso_situacao rst ON rst.id_situacao = rm.fk_id_situacao "
                    . " JOIN recurso_equipamento re ON re.id_equipamento = rs.fk_id_equipamento "
                    . " LEFT JOIN pessoa p ON p.id_pessoa = rm.fk_id_pessoa_emprest "
                    . " LEFT JOIN ge_turmas t ON t.id_turma = ( "
                    . "     SELECT t.id_turma FROM ge_turma_aluno ta "
                    . "     JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
                    . "     JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                    . "     WHERE ta.fk_id_pessoa = p.id_pessoa AND ta.fk_id_tas = 0 LIMIT 1 "
                    . " ) "
                    . " LEFT JOIN instancia i on i.id_inst = rm.fk_id_inst "
                    . " WHERE rm.fk_id_serial = $id_serial"
                    . " ORDER BY rm.dt_update DESC, rm.id_move DESC ";

        }else{
            $sql = "SELECT "
                    . " i.n_inst,rm.dt_update,rm.comodato, re.n_equipamento, rst.n_situacao, re.prazo_max, p.emailgoogle,p.n_pessoa, p.sexo, p.id_pessoa, re.n_equipamento, rs.n_serial, rm.id_move, rm.dt_inicio, rm.dt_fim, t.n_turma, rm.dt_devolucao, rm.fk_id_situacao "
                    . " FROM recurso_movimentacao rm "
                    . " JOIN recurso_serial rs ON rs.id_serial = rm.fk_id_serial "
                    . " JOIN recurso_situacao rst ON rst.id_situacao = rm.fk_id_situacao "
                    . " JOIN recurso_equipamento re ON re.id_equipamento = rs.fk_id_equipamento "
                    . " JOIN pessoa p ON p.id_pessoa = rm.fk_id_pessoa_emprest "
                    . " LEFT JOIN ge_turmas t ON t.id_turma = ( "
                    . "     SELECT t.id_turma FROM ge_turma_aluno ta "
                    . "     JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
                    . "     JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                    . "     WHERE ta.fk_id_pessoa = p.id_pessoa AND ta.fk_id_tas = 0 LIMIT 1 "
                    . " ) "
                    . " LEFT JOIN instancia i on i.id_inst = rm.fk_id_inst "
                    . " WHERE rm.fk_id_pessoa_emprest = $id_pessoa"
                    . " ORDER BY rm.dt_update DESC , rm.id_move DESC";
        }
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $form = [];
        if (!empty($array)) {
            foreach ($array as $k => $v) {
                $devolveu = "";
                if (!empty($v['id_move'])) {
                    if (!empty($v['n_pessoa'])) {
                       if (empty($v['n_turma'])) {
                            $array[$k]['nome'] = $v['n_pessoa'].' - Funcionário';
                        }else{
                            $array[$k]['nome'] = $v['n_pessoa'].' - '.$v['n_turma'];
                        }
                        if ($v['comodato'] == 0) {
                            if (!empty($v['dt_fim'])) {
                                $array[$k]['periodo'] = dataErp::converteBr($v['dt_inicio']).' a '.dataErp::converteBr($v['dt_fim']);
                            }   
                        }else{
                            $array[$k]['periodo'] = "Comodato";
                        } 
                    }
                    if (!empty($v['dt_inicio']) && ($v['fk_id_situacao']<>2 && $v['fk_id_situacao']<>3)) {
                        $array[$k]['data'] = dataErp::converteBr($v['dt_inicio']).' a '.dataErp::converteBr($v['dt_update']);
                        if ($v['fk_id_situacao'] == 1 || $v['fk_id_situacao'] == 7) {
                            $devolveu = 'Devolvido / ';
                        }
                    }else{
                        $array[$k]['data'] = $v['dt_update'];
                    }
                    $array[$k]['situacao'] = $devolveu.$v['n_situacao'];
                } else {
                    unset($array[$k]); 
                }
            }
            
            $form['array'] = $array;
            $form['fields'] = [
                'Eprestado para' => 'nome',
                'Equipamento' => 'n_equipamento',
                'Número de Série' => 'n_serial',
                'Situação' => 'situacao',
                'Período do Empréstimo' => 'periodo',
                'Data' => 'data',
                'E-mail' => 'emailgoogle',
                'Local' => 'n_inst',
            ];
        }

        return $form;
    }

    public function movimentacaoGet($id_move) {
        $sql = "SELECT "
                . " rm.fk_id_inst, rm.comodato, rm.obs, i.n_inst, rl.n_local, rm.professor, re.fk_id_inst_devolve_prof, fk_id_inst_devolve_geral, re.id_equipamento, rst.n_situacao, re.prazo_max, p.emailgoogle,p.n_pessoa, p.sexo, p.id_pessoa, re.n_equipamento, rs.n_serial, rs.id_serial, rm.id_move, rm.dt_inicio, rm.dt_fim, t.n_turma, rm.dt_devolucao "
                . " FROM recurso_movimentacao rm "
                . " JOIN recurso_serial rs ON rs.id_serial = rm.fk_id_serial "
                . " JOIN recurso_situacao rst ON rst.id_situacao = rm.fk_id_situacao "
                . " JOIN recurso_equipamento re ON re.id_equipamento = rs.fk_id_equipamento "
                . " LEFT JOIN recurso_local rl ON rl.id_local = rs.fk_id_local "
                . " LEFT JOIN pessoa p ON p.id_pessoa = rm.fk_id_pessoa_emprest "
                . " LEFT JOIN ge_turmas t ON t.id_turma = ( "
                . "     SELECT t.id_turma FROM ge_turma_aluno ta "
                . "     JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
                . "     JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . "     WHERE ta.fk_id_pessoa = p.id_pessoa AND ta.fk_id_tas = 0 LIMIT 1 "
                . " ) "
                . " LEFT JOIN instancia i on i.id_inst = rm.fk_id_inst "
                . " WHERE rm.id_move = $id_move"
                . " ORDER BY rm.dt_inicio ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($array)) {
            $array['local_devolve'] = $this->getLocalDevolveEquipamento($array['n_inst'],$array['id_equipamento'],$array['professor'],);
            
        }
        return $array;
    }

    public function getLocalDevolveEquipamento($n_inst,$id_equipamento,$professor) {
        $local_devolve = "Verifique na Secretaria";
        if (!empty($id_equipamento)) {
            $equipamento = sql::get('recurso_equipamento', 'fk_id_inst_devolve_prof,fk_id_inst_devolve_geral', ['id_equipamento' => $id_equipamento], 'fetch');
            if (!empty($equipamento)) {
                if ($professor == 1) {
                    if ($equipamento['fk_id_inst_devolve_prof'] <> -1) {
                        $devolve = sql::get('instancia', 'n_inst', ['id_inst' => $equipamento['fk_id_inst_devolve_prof']], 'fetch');
                        $local_devolve = $devolve['n_inst'];
                     }else{
                        $local_devolve = $n_inst;
                     }
                }else{
                    if ($equipamento['fk_id_inst_devolve_geral'] <> -1) {
                        $devolve = sql::get('instancia', 'n_inst', ['id_inst' => $equipamento['fk_id_inst_devolve_geral']], 'fetch'); 
                        $local_devolve = $devolve['n_inst'];
                     }else{
                        $local_devolve = $n_inst;
                     }
                }   
            }  
        }
        return $local_devolve;
    }

    public function getEquipamento($id_inst) {
        $WHERE_id_inst = "";
        if (!empty($id_inst)) {
            $WHERE_id_inst = " WHERE rs.fk_id_inst = ".$id_inst;
        }
        $sql = " SELECT id_equipamento, n_equipamento FROM recurso_serial rs "
                . " join recurso_equipamento re on re.id_equipamento = rs.fk_id_equipamento "
                . $WHERE_id_inst
                . " ORDER BY n_equipamento ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $h = [];
        foreach ($array as $v) {
            
            $h[$v['id_equipamento']] = $v['n_equipamento'];
        }

        return $h;
    }

    public function getLocal($id_inst,$id_equipamento = null) { 
        $WHERE_equipamento = "";
        if (!empty($id_equipamento)) {
            $WHERE_equipamento = " AND rs.fk_id_equipamento = ".$id_equipamento;
            $LEFT_equipamento = "";
        }
        $sql = " SELECT distinct id_local, n_local FROM recurso_local rl "
                . " LEFT JOIN recurso_serial rs on rl.id_local = rs.fk_id_local "
                . " WHERE rl.at_local = 1 AND rl.fk_id_inst = ".$id_inst
                . $WHERE_equipamento
                . " ORDER BY n_local ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $h[-1] = "Não Alocado";
        foreach ($array as $v) {
            $h[$v['id_local']] = $v['n_local'];
        }
        return $h;
    }

    public function getSerial($id_inst,$id_equipamento = null, $id_local = null) { 
        $WHERE_equipamento = "";
        $WHERE_local = "";
        if (!empty($id_equipamento)) {
            $WHERE_equipamento = " AND rs.fk_id_equipamento = ".$id_equipamento;
        }
        if (!empty($id_local)) {
            $WHERE_local = " AND rs.fk_id_local = ".$id_local;
        }
        $sql = " SELECT  * FROM recurso_serial rs "
                . " WHERE rs.fk_id_inst = ".$id_inst
                . $WHERE_equipamento
                . $WHERE_local
                . " ORDER BY n_serial ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function sitCor() {
        return [
            0 => 'btn btn-outline-danger',
            1 => 'btn btn-success',
            2 => 'btn btn-warning',
            3 => 'btn btn-info',
            4 => 'btn btn-warning',
            6 => 'btn btn-danger',
            8 => 'btn btn-primary'
        ];
    }

    public function equipamentoGet($id_situacao=null,$id_inst=null,$id_serial=null,$id_equipamento=null,$resumo=null,$excel=null,$n_pessoa=null,$cpf=null,$rm=null,$email=null,$n_serial=null) {
        $campos =  "";
        $WHERE =  "";
        if (!empty($id_situacao)) {
            $id_situacao = " AND rs.fk_id_situacao = $id_situacao ";
        }
        if (!empty($id_equipamento)) {
            $id_equipamento = " AND rs.fk_id_equipamento = $id_equipamento ";
        }
        if (!empty($id_serial)) {
            $id_serial = " AND rs.id_serial = $id_serial ";
        }
        if (!empty($n_serial)) {
            $n_serial = " AND rs.n_serial = '$n_serial' ";
        }
        if (!empty($id_inst)) {
            $id_inst = " AND rs.fk_id_inst = $id_inst ";
        }
        if (!empty($n_pessoa)) {
            $WHERE .= " AND p.n_pessoa like '%".$n_pessoa."%' ";
        }
        if (!empty($cpf)) {
            $campos = (!empty($campos) ? $campos."cpf, " : "cpf, ");
            $WHERE .= " AND p.cpf = '".$cpf."'";
        }
        if (!empty($rm)) {
            $campos = (!empty($campos) ? $campos."rm, " : "rm, ");
            $WHERE .= " AND rm.rm = '".$rm."'";
        }
        if (!empty($email)) {
            $campos = (!empty($campos) ? $campos."emailgoogle, " : "emailgoogle, ");
            $WHERE .= " AND p.emailgoogle '".$email."'";
        }
        if (!empty($resumo)) {
            $ORDER = " ORDER BY rs.fk_id_situacao ";
        }else if(!empty($excel)){
            $ORDER = " ORDER BY i.n_inst,re.n_equipamento"; 
        }else{
            $ORDER = " ORDER BY rs.fk_id_local ";   
        }
        $sql = "SELECT "
                . $campos
                . " p.n_pessoa, rm.id_move, rst.id_situacao, p.id_pessoa, re.id_equipamento, rs.n_serial, rs.id_serial, rs.fk_id_local, rl.n_local, re.n_equipamento, rst.n_situacao, re.prazo_max, rs.fk_id_inst, i.n_inst "
                . " FROM recurso_serial rs "
                . " left JOIN recurso_movimentacao rm ON rs.id_serial = rm.fk_id_serial "
                . " JOIN recurso_situacao rst ON rst.id_situacao = rs.fk_id_situacao "
                . " JOIN recurso_equipamento re ON re.id_equipamento = rs.fk_id_equipamento "
                . " LEFT JOIN recurso_local rl ON rl.id_local = rs.fk_id_local "
                . " LEFT JOIN pessoa p ON p.id_pessoa = rm.fk_id_pessoa_emprest "
                . " LEFT JOIN instancia i on i.id_inst = rs.fk_id_inst "
                . " WHERE 1=1 "
                . $WHERE
                . $id_inst
                . $id_situacao
                . $id_equipamento
                . $id_serial
                . $n_serial
                . $ORDER;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($array)) {
            if (!empty($resumo)){
                foreach ($array as $v) {
                    $equipamentos[$v['fk_id_inst']][$v['id_serial']] = $v;
                }   
            }else{
                foreach ($array as $v) {
                    if (empty($v['fk_id_local'])) {
                        $v['fk_id_local'] = -1;
                    }

                    $equipamentos[$v['fk_id_local']][$v['id_serial']] = $v;
                }    
            }
            return $equipamentos;
            
        } else {
            return;
        }
    }

    public function itensGet($id_equipamento,$checkbox=null,$id_move=null) {
        if (!empty($id_move)) {
          $sql = " SELECT DISTINCT n_item, id_item FROM recurso_movi_item rmi "
                . " JOIN recurso_equip_item rei ON rei.id_item = rmi.fk_id_item"
                . " WHERE fk_id_movi = ".$id_move
                . " ORDER BY n_item "; 
        }else{
          $sql = " SELECT DISTINCT n_item, id_item FROM recurso_equip_item rei "
                . " WHERE rei.fk_id_equipamento = ".$id_equipamento
                . " AND at_item = 1"
                . " ORDER BY n_item ";
        }
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $itens = "";
        if (!empty($array)) {
            if (!empty($checkbox)) {
                foreach ($array as $v) {
                    $itens = $itens."<div class='row'><div class='col'>".formErp::checkbox('itens['.$v['id_item'].']', 1, $v['n_item'])."</div></div>";
                }   
            }else{
                foreach ($array as $v) {
                    $itens = (!empty($itens) ? $itens.", ".$v['n_item'] : $v['n_item']);
                }    
            }
        }else{
           $itens = "Não há itens adicionais a este equipamento."; 
        }
        return $itens;
    }

    public function dashGetEquip($id_cate) {
        if (!empty($id_cate)) {
           $id_cate = " AND re.fk_id_cate = ".$id_cate;
        }
        $sql = " SELECT rs.id_serial "
                . " FROM recurso_serial rs "
                . " JOIN recurso_equipamento re ON re.id_equipamento = rs.fk_id_equipamento "
                . " JOIN recurso_situacao rst ON rst.id_situacao = rs.fk_id_situacao "
                . " WHERE rs.fk_id_inst = 13"
                . $id_cate;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $totais = count($array);
        return $totais;
    }

    public function dashGet($id_cate=null,$id_inst=null) {

        if (!empty($id_cate)) {
           $id_cate = " AND re.fk_id_cate = ".$id_cate;
        }
        if (!empty($id_inst)) {
           $id_inst = " AND rs.fk_id_inst = ".$id_inst;
        }
        $sql = " SELECT rs.id_serial, rst.id_situacao, rs.fk_id_inst "
                . " FROM recurso_serial rs "
                . " JOIN recurso_equipamento re ON re.id_equipamento = rs.fk_id_equipamento "
                . " JOIN recurso_situacao rst ON rst.id_situacao = rs.fk_id_situacao "
                . " WHERE 1=1 "
                . $id_cate
                . $id_inst;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $totais['serial'] = count($array);
        $totais['regular'] = 0;
        $totais['emprestado'] = 0;
        $totais['manutencao'] = 0;
        $totais['extraviado'] = 0;
        $totais['inservivel'] = 0;
        $totais['n_devolvido'] = 0;
        
        foreach ($array as $v) {
            if ($v['id_situacao'] == 1) {
                $totais['regular']++;
                 $totais['regular100'] = intval(($totais['regular'] / $totais['serial']) * 100);
            }elseif($v['id_situacao'] == 2) {
                $totais['emprestado']++;
                $totais['emprestado100'] = intval(($totais['emprestado'] / $totais['serial']) * 100);
            }elseif($v['id_situacao'] == 3) {
                $totais['manutencao']++;
                $totais['manutencao100'] = intval(($totais['manutencao'] / $totais['serial']) * 100);
            }elseif($v['id_situacao'] == 4) {
                $totais['extraviado']++;
                $totais['extraviado100'] = intval(($totais['extraviado'] / $totais['serial']) * 100);
            }elseif($v['id_situacao'] == 5) {
                $totais['inservivel']++;
                $totais['inservivel100'] = intval(($totais['inservivel'] / $totais['serial']) * 100);
            }elseif($v['id_situacao'] == 6) {
                $totais['n_devolvido']++;
                $totais['n_devolvido100'] = intval(($totais['n_devolvido'] / $totais['serial']) * 100);
            }
        }

        return $totais;

    }

    public function equipamentoSelect() {
        $sql = " SELECT id_equipamento, n_equipamento "
                . " FROM recurso_equipamento  "
                . " WHERE at_equipamento = 1 AND fk_id_cate =".$_SESSION['userdata']['id_cate'];
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $equipamento = [];
        foreach ($array  as $v) {
            $equipamento[$v['id_equipamento']] = $v['n_equipamento'];
        }
        return $equipamento;
    }

    public function gerente($getInst=null,$instNull = null) {
        if (in_array(user::session('id_nivel'), [10])) {
            $result = 1;//usuario é gerente
            if (!empty($getInst)) {
                if (!empty($instNull)) {
                    $result = null;//sem filtro de instancia
                }else{
                    $result = 13;//instancia da secretaria   
                }
            }
        } else {
            $result = 0;
            if (!empty($getInst)) {
               $result = toolErp::id_inst();
            }
        }
        return $result;
    }

    public function relatGeral($par = []) {
        $tuplas = 100;
        $par['tuplas'] = $tuplas;
        $chromeCount = $this->equipamentoCount($par);

        $par['limit'] = report::pagination($tuplas, $chromeCount, $par);

        $chrome = $this->chromeRede($par);

        if (!empty($chrome)) {
            foreach ($chrome as $k => $v) {
                $chrome[$k]['ac'] = '<button class="btn btn-info" onclick="ch(' . $v['id_ch'] . ')">Acessar</button>';
            }
            $form['array'] = $chrome;
            $form['fields'] = [
                'nº de Série' => 'serial',
                'Modelo' => 'n_cm',
                'Situação' => 'n_cs',
                'Responsável' => 'n_pessoa',
                'Matrícula' => 'rm',
                'E-mail' => 'emailgoogle',
                '||1' => 'ac'
            ];
            echo '<br /><br />';
            report::simple($form);

            if (!empty($chrome)) {
                return 1;
            }
        } else {
            ?>
            <br /><br />
            <div class="alert alert-danger" style="text-align: center; width:  300px; margin: 0 auto">
                Não Encontrado
            </div>
            <?php
        }
    }

    public function equipamentoCount($par = []) {
        extract($par);
        //$where = $this->chromeRedeWhere($par);

        $sql = "SELECT count(DISTINCT(id_serial)) ct FROM recurso_serial rs "
                . " JOIN recurso_equipamento re on re.id_equipamento = rs.fk_id_equipamento "
                . " JOIN recurso_situacao rst on rst.id_situacao = rs.fk_id_situacao "
                . " LEFT JOIN recurso_movimentacao rm ON rm.id_move = (select max(id_move) from recurso_movimentacao where unico_id = rs.unico_id) "
                . " LEFT JOIN pessoa p on p.id_pessoa = rm.fk_id_pessoa_emprest "
                . " LEFT JOIN ge_funcionario f on f.fk_id_pessoa = rm.fk_id_pessoa_emprest AND f.rm = rm.rm"
                . " WHERE 1 ";
                //. $where;

        $query = pdoSis::getInstance()->query($sql);
        $count = $query->fetch(PDO::FETCH_ASSOC)['ct'];

        return $count;
    }

    public function chromeRede($par = [], $fields = null) {

        extract($par);
        if (empty($fields)) {
            $fields = " DISTINCT c.id_ch, c.serial, c.fk_id_inst, c.mac, c.carrinho, c.recadastro, c.dt_cad, "
                    . " p.n_pessoa, p.id_pessoa, p.sexo, p.cpf, p.emailgoogle, m.n_cm, s.n_cs, f.rm, mt.fk_id_ms ";
        }
        $where = $this->chromeRedeWhere($par);
        $limit = empty($limit) ? 0 : $limit;

        $sql = "SELECT "
                . $fields
                . " FROM lab_chrome c "
                . " JOIN lab_chrome_modelo m on m.id_cm = c.fk_id_cm "
                . " JOIN lab_chrome_status s on s.id_cs = c.fk_id_cs "
                . " left join lab_chrome_emprestimo e on e.fk_id_ch = c.id_ch and e.dt_fim is null "
                . " left join lab_chrome_manutencao mt on mt.fk_id_ch = c.id_ch and mt.fk_id_ms != 5"
                . " LEFT JOIN pessoa p on p.id_pessoa = e.fk_id_pessoa "
                . " LEFT JOIN ge_funcionario f on f.fk_id_pessoa = e.fk_id_pessoa "
                . " where 1 "
                . $where
                . " "
                . " ORDER BY n_pessoa"
                . " limit $limit, $tuplas ";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $cr = [];
        foreach ($array as $k => $v) {
            if (!empty($v['rm'])) {
                if (array_key_exists($v['id_ch'], $cr)) {
                    $cr[$v['id_ch']]['rm'] = $cr[$v['id_ch']]['rm'] . '/' . $v['rm'];
                } else {
                    $cr[$v['id_ch']] = $v;
                }
            } else {
                $cr[$v['id_ch']] = $v;
            }
        }
        unset($array);
        if (!empty($cr)) {
            return $cr;
        }
    }

    public function info($texto){
        $info = '<span type="button" class=" btn-outline-warning info rounded-circle" data-toggle="tooltip" data-placement="top" title="'.$texto.'">&#9432;</span>';
        return $info;
    }

    public function getTotaisSerial($equipamentos){
        $sitTotal[8] =0;
        $sitTotal[1] =0;
        $sitTotal[2] =0;
        $sitTotal[3] =0;
        $sitTotal[4] =0;
        $sitTotal[5] =0;
        $sitTotal[6] =0;
        $sitTotal[7] =0;
        foreach ($equipamentos as $num => $local) {
            foreach ($local as $n_serial => $v) {
                if (!empty($v['id_situacao'])) {
                    if ($v['id_situacao'] == 8) {
                        $sitTotal[8]++;
                    }elseif ($v['id_situacao'] == 1) {
                        $sitTotal[1]++;
                    }elseif ($v['id_situacao'] == 2) {
                        $sitTotal[2]++;
                    } elseif ($v['id_situacao'] == 3) {
                        $sitTotal[3]++;
                    }elseif ($v['id_situacao'] == 4) {
                        $sitTotal[4]++;
                    }elseif ($v['id_situacao'] == 5) {
                        $sitTotal[5]++; 
                    } elseif ($v['id_situacao'] == 6) {
                        $sitTotal[6]++; 
                    } elseif ($v['id_situacao'] == 7) {
                        $sitTotal[7]++; 
                    } 
                } else {
                    $sitTotal[0]++;
                }
            }
        }
        return $sitTotal;
    }

    public function equipamentoResumoGet($id_situacao=null,$id_inst=null,$id_serial=null,$id_equipamento=null) {
        if (!empty($id_situacao)) {
            $id_situacao = " AND rs.fk_id_situacao = $id_situacao ";
        }
        if (!empty($id_equipamento)) {
            $id_equipamento = " AND rs.fk_id_equipamento = $id_equipamento ";
        }
        if (!empty($id_serial)) {
            $id_serial = " AND rs.id_serial = $id_serial ";
        }
        if (!empty($id_inst)) {
            $id_inst = " AND rs.fk_id_inst = $id_inst ";
        }
        $sql = "SELECT "
                . " rst.id_situacao, rs.n_serial, i.n_inst, rs.id_serial, rs.fk_id_inst "
                . " FROM recurso_serial rs "
                . " JOIN recurso_situacao rst ON rst.id_situacao = rs.fk_id_situacao "
                . " JOIN recurso_equipamento re ON re.id_equipamento = rs.fk_id_equipamento "
                . " LEFT JOIN instancia i on i.id_inst = rs.fk_id_inst "
                . " WHERE 1=1 "
                . $id_inst
                . $id_situacao
                . $id_equipamento
                . $id_serial
                . " ORDER BY rs.fk_id_situacao ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($array)) {
            foreach ($array as $v) {
                $equipamentos[$v['fk_id_inst']][$v['id_serial']] = $v;
            }
            return $equipamentos;
        }else {
            return;
        }
    }

    public function getInst() {
        $sql = "SELECT id_inst, n_inst FROM instancia "
                 . "WHERE instancia.ativo = 1 AND fk_id_tp<3 AND id_inst NOT IN (2)";
        $query = pdoSis::getInstance()->query($sql);
        $esc = null;
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $esc[$v['id_inst']] = $v['n_inst'];
        }

        return $esc;
    }

    public function emprestadoFuncionarioInativo(){
        $sql = "SELECT f.rm, i.n_inst,rm.dt_update, rm.comodato, re.n_equipamento, rst.n_situacao, re.prazo_max, p.emailgoogle,p.n_pessoa, p.sexo, p.id_pessoa, re.n_equipamento, rs.n_serial, rm.id_move, rm.dt_inicio, rm.dt_fim, rm.dt_devolucao, rm.fk_id_situacao"
                . " FROM ge2.recurso_movimentacao rm "
                . " JOIN recurso_serial rs ON rs.id_serial = rm.fk_id_serial"
                . " JOIN recurso_equipamento re ON re.id_equipamento = rs.fk_id_equipamento"
                . " JOIN pessoa p ON p.id_pessoa = rm.fk_id_pessoa_emprest"
                . " JOIN ge_funcionario f on f.fk_id_pessoa = rm.fk_id_pessoa_emprest AND at_func = 0"
                . " LEFT JOIN instancia i on i.id_inst = rm.fk_id_inst "
                . " JOIN recurso_situacao rst ON rst.id_situacao = rm.fk_id_situacao "
                . " WHERE rm.fk_id_situacao = 2";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $form = [];
        if (!empty($array)) {
            foreach ($array as $k => $v) {
                $devolveu = "";
                if (!empty($v['id_move'])) {
                    if (!empty($v['n_pessoa'])) {
                       if (empty($v['n_turma'])) {
                            $array[$k]['nome'] = $v['n_pessoa'].' ('.$v['rm'].')';
                        }else{
                            $array[$k]['nome'] = $v['n_pessoa'].' - '.$v['n_turma'];
                        }
                        if ($v['comodato'] == 0) {
                            if (!empty($v['dt_fim'])) {
                                $array[$k]['periodo'] = dataErp::converteBr($v['dt_inicio']).' a '.dataErp::converteBr($v['dt_fim']);
                            }   
                        }else{
                            $array[$k]['periodo'] = "Comodato";
                        } 
                    }
                    if (!empty($v['dt_inicio']) && ($v['fk_id_situacao']<>2 && $v['fk_id_situacao']<>3)) {
                        $array[$k]['data'] = dataErp::converteBr($v['dt_inicio']).' a '.dataErp::converteBr($v['dt_update']);
                        if ($v['fk_id_situacao'] == 1 || $v['fk_id_situacao'] == 7) {
                            $devolveu = 'Devolvido / ';
                        }
                    }else{
                        $array[$k]['data'] = $v['dt_update'];
                    }
                    $array[$k]['situacao'] = $devolveu.$v['n_situacao'];
                } else {
                    unset($array[$k]); 
                }
            }
            
            $form['array'] = $array;
            $form['fields'] = [
                'Eprestado para' => 'nome',
                'Equipamento' => 'n_equipamento',
                'Número de Série' => 'n_serial',
                'Situação' => 'situacao',
                'Período do Empréstimo' => 'periodo',
                'Data' => 'data',
                'E-mail' => 'emailgoogle',
                'Local' => 'n_inst',
            ];
        }
        return $form;
    }
}

