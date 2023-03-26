<?php
ini_set('memory_limit', '5000M');
ini_set('max_execution_time', 3000000);
ini_set('max_input_time', 6000000);

/**
  if($this->db->tokenCheck('table')){

  }
 * 
 */
class gdaeModel {

    public $db;
    public $_gt_gdae;

    public function __construct($db = false, $controller = null) {

        $this->db = $db;
        $this->db = new DB();
        $this->_gt_gdae = new gt_gdae;
        $this->controller = $controller;
        $limit = @$_REQUEST['limit'];
        $id_inst = @$_REQUEST['id_inst'];
        $ano = @$_REQUEST['ano'];
        $novo = @$_REQUEST['novo'];
        $semestre = @$_REQUEST['semestre'];
        $id_pl = @$_REQUEST['id_pl'];
        if (!empty($_REQUEST['classesGdae'])) {
            $this->ImportaClasses($ano, $id_inst);
        } elseif (!empty($_REQUEST['matriculasGdae'])) {
            $this->matriculas($ano, $id_inst);
        } elseif (!empty($_REQUEST['atualizarAlunos'])) {
            $this->atualizarAlunos($ano, $id_inst);
        } elseif (!empty($_REQUEST['atualizarMatriculas'])) {
            $this->atualizarMatriculas($ano, $semestre, $id_inst, $id_pl);
        } elseif (!empty($_REQUEST['atualizarPessoa'])) {
            $this->atualizarPessoa($ano, $semestre, $novo);
        } elseif (!empty($_REQUEST['atulizarTabelaPessoa'])) {
            $this->atualizarTabelaPessoa($ano);
        } elseif (!empty($_REQUEST['matriculasErro'])) {
            $this->matriculasErro(1);
        } elseif (!empty($_REQUEST['atulizarTabelaHist'])) {
            $this->atulizarTabelaHist($ano);
        }
    }

    public function importaClasses($AnoLetivo = NULL, $id_inst = NULL) {
        $this->log('Importar classe', 'início');

        if (empty($AnoLetivo)) {
            $AnoLetivo = date("Y");
        }

        $id_pl = gtMain::semestrePeriodos($AnoLetivo);

        if (empty($id_inst)) {
            $CodEscola = NULL;
        } else {
            $esc = new escola($id_inst);
            $CodEscola = $esc->_cie;
        }
        if (!empty($CodEscola)) {
            $where = ['cie_escola' => $CodEscola];
        } else {
            $where = NULL;
        }
        $escolas = sql::get('ge_escolas', 'cie_escola as cie, fk_id_inst as id_inst', $where);

        foreach ($escolas as $a) {
            $sem = [0 => NULL, 1 => 1, 2 => 2];
            for ($Semestre = 0; $Semestre <= 2; $Semestre++) {

                $c = $this->_gt_gdae->ConsultaClasseAlunoPorEscola($AnoLetivo, $a['cie'], $sem[$Semestre], NULL, NULL, NULL, NULL);
                echo '<br />' . $AnoLetivo . ', ' . $a['cie'] . ',' . $sem[$Semestre];
                if (!empty($c)) {
                    if (is_string($c)) {
                        $erro['servico'] = 'ConsultaClasseAlunoPorEscola';
                        $erro['cie'] = $a['cie'];
                        $erro['msg'] = $c;
                        $erro['td_log'] = date("Y-m-d");
                        $this->db->replace('gdae_log_erro', $erro);
                    } else {

                        $cc_ = (array) $c['Mensagens']->MsgConsultaClasseAlunoPorEscola;
                        if (empty($cc_[0])) {
                            $cc[] = $cc_;
                        } else {
                            $cc = $cc_;
                        }
                        foreach ($cc as $v) {
                            $v = (array) $v;
                            $v['data'] = date("Y-m-d");
                            $v['ano'] = $AnoLetivo;
                            $this->db->replace('gdae_classe', $v);
                        }

                        /** sicronizar com ge_turma
                         * 
                         */
                        $this->sincronizarClasse($a['id_inst'], $id_pl[$Semestre], $a['cie'], $AnoLetivo);
                    }
                }
            }
        }
        tool::alert("Concluido");
        $this->log('importar classe', 'fim');
    }

