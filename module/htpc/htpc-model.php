<?php

class htpcModel extends MainModel {

    public $db;
    
    /**
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

        if ($this->db->tokenCheck('uploadDoc')) {
            $this->uploadDoc();
        } elseif ($this->db->tokenCheck('uploadDocPautaProposta')) {
            $this->uploadDocPautaProposta();
        } elseif ($this->db->tokenCheck('ireplaceATA', true)) {
            $this->ireplaceATA();
        }
    }

    public function proporPauta()
    {
        if (!empty($_POST[1]['id_pauta_proposta]'])) {
            $id_pauta_proposta = $_POST[1]['id_pauta_proposta]'];
        }

        if (!empty($_POST[1]['id_curso'])) {
            $id_cursos = $_POST[1]['id_curso'];
        }

        if (!empty($_POST['last_id'])) {
            $id_pauta_proposta = $_POST['last_id'];
        }

        if ( !empty($id_pauta_proposta) ) {
            $this->db->delete('htpc_pauta_proposta_curso', 'fk_id_pauta_proposta', $id_pauta_proposta, 1);

            if (!empty($id_cursos)) {
                foreach ($id_cursos as $key => $id_curso) {
                    $ins = [];
                    $ins['fk_id_pauta_proposta'] = $id_pauta_proposta;
                    $ins['fk_id_curso'] = $id_curso;
                    $this->db->ireplace('htpc_pauta_proposta_curso', $ins, 1);
                }
            }
        }

        $dados = $this->getPautaProposta();
        if (!empty($dados)) {
            foreach ($dados as $k => $v) {
                $anexos = $this->getAnexosPautaProposta($v['id_pauta_proposta']);
                if (!empty($anexos) || !empty(self::isCoordenadoria()) ) {
                    $dados[$k]['upload'] = '<button class="btn btn-outline-info" onclick="up(' . $v['id_pauta_proposta'] . ')">Anexo</button>';
                } else {
                    $dados[$k]['upload'] = '';
                }

                $dados[$k]['disponibilidade'] = !empty($v['disponivel']) ? ' <span class="alert-success">disponível</span> Disponível' : ' <span class="alert-danger">não disponível</span> Não Disponivel';
                $dados[$k]['edit'] = '<button class="btn btn-outline-info" onclick="editar(' . $v['id_pauta_proposta'] . ')">Editar</button>';
                $dados[$k]['n_pauta'] = '<span style="white-space:pre-wrap">'.$v['n_pauta'].'</span>';
                $dados[$k]['cursos'] = toolErp::virgulaE( explode(',', $v['cursos']));
            }
        }

        $formDados['array'] = $dados;
        $formDados['fields'] = [
            'Disponibilidade' => 'disponibilidade',
            'Data Início' => 'dt_inicio',
            'Data Fim' => 'dt_fim',
            'Pauta' => 'n_pauta',
            'Cursos' => 'cursos',
            '||2' => 'upload',
            '||1' => 'edit'
        ];

        if (!empty(self::isCoordenador())) {
            unset($formDados['fields']['||1']);
        }

        return [
            'formDados' => $formDados,
        ];
    }

    public function cadastrarPropostaPauta()
    {
        $closeModal = filter_input(INPUT_POST, 'closeModal', FILTER_SANITIZE_NUMBER_INT);
        $id_pauta_proposta = filter_input(INPUT_POST, 'id_pauta_proposta', FILTER_SANITIZE_NUMBER_INT);
        $dt_inicio = '';
        $dt_fim = '';
        $n_pauta = '';
        $visivel_professor = 0;
        $cursos = $this->getCursos();
        $cursosProposta = [];

        if (!empty($id_pauta_proposta)) {
            $propostaArray = $this->getPautaProposta($id_pauta_proposta);
            if (!empty($propostaArray)) {
                foreach ($propostaArray as $k => $v) {
                    $proposta = $v;
                }
                $dt_inicio = $this->atribuiData($proposta['dt_inicio']);
                $dt_fim = $this->atribuiData($proposta['dt_fim']);
                $n_pauta = $proposta['n_pauta'];
                $visivel_professor = $proposta['visivel_professor'];
            }

            $cursosProposta = $this->getCursosPautaProposta($id_pauta_proposta);
        }

        return [
            'closeModal' => $closeModal,
            'id_pauta_proposta' => $id_pauta_proposta,
            'dt_inicio' => $dt_inicio,
            'dt_fim' => $dt_fim,
            'n_pauta' => $n_pauta,
            'cursos' => $cursos,
            'cursosProposta' => $cursosProposta,
        ];
    }

    public function atribuiData($data = null){
        if (!empty($data)) {
           $data = $data; 
        }else{
            $data = date("Y-m-d");
        }
        return $data;
    }

    public function listarPautas(){
        $old = filter_input(INPUT_POST, 'old', FILTER_SANITIZE_NUMBER_INT);
        $desativarPauta = filter_input(INPUT_POST, 'desativarPauta', FILTER_SANITIZE_NUMBER_INT);
        $id_pauta = filter_input(INPUT_POST, 'id_pauta', FILTER_SANITIZE_NUMBER_INT);

        if (!empty($desativarPauta)){
            $this->pautaDesativar($id_pauta);
        }

        $dados = $this->getPauta(null, empty($old));
        if (!empty(self::isProfessor())){
            $pautas_prop = $this->getPautaProposta(null, 1, empty($old));
            $dados = array_merge($dados, $pautas_prop);
        }

        if (!empty($dados)) {
            foreach ($dados as $k => $v) {
                $dados[$k]['n_pauta'] = '<span style="white-space:pre-wrap">'.$v['n_pauta'].'</span>';

                if ( !isset($v['id_pauta_proposta']) ){
                    $dados[$k]['data_pauta'] = $v['dt_pauta'];
                    $dados[$k]['upload'] = '<button class="btn btn-outline-info" onclick="up(' . $v['id_pauta'] . ')">Anexo</button>';
                    $dados[$k]['edit'] = '<button class="btn btn-outline-info" onclick="editar(' . $v['id_pauta'] . ')">Editar</button>';
                    $dados[$k]['desativar'] = '<button class="btn btn-outline-danger" onclick="desativar(' . $v['id_pauta'] . ')">Desativar</button>';
                    $dados[$k]['pdf'] = '<button class="btn btn-outline-secondary" onclick="pdf('. $v['id_pauta'] .')">PDF</button>';
                } else {
                    $dados[$k]['upload'] = '<button class="btn btn-outline-info" onclick="upPP(' . $v['id_pauta_proposta'] . ')">Anexo</button>';
                    $dados[$k]['data_pauta'] = $v['dt_inicio'] .' a '. $v['dt_fim'];
                }
            }
        }

        $formDados['array'] = $dados;
        $formDados['fields'] = [
            'Data da Pauta' => 'data_pauta',
            'Pauta' => 'n_pauta',
            'Proposto por' => 'proposto_por',
            '||1' => 'pdf',
            '||2' => 'upload',
            '||3' => 'edit',
            '||4' => 'desativar',
        ];

        if (!empty(self::isProfessor())){
            unset($formDados['fields']['||3']);
            unset($formDados['fields']['||4']);
        } else {
            unset($formDados['fields']['proposto_por']);
        }

        return [
            'formDados' => $formDados,
            'old' => $old,
            'dados' => $dados,
        ];
    }

    public function cadastrarPauta(){
        $closeModal = filter_input(INPUT_POST, 'closeModal', FILTER_SANITIZE_NUMBER_INT);
        $id_pauta = filter_input(INPUT_POST, 'id_pauta', FILTER_SANITIZE_NUMBER_INT);
        $id_pauta_proposta = filter_input(INPUT_POST, 'id_pauta_proposta', FILTER_SANITIZE_NUMBER_INT);
        $pautaArray = [];
        $propostaArraySelected = [];
        $anexos = [];

        if (!empty($id_pauta)) {
            $pautaArray = $this->getPauta($id_pauta);
            $anexos = $this->getAnexosPauta($id_pauta);
        }

        if (!empty($id_pauta_proposta)) {
            $propostaArraySelected = $this->getPautaProposta($id_pauta_proposta);
        }

        $propostaArray = $this->getPautaProposta();
        foreach ($propostaArraySelected as $k => $v) {
            $proposta = $v;
        }
        $pauta = [];
        $pauta['visivel_professor'] = 1;
        foreach ($pautaArray as $k => $v) {
            $pauta = $v;
        }
        $dt_pauta = $this->atribuiData(@$pauta['dt_pauta']);
        $periodoList = $this->getPeriodo();

        if (!empty($proposta['n_pauta'])) {
           $n_pauta = $proposta['n_pauta']; 
        }else{
           $n_pauta = @$pauta['n_pauta']; 
        }

        return [
            'closeModal' => $closeModal,
            'PropostaPauta' => $propostaArray,
            'PropostaPautaSelected' => @$proposta['n_pauta'],
            'dt_pauta' => $dt_pauta,
            'periodoList' => $periodoList,
            'id_pauta' => $id_pauta,
            'visivel_professor' => $pauta['visivel_professor'],
            'n_pauta' => $n_pauta,
            'anexos' => $anexos,
        ];
    }

    public function listarAtas($index = null){
        $id_prof = self::isProfessor();
        $dados = $this->getAta(null, !empty($id_prof) ? ['A', 'F'] : null, !empty($id_prof) ? $this->atribuiData() : null);
        $pautas = [];

        if (!empty($dados)) {

            $rm = '';
            if (!empty($id_prof)){
                $p = $this->getProfessores(null, null, $id_prof);
                if (!empty($p)) {
                    $p = current($p);
                    $rm = $p['rm'];
                }
            }

            if ( isset($_POST[1]['id_ata']) && isset($_POST['ausenteParaTodos']) && $_POST['ausenteParaTodos'] === true ) {
                $this->geraAusencia($_POST[1]['id_ata']);
            }

            $btnCor = 'outline-';
            $btnTam = '';
            if (!empty($index)) {
                $btnTam = ' btn-lg ';
                $btnCor = '';
            }

            $periodoList = $this->getPeriodo();
            foreach ($dados as $k => $v)
            {
                if ($v['status'] == 'F') {
                    $dados[$k]['action'] = '<button class="btn btn-outline-warning" onclick="addEmenta(' . $v['id_ata'] . ')">Adicionar Ementa</button>';
                    $dados[$k]['pdf'] = '<button class="btn btn-outline-secondary" onclick="pdf('. $v['id_ata'] .')">PDF</button>';
                } else {
                    $dados[$k]['edit'] = '<button class="btn btn-outline-primary" onclick="editATA(' . $v['id_ata'] . ')">Acessar ATA</button>';
                }

                if ($v['status'] == 'A') {
                    if (empty($v['prof_presente'])) {
                        $dados[$k]['presenca_prof'] = '<button class="btn btn-'.$btnCor.'success '.$btnTam.'" onclick="presencaProf('. $v['id_ata'] .', '.$id_prof.',\''.$rm.'\')">Marcar Presença</button>';
                    } else {
                        $dados[$k]['presenca_prof'] = '<div class="alert alert-success">Presente</div>';
                    }
                } else {
                    $dados[$k]['presenca_prof'] = '';
                }

                $dados[$k]['n_periodo'] = $periodoList[$v['periodo']];
                $dados[$k]['view'] = '<button class="btn btn-'.$btnCor.'info '.$btnTam.'" onclick="view(' . $v['id_ata'] . ')">Visualizar ATA</button>';
            }

            if (!empty($id_prof) && !empty($dados)){
                $pautas = $this->getPautasATA($dados[0]['id_ata']);
                //$pautas_prop = $this->getPautaProposta(null, 1);
                //$pautas = array_merge($pautas, $pautas_prop);
            }
        }

        $formDados['array'] = $dados;
        $formDados['fields'] = [
            'Data da Ata' => 'dt_ata',
            'Período' => 'n_periodo',
            'Status' => 'n_status',
            '||1' => 'pdf',
            '||2' => 'edit',
            '||3' => 'view',
            '||4' => 'action',
            '||5' => 'presenca_prof',
        ];

        if (!empty($id_prof)){
            unset($formDados['fields']['||2']);
            unset($formDados['fields']['||4']);
        } else {
            unset($formDados['fields']['||5']);
        }

        return [
            'formDados' => $formDados,
            'dados' => $dados,
            'isProfessor' => $id_prof,
            'pautas' => $pautas,
        ];
    }

    public function cadastrarAta(){
        $closeModal = filter_input(INPUT_POST, 'closeModal', FILTER_SANITIZE_NUMBER_INT);
        $id_ata = filter_input(INPUT_POST, 'id_ata', FILTER_SANITIZE_NUMBER_INT);
        $id_pauta = filter_input(INPUT_POST, 'id_pauta', FILTER_SANITIZE_NUMBER_INT);
        $id_ata_pauta = filter_input(INPUT_POST, 'id_ata_pauta', FILTER_SANITIZE_NUMBER_INT);
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
        $ataArray = [];
        $ata = [];
        $pauta = [];
        $pautaArraySelected = [];
        $pautaArray = [];
        $pautaATA = [];
        $ementas = [];
        $anexos = [];
        $listaPresenca = [];

        if (!empty($id_ata)) {
            $ataArray = $this->getAta($id_ata);
            $pautaArray = $this->getPauta(null, true);

            if (!empty($id_pauta) && !empty($pautaArray) && !empty($pautaArray[$id_pauta])) {
                $pautaArraySelected = $pautaArray[$id_pauta];

                if (!empty($action)) {
                    if ($action == 'insert') {
                        
                        //para q a pagina de edit se mantenha editando
                        $action = 'edit';

                        $existePautaNaATA = $this->getPautasATA($id_ata, $id_pauta);
                        if ( empty($existePautaNaATA) ) {
                            $ins = [];
                            $ins['fk_id_ata'] = $id_ata;
                            $ins['fk_id_pauta'] = $id_pauta;
                            $ins['ordem'] = $this->getUltimaOrdem($id_ata);
                            $this->db->ireplace('htpc_ata_pauta', $ins, 1);

                            //insere os anexos da pauta
                            $anexPauta = $this->getAnexosPauta($id_pauta);
                            foreach ($anexPauta as $key => $value)
                            {
                                $ins = [];
                                $ins['fk_id_ata'] = $id_ata;
                                $ins['fk_id_pauta'] = $id_pauta;
                                $ins['fk_id_pessoa'] = toolErp::id_pessoa();
                                $ins['n_up'] = $value['n_up'];
                                $ins['link'] = $value['link'];
                                $ins['tipo'] = $value['tipo'];
                                $this->db->ireplace('htpc_ata_up', $ins, 1);
                            }
                        }
                    }

                    if ($action == 'remove') {
                        //para q a pagina de edit se mantenha editando
                        $action = 'edit';

                        $this->db->delete('htpc_ata_pauta', 'id_ata_pauta', $id_ata_pauta, 1);

                        //remove os anexos da pauta/ata
                        $anexATA = $this->getAnexosATA($id_ata);
                        foreach ($anexATA as $key => $value) {
                            $this->db->delete('htpc_ata_up', 'id_ata_up', $value['id_ata_up'], 1);
                        }
                    }
                }
            }

            //seta a ordem 
            if (!empty($action) && $action == 'order')
            {
                //para q a pagina de edit se mantenha editando
                $action = 'edit';

                if (!empty($_POST['itens_id']))
                {
                    foreach ($_POST['itens_id'] as $k => $v)
                    {
                        $ins = [];
                        $ins['id_ata_pauta'] = $_POST['itens_id'][$k];
                        $ins['ordem'] = $_POST['itens_ordem'][$k];
                        $this->db->ireplace('htpc_ata_pauta', $ins, 1);
                    }
                }
            }

            // recupera os dados da ATA
            $ementas = $this->getEmentas($id_ata);
            $pautaATA = $this->getPautasATA($id_ata);
            $anexos = $this->getAnexosATA($id_ata);
            $listaPresenca = $this->getListaPresenca($id_ata);

            if (!empty($anexos)) {
                foreach ($anexos as $k => $v) {
                    $anexos[$k]['docx'] = formErp::submit('&#8681;', null, null, HOME_URI . '/pub/htpc/' . $v['link'], 1,null,'btn btn-outline-info');
                }
            }

            if (!empty($pautaATA)){
                foreach ($pautaATA as $key => $value) {
                    $pautaATA[$key]['anexos'] = $this->getAnexosPauta($value['id_pauta']);
                }
            }
        }

        foreach ($pautaArraySelected as $k => $v) {
            $pauta = $v;
        }

        $todosStatus = $this->getStatusATA();
        $periodoList = $this->getPeriodo();
        $n_ata = $this->setTextDefault();
        $dt_ata = '';
        $periodo = '';
        $n_periodo = '';
        $status = 'N';
        if (!empty($ataArray)) {
            foreach ($ataArray as $k => $v) {
                $ata = $v;
            }
            $dt_ata = $this->atribuiData($ata['dt_ata']);
            $n_ata = $ata['n_ata'];
            $status = $ata['status'];
            $periodo = $ata['periodo'];
            if (!empty($periodo)) {
                $n_periodo = $periodoList[$periodo];
            }
        }

        //cadastro
        if (empty($id_ata)) {
            unset($todosStatus['F']);
        } else {
            if ($status == 'N'){
                unset($todosStatus['F']);
            } elseif ($status == 'A') {
                unset($todosStatus['N']);
            } else {
                $todosStatus = [];
            }
        }

        $btn = $this->btnSalvarAta($status);

        return [
            'closeModal' => $closeModal,
            'pautas' => $pautaArray,
            'pautaArraySelected' => @$pauta['n_ata'],
            'dt_ata' => $dt_ata,
            'id_ata' => $id_ata,
            'status' => $status,
            'n_ata' => $n_ata,
            'btn' => $btn,
            'periodoList' => $periodoList,
            'periodo' => $periodo,
            'n_periodo' => $n_periodo,
            'pautaATA' => $pautaATA,
            'ementas' => $ementas,
            'action' => $action,
            'todosStatus' => $todosStatus,
            'anexos' => $anexos,
            'listaPresenca' => $listaPresenca,
        ];
    }

    public function presenca(){
        $id_ata = filter_input(INPUT_POST, 'id_ata', FILTER_SANITIZE_NUMBER_INT);
        $fk_id_pessoa = filter_input(INPUT_POST, 'fk_id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $rm = filter_input(INPUT_POST, 'rm', FILTER_SANITIZE_STRING);

        if (empty($id_ata)) {
            toolErp::alert("Parâmetro esperado não encontrado [id_ata]");
            ?>
            <script>
                history.back();
            </script>
            <?php
        }

        $presentes = [];
        //desativa registro após gerar remoção de presença
        $pre = $_POST[1]['presente'] ?? null;
        if (!empty($_POST['last_id']) && $pre == '0' ) {
            $presentPessoa = $this->getListaPresenca($id_ata, $_POST[1]['fk_id_pessoa'], $_POST[1]['rm'], 1);

            if (!empty($presentPessoa))
            {
                $logDesc = "Removeu Presença na ATA ($id_ata) para ";
                if (toolErp::sexo_pessoa($_POST[1]['fk_id_pessoa']) == 'F'){
                    $logDesc .= "a Professora ";
                } else {
                    $logDesc .= "o Professor ";
                }
                $logDesc .= toolErp::n_pessoa($_POST[1]['fk_id_pessoa']);
                log::logSet($logDesc);

                $presentPessoa = current($presentPessoa);
                $ins = [];
                $ins['id_presenca'] = $presentPessoa['id_presenca'];
                $ins['ativo'] = 0;
                $this->db->ireplace('htpc_presenca', $ins, 1);
            }
        }

        if (!empty($fk_id_pessoa)) {
            $presentes = $this->getListaPresenca($id_ata, null, null, 1);

            if ( !isset($presentes[$fk_id_pessoa.'-'.$rm]) ) {

                $logDesc = "Atribuiu Presença na ATA ($id_ata) para ";
                if (toolErp::sexo_pessoa($fk_id_pessoa) == 'F'){
                    $logDesc .= "a Professora ";
                } else {
                    $logDesc .= "o Professor ";
                }
                $logDesc .= toolErp::n_pessoa($fk_id_pessoa);

                $ins = [];
                $ins['fk_id_ata'] = $id_ata;
                $ins['fk_id_pessoa_reg'] = toolErp::id_pessoa();
                $ins['fk_id_pessoa'] = $fk_id_pessoa;
                $ins['rm'] = $rm;
                $ins['presente'] = 1;
                $this->db->ireplace('htpc_presenca', $ins, 1);
                log::logSet($logDesc);
            }
        }

        if ( !empty($_POST['back']) ){
            if ($_POST['back']=="listATA" ){
                header('Location:'. HOME_URI .'/htpc/atas');
            } elseif ($_POST['back']=="indexATA" ){
                header('Location:'. HOME_URI .'/htpc/index');
            }
        }

        //retorna a lista de presença atualizada
        $presentes = $this->getListaPresenca($id_ata);

        $dia_semana_ata = null;
        $dt_ata = null;
        $periodo = null;
        $status = null;

        $ataArray = $this->getAta($id_ata);
        if (!empty($ataArray)) {
            $dia_semana_ata = $ataArray[0]['dia_semana_ata'];
            $dt_ata = $ataArray[0]['dt_ata'];
            $periodo = $ataArray[0]['periodo'];
            $status = $ataArray[0]['status'];
        }

        $pf = $this->getProfessores($dia_semana_ata, $periodo);
        $disci = $this->getDisciplinas();

        foreach ($pf as $k => $v) {
            $nd = NULL;
            $discip = explode('|', $v['disciplinas']);
            $cont = 4;

            foreach ($discip as $d) {
                if ($d != '') {
                    if ($cont < count($discip)) {
                        @$nd .= $disci[$d] . ', ';
                    } else {
                        @$nd .= $disci[$d] . ' e ';
                    }
                    $cont++;
                }
            }
            $pf[$k]['disc'] = substr($nd, 0, -3);
            if (!empty($v['hac_dia'])) {
                $pf[$k]['hac_dia'] = '<div style="white-space: nowrap">' . $v['hac_dia'] . 'ª Feira</div>';
            } else {
                $pf[$k]['hac_dia'] = '<span data-toggle="tooltip" data-placement="top" title="Não se aplica">NSA<span>';
            }
            if (empty($v['hac_periodo'])) {
                $pf[$k]['hac_periodo'] = '<span data-toggle="tooltip" data-placement="top" title="Não se aplica">NSA<span>';
            }

            // está na lista
            if ( isset($presentes[$v['id_pessoa'].'-'.$v['rm']]) ) {
                if ( empty($presentes[$v['id_pessoa'].'-'.$v['rm']]['presente']) ) {
                    $pf[$k]['presenca'] = '<small class="alert alert-danger" id="label_'.$v['id_pessoa'].'-'.$v['rm'].'">Ausente</small>';
                    $pf[$k]['up'] = '<span style="white-space:pre-wrap">'.$presentes[$v['id_pessoa'].'-'.$v['rm']]['justificativa'].'</span>';
                } else {
                    $pf[$k]['presenca'] = '<small class="alert alert-success" id="label_'.$v['id_pessoa'].'-'.$v['rm'].'">Presente</small>';
                    $pf[$k]['up'] = '<div id="'.$v['id_pessoa'].'-'.$v['rm'].'" data-id_pessoa="'.$v['id_pessoa'].'" data-rm="'.$v['rm'].'" data-presente="1"><a href="javascript:void(0)" class="btn btn-outline-danger" onclick="remove('.$id_ata.', '. $v['id_pessoa'].', \''.$v['rm'] .'\')">Retirar Presença</a></div>';
                }
            } else {
                if ($status == 'F') {
                    $pf[$k]['presenca'] = '<small class="alert alert-danger" id="label_'.$v['id_pessoa'].'-'.$v['rm'].'">Ausente</small>';
                    $pf[$k]['up'] = '<span style="white-space:pre-wrap">ATA Fechada sem indicação de presença</span>';
                } else {
                    $pf[$k]['presenca'] = '<small class="alert alert-danger" id="label_'.$v['id_pessoa'].'-'.$v['rm'].'">Ausente</small>';
                    $pf[$k]['up'] = '<div id="'.$v['id_pessoa'].'-'.$v['rm'].'" data-id_pessoa="'.$v['id_pessoa'].'" data-rm="'.$v['rm'].'" data-presente=""><a href="javascript:void(0)" class="btn btn-outline-success" onclick="presente('.$id_ata.', '. $v['id_pessoa'].', \''.$v['rm'] .'\')">Atribuir Presença</a></div>';
                    //onclick="presente('. $v['id_pessoa'].', \''.$v['rm'] .'\')"
                }
            }
        }

        $formDados['array'] = $pf;
        $formDados['fields'] = [
            'Professor' => 'n_pessoa',
            'E-mail' => 'email',
            'Matrícula' => 'rm',
            'Disciplinas' => 'disc',
            'Dia' => 'hac_dia',
            'Período' => 'hac_periodo',
            'Status' => 'presenca',
            'Ação' => 'up',
        ];

        return [
            'formDados' => $formDados,
            'dt_ata' => $dt_ata,
            'id_ata' => $id_ata,
            'presentes' => $presentes,
        ];
    }

    public function presencaRemover(){
        $id_ata = filter_input(INPUT_POST, 'id_ata', FILTER_SANITIZE_NUMBER_INT);
        $fk_id_pessoa = filter_input(INPUT_POST, 'fk_id_pessoa', FILTER_SANITIZE_NUMBER_INT);
        $rm = filter_input(INPUT_POST, 'rm', FILTER_SANITIZE_STRING);
        $acao = filter_input(INPUT_POST, 'acao', FILTER_SANITIZE_STRING);

        return [
            'id_ata' => $id_ata,
            'fk_id_pessoa' => $fk_id_pessoa,
            'rm' => $rm,
            'acao' => $acao,
        ];
    }

    public function ementaCadastro(){
        $id_ata = filter_input(INPUT_POST, 'id_ata', FILTER_SANITIZE_NUMBER_INT);
        $closeModal = filter_input(INPUT_POST, 'closeModal', FILTER_SANITIZE_NUMBER_INT);

        if (!empty($_POST['last_id'])) {
            log::logSet("Criou uma nova Ementa na ATA ($id_ata)");
        }

        return [
            'id_ata' => $id_ata,
            'closeModal' => $closeModal,
        ];
    }

    public function presencaGet(){
        $id_ata = filter_input(INPUT_GET, 'id_ata', FILTER_SANITIZE_NUMBER_INT);
        $presentes = $this->getListaPresenca($id_ata);
        return $presentes;
    }

    public function saveATAPut()
    {
        $id_ata = filter_input(INPUT_GET, 'id_ata', FILTER_SANITIZE_NUMBER_INT);
        $status = $_POST[1]['status'] ?? null;
        $dt_ata = $_POST[1]['dt_ata'] ?? null;
        $periodo = $_POST[1]['periodo'] ?? null;
        $n_ata = $_POST[1]['n_ata'] ?? null;
        $fk_id_pessoa = $_POST[1]['fk_id_pessoa'] ?? null;

        $update = false;
        $data = [];        
        if ( !empty($id_ata) ) {
            if ( !empty($status) ) $data['status'] = $status;
            if ( !empty($dt_ata) ) $data['dt_ata'] = $dt_ata;
            if ( !empty($periodo) ) $data['periodo'] = $periodo;
            if ( !empty($n_ata) ) $data['n_ata'] = $n_ata;
            if ( !empty($fk_id_pessoa) ) $data['fk_id_pessoa'] = $fk_id_pessoa;

            if (!empty($data)) {
                $data['id_ata'] = $id_ata;
                $this->db->ireplace('htpc_ata', $data, 1);
                $update = true;
            }
        }

        return $update;
    }

    public function listarAnexosPauta(){
        $fk_id_pauta = filter_input(INPUT_POST, 'id_pauta', FILTER_SANITIZE_NUMBER_INT);

        $anexos = $this->getAnexosPauta($fk_id_pauta);
        if (!empty($anexos)) {
            foreach ($anexos as $k => $v) {
                $anexos[$k]['docx'] = formErp::submit('&#8681;', null, null, HOME_URI . '/pub/htpc/' . $v['link'], 1,null,'btn btn-outline-info');
            }
        }

        return [
            'fk_id_pauta' => $fk_id_pauta,
            'anexos' => $anexos,
        ];
    }

    public function uploadDoc() {
        $fk_id_pauta = filter_input(INPUT_POST, 'fk_id_pauta', FILTER_SANITIZE_NUMBER_INT);
        $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);

        if (!empty($_FILES['arquivo'])) {
            @$exten = end(explode('.', $_FILES['arquivo']['name'][$i]));
            $nome_origin = $_FILES['arquivo']['name'];
            $file = ABSPATH . '/pub/htpc/';
            $upload = new upload($file, $fk_id_pauta);
            $end = $upload->up();
            if ($end) {
                $ins['fk_id_pauta'] = $fk_id_pauta;
                $ins['fk_id_pessoa'] = toolErp::id_pessoa();
                $ins['n_up'] = toolErp::escapaAspa($nome_origin);
                $ins['link'] = $end;
                $ins['tipo'] = $tipo;
                $this->db->ireplace('htpc_pauta_up', $ins);
            } else {
                toolErp::alertModal('Erro ao enviar. Tente novamente');
            }
        } else {
            toolErp::alertModal('Erro ao enviar. Tente novamente');
        }

        return $this->proporPauta();
    }

    public function pautaDesativar($id_pauta){
        $ins = [];
        $ins['id_pauta'] = $id_pauta;
        $ins['ativo'] = '0';
        $this->db->ireplace('htpc_pauta', $ins);
        return true;
    }

    public function ireplaceATA()
    {
        try {
            if (!empty($_POST[1])) {
                $dados = [];

                $dt_ata  = $_POST[1]['dt_ata']  ?? null;
                $periodo = $_POST[1]['periodo'] ?? null;
                $id_ata  = $_POST[1]['id_ata']  ?? null;

                if (empty($dt_ata)) {
                    throw new Exception('A data é obrigatória');
                }

                if (empty($periodo)) {
                    throw new Exception('O período é obrigatório');
                }

                $ata_existe = $this->getAta(null, null, $dt_ata, $periodo);
                if (!empty($ata_existe) && !empty($id_ata)) {
                    $findAta = array_column($ata_existe, 'id_ata');
                    $findAta = array_filter($findAta);
                    $k = array_search($id_ata, $findAta);
                    if ($k !== false) {
                        unset($ata_existe[$k]);
                    }
                }

                if (!empty($ata_existe)) {

                    throw new Exception('Já existe uma ATA para a data '.dataErp::converteBr($dt_ata).' no período da '.dataErp::periodoDoDia($periodo));
                }

                $dia_semana_ata = date('w', strtotime($dt_ata));
                $profs = $this->getProfessores($dia_semana_ata+1, $periodo);
                if ( empty($profs) ) {
                    throw new Exception('Não existem professores para '. dataErp::diasDaSemana($dia_semana_ata) .' no período da '. dataErp::periodoDoDia($periodo));
                }

                // se está inserindo uma ATA
                if (empty($id_ata))
                {
                    $logDesc = "Criou uma nova ATA";

                } else {
                    $dados = $this->getAta($id_ata);
                    $logDesc = "Atualizou a ATA: ";
                }

                $this->db->ireplace('htpc_ata', $_POST[1]);

                if (!empty($dados)) {
                    $dados = current($dados);
                    $fields = [
                        'status' => 'Status',
                        'dt_ata' => 'Data da ATA',
                        'periodo' => 'Período',
                        'n_ata' => 'ATA',
                    ];
                    $alt = [];
                    foreach ($fields as $key => $value) {
                        if ( !isset($dados[$key]) ) $dados[$key] = '';
                        if ( !isset($_POST[1][$key]) ) $_POST[1][$key] = '';
                        if ($dados[$key] != $_POST[1][$key]) {
                            $alt[] = $value;
                        }
                    }
                    $logDesc .= empty($alt) ? "Nenhum dado alterado" : toolErp::virgulaE($alt);        
                }

                log::logSet($logDesc);
            }
        } catch (Exception $e) {
            tool::alert($e->getMessage());
            return false;
        }

        return true;
    }

    public function listarAnexosPautaProposta(){
        $fk_id_pauta_proposta = filter_input(INPUT_POST, 'id_pauta_proposta', FILTER_SANITIZE_NUMBER_INT);

        $anexos = $this->getAnexosPautaProposta($fk_id_pauta_proposta);
        if (!empty($anexos)) {
            foreach ($anexos as $k => $v) {
                $anexos[$k]['docx'] = formErp::submit('&#8681;', null, null, HOME_URI . '/pub/htpc/' . $v['link'], 1,null,'btn btn-outline-info');
            }
        }

        return [
            'fk_id_pauta_proposta' => $fk_id_pauta_proposta,
            'anexos' => $anexos,
        ];
    }

    public function uploadDocPautaProposta() {
        $fk_id_pauta_proposta = filter_input(INPUT_POST, 'fk_id_pauta_proposta', FILTER_SANITIZE_NUMBER_INT);
        $tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING);

        if (!empty($_FILES['arquivo'])) {
            @$exten = end(explode('.', $_FILES['arquivo']['name'][$i]));
            $nome_origin = $_FILES['arquivo']['name'];
            $file = ABSPATH . '/pub/htpc/';
            $upload = new upload($file, $fk_id_pauta_proposta);
            $end = $upload->up();
            if ($end) {
                $ins['fk_id_pauta_proposta'] = $fk_id_pauta_proposta;
                $ins['fk_id_pessoa'] = toolErp::id_pessoa();
                $ins['n_up'] = toolErp::escapaAspa($nome_origin);
                $ins['link'] = $end;
                $ins['tipo'] = $tipo;
                $this->db->ireplace('htpc_pauta_proposta_up', $ins);
            } else {
                toolErp::alertModal('Erro ao enviar. Tente novamente');
            }
        } else {
            toolErp::alertModal('Erro ao enviar. Tente novamente');
        }

        return $this->listarPautas();
    }
    public static function getStatusATA(){
        $status = [
            'N'=> 'Novo',
            'A'=> 'Aberto',
            'F'=> 'Fechado',
        ];
        return $status;
    }

    public static function getPeriodo(){
        $periodo = [
            'M'=> 'Manhã',
            'T'=> 'Tarde',
            'N'=> 'Noite',
        ];
        return $periodo;
    }

    public static function isProfessor() {
        if (in_array(user::session('id_nivel'), [24])) {
            $is = toolErp::id_pessoa();
        } else {
            $is = 0;
        }
        return $is;
    }

    public static function isCoordenador() {
        if (in_array(user::session('id_nivel'), [48])) {
            $is = toolErp::id_pessoa();
        } else {
            $is = 0;
        }
        return $is;
    }

    public static function isCoordenadoria() {
        if (in_array(user::session('id_nivel'), [26])) {
            $is = toolErp::id_pessoa();
        } else {
            $is = 0;
        }
        return $is;
    }

    public function setTextDefault()
    {
        $oEscola = new escola();
        $end = $oEscola->endereco();
        $end = current($end);
        $nome = toolErp::n_pessoa(toolErp::id_pessoa());
        $sexo = toolErp::sexo_pessoa(toolErp::id_pessoa());

        if ($sexo == 'M'){
            $tSexo = "o Professor Coordenador Pedagógico";
        } else {
            $tSexo = "a Professora Coordenadora Pedagógica";
        }

        $art = toolErp::sexoArt($sexo);
        $text = "ATA DE HTPC - HORA DE TRABALHO PEDAGÓGICO COLETIVO DA ".$end['nome'].".\n\n\nAos ". date('d') ." dias do mês de ". dataErp::meses(date('m')) ." do ano de ". toolErp::valorPorExtenso(date('Y'), false) ." reuniram-se em uma das dependências da ".$end['nome'].", situada na ". $end['logradouro'] .", nº ". $end['num'] .", ". $end['bairro'] .", ". $end['cidade'] ." - ". $end['uf'] .", ". $tSexo ." ". $nome .", juntamente com os professores que lecionam nesta Unidade Escolar, para a realização da HTPC - Hora de Trabalho Pedagógico Coletivo, no horário das ___h às ___h. Na oportunidade foram abordados os seguintes assuntos:
            \n\n\n\n\nNada mais havendo a tratar, eu, ". $nome .", dei por encerrada a reunião e a ATA segue assinada por meio da seguinte lista de presença:";
        return $text;
    }

    public function getAta($id = null, $status = null, $data = null, $periodo = null){
        $sqlAux = "";
        if (!empty($id)) {
            $sqlAux .= " AND a.id_ata = $id ";
        }

        if (!empty($status)) {
            if (is_array($status)) {
                $status = implode("', '", $status);
            }

            $sqlAux .= " AND a.status IN('$status') ";
        }

        if (!empty($data)) {
            $sqlAux .= " AND a.dt_ata = '$data' ";
        }

        if (!empty($periodo)) {
            $sqlAux .= " AND a.periodo = '$periodo' ";
        }

        $id_prof = self::isProfessor();

        //coordenador ve ATAs criadas por ele
        if ( !empty(self::isCoordenador()) ) {
            $sqlAux .= " AND a.fk_id_inst = ". toolErp::id_inst();
        }

        //professor ve ATAs da escola
        if ( !empty($id_prof) ) {
            $sqlAux .= " AND a.fk_id_inst IN(". $this->getSQLEsolasProf($id_prof).") ";
        }

        $sql = "SELECT a.id_ata, a.n_ata, a.dt_ata, IF(a.dt_ata IS NULL, NULL, DATE_FORMAT(a.dt_ata, '%w')+1) dia_semana_ata, a.periodo, a.status, IF(a.status = 'N', 'NOVA', IF(a.status = 'A', 'ABERTA', 'FECHADA')) n_status, (SELECT id_presenca FROM htpc_presenca WHERE fk_id_ata = a.id_ata AND fk_id_pessoa = $id_prof AND presente = 1 AND ativo = 1 LIMIT 1) prof_presente "
                . " FROM htpc_ata a"
                . " WHERE 1 = 1 "
                . $sqlAux
                . " ORDER BY IF(a.dt_ata = DATE(NOW()), 0, 1), a.dt_ata DESC";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function getPauta($id = null, $abertas = null){
        $sqlAux = "";
        if (!empty($id)) {
            $sqlAux .= " AND pt.id_pauta = $id ";
        }

        if (!is_null($abertas)) {
            if (!empty($abertas)) {
                $sqlAux .= " AND DATE_ADD(pt.dt_pauta, INTERVAL 3 DAY) >= DATE(NOW()) ";
            } else {
                $sqlAux .= " AND DATE_ADD(pt.dt_pauta, INTERVAL 3 DAY) < DATE(NOW()) ";
            }
        }

        //coordenador ve pautas criadas por ele
        if ( !empty(self::isCoordenador()) ) {
            $sqlAux .= " AND pt.fk_id_inst = ". toolErp::id_inst();
        }

        //professor ve pautas da escola
        if ( !empty(self::isProfessor()) ) {
            $sqlAux .= " AND pt.fk_id_inst IN(". $this->getSQLEsolasProf(self::isProfessor()).") ";
            $sqlAux .= " AND pt.visivel_professor = 1 ";
        }

        $sql = "SELECT pt.id_pauta, pt.n_pauta, pt.dt_pauta, pt.visivel_professor, 'Coordenador' AS proposto_por "
                . " FROM htpc_pauta pt"
                . " WHERE pt.ativo = 1 "
                . $sqlAux
                . " ORDER BY pt.dt_pauta";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $return = [];
        if (!empty($array)) {
            foreach ($array as $key => $value) {
                $return[$value['id_pauta']] = $value;
            }
        }

        return $return;
    }

    public function getPautaProposta($id = null, $disponivel = null, $abertas = null)
    {
        $sqlAux = "";
        if (!empty($id)) {
            $sqlAux .= " AND pp.id_pauta_proposta = $id ";
        }

        if (!empty(self::isProfessor())) {
            $sqlAux .= " AND pp.visivel_professor = 1";
        }

        if (!is_null($disponivel)) {
            $sqlAux .= " AND IF(DATE(NOW()) BETWEEN pp.dt_inicio AND pp.dt_fim, 1, 0) = ". (!empty($disponivel) ? 1 : 0 );
        }

        if (!is_null($abertas)) {
            if (!empty($abertas)) {
                $sqlAux .= " AND DATE_ADD(pp.dt_fim, INTERVAL 3 DAY) >= DATE(NOW()) ";
            } else {
                $sqlAux .= " AND DATE_ADD(pp.dt_fim, INTERVAL 3 DAY) < DATE(NOW()) ";
            }
        }

        // proposta dos cursos da escola
        if (!empty(self::isCoordenador())) {
            $sqlAux .= " AND pp.id_pauta_proposta IN("
             . " SELECT DISTINCT hppc.fk_id_pauta_proposta "
             . " FROM htpc_pauta_proposta_curso hppc "
             . " INNER JOIN ge_cursos gc ON hppc.fk_id_curso = gc.id_curso "
             . " INNER JOIN ge_ciclos gcl ON gc.id_curso = gcl.fk_id_curso "
             . " INNER JOIN ge_turmas gt ON gcl.id_ciclo = gt.fk_id_ciclo "
             . " WHERE hppc.fk_id_pauta_proposta = pp.id_pauta_proposta AND gt.fk_id_inst = ". toolErp::id_inst()
             . " AND gc.ativo = 1 AND gcl.ativo = 1 "
             . " ) ";
        }

        if (!empty(self::isProfessor())) {
            $sqlAux .= " AND pp.visivel_professor = 1 ";
        }

        $sql = "SELECT pp.id_pauta_proposta, p.id_pessoa, p.n_pessoa, pp.n_pauta, pp.dt_inicio, pp.dt_fim, IF(DATE(NOW()) BETWEEN pp.dt_inicio AND pp.dt_fim, 1, 0) AS disponivel, (SELECT GROUP_CONCAT(c.n_curso) FROM htpc_pauta_proposta_curso pc JOIN ge_cursos c ON pc.fk_id_curso = c.id_curso WHERE pc.fk_id_pauta_proposta = pp.id_pauta_proposta) cursos, 'Coordenadoria' AS proposto_por "
                . " FROM htpc_pauta_proposta pp"
                . " JOIN pessoa p on p.id_pessoa = pp.fk_id_pessoa"
                . " WHERE pp.ativo = 1 "
                . $sqlAux
                . " ORDER BY disponivel DESC, pp.dt_inicio, pp.dt_fim";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public function getUltimaOrdem($id_ata){
        $sql = "SELECT ordem "
                . " FROM htpc_ata_pauta "
                . " WHERE fk_id_ata = $id_ata AND ativo = 1"
                . " ORDER BY ordem DESC LIMIT 1";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);
        $ordem = 1;
        if (!empty($array)) {
            $ordem = $array['ordem']+1;
        }
        return (string)$ordem;
    }

    public function getPautasATA($id_ata, $fk_id_pauta = null){
        $sqlAux = '';
        if (!empty($fk_id_pauta)){
            $sqlAux .= " AND ap.fk_id_pauta = $fk_id_pauta ";
        }
        $sql = "SELECT ap.id_ata_pauta, p.id_pauta, p.n_pauta, ap.ordem"
                . " FROM htpc_ata_pauta ap "
                . " INNER JOIN htpc_pauta p ON ap.fk_id_pauta = p.id_pauta "
                . " WHERE ap.fk_id_ata = $id_ata AND ap.ativo = 1"
                . $sqlAux
                . " ORDER BY ap.ordem";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (empty($array)) {
            return [];
        }
        return $array;
    }

    public function getProfessores($dia_semana_ata = null, $periodo = null, $id_pessoa = null){
        $sqlAux = '';
        if (!empty($dia_semana_ata)){
            $sqlAux .= " AND pe.hac_dia = $dia_semana_ata ";
        }
        if (!empty($periodo)){
            $sqlAux .= " AND LEFT(pe.hac_periodo, 1) = '$periodo' ";
        }
        if (!empty($id_pessoa)){
            $sqlAux .= " AND p.id_pessoa = $id_pessoa ";
        }

        $sql = "SELECT distinct "
            . " p.id_pessoa, nao_hac, p.n_pessoa, p.emailgoogle as email, "
            . " pe.rm, pe.id_pe, pe.disciplinas, pe.hac_periodo, "
            . " pe.hac_dia,fk_id_psc "
            . " FROM ge_prof_esc pe "
            . " JOIN ge_funcionario f on f.rm = pe.rm and f.at_func = 1 "
            . " JOIN pessoa p on p.id_pessoa = f.fk_id_pessoa "
            . " WHERE pe.fk_id_inst = " . tool::id_inst()
            . " AND pe.hac_dia IS NOT NULL AND pe.hac_dia <> '' "
            . $sqlAux
            . " ORDER BY IF(pe.hac_dia = DATE_FORMAT(NOW(), '%w')+1, 0, 1), p.n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (empty($array)) {
            return [];
        }
        return $array;
    }

    public function pautaPDF() {
        return $this->cadastrarPauta();
    }

    public function ataPDF() {
        return $this->cadastrarAta();
    }

    public function getDisciplinas(){
        $disc = sqlErp::get('ge_disciplinas', '*', 'WHERE status_disc = True');
        if (empty($disc)) {
            return [];
        }

        $disci = [];
        foreach ($disc as $v) {
            $disci[$v['id_disc']] = $v['n_disc'];
        }

        $disci['nc'] = "Professor Polivalente";
        $disci['20'] = 'Informática';
        $disci['16'] = 'Filosofia';

        return $disci;
    }

    public function getListaPresenca($id_ata, $fk_id_pessoa = null, $rm = null, $presente = null){
        $sqlAux = '';
        if (!empty($fk_id_pessoa)){
            $sqlAux .= " AND hp.fk_id_pessoa = $fk_id_pessoa";
        }

        if (!empty($rm)){
            $sqlAux .= " AND hp.rm = '$rm'";
        }

        if (!empty($presente)){
            $sqlAux .= " AND hp.presente = $presente";
        }

        $sql = "select hp.id_presenca, hp.fk_id_pessoa_reg, hp.fk_id_pessoa, hp.rm, hp.presente, hp.justificativa, p.n_pessoa "
            . " from htpc_presenca hp "
            . " INNER JOIN pessoa p ON hp.fk_id_pessoa = p.id_pessoa "
            . " where hp.fk_id_ata = " . $id_ata
            . " AND hp.ativo = 1"
            . $sqlAux;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (empty($array)) {
            return [];
        }
        foreach ($array as $k => $v) {
            unset($array[$k]); 
            $array[$v['fk_id_pessoa'].'-'.$v['rm']] = $v;
        }
        return $array;
    }

    public function getEmentas($id_ata){
        $sql = "select he.id_ementa, p.id_pessoa, p.n_pessoa, he.descricao, DATE_FORMAT(he.dt_criacao, '%d/%m/%Y %H:%i:%s') dt_criacao"
            . " from htpc_ementa he "
            . " join htpc_ata a ON he.fk_id_ata = a.id_ata "
            . " join pessoa p ON p.id_pessoa = he.fk_id_pessoa "
            . " where he.fk_id_ata = " . $id_ata
            . " order by he.dt_criacao ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (empty($array)) {
            return [];
        }
        return $array;
    }

    public function getCursos() {
        $tp = sqlErp::get('ge_cursos');
        foreach ($tp as $v) {
            $tpe[$v['id_curso']] = $v['n_curso'];
        }
        return $tpe;
    }

    public function getCursosPautaProposta($id_pauta_proposta) {
        $sql = "select fk_id_curso"
            . " from htpc_pauta_proposta_curso "
            . " where fk_id_pauta_proposta = ". $id_pauta_proposta;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $rt = [];

        if (empty($array)) {
            return [];
        } else {
            foreach ($array as $v) {
                $rt[$v['fk_id_curso']] = $v['fk_id_curso'];
            }
        }
        return $rt;
    }

    public function getAnexosPauta($fk_id_pauta) {
        $sql = "select up.id_pauta_up, up.fk_id_pessoa, up.fk_id_pauta, p.n_pessoa, up.n_up, up.tipo, up.link, DATE_FORMAT(up.dt_update, '%d/%m/%Y %H:%i') dt_update"
            . " from htpc_pauta_up up "
            . " JOIN pessoa p on up.fk_id_pessoa = p.id_pessoa "
            . " where up.fk_id_pauta = ". $fk_id_pauta;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (empty($array)) {
            return [];
        }
        return $array;
    }

    public function getAnexosATA($id_ata) {
        $sql = "select up.id_ata_up, up.fk_id_pessoa, up.fk_id_ata, up.fk_id_pauta, p.n_pessoa, up.n_up, up.tipo, up.link, DATE_FORMAT(up.dt_update, '%d/%m/%Y %H:%i') dt_update"
            . " from htpc_ata_up up "
            . " JOIN pessoa p on up.fk_id_pessoa = p.id_pessoa "
            . " where up.fk_id_ata = ". $id_ata;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (empty($array)) {
            return [];
        }
        return $array;
    }

    public function getSQLEsolasProf($id_pessoa)
    {
        $sql = "SELECT DISTINCT pe.fk_id_inst "
            . " FROM ge_prof_esc pe "
            . " JOIN ge_funcionario f ON f.rm = pe.rm AND f.at_func = 1 "
            . " JOIN pessoa p ON p.id_pessoa = f.fk_id_pessoa "
            . " WHERE f.fk_id_pessoa = ". $id_pessoa;
        return $sql;
    }

    public function btnSalvarAta($status)
    {
        if ($status == 'N') {
           $btn = '<div class="col">'.formErp::button('Apenas Salvar ATA', NULL, 'validate()', 'btn btn-info').'</div><div class="col">'
                .formErp::button('Salvar e Abrir ATA', NULL,"validate( 'A')", 'btn btn-info').'</div>';
        }elseif ($status == 'A') {
            $btn = '<div class="col">'.formErp::button('Apenas Salvar ATA', NULL,'validate()', 'btn btn-info').'</div><div class="col">'
                .formErp::button('Salvar e Fechar ATA', NULL,"validate( 'F')", 'btn btn-info').'</div>';
        }else{
            $btn = formErp::button('Salvar ATA', NULL,"validate( 'F')", 'btn btn-info');
        }
        return $btn;
    }

    public function getAnexosPautaProposta($fk_id_pauta_proposta) {
        $sql = "select up.id_pauta_proposta_up, up.fk_id_pessoa, up.fk_id_pauta_proposta, p.n_pessoa, up.n_up, up.tipo, up.link, DATE_FORMAT(up.dt_update, '%d/%m/%Y %H:%i') dt_update"
            . " from htpc_pauta_proposta_up up "
            . " JOIN pessoa p on up.fk_id_pessoa = p.id_pessoa "
            . " where up.fk_id_pauta_proposta = ". $fk_id_pauta_proposta;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (empty($array)) {
            return [];
        }
        return $array;
    }

    public function geraAusencia($id_ata)
    {
        $ata = $this->getAta($id_ata);
        if (empty($ata)) {
            return false;
        }

        $ata = current($ata);
        $dia_semana_ata = $ata['dia_semana_ata'];
        $periodo = $ata['periodo'];

        $dados = $this->getProfessores($dia_semana_ata, $periodo);
        if (empty($dados)) {
            return false;
        }

        foreach ($dados as $key => $value)
        {
            $presenca = $this->getListaPresenca($id_ata, $value['id_pessoa'], $value['rm']);
            if (!empty($presenca)) {
                continue;
            }

            $ins = [];
            $ins['fk_id_ata'] = $id_ata;
            $ins['fk_id_pessoa_reg'] = toolErp::id_pessoa();
            $ins['fk_id_pessoa'] = $value['id_pessoa'];
            $ins['rm'] = $value['rm'];
            $ins['presente'] = '0';
            $ins['justificativa'] = "Fechamento da ATA sem presença do Professor";
            $this->db->ireplace('htpc_presenca', $ins, 1);            
        }

        return true;
    }
}
