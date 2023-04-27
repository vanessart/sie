<?php

class santanaParnaiba extends integracao {
	public function __construct()
	{
		// $this->name = $this->dadosCLI[$element]['name'] ?? null;
		// $this->endpoint = $this->dadosCLI[$element]['endpoint'] ?? null;
		// $this->method = $this->dadosCLI[$element]['method'] ?? null;
		$this->url = "https://intranet.santanadeparnaiba.sp.gov.br/APIFramework/";
		// $this->contentType = $this->dadosCLI[$element]['contenttype'] ?? null;

		// $dados = $this->dadosCLI[$element]['dados']	
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
		$this->url = $this->dadosCLI[$element]['url'] ?? null;
		$this->contentType = $this->dadosCLI[$element]['contenttype'] ?? null;

		$dados = $this->dadosCLI[$element]['dados'] ?? null;

		return $dados;
	}

	public static function dadosAuth()
	{
		$this->name = "Autenticação";
		$this->endpoint = "token";
		$this->method = "POST";
		$this->contentType = null;

		$dados = $this->dadosCLI[$element]['dados'] ?? null;
	}
}