    public function matriculas($AnoLetivo = NULL, $id_inst = NULL) {
        $sql = "TRUNCATE gdae_aluno_erro";
        $query = pdoSis::getInstance()->query($sql);
        $this->log('tabela gdae_aluno', 'Início');

        if (empty($AnoLetivo)) {
            $AnoLetivo = date("Y");
        }
        if (empty($id_inst)) {
            $where = ['ano' => $AnoLetivo];
        } else {
            $esc = new escola($id_inst);
            $where = ['outCodEscola' => $esc->_cie, 'ano' => $AnoLetivo];
        }
        $turmas = sql::get('gdae_classe', 'outNrClasse', $where);

        foreach ($turmas as $v) {

            @$alunos = $this->_gt_gdae->ConsultaFormacaoClasse($v['outNrClasse']);
            if (is_string(@$alunos) || empty($alunos)) {
                if (is_array(@$alunos)) {
                    $alunos = NULL;
                }
                $sql = "REPLACE INTO `gdae_aluno_erro` (`classe`, `msg`, `data`) VALUES ('" . $v['outNrClasse'] . "', '" . @@$alunos . "', CURRENT_TIMESTAMP); ";
                $query = pdoSis::getInstance()->query($sql);
            } else {
                if (!empty($alunos['Mensagens'])) {

                    @$alunos = @$alunos['Mensagens']->ConsultaClasse;
                    unset($numero);
                    foreach (@$alunos as $a) {
                        $a = (array) $a;
                        $this->db->replace('gdae_aluno', $a);
                        $numero[$a['numero']] = $a['numero'];
                    }
                    if (is_array(@$numero) && !empty($v['outNrClasse']) && !empty(implode(',', $numero)) && !empty($a['numero'])) {
                        echo '<br />' . $sql = "DELETE FROM `gdae_aluno` "
                        . " WHERE `outNumClasse` =  " . $v['outNrClasse']
                        . " AND `numero` NOT IN (" . implode(',', $numero) . ") ";
                        $query = pdoSis::getInstance()->query($sql);
                    }
                } else {
                    echo '<br /> Eroo Classe' . $v['outNrClasse'];
                }
            }
        }
        $this->log('tabela gdae_aluno', 'Fim');

        $count = 1;
        $this->matriculasErro($count);
    }

    public function atualizarMatriculas($AnoLetivo = NULL, $id_inst = NULL, $id_pl = NULL) {
        if (empty($AnoLetivo)) {
            $AnoLetivo = date("Y");
        }
        if (empty($id_inst)) {
            $where = ['outAno' => $AnoLetivo];
        } else {
            $esc = new escola($id_inst);
            $where = ['outCodEscola' => $esc->_cie, 'outAnoLetivo' => $AnoLetivo];
        }

        $alu = sql::get('gdae_aluno', ' RA, digitoRA, UF, outNumClasse ', $where);

        if (!empty($alu)) {
            foreach ($alu as $v) {
                $this->insertMatricula($v);
            }
        }
    }

    public function insertMatricula($v) {
        @$a = $this->_gt_gdae->ConsultarMatriculaClasseRA($v['RA'], $v['outNumClasse'], $v['digitoRA'], $v['UF'], 'TR');

        if (is_string(@$a) || empty($a)) {
            if (is_array(@$a)) {
                $a = NULL;
            }
            $sql = "REPLACE INTO gdae_matricula_erro (`ra`, `ra_dig`, `ra_uf`, `classe`, `msg`, `data`) VALUES ("
                    . "'" . $v['RA'] . "', "
                    . "'" . $v['digitoRA'] . "', "
                    . "'" . strtoupper($v['UF']) . "', "
                    . "'" . $v['outNumClasse'] . "',"
                    . " '" . @$a . "',"
                    . " CURRENT_TIMESTAMP);";
            $query = pdoSis::getInstance()->query($sql);
        } else {

            @$aluno = (array) @$a['Mensagens']->ConsultarMatrRAClasse;

            unset($ins);
            $ins['outAnoLetivo'] = @$aluno['outAnoLetivo'];
            $ins['outData'] = data::converteUS(@$aluno['outData']);
            $ins['outDataFim'] = data::converteUS(@$aluno['outDataFim']);
            $ins['outDataInicio'] = data::converte(@$aluno['outDataInicio']);
            $ins['outDataNascimento'] = data::converte(@$aluno['outDataNascimento']);
            $ins['outDescTipoEnsino'] = @$aluno['outDescTipoEnsino'];
            $ins['outDescricaoTurno'] = @$aluno['outDescricaoTurno'];
            $ins['outDigitoRA'] = @@$aluno['outDigitoRA'];
            $ins['outEscola'] = @$aluno['outEscola'];
            $ins['outHoraFinal'] = @$aluno['outHoraFinal'];
            $ins['outHoraInicial'] = @$aluno['outHoraInicial'];
            $ins['outLitTransp'] = @$aluno['outLitTransp'];
            $ins['outNomeAluno'] = @$aluno['outNomeAluno'];
            $ins['outNomeMae'] = @$aluno['outNomeMae'];
            $ins['outNomePai'] = @$aluno['outNomePai'];
            $ins['outNumAluno'] = @$aluno['outNumAluno'];
            $ins['outNumClasse'] = @$aluno['outNumClasse'];
            $ins['outRA'] = @$aluno['outRA'];
            $ins['outSerie'] = @$aluno['outSerie'];
            $ins['outTipoEnsino'] = @$aluno['outTipoEnsino'];
            $ins['outTurma'] = @$aluno['outTurma'];
            $ins['outTurno'] = @$aluno['outTurno'];
            $ins['outUF'] = @$aluno['outUF'];

            $this->db->replace('gdae_matricula', $ins);
        }
    }

