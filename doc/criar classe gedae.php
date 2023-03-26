<?php
$xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<definitions xmlns="http://schemas.xmlsoap.org/wsdl/" xmlns:SOAP-ENC="http://schemas.xmlsoap.org/soap/encoding/" xmlns:mime="http://schemas.xmlsoap.org/wsdl/mime/" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:s0="http://www.educacao.sp.gov.br/Ensemble" xmlns:soap="http://schemas.xmlsoap.org/wsdl/soap/" xmlns:wsdl="http://schemas.xmlsoap.org/wsdl/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" targetNamespace="http://www.educacao.sp.gov.br/Ensemble">
    <types>
        <s:schema elementFormDefault="qualified" targetNamespace="http://www.educacao.sp.gov.br/Ensemble">
            <s:element name="AlterarColetaClasse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inNumClasse" type="s:string"/>
                        <s:element minOccurs="0" name="inCapacidadeFisica" type="s:string"/>
                        <s:element minOccurs="0" name="inCursoSemestral1" type="s:string"/>
                        <s:element minOccurs="0" name="inCursoSemestral2" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaInicioAula" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaTerminoAula" type="s:string"/>
                        <s:element minOccurs="0" name="inCodigoINEP" type="s:string"/>
                        <s:element minOccurs="0" name="inHoraFinal" type="s:string"/>
                        <s:element minOccurs="0" name="inHoraInicial" type="s:string"/>
                        <s:element minOccurs="0" name="inMesInicioAula" type="s:string"/>
                        <s:element minOccurs="0" name="inMesTerminoAula" type="s:string"/>
                        <s:element minOccurs="0" name="inNumeroSala" type="s:string"/>
                        <s:element minOccurs="0" name="inSerieAno" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoClasse" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoEnsino" type="s:string"/>
                        <s:element minOccurs="0" name="inTurma" type="s:string"/>
                        <s:element minOccurs="0" name="inTurno" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="inDiasSemana" type="s0:DiasSemana"/>
                        <s:element minOccurs="0" name="inProgMaisEducacao" type="s:string"/>
                        <s:element minOccurs="0" name="inAEE" type="s:string"/>
                        <s:element minOccurs="0" name="inATC" type="s:string"/>
                        <s:element minOccurs="0" name="inFlagConvenioEst" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="DiasSemana">
                <s:sequence>
                    <s:element minOccurs="0" name="inSegundaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="inTercaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="inQuartaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="inQuintaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="inSextaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="inSabado" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="AlterarColetaClasseResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                        <s:element minOccurs="0" name="MensagensErro" type="s0:ArrayOfMensagensErroItemString"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMensagensErroItemString">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MensagensErroItem" nillable="true" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="AlterarDadosPessoaisFichaAluno">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inAnoNascimento" type="s:string"/>
                        <s:element minOccurs="0" name="inBolsaFamilia" type="s:string"/>
                        <s:element minOccurs="0" name="inCorRaca" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaNascimento" type="s:string"/>
                        <s:element minOccurs="0" name="inMesNascimento" type="s:string"/>
                        <s:element minOccurs="0" name="inNecEspecial" type="s:string"/>
                        <s:element minOccurs="0" name="inNomeAluno" type="s:string"/>
                        <s:element minOccurs="0" name="inNomeMae" type="s:string"/>
                        <s:element minOccurs="0" name="inNomePai" type="s:string"/>
                        <s:element minOccurs="0" name="inSexo" type="s:string"/>
                        <s:element minOccurs="0" name="inAltasHabSuperdotacao" type="s:string"/>
                        <s:element minOccurs="0" name="inAutistaClassico" type="s:string"/>
                        <s:element minOccurs="0" name="inBaixaVisao" type="s:string"/>
                        <s:element minOccurs="0" name="inCegueira" type="s:string"/>
                        <s:element minOccurs="0" name="inFisicaCadeirante" type="s:string"/>
                        <s:element minOccurs="0" name="inFisicaOutros" type="s:string"/>
                        <s:element minOccurs="0" name="inFisicaParalisiaCerebral" type="s:string"/>
                        <s:element minOccurs="0" name="inIntelectual" type="s:string"/>
                        <s:element minOccurs="0" name="inMultipla" type="s:string"/>
                        <s:element minOccurs="0" name="inSindromeAsperger" type="s:string"/>
                        <s:element minOccurs="0" name="inSindromeDown" type="s:string"/>
                        <s:element minOccurs="0" name="inSindromeRett" type="s:string"/>
                        <s:element minOccurs="0" name="inSurdezLeveModerada" type="s:string"/>
                        <s:element minOccurs="0" name="inSurdezSeveraProfunda" type="s:string"/>
                        <s:element minOccurs="0" name="inSurdocegueira" type="s:string"/>
                        <s:element minOccurs="0" name="inTransDesintegrativoInf" type="s:string"/>
                        <s:element minOccurs="0" name="inGemeo" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmaoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inPermanente" type="s:string"/>
                        <s:element minOccurs="0" name="inTemporaria" type="s:string"/>
                        <s:element minOccurs="0" name="inMobilidadeReduzida" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inAuxilioLeitor" type="s:string"/>
                        <s:element minOccurs="0" name="inAuxilioTranscricao" type="s:string"/>
                        <s:element minOccurs="0" name="inGuiaInterprete" type="s:string"/>
                        <s:element minOccurs="0" name="inInterpreteLibras" type="s:string"/>
                        <s:element minOccurs="0" name="inLeituraLabial" type="s:string"/>
                        <s:element minOccurs="0" name="inNenhum" type="s:string"/>
                        <s:element minOccurs="0" name="inProvaBraile" type="s:string"/>
                        <s:element minOccurs="0" name="inProvaAmpliada" type="s:string"/>
                        <s:element minOccurs="0" name="inTam16" type="s:string"/>
                        <s:element minOccurs="0" name="inTam20" type="s:string"/>
                        <s:element minOccurs="0" name="inTam24" type="s:string"/>
                        <s:element minOccurs="0" name="inNomeSocial" type="s:string"/>
                        <s:element minOccurs="0" name="inQuilombola" type="s:string"/>
                        <s:element minOccurs="0" name="inEmail" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmaoUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmaoDigitoRA" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="AlterarDadosPessoaisFichaAlunoResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Erro" type="s:string"/>
                        <s:element minOccurs="0" name="Sucesso" type="s:string"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="AlterarDocumentosFichaAluno">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inAnoEmissao" type="s:string"/>
                        <s:element minOccurs="0" name="inAnoEntBrasil" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaEmissao" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaEntBrasil" type="s:string"/>
                        <s:element minOccurs="0" name="inMesEmissao" type="s:string"/>
                        <s:element minOccurs="0" name="inMesEntBrasil" type="s:string"/>
                        <s:element minOccurs="0" name="inMunicipioNasc" type="s:string"/>
                        <s:element minOccurs="0" name="inNacionalidade" type="s:string"/>
                        <s:element minOccurs="0" name="inNumNIS" type="s:string"/>
                        <s:element minOccurs="0" name="inPaisOrigem" type="s:string"/>
                        <s:element minOccurs="0" name="inRGRNE01" type="s:string"/>
                        <s:element minOccurs="0" name="inRGRNE02" type="s:string"/>
                        <s:element minOccurs="0" name="inRGRNE03" type="s:string"/>
                        <s:element minOccurs="0" name="inUFNasc" type="s:string"/>
                        <s:element minOccurs="0" name="inCPF" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="CertidaoAntiga" type="s0:CertidaoAntiga"/>
                        <s:element minOccurs="0" name="CertidaoNova" type="s0:CertidaoNova"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="CertidaoAntiga">
                <s:complexContent>
                    <s:extension base="s0:TipoCertidao">
                        <s:sequence>
                            <s:element minOccurs="0" name="inFolha" type="s:string"/>
                            <s:element minOccurs="0" name="inMunicipioComarca">
                                <s:simpleType>
                                    <s:restriction base="s:string">
                                        <s:maxLength value="100"/>
                                    </s:restriction>
                                </s:simpleType>
                            </s:element>
                            <s:element minOccurs="0" name="inUFComarca" type="s:string"/>
                            <s:element minOccurs="0" name="inLivro" type="s:string"/>
                            <s:element minOccurs="0" name="inDistritoCertidao">
                                <s:simpleType>
                                    <s:restriction base="s:string">
                                        <s:maxLength value="100"/>
                                    </s:restriction>
                                </s:simpleType>
                            </s:element>
                            <s:element minOccurs="0" name="inNumCertidao" type="s:string"/>
                            <s:element minOccurs="0" name="inAnoEmisCertidao" type="s:string"/>
                            <s:element minOccurs="0" name="inDiaEmisCertidao" type="s:string"/>
                            <s:element minOccurs="0" name="inMesEmisCertidao" type="s:string"/>
                        </s:sequence>
                    </s:extension>
                </s:complexContent>
            </s:complexType>
            <s:complexType abstract="true" name="TipoCertidao"/>
            <s:complexType name="CertidaoNova">
                <s:complexContent>
                    <s:extension base="s0:TipoCertidao">
                        <s:sequence>
                            <s:element minOccurs="0" name="inAnoEmisCertMatr" type="s:string"/>
                            <s:element minOccurs="0" name="inCertMatr01" type="s:string"/>
                            <s:element minOccurs="0" name="inCertMatr02" type="s:string"/>
                            <s:element minOccurs="0" name="inCertMatr03" type="s:string"/>
                            <s:element minOccurs="0" name="inCertMatr04" type="s:string"/>
                            <s:element minOccurs="0" name="inCertMatr05" type="s:string"/>
                            <s:element minOccurs="0" name="inCertMatr06" type="s:string"/>
                            <s:element minOccurs="0" name="inCertMatr07" type="s:string"/>
                            <s:element minOccurs="0" name="inCertMatr08" type="s:string"/>
                            <s:element minOccurs="0" name="inCertMatr09" type="s:string"/>
                            <s:element minOccurs="0" name="inDiaEmisCertMatr" type="s:string"/>
                            <s:element minOccurs="0" name="inMesEmisCertMatr" type="s:string"/>
                        </s:sequence>
                    </s:extension>
                </s:complexContent>
            </s:complexType>
            <s:element name="AlterarDocumentosFichaAlunoResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Erro" type="s:string"/>
                        <s:element minOccurs="0" name="Sucesso" type="s:string"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="AlterarEnderecoFichaAluno">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inBairro" type="s:string"/>
                        <s:element minOccurs="0" name="inCep" type="s:string"/>
                        <s:element minOccurs="0" name="inCidade" type="s:string"/>
                        <s:element minOccurs="0" name="inComplemento" type="s:string"/>
                        <s:element minOccurs="0" name="inDDD" type="s:string"/>
                        <s:element minOccurs="0" name="inFoneRecados" type="s:string"/>
                        <s:element minOccurs="0" name="inFoneResidencial" type="s:string"/>
                        <s:element minOccurs="0" name="inLogradouro" type="s:string"/>
                        <s:element minOccurs="0" name="inNomeFoneRecado" type="s:string"/>
                        <s:element minOccurs="0" name="inNumero" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoLogradouro" type="s:string"/>
                        <s:element minOccurs="0" name="inUF" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inSMS" type="s:string"/>
                        <s:element minOccurs="0" name="inDDDCel" type="s:string"/>
                        <s:element minOccurs="0" name="inFoneCel" type="s:string"/>
                        <s:element minOccurs="0" name="inLatitude" type="s:string"/>
                        <s:element minOccurs="0" name="inLongitude" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="AlterarEnderecoFichaAlunoResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="outErro" type="s:string"/>
                        <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="AlterarEnderecoIndicativo">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inBairro" type="s:string"/>
                        <s:element minOccurs="0" name="inCep" type="s:string"/>
                        <s:element minOccurs="0" name="inCidade" type="s:string"/>
                        <s:element minOccurs="0" name="inDDD" type="s:string"/>
                        <s:element minOccurs="0" name="inFoneResidencial" type="s:string"/>
                        <s:element minOccurs="0" name="inLogradouro" type="s:string"/>
                        <s:element minOccurs="0" name="inNumero" type="s:string"/>
                        <s:element minOccurs="0" name="inRecados" type="s:string"/>
                        <s:element minOccurs="0" name="inUF" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="AlterarEnderecoIndicativoResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="outErro" type="s:string"/>
                        <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="AssociarIrmao">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmaoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmaoDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmaoUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="AssociarIrmaoResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgAssociarIrmaoMsgAssociarIrmao"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgAssociarIrmaoMsgAssociarIrmao">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgAssociarIrmao" nillable="true" type="s0:MsgAssociarIrmao"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgAssociarIrmao">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="BaixarMatrFalecimentoRA">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inAnoFalecimento" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaFalecimento" type="s:string"/>
                        <s:element minOccurs="0" name="inMesFalecimento" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="BaixarMatrFalecimentoRAResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfBxMatrFalecimentoRABxMatrFalecimentoRA"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfBxMatrFalecimentoRABxMatrFalecimentoRA">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="BxMatrFalecimentoRA" nillable="true" type="s0:BxMatrFalecimentoRA"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="BxMatrFalecimentoRA">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="BaixarMatriculaTransferencia">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUF" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaTransferencia" type="s:string"/>
                        <s:element minOccurs="0" name="inMesTransferencia" type="s:string"/>
                        <s:element minOccurs="0" name="inMotivo" type="s:string"/>
                        <s:element minOccurs="0" name="TipoEnsino" type="s:string"/>
                        <s:element minOccurs="0" name="Serie" type="s:string"/>
                        <s:element minOccurs="0" name="inCodEscola" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="BaixarMatriculaTransferenciaResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfBaixarMatrTransferenciaBaixarMatrTransferencia"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfBaixarMatrTransferenciaBaixarMatrTransferencia">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="BaixarMatrTransferencia" nillable="true" type="s0:BaixarMatrTransferencia"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="BaixarMatrTransferencia">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="CancelarEncerramentoRendEscolar">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inCodEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inSemestre" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="CancelarEncerramentoRendEscolarResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                        <s:element minOccurs="0" name="outErro" type="s:string"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="CancelarInscricaoAlunoPorDeslocamento">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="CancelarInscricaoAlunoPorDeslocamentoResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgCancelarInscricaolnPrDslcmntMsgCancelarInscricaolnPrDslcmnt"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgCancelarInscricaolnPrDslcmntMsgCancelarInscricaolnPrDslcmnt">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgCancelarInscricaolnPrDslcmnt" nillable="true" type="s0:MsgCancelarInscricaolnPrDslcmnt"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgCancelarInscricaolnPrDslcmnt">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="CancelarInscricaoAlunoTransf">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="inEscola" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="CancelarInscricaoAlunoTransfResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgCancelarInscricaoAlunoTransferenciaMsgCancelarInscricaoAlunoTransferencia"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgCancelarInscricaoAlunoTransferenciaMsgCancelarInscricaoAlunoTransferencia">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgCancelarInscricaoAlunoTransferencia" nillable="true" type="s0:MsgCancelarInscricaoAlunoTransferencia"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgCancelarInscricaoAlunoTransferencia">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="CancelarInscricaoDefinicao">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="inFase" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="CancelarInscricaoDefinicaoResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgCancelarInscricaoDefinicaoMsgCancelarInscricaoDefinicao"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgCancelarInscricaoDefinicaoMsgCancelarInscricaoDefinicao">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgCancelarInscricaoDefinicao" nillable="true" type="s0:MsgCancelarInscricaoDefinicao"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgCancelarInscricaoDefinicao">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="CancelarIntencaoTransferencia">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="CancelarIntencaoTransferenciaResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgCancelarIntencaoTransferenciaMsgCancelarIntencaoTransferencia"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgCancelarIntencaoTransferenciaMsgCancelarIntencaoTransferencia">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgCancelarIntencaoTransferencia" nillable="true" type="s0:MsgCancelarIntencaoTransferencia"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgCancelarIntencaoTransferencia">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="CancelarTerminoDigitacao">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inCodEscola" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="CancelarTerminoDigitacaoResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                        <s:element minOccurs="0" name="outErro" type="s:string"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ClassificarGerarNrChamadaClassePorEscola">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="inCodigoEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inOrdemClassificacao" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoEnsino" type="s:string"/>
                        <s:element minOccurs="0" name="inSerieAno" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ClassificarGerarNrChamadaClassePorEscolaResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                        <s:element minOccurs="0" name="outErro" type="s:string"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ClassificarGerarNumeroChamadaPorClasse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="inNumClasse" type="s:string"/>
                        <s:element minOccurs="0" name="inOrdemClassificacao" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ClassificarGerarNumeroChamadaPorClasseResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                        <s:element minOccurs="0" name="outErro" type="s:string"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ConsultaClasseAlunoPorEscola">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inAnoLetivo" type="s:string"/>
                        <s:element minOccurs="0" name="inCodEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inSemestre" type="s:string"/>
                        <s:element minOccurs="0" name="inSerieAno" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoEnsino" type="s:string"/>
                        <s:element minOccurs="0" name="inTurma" type="s:string"/>
                        <s:element minOccurs="0" name="inTurno" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ConsultaClasseAlunoPorEscolaResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="outErro" type="s:string"/>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgConsultaClasseAlunoPorEscolaMsgConsultaClasseAlunoPorEscola"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgConsultaClasseAlunoPorEscolaMsgConsultaClasseAlunoPorEscola">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgConsultaClasseAlunoPorEscola" nillable="true" type="s0:MsgConsultaClasseAlunoPorEscola"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgConsultaClasseAlunoPorEscola">
                <s:sequence>
                    <s:element minOccurs="0" name="outCodEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outCodHabilit" type="s:string"/>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outNrClasse" type="s:string"/>
                    <s:element minOccurs="0" name="outQtdeAban" type="s:string"/>
                    <s:element minOccurs="0" name="outQtdeAtual" type="s:string"/>
                    <s:element minOccurs="0" name="outQtdeCad" type="s:string"/>
                    <s:element minOccurs="0" name="outQtdeCes" type="s:string"/>
                    <s:element minOccurs="0" name="outQtdeOutros" type="s:string"/>
                    <s:element minOccurs="0" name="outQtdeRecla" type="s:string"/>
                    <s:element minOccurs="0" name="outQtdeReman" type="s:string"/>
                    <s:element minOccurs="0" name="outQtdeTransf" type="s:string"/>
                    <s:element minOccurs="0" name="outSemestre" type="s:string"/>
                    <s:element minOccurs="0" name="outSerieAno" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoClasse" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outTurma" type="s:string"/>
                    <s:element minOccurs="0" name="outTurno" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="ConsultaFormacaoClasse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inNumClasse" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ConsultaFormacaoClasseResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfConsultaClasseConsultaClasse"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfConsultaClasseConsultaClasse">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="ConsultaClasse" nillable="true" type="s0:ConsultaClasse"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="ConsultaClasse">
                <s:sequence>
                    <s:element minOccurs="0" name="RA" type="s:string"/>
                    <s:element minOccurs="0" name="UF" type="s:string"/>
                    <s:element minOccurs="0" name="digitoRA" type="s:string"/>
                    <s:element minOccurs="0" name="nomeAluno" type="s:string"/>
                    <s:element minOccurs="0" name="numero" type="s:string"/>
                    <s:element minOccurs="0" name="outAbandono" type="s:string"/>
                    <s:element minOccurs="0" name="outAno" type="s:string"/>
                    <s:element minOccurs="0" name="outAtual" type="s:string"/>
                    <s:element minOccurs="0" name="outCadastrado" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outHorario" type="s:string"/>
                    <s:element minOccurs="0" name="outNaoCompareceu" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outNumClasse" type="s:string"/>
                    <s:element minOccurs="0" name="outNumSala" type="s:string"/>
                    <s:element minOccurs="0" name="outOutros" type="s:string"/>
                    <s:element minOccurs="0" name="outRemanejado" type="s:string"/>
                    <s:element minOccurs="0" name="outSerie" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outTransferido" type="s:string"/>
                    <s:element minOccurs="0" name="outTurma" type="s:string"/>
                    <s:element minOccurs="0" name="outTurno" type="s:string"/>
                    <s:element minOccurs="0" name="status" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoClasse" type="s:string"/>
                    <s:element minOccurs="0" name="seriemulti" type="s:string"/>
                    <s:element minOccurs="0" name="tipoensinomulti" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="ConsultarCep">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inCep" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ConsultarCepResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="outLocal" type="s:string"/>
                        <s:element minOccurs="0" name="outUF" type="s:string"/>
                        <s:element minOccurs="0" name="outTipoLogradouro" type="s:string"/>
                        <s:element minOccurs="0" name="outNomeLogradouro" type="s:string"/>
                        <s:element minOccurs="0" name="outComplemento" type="s:string"/>
                        <s:element minOccurs="0" name="outNomeAbreviado" type="s:string"/>
                        <s:element minOccurs="0" name="outCEP" type="s:string"/>
                        <s:element minOccurs="0" name="outBairroInicio" type="s:string"/>
                        <s:element minOccurs="0" name="outBairroFinal" type="s:string"/>
                        <s:element minOccurs="0" name="outDtAlteracao" type="s:string"/>
                        <s:element minOccurs="0" name="outErro" type="s:string"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ConsultarCepLogradouro">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inNomeLocal" type="s:string"/>
                        <s:element minOccurs="0" name="inNomeLogradouro" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ConsultarCepLogradouroResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgConsultaCEPLogradouroMsgConsultaCEPLogradouro"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgConsultaCEPLogradouroMsgConsultaCEPLogradouro">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgConsultaCEPLogradouro" nillable="true" type="s0:MsgConsultaCEPLogradouro"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgConsultaCEPLogradouro">
                <s:sequence>
                    <s:element minOccurs="0" name="outCep" type="s:string"/>
                    <s:element minOccurs="0" name="outComplementoBairro" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeLogradouro" type="s:string"/>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="ConsultarColetaClasse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="inNumClasse" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ConsultarColetaClasseResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgConsultaColetaClasseMsgConsultaColetaClasse"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgConsultaColetaClasseMsgConsultaColetaClasse">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgConsultaColetaClasse" nillable="true" type="s0:MsgConsultaColetaClasse"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgConsultaColetaClasse">
                <s:sequence>
                    <s:element minOccurs="0" name="outAEE01" type="s:string"/>
                    <s:element minOccurs="0" name="outAEE02" type="s:string"/>
                    <s:element minOccurs="0" name="outAEE03" type="s:string"/>
                    <s:element minOccurs="0" name="outAEE04" type="s:string"/>
                    <s:element minOccurs="0" name="outAEE05" type="s:string"/>
                    <s:element minOccurs="0" name="outAEE06" type="s:string"/>
                    <s:element minOccurs="0" name="outAEE07" type="s:string"/>
                    <s:element minOccurs="0" name="outAEE08" type="s:string"/>
                    <s:element minOccurs="0" name="outAEE09" type="s:string"/>
                    <s:element minOccurs="0" name="outAEE10" type="s:string"/>
                    <s:element minOccurs="0" name="outAEE11" type="s:string"/>
                    <s:element minOccurs="0" name="outCapacidadeFisica" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInicio" type="s:string"/>
                    <s:element minOccurs="0" name="outDescrHabillit" type="s:string"/>
                    <s:element minOccurs="0" name="outDescrNivel" type="s:string"/>
                    <s:element minOccurs="0" name="outDescrPreench" type="s:string"/>
                    <s:element minOccurs="0" name="outDescrProgEstadual" type="s:string"/>
                    <s:element minOccurs="0" name="outDescrSala" type="s:string"/>
                    <s:element minOccurs="0" name="outDescrSerie" type="s:string"/>
                    <s:element minOccurs="0" name="outDescrSituacaoClasse" type="s:string"/>
                    <s:element minOccurs="0" name="outDescrTipoEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outDescrTurma" type="s:string"/>
                    <s:element minOccurs="0" name="outDescrTurno" type="s:string"/>
                    <s:element minOccurs="0" name="outDomingo" type="s:string"/>
                    <s:element minOccurs="0" name="outDurClasse" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outHabilitacao" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFinal" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicial" type="s:string"/>
                    <s:element minOccurs="0" name="outInicioAula" type="s:string"/>
                    <s:element minOccurs="0" name="outNivel" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeUnidade" type="s:string"/>
                    <s:element minOccurs="0" name="outNumeroSala" type="s:string"/>
                    <s:element minOccurs="0" name="outProgMaisEduc" type="s:string"/>
                    <s:element minOccurs="0" name="outQtdAlunoClasse" type="s:string"/>
                    <s:element minOccurs="0" name="outQtdCargaHoraHabilitaca" type="s:string"/>
                    <s:element minOccurs="0" name="outQuartaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outQuintaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outSabado" type="s:string"/>
                    <s:element minOccurs="0" name="outSegundaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outSerieAno" type="s:string"/>
                    <s:element minOccurs="0" name="outSextaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outTercaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outTerminoAula" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoClasse" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outTurma" type="s:string"/>
                    <s:element minOccurs="0" name="outTurno" type="s:string"/>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outCodATC01" type="s:string"/>
                    <s:element minOccurs="0" name="outCodATC02" type="s:string"/>
                    <s:element minOccurs="0" name="outCodATC03" type="s:string"/>
                    <s:element minOccurs="0" name="outCodATC04" type="s:string"/>
                    <s:element minOccurs="0" name="outCodATC05" type="s:string"/>
                    <s:element minOccurs="0" name="outCodATC06" type="s:string"/>
                    <s:element minOccurs="0" name="outDescrATC01" type="s:string"/>
                    <s:element minOccurs="0" name="outDescrATC02" type="s:string"/>
                    <s:element minOccurs="0" name="outDescrATC03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescrATC04" type="s:string"/>
                    <s:element minOccurs="0" name="outDescrATC05" type="s:string"/>
                    <s:element minOccurs="0" name="outDescrATC06" type="s:string"/>
                    <s:element minOccurs="0" name="outClasseConvenioEst" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="ConsultarDefinicaoInscricaoRA">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDefinicaoInscricao" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ConsultarDefinicaoInscricaoRAResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:Response"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="Response">
                <s:complexContent>
                    <s:extension base="s0:Ens_Response">
                        <s:sequence>
                            <s:element minOccurs="0" name="outAnoLetivo" type="s:string"/>
                            <s:element minOccurs="0" name="outCidadeInscricao" type="s:string"/>
                            <s:element minOccurs="0" name="outCidadeMatricula" type="s:string"/>
                            <s:element minOccurs="0" name="outCodDiretoriaInscricao" type="s:string"/>
                            <s:element minOccurs="0" name="outCodDiretoriaMatricula" type="s:string"/>
                            <s:element minOccurs="0" name="outCodEscolaInscricao" type="s:string"/>
                            <s:element minOccurs="0" name="outCodEscolaMatricula" type="s:string"/>
                            <s:element minOccurs="0" name="outDataInscricaoInscricao" type="s:string"/>
                            <s:element minOccurs="0" name="outDataInscricaoMatricula" type="s:string"/>
                            <s:element minOccurs="0" name="outDataNascimento" type="s:string"/>
                            <s:element minOccurs="0" name="outDescSerieInscricao" type="s:string"/>
                            <s:element minOccurs="0" name="outDescSerieMatricula" type="s:string"/>
                            <s:element minOccurs="0" name="outDescTipoEnsinoInscricao" type="s:string"/>
                            <s:element minOccurs="0" name="outDescTipoEnsinoMatricula" type="s:string"/>
                            <s:element minOccurs="0" name="outDigitoRA" type="s:string"/>
                            <s:element minOccurs="0" name="outMotivoInscricao" type="s:string"/>
                            <s:element minOccurs="0" name="outNome" type="s:string"/>
                            <s:element minOccurs="0" name="outNomeDiretoriaInscricao" type="s:string"/>
                            <s:element minOccurs="0" name="outNomeDiretoriaMatricula" type="s:string"/>
                            <s:element minOccurs="0" name="outNomeEscolaInscricao" type="s:string"/>
                            <s:element minOccurs="0" name="outNomeEscolaMatricula" type="s:string"/>
                            <s:element minOccurs="0" name="outRA" type="s:string"/>
                            <s:element minOccurs="0" name="outRedeEnsinoInscricao" type="s:string"/>
                            <s:element minOccurs="0" name="outRedeEnsinoMatricula" type="s:string"/>
                            <s:element minOccurs="0" name="outUFRA" type="s:string"/>
                            <s:element minOccurs="0" name="outErro" type="s:string"/>
                        </s:sequence>
                    </s:extension>
                </s:complexContent>
            </s:complexType>
            <s:complexType name="Ens_Response">
                <s:complexContent>
                    <s:extension base="s0:Ens_Messagebody"/>
                </s:complexContent>
            </s:complexType>
            <s:complexType name="Ens_Messagebody"/>
            <s:element name="ConsultarEscolaCIE">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inCodEscola" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ConsultarEscolaCIEResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfConsultEscolaCIEConsultEscolaCIE"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfConsultEscolaCIEConsultEscolaCIE">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="ConsultEscolaCIE" nillable="true" type="s0:ConsultEscolaCIE"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="ConsultEscolaCIE">
                <s:sequence>
                    <s:element minOccurs="0" name="outAnoLetivo" type="s:string"/>
                    <s:element minOccurs="0" name="outAnoMunc" type="s:string"/>
                    <s:element minOccurs="0" name="outBairro" type="s:string"/>
                    <s:element minOccurs="0" name="outCaixaPostal" type="s:string"/>
                    <s:element minOccurs="0" name="outCategoriaEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outCep" type="s:string"/>
                    <s:element minOccurs="0" name="outCepCaixaPostal" type="s:string"/>
                    <s:element minOccurs="0" name="outCodDistrito" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscMun" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscolaVinc" type="s:string"/>
                    <s:element minOccurs="0" name="outCodIdentMunicipio" type="s:string"/>
                    <s:element minOccurs="0" name="outCodUnidAdm" type="s:string"/>
                    <s:element minOccurs="0" name="outDataAlteracao" type="s:string"/>
                    <s:element minOccurs="0" name="outDDD" type="s:string"/>
                    <s:element minOccurs="0" name="outDescAdicional" type="s:string"/>
                    <s:element minOccurs="0" name="outDescDepAdm" type="s:string"/>
                    <s:element minOccurs="0" name="outDescDiretoria01" type="s:string"/>
                    <s:element minOccurs="0" name="outDescDiretoria02" type="s:string"/>
                    <s:element minOccurs="0" name="outDescEnsino01" type="s:string"/>
                    <s:element minOccurs="0" name="outDescEnsino02" type="s:string"/>
                    <s:element minOccurs="0" name="outDescEnsino03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescEnsino04" type="s:string"/>
                    <s:element minOccurs="0" name="outDescEnsino05" type="s:string"/>
                    <s:element minOccurs="0" name="outDescEnsino06" type="s:string"/>
                    <s:element minOccurs="0" name="outDescEnsino07" type="s:string"/>
                    <s:element minOccurs="0" name="outDescEnsino08" type="s:string"/>
                    <s:element minOccurs="0" name="outDescRendimento" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTipoEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outDtFimDigRendimento" type="s:string"/>
                    <s:element minOccurs="0" name="outEmail" type="s:string"/>
                    <s:element minOccurs="0" name="outEndereco" type="s:string"/>
                    <s:element minOccurs="0" name="outEndZona" type="s:string"/>
                    <s:element minOccurs="0" name="outFax" type="s:string"/>
                    <s:element minOccurs="0" name="outLitCodUnid" type="s:string"/>
                    <s:element minOccurs="0" name="outLitMun" type="s:string"/>
                    <s:element minOccurs="0" name="outLitVinc" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAbrEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAbrEscolaVinc" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAbrMunicipio" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeDistrito" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outNumCaixaPostal" type="s:string"/>
                    <s:element minOccurs="0" name="outNumDiretoria01" type="s:string"/>
                    <s:element minOccurs="0" name="outNumDiretoria02" type="s:string"/>
                    <s:element minOccurs="0" name="outProgPAI" type="s:string"/>
                    <s:element minOccurs="0" name="outSitEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outTelefone01" type="s:string"/>
                    <s:element minOccurs="0" name="outTelefone02" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEscola" type="s:string"/>
                    <s:element minOccurs="0" name="aluno" type="s:string"/>
                    <s:element minOccurs="0" name="ano" type="s:string"/>
                    <s:element minOccurs="0" name="classe" type="s:string"/>
                    <s:element minOccurs="0" name="educEspec" type="s:string"/>
                    <s:element minOccurs="0" name="ejaFund" type="s:string"/>
                    <s:element minOccurs="0" name="ejaMedio" type="s:string"/>
                    <s:element minOccurs="0" name="fundamental1a4" type="s:string"/>
                    <s:element minOccurs="0" name="fundamental5a8" type="s:string"/>
                    <s:element minOccurs="0" name="fundamental9" type="s:string"/>
                    <s:element minOccurs="0" name="medio" type="s:string"/>
                    <s:element minOccurs="0" name="outros" type="s:string"/>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outConstr" type="s:string"/>
                    <s:element minOccurs="0" name="outRedeFisica" type="s:string"/>
                    <s:element minOccurs="0" name="educinfantil" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="ConsultarFichaAluno">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:choice minOccurs="0">
                            <s:element name="ConsultaFonetica" type="s0:ConsultaFonetica"/>
                            <s:element name="ConsultaRA" type="s0:ConsultaRA"/>
                        </s:choice>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ConsultaFonetica">
                <s:complexContent>
                    <s:extension base="s0:Consulta">
                        <s:sequence>
                            <s:element minOccurs="0" name="inNomeAluno">
                                <s:simpleType>
                                    <s:restriction base="s:string">
                                        <s:maxLength value="32000"/>
                                    </s:restriction>
                                </s:simpleType>
                            </s:element>
                            <s:element minOccurs="0" name="inNomeMae">
                                <s:simpleType>
                                    <s:restriction base="s:string">
                                        <s:maxLength value="32000"/>
                                    </s:restriction>
                                </s:simpleType>
                            </s:element>
                            <s:element minOccurs="0" name="inDiaNascimento" type="s:string"/>
                            <s:element minOccurs="0" name="inMesNascimento" type="s:string"/>
                            <s:element minOccurs="0" name="inAnoNascimento" type="s:string"/>
                            <s:element minOccurs="0" name="inNomeSocial">
                                <s:simpleType>
                                    <s:restriction base="s:string">
                                        <s:maxLength value="32000"/>
                                    </s:restriction>
                                </s:simpleType>
                            </s:element>
                        </s:sequence>
                    </s:extension>
                </s:complexContent>
            </s:complexType>
            <s:complexType abstract="true" name="Consulta"/>
            <s:complexType name="ConsultaRA">
                <s:complexContent>
                    <s:extension base="s0:Consulta">
                        <s:sequence>
                            <s:element minOccurs="0" name="inRA" type="s:string"/>
                            <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                            <s:element minOccurs="0" name="inUF" type="s:string"/>
                        </s:sequence>
                    </s:extension>
                </s:complexContent>
            </s:complexType>
            <s:element name="ConsultarFichaAlunoResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                        <s:element minOccurs="0" name="outErro" type="s:string"/>
                        <s:element minOccurs="0" name="FichasAluno" type="s0:ArrayOfFichaAlunoFichaAluno"/>
                        <s:element minOccurs="0" name="ListaAlunos" type="s0:ArrayOfListaAlunosListaAlunos"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfFichaAlunoFichaAluno">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="FichaAluno" nillable="true" type="s0:FichaAluno"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="FichaAluno">
                <s:sequence>
                    <s:element minOccurs="0" name="outBairro" type="s:string"/>
                    <s:element minOccurs="0" name="outBolsaFamilia" type="s:string"/>
                    <s:element minOccurs="0" name="outCEP" type="s:string"/>
                    <s:element minOccurs="0" name="outCPF" type="s:string"/>
                    <s:element minOccurs="0" name="outCidade" type="s:string"/>
                    <s:element minOccurs="0" name="outDeficiencias" type="s0:ArrayOfoutDeficienciasItemString"/>
                    <s:element minOccurs="0" name="outCodPaisOrigem" type="s:string"/>
                    <s:element minOccurs="0" name="outCorRaca" type="s:string"/>
                    <s:element minOccurs="0" name="outDDD" type="s:string"/>
                    <s:element minOccurs="0" name="outDataAlteracao" type="s:string"/>
                    <s:element minOccurs="0" name="outDataEmisRegNasc" type="s:string"/>
                    <s:element minOccurs="0" name="outDataEmissaoRG" type="s:string"/>
                    <s:element minOccurs="0" name="outDataEntrBrasil" type="s:string"/>
                    <s:element minOccurs="0" name="outDataNascimento" type="s:string"/>
                    <s:element minOccurs="0" name="outDescMunNasc" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRA" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRG" type="s:string"/>
                    <s:element minOccurs="0" name="outEndereco" type="s:string"/>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outFoneRecado" type="s:string"/>
                    <s:element minOccurs="0" name="outFoneResidencial" type="s:string"/>
                    <s:element minOccurs="0" name="outGemeo" type="s:string"/>
                    <s:element minOccurs="0" name="outIdentAlunoMEC" type="s:string"/>
                    <s:element minOccurs="0" name="outMobilidadeReduzida" type="s:string"/>
                    <s:element minOccurs="0" name="outNacionalidade" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeMae" type="s:string"/>
                    <s:element minOccurs="0" name="outNomePai" type="s:string"/>
                    <s:element minOccurs="0" name="outNomePaisOrigem" type="s:string"/>
                    <s:element minOccurs="0" name="outNumNis" type="s:string"/>
                    <s:element minOccurs="0" name="outNumRG" type="s:string"/>
                    <s:element minOccurs="0" name="outNumRegNasc" type="s:string"/>
                    <s:element minOccurs="0" name="outObsRecado" type="s:string"/>
                    <s:element minOccurs="0" name="outOperador" type="s:string"/>
                    <s:element minOccurs="0" name="outRA" type="s:string"/>
                    <s:element minOccurs="0" name="outSexo" type="s:string"/>
                    <s:element minOccurs="0" name="outUF" type="s:string"/>
                    <s:element minOccurs="0" name="outUFNascimento" type="s:string"/>
                    <s:element minOccurs="0" name="outUFRA" type="s:string"/>
                    <s:element minOccurs="0" name="outUFRG" type="s:string"/>
                    <s:element minOccurs="0" name="outZona" type="s:string"/>
                    <s:element minOccurs="0" name="outCertidaoResp" type="s0:TipoCertidaoResp"/>
                    <s:element minOccurs="0" name="outAuxilioLeitor" type="s:string"/>
                    <s:element minOccurs="0" name="outAuxilioTranscricao" type="s:string"/>
                    <s:element minOccurs="0" name="outDDDCel" type="s:string"/>
                    <s:element minOccurs="0" name="outFoneCel" type="s:string"/>
                    <s:element minOccurs="0" name="outGuiaInterprete" type="s:string"/>
                    <s:element minOccurs="0" name="outInterpreteLibras" type="s:string"/>
                    <s:element minOccurs="0" name="outLeituraLabial" type="s:string"/>
                    <s:element minOccurs="0" name="outNenhum" type="s:string"/>
                    <s:element minOccurs="0" name="outProvaAmpliada" type="s:string"/>
                    <s:element minOccurs="0" name="outProvaBraile" type="s:string"/>
                    <s:element minOccurs="0" name="outSMS" type="s:string"/>
                    <s:element minOccurs="0" name="outTam16" type="s:string"/>
                    <s:element minOccurs="0" name="outTam20" type="s:string"/>
                    <s:element minOccurs="0" name="outTam24" type="s:string"/>
                    <s:element minOccurs="0" name="outCuidador" type="s:string"/>
                    <s:element minOccurs="0" name="outEmail" type="s:string"/>
                    <s:element minOccurs="0" name="outIrmaos" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeSocial" type="s:string"/>
                    <s:element minOccurs="0" name="outProfisSaude" type="s:string"/>
                    <s:element minOccurs="0" name="outQuilombola" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="ArrayOfoutDeficienciasItemString">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="outDeficienciasItem" nillable="true" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="CertidaoAntigaResp">
                <s:complexContent>
                    <s:extension base="s0:TipoCertidaoResp">
                        <s:sequence>
                            <s:element minOccurs="0" name="outDescDistritoNasc" type="s:string"/>
                            <s:element minOccurs="0" name="outDescMunComarca" type="s:string"/>
                            <s:element minOccurs="0" name="outFolhaRegNum" type="s:string"/>
                            <s:element minOccurs="0" name="outNumLivroReg" type="s:string"/>
                            <s:element minOccurs="0" name="outUFComarca" type="s:string"/>
                        </s:sequence>
                    </s:extension>
                </s:complexContent>
            </s:complexType>
            <s:complexType abstract="true" name="TipoCertidaoResp"/>
            <s:complexType name="CertidaoNovaResp">
                <s:complexContent>
                    <s:extension base="s0:TipoCertidaoResp">
                        <s:sequence>
                            <s:element minOccurs="0" name="outCertMatr01" type="s:string"/>
                            <s:element minOccurs="0" name="outCertMatr02" type="s:string"/>
                            <s:element minOccurs="0" name="outCertMatr03" type="s:string"/>
                            <s:element minOccurs="0" name="outCertMatr04" type="s:string"/>
                            <s:element minOccurs="0" name="outCertMatr05" type="s:string"/>
                            <s:element minOccurs="0" name="outCertMatr06" type="s:string"/>
                            <s:element minOccurs="0" name="outCertMatr07" type="s:string"/>
                            <s:element minOccurs="0" name="outCertMatr08" type="s:string"/>
                            <s:element minOccurs="0" name="outCertMatr09" type="s:string"/>
                        </s:sequence>
                    </s:extension>
                </s:complexContent>
            </s:complexType>
            <s:complexType name="ArrayOfListaAlunosListaAlunos">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="ListaAlunos" nillable="true" type="s0:ListaAlunos"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="ListaAlunos">
                <s:sequence>
                    <s:element minOccurs="0" name="outRA" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRA" type="s:string"/>
                    <s:element minOccurs="0" name="outUFRA" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeMae" type="s:string"/>
                    <s:element minOccurs="0" name="outDataNascimento" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="ConsultarIrmao">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ConsultarIrmaoResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgConsultarIrmaoMsgConsultarIrmao"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgConsultarIrmaoMsgConsultarIrmao">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgConsultarIrmao" nillable="true" type="s0:MsgConsultarIrmao"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgConsultarIrmao">
                <s:sequence>
                    <s:element minOccurs="0" name="RAIrmao" type="s:string"/>
                    <s:element minOccurs="0" name="UFIrmao" type="s:string"/>
                    <s:element minOccurs="0" name="dataNascimento" type="s:string"/>
                    <s:element minOccurs="0" name="digitoRAIrmao" type="s:string"/>
                    <s:element minOccurs="0" name="gemeo" type="s:string"/>
                    <s:element minOccurs="0" name="nomeAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="ConsultarMatriculaClasseRA">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inNumClasse" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUF" type="s:string"/>
                        <s:element minOccurs="0" name="inSituacao" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ConsultarMatriculaClasseRAResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfConsultarMatrRAClasseConsultarMatrRAClasse"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfConsultarMatrRAClasseConsultarMatrRAClasse">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="ConsultarMatrRAClasse" nillable="true" type="s0:ConsultarMatrRAClasse"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="ConsultarMatrRAClasse">
                <s:sequence>
                    <s:element minOccurs="0" name="outAnoLetivo" type="s:string"/>
                    <s:element minOccurs="0" name="outAnoLetivo03" type="s:string"/>
                    <s:element minOccurs="0" name="outAnoLetivo05" type="s:string"/>
                    <s:element minOccurs="0" name="outAnoLetivo06" type="s:string"/>
                    <s:element minOccurs="0" name="outAnoLetivo08" type="s:string"/>
                    <s:element minOccurs="0" name="outAula1" type="s:string"/>
                    <s:element minOccurs="0" name="outAula2" type="s:string"/>
                    <s:element minOccurs="0" name="outAula3" type="s:string"/>
                    <s:element minOccurs="0" name="outCargaHora" type="s:string"/>
                    <s:element minOccurs="0" name="outCategoria08" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscola03" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscola04" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscola05" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscola06" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscola08" type="s:string"/>
                    <s:element minOccurs="0" name="outCodSituacaoAluno04" type="s:string"/>
                    <s:element minOccurs="0" name="outCodSituacaoAluno08" type="s:string"/>
                    <s:element minOccurs="0" name="outCodUnidade05" type="s:string"/>
                    <s:element minOccurs="0" name="outCodUnidade06" type="s:string"/>
                    <s:element minOccurs="0" name="outCodUnidade08" type="s:string"/>
                    <s:element minOccurs="0" name="outData" type="s:string"/>
                    <s:element minOccurs="0" name="outDataAlter" type="s:string"/>
                    <s:element minOccurs="0" name="outDataAlter03" type="s:string"/>
                    <s:element minOccurs="0" name="outDataAlter04" type="s:string"/>
                    <s:element minOccurs="0" name="outDataAlter05" type="s:string"/>
                    <s:element minOccurs="0" name="outDataAlter06" type="s:string"/>
                    <s:element minOccurs="0" name="outDataAlter08" type="s:string"/>
                    <s:element minOccurs="0" name="outDataFim" type="s:string"/>
                    <s:element minOccurs="0" name="outDataFim03" type="s:string"/>
                    <s:element minOccurs="0" name="outDataFim04" type="s:string"/>
                    <s:element minOccurs="0" name="outDataFimAula08" type="s:string"/>
                    <s:element minOccurs="0" name="outDataFimMatric05" type="s:string"/>
                    <s:element minOccurs="0" name="outDataFimMatric06" type="s:string"/>
                    <s:element minOccurs="0" name="outDataFimMatric08" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInclusaoCenso" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInicio" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInicio03" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInicio04" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInicioAula08" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInicioMatric05" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInicioMatric08" type="s:string"/>
                    <s:element minOccurs="0" name="outDataNascimento" type="s:string"/>
                    <s:element minOccurs="0" name="outDataNascimento03" type="s:string"/>
                    <s:element minOccurs="0" name="outDataNascimento04" type="s:string"/>
                    <s:element minOccurs="0" name="outDataNascimento05" type="s:string"/>
                    <s:element minOccurs="0" name="outDataNascimento06" type="s:string"/>
                    <s:element minOccurs="0" name="outDataNascimento08" type="s:string"/>
                    <s:element minOccurs="0" name="outDatainicioMatric06" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHab" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHab03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHab04" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHabNivel03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHoraFinal" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHoraFinal03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHoraInicial" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHoraInicial03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescNivelEnsino03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescNivelSerie03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescRend" type="s:string"/>
                    <s:element minOccurs="0" name="outDescRend06" type="s:string"/>
                    <s:element minOccurs="0" name="outDescSerie03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescSerie04" type="s:string"/>
                    <s:element minOccurs="0" name="outDescSerie05" type="s:string"/>
                    <s:element minOccurs="0" name="outDescSituacao05" type="s:string"/>
                    <s:element minOccurs="0" name="outDescSituacao06" type="s:string"/>
                    <s:element minOccurs="0" name="outDescSituacaoAluno08" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTipoEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTipoEnsino03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTipoEnsino04" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTipoEnsino05" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTipoEnsino06" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTipoEnsino08" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTurma" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTurma03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTurno03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTurno06" type="s:string"/>
                    <s:element minOccurs="0" name="outDescricaoTurno" type="s:string"/>
                    <s:element minOccurs="0" name="outDiaAula1" type="s:string"/>
                    <s:element minOccurs="0" name="outDiaAula2" type="s:string"/>
                    <s:element minOccurs="0" name="outDiaAula3" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRA" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRA03" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRA04" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRA05" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRA06" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRA08" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRG08" type="s:string"/>
                    <s:element minOccurs="0" name="outDomingo" type="s:string"/>
                    <s:element minOccurs="0" name="outDtaConfirmada04" type="s:string"/>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outGenero08" type="s:string"/>
                    <s:element minOccurs="0" name="outHab" type="s:string"/>
                    <s:element minOccurs="0" name="outHab03" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraAlter" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraAlter03" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraAlter04" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraAlter05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraAlter06" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraAlter08" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimDomingo05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimQuarta05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimQuinta05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimSabado05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimSegunda05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimSexta05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimTerca05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFinal" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFinal03" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFinalAula1" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFinalAula2" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFinalAula3" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicAula1" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicAula2" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicAula3" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicDomingo05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicQuarta05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicQuinta05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicSabado05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicSegunda05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicSexta05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicTerca05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicial" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicial03" type="s:string"/>
                    <s:element minOccurs="0" name="outIdentHab04" type="s:string"/>
                    <s:element minOccurs="0" name="outIdentHabNivel03" type="s:string"/>
                    <s:element minOccurs="0" name="outIdentSerie04" type="s:string"/>
                    <s:element minOccurs="0" name="outLitCenso" type="s:string"/>
                    <s:element minOccurs="0" name="outLitHab04" type="s:string"/>
                    <s:element minOccurs="0" name="outLitRend06" type="s:string"/>
                    <s:element minOccurs="0" name="outLitSerie04" type="s:string"/>
                    <s:element minOccurs="0" name="outLitSituacao05" type="s:string"/>
                    <s:element minOccurs="0" name="outLitTransf" type="s:string"/>
                    <s:element minOccurs="0" name="outLitTransf03" type="s:string"/>
                    <s:element minOccurs="0" name="outLitTransf04" type="s:string"/>
                    <s:element minOccurs="0" name="outLitTransp" type="s:string"/>
                    <s:element minOccurs="0" name="outModalidade08" type="s:string"/>
                    <s:element minOccurs="0" name="outModulo06" type="s:string"/>
                    <s:element minOccurs="0" name="outMsg08" type="s:string"/>
                    <s:element minOccurs="0" name="outNivelEnsino03" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAbrevEscola03" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAbrevEscola04" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAbrevEscola05" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAbrevEscola06" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAbrevEscola08" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAluno03" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAluno04" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAluno05" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAluno06" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAluno08" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeCategoria08" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeGenero08" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeMae" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeMae03" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeMae04" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeMae05" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeMae06" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeMae08" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeModalidade08" type="s:string"/>
                    <s:element minOccurs="0" name="outNomePai" type="s:string"/>
                    <s:element minOccurs="0" name="outNomePai03" type="s:string"/>
                    <s:element minOccurs="0" name="outNomePai04" type="s:string"/>
                    <s:element minOccurs="0" name="outNomePai05" type="s:string"/>
                    <s:element minOccurs="0" name="outNomePai06" type="s:string"/>
                    <s:element minOccurs="0" name="outNomePai08" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeUnidade05" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeUnidade06" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeUnidade08" type="s:string"/>
                    <s:element minOccurs="0" name="outNumAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outNumAluno03" type="s:string"/>
                    <s:element minOccurs="0" name="outNumAluno05" type="s:string"/>
                    <s:element minOccurs="0" name="outNumAluno06" type="s:string"/>
                    <s:element minOccurs="0" name="outNumAluno08" type="s:string"/>
                    <s:element minOccurs="0" name="outNumClasse" type="s:string"/>
                    <s:element minOccurs="0" name="outNumClasse03" type="s:string"/>
                    <s:element minOccurs="0" name="outNumClasse05" type="s:string"/>
                    <s:element minOccurs="0" name="outNumClasse06" type="s:string"/>
                    <s:element minOccurs="0" name="outNumClasse08" type="s:string"/>
                    <s:element minOccurs="0" name="outObservacao" type="s:string"/>
                    <s:element minOccurs="0" name="outObservacao04" type="s:string"/>
                    <s:element minOccurs="0" name="outOpcaoAlter" type="s:string"/>
                    <s:element minOccurs="0" name="outOpcaoAlter03" type="s:string"/>
                    <s:element minOccurs="0" name="outOpcaoAlter06" type="s:string"/>
                    <s:element minOccurs="0" name="outOpcaoAlter08" type="s:string"/>
                    <s:element minOccurs="0" name="outPeriodo06" type="s:string"/>
                    <s:element minOccurs="0" name="outQtCargaHoraHab" type="s:string"/>
                    <s:element minOccurs="0" name="outQuartaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outQuintaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outRA" type="s:string"/>
                    <s:element minOccurs="0" name="outRA03" type="s:string"/>
                    <s:element minOccurs="0" name="outRA04" type="s:string"/>
                    <s:element minOccurs="0" name="outRA05" type="s:string"/>
                    <s:element minOccurs="0" name="outRA06" type="s:string"/>
                    <s:element minOccurs="0" name="outRA08" type="s:string"/>
                    <s:element minOccurs="0" name="outRG08" type="s:string"/>
                    <s:element minOccurs="0" name="outSabado" type="s:string"/>
                    <s:element minOccurs="0" name="outSegundaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outSerie" type="s:string"/>
                    <s:element minOccurs="0" name="outSerie03" type="s:string"/>
                    <s:element minOccurs="0" name="outSerie05" type="s:string"/>
                    <s:element minOccurs="0" name="outSextaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outSituacao04" type="s:string"/>
                    <s:element minOccurs="0" name="outSituacao06" type="s:string"/>
                    <s:element minOccurs="0" name="outTercaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoClasse" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoClasse03" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEnsino03" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEnsino04" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEnsino05" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEnsino06" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEnsino08" type="s:string"/>
                    <s:element minOccurs="0" name="outTurma" type="s:string"/>
                    <s:element minOccurs="0" name="outTurma03" type="s:string"/>
                    <s:element minOccurs="0" name="outTurma05" type="s:string"/>
                    <s:element minOccurs="0" name="outTurma06" type="s:string"/>
                    <s:element minOccurs="0" name="outTurno" type="s:string"/>
                    <s:element minOccurs="0" name="outTurno03" type="s:string"/>
                    <s:element minOccurs="0" name="outTurno06" type="s:string"/>
                    <s:element minOccurs="0" name="outUF" type="s:string"/>
                    <s:element minOccurs="0" name="outUF03" type="s:string"/>
                    <s:element minOccurs="0" name="outUF04" type="s:string"/>
                    <s:element minOccurs="0" name="outUF05" type="s:string"/>
                    <s:element minOccurs="0" name="outUF06" type="s:string"/>
                    <s:element minOccurs="0" name="outUF08" type="s:string"/>
                    <s:element minOccurs="0" name="outUFRG08" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="ConsultarMatriculaPorClasse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUF" type="s:string"/>
                        <s:element minOccurs="0" name="inNumClasse" type="s:string"/>
                        <s:element minOccurs="0" name="inSituacao" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ConsultarMatriculaPorClasseResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfConsultarMatriculaPorClasseRecordConsultarMatriculaPorClasseRecord"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfConsultarMatriculaPorClasseRecordConsultarMatriculaPorClasseRecord">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="ConsultarMatriculaPorClasseRecord" nillable="true" type="s0:ConsultarMatriculaPorClasseRecord"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="ConsultarMatriculaPorClasseRecord">
                <s:sequence>
                    <s:element minOccurs="0" name="outAnoLetivo" type="s:string"/>
                    <s:element minOccurs="0" name="outData" type="s:string"/>
                    <s:element minOccurs="0" name="outDataFim" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInicio" type="s:string"/>
                    <s:element minOccurs="0" name="outDataNascimento" type="s:string"/>
                    <s:element minOccurs="0" name="outDescricaoTurno" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTipoEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRA" type="s:string"/>
                    <s:element minOccurs="0" name="outEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outHab" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFinal" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicial" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeMae" type="s:string"/>
                    <s:element minOccurs="0" name="outNomePai" type="s:string"/>
                    <s:element minOccurs="0" name="outNumAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outRA" type="s:string"/>
                    <s:element minOccurs="0" name="outSerie" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outTurma" type="s:string"/>
                    <s:element minOccurs="0" name="outTurno" type="s:string"/>
                    <s:element minOccurs="0" name="outUF" type="s:string"/>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outCargaHora" type="s:string"/>
                    <s:element minOccurs="0" name="outDataAlter" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHab" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHoraFinal" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHoraInicial" type="s:string"/>
                    <s:element minOccurs="0" name="outDescRend" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTurma" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraAlter" type="s:string"/>
                    <s:element minOccurs="0" name="outLitCenso" type="s:string"/>
                    <s:element minOccurs="0" name="outLitTransf" type="s:string"/>
                    <s:element minOccurs="0" name="outLitTransp" type="s:string"/>
                    <s:element minOccurs="0" name="outObservacao" type="s:string"/>
                    <s:element minOccurs="0" name="outOpcaoAlter" type="s:string"/>
                    <s:element minOccurs="0" name="outQtCargaHoraHab" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoClasse" type="s:string"/>
                    <s:element minOccurs="0" name="outAula2" type="s:string"/>
                    <s:element minOccurs="0" name="outAula3" type="s:string"/>
                    <s:element minOccurs="0" name="outDiaAula1" type="s:string"/>
                    <s:element minOccurs="0" name="outDiaAula2" type="s:string"/>
                    <s:element minOccurs="0" name="outDiaAula3" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFinalAula1" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFinalAula2" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFinalAula3" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicAula1" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicAula2" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicAula3" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInclusaoCenso" type="s:string"/>
                    <s:element minOccurs="0" name="outDomingo" type="s:string"/>
                    <s:element minOccurs="0" name="outQuartaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outQuintaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outSabado" type="s:string"/>
                    <s:element minOccurs="0" name="outSegundaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outSextaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outTercaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outNumClasse" type="s:string"/>
                    <s:element minOccurs="0" name="outPronatec" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHabNivel" type="s:string"/>
                    <s:element minOccurs="0" name="outIdentHabNivel" type="s:string"/>
                    <s:element minOccurs="0" name="outDescNivelSerie" type="s:string"/>
                    <s:element minOccurs="0" name="outDescNivelEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outDescSerie" type="s:string"/>
                    <s:element minOccurs="0" name="outNivelEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAbrevEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTurno" type="s:string"/>
                    <s:element minOccurs="0" name="outCodSituacaoAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outDataConfirmada" type="s:string"/>
                    <s:element minOccurs="0" name="outIdentHab" type="s:string"/>
                    <s:element minOccurs="0" name="outIdentSerie" type="s:string"/>
                    <s:element minOccurs="0" name="outLitHab" type="s:string"/>
                    <s:element minOccurs="0" name="outLitSerie" type="s:string"/>
                    <s:element minOccurs="0" name="outSituacao" type="s:string"/>
                    <s:element minOccurs="0" name="outCodUnidade" type="s:string"/>
                    <s:element minOccurs="0" name="outDataFimMatric" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInicioMatric" type="s:string"/>
                    <s:element minOccurs="0" name="outDescSituacao" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicTerca" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimDomingo" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimQuarta" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimQuinta" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimSabado" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimSegunda" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimSexta" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimTerca" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicDomingo" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicQuarta" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicQuinta" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicSabado" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicSegunda" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicSexta" type="s:string"/>
                    <s:element minOccurs="0" name="outLitSituacao" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeUnidade" type="s:string"/>
                    <s:element minOccurs="0" name="outAula1" type="s:string"/>
                    <s:element minOccurs="0" name="outLitRend" type="s:string"/>
                    <s:element minOccurs="0" name="outModalidade" type="s:string"/>
                    <s:element minOccurs="0" name="outModulo" type="s:string"/>
                    <s:element minOccurs="0" name="outPeriodo" type="s:string"/>
                    <s:element minOccurs="0" name="outCategoria" type="s:string"/>
                    <s:element minOccurs="0" name="outDataFimAula" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInicioAula" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRG" type="s:string"/>
                    <s:element minOccurs="0" name="outGenero" type="s:string"/>
                    <s:element minOccurs="0" name="outMsg" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeCategoria" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeGenero" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeModalidade" type="s:string"/>
                    <s:element minOccurs="0" name="outRG" type="s:string"/>
                    <s:element minOccurs="0" name="outUFRG" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="ConsultarMatriculas">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUF" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ConsultarMatriculasResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfConsultarMatriculasRecordConsultarMatriculasRecord"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfConsultarMatriculasRecordConsultarMatriculasRecord">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="ConsultarMatriculasRecord" nillable="true" type="s0:ConsultarMatriculasRecord"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="ConsultarMatriculasRecord">
                <s:sequence>
                    <s:element minOccurs="0" name="outAnoLetivo" type="s:string"/>
                    <s:element minOccurs="0" name="outData" type="s:string"/>
                    <s:element minOccurs="0" name="outDataFim" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInicio" type="s:string"/>
                    <s:element minOccurs="0" name="outDataNascimento" type="s:string"/>
                    <s:element minOccurs="0" name="outDescricaoTurno" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTipoEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRA" type="s:string"/>
                    <s:element minOccurs="0" name="outEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outHab" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFinal" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicial" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeMae" type="s:string"/>
                    <s:element minOccurs="0" name="outNomePai" type="s:string"/>
                    <s:element minOccurs="0" name="outNumAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outNumClasse" type="s:string"/>
                    <s:element minOccurs="0" name="outRA" type="s:string"/>
                    <s:element minOccurs="0" name="outSerie" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outTurma" type="s:string"/>
                    <s:element minOccurs="0" name="outTurno" type="s:string"/>
                    <s:element minOccurs="0" name="outUF" type="s:string"/>
                    <s:element minOccurs="0" name="AnoLetivo" type="s:string"/>
                    <s:element minOccurs="0" name="CodigoEscola" type="s:string"/>
                    <s:element minOccurs="0" name="DataMatricula" type="s:string"/>
                    <s:element minOccurs="0" name="Hab" type="s:string"/>
                    <s:element minOccurs="0" name="Numero" type="s:string"/>
                    <s:element minOccurs="0" name="NumeroClasse" type="s:string"/>
                    <s:element minOccurs="0" name="Serie" type="s:string"/>
                    <s:element minOccurs="0" name="TipoEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="Turma" type="s:string"/>
                    <s:element minOccurs="0" name="Turno" type="s:string"/>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outAlunoMatric" type="s:string"/>
                    <s:element minOccurs="0" name="outAlunoMatricDet" type="s:string"/>
                    <s:element minOccurs="0" name="outCodMunicipio" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInscricao" type="s:string"/>
                    <s:element minOccurs="0" name="outDDDAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outDescMunicipio" type="s:string"/>
                    <s:element minOccurs="0" name="outDescSerie" type="s:string"/>
                    <s:element minOccurs="0" name="outEnderecoAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outEndMatricula" type="s:string"/>
                    <s:element minOccurs="0" name="outFase" type="s:string"/>
                    <s:element minOccurs="0" name="outMsgCompartilhado" type="s:string"/>
                    <s:element minOccurs="0" name="outMsgCompartilhadoDet" type="s:string"/>
                    <s:element minOccurs="0" name="outNome" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outTelResidencial" type="s:string"/>
                    <s:element minOccurs="0" name="outUFAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outUFMatric" type="s:string"/>
                    <s:element minOccurs="0" name="outCargaHora" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHab" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHoraFinal" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHoraInicial" type="s:string"/>
                    <s:element minOccurs="0" name="outDescRend" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTurma" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraAlter" type="s:string"/>
                    <s:element minOccurs="0" name="outLitCenso" type="s:string"/>
                    <s:element minOccurs="0" name="outLitTransf" type="s:string"/>
                    <s:element minOccurs="0" name="outLitTransp" type="s:string"/>
                    <s:element minOccurs="0" name="outObservacao" type="s:string"/>
                    <s:element minOccurs="0" name="outOpcaoAlter" type="s:string"/>
                    <s:element minOccurs="0" name="outQtCargaHoraHab" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoClasse" type="s:string"/>
                    <s:element minOccurs="0" name="outDataAlter" type="s:string"/>
                    <s:element minOccurs="0" name="outAtende" type="s:string"/>
                    <s:element minOccurs="0" name="outBairro" type="s:string"/>
                    <s:element minOccurs="0" name="outCep" type="s:string"/>
                    <s:element minOccurs="0" name="outCidade" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscolaAloc" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscolaSugestao" type="s:string"/>
                    <s:element minOccurs="0" name="outDataAloc" type="s:string"/>
                    <s:element minOccurs="0" name="outEndreco" type="s:string"/>
                    <s:element minOccurs="0" name="outEndUF" type="s:string"/>
                    <s:element minOccurs="0" name="outLitClasse" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeEscolaAloc" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeEscolaSugestao" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeMunicipio" type="s:string"/>
                    <s:element minOccurs="0" name="outNumClasseAloc" type="s:string"/>
                    <s:element minOccurs="0" name="outSugestao" type="s:string"/>
                    <s:element minOccurs="0" name="SituacaoMatricula" type="s:string"/>
                    <s:element minOccurs="0" name="outCodSituacaoAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outDtaConfirmada" type="s:string"/>
                    <s:element minOccurs="0" name="outIdentHab" type="s:string"/>
                    <s:element minOccurs="0" name="outIdentSerie" type="s:string"/>
                    <s:element minOccurs="0" name="outLitHab" type="s:string"/>
                    <s:element minOccurs="0" name="outLitSerie" type="s:string"/>
                    <s:element minOccurs="0" name="outSituacao" type="s:string"/>
                    <s:element minOccurs="0" name="outDomingo" type="s:string"/>
                    <s:element minOccurs="0" name="outQuartaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outQuintaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outSabado" type="s:string"/>
                    <s:element minOccurs="0" name="outSegundaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outSextaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outTercaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outPronatec" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInclusaoCenso" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHabNivel" type="s:string"/>
                    <s:element minOccurs="0" name="outDescNivelEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outDescNivelSerie" type="s:string"/>
                    <s:element minOccurs="0" name="outIdentHabNivel" type="s:string"/>
                    <s:element minOccurs="0" name="outNivelEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAbrevEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outCodUnidade" type="s:string"/>
                    <s:element minOccurs="0" name="outDataFimMatric" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInicioMatric" type="s:string"/>
                    <s:element minOccurs="0" name="outDescSituacao" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimDomingo" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimQuarta" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimQuinta" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimSabado" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimSegunda" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimSexta" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimTerca" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicDomingo" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicQuarta" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicQuinta" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicSabado" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicSegunda" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicSexta" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicTerca" type="s:string"/>
                    <s:element minOccurs="0" name="outLitSituacao" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeUnidade" type="s:string"/>
                    <s:element minOccurs="0" name="outBairroAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outBairroMatric" type="s:string"/>
                    <s:element minOccurs="0" name="outCepAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outCepMatric" type="s:string"/>
                    <s:element minOccurs="0" name="outCidadeAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outCidadeMatric" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTurno" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="ConsultarMatriculasRA">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUF" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ConsultarMatriculasRAResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfConsultarMatricRAConsultarMatricRA"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfConsultarMatricRAConsultarMatricRA">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="ConsultarMatricRA" nillable="true" type="s0:ConsultarMatricRA"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="ConsultarMatricRA">
                <s:sequence>
                    <s:element minOccurs="0" name="anoLetivo" type="s:string"/>
                    <s:element minOccurs="0" name="codigoEscola" type="s:string"/>
                    <s:element minOccurs="0" name="dataMatricula" type="s:string"/>
                    <s:element minOccurs="0" name="hab" type="s:string"/>
                    <s:element minOccurs="0" name="numero" type="s:string"/>
                    <s:element minOccurs="0" name="numeroClasse" type="s:string"/>
                    <s:element minOccurs="0" name="outAlunoMatric" type="s:string"/>
                    <s:element minOccurs="0" name="outAlunoMatricDet" type="s:string"/>
                    <s:element minOccurs="0" name="outAnoLetivo" type="s:string"/>
                    <s:element minOccurs="0" name="outAnoLetivo02" type="s:string"/>
                    <s:element minOccurs="0" name="outAnoLetivo03" type="s:string"/>
                    <s:element minOccurs="0" name="outAnoLetivo05" type="s:string"/>
                    <s:element minOccurs="0" name="outAnoLetivo07" type="s:string"/>
                    <s:element minOccurs="0" name="outAtende07" type="s:string"/>
                    <s:element minOccurs="0" name="outBairro07" type="s:string"/>
                    <s:element minOccurs="0" name="outBairroAluno02" type="s:string"/>
                    <s:element minOccurs="0" name="outBairroMatric02" type="s:string"/>
                    <s:element minOccurs="0" name="outCargaHora" type="s:string"/>
                    <s:element minOccurs="0" name="outCep07" type="s:string"/>
                    <s:element minOccurs="0" name="outCepAluno02" type="s:string"/>
                    <s:element minOccurs="0" name="outCepMatric02" type="s:string"/>
                    <s:element minOccurs="0" name="outCidade07" type="s:string"/>
                    <s:element minOccurs="0" name="outCidadeAluno02" type="s:string"/>
                    <s:element minOccurs="0" name="outCidadeMatric02" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscola02" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscola03" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscola04" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscola05" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscola07" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscolaAloc07" type="s:string"/>
                    <s:element minOccurs="0" name="outCodEscolaSugestao07" type="s:string"/>
                    <s:element minOccurs="0" name="outCodMunicipio02" type="s:string"/>
                    <s:element minOccurs="0" name="outCodMunicipio07" type="s:string"/>
                    <s:element minOccurs="0" name="outCodSituacaoAluno04" type="s:string"/>
                    <s:element minOccurs="0" name="outCodUnidade05" type="s:string"/>
                    <s:element minOccurs="0" name="outDDDAluno02" type="s:string"/>
                    <s:element minOccurs="0" name="outData" type="s:string"/>
                    <s:element minOccurs="0" name="outDataAloc07" type="s:string"/>
                    <s:element minOccurs="0" name="outDataAlter" type="s:string"/>
                    <s:element minOccurs="0" name="outDataAlter03" type="s:string"/>
                    <s:element minOccurs="0" name="outDataAlter04" type="s:string"/>
                    <s:element minOccurs="0" name="outDataAlter05" type="s:string"/>
                    <s:element minOccurs="0" name="outDataFim" type="s:string"/>
                    <s:element minOccurs="0" name="outDataFim03" type="s:string"/>
                    <s:element minOccurs="0" name="outDataFim04" type="s:string"/>
                    <s:element minOccurs="0" name="outDataFimMatric05" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInclusaoCenso" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInicio" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInicio03" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInicio04" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInicioMatric05" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInscricao02" type="s:string"/>
                    <s:element minOccurs="0" name="outDataInscricao07" type="s:string"/>
                    <s:element minOccurs="0" name="outDataNascimento" type="s:string"/>
                    <s:element minOccurs="0" name="outDataNascimento02" type="s:string"/>
                    <s:element minOccurs="0" name="outDataNascimento03" type="s:string"/>
                    <s:element minOccurs="0" name="outDataNascimento04" type="s:string"/>
                    <s:element minOccurs="0" name="outDataNascimento05" type="s:string"/>
                    <s:element minOccurs="0" name="outDataNascimento07" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHab" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHab03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHab04" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHabNivel03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHoraFinal" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHoraFinal03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHoraInicial" type="s:string"/>
                    <s:element minOccurs="0" name="outDescHoraInicial03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescMunicipio02" type="s:string"/>
                    <s:element minOccurs="0" name="outDescNivelEnsino03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescNivelSerie03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescRend" type="s:string"/>
                    <s:element minOccurs="0" name="outDescSerie02" type="s:string"/>
                    <s:element minOccurs="0" name="outDescSerie03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescSerie04" type="s:string"/>
                    <s:element minOccurs="0" name="outDescSerie05" type="s:string"/>
                    <s:element minOccurs="0" name="outDescSerie07" type="s:string"/>
                    <s:element minOccurs="0" name="outDescSituacao05" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTipoEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTipoEnsino02" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTipoEnsino03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTipoEnsino04" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTipoEnsino05" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTipoEnsino07" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTurma" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTurma03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTurno03" type="s:string"/>
                    <s:element minOccurs="0" name="outDescTurno07" type="s:string"/>
                    <s:element minOccurs="0" name="outDescricaoTurno" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRA" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRA02" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRA03" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRA04" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRA05" type="s:string"/>
                    <s:element minOccurs="0" name="outDigitoRA07" type="s:string"/>
                    <s:element minOccurs="0" name="outDomingo" type="s:string"/>
                    <s:element minOccurs="0" name="outDtaConfirmada04" type="s:string"/>
                    <s:element minOccurs="0" name="outEndMatricula02" type="s:string"/>
                    <s:element minOccurs="0" name="outEndUF07" type="s:string"/>
                    <s:element minOccurs="0" name="outEnderecoAluno02" type="s:string"/>
                    <s:element minOccurs="0" name="outEndreco07" type="s:string"/>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outFase02" type="s:string"/>
                    <s:element minOccurs="0" name="outFase07" type="s:string"/>
                    <s:element minOccurs="0" name="outHab" type="s:string"/>
                    <s:element minOccurs="0" name="outHab03" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraAlter" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraAlter03" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraAlter04" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraAlter05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimDomingo05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimQuarta05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimQuinta05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimSabado05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimSegunda05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimSexta05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFimTerca05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFinal" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraFinal03" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicDomingo05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicQuarta05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicQuinta05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicSabado05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicSegunda05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicSexta05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicTerca05" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicial" type="s:string"/>
                    <s:element minOccurs="0" name="outHoraInicial03" type="s:string"/>
                    <s:element minOccurs="0" name="outIdentHab04" type="s:string"/>
                    <s:element minOccurs="0" name="outIdentHabNivel03" type="s:string"/>
                    <s:element minOccurs="0" name="outIdentSerie04" type="s:string"/>
                    <s:element minOccurs="0" name="outLitCenso" type="s:string"/>
                    <s:element minOccurs="0" name="outLitClasse07" type="s:string"/>
                    <s:element minOccurs="0" name="outLitHab04" type="s:string"/>
                    <s:element minOccurs="0" name="outLitSerie04" type="s:string"/>
                    <s:element minOccurs="0" name="outLitSituacao05" type="s:string"/>
                    <s:element minOccurs="0" name="outLitTransf" type="s:string"/>
                    <s:element minOccurs="0" name="outLitTransf03" type="s:string"/>
                    <s:element minOccurs="0" name="outLitTransf04" type="s:string"/>
                    <s:element minOccurs="0" name="outLitTransp" type="s:string"/>
                    <s:element minOccurs="0" name="outMsgCompartilhado" type="s:string"/>
                    <s:element minOccurs="0" name="outMsgCompartilhadoDet" type="s:string"/>
                    <s:element minOccurs="0" name="outNivelEnsino03" type="s:string"/>
                    <s:element minOccurs="0" name="outNome02" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAbrevEscola03" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAbrevEscola04" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAbrevEscola05" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAluno03" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAluno04" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAluno05" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeAluno07" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeEscola02" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeEscola07" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeEscolaAloc07" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeEscolaSugestao07" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeMae" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeMae02" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeMae03" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeMae04" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeMae05" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeMae07" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeMunicipio07" type="s:string"/>
                    <s:element minOccurs="0" name="outNomePai" type="s:string"/>
                    <s:element minOccurs="0" name="outNomePai02" type="s:string"/>
                    <s:element minOccurs="0" name="outNomePai03" type="s:string"/>
                    <s:element minOccurs="0" name="outNomePai04" type="s:string"/>
                    <s:element minOccurs="0" name="outNomePai05" type="s:string"/>
                    <s:element minOccurs="0" name="outNomePai07" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeUnidade05" type="s:string"/>
                    <s:element minOccurs="0" name="outNumAluno" type="s:string"/>
                    <s:element minOccurs="0" name="outNumAluno03" type="s:string"/>
                    <s:element minOccurs="0" name="outNumAluno05" type="s:string"/>
                    <s:element minOccurs="0" name="outNumClasse" type="s:string"/>
                    <s:element minOccurs="0" name="outNumClasse03" type="s:string"/>
                    <s:element minOccurs="0" name="outNumClasse05" type="s:string"/>
                    <s:element minOccurs="0" name="outNumClasseAloc07" type="s:string"/>
                    <s:element minOccurs="0" name="outObservacao" type="s:string"/>
                    <s:element minOccurs="0" name="outObservacao04" type="s:string"/>
                    <s:element minOccurs="0" name="outObservacao07" type="s:string"/>
                    <s:element minOccurs="0" name="outOpcaoAlter" type="s:string"/>
                    <s:element minOccurs="0" name="outOpcaoAlter03" type="s:string"/>
                    <s:element minOccurs="0" name="outPronatec" type="s:string"/>
                    <s:element minOccurs="0" name="outQtCargaHoraHab" type="s:string"/>
                    <s:element minOccurs="0" name="outQuartaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outQuintaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outRA" type="s:string"/>
                    <s:element minOccurs="0" name="outRA02" type="s:string"/>
                    <s:element minOccurs="0" name="outRA03" type="s:string"/>
                    <s:element minOccurs="0" name="outRA04" type="s:string"/>
                    <s:element minOccurs="0" name="outRA05" type="s:string"/>
                    <s:element minOccurs="0" name="outRA07" type="s:string"/>
                    <s:element minOccurs="0" name="outSabado" type="s:string"/>
                    <s:element minOccurs="0" name="outSegundaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outSerie" type="s:string"/>
                    <s:element minOccurs="0" name="outSerie02" type="s:string"/>
                    <s:element minOccurs="0" name="outSerie03" type="s:string"/>
                    <s:element minOccurs="0" name="outSerie05" type="s:string"/>
                    <s:element minOccurs="0" name="outSerie07" type="s:string"/>
                    <s:element minOccurs="0" name="outSextaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outSituacao04" type="s:string"/>
                    <s:element minOccurs="0" name="outSugestao07" type="s:string"/>
                    <s:element minOccurs="0" name="outTelResidencial02" type="s:string"/>
                    <s:element minOccurs="0" name="outTercaFeira" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoClasse" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoClasse03" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEnsino02" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEnsino03" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEnsino04" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEnsino05" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEnsino07" type="s:string"/>
                    <s:element minOccurs="0" name="outTurma" type="s:string"/>
                    <s:element minOccurs="0" name="outTurma03" type="s:string"/>
                    <s:element minOccurs="0" name="outTurma05" type="s:string"/>
                    <s:element minOccurs="0" name="outTurno" type="s:string"/>
                    <s:element minOccurs="0" name="outTurno03" type="s:string"/>
                    <s:element minOccurs="0" name="outTurno07" type="s:string"/>
                    <s:element minOccurs="0" name="outUF" type="s:string"/>
                    <s:element minOccurs="0" name="outUF02" type="s:string"/>
                    <s:element minOccurs="0" name="outUF03" type="s:string"/>
                    <s:element minOccurs="0" name="outUF04" type="s:string"/>
                    <s:element minOccurs="0" name="outUF05" type="s:string"/>
                    <s:element minOccurs="0" name="outUF07" type="s:string"/>
                    <s:element minOccurs="0" name="outUFAluno02" type="s:string"/>
                    <s:element minOccurs="0" name="outUFMatric02" type="s:string"/>
                    <s:element minOccurs="0" name="serie" type="s:string"/>
                    <s:element minOccurs="0" name="situacaoMatricula" type="s:string"/>
                    <s:element minOccurs="0" name="tipoEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="turma" type="s:string"/>
                    <s:element minOccurs="0" name="turno" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="ConsultarQuadroResumo">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inCodEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ConsultarQuadroResumoResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                        <s:element minOccurs="0" name="outErro" type="s:string"/>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgConsultarQuadroResumoMsgConsultarQuadroResumo"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgConsultarQuadroResumoMsgConsultarQuadroResumo">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgConsultarQuadroResumo" nillable="true" type="s0:MsgConsultarQuadroResumo"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgConsultarQuadroResumo">
                <s:sequence>
                    <s:element minOccurs="0" name="outAtividadeCompl1" type="s:string"/>
                    <s:element minOccurs="0" name="outAtividadeCompl2" type="s:string"/>
                    <s:element minOccurs="0" name="outAtividadeCompl3" type="s:string"/>
                    <s:element minOccurs="0" name="outAtividadeCompl4" type="s:string"/>
                    <s:element minOccurs="0" name="outAuditiva" type="s:string"/>
                    <s:element minOccurs="0" name="outCursoNormal1" type="s:string"/>
                    <s:element minOccurs="0" name="outCursoNormal2" type="s:string"/>
                    <s:element minOccurs="0" name="outCursoNormal3" type="s:string"/>
                    <s:element minOccurs="0" name="outCursoNormal4" type="s:string"/>
                    <s:element minOccurs="0" name="outEducEspecializado" type="s:string"/>
                    <s:element minOccurs="0" name="outEducEspExclusiva" type="s:string"/>
                    <s:element minOccurs="0" name="outEjaEnsMedio" type="s:string"/>
                    <s:element minOccurs="0" name="outEnsinoLinguas" type="s:string"/>
                    <s:element minOccurs="0" name="outEnsMedio1" type="s:string"/>
                    <s:element minOccurs="0" name="outEnsMedio2" type="s:string"/>
                    <s:element minOccurs="0" name="outEnsMedio3" type="s:string"/>
                    <s:element minOccurs="0" name="outEnsMedio4" type="s:string"/>
                    <s:element minOccurs="0" name="outEnsMedioIntEdProf" type="s:string"/>
                    <s:element minOccurs="0" name="outEspecializacao" type="s:string"/>
                    <s:element minOccurs="0" name="outFisica" type="s:string"/>
                    <s:element minOccurs="0" name="outFund8anos5" type="s:string"/>
                    <s:element minOccurs="0" name="outFund8anos6" type="s:string"/>
                    <s:element minOccurs="0" name="outFund8anos7" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anos1" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anos2" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anos3" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anos4" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anos5" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anos6" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anos7" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anos8" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anos9" type="s:string"/>
                    <s:element minOccurs="0" name="outInfantil1" type="s:string"/>
                    <s:element minOccurs="0" name="outInfantil2" type="s:string"/>
                    <s:element minOccurs="0" name="outInfantil4" type="s:string"/>
                    <s:element minOccurs="0" name="outInfantil5" type="s:string"/>
                    <s:element minOccurs="0" name="outInfantil6" type="s:string"/>
                    <s:element minOccurs="0" name="outInfantil7" type="s:string"/>
                    <s:element minOccurs="0" name="outInfantilMultisseriadas" type="s:string"/>
                    <s:element minOccurs="0" name="outMultipla" type="s:string"/>
                    <s:element minOccurs="0" name="outQualificacaoBasica" type="s:string"/>
                    <s:element minOccurs="0" name="outVisual" type="s:string"/>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outProejaEnsFundamental" type="s:string"/>
                    <s:element minOccurs="0" name="outProejaEnsMedio" type="s:string"/>
                    <s:element minOccurs="0" name="outFund8anos8" type="s:string"/>
                    <s:element minOccurs="0" name="outConcomSubs" type="s:string"/>
                    <s:element minOccurs="0" name="outEjaEnsFundAF" type="s:string"/>
                    <s:element minOccurs="0" name="outEjaEnsFundAI" type="s:string"/>
                    <s:element minOccurs="0" name="outIntelectual" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anosMultisseriadas" type="s:string"/>
                    <s:element minOccurs="0" name="outProJovemUrb" type="s:string"/>
                    <s:element minOccurs="0" name="outTranstEspAutismo" type="s:string"/>
                    <s:element minOccurs="0" name="outNaoSeriado" type="s:string"/>
                    <s:element minOccurs="0" name="outAtividadeCompl1cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outAtividadeCompl2cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outAtividadeCompl3cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outAtividadeCompl4cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outAuditivacltd" type="s:string"/>
                    <s:element minOccurs="0" name="outConcomSubscltd" type="s:string"/>
                    <s:element minOccurs="0" name="outCursoNormal1cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outCursoNormal2cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outCursoNormal3cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outCursoNormal4cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outEducEspecializadocltd" type="s:string"/>
                    <s:element minOccurs="0" name="outEducEspExclusivacltd" type="s:string"/>
                    <s:element minOccurs="0" name="outEjaEnsFundAFcltd" type="s:string"/>
                    <s:element minOccurs="0" name="outEjaEnsFundAIcltd" type="s:string"/>
                    <s:element minOccurs="0" name="outEjaEnsMediocltd" type="s:string"/>
                    <s:element minOccurs="0" name="outEnsMedio1cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outEnsMedio2cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outEnsMedio3cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outEnsMedio4cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outEnsMedioIntEdProfcltd" type="s:string"/>
                    <s:element minOccurs="0" name="outEspecializacaocltd" type="s:string"/>
                    <s:element minOccurs="0" name="outFisicacltd" type="s:string"/>
                    <s:element minOccurs="0" name="outFund8anos5cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outFund8anos6cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outFund8anos7cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outFund8anos8cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anos1cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anos2cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anos3cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anos4cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anos5cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anos6cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anos7cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anos8cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anos9cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outFund9anosMultisseriadascltd" type="s:string"/>
                    <s:element minOccurs="0" name="outInfantil1cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outInfantil2cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outInfantil5cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outInfantil6cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outInfantil7cltd" type="s:string"/>
                    <s:element minOccurs="0" name="outInfantilMultisseriadascltd" type="s:string"/>
                    <s:element minOccurs="0" name="outIntelectualcltd" type="s:string"/>
                    <s:element minOccurs="0" name="outMultiplacltd" type="s:string"/>
                    <s:element minOccurs="0" name="outNaoSeriadocltd" type="s:string"/>
                    <s:element minOccurs="0" name="outProejaEnsFundamentalcltd" type="s:string"/>
                    <s:element minOccurs="0" name="outProejaEnsMediocltd" type="s:string"/>
                    <s:element minOccurs="0" name="outProJovemUrbcltd" type="s:string"/>
                    <s:element minOccurs="0" name="outQualificacaoBasicacltd" type="s:string"/>
                    <s:element minOccurs="0" name="outTranstEspAutismocltd" type="s:string"/>
                    <s:element minOccurs="0" name="outVisualcltd" type="s:string"/>
                    <s:element minOccurs="0" name="outClassesColetadas" type="s:string"/>
                    <s:element minOccurs="0" name="outClassesDigitadas" type="s:string"/>
                    <s:element minOccurs="0" name="outInfantil4cltd" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="ConsultarRendPorNumeroClasse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="inNumClasse" type="s:string"/>
                        <s:element minOccurs="0" name="inSemestre" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ConsultarRendPorNumeroClasseResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:Record"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="Record">
                <s:sequence>
                    <s:element minOccurs="0" name="outTipoEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outSerie" type="s:string"/>
                    <s:element minOccurs="0" name="outEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outHora" type="s:string"/>
                    <s:element minOccurs="0" name="outNumSala" type="s:string"/>
                    <s:element minOccurs="0" name="outTurma" type="s:string"/>
                    <s:element minOccurs="0" name="outTurno" type="s:string"/>
                    <s:element minOccurs="0" name="outTotAluAprovado" type="s:string"/>
                    <s:element minOccurs="0" name="outTotAluAprovadoParc" type="s:string"/>
                    <s:element minOccurs="0" name="outTotAluEmAndamento" type="s:string"/>
                    <s:element minOccurs="0" name="outTotAluGeral" type="s:string"/>
                    <s:element minOccurs="0" name="outTotAluRendFreqInsuf" type="s:string"/>
                    <s:element minOccurs="0" name="outTotAluRetFreqInsuf" type="s:string"/>
                    <s:element minOccurs="0" name="outTotAluRetParcial" type="s:string"/>
                    <s:element minOccurs="0" name="outTotAluTermEspecifica" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="Alunos" type="s0:ArrayOfRecordAlunoRecordAluno"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="ArrayOfRecordAlunoRecordAluno">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="RecordAluno" nillable="true" type="s0:RecordAluno"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="RecordAluno">
                <s:sequence>
                    <s:element minOccurs="0" name="outRA" type="s:string"/>
                    <s:element minOccurs="0" name="outDigito" type="s:string"/>
                    <s:element minOccurs="0" name="outUF" type="s:string"/>
                    <s:element minOccurs="0" name="outNome" type="s:string"/>
                    <s:element minOccurs="0" name="outNumero" type="s:string"/>
                    <s:element minOccurs="0" name="outRendimento" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="ConsultarTotaisporEscola">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inAnoLetivo" type="s:string"/>
                        <s:element minOccurs="0" name="inCodEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inSemestre" type="s:string"/>
                        <s:element minOccurs="0" name="inSerieAno" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoEnsino" type="s:string"/>
                        <s:element minOccurs="0" name="inTurma" type="s:string"/>
                        <s:element minOccurs="0" name="inTurno" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ConsultarTotaisporEscolaResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfConsultarTotaisporEscolaRecordConsultarTotaisporEscolaRecord"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfConsultarTotaisporEscolaRecordConsultarTotaisporEscolaRecord">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="ConsultarTotaisporEscolaRecord" nillable="true" type="s0:ConsultarTotaisporEscolaRecord"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="ConsultarTotaisporEscolaRecord">
                <s:sequence>
                    <s:element minOccurs="0" name="outCodEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outNomeEscola" type="s:string"/>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outCodTipoEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outDescrTipoEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outTotAbandono" type="s:string"/>
                    <s:element minOccurs="0" name="outTotAlunoCad" type="s:string"/>
                    <s:element minOccurs="0" name="outTotAtivos" type="s:string"/>
                    <s:element minOccurs="0" name="outTotCessado" type="s:string"/>
                    <s:element minOccurs="0" name="outTotClasses" type="s:string"/>
                    <s:element minOccurs="0" name="outTotOutros" type="s:string"/>
                    <s:element minOccurs="0" name="outTotReclassificado" type="s:string"/>
                    <s:element minOccurs="0" name="outTotRemanejado" type="s:string"/>
                    <s:element minOccurs="0" name="outTotTransferido" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="DefinirAlunoEnsinoMedio">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="inTurno" type="s:string"/>
                        <s:element minOccurs="0" name="EndResidencial" type="s0:EndResidencial"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="EndResidencial">
                <s:sequence>
                    <s:element minOccurs="0" name="inBairro" type="s:string"/>
                    <s:element minOccurs="0" name="inCep" type="s:string"/>
                    <s:element minOccurs="0" name="inCidade" type="s:string"/>
                    <s:element minOccurs="0" name="inComplemento" type="s:string"/>
                    <s:element minOccurs="0" name="inDDD" type="s:string"/>
                    <s:element minOccurs="0" name="inFoneRecados" type="s:string"/>
                    <s:element minOccurs="0" name="inFoneResidencial" type="s:string"/>
                    <s:element minOccurs="0" name="inLogradouro" type="s:string"/>
                    <s:element minOccurs="0" name="inNomeFoneRecado" type="s:string"/>
                    <s:element minOccurs="0" name="inNumero" type="s:string"/>
                    <s:element minOccurs="0" name="inUF" type="s:string"/>
                    <s:element minOccurs="0" name="inTipoLogradouro" type="s:string"/>
                    <s:element minOccurs="0" name="inLatitude" type="s:string"/>
                    <s:element minOccurs="0" name="inLongitude" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="DefinirAlunoEnsinoMedioResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgDefinirAlunoEnsinoMedioMsgDefinirAlunoEnsinoMedio"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgDefinirAlunoEnsinoMedioMsgDefinirAlunoEnsinoMedio">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgDefinirAlunoEnsinoMedio" nillable="true" type="s0:MsgDefinirAlunoEnsinoMedio"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgDefinirAlunoEnsinoMedio">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="EncerrarRendimentoEscolarPorCIE">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inCodEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inSemestre" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="EncerrarRendimentoEscolarPorCIEResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgEncerramentoRendimentoMsgEncerramentoRendimento"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgEncerramentoRendimentoMsgEncerramentoRendimento">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgEncerramentoRendimento" nillable="true" type="s0:MsgEncerramentoRendimento"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgEncerramentoRendimento">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="EstornarBaixaMatrTransf">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inCodEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inSerieAno" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoEnsino" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="EstornarBaixaMatrTransfResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfEstrnBxMatrTransferenciaEstrnBxMatrTransferencia"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfEstrnBxMatrTransferenciaEstrnBxMatrTransferencia">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="EstrnBxMatrTransferencia" nillable="true" type="s0:EstrnBxMatrTransferencia"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="EstrnBxMatrTransferencia">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="EstornarRegistroAbandono">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaRetorno" type="s:string"/>
                        <s:element minOccurs="0" name="inMesRetorno" type="s:string"/>
                        <s:element minOccurs="0" name="inCodEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inSerieAno" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoEnsino" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="EstornarRegistroAbandonoResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfEstornarRegAbandonoEstornarRegAbandono"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfEstornarRegAbandonoEstornarRegAbandono">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="EstornarRegAbandono" nillable="true" type="s0:EstornarRegAbandono"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="EstornarRegAbandono">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="ExcluirColetaClasse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inNumClasse" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ExcluirColetaClasseResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgExcluirColetaClasseMsgExcluirColetaClasse"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgExcluirColetaClasseMsgExcluirColetaClasse">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgExcluirColetaClasse" nillable="true" type="s0:MsgExcluirColetaClasse"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgExcluirColetaClasse">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="ExcluirIrmao">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmaoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmaoDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmaoUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ExcluirIrmaoResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgExcluirIrmaoMsgExcluirIrmao"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgExcluirIrmaoMsgExcluirIrmao">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgExcluirIrmao" nillable="true" type="s0:MsgExcluirIrmao"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgExcluirIrmao">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="ExcluirMatricula">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUF" type="s:string"/>
                        <s:element minOccurs="0" name="inNumClasse" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="inSerieAno" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoEnsino" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ExcluirMatriculaResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                        <s:element minOccurs="0" name="outErro" type="s:string"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="IncluirColetaClasse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inCodEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inCodUnidade" type="s:string"/>
                        <s:element minOccurs="0" name="inCapacidadeFisica" type="s:string"/>
                        <s:element minOccurs="0" name="inCursoSemestral1" type="s:string"/>
                        <s:element minOccurs="0" name="inCursoSemestral2" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaInicioAula" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaTerminoAula" type="s:string"/>
                        <s:element minOccurs="0" name="inCodigoINEP" type="s:string"/>
                        <s:element minOccurs="0" name="inHoraFinal" type="s:string"/>
                        <s:element minOccurs="0" name="inHoraInicial" type="s:string"/>
                        <s:element minOccurs="0" name="inMesInicioAula" type="s:string"/>
                        <s:element minOccurs="0" name="inMesTerminoAula" type="s:string"/>
                        <s:element minOccurs="0" name="inNumeroSala" type="s:string"/>
                        <s:element minOccurs="0" name="inSerieAno" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoClasse" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoEnsino" type="s:string"/>
                        <s:element minOccurs="0" name="inTurma" type="s:string"/>
                        <s:element minOccurs="0" name="inTurno" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="inDiasSemana" type="s0:DiasSemana"/>
                        <s:element minOccurs="0" name="inProgMaisEducacao" type="s:string"/>
                        <s:element minOccurs="0" name="inAEE" type="s:string"/>
                        <s:element minOccurs="0" name="inATC" type="s:string"/>
                        <s:element minOccurs="0" name="inFlagConvenioEst" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="IncluirColetaClasseResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgIncluirColetaClasseMsgIncluirColetaClasse"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgIncluirColetaClasseMsgIncluirColetaClasse">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgIncluirColetaClasse" nillable="true" type="s0:MsgIncluirColetaClasse"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgIncluirColetaClasse">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outMensagem" type="s:string"/>
                    <s:element minOccurs="0" name="outNumClasse" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="InformarTerminoDigitacao">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inCodEscola" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="InformarTerminoDigitacaoResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                        <s:element minOccurs="0" name="outErro" type="s:string"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="InscreverAlunoPorDeslocamento">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="EndResidencial" type="s0:EndResidencial"/>
                        <s:element minOccurs="0" name="inTipoInscricao" type="s:string"/>
                        <s:element minOccurs="0" name="inMotivo" type="s:string"/>
                        <s:element minOccurs="0" name="inDDDCel" type="s:string"/>
                        <s:element minOccurs="0" name="inFoneCel" type="s:string"/>
                        <s:element minOccurs="0" name="inSMS" type="s:string"/>
                        <s:element minOccurs="0" name="EndIndicativo" type="s0:EndIndicativo"/>
                        <s:element minOccurs="0" name="inInteresseIntegral" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="EndIndicativo">
                <s:sequence>
                    <s:element minOccurs="0" name="inLogradouro" type="s:string"/>
                    <s:element minOccurs="0" name="inNumero" type="s:string"/>
                    <s:element minOccurs="0" name="inBairro" type="s:string"/>
                    <s:element minOccurs="0" name="inCep" type="s:string"/>
                    <s:element minOccurs="0" name="inCidade" type="s:string"/>
                    <s:element minOccurs="0" name="inUF" type="s:string"/>
                    <s:element minOccurs="0" name="inLatitude" type="s:string"/>
                    <s:element minOccurs="0" name="inLongitude" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="InscreverAlunoPorDeslocamentoResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgInscreverAlunoPorDeslocamentoMsgInscreverAlunoPorDeslocamento"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgInscreverAlunoPorDeslocamentoMsgInscreverAlunoPorDeslocamento">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgInscreverAlunoPorDeslocamento" nillable="true" type="s0:MsgInscreverAlunoPorDeslocamento"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgInscreverAlunoPorDeslocamento">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="InscreverAlunotransferencia">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="inEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inAnoSerie" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoEnsino" type="s:string"/>
                        <s:element minOccurs="0" name="EndResidencial" type="s0:EndResidencial"/>
                        <s:element minOccurs="0" name="inMotivo" type="s:string"/>
                        <s:element minOccurs="0" name="inInteresseIntegral" type="s:string"/>
                        <s:element minOccurs="0" name="EndIndicativo" type="s0:EndIndicativo"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="InscreverAlunotransferenciaResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgInscreverAlunotransferenciaMsgInscreverAlunotransferencia"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgInscreverAlunotransferenciaMsgInscreverAlunotransferencia">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgInscreverAlunotransferencia" nillable="true" type="s0:MsgInscreverAlunotransferencia"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgInscreverAlunotransferencia">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="InscricaoIntencaoTransferencia">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inSerieAno" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoEnsino" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="inBairro" type="s:string"/>
                        <s:element minOccurs="0" name="inCep" type="s:string"/>
                        <s:element minOccurs="0" name="inCidade" type="s:string"/>
                        <s:element minOccurs="0" name="inComplemento" type="s:string"/>
                        <s:element minOccurs="0" name="inDDD" type="s:string"/>
                        <s:element minOccurs="0" name="inDDDCel" type="s:string"/>
                        <s:element minOccurs="0" name="inFoneCel" type="s:string"/>
                        <s:element minOccurs="0" name="inFoneRecados" type="s:string"/>
                        <s:element minOccurs="0" name="inFoneResidencial" type="s:string"/>
                        <s:element minOccurs="0" name="inLogradouro" type="s:string"/>
                        <s:element minOccurs="0" name="inNomeRecados" type="s:string"/>
                        <s:element minOccurs="0" name="inNumero" type="s:string"/>
                        <s:element minOccurs="0" name="inSMS" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoLogradouro" type="s:string"/>
                        <s:element minOccurs="0" name="inUF" type="s:string"/>
                        <s:element minOccurs="0" name="inInteresseIntegral" type="s:string"/>
                        <s:element minOccurs="0" name="EndIndicativo" type="s0:EndIndicativo"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="InscricaoIntencaoTransferenciaResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgInscricaoIntencaoTransferenciaMsgInscricaoIntencaoTransferencia"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgInscricaoIntencaoTransferenciaMsgInscricaoIntencaoTransferencia">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgInscricaoIntencaoTransferencia" nillable="true" type="s0:MsgInscricaoIntencaoTransferencia"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgInscricaoIntencaoTransferencia">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="LancarRendimentoEscolarClasse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inNumClasse" type="s:string"/>
                        <s:element minOccurs="0" name="inSemestre" type="s:string"/>
                        <s:element minOccurs="0" name="inAlunos" type="s0:ArrayOfAlunoAluno"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfAlunoAluno">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="Aluno" nillable="true" type="s0:Aluno"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="Aluno">
                <s:sequence>
                    <s:element minOccurs="0" name="inRA" type="s:string"/>
                    <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                    <s:element minOccurs="0" name="inUF" type="s:string"/>
                    <s:element minOccurs="0" name="inStatus" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="LancarRendimentoEscolarClasseResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfLancarRendEscClasseLancarRendEscClasse"/>
                        <s:element minOccurs="0" name="ListaAlunosValidos" type="s0:ArrayOfAlunoValidoAlunoValido"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfLancarRendEscClasseLancarRendEscClasse">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="LancarRendEscClasse" nillable="true" type="s0:LancarRendEscClasse"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="LancarRendEscClasse">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="ArrayOfAlunoValidoAlunoValido">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="AlunoValido" nillable="true" type="s0:AlunoValido"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="AlunoValido">
                <s:sequence>
                    <s:element minOccurs="0" name="inRA" type="s:string"/>
                    <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                    <s:element minOccurs="0" name="inUF" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="RealizarMatricAntecipadaFases">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inCodEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="inFase" type="s:string"/>
                        <s:element minOccurs="0" name="inSerieAno" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoEnsino" type="s:string"/>
                        <s:element minOccurs="0" name="inAnoEmissaoRG" type="s:string"/>
                        <s:element minOccurs="0" name="inAnoNascimento" type="s:string"/>
                        <s:element minOccurs="0" name="inBolsaFamilia" type="s:string"/>
                        <s:element minOccurs="0" name="inCorRaca" type="s:string"/>
                        <s:element minOccurs="0" name="inCPF" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaEmissaoRG" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaNascimento" type="s:string"/>
                        <s:element minOccurs="0" name="inGemeo" type="s:string"/>
                        <s:element minOccurs="0" name="inMesEmissaoRG" type="s:string"/>
                        <s:element minOccurs="0" name="inMesNascimento" type="s:string"/>
                        <s:element minOccurs="0" name="inMobilidadeReduzida" type="s:string"/>
                        <s:element minOccurs="0" name="inNacionalidade" type="s:string"/>
                        <s:element minOccurs="0" name="inNecessidadeEspecial" type="s:string"/>
                        <s:element minOccurs="0" name="inNIS" type="s:string"/>
                        <s:element minOccurs="0" name="inNomeAluno" type="s:string"/>
                        <s:element minOccurs="0" name="inNomeMae" type="s:string"/>
                        <s:element minOccurs="0" name="inNomePai" type="s:string"/>
                        <s:element minOccurs="0" name="inPaisOrigem" type="s:string"/>
                        <s:element minOccurs="0" name="inSexo" type="s:string"/>
                        <s:element minOccurs="0" name="inPermanente" type="s:string"/>
                        <s:element minOccurs="0" name="inTemporaria" type="s:string"/>
                        <s:element minOccurs="0" name="inAltasHabSuperdotacao" type="s:string"/>
                        <s:element minOccurs="0" name="inAutistaClassico" type="s:string"/>
                        <s:element minOccurs="0" name="inBaixaVisao" type="s:string"/>
                        <s:element minOccurs="0" name="inCegueira" type="s:string"/>
                        <s:element minOccurs="0" name="inFisicaCadeirante" type="s:string"/>
                        <s:element minOccurs="0" name="inFisicaOutros" type="s:string"/>
                        <s:element minOccurs="0" name="inFisicaParalisiaCerebral" type="s:string"/>
                        <s:element minOccurs="0" name="inIntelectual" type="s:string"/>
                        <s:element minOccurs="0" name="inMultipla" type="s:string"/>
                        <s:element minOccurs="0" name="inSindromeAsperger" type="s:string"/>
                        <s:element minOccurs="0" name="inSindromeDown" type="s:string"/>
                        <s:element minOccurs="0" name="inSindromeRett" type="s:string"/>
                        <s:element minOccurs="0" name="inSurdezLeveModerada" type="s:string"/>
                        <s:element minOccurs="0" name="inSurdezSeveraProfunda" type="s:string"/>
                        <s:element minOccurs="0" name="inSurdocegueira" type="s:string"/>
                        <s:element minOccurs="0" name="inTransDesintegrativoInf" type="s:string"/>
                        <s:element minOccurs="0" name="inAnoEntBrasil" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaEntBrasil" type="s:string"/>
                        <s:element minOccurs="0" name="inMesEntBrasil" type="s:string"/>
                        <s:element minOccurs="0" name="inRGRNE01" type="s:string"/>
                        <s:element minOccurs="0" name="inRGRNE02" type="s:string"/>
                        <s:element minOccurs="0" name="inRGRNE03" type="s:string"/>
                        <s:element minOccurs="0" name="inMunicipioNasc" type="s:string"/>
                        <s:element minOccurs="0" name="inUFNasc" type="s:string"/>
                        <s:element minOccurs="0" name="EndResidencial" type="s0:EndResidencial"/>
                        <s:element minOccurs="0" name="EndMatricula" type="s0:EndIndicativo"/>
                        <s:choice minOccurs="0">
                            <s:element name="CertidaoAntiga" type="s0:CertidaoAntiga"/>
                            <s:element name="CertidaoNova" type="s0:CertidaoNova"/>
                        </s:choice>
                        <s:element minOccurs="0" name="inAuxilioLeitor" type="s:string"/>
                        <s:element minOccurs="0" name="inAuxilioTranscricao" type="s:string"/>
                        <s:element minOccurs="0" name="inGuiaInterprete" type="s:string"/>
                        <s:element minOccurs="0" name="inInterpreteLibras" type="s:string"/>
                        <s:element minOccurs="0" name="inLeituraLabial" type="s:string"/>
                        <s:element minOccurs="0" name="inNenhum" type="s:string"/>
                        <s:element minOccurs="0" name="inProvaAmpliada" type="s:string"/>
                        <s:element minOccurs="0" name="inProvaBraile" type="s:string"/>
                        <s:element minOccurs="0" name="inTam16" type="s:string"/>
                        <s:element minOccurs="0" name="inTam20" type="s:string"/>
                        <s:element minOccurs="0" name="inTam24" type="s:string"/>
                        <s:element minOccurs="0" name="inSMS" type="s:string"/>
                        <s:element minOccurs="0" name="inFoneCel" type="s:string"/>
                        <s:element minOccurs="0" name="inDDDCel" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmao" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmaoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmaoDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmaoUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inNomeSocial" type="s:string"/>
                        <s:element minOccurs="0" name="inEmail" type="s:string"/>
                        <s:element minOccurs="0" name="inQuilombola" type="s:string"/>
                        <s:element minOccurs="0" name="inNecesAtendNoturno" type="s:string"/>
                        <s:element minOccurs="0" name="inInteresseEspanhol" type="s:string"/>
                        <s:element minOccurs="0" name="inInteresseIntegral" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="RealizarMatricAntecipadaFasesResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="outRA" type="s:string"/>
                        <s:element minOccurs="0" name="outDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="outUF" type="s:string"/>
                        <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                        <s:element minOccurs="0" name="outErro" type="s:string"/>
                        <s:element minOccurs="0" name="outSucessoGemeo" type="s:string"/>
                        <s:element minOccurs="0" name="outErroGemeo" type="s:string"/>
                        <s:element minOccurs="0" name="outSucessoIrmao" type="s:string"/>
                        <s:element minOccurs="0" name="outErroIrmao" type="s:string"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="RealizarMatricAntecipadaRAFases">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inCodEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="inFase" type="s:string"/>
                        <s:element minOccurs="0" name="inSerieAno" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoEnsino" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="EndResidencial" type="s0:EndResidencial"/>
                        <s:element minOccurs="0" name="EndMatricula" type="s0:EndIndicativo"/>
                        <s:element minOccurs="0" name="inSMS" type="s:string"/>
                        <s:element minOccurs="0" name="inFoneCel" type="s:string"/>
                        <s:element minOccurs="0" name="inDDDCel" type="s:string"/>
                        <s:element minOccurs="0" name="inTurno" type="s:string"/>
                        <s:element minOccurs="0" name="inNecesAtendNoturno" type="s:string"/>
                        <s:element minOccurs="0" name="inInteresseIntegral" type="s:string"/>
                        <s:element minOccurs="0" name="inInteresseEspanhol" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="RealizarMatricAntecipadaRAFasesResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgMatriculaAntecipadaF2RAMsgMatriculaAntecipadaF2RA"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgMatriculaAntecipadaF2RAMsgMatriculaAntecipadaF2RA">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgMatriculaAntecipadaF2RA" nillable="true" type="s0:MsgMatriculaAntecipadaF2RA"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgMatriculaAntecipadaF2RA">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="RealizarMatriculaAntecipada">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="EndResidencial" type="s0:EndResidencial"/>
                        <s:element minOccurs="0" name="EndIndicativo" type="s0:EndIndicativo"/>
                        <s:element minOccurs="0" name="inDDDCel" type="s:string"/>
                        <s:element minOccurs="0" name="inFoneCel" type="s:string"/>
                        <s:element minOccurs="0" name="inSMS" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmao" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmaoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmaoDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmaoUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inInteresseIntegral" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="RealizarMatriculaAntecipadaResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                        <s:element minOccurs="0" name="outErro" type="s:string"/>
                        <s:element minOccurs="0" name="outSucessoIrmao" type="s:string"/>
                        <s:element minOccurs="0" name="outErroIrmao" type="s:string"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="RealizarMatriculaInfoComRA">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inSerieAno" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoEnsino" type="s:string"/>
                        <s:element minOccurs="0" name="inNumClasse" type="s:string"/>
                        <s:element minOccurs="0" name="inDataMatricula" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inMesMatricula" type="s:string"/>
                        <s:element minOccurs="0" name="inNumAluno" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="EndResidencial" type="s0:EndResidencial"/>
                        <s:element minOccurs="0" name="inDDDCel" type="s:string"/>
                        <s:element minOccurs="0" name="inFoneCel" type="s:string"/>
                        <s:element minOccurs="0" name="inSMS" type="s:string"/>
                        <s:element minOccurs="0" name="inConvenio" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="RealizarMatriculaInfoComRAResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="outSucessoMatricula" type="s:string"/>
                        <s:element minOccurs="0" name="outErroMatricula" type="s:string"/>
                        <s:element minOccurs="0" name="outSucessoAltEndereco" type="s:string"/>
                        <s:element minOccurs="0" name="outErroAltEndereco" type="s:string"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="RealizarMatriculaInfoSemRA">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="inNumClasse" type="s:string"/>
                        <s:element minOccurs="0" name="inNumAluno" type="s:string"/>
                        <s:element minOccurs="0" name="inDataMatricula" type="s:string"/>
                        <s:element minOccurs="0" name="inMesMatricula" type="s:string"/>
                        <s:element minOccurs="0" name="inNomeAluno" type="s:string"/>
                        <s:element minOccurs="0" name="inSexo" type="s:string"/>
                        <s:element minOccurs="0" name="inCorRaca" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaNascimento" type="s:string"/>
                        <s:element minOccurs="0" name="inMesNascimento" type="s:string"/>
                        <s:element minOccurs="0" name="inAnoNascimento" type="s:string"/>
                        <s:element minOccurs="0" name="inNomeMae" type="s:string"/>
                        <s:element minOccurs="0" name="inNomePai" type="s:string"/>
                        <s:element minOccurs="0" name="inNacionalidade" type="s:string"/>
                        <s:element minOccurs="0" name="inPaisOrigem" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaEntBrasil" type="s:string"/>
                        <s:element minOccurs="0" name="inMesEntBrasil" type="s:string"/>
                        <s:element minOccurs="0" name="inAnoEntBrasil" type="s:string"/>
                        <s:element minOccurs="0" name="inRGRNE01" type="s:string"/>
                        <s:element minOccurs="0" name="inRGRNE02" type="s:string"/>
                        <s:element minOccurs="0" name="inRGRNE03" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaEmissao" type="s:string"/>
                        <s:element minOccurs="0" name="inMesEmissao" type="s:string"/>
                        <s:element minOccurs="0" name="inAnoEmissao" type="s:string"/>
                        <s:element minOccurs="0" name="inNumNIS" type="s:string"/>
                        <s:element minOccurs="0" name="inBolsaFamilia" type="s:string"/>
                        <s:element minOccurs="0" name="inNecEducEspecial" type="s:string"/>
                        <s:element minOccurs="0" name="inMultipla" type="s:string"/>
                        <s:element minOccurs="0" name="inCegueira" type="s:string"/>
                        <s:element minOccurs="0" name="inBaixaVisao" type="s:string"/>
                        <s:element minOccurs="0" name="inSurdezSeveraProfunda" type="s:string"/>
                        <s:element minOccurs="0" name="inSurdezLeveModerada" type="s:string"/>
                        <s:element minOccurs="0" name="inSurdocegueira" type="s:string"/>
                        <s:element minOccurs="0" name="inFisicaParalisiaCerebral" type="s:string"/>
                        <s:element minOccurs="0" name="inFisicaCadeirante" type="s:string"/>
                        <s:element minOccurs="0" name="inFisicaOutros" type="s:string"/>
                        <s:element minOccurs="0" name="inSindromeDown" type="s:string"/>
                        <s:element minOccurs="0" name="inIntelectual" type="s:string"/>
                        <s:element minOccurs="0" name="inAutistaClassico" type="s:string"/>
                        <s:element minOccurs="0" name="inSindromeAsperger" type="s:string"/>
                        <s:element minOccurs="0" name="inSindromeRett" type="s:string"/>
                        <s:element minOccurs="0" name="inTransDesintegrativoInf" type="s:string"/>
                        <s:element minOccurs="0" name="inAltasHabSuperdotacao" type="s:string"/>
                        <s:element minOccurs="0" name="inCep" type="s:string"/>
                        <s:element minOccurs="0" name="inCidade" type="s:string"/>
                        <s:element minOccurs="0" name="inUF" type="s:string"/>
                        <s:element minOccurs="0" name="inTipLog" type="s:string"/>
                        <s:element minOccurs="0" name="inLogradouro" type="s:string"/>
                        <s:element minOccurs="0" name="inNumero" type="s:string"/>
                        <s:element minOccurs="0" name="inComplemento" type="s:string"/>
                        <s:element minOccurs="0" name="inBairro" type="s:string"/>
                        <s:element minOccurs="0" name="inDDD" type="s:string"/>
                        <s:element minOccurs="0" name="inFoneResidencial" type="s:string"/>
                        <s:element minOccurs="0" name="inFoneRecados" type="s:string"/>
                        <s:element minOccurs="0" name="inNomeFoneRecado" type="s:string"/>
                        <s:element minOccurs="0" name="inMunicipioNasc" type="s:string"/>
                        <s:element minOccurs="0" name="inUFNasc" type="s:string"/>
                        <s:element minOccurs="0" name="inGemeo" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmaoRA" type="s:string"/>
                        <s:choice minOccurs="0">
                            <s:element name="CertidaoAntiga" type="s0:CertidaoAntiga"/>
                            <s:element name="CertidaoNova" type="s0:CertidaoNova"/>
                        </s:choice>
                        <s:element minOccurs="0" name="inSerieAno" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoEnsino" type="s:string"/>
                        <s:element minOccurs="0" name="inCPF" type="s:string"/>
                        <s:element minOccurs="0" name="inPermanente" type="s:string"/>
                        <s:element minOccurs="0" name="inTemporaria" type="s:string"/>
                        <s:element minOccurs="0" name="inMobilidadeReduzida" type="s:string"/>
                        <s:element minOccurs="0" name="inAuxilioLeitor" type="s:string"/>
                        <s:element minOccurs="0" name="inAuxilioTranscricao" type="s:string"/>
                        <s:element minOccurs="0" name="inGuiaInterprete" type="s:string"/>
                        <s:element minOccurs="0" name="inInterpreteLibras" type="s:string"/>
                        <s:element minOccurs="0" name="inLeituraLabial" type="s:string"/>
                        <s:element minOccurs="0" name="inNenhum" type="s:string"/>
                        <s:element minOccurs="0" name="inProvaAmpliada" type="s:string"/>
                        <s:element minOccurs="0" name="inProvaBraile" type="s:string"/>
                        <s:element minOccurs="0" name="inTam16" type="s:string"/>
                        <s:element minOccurs="0" name="inTam20" type="s:string"/>
                        <s:element minOccurs="0" name="inTam24" type="s:string"/>
                        <s:element minOccurs="0" name="inDDDCel" type="s:string"/>
                        <s:element minOccurs="0" name="inFoneCel" type="s:string"/>
                        <s:element minOccurs="0" name="inSMS" type="s:string"/>
                        <s:element minOccurs="0" name="inEmail" type="s:string"/>
                        <s:element minOccurs="0" name="inIrmao" type="s:string"/>
                        <s:element minOccurs="0" name="inNomeSocial" type="s:string"/>
                        <s:element minOccurs="0" name="inQuilombola" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="RealizarMatriculaInfoSemRAResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Erro" type="s:string"/>
                        <s:element minOccurs="0" name="Sucesso" type="s:string"/>
                        <s:element minOccurs="0" name="DigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="RA" type="s:string"/>
                        <s:element minOccurs="0" name="UF" type="s:string"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ReclassificarMatriculas">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUF" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaReclassificacao" type="s:string"/>
                        <s:element minOccurs="0" name="inMesReclassificacao" type="s:string"/>
                        <s:element minOccurs="0" name="inNumAluno" type="s:string"/>
                        <s:element minOccurs="0" name="inSerie" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoEnsino" type="s:string"/>
                        <s:element minOccurs="0" name="inTurma" type="s:string"/>
                        <s:element minOccurs="0" name="inTurno" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="ReclassificarMatriculasResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfReclassificacaoMatrReclassificacaoMatr"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfReclassificacaoMatrReclassificacaoMatr">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="ReclassificacaoMatr" nillable="true" type="s0:ReclassificacaoMatr"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="ReclassificacaoMatr">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="RegistrarAbandono">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaAbandono" type="s:string"/>
                        <s:element minOccurs="0" name="inMesAbandono" type="s:string"/>
                        <s:element minOccurs="0" name="CodigoEscola" type="s:string"/>
                        <s:element minOccurs="0" name="Serie" type="s:string"/>
                        <s:element minOccurs="0" name="TipoEnsino" type="s:string"/>
                        <s:element minOccurs="0" name="inUFRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="RegistrarAbandonoResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfRegistraAbandonoRegistraAbandono"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfRegistraAbandonoRegistraAbandono">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="RegistraAbandono" nillable="true" type="s0:RegistraAbandono"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="RegistraAbandono">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="RegistrarNaoComparecimento">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inCodEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inSerieAno" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoEnsino" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="RegistrarNaoComparecimentoResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfRegNaoComparecimentoRegNaoComparecimento"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfRegNaoComparecimentoRegNaoComparecimento">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="RegNaoComparecimento" nillable="true" type="s0:RegNaoComparecimento"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="RegNaoComparecimento">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                </s:sequence>
            </s:complexType>
            <s:element name="RemanejarMatPorRA">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inCodEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inNumClasse" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUF" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoEnsino" type="s:string"/>
                        <s:element minOccurs="0" name="inSerieAno" type="s:string"/>
                        <s:element minOccurs="0" name="inAlunoAvaliado" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaRemanejamento" type="s:string"/>
                        <s:element minOccurs="0" name="inMesRemanejamento" type="s:string"/>
                        <s:element minOccurs="0" name="inNumAluno" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="RemanejarMatPorRAResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfMsgRemanejamentoMsgRemanejamento"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfMsgRemanejamentoMsgRemanejamento">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="MsgRemanejamento" nillable="true" type="s0:MsgRemanejamento"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="MsgRemanejamento">
                <s:sequence>
                    <s:element minOccurs="0" name="outMsgSucesso">
                        <s:simpleType>
                            <s:restriction base="s:string">
                                <s:maxLength value="500"/>
                            </s:restriction>
                        </s:simpleType>
                    </s:element>
                    <s:element minOccurs="0" name="outMsgErro">
                        <s:simpleType>
                            <s:restriction base="s:string">
                                <s:maxLength value="5000"/>
                            </s:restriction>
                        </s:simpleType>
                    </s:element>
                </s:sequence>
            </s:complexType>
            <s:element name="TrocarAlunoClasseRA">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Usuario" type="s:string"/>
                        <s:element minOccurs="0" name="Senha" type="s:string"/>
                        <s:element minOccurs="0" name="inAno" type="s:string"/>
                        <s:element minOccurs="0" name="inDigitoRA" type="s:string"/>
                        <s:element minOccurs="0" name="inRA" type="s:string"/>
                        <s:element minOccurs="0" name="inUF" type="s:string"/>
                        <s:element minOccurs="0" name="inCodEscola" type="s:string"/>
                        <s:element minOccurs="0" name="inNumAluno" type="s:string"/>
                        <s:element minOccurs="0" name="inTurma" type="s:string"/>
                        <s:element minOccurs="0" name="inTurno" type="s:string"/>
                        <s:element minOccurs="0" name="inTipoEnsino" type="s:string"/>
                        <s:element minOccurs="0" name="inSerieAno" type="s:string"/>
                        <s:element minOccurs="0" name="inDiaTroca" type="s:string"/>
                        <s:element minOccurs="0" name="inMesTroca" type="s:string"/>
                        <s:element minOccurs="0" name="inAnoTroca" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:element name="TrocarAlunoClasseRAResponse">
                <s:complexType>
                    <s:sequence>
                        <s:element minOccurs="0" name="Mensagens" type="s0:ArrayOfTrocaEntreClassesTrocaEntreClasses"/>
                        <s:element minOccurs="0" name="outProcessoID" type="s:string"/>
                    </s:sequence>
                </s:complexType>
            </s:element>
            <s:complexType name="ArrayOfTrocaEntreClassesTrocaEntreClasses">
                <s:sequence>
                    <s:element maxOccurs="unbounded" minOccurs="0" name="TrocaEntreClasses" nillable="true" type="s0:TrocaEntreClasses"/>
                </s:sequence>
            </s:complexType>
            <s:complexType name="TrocaEntreClasses">
                <s:sequence>
                    <s:element minOccurs="0" name="outErro" type="s:string"/>
                    <s:element minOccurs="0" name="outHorario" type="s:string"/>
                    <s:element minOccurs="0" name="outNumClasse" type="s:string"/>
                    <s:element minOccurs="0" name="outOpcao" type="s:string"/>
                    <s:element minOccurs="0" name="outSucesso" type="s:string"/>
                    <s:element minOccurs="0" name="outTipoEnsino" type="s:string"/>
                    <s:element minOccurs="0" name="outTurma" type="s:string"/>
                    <s:element minOccurs="0" name="outTurno" type="s:string"/>
                </s:sequence>
            </s:complexType>
        </s:schema>
    </types>
    <message name="AlterarColetaClasseSoapIn">
        <part name="parameters" element="s0:AlterarColetaClasse"/>
    </message>
    <message name="AlterarColetaClasseSoapOut">
        <part name="parameters" element="s0:AlterarColetaClasseResponse"/>
    </message>
    <message name="AlterarDadosPessoaisFichaAlunoSoapIn">
        <part name="parameters" element="s0:AlterarDadosPessoaisFichaAluno"/>
    </message>
    <message name="AlterarDadosPessoaisFichaAlunoSoapOut">
        <part name="parameters" element="s0:AlterarDadosPessoaisFichaAlunoResponse"/>
    </message>
    <message name="AlterarDocumentosFichaAlunoSoapIn">
        <part name="parameters" element="s0:AlterarDocumentosFichaAluno"/>
    </message>
    <message name="AlterarDocumentosFichaAlunoSoapOut">
        <part name="parameters" element="s0:AlterarDocumentosFichaAlunoResponse"/>
    </message>
    <message name="AlterarEnderecoFichaAlunoSoapIn">
        <part name="parameters" element="s0:AlterarEnderecoFichaAluno"/>
    </message>
    <message name="AlterarEnderecoFichaAlunoSoapOut">
        <part name="parameters" element="s0:AlterarEnderecoFichaAlunoResponse"/>
    </message>
    <message name="AlterarEnderecoIndicativoSoapIn">
        <part name="parameters" element="s0:AlterarEnderecoIndicativo"/>
    </message>
    <message name="AlterarEnderecoIndicativoSoapOut">
        <part name="parameters" element="s0:AlterarEnderecoIndicativoResponse"/>
    </message>
    <message name="AssociarIrmaoSoapIn">
        <part name="parameters" element="s0:AssociarIrmao"/>
    </message>
    <message name="AssociarIrmaoSoapOut">
        <part name="parameters" element="s0:AssociarIrmaoResponse"/>
    </message>
    <message name="BaixarMatrFalecimentoRASoapIn">
        <part name="parameters" element="s0:BaixarMatrFalecimentoRA"/>
    </message>
    <message name="BaixarMatrFalecimentoRASoapOut">
        <part name="parameters" element="s0:BaixarMatrFalecimentoRAResponse"/>
    </message>
    <message name="BaixarMatriculaTransferenciaSoapIn">
        <part name="parameters" element="s0:BaixarMatriculaTransferencia"/>
    </message>
    <message name="BaixarMatriculaTransferenciaSoapOut">
        <part name="parameters" element="s0:BaixarMatriculaTransferenciaResponse"/>
    </message>
    <message name="CancelarEncerramentoRendEscolarSoapIn">
        <part name="parameters" element="s0:CancelarEncerramentoRendEscolar"/>
    </message>
    <message name="CancelarEncerramentoRendEscolarSoapOut">
        <part name="parameters" element="s0:CancelarEncerramentoRendEscolarResponse"/>
    </message>
    <message name="CancelarInscricaoAlunoPorDeslocamentoSoapIn">
        <part name="parameters" element="s0:CancelarInscricaoAlunoPorDeslocamento"/>
    </message>
    <message name="CancelarInscricaoAlunoPorDeslocamentoSoapOut">
        <part name="parameters" element="s0:CancelarInscricaoAlunoPorDeslocamentoResponse"/>
    </message>
    <message name="CancelarInscricaoAlunoTransfSoapIn">
        <part name="parameters" element="s0:CancelarInscricaoAlunoTransf"/>
    </message>
    <message name="CancelarInscricaoAlunoTransfSoapOut">
        <part name="parameters" element="s0:CancelarInscricaoAlunoTransfResponse"/>
    </message>
    <message name="CancelarInscricaoDefinicaoSoapIn">
        <part name="parameters" element="s0:CancelarInscricaoDefinicao"/>
    </message>
    <message name="CancelarInscricaoDefinicaoSoapOut">
        <part name="parameters" element="s0:CancelarInscricaoDefinicaoResponse"/>
    </message>
    <message name="CancelarIntencaoTransferenciaSoapIn">
        <part name="parameters" element="s0:CancelarIntencaoTransferencia"/>
    </message>
    <message name="CancelarIntencaoTransferenciaSoapOut">
        <part name="parameters" element="s0:CancelarIntencaoTransferenciaResponse"/>
    </message>
    <message name="CancelarTerminoDigitacaoSoapIn">
        <part name="parameters" element="s0:CancelarTerminoDigitacao"/>
    </message>
    <message name="CancelarTerminoDigitacaoSoapOut">
        <part name="parameters" element="s0:CancelarTerminoDigitacaoResponse"/>
    </message>
    <message name="ClassificarGerarNrChamadaClassePorEscolaSoapIn">
        <part name="parameters" element="s0:ClassificarGerarNrChamadaClassePorEscola"/>
    </message>
    <message name="ClassificarGerarNrChamadaClassePorEscolaSoapOut">
        <part name="parameters" element="s0:ClassificarGerarNrChamadaClassePorEscolaResponse"/>
    </message>
    <message name="ClassificarGerarNumeroChamadaPorClasseSoapIn">
        <part name="parameters" element="s0:ClassificarGerarNumeroChamadaPorClasse"/>
    </message>
    <message name="ClassificarGerarNumeroChamadaPorClasseSoapOut">
        <part name="parameters" element="s0:ClassificarGerarNumeroChamadaPorClasseResponse"/>
    </message>
    <message name="ConsultaClasseAlunoPorEscolaSoapIn">
        <part name="parameters" element="s0:ConsultaClasseAlunoPorEscola"/>
    </message>
    <message name="ConsultaClasseAlunoPorEscolaSoapOut">
        <part name="parameters" element="s0:ConsultaClasseAlunoPorEscolaResponse"/>
    </message>
    <message name="ConsultaFormacaoClasseSoapIn">
        <part name="parameters" element="s0:ConsultaFormacaoClasse"/>
    </message>
    <message name="ConsultaFormacaoClasseSoapOut">
        <part name="parameters" element="s0:ConsultaFormacaoClasseResponse"/>
    </message>
    <message name="ConsultarCepSoapIn">
        <part name="parameters" element="s0:ConsultarCep"/>
    </message>
    <message name="ConsultarCepSoapOut">
        <part name="parameters" element="s0:ConsultarCepResponse"/>
    </message>
    <message name="ConsultarCepLogradouroSoapIn">
        <part name="parameters" element="s0:ConsultarCepLogradouro"/>
    </message>
    <message name="ConsultarCepLogradouroSoapOut">
        <part name="parameters" element="s0:ConsultarCepLogradouroResponse"/>
    </message>
    <message name="ConsultarColetaClasseSoapIn">
        <part name="parameters" element="s0:ConsultarColetaClasse"/>
    </message>
    <message name="ConsultarColetaClasseSoapOut">
        <part name="parameters" element="s0:ConsultarColetaClasseResponse"/>
    </message>
    <message name="ConsultarDefinicaoInscricaoRASoapIn">
        <part name="parameters" element="s0:ConsultarDefinicaoInscricaoRA"/>
    </message>
    <message name="ConsultarDefinicaoInscricaoRASoapOut">
        <part name="parameters" element="s0:ConsultarDefinicaoInscricaoRAResponse"/>
    </message>
    <message name="ConsultarEscolaCIESoapIn">
        <part name="parameters" element="s0:ConsultarEscolaCIE"/>
    </message>
    <message name="ConsultarEscolaCIESoapOut">
        <part name="parameters" element="s0:ConsultarEscolaCIEResponse"/>
    </message>
    <message name="ConsultarFichaAlunoSoapIn">
        <part name="parameters" element="s0:ConsultarFichaAluno"/>
    </message>
    <message name="ConsultarFichaAlunoSoapOut">
        <part name="parameters" element="s0:ConsultarFichaAlunoResponse"/>
    </message>
    <message name="ConsultarIrmaoSoapIn">
        <part name="parameters" element="s0:ConsultarIrmao"/>
    </message>
    <message name="ConsultarIrmaoSoapOut">
        <part name="parameters" element="s0:ConsultarIrmaoResponse"/>
    </message>
    <message name="ConsultarMatriculaClasseRASoapIn">
        <part name="parameters" element="s0:ConsultarMatriculaClasseRA"/>
    </message>
    <message name="ConsultarMatriculaClasseRASoapOut">
        <part name="parameters" element="s0:ConsultarMatriculaClasseRAResponse"/>
    </message>
    <message name="ConsultarMatriculaPorClasseSoapIn">
        <part name="parameters" element="s0:ConsultarMatriculaPorClasse"/>
    </message>
    <message name="ConsultarMatriculaPorClasseSoapOut">
        <part name="parameters" element="s0:ConsultarMatriculaPorClasseResponse"/>
    </message>
    <message name="ConsultarMatriculasSoapIn">
        <part name="parameters" element="s0:ConsultarMatriculas"/>
    </message>
    <message name="ConsultarMatriculasSoapOut">
        <part name="parameters" element="s0:ConsultarMatriculasResponse"/>
    </message>
    <message name="ConsultarMatriculasRASoapIn">
        <part name="parameters" element="s0:ConsultarMatriculasRA"/>
    </message>
    <message name="ConsultarMatriculasRASoapOut">
        <part name="parameters" element="s0:ConsultarMatriculasRAResponse"/>
    </message>
    <message name="ConsultarQuadroResumoSoapIn">
        <part name="parameters" element="s0:ConsultarQuadroResumo"/>
    </message>
    <message name="ConsultarQuadroResumoSoapOut">
        <part name="parameters" element="s0:ConsultarQuadroResumoResponse"/>
    </message>
    <message name="ConsultarRendPorNumeroClasseSoapIn">
        <part name="parameters" element="s0:ConsultarRendPorNumeroClasse"/>
    </message>
    <message name="ConsultarRendPorNumeroClasseSoapOut">
        <part name="parameters" element="s0:ConsultarRendPorNumeroClasseResponse"/>
    </message>
    <message name="ConsultarTotaisporEscolaSoapIn">
        <part name="parameters" element="s0:ConsultarTotaisporEscola"/>
    </message>
    <message name="ConsultarTotaisporEscolaSoapOut">
        <part name="parameters" element="s0:ConsultarTotaisporEscolaResponse"/>
    </message>
    <message name="DefinirAlunoEnsinoMedioSoapIn">
        <part name="parameters" element="s0:DefinirAlunoEnsinoMedio"/>
    </message>
    <message name="DefinirAlunoEnsinoMedioSoapOut">
        <part name="parameters" element="s0:DefinirAlunoEnsinoMedioResponse"/>
    </message>
    <message name="EncerrarRendimentoEscolarPorCIESoapIn">
        <part name="parameters" element="s0:EncerrarRendimentoEscolarPorCIE"/>
    </message>
    <message name="EncerrarRendimentoEscolarPorCIESoapOut">
        <part name="parameters" element="s0:EncerrarRendimentoEscolarPorCIEResponse"/>
    </message>
    <message name="EstornarBaixaMatrTransfSoapIn">
        <part name="parameters" element="s0:EstornarBaixaMatrTransf"/>
    </message>
    <message name="EstornarBaixaMatrTransfSoapOut">
        <part name="parameters" element="s0:EstornarBaixaMatrTransfResponse"/>
    </message>
    <message name="EstornarRegistroAbandonoSoapIn">
        <part name="parameters" element="s0:EstornarRegistroAbandono"/>
    </message>
    <message name="EstornarRegistroAbandonoSoapOut">
        <part name="parameters" element="s0:EstornarRegistroAbandonoResponse"/>
    </message>
    <message name="ExcluirColetaClasseSoapIn">
        <part name="parameters" element="s0:ExcluirColetaClasse"/>
    </message>
    <message name="ExcluirColetaClasseSoapOut">
        <part name="parameters" element="s0:ExcluirColetaClasseResponse"/>
    </message>
    <message name="ExcluirIrmaoSoapIn">
        <part name="parameters" element="s0:ExcluirIrmao"/>
    </message>
    <message name="ExcluirIrmaoSoapOut">
        <part name="parameters" element="s0:ExcluirIrmaoResponse"/>
    </message>
    <message name="ExcluirMatriculaSoapIn">
        <part name="parameters" element="s0:ExcluirMatricula"/>
    </message>
    <message name="ExcluirMatriculaSoapOut">
        <part name="parameters" element="s0:ExcluirMatriculaResponse"/>
    </message>
    <message name="IncluirColetaClasseSoapIn">
        <part name="parameters" element="s0:IncluirColetaClasse"/>
    </message>
    <message name="IncluirColetaClasseSoapOut">
        <part name="parameters" element="s0:IncluirColetaClasseResponse"/>
    </message>
    <message name="InformarTerminoDigitacaoSoapIn">
        <part name="parameters" element="s0:InformarTerminoDigitacao"/>
    </message>
    <message name="InformarTerminoDigitacaoSoapOut">
        <part name="parameters" element="s0:InformarTerminoDigitacaoResponse"/>
    </message>
    <message name="InscreverAlunoPorDeslocamentoSoapIn">
        <part name="parameters" element="s0:InscreverAlunoPorDeslocamento"/>
    </message>
    <message name="InscreverAlunoPorDeslocamentoSoapOut">
        <part name="parameters" element="s0:InscreverAlunoPorDeslocamentoResponse"/>
    </message>
    <message name="InscreverAlunotransferenciaSoapIn">
        <part name="parameters" element="s0:InscreverAlunotransferencia"/>
    </message>
    <message name="InscreverAlunotransferenciaSoapOut">
        <part name="parameters" element="s0:InscreverAlunotransferenciaResponse"/>
    </message>
    <message name="InscricaoIntencaoTransferenciaSoapIn">
        <part name="parameters" element="s0:InscricaoIntencaoTransferencia"/>
    </message>
    <message name="InscricaoIntencaoTransferenciaSoapOut">
        <part name="parameters" element="s0:InscricaoIntencaoTransferenciaResponse"/>
    </message>
    <message name="LancarRendimentoEscolarClasseSoapIn">
        <part name="parameters" element="s0:LancarRendimentoEscolarClasse"/>
    </message>
    <message name="LancarRendimentoEscolarClasseSoapOut">
        <part name="parameters" element="s0:LancarRendimentoEscolarClasseResponse"/>
    </message>
    <message name="RealizarMatricAntecipadaFasesSoapIn">
        <part name="parameters" element="s0:RealizarMatricAntecipadaFases"/>
    </message>
    <message name="RealizarMatricAntecipadaFasesSoapOut">
        <part name="parameters" element="s0:RealizarMatricAntecipadaFasesResponse"/>
    </message>
    <message name="RealizarMatricAntecipadaRAFasesSoapIn">
        <part name="parameters" element="s0:RealizarMatricAntecipadaRAFases"/>
    </message>
    <message name="RealizarMatricAntecipadaRAFasesSoapOut">
        <part name="parameters" element="s0:RealizarMatricAntecipadaRAFasesResponse"/>
    </message>
    <message name="RealizarMatriculaAntecipadaSoapIn">
        <part name="parameters" element="s0:RealizarMatriculaAntecipada"/>
    </message>
    <message name="RealizarMatriculaAntecipadaSoapOut">
        <part name="parameters" element="s0:RealizarMatriculaAntecipadaResponse"/>
    </message>
    <message name="RealizarMatriculaInfoComRASoapIn">
        <part name="parameters" element="s0:RealizarMatriculaInfoComRA"/>
    </message>
    <message name="RealizarMatriculaInfoComRASoapOut">
        <part name="parameters" element="s0:RealizarMatriculaInfoComRAResponse"/>
    </message>
    <message name="RealizarMatriculaInfoSemRASoapIn">
        <part name="parameters" element="s0:RealizarMatriculaInfoSemRA"/>
    </message>
    <message name="RealizarMatriculaInfoSemRASoapOut">
        <part name="parameters" element="s0:RealizarMatriculaInfoSemRAResponse"/>
    </message>
    <message name="ReclassificarMatriculasSoapIn">
        <part name="parameters" element="s0:ReclassificarMatriculas"/>
    </message>
    <message name="ReclassificarMatriculasSoapOut">
        <part name="parameters" element="s0:ReclassificarMatriculasResponse"/>
    </message>
    <message name="RegistrarAbandonoSoapIn">
        <part name="parameters" element="s0:RegistrarAbandono"/>
    </message>
    <message name="RegistrarAbandonoSoapOut">
        <part name="parameters" element="s0:RegistrarAbandonoResponse"/>
    </message>
    <message name="RegistrarNaoComparecimentoSoapIn">
        <part name="parameters" element="s0:RegistrarNaoComparecimento"/>
    </message>
    <message name="RegistrarNaoComparecimentoSoapOut">
        <part name="parameters" element="s0:RegistrarNaoComparecimentoResponse"/>
    </message>
    <message name="RemanejarMatPorRASoapIn">
        <part name="parameters" element="s0:RemanejarMatPorRA"/>
    </message>
    <message name="RemanejarMatPorRASoapOut">
        <part name="parameters" element="s0:RemanejarMatPorRAResponse"/>
    </message>
    <message name="TrocarAlunoClasseRASoapIn">
        <part name="parameters" element="s0:TrocarAlunoClasseRA"/>
    </message>
    <message name="TrocarAlunoClasseRASoapOut">
        <part name="parameters" element="s0:TrocarAlunoClasseRAResponse"/>
    </message>
    <portType name="SecretariaMunicipalSoap">
        <operation name="AlterarColetaClasse">
            <input message="s0:AlterarColetaClasseSoapIn"/>
            <output message="s0:AlterarColetaClasseSoapOut"/>
        </operation>
        <operation name="AlterarDadosPessoaisFichaAluno">
            <input message="s0:AlterarDadosPessoaisFichaAlunoSoapIn"/>
            <output message="s0:AlterarDadosPessoaisFichaAlunoSoapOut"/>
        </operation>
        <operation name="AlterarDocumentosFichaAluno">
            <input message="s0:AlterarDocumentosFichaAlunoSoapIn"/>
            <output message="s0:AlterarDocumentosFichaAlunoSoapOut"/>
        </operation>
        <operation name="AlterarEnderecoFichaAluno">
            <input message="s0:AlterarEnderecoFichaAlunoSoapIn"/>
            <output message="s0:AlterarEnderecoFichaAlunoSoapOut"/>
        </operation>
        <operation name="AlterarEnderecoIndicativo">
            <input message="s0:AlterarEnderecoIndicativoSoapIn"/>
            <output message="s0:AlterarEnderecoIndicativoSoapOut"/>
        </operation>
        <operation name="AssociarIrmao">
            <input message="s0:AssociarIrmaoSoapIn"/>
            <output message="s0:AssociarIrmaoSoapOut"/>
        </operation>
        <operation name="BaixarMatrFalecimentoRA">
            <input message="s0:BaixarMatrFalecimentoRASoapIn"/>
            <output message="s0:BaixarMatrFalecimentoRASoapOut"/>
        </operation>
        <operation name="BaixarMatriculaTransferencia">
            <input message="s0:BaixarMatriculaTransferenciaSoapIn"/>
            <output message="s0:BaixarMatriculaTransferenciaSoapOut"/>
        </operation>
        <operation name="CancelarEncerramentoRendEscolar">
            <input message="s0:CancelarEncerramentoRendEscolarSoapIn"/>
            <output message="s0:CancelarEncerramentoRendEscolarSoapOut"/>
        </operation>
        <operation name="CancelarInscricaoAlunoPorDeslocamento">
            <input message="s0:CancelarInscricaoAlunoPorDeslocamentoSoapIn"/>
            <output message="s0:CancelarInscricaoAlunoPorDeslocamentoSoapOut"/>
        </operation>
        <operation name="CancelarInscricaoAlunoTransf">
            <input message="s0:CancelarInscricaoAlunoTransfSoapIn"/>
            <output message="s0:CancelarInscricaoAlunoTransfSoapOut"/>
        </operation>
        <operation name="CancelarInscricaoDefinicao">
            <input message="s0:CancelarInscricaoDefinicaoSoapIn"/>
            <output message="s0:CancelarInscricaoDefinicaoSoapOut"/>
        </operation>
        <operation name="CancelarIntencaoTransferencia">
            <input message="s0:CancelarIntencaoTransferenciaSoapIn"/>
            <output message="s0:CancelarIntencaoTransferenciaSoapOut"/>
        </operation>
        <operation name="CancelarTerminoDigitacao">
            <input message="s0:CancelarTerminoDigitacaoSoapIn"/>
            <output message="s0:CancelarTerminoDigitacaoSoapOut"/>
        </operation>
        <operation name="ClassificarGerarNrChamadaClassePorEscola">
            <input message="s0:ClassificarGerarNrChamadaClassePorEscolaSoapIn"/>
            <output message="s0:ClassificarGerarNrChamadaClassePorEscolaSoapOut"/>
        </operation>
        <operation name="ClassificarGerarNumeroChamadaPorClasse">
            <input message="s0:ClassificarGerarNumeroChamadaPorClasseSoapIn"/>
            <output message="s0:ClassificarGerarNumeroChamadaPorClasseSoapOut"/>
        </operation>
        <operation name="ConsultaClasseAlunoPorEscola">
            <input message="s0:ConsultaClasseAlunoPorEscolaSoapIn"/>
            <output message="s0:ConsultaClasseAlunoPorEscolaSoapOut"/>
        </operation>
        <operation name="ConsultaFormacaoClasse">
            <input message="s0:ConsultaFormacaoClasseSoapIn"/>
            <output message="s0:ConsultaFormacaoClasseSoapOut"/>
        </operation>
        <operation name="ConsultarCep">
            <input message="s0:ConsultarCepSoapIn"/>
            <output message="s0:ConsultarCepSoapOut"/>
        </operation>
        <operation name="ConsultarCepLogradouro">
            <input message="s0:ConsultarCepLogradouroSoapIn"/>
            <output message="s0:ConsultarCepLogradouroSoapOut"/>
        </operation>
        <operation name="ConsultarColetaClasse">
            <input message="s0:ConsultarColetaClasseSoapIn"/>
            <output message="s0:ConsultarColetaClasseSoapOut"/>
        </operation>
        <operation name="ConsultarDefinicaoInscricaoRA">
            <input message="s0:ConsultarDefinicaoInscricaoRASoapIn"/>
            <output message="s0:ConsultarDefinicaoInscricaoRASoapOut"/>
        </operation>
        <operation name="ConsultarEscolaCIE">
            <input message="s0:ConsultarEscolaCIESoapIn"/>
            <output message="s0:ConsultarEscolaCIESoapOut"/>
        </operation>
        <operation name="ConsultarFichaAluno">
            <input message="s0:ConsultarFichaAlunoSoapIn"/>
            <output message="s0:ConsultarFichaAlunoSoapOut"/>
        </operation>
        <operation name="ConsultarIrmao">
            <input message="s0:ConsultarIrmaoSoapIn"/>
            <output message="s0:ConsultarIrmaoSoapOut"/>
        </operation>
        <operation name="ConsultarMatriculaClasseRA">
            <input message="s0:ConsultarMatriculaClasseRASoapIn"/>
            <output message="s0:ConsultarMatriculaClasseRASoapOut"/>
        </operation>
        <operation name="ConsultarMatriculaPorClasse">
            <input message="s0:ConsultarMatriculaPorClasseSoapIn"/>
            <output message="s0:ConsultarMatriculaPorClasseSoapOut"/>
        </operation>
        <operation name="ConsultarMatriculas">
            <input message="s0:ConsultarMatriculasSoapIn"/>
            <output message="s0:ConsultarMatriculasSoapOut"/>
        </operation>
        <operation name="ConsultarMatriculasRA">
            <input message="s0:ConsultarMatriculasRASoapIn"/>
            <output message="s0:ConsultarMatriculasRASoapOut"/>
        </operation>
        <operation name="ConsultarQuadroResumo">
            <input message="s0:ConsultarQuadroResumoSoapIn"/>
            <output message="s0:ConsultarQuadroResumoSoapOut"/>
        </operation>
        <operation name="ConsultarRendPorNumeroClasse">
            <input message="s0:ConsultarRendPorNumeroClasseSoapIn"/>
            <output message="s0:ConsultarRendPorNumeroClasseSoapOut"/>
        </operation>
        <operation name="ConsultarTotaisporEscola">
            <input message="s0:ConsultarTotaisporEscolaSoapIn"/>
            <output message="s0:ConsultarTotaisporEscolaSoapOut"/>
        </operation>
        <operation name="DefinirAlunoEnsinoMedio">
            <input message="s0:DefinirAlunoEnsinoMedioSoapIn"/>
            <output message="s0:DefinirAlunoEnsinoMedioSoapOut"/>
        </operation>
        <operation name="EncerrarRendimentoEscolarPorCIE">
            <input message="s0:EncerrarRendimentoEscolarPorCIESoapIn"/>
            <output message="s0:EncerrarRendimentoEscolarPorCIESoapOut"/>
        </operation>
        <operation name="EstornarBaixaMatrTransf">
            <input message="s0:EstornarBaixaMatrTransfSoapIn"/>
            <output message="s0:EstornarBaixaMatrTransfSoapOut"/>
        </operation>
        <operation name="EstornarRegistroAbandono">
            <input message="s0:EstornarRegistroAbandonoSoapIn"/>
            <output message="s0:EstornarRegistroAbandonoSoapOut"/>
        </operation>
        <operation name="ExcluirColetaClasse">
            <input message="s0:ExcluirColetaClasseSoapIn"/>
            <output message="s0:ExcluirColetaClasseSoapOut"/>
        </operation>
        <operation name="ExcluirIrmao">
            <input message="s0:ExcluirIrmaoSoapIn"/>
            <output message="s0:ExcluirIrmaoSoapOut"/>
        </operation>
        <operation name="ExcluirMatricula">
            <input message="s0:ExcluirMatriculaSoapIn"/>
            <output message="s0:ExcluirMatriculaSoapOut"/>
        </operation>
        <operation name="IncluirColetaClasse">
            <input message="s0:IncluirColetaClasseSoapIn"/>
            <output message="s0:IncluirColetaClasseSoapOut"/>
        </operation>
        <operation name="InformarTerminoDigitacao">
            <input message="s0:InformarTerminoDigitacaoSoapIn"/>
            <output message="s0:InformarTerminoDigitacaoSoapOut"/>
        </operation>
        <operation name="InscreverAlunoPorDeslocamento">
            <input message="s0:InscreverAlunoPorDeslocamentoSoapIn"/>
            <output message="s0:InscreverAlunoPorDeslocamentoSoapOut"/>
        </operation>
        <operation name="InscreverAlunotransferencia">
            <input message="s0:InscreverAlunotransferenciaSoapIn"/>
            <output message="s0:InscreverAlunotransferenciaSoapOut"/>
        </operation>
        <operation name="InscricaoIntencaoTransferencia">
            <input message="s0:InscricaoIntencaoTransferenciaSoapIn"/>
            <output message="s0:InscricaoIntencaoTransferenciaSoapOut"/>
        </operation>
        <operation name="LancarRendimentoEscolarClasse">
            <input message="s0:LancarRendimentoEscolarClasseSoapIn"/>
            <output message="s0:LancarRendimentoEscolarClasseSoapOut"/>
        </operation>
        <operation name="RealizarMatricAntecipadaFases">
            <input message="s0:RealizarMatricAntecipadaFasesSoapIn"/>
            <output message="s0:RealizarMatricAntecipadaFasesSoapOut"/>
        </operation>
        <operation name="RealizarMatricAntecipadaRAFases">
            <input message="s0:RealizarMatricAntecipadaRAFasesSoapIn"/>
            <output message="s0:RealizarMatricAntecipadaRAFasesSoapOut"/>
        </operation>
        <operation name="RealizarMatriculaAntecipada">
            <input message="s0:RealizarMatriculaAntecipadaSoapIn"/>
            <output message="s0:RealizarMatriculaAntecipadaSoapOut"/>
        </operation>
        <operation name="RealizarMatriculaInfoComRA">
            <input message="s0:RealizarMatriculaInfoComRASoapIn"/>
            <output message="s0:RealizarMatriculaInfoComRASoapOut"/>
        </operation>
        <operation name="RealizarMatriculaInfoSemRA">
            <input message="s0:RealizarMatriculaInfoSemRASoapIn"/>
            <output message="s0:RealizarMatriculaInfoSemRASoapOut"/>
        </operation>
        <operation name="ReclassificarMatriculas">
            <input message="s0:ReclassificarMatriculasSoapIn"/>
            <output message="s0:ReclassificarMatriculasSoapOut"/>
        </operation>
        <operation name="RegistrarAbandono">
            <input message="s0:RegistrarAbandonoSoapIn"/>
            <output message="s0:RegistrarAbandonoSoapOut"/>
        </operation>
        <operation name="RegistrarNaoComparecimento">
            <input message="s0:RegistrarNaoComparecimentoSoapIn"/>
            <output message="s0:RegistrarNaoComparecimentoSoapOut"/>
        </operation>
        <operation name="RemanejarMatPorRA">
            <input message="s0:RemanejarMatPorRASoapIn"/>
            <output message="s0:RemanejarMatPorRASoapOut"/>
        </operation>
        <operation name="TrocarAlunoClasseRA">
            <input message="s0:TrocarAlunoClasseRASoapIn"/>
            <output message="s0:TrocarAlunoClasseRASoapOut"/>
        </operation>
    </portType>
    <binding name="SecretariaMunicipalSoap" type="s0:SecretariaMunicipalSoap">
        <soap:binding transport="http://schemas.xmlsoap.org/soap/http" style="document"/>
        <operation name="AlterarColetaClasse">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.AlterarColetaClasse" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="AlterarDadosPessoaisFichaAluno">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.AlterarDadosPessoaisFichaAluno" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="AlterarDocumentosFichaAluno">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.AlterarDocumentosFichaAluno" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="AlterarEnderecoFichaAluno">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.AlterarEnderecoFichaAluno" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="AlterarEnderecoIndicativo">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.AlterarEnderecoIndicativo" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="AssociarIrmao">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.AssociarIrmao" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="BaixarMatrFalecimentoRA">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.BaixarMatrFalecimentoRA" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="BaixarMatriculaTransferencia">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.BaixarMatriculaTransferencia" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="CancelarEncerramentoRendEscolar">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.CancelarEncerramentoRendEscolar" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="CancelarInscricaoAlunoPorDeslocamento">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.CancelarInscricaoAlunoPorDeslocamento" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="CancelarInscricaoAlunoTransf">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.CancelarInscricaoAlunoTransf" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="CancelarInscricaoDefinicao">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.CancelarInscricaoDefinicao" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="CancelarIntencaoTransferencia">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.CancelarIntencaoTransferencia" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="CancelarTerminoDigitacao">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.CancelarTerminoDigitacao" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ClassificarGerarNrChamadaClassePorEscola">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ClassificarGerarNrChamadaClassePorEscola" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ClassificarGerarNumeroChamadaPorClasse">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ClassificarGerarNumeroChamadaPorClasse" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ConsultaClasseAlunoPorEscola">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ConsultaClasseAlunoPorEscola" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ConsultaFormacaoClasse">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ConsultaFormacaoClasse" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ConsultarCep">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ConsultarCep" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ConsultarCepLogradouro">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ConsultarCepLogradouro" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ConsultarColetaClasse">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ConsultarColetaClasse" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ConsultarDefinicaoInscricaoRA">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ConsultarDefinicaoInscricaoRA" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ConsultarEscolaCIE">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ConsultarEscolaCIE" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ConsultarFichaAluno">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ConsultarFichaAluno" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ConsultarIrmao">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ConsultarIrmao" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ConsultarMatriculaClasseRA">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ConsultarMatriculaClasseRA" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ConsultarMatriculaPorClasse">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ConsultarMatriculaPorClasse" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ConsultarMatriculas">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ConsultarMatriculas" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ConsultarMatriculasRA">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ConsultarMatriculasRA" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ConsultarQuadroResumo">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ConsultarQuadroResumo" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ConsultarRendPorNumeroClasse">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ConsultarRendPorNumeroClasse" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ConsultarTotaisporEscola">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ConsultarTotaisporEscola" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="DefinirAlunoEnsinoMedio">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.DefinirAlunoEnsinoMedio" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="EncerrarRendimentoEscolarPorCIE">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.EncerrarRendimentoEscolarPorCIE" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="EstornarBaixaMatrTransf">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.EstornarBaixaMatrTransf" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="EstornarRegistroAbandono">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.EstornarRegistroAbandono" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ExcluirColetaClasse">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ExcluirColetaClasse" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ExcluirIrmao">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ExcluirIrmao" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ExcluirMatricula">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ExcluirMatricula" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="IncluirColetaClasse">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.IncluirColetaClasse" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="InformarTerminoDigitacao">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.InformarTerminoDigitacao" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="InscreverAlunoPorDeslocamento">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.InscreverAlunoPorDeslocamento" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="InscreverAlunotransferencia">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.InscreverAlunotransferencia" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="InscricaoIntencaoTransferencia">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.InscricaoIntencaoTransferencia" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="LancarRendimentoEscolarClasse">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.LancarRendimentoEscolarClasse" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="RealizarMatricAntecipadaFases">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.RealizarMatricAntecipadaFases" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="RealizarMatricAntecipadaRAFases">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.RealizarMatricAntecipadaRAFases" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="RealizarMatriculaAntecipada">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.RealizarMatriculaAntecipada" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="RealizarMatriculaInfoComRA">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.RealizarMatriculaInfoComRA" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="RealizarMatriculaInfoSemRA">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.RealizarMatriculaInfoSemRA" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="ReclassificarMatriculas">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.ReclassificarMatriculas" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="RegistrarAbandono">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.RegistrarAbandono" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="RegistrarNaoComparecimento">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.RegistrarNaoComparecimento" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="RemanejarMatPorRA">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.RemanejarMatPorRA" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
        <operation name="TrocarAlunoClasseRA">
            <soap:operation soapAction="http://www.educacao.sp.gov.br/Ensemble/Sec.BS.SecretariaMunicipal.TrocarAlunoClasseRA" style="document"/>
            <input>
                <soap:body use="literal"/>
            </input>
            <output>
                <soap:body use="literal"/>
            </output>
        </operation>
    </binding>
    <service name="SecretariaMunicipal">
        <port name="SecretariaMunicipalSoap" binding="s0:SecretariaMunicipalSoap">
            <soap:address location="https://gdaenet.edunet.sp.gov.br:57772/educacao/Sec.BS.SecretariaMunicipal.cls"/>
        </port>
    </service>
