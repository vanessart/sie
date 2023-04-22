<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
https://homologacaointegracaosed.educacao.sp.gov.br/documentacao/CadastrodeAlunos/REST/
/**
 * Description of rest
 *
 * @author marco
 */

class rest {

    public static function relacaoClasses($codEscola, $anoLetivo, $db = NULL, $type = 'array') {
        $paramentos['inAnoLetivo'] = $anoLetivo;
        $paramentos['inCodEscola'] = $codEscola;

        $dados = rest::servico('/RelacaoAlunosClasse/RelacaoClasses', $paramentos, $db, $type);

        return $dados;
    }

    public static function formacaoClasse($codClasse, $db = NULL, $type = 'array') {
        $paramentos['inNumClasse'] = $codClasse;
        $dados = rest::servico('/RelacaoAlunosClasse/FormacaoClasse', $paramentos, $db, $type);

        return $dados;
    }

    /**
     * 
     * @param type $idEscola
     * @param type $tipoInscricao
     *          0 – Inscrição de Alunos por Transferência
      1 – Definição de Alunos para o 1º Ano do Ensino Fundamental
      2 – Definição de Alunos para o 6º Ano do Ensino Fundamental
      4 – Inscrição de Aluno Fora da Rede Pública (Ensino Fundamental)
      6 – Definição de Alunos para o Ensino Médio
      7 – Inscrição de Aluno Fora da Rede Pública (Ensino Médio)
      8 – Inscrição de Alunos por Deslocamento
      9 – Inscrição por Intenção de Transferência
     * @param type $dig
     * @param type $anoLetivo
     * @param type $db
     * @param type $type
     * @return type
     */
    public static function listarInscricoesEscola($idEscola, $tipoInscricao, $anoLetivo, $db = NULL, $type = 'array') {
        $paramentos['inCodEscola'] = $idEscola;
        $paramentos['inTipoInscricao'] = $tipoInscricao;
        $paramentos['inAnoLetivo'] = $anoLetivo;

        $dados = rest::servico('/Inscricao/ListarInscricoesAluno', $paramentos, $db, $type);

        return $dados;
    }

    public static function listarInscricoesAluno($ra, $uf, $dig = NULL, $anoLetivo = NULL, $db = NULL, $type = 'array') {
        $paramentos['inNumRA'] = $ra;
        $paramentos['inSiglaUFRA'] = $uf;
        $paramentos['inAnoLetivo'] = $anoLetivo;
        if ($dig) {
            $paramentos['inDigitoRA'] = $dig;
        }
        $dados = rest::servico('/Inscricao/ListarInscricoesAluno', $paramentos, $db, $type);

        return $dados;
    }

    /**
     * 
     * @param type $ra
     * @param type $uf
     * @param type $dig
     * @param type $numClasse
     * @param type $situacao 
     *      0 ATIVO / ENCERRADO
      2 ABANDONOU
      1 TRANSFERIDO
      31 BAIXA – TRANSFERÊNCIA
      19 TRANSFERIDO - CEEJA / EAD
      16 TRANSFERIDO (CONVERSÃO DO ABANDONO)
      10 REMANEJAMENTO
      17 REMANEJADO (CONVERSÃO DO ABANDONO)
      6 CESSÃO POR OBJETIVOS ATINGIDOS
      7 CESSÃO POR NÃO FREQUÊNCIA
      8 CESSÃO POR TRANSFERÊNCIA/REMANEJAMENTO
      9 CESSÃO POR DESISTÊNCIA
      11 CESSÃO POR EXAME
      12 CESSÃO POR NÚMERO REDUZIDO DE ALUNOS
      13 CESSÃO POR FALTA DE DOCENTE
      14 CESSÃO POR DISPENSA
      15 CESSÃO POR CONCLUSÃO DO CURSO
      4 FALECIDO
      5 NÃO COMPARECIMENTO
      18 NÃO COMPARECIMENTO / FORA DO PRAZO
      20 NÃO COMPARECIMENTO - CEEJA / EAD
      3 RECLASSIFICADO
     * @param type $db
     * @param type $type
     * @return type
     */
    public static function exibirMatriculaClasseRA($ra, $uf, $dig = NULL, $numClasse = NULL, $situacao = NULL, $db = NULL, $type = 'array') {
        $paramentos['inNumRA'] = $ra;
        $paramentos['inSiglaUFRA'] = $uf;
        $paramentos['inNumClasse'] = $numClasse;
        $paramentos['inSituacao'] = $situacao;
        if ($dig) {
            $paramentos['inDigitoRA'] = $dig;
        }
        $dados = rest::servico('/Matricula/ExibirMatriculaClasseRA', $paramentos, $db, $type);

        return $dados;
    }