    public function sincronizarClasse($id_inst, $id_pl, $cie, $AnoLetivo) {
        return;
        $pl = sql::get('ge_periodo_letivo', '*', ['id_pl' => $id_pl], 'fetch');
        $n_pl = $pl['n_pl'];
        $semestre = $pl['semestre'];
        $sql = "update ge_turmas set gdae_set = 1 "
                . " where fk_id_inst = " . $id_inst
                . " and fk_id_pl in ('$id_pl') ";
        $query = pdoSis::getInstance()->query($sql);

        $turno = gt_gdaeSet::turno(NULL, 'sg_turno');

        $idTurma = gt_gdaeSet::turmasCicloLetra($id_inst, $id_pl);

        $cc = sql::get('gdae_classe', '*', ['outCodEscola' => $cie, 'ano' => $AnoLetivo, 'outSemestre' => $semestre]);
        /**
         *  Não temos o curso EEE, nº 33 no SED 
         * Altera EEE (33) para Fundamental (14)
         */
        foreach ($cc as $v) {
            if ($v['outTipoEnsino'] == 33) {
                $v['outTipoEnsino'] = 14;
            }
            $sql = " select * from ge_ciclos ci "
                    . " join ge_cursos c on c.id_curso = ci.fk_id_curso "
                    . " where c.TipoEnsino = " . $v['outTipoEnsino']
                    . " and SerieAno = " . $v['outSerieAno'];
            $query = pdoSis::getInstance()->query($sql);
            $ciclo = $query->fetch(PDO::FETCH_ASSOC);

            $id_turma = @$idTurma[$ciclo['id_ciclo']][strtoupper(trim($v['outTurma']))];
            if (!empty($id_turma)) {
                $insert['id_turma'] = $id_turma;
            } else {
                $insert['id_turma'] = NULL;
            }

            $insert['prodesp'] = $v['outNrClasse'];
            $insert['n_turma'] = $ciclo['n_ciclo'] . ' ' . strtoupper(trim($v['outTurma']));
            $insert['fk_id_inst'] = $id_inst;
            $insert['fk_id_ciclo'] = $ciclo['id_ciclo'];
            $insert['fk_id_grade'] = $ciclo['fk_id_grade'];
            @$insert['codigo'] = $ciclo['sg_curso'] . $turno[trim($v['outTurno'])] . $ciclo['sg_ciclo'] . strtoupper(trim($v['outTurma']));
            @$insert['periodo'] = $turno[trim($v['outTurno'])];

            $insert['periodo_letivo'] = $n_pl;
            $insert['fk_id_pl'] = $id_pl;
            $insert['letra'] = strtoupper(trim($v['outTurma']));
            $insert['gdae_set'] = '0';

            $this->db->ireplace('ge_turmas', $insert, 1);
        }
        $sql = "SET foreign_key_checks = 0; ";
        $query = pdoSis::getInstance()->query($sql);
        $sql = "DELETE FROM `ge_turmas` "
                . " WHERE `fk_id_inst` = " . $id_inst
                . " and fk_id_pl in ( " . $id_pl . ")"
                . " and gdae_set = 1 ";
        //$query = pdoSis::getInstance()->query($sql);
    }

    public function listTurmaGdae($id_inst, $ano) {

        $id_pl = gtMain::periodosAno($ano);

        $t = gtTurmas::turmas($id_inst, $id_pl);

        if (!empty($t)) {
            foreach ($t as $k => $v) {
                $vazia = sql::get('ge_turma_aluno', 'id_turma_aluno', ['fk_id_turma' => $v['id_turma']], 'fetch');
                if (!empty($vazia)) {
                    $t[$k]['vazio'] = '<img src="' . HOME_URI . '/views/_images/aluno.png"/>';
                }
                $t[$k]['id_pl'] = $id_pl;
                $t[$k]['ac'] = formulario::submit('Acessar', NULL, ['id_turma' => $v['id_turma'], 'id_pl' => $id_pl], HOME_URI . '/gt/gerirclasse');
                $t[$k]['edit'] = formulario::submit('Editar', NULL, ['id_turma' => $v['id_turma'], 'modal' => 1, 'id_pl' => $id_pl]);
            }
            if (!empty($t)) {
                $form['array'] = $t;
                $form['fields'] = [
                    'ID' => 'id_turma',
                    'Prodesp' => 'prodesp',
                    'Curso' => 'n_curso',
                    'Código' => 'codigo',
                    'Turma' => 'n_turma',
                    '||1' => 'vazio',
                ];

                tool::relatSimples($form);
            }
        } else {
            ?>
            <div class="alert alert-warning" style="text-align: center; font-size: 18px">
                Não há Classes neste período
            </div>
            <?php
        }
    }

    public function matriculasErro($count) {
        echo 'Início' . date('h:i:s') . '<br />';

        $sql = "SELECT count(`classe`) as contador FROM `gdae_aluno_erro` WHERE 1";
        $query = pdoSis::getInstance()->query($sql);
        echo 'Classes não baixadas: ' . $array = $query->fetch(PDO::FETCH_ASSOC)['contador'] . '<br />';

        $turmas = sql::get('gdae_aluno_erro');

        if (!empty($turmas)) {
            foreach ($turmas as $v) {
                @$alunos = $this->_gt_gdae->ConsultaFormacaoClasse($v['classe']);

                if (!is_string(@$alunos)) {
                    if (!empty($alunos['Mensagens'])) {
                        @$alunos = @$alunos['Mensagens']->ConsultaClasse;
                        foreach (@$alunos as $a) {
                            $a = (array) $a;
                            $this->db->replace('gdae_aluno', $a);
                        }
                    } else {
                        tool::alert($v['classe']);
                    }
                    $sql = "DELETE FROM `gdae_aluno_erro` WHERE `gdae_aluno_erro`.`classe` = " . $v['classe'];
                    $query = pdoSis::getInstance()->query($sql);
                }
            }
            echo 'fim:' . date('h:i:s') . '<br />';
            $turmas = sql::get('gdae_aluno_erro');
            if ($count++ < 5) {
                $this->matriculasErro($count);
            } else {
                $sql = "INSERT INTO `gdae_log_erro` (`id_log`, `servico`, `msg`, `td_log`) VALUES ("
                        . "NULL,"
                        . " 'matriculasErro',"
                        . " 'Não finalizou a tabela gdae_log_erro',"
                        . " CURRENT_TIMESTAMP);";
                $query = pdoSis::getInstance()->query($sql);
            }
        }
    }

