<?php

class visaoModel extends MainModel {

    public $db;
    public $escola;

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
        $this->db = new DB();

        // Configura o controlador
        $this->controller = $controller;

        // Configura os parâmetros
        //$this->parametros = $this->controller->parametros;
        // Configura os dados do usuário
        $this->userdata = $this->controller->userdata;

        if (!empty($_POST['Atualizar'])) {
            $d = $this->cadastroalunovisao();
            log::logSet("Gera ou atz tabela cv_visao_aluno");
            tool::alert("Operação efetuada com Sucesso!");
        }

        if (DB::sqlKeyVerif('gravaexame')) {
            $atz = $this->gravateste();
            log::logSet("Atualização dos Exames cv_visao_aluno (RSE " . $_POST['id_pessoa'] . ")");
        }

        if (DB::sqlKeyVerif('gravaexamereteste')) {
            $atz = $this->gravareteste();
            log::logSet("Atualização dos Exames reteste cv_visao_aluno (RSE " . $_POST['id_pessoa'] . ")");
        }
        if (DB::sqlKeyVerif('gravaacompanhamento')) {
            $atz = $this->gravaacompanhamentovisao();
            log::logSet("Atualização dos Acompanhamento cv_visao_aluno (RSE " . $_POST['id_pessoa'] . ")");
        }
    }

