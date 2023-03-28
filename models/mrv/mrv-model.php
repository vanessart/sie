<?php

class mrvModel extends MainModel {

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
        $this->db = new DB();

        // Configura o controlador
        $this->controller = $controller;

        // Configura os parâmetros
        //$this->parametros = $this->controller->parametros;
        // Configura os dados do usuário
        $this->userdata = $this->controller->userdata;

        if (DB::sqlKeyVerif('naoInteresse')) {

            $id_pessoa = $_POST['id_pessoa'];

            $aluno = new aluno($id_pessoa);
            $aluno->vidaEscolar(NULL, tool::id_inst());

            @$end = sql::get('endereco', '*', ['fk_id_pessoa' => @$id_pessoa], 'fetch');
            $insert['id_pessoa'] = @$_POST['id_pessoa'];
            $insert['n_pessoa'] = @$_POST['n_pessoa'];
            $insert['cie_ben'] = @$_POST['cie'];
            $insert['n_escola_ben'] = @$_POST['escola'];
            $insert['rg_aluno_ben'] = $aluno->_rg;
            $insert['oe_aluno_ben'] = $aluno->_rgOe;
            $insert['ra_ben'] = $aluno->_ra . '-' . $aluno->_ra_dig . ' ' . $aluno->_ra_uf;
            $insert['dt_nasc'] = $aluno->_nasc;
            // $insert['n_responsavel_ben'] = $aluno->_responsavel;
            // $insert['cpf_responsavel_ben'] = $aluno->_responsCpf;
            $insert['logradouro'] = $end['logradouro_gdae'];
            $insert['num_ben'] = $end['num_gdae'];
            $insert['complemento_ben'] = $end['complemento'];
            $insert['bairro'] = $end['bairro'];
            $insert['localidade'] = $end['cidade'];
            $insert['uf'] = $end['uf'];
            $insert['cep'] = $end['cep'];
            $insert['tel_ben'] = $aluno->_tel1;
            $insert['cel_ben'] = $aluno->_tel2;
            $insert['email_fieb_ben'] = $aluno->_responsEmail;
            $insert['sex_aluno_ben'] = $aluno->_sexo;
            $insert['turma_ben'] = $aluno->_codigo_classe;
            $insert['num_chamada_ben'] = @$_POST['chamada'];
            $insert['fk_id_turma'] = @$_POST['id_turma'];
            $insert['status_ben'] = 'NI';
            $insert['fk_id_pl'] = '87';
            if (($end['cidade'] == ucfirst(CLI_CIDADE)) or ( $end['cidade'] == CLI_CIDADE)) {
                $insert['morador_barueri_ben'] = 'Sim';
                $insert['categoria'] = '99';
                $insert['obs_ben'] = 'Não há Interesse';
            } else {
                $insert['morador_barueri_ben'] = 'Não';
                $insert['categoria'] = '98';
                $insert['obs_ben'] = 'Não Munícipe';
            }

            $this->db->ireplace('mrv_beneficiado', $insert, 1);

            log::logSet("Cadastrou (escola " . tool::id_inst() . ")");
            tool::alert("Salvo com sucesso!");
        }

        if (DB::sqlKeyVerif('inscricaoitb')) {

            $id_pessoa = $_POST['id_pessoa'];

            $aluno = new aluno($id_pessoa);
            $aluno->vidaEscolar(NULL, tool::id_inst());

            @$end = sql::get('endereco', '*', ['fk_id_pessoa' => @$id_pessoa], 'fetch');

            $insert['id_pessoa'] = $aluno->_rse;
            $insert['n_pessoa'] = $aluno->_nome;
            $insert['cie_ben'] = tool::cie();
            $insert['n_escola_ben'] = $aluno->_escola;
            $insert['rg_aluno_ben'] = $aluno->_rg;
            $insert['oe_aluno_ben'] = $aluno->_rgOe;
            $insert['ra_ben'] = $aluno->_ra . '-' . $aluno->_ra_dig . ' ' . $aluno->_ra_uf;
            $insert['dt_nasc'] = $aluno->_nasc;
            // tinha muitas escola sem os dados
            // $insert['n_responsavel_ben'] = $aluno->_responsavel;
            // $insert['rg_responsavel_ben'] = 1;
            // $insert['cpf_responsavel_ben'] = $aluno->_responsCpf;
            $insert['logradouro'] = $end['logradouro'];
            $insert['num_ben'] = $end['num'];
            $insert['complemento_ben'] = $end['complemento'];
            $insert['bairro'] = $end['bairro'];
            $insert['localidade'] = $end['cidade'];
            $insert['uf'] = $end['uf'];
            $insert['cep'] = $end['cep'];
            $insert['tel_ben'] = $aluno->_tel1;
            $insert['cel_ben'] = $aluno->_tel2;
            //$insert['email_fieb_ben'] = $aluno->_responsEmail;
            $insert['email_fieb_ben'] = $this->pegaemailgoogle($id_pessoa);
            $insert['sex_aluno_ben'] = $aluno->_sexo;
            $insert['turma_ben'] = $aluno->_codigo_classe;
            $insert['num_chamada_ben'] = $aluno->_chamada;
            $insert['fk_id_pl'] = '87';

            /*
              $insert['media6_ben'] = 0;
              $insert['media7_ben'] = 0;
              $insert['media8_ben'] = 0;
              $insert['media9_ben'] = 0;
              $insert['media_final_ben'] = 0;
             *
             */

            $insert['morador_barueri_ben'] = "Sim";
            $insert['categoria'] = '1';
            // $insert['morador4_completo_ben'] = $_POST['mora4'];
            //$insert['estudou4_ben'] = $_POST['estuda'];
            // $insert['obs_ben'] = 1;
            $insert['fk_id_turma'] = $aluno->_id_turma;
            // $insert['classificacao_escola'] = 0;

            $sta = 'Ag. Def.';
            /*
              // if (($_POST['mora4'] == 'Sim') AND ( $_POST['estuda'] == 'Sim')) {
              if ($_POST['mora4'] == 'Sim') {
              // somente para confirmar
              if ($_POST['morador'] == 'Sim') {
              $insert['categoria'] = '1';
              } else {
              $insert['categoria'] = '98';
              $insert['obs_ben'] = 'Morador de ' . $_POST['cidade'];
              $sta = 'Não Munícipe';
              }
              //} elseif ($_POST['morador'] == 'Sim') {
              //    $insert['categoria'] = '3';
              } else {
              $insert['categoria'] = '98';
              $insert['obs_ben'] = 'Morador de ' . $_POST['cidade'];
              $sta = 'Não Munícipe';
              }

             */
            $insert['status_ben'] = $sta;
            // $insert['classificacao_geral'] = 0;

            $this->db->ireplace('mrv_beneficiado', $insert, 1);

            if ($sta == 'Ag. Def.') {
                $notashist = $this->peganotasNovo($id_pessoa);
                $notabimes = $this->peganotasnono($id_pessoa);
                // $media = $this->calculamedia($id_pessoa);
            }
            log::logSet("Cadastrou (escola " . tool::id_inst() . ")");
            tool::alert("Salvo com sucesso!");
        }

        if ($this->db->sqlKeyVerif('gravaatz')) {

            if ($_POST[1]['status_ben'] == 'NI') {
                $_POST[1]['categoria'] = '99';
                // } elseif (($_POST[1]['morador4_completo_ben'] == 'Sim') AND ( $_POST[1]['estudou4_ben'] == 'Sim')) {
            } else {
                $_POST[1]['categoria'] = '1';
            }

            if ($_POST[1]['status_ben'] == 'Não Munícipe') {
                $_POST[1]['categoria'] = '98';
            }

            if ($_POST[1]['status_ben'] == 'Indeferida(Doc)') {
                $_POST[1]['categoria'] = '97';
            }

            if ($_POST[1]['status_ben'] == 'Ag. Def.') {
                $notashist = $this->peganotasNovo($_POST['id_pessoa']);
                $notabimes = $this->peganotasnono($_POST['id_pessoa']);
            }

            $this->db->ireplace('mrv_beneficiado', $_POST[1]);
        }
        if (!empty($_POST['grava'])) {

            foreach ($_POST['nota'] as $kk => $vv) {
                $vv['b1'] = str_replace(',', '.', $vv['b1']);
                $vv['b2'] = str_replace(',', '.', $vv['b2']);
                $sqln = "UPDATE mrv_nono SET bi1 = '" . $vv['b1'] . "', bi2 = '" . $vv['b2'] . "' WHERE fk_id_pessoa = " . $_POST['id_pessoa'] . " AND fk_id_disc = '" . $kk . "'";
                $query = $this->db->query($sqln);
                foreach ([6, 7, 8] as $vvv) {
                    $vv[$vvv] = str_replace(',', '.', $vv[$vvv]);
                    $sqla = "UPDATE mrv_notas SET nota = '" . $vv[$vvv] . "' WHERE fk_id_pessoa = " . $_POST['id_pessoa'] . " AND fk_id_disc = '" . $kk . "' AND ano = $vvv";
                    $query = $this->db->query($sqla);
                }
            }
            log::logSet("Atualizou (escola " . tool::id_inst() . ")");
            tool::alert("Atualizaçao efetuda com sucesso!");
        }
        if (!empty($_POST['selecao'])) {
            $mediaaluno = $this->calculamedia($_POST['as']);
        }

