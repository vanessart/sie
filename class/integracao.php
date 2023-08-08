<?php

class integracao {

	public array $_data;
	public static $token = NULL;
	public $dadosCLI = null;
	protected $cliente = null;

	public function __construct()
	{
		try {
			$this->dadosCLI = $this->getCLI();

			if ( is_null($this->dadosCLI) ) {
				throw new Exception("Cliente não Configurado", 1001);
			}

			if ( !isset($this->dadosCLI['class']) ) {
				throw new Exception("Class Cliente não Configurado", 1002);
			}

			$class = $this->dadosCLI['class'];
			if (class_exists($class)) {
				$this->cliente = new $class();
			} else {
				throw new Exception("Class [$class] não Configurado", 1003);
			}

		} catch (Exception $e) {
			$this->dadosCLI['ret'] = self::defaultReturnFail($e);
			return self::defaultReturnFail($e);
		}
	}

	public function __set($key, $value)
	{
		$this->_data[$key] = $value;
	}

	public function __get($key)
	{
		if($key=='token') return;
		return $this->_data[$key];
	}

	public function setDados($data = []){

		if ( is_null($data) ) {
			throw new Exception("Nenhum dado configurado", 1004);
		}

		foreach ($data as $key => $value) {
			$this->$key = $value;
		}

		if ( empty($this->_data['dados']) ) {
			$dados = [];
		} else {
			$dados = $this->_data['dados'];
		}

		return $dados;
	}

	public function auth()
	{
		try {

			if ( !empty(self::$token) ) {
				$ret = self::defaultReturn();
				return $ret;
			}

			// atribui os dados do endpoint de autenticacão
			$da = $this->cliente->dadosAuth();

			$dados = $this->setDados($da);

			$r = $this->execCurl($dados);

			if (empty($r['access_token'])) {
				throw new Exception("API ". $this->_data['name'] ." não retornou o token", 1009);
			}
			
			self::$token = $r['access_token'];

			$ret = self::defaultReturn();

		} catch (Exception $e) {
			$ret = self::defaultReturnFail($e);
		}

		return $ret;
	}

	public function escolas()
	{
		try {
			$a = $this->auth();
			if (isset($a['status']) && $a['status'] === false) {
				throw new Exception($a['message'], $a['code']);
			}

			// atribui os dados do endpoint de escolas
			$da = $this->cliente->dadosEscolas();

			$dados = $this->setDados($da);

			$r = $this->execCurl($dados);
			
			$this->escolasModel($r['Data']);

			$ret = self::defaultReturn();

		} catch (Exception $e) {
			$ret = self::defaultReturnFail($e);
		}

		return $ret;
	}

	public function turmas($inCodColegio = null, $atualizaAluno = false)
	{
		try {
			$a = $this->auth();
			if (isset($a['status']) && $a['status'] === false) {
				throw new Exception($a['message'], $a['code']);
			}

			// atribui os dados do endpoint de escolas
			$da = $this->cliente->dadosTurmas($inCodColegio);

			$dados = $this->setDados($da);

			$r = $this->execCurl($dados);
			
			$dt = $this->turmasModel($r['Data'], $atualizaAluno);

			$ret = self::defaultReturn(['resultado' => $dt]);

		} catch (Exception $e) {
			$ret = self::defaultReturnFail($e);
		}

		return $ret;
	}

	public function alunos($inCodColegio = null, $inNomeAluno = null, $inRa = null)
	{
		try {
			$a = $this->auth();
			if (isset($a['status']) && $a['status'] === false) {
				throw new Exception($a['message'], $a['code']);
			}

			// atribui os dados do endpoint de escolas
			$da = $this->cliente->dadosAlunos($inCodColegio, $inNomeAluno, $inRa);

			$dados = $this->setDados($da);

			$r = $this->execCurl($dados);

			$this->alunosModel($r['Data']);

			$ret = self::defaultReturn();

		} catch (Exception $e) {
			$ret = self::defaultReturnFail($e);
		}

		return $ret;
	}

	public function getCLI()
	{
		if ($this->dadosCLI === null) {
			// identifica o cliente e pega os dados para a integracao
			$this->dadosCLI = [
				'class' => CLI_INTEGRACAO,
			];
		}
		return $this->dadosCLI;
	}

	public function getToken()
	{
		// verifica o tipo de token usado
		// verifica se existe o token
		// verifica se o token expirou
		// retorna o token
		return self::$token;
	}