    /**
     * 
     * @param type $classeProdesp  array com alunos do gdae
     * @return type alunos do gdae que tb estam na base de dados do sistema
     */
    public function alunosComIdPessoa($classeProdesp) {
        foreach ($classeProdesp as $v) {
            $v = (array) $v;
            $ras[] = gt_gdaeSet::raNormatiza($v['RA']);
            $ras1[] = $v['RA'];
            $rasId[] = trim($v['digitoRA']);
            $rasUF[] = trim($v['UF']);
        }
        //alunos com RA e IDRA
        $sql = "select id_pessoa, n_pessoa, dt_nasc, sexo, ra, ra_dig, ra_uf, pai, mae from pessoa "
                . " where "
                . "("
                . " ra in ('" . implode("','", $ras) . "') "
                . " or ra in ('" . implode("','", $ras1) . "') "
                . ") "
                . " and "
                . "(ra_dig in ('" . implode("','", $rasId) . "') "
                . " or ra_dig "
                . ") "
                . " and ra_uf in ('" . implode("','", $rasUF) . "')  ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $siebComRaId[gt_gdaeSet::raNormatiza($v['ra']) . '-' . trim($v['ra_dig']) . '-' . strtoupper($v['ra_uf'])] = $v;
        }

        return @$siebComRaId;
    }

    public function atualizarPessoa($AnoLetivo = NULL, $id_inst = NULL, $novo = NULL) {
        if (!empty($novo)) {
            $novo = " AND RA not in (SELECT outRA FROM `gdae_pessoa` ORDER BY `outRA` ASC) ";
        } else {
            $novo = NULL;
        }

        if (empty($AnoLetivo)) {
            $AnoLetivo = date("Y");
        }
        if (empty($id_inst)) {
            echo '<br />' . $sql = "SELECT distinct outNumClasse FROM `gdae_aluno` "
            . " WHERE `outAno` LIKE '$AnoLetivo' ";
        } else {
            echo '<br />' . $sql = "SELECT distinct outNumClasse FROM `gdae_aluno` "
            . " WHERE `outAno` LIKE '$AnoLetivo' "
            . " AND outCodEscola = '" . $esc->_cie . "' ";
            $esc = new escola($id_inst);
        }
        $query = pdoSis::getInstance()->query($sql);
        $turmas = $query->fetchAll(PDO::FETCH_ASSOC);
        $turmas = array_column($turmas, 'outNumClasse');

        foreach ($turmas as $t) {

            $fields = 'RA, digitoRA, UF, outNumClasse, '
                    . 'numero, nomeAluno, outAno, outCodEscola, '
                    . 'outErro, outSerie, outTipoEnsino, '
                    . 'outTurma, outTurno, status, '
                    . 'seriemulti, tipoensinomulti, data';
            $sql = "SELECT $fields FROM `gdae_aluno` "
                    . " WHERE `outNumClasse` LIKE '$t' "
                    . $novo
                    . " ORDER BY `gdae_aluno`.`numero` ASC";
            $query = pdoSis::getInstance()->query($sql);
            $classeProdesp = $query->fetchAll(PDO::FETCH_ASSOC);
            if (is_array($classeProdesp)) {
                foreach ($classeProdesp as $v) {
                    $p = $this->incluirPessoa($v['RA'], $v['digitoRA'], $v['UF'], $AnoLetivo);
                }
            }
        }
    }

