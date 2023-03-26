<?php

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);

$tm_inicio = microtime( true );

$AnoLetivo = @$_REQUEST['ano'];

include '/var/www/html/ge/class/gt_gdae.php';
include '/var/www/html/ge/class/DB.php';
include '/var/www/html/ge/class/data.php';

class pdoSis {

    public static $instance;

    private function __construct() {
        //
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new PDO('mysql:host=' . 'localhost' . ';dbname=' . 'ge2', 'root', 'linkair2009@@@456#1', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
        }

        return self::$instance;
    }

}

function logSis($n_log, $descr) {
    $descr = str_replace("'", '', $descr);
    $sql = "INSERT INTO `gdae_log` (`id_log`, `n_log`, `descr`, `data`) VALUES (NULL, '" . @$n_log . "', '" . @$descr . "', CURRENT_TIMESTAMP);";
    $query = pdoSis::getInstance()->query($sql);
}

function replace($table) {
    // Configura o array de colunas
    $cols = array();

    // Configura o valor inicial do modelo
    $place_holders = '(';

    // Configura o array de valores
    $values = array();

    // O $j will assegura que colunas serão configuradas apenas uma vez
    $j = 1;

    // Obtém os argumentos enviados
    $data = func_get_args();

    // É preciso enviar pelo menos um array de chaves e valores
    if (!isset($data[1]) || !is_array($data[1])) {
        return;
    }

    // Faz um laço nos argumentos
    for ($i = 1; $i < count($data); $i++) {

        // Obtém as chaves como colunas e valores como valores
        if (!empty($data[$i]) && is_array($data[$i])) {
            foreach ($data[$i] as $col => $val) {

                // A primeira volta do laço configura as colunas
                if ($i === 1 && is_string($col)) {
                    $cols[] = "`$col`";
                }

                if ($j <> $i) {
                    // Configura os divisores
                    $place_holders .= '), (';
                }

                // Configura os place holders do PDO
                $place_holders .= '?, ';

                // Configura os valores que vamos enviar
                $values[] = str_replace("'", '', $val);
                ;

                $j = $i;
            }
        }
        // Remove os caracteres extra dos place holders
        $place_holders = substr($place_holders, 0, strlen($place_holders) - 2);
    }

    // Separa as colunas por vírgula

    $valores = "'" . implode("', '", $values) . "'";
    if (!empty($cols)) {
        $cols = implode(', ', $cols);
        // Cria a declaração para enviar ao PDO
        $sql = "REPLACE INTO `$table` ( $cols ) VALUES ($valores) ";

        $query = pdoSis::getInstance()->query($sql);
    }
}

function alunosComIdPessoa($classeProdesp) {
    foreach ($classeProdesp as $v) {
        $v = (array) $v;
        $ras[] = raNormatiza($v['RA']);
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
        $siebComRaId[raNormatiza($v['ra']) . '-' . trim($v['ra_dig']) . '-' . $v['ra_uf']] = $v;
    }

    return @$siebComRaId;
}

function raNormatiza($ra) {
    if (is_numeric($ra)) {
        $ra = intval($ra);
    }

    return $ra;
}

function incluirAlerarAlunoRA($v) {


    //buscar dados na matrícula
    $sql = "select * from gdae_pessoa "
            . " where outRA like '" . (raNormatiza($v['RA'])) . "' "
            . " and outdigitoRA like '" . (trim($v['digitoRA'])) . "' "
            . " AND outUF like '" . ($v['UF']) . "'";
    $query = pdoSis::getInstance()->query($sql);
    $pessoaGdae = $query->fetch(PDO::FETCH_ASSOC);

    if (empty($pessoaGdae)) {
        logSis('Novo aluno incluido', $v['RA']);

//buscar dados no Gdae

        incluirPessoa($v['RA'], $v['digitoRA'], $v['UF'], @$v['outAno']);

        $sql = "select * from gdae_pessoa "
                . " where outRA like '" . (raNormatiza($v['RA'])) . "' "
                . " and outdigitoRA like '" . (trim($v['digitoRA'])) . "' "
                . " AND outUF like '" . ($v['UF']) . "'";
        $query = pdoSis::getInstance()->query($sql);
        $pessoaGdae = $query->fetch(PDO::FETCH_ASSOC);
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
            $sql = "SELECT * FROM `pessoa` WHERE `ra` like '" . raNormatiza($v['RA']) . "'";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetch(PDO::FETCH_ASSOC);
            if (empty($array)) {
                $sql = "UPDATE `pessoa` "
                        . " SET ra = '" . raNormatiza($v['RA']) . "', ra_dig = '" . trim($v['digitoRA']) . "', ra_uf = '" . strtoupper($v['UF']) . "' "
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
            $id_pessoa = pessoaInsertTbGdaePessoa($pessoaGdae);

            return $id_pessoa;
        }
    } else {
        $id_pessoa = pessoaInsertTbGdaeAluno($v);

        return $id_pessoa;
    }
}

