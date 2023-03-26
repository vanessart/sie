<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of gdae
 * user = sed12000g
 * password =  
 * 
 * pm12000 n/ Digital28
 * @author marco
 */
class gdae {

    //  private $_password = 'q5v5wi20:al';
    private $_wsdl = "https://integracaosed.educacao.sp.gov.br/educacao/Sec.BS.SecretariaMunicipal.CLS?WSDL=1";
//   private $_wsdl = "https://homologacaointegracaosed.educacao.sp.gov.br/educacao/Sec.BS.SecretariaMunicipal.CLS?WSDL=1";
    private $_location = "https://integracaosed.educacao.sp.gov.br/educacao/Sec.BS.SecretariaMunicipal.CLS";
//    private $_location = "https://homologacaointegracaosed.educacao.sp.gov.br/educacao/Sec.BS.SecretariaMunicipal.CLS";
    //   private $_wsdl = "https://gdaenet.edunet.sp.gov.br:57773/educacao/Sec.BS.SecretariaMunicipal.cls?wsdl=1";
//    private $_location = "https://gdaenet.edunet.sp.gov.br:57773/educacao/Sec.BS.SecretariaMunicipal.cls";
    private $_user = 'SME206';
    private $_password = 'p4u4xh19;zk';

    private function exec($function, $arguments) {
        $arguments[$function]['Usuario'] = $this->_user;
        $arguments[$function]['Senha'] = $this->_password;

        try {
            $client = new SoapClient($this->_wsdl);
            $options = array('location' => $this->_location);
            $result = $client->__soapCall($function, $arguments, $options);
        } catch (Exception $ex) {
            tool::alert('Erro Prodesp.  Tente mais tarde');
            $_SESSION['tmp']['erroProdesp']=1;
        }


        if (!empty($result->outErro)) {
            return $result->outErro;
        } elseif (!@empty(current(current($result))->outErro)) {
            return current(current($result))->outErro;
        } else {
            if(!empty($result)){
            return (array) $result;
            }
        }
    }

//############################## Manual ########################################

    /**
     * 
     * As consultas ocorrerão a partir do RA do aluno. O retorno do serviço serão os dados da ficha ou uma relação de alunos
     * que se enquadraram na busca fonética
     */
    public function ConsultarFichaAlunoRa($ra, $digitoRa, $ufRa) {
        $function = 'ConsultarFichaAluno';
        $arguments = array('ConsultarFichaAluno' => array(
                'ConsultaRA' => [
                    'inRA' => $ra,
                    'inDigitoRA' => $digitoRa,
                    'inUF' => $ufRa
                ]
        ));
        $result = $this->exec($function, $arguments);

        return $result;
    }

    /**
     * As consultas ocorrerão a partir da busca fonética utilizando o nome do aluno, nome
     * da mãe e data de nascimento. O retorno do serviço serão os dados da ficha ou uma relação de alunos
     * que se enquadraram na busca fonética
     */
    public function ConsultarFichaAlunoFonetica($nomeAluno = NULL, $diaNascimento = NULL, $mesNascimento = NULL, $anoNascimento = NULL, $nomeMae = NULL, $nomeSocial = NULL) {
        $function = 'ConsultarFichaAluno';
        $arguments = array('ConsultarFichaAluno' => array(
                'ConsultaFonetica' => [
                    'inNomeAluno' => $nomeAluno,
                    'inDiaNascimento' => $diaNascimento,
                    'inMesNascimento' => $mesNascimento,
                    'inAnoNascimento' => $anoNascimento,
                    'inNomeMae' => $nomeMae,
                    'inNomeSocial' => $nomeSocial
                ]
        ));
        $result = $this->exec($function, $arguments);

        return $result;
    }

//############################## Manual ########################################