//sincroniza gdae_aluno com ge_turma_aluno
    public function atualizarAlunos($AnoLetivo = NULL, $id_inst = NULL) {
        $conta = 1;
        $this->log('Atualização ge_turma_aluno', 'início');

        if (empty($AnoLetivo)) {
            $AnoLetivo = date("Y");
        }
        if (empty($id_inst)) {
            $where = ['outAno' => $AnoLetivo];
        } else {
            $esc = new escola($id_inst);
            $where = ['outCodEscola' => $esc->_cie, 'outAno' => $AnoLetivo];
        }

        $turmas = sql::get('gdae_aluno', 'distinct outNumClasse', $where);
        $turmas = array_column($turmas, 'outNumClasse');

        foreach ($turmas as $t) {
            $turmaDados = sql::get('ge_turmas', '*', ['prodesp' => $t], 'fetch');
            if (!empty($turmaDados)) {
                $where1['outNumClasse'] = $t;
                $where1['>'] = 'numero';
                $fields = 'RA, digitoRA, UF, outNumClasse, '
                        . 'numero, nomeAluno, outAno, outCodEscola, '
                        . 'outErro, outSerie, outTipoEnsino, '
                        . 'outTurma, outTurno, status, '
                        . 'seriemulti, tipoensinomulti, data';
                $classeProdesp = sql::get('gdae_aluno', $fields, $where1);
                if (is_array($classeProdesp)) {

                    //confiro os alunos que já estão na turma
                    $siebComRaId = $this->alunosComIdPessoa($classeProdesp);
                    unset($numero);
                    foreach ($classeProdesp as $v) {

                        $alunoSieb = @$siebComRaId[gt_gdaeSet::raNormatiza($v['RA']) . '-' . trim($v['digitoRA']) . '-' . trim($v['UF'])];
                        $id_pessoa = NULL;
                        $v = (array) $v;
                        //se a pessoa não exiete

                        if (empty($alunoSieb)) {
                            $id_pessoa = $this->incluirAlerarAlunoRA($v);
                        } else {
                            $id_pessoa = $alunoSieb['id_pessoa'];
                        }
                        if (!empty($alunoSieb) or!empty($id_pessoa)) {
                            $this->alunoClasse($v, $turmaDados, $id_pessoa);
                        }

                        if (@$conta % 1000 == 0) {
                            $this->log('Lançamentos', $conta);
                        }
                        $conta++;
                        $numero[$v['numero']] = $v['numero'];
                    }
                    if (is_array(@$numero) && !empty($turmaDados['id_turma'])) {
                        $sql = "DELETE FROM `ge_turma_aluno` "
                                . " WHERE `fk_id_turma` =  " . $turmaDados['id_turma']
                                . " AND `chamada` NOT IN (" . implode(',', $numero) . ") ";
                        $query = pdoSis::getInstance()->query($sql);
                    }
                }
            } else {
                $this->log('Turma não encontrada no Sieb', 'prodesp: ' . $t);
            }
            $sql = "update ge_turmas set dt_gdae = '" . date("Y-m-d H:i:s") . "' "
                    . " where prodesp = $t";
            $query = pdoSis::getInstance()->query($sql);
        }
        $this->log('Atualização ge_turma_aluno', 'fimal');
    }

    public function pessoaInsertTbGdaeAluno($dados) {
        $ins['n_pessoa'] = $dados['nomeAluno'];
        $ins['ra'] = gt_gdaeSet::raNormatiza($dados['RA']);
        $ins['ra_dig'] = $dados['digitoRA'];
        $ins['ra_uf'] = strtoupper($dados['UF']);

        $id_pessoa = $this->db->insert('pessoa', $ins);

        return $id_pessoa;
    }

    public function pessoaInsertTbGdaePessoa($dados, $id_pessoa = NULL) {

        $ins['n_pessoa'] = $dados['outNomeAluno'];
        $ins['n_social'] = $dados['outNomeSocial'];
        $ins['dt_nasc'] = data::converteUS($dados['outDataNascimento']);
        $ins['ativo'] = '1';
        $ins['cpf'] = $dados['outCPF'];
        $ins['sexo'] = substr($dados['outSexo'], 0, 1);
        $ins['ra'] = gt_gdaeSet::raNormatiza($dados['outRA']);
        $ins['ra_dig'] = trim($dados['outDigitoRA']);
        $ins['ra_uf'] = strtoupper($dados['outUF']);
        $ins['rg'] = $dados['outNumRG'];
        $ins['rg_dig'] = $dados['outDigitoRG'];
        $ins['rg_uf'] = $dados['outUFRG'];
        $ins['dt_rg'] = data::converteUS(@$dados['outDataEmissaoRG']);
        ;
        if (!empty($dados['outCertidaoResp'])) {
            $cert = (array) unserialize($dados['outCertidaoResp']);
            if (!empty($cert['outFolhaRegNum'])) {
                $ins['certidao'] = @$dados['outNumRegNasc'] . ' - ' . @$cert['outNumLivroReg'] . ' - ' . @$cert['outFolhaRegNum'];
            } elseif (!empty($cert['outCertMatr01'])) {
                $ins['novacert_cartorio'] = @$cert['outCertMatr01'];
                $ins['novacert_acervo'] = @$cert['outCertMatr02'];
                $ins['novacert_regcivil'] = @$cert['outCertMatr03'];
                $ins['novacert_ano'] = @$cert['outCertMatr04'];
                $ins['novacert_tipolivro'] = @$cert['outCertMatr05'];
                $ins['novacert_numlivro'] = @$cert['outCertMatr06'];
                $ins['novacert_folha'] = @$cert['outCertMatr07'];
                $ins['novacert_termo'] = @$cert['outCertMatr08'];
                $ins['novacert_controle'] = @$cert['outCertMatr09'];
            }
        }
        $ins['pai'] = $dados['outNomePai'];
        $ins['mae'] = $dados['outNomeMae'];
        $ins['nacionalidade'] = $dados['outNomePaisOrigem'];
        $ins['uf_nasc'] = $dados['outUFNascimento'];
        $ins['cidade_nasc'] = $dados['outDescMunNasc'];
        // $ins['deficiencia'] = empty($dados['outDeficiencias']) ? NULL : '1';
        $ins['cor_pele'] = $dados['outCorRaca'];
        if (!empty($dados['outFoneCel'])) {
            $ins['tel1'] = $dados['outFoneCel'];
            $ins['ddd1'] = $dados['outDDDCel'];
        }
        if (!empty($dados['outFoneRecado'])) {
            $ins['tel2'] = $dados['outFoneRecado'];
            $ins['ddd2'] = $dados['outDDD'];
        }
        if (!empty($dados['outFoneResidencial'])) {
            $ins['tel3'] = $dados['outFoneResidencial'];
            $ins['ddd3'] = $dados['outDDD'];
        }
        $ins['nis'] = $dados['outNumNis'];
        $ins['dt_gdae'] = date("Y-m-d");
        $ins['id_pessoa'] = @$id_pessoa;

        $pessoa = $this->db->ireplace('pessoa', $ins, 1);

        $this->pessoaInsertTbGdaePessoaEnd($dados, @$id_pessoa);

        return $id_pessoa;
    }

    public function pessoaInsertTbGdaePessoaEnd($dados, $id_pessoa = NULL) {

        if (empty($id_pessoa)) {
            $id_pessoa = sql::get('pessoa', 'id_pessoa', ['ra' => gt_gdaeSet::raNormatiza($dados['outRA']), 'ra_dig' => trim($dados['outDigitoRA']), 'ra_uf' => strtoupper($dados['outUF'])], 'fetch')['id_pessoa'];
        }

        $endereco = sql::get('endereco', ' logradouro, id_end ', ['fk_id_pessoa' => $id_pessoa, 'fk_id_tp' => 1], 'fetch');


        if (empty($endereco['logradouro'])) {
            $end['logradouro'] = @$dados['outLogradouro'];
            $end['num'] = @$dados['outNumero'];
        }

        $end['bairro'] = @$dados['outBairro'];
        $end['cep'] = @$dados['outCEP'];
        $end['cidade'] = @$dados['outCidade'];
        $end['logradouro_gdae'] = @$dados['outLogradouro'];
        $end['uf'] = @$dados['outUF'];
        $end['latitude'] = @$dados['outLatitude'];
        $end['longitude'] = @$dados['outLongitude'];
        $end['num_gdae'] = @$dados['outNumero'];
        $end['complemento'] = @$dados['outComplemento'];
        $end['id_end'] = @$endereco['id_end'];
        $end['fk_id_tp'] = 1;
        $end['fk_id_pessoa'] = $id_pessoa;

        $this->db->ireplace('endereco', $end, 1);
    }

    /**
     * se o RA não for encontrado, busca um aluno e faz update, se não achar o aluno, faz um insert
     */
    public function incluirAlerarAlunoRA($v) {

        $where2 = [
            'outRA' => gt_gdaeSet::raNormatiza($v['RA']),
            'outdigitoRA' => trim($v['digitoRA']),
            'outUF' => $v['UF'],
        ];
//buscar dados na matrícula
        $pessoaGdae = sql::get('gdae_pessoa', ' * ', $where2, 'fetch');

        if (empty($pessoaGdae)) {
            $this->log('Novo aluno incluido', $v['RA']);

//buscar dados no Gdae
            $this->incluirPessoa($v['RA'], $v['digitoRA'], $v['UF'], @$v['outAno']);

            $pessoaGdae = sql::get('gdae_pessoa', ' * ', $where2, 'fetch');
        }

        if (!empty($pessoaGdae)) {
            $sql = "select id_pessoa from pessoa "
                    . "where n_pessoa like '" . str_replace("'", '', $v['nomeAluno']) . "'"
                    . " and dt_nasc = '" . data::converteUS($pessoaGdae['outDataNascimento']) . "' "
                    . " and mae like '" . explode(' ', $pessoaGdae['outNomeMae'])[0] . "%' "
                    . " and (ra is NULL or ra = '0')";
            $query = pdoSis::getInstance()->query($sql);
            $pessoaSearch = $query->fetch(PDO::FETCH_ASSOC);
            if (!empty($pessoaSearch)) {
//confirmo se não há este ra cadastrano no sistema
                $sql = "SELECT * FROM `pessoa` WHERE `ra` like '" . gt_gdaeSet::raNormatiza($v['RA']) . "'";
                $query = pdoSis::getInstance()->query($sql);
                $array = $query->fetch(PDO::FETCH_ASSOC);
                if (empty($array)) {
                    $sql = "UPDATE `pessoa` "
                            . " SET ra = '" . gt_gdaeSet::raNormatiza($v['RA']) . "', ra_dig = '" . trim($v['digitoRA']) . "', ra_uf = '" . strtoupper($v['UF']) . "' "
                            . " where id_pessoa = " . $pessoaSearch['id_pessoa'];
                    $query = pdoSis::getInstance()->query($sql);

                    return $pessoaSearch['id_pessoa'];
                } else {
//completa digito e uf na tabela

                    $sql = "UPDATE `pessoa` "
                            . " SET  ra_dig = '" . trim($v['digitoRA']) . "', ra_uf = '" . strtoupper($v['UF']) . "' "
                            . " where id_pessoa = " . $array['id_pessoa'];
                    $query = pdoSis::getInstance()->query($sql);

                    return $pessoaSearch['id_pessoa'];
                }
            } else {
                $id_pessoa = $this->pessoaInsertTbGdaePessoa($pessoaGdae);

                return $id_pessoa;
            }
        } else {
            $id_pessoa = $this->pessoaInsertTbGdaeAluno($v);

            return $id_pessoa;
        }
    }

    public function alunoClasse($aluno, $turmaDados, $id_pessoa) {

        $periodo_letivo = sql::get('ge_periodo_letivo', 'n_pl', ['id_pl' => $turmaDados['fk_id_pl']], 'fetch')['n_pl'];
        $turmaAluno = sql::get('ge_turma_aluno', '*', ['fk_id_turma' => $turmaDados['id_turma'], 'chamada' => @$aluno['numero'], 'fk_id_pessoa' => @$id_pessoa], 'fetch');
        if (!in_array(@$aluno['status'], ['', 'ATIVO']) && @$turmaAluno['situacao'] == 'Frequente') {
            $dados = [
                'RA' => gt_gdaeSet::raNormatiza(@$aluno['RA']),
                'digitoRA' => trim(@$aluno['digitoRA']),
                'UF' => @$aluno['UF'],
                'outNumClasse' => $turmaDados['prodesp']
            ];

            $this->insertMatricula($dados);
        }
        $where2 = [
            'outRA' => gt_gdaeSet::raNormatiza(@$aluno['RA']),
            'outdigitoRA' => trim(@$aluno['digitoRA']),
            'outUF' => @$aluno['UF'],
        ];

        $matricula = sql::get('gdae_matricula', '*', $where2, 'fetch');

        if (empty($matricula)) {
            $dados = [
                'RA' => gt_gdaeSet::raNormatiza(@$aluno['RA']),
                'digitoRA' => trim(@$aluno['digitoRA']),
                'UF' => @$aluno['UF'],
                'outNumClasse' => $turmaDados['prodesp']
            ];

            $this->insertMatricula($dados);
            $matricula = sql::get('gdae_matricula', '*', $where2, 'fetch');
        }


        if (empty($turmaAluno)) {
            unset($ta);
            $sql = "SET FOREIGN_KEY_CHECKS = 0;";
            $query = pdoSis::getInstance()->query($sql);
            $sql = "DELETE FROM `ge_turma_aluno` WHERE `fk_id_turma` = '" . $turmaDados['id_turma'] . "' AND `chamada` = " . @$aluno['numero'];
            $query = pdoSis::getInstance()->query($sql);
            $this->log('apagando turma-aluno', str_replace("'", '', $sql));

            $ta['codigo_classe'] = $turmaDados['codigo'];
            $ta['fk_id_turma'] = $turmaDados['id_turma'];
            $ta['periodo_letivo'] = $periodo_letivo;
            $ta['fk_id_pessoa'] = $id_pessoa;
            $ta['fk_id_inst'] = $turmaDados['fk_id_inst'];
            $ta['chamada'] = @$aluno['numero'];
            $ta['situacao'] = gt_gdaeSet::situacaoAlunoMigracao(@$aluno['status']);
            if (empty($matricula['outDataInicio'])) {
                $ta['dt_matricula'] = date("Y-m-d");
            } else {
                $ta['dt_matricula'] = data::converteUS(@$matricula['outDataInicio']);
            }
            if (!empty($matricula['outDataFim'])) {
                $ta['dt_matricula_fim'] = data::converteUS(@$matricula['outDataFim']);
                $ta['dt_transferencia'] = data::converteUS(@$matricula['outDataFim']);
            }
            $ta['turma_status'] = '1';
            $ta['gdae'] = 1;
            $this->db->insert('ge_turma_aluno', $ta);
        } else {
            $ta['id_turma_aluno'] = $turmaAluno['id_turma_aluno'];
            $ta['codigo_classe'] = $turmaDados['codigo'];
            $ta['fk_id_turma'] = $turmaDados['id_turma'];
            $ta['periodo_letivo'] = $periodo_letivo;
            $ta['fk_id_pessoa'] = $id_pessoa;
            $ta['fk_id_inst'] = $turmaDados['fk_id_inst'];
            $ta['chamada'] = @$aluno['numero'];
            $ta['situacao'] = gt_gdaeSet::situacaoAlunoMigracao(@$aluno['status']);
            if (empty($turmaAluno['dt_matricula'])) {
                $ta['dt_matricula'] = data::converteUS(@$matricula['outDataInicio']);
            }
            if (!empty($matricula['outDataFim'])) {
                $ta['dt_matricula_fim'] = data::converteUS(@$matricula['outDataFim']);
                $ta['dt_transferencia'] = data::converteUS(@$matricula['outDataFim']);
            } elseif (!in_array(@$aluno['status'], ['', 'ATIVO']) && $turmaAluno['situacao'] == 'Frequente') {
                $ta['dt_matricula_fim'] = date("Y-m-d");
                $ta['dt_transferencia'] = date("Y-m-d");
            }
            $ta['turma_status'] = '1';
            $ta['gdae'] = 2;
            $this->db->update('ge_turma_aluno', 'id_turma_aluno', $turmaAluno['id_turma_aluno'], $ta);
        }
    }

    function incluirPessoa($ra, $raDigito, $uf, $AnoLetivo = NULL) {
        $p = $this->_gt_gdae->ConsultarFichaAlunoRa($ra, $raDigito, $uf);

        if (is_array($p) && !empty($p)) {
            $pg = (array) $p['FichasAluno']->FichaAluno;

            @$pg['outDigitoRA'] = trim(@$pg['outDigitoRA']);
            $pg['outRA'] = gt_gdaeSet::raNormatiza($pg['outRA']);
            /**
              if (!empty($pg['outDeficiencias'])) {
              $pg['outDeficiencias'] = serialize($pg['outDeficiencias']);
              }
             * 
             */
            if (!empty($pg['outEndIndicativo'])) {
                $pg['outEndIndicativo'] = serialize($pg['outEndIndicativo']);
            }
            if (!empty($pg['outDeficiencias'])) {
                $pg['outDeficiencias'] = serialize($pg['outDeficiencias']);
            }
            if (!empty($pg['outCertidaoResp'])) {
                $pg['outCertidaoResp'] = serialize($pg['outCertidaoResp']);
            }
            @$pg['outDigitoRA'] = @trim($pg['outDigitoRA']);
            @$pg['ano'] = $AnoLetivo;
            $this->db->replace('gdae_pessoa', $pg);
        } else {
            $sql = "INSERT INTO `gdae_log_erro` "
                    . " (`id_log`, `servico`, `tabela`, `msg`, `ra`, `ra_dig`, `ra_uf`, `classe`, `cie`, `td_log`)"
                    . " VALUES ("
                    . " NULL, "
                    . " 'ConsultarFichaAlunoRa', "
                    . " 'gdae_pessoa', "
                    . " '$p', "
                    . " '" . $ra . "', "
                    . " '" . $raDigito . "', "
                    . " '" . @$uf . "', "
                    . " NULL, "
                    . " NULL, "
                    . " CURRENT_TIMESTAMP"
                    . ");";
            $query = pdoSis::getInstance()->query($sql);
        }
    }

    public function log($n_log, $descr) {
        $sql = "INSERT INTO `gdae_log` (`id_log`, `n_log`, `descr`, `data`) VALUES (NULL, '$n_log', '$descr', CURRENT_TIMESTAMP);";
        $query = pdoSis::getInstance()->query($sql);
    }

    public function atualizarTabelaPessoa($ano = NULL) {
        $this->log('gdae_pessoa', 'Início');
        if (!empty($ano)) {
            $ano = " WHERE `ano` = $ano ";
        }
        $exit = NULL;
        $limit = 0;
        while ($exit != 1) {
            $sql = "SELECT * FROM `gdae_pessoa` "
                    . $ano
                    . " ORDER BY `gdae_pessoa`.`outNomeAluno` ASC "
                    . " limit $limit, 1000";
            $query = pdoSis::getInstance()->query($sql);
            $alunoGdae = $query->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($alunoGdae)) {
                foreach ($alunoGdae as $v) {
                    $id_pessoa = sql::get('pessoa', 'id_pessoa', ['ra' => $v['outRA'], 'ra_dig' => trim($v['outDigitoRA']), 'ra_uf' => strtoupper($v['outUF'])], 'fetch')['id_pessoa'];
                    if (!empty($id_pessoa)) {
                        $this->pessoaInsertTbGdaePessoa($v, $id_pessoa);
                    }
                }
                $limit += 1000;
                $this->log('gdae_pessoa', $limit);
            } else {
                $exit = 1;
            }
        }
        $this->log('gdae_pessoa', 'Fim');
    }

    public function atulizarTabelaHist($ano = NULL) {
        $this->log('historico', 'Início');

        if (!empty($ano)) {
            $ano = " where `outAno` = $ano ";
        }
        $exit = NULL;
        $limit = 0;
        while ($exit != 1) {
            $fields = ' RA, digitoRA, UF ';
            $sql = "SELECT $fields FROM `gdae_aluno` "
                    . $ano
                    . " ORDER BY `gdae_aluno`.`numero` desc"
                    . ' limit ' . $limit . ',1000';
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($array)) {
                foreach ($array as $a) {
                    $anos = $this->_gt_gdae->ConsultarMatriculasRA(@$a['RA'], @$a['digitoRA'], @$a['UF']);
                    if (!empty($anos['Mensagens']->ConsultarMatricRA)) {
                        foreach ($anos['Mensagens']->ConsultarMatricRA as $k => $v) {
                            unset($ins);
                            @$v = (array) $v;
                            @$ins['ra'] = $a['RA'];
                            @$ins['ra_dig'] = $a['digitoRA'];
                            @$ins['ra_uf'] = strtoupper($a['UF']);
                            @$ins['numeroClasse'] = @$v['numeroClasse'];
                            @$ins['numero'] = @$v['numero'];
                            @$ins['anoLetivo'] = $v['anoLetivo'];
                            @$ins['codigoEscola'] = @$v['codigoEscola'];
                            @$ins['dataMatricula'] = @$v['dataMatricula'];
                            @$ins['serie'] = @$v['serie'];
                            @$ins['tipoEnsino'] = @$v['tipoEnsino'];
                            @$ins['turma'] = @$v['turma'];
                            @$ins['turno'] = @$v['turno'];

                            $this->db->replace('gdae_hist', $ins);
                        }
                    } else {
                        @$ins['ra'] = $a['RA'];
                        @$ins['ra_dig'] = $a['digitoRA'];
                        @$ins['ra_uf'] = strtoupper($a['UF']);
                        @$ins['servico'] = 'historico';
                        @$ins['msg'] = 'erro prodesp';

                        $this->db->replace('gdae_tmp', $ins);
                    }
                }
                $limit += 1000;
                $this->log('historico', $limit);
            } else {
                $exit = 1;
            }
        }
        $this->log('historico', 'fim');
    }

}
