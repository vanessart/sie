<?php

class integracao {

	protected $method = 'GET';
	protected $url = '';
	protected $endpoint = '';
	protected $name = '';
	protected $token = null;
	protected $dadosCLI = null;
	protected $cliente = null;

	public function __construct(){
		try {
			$this->dadosCLI = $this->getDadosCLI();

			if ( is_null($this->dadosCLI) ) {
				throw new Exception("Cliente não Configurado", 1001);
			}

			if ( is_null($this->dadosCLI['class']) ) {
				throw new Exception("Class Cliente não Configurado", 1002);
			}

			$class = $this->dadosCLI['class'];
			$this->cliente = new $class();

		} catch (Exception $e) {
			return self::defaultReturnFail($e);
		}

		$this->url = $this->dadosCLI['url'];
	}

	public function auth()
	{
		try {
			// atribui os dados do endpoint de autenticacão
			$dados = $this->cliente::dadosAuth();

			$r = $this->execCurl($dados);
			if ()
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
		// code...
	}

	public function getDadosCLI()
	{
		if ($this->dadosCLI === null) {
			// identifica o cliente e pega os dados para a integracao
			$this->dadosCLI = [
				'user' => '',
				'pass' => '',
				'grant_type' => '',
				'ep_Auth' => '',
			]
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
		//
		$ret = file_get_contents_by_curl($this->url . $this->endpoint, $this->method, $this->contentType, $dados);
		if (empty($ret)) {
			throw new Exception("API ". $this->name ." não retornou dados", 1);
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