</definitions>
XML;

function xmlToArray($xml, $options = array()) {
    $defaults = array(
        'namespaceSeparator' => ':', // você pode querer que isso seja algo diferente de um cólon
        'attributePrefix' => '@', // para distinguir entre os nós e os atributos com o mesmo nome
        'alwaysArray' => array(), // array de tags que devem sempre ser array
        'autoArray' => true, // só criar arrays para as tags que aparecem mais de uma vez
        'textContent' => '$', // chave utilizada para o conteúdo do texto de elementos
        'autoText' => true, // pular chave "textContent" se o nó não tem atributos ou nós filho
        'keySearch' => false, // pesquisa opcional e substituir na tag e nomes de atributos
        'keyReplace' => false        // substituir valores por valores acima de busca
    );
    $options = array_merge($defaults, $options);
    $namespaces = $xml->getDocNamespaces();
    $namespaces[''] = null; // adiciona namespace base(vazio) 
    // Obtém os atributos de todos os namespaces
    $attributesArray = array();
    foreach ($namespaces as $prefix => $namespace) {
        foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
            // Substituir caracteres no nome do atributo
            if ($options['keySearch'])
                $attributeName = str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
            $attributeKey = $options['attributePrefix']
                    . ($prefix ? $prefix . $options['namespaceSeparator'] : '')
                    . $attributeName;
            $attributesArray[$attributeKey] = (string) $attribute;
        }
    }

    // Obtém nós filhos de todos os namespaces
    $tagsArray = array();
    foreach ($namespaces as $prefix => $namespace) {
        foreach ($xml->children($namespace) as $childXml) {
            // Recursividade em nós filho
            $childArray = xmlToArray($childXml, $options);
            list($childTagName, $childProperties) = each($childArray);

            // Substituir caracteres no nome da tag
            if ($options['keySearch'])
                $childTagName = str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
            // Adiciona um prefixo namespace, se houver
            if ($prefix)
                $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;

            if (!isset($tagsArray[$childTagName])) {
                // Só entra com esta chave
                // Testa se as tags deste tipo deve ser sempre matrizes, não importa a contagem de elementos
                $tagsArray[$childTagName] = in_array($childTagName, $options['alwaysArray']) || !$options['autoArray'] ? array($childProperties) : $childProperties;
            } elseif (
                    is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName]) === range(0, count($tagsArray[$childTagName]) - 1)
            ) {
                $tagsArray[$childTagName][] = $childProperties;
            } else {
                $tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
            }
        }
    }

    // Obtém o texto do nó
    $textContentArray = array();
    $plainText = trim((string) $xml);
    if ($plainText !== '')
        $textContentArray[$options['textContent']] = $plainText;

    $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '') ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;

    // Retorna o nó como array
    return array(
        $xml->getName() => $propertiesArray
    );
}

$xml_ = simplexml_load_string($xml);
$arrayData = xmlToArray($xml_);

$arrayData = current($arrayData)['types']['s:schema']['s:element'];

foreach ($arrayData as $v) {
    $element = NULL;
    $element1 = NULL;
    foreach ($v['s:complexType']['s:sequence']['s:element'] as $vv) {
        if ($vv['@name'] != 'Usuario' && $vv['@name'] != 'Senha' && substr($vv['@name'], 0, 2) == 'in') {
            $element[] = '$'. substr($vv['@name'], 2);
            $element1[] = "'".$vv['@name']."' => ". '$'. substr($vv['@name'], 2);
        }
    }
    if (!empty($element)) {
        echo '<br /><br /><br />';
        echo 'public function ' . $v['@name'] . '(<br />' . implode(',<br />', $element).'){<br />';
        echo '$function'." = '".$v['@name']."';<br />";
        echo '$arguments' ."= array('".$v['@name']."' => array(<br />";
        echo implode(',<br />', $element1);
        echo '<br />));';
        echo '<br /><br />$result = $this->exec($function, $arguments);<br /><br />return $result;';
        echo '<br />}';
       
        //print_r($v['s:complexType']['s:sequence']['s:element']);
    }
}