<?php

class santanaParnaiba extends integracao {
	public function __construct()
	{
		// $this->name = $this->dadosCLI[$element]['name'] ?? null;
		// $this->endpoint = $this->dadosCLI[$element]['endpoint'] ?? null;
		// $this->method = $this->dadosCLI[$element]['method'] ?? null;
		$this->url = "https://intranet.santanadeparnaiba.sp.gov.br/";
		// $this->header = $this->dadosCLI[$element]['header'] ?? null;
		$this->UserAgent = "User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 16_7_14 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148";
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
			'endpoint' => "APIFramework/token",
			'method' => "POST",
			'header' => [
				"Content-Type: application/x-www-form-urlencoded", 
				$this->UserAgent
			],
			'dados' => [
				"username" => "59959338000118",
				"password" => "575uYp+K",
				"grant_type" => "password"
			],
		];
	}

	public function dadosEscolas()
	{
		return [
			'name' => "Escolas",
			'url' => $this->url,
			'endpoint' => "SisEduc-API/integracao/lm/colegio",
			'method' => "GET",
			'header' => [ "Authorization: Bearer ".  $this->getToken(), $this->UserAgent ],
			'dados' => [],
		];
	}

	public function dadosTurmas($inCodColegio = null)
	{
		$dados = !empty($inCodColegio) ? ['inCodColegio' => $inCodColegio] : [];
		return [
			'name' => "Turmas",
			'url' => $this->url,
			'endpoint' => "SisEduc-API/integracao/lm/turma",
			'method' => "GET",
			'header' => [ "Authorization: Bearer ".  $this->getToken(), $this->UserAgent ],
			'dados' => $dados,
		];
	}

	public function dadosAlunos($inCodColegio = null, $inNomeAluno = null, $inRa = null)
	{
		$dados = [];
		if (!empty($inCodColegio)) { $dados['inCodColegio'] = $inCodColegio; }
		if (!empty($inNomeAluno)) { $dados['inNomeAluno'] = $inNomeAluno; }
		if (!empty($inRa)) { $dados['inRa'] = $inRa; }
		return [
			'name' => "Alunos",
			'url' => $this->url,
			'endpoint' => "SisEduc-API/integracao/lm/aluno",
			'method' => "GET",
			'header' => [ "Authorization: Bearer ".  $this->getToken(), $this->UserAgent ],
			'dados' => $dados,
		];
	}
}