function incluirPessoa($ra, $raDigito, $uf, $AnoLetivo = NULL) {
    $db = new DB('localhost', 'ge2', 'linkair2009@@@456#1', 'root', 'utf8', 1);
    $gt_gdae = new gt_gdae();
    $p = $gt_gdae->ConsultarFichaAlunoRa($ra, $raDigito, $uf);

    if (is_array($p) && !empty($p)) {
        $pg = (array) $p['FichasAluno']->FichaAluno;

        @$pg['outDigitoRA'] = trim(@$pg['outDigitoRA']);
        $pg['outRA'] = raNormatiza($pg['outRA']);
        if (!empty($pg['outDeficiencias'])) {
            $pg['outDeficiencias'] = serialize($pg['outDeficiencias']);
        }
        if (!empty($pg['outEndIndicativo'])) {
            $pg['outEndIndicativo'] = serialize($pg['outEndIndicativo']);
        }
        if (!empty($pg['outCertidaoResp'])) {
            $pg['outCertidaoResp'] = serialize($pg['outCertidaoResp']);
        }
        @$pg['outDigitoRA'] = @trim($pg['outDigitoRA']);
        @$pg['ano'] = $AnoLetivo;
        $db->replace('gdae_pessoa', $pg);
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

function pessoaInsertTbGdaePessoa($dados, $id_pessoa = NULL) {
    $db = new DB('localhost', 'ge2', 'linkair2009@@@456#1', 'root', 'utf8', 1);
    $ins['n_pessoa'] = $dados['outNomeAluno'];
    $ins['n_social'] = $dados['outNomeSocial'];
    $ins['dt_nasc'] = data::converteUS($dados['outDataNascimento']);
    $ins['ativo'] = '1';
    $ins['cpf'] = $dados['outCPF'];
    $ins['sexo'] = substr($dados['outSexo'], 0, 1);
    $ins['ra'] = raNormatiza($dados['outRA']);
    $ins['ra_dig'] = trim($dados['outDigitoRA']);
    $ins['ra_uf'] = strtoupper($dados['outUF']);
    $ins['rg'] = $dados['outNumRG'];
    $ins['rg_dig'] = $dados['outDigitoRG'];
    $ins['rg_uf'] = $dados['outUFRG'];
    $ins['dt_rg'] = $dados['outDataEmissaoRG'];
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
    $ins['deficiencia'] = empty($dados['outDeficiencias']) ? NULL : '1';
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

    $pessoa = $db->ireplace('pessoa', $ins, 1);

    pessoaInsertTbGdaePessoaEnd($dados, @$id_pessoa);

    return $id_pessoa;
}

function pessoaInsertTbGdaePessoaEnd($dados, $id_pessoa = NULL) {

    $db = new DB('localhost', 'ge2', 'linkair2009@@@456#1', 'root', 'utf8', 1);
    if (empty($id_pessoa)) {
        $sql = "select * from pessoa "
                . " where ra like '" . (raNormatiza($dados['outRA'])) . "' "
                . " and ra_dig like '" . (trim($dados['outDigitoRA'])) . "' "
                . " AND ra_uf like '" . (strtoupper($dados['outUF'])) . "'";
        $query = pdoSis::getInstance()->query($sql);
        $id_pessoa = $query->fetch(PDO::FETCH_ASSOC)['id_pessoa'];
    }
    if (!empty($id_pessoa)) {
        $sql = "select logradouro, id_end from endereco "
                . " WHERE fk_id_pessoa like '$id_pessoa' "
                . " and fk_id_tp like '1'";
        $query = pdoSis::getInstance()->query($sql);
        $endereco = $query->fetch(PDO::FETCH_ASSOC);


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

        replace('endereco', $end, 1);
    } else {
        logSis('Pessoa não encontrada ', $sql);
    }
}

function alunoClasse($aluno, $turmaDados, $id_pessoa) {
    $db = new DB('localhost', 'ge2', 'linkair2009@@@456#1', 'root', 'utf8', 1);
    $sql = "select n_pl from ge_periodo_letivo "
            . " where id_pl = " . $turmaDados['fk_id_pl'];
    $query = pdoSis::getInstance()->query($sql);
    $periodo_letivo = $query->fetch(PDO::FETCH_ASSOC)['n_pl'];

    $sql = "select * from ge_turma_aluno "
            . " where fk_id_turma =  " . $turmaDados['id_turma']
            . " and chamada = " . @$aluno['numero']
            . " and fk_id_pessoa = @$id_pessoa ";
    $query = pdoSis::getInstance()->query($sql);
    $turmaAluno = $query->fetch(PDO::FETCH_ASSOC);

    if (@$aluno['status'] != '' && @$turmaAluno['situacao'] == 'Frequente') {
        $dados = [
            'RA' => raNormatiza(@$aluno['RA']),
            'digitoRA' => trim(@$aluno['digitoRA']),
            'UF' => @$aluno['UF'],
            'outNumClasse' => $turmaDados['prodesp']
        ];

        insertMatricula($dados);
    }

    $sql = "select * from gdae_matricula "
            . " where outRA like '" . (raNormatiza(@$aluno['RA'])) . "' "
            . " and outdigitoRA like '" . (trim(@$aluno['digitoRA'])) . "' "
            . " and outUF like '" . (@$aluno['UF']) . "' ";
    $query = pdoSis::getInstance()->query($sql);
    $matricula = $query->fetch(PDO::FETCH_ASSOC);



    if (empty($matricula)) {
        $dados = [
            'RA' => raNormatiza(@$aluno['RA']),
            'digitoRA' => trim(@$aluno['digitoRA']),
            'UF' => @$aluno['UF'],
            'outNumClasse' => $turmaDados['prodesp']
        ];

        insertMatricula($dados);
        $sql = "select * from gdae_matricula "
                . " where outRA like '" . (raNormatiza(@$aluno['RA'])) . "' "
                . " and outdigitoRA like '" . (trim(@$aluno['digitoRA'])) . "' "
                . " and outUF like '" . (@$aluno['UF']) . "' ";
        $query = pdoSis::getInstance()->query($sql);
        $matricula = $query->fetch(PDO::FETCH_ASSOC);
    }


    if (empty($turmaAluno)) {
        $sql = "SET FOREIGN_KEY_CHECKS = 0;";
        $query = pdoSis::getInstance()->query($sql);
        $sql = "DELETE FROM `ge_turma_aluno` WHERE `fk_id_turma` = '" . $turmaDados['id_turma'] . "' AND `chamada` = " . @$aluno['numero'];
        $query = pdoSis::getInstance()->query($sql);
        logSis('apagando turma-aluno', str_replace("'", '', $sql));

        $ta['codigo_classe'] = $turmaDados['codigo'];
        $ta['fk_id_turma'] = $turmaDados['id_turma'];
        $ta['periodo_letivo'] = $periodo_letivo;
        $ta['fk_id_pessoa'] = $id_pessoa;
        $ta['fk_id_inst'] = $turmaDados['fk_id_inst'];
        $ta['chamada'] = @$aluno['numero'];
        $ta['situacao'] = situacaoAlunoMigracao(@$aluno['status']);
        if (empty($matricula['outDataInicio']) && substr($periodo_letivo, -4) == date("Y")) {
            $ta['dt_matricula'] = date("Y-m-d");
        } else {
            $ta['dt_matricula'] = data::converteUS(@$matricula['outDataInicio']);
        }
        if (@$aluno['status'] != '') {
            $ta['dt_matricula_fim'] = data::converteUS(@$matricula['outDataFim']);
            $ta['dt_transferencia'] = data::converteUS(@$matricula['outDataFim']);
        }
        $ta['turma_status'] = '1';

        $db->insert('ge_turma_aluno', $ta);
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
        if (@$aluno['status'] != '' && $turmaAluno['situacao'] == 'Frequente') {
            $ta['dt_matricula_fim'] = date("Y-m-d");
            $ta['dt_transferencia'] = date("Y-m-d");
        } elseif (@$aluno['status'] != '') {
            $ta['dt_matricula_fim'] = data::converteUS(@$matricula['outDataFim']);
            $ta['dt_transferencia'] = data::converteUS(@$matricula['outDataFim']);
        }
        $ta['turma_status'] = '1';

        $db->update('ge_turma_aluno', 'id_turma_aluno', $turmaAluno['id_turma_aluno'], $ta);
    }
}

function insertMatricula($v) {
    $db = new DB('localhost', 'ge2', 'linkair2009@@@456#1', 'root', 'utf8', 1);
    $gt_gdae = new gt_gdae();
    @$a = $gt_gdae->ConsultarMatriculaClasseRA($v['RA'], $v['outNumClasse'], $v['digitoRA'], $v['UF'], NULL);

    if (is_string(@$a) || empty($a)) {
        if (is_array(@$a)) {
            $a = NULL;
        }
        $sql = "REPLACE INTO gdae_matricula_erro (`ra`, `ra_dig`, `ra_uf`, `classe`, `msg`, `data`) VALUES ("
                . "'" . $v['RA'] . "', "
                . "'" . $v['digitoRA'] . "', "
                . "'" . $v['UF'] . "', "
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

        $db->replace('gdae_matricula', $ins);
    }
}

function situacaoAlunoMigracao($situacao = NULL) {

    $sit = [
        '' => 'Frequente',
        'TRANSFERIDO' => 'Transferido Escola',
        'BAIXA - TRANSFERÊNCIA' => 'Transferido Escola',
        'BAIXA - TR' => 'Transferido Escola',
        'ABANDONOU' => 'Abandonou',
        'RECLASSIFICADO' => 'Reclassificado',
        'RECLASSIFI' => 'Reclassificado',
        'FALECIDO' => 'Falecido',
        'NÃO COMPARECEU' => 'Não Compareceu',
        'CESSÃO POR OBJETIVOS ATINGIDOS' => 'Cessão Por Objetivos Atingidos',
        'CESSÃO POR NÃO FREQUÊNCIA' => 'Cessão Por Não Frequência',
        'CESSÃO POR TRANSFERÊNCIA/REMANEJAMENTO' => 'Cessão Por Transferência/Remanejamento',
        'CESSÃO POR DESISTÊNCIA' => 'Cessão Por Desistência',
        'REMANEJAMENTO' => 'Remanejamento',
        'CESSÃO POR EXAME' => 'Cessão Por Exame',
        'CESSÃO POR NÚMERO REDUZIDO DE ALUNOS' => 'Cessão Por Número Reduzido De Alunos',
        'CESSÃO POR FALTA DE DOCENTE' => 'Cessão Por Falta De Docente',
        'CESSÃO POR DISPENSA' => 'Cessão Por Dispensa',
        'CESSÃO POR' => 'Cessão',
        'CESSÃO POR CONCLUSÃO DO CURSO' => 'Cessão Por Conclusão Do Curso',
        'TRANSFERIDO (CONVERSÃO DO ABANDONO)' => 'Transferido Escola',
        'TRANSFERID' => 'Transferido (Conversão Do Abandono)',
        'REMANEJADO (CONVERSÃO DO ABANDONO)' => 'Remanejado (Conversão Do Abandono)',
        'REMANEJAME' => 'Remanejado (Conversão Do Abandono)',
        'NÃO COMPARECEU / FORA DO PRAZO' => 'Não Compareceu / Fora Do Prazo',
        'TRANSFERIDO - CEEJA' => 'Transferido - CEEJA',
        'NÃO COMPARECIMENTO - CEEJA' => 'Não Comparecimento - CEEJA',
        'CONCLUINTE - CEEJA' => 'Concluinte - CEEJA',
        'NÃO COMPARECIMENTO' => 'Não Compareceu',
        'NÃO COMPAR' => 'Não Compareceu'
    ];

    if (!empty($sit[$situacao])) {
        return $sit[$situacao];
    } else {
        tool::alert("Situação Não Definida. Procure o Depto de Informática (" . $situacao . ")");
        return 'Indefinida';
    }
}

function matriculasErro($count) {
    $gt_gdae = new gt_gdae();
    echo 'Início' . date('h:i:s') . '<br />';

    $sql = "SELECT count(`classe`) as contador FROM `gdae_aluno_erro` WHERE 1";
    $query = pdoSis::getInstance()->query($sql);
    echo 'Classes não baixadas: ' . $array = $query->fetch(PDO::FETCH_ASSOC)['contador'] . '<br />';

    $sql = "select * from gdae_aluno_erro ";
    $query = pdoSis::getInstance()->query($sql);
    $turmas = $query->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($turmas)) {
        foreach ($turmas as $v) {
            @$alunos = $gt_gdae->ConsultaFormacaoClasse($v['classe']);

            if (!is_string(@$alunos)) {
                if (!empty($alunos['Mensagens'])) {
                    @$alunos = @$alunos['Mensagens']->ConsultaClasse;
                    foreach (@$alunos as $a) {
                        $a = (array) $a;
                        replace('gdae_aluno', $a);
                    }
                } else {
                    logSis('erro incluir aluno gdae_aluno', $v['classe']);
                }
                if (!empty($v['classe'])) {
                    $sql = "DELETE FROM `gdae_aluno_erro` WHERE `gdae_aluno_erro`.`classe` = " . $v['classe'];
                    $query = pdoSis::getInstance()->query($sql);
                }
            }
        }
        logSis('fim refazer erro ', $v['classe']);
        $sql = "select * from gdae_aluno_erro ";
        $query = pdoSis::getInstance()->query($sql);
        $turmas = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($count++ < 5) {
            matriculasErro($count);
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

function pessoaInsertTbGdaeAluno($dados) {
    $ins['n_pessoa'] = $dados['nomeAluno'];
    $ins['ra'] = raNormatiza($dados['RA']);
    $ins['ra_dig'] = $dados['digitoRA'];
    $ins['ra_uf'] = strtoupper($dados['UF']);

    $id_pessoa = replace('pessoa', $ins);

    return $id_pessoa;
}

########################## Início ##########################################
matriculas($AnoLetivo);
atualizarAlunos($AnoLetivo);

function matriculas($AnoLetivo = NULL, $id_inst = NULL) {
    $gt_gdae = new gt_gdae;
    $sql = "TRUNCATE gdae_aluno_erro";
    $query = pdoSis::getInstance()->query($sql);
    logSis('tabela gdae_aluno', 'Início');

    if (empty($AnoLetivo)) {
        $AnoLetivo = date("Y");
    }

    $sql = "select outNrClasse from gdae_classe "
            . " where ano like '$AnoLetivo'";
    $query = pdoSis::getInstance()->query($sql);
    $turmas = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($turmas as $v) {

        @$alunos = $gt_gdae->ConsultaFormacaoClasse($v['outNrClasse']);

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
                    replace('gdae_aluno', $a);
                    $numero[@$a['numero']] = @$a['numero'];
                }
                if (is_array(@$numero) && !empty($v['outNrClasse']) && !empty(implode(',', $numero)) && !empty($a['numero'])) {
                    $sql = "DELETE FROM `gdae_aluno` "
                            . " WHERE `outNumClasse` =  " . $v['outNrClasse']
                            . " AND `numero` NOT IN (" . implode(',', $numero) . ") ";
                    $query = pdoSis::getInstance()->query($sql);
                }
            } else {
                logSis('Eroo Classe', $v['outNrClasse']);
            }
        }
    }
    logSis('tabela gdae_aluno', 'Fim');

    $count = 1;
    matriculasErro($count);
}

//sincroniza gdae_aluno com ge_turma_aluno
function atualizarAlunos($AnoLetivo = NULL) {
    $conta = 1;
    logSis('Atualização ge_turma_aluno', 'início');

    if (empty($AnoLetivo)) {
        $AnoLetivo = date("Y");
    }

    $sql = "select distinct outNumClasse from gdae_aluno "
            . " where outAno like '$AnoLetivo'";
    $query = pdoSis::getInstance()->query($sql);
    $turmas = $query->fetchAll(PDO::FETCH_ASSOC);
    $turmas = array_column($turmas, 'outNumClasse');

    foreach ($turmas as $t) {

        $sql = "select * from ge_turmas "
                . "where prodesp like '$t' ";
        $query = pdoSis::getInstance()->query($sql);
        $turmaDados = $query->fetch(PDO::FETCH_ASSOC);

        if (!empty($turmaDados)) {
            $fields = 'RA, digitoRA, UF, outNumClasse, '
                    . 'numero, nomeAluno, outAno, outCodEscola, '
                    . 'outErro, outSerie, outTipoEnsino, '
                    . 'outTurma, outTurno, status, '
                    . 'seriemulti, tipoensinomulti, data';

            $sql = "select $fields from gdae_aluno "
                    . " where outNumClasse like '$t' "
                    . " order by numero ";
            $query = pdoSis::getInstance()->query($sql);
            $classeProdesp = $query->fetchAll(PDO::FETCH_ASSOC);
            if (is_array($classeProdesp)) {

                //confiro os alunos que já estão na turma
                $siebComRaId = alunosComIdPessoa($classeProdesp);
                unset($numero);
                foreach ($classeProdesp as $v) {

                    $alunoSieb = @$siebComRaId[raNormatiza($v['RA']) . '-' . trim($v['digitoRA']) . '-' . trim($v['UF'])];
                    $id_pessoa = NULL;
                    $v = (array) $v;
                    //se a pessoa não exiete

                    if (empty($alunoSieb)) {
                        $id_pessoa = incluirAlerarAlunoRA($v);
                    } else {
                        $id_pessoa = $alunoSieb['id_pessoa'];
                    }

                    if (!empty($alunoSieb) or ! empty($id_pessoa)) {
                        alunoClasse($v, $turmaDados, $id_pessoa);
                    }

                    if (@$conta % 1000 == 0) {
                        logSis('Lançamentos', $conta);
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
            logSis('Turma não encontrada no Sieb', 'prodesp: ' . $t);
        }
        $sql = "update ge_turmas set dt_gdae = '" . date("Y-m-d H:i:s") . "' "
                . " where prodesp = $t";
        $query = pdoSis::getInstance()->query($sql);
    }
    logSis('Atualização ge_turma_aluno', 'fimal');
}


// Armazena  o timestamp apos a execucao do script
$tm_fim = microtime( true );
// Calcula o tempo de execucao do script 
$tempo_execucao = $tm_fim - $tm_inicio;
$horas = (int) ($tempo_execucao/60/60);
$minutos = (int) ($tempo_execucao/60) - $horas * 60;
$segundos = (int) $tempo_execucao - $horas * 60 * 60 - $minutos * 60;

// Exibe o tempo de execucao do script em segundos
$fp = fopen('/var/www/html/log_atualizar_classe.txt', 'r+');
fwrite($fp, PHP_EOL . 'Data de execução: ' . date('Y/m/d H:i:s') . PHP_EOL );
fwrite($fp, "Tempo de execução: Hora:$horas Minutos: $minutos Segundos: $segundos " . PHP_EOL);
fwrite($fp, '##########################################################################' . PHP_EOL);
fwrite($fp, ' ' . PHP_EOL);
fclose($fp);

