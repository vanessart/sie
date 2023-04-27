<?php

class integracao {

	public array $_data;
	protected $token = null;
	protected $dadosCLI = null;
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
			$this->cliente = new $class();

		} catch (Exception $e) {
			return self::defaultReturnFail($e);
		}
	}

	public function __set($key, $value)
	{
		$this->_data[$key] = $value;
	}

	public function __get($key)
	{
		return $this->_data[$key];
	}

	public function setDados($data = []){
		
		if ( is_null($data) ) {
			throw new Exception("Nenhum dado configurado", 1004);
		}

		foreach ($data as $key => $value) {
			$this->$key = $value;
		}

		// echo '<pre>';var_dump($this);die();
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

			// atribui os dados do endpoint de autenticacão
			$da = $this->cliente->dadosAuth();
			// echo '<pre>';var_dump($da);die();

			$dados = $this->setDados($da);
			// var_dump($dados);die();
			$r = $this->execCurl($dados);
			var_dump('zzz', $r);die();
			$ret = self::defaultReturn();

		} catch (Exception $e) {
			$ret = self::defaultReturnFail($e);
		}

		return $ret;
	}

	public function escolas()
	{
		// code...
	}

	public function turmas()
	{
		// code...
	}

	public function alunos()
	{
		try {
			$a = $this->auth();
			if (isset($a['status']) && $a['status'] === false) {
				throw new Exception($a['message'], $a['code']);
			}

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
		return $this->token;
	}

	public function execCurl($dados = [])
	{
		// echo '<pre>';var_dump($this);die();

		//
		$ret = file_get_contents_by_curl($this->_data['url'] . $this->_data['endpoint'], $this->_data['method'], $this->_data['header'], $dados);
		echo '<pre>';var_dump($this->_data['url'] . $this->_data['endpoint'], $this->_data['method'], $this->_data['header'], $dados);
		echo '<pre>';var_dump($ret);die();
		if (empty($ret)) {
			throw new Exception("API ". $this->_data['name'] ." não retornou dados", 1);
		}

		// $r = json_decode($ret);
		// if (empty($r)) {
		// 	throw new Exception("API Login retornou falha. \n". print_r($ret, true), 1);
		// }

		// if (empty($r->status)) {
		// 	throw new Exception($r->msg ?? $r->error, 1);
		// }
	}

	public static function defaultReturn()
	{
		$ret = [
			'status' => true,
			'message' => 'Sucesso',
			'code' => 0,
		];

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

}