<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of gdaeForm
 *
 * @author marco
 */
class gdaeForm {

    private function exec($function, $arguments) {
        $form = '<form method = "POST">';
        $form .= '<br /><div style="padding-left: 50px">';
        foreach ($arguments as $k => $v) {
            $form .= '<br /><br />' . $k . '<input type="text" name="1[' . $k . ']" value="' . @$_POST[1][$k] . '" />';
        }
        $form .= '</div>';
        $form .= '<br /><div  style="text-align: center"><input type = "submit" value = "Enviar" /></div>';
        $form .= '<input type="hidden" name="form" value="1" />';
        $form .= '<input type="hidden" name="metodo" value="' . $function . '" />';
        $form .= '</form>';

        return $form;
    }

    public function AlterarColetaClasse() {
        $function = 'AlterarColetaClasse';
        $arguments = array(
            'inNumClasse' => NULL,
            'inCapacidadeFisica' => NULL,
            'inCursoSemestral1' => NULL,
            'inCursoSemestral2' => NULL,
            'inDiaInicioAula' => NULL,
            'inDiaTerminoAula' => NULL,
            'inCodigoINEP' => NULL,
            'inHoraFinal' => NULL,
            'inHoraInicial' => NULL,
            'inMesInicioAula' => NULL,
            'inMesTerminoAula' => NULL,
            'inNumeroSala' => NULL,
            'inSerieAno' => NULL,
            'inTipoClasse' => NULL,
            'inTipoEnsino' => NULL,
            'inTurma' => NULL,
            'inTurno' => NULL,
            'inAno' => NULL,
            'inDiasSemana' => NULL,
            'inProgMaisEducacao' => NULL,
            'inAEE' => NULL,
            'inATC' => NULL,
            'inFlagConvenioEst' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function AlterarDadosPessoaisFichaAluno() {
        $function = 'AlterarDadosPessoaisFichaAluno';
        $arguments = array(
            'inRA' => NULL,
            'inAnoNascimento' => NULL,
            'inBolsaFamilia' => NULL,
            'inCorRaca' => NULL,
            'inDiaNascimento' => NULL,
            'inMesNascimento' => NULL,
            'inNecEspecial' => NULL,
            'inNomeAluno' => NULL,
            'inNomeMae' => NULL,
            'inNomePai' => NULL,
            'inSexo' => NULL,
            'inAltasHabSuperdotacao' => NULL,
            'inAutistaClassico' => NULL,
            'inBaixaVisao' => NULL,
            'inCegueira' => NULL,
            'inFisicaCadeirante' => NULL,
            'inFisicaOutros' => NULL,
            'inFisicaParalisiaCerebral' => NULL,
            'inIntelectual' => NULL,
            'inMultipla' => NULL,
            'inSindromeAsperger' => NULL,
            'inSindromeDown' => NULL,
            'inSindromeRett' => NULL,
            'inSurdezLeveModerada' => NULL,
            'inSurdezSeveraProfunda' => NULL,
            'inSurdocegueira' => NULL,
            'inTransDesintegrativoInf' => NULL,
            'inGemeo' => NULL,
            'inIrmaoRA' => NULL,
            'inPermanente' => NULL,
            'inTemporaria' => NULL,
            'inMobilidadeReduzida' => NULL,
            'inDigitoRA' => NULL,
            'inUFRA' => NULL,
            'inAuxilioLeitor' => NULL,
            'inAuxilioTranscricao' => NULL,
            'inGuiaInterprete' => NULL,
            'inInterpreteLibras' => NULL,
            'inLeituraLabial' => NULL,
            'inNenhum' => NULL,
            'inProvaBraile' => NULL,
            'inProvaAmpliada' => NULL,
            'inTam16' => NULL,
            'inTam20' => NULL,
            'inTam24' => NULL,
            'inNomeSocial' => NULL,
            'inQuilombola' => NULL,
            'inEmail' => NULL,
            'inIrmaoUFRA' => NULL,
            'inIrmaoDigitoRA' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function AlterarDocumentosFichaAluno() {
        $function = 'AlterarDocumentosFichaAluno';
        $arguments = array(
            'inRA' => NULL,
            'inAnoEmissao' => NULL,
            'inAnoEntBrasil' => NULL,
            'inDiaEmissao' => NULL,
            'inDiaEntBrasil' => NULL,
            'inMesEmissao' => NULL,
            'inMesEntBrasil' => NULL,
            'inMunicipioNasc' => NULL,
            'inNacionalidade' => NULL,
            'inNumNIS' => NULL,
            'inPaisOrigem' => NULL,
            'inRGRNE01' => NULL,
            'inRGRNE02' => NULL,
            'inRGRNE03' => NULL,
            'inUFNasc' => NULL,
            'inCPF' => NULL,
            'inDigitoRA' => NULL,
            'inUFRA' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function AlterarEnderecoFichaAluno() {
        $function = 'AlterarEnderecoFichaAluno';
        $arguments = array(
            'inRA' => NULL,
            'inBairro' => NULL,
            'inCep' => NULL,
            'inCidade' => NULL,
            'inComplemento' => NULL,
            'inDDD' => NULL,
            'inFoneRecados' => NULL,
            'inFoneResidencial' => NULL,
            'inLogradouro' => NULL,
            'inNomeFoneRecado' => NULL,
            'inNumero' => NULL,
            'inTipoLogradouro' => NULL,
            'inUF' => NULL,
            'inDigitoRA' => NULL,
            'inUFRA' => NULL,
            'inSMS' => NULL,
            'inDDDCel' => NULL,
            'inFoneCel' => NULL,
            'inLatitude' => NULL,
            'inLongitude' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function AlterarEnderecoIndicativo() {
        $function = 'AlterarEnderecoIndicativo';
        $arguments = array(
            'inRA' => NULL,
            'inDigitoRA' => NULL,
            'inUFRA' => NULL,
            'inBairro' => NULL,
            'inCep' => NULL,
            'inCidade' => NULL,
            'inDDD' => NULL,
            'inFoneResidencial' => NULL,
            'inLogradouro' => NULL,
            'inNumero' => NULL,
            'inRecados' => NULL,
            'inUF' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function AssociarIrmao() {
        $function = 'AssociarIrmao';
        $arguments = array(
            'inRA' => NULL,
            'inIrmaoRA' => NULL,
            'inIrmaoDigitoRA' => NULL,
            'inIrmaoUFRA' => NULL,
            'inDigitoRA' => NULL,
            'inUFRA' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function BaixarMatrFalecimentoRA() {
        $function = 'BaixarMatrFalecimentoRA';
        $arguments = array(
            'inRA' => NULL,
            'inAnoFalecimento' => NULL,
            'inDiaFalecimento' => NULL,
            'inMesFalecimento' => NULL,
            'inUFRA' => NULL,
            'inDigitoRA' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function BaixarMatriculaTransferencia() {
        $function = 'BaixarMatriculaTransferencia';
        $arguments = array(
            'inDigitoRA' => NULL,
            'inRA' => NULL,
            'inUF' => NULL,
            'inDiaTransferencia' => NULL,
            'inMesTransferencia' => NULL,
            'inMotivo' => NULL,
            'inCodEscola' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function CancelarEncerramentoRendEscolar() {
        $function = 'CancelarEncerramentoRendEscolar';
        $arguments = array(
            'inCodEscola' => NULL,
            'inSemestre' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function CancelarInscricaoAlunoPorDeslocamento() {
        $function = 'CancelarInscricaoAlunoPorDeslocamento';
        $arguments = array(
            'inRA' => NULL,
            'inDigitoRA' => NULL,
            'inUFRA' => NULL,
            'inEscola' => NULL,
            'inAno' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function CancelarInscricaoAlunoTransf() {
        $function = 'CancelarInscricaoAlunoTransf';
        $arguments = array(
            'inRA' => NULL,
            'inDigitoRA' => NULL,
            'inUFRA' => NULL,
            'inAno' => NULL,
            'inEscola' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function CancelarInscricaoDefinicao() {
        $function = 'CancelarInscricaoDefinicao';
        $arguments = array(
            'inRA' => NULL,
            'inDigitoRA' => NULL,
            'inUFRA' => NULL,
            'inEscola' => NULL,
            'inAno' => NULL,
            'inFase' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function CancelarIntencaoTransferencia() {
        $function = 'CancelarIntencaoTransferencia';
        $arguments = array(
            'inRA' => NULL,
            'inDigitoRA' => NULL,
            'inUFRA' => NULL,
            'inEscola' => NULL,
            'inAno' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function CancelarTerminoDigitacao() {
        $function = 'CancelarTerminoDigitacao';
        $arguments = array(
            'inCodEscola' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ClassificarGerarNrChamadaClassePorEscola() {
        $function = 'ClassificarGerarNrChamadaClassePorEscola';
        $arguments = array(
            'inAno' => NULL,
            'inCodigoEscola' => NULL,
            'inOrdemClassificacao' => NULL,
            'inTipoEnsino' => NULL,
            'inSerieAno' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ClassificarGerarNumeroChamadaPorClasse() {
        $function = 'ClassificarGerarNumeroChamadaPorClasse';
        $arguments = array(
            'inAno' => NULL,
            'inNumClasse' => NULL,
            'inOrdemClassificacao' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultaClasseAlunoPorEscola() {
        $function = 'ConsultaClasseAlunoPorEscola';
        $arguments = array(
            'inAnoLetivo' => NULL,
            'inCodEscola' => NULL,
            'inSemestre' => NULL,
            'inSerieAno' => NULL,
            'inTipoEnsino' => NULL,
            'inTurma' => NULL,
            'inTurno' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultaFormacaoClasse() {
        $function = 'ConsultaFormacaoClasse';
        $arguments = array(
            'inNumClasse' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarCep() {
        $function = 'ConsultarCep';
        $arguments = array(
            'inCep' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarCepLogradouro() {
        $function = 'ConsultarCepLogradouro';
        $arguments = array(
            'inNomeLocal' => NULL,
            'inNomeLogradouro' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarColetaClasse() {
        $function = 'ConsultarColetaClasse';
        $arguments = array(
            'inAno' => NULL,
            'inNumClasse' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarDefinicaoInscricaoRA() {
        $function = 'ConsultarDefinicaoInscricaoRA';
        $arguments = array(
            'inRA' => NULL,
            'inDigitoRA' => NULL,
            'inUFRA' => NULL,
            'inDefinicaoInscricao' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarEscolaCIE() {
        $function = 'ConsultarEscolaCIE';
        $arguments = array(
            'inCodEscola' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarIrmao() {
        $function = 'ConsultarIrmao';
        $arguments = array(
            'inRA' => NULL,
            'inUFRA' => NULL,
            'inDigitoRA' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarMatriculaClasseRA() {
        $function = 'ConsultarMatriculaClasseRA';
        $arguments = array(
            'inRA' => NULL,
            'inNumClasse' => NULL,
            'inDigitoRA' => NULL,
            'inUF' => NULL,
            'inSituacao' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarMatriculaPorClasse() {
        $function = 'ConsultarMatriculaPorClasse';
        $arguments = array(
            'inRA' => NULL,
            'inDigitoRA' => NULL,
            'inUF' => NULL,
            'inNumClasse' => NULL,
            'inSituacao' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarMatriculas() {
        $function = 'ConsultarMatriculas';
        $arguments = array(
            'inRA' => NULL,
            'inDigitoRA' => NULL,
            'inUF' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarMatriculasRA() {
        $function = 'ConsultarMatriculasRA';
        $arguments = array(
            'inRA' => NULL,
            'inDigitoRA' => NULL,
            'inUF' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarQuadroResumo() {
        $function = 'ConsultarQuadroResumo';
        $arguments = array(
            'inCodEscola' => NULL,
            'inAno' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarRendPorNumeroClasse() {
        $function = 'ConsultarRendPorNumeroClasse';
        $arguments = array(
            'inAno' => NULL,
            'inNumClasse' => NULL,
            'inSemestre' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarTotaisporEscola() {
        $function = 'ConsultarTotaisporEscola';
        $arguments = array(
            'inAnoLetivo' => NULL,
            'inCodEscola' => NULL,
            'inSemestre' => NULL,
            'inSerieAno' => NULL,
            'inTipoEnsino' => NULL,
            'inTurma' => NULL,
            'inTurno' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function DefinirAlunoEnsinoMedio() {
        $function = 'DefinirAlunoEnsinoMedio';
        $arguments = array(
            'inRA' => NULL,
            'inDigitoRA' => NULL,
            'inUFRA' => NULL,
            'inAno' => NULL,
            'inTurno' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function EncerrarRendimentoEscolarPorCIE() {
        $function = 'EncerrarRendimentoEscolarPorCIE';
        $arguments = array(
            'inCodEscola' => NULL,
            'inSemestre' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function EstornarBaixaMatrTransf() {
        $function = 'EstornarBaixaMatrTransf';
        $arguments = array(
            'inCodEscola' => NULL,
            'inRA' => NULL,
            'inSerieAno' => NULL,
            'inTipoEnsino' => NULL,
            'inUFRA' => NULL,
            'inDigitoRA' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function EstornarRegistroAbandono() {
        $function = 'EstornarRegistroAbandono';
        $arguments = array(
            'inRA' => NULL,
            'inDiaRetorno' => NULL,
            'inMesRetorno' => NULL,
            'inCodEscola' => NULL,
            'inSerieAno' => NULL,
            'inTipoEnsino' => NULL,
            'inUFRA' => NULL,
            'inDigitoRA' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ExcluirColetaClasse() {
        $function = 'ExcluirColetaClasse';
        $arguments = array(
            'inNumClasse' => NULL,
            'inAno' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ExcluirIrmao() {
        $function = 'ExcluirIrmao';
        $arguments = array(
            'inRA' => NULL,
            'inIrmaoRA' => NULL,
            'inIrmaoDigitoRA' => NULL,
            'inIrmaoUFRA' => NULL,
            'inUFRA' => NULL,
            'inDigitoRA' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ExcluirMatricula() {
        $function = 'ExcluirMatricula';
        $arguments = array(
            'inRA' => NULL,
            'inDigitoRA' => NULL,
            'inUF' => NULL,
            'inNumClasse' => NULL,
            'inAno' => NULL,
            'inSerieAno' => NULL,
            'inTipoEnsino' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function IncluirColetaClasse() {
        $function = 'IncluirColetaClasse';
        $arguments = array(
            'inCodEscola' => NULL,
            'inCodUnidade' => NULL,
            'inCapacidadeFisica' => NULL,
            'inCursoSemestral1' => NULL,
            'inCursoSemestral2' => NULL,
            'inDiaInicioAula' => NULL,
            'inDiaTerminoAula' => NULL,
            'inCodigoINEP' => NULL,
            'inHoraFinal' => NULL,
            'inHoraInicial' => NULL,
            'inMesInicioAula' => NULL,
            'inMesTerminoAula' => NULL,
            'inNumeroSala' => NULL,
            'inSerieAno' => NULL,
            'inTipoClasse' => NULL,
            'inTipoEnsino' => NULL,
            'inTurma' => NULL,
            'inTurno' => NULL,
            'inAno' => NULL,
            'inDiasSemana' => NULL,
            'inProgMaisEducacao' => NULL,
            'inAEE' => NULL,
            'inATC' => NULL,
            'inFlagConvenioEst' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function InformarTerminoDigitacao() {
        $function = 'InformarTerminoDigitacao';
        $arguments = array(
            'inCodEscola' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function InscreverAlunoPorDeslocamento() {
        $function = 'InscreverAlunoPorDeslocamento';
        $arguments = array(
            'inRA' => NULL,
            'inDigitoRA' => NULL,
            'inUFRA' => NULL,
            'inEscola' => NULL,
            'inAno' => NULL,
            'inTipoInscricao' => NULL,
            'inMotivo' => NULL,
            'inDDDCel' => NULL,
            'inFoneCel' => NULL,
            'inSMS' => NULL,
            'inInteresseIntegral' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function InscreverAlunotransferencia() {
        $function = 'InscreverAlunotransferencia';
        $arguments = array(
            'inRA' => NULL,
            'inDigitoRA' => NULL,
            'inUFRA' => NULL,
            'inAno' => NULL,
            'inEscola' => NULL,
            'inAnoSerie' => NULL,
            'inTipoEnsino' => NULL,
            'inMotivo' => NULL,
            'inInteresseIntegral' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function InscricaoIntencaoTransferencia() {
        $function = 'InscricaoIntencaoTransferencia';
        $arguments = array(
            'inRA' => NULL,
            'inDigitoRA' => NULL,
            'inUFRA' => NULL,
            'inEscola' => NULL,
            'inSerieAno' => NULL,
            'inTipoEnsino' => NULL,
            'inAno' => NULL,
            'inBairro' => NULL,
            'inCep' => NULL,
            'inCidade' => NULL,
            'inComplemento' => NULL,
            'inDDD' => NULL,
            'inDDDCel' => NULL,
            'inFoneCel' => NULL,
            'inFoneRecados' => NULL,
            'inFoneResidencial' => NULL,
            'inLogradouro' => NULL,
            'inNomeRecados' => NULL,
            'inNumero' => NULL,
            'inSMS' => NULL,
            'inTipoLogradouro' => NULL,
            'inUF' => NULL,
            'inInteresseIntegral' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function LancarRendimentoEscolarClasse() {
        $function = 'LancarRendimentoEscolarClasse';
        $arguments = array(
            'inNumClasse' => NULL,
            'inSemestre' => NULL,
            'inAlunos' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function RealizarMatricAntecipadaFases() {
        $function = 'RealizarMatricAntecipadaFases';
        $arguments = array(
            'inCodEscola' => NULL,
            'inAno' => NULL,
            'inFase' => NULL,
            'inSerieAno' => NULL,
            'inTipoEnsino' => NULL,
            'inAnoEmissaoRG' => NULL,
            'inAnoNascimento' => NULL,
            'inBolsaFamilia' => NULL,
            'inCorRaca' => NULL,
            'inCPF' => NULL,
            'inDiaEmissaoRG' => NULL,
            'inDiaNascimento' => NULL,
            'inGemeo' => NULL,
            'inMesEmissaoRG' => NULL,
            'inMesNascimento' => NULL,
            'inMobilidadeReduzida' => NULL,
            'inNacionalidade' => NULL,
            'inNecessidadeEspecial' => NULL,
            'inNIS' => NULL,
            'inNomeAluno' => NULL,
            'inNomeMae' => NULL,
            'inNomePai' => NULL,
            'inPaisOrigem' => NULL,
            'inSexo' => NULL,
            'inPermanente' => NULL,
            'inTemporaria' => NULL,
            'inAltasHabSuperdotacao' => NULL,
            'inAutistaClassico' => NULL,
            'inBaixaVisao' => NULL,
            'inCegueira' => NULL,
            'inFisicaCadeirante' => NULL,
            'inFisicaOutros' => NULL,
            'inFisicaParalisiaCerebral' => NULL,
            'inIntelectual' => NULL,
            'inMultipla' => NULL,
            'inSindromeAsperger' => NULL,
            'inSindromeDown' => NULL,
            'inSindromeRett' => NULL,
            'inSurdezLeveModerada' => NULL,
            'inSurdezSeveraProfunda' => NULL,
            'inSurdocegueira' => NULL,
            'inTransDesintegrativoInf' => NULL,
            'inAnoEntBrasil' => NULL,
            'inDiaEntBrasil' => NULL,
            'inMesEntBrasil' => NULL,
            'inRGRNE01' => NULL,
            'inRGRNE02' => NULL,
            'inRGRNE03' => NULL,
            'inMunicipioNasc' => NULL,
            'inUFNasc' => NULL,
            'inAuxilioLeitor' => NULL,
            'inAuxilioTranscricao' => NULL,
            'inGuiaInterprete' => NULL,
            'inInterpreteLibras' => NULL,
            'inLeituraLabial' => NULL,
            'inNenhum' => NULL,
            'inProvaAmpliada' => NULL,
            'inProvaBraile' => NULL,
            'inTam16' => NULL,
            'inTam20' => NULL,
            'inTam24' => NULL,
            'inSMS' => NULL,
            'inFoneCel' => NULL,
            'inDDDCel' => NULL,
            'inIrmao' => NULL,
            'inIrmaoRA' => NULL,
            'inIrmaoDigitoRA' => NULL,
            'inIrmaoUFRA' => NULL,
            'inNomeSocial' => NULL,
            'inEmail' => NULL,
            'inQuilombola' => NULL,
            'inNecesAtendNoturno' => NULL,
            'inInteresseEspanhol' => NULL,
            'inInteresseIntegral' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function RealizarMatricAntecipadaRAFases() {
        $function = 'RealizarMatricAntecipadaRAFases';
        $arguments = array(
            'inCodEscola' => NULL,
            'inAno' => NULL,
            'inFase' => NULL,
            'inSerieAno' => NULL,
            'inTipoEnsino' => NULL,
            'inRA' => NULL,
            'inDigitoRA' => NULL,
            'inUFRA' => NULL,
            'inSMS' => NULL,
            'inFoneCel' => NULL,
            'inDDDCel' => NULL,
            'inTurno' => NULL,
            'inNecesAtendNoturno' => NULL,
            'inInteresseIntegral' => NULL,
            'inInteresseEspanhol' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function RealizarMatriculaAntecipada() {
        $function = 'RealizarMatriculaAntecipada';
        $arguments = array(
            'inRA' => NULL,
            'inDigitoRA' => NULL,
            'inUFRA' => NULL,
            'inAno' => NULL,
            'inDDDCel' => NULL,
            'inFoneCel' => NULL,
            'inSMS' => NULL,
            'inIrmao' => NULL,
            'inIrmaoRA' => NULL,
            'inIrmaoDigitoRA' => NULL,
            'inIrmaoUFRA' => NULL,
            'inInteresseIntegral' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function RealizarMatriculaInfoComRA() {
        $function = 'RealizarMatriculaInfoComRA';
        $arguments = array(
            'inSerieAno' => NULL,
            'inTipoEnsino' => NULL,
            'inNumClasse' => NULL,
            'inDataMatricula' => NULL,
            'inRA' => NULL,
            'inDigitoRA' => NULL,
            'inMesMatricula' => NULL,
            'inNumAluno' => NULL,
            'inUFRA' => NULL,
            'inAno' => NULL,
            'inDDDCel' => NULL,
            'inFoneCel' => NULL,
            'inSMS' => NULL,
            'inConvenio' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function RealizarMatriculaInfoSemRA() {
        $function = 'RealizarMatriculaInfoSemRA';
        $arguments = array(
            'inAno' => NULL,
            'inNumClasse' => NULL,
            'inNumAluno' => NULL,
            'inDataMatricula' => NULL,
            'inMesMatricula' => NULL,
            'inNomeAluno' => NULL,
            'inSexo' => NULL,
            'inCorRaca' => NULL,
            'inDiaNascimento' => NULL,
            'inMesNascimento' => NULL,
            'inAnoNascimento' => NULL,
            'inNomeMae' => NULL,
            'inNomePai' => NULL,
            'inNacionalidade' => NULL,
            'inPaisOrigem' => NULL,
            'inDiaEntBrasil' => NULL,
            'inMesEntBrasil' => NULL,
            'inAnoEntBrasil' => NULL,
            'inRGRNE01' => NULL,
            'inRGRNE02' => NULL,
            'inRGRNE03' => NULL,
            'inDiaEmissao' => NULL,
            'inMesEmissao' => NULL,
            'inAnoEmissao' => NULL,
            'inNumNIS' => NULL,
            'inBolsaFamilia' => NULL,
            'inNecEducEspecial' => NULL,
            'inMultipla' => NULL,
            'inCegueira' => NULL,
            'inBaixaVisao' => NULL,
            'inSurdezSeveraProfunda' => NULL,
            'inSurdezLeveModerada' => NULL,
            'inSurdocegueira' => NULL,
            'inFisicaParalisiaCerebral' => NULL,
            'inFisicaCadeirante' => NULL,
            'inFisicaOutros' => NULL,
            'inSindromeDown' => NULL,
            'inIntelectual' => NULL,
            'inAutistaClassico' => NULL,
            'inSindromeAsperger' => NULL,
            'inSindromeRett' => NULL,
            'inTransDesintegrativoInf' => NULL,
            'inAltasHabSuperdotacao' => NULL,
            'inCep' => NULL,
            'inCidade' => NULL,
            'inUF' => NULL,
            'inTipLog' => NULL,
            'inLogradouro' => NULL,
            'inNumero' => NULL,
            'inComplemento' => NULL,
            'inBairro' => NULL,
            'inDDD' => NULL,
            'inFoneResidencial' => NULL,
            'inFoneRecados' => NULL,
            'inNomeFoneRecado' => NULL,
            'inMunicipioNasc' => NULL,
            'inUFNasc' => NULL,
            'inGemeo' => NULL,
            'inIrmaoRA' => NULL,
            'inSerieAno' => NULL,
            'inTipoEnsino' => NULL,
            'inCPF' => NULL,
            'inPermanente' => NULL,
            'inTemporaria' => NULL,
            'inMobilidadeReduzida' => NULL,
            'inAuxilioLeitor' => NULL,
            'inAuxilioTranscricao' => NULL,
            'inGuiaInterprete' => NULL,
            'inInterpreteLibras' => NULL,
            'inLeituraLabial' => NULL,
            'inNenhum' => NULL,
            'inProvaAmpliada' => NULL,
            'inProvaBraile' => NULL,
            'inTam16' => NULL,
            'inTam20' => NULL,
            'inTam24' => NULL,
            'inDDDCel' => NULL,
            'inFoneCel' => NULL,
            'inSMS' => NULL,
            'inEmail' => NULL,
            'inIrmao' => NULL,
            'inNomeSocial' => NULL,
            'inQuilombola' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ReclassificarMatriculas() {
        $function = 'ReclassificarMatriculas';
        $arguments = array(
            'inDigitoRA' => NULL,
            'inRA' => NULL,
            'inUF' => NULL,
            'inDiaReclassificacao' => NULL,
            'inMesReclassificacao' => NULL,
            'inNumAluno' => NULL,
            'inSerie' => NULL,
            'inTipoEnsino' => NULL,
            'inTurma' => NULL,
            'inTurno' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function RegistrarAbandono() {
        $function = 'RegistrarAbandono';
        $arguments = array(
            'inRA' => NULL,
            'inDiaAbandono' => NULL,
            'inMesAbandono' => NULL,
            'inUFRA' => NULL,
            'inDigitoRA' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function RegistrarNaoComparecimento() {
        $function = 'RegistrarNaoComparecimento';
        $arguments = array(
            'inRA' => NULL,
            'inCodEscola' => NULL,
            'inSerieAno' => NULL,
            'inTipoEnsino' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function RemanejarMatPorRA() {
        $function = 'RemanejarMatPorRA';
        $arguments = array(
            'inCodEscola' => NULL,
            'inNumClasse' => NULL,
            'inRA' => NULL,
            'inDigitoRA' => NULL,
            'inUF' => NULL,
            'inTipoEnsino' => NULL,
            'inSerieAno' => NULL,
            'inAlunoAvaliado' => NULL,
            'inDiaRemanejamento' => NULL,
            'inMesRemanejamento' => NULL,
            'inNumAluno' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function TrocarAlunoClasseRA() {
        $function = 'TrocarAlunoClasseRA';
        $arguments = array(
            'inAno' => NULL,
            'inDigitoRA' => NULL,
            'inRA' => NULL,
            'inUF' => NULL,
            'inCodEscola' => NULL,
            'inNumAluno' => NULL,
            'inTurma' => NULL,
            'inTurno' => NULL,
            'inTipoEnsino' => NULL,
            'inSerieAno' => NULL,
            'inDiaTroca' => NULL,
            'inMesTroca' => NULL,
            'inAnoTroca' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

###################################### manual ##################################

    public function ConsultarFichaAlunoRa() {
        $function = 'ConsultarFichaAlunoRa';
        $arguments = array(
                    'inRA' => NULL,
                    'inDigitoRA' => NULL,
                    'inUF' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

    public function ConsultarFichaAlunoFonetica() {
        $function = 'ConsultarFichaAlunoFonetica';
        $arguments = array(
                    'inNomeAluno' => NULL,
                    'inDiaNascimento' => NULL,
                    'inMesNascimento' => NULL,
                    'inAnoNascimento' => NULL,
                    'inNomeMae' => NULL,
                    'inNomeSocial' => NULL
        );

        $result = $this->exec($function, $arguments);

        return $result;
    }

}