// Crie seus próprios métodos daqui em diante


    public function pdfvisao($orientation = 'P', $mode = 'c', $format = 'A4', $default_font_size = 0, $default_font = '', $mgl = 15, $mgr = 15, $mgt = 21, $mgb = 20, $mgh = 9, $mgf = 9) {

        $header = '<table style="width: 100%; border: 1px solid">'
                . '<tr>'
                . '<td style="font-size: 14px"> &nbsp;CAMPANHA DA VISÃO "ALÉM DO OLHAR"</td>'
                . '<td rowspan = "2" style=" text-align: right">'
                . '<img style="width: 450px;" src="' . HOME_URI . '/views/_images/LogoEducSdpd.png"/>'
                . '</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 12px"> &nbsp;SECRETARIA DOS DIREITOS DA PESSOA COM DEFICIÊNCIA</td>'
                . '</tr>'
                . '</table>';

        $body = ob_get_contents();
        ob_end_clean();

        //include( ABSPATH . "/app/mpdf/mpdf.php");

        //$mpdf = new mPDF($mode, $format, $default_font_size, $default_font, $mgl, $mgr, $mgt, $mgb, $mgh);


        require_once ABSPATH . '/vendor/autoload.php';
        $config = [
            'tempDir' => ABSPATH . '/tmp'
        ];
        $mpdf = new \Mpdf\Mpdf($config);

        $footer = "<div style=\"padding: 8px; background-color: #D4DF92;\" ><table width=\"1000\"><tr><td style=\" font-weight: bold;width: 200px\">".SISTEMA_NOME."</td><td style=\" text-align: center\">".CLI_CIDADE.", " . date("d") . " de " . data::mes(date("m")) . " de " . date("Y") . "</td><td  style=\"width: 300px\" align=\"right\">{PAGENO}/{nb}</td></tr></table></div>";
        $mpdf->SetHTMLHeader($header);
        $mpdf->SetHTMLFooter($footer);
        $css = file_get_contents(ABSPATH . "/views/_css/style.css");
        $mpdf->WriteHTML($css, 1);
        $css1 = file_get_contents(ABSPATH . "/views/_css/bootstrap-theme.css");
        $mpdf->WriteHTML($css1, 1);
        $css2 = file_get_contents(ABSPATH . "/views/_css/bootstrap.css");
        $mpdf->WriteHTML($css2, 1);

        if ($orientation == 'L' || $orientation == 'l') {
            $mpdf->AddPage('L', 'A4', 0, '', '', 15, 15, 33, 15, 20, 6);
        }else{
            $mpdf->AddPage('R', 'A4', 0, '', '', 15, 15, 58, 15, 20, 6);
        }

        $mpdf->WriteHTML($body);

        $mpdf->Output();
        exit;
    }

    public function pegaclasse($idturmas, $rel) {

        $idturmas = implode(",", $idturmas);

        switch ($rel) {
            case "teste":
                $sql = "SELECT i.n_inst, t.n_turma, t.periodo, t.id_turma, t.periodo_letivo,"
                        . " p.id_pessoa, p.n_pessoa, p.dt_nasc,ta.chamada FROM instancia i"
                        . " JOIN ge_turmas t ON t.fk_id_inst = i.id_inst"
                        . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = t.id_turma"
                        . " JOIN pessoa p ON p.id_pessoa = ta.fk_id_pessoa"
                        . " WHERE t.id_turma  in (" . $idturmas . ") " . ""
                        . " AND ta.situacao = '" . 'Frequente' . "'"
                        . " ORDER BY t.n_turma, ta.chamada";
                break;
            case "reteste":
                $sql = "SELECT i.n_inst, t.n_turma, t.periodo, t.id_turma, t.periodo_letivo, p.id_pessoa,"
                        . " p.n_pessoa, p.dt_nasc,ta.chamada FROM instancia i JOIN ge_turmas t ON t.fk_id_inst = i.id_inst"
                        . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = t.id_turma JOIN pessoa p ON p.id_pessoa = ta.fk_id_pessoa"
                        . " JOIN cv_visao_aluno va ON va.fk_id_pessoa = p.id_pessoa"
                        . " AND va.periodo_letivo = t.periodo_letivo"
                        . " WHERE va.fk_id_turma in (" . $idturmas . ") " . ""
                        . "  AND ta.situacao = '" . 'Frequente' . "'"
                        . " AND va.situacao_teste = '" . 'FALHA' . "'"
                        . " ORDER BY t.n_turma, ta.chamada";
                break;
            case "testeresu":

                $sql = "SELECT i.n_inst, t.n_turma, t.periodo, t.id_turma,t.periodo_letivo, p.id_pessoa,"
                        . " p.n_pessoa, p.dt_nasc,ta.chamada, va.reteste,"
                        . " va.oculos, va.olho_direito, va.olho_esquerdo, va.fk_id_sinais, va.usouoculoslentes, va.situacao_teste FROM instancia i"
                        . " JOIN ge_turmas t ON t.fk_id_inst = i.id_inst"
                        . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = t.id_turma JOIN pessoa p ON p.id_pessoa = ta.fk_id_pessoa"
                        . " JOIN cv_visao_aluno va ON va.fk_id_pessoa = p.id_pessoa"
                        . " AND va.periodo_letivo = t.periodo_letivo"
                        . " AND va.periodo_letivo = t.periodo_letivo"
                        . " WHERE va.fk_id_turma  in (" . $idturmas . ") " . " AND ta.situacao = '" . 'Frequente' . "'"
                        . " ORDER BY t.n_turma, ta.chamada";

                break;
            case "retesteresu":
                $sql = "SELECT i.n_inst, t.n_turma, t.periodo, t.id_turma, t.periodo_letivo,"
                        . " p.id_pessoa, p.n_pessoa, p.dt_nasc,ta.chamada,"
                        . " va.reteste_oculos, va.reteste_direito, va.reteste_esquerdo, va.reteste_sinais, va.usouoculoslentes,"
                        . " va.encam_oftalmologista, va.cartao, va.reteste_sit FROM instancia i"
                        . " JOIN ge_turmas t ON t.fk_id_inst = i.id_inst"
                        . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = t.id_turma JOIN pessoa p ON p.id_pessoa = ta.fk_id_pessoa"
                        . " JOIN cv_visao_aluno va ON va.fk_id_pessoa = p.id_pessoa"
                        . " AND va.periodo_letivo = t.periodo_letivo"
                        . " WHERE va.fk_id_turma in (" . $idturmas . ") " . "  AND ta.situacao = '" . 'Frequente' . "'"
                        . " AND va.situacao_teste = '" . 'FALHA' . "' ORDER BY t.n_turma, ta.chamada";
                break;
            default:
                $sql = "";
        }

        $query = $this->db->query($sql);
        $aluno = $query->fetchAll();

        return $aluno;
    }

    public function pegatelescola() {

        $sql = "SELECT fkid, num FROM telefones WHERE fkid = '" . tool::id_inst() . "' LIMIT 2";
        $query = $this->db->query($sql);
        $tel = $query->fetchAll();

        foreach ($tel as $v) {
            if (empty($telefones)) {
                $telefones = $v['num'];
            } else {
                $telefones = $v['num'] . " - " . $telefones;
            }
        }
        return @$telefones;
    }

    public function pegaalunovisao($idturma) {

        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.dt_nasc, p.sus, p.sexo, t.codigo, t.id_turma, va.id_visao,"
                . " va.situacao_teste, va.reteste_sit, ta.chamada FROM pessoa p"
                . " JOIN cv_visao_aluno va ON va.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = va.fk_id_pessoa AND ta.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND t.periodo_letivo = va.periodo_letivo"
                . " WHERE t.id_turma = '" . $idturma . "' AND ta.situacao = 'Frequente' ORDER BY ta.chamada";

        $query = $this->db->query($sql);
        $dados = $query->fetchAll();

        return $dados;
    }

    public function cadastroalunovisao() {
        // Primeiro acesso e atualização das matriculas suplementares
        // ciclo 1 primeiro do fundamental 20 segunda fase do pré

       $sql = "SELECT DISTINCTROW ta.fk_id_inst, ta.fk_id_pessoa, ta.fk_id_turma FROM ge_turma_aluno ta"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " LEFT JOIN cv_visao_aluno va ON va.fk_id_pessoa = ta.fk_id_pessoa"
                . " AND va.periodo_letivo = t.periodo_letivo"
                . " WHERE ta.situacao = '" . 'Frequente' . "' AND ta.fk_id_inst = '" . tool::id_inst() . "'"
                . " AND t.fk_id_ciclo IN  (1) AND t.fk_id_pl = '" . '87' . "'"
                . " AND va.fk_id_pessoa IS NULL";


        $query = $this->db->query($sql);
        $dados = $query->fetchAll();

        if (!empty($dados)) {
            foreach ($dados as $v) {
                $w['fk_id_inst'] = $v['fk_id_inst'];
                $w['fk_id_pessoa'] = $v['fk_id_pessoa'];
                $w['fk_id_turma'] = $v['fk_id_turma'];
                // Valores padrão
                $w['oculos'] = 'Não';
                $w['olho_direito'] = '1';
                $w['olho_esquerdo'] = '1';
                $w['dt_teste'] = date("Y-m-d");
                $w['situacao_teste'] = 'Não Submetido';
                $w['fk_id_sinais'] = 1;
                $w['reteste'] = 'Não';
                $w['cartao'] = 'Não';
                $w['necessidade_esp'] = 'Não';
                $w['fk_id_deficiencia'] = 1;
                $w['usouoculoslentes'] = "Não";
                $w['encam_oftalmologista'] = "Não";
                $w['reteste_oculos'] = "Não";
                $w['teste_av'] = "Não";
                $w['ag_consulta'] = "Não";
                $w['retirou_cartao'] = "Não";
                $w['devolveu_cartao'] = "Não";
                $w['cartao_prontuario'] = "Não";
                $w['preenchimento_medico'] = "Não";
                $w['laudo_receituario'] = "Não";
                $w['periodo_letivo'] = date('Y');

                $this->db->ireplace('cv_visao_aluno', $w, 1);

                log::logSet("Incluiu aluno na tabela cv_visao_aluno (RSE " . $w['fk_id_pessoa'] . ")");
            }
        }

        //Atualiza alunos transferidos
        $sql = "UPDATE cv_visao_aluno va JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = va.fk_id_pessoa"
                . " JOIN ge_turmas t ON t.periodo_letivo = va.periodo_letivo AND t.id_turma = ta.fk_id_turma"
                . " SET va.fk_id_inst = ta.fk_id_inst, va.fk_id_turma = ta.fk_id_turma"
                . " WHERE ta.fk_id_inst = '" . tool::id_inst() . "' AND va.periodo_letivo = '" . date('Y') . "'"
                . " AND ta.situacao = '" . 'Frequente' . "'";

        $query = $this->db->query($sql);

        log::logSet("Atualizou alunos transferidos");
    }

    public function pegaclassevisao($idescola = NULL, $p = NULL) {

        if (empty($idescola)) {
            $idescola = tool::id_inst();
        }

        $sql = "SELECT DISTINCT t.id_turma, t.codigo FROM cv_visao_aluno va"
                . " JOIN ge_turmas t ON t.id_turma = va.fk_id_turma WHERE t.fk_id_inst = '" . $idescola . "'"
                . " AND t.fk_id_pl = 87 AND t.fk_id_ciclo = 1  ORDER BY t.codigo";

        $query = $this->db->query($sql);
        $dados = $query->fetchAll();

        if (!empty($dados)) {
            foreach ($dados as $v) {
                $classe[$v['id_turma']] = $v['codigo'];
            }

            return $classe;
        }
    }

    public function gravateste() {

        $insert['id_visao'] = $_POST[1]['id_visao'];
        $insert['oculos'] = $_POST[1]['oc'];
        $insert['dt_teste'] = data::converteUS($_POST[1]['dt_teste']);
        $insert['olho_direito'] = $_POST[1]['cod'];
        $insert['olho_esquerdo'] = $_POST[1]['coe'];
        $insert['fk_id_sinais'] = $_POST[1]['si'];
        $insert['outros_sinais'] = $_POST[1]['outros_sinais'];
        $insert['situacao_teste'] = $_POST[1]['situacao_teste'];
        $insert['observacao'] = $_POST[1]['observacao'];
        $insert['necessidade_esp'] = $_POST[1]['ne'];
        $insert['fk_id_deficiencia'] = $_POST[1]['df'];

        if ($_POST[1]['situacao_teste'] == 'FALHA') {
            $insert['reteste'] = 'Sim';
            $insert['dt_reteste'] = date("Y-m-d");
            $insert['reteste_direito'] = '1';
            $insert['reteste_esquerdo'] = '1';
            $insert['reteste_sinais'] = $_POST[1]['si'];
            $insert['reteste_outros'] = $_POST[1]['outros_sinais'];
            $insert['reteste_sit'] = 'Não Submetido';
            $insert['cartao'] = 'Não';
        } else {
            $insert['teste_av'] = 'Sim';
            $insert['reteste'] = 'Não';
            $insert['dt_reteste'] = null;
            $insert['reteste_direito'] = '0';
            $insert['reteste_esquerdo'] = '0';
            $insert['reteste_sinais'] = '0';
            $insert['reteste_outros'] = null;
            $insert['reteste_sit'] = null;
            $insert['cartao'] = 'Não';
        }

        $this->db->ireplace('cv_visao_aluno', $insert, 1);
    }

    public function gravareteste() {

        $insert['id_visao'] = $_POST[1]['id_visao'];
        $insert['observacao'] = $_POST[1]['observacao'];
        $insert['dt_reteste'] = data::converteUS($_POST[1]['dt_reteste']);
        $insert['reteste_direito'] = $_POST[1]['pap'];
        $insert['reteste_esquerdo'] = $_POST[1]['pae'];
        $insert['reteste_sinais'] = $_POST[1]['sir'];
        $insert['reteste_outros'] = $_POST[1]['reteste_outros'];
        $insert['reteste_sit'] = $_POST[1]['reteste_sit'];
        $insert['cartao'] = $_POST[1]['ca'];
        $insert['encam_oftalmologista'] = $_POST[1]['of'];
        $insert['reteste_oculos'] = $_POST[1]['ocr'];
        $insert['consulta_oftal'] = 'Não';
        $insert['consulta_local'] = 'Não Informado';
        $insert['indicacao_oculos'] = 'Não';
        $insert['exames_compl'] = 'Não';
        $insert['aquisicao_oculos'] = 'Não';
        $insert['aquisicao_local'] = 'Não Informado';
        $insert['sdpd_recebido'] = 'Não';
        $insert['sdpd_tabulacao'] = 'Não';
        $insert['teste_av'] = 'Sim';

        $this->db->ireplace('cv_visao_aluno', $insert, 1);
    }

    public function gravaacompanhamentovisao() {

        $insert['id_visao'] = $_POST['id_visao'];

        $insert['obs_olho_d'] = $_POST[1]['obs_olho_d'];
        $insert['obs_olho_e'] = $_POST[1]['obs_olho_e'];
        $insert['dt_oftal'] = data::converteUS($_POST[1]['dt_oftal']);
        $insert['encam_oftalmologista'] = $_POST[1]['of'];
        $insert['consulta_oftal'] = $_POST[1]['cof'];

        if (!empty($_POST[1]['loc'])) {
            $insert['consulta_local'] = $_POST[1]['loc'];
        }

        $insert['dt_consulta'] = data::converteUS($_POST[1]['dt_consulta']);
        $insert['cod_cid_10'] = $_POST[1]['cod_cid_10'];
        $insert['usouoculoslentes'] = $_POST[1]['ol'];
        $insert['indicacao_oculos'] = $_POST[1]['io'];
        $insert['exames_compl'] = $_POST[1]['ex'];
        $insert['exames_quais'] = $_POST[1]['exames_quais'];
        $insert['nome_medico'] = $_POST[1]['nome_medico'];
        $insert['aquisicao_oculos'] = $_POST[1]['aqo'];

        if (!empty($_POST[1]['locaq'])) {
            $insert['aquisicao_local'] = $_POST[1]['locaq'];
        }
        $insert['dt_aquisicao'] = data::converteUS($_POST[1]['dt_aquisicao']);

        $insert['ag_consulta'] = $_POST[1]['con'];
        $insert['dt_agendamento'] = data::converteUS($_POST[1]['dt_agendamento']);
        $insert['retirou_cartao'] = $_POST[1]['rc'];
        $insert['dt_entrega_cartao'] = data::converteUS($_POST[1]['dt_entrega_cartao']);
        $insert['devolveu_cartao'] = $_POST[1]['dev'];
        $insert['dt_devolucao_cartao'] = data::converteUS($_POST[1]['dt_devolucao_cartao']);
        $insert['cartao_prontuario'] = $_POST[1]['cp'];
        $insert['preenchimento_medico'] = $_POST[1]['pm'];
        $insert['laudo_receituario'] = $_POST[1]['lm'];

        $this->db->ireplace('cv_visao_aluno', $insert, 1);
    }

    public function totalalunos($idturmas, $id_inst = NULL) {

        if (empty($id_inst)) {
            $id_inst = tool::id_inst();
        }

        $idturmas = implode(",", $idturmas);

        $sql = "SELECT cv.fk_id_turma FROM cv_visao_aluno cv"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = cv.fk_id_turma AND ta.fk_id_pessoa = cv.fk_id_pessoa"
                . " WHERE ta.fk_id_turma IN (" . $idturmas . ") "
                . " AND ta.situacao = '" . 'Frequente' . "'"
                . " AND ta.fk_id_inst = '" . $id_inst . "'";

        $query = $this->db->query($sql);
        $dados = $query->fetchAll();

        foreach ($dados as $v) {
            @$total_cv[$v['fk_id_turma']] ++;
        }

        return $total_cv;
    }

    public function resultadoteste($id_escola = NULL, $p = NULL) {

        if (empty($p)) {
            $p = date('Y');
        }

        if (empty($id_escola)) {
            $id_escola = tool::id_inst();
        }

        $sql = "SELECT DISTINCT va.fk_id_inst, va.fk_id_turma FROM cv_visao_aluno va"
                . " WHERE va.periodo_letivo = '" . $p . "' AND va.fk_id_inst = '" . $id_escola . "'";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();
        // Iniciando as variaveis para não ter problema no relatório
        foreach ($array as $v) {

            @$resultado[$v['fk_id_turma']]['Oculos']['Sim'] = 0;
            @$resultado[$v['fk_id_turma']]['Oculos']['Não'] = 0;
            @$resultado[$v['fk_id_turma']]['Situacao']['PASSA'] = 0;
            @$resultado[$v['fk_id_turma']]['Situacao']['FALHA'] = 0;
            @$resultado[$v['fk_id_turma']]['Situacao']['Não Submetido'] = 0;

            @$resultado[$v['fk_id_turma']]['Deficiencia'][1] = 0;
            @$resultado[$v['fk_id_turma']]['Deficiencia'][2] = 0;
            @$resultado[$v['fk_id_turma']]['Deficiencia'][3] = 0;
            @$resultado[$v['fk_id_turma']]['Deficiencia'][4] = 0;
            @$resultado[$v['fk_id_turma']]['Deficiencia'][5] = 0;
            @$resultado[$v['fk_id_turma']]['Deficiencia'][6] = 0;
            @$resultado[$v['fk_id_turma']]['Deficiencia'][7] = 0;


            @$resultado[$v['fk_id_turma']]['NecesEsp']['Sim'] = 0;
            @$resultado[$v['fk_id_turma']]['NecesEsp']['Não'] = 0;

            @$resultado[$v['fk_id_turma']]['Sinais'][1] = 0;
            @$resultado[$v['fk_id_turma']]['Sinais'][2] = 0;
            @$resultado[$v['fk_id_turma']]['Sinais'][3] = 0;
            @$resultado[$v['fk_id_turma']]['Sinais'][4] = 0;
            @$resultado[$v['fk_id_turma']]['Sinais'][5] = 0;
            @$resultado[$v['fk_id_turma']]['Sinais'][6] = 0;
            @$resultado[$v['fk_id_turma']]['Sinais'][7] = 0;
            @$resultado[$v['fk_id_turma']]['Sinais'][8] = 0;

            @$resultado[$v['fk_id_turma']]['UsouOculos']['Sim'] = 0;
            @$resultado[$v['fk_id_turma']]['UsouOculos']['Não'] = 0;

            //Acumulado
            @$resultado[$v['fk_id_inst']]['Oculosac']['Sim'] = 0;
            @$resultado[$v['fk_id_inst']]['Oculosac']['Não'] = 0;

            @$resultado[$v['fk_id_inst']]['Situacaoac']['PASSA'] = 0;
            @$resultado[$v['fk_id_inst']]['Situacaoac']['FALHA'] = 0;
            @$resultado[$v['fk_id_inst']]['Situacaoac']['Não Submetido'] = 0;

            @$resultado[$v['fk_id_inst']]['Deficienciaac'][1] = 0;
            @$resultado[$v['fk_id_inst']]['Deficienciaac'][2] = 0;
            @$resultado[$v['fk_id_inst']]['Deficienciaac'][3] = 0;
            @$resultado[$v['fk_id_inst']]['Deficienciaac'][4] = 0;
            @$resultado[$v['fk_id_inst']]['Deficienciaac'][5] = 0;
            @$resultado[$v['fk_id_inst']]['Deficienciaac'][6] = 0;
            @$resultado[$v['fk_id_inst']]['Deficienciaac'][7] = 0;

            @$resultado[$v['fk_id_inst']]['NecesEspac']['Sim'] = 0;
            @$resultado[$v['fk_id_inst']]['NecesEspac']['Não'] = 0;

            @$resultado[$v['fk_id_inst']]['Sinaisac'][1] = 0;
            @$resultado[$v['fk_id_inst']]['Sinaisac'][2] = 0;
            @$resultado[$v['fk_id_inst']]['Sinaisac'][3] = 0;
            @$resultado[$v['fk_id_inst']]['Sinaisac'][4] = 0;
            @$resultado[$v['fk_id_inst']]['Sinaisac'][5] = 0;
            @$resultado[$v['fk_id_inst']]['Sinaisac'][6] = 0;
            @$resultado[$v['fk_id_inst']]['Sinaisac'][7] = 0;
            @$resultado[$v['fk_id_inst']]['Sinaisac'][8] = 0;

            @$resultado[$v['fk_id_inst']]['usouoculosac']['Sim'] = 0;
            @$resultado[$v['fk_id_inst']]['usouoculosac']['Não'] = 0;
        }

        $sql = "SELECT va.id_visao, va.fk_id_inst, va.fk_id_pessoa, va.fk_id_turma, va.oculos, va.fk_id_sinais,"
                . " va.situacao_teste, va.necessidade_esp, va.fk_id_deficiencia, va.usouoculoslentes,"
                . " va.periodo_letivo, t.codigo FROM cv_visao_aluno va"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = va.fk_id_pessoa AND ta.fk_id_turma = va.fk_id_turma"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " WHERE va.periodo_letivo = '" . $p . "' AND va.fk_id_inst = '" . $id_escola . "'"
                . " AND ta.situacao = '" . 'Frequente' . "'";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        foreach ($array as $v) {

            @$resultado[$v['fk_id_turma']]['Oculos'][$v['oculos']] ++;
            @$resultado[$v['fk_id_turma']]['Situacao'][$v['situacao_teste']] ++;
            @$resultado[$v['fk_id_turma']]['Deficiencia'][$v['fk_id_deficiencia']] ++;
            @$resultado[$v['fk_id_turma']]['NecesEsp'][$v['necessidade_esp']] ++;
            @$resultado[$v['fk_id_turma']]['Sinais'][$v['fk_id_sinais']] ++;
            @$resultado[$v['fk_id_turma']]['UsouOculos'][$v['usouoculoslentes']] ++;
            @$resultado[$v['fk_id_turma']]['Frequente'][$v['fk_id_inst']] ++;

            //Acumulado
            @$resultado[$v['fk_id_inst']]['Oculosac'][$v['oculos']] ++;
            @$resultado[$v['fk_id_inst']]['Situacaoac'][$v['situacao_teste']] ++;
            @$resultado[$v['fk_id_inst']]['Deficienciaac'][$v['fk_id_deficiencia']] ++;
            @$resultado[$v['fk_id_inst']]['NecesEspac'][$v['necessidade_esp']] ++;
            @$resultado[$v['fk_id_inst']]['Sinaisac'][$v['fk_id_sinais']] ++;
            @$resultado[$v['fk_id_inst']]['usouoculosac'][$v['usouoculoslentes']] ++;
            @$resultado[$v['fk_id_inst']]['Frequenteac'][$v['fk_id_inst']] ++;
        }

        return @$resultado;
    }

    public function auxtabela($tabela) {

        switch ($tabela) {
            case 'cv_sinais':
                $sql = "SELECT * FROM cv_sinais ORDER BY id_sinais";

                $query = $this->db->query($sql);
                $array = $query->fetchAll();

                foreach ($array as $v) {
                    $tab[$v['id_sinais']] = $v['sinal'];
                }
                break;
            case 'cv_teste_papel':
                $sql = "SELECT * FROM cv_teste_papel ORDER BY id_teste_papel";
                $query = $this->db->query($sql);
                $array = $query->fetchAll();

                foreach ($array as $v) {
                    $tab[$v['id_teste_papel']] = $v['valor_papel'];
                }
                $tab[1] = 'NR';
                break;
            case 'cv_teste_computador':
                $sql = "SELECT * FROM cv_teste_computador ORDER BY id_teste_comp";
                $query = $this->db->query($sql);
                $array = $query->fetchAll();

                foreach ($array as $v) {
                    $tab[$v['id_teste_comp']] = $v['valor_teste_comp'];
                }
                $tab[1] = 'NR';
                break;
            case 'cv_deficiencia':
                $sql = "SELECT * FROM cv_deficiencia ORDER BY id_deficiencia";
                $query = $this->db->query($sql);
                $array = $query->fetchAll();

                foreach ($array as $v) {
                    $tab[$v['id_deficiencia']] = $v['desc_deficiencia'];
                }
                break;
            default :
                $sql = "";
        }

        return $tab;
    }

    public function resultadoreteste($id_escola = NULL, $p = NULL) {

        if (empty($p)) {
            $p = date('Y');
        }
        if (empty($id_escola)) {
            $id_escola = tool::id_inst();
        }

        $sql = "SELECT DISTINCT va.fk_id_inst, va.fk_id_turma FROM cv_visao_aluno va"
                . " WHERE va.periodo_letivo = '" . $p . "' AND va.fk_id_inst = '" . $id_escola . "'";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();
        // Iniciando as variaveis para não ter problema no relatório
        foreach ($array as $v) {
            @$resultadoac[$v['fk_id_turma']]['Reteste']['Sim'] = 0;
            @$resultadoac[$v['fk_id_turma']]['Reteste']['Não'] = 0;
            @$resultadoac[$v['fk_id_turma']]['Oculosre']['Sim'] = 0;
            @$resultadoac[$v['fk_id_turma']]['Oculosre']['Não'] = 0;
            @$resultadoac[$v['fk_id_turma']]['Situacaore']['PASSA'] = 0;
            @$resultadoac[$v['fk_id_turma']]['Situacaore']['FALHA'] = 0;
            @$resultadoac[$v['fk_id_turma']]['Situacaore']['Não Submetido'] = 0;

            @$resultadoac[$v['fk_id_turma']]['Sinaisre'][1] = 0;
            @$resultadoac[$v['fk_id_turma']]['Sinaisre'][2] = 0;
            @$resultadoac[$v['fk_id_turma']]['Sinaisre'][3] = 0;
            @$resultadoac[$v['fk_id_turma']]['Sinaisre'][4] = 0;
            @$resultadoac[$v['fk_id_turma']]['Sinaisre'][5] = 0;
            @$resultadoac[$v['fk_id_turma']]['Sinaisre'][6] = 0;
            @$resultadoac[$v['fk_id_turma']]['Sinaisre'][7] = 0;
            @$resultadoac[$v['fk_id_turma']]['Sinaisre'][8] = 0;

            @$resultadoac[$v['fk_id_turma']]['Oftalmologista']['Sim'] = 0;
            @$resultadoac[$v['fk_id_turma']]['Oftalmologista']['Não'] = 0;
            @$resultadoac[$v['fk_id_turma']]['Cartao']['Sim'] = 0;
            @$resultadoac[$v['fk_id_turma']]['Cartao']['Não'] = 0;
            //Acumulado
            @$resultadoac[$v['fk_id_inst']]['Retesteac']['Sim'] = 0;
            @$resultadoac[$v['fk_id_inst']]['Retesteac']['Não'] = 0;
            @$resultadoac[$v['fk_id_inst']]['Oculosreac']['Sim'] = 0;
            @$resultadoac[$v['fk_id_inst']]['Oculosreac']['Não'] = 0;

            @$resultadoac[$v['fk_id_inst']]['Situacaoreac']['PASSA'] = 0;
            @$resultadoac[$v['fk_id_inst']]['Situacaoreac']['FALHA'] = 0;
            @$resultadoac[$v['fk_id_inst']]['Situacaoreac']['Não Submetido'] = 0;

            @$resultadoac[$v['fk_id_inst']]['Sinaisreac'][1] = 0;
            @$resultadoac[$v['fk_id_inst']]['Sinaisreac'][2] = 0;
            @$resultadoac[$v['fk_id_inst']]['Sinaisreac'][3] = 0;
            @$resultadoac[$v['fk_id_inst']]['Sinaisreac'][4] = 0;
            @$resultadoac[$v['fk_id_inst']]['Sinaisreac'][5] = 0;
            @$resultadoac[$v['fk_id_inst']]['Sinaisreac'][6] = 0;
            @$resultadoac[$v['fk_id_inst']]['Sinaisreac'][7] = 0;
            @$resultadoac[$v['fk_id_inst']]['Sinaisreac'][8] = 0;
            @$resultadoac[$v['fk_id_inst']]['Oftalmologistaac']['Sim'] = 0;
            @$resultadoac[$v['fk_id_inst']]['Oftalmologistaac']['Não'] = 0;
            @$resultadoac[$v['fk_id_inst']]['Cartaoac']['Sim'] = 0;
            @$resultadoac[$v['fk_id_inst']]['Cartaoac']['Não'] = 0;
        }

        $sql = "SELECT va.id_visao, va.fk_id_inst, va.fk_id_pessoa, va.fk_id_turma, va.reteste, va.reteste_sinais,"
                . " va.reteste_sit, va.cartao, va.encam_oftalmologista, va.reteste_oculos,"
                . " va.periodo_letivo, t.codigo FROM cv_visao_aluno va"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = va.fk_id_pessoa AND ta.fk_id_turma = va.fk_id_turma"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " WHERE va.periodo_letivo = '" . $p . "' AND va.fk_id_inst = '" . $id_escola . "'"
                . " AND ta.situacao = '" . 'Frequente' . "'";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        foreach ($array as $v) {

            @$resultadoac[$v['fk_id_turma']]['Frequentere'][$v['fk_id_inst']] ++;
            @$resultadoac[$v['fk_id_turma']]['Reteste'][$v['reteste']] ++;

            // Acumulado
            @$resultadoac[$v['fk_id_inst']]['Frequentereac'][$v['fk_id_inst']] ++;
            @$resultadoac[$v['fk_id_inst']]['Retesteac'][$v['reteste']] ++;

            if ($v['reteste'] == 'Sim') {
                @$resultadoac[$v['fk_id_turma']]['Situacaore'][$v['reteste_sit']] ++;
                @$resultadoac[$v['fk_id_turma']]['Oculosre'][$v['reteste_oculos']] ++;
                @$resultadoac[$v['fk_id_turma']]['Sinaisre'][$v['reteste_sinais']] ++;
                @$resultadoac[$v['fk_id_turma']]['Oftalmologista'][$v['encam_oftalmologista']] ++;
                @$resultadoac[$v['fk_id_turma']]['Cartao'][$v['cartao']] ++;
                //Acumulado
                @$resultadoac[$v['fk_id_inst']]['Situacaoreac'][$v['reteste_sit']] ++;
                @$resultadoac[$v['fk_id_inst']]['Oftalmologistaac'][$v['encam_oftalmologista']] ++;
                @$resultadoac[$v['fk_id_inst']]['Oculosreac'][$v['reteste_oculos']] ++;
                @$resultadoac[$v['fk_id_inst']]['Sinaisreac'][$v['reteste_sinais']] ++;
                @$resultadoac[$v['fk_id_inst']]['Cartaoac'][$v['cartao']] ++;
            }
        }

        return @$resultadoac;
    }

    public function relatorioacompanhamento() {

        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.dt_nasc, p.sus, va.consulta_oftal, va.consulta_local, va.dt_oftal,"
                . " va.cod_cid_10, va.usouoculoslentes, va.dt_consulta, va.indicacao_oculos, va.exames_compl,"
                . " va.aquisicao_oculos, va.aquisicao_local, va.dt_aquisicao, va.teste_av, va.dt_reteste, t.codigo,"
                . " va.sdpd_recebido, va.sdpd_tabulacao, va.dt_sdpd FROM cv_visao_aluno va"
                . " JOIN pessoa p ON p.id_pessoa = va.fk_id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = va.fk_id_turma"
                . " WHERE va.reteste_sit = '" . 'FALHA' . "' AND va.fk_id_inst = '" . tool::id_inst() . "'"
                . " AND va.periodo_letivo = '" . date('Y') . "' ORDER BY t.codigo, p.n_pessoa";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        return $array;
    }

    public function resultadotestegrafico() {

        $sinais = $this->auxtabela('cv_sinais');
        $def = $this->auxtabela('cv_deficiencia');

        $sql = "SELECT DISTINCT va.fk_id_inst, va.fk_id_turma FROM cv_visao_aluno va"
                . " WHERE va.periodo_letivo = '" . date('Y') . "' AND va.fk_id_inst = '" . tool::id_inst() . "'";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();
        // Iniciando as variaveis para não ter problema no relatório
        foreach ($array as $v) {

            //Acumulado
            @$resultado[$v['fk_id_inst']]['Oculosac']['Não'] = 0;
            @$resultado[$v['fk_id_inst']]['Oculosac']['Sim'] = 0;

            @$resultado[$v['fk_id_inst']]['Situacaoac']['PASSA'] = 0;
            @$resultado[$v['fk_id_inst']]['Situacaoac']['FALHA'] = 0;
            @$resultado[$v['fk_id_inst']]['Situacaoac']['Não Submetido'] = 0;

            @$resultado[$v['fk_id_inst']]['Deficienciaac'][$def[1]] = 0;
            @$resultado[$v['fk_id_inst']]['Deficienciaac'][$def[2]] = 0;
            @$resultado[$v['fk_id_inst']]['Deficienciaac'][$def[3]] = 0;
            @$resultado[$v['fk_id_inst']]['Deficienciaac'][$def[4]] = 0;
            @$resultado[$v['fk_id_inst']]['Deficienciaac'][$def[5]] = 0;
            @$resultado[$v['fk_id_inst']]['Deficienciaac'][$def[6]] = 0;
            @$resultado[$v['fk_id_inst']]['Deficienciaac'][$def[7]] = 0;

            @$resultado[$v['fk_id_inst']]['NecesEspac']['Não'] = 0;
            @$resultado[$v['fk_id_inst']]['NecesEspac']['Sim'] = 0;

            @$resultado[$v['fk_id_inst']]['Sinaisac'][$sinais[1]] = 0;
            @$resultado[$v['fk_id_inst']]['Sinaisac'][$sinais[2]] = 0;
            @$resultado[$v['fk_id_inst']]['Sinaisac'][$sinais[3]] = 0;
            @$resultado[$v['fk_id_inst']]['Sinaisac'][$sinais[4]] = 0;
            @$resultado[$v['fk_id_inst']]['Sinaisac'][$sinais[5]] = 0;
            @$resultado[$v['fk_id_inst']]['Sinaisac'][$sinais[6]] = 0;
            @$resultado[$v['fk_id_inst']]['Sinaisac'][$sinais[7]] = 0;
            @$resultado[$v['fk_id_inst']]['Sinaisac'][$sinais[8]] = 0;

            @$resultado[$v['fk_id_inst']]['usouoculosac']['Não'] = 0;
            @$resultado[$v['fk_id_inst']]['usouoculosac']['Sim'] = 0;
        }

        $sql = "SELECT va.id_visao, va.fk_id_inst, va.fk_id_pessoa, va.fk_id_turma, va.oculos, va.fk_id_sinais,"
                . " va.situacao_teste, va.necessidade_esp, va.fk_id_deficiencia, va.usouoculoslentes,"
                . " va.periodo_letivo, t.codigo FROM cv_visao_aluno va"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = va.fk_id_pessoa AND ta.fk_id_turma = va.fk_id_turma"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " WHERE va.periodo_letivo = '" . date('Y') . "' AND va.fk_id_inst = '" . tool::id_inst() . "'"
                . " AND ta.situacao = '" . 'Frequente' . "'";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        foreach ($array as $v) {

            @$resultado[$v['fk_id_turma']]['Oculos'][$v['oculos']] ++;
            @$resultado[$v['fk_id_turma']]['Situacao'][$v['situacao_teste']] ++;
            @$resultado[$v['fk_id_turma']]['Deficiencia'][$v['fk_id_deficiencia']] ++;
            @$resultado[$v['fk_id_turma']]['NecesEsp'][$v['necessidade_esp']] ++;
            @$resultado[$v['fk_id_turma']]['Sinais'][$v['fk_id_sinais']] ++;
            @$resultado[$v['fk_id_turma']]['UsouOculos'][$v['usouoculoslentes']] ++;
            @$resultado[$v['fk_id_turma']]['Frequente'][$v['fk_id_inst']] ++;

            //Acumulado
            @$resultado[$v['fk_id_inst']]['Oculosac'][$v['oculos']] ++;
            @$resultado[$v['fk_id_inst']]['Situacaoac'][$v['situacao_teste']] ++;
            @$resultado[$v['fk_id_inst']]['Deficienciaac'][$def[$v['fk_id_deficiencia']]] ++;
            @$resultado[$v['fk_id_inst']]['NecesEspac'][$v['necessidade_esp']] ++;
            @$resultado[$v['fk_id_inst']]['Sinaisac'][$sinais[$v['fk_id_sinais']]] ++;
            @$resultado[$v['fk_id_inst']]['usouoculosac'][$v['usouoculoslentes']] ++;
            @$resultado['Frequenteac'][$v['fk_id_inst']] ++;
        }

        return @$resultado;
    }

    public function resultadoretestegrafico($p = NULL) {
        if (empty($p)) {
            $p = date('Y');
        }
        $sinais = $this->auxtabela('cv_sinais');

        $sql = "SELECT DISTINCT va.fk_id_inst, va.fk_id_turma FROM cv_visao_aluno va"
                . " WHERE va.periodo_letivo = '" . $p . "' AND va.fk_id_inst = '" . tool::id_inst() . "'";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();
        // Iniciando as variaveis para não ter problema no relatório
        foreach ($array as $v) {

            //Acumulado
            @$resultadoac[$v['fk_id_inst']]['Retesteac']['Não'] = 0;
            @$resultadoac[$v['fk_id_inst']]['Retesteac']['Sim'] = 0;

            @$resultadoac[$v['fk_id_inst']]['Situacaoreac']['PASSA'] = 0;
            @$resultadoac[$v['fk_id_inst']]['Situacaoreac']['FALHA'] = 0;
            @$resultadoac[$v['fk_id_inst']]['Situacaoreac']['Não Submetido'] = 0;

            @$resultadoac[$v['fk_id_inst']]['Oculosreac']['Não'] = 0;
            @$resultadoac[$v['fk_id_inst']]['Oculosreac']['Sim'] = 0;

            @$resultadoac[$v['fk_id_inst']]['Sinaisreac'][$sinais[1]] = 0;
            @$resultadoac[$v['fk_id_inst']]['Sinaisreac'][$sinais[2]] = 0;
            @$resultadoac[$v['fk_id_inst']]['Sinaisreac'][$sinais[3]] = 0;
            @$resultadoac[$v['fk_id_inst']]['Sinaisreac'][$sinais[4]] = 0;
            @$resultadoac[$v['fk_id_inst']]['Sinaisreac'][$sinais[5]] = 0;
            @$resultadoac[$v['fk_id_inst']]['Sinaisreac'][$sinais[6]] = 0;
            @$resultadoac[$v['fk_id_inst']]['Sinaisreac'][$sinais[7]] = 0;
            @$resultadoac[$v['fk_id_inst']]['Sinaisreac'][$sinais[8]] = 0;

            @$resultadoac[$v['fk_id_inst']]['Oftalmologistaac']['Não'] = 0;
            @$resultadoac[$v['fk_id_inst']]['Oftalmologistaac']['Sim'] = 0;

            @$resultadoac[$v['fk_id_inst']]['Cartaoac']['Não'] = 0;
            @$resultadoac[$v['fk_id_inst']]['Cartaoac']['Sim'] = 0;
        }

        $sql = "SELECT va.id_visao, va.fk_id_inst, va.fk_id_pessoa, va.fk_id_turma, va.reteste, va.reteste_sinais,"
                . " va.reteste_sit, va.cartao, va.encam_oftalmologista, va.reteste_oculos,"
                . " va.periodo_letivo, t.codigo FROM cv_visao_aluno va"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = va.fk_id_pessoa AND ta.fk_id_turma = va.fk_id_turma"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " WHERE va.periodo_letivo = '" . date('Y') . "' AND va.fk_id_inst = '" . tool::id_inst() . "'"
                . " AND ta.situacao = '" . 'Frequente' . "'";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        foreach ($array as $v) {

            // Acumulado
            @$resultadoac['Frequentereac'][$v['fk_id_inst']] ++;
            @$resultadoac[$v['fk_id_inst']]['Retesteac'][$v['reteste']] ++;

            if ($v['reteste'] == 'Sim') {
                //Acumulado
                @$resultadoac[$v['fk_id_inst']]['Situacaoreac'][$v['reteste_sit']] ++;
                @$resultadoac[$v['fk_id_inst']]['Oftalmologistaac'][$v['encam_oftalmologista']] ++;
                @$resultadoac[$v['fk_id_inst']]['Oculosreac'][$v['reteste_oculos']] ++;
                @$resultadoac[$v['fk_id_inst']]['Sinaisreac'][$sinais[$v['reteste_sinais']]] ++;
                @$resultadoac[$v['fk_id_inst']]['Cartaoac'][$v['cartao']] ++;
                @$resultadoac['reteste'][$v['fk_id_inst']] ++;
            }
        }

        return @$resultadoac;
    }

    public function situacaoescolarede($p) {

        //verifica a escola 
        $frequente = $this->verificatabelaresumo($p);

        $rede = "SELECT * FROM cv_resumo_visao WHERE periodo_letivo = '" . $p . "'";

        $query = $this->db->query($rede);
        $escola = $query->fetchAll();

        foreach ($escola as $v) {

            $res = "SELECT * FROM cv_visao_aluno va"
                    . " JOIN ge_turma_aluno ta ON ta.fk_id_inst = va.fk_id_inst AND ta.fk_id_pessoa = va.fk_id_pessoa"
                    . " AND ta.fk_id_turma = va.fk_id_turma"
                    . " WHERE va.fk_id_inst = '" . $v['fk_id_inst'] . "' AND va.periodo_letivo = '" . $v['periodo_letivo'] . "'"
                    . " AND ta.situacao = '" . 'Frequente' . "'";

            $query = $this->db->query($res);
            $conta = $query->fetchAll();

            foreach ($conta as $g) {
                @$teste[$v['fk_id_inst']][$g['situacao_teste']] ++;
                @$reteste[$v['fk_id_inst']][$g['reteste_sit']] ++;
                @$oftal[$v['fk_id_inst']][$g['encam_oftalmologista']] ++;
            }

            $insert['id_cv_resumo'] = $v['id_cv_resumo'];
            $insert['teste_p'] = intval(@$teste[$v['fk_id_inst']]['PASSA']);
            $insert['teste_f'] = intval(@$teste[$v['fk_id_inst']]['FALHA']);
            $insert['teste_nr'] = intval(@$teste[$v['fk_id_inst']]['Não Submetido']);
            $insert['reteste_p'] = intval(@$reteste[$v['fk_id_inst']]['PASSA']);
            $insert['reteste_f'] = intval(@$reteste[$v['fk_id_inst']]['FALHA']);
            $insert['reteste_nr'] = intval(@$reteste[$v['fk_id_inst']]['Não Submetido']);
            $insert['encaminhamento_s'] = intval(@$oftal[$v['fk_id_inst']]['Sim']);
            $insert['encaminhamento_n'] = intval(@$oftal[$v['fk_id_inst']]['Não']);
            $insert['dt_emissao'] = date('Y-m-d');

            $this->db->ireplace('cv_resumo_visao', $insert, 1);
        }

        log::logSet("Gerou relatório resumo (escola)");
    }

    public function verificatabelaresumo($pe = NULL) {

        if (empty($pe)) {
            $pe = date('Y');
        }

        if ($pe == '2018') {
            $c = '1,20';
        } else {
            $c = '1';
        }

        $sql = "SELECT ta.situacao, ta.periodo_letivo, ta.fk_id_inst, t.fk_id_ciclo"
                . " FROM ge_turma_aluno ta"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " WHERE ta.periodo_letivo = '" . $pe . "' AND ta.situacao = '" . 'Frequente' . "'"
                . " AND t.fk_id_ciclo IN (" . $c . ")";

        $query = $this->db->query($sql);
        $freq = $query->fetchAll();

        foreach ($freq as $vv) {
            @$conta[$vv['fk_id_inst']]['frequente'] ++;
        }

        $rede = "SELECT DISTINCT fk_id_inst, periodo_letivo FROM ge_turmas WHERE fk_id_ciclo IN (" . $c . ") AND periodo_letivo = '" . $pe . "'";

        $query = $this->db->query($rede);
        $escola = $query->fetchAll();

        foreach ($escola as $v) {

            $sql = "SELECT * FROM cv_resumo_visao rs"
                    . " WHERE rs.fk_id_inst = '" . $v['fk_id_inst'] . "' AND rs.periodo_letivo = '" . $pe . "'";

            $query = $this->db->query($sql);
            $dados = $query->fetch();

            if (!empty($dados)) {
                $insert['id_cv_resumo'] = $dados['id_cv_resumo'];
            } else {
                $insert['id_cv_resumo'] = $this->pegaidresumo();
                $insert['fk_id_inst'] = $v['fk_id_inst'];
                $insert['periodo_letivo'] = $pe;
            }

            $insert['frequente'] = intval(@$conta[$v['fk_id_inst']]['frequente']);

            $this->db->ireplace('cv_resumo_visao', $insert, 1);
        }
    }

    public function wresumo($tipo, $per) {

       $sql = "SELECT rv.periodo_letivo, SUM(rv.frequente) as f, SUM(rv.teste_p) as tp, SUM(rv.teste_f) as tf,"
                . " SUM(rv.teste_nr) as tnr, SUM(rv.reteste_p) as rtp, SUM(rv.reteste_f) as rtf,"
                . " SUM(rv.reteste_nr) as rtnr, SUM(rv.encaminhamento_s) as es, SUM(rv.encaminhamento_n) as en"
                . " FROM cv_resumo_visao rv GROUP BY rv.periodo_letivo HAVING(rv.periodo_letivo = '" . $per . "')";

        $query = $this->db->query($sql);
        $resumo = $query->fetch();

        if ($tipo == 'grafico') {

            @$graf['Teste']['PASSA'] = $resumo['tp'];
            @$graf['Teste']['FALHA'] = $resumo['tf'];
            @$graf['Teste']['Não Submetido'] = $resumo['tnr'];
            @$graf['Reteste']['PASSA'] = $resumo['rtp'];
            @$graf['Reteste']['FALHA'] = $resumo['rtf'];
            @$graf['Reteste']['Não Submetido'] = $resumo['rtnr'];
            @$graf['Oftalmologista']['Não'] = $resumo['en'];
            @$graf['Oftalmologista']['Sim'] = $resumo['es'];

            return $graf;
        } else {
            return $resumo;
        }
    }

    public function pegaidresumo() {

        $sql = "SELECT * FROM cv_resumo_visao ORDER BY id_cv_resumo DESC";
        $query = $this->db->query($sql);
        $id_resumo = $query->fetch()['id_cv_resumo'];

        $id_resumo += 1;

        return $id_resumo;
    }

    public function pegaanovisao() {

        $sql = "SELECT DISTINCT periodo_letivo FROM cv_visao_aluno"
                . " ORDER BY periodo_letivo";

        $query = $this->db->query($sql);
        $sel = $query->fetchAll();

        foreach ($sel as $v) {
            $selecao[$v['periodo_letivo']] = $v['periodo_letivo'];
        }
        return $selecao;
    }

    public function pegaescolavisao($periodo) {

        $sql = "SELECT DISTINCT i.id_inst, i.n_inst FROM instancia i"
                . " JOIN cv_visao_aluno va ON va.fk_id_inst = i.id_inst"
                . " WHERE va.periodo_letivo = '" . $periodo . "'"
                . " ORDER BY i.n_inst";

        $query = $this->db->query($sql);
        $d = $query->fetchAll();

        foreach ($d as $v) {
            $esc[$v['id_inst']] = $v['n_inst'];
        }

        return $esc;
    }

    public function pegaclassevisaosec($per, $esc) {

        $sql = "SELECT DISTINCT t.id_turma, t.n_turma FROM cv_visao_aluno va"
                . " JOIN ge_turmas t ON t.id_turma = va.fk_id_turma"
                . " WHERE va.periodo_letivo = '" . $per . "'"
                . " AND va.fk_id_inst = '" . $esc . "'"
                . " ORDER BY t.n_turma";

        $query = $this->db->query($sql);
        $e = $query->fetchAll();

        foreach ($e as $v) {
            $classe[$v['id_turma']] = $v['n_turma'];
        }
        return $classe;
    }

    public function pegadadosvisaosec($per, $esc, $clas, $tipo) {

        if ($tipo == 1) {
            $sql = "SELECT va.fk_id_inst, p.n_pessoa, p.dt_nasc, ta.chamada, va.oculos, va.olho_direito,"
                    . " va.olho_esquerdo, va.fk_id_sinais, va.outros_sinais,"
                    . " va.usouoculoslentes, va.situacao_teste FROM cv_visao_aluno va"
                    . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = va.fk_id_turma"
                    . " AND ta.fk_id_pessoa = va.fk_id_pessoa"
                    . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                    . " AND t.id_turma = ta.fk_id_turma"
                    . " JOIN pessoa p ON p.id_pessoa = va.fk_id_pessoa"
                    . " WHERE va.fk_id_inst = '" . $esc . "' AND ta.situacao = '" . 'Frequente' . "'"
                    . " AND va.fk_id_turma = '" . $clas . "' AND va.periodo_letivo = '" . $per . "'"
                    . " ORDER BY ta.chamada";
        } else {
            $sql = "SELECT va.id_visao, va.fk_id_inst, p.n_pessoa, p.dt_nasc, ta.fk_id_turma, ta.chamada, va.reteste_oculos, va.reteste_direito,"
                    . " va.reteste_esquerdo, va.reteste_sinais, va.periodo_letivo, t.n_turma,"
                    . " va.reteste_sit FROM cv_visao_aluno va"
                    . " JOIN ge_turma_aluno ta ON ta.fk_id_turma = va.fk_id_turma"
                    . " AND ta.fk_id_pessoa = va.fk_id_pessoa"
                    . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                    . " AND t.id_turma = ta.fk_id_turma"
                    . " JOIN pessoa p ON p.id_pessoa = va.fk_id_pessoa"
                    . " WHERE va.fk_id_inst = '" . $esc . "' AND ta.situacao = '" . 'Frequente' . "'"
                    . " AND va.fk_id_turma = '" . $clas . "' AND va.periodo_letivo = '" . $per . "'"
                    . " AND va.reteste = 'Sim'"
                    . " ORDER BY ta.chamada";
        }

        $query = $this->db->query($sql);
        $d = $query->fetchAll();

        return $d;
    }

}