        if (!empty($_POST['media'])) {

            $ver = "SELECT status_escola, vagas_itb FROM mrv_escolas WHERE fk_id_inst = '" . tool::id_inst() . "'";

            $query = $this->db->query($ver);
            $res = $query->fetch();

            switch ($res['status_escola']) {
                case "FinalizadoFinal":
                    tool::alert("Status Finalizado");
                    break;
                case "Pendente":
                    tool::alert("Exite(m) pendência(s): Clique em Auditoria para verificar");
                    break;
                case "Calcular":

                    $alunoItb = "SELECT id_pessoa FROM mrv_beneficiado WHERE fk_id_pl = 87 AND cie_ben = '" . tool::cie() . "' AND categoria IN('1')";

                    $query = $this->db->query($alunoItb);
                    $al = $query->fetchAll();

                    foreach ($al as $k => $v) {
                        //   echo $v['id_pessoa'] . '<br />';
                        $sel[$k] = $v['id_pessoa'];
                    }

                    $acerto = $this->atzdadosstatus();
                    $mediaaluno = $this->calculamedia($sel);
                    $classif = $this->classificacaoitb();

                    $finaliza = "UPDATE mrv_escolas SET status_escola = '" . 'FinalizadoFinal' . "' WHERE fk_id_inst = '" . tool::id_inst() . "'";
                    $query = $this->db->query($finaliza);
                    tool::alert("Operação efetuada com Sucesso");
                    break;
                default :
                    tool::alert("Clique em Auditoria para libera o cálculo da média");
            }
        }

        if (!empty($_POST['atzdados'])) {
            $cor = $this->correcaodados();
        }

        if (!empty($_POST['classfinal'])) {
            $final = $this->classificacaoitbgeral();
        }

        if (!empty($_POST['AcertoNono'])) {
            $acerto = $this->acertopeganotasnono();
        }

        if (!empty($_POST['atzmediageral'])) {
            $final = $this->acertomediageral();
        }
    }

