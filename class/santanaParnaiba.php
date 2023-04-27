<?php

class santanaParnaiba extends integracao {
	public function __construct()
	{
		// $this->name = $this->dadosCLI[$element]['name'] ?? null;
		// $this->endpoint = $this->dadosCLI[$element]['endpoint'] ?? null;
		// $this->method = $this->dadosCLI[$element]['method'] ?? null;
		$this->url = "https://intranet.santanadeparnaiba.sp.gov.br/APIFramework/";
		// $this->header = $this->dadosCLI[$element]['header'] ?? null;

		// $dados = $this->dadosCLI[$element]['dados']	
	}

	public function getDadosCLI()
	{
		return [
			'url' => $this->url
		];
	}

	public static function getDataEndPoint($element)
	{

		if ( empty($this->dadosCLI[$element]) ) {
			throw new Exception("Elemento [$element] não configurado", 1003);
			return false;
		}

		$this->name = $this->dadosCLI[$element]['name'] ?? null;
		$this->endpoint = $this->dadosCLI[$element]['endpoint'] ?? null;
		$this->method = $this->dadosCLI[$element]['method'] ?? null;
		$this->_url = $this->dadosCLI[$element]['url'] ?? null;
		$this->header = $this->dadosCLI[$element]['header'] ?? null;

		$dados = $this->dadosCLI[$element]['dados'] ?? null;

		return $dados;
	}

	public function dadosAuth()
	{
		return [
			'name' => "Autenticação",
			'url' => $this->url,
			'endpoint' => "token",
			'method' => "POST",
			'header' => [ "Content-Type: application/x-www-form-urlencoded" ],
			'dados' => [
				"username" => "59959338000118",
				"password" => "575uYp+K",
				"grant_type" => "password"
			],
		];
	}
}