	public function execCurl($dados = [])
	{
		$ret = file_get_contents_by_curl($this->_data['url'] . $this->_data['endpoint'], $this->_data['method'], $this->_data['header'], $dados);

		if (empty($ret)) {
			throw new Exception("API ". $this->_data['name'] ." não retornou dados", 1005);
		}

		if (!isset($ret['code'])) {
			throw new Exception("API ". $this->_data['name'] ." não retornou o código de erro", 1006);
		}

		$r = json_decode($ret['ret'], true);
		if (empty($r)) {
			throw new Exception("API ". $this->_data['name'] ." retornou falha. \n". print_r($ret, true), 1007);
		}

		if ($ret['code'] != 200 ) {
			if (!empty($r['error'])) {
				throw new Exception($r['error'], 1010);
			} elseif (!empty($r['Messages'])) {
				throw new Exception($r['Messages'], 1010);
			} else {
				throw new Exception("API ". $this->_data['name'] ." retornou falha. \n". print_r($ret, true), 1008);
			}
		}

		return $r;
	}

	public static function defaultReturn($dados = [])
	{
		$ret = [
			'status' => true,
			'message' => 'Sucesso',
			'code' => 0,
		];

		if (!empty($dados)) {
			$ret = array_merge($ret, $dados);
		}

		return $ret;
	}

	public static function defaultReturnFail($e)
	{
		$ret = [
			'status' => false,
			'message' => $e->getMessage(),
			'code' => $e->getCode() ?? 1,
		];

		return $ret;
	}

	public function escolasModel($a=[])
	{
	    foreach ($a as $k => $v) {
	        try {
	            $outNomeColegio = str_replace("'", "", $v['outNomeColegio']);

	            $sql = "REPLACE INTO instancia VALUES (". $v['outCodColegio'] .",'". $outNomeColegio ."', 0, 1, 0, null, 0, 1, 0)";
	            // echo $sql."<br>";
	            $query = pdoSis::getInstance()->query($sql);

	            $sql = "REPLACE INTO predio (id_predio, n_predio,sigla,descricao,ativo,cep,logradouro,num,complemento,bairro,cidade,uf,tel1,tel2,tel3) VALUES (". $v['outCodColegio'] .",'". $outNomeColegio ."', '', '', 1, '', '', '', '', '', 'Santana de Paranaíba', 'SP', NULL, NULL, NULL)";
	            // echo $sql."<br>";
	            $query = pdoSis::getInstance()->query($sql);

	            $sql = "REPLACE INTO ge_escolas (fk_id_inst,fk_id_tp_ens,classe,ato_cria,ato_municipa,vizualizar) VALUES (". $v['outCodColegio'] .", '1|2', 1, '','',1)";
	            // echo $sql."<br>";
	            $query = pdoSis::getInstance()->query($sql);

	            $sql = "INSERT INTO sed_inst_curso (fk_id_inst,fk_id_curso) SELECT ". $v['outCodColegio'] .", gc.id_curso FROM ge_cursos gc LEFT JOIN sed_inst_curso ic ON gc.id_curso = ic.fk_id_curso AND ic.fk_id_inst = ". $v['outCodColegio'] ." WHERE gc.id_curso IN(1,11) AND ic.id_ic IS NULL;";
	            // echo $sql."<br>";
	            $query = pdoSis::getInstance()->query($sql);

	            // echo "<br><br>";
	        } catch (Exception $e) {
	            echo "<span style='color: #f00'>";
	            var_dump($e->getMessage(), $e->getCode(), $v);
	            echo "</span><br>";
	        }
	    }
	}

	public function turmasModel($a=[], $atualizaAluno = false)
	{
		$cont = 0;
	    $max = 3;
	    $idsTurmas = [];
	    foreach ($a as $k => $v) {
	        $cont++;

			// limpa a chamada do aluno para não duplicar registros
			if (!empty($atualizaAluno)) {
				$sql = "UPDATE ge_turma_aluno "
				. " SET chamada = NULL, situacao = NULL, fk_id_tas = NULL, fk_id_sit_sed = 0 "
				. " WHERE fk_id_turma = " . $v['outCodTurma'];
				$query = pdoSis::getInstance()->query($sql);
			}

	        $periodo = substr($v['outTurno'], 0, 1);    
	        $letra = substr($v['outTurma'], -1);
	        $ciclo = preg_replace( '/[^0-9]/', '', $v['outTurma'] );

	        // var_dump($periodo, $letra);
	        // echo "<br>";

	        // if ($cont > $max) die();

	        $sql = "REPLACE INTO `ge_turmas` (`id_turma`, `n_turma`, `fk_id_inst`, `fk_id_ciclo`, `fk_id_grade`, `codigo`, `periodo`, `periodo_letivo`, `fk_id_pl`, `letra`, `status`, `dt_gdae` )
	            SELECT ". $v['outCodTurma'] .", '". str_replace("'", "", $v['outTurma']) ."', i.id_inst, gc.id_ciclo, IF(te.id_tp_ens = 4, 2, 3) AS fk_id_grade, concat(c.sg_curso, '".$periodo."', gc.sg_ciclo, '".$letra."') AS codigo, '".$periodo."', pl.ano, pl.id_pl, '".$letra."', 1, NOW()
	            FROM ge_tp_ensino te
	            JOIN ge_cursos c ON te.id_tp_ens = c.fk_id_tp_ens 
	            JOIN ge_ciclos gc ON c.id_curso = gc.fk_id_curso 
	            JOIN ge_periodo_letivo pl ON pl.id_pl  = 1
	            , instancia i 
	            WHERE te.id_tp_ens IN(4, 7) AND gc.sg_ciclo between '1' AND '9'
	            AND c.n_curso = '".$v['outCiclo']."'
	            AND gc.sg_ciclo = '".$ciclo."'
	            AND i.id_inst = ". $v['outCodColegio'] .";";
	        // echo $sql;
	        $query = pdoSis::getInstance()->query($sql);
	        if ($query) {
	        	$idsTurmas[] = $v['outCodTurma'];
	        }

	        // echo "<br><br>";
	    }

	    return $idsTurmas;
	}