// Crie seus próprios métodos daqui em diante

    public function peganotasAntigo($id_pessoa) {
        //Histórico antigo
        $h = new historico($id_pessoa);
        $hist = $h->_dadosAntigos;

        @$nota = $hist['discNota'];
        $cod = [6, 9, 10, 11, 12, 13, 14, 15, 30];
        foreach ($cod as $v) {

            for ($x = 6; $x < 9; $x++) {
                //Juntei as notas de Inglês mudar o $x
                // 2019 era 15
                //Preparei p 2022
                if ($v == 30) {
                    if ($x == 7 || $x == 8) {
                        $insert['nota'] = (empty($nota[$x][$v]) ? 0 : $nota[$x][30]);
                        $insert['fk_id_pessoa'] = $id_pessoa;
                        $insert['fk_id_disc'] = 15;
                        $insert['ano'] = $x;

                        $this->db->replace('mrv_notas', $insert);
                    }
                } else {
                    $insert['fk_id_pessoa'] = $id_pessoa;
                    $insert['fk_id_disc'] = $v;
                    $insert['ano'] = $x;
                    $insert['nota'] = (empty($nota[$x][$v]) ? 0 : $nota[$x][$v]);

                    $this->db->replace('mrv_notas', $insert);
                }
            }
        }
    }

    public function peganotasnono($id_pessoa) {

        $sql = "SELECT * FROM aval_mf_1_87 WHERE fk_id_pessoa = $id_pessoa";
        $query = pdoHab::getInstance()->query($sql);
        $nono = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($nono as $v) {
            foreach ($v as $kk => $vv) {
                if (substr($kk, 0, 6) == 'media_' && !empty($vv)) {
                    $notaNono[$v['atual_letiva']][substr($kk, 6)] = $vv;
                }
            }
        }

        //$cod = [6, 9, 10, 11, 12, 13, 14, 15, 30];
        $cod = [6, 9, 10, 11, 12, 13, 14, 30];

        foreach ($cod as $v) {
            $insert['fk_id_pessoa'] = $id_pessoa;
            //Juntei as notas de Inglês
            if ($v == 30) {
                $insert['fk_id_disc'] = 15;
            } else {
                $insert['fk_id_disc'] = $v;
            }

            $insert['bi1'] = (empty($notaNono[1][$v]) ? 0 : $notaNono[1][$v]);
            $insert['bi2'] = (empty($notaNono[2][$v]) ? 0 : $notaNono[2][$v]);
            $this->db->replace('mrv_nono', $insert);
        }
    }

    public function calculamedia($alunoitb) {

        foreach ($alunoitb as $v) {
            // 224857 Aluna Sayuru Uema Coelho - Histórico em Japonês
            if ($v != 224857) {
                $ponto = $this->verificapontomedia($v);

                $cal = "SELECT fk_id_pessoa, ano, AVG(nota) as m FROM mrv_notas GROUP BY fk_id_pessoa, ano HAVING (fk_id_pessoa = '" . $v . "')";

                $query = $this->db->query($cal);
                $array = $query->fetchAll();

                $anoserie = [6, 7, 8];
                $mediaf = 0;

                if (!empty($array)) {
                    foreach ($array as $vv) {

                        $mediaf = $mediaf + $vv['m'];
                        foreach ($anoserie as $w) {
                            $insert['id_pessoa'] = $vv['fk_id_pessoa'];
                            $insert['media' . $vv['ano'] . '_ben'] = $vv['m'];

                            $this->db->ireplace('mrv_beneficiado', $insert, 1);
                        }
                    }

                    $calb = "SELECT fk_id_pessoa, AVG(bi1) as b1, AVG(bi2) as b2 FROM mrv_nono GROUP BY fk_id_pessoa HAVING (fk_id_pessoa = '" . $v . "')";
                    $query = $this->db->query($calb);
                    $bimestre = $query->fetchAll();

                    foreach ($bimestre as $b) {
                        $mediab = (($b['b1'] + $b['b2']) / 2);
                    }

                    $mediaf = ($mediaf + $mediab) / 4;
                    if ($mediaf < 6.5) {
                        $sit = 'Indeferida';
                    } else {
                        $sit = 'Deferida';
                    }

                    $insert['id_pessoa'] = $b['fk_id_pessoa'];
                    $insert['media9_ben'] = $mediab;
                    $insert['media_final_ben'] = $mediaf;
                    $insert['status_ben'] = $sit;
                    log::logSet("Pegou as Notas (escola " . tool::id_inst() . ")");
                    $this->db->ireplace('mrv_beneficiado', $insert, 1);
                } else {
                    tool::alert("Aluno sem Nota");
                }
            }     // tool::alert("Operação efetuada com Sucesso");
        }
    }

    public function digitacaonotas($id_pessoa) {

        $sql = "SELECT d.n_disc, n.fk_id_pessoa, n.ano, n.nota FROM ge_disciplinas d"
                . " JOIN mrv_notas n ON d.id_disc = n.fk_id_disc WHERE n.fk_id_pessoa = '" . $id_pessoa . "'";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        foreach ($array as $v) {
            $dados[$v['n_disc']][$v['ano']] = $v['nota'];
        }

        $sql = "SELECT n.fk_id_pessoa, n.bi1, n.bi2, d.n_disc FROM mrv_nono n"
                . " JOIN ge_disciplinas d on d.id_disc = n.fk_id_disc WHERE n.fk_id_pessoa = '" . $id_pessoa . "'";

        $query = $this->db->query($sql);
        $array = $query->fetchAll();

        foreach ($array as $v) {
            $dados[$v['n_disc']]['b1'] = $v['bi1'];
            $dados[$v['n_disc']]['b2'] = $v['bi2'];
        }

        return $dados;
    }

    public function listadeferimento($turma) {
        /*
          $wsql = "SELECT p.id_pessoa, p.n_pessoa, p.dt_nasc, p.ra, p.ra_dig, ta.fk_id_turma, ta.chamada,"
          . " ta.codigo_classe, b.status_ben, b.categoria FROM pessoa p"
          . " RIGHT JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa"
          . " LEFT JOIN mrv_beneficiado b on b.id_pessoa = p.id_pessoa"
          . " WHERE ta.situacao = '" . 'Frequente' . "' AND ta.fk_id_turma = '" . $turma . "' ORDER BY ta.codigo_classe, ta.chamada";

         */
        $wsql = "SELECT p.id_pessoa, p.n_pessoa, p.dt_nasc, p.ra, p.ra_dig, ta.fk_id_turma, ta.chamada,"
                . " ta.codigo_classe, b.status_ben, b.categoria, e.cidade FROM pessoa p"
                . " JOIN mrv_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa"
                . " LEFT JOIN mrv_beneficiado b on b.id_pessoa = p.id_pessoa"
                . " LEFT JOIN endereco e ON e.fk_id_pessoa = p.id_pessoa"
                . " WHERE ta.situacao = '" . 'Frequente' . "' AND ta.fk_id_turma = '" . $turma . "' ORDER BY ta.codigo_classe, ta.chamada";

        $query = $this->db->query($wsql);
        $dados = $query->fetchAll();

        return $dados;
    }

    public function completadadossala($idturma) {

        $cab = sql::get(['ge_turmas', 'ge_ciclos'], '*', ['id_turma' => $idturma]);

        foreach ($cab as $b) {

            @$prof = $b['professor'];
            @$cod = $b['codigo'];
            @$periodo = $b['periodo_letivo'];
            @$desc = $b['n_turma'];
            @$dtr = $b['n_ciclo'];

            switch ($b['periodo']) {
                case "M":
                    $p = "Manhã";
                    $pp = "Matutino";
                    break;
                case "T":
                    $p = "Tarde";
                    $pp = "Vespertino";
                    break;
                case "I":
                    $p = "Integral";
                    $pp = "Integral";
                    break;
                case "G":
                    $p = "Geral";
                    $pp = "Integral";
                    break;
                case "N":
                    $p = "Noite";
                    $pp = "Noturno";
                    break;
            }

            switch (substr($b['codigo'], 0, 2)) {
                case "EB":
                case "EM":
                case "EI":
                    $ed = " da Educação Infantil";
                    break;
                case "EF":
                    $ed = " do Ensino Fundamental";
                    break;
                case "EJ":
                    $ed = " Eja do Ensino Fundamental";
                    break;
            }
        }

        $ppp = $cod . '|' . $periodo . '|' . $p . '|' . @$prof . '|' . @$pp . '|' . @$desc . '|' . $ed . '|' . $dtr;

        return $ppp;
    }

    public function resumoinscricao() {

        //Atualizando situação
        $sql = "UPDATE mrv_turma_aluno mrv"
                . " JOIN ge_turma_aluno ta ON ta.id_turma_aluno = mrv.id_turma_aluno"
                . " SET mrv.situacao = ta.situacao, mrv.fk_id_tas = ta.fk_id_tas"
                . " WHERE ta.fk_id_tas = 1 AND ta.periodo_letivo = 2022";

        $query = $this->db->query($sql);

        // Ajuste alunos transferidos e matriculados fora da escola (TE)
        $wcie_e = tool::cie();
        //Roberto Brandão
        if ($wcie_e == '244788') {
            $sql = "UPDATE mrv_turma_aluno mrv"
                    . " SET mrv.situacao = 'Frequente', mrv.fk_id_tas = 0"
                    . " WHERE  fk_id_inst = 48 AND periodo_letivo = 2022"
                    . " AND fk_id_pessoa = 223940";

            $query = $this->db->query($sql);
        }
        //Gilberto
        if ($wcie_e == '244764') {
            $sql = "UPDATE mrv_turma_aluno mrv"
                    . " SET mrv.situacao = 'Frequente', mrv.fk_id_tas = 0"
                    . " WHERE  fk_id_inst = 46 AND periodo_letivo = 2022"
                    . " AND fk_id_pessoa = 272946";

            $query = $this->db->query($sql);
        }
        //Taro
        if ($wcie_e == '244776') {
            $sql = "UPDATE mrv_turma_aluno mrv"
                    . " SET mrv.situacao = 'Frequente', mrv.fk_id_tas = 0"
                    . " WHERE  fk_id_inst = 47 AND periodo_letivo = 2022"
                    . " AND fk_id_pessoa = 194693";

            $query = $this->db->query($sql);
        }
        //Atualizando campo CIE
        $sql = "UPDATE mrv_beneficiado b"
                . " JOIN mrv_turma_aluno ta ON ta.fk_id_pessoa = b.id_pessoa AND ta.fk_id_turma = b.fk_id_turma"
                . " JOIN mrv_escolas e ON e.fk_id_inst = ta.fk_id_inst"
                . " SET b.cie_ben = e.cie_escola WHERE b.fk_id_pl = 87"
                . " AND ta.situacao = 'Frequente' AND b.cie_ben IS NULL";

        $query = $this->db->query($sql);

        $id_inst = tool::id_inst();
        $sel = "SELECT ta.fk_id_inst, ta.situacao FROM mrv_turma_aluno ta"
                . " JOIN mrv_beneficiado b ON b.id_pessoa = ta.fk_id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " WHERE t.fk_id_pl = 87 AND ta.fk_id_inst = '" . $id_inst . "'";

        $query = $this->db->query($sel);
        $dados = $query->fetchAll();

        foreach ($dados as $v) {
            @$res[$v['fk_id_inst']][$v['situacao']]++;
        }

        $sit = ['Ag. Def.', 'Deferida', 'Indeferida', 'NI', 'Outras', 'Não Munícipe', 'Indeferida(Doc)', 'Transferido'];
        // Iniciando a Array       
        foreach ($sit as $s) {
            @$res[$id_inst][$s] = 0;
        }

        $sel = "SELECT b.status_ben, e.fk_id_inst, e.vagas_itb FROM mrv_beneficiado b"
                . " JOIN mrv_escolas e ON e.cie_escola = b.cie_ben"
                . " WHERE b.fk_id_pl = '" . '87' . "' AND e.fk_id_inst = '" . tool::id_inst() . "'";

        $query = $this->db->query($sel);
        $dados = $query->fetchAll();

        foreach ($dados as $v) {
            @$res[$v['fk_id_inst']][$v['status_ben']]++;
        }
        @$res[$v['fk_id_inst']]['Num. Vagas'] = $v['vagas_itb'];

        return $res;
    }

    public function alunosselecionados($al) {

        $idaluno = implode(",", $al);

        $wsql = "SELECT * FROM mrv_beneficiado WHERE id_pessoa IN(" . $idaluno . ")";

        $query = $this->db->query($wsql);
        $dados = $query->fetchAll();

        return $dados;
    }

    public function acertopeganotasnono() {

        $aluno = "SELECT distinct id_pessoa FROM mrv_beneficiado"
                . " JOIN mrv_nono ON mrv_nono.fk_id_pessoa = mrv_beneficiado.id_pessoa"
                . " WHERE mrv_beneficiado.fk_id_pl = 87";

        $query = $this->db->query($aluno);
        $aluno2 = $query->fetchAll();

        foreach ($aluno2 as $y) {

            $sql = "SELECT * FROM aval_mf_1_87 WHERE fk_id_pessoa = '" . $y['id_pessoa'] . "'";
            $query = pdoHab::getInstance()->query($sql);
            $nono = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($nono as $v) {
                foreach ($v as $kk => $vv) {
                    if (substr($kk, 0, 6) == 'media_' && !empty($vv)) {
                        $notaNono[$v['atual_letiva']][substr($kk, 6)] = $vv;
                    }
                }
            }
            $cod = [6, 9, 10, 11, 12, 13, 14, 15, 30];
            foreach ($cod as $v) {
                $insert['fk_id_pessoa'] = $y['id_pessoa'];
                $insert['fk_id_disc'] = $v;
                $insert['bi1'] = (empty($notaNono[1][$v]) ? 0 : $notaNono[1][$v]);
                $insert['bi2'] = (empty($notaNono[2][$v]) ? 0 : $notaNono[2][$v]);
                $this->db->replace('mrv_nono', $insert);
            }
        }

        tool::alert("Operação efetuada com Sucesso");
    }

    public function verificastatusescola() {
        /*
          Pendente
          Calcular
          FinalizadoFinal

         *
         */
        $sql = "SELECT status_escola FROM mrv_escolas WHERE cie_escola = '" . tool::cie() . "'";
        $query = $this->db->query($sql);
        $dados = $query->fetch();

        if ($dados['status_escola'] == "FinalizadoFinal") {
            $final = "OK";
        } else {
            $final = "Pendente";
        }
        return $final;
    }

    public function classificacaoitb() {
        $sql = "UPDATE mrv_beneficiado"
                . " SET classificacao_escola = 0,  classificacao_geral = 0"
                . " WHERE cie_ben = '" . tool::cie() . "' AND fk_id_pl = 87";
        $query = $this->db->query($sql);

        $sql = "SELECT b.id_pessoa, b.media_final_ben, b.classificacao_escola, b.categoria FROM mrv_beneficiado b"
                . " WHERE status_ben = 'Deferida' AND fk_id_pl = 87 AND cie_ben = '" . tool::cie() . "' ORDER BY b.media_final_ben DESC,"
                . " b.media9_ben DESC, b.media8_ben DESC, b.media7_ben DESC, b.media6_ben DESC, b.dt_nasc";

        $query = $this->db->query($sql);
        $dados = $query->fetchAll();

        $clas = 0;

        foreach ($dados as $k => $v) {
            $clas += 1;
            //  if ($clas <= $vaga) {
            $wsql = "UPDATE mrv_beneficiado SET classificacao_escola = '" . $clas . "' Where id_pessoa = '" . $v['id_pessoa'] . "'";
            //  } else {
            //      $wsql = "UPDATE mrv_beneficiado SET classificacao_escola = '" . $clas . "', categoria =  '" . "2" . "' Where id_pessoa = '" . $v['id_pessoa'] . "'";
            //   }

            $query = $this->db->query($wsql);
        }
        /*
          $sql = "SELECT b.id_pessoa, b.media_final_ben, b.classificacao_escola, b.categoria FROM mrv_beneficiado b"
          . " WHERE categoria = '" . '3' . "' AND fk_id_pl = 87 AND status_ben = 'Deferida' AND cie_ben = '" . tool::cie() . "' ORDER BY b.media_final_ben DESC,"
          . " b.media9_ben DESC, b.media8_ben DESC, b.media7_ben DESC, b.media6_ben DESC, b.dt_nasc";

          $query = $this->db->query($sql);
          $dados = $query->fetchAll();

          foreach ($dados as $k => $v) {
          $clas += 1;

          $wsql = "UPDATE mrv_beneficiado SET classificacao_escola = '" . $clas . "', categoria =  '" . "3" . "' Where id_pessoa = '" . $v['id_pessoa'] . "' AND fk_id_pl = 87";

          $query = $this->db->query($wsql);
          }
         * /
         */
    }

    public function correcaodados() {

        //atz Categoria
        $sql = "UPDATE mrv_beneficiado b SET categoria = '" . '1' . "'"
                . "WHERE  b.morador_barueri_ben = '" . 'Sim' . "' AND b.morador4_completo_ben = '" . 'Sim' . "'"
                . " AND b.estudou4_ben = '" . 'Sim' . "' AND b.status_ben = '" . 'Deferida' . "' AND cie_ben = '" . tool::cie() . "'";

        $query = $this->db->query($sql);

        // Não mora a 4 anos
        $sql = "UPDATE mrv_beneficiado b SET categoria = '" . '3' . "'"
                . " WHERE  b.morador_barueri_ben = '" . 'Sim' . "'"
                . " AND b.morador4_completo_ben = '" . 'Não' . "' AND b.status_ben =  '" . 'Deferida' . "' AND cie_ben = '" . tool::cie() . "'";
        $query = $this->db->query($sql);

        // Não estudou 4 anos
        $sql = "UPDATE mrv_beneficiado b SET categoria = '" . '3' . "'"
                . " WHERE  b.morador_barueri_ben = '" . 'Sim' . "'"
                . " AND b.estudou4_ben =  '" . 'Não' . "' AND b.status_ben =  '" . 'Deferida' . "' AND cie_ben = '" . tool::cie() . "'";
        $query = $this->db->query($sql);

        //Não Morador
        $sql = "UPDATE mrv_beneficiado b SET categoria = '" . '99' . "' WHERE b.morador_barueri_ben = '" . 'Não' . "' AND cie_ben = '" . tool::cie() . "'";
        $query = $this->db->query($sql);

        //   tool::alert("OK");
    }

    public function verificaponto() {

        $atznotas = "SELECT * FROM mrv_notas n"
                . " JOIN mrv_beneficiado b on b.id_pessoa = n.fk_id_pessoa"
                . " WHERE b.fk_id_pl = 87 AND b.cie_ben = '" . tool::cie() . "' AND nota LIKE '" . "%,%" . "'";

        $query = $this->db->query($atznotas);
        $atz = $query->fetchAll();

        foreach ($atz as $at) {

            $sql = "update mrv_notas "
                    . " set nota =  " . str_replace(',', '.', $at['nota']) . " "
                    . " where fk_id_pessoa = " . $at['fk_id_pessoa']
                    . " and ano = " . $at['ano']
                    . " and fk_id_disc = " . $at['fk_id_disc'];

            $query = $this->db->query($sql);
        }

        //bimestre

        $atzbi = "SELECT * FROM mrv_nono n"
                . " JOIN mrv_beneficiado b ON b.id_pessoa = n.fk_id_pessoa"
                . " WHERE b.fk_id_pl = 87 AND b.cie_ben = '" . tool::cie() . "' AND n.bi1 LIKE '" . "%,%" . "'";

        $query = $this->db->query($atzbi);
        $atzb = $query->fetchAll();

        foreach ($atzb as $atb) {

            $sql = "update mrv_nono "
                    . " SET bi1 = " . str_replace(',', '.', $atb['bi1'])
                    . " where fk_id_pessoa = " . $atb['fk_id_pessoa']
                    . " and fk_id_disc = " . $atb['fk_id_disc'];
            $query = $this->db->query($sql);
        }

        $atzbi = "SELECT * FROM mrv_nono n"
                . " JOIN mrv_beneficiado b ON b.id_pessoa = n.fk_id_pessoa"
                . " WHERE b.fk_id_pl = 87 AND b.cie_ben = '" . tool::cie() . "' AND n.bi2 LIKE '" . "%,%" . "'";

        $query = $this->db->query($atzbi);
        $atzb = $query->fetchAll();

        foreach ($atzb as $atb) {

            $sql = "update mrv_nono "
                    . " SET bi2 = " . str_replace(',', '.', $atb['bi2'])
                    . " where fk_id_pessoa = " . $atb['fk_id_pessoa']
                    . " and fk_id_disc = " . $atb['fk_id_disc'];
            $query = $this->db->query($sql);
        }
    }

    public function atzdadosstatus() {

        $sql = "SELECT  * FROM mrv_beneficiado WHERE fk_id_pl = 87 AND status_ben = '" . 'Deferida' . "' AND cie_ben = '" . tool::cie() . "' AND categoria = '" . '99' . "'";

        $query = $this->db->query($sql);
        $dados = $query->fetchAll();

        if (!empty($dados)) {
            foreach ($dados as $v) {
                if (strcasecmp($v['localidade'], CLI_CIDADE) == 0) {
                    /*
                      if (($v['morador4_completo_ben'] == 'Sim') AND ( $v['estudou4_ben'] == 'Sim')) {
                      $cat = '1';
                      $s = "Deferida";
                      } else {
                      $cat = '3';
                      $s = "Deferida";
                      }
                     * 
                     */
                    $cat = '1';
                    $s = "Deferida";
                } else {
                    $cat = '99';
                    $s = "Indeferida";
                }

                $wsql = "UPDATE mrv_beneficiado SET categoria = '" . $cat . "', status_ben  = '" . $s . "' WHERE id_pessoa = '" . $v['id_pessoa'] . "'";
                $query = $this->db->query($wsql);
            }
        }
        $sql = "UPDATE mrv_beneficiado SET classificacao_escola = 0, classificacao_geral = 0 WHERE fk_id_pl = 87 AND cie_ben = '" . tool::cie() . "'";
        $query = $this->db->query($sql);

        //  $sql = "UPDATE mrv_beneficiado SET categoria = '" . '1' . "' WHERE categoria = '" . '2' . "' AND cie_ben = '" . tool::cie() . "'";
        // $query = $this->db->query($sql);
    }

    public function classificacaoitbgeral() {

        //   $sql = "SELECT * FROM mrv_escolas WHERE status_escola NOT LIKE 'FinalizadoFinal' ORDER BY `status_escola` ASC ";
        //  $query = $this->db->query($sql);
        //  if (!empty($query)) {
        //  tool::alert("Favor Finalizar todas as escolas");
        //    } else {
        $sql = "UPDATE mrv_beneficiado SET classificacao_geral = 0 where fk_id_pl = 87";
        $query = $this->db->query($sql);
        /*
          $sql = "SELECT b.id_pessoa, b.media_final_ben, b.classificacao_escola, b.categoria FROM mrv_beneficiado b"
          . " WHERE b.status_ben = 'Deferida' AND b.fk_id_pl = 81 ORDER BY b.categoria ASC, b.media_final_ben DESC,"
          . " b.media9_ben DESC, b.media8_ben DESC, b.media7_ben DESC, b.media6_ben DESC, b.dt_nasc";

          $query = $this->db->query($sql);
          $dados = $query->fetchAll();

          $clas = 0;

          foreach ($dados as $k => $v) {

          $clas += 1;
          if ($clas <= 5000) {
          if ($v['categoria'] == 4) {
          //rodei mais de uma vez
          $wsql = "UPDATE mrv_beneficiado SET classificacao_geral = '" . $clas . "', categoria = '" . '5' . "' Where id_pessoa = '" . $v['id_pessoa'] . "' And fk_id_pl = 81";
          } else {
          $wsql = "UPDATE mrv_beneficiado SET classificacao_geral = '" . $clas . "' Where id_pessoa = '" . $v['id_pessoa'] . "' And fk_id_pl = 81";
          }
          } else {
          $wsql = "UPDATE mrv_beneficiado SET classificacao_geral = '" . $clas . "', categoria = '" . '4' . "' Where id_pessoa = '" . $v['id_pessoa'] . "' And fk_id_pl = 81";
          }
          $query = $this->db->query($wsql);
          }

          tool::alert("OK");
          //  }


         * 
         */
        $clas = 0;

        $sql = "SELECT b.id_pessoa, b.media_final_ben, b.classificacao_escola, b.categoria FROM mrv_beneficiado b"
                . " WHERE b.status_ben = 'Deferida' AND b.fk_id_pl = 87 AND b.categoria in (1) ORDER BY b.media_final_ben DESC,"
                . " b.media9_ben DESC, b.media8_ben DESC, b.media7_ben DESC, b.media6_ben DESC, b.dt_nasc";

        $query = $this->db->query($sql);
        $dados = $query->fetchAll();

        foreach ($dados as $k => $v) {
            $clas += 1;
            $wsql = "UPDATE mrv_beneficiado SET classificacao_geral = '" . $clas . "' Where id_pessoa = '" . $v['id_pessoa'] . "' And fk_id_pl = 87";
            $query = $this->db->query($wsql);
        }
        tool::alert("OK");
    }

    public function auditoriaitb() {

        $cie = tool::cie();

        $sql = "UPDATE mrv_beneficiado SET localidade = '" . CLI_CIDADE . "'"
                . " WHERE localidade = '" . strtolower(CLI_CIDADE) . "' AND fk_id_pl = 87"
                . " AND cie_ben = '" . $cie . "'";

        $query = $this->db->query($sql);

        $nt = $this->verificaponto();
        $sta = $this->resumoinscricao();

        foreach ($sta as $k => $v) {
            $soma = $v['Frequente'] - $v['Ag. Def.'] - $v['Deferida'] - $v['Indeferida'] - $v['NI'] - $v['Outras'] - $v['Indeferida(Doc)'];

            if ($soma != 0) {
                $erro_res[$k]['Inscricao'] = 'Existe aluno pendente no Menu Cadastro';
            }
            $soma = $v['Ag. Def.'] + $v['Outras'];
            if ($soma != 0) {
                $erro_res[$k]['Ag. Def.'] = 'Existe(m) ' . $soma . ' aluno(s) Ag. Def.';
            }
        }

        // $wsql = "SELECT * FROM mrv_beneficiado WHERE cie_ben = '" . tool::cie() . "' AND status_ben != '" . "Indeferida" . "' AND fk_id_pl = 24";
        $wsql = "SELECT * FROM mrv_beneficiado WHERE cie_ben = '" . tool::cie() . "' AND fk_id_pl = 87 AND categoria IN('1','2','3')";
        $query = $this->db->query($wsql);
        $dados = $query->fetchAll();

        foreach ($dados as $v) {
            switch ($v['status_ben']) {
                case "Deferida":
                case "Indeferida":
                    if (($v['localidade'] == ucfirst(CLI_CIDADE)) OR ( $v['localidade'] == CLI_CIDADE)) {
                        /*
                          if (($v['morador4_completo_ben'] == 'Sim') AND ( $v['estudou4_ben'] == 'Sim')) {
                          if ($v['categoria'] != "1") {
                          if ($v['categoria'] != "2") {
                          $erro_res[$k]['Categoria'][$v['id_pessoa']] = 'Atualizar os dados no Menu Editar: do(a) aluno(a) ' . $v['n_pessoa'];
                          }
                          }
                          } else {
                          if ($v['categoria'] != "3") {
                          $erro_res[$k]['Categoria'][$v['id_pessoa']] = 'Atualizar os dados no Menu Editar: do(a) aluno(a) ' . $v['n_pessoa'];
                          }
                          }
                         * 
                         */
                    } else {
                        $erro_res[$k]['Morador'][$v['id_pessoa']] = 'Aluno(a) ' . $v['n_pessoa'] . ' é morador de ' . $v['localidade'];
                    }

                    break;
                case "Indeferida(Doc)":
                    If ($v['categoria'] != '97') {
                        $erro_res[$k]['Categoria'][$v['id_pessoa']] = 'Atualizar os dados no Menu Editar: do(a) aluno(a) ' . $v['n_pessoa'];
                    }
                    break;
                case "NI":
                    If ($v['categoria'] != '99') {
                        $erro_res[$k]['Categoria'][$v['id_pessoa']] = 'Atualizar os dados no Menu Editar: do(a) aluno(a) ' . $v['n_pessoa'];
                    }
                    break;
                case "Transferido":
                    If ($v['categoria'] != '99') {
                        //echo 'aqui';
                        //  $erro_res[$k]['Categoria'][$v['id_pessoa']] = 'Atualizar os dados no Menu Editar: do(a) aluno(a) ' . $v['n_pessoa'];
                    } else {
                        //   echo 'aqui2';
                    }
                    break;
                default :
                    $erro_res[$k]['Categoria'][$v['id_pessoa']] = 'Atualizar os dados no Menu Editar: do(a) aluno(a) ' . $v['n_pessoa'];
                    break;
            }
        }

        //Verifica Notas
        $wsql = "SELECT b.id_pessoa, b.n_pessoa, b.cie_ben, n.ano, n.fk_id_disc, n.nota, b.turma_ben FROM mrv_notas n"
                . " RIGHT JOIN mrv_beneficiado b ON b.id_pessoa = n.fk_id_pessoa WHERE b.cie_ben = '" . tool::cie() . "'"
                . " AND b.categoria IN('1','2','3') AND b.fk_id_pl = 87";

        $query = $this->db->query($wsql);
        $dados = $query->fetchAll();

        foreach ($dados as $v) {

            $v['nota'] = str_replace(',', '.', $v['nota']);

            if ($v['nota'] == 0) {
                $erro_res[$v['cie_ben']][$v['id_pessoa']]['Nota'][] = 'Nota = 0: Verificar: Nota do(a) aluno(a) ' . $v['n_pessoa'] . ' ano ' . $v['ano'] . ' turma ' . $v['turma_ben'];
            } else {
                if (!(is_nan($v['nota']))) {

                    If ($v['nota'] > 10) {
                        $erro_res[$v['cie_ben']][$v['id_pessoa']]['Nota'][] = 'Nota Maior que 10: Verificar: Nota do(a) aluno(a) ' . $v['n_pessoa'] . ' ano ' . $v['ano'] . ' turma ' . $v['turma_ben'];
                    }
                    If ($v['nota'] < 5) {
                        $erro_res[$v['cie_ben']][$v['id_pessoa']]['Nota'][] = 'Nota Menor que 5: Verificar: Nota do(a) aluno(a)  ' . $v['n_pessoa'] . ' ano ' . $v['ano'] . ' turma ' . $v['turma_ben'];
                    }
                } else {
                    $erro_res[$v['cie_ben']][$v['id_pessoa']]['Nota'][] = 'Verificar: Nota do(a) aluno(a) ' . $v['n_pessoa'] . ' ano ' . $v['ano'] . ' turma ' . $v['turma_ben'];
                }
            }
        }

        $wsql = "SELECT b.id_pessoa, b.n_pessoa, b.cie_ben, n.fk_id_pessoa, n.bi1, n.bi2 FROM mrv_nono n"
                . " RIGHT JOIN mrv_beneficiado b ON b.id_pessoa = n.fk_id_pessoa WHERE b.cie_ben = '" . tool::cie() . "'"
                . " AND status_ben = '" . 'Deferida' . "' AND fk_id_pl = 87";

        $query = $this->db->query($wsql);
        $dados = $query->fetchAll();

        foreach ($dados as $v) {

            $v['bi1'] = str_replace(',', '.', $v['bi1']);
            $v['bi2'] = str_replace(',', '.', $v['bi2']);

            if (($v['bi1'] == 0) OR ( $v['bi2'] == 0)) {
                $erro_res[$v['cie_ben']][$v['id_pessoa']]['Nota'][] = 'Verificar: Nota do(a) aluno(a) ' . $v['n_pessoa'];
            } else {
                if (!(is_nan($v['bi1']))) {
                    If ($v['bi1'] > 10) {
                        $erro_res[$v['cie_ben']][$v['id_pessoa']]['Nota'][] = 'Nota Maior que 10: Verificar: Nota do(a) aluno(a) ' . $v['n_pessoa'] . ' 1 Bim ' . $v['bi1'] . ' turma ' . $v['turma_ben'];
                    }
                } else {
                    $erro_res[$v['cie_ben']][$v['id_pessoa']]['Nota'][] = 'Verificar: Nota do(a) aluno(a)' . $v['n_pessoa'] . ' 1 Bim ' . $v['bi1'] . ' turma ' . $v['turma_ben'];
                }
                if (!(is_nan($v['bi2']))) {
                    If ($v['bi2'] > 10) {
                        $erro_res[$v['cie_ben']][$v['id_pessoa']]['Nota'][] = 'Nota Maior que 10: Verificar: Nota do(a) aluno(a) ' . $v['n_pessoa'] . ' 2 Bim ' . $v['bi2'] . ' turma ' . $v['turma_ben'];
                    }
                } else {
                    $erro_res[$v['cie_ben']][$v['id_pessoa']]['Nota'][] = 'Verificar: Nota do(a) aluno(a) ' . $v['n_pessoa'] . ' 2 Bim ' . $v['bi2'] . ' turma ' . $v['turma_ben'];
                }
            }
        }

        if (!empty($erro_res)) {
            tool::alert("Verifique as pendências clicando no botão <Visualizar>");
            $aud = $erro_res;

            $sit = "UPDATE mrv_escolas SET status_escola = '" . 'Pendente' . "' WHERE fk_id_inst = '" . tool::id_inst() . "'";
            $query = $this->db->query($sit);
        } else {
            //tool::alert("Favor aguardar ...");
            tool::alert("Para prosseguir clique no botão <Calcular Média>");
            $aud = "OK";

            $sit = "UPDATE mrv_escolas SET status_escola = '" . 'Calcular' . "' WHERE fk_id_inst = '" . tool::id_inst() . "'";
            // $sit = "UPDATE mrv_escolas SET status_escola = '" . 'FinalizadoFinal' . "' WHERE fk_id_inst = '" . tool::id_inst() . "'";
            $query = $this->db->query($sit);
        }

        return $aud;
    }

    public function situacaoalunosfinal($sit) {

        if ($sit == 'Indeferida') {
            $sql = "SELECT * FROM mrv_beneficiado WHERE fk_id_pl = '87' and cie_ben = '" . tool::cie() . "' AND status_ben IN ('Indeferida', 'Indeferida(Doc)')"
                    . " ORDER BY turma_ben, num_chamada_ben";
        } else {
            $sql = "SELECT * FROM mrv_beneficiado WHERE fk_id_pl = '87' and cie_ben = '" . tool::cie() . "' AND status_ben = '" . $sit . "'"
                    . " ORDER BY turma_ben, num_chamada_ben";
        }

        $query = $this->db->query($sql);
        $dados = $query->fetchAll();

        return $dados;
    }

    public function pegaescola($id = NULL) {

        if (empty($id)) {
            $sql = "SELECT i.n_inst, e.cie_escola FROM instancia i"
                    . " JOIN mrv_escolas e ON e.fk_id_inst = i.id_inst"
                    . " WHERE e.status_escola = '" . 'FinalizadoFinal' . "'"
                    . " ORDER BY i.n_inst";
        } else {
            $sql = "SELECT i.n_inst, e.cie_escola FROM instancia i"
                    . " JOIN mrv_escolas e ON e.fk_id_inst = i.id_inst"
                    . " WHERE e.fk_id_inst IN($id)"
                    . " ORDER BY i.n_inst";
        }
        $query = $this->db->query($sql);
        $dados = $query->fetchAll();

        return $dados;
    }

    public function anoanterior() {

        $sql = "SELECT * FROM ge_turmas t"
                . " WHERE t.fk_id_inst = '" . tool::id_inst() . "' AND t.fk_id_ciclo = 9 AND t.fk_id_pl = 87";

        $query = $this->db->query($sql);
        $turma = $query->fetchAll();

        $option = [];
        if (!empty($turma)) {
            foreach ($turma as $v) {
                $option[$v['id_turma']] = $v['codigo'];
            }
        }
        return $option;
    }

    public function pdfSecretariaE($orientation = 'P', $mode = 'c', $format = 'A4', $default_font_size = 0, $default_font = '', $mgl = 15, $mgr = 15, $mgt = 35, $mgb = 20, $mgh = 9, $mgf = 9) {

        $header = '<table style="width: 100%; border: 1px solid">'
                . '<tr>'
                . '<td rowspan = "5">'
                . '<img style="width: 70px" src="' . HOME_URI . '/views/_images/brasao.jpg"/>'
                . '</td>'
                . '<td style="font-size: 18px">'. CLI_NOME .'</td>'
                . '<td rowspan = "5" style=" text-align: right">'
                . '<img style="width: 210px;" src="' . HOME_URI . '/views/_images/logo_relatorio.jpg"/>'
                . '</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 16px">SE - Secretaria de Educação</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 12px">'. CLI_END .'</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 12px">'. CLI_BAIRRO .' - '. CLI_CIDADE .' - '. CLI_UF .' CEP '. CLI_CEP .' Fone '. CLI_FONE .'</td>'
                . '</tr>'
                . '<tr>'
                . '<td style="font-size: 12px">'. CLI_URL .' - Email: '. CLI_MAIL .'</td>'
                . '</tr>'
                . '</table>';

        $body = ob_get_contents();
        ob_end_clean();
        include( ABSPATH . "/app/mpdf/mpdf.php");

        $mpdf = new mPDF($mode, $format, $default_font_size, $default_font, $mgl, $mgr, $mgt, $mgb, $mgh);

        $footer = "<div style=\"padding: 8px; background-color: #D4DF92;\" ><table width=\"1000\"><tr><td style=\" font-weight: bold;width: 200px\">SIEB</td><td style=\" text-align: center\">". ucfirst(CLI_CIDADE) .", " . date("d") . " de " . data::mes(date("m")) . " de " . date("Y") . "</td><td  style=\"width: 300px\" align=\"right\">{PAGENO}/{nb}</td></tr></table></div>";
        $mpdf->SetHTMLHeader($header);
        $mpdf->SetHTMLFooter($footer);
        $css = file_get_contents(ABSPATH . "/views/_css/style.css");
        $mpdf->WriteHTML($css, 1);
        $css1 = file_get_contents(ABSPATH . "/views/_css/bootstrap-theme.css");
        $mpdf->WriteHTML($css1, 1);
        $css2 = file_get_contents(ABSPATH . "/views/_css/bootstrap.css");
        $mpdf->WriteHTML($css2, 1);
        if ($orientation == 'L' || $orientation == 'l') {
            $mpdf->AddPage('L');
        }
        $mpdf->WriteHTML($body);

        $mpdf->Output();
        exit;
    }

    public function pegaalunocidade($idturma) {

        $sql = "SELECT t.id_turma, t.codigo, ta.situacao, p.id_pessoa, p.n_pessoa,"
                . " ta.chamada, p.dt_nasc, ta.id_turma_aluno, e.cidade FROM pessoa p"
                . " JOIN mrv_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " LEFT JOIN endereco e ON e.fk_id_pessoa = p.id_pessoa"
                . " WHERE t.fk_id_pl = 87 AND t.id_turma = '" . $idturma . "'"
                . " ORDER BY ta.chamada";

        $query = $this->db->query($sql);
        $dados = $query->fetchAll();

        return $dados;
    }

    public function pegapendencias() {

        $sql = "SELECT p.id_pessoa, p.n_pessoa, t.codigo, e.cidade FROM pessoa p"
                . " JOIN mrv_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " LEFT JOIN endereco e ON e.fk_id_pessoa = p.id_pessoa"
                . " LEFT JOIN mrv_beneficiado b ON b.id_pessoa = p.id_pessoa"
                . " AND b.id_pessoa = ta.fk_id_pessoa AND b.fk_id_turma = ta.fk_id_turma"
                . " WHERE t.fk_id_pl = 87 AND t.fk_id_ciclo =  '9'"
                . " AND ta.fk_id_inst = '" . tool::id_inst() . "'"
                . " AND ta.situacao = '" . 'Frequente' . "'"
                . " AND b.id_pessoa IS NULL AND e.cidade IN('".CLI_CIDADE."', '".ucfirst(CLI_CIDADE)."', '".strtolower(CLI_CIDADE)."')"
                . " ORDER BY t.codigo, p.n_pessoa";

        $query = $this->db->query($sql);
        $dados = $query->fetchAll();

        return $dados;
    }

    public function verificastatus() {
        //dupliquei
        $sql = "SELECT status_escola FROM mrv_escolas WHERE fk_id_inst = '" . tool::id_inst() . "'";

        $query = $this->db->query($sql);
        $dados = $query->fetch();

        return $dados['status_escola'];
    }

    public function categoria() {
        // criei função so para anotações
        // Categoria = 1 => Morador e Estuda os 4 anos
        // Categoria = 2 => Morador e Estuda os 4 anos mas não entrou na classificação da escola
        // Categoria = 3 => Morador menos de 4 anos
        // Categoria = 98 => Não Munícipe
        // Categoria = 99 => Munícipe mas não tem interesse
        // Catergoria = 97 => Indeferida(Doc) - Transferido
    }

    public function geratabelaalunoclasse($idinst) {

        $wsql = "SELECT * FROM mrv_turma_aluno mt"
                . " JOIN ge_turmas t ON t.id_turma = mt.fk_id_turma"
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                . " WHERE t.fk_id_inst = '" . $idinst . "' AND pl.at_pl = 1";

        $query = $this->db->query($wsql);
        $dados = $query->fetchAll();

        if (empty($dados)) {
            $sql = "INSERT INTO mrv_turma_aluno(id_turma_aluno,codigo_classe,"
                    . " fk_id_turma,periodo_letivo,fk_id_pessoa,fk_id_inst,chamada,situacao,"
                    . " dt_matricula,dt_matricula_fim,dt_transferencia,origem_escola,destino_escola,"
                    . " destino_escola_cidade,tp_destino,justificativa_transf,turma_status,situacao_final,conselho,gdae)"
                    . " SELECT ta.id_turma_aluno,ta.codigo_classe,ta.fk_id_turma,ta.periodo_letivo,"
                    . " ta.fk_id_pessoa,ta.fk_id_inst,ta.chamada,ta.situacao,ta.dt_matricula,ta.dt_matricula_fim,ta.dt_transferencia,"
                    . " ta.origem_escola,ta.destino_escola,ta.destino_escola_cidade,ta.tp_destino,"
                    . " ta.justificativa_transf,ta.turma_status,ta.situacao_final,ta.conselho,ta.gdae FROM ge_turma_aluno ta"
                    . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                    . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                    . " WHERE pl.at_pl = 1 AND t.fk_id_ciclo = 9 AND t.fk_id_inst = '" . $idinst . "'";

            $query = $this->db->query($sql);
        }
    }

    public function pegaturmanono($idinst) {

        $sql = "SELECT DISTINCT ta.fk_id_turma, t.codigo FROM mrv_turma_aluno ta"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                . " WHERE ta.fk_id_inst = '" . $idinst . "' AND pl.at_pl = 1"
                . " ORDER BY t.codigo";

        $query = $this->db->query($sql);
        $dados = $query->fetchAll();

        if (!empty($dados)) {
            foreach ($dados as $v) {
                $d[$v['fk_id_turma']] = $v['codigo'];
            }

            return $d;
        }
    }

    public function pegaemailgoogle($idpessoa) {

        $sql = "SELECT p.emailgoogle FROM pessoa p WHERE p.id_pessoa = '" . $idpessoa . "'";

        $query = $this->db->query($sql);
        $aluno = $query->fetch();

        return $aluno['emailgoogle'];
    }

    public function fichainscricao($t) {

        $sql = "SELECT p.id_pessoa, p.n_pessoa, p.ra, p.ra_dig, p.ra_uf, t.id_turma,"
                . " t.codigo, t.letra, ta.chamada, ta.situacao, dt_nasc, emailgoogle FROM pessoa p"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl"
                . " WHERE ta.situacao = 'Frequente' AND pl.at_pl = 1"
                . " AND ta.fk_id_turma IN($t)";

        $query = $this->db->query($sql);
        $ficha = $query->fetchAll();

        return $ficha;
    }

    public function verificapontomedia($idaluno) {

        $sql = "SELECT * FROM mrv_notas WHERE fk_id_pessoa = " . $idaluno;

        $query = $this->db->query($sql);
        $aluno = $query->fetchAll();

        foreach ($aluno as $v) {

            $wsql = "UPDATE mrv_notas SET nota = " . str_replace(',', '.', $v['nota'])
                    . " WHERE fk_id_disc = '" . $v['fk_id_disc'] . "' AND fk_id_pessoa = " . $v['fk_id_pessoa']
                    . " AND ano = " . $v['ano'];
            $query = $this->db->query($wsql);
        }

        $sql2 = "SELECT * FROM mrv_nono WHERE fk_id_pessoa = " . $idaluno;

        $query = $this->db->query($sql2);
        $alunobi = $query->fetchAll();

        foreach ($alunobi as $v) {

            $wsqlbi = "UPDATE mrv_nono SET bi1 = " . str_replace(',', '.', $v['bi1'])
                    . ", bi2 = " . str_replace(',', '.', $v['bi2']) . " WHERE fk_id_disc = '" . $v['fk_id_disc'] . "'"
                    . " AND fk_id_pessoa = " . $v['fk_id_pessoa'];

            $query = $this->db->query($wsqlbi);
        }
    }

    public function acertomediageral() {

        $sql = "SELECT id_pessoa FROM mrv_beneficiado"
                . " WHERE fk_id_pl = 87 AND status_ben IN('Deferida','Indeferida','Ag. Def.')";

        $query = $this->db->query($sql);
        $alunos = $query->fetchAll();

        foreach ($alunos as $v) {
            $a[] = $v['id_pessoa'];
        }

        $d = $this->calculamedia($a);

        tool::alert("OK");
    }

    public function alunoinscricao($idturma) {

        $sql = "SELECT id_pessoa, n_pessoa, status_ben, turma_ben, num_chamada_ben, ra_ben, dt_nasc, rg_aluno_ben, fk_id_turma FROM mrv_beneficiado"
                . " WHERE fk_id_turma = " . $idturma . " AND status_ben IN ('Deferida','Indeferida','Indeferida(Doc)') ORDER BY num_chamada_ben";

        $query = $this->db->query($sql);
        $al = $query->fetchAll();

        return $al;
    }

    public function pegastatus() {
        $sta = [
            'Frequente' => 'Frequente',
            'Munícipe' => 'Munícipe',
            'Não Munícipe' => 'Não Munícipe',
            'Ag. Inscrição' => 'Ag. Inscrição',
            'Ag. Def.' => 'Ag. Def.',
            'Deferida' => 'Deferida',
            'Indeferida' => 'Indeferida',
            'NI' => 'NI',
        ];

        return $sta;
    }

    public function pegadadosrelatorio() {

        $st = $this->pegastatus();

        $sql = "SELECT t.fk_id_inst FROM ge_turmas t WHERE t.fk_id_pl = 87 AND t.fk_id_ciclo = 9";

        $query = $this->db->query($sql);
        $esc = $query->fetchAll();

        //cria as variaveis
        foreach ($esc as $v) {
            foreach ($st as $w) {
                $d[$v['fk_id_inst']][$w] = 0;
            }
        }

        $sql = "SELECT ta.fk_id_inst, COUNT(ta.situacao) AS Total FROM mrv_turma_aluno ta"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " GROUP BY ta.fk_id_inst, ta.situacao, t.fk_id_ciclo, t.fk_id_pl"
                . " HAVING ta.situacao = 'Frequente' AND t.fk_id_ciclo = 9 AND t.fk_id_pl = 8787";

        $query = $this->db->query($sql);
        $freq = $query->fetchAll();

        foreach ($freq as $v) {
            $d[$v['fk_id_inst']]['Frequente'] = $v['Total'];
        }

        $sql = "SELECT ta.fk_id_inst, e.cidade FROM mrv_turma_aluno ta"
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma"
                . " LEFT JOIN endereco e ON e.fk_id_pessoa = ta.fk_id_pessoa"
                . " WHERE ta.situacao = 'Frequente' AND t.fk_id_ciclo = 9 AND t.fk_id_pl = 87";

        $query = $this->db->query($sql);
        $cidade = $query->fetchAll();

        foreach ($cidade as $v) {
            if (strcasecmp($v['cidade'], CLI_CIDADE) == 0) {
                $d[$v['fk_id_inst']]['Munícipe'] = $d[$v['fk_id_inst']]['Munícipe'] + 1;
            } else {
                $d[$v['fk_id_inst']]['Não Munícipe'] = $d[$v['fk_id_inst']]['Não Munícipe'] + 1;
            }
        }
        $sql = "SELECT ta.fk_id_inst, b.status_ben, COUNT(b.status_ben) AS Total FROM mrv_turma_aluno ta"
                . " JOIN mrv_beneficiado b ON b.id_pessoa = ta.fk_id_pessoa"
                . " GROUP BY ta.fk_id_inst, b.status_ben, b.fk_id_pl, ta.situacao"
                . " HAVING b.fk_id_pl = 87 AND ta.situacao = '" . 'Frequente' . "'";

        $query = $this->db->query($sql);
        $status = $query->fetchAll();

        foreach ($status as $v) {
            if ($v['status_ben'] != 'Não Munícipe') {
                $d[$v['fk_id_inst']][$v['status_ben']] = $v['Total'];
            }
        }

        $sql = "SELECT DISTINCT t.fk_id_inst FROM ge_turmas t WHERE t.fk_id_pl = 87 AND t.fk_id_ciclo = 9";

        $query = $this->db->query($sql);
        $ag = $query->fetchAll();

        unset($st['Munícipe'], $st['Frequente'], $st['Ag. Inscrição']);

        foreach ($ag as $v) {
            $d[$v['fk_id_inst']]['Ag. Inscrição'] = $d[$v['fk_id_inst']]['Frequente'];
            foreach ($st as $w) {
                $d[$v['fk_id_inst']]['Ag. Inscrição'] = ($d[$v['fk_id_inst']]['Ag. Inscrição']) - ($d[$v['fk_id_inst']][$w]);
            }
        }
        return $d;
    }

    public function pegaescolarel() {

        $sql = "SELECT i.id_inst, i.n_inst FROM mrv_escolas e"
                . " JOIN instancia i ON i.id_inst = e.fk_id_inst"
                . " ORDER BY i.n_inst";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $es[$v['id_inst']] = $v['n_inst'];
        }

        return $es;
    }

    public function verificaalunocadastro() {
        $id_inst = tool::id_inst();

        $sql = "SELECT ta.fk_id_inst, ta.situacao, ta.fk_id_pessoa FROM mrv_turma_aluno ta"
                . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma"
                . " LEFT JOIN endereco e ON e.fk_id_pessoa = ta.fk_id_pessoa"
                . " WHERE t.fk_id_ciclo = '" . '9' . "' AND t.fk_id_pl = '" . '87' . "' AND  ta.situacao = '" . 'Frequente' . "'"
                . " AND ta.fk_id_inst = '" . $id_inst . "' AND cidade IN('".ucfirst(CLI_CIDADE)."', '".CLI_CIDADE."', '".strtolower(CLI_CIDADE)."')";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $aluno[] = $v['fk_id_pessoa'];
        }

        $sel = "SELECT b.status_ben, e.fk_id_inst, e.vagas_itb, b.id_pessoa, b.n_pessoa FROM mrv_beneficiado b"
                . " JOIN mrv_escolas e ON e.cie_escola = b.cie_ben"
                . " WHERE b.fk_id_pl = '" . '87' . "' AND e.fk_id_inst = '" . $id_inst . "'";

        $query = pdoSis::getInstance()->query($sel);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $w) {

            $aluno2[] = $w['id_pessoa'];

            if (in_array($w['id_pessoa'], $aluno)) {
                
            } else {
                $alunoErro = @$AlunoErro . " - " . $w['id_pessoa'];
            }
        }

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            if (in_array(($v['fk_id_pessoa']), $aluno2)) {
                
            } else {
                $alunoErro = @$AlunoErro . " - " . $v['fk_id_pessoa'];
            }
        }

        return @$alunoErro;
    }

    public function peganotasNovo($idpessoa) {

        $disci = [6 => 6, 9 => 9, 10 => 10, 11 => 11, 12 => 12, 13 => 13, 14 => 14, 15 => 15];
        for ($x = 6; $x <= 8; $x++) {
            foreach ($disci as $w) {
                $geratabela = "INSERT INTO mrv_notas(fk_id_pessoa, ano, fk_id_disc, nota)"
                        . " VALUES('" . $idpessoa . "', '" . $x . "', '" . $w . "', '0')";

                $query = $this->db->query($geratabela);
            }
        }

        $disciplina = '6,9,10,11,12,13,14,15,30';

        $sql = "SELECT * FROM mrv_notas mn WHERE mn.fk_id_pessoa = " . $idpessoa;

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        for ($x = 6; $x <= 8; $x++) {
            $sql2 = "SELECT hn.fk_id_disc, hn.fk_id_pessoa, hn.n_" . $x . " AS nota"
                    . " FROM historico_notas  hn WHERE hn.ativo = 1"
                    . " AND hn.fk_id_pessoa = " . $idpessoa
                    . " AND hn.fk_id_disc IN($disciplina) ORDER BY hn.fk_id_disc";

            $query = pdoSis::getInstance()->query($sql2);
            $notas = $query->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($notas)) {
                foreach ($notas as $v) {
                    // verificar ingles 15
                    if ($v['fk_id_disc'] == 30) {
                        if (!empty($v['nota'])) {
                            $insert = "UPDATE mrv_notas SET nota = '" . $v['nota'] . "'"
                                    . " WHERE ano = '" . $x . "'"
                                    . " AND fk_id_disc = '15' AND fk_id_pessoa = '" . $v['fk_id_pessoa'] . "'";
                        }
                    } else {
                        $insert = "UPDATE mrv_notas SET nota = '" . $v['nota'] . "'"
                                . " WHERE ano = '" . $x . "'"
                                . " AND fk_id_disc = '" . $v['fk_id_disc'] . "'"
                                . " AND fk_id_pessoa = '" . $v['fk_id_pessoa'] . "'";
                    }

                    $query = $this->db->query($insert);
                }
            }
        }
    }

    public function classificacaofinalpdf($criterio) {
        
        $sql = "SELECT * FROM mrv_beneficiado"
                . " WHERE status_ben LIKE 'Deferida'"
                . " AND classificacao_geral BETWEEN " . $criterio . " AND fk_id_pl = 87"
                . " ORDER BY classificacao_geral";

        $query = pdoSis::getInstance()->query($sql);
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        
        return $res;
    }

}