    public function AlterarColetaClasse(
            $NumClasse, $CapacidadeFisica, $CursoSemestral1, $CursoSemestral2, $DiaInicioAula, $DiaTerminoAula, $CodigoINEP, $HoraFinal, $HoraInicial, $MesInicioAula, $MesTerminoAula, $NumeroSala, $SerieAno, $TipoClasse, $TipoEnsino, $Turma, $Turno, $Ano, $DiasSemana, $ProgMaisEducacao, $AEE, $ATC, $FlagConvenioEst) {
        $function = 'AlterarColetaClasse';
        $arguments = array('AlterarColetaClasse' => array(
                'inNumClasse' => $NumClasse,
                'inCapacidadeFisica' => $CapacidadeFisica,
                'inCursoSemestral1' => $CursoSemestral1,
                'inCursoSemestral2' => $CursoSemestral2,
                'inDiaInicioAula' => $DiaInicioAula,
                'inDiaTerminoAula' => $DiaTerminoAula,
                'inCodigoINEP' => $CodigoINEP,
                'inHoraFinal' => $HoraFinal,
                'inHoraInicial' => $HoraInicial,
                'inMesInicioAula' => $MesInicioAula,
                'inMesTerminoAula' => $MesTerminoAula,
                'inNumeroSala' => $NumeroSala,
                'inSerieAno' => $SerieAno,
                'inTipoClasse' => $TipoClasse,
                'inTipoEnsino' => $TipoEnsino,
                'inTurma' => $Turma,
                'inTurno' => $Turno,
                'inAno' => $Ano,
                'inDiasSemana' => $DiasSemana,
                'inProgMaisEducacao' => $ProgMaisEducacao,
                'inAEE' => $AEE,
                'inATC' => $ATC,
                'inFlagConvenioEst' => $FlagConvenioEst
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function AlterarDadosPessoaisFichaAluno(
            $RA, $AnoNascimento, $BolsaFamilia, $CorRaca, $DiaNascimento, $MesNascimento, $NecEspecial, $NomeAluno, $NomeMae, $NomePai, $Sexo, $AltasHabSuperdotacao, $AutistaClassico, $BaixaVisao, $Cegueira, $FisicaCadeirante, $FisicaOutros, $FisicaParalisiaCerebral, $Intelectual, $Multipla, $SindromeAsperger, $SindromeDown, $SindromeRett, $SurdezLeveModerada, $SurdezSeveraProfunda, $Surdocegueira, $TransDesintegrativoInf, $Gemeo, $IrmaoRA, $Permanente, $Temporaria, $MobilidadeReduzida, $DigitoRA, $UFRA, $AuxilioLeitor, $AuxilioTranscricao, $GuiaInterprete, $InterpreteLibras, $LeituraLabial, $Nenhum, $ProvaBraile, $ProvaAmpliada, $Tam16, $Tam20, $Tam24, $NomeSocial, $Quilombola, $Email, $IrmaoUFRA, $IrmaoDigitoRA) {
        $function = 'AlterarDadosPessoaisFichaAluno';
        $arguments = array('AlterarDadosPessoaisFichaAluno' => array(
                'inRA' => $RA,
                'inAnoNascimento' => $AnoNascimento,
                'inBolsaFamilia' => $BolsaFamilia,
                'inCorRaca' => $CorRaca,
                'inDiaNascimento' => $DiaNascimento,
                'inMesNascimento' => $MesNascimento,
                'inNecEspecial' => $NecEspecial,
                'inNomeAluno' => $NomeAluno,
                'inNomeMae' => $NomeMae,
                'inNomePai' => $NomePai,
                'inSexo' => $Sexo,
                'inAltasHabSuperdotacao' => $AltasHabSuperdotacao,
                'inAutistaClassico' => $AutistaClassico,
                'inBaixaVisao' => $BaixaVisao,
                'inCegueira' => $Cegueira,
                'inFisicaCadeirante' => $FisicaCadeirante,
                'inFisicaOutros' => $FisicaOutros,
                'inFisicaParalisiaCerebral' => $FisicaParalisiaCerebral,
                'inIntelectual' => $Intelectual,
                'inMultipla' => $Multipla,
                'inSindromeAsperger' => $SindromeAsperger,
                'inSindromeDown' => $SindromeDown,
                'inSindromeRett' => $SindromeRett,
                'inSurdezLeveModerada' => $SurdezLeveModerada,
                'inSurdezSeveraProfunda' => $SurdezSeveraProfunda,
                'inSurdocegueira' => $Surdocegueira,
                'inTransDesintegrativoInf' => $TransDesintegrativoInf,
                'inGemeo' => $Gemeo,
                'inIrmaoRA' => $IrmaoRA,
                'inPermanente' => $Permanente,
                'inTemporaria' => $Temporaria,
                'inMobilidadeReduzida' => $MobilidadeReduzida,
                'inDigitoRA' => $DigitoRA,
                'inUFRA' => $UFRA,
                'inAuxilioLeitor' => $AuxilioLeitor,
                'inAuxilioTranscricao' => $AuxilioTranscricao,
                'inGuiaInterprete' => $GuiaInterprete,
                'inInterpreteLibras' => $InterpreteLibras,
                'inLeituraLabial' => $LeituraLabial,
                'inNenhum' => $Nenhum,
                'inProvaBraile' => $ProvaBraile,
                'inProvaAmpliada' => $ProvaAmpliada,
                'inTam16' => $Tam16,
                'inTam20' => $Tam20,
                'inTam24' => $Tam24,
                'inNomeSocial' => $NomeSocial,
                'inQuilombola' => $Quilombola,
                'inEmail' => $Email,
                'inIrmaoUFRA' => $IrmaoUFRA,
                'inIrmaoDigitoRA' => $IrmaoDigitoRA
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function AlterarDocumentosFichaAluno(
            $RA, $AnoEmissao, $AnoEntBrasil, $DiaEmissao, $DiaEntBrasil, $MesEmissao, $MesEntBrasil, $MunicipioNasc, $Nacionalidade, $NumNIS, $PaisOrigem, $RGRNE01, $RGRNE02, $RGRNE03, $UFNasc, $CPF, $DigitoRA, $UFRA) {
        $function = 'AlterarDocumentosFichaAluno';
        $arguments = array('AlterarDocumentosFichaAluno' => array(
                'inRA' => $RA,
                'inAnoEmissao' => $AnoEmissao,
                'inAnoEntBrasil' => $AnoEntBrasil,
                'inDiaEmissao' => $DiaEmissao,
                'inDiaEntBrasil' => $DiaEntBrasil,
                'inMesEmissao' => $MesEmissao,
                'inMesEntBrasil' => $MesEntBrasil,
                'inMunicipioNasc' => $MunicipioNasc,
                'inNacionalidade' => $Nacionalidade,
                'inNumNIS' => $NumNIS,
                'inPaisOrigem' => $PaisOrigem,
                'inRGRNE01' => $RGRNE01,
                'inRGRNE02' => $RGRNE02,
                'inRGRNE03' => $RGRNE03,
                'inUFNasc' => $UFNasc,
                'inCPF' => $CPF,
                'inDigitoRA' => $DigitoRA,
                'inUFRA' => $UFRA
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function AlterarEnderecoFichaAluno(
            $RA, $Bairro, $Cep, $Cidade, $Complemento, $DDD, $FoneRecados, $FoneResidencial, $Logradouro, $NomeFoneRecado, $Numero, $TipoLogradouro, $UF, $DigitoRA, $UFRA, $SMS, $DDDCel, $FoneCel, $Latitude, $Longitude) {
        $function = 'AlterarEnderecoFichaAluno';
        $arguments = array('AlterarEnderecoFichaAluno' => array(
                'inRA' => $RA,
                'inBairro' => $Bairro,
                'inCep' => $Cep,
                'inCidade' => $Cidade,
                'inComplemento' => $Complemento,
                'inDDD' => $DDD,
                'inFoneRecados' => $FoneRecados,
                'inFoneResidencial' => $FoneResidencial,
                'inLogradouro' => $Logradouro,
                'inNomeFoneRecado' => $NomeFoneRecado,
                'inNumero' => $Numero,
                'inTipoLogradouro' => $TipoLogradouro,
                'inUF' => $UF,
                'inDigitoRA' => $DigitoRA,
                'inUFRA' => $UFRA,
                'inSMS' => $SMS,
                'inDDDCel' => $DDDCel,
                'inFoneCel' => $FoneCel,
                'inLatitude' => $Latitude,
                'inLongitude' => $Longitude
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function AlterarEnderecoIndicativo(
            $RA, $DigitoRA, $UFRA, $Bairro, $Cep, $Cidade, $DDD, $FoneResidencial, $Logradouro, $Numero, $Recados, $UF) {
        $function = 'AlterarEnderecoIndicativo';
        $arguments = array('AlterarEnderecoIndicativo' => array(
                'inRA' => $RA,
                'inDigitoRA' => $DigitoRA,
                'inUFRA' => $UFRA,
                'inBairro' => $Bairro,
                'inCep' => $Cep,
                'inCidade' => $Cidade,
                'inDDD' => $DDD,
                'inFoneResidencial' => $FoneResidencial,
                'inLogradouro' => $Logradouro,
                'inNumero' => $Numero,
                'inRecados' => $Recados,
                'inUF' => $UF
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function AssociarIrmao(
            $RA, $IrmaoRA, $IrmaoDigitoRA, $IrmaoUFRA, $DigitoRA, $UFRA) {
        $function = 'AssociarIrmao';
        $arguments = array('AssociarIrmao' => array(
                'inRA' => $RA,
                'inIrmaoRA' => $IrmaoRA,
                'inIrmaoDigitoRA' => $IrmaoDigitoRA,
                'inIrmaoUFRA' => $IrmaoUFRA,
                'inDigitoRA' => $DigitoRA,
                'inUFRA' => $UFRA
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function BaixarMatrFalecimentoRA(
            $RA, $AnoFalecimento, $DiaFalecimento, $MesFalecimento, $UFRA, $DigitoRA) {
        $function = 'BaixarMatrFalecimentoRA';
        $arguments = array('BaixarMatrFalecimentoRA' => array(
                'inRA' => $RA,
                'inAnoFalecimento' => $AnoFalecimento,
                'inDiaFalecimento' => $DiaFalecimento,
                'inMesFalecimento' => $MesFalecimento,
                'inUFRA' => $UFRA,
                'inDigitoRA' => $DigitoRA
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function BaixarMatriculaTransferencia(
            $DigitoRA, $RA, $UF, $DiaTransferencia, $MesTransferencia, $Motivo, $CodEscola) {
        $function = 'BaixarMatriculaTransferencia';
        $arguments = array('BaixarMatriculaTransferencia' => array(
                'inDigitoRA' => $DigitoRA,
                'inRA' => $RA,
                'inUF' => $UF,
                'inDiaTransferencia' => $DiaTransferencia,
                'inMesTransferencia' => $MesTransferencia,
                'inMotivo' => $Motivo,
                'inCodEscola' => $CodEscola
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function CancelarEncerramentoRendEscolar(
            $CodEscola, $Semestre) {
        $function = 'CancelarEncerramentoRendEscolar';
        $arguments = array('CancelarEncerramentoRendEscolar' => array(
                'inCodEscola' => $CodEscola,
                'inSemestre' => $Semestre
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function CancelarInscricaoAlunoPorDeslocamento(
            $RA, $DigitoRA, $UFRA, $Escola, $Ano) {
        $function = 'CancelarInscricaoAlunoPorDeslocamento';
        $arguments = array('CancelarInscricaoAlunoPorDeslocamento' => array(
                'inRA' => $RA,
                'inDigitoRA' => $DigitoRA,
                'inUFRA' => $UFRA,
                'inEscola' => $Escola,
                'inAno' => $Ano
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function CancelarInscricaoAlunoTransf(
            $RA, $DigitoRA, $UFRA, $Ano, $Escola) {
        $function = 'CancelarInscricaoAlunoTransf';
        $arguments = array('CancelarInscricaoAlunoTransf' => array(
                'inRA' => $RA,
                'inDigitoRA' => $DigitoRA,
                'inUFRA' => $UFRA,
                'inAno' => $Ano,
                'inEscola' => $Escola
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function CancelarInscricaoDefinicao(
            $RA, $DigitoRA, $UFRA, $Escola, $Ano, $Fase) {
        $function = 'CancelarInscricaoDefinicao';
        $arguments = array('CancelarInscricaoDefinicao' => array(
                'inRA' => $RA,
                'inDigitoRA' => $DigitoRA,
                'inUFRA' => $UFRA,
                'inEscola' => $Escola,
                'inAno' => $Ano,
                'inFase' => $Fase
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function CancelarIntencaoTransferencia(
            $RA, $DigitoRA, $UFRA, $Escola, $Ano) {
        $function = 'CancelarIntencaoTransferencia';
        $arguments = array('CancelarIntencaoTransferencia' => array(
                'inRA' => $RA,
                'inDigitoRA' => $DigitoRA,
                'inUFRA' => $UFRA,
                'inEscola' => $Escola,
                'inAno' => $Ano
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function CancelarTerminoDigitacao(
            $CodEscola) {
        $function = 'CancelarTerminoDigitacao';
        $arguments = array('CancelarTerminoDigitacao' => array(
                'inCodEscola' => $CodEscola
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ClassificarGerarNrChamadaClassePorEscola(
            $Ano, $CodigoEscola, $OrdemClassificacao, $TipoEnsino, $SerieAno) {
        $function = 'ClassificarGerarNrChamadaClassePorEscola';
        $arguments = array('ClassificarGerarNrChamadaClassePorEscola' => array(
                'inAno' => $Ano,
                'inCodigoEscola' => $CodigoEscola,
                'inOrdemClassificacao' => $OrdemClassificacao,
                'inTipoEnsino' => $TipoEnsino,
                'inSerieAno' => $SerieAno
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ClassificarGerarNumeroChamadaPorClasse(
            $Ano, $NumClasse, $OrdemClassificacao) {
        $function = 'ClassificarGerarNumeroChamadaPorClasse';
        $arguments = array('ClassificarGerarNumeroChamadaPorClasse' => array(
                'inAno' => $Ano,
                'inNumClasse' => $NumClasse,
                'inOrdemClassificacao' => $OrdemClassificacao
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultaClasseAlunoPorEscola(
            $AnoLetivo, $CodEscola, $Semestre, $SerieAno, $TipoEnsino, $Turma, $Turno) {
        $function = 'ConsultaClasseAlunoPorEscola';
        $arguments = array('ConsultaClasseAlunoPorEscola' => array(
                'inAnoLetivo' => $AnoLetivo,
                'inCodEscola' => $CodEscola,
                'inSemestre' => $Semestre,
                'inSerieAno' => $SerieAno,
                'inTipoEnsino' => $TipoEnsino,
                'inTurma' => $Turma,
                'inTurno' => $Turno
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultaFormacaoClasse(
            $NumClasse) {
        $function = 'ConsultaFormacaoClasse';
        $arguments = array('ConsultaFormacaoClasse' => array(
                'inNumClasse' => $NumClasse
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarCep(
            $Cep) {
        $function = 'ConsultarCep';
        $arguments = array('ConsultarCep' => array(
                'inCep' => $Cep
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarCepLogradouro(
            $NomeLocal, $NomeLogradouro) {
        $function = 'ConsultarCepLogradouro';
        $arguments = array('ConsultarCepLogradouro' => array(
                'inNomeLocal' => $NomeLocal,
                'inNomeLogradouro' => $NomeLogradouro
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarColetaClasse(
            $Ano, $NumClasse) {
        $function = 'ConsultarColetaClasse';
        $arguments = array('ConsultarColetaClasse' => array(
                'inAno' => $Ano,
                'inNumClasse' => $NumClasse
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarDefinicaoInscricaoRA(
            $RA, $DigitoRA, $UFRA, $DefinicaoInscricao) {
        $function = 'ConsultarDefinicaoInscricaoRA';
        $arguments = array('ConsultarDefinicaoInscricaoRA' => array(
                'inRA' => $RA,
                'inDigitoRA' => $DigitoRA,
                'inUFRA' => $UFRA,
                'inDefinicaoInscricao' => $DefinicaoInscricao
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarEscolaCIE(
            $CodEscola) {
        $function = 'ConsultarEscolaCIE';
        $arguments = array('ConsultarEscolaCIE' => array(
                'inCodEscola' => $CodEscola
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarIrmao(
            $RA, $UFRA, $DigitoRA) {
        $function = 'ConsultarIrmao';
        $arguments = array('ConsultarIrmao' => array(
                'inRA' => $RA,
                'inUFRA' => $UFRA,
                'inDigitoRA' => $DigitoRA
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarMatriculaClasseRA(
            $RA, $NumClasse, $DigitoRA, $UF, $Situacao) {
        $function = 'ConsultarMatriculaClasseRA';
        $arguments = array('ConsultarMatriculaClasseRA' => array(
                'inRA' => $RA,
                'inNumClasse' => $NumClasse,
                'inDigitoRA' => $DigitoRA,
                'inUF' => $UF,
                'inSituacao' => $Situacao
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarMatriculaPorClasse(
            $RA, $DigitoRA, $UF, $NumClasse, $Situacao) {
        $function = 'ConsultarMatriculaPorClasse';
        $arguments = array('ConsultarMatriculaPorClasse' => array(
                'inRA' => $RA,
                'inDigitoRA' => $DigitoRA,
                'inUF' => $UF,
                'inNumClasse' => $NumClasse,
                'inSituacao' => $Situacao
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarMatriculas(
            $RA, $DigitoRA, $UF) {
        $function = 'ConsultarMatriculas';
        $arguments = array('ConsultarMatriculas' => array(
                'inRA' => $RA,
                'inDigitoRA' => $DigitoRA,
                'inUF' => $UF
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarMatriculasRA(
            $RA, $DigitoRA, $UF) {
        $function = 'ConsultarMatriculasRA';
        $arguments = array('ConsultarMatriculasRA' => array(
                'inRA' => $RA,
                'inDigitoRA' => $DigitoRA,
                'inUF' => $UF
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarQuadroResumo(
            $CodEscola, $Ano) {
        $function = 'ConsultarQuadroResumo';
        $arguments = array('ConsultarQuadroResumo' => array(
                'inCodEscola' => $CodEscola,
                'inAno' => $Ano
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarRendPorNumeroClasse(
            $Ano, $NumClasse, $Semestre) {
        $function = 'ConsultarRendPorNumeroClasse';
        $arguments = array('ConsultarRendPorNumeroClasse' => array(
                'inAno' => $Ano,
                'inNumClasse' => $NumClasse,
                'inSemestre' => $Semestre
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarTotaisporEscola(
            $AnoLetivo, $CodEscola, $Semestre, $SerieAno, $TipoEnsino, $Turma, $Turno) {
        $function = 'ConsultarTotaisporEscola';
        $arguments = array('ConsultarTotaisporEscola' => array(
                'inAnoLetivo' => $AnoLetivo,
                'inCodEscola' => $CodEscola,
                'inSemestre' => $Semestre,
                'inSerieAno' => $SerieAno,
                'inTipoEnsino' => $TipoEnsino,
                'inTurma' => $Turma,
                'inTurno' => $Turno
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function DefinirAlunoEnsinoMedio(
            $RA, $DigitoRA, $UFRA, $Ano, $Turno) {
        $function = 'DefinirAlunoEnsinoMedio';
        $arguments = array('DefinirAlunoEnsinoMedio' => array(
                'inRA' => $RA,
                'inDigitoRA' => $DigitoRA,
                'inUFRA' => $UFRA,
                'inAno' => $Ano,
                'inTurno' => $Turno
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function EncerrarRendimentoEscolarPorCIE(
            $CodEscola, $Semestre) {
        $function = 'EncerrarRendimentoEscolarPorCIE';
        $arguments = array('EncerrarRendimentoEscolarPorCIE' => array(
                'inCodEscola' => $CodEscola,
                'inSemestre' => $Semestre
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function EstornarBaixaMatrTransf(
            $CodEscola, $RA, $SerieAno, $TipoEnsino, $UFRA, $DigitoRA) {
        $function = 'EstornarBaixaMatrTransf';
        $arguments = array('EstornarBaixaMatrTransf' => array(
                'inCodEscola' => $CodEscola,
                'inRA' => $RA,
                'inSerieAno' => $SerieAno,
                'inTipoEnsino' => $TipoEnsino,
                'inUFRA' => $UFRA,
                'inDigitoRA' => $DigitoRA
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function EstornarRegistroAbandono(
            $RA, $DiaRetorno, $MesRetorno, $CodEscola, $SerieAno, $TipoEnsino, $UFRA, $DigitoRA) {
        $function = 'EstornarRegistroAbandono';
        $arguments = array('EstornarRegistroAbandono' => array(
                'inRA' => $RA,
                'inDiaRetorno' => $DiaRetorno,
                'inMesRetorno' => $MesRetorno,
                'inCodEscola' => $CodEscola,
                'inSerieAno' => $SerieAno,
                'inTipoEnsino' => $TipoEnsino,
                'inUFRA' => $UFRA,
                'inDigitoRA' => $DigitoRA
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ExcluirColetaClasse(
            $NumClasse, $Ano) {
        $function = 'ExcluirColetaClasse';
        $arguments = array('ExcluirColetaClasse' => array(
                'inNumClasse' => $NumClasse,
                'inAno' => $Ano
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ExcluirIrmao(
            $RA, $IrmaoRA, $IrmaoDigitoRA, $IrmaoUFRA, $UFRA, $DigitoRA) {
        $function = 'ExcluirIrmao';
        $arguments = array('ExcluirIrmao' => array(
                'inRA' => $RA,
                'inIrmaoRA' => $IrmaoRA,
                'inIrmaoDigitoRA' => $IrmaoDigitoRA,
                'inIrmaoUFRA' => $IrmaoUFRA,
                'inUFRA' => $UFRA,
                'inDigitoRA' => $DigitoRA
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ExcluirMatricula(
            $RA, $DigitoRA, $UF, $NumClasse, $Ano, $SerieAno, $TipoEnsino) {
        $function = 'ExcluirMatricula';
        $arguments = array('ExcluirMatricula' => array(
                'inRA' => $RA,
                'inDigitoRA' => $DigitoRA,
                'inUF' => $UF,
                'inNumClasse' => $NumClasse,
                'inAno' => $Ano,
                'inSerieAno' => $SerieAno,
                'inTipoEnsino' => $TipoEnsino
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function IncluirColetaClasse(
            $CodEscola, $CodUnidade, $CapacidadeFisica, $CursoSemestral1, $CursoSemestral2, $DiaInicioAula, $DiaTerminoAula, $CodigoINEP, $HoraFinal, $HoraInicial, $MesInicioAula, $MesTerminoAula, $NumeroSala, $SerieAno, $TipoClasse, $TipoEnsino, $Turma, $Turno, $Ano, $DiasSemana, $ProgMaisEducacao, $AEE, $ATC, $FlagConvenioEst) {
        $function = 'IncluirColetaClasse';
        $arguments = array('IncluirColetaClasse' => array(
                'inCodEscola' => $CodEscola,
                'inCodUnidade' => $CodUnidade,
                'inCapacidadeFisica' => $CapacidadeFisica,
                'inCursoSemestral1' => $CursoSemestral1,
                'inCursoSemestral2' => $CursoSemestral2,
                'inDiaInicioAula' => $DiaInicioAula,
                'inDiaTerminoAula' => $DiaTerminoAula,
                'inCodigoINEP' => $CodigoINEP,
                'inHoraFinal' => $HoraFinal,
                'inHoraInicial' => $HoraInicial,
                'inMesInicioAula' => $MesInicioAula,
                'inMesTerminoAula' => $MesTerminoAula,
                'inNumeroSala' => $NumeroSala,
                'inSerieAno' => $SerieAno,
                'inTipoClasse' => $TipoClasse,
                'inTipoEnsino' => $TipoEnsino,
                'inTurma' => $Turma,
                'inTurno' => $Turno,
                'inAno' => $Ano,
                'inDiasSemana' => $DiasSemana,
                'inProgMaisEducacao' => $ProgMaisEducacao,
                'inAEE' => $AEE,
                'inATC' => $ATC,
                'inFlagConvenioEst' => $FlagConvenioEst
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function InformarTerminoDigitacao(
            $CodEscola) {
        $function = 'InformarTerminoDigitacao';
        $arguments = array('InformarTerminoDigitacao' => array(
                'inCodEscola' => $CodEscola
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function InscreverAlunoPorDeslocamento(
            $RA, $DigitoRA, $UFRA, $Escola, $Ano, $TipoInscricao, $Motivo, $DDDCel, $FoneCel, $SMS, $InteresseIntegral) {
        $function = 'InscreverAlunoPorDeslocamento';
        $arguments = array('InscreverAlunoPorDeslocamento' => array(
                'inRA' => $RA,
                'inDigitoRA' => $DigitoRA,
                'inUFRA' => $UFRA,
                'inEscola' => $Escola,
                'inAno' => $Ano,
                'inTipoInscricao' => $TipoInscricao,
                'inMotivo' => $Motivo,
                'inDDDCel' => $DDDCel,
                'inFoneCel' => $FoneCel,
                'inSMS' => $SMS,
                'inInteresseIntegral' => $InteresseIntegral
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function InscreverAlunotransferencia(
            $RA, $DigitoRA, $UFRA, $Ano, $Escola, $AnoSerie, $TipoEnsino, $Motivo, $InteresseIntegral) {
        $function = 'InscreverAlunotransferencia';
        $arguments = array('InscreverAlunotransferencia' => array(
                'inRA' => $RA,
                'inDigitoRA' => $DigitoRA,
                'inUFRA' => $UFRA,
                'inAno' => $Ano,
                'inEscola' => $Escola,
                'inAnoSerie' => $AnoSerie,
                'inTipoEnsino' => $TipoEnsino,
                'inMotivo' => $Motivo,
                'inInteresseIntegral' => $InteresseIntegral
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function InscricaoIntencaoTransferencia(
            $RA, $DigitoRA, $UFRA, $Escola, $SerieAno, $TipoEnsino, $Ano, $Bairro, $Cep, $Cidade, $Complemento, $DDD, $DDDCel, $FoneCel, $FoneRecados, $FoneResidencial, $Logradouro, $NomeRecados, $Numero, $SMS, $TipoLogradouro, $UF, $InteresseIntegral) {
        $function = 'InscricaoIntencaoTransferencia';
        $arguments = array('InscricaoIntencaoTransferencia' => array(
                'inRA' => $RA,
                'inDigitoRA' => $DigitoRA,
                'inUFRA' => $UFRA,
                'inEscola' => $Escola,
                'inSerieAno' => $SerieAno,
                'inTipoEnsino' => $TipoEnsino,
                'inAno' => $Ano,
                'inBairro' => $Bairro,
                'inCep' => $Cep,
                'inCidade' => $Cidade,
                'inComplemento' => $Complemento,
                'inDDD' => $DDD,
                'inDDDCel' => $DDDCel,
                'inFoneCel' => $FoneCel,
                'inFoneRecados' => $FoneRecados,
                'inFoneResidencial' => $FoneResidencial,
                'inLogradouro' => $Logradouro,
                'inNomeRecados' => $NomeRecados,
                'inNumero' => $Numero,
                'inSMS' => $SMS,
                'inTipoLogradouro' => $TipoLogradouro,
                'inUF' => $UF,
                'inInteresseIntegral' => $InteresseIntegral
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function LancarRendimentoEscolarClasse(
            $NumClasse, $Semestre, $Alunos) {
        $function = 'LancarRendimentoEscolarClasse';
        $arguments = array('LancarRendimentoEscolarClasse' => array(
                'inNumClasse' => $NumClasse,
                'inSemestre' => $Semestre,
                'inAlunos' => $Alunos
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function RealizarMatricAntecipadaFases(
            $CodEscola, $Ano, $Fase, $SerieAno, $TipoEnsino, $AnoEmissaoRG, $AnoNascimento, $BolsaFamilia, $CorRaca, $CPF, $DiaEmissaoRG, $DiaNascimento, $Gemeo, $MesEmissaoRG, $MesNascimento, $MobilidadeReduzida, $Nacionalidade, $NecessidadeEspecial, $NIS, $NomeAluno, $NomeMae, $NomePai, $PaisOrigem, $Sexo, $Permanente, $Temporaria, $AltasHabSuperdotacao, $AutistaClassico, $BaixaVisao, $Cegueira, $FisicaCadeirante, $FisicaOutros, $FisicaParalisiaCerebral, $Intelectual, $Multipla, $SindromeAsperger, $SindromeDown, $SindromeRett, $SurdezLeveModerada, $SurdezSeveraProfunda, $Surdocegueira, $TransDesintegrativoInf, $AnoEntBrasil, $DiaEntBrasil, $MesEntBrasil, $RGRNE01, $RGRNE02, $RGRNE03, $MunicipioNasc, $UFNasc, $AuxilioLeitor, $AuxilioTranscricao, $GuiaInterprete, $InterpreteLibras, $LeituraLabial, $Nenhum, $ProvaAmpliada, $ProvaBraile, $Tam16, $Tam20, $Tam24, $SMS, $FoneCel, $DDDCel, $Irmao, $IrmaoRA, $IrmaoDigitoRA, $IrmaoUFRA, $NomeSocial, $Email, $Quilombola, $NecesAtendNoturno, $InteresseEspanhol, $InteresseIntegral) {
        $function = 'RealizarMatricAntecipadaFases';
        $arguments = array('RealizarMatricAntecipadaFases' => array(
                'inCodEscola' => $CodEscola,
                'inAno' => $Ano,
                'inFase' => $Fase,
                'inSerieAno' => $SerieAno,
                'inTipoEnsino' => $TipoEnsino,
                'inAnoEmissaoRG' => $AnoEmissaoRG,
                'inAnoNascimento' => $AnoNascimento,
                'inBolsaFamilia' => $BolsaFamilia,
                'inCorRaca' => $CorRaca,
                'inCPF' => $CPF,
                'inDiaEmissaoRG' => $DiaEmissaoRG,
                'inDiaNascimento' => $DiaNascimento,
                'inGemeo' => $Gemeo,
                'inMesEmissaoRG' => $MesEmissaoRG,
                'inMesNascimento' => $MesNascimento,
                'inMobilidadeReduzida' => $MobilidadeReduzida,
                'inNacionalidade' => $Nacionalidade,
                'inNecessidadeEspecial' => $NecessidadeEspecial,
                'inNIS' => $NIS,
                'inNomeAluno' => $NomeAluno,
                'inNomeMae' => $NomeMae,
                'inNomePai' => $NomePai,
                'inPaisOrigem' => $PaisOrigem,
                'inSexo' => $Sexo,
                'inPermanente' => $Permanente,
                'inTemporaria' => $Temporaria,
                'inAltasHabSuperdotacao' => $AltasHabSuperdotacao,
                'inAutistaClassico' => $AutistaClassico,
                'inBaixaVisao' => $BaixaVisao,
                'inCegueira' => $Cegueira,
                'inFisicaCadeirante' => $FisicaCadeirante,
                'inFisicaOutros' => $FisicaOutros,
                'inFisicaParalisiaCerebral' => $FisicaParalisiaCerebral,
                'inIntelectual' => $Intelectual,
                'inMultipla' => $Multipla,
                'inSindromeAsperger' => $SindromeAsperger,
                'inSindromeDown' => $SindromeDown,
                'inSindromeRett' => $SindromeRett,
                'inSurdezLeveModerada' => $SurdezLeveModerada,
                'inSurdezSeveraProfunda' => $SurdezSeveraProfunda,
                'inSurdocegueira' => $Surdocegueira,
                'inTransDesintegrativoInf' => $TransDesintegrativoInf,
                'inAnoEntBrasil' => $AnoEntBrasil,
                'inDiaEntBrasil' => $DiaEntBrasil,
                'inMesEntBrasil' => $MesEntBrasil,
                'inRGRNE01' => $RGRNE01,
                'inRGRNE02' => $RGRNE02,
                'inRGRNE03' => $RGRNE03,
                'inMunicipioNasc' => $MunicipioNasc,
                'inUFNasc' => $UFNasc,
                'inAuxilioLeitor' => $AuxilioLeitor,
                'inAuxilioTranscricao' => $AuxilioTranscricao,
                'inGuiaInterprete' => $GuiaInterprete,
                'inInterpreteLibras' => $InterpreteLibras,
                'inLeituraLabial' => $LeituraLabial,
                'inNenhum' => $Nenhum,
                'inProvaAmpliada' => $ProvaAmpliada,
                'inProvaBraile' => $ProvaBraile,
                'inTam16' => $Tam16,
                'inTam20' => $Tam20,
                'inTam24' => $Tam24,
                'inSMS' => $SMS,
                'inFoneCel' => $FoneCel,
                'inDDDCel' => $DDDCel,
                'inIrmao' => $Irmao,
                'inIrmaoRA' => $IrmaoRA,
                'inIrmaoDigitoRA' => $IrmaoDigitoRA,
                'inIrmaoUFRA' => $IrmaoUFRA,
                'inNomeSocial' => $NomeSocial,
                'inEmail' => $Email,
                'inQuilombola' => $Quilombola,
                'inNecesAtendNoturno' => $NecesAtendNoturno,
                'inInteresseEspanhol' => $InteresseEspanhol,
                'inInteresseIntegral' => $InteresseIntegral
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function RealizarMatricAntecipadaRAFases(
            $CodEscola, $Ano, $Fase, $SerieAno, $TipoEnsino, $RA, $DigitoRA, $UFRA, $SMS, $FoneCel, $DDDCel, $Turno, $NecesAtendNoturno, $InteresseIntegral, $InteresseEspanhol) {
        $function = 'RealizarMatricAntecipadaRAFases';
        $arguments = array('RealizarMatricAntecipadaRAFases' => array(
                'inCodEscola' => $CodEscola,
                'inAno' => $Ano,
                'inFase' => $Fase,
                'inSerieAno' => $SerieAno,
                'inTipoEnsino' => $TipoEnsino,
                'inRA' => $RA,
                'inDigitoRA' => $DigitoRA,
                'inUFRA' => $UFRA,
                'inSMS' => $SMS,
                'inFoneCel' => $FoneCel,
                'inDDDCel' => $DDDCel,
                'inTurno' => $Turno,
                'inNecesAtendNoturno' => $NecesAtendNoturno,
                'inInteresseIntegral' => $InteresseIntegral,
                'inInteresseEspanhol' => $InteresseEspanhol
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function RealizarMatriculaAntecipada(
            $RA, $DigitoRA, $UFRA, $Ano, $DDDCel, $FoneCel, $SMS, $Irmao, $IrmaoRA, $IrmaoDigitoRA, $IrmaoUFRA, $InteresseIntegral) {
        $function = 'RealizarMatriculaAntecipada';
        $arguments = array('RealizarMatriculaAntecipada' => array(
                'inRA' => $RA,
                'inDigitoRA' => $DigitoRA,
                'inUFRA' => $UFRA,
                'inAno' => $Ano,
                'inDDDCel' => $DDDCel,
                'inFoneCel' => $FoneCel,
                'inSMS' => $SMS,
                'inIrmao' => $Irmao,
                'inIrmaoRA' => $IrmaoRA,
                'inIrmaoDigitoRA' => $IrmaoDigitoRA,
                'inIrmaoUFRA' => $IrmaoUFRA,
                'inInteresseIntegral' => $InteresseIntegral
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function RealizarMatriculaInfoComRA(
            $SerieAno, $TipoEnsino, $NumClasse, $DataMatricula, $RA, $DigitoRA, $MesMatricula, $NumAluno, $UFRA, $Ano, $DDDCel, $FoneCel, $SMS, $Convenio) {
        $function = 'RealizarMatriculaInfoComRA';
        $arguments = array('RealizarMatriculaInfoComRA' => array(
                'inSerieAno' => $SerieAno,
                'inTipoEnsino' => $TipoEnsino,
                'inNumClasse' => $NumClasse,
                'inDataMatricula' => $DataMatricula,
                'inRA' => $RA,
                'inDigitoRA' => $DigitoRA,
                'inMesMatricula' => $MesMatricula,
                'inNumAluno' => $NumAluno,
                'inUFRA' => $UFRA,
                'inAno' => $Ano,
                'inDDDCel' => $DDDCel,
                'inFoneCel' => $FoneCel,
                'inSMS' => $SMS,
                'inConvenio' => $Convenio
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function RealizarMatriculaInfoSemRA(
            $Ano, $NumClasse, $NumAluno, $DataMatricula, $MesMatricula, $NomeAluno, $Sexo, $CorRaca, $DiaNascimento, $MesNascimento, $AnoNascimento, $NomeMae, $NomePai, $Nacionalidade, $PaisOrigem, $DiaEntBrasil, $MesEntBrasil, $AnoEntBrasil, $RGRNE01, $RGRNE02, $RGRNE03, $DiaEmissao, $MesEmissao, $AnoEmissao, $NumNIS, $BolsaFamilia, $NecEducEspecial, $Multipla, $Cegueira, $BaixaVisao, $SurdezSeveraProfunda, $SurdezLeveModerada, $Surdocegueira, $FisicaParalisiaCerebral, $FisicaCadeirante, $FisicaOutros, $SindromeDown, $Intelectual, $AutistaClassico, $SindromeAsperger, $SindromeRett, $TransDesintegrativoInf, $AltasHabSuperdotacao, $Cep, $Cidade, $UF, $TipLog, $Logradouro, $Numero, $Complemento, $Bairro, $DDD, $FoneResidencial, $FoneRecados, $NomeFoneRecado, $MunicipioNasc, $UFNasc, $Gemeo, $IrmaoRA, $SerieAno, $TipoEnsino, $CPF, $Permanente, $Temporaria, $MobilidadeReduzida, $AuxilioLeitor, $AuxilioTranscricao, $GuiaInterprete, $InterpreteLibras, $LeituraLabial, $Nenhum, $ProvaAmpliada, $ProvaBraile, $Tam16, $Tam20, $Tam24, $DDDCel, $FoneCel, $SMS, $Email, $Irmao, $NomeSocial, $Quilombola) {
        $function = 'RealizarMatriculaInfoSemRA';
        $arguments = array('RealizarMatriculaInfoSemRA' => array(
                'inAno' => $Ano,
                'inNumClasse' => $NumClasse,
                'inNumAluno' => $NumAluno,
                'inDataMatricula' => $DataMatricula,
                'inMesMatricula' => $MesMatricula,
                'inNomeAluno' => $NomeAluno,
                'inSexo' => $Sexo,
                'inCorRaca' => $CorRaca,
                'inDiaNascimento' => $DiaNascimento,
                'inMesNascimento' => $MesNascimento,
                'inAnoNascimento' => $AnoNascimento,
                'inNomeMae' => $NomeMae,
                'inNomePai' => $NomePai,
                'inNacionalidade' => $Nacionalidade,
                'inPaisOrigem' => $PaisOrigem,
                'inDiaEntBrasil' => $DiaEntBrasil,
                'inMesEntBrasil' => $MesEntBrasil,
                'inAnoEntBrasil' => $AnoEntBrasil,
                'inRGRNE01' => $RGRNE01,
                'inRGRNE02' => $RGRNE02,
                'inRGRNE03' => $RGRNE03,
                'inDiaEmissao' => $DiaEmissao,
                'inMesEmissao' => $MesEmissao,
                'inAnoEmissao' => $AnoEmissao,
                'inNumNIS' => $NumNIS,
                'inBolsaFamilia' => $BolsaFamilia,
                'inNecEducEspecial' => $NecEducEspecial,
                'inMultipla' => $Multipla,
                'inCegueira' => $Cegueira,
                'inBaixaVisao' => $BaixaVisao,
                'inSurdezSeveraProfunda' => $SurdezSeveraProfunda,
                'inSurdezLeveModerada' => $SurdezLeveModerada,
                'inSurdocegueira' => $Surdocegueira,
                'inFisicaParalisiaCerebral' => $FisicaParalisiaCerebral,
                'inFisicaCadeirante' => $FisicaCadeirante,
                'inFisicaOutros' => $FisicaOutros,
                'inSindromeDown' => $SindromeDown,
                'inIntelectual' => $Intelectual,
                'inAutistaClassico' => $AutistaClassico,
                'inSindromeAsperger' => $SindromeAsperger,
                'inSindromeRett' => $SindromeRett,
                'inTransDesintegrativoInf' => $TransDesintegrativoInf,
                'inAltasHabSuperdotacao' => $AltasHabSuperdotacao,
                'inCep' => $Cep,
                'inCidade' => $Cidade,
                'inUF' => $UF,
                'inTipLog' => $TipLog,
                'inLogradouro' => $Logradouro,
                'inNumero' => $Numero,
                'inComplemento' => $Complemento,
                'inBairro' => $Bairro,
                'inDDD' => $DDD,
                'inFoneResidencial' => $FoneResidencial,
                'inFoneRecados' => $FoneRecados,
                'inNomeFoneRecado' => $NomeFoneRecado,
                'inMunicipioNasc' => $MunicipioNasc,
                'inUFNasc' => $UFNasc,
                'inGemeo' => $Gemeo,
                'inIrmaoRA' => $IrmaoRA,
                'inSerieAno' => $SerieAno,
                'inTipoEnsino' => $TipoEnsino,
                'inCPF' => $CPF,
                'inPermanente' => $Permanente,
                'inTemporaria' => $Temporaria,
                'inMobilidadeReduzida' => $MobilidadeReduzida,
                'inAuxilioLeitor' => $AuxilioLeitor,
                'inAuxilioTranscricao' => $AuxilioTranscricao,
                'inGuiaInterprete' => $GuiaInterprete,
                'inInterpreteLibras' => $InterpreteLibras,
                'inLeituraLabial' => $LeituraLabial,
                'inNenhum' => $Nenhum,
                'inProvaAmpliada' => $ProvaAmpliada,
                'inProvaBraile' => $ProvaBraile,
                'inTam16' => $Tam16,
                'inTam20' => $Tam20,
                'inTam24' => $Tam24,
                'inDDDCel' => $DDDCel,
                'inFoneCel' => $FoneCel,
                'inSMS' => $SMS,
                'inEmail' => $Email,
                'inIrmao' => $Irmao,
                'inNomeSocial' => $NomeSocial,
                'inQuilombola' => $Quilombola
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ReclassificarMatriculas(
            $DigitoRA, $RA, $UF, $DiaReclassificacao, $MesReclassificacao, $NumAluno, $Serie, $TipoEnsino, $Turma, $Turno) {
        $function = 'ReclassificarMatriculas';
        $arguments = array('ReclassificarMatriculas' => array(
                'inDigitoRA' => $DigitoRA,
                'inRA' => $RA,
                'inUF' => $UF,
                'inDiaReclassificacao' => $DiaReclassificacao,
                'inMesReclassificacao' => $MesReclassificacao,
                'inNumAluno' => $NumAluno,
                'inSerie' => $Serie,
                'inTipoEnsino' => $TipoEnsino,
                'inTurma' => $Turma,
                'inTurno' => $Turno
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function RegistrarAbandono(
            $RA, $DiaAbandono, $MesAbandono, $UFRA, $DigitoRA) {
        $function = 'RegistrarAbandono';
        $arguments = array('RegistrarAbandono' => array(
                'inRA' => $RA,
                'inDiaAbandono' => $DiaAbandono,
                'inMesAbandono' => $MesAbandono,
                'inUFRA' => $UFRA,
                'inDigitoRA' => $DigitoRA
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function RegistrarNaoComparecimento(
            $RA, $CodEscola, $SerieAno, $TipoEnsino) {
        $function = 'RegistrarNaoComparecimento';
        $arguments = array('RegistrarNaoComparecimento' => array(
                'inRA' => $RA,
                'inCodEscola' => $CodEscola,
                'inSerieAno' => $SerieAno,
                'inTipoEnsino' => $TipoEnsino
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function RemanejarMatPorRA(
            $CodEscola, $NumClasse, $RA, $DigitoRA, $UF, $TipoEnsino, $SerieAno, $AlunoAvaliado, $DiaRemanejamento, $MesRemanejamento, $NumAluno) {
        $function = 'RemanejarMatPorRA';
        $arguments = array('RemanejarMatPorRA' => array(
                'inCodEscola' => $CodEscola,
                'inNumClasse' => $NumClasse,
                'inRA' => $RA,
                'inDigitoRA' => $DigitoRA,
                'inUF' => $UF,
                'inTipoEnsino' => $TipoEnsino,
                'inSerieAno' => $SerieAno,
                'inAlunoAvaliado' => $AlunoAvaliado,
                'inDiaRemanejamento' => $DiaRemanejamento,
                'inMesRemanejamento' => $MesRemanejamento,
                'inNumAluno' => $NumAluno
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function TrocarAlunoClasseRA(
            $Ano, $DigitoRA, $RA, $UF, $CodEscola, $NumAluno, $Turma, $Turno, $TipoEnsino, $SerieAno, $DiaTroca, $MesTroca, $AnoTroca) {
        $function = 'TrocarAlunoClasseRA';
        $arguments = array('TrocarAlunoClasseRA' => array(
                'inAno' => $Ano,
                'inDigitoRA' => $DigitoRA,
                'inRA' => $RA,
                'inUF' => $UF,
                'inCodEscola' => $CodEscola,
                'inNumAluno' => $NumAluno,
                'inTurma' => $Turma,
                'inTurno' => $Turno,
                'inTipoEnsino' => $TipoEnsino,
                'inSerieAno' => $SerieAno,
                'inDiaTroca' => $DiaTroca,
                'inMesTroca' => $MesTroca,
                'inAnoTroca' => $AnoTroca
        ));

        $result = $this->exec($function, $arguments);

        return $result;
    }

}
