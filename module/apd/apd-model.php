<?php

class apdModel extends MainModel {

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
        if ($opt = form::jqOption()) {
            $metodo = $opt[0];
            if (in_array($metodo, get_class_methods($this))) {
                $this->$metodo($opt[1]);
            }
        }

        if ($this->db->tokenCheck('uploadDoc',true)) {
            $this->uploadDoc();
        }else if ($this->db->tokenCheck('apdFotoSalvar',true)) {
            $this->apdFotoSalvar();
        }else if ($this->db->tokenCheck('PdiAtend',true)) {
            $this->PdiAtend();
        }else if ($this->db->tokenCheck('PdiHab',true)) {
            $this->PdiHab();
        }elseif ($this->db->tokenCheck('removeAluno',true)) {
            $this->removeAluno();
        }elseif ($this->db->tokenCheck('ge_aluno_necessidades_especiaisSet')) {
            $this->ge_aluno_necessidades_especiaisSet();
        }
    }

    public function ge_aluno_necessidades_especiaisSet() {
        $id_porte = @$_POST[1]['fk_id_porte'];
        $id_pessoa = @$_POST[1]['id_pessoa'];
        $sql = "UPDATE ge_aluno_necessidades_especiais SET fk_id_porte = $id_porte WHERE fk_id_pessoa = $id_pessoa;";
        $query = pdoSis::getInstance()->query($sql);
        toolErp::alert("Concluído");
    }

    public function apdFotoSalvar() {

        $ins = @$_POST[1];
        $id_foto = $ins['id_foto'];
        $dt_foto = $ins['dt_foto'];
        $id_turma = $ins['fk_id_turma_AEE'];
        if (!empty($_FILES['arquivo']['name'])) {
            @$exten = end(explode('.', $_FILES['arquivo']['name']));
            if (!in_array($exten, ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'])) {
                toolErp::alert('Só é permitido anexar com extensões jpg, jpeg e png ');
                return;
            }
            $nome_origin = $_FILES['arquivo']['name'];
            $file = ABSPATH . '/pub/fotoApd/';
            $up = new upload($file, $id_foto, 15000000, ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG']);
            $end = $up->up();
            if ($end) {
                $ins['link'] = $end;
                $ins['nome_origin'] = toolErp::escapaAspa($nome_origin);
                $uni_letiva = $this->getUnidadeLetiva(1,$id_turma,"$dt_foto");
                if (!empty($uni_letiva)) {
                   $ins['bimestre'] = $uni_letiva;
                }
                $this->db->ireplace('apd_foto', $ins);
            } else {
                toolErp::alert('Erro ao enviar. Tente novamente');
            }
        } elseif (!empty($ins['link_video'])) {
            $uni_letiva = $this->getUnidadeLetiva(1,$id_turma,"$dt_foto");
            if (!empty($uni_letiva)) {
               $ins['bimestre'] = $uni_letiva;
            }
            $this->db->ireplace('apd_foto', $ins);
        } else {
            toolErp::alert("Não foi possível salvar. Inclua uma foto ou um vídeo!");
        }
    }

    public function getApdFotos($id_turma, $id_pessoa, $bimestre ) {
        $sql = "SELECT f.id_foto,f.n_foto, f.descricao, f.link, f.link_video, f.dt_foto, f.fk_id_pessoa_prof"
                . " FROM apd_foto f"
                . " WHERE fk_id_turma_AEE = " . $id_turma
                . " AND fk_id_pessoa = " . $id_pessoa
                . " AND bimestre = " . $bimestre
                . " ORDER BY dt_foto ,id_foto";
        $query = pdoSis::getInstance()->query($sql);
        $fotos = $query->fetchAll(PDO::FETCH_ASSOC);

        return $fotos;
    }

    public function index_doc() {

        $turmas = $this->getTurmasProf();
        $id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
        $id_inst = toolErp::id_inst();
        $alunos = 0;
        $bimestres = sql::get('ge_cursos', 'qt_letiva, un_letiva, atual_letiva', ['id_curso' => 1], 'fetch');
        if (!empty($bimestres)) {
            $bimestre = $bimestres['atual_letiva'];
            $n_bimestre =  $bimestre."º ".$bimestres["un_letiva"];
        }
        if (!empty($id_turma)) {
            $alunos = $this->getAlunosProf($id_turma);
        }else{
            foreach ($turmas as $v) {
                $id_turma = $v["id_turma"];
                if ($id_turma) {
                    break;
                }
            }
            if (!empty($id_turma)) {
                $alunos = $this->getAlunosProf($id_turma);
            } 
        }
        $form = [];
        if ($alunos) {
            foreach ($alunos as $k => $v) {

                $aval = $this->getAval($v['id_pessoa']);
                $entr = $this->getEntre($v['id_pessoa']);
                if (!empty($entr)) {
                    $alunos[$k]['imprEntre'] = '<button class="btn btn-outline-primary" onclick="impr(' . $entr['id_entre'] . ',1,' . $v['id_turma'] . ',\'' . $v['n_pessoa'] . '\',' . $v['id_pessoa'] . ')">Imprimir Entrevista</button>';
                }
                if (!empty($aval)) {
                    $alunos[$k]['imprAval'] = '<button class="btn btn-outline-primary" onclick="impr(' . $aval['id_aval'] . ',2,' . $v['id_turma'] . ')">Imprimir Avaliação</button>';
                }   
                $alunos[$k]['pdi'] = '<button class="btn btn-outline-primary" onclick="pdi(' . $v['id_pessoa'] . ',\'' . $v['n_pessoa'] . '\',' . $v['id_turma'] . ',\'' . $v['n_turma'] . '\')">PDI</button>';
                $alunos[$k]['ava'] = '<button class="btn btn-outline-primary" onclick="ava(' . $v['id_pessoa'] . ',\'' . $v['n_pessoa'] . '\' ,' . $v['id_turma'] . ',' . (!empty($aval['id_aval'])?$aval['id_aval']:'null') . ' )">Avaliação Inicial</button>';
                $alunos[$k]['entr'] = '<button class="btn btn-outline-primary" onclick="entr(' . $v['id_pessoa'] . ',\'' . $v['n_pessoa'] . '\' ,' . $v['id_turma'] . ',' . (!empty($entr['id_entre'])?$entr['id_entre']:'null') . ')">Entrevista Familiar</button>';
                $alunos[$k]['entr'] = '<button class="btn btn-outline-primary" onclick="entr(' . $v['id_pessoa'] . ',\'' . $v['n_pessoa'] . '\' ,' . $v['id_turma'] . ',' . (!empty($entr['id_entre'])?$entr['id_entre']:'null') . ',' . (!empty($entr['id_pl'])?$entr['id_pl']:'null') . ')">Entrevista Familiar</button>';

                $pdi = $this->getPDI($v['id_pessoa']);
                if ($pdi) {
                    $id_pdi = $pdi['id_pdi'];
                    $atend = $this->getAtend($id_pdi,$bimestre,null);
                    $qtd_atend = 0;
                    if (!empty($atend)) {
                        $qtd_atend = count($atend );
                    }
                    $atend = sql::get('apd_pdi_atend', 'id_atend', " WHERE fk_id_pdi = $id_pdi AND atualLetiva = $bimestre AND dt_atend like '".date("Y-m-d")."'", 'fetch');
                    if (!empty($atend)) {
                        $id_atend = $atend['id_atend'];
                    }else{
                        $id_atend = 0;
                    }
                    $alunos[$k]['atend'] =  $qtd_atend;
                    $alunos[$k]['form'] ='<button class="btn btn-outline-info" onclick="edit(' . $id_atend . ',' . $v['id_pessoa'] . ',' . $id_pdi . ',\'' . $v['n_pessoa'] . '\' )">Atendimento</button>';
                    $alunos[$k]['foto'] ='<button class="btn btn-outline-info" onclick="foto(' . $v['id_pessoa'] . ',\'' . $v['n_pessoa'] . '\' )">Template</button>';
                }
                
            }
            $form['array'] = $alunos;
            $form['fields'] = [
                'RSE' => 'id_pessoa',
                'Aluno' => 'n_pessoa',
                'Turma' => 'n_turma',
                'Deficiência' => 'n_ne',
                'Porte' => 'n_porte',
                //'Atendimentos' => 'atend',
                //'||4' => 'imprEntre',
                //'||5' => 'imprAval',
                '||2' => 'entr',
                '||1' => 'ava',
                '||3' => 'pdi',
                '||6' => 'form',
                '||7' => 'foto',
            ];
        }

        return [
            'form' => $form,
            'turmas' => $turmas,
            'bimestre' => $bimestre,
            'n_bimestre' => $n_bimestre,
            'id_turma' => $id_turma,//turma AEE
        ];

    }

    public function getNumAtend($bimestre,$id_turma) {
        $sql = " SELECT "
                . " count(id_atend) as numAtend "
                . " FROM apd_pdi pdi "
                . " JOIN apd_pdi_atend ate ON ate.fk_id_pdi = pdi.id_pdi "
                . " WHERE fk_id_turma_AEE = $id_turma AND atualLetiva = $bimestre ";
        $query = pdoSis::getInstance()->query($sql);
        $arrAtend = $query->fetch(PDO::FETCH_ASSOC);

        $sql = " SELECT "
                . " count(distinct fk_id_pessoa) as aluno "
                . " FROM apd_pdi pdi "
                . " JOIN apd_pdi_atend ate ON ate.fk_id_pdi = pdi.id_pdi "
                . " WHERE fk_id_turma_AEE = $id_turma AND atualLetiva = $bimestre ";
        $query = pdoSis::getInstance()->query($sql);
        $arrAluno = $query->fetch(PDO::FETCH_ASSOC);

        $numAtend['atend'] = 0;
        $numAtend['aluno'] = 0;
        $numAtend['media'] = 0;
        if ($arrAtend) {
            $numAtend['atend'] = $arrAtend['numAtend'];
        }

        if ($arrAluno) {
            $numAtend['aluno'] = $arrAluno['aluno'];
        }

        if (!empty($numAtend['atend']) && !empty($numAtend['aluno'])) {
            $numAtend['media'] = round($numAtend['atend']/$numAtend['aluno']);
        }

        return $numAtend;

    }

    public function getDocs($id_pessoa,$id_turma, $id_pl=null) {
        $pdi = $this->getPDI($id_pessoa,$id_pl);
        $adaptacao = $this->getAdapt($id_pessoa,$id_pl);
        //$adaptacao = sql::get('apd_aluno_adaptacao', 'id_aluno_adaptacao, qt_letiva', ['fk_id_pessoa' => $id_pessoa]);
        $docs = [];
        $bimestre = [1,2,3,4];
        if (toolErp::id_nilvel() == 10 || toolErp::id_nilvel() == 24  || toolErp::id_nilvel() == 18) {
            $docs[1]['titulo'] = 'Relatório de Avaliação Inicial do Aluno';
            $docs[2]['titulo'] = 'Entrevista Familiar';

            $entre = $this->getEntre($id_pessoa,$id_pl);
            $aval = $this->getAval($id_pessoa,$id_pl);
            if ($aval) {
                $id_aval = $aval['id_aval'];
                $docs[1]['btn4'] =  '<button class="btn btn-outline-primary" onclick="doc(\'PDFaval\',' . @$id_aval . ')">Visualizar</button>';
            }
            if ($entre) {
                $id_entre = $entre['id_entre'];
                $docs[2]['btn4'] = '<button class="btn btn-outline-primary" onclick="doc(\'PDFentre\',' . @$id_entre . ')">Visualizar</button>';
            }
        }
        $docs[3]['titulo'] = 'PDI - Plano de Desenvolvimento Individual (Anexo II)';
        $docs[4]['titulo'] = 'Adaptação Curricular';
        $docs[5]['titulo'] = 'Registro Fotográfico';
        $docs[6]['titulo'] = 'Termos de Aceite';
        $docs[7]['titulo'] = 'Termos de Recusa';

        if (!empty($pdi)) {
            $id_pdi = $pdi['id_pdi'];
            $id_turma_AEE = $pdi['fk_id_turma_AEE'];

            foreach ($bimestre as $v) {
                $hab = sql::get('apd_pdi_hab', 'id_pdi_hab', ['fk_id_pdi' => $id_pdi, 'atualLetiva' => $v]);
                $descr = sql::get('apd_pdi_descritiva','id_descritiva', ['fk_id_pdi' => $id_pdi, 'atualLetiva' => $v], 'fetch');
                $atend = $this->getAtend($id_pdi,$v,null);

                if (!empty($hab) || !empty($descr) || !empty($atend) ) {
                    $docs[3]['btn'.$v] = '<button class="btn btn-outline-primary" onclick="doc(\'PDFInicio\',' . @$id_pdi . ',' .$v . ')">' .$v . 'º Bimestre</button>';
                }
            }
        }

        if (!empty($adaptacao)) {
            foreach ($adaptacao as $j) {
                $id_adapt = $j['id_aluno_adaptacao'];
                foreach ($bimestre as $v) {
                    $adaptacao = sql::get('apd_aluno_adaptacao', 'id_aluno_adaptacao', ['id_aluno_adaptacao' => $id_adapt, 'qt_letiva' => $v], 'fetch');

                    if ($adaptacao) {
                        $docs[4]['btn'.$v] = '<button class="btn btn-outline-primary" onclick="doc(\'boletimPDF\',' . @$id_adapt . ',' .$v . ')">' .$v . 'º Bimestre</button>';
                    }
                }
            }
        }

        if (!empty($id_turma_AEE)) {
            foreach ($bimestre as $v) {
                $fotos = $this->getRegFoto($id_pessoa,$v,$id_pl);
                if ($fotos) {
                    $docs[5]['btn'.$v] = '<button class="btn btn-outline-primary" onclick="doc(\'fotosPDF\',' . @$id_turma_AEE . ',' .$v . ')">' .$v . 'º Bimestre</button>';
                }
            }
        }

        $termosAceite = $this->getTermos(null, $id_pessoa, 'A');
        if (!empty($termosAceite)) {
            //ordena por ano
            toolErp::ordenaArray($termosAceite, 'ano');

            $i=1;
            foreach ($termosAceite as $v) {
                $docs[6]['btn'.$i] = '<button class="btn btn-outline-primary" onclick="doc(\'termoAceite\', '. $v['id_protocolo'] . ')">' . $v['ano'] . '</button>';
                $i++;
            }
        }

        $termosRecusa = $this->getTermos(null, $id_pessoa, 'R');
        if (!empty($termosRecusa)) {
            //ordena por ano
            toolErp::ordenaArray($termosRecusa, 'ano');

            $i=1;
            foreach ($termosRecusa as $k => $v) {
                $docs[7]['btn'.$i] = '<button class="btn btn-outline-danger" onclick="doc(\'termoRecusa\', '. $v['id_protocolo'] . ')">' . $v['ano'] . '</button>';
                $i++;
            }
        }

        return $docs;

    }

    public static function idade($data) {
        if (substr($data, 2, 1) == '/') {
            // Separa em dia, mês e ano
            list($dia, $mes, $ano) = explode('/', $data);
        } else {
            list($ano, $mes, $dia) = explode('-', $data);
        }

        // Descobre que dia é hoje e retorna a unix timestamp
        $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        // Descobre a unix timestamp da data de nascimento do fulano
        $nascimento = mktime(0, 0, 0, $mes, $dia, $ano);

        // Depois apenas fazemos o cálculo já citado :)
        $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);

        return $idade;
    }

    public function GetrespEntre($campo,$resp,$id_entre=null) {

        $Arr = "";
        $entre = sql::get('apd_doc_entrevista',"sens_1,sens_2,desenvol_1,desenvol_2,desenvol_3,lingu_1,lingu_2,fisico_1,fisico_2,fisico_3,hist_saude_1,hist_saude_2,hist_saude_3,hist_saude_4,hist_saude_5,hist_saude_6", ['id_entre' => $id_entre], 'fetch');
        foreach ($entre as $k => $v) {
            if (!empty($v)) {
               $obs[$k] = ': '.$v;
            }
        }
        if ($campo == 'sens') {
            $Arr = [
                '1' => 'Visão'.@$obs['sens_1'],
                '2' => 'Audição'.@$obs['sens_2']
            ];
        }

        if ($campo == 'desenvol') {
            $Arr = [
                '1' => 'Sentar'.@$obs['desenvol_1'],
                '2' => 'Engatinhar'.@$obs['desenvol_2'],
                '3' => 'Andar'.@$obs['desenvol_3']
            ];
        }
        
        if ($campo == 'lingu') {
            $Arr = [
                '1' => 'Balbuciar'.@$obs['lingu_1'],
                '2' => 'falar'.@$obs['lingu_2']
            ];
        }
        
        if ($campo == 'fisico') {
            $Arr = [
                '1' => 'Controle esfincteriano'.@$obs['fisico_1'],
                '2' => 'Enurese'.@$obs['fisico_2'],
                '3' => 'Encoprese'.@$obs['fisico_3']
            ];
        }
        
        if ($campo == 'hist_saude') {
            $Arr = [
                '1' => 'Doenças'.@$obs['hist_saude_1'],
                '2' => 'Cirurgias'.@$obs['hist_saude_2'],
                '3' => 'Quedas'.@$obs['hist_saude_3'],
                '4' => 'Desmaios'.@$obs['hist_saude_4'],
                '5' => 'Convulções'.@$obs['hist_saude_5'],
                '6' => 'Outros'.@$obs['hist_saude_6']
            ];
        }
      
        $vir = "";
        $resposta = "";
        if (!empty($resp) && !empty($Arr)) {
            foreach ($resp as $v) {
                if (!empty($Arr[$v])) {
                    $resposta = $resposta.$vir.$Arr[$v];
                    $vir = "; ";
                }
                  
            }
        }

        if (!empty($resposta)) {
           return $resposta; 
        }else{
            return 'Não Informado';
        }
    }
    public function planoHabil($id_hab) {
        $sql = " SELECT "
                . " hab.descricao, hab.codigo, disc.id_disc, disc.n_disc, hab.id_hab,"
                . " ut.n_ut, oc.n_oc, hab.metodologicas, hab.verific_aprendizagem "
                . " FROM coord_hab hab "
                . " left join coord_uni_tematica ut on ut.id_ut = hab.fk_id_ut "
                . " left join coord_objeto_conhecimento oc on oc.id_oc = hab.fk_id_oc "
                . " left join ge_disciplinas disc on disc.id_disc = hab.fk_id_disc "
                . " WHERE hab.id_hab = $id_hab"
                . " order by disc.n_disc";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

     public function getHabil() {
        
        $sql = " SELECT h.id_hab, h.descricao, h.codigo, c.atual_letiva FROM coord_hab h "
                . " join coord_grup_hab gh on gh.id_gh = h.fk_id_gh "
                . " JOIN coord_hab_ciclo c on c.fk_id_hab = h.id_hab "
                . " WHERE gh.at_gh = 1 "
                . " ORDER BY h.codigo ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            
            $h['a'][$v['id_hab']] = $v['codigo'] . ' - ' . $v['descricao'];
        }
        $h['p'] = [];

        return $h;
    }

    public static function getAtend($id_pdi, $atualLetiva, $data = null, $presenca=null) { 
        $atend = '';
        $WHERE = '';
        if ($data) {
          $WHERE = $WHERE." AND dt_atend like '".$data."'";  
        }
        if (!is_null($presenca)&&strlen($presenca)>0) {
          $WHERE = $WHERE." AND presenca = $presenca";  
        }
        if (!empty($id_pdi)) {
           $sql = "SELECT"
                    . " id_atend,acao,dt_atend,presenca,justifica"
                    . " FROM apd_pdi_atend"
                    . " WHERE fk_id_pdi = $id_pdi AND atualLetiva = $atualLetiva "
                    .$WHERE;
            $query = pdoSis::getInstance()->query($sql);
            $atend = $query->fetchAll(PDO::FETCH_ASSOC);    
        }

        return $atend;

    }

    public static function getAtendDesc($id_pdi, $atualLetiva, $data=null) { 
        $atend = '';
        $Wdata = '';
        if ($data) {
          $Wdata = " AND dt_atend like '".$data."'";  
        }
        if (!empty($id_pdi)) {
           $sql = "SELECT"
                    . " id_atend,acao,justifica,presenca,dt_atend"
                    . " FROM apd_pdi_atend"
                    . " WHERE fk_id_pdi = $id_pdi AND atualLetiva = $atualLetiva".$Wdata
                    . " ORDER BY dt_atend DESC";
            $query = pdoSis::getInstance()->query($sql);
            $atend = $query->fetchAll(PDO::FETCH_ASSOC);    
        }

        return $atend;
    }

    public function getTurmasProf() {
        $id_pessoa = toolErp::id_pessoa();
        $id_inst = toolErp::id_inst();

        $sql = "SELECT tAEE.id_turma, tAEE.n_turma, i.n_inst "
                . " FROM ge_aloca_prof a "
                . " JOIN ge_turmas tAEE on tAEE.id_turma = a.fk_id_turma AND tAEE.fk_id_ciclo = 32 "
                . " JOIN instancia i ON i.id_inst = tAEE.fk_id_inst "
                . " JOIN ge_funcionario f on f.rm = a.rm"
                . " WHERE f.fk_id_pessoa = ".$id_pessoa;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;

    }

    public function getAlunosProf($id_turma) {
        $sql =  "SELECT chamada,id_pessoa,n_pessoa,id_turma,n_turma, GROUP_CONCAT(n_ne ORDER BY n_ne SEPARATOR ', ') AS n_ne, n_porte, id_porte "
            . " FROM ("
                . "SELECT DISTINCT ta.chamada, p.id_pessoa, p.n_pessoa, tt.id_turma, tt.n_turma, n.n_ne, n.id_ne, po.n_porte, po.id_porte, ae.id_def "
                . " FROM ("
                    . " SELECT ta.fk_id_turma, ta.fk_id_pessoa, ta.chamada, NULL fk_id_turma_AEE "
                    . " FROM ge_turma_aluno ta "
                    . " JOIN ge_turma_aluno_situacao tas ON tas.id_tas = ta.fk_id_tas "
                    # ESTA EM OUTRA TURMA
                    . " LEFT JOIN apd_turma_aluno ata ON ata.fk_id_turma_AEE = ta.fk_id_turma AND ata.fk_id_pessoa_aluno = ta.fk_id_pessoa "
                    . "     AND ata.fk_id_turma_turno <> ta.fk_id_turma "
                    . " WHERE (ta.fk_id_tas = 0 OR ta.fk_id_tas IS NULL) "
                    . " AND ta.fk_id_turma = $id_turma "
                    # NEGA A CONDICAO DE ESTAR EM OUTRA TURMA
                    . " AND ata.id_turma_alu_AEE IS NULL "
                    . " UNION "
                    . " SELECT ata.fk_id_turma_turno fk_id_turma, ta.fk_id_pessoa, ta.chamada, ata.fk_id_turma_AEE "
                    . " FROM ge_turma_aluno ta "
                    . " JOIN ge_turma_aluno_situacao tas ON tas.id_tas = ta.fk_id_tas "
                    . " JOIN apd_turma_aluno ata ON ata.fk_id_turma_AEE = ta.fk_id_turma AND ata.fk_id_pessoa_aluno = ta.fk_id_pessoa "
                    . " WHERE (ta.fk_id_tas = 0 OR ta.fk_id_tas IS NULL) "
                    . "     AND ata.fk_id_turma_turno = $id_turma "
                . " ) ta"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
                . " JOIN pessoa p ON p.id_pessoa = ta.fk_id_pessoa "
                . " LEFT JOIN ge_aluno_necessidades_especiais ae ON ta.fk_id_pessoa = ae.fk_id_pessoa "
                . " LEFT JOIN ge_aluno_necessidades_especiais_porte po ON po.id_porte = ae.fk_id_porte "
                . " LEFT JOIN ge_necessidades_especiais n ON n.id_ne = ae.fk_id_ne "
                . " JOIN ge_turma_aluno tr ON tr.fk_id_pessoa = p.id_pessoa AND (tr.fk_id_tas = 0 OR tr.fk_id_tas IS NULL) "
                . " LEFT JOIN ge_turmas tt ON tt.id_turma = tr.fk_id_turma AND tt.fk_id_ciclo != 32"
                . " JOIN ge_periodo_letivo plr ON plr.id_pl = tt.fk_id_pl AND plr.at_pl = 1"
                . " WHERE t.id_turma = $id_turma"
                . " ORDER BY ta.chamada "
            . ") AS tab1"
            . " GROUP BY chamada , id_pessoa , n_pessoa , id_turma , n_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $alu = [];
        if ($array) {
            foreach ($array as $v) {
                if (!empty($alu[$v['id_pessoa']])) {
                    $def = $alu[$v['id_pessoa']]['def'];
                }
                $alu[$v['id_pessoa']] = $v;
                if (empty($def)) {
                    $alu[$v['id_pessoa']]['def'] = $v['n_ne'];
                } else {
                    $alu[$v['id_pessoa']]['def'] = $def . '; ' . $v['n_ne'];
                }
            }
        }
        return $alu;
    }

    public static function nota() {
        $nota = sql::get('apd_nota', 'id_nota, sigla, valor');

        foreach ($nota as $k => $v) {
           $valor[$v["id_nota"]]["sigla"] = $v["sigla"];
           $valor[$v["id_nota"]]["valor"] = $v["valor"];
        }
        return $valor;
    }

    public function nota_bimestre($nota) {

        if ($nota <=50) {
            $nota = "ED";
        }elseif($nota > 50 && $nota <= 80){
            $nota = "B";
        }elseif ($nota > 80){
            $nota = "MB";
        }else{
            $nota = "";
        }

        return $nota;

    }

    public function profePEBI($id_pessoa) {
        $sql = " SELECT prof.rm, p.n_pessoa "
        . " FROM ge_turma_aluno ta "
        . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
        . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
        . " JOIN ge_aloca_prof prof on prof.fk_id_turma = t.id_turma and prof.iddisc like 'nc' "
        . " join ge_funcionario f on f.rm = prof.rm "
        . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
        . " WHERE ta.fk_id_pessoa = $id_pessoa ;";
        $query = pdoSis::getInstance()->query($sql);
        $nc = $query->fetch(PDO::FETCH_ASSOC);
        $prof_turma = "";
        if ($nc ) {
            $prof_turma = $nc['n_pessoa'] . ' (' . $nc['rm'] . ')';
        }
        return $prof_turma;
    }

    public function profeSala($id_pessoa, $sexo=null) {
        $sql = "SELECT DISTINCT prof.rm, p.n_pessoa, p.id_pessoa "
        . " FROM ge_turma_aluno ta "
        . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma AND fk_id_ciclo != 32"
        . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
        . " JOIN ge_aloca_prof prof on prof.fk_id_turma = t.id_turma "
        . " join ge_funcionario f on f.rm = prof.rm "
        . " join pessoa p on p.id_pessoa = f.fk_id_pessoa "
        . " WHERE ta.fk_id_pessoa = $id_pessoa;";
        $query = pdoSis::getInstance()->query($sql);
        $nc = $query->fetchAll(PDO::FETCH_ASSOC);
        $prof_turma = "";
        $vir = "";
        $sexo_prof = 'Professoras';
        foreach ($nc as $k => $v) {
            $prof_turma = $prof_turma.$vir." ".$v['n_pessoa'] . ' (' . $v['rm'] . ')';
            $vir = ",";
            if (!empty($sexo)) {
               $FM = toolErp::sexo_pessoa($v['id_pessoa']);
               if ($FM == 'M') {
                   $sexo_prof = 'Professores(as)';
                }
            }
        }
        if (!empty($sexo)) {
            return $sexo_prof;
        }else{
            return $prof_turma;
        }  
    }

    public function uploadDoc() {
        $id_pessoa_apd = filter_input(INPUT_POST, 'id_pessoa_apd', FILTER_SANITIZE_NUMBER_INT);
        $id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
        $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);

        if (!empty($_FILES['arquivo'])) {
            @$exten = end(explode('.', $_FILES['arquivo']['name'][$i]));
            $nome_origin = $_FILES['arquivo']['name'];
            $file = ABSPATH . '/pub/apd/';
            $upload = new upload($file, $id_pessoa_apd);
            $end = $upload->up();
            if ($end) {
                $ins['n_up'] = toolErp::escapaAspa($nome_origin);
                $ins['fk_id_pessoa'] = $id_pessoa_apd;
                $ins['fk_id_pessoa_anexa'] = toolErp::id_pessoa();
                $ins['link'] = $end;
                $ins['tipo'] = $tipo;
                $this->db->ireplace('apd_up', $ins);
            } else {
                toolErp::alert('Erro ao enviar. Tente novamente');
            }
        } else {
            toolErp::alert('Erro ao enviar. Tente novamente');
        }
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

    public static function alunoGet($id_pessoa = NULL, $fields = " p.id_pessoa, p.n_pessoa, t.id_turma, t.n_turma, i.id_inst, i.n_inst, n.n_ne, n.id_ne ") {
        $sql = "SELECT DISTINCT"
                . " $fields "
                . " FROM ge_aluno_necessidades_especiais ae "
                . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = ae.fk_id_pessoa AND (ta.fk_id_tas = 0 OR ta.fk_id_tas IS NULL)"
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma AND t.fk_id_ciclo != 32 "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN pessoa p on p.id_pessoa = ae.fk_id_pessoa "
                . " JOIN instancia i on i.id_inst = t.fk_id_inst "
                . " LEFT JOIN ge_necessidades_especiais n on n.id_ne = ae.fk_id_ne "
                . " WHERE ae.fk_id_pessoa = $id_pessoa;";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $alu = [];
        if ($array) {
            foreach ($array as $v) {
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

    public function alunosEsc($id_inst,$id_pessoa) {
        $WHEREpessoa = "";
        $WHEREinst = "";

        if (!empty($id_inst)) {
            //if (toolErp::id_nilvel() == 48) {
                $WHEREinst = " AND t.fk_id_inst = $id_inst ";
            // }else{
            //     $WHEREinst = " AND tt.fk_id_inst = $id_inst ";
            //}
        }

        if (!empty($id_pessoa)) {
            $WHEREpessoa = " AND p.id_pessoa = $id_pessoa ";
        }

        $sql = "SELECT DISTINCT"
                . " p.id_pessoa, p.n_pessoa, t.id_turma, t.n_turma AS turmaRegular, IF(tr.id_turma_aluno IS NULL, 0, 1) AS ISalunoAEE, IF(tr.id_turma_aluno IS NULL, 'Sem Turma AEE', tt.n_turma) as turmaAEE, i.id_inst, t.periodo, i.n_inst, n.n_ne, n.id_ne, po.n_porte, po.id_porte, ae.id_def "
                . " FROM ge_aluno_necessidades_especiais ae "
                . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = ae.fk_id_pessoa AND (ta.fk_id_tas = 0 OR ta.fk_id_tas IS NULL)"
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma AND t.fk_id_ciclo != 32 "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN pessoa p on p.id_pessoa = ae.fk_id_pessoa "
                . " JOIN instancia i on i.id_inst = t.fk_id_inst "
//                . " join ge_aluno_necessidades_especiais_porte po on po.id_porte = ae.fk_id_porte AND ae.fk_id_porte <> 1"
                . " join ge_aluno_necessidades_especiais_porte po on po.id_porte = ae.fk_id_porte "
                . " LEFT JOIN ge_necessidades_especiais n on n.id_ne = ae.fk_id_ne "
                . " LEFT JOIN ge_turma_aluno tr  ON tr.id_turma_aluno = ( "
                . "     SELECT max(trx.id_turma_aluno) FROM ge_turma_aluno trx "
                . "     JOIN ge_turmas ttx ON ttx.id_turma = trx.fk_id_turma "
                . "     JOIN ge_periodo_letivo plAEE ON plAEE.id_pl = ttx.fk_id_pl "
                . "     AND plAEE.at_pl = 1 "
                . "     WHERE ttx.fk_id_ciclo = 32 AND trx.fk_id_pessoa = p.id_pessoa AND (trx.fk_id_tas = 0 OR trx.fk_id_tas IS NULL) "
                . " ) "
                . " LEFT JOIN ge_turmas tt ON tt.id_turma = tr.fk_id_turma "
                . " WHERE 1=1 "
                . $WHEREinst
                . $WHEREpessoa
                . " ORDER BY p.n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $alu = [];
        if ($array) {
            foreach ($array as $v) {
                if (!empty($alu[$v['id_pessoa']])) {
                    $def = $alu[$v['id_pessoa']]['def'];
                }
                $alu[$v['id_pessoa']] = $v;
                if (empty($def)) {
                    $alu[$v['id_pessoa']]['def'] = $v['n_ne'];
                } else {
                    $alu[$v['id_pessoa']]['def'] = $def . ';' . $v['n_ne'];
                }
            }
        }

        return $alu;
    }
    public function ListAlunoAEE() {
            $sql = "SELECT "
                    . " p.id_pessoa, p.n_pessoa"
                    . " FROM ge_aluno_necessidades_especiais ae "
                    . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = ae.fk_id_pessoa AND (ta.fk_id_tas = 0 OR ta.fk_id_tas IS NULL)"
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma AND t.fk_id_ciclo != 32 "
                    . " JOIN ge_aluno_necessidades_especiais apd on apd.fk_id_pessoa = ta.fk_id_pessoa"
                    . " JOIN ge_aluno_necessidades_especiais_porte porte on apd.fk_id_porte = porte.id_porte AND apd.fk_id_porte <> 1"
                    . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                    . " JOIN pessoa p on p.id_pessoa = ae.fk_id_pessoa "
                    . " order by p.n_pessoa ";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            return tool::idName($array);
    }   


    public function alunosEscBoletim($id_inst,$id_pessoa) {
        $WHEREpessoa = "";
        $WHEREinst = "";

        if (!empty($id_inst)) {
            $WHEREinst = " AND t.fk_id_inst = $id_inst ";
        }

        if (!empty($id_pessoa)) {
            $WHEREpessoa = " AND p.id_pessoa = $id_pessoa ";
        }

        $sql = "SELECT DISTINCT"
                . " p.id_pessoa, p.n_pessoa, t.id_turma, t.n_turma, i.id_inst, i.n_inst, n.n_ne, n.id_ne, po.n_porte, po.id_porte, ae.id_def "
                . " FROM ge_aluno_necessidades_especiais ae "
                . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = ae.fk_id_pessoa AND (ta.fk_id_tas = 0 OR ta.fk_id_tas IS NULL)"
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma AND t.fk_id_ciclo != 32 "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl AND pl.at_pl = 1 "
                . " JOIN pessoa p on p.id_pessoa = ae.fk_id_pessoa "
                . " JOIN instancia i on i.id_inst = t.fk_id_inst "
                . " join ge_aluno_necessidades_especiais_porte po on po.id_porte = ae.fk_id_porte "
                . " LEFT JOIN ge_necessidades_especiais n on n.id_ne = ae.fk_id_ne "
                . " WHERE po.id_porte = 3 "
                . $WHEREinst
                . $WHEREpessoa
                . " order by p.n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $alu = [];
        if ($array) {
            foreach ($array as $v) {
                if (!empty($alu[$v['id_pessoa']])) {
                    $def = $alu[$v['id_pessoa']]['def'];
                }
                $alu[$v['id_pessoa']] = $v;
                if (empty($def)) {
                    $alu[$v['id_pessoa']]['def'] = $v['n_ne'];
                } else {
                    $alu[$v['id_pessoa']]['def'] = $def . ';' . $v['n_ne'];
                }
            }
        }

        return $alu;
    }

    public function getProfAEE($id_aluno) {
        $sql = " SELECT p.id_pessoa, p.n_pessoa, ap.rm "
            ." FROM ge_turma_aluno ta "
            ." JOIN ge_turmas t on ta.fk_id_turma = t.id_turma AND t.fk_id_ciclo = 32 "
            ." JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1 "
            ." JOIN ge_aloca_prof ap on ap.fk_id_turma = t.id_turma "
            ." JOIN ge_funcionario f on f.rm = ap.rm "
            ." JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa "
            ." WHERE ta.fk_id_pessoa = $id_aluno AND (ta.fk_id_tas = 0 OR ta.fk_id_tas IS NULL) ";
        $query = pdoSis::getInstance()->query($sql);
        $arrProf = $query->fetch(PDO::FETCH_ASSOC);
        $arrProf['prof'] = 'Prof.';
        if (!empty($arrProf)) {
           $prof_sexo = toolErp::sexo_pessoa($arrProf['id_pessoa']);
           if ($prof_sexo == 'M') {
               $arrProf['prof'] = 'Prof.';
            }else{
               $arrProf['prof'] = 'Profa.';

            }
        }
        return $arrProf;
    }

    public function adaptCurrHabil($id_hab) {
        $sql = " SELECT "
                . " hab.descricao, hab.codigo, disc.id_disc, disc.n_disc, hab.id_hab,"
                . " ut.n_ut, oc.n_oc, hab.metodologicas, hab.verific_aprendizagem "
                . " FROM coord_hab hab "
                . " left join coord_uni_tematica ut on ut.id_ut = hab.fk_id_ut "
                . " left join coord_objeto_conhecimento oc on oc.id_oc = hab.fk_id_oc "
                . " left join ge_disciplinas disc on disc.id_disc = hab.fk_id_disc "
                . " WHERE hab.id_hab = $id_hab"
                . " order by disc.n_disc";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function PdiAtend() {
        $ins = @$_POST[1];
        $id_atend = $this->db->ireplace('apd_pdi_atend', $ins);

        if (!empty($_FILES['arquivo']) && $_POST['up']==1) {
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
    }

    public function getAtendFaltasRel($id_turma) {
        $sql = " SELECT "
                . " count(id_atend) as numAtend,p.n_pessoa, pdi.fk_id_pessoa, presenca, atualLetiva, pdi.id_pdi "
                . " FROM apd_pdi pdi "
                . " JOIN apd_pdi_atend ate ON ate.fk_id_pdi = pdi.id_pdi "
                . " JOIN pessoa p on p.id_pessoa = pdi.fk_id_pessoa  "
                . " WHERE fk_id_turma_AEE IN ($id_turma) AND YEAR(dt_atend) = YEAR(NOW()) "
                . " GROUP BY pdi.fk_id_pessoa, presenca, atualLetiva"
                . " ORDER BY atualLetiva, p.n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $arr = $query->fetchAll(PDO::FETCH_ASSOC);
        $arrayNovo = array();
        foreach ($arr as $v) {
            
            $arrayNovo[$v['fk_id_pessoa']]['nome'] = $v['n_pessoa'];
            $arrayNovo[$v['fk_id_pessoa']]['id_pdi'] = $v['id_pdi'];
            
            if (!isset($arrayNovo[$v['fk_id_pessoa']][$v['atualLetiva']])) {
                $arrayNovo[$v['fk_id_pessoa']][$v['atualLetiva']] = ['-1' => 0];
            }

            $arrayNovo[$v['fk_id_pessoa']][$v['atualLetiva']][$v['presenca']] = $v['numAtend'];
            $arrayNovo[$v['fk_id_pessoa']][$v['atualLetiva']][-1] += $v['numAtend'];
        }
        return $arrayNovo;
    }

    public function getAtendFaltasRelMes($id_turma) {
        $sql = " SELECT "
                . " count(id_atend) as numAtend, pdi.fk_id_pessoa, presenca, month(dt_atend) AS mes "
                . " FROM apd_pdi pdi "
                . " JOIN apd_pdi_atend ate ON ate.fk_id_pdi = pdi.id_pdi "
                . " JOIN pessoa p on p.id_pessoa = pdi.fk_id_pessoa  "
                . " WHERE fk_id_turma_AEE = $id_turma AND YEAR(dt_atend) = YEAR(NOW()) "
                . " GROUP BY pdi.fk_id_pessoa, presenca, month(dt_atend)"
                . " ORDER BY month(dt_atend), p.n_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $arr = $query->fetchAll(PDO::FETCH_ASSOC);
        return $arr;
    }

    public function getAtendFaltasRel_MESES($array) {
        $arrayMes = array();
        
        foreach ($array as $v) {
            $mes = data::mes($v['mes']);
            $arrayMes[$v['mes']] = $mes;
        }

        for ($i=0; $i <12 ; $i++) { 
             // code...
        
            $mes = data::mes($i);
            $arrayMes[$i] = $mes;
        }
        return $arrayMes;
    }

    public function getTurmasAEE($id_inst) {
        $sql = "SELECT id_turma, n_turma"
                . " FROM ge_turmas t "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1 "
                . " WHERE fk_id_ciclo = 32 AND fk_id_inst = ".$id_inst;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public static function getEscolaAEE() {
        $sql = "SELECT id_inst, n_inst"
                . " FROM ge_turmas t "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1 "
                . " JOIN instancia i ON t.fk_id_inst = i.id_inst "
                . " WHERE t.fk_id_ciclo = 32";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        
        $escolas = array();
        foreach ($array as $v) {
            $escolas[$v['id_inst']] = $v['n_inst'];
        }
        return $escolas;
    }

    public function getIdTurmasAEE() {
        $id_inst = toolErp::id_inst();
        $sql = "SELECT id_turma"
                . " FROM ge_turmas t "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1 "
                . " WHERE fk_id_ciclo = 32 AND fk_id_inst = ".$id_inst;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $id_turma = "";
        foreach ($array as $key => $v) {
            $id_turma = (!empty($id_turma) ? $id_turma.", ".$v['id_turma'] : $v['id_turma']);      
        }
        return $id_turma;
    }

    public function dashGet($id_turma=null,$bimestre=null,$turmasEscola=null) {

        if (!empty($id_turma)) {
           $WHERE = " WHERE fk_id_turma_AEE = $id_turma AND atualLetiva = $bimestre "; 
        }elseif (!empty($turmasEscola)) {
            $WHERE = " WHERE fk_id_turma_AEE IN ($turmasEscola) ";
        }else{
            $WHERE = " WHERE YEAR(dt_atend) = YEAR(NOW()) ";
        }
        
        $sql = "SELECT count(id_atend) as numAtend, presenca"
                . " FROM apd_pdi pdi  "
                . " JOIN apd_pdi_atend ate ON ate.fk_id_pdi = pdi.id_pdi "
                . $WHERE
                . " GROUP BY presenca ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $atendimentos = array();
        $atendimentos['total'] = 0;
        $atendimentos['faltas'] = 0;
        $atendimentos[0] = 0;
        $atendimentos[1] = 0;
        foreach ($array as $v) {
            $atendimentos[$v['presenca']] = $v['numAtend'];
            $atendimentos['total'] = $atendimentos['total'] + $v['numAtend'];
        }

        if (!empty($atendimentos['total'])) {
           $atendimentos['faltas'] = intval(($atendimentos['0'] / $atendimentos['total']) * 100); 
        }
        
        return $atendimentos;
    }

    public function dashGetFaltas($id_turma=null) {
        if (!empty($id_turma)) {
           $WHERE = " WHERE fk_id_turma_AEE IN ($id_turma) AND presenca = 0  "; 
        }else{
            $WHERE = "WHERE YEAR(dt_atend) = YEAR(NOW()) AND presenca = 0  ";
        }
        $sql = "SELECT count(id_atend) as numAtend, month(dt_atend) as mes"
                . " FROM apd_pdi pdi  "
                . " JOIN apd_pdi_atend ate ON ate.fk_id_pdi = pdi.id_pdi "
                . " WHERE YEAR(dt_atend) = YEAR(NOW()) AND presenca = 0 "
                . " GROUP BY month(dt_atend) "
                . " ORDER BY month(dt_atend) ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $faltas = 0;
        $meses = "";
        foreach ($array as $v) {
            $mes = "'".data::mes($v['mes'])."'";
            $meses = (!empty($meses) ? $meses.", ".$mes : $mes);
            $faltas = (!empty($faltas) ? $faltas.", ".$v['numAtend'] : $v['numAtend']);
        }
        $total['faltas'] = $faltas;
        $total['meses'] = $meses;
        return $total;
    }

    public function turmaList($id_inst){
        ob_clean();
        $sql = "SELECT id_turma, n_turma"
                . " FROM ge_turmas t "
                . " JOIN ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1 "
                . " WHERE fk_id_ciclo = 32 AND fk_id_inst = ".$id_inst;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $k => $v) {
           $turmas[$v['id_turma']] = $v['n_turma'];
        }
        echo json_encode($turmas);
        exit();
    }

    public function ViewHistoricoAtend($resultSql,$titulo){?>
        <style type="text/css">
             /* Esconde o input */
            input[type='file'] {
                display: none
            }
            /* Aparência que terá o seletor de arquivo */
            .labelSet {
                background-color: #3498db;
                border-radius: 5px;
                color: #fff;
                cursor: pointer;
                margin: 0 auto;
                padding: 6px;
                width: 300px;
                text-align: center
            }
            .input-group-text
            {
            display: none;
            }

            .titulo { 
                color: #888;
                font-size: 16px;
                padding-bottom: 5px;
            }
            .tituloG { 
                font-weight: bold;
                color: #888;
                font-size: 18px;
                margin-bottom: 5px;
                text-align: center;
                padding: 10px;
                padding-bottom: 20px;
            }
            .info{
                margin-bottom: 5px;
            }
            textarea {height: 270px !important;}
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
            .mensagens .mensagemLinha-3{border-left: 5px solid #f9ca6e;}
            .esconde .input-group-text{ display: none; }
            .tituloBox{
                font-size: 17px;
                font-weight: bold;
                text-align: center;
            }
            .tituloBox.box-0{ color: #7ed8f5;}
            .tituloBox.box-1{ color: #f3b4ef;}
            .tituloBox.box-3{ color: #f9ca6e;}
        </style>
         <div class="row">
            <div class="col-md-12 mensagens">
                <div class="mensagem mensagemLinha-1" >
                    <div>
                        <p class="tituloBox box-1"><?= $titulo ?></p>
                        <?php 
                        if (!empty($resultSql)) {  
                            foreach ($resultSql as $v) { ?>
                                <label class="dataMensagem"<?= ($v["presenca"]==0)?'style="color:red"':"" ?>><?= ($v["dt_atend"] != '0000-00-00') ? data::converteBr($v["dt_atend"]) : "" ?></label><br>
                                <span class="corpoMensagem"></strong><?= ($v["presenca"]==1) ? $v["acao"] : 'Falta Justificada: '.$v["justifica"] ?></span><br>
                                <?php   
                            }
                        }else{
                            echo '<span class="corpoMensagem"><strong>Sem Registro</strong></span>';
                        } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function getTurmaAluno($id_pessoa,$tipo_turma=null){
        if ($tipo_turma == 1) {
           $WHERE = " AND t.fk_id_ciclo != 32 ";//turma regular
        }else{
            $WHERE = " AND t.fk_id_ciclo = 32 ";//turma AEE
        }
        $sql = "SELECT id_turma, n_turma,t.fk_id_inst,i.n_inst"
                . " FROM ge_turma_aluno ta"
                . " JOIN ge_turma_aluno_situacao tas on tas.id_tas = ta.fk_id_tas"
                . " JOIN ge_turmas t on t.id_turma= ta.fk_id_turma "
                . " JOIN instancia i on i.id_inst= ta.fk_id_inst "
                . " JOIN ge_periodo_letivo plr ON plr.id_pl = t.fk_id_pl AND plr.at_pl = 1"
                . " WHERE (ta.fk_id_tas = 0 OR ta.fk_id_tas IS NULL) AND ta.fk_id_pessoa = ".$id_pessoa
                . $WHERE;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getPDI($id_pessoa,$id_pl=null){
        if (empty($id_pl)) {
            $id_pl = curso::id_pl_atual()['id_pl'];
        }
        $sql = "SELECT pdi.id_pdi, pdi.fk_id_turma_AEE"
                . " FROM apd_pdi pdi"
                . " JOIN ge_turmas t on t.id_turma= pdi.fk_id_turma"
                . " JOIN ge_periodo_letivo plr ON plr.id_pl = t.fk_id_pl AND t.fk_id_pl = $id_pl"
                . " WHERE pdi.fk_id_pessoa = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getAval($id_pessoa,$id_pl=null){
        if (empty($id_pl)) {
            $id_pl = curso::id_pl_atual()['id_pl'];
        }
        $sql = "SELECT ava.id_aval"
                . " FROM apd_doc_aval ava"
                . " JOIN ge_turmas t on t.id_turma= ava.fk_id_turma"
                . " JOIN ge_periodo_letivo plr ON plr.id_pl = t.fk_id_pl AND t.fk_id_pl = $id_pl"
                . " WHERE ava.fk_id_pessoa = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        return $array;
    }
  
    public function getEntre($id_pessoa,$id_pl=null){
        if (empty($id_pl)) {
            $id_pl = curso::id_pl_atual()['id_pl'];
        }
        $sql = "SELECT en.id_entre, plr.id_pl"
                . " FROM apd_doc_entrevista en"
                . " JOIN ge_turmas t on t.id_turma= en.fk_id_turma"
                . " JOIN ge_periodo_letivo plr ON plr.id_pl = t.fk_id_pl AND plr.semestre = 0"
                . " WHERE en.fk_id_pessoa = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        if (empty($array)) $array = [];
        return $array;
    }
    
    public function getAdapt($id_pessoa,$id_pl=null){
        if (empty($id_pl)) {
            $id_pl = curso::id_pl_atual()['id_pl'];
        }
        $sql = "SELECT ad.id_aluno_adaptacao,ad.qt_letiva"
                . " FROM apd_aluno_adaptacao ad"
                . " JOIN ge_turma_aluno ta ON ta.id_turma_aluno = ad.fk_id_turma_aluno"
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma"
                . " JOIN ge_periodo_letivo plr ON plr.id_pl = t.fk_id_pl AND t.fk_id_pl = $id_pl"
                . " WHERE ad.fk_id_pessoa = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public function getRegFoto($id_pessoa,$bimestre,$id_pl=null) {
        if (empty($id_pl)) {
            $id_pl = curso::id_pl_atual()['id_pl'];
        }
        $sql = "SELECT f.id_foto,f.n_foto, f.descricao, f.link, f.link_video, f.dt_foto, f.fk_id_pessoa_prof"
                . " FROM apd_foto f"
                . " JOIN ge_turmas t on t.id_turma= f.fk_id_turma_AEE"
                . " JOIN ge_periodo_letivo plr ON plr.id_pl = t.fk_id_pl AND t.fk_id_pl = $id_pl"
                . " WHERE f.fk_id_pessoa = $id_pessoa"
                . " AND bimestre = " . $bimestre
                . " ORDER BY dt_foto ,id_foto";
        $query = pdoSis::getInstance()->query($sql);
        $fotos = $query->fetchAll(PDO::FETCH_ASSOC);
        return $fotos;
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
    
    public function getEscolasSupervisor() {
        $sql = " SELECT i.id_inst, i.n_inst FROM instancia i "
    . " JOIN ge_escolas e ON e.fk_id_inst = i.id_inst  "
    . " JOIN vis_setor_instancia vsi ON vsi.fk_id_inst = i.id_inst AND at_setor_instancia = 1 "
    . " JOIN vis_setor vs ON vsi.fk_id_setor = vs.id_setor AND at_setor = 1 "
    . " JOIN ge_turmas t on t.fk_id_inst = i.id_inst AND fk_id_ciclo = 32"
    . " JOIN ge_periodo_letivo plr ON plr.id_pl = t.fk_id_pl AND plr.at_pl = 1"
    . " WHERE vs.fk_id_pessoa = ".toolErp::id_pessoa()
    . " GROUP BY i.n_inst "
    . " ORDER BY i.n_inst ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return tool::idName($array);
    }

    public function getPorte($id_pessoa) {
            $sql = "SELECT id_porte FROM ge_aluno_necessidades_especiais ne "
                    . " JOIN ge_aluno_necessidades_especiais_porte po ON po.id_porte = ne.fk_id_porte "
                    . " WHERE ne.fk_id_pessoa = $id_pessoa";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetch(PDO::FETCH_ASSOC);

            if (!empty($array)) {
                if ($array['id_porte'] == 2) {
                   $porte = 'Pequeno Porte';
                }elseif ($array['id_porte'] == 3) {
                   $porte = 'Grande Porte';
                }else{
                    $porte =  'Sem Adaptação';
                }
            }else{
                $porte = 'Adaptação Curricular';
            }
            
            return  $porte;
        }

    public function getUnidadeLetiva($id_curso, $id_turma, $data) {
        $sql = "SELECT atual_letiva FROM `sed_letiva_data` sld"
        . " JOIN ge_turmas t on sld.fk_id_pl = t.fk_id_pl "
        . " WHERE sld.fk_id_curso = $id_curso AND t.id_turma = $id_turma AND '".$data ."' BETWEEN dt_inicio AND dt_fim ";
        $query = pdoSis::getInstance()->query($sql);
        $uni_letiva = $query->fetch(PDO::FETCH_ASSOC);
        return $uni_letiva['atual_letiva'];
    }

    public function PdiHab() {
        $ins = @$_POST[1];
        $this->db->ireplace('apd_pdi_hab', $ins);
        $bim= $ins['atualLetiva'] + 1;
        $fk_id_pdi = $ins['fk_id_pdi'];

        if (!empty($ins['fk_id_hab'])) {
           for ($i = $bim; $i <= 4 ; $i++) { 
                $ins['atualLetiva'] = $i;
                $sql = "SELECT id_pdi_hab FROM `apd_pdi_hab` WHERE fk_id_pdi = $fk_id_pdi AND fk_id_hab = {$ins['fk_id_hab']} AND atualLetiva = $i";
                $query = $this->db->query($sql);
                $array = $query->fetch();
                if (!empty($array)) {
                    $ins['id_pdi_hab'] = $array['id_pdi_hab'];
                    $this->db->update('apd_pdi_hab', 'id_pdi_hab', $array['id_pdi_hab'], $ins, true);
                } else {
                    $this->db->insert('apd_pdi_hab', $ins, true);
                }
            } 
        } 
    }

    public function getAlunosTurmaTurno($id_turma) {
        $sql =  "SELECT chamada,id_pessoa,n_pessoa,id_turma,n_turma, GROUP_CONCAT(n_ne ORDER BY n_ne SEPARATOR ', ') AS n_ne, n_porte, id_porte , fk_id_turma_AEE"
            . " FROM ("
                . "SELECT DISTINCT ta.chamada, p.id_pessoa, p.n_pessoa, tt.id_turma, tt.n_turma, n.n_ne, n.id_ne, po.n_porte, po.id_porte, ae.id_def, fk_id_turma_AEE "
                . " FROM ("
                    . " SELECT ta.fk_id_turma, ta.fk_id_pessoa, ta.chamada, NULL fk_id_turma_AEE "
                    . " FROM ge_turma_aluno ta "
                    . " JOIN ge_turma_aluno_situacao tas ON tas.id_tas = ta.fk_id_tas "
                    # ESTA EM OUTRA TURMA
                    . " LEFT JOIN apd_turma_aluno ata ON ata.fk_id_turma_AEE = ta.fk_id_turma AND ata.fk_id_pessoa_aluno = ta.fk_id_pessoa "
                    . "     AND ata.fk_id_turma_turno <> ta.fk_id_turma "
                    . " WHERE (ta.fk_id_tas = 0 OR ta.fk_id_tas IS NULL) "
                    . " AND ta.fk_id_turma = $id_turma "
                    # NEGA A CONDICAO DE ESTAR EM OUTRA TURMA
                    . " AND ata.id_turma_alu_AEE IS NULL "
                    . " UNION "
                    . " SELECT ata.fk_id_turma_turno fk_id_turma, ta.fk_id_pessoa, ta.chamada, ata.fk_id_turma_AEE "
                    . " FROM ge_turma_aluno ta "
                    . " JOIN ge_turma_aluno_situacao tas ON tas.id_tas = ta.fk_id_tas "
                    . " JOIN apd_turma_aluno ata ON ata.fk_id_turma_AEE = ta.fk_id_turma AND ata.fk_id_pessoa_aluno = ta.fk_id_pessoa "
                    . " WHERE (ta.fk_id_tas = 0 OR ta.fk_id_tas IS NULL) "
                    . "     AND ata.fk_id_turma_turno = $id_turma "
                . " ) ta"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
                . " JOIN pessoa p ON p.id_pessoa = ta.fk_id_pessoa "
                . " LEFT JOIN ge_aluno_necessidades_especiais ae ON ta.fk_id_pessoa = ae.fk_id_pessoa "
                . " LEFT JOIN ge_aluno_necessidades_especiais_porte po ON po.id_porte = ae.fk_id_porte "
                . " LEFT JOIN ge_necessidades_especiais n ON n.id_ne = ae.fk_id_ne "
                . " JOIN ge_turma_aluno tr ON tr.fk_id_pessoa = p.id_pessoa AND (tr.fk_id_tas = 0 OR tr.fk_id_tas IS NULL) "
                . " LEFT JOIN ge_turmas tt ON tt.id_turma = tr.fk_id_turma AND tt.fk_id_ciclo != 32"
                . " JOIN ge_periodo_letivo plr ON plr.id_pl = tt.fk_id_pl AND plr.at_pl = 1"
                . " WHERE t.id_turma = $id_turma"
                . " ORDER BY ta.chamada "
            . ") AS tab1"
            . " GROUP BY chamada , id_pessoa , n_pessoa , id_turma , n_turma ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $alu = [];
        if ($array) {
            foreach ($array as $v) {
                if (!empty($alu[$v['id_pessoa']])) {
                    $def = $alu[$v['id_pessoa']]['def'];
                }
                $alu[$v['id_pessoa']] = $v;
                if (empty($def)) {
                    $alu[$v['id_pessoa']]['def'] = $v['n_ne'];
                } else {
                    $alu[$v['id_pessoa']]['def'] = $def . '; ' . $v['n_ne'];
                }
            }
        }
        return $alu;
    }

    public function removeAluno() {
        $fk_id_pessoa_aluno = filter_input(INPUT_POST, 'fk_id_pessoa_aluno', FILTER_SANITIZE_NUMBER_INT);
        $this->db->delete('apd_turma_aluno','fk_id_pessoa_aluno', $fk_id_pessoa_aluno);
        if (!empty($nome)) {
            log::logSet("Removeu o aluno RSE " . $fk_id_pessoa_aluno);  
        }
    }

    public function getTermos($id_inst = null, $id_pessoa = null, $tipo = 'A') {
        $sqlAux = '';
        if (!empty($id_inst)) {
            $sqlAux .= " AND pc.fk_id_inst = ". $id_inst;
        }
        if (!empty($id_pessoa)) {
            $sqlAux .= " AND pc.fk_id_pessoa = ". $id_pessoa;
        }

        $sql = "SELECT "
            . " pc.id_protocolo, p.id_pessoa, p.n_pessoa, pta.nome, pta.rg, pta.dias, pta.horarios, pta.autorizado, pta.conduzido_por, pta.motivo, pta.dt_update, ass.assinatura, ass.IP, ass.dt_update AS dt_assinatura, pc.fk_id_pessoa, ps.n_status, pl.ano "
            . " FROM protocolo_termo pta "
            . " JOIN protocolo_cadastro pc ON pta.fk_id_protocolo = pc.id_protocolo "
            . " JOIN pessoa p ON p.id_pessoa = pta.fk_id_pessoa "
            . " JOIN asd_assinatura ass ON ass.id_assinatura = pta.fk_id_assinatura "
            . " JOIN protocolo_status_pessoa psp ON psp.id_proto_status_pessoa = ("
                . " SELECT max(id_proto_status_pessoa) FROM protocolo_status_pessoa WHERE fk_id_protocolo = pc.id_protocolo) "
            . " JOIN protocolo_status ps ON ps.id_proto_status = psp.fk_id_proto_status "
            . " LEFT JOIN ge_periodo_letivo pl ON pc.fk_id_pl = pl.id_pl "
            . " WHERE pta.tipo = '$tipo' AND pta.ativo = 1 "
            . $sqlAux;
        $query = pdoSis::getInstance()->query($sql);
        $protocolo = $query->fetchAll(PDO::FETCH_ASSOC);

        return $protocolo;
    }

    public function getPLaluno($id_pessoa) {
        $sql = "SELECT DISTINCT"
            . " id_pl, n_pl FROM ge_turma_aluno ta "
            . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND t.fk_id_ciclo = 32 "
            . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl AND pl.semestre = 0 "
            . " WHERE ta.fk_id_pessoa = $id_pessoa ORDER BY n_pl DESC ";
        $query = pdoSis::getInstance()->query($sql);
        $PL = $query->fetchAll(PDO::FETCH_ASSOC);
        return $PL;
    }

    public function getAtualizacaoEntre($id_entre, $id_pl=null) {
        
        if (empty($id_entre)) {
            return [];
        }

        if (!empty($id_pl)) {
           $id_pl = 'AND id_pl = '.$id_pl;
        }
        $sql = "SELECT "
            . " id_entre_atual, atualizacao, id_pl, n_pl FROM apd_doc_entrevista_atualiza atual "
            . " JOIN ge_periodo_letivo pl ON pl.id_pl = atual.fk_id_pl ".$id_pl
            . " WHERE atual.fk_id_entre = $id_entre ORDER BY n_pl DESC ";
        $query = pdoSis::getInstance()->query($sql);
        $atual = $query->fetchAll(PDO::FETCH_ASSOC);

        return $atual;
    }

    public function cidGetPessoa($id_pessoa) {
        if ($id_pessoa) {
            $sql = "SELECT csc.id FROM cid_sub_categoria csc"
                    . " JOIN cid_pessoa cp on cp.fk_id_cid = csc.id_cid AND cp.fk_id_pessoa = $id_pessoa"
                    . " ORDER BY csc.id ";
            $query = pdoSis::getInstance()->query($sql);
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            $cid = '';
            if ($result) {
                foreach ($result as $k => $v) {
                   $cid = $cid ? $cid.', '. $v['id'] : $v['id'];
                }
            }
        }
        return $cid;
    }

    public function getTurmaSelect($turmas, $id_turma){
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
