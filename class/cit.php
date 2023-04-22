<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cit
 * POST api/funcionarios
 * POST api/funcionarios/dependentes
 * POST api/funcionarios/formacoes
 * POST api/funcionarios/cargoorigem
 * POST api/funcionarios/salarioorigem
 * POST api/funcionarios/cargos
 * POST api/funcionarios/salarios
 * POST api/funcionarios/cargahoraria
 * POST api/funcionarios/situacoes
 * POST api/funcionarios/remanejamentos
 * POST api/funcionarios/ferias
 * POST api/funcionarios/suspestagioprob
 * POST api/funcionarios/readaptacoes
 * POST api/funcionarios/advertencias
 * POST api/funcionarios/suspensoes
 * POST api/funcionarios/temposervico
 * @author marco
 */
class cit {

    public static function funcionarios($IdFuncionario = null, $DataFiltro = null, $api = null, $db = NULL, $type = 'array') {
        if ($IdFuncionario) {
            $paramentos['IdFuncionario'] = $IdFuncionario;
        }
        if ($DataFiltro) {
            $paramentos['DataFiltro'] = $DataFiltro;
        }
        $dados = cit::servico('/api/funcionarios' . ($api ? '/' . $api : null), $paramentos, $db, $type);

        return $dados;
    }

    public static function parameters($parameters) {
        if ($parameters) {
            foreach ($parameters as $k => $v) {
                $parArr[] = $k . '=' . $v;
            }

            return '?' . (implode('&', $parArr));
        }
    }

    public static function url() {
        return 'https://servicos.barueri.sp.gov.br/rhoris.educ';
    }

    public static function token() {
        $client_id = 'd2e84f9167484e608993ab69ab0ff1bc';
        $client_secret = "o4ccFfNBd9bKabPANDOaypkC0WXZ2GtfziVvopX13t4";
        $url = cit::url() . "/oauth/token";
        $parameters = ['client_id' => $client_id, 'client_secret' => $client_secret, 'grant_type' => 'client_credentials'];


        $options = array('http' => array(
                'header' => 'Content-Type: application/x-www-form-urlencoded\r\n',
                'method' => 'POST',
                'content' => http_build_query($parameters)
        ));

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return json_decode($result);
    }

    public static function setup() {
        $response = cit::token();

        if (!empty($response->access_token)) {
            $_SESSION['cit']['token'] = $response->access_token;
            $mongo = new mongoCrude('Cit');
            $mongo->update('setup', ['id_set' => '1'], ['token' => $response->access_token, 'data' => date("Y-m-d"), 'time' => date("H:i:s")]);


            return ['token' => $response->access_token, 'data' => date("Y-m-d"), 'time' => date("H:i:s")];
        }
    }

    public static function curlExec($servico, $parameters, $type = 'array') {
        if ( empty($_SESSION['cit']) ) {
            return null;
        }

        $url = cit::url() . $servico . cit::parameters($parameters);

        $curl = curl_init($url);
        $headr[] = 'Content-length: 0';
        $headr[] = 'Content-Type: text/xml; charset=utf-8';
        $headr[] = 'Authorization: Bearer ' . $_SESSION['cit']['token'];
        $headr[] = "Cache-Control: max-age=0";
        $headr[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $headr[] = "Accept-Language: en-us,en;q=0.5";
        $postRequest = array();
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postRequest);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headr);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, True);
        $return = iconv("Windows-1252", "UTF-8", curl_exec($curl));
        curl_close($curl);

        return json_decode($return);
    }

    public static function servico($servico, $paramentos, $db = NULL, $type = 'array') {
        $dados = cit::curlExec($servico, $paramentos, $type);

        if (!empty($dados)) {

            if ($db) {
                $dados = array_merge($dados, $paramentos);
                $mongo = new mongoCrude('Cit');
                $servico = explode('/', $servico);
                $servico = end($servico);
                $mongo->update($servico, $paramentos, $dados);
            }
            return $dados;
        }
    }

}