    public static function listarMatriculasRA($ra, $uf, $dig = NULL, $db = NULL, $type = 'array') {
        $paramentos['inNumRA'] = $ra;
        $paramentos['inSiglaUFRA'] = $uf;
        if ($dig) {
            $paramentos['inDigitoRA'] = $dig;
        }
        $dados = rest::servico('/Matricula/ListarMatriculasRA', $paramentos, $db, $type);

        return $dados;
    }

    public static function exibirFichaAluno($ra, $uf, $dig = NULL, $db = NULL, $type = 'array') {
        $paramentos['inNumRA'] = $ra;
        $paramentos['inSiglaUFRA'] = $uf;
        if ($dig) {
            $paramentos['inDigitoRA'] = $dig;
        }
        $dados = rest::servico('/Aluno/ExibirFichaAluno', $paramentos, $db, $type);

        return $dados;
    }

    /**
     * Daqui para baixo são metodos interno da classe
     * @param type $servico
     * @param type $paramentos
     * @param type $db
     * @param type $type
     * @return type
     */
    public static function servico($servico, $paramentos, $db = NULL, $type = 'array') {
        $dados = rest::curlExec($servico, $paramentos, $type);

        if (!empty($dados)) {

            if ($db) {
                $dados = array_merge($dados, $paramentos);
                $mongo = new mongoCrude('Gdae');
                $servico = explode('/', $servico);
                $servico = end($servico);
                $mongo->update($servico, $paramentos, $dados);
            }
            return $dados;
        }
    }

    public static function curlExec($servico, $paramentos, $type = 'array') {
        $pass = 0;
        if (is_array($paramentos)) {
            $paramentos = http_build_query($paramentos);
        }
        // if (empty($_SESSION['rest']['token'])) {
            $mongo = new mongoCrude('Gdae');
            $d = (array) $mongo->query('setup', ['id_set' => '1']);
            if (empty($d)) {
                echo "Gdae: Cliente não configurado";
                return;
            }
            $d = $d[0];
            // $_SESSION['rest']['token'] = $d['token'];
        // }

        while ($pass < 3) {
            $curl = curl_init();
            $url = SED_URL . $servico . '?' . $paramentos;
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Accept: */*",
                    "Authorization: Bearer " . $d['token'],
                    "Cache-Control: no-cache",
                    "Connection: keep-alive",
                    "Content-Type: application/json; charset=UTF-8",
                    "User-Agent: SiebSed",
                    "accept-encoding: gzip, deflate",
                    "cache-control: no-cache"
                ),
            ));

            $response = curl_exec($curl);

            $err = curl_error($curl);

            curl_close($curl);

            $teste = json_decode($response, true);
            if ($err || count($teste) == 1 || !empty($teste['outErro'])) {
                sleep(10);
                rest::setup();
                $pass++;
            } else {
                $pass = 3;
            }
        }

        if ($err) {
            echo "cURL Error #:" . $err;
        } elseif ($type == 'array') {
            return json_decode($response, true);
        } elseif ($type == 'obj') {
            return json_decode($response);
        } elseif ($type == 'json') {
            return $response;
        }
    }

    public static function setup() {
        $response = rest::token();
        if (!empty($response->outAutenticacao)) {
           // $_SESSION['rest']['token'] = $response->outAutenticacao;
            $mongo = new mongoCrude('Gdae');
            $mongo->update('setup', ['id_set' => '1'], ['token' => $response->outAutenticacao]);
        }
    }

    public static function token() {
        $Authorization = base64_encode(SED_USER . ':' . SED_PASSWORD);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => SED_URL . '/Usuario/ValidarUsuario',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Authorization: Basic " . $Authorization,
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Content-Type: application/json; charset=UTF-8",
                "User-Agent: SiebSed",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);

        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return json_decode($response);
        }
    }

}