	public function alunosModel($a=[])
	{
		$cont = 0;
	    $max = 2;
	    $alunosSie = [];

	    echo '<pre>';
	    foreach ($a as $k => $pes) {
	        try {
	            $cont++;
	            // var_dump($pes);
	            // echo "<br>";

	            // if ($cont >= $max) die();

	            $dt = DateTime::createFromFormat('d/m/Y H:i:s', $pes['outDataNascimento']);
	            $outDataNascimento = $dt->format('Y-m-d');

	            if (empty($pes['outNumRa'])) {
	                throw new Exception("Atributo outNumRa não informado");
	            }

	            $sql = "SELECT n_pessoa, id_pessoa, cpf FROM `pessoa` WHERE `ra` LIKE '" . trim($pes['outNumRa']) . "' ";
	            $query = pdoSis::getInstance()->query($sql);
	            $array = $query->fetch(PDO::FETCH_ASSOC);

	            if (empty($array)) {

	                $sql = "INSERT INTO `pessoa`( "
	                    . " `n_pessoa`, `n_social`, `dt_nasc`, `email`, `ativo`, `sexo`, `mae`, `ra`, `ra_dig`, `cpf`, `rg`, `rg_dig`, `rg_uf`, `nis`, `ra_uf`, `dt_gdae`) "
	                    . " VALUES ("
	                    . "'" . str_replace("'", "", $pes['outNomeAluno']) . "', "
	                    . "'" . str_replace("'", "", $pes['outNomeAluno']) . "', "
	                    . "'" . $outDataNascimento . "', "
	                    . "NULL, "
	                    . "1, "
	                    . "'" . $pes['outSexo'] . "', "
	                    . "'" . str_replace("'", "", $pes['outNomeMae']) . "', "
	                    . "'" . trim($pes['outNumRa']) . "', "
	                    . "'" . trim($pes['outRa']) . "', "
	                    . " NULL, "
	                    . " NULL, "
	                    . " NULL, "
	                    . " NULL, "
	                    . " NULL, "
	                    . "'" . $pes['outRaUf'] . "', "
	                    . "NOW() "
	                    . " );";

	                    // echo $sql;
	                    $query = pdoSis::getInstance()->query($sql);
	                    $id_pessoa = pdoSis::getInstance()->lastInsertId();
	            } else {
	                $id_pessoa = $array['id_pessoa'];
	            }

	            if (empty($id_pessoa)) {
	                throw new Exception("Id da Pessoa não identificado");
	            }

	            // get _turmas
	            $turma = self::getTurmaErp($pes['outCodTurma']);
	            if (empty($turma)) {

	            	$r = $this->turmas($pes['outCodColegio'], true);
	            	if (empty($r['status'])) {
	                	throw new Exception("Turma não identificada: ". $pes['outCodTurma']);
	            	}

	            	$turma = self::getTurmaErp($pes['outCodTurma']);
	            }

	            if ( !isset($alunosSie[$turma['id_turma']]) ) {
	    			$alunosSie[$turma['id_turma']] = self::alunosTurmaErp($turma['id_turma']);
	            }

	            switch ($pes['outSituacao']) {
	                case 'Cursando':
	                    $situacao = "'Frequente'";
	                    $fk_id_tas = 0;
	                    $fk_id_sit_sed = 1;
	                    break;

	                case 'Não Comp. - Fora Prazo':
	                    $situacao = "'Não Compareceu'";
	                    $fk_id_tas = 5;
	                    $fk_id_sit_sed = 9;
	                    break;

	                case 'Remanejado':
	                    $situacao = "'Remanejamento'";
	                    $fk_id_tas = 9;
	                    $fk_id_sit_sed = 14;
	                    break;

	                case 'Reclassificado':
	                    $situacao = "'Reclassificado'";
	                    $fk_id_tas = 3;
	                    $fk_id_sit_sed = 6;
	                    break;

	                case 'Transferido':
	                    $situacao = "'Transferido Escola'";
	                    $fk_id_tas = 1;
	                    $fk_id_sit_sed = 2;
	                    break;

	                default:
	                    $situacao = 'null';
	                    $fk_id_tas = 'null';
	                    $fk_id_sit_sed = 0;

	                    $agora = new DateTime();
	                    error_log( $agora->format("Y-m-d H:i:s") . " - $id_pessoa - situacao null\n". print_r($pes, true) ."\n\n", 3, "/var/log/log-sp.log" );
	                    break;
	            }

	            $raSed = intval($pes['outNumRa']);
                if (!empty($alunosSie[$turma['id_turma']][$raSed])) {
                    $key = key($alunosSie[$turma['id_turma']][$raSed]);
                    $aluno = $alunosSie[$turma['id_turma']][$raSed][$key];
                    unset($alunosSie[$turma['id_turma']][$raSed][$key]);
                    if (!current($alunosSie[$turma['id_turma']][$raSed])) {
                        unset($alunosSie[$turma['id_turma']][$raSed]);
                    }
                } else {
                    $aluno = [];
                }

                if (!empty($aluno))
                {
                	// echo '<pre>';
                	// print_r($aluno);
                	// echo '</pre>';
                	$sqlUp = '';
                    if ($fk_id_sit_sed != $aluno['fk_id_sit_sed']) 
                    {
                        $sqlUp .= " , situacao = $situacao, "
                                . " fk_id_sit_sed = $fk_id_sit_sed, "
                                . " fk_id_tas = $fk_id_tas ";
                    }

                    $sql = "UPDATE ge_turma_aluno "
                            . " SET chamada = '" . $pes['outNumAlunoSalaDeAula'] . "' "
                            . $sqlUp
                            . " WHERE id_turma_aluno = " . $aluno['id_turma_aluno'];
                    // print_r($sql);
                	
                    $query = pdoSis::getInstance()->query($sql);

				} else {

		            $sql = "REPLACE INTO `ge_turma_aluno`("
		            . " codigo_classe, fk_id_turma, periodo_letivo, fk_id_pessoa, fk_id_inst, chamada, situacao, dt_matricula, dt_gdae, fk_id_sit_sed, fk_id_tas"
		            . " ) "
		            . " VALUES ( "
		            . "'" . $turma['codigo'] . "', " //codigo_classe
		            . "'" . $turma['id_turma'] . "', " //fk_id_turma
		            . "'" . $turma['periodo_letivo'] . "', " //periodo_letivo
		            . "'" . $id_pessoa . "', " //fk_id_pessoa
		            . "'" . $turma['fk_id_inst'] . "', " //fk_id_inst
		            . "'" . $pes['outNumAlunoSalaDeAula'] . "', " //chamada
		            . $situacao . ", " //situacao
		            . "'" . date("Y-m-d") . "', " //dt_matricula
		            . "'" . date("Y-m-d") . "', " //dt_gdae
		            . $fk_id_sit_sed .", " //fk_id_sit_sed
		            . $fk_id_tas //fk_id_tas
		            . ")";

		            // print_r($sql);
		            // echo "<br>";
		            $query = pdoSis::getInstance()->query($sql);

		        }

	        } catch (Exception $e) {
	        	$agora = new DateTime();
	            error_log( $agora->format("Y-m-d H:i:s") . " - ". @$id_pessoa ." - error \n". print_r($pes, true) ."\n\n", 3, "/var/log/log-sp.log" );

	            echo "<span style='color: #f00'>";
	            var_dump($e->getMessage(), $e->getCode(), $pes);
	            echo "</span><br>";
	        }

	        $alunosSie = null;
	        $aluno = null;
	    }
	}

	public static function alunosTurmaErp($id_turma) {
        $fields = 'id_turma_aluno, chamada, situacao, fk_id_turma, fk_id_sit_sed, fk_id_ciclo_aluno '
                . ', id_pessoa, n_pessoa, dt_nasc, ra, ra_dig, ra_uf';
        $aS = sql::get(['ge_turma_aluno', 'pessoa'], $fields, ['fk_id_turma' => $id_turma, '>' => 'chamada']);
        foreach ($aS as $v) {
            $alunosSie[$v['ra']][] = $v;
        }
        if (!empty($alunosSie)) {
            return $alunosSie;
        } else {
            return [];
        }
    }

    public static function getTurmaErp($id_turma) {
        $sql = "SELECT id_turma, codigo, periodo_letivo, fk_id_inst, prodesp, fk_id_pl FROM ge_turmas WHERE id_turma = ". $id_turma;
        // print_r($sql);
        // echo "<br>";
        $query = pdoSis::getInstance()->query($sql);
        $turma = $query->fetch(PDO::FETCH_ASSOC);
        return !empty($turma) ? $turma : [];
    }
}