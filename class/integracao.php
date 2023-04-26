<?php

class integracao {
	
	protected $method = 'GET';
	protected $url = '';
	protected $endpoint = '';
	protected $token = null;
	protected $dadosCLI = null;

	public function __construct(){
		$this->dadosCLI = $this->getDadosCLI();
	}

	public function auth()
	{
		try {
			$this->endpoint = $this->dadosCLI['ep_Auth'];

			$r = $this->execCurl();
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

	public function execCurl($dados)
	{
		//
		$ret = file_get_contents_by_curl($this->url, $this->method, $dados);
		if (empty($ret)) {
			throw new Exception("API não retornou dados", 1);
			// \n Por favor contacte o Dep. de Informática
		}

		$r = json_decode($ret);
		if (empty($r)) {
			throw new Exception("API Login retornou falha. \n". print_r($ret, true), 1);
		}

		if (empty($r->status)) {
			throw new Exception($r->msg ?? $r->error, 1);
		}
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