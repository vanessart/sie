<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of restImport
 *
 * @author marco
 */
class restImport {

    /**
     * 
     * @param type $prodesp incluir número prodesp da turma
     * @return type falso/verdadeiro
     */
    public static function turmasListaNegra($prodesp) {
        $lista = [
            262918667,
            262919665,
            261680318,
            261669139,
            261686570,
            261689095,
            261623490,
            261776926,
            261842280,
            261849236,
            261863294,
            261856561,
            260033212
        ];
        return in_array($prodesp, $lista);
    }

    public static function baixarClasses($anoLetivo, $id_inst = NULL) {
        $return = [];
        $ciclos = ng_sedErp::cicloErpSed();
        $turno = ng_sedErp::turno();
        $dt_gdae = date("Y-m-d H:i:s");

        $escolas = escolas::gets($id_inst, 'id_inst', 'cie_escola, id_inst, n_inst');

        foreach ($escolas as $v) {
            if (!empty($v['cie_escola'])) {
                $dadosSed = rest::relacaoClasses($v['cie_escola'], $anoLetivo, 1);
                $turmaExiste = ng_sedErp::turmasInst($v['id_inst'], $anoLetivo);

                if (!empty($dadosSed['outClasses'])) {
                    foreach ($dadosSed['outClasses'] as $s) {
                        if (restImport::turmasListaNegra($s['outNumClasse'])) {
                            continue;
                        }
                        $letra = strtoupper(@$s['outTurma']);
                        $ciclo = @$ciclos[@$s['outCodTipoEnsino']][@$s['outCodSerieAno']];
                        $fk_id_pl = ng_sedErp::periodoLetivo(@$anoLetivo, intval(@$s['outSemestre']));
                        if (empty($fk_id_pl)) {
                            toolErp::alert("Não existe Período Letivo ativo em $anoLetivo");
                            return;
                        }
                        $n_turma = @$ciclo['n_ciclo'] . ' ' . @$letra;
                        $periodo = $turno[$s['outCodTurno']];
                        $codigo = @$ciclo['sg_curso'] . @$periodo . @$ciclo['sg_ciclo'] . @$letra;
                        if (!empty(@$turmaExiste[$ciclo['id_ciclo']][@$letra])) {
                            $id_turma = $turmaExiste[@$ciclo['id_ciclo']][@$letra];
                        } else {
                            $id_turma = 'NULL';
                        }
                        if (!empty($ciclo['id_ciclo']) && !empty($ciclo['fk_id_grade'])) {
                            $sql = "replace INTO `ge_turmas` ("
                                    . " `id_turma`, "
                                    . " `n_turma`, "
                                    . " `fk_id_inst`, "
                                    . " `fk_id_ciclo`, "
                                    . " `fk_id_grade`, "
                                    . " `codigo`, "
                                    . " `periodo`, "
                                    . " `periodo_letivo`, "
                                    . " `fk_id_pl`, "
                                    . " `letra`, "
                                    . " `prodesp`, "
                                    . " `status`, "
                                    . " `dt_gdae` "
                                    . ") VALUES ("
                                    . " $id_turma, "
                                    . " '$n_turma', "
                                    . " '" . @$v['id_inst'] . "', "
                                    . " '" . @$ciclo['id_ciclo'] . "', "
                                    . " '" . @$ciclo['fk_id_grade'] . "', "
                                    . " '$codigo', "
                                    . " '$periodo', "
                                    . " '$anoLetivo', "
                                    . " '$fk_id_pl', "
                                    . " '" . $letra . "', "
                                    . " '" . $s['outNumClasse'] . "', "
                                    . "1,"
                                    . " '$dt_gdae' "
                                    . ")";
                            try {
                                $query = pdoSis::getInstance()->query($sql);
                                $return[] = [
                                    'Escola' => $v['n_inst'],
                                    'Turma' => $n_turma,
                                    'id_turma' => $id_turma,
                                    'prodesp' => $s['outNumClasse']
                                ];
                            } catch (Exception $exc) {
                                $sql = "INSERT INTO `sed_erro` (erro) VALUES ('Turma " . $s['outNumClasse'] . " não registrada - id_grade " . $ciclo['fk_id_grade'] . " - id_turma: $id_turma.');";
                                $query = pdoSis::getInstance()->query($sql);
                            }
                        } else {
                            $sql = "INSERT INTO `sed_erro` (erro) VALUES ('Não veio o id_ciclo " . $n_turma . " , instancia: " . @$v['id_inst'] . " campos SED - outCodTipoEnsino: " . @$s['outCodTipoEnsino'] . 'outCodSerieAno: ' . @$s['outCodSerieAno'] . " .');";
                            $query = pdoSis::getInstance()->query($sql);
                        }
                        if (empty($id_turma)) {
                            $sql = "INSERT INTO `sed_erro` (erro) VALUES ('Turma nova: " . $n_turma . " , instancia: " . @$v['id_inst'] . " não registrada.');";
                            $query = pdoSis::getInstance()->query($sql);
                        }
                    }
                }
            }
        }
        return $return;
    }

    public static function baixarTurmaAluno($codClasse = NULL, $id_inst = NULL, $id_pl = NULL, $sincronizar = null) {
        $sitSieb = restImport::sitSedSieb();
        $fieldsTurma = " codigo, id_turma, periodo_letivo, fk_id_inst, prodesp, fk_id_pl ";
        if (!empty($codClasse)) {
            $dados = rest::formacaoClasse($codClasse, 1);
            @$turma = sql::get('ge_turmas', $fieldsTurma, ['prodesp' => $codClasse], 'fetch');
            if (!empty($sincronizar)) {
                restImport::sicronizaTurmaAluno($dados, $turma, $sitSieb);
            }
            return $dados;
        } elseif (!empty($id_inst) && !empty($id_pl)) {
            $sql = "Select $fieldsTurma from ge_turmas "
                    . " where fk_id_inst = $id_inst "
                    . " and fk_id_pl in ($id_pl)";
            $query = pdoSis::getInstance()->query($sql);
            $turmas = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($turmas as $v) {
                $dados = rest::formacaoClasse($v['prodesp'], 1);
                if (!empty($sincronizar)) {
                    restImport::sicronizaTurmaAluno($dados, $v, $sitSieb);
                }
            }
        } else {
            tool::alert('Não há critérios suficiente');
            return;
        }
    }

    public static function alunosTurmaErp($id_turma) {
        $fields = 'id_turma_aluno, chamada, situacao, fk_id_sit_sed, fk_id_ciclo_aluno '
                . ', id_pessoa, n_pessoa, dt_nasc, ra, ra_dig, ra_uf';
        $aS = sql::get(['ge_turma_aluno', 'pessoa'], $fields, ['fk_id_turma' => $id_turma, '>' => 'chamada']);
        foreach ($aS as $v) {
            $alunosSieb[$v['ra']][] = $v;
        }
        if (!empty($alunosSieb)) {
            return $alunosSieb;
        } else {
            return [];
        }
    }

    public static function sicronizaTurmaAluno($dados, $turma, $sitSieb) {
        ini_set('max_execution_time', '200');
        set_time_limit(200);
        if (!empty($dados['outAlunos'])) {
            $sql = "update ge_turma_aluno set "
                    . " chamada = null "
                    . " where fk_id_turma = " . $turma['id_turma'];
            try {
                $query = pdoSis::getInstance()->query($sql);
            } catch (Exception $exc) {
                
            }


            foreach ($dados['outAlunos'] as $v) {
                if (!empty($v['outNumAluno'])) {
                    $dadosFormat[intval($v['outNumAluno'])] = $v;
                }
            }
            if (!empty($dadosFormat)) {
                ksort($dadosFormat);
                unset($dados);
                $alunosSieb = restImport::alunosTurmaErp($turma['id_turma']);

                foreach ($dadosFormat as $v) {
                    $raSed = intval($v['outNumRA']);
                    if (!empty($alunosSieb[$raSed])) {
                        $key = key($alunosSieb[$raSed]);
                        $aluno = $alunosSieb[$raSed][$key];
                        unset($alunosSieb[$raSed][$key]);
                        if (!current($alunosSieb[$raSed])) {
                            unset($alunosSieb[$raSed]);
                        }
                    } else {
                        $aluno = null;
                    }

                    if (!empty($aluno)) {
                        if ($v['outCodSitMatricula'] != $aluno['fk_id_sit_sed']) {

                            //mudar situação do aluno
                            if ($v['outCodSitMatricula'] != 0) {
                                $dt_matricula_fim = " dt_matricula_fim = '" . date("Y-m-d") . "', ";
                                $dt_matricula_fim .= " dt_transferencia = '" . date("Y-m-d") . "', ";
                            } else {
                                $dt_matricula_fim = null;
                            }

                            $sql = "UPDATE ge_turma_aluno SET "
                                    . " situacao = '" . $sitSieb[$v['outCodSitMatricula']]['n_tas'] . "', "
                                    . " fk_id_sit_sed = " . $v['outCodSitMatricula'] . ", "
                                    . $dt_matricula_fim
                                    . " fk_id_tas = " . $sitSieb[$v['outCodSitMatricula']]['id_tas']
                                    . " WHERE id_turma_aluno = " . $aluno['id_turma_aluno'];
                            $query = pdoSis::getInstance()->query($sql);
                        }
                        $sql = "update ge_turma_aluno set "
                                . "chamada = '" . $v['outNumAluno'] . "' "
                                . " where id_turma_aluno = " . $aluno['id_turma_aluno'];
                        $query = pdoSis::getInstance()->query($sql);
                        /**
                          $sql = "SELECT n_pessoa, id_pessoa, dt_nasc FROM `pessoa` WHERE `ra` LIKE '" . intval($raSed) . "' AND `ra_uf` LIKE '" . $v['outSiglaUFRA'] . "'";
                          $query = pdoSis::getInstance()->query($sql);
                          $pessoa = $query->fetch(PDO::FETCH_ASSOC);
                          if ($pessoa) {
                          if (empty($pessoa['n_pessoa'])) {
                          $sql = "update pessoa set "
                          . " n_pessoa = '" . $v['outNomeAluno'] . "' "
                          . "where id_pessoa = " . $pessoa['id_pessoa'];
                          $query = pdoSis::getInstance()->query($sql);
                          }
                          if (empty($pessoa['dt_nasc']) || @$pessoa['dt_nasc'] = '0000-00-00') {
                          $sql = "update pessoa set "
                          . " dt_nasc = '" . data::converteUS($v['outDataNascimento']) . "' "
                          . " where id_pessoa = " . $pessoa['id_pessoa'];
                          $query = pdoSis::getInstance()->query($sql);
                          }
                          }
                         * 
                         */
                    } else {
                        $sql = "SELECT * FROM `pessoa` WHERE `ra` LIKE '" . intval($raSed) . "' AND `ra_uf` LIKE '" . $v['outSiglaUFRA'] . "'";
                        try {
                            $query = pdoSis::getInstance()->query($sql);
                        } catch (Exception $exc) {
                            ?>
                            <div class="alert alert-danger">
                                <p>ocorreu um erro. Para resolver, encaminhe o código abaixo ao DTTIE</p>
                                <?= $sql ?>
                            </div>
                            <?php
                        }


                        $pessoa = $query->fetch(PDO::FETCH_ASSOC);
                        if (empty($pessoa)) {
                            $pessoa = sql::get('pessoa', '*', ['n_pessoa' => str_replace("'", '', $v['outNomeAluno']), 'dt_nasc' => data::converteUS($v['outDataNascimento'])], 'fetch');
                            if ($pessoa) {
                                $sql = "update pessoa set "
                                        . " ra = '" . $v['outNumRA'] . "', "
                                        . " ra_uf =  '" . trim($v['outSiglaUFRA']) . "', "
                                        . " ra_dig = '" . trim($v['outDigitoRA']) . "' "
                                        . " where id_pessoa = " . $pessoa['id_pessoa'];
                                $query = pdoSis::getInstance()->query($sql);
                            }
                        } elseif (empty($pessoa['n_pessoa'])) {
                            $sql = "update pessoa set "
                                    . " n_pessoa = '" . $v['outNomeAluno'] . "' "
                                    . "where id_pessoa = " . $pessoa['id_pessoa'];
                            $query = pdoSis::getInstance()->query($sql);
                        }
                        if (empty($pessoa)) {
                            $pessoa = restImport::alunoNovoRede($v['outNumRA'], $v['outSiglaUFRA'], $v['outDigitoRA']);
                        }
                        if (is_array(@$pessoa)) {
                            restImport::matricula($pessoa, $turma, $v, $sitSieb);
                        } else {
                            $sql = "INSERT INTO `sed_erro` (erro) VALUES ('Aluno RA " . $v['outNumRA'] . " não registrado.');";
                            $query = pdoSis::getInstance()->query($sql);
                        }
                    }
                }
            }
            if (!empty($alunosSieb)) {
                restImport::apagarAlunoTurma($alunosSieb);
            }
            $sql = "UPDATE `ge_turmas` SET dt_gdae = '" . date('Y-m-d H:i:s') . "' WHERE id_turma = '" . $turma['id_turma'] . "'";
            $query = pdoSis::getInstance()->query($sql);
        } else {
            $sql = "INSERT INTO `sed_erro` (erro) VALUES ('Não foi recebido os dados do SED referente a id_turma " . $turma['id_turma'] . "');";
            $query = pdoSis::getInstance()->query($sql);
        }
    }

    public static function apagarAlunoTurma($arr) {
        foreach ($arr as $y) {
            foreach ($y as $v) {
                $sql = "DELETE FROM `ge_turma_aluno` WHERE `ge_turma_aluno`.`id_turma_aluno` = " . $v['id_turma_aluno'];
                $query = pdoSis::getInstance()->query($sql);
            }
        }
    }

    public static function sitSedSieb() {
        $sql = "SELECT se.id_tass, si.* FROM ge_turma_aluno_situacao_sed se "
                . " JOIN ge_turma_aluno_situacao si on si.id_tas = se.fk_id_tas ";
        $query = pdoSis::getInstance()->query($sql);
        $arr = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($arr as $v) {
            $sit[$v['id_tass']] = $v;
        }

        return $sit;
    }

    public static function alunoNovoRede($ra, $uf, $dig = null, $id_pessoa = null) {
        if ($id_pessoa) {
            $sql = "SELECT "
                    . " p.id_pessoa, IF(v.fk_id_pessoa is not null, v.fk_id_pessoa, p.n_pessoa) as n_pessoa, "
                    . "p.cpf,  p.obs, p.rg, p.rg_dig, p.rg_uf, p.rg_oe, p.dt_rg, p.pai, p.id_pessoa,"
                    . "  p.novacert_cartorio "
                    . " FROM pessoa p "
                    . " LEFT JOIN ge_aluno_nsdp v on v.fk_id_pessoa = p.id_pessoa "
                    . " where p.id_pessoa = $id_pessoa ";
            $query = pdoSis::getInstance()->query($sql);
            $pessoaSieb = $query->fetch(PDO::FETCH_ASSOC);
        }
        $dados = rest::exibirFichaAluno($ra, $uf, $dig, 1);
        if ($dados) {
            if (!empty($dados['outErro']) || !empty($dados->outErro) || empty($dados['outDadosPessoais']['outCodSexo'])) {
                echo 'Erro<br />';
                return @$dados['outErro'];
            } else {
                if (!empty($dados['outDadosPessoais']['outNomeAfetivo'])) {
                    $n_pessoa = $dados['outDadosPessoais']['outNomeAfetivo'];
                    $n_social = $dados['outDadosPessoais']['outNomeAluno'];
                } elseif (!empty($dados['outDadosPessoais']['outNomeSocial'])) {
                    $n_pessoa = $dados['outDadosPessoais']['outNomeSocial'];
                    $n_social = $dados['outDadosPessoais']['outNomeAluno'];
                } else {
                    $n_pessoa = $dados['outDadosPessoais']['outNomeAluno'];
                    $n_social = @$dados['outDadosPessoais']['outNomeSocial'];
                }
                if (!empty($n_pessoa)) {
                    $pes['n_pessoa'] = str_replace("'", "", $n_pessoa);
                }
                $pes['n_social'] = str_replace("'", "", $n_social);
                $pes['dt_nasc'] = data::converteUS(@$dados['outDadosPessoais']['outDataNascimento']);
                $pes['email'] = str_replace("'", "", @$dados['outDadosPessoais']['outEmail']);
                $pes['ativo'] = 1;
                $pes['sexo'] = (@$dados['outDadosPessoais']['outCodSexo'] == 1 ? 'M' : 'F');
                $pes['ra'] = intval(@$ra);
                $pes['ra_dig'] = trim(strtoupper(@$dados['outDadosPessoais']['outDigitoRA']));
                $pes['ra_uf'] = strtoupper(@$uf);
                $pes['cpf'] = @$dados['outDocumentos']['outCPF'];
                $pes['rg'] = @$dados['outDocumentos']['outNumDoctoCivil'];
                $pes['rg_dig'] = @$dados['outDocumentos']['outDigitoDoctoCivil'];
                $pes['rg_uf'] = @$dados['outDocumentos']['outUFDoctoCivil'];
                $pes['nis'] = @$dados['outDocumentos']['outNumNIS'];
                $pes['pai'] = str_replace("'", "", @$dados['outDadosPessoais']['outNomePai']);
                $pes['mae'] = str_replace("'", "", @$dados['outDadosPessoais']['outNomeMae']);
                $pes['nacionalidade'] = @$dados['outDadosPessoais']['outDescNacionalidade'];
                $pes['uf_nasc'] = @$dados['outDadosPessoais']['outUFMunNascto'];
                $pes['cidade_nasc'] = str_replace("'", "", @$dados['outDadosPessoais']['outNomeMunNascto']);
                $pes['cor_pele'] = @$dados['outDadosPessoais']['outDescCorRaca'];
                $pes['novacert_cartorio'] = @$dados['outCertidaoNova']['outCertMatr01'];
                $pes['novacert_acervo'] = @$dados['outCertidaoNova']['outCertMatr02'];
                $pes['novacert_regcivil'] = @$dados['outCertidaoNova']['outCertMatr03'];
                $pes['novacert_ano'] = @$dados['outCertidaoNova']['outCertMatr04'];
                $pes['novacert_tipolivro'] = @$dados['outCertidaoNova']['outCertMatr05'];
                $pes['novacert_numlivro'] = @$dados['outCertidaoNova']['outCertMatr06'];
                $pes['novacert_folha'] = @$dados['outCertidaoNova']['outCertMatr07'];
                $pes['novacert_termo'] = @$dados['outCertidaoNova']['outCertMatr08'];
                $pes['novacert_controle'] = @$dados['outCertidaoNova']['outCertMatr09'];
                $pes['dt_gdae'] = date("Y-m-d");
                $pes['inep'] = @$dados['outDocumentos']['outCodINEP'];
                $pes['sus'] = @$dados['outDocumentos']['outNumeroCNS'];
                $pes['deficiencia'] = @current($dados['outListaNecessidadesEspeciais'])['outCodNecesEspecial'];
                if (!empty($dados['outCertidaoAntiga'])) {
                    $pes['certidao'] = @$dados['outCertidaoAntiga']['outNumCertidao'] . '-' . @$dados['outCertidaoAntiga']['outNumLivroReg'] . '-' . @$dados['outCertidaoAntiga']['outFolhaRegNum'] . '-' . @$dados['outCertidaoAntiga']['outNomeMunComarca'] . '-' . @$dados['outCertidaoAntiga']['outUFComarca'] . '-' . @$dados['outCertidaoAntiga']['outDistritoNasc'];
                } else {
                    $pes['certidao'] = null;
                }

                if ($id_pessoa) {
                    if (!empty($pes['cpf'])) {
                        $sql = "SELECT n_pessoa, id_pessoa, cpf FROM `pessoa` WHERE `id_pessoa` != " . $id_pessoa . " AND `cpf` LIKE '" . $pes['cpf'] . "' ";

                        $query = pdoSis::getInstance()->query($sql);
                        $temCpf = $query->fetch(PDO::FETCH_ASSOC);
                    }
                    //$sql = "SELECT n_pessoa, id_pessoa, cpf, obs, rg, rg_dig, rg_uf, rg_oe, dt_rg, pai FROM `pessoa` WHERE `id_pessoa` = " . $id_pessoa;

                    $obs = null;
                    if (!empty($temCpf)) {
                        $obs = $temCpf['n_pessoa'] . ', RSE ' . $temCpf['id_pessoa'] . ', já está cadastrado com o CPF ' . $temCpf['cpf'] . "\n\n";
                        $obs .= $temCpf['obs'];
                        $pes['cpf'] = null;
                    }
                    $sql = "UPDATE `pessoa` SET "
                            . " `n_pessoa`='" . $pes['n_pessoa'] . "', "
                            . " `n_social`='" . $pes['n_social'] . "', "
                            . " `dt_nasc`='" . $pes['dt_nasc'] . "', "
                            . " `email`='" . $pes['email'] . "', "
                            . " `ativo`=1, "
                            . " `sexo`='" . $pes['sexo'] . "', ";
                    if ($pessoaSieb['pai'] != 'SPE' && !empty($pes['pai'])) {
                        $sql .= " `pai`='" . $pes['pai'] . "', ";
                    }
                    if (!empty($pes['mae'])) {
                        $sql .= " `mae`='" . $pes['mae'] . "', ";
                    }
                    $sql .= " `ra`='" . $pes['ra'] . "', "
                            . " `ra_dig`='" . trim($pes['ra_dig']) . "', "
                            . " `ra_uf`='" . $pes['ra_uf'] . "', ";

                    if (!empty($pes['rg']) && empty($pessoaSieb['rg'])) {
                        $sql .= " `rg`='" . $pes['rg'] . "', "
                                . " `rg_dig`='" . $pes['rg_dig'] . "', "
                                . " `rg_uf`='" . $pes['rg_uf'] . "', ";
                    }
                    $sql .= " `nis`='" . $pes['nis'] . "', "
                            . " `certidao`='" . $pes['certidao'] . "', "
                            . " `nacionalidade`='" . $pes['nacionalidade'] . "', "
                            . " `uf_nasc`='" . $pes['uf_nasc'] . "', "
                            . " `cidade_nasc`='" . $pes['cidade_nasc'] . "', "
                            . " `cor_pele`='" . $pes['cor_pele'] . "', ";
                    $sql .= " `novacert_cartorio`='" . $pes['novacert_cartorio'] . "', "
                            . " `novacert_acervo`='" . $pes['novacert_acervo'] . "', "
                            . " `novacert_regcivil`='" . $pes['novacert_regcivil'] . "', "
                            . " `novacert_ano`='" . $pes['novacert_ano'] . "', "
                            . " `novacert_tipolivro`='" . $pes['novacert_tipolivro'] . "', "
                            . " `novacert_numlivro`='" . $pes['novacert_numlivro'] . "', "
                            . " `novacert_folha`='" . $pes['novacert_folha'] . "', "
                            . " `novacert_termo`='" . $pes['novacert_termo'] . "', "
                            . " `novacert_controle`='" . $pes['novacert_controle'] . "', ";
                    $sql .= " `deficiencia`='" . $pes['deficiencia'] . "', "
                            . " `dt_gdae`='" . $pes['dt_gdae'] . "', ";
                    if (!empty($pes['cpf']) && empty($temCpf['cpf'])) {
                        $sql .= " `cpf`='" . $pes['cpf'] . "', ";
                    }
                    if (!empty($pes['sus'])) {
                        $sql .= " `sus`='" . $pes['sus'] . "', ";
                    }
                    if (!empty($obs)) {
                        $sql .= " `obs`='" . $obs . "', ";
                    }

                    $sql .= " `inep`='" . $pes['inep'] . "' "
                            . " WHERE id_pessoa = $id_pessoa ";
                } else {
                    $sql = " INSERT INTO `pessoa`( "
                            . " `n_pessoa`, `n_social`, `dt_nasc`, `email`, `ativo`, `sexo`, `ra`, `ra_dig`, `cpf`, `rg`, `rg_dig`, `rg_uf`, `nis`, `ra_uf`, certidao, `pai`, `mae`,"
                            . " `nacionalidade`, `uf_nasc`, `cidade_nasc`, `cor_pele`, `novacert_cartorio`, `novacert_acervo`, `novacert_regcivil`, `novacert_ano`, `novacert_tipolivro`, `novacert_numlivro`, `novacert_folha`, `novacert_termo`, `novacert_controle`, `dt_gdae`, `inep`, `sus`, `deficiencia`) "
                            . " VALUES ("
                            . "'" . $pes['n_pessoa'] . "', "
                            . "'" . $pes['n_social'] . "', "
                            . "'" . $pes['dt_nasc'] . "', "
                            . "'" . $pes['email'] . "', "
                            . "'" . $pes['ativo'] . "', "
                            . "'" . $pes['sexo'] . "', "
                            . "'" . $pes['ra'] . "', "
                            . "'" . trim($pes['ra_dig']) . "', "
                            . (!empty($pes['cpf']) ? "'" . $pes['cpf'] . "', " : " NULL, ")
                            . "'" . $pes['rg'] . "', "
                            . "'" . $pes['rg_dig'] . "', "
                            . "'" . $pes['rg_uf'] . "', "
                            . "'" . $pes['nis'] . "', "
                            . "'" . $pes['ra_uf'] . "', "
                            . "'" . $pes['certidao'] . "', "
                            . "'" . $pes['pai'] . "', "
                            . "'" . $pes['mae'] . "', "
                            . "'" . $pes['nacionalidade'] . "', "
                            . "'" . $pes['uf_nasc'] . "', "
                            . "'" . $pes['cidade_nasc'] . "', "
                            . "'" . $pes['cor_pele'] . "', "
                            . "'" . $pes['novacert_cartorio'] . "', "
                            . "'" . $pes['novacert_acervo'] . "', "
                            . "'" . $pes['novacert_regcivil'] . "', "
                            . "'" . $pes['novacert_ano'] . "', "
                            . "'" . $pes['novacert_tipolivro'] . "', "
                            . "'" . $pes['novacert_numlivro'] . "', "
                            . "'" . $pes['novacert_folha'] . "', "
                            . "'" . $pes['novacert_termo'] . "', "
                            . "'" . $pes['novacert_controle'] . "', "
                            . "'" . $pes['dt_gdae'] . "', "
                            . "'" . $pes['inep'] . "', "
                            . "'" . $pes['sus'] . "', "
                            . "'" . $pes['deficiencia'] . "'"
                            . " )";
                }
                try {
                    $query = pdoSis::getInstance()->query($sql);
                } catch (Exception $exc) {
                    ?>
                    <div class="alert alert-danger">
                        <p>ocorreu um erro. Para resolver, encaminhe o código abaixo ao DTTIE</p>
                        <?= $sql ?>
                    </div>
                    <?php
                }

                if ($id_pessoa) {
                    $pessoa = sql::get('pessoa', '*', ['id_pessoa' => $id_pessoa], 'fetch');
                } else {
                    $pessoa = sql::get('pessoa', '*', ['ra' => $pes['ra'], 'ra_uf' => $pes['ra_uf']], 'fetch');
                }
                if (!empty($pessoa)) {
                    $end = sql::get('endereco', ' fk_id_pessoa, logradouro, num ', ['fk_id_pessoa' => $pessoa['id_pessoa']], 'fetch');
                    if ($end) {
                        if (empty($end['logradouro'])) {
                            $logradouro = str_replace("'", "", @$dados['outEnderecoResidencial']['outLogradouro']);
                        } else {
                            $logradouro = $end['logradouro'];
                        }
                        if (empty($end['num'])) {
                            $num = str_replace("'", "", @$dados['outEnderecoResidencial']['outNumero']);
                        } else {
                            $num = $end['num'];
                        }
                        $sql = "UPDATE `endereco` SET "
                                . " `cep` = '" . @$dados['outEnderecoResidencial']['outCep'] . "', "
                                . " `logradouro` = '" . $logradouro . "', "
                                . " `logradouro_gdae` = '" . str_replace("'", "", @$dados['outEnderecoResidencial']['outLogradouro']) . "', "
                                . " `num` = '" . $num . "', "
                                . " `num_gdae` = '" . str_replace("'", "", @$dados['outEnderecoResidencial']['outNumero']) . "', "
                                . " `complemento` = '" . str_replace("'", "", @$dados['outEnderecoResidencial']['outComplemento']) . "', "
                                . " `bairro` = '" . str_replace("'", "", @$dados['outEnderecoResidencial']['outBairro']) . "', "
                                . " `cidade` = '" . str_replace("'", "", @$dados['outEnderecoResidencial']['outNomeCidade']) . "', "
                                . " `uf` = '" . str_replace("'", "", @$dados['outEnderecoResidencial']['outUFCidade']) . "', "
                                . " `dt_barueri` = '" . date("Y-m-d") . "', "
                                . " `latitude` = '" . str_replace("'", "", @$dados['outEnderecoResidencial']['outLatitude']) . "', "
                                . " `longitude` = '" . str_replace("'", "", @$dados['outEnderecoResidencial']['outLongitude']) . "' "
                                . " WHERE fk_id_pessoa = " . $pessoa['id_pessoa'];
                    } else {
                        $sql = "INSERT INTO `endereco` (`fk_id_pessoa`, `cep`, `logradouro`, `logradouro_gdae`, `num`, `num_gdae`, `complemento`, `bairro`, `cidade`, `uf`, `dt_barueri`, `latitude`, `longitude`) VALUES "
                                . " ("
                                . " '" . $pessoa['id_pessoa'] . "', "
                                . " '" . @$dados['outEnderecoResidencial']['outCep'] . "', "
                                . " '" . str_replace("'", "", @$dados['outEnderecoResidencial']['outLogradouro']) . "', "
                                . " '" . str_replace("'", "", @$dados['outEnderecoResidencial']['outLogradouro']) . "', "
                                . " '" . str_replace("'", "", @$dados['outEnderecoResidencial']['outNumero']) . "', "
                                . " '" . str_replace("'", "", @$dados['outEnderecoResidencial']['outNumero']) . "', "
                                . " '" . str_replace("'", "", @$dados['outEnderecoResidencial']['outComplemento']) . "', "
                                . " '" . str_replace("'", "", @$dados['outEnderecoResidencial']['outBairro']) . "', "
                                . " '" . str_replace("'", "", @$dados['outEnderecoResidencial']['outNomeCidade']) . "', "
                                . " '" . str_replace("'", "", @$dados['outEnderecoResidencial']['outUFCidade']) . "', "
                                . " '" . date("Y-m-d") . "', "
                                . " '" . str_replace("'", "", @$dados['outEnderecoResidencial']['outLatitude']) . "', "
                                . " '" . str_replace("'", "", @$dados['outEnderecoResidencial']['outLongitude']) . "' "
                                . ")";
                    }
                    try {
                        pdoSis::getInstance()->query($sql);
                    } catch (Exception $exc) {
                        $sql = "INSERT INTO `sed_erro` (erro) VALUES ('id_pessoa " . $pessoa['id_pessoa'] . " não foi cadastrado na tabela endereco.');";
                        $query = pdoSis::getInstance()->query($sql);
                    }
                    if (!empty($dados['outTelefones'])) {
                        foreach ($dados['outTelefones'] as $t) {
                            $tel = sql::get('telefones', ' num ', ['fk_id_pessoa' => $pessoa['id_pessoa'], 'num' => $t['outNumero']], 'fetch');
                            if (empty($tel)) {
                                $sql = "INSERT INTO `telefones` (`fk_id_pessoa`, `ddd`, `num`, `fk_id_tt`, `complemento`) VALUES ( "
                                        . " '" . $pessoa['id_pessoa'] . "', "
                                        . " '" . $t['outDDDNumero'] . "', "
                                        . " '" . $t['outNumero'] . "', "
                                        . " '" . $t['outTipoTelefone'] . "', "
                                        . " '" . $t['outComplemento'] . "' "
                                        . ")";
                                try {
                                    pdoSis::getInstance()->query($sql);
                                } catch (Exception $exc) {
                                    $sql = "INSERT INTO `sed_erro` (erro) VALUES ('id_pessoa " . $pessoa['id_pessoa'] . " não foi cadastrado na tabela telefones.');";
                                    $query = pdoSis::getInstance()->query($sql);
                                }
                            }
                        }
                    }
                    if (!empty($dados['outListaNecessidadesEspeciais'])) {
                        foreach ($dados['outListaNecessidadesEspeciais'] as $v) {
                            $sql = "SELECT id_def FROM ge_aluno_necessidades_especiais "
                                    . " WHERE `fk_id_pessoa` = " . $pessoa['id_pessoa']
                                    . " AND `fk_id_ne` = " . $v['outCodNecesEspecial'];
                            $query = pdoSis::getInstance()->query($sql);
                            $test = $query->fetch(PDO::FETCH_ASSOC);
                            if (empty($test['id_def'])) {
                                $sql = "INSERT INTO `ge_aluno_necessidades_especiais` (`id_def`, `fk_id_pessoa`, `ra`, `ra_uf`, `fk_id_ne`, `fk_id_porte`) VALUES ("
                                        . " NULL, " . $pessoa['id_pessoa'] . ", '" . $pessoa['ra'] . "', '" . $pessoa['ra_uf'] . "', '" . $v['outCodNecesEspecial'] . "', '2');";
                                $query = pdoSis::getInstance()->query($sql);
                            }
                        }
                    }
                    return $pessoa;
                }
            }
        } else {
            $sql = "INSERT INTO `sed_erro` (erro) VALUES ('RA " . $ra . " não foi recebido os dados da Prodesp.');";
            $query = pdoSis::getInstance()->query($sql);
        }
    }

    public static function matricula($pessoa, $turma, $formacaoClasseAluno, $sitSieb) {
        if (isset($sitSieb[$formacaoClasseAluno['outCodSitMatricula']]['id_tas'])) {
            $fk_id_tas = $sitSieb[$formacaoClasseAluno['outCodSitMatricula']]['id_tas'];
        } else {
            $fk_id_tas = null;
        }
        if (isset($sitSieb[$formacaoClasseAluno['outCodSitMatricula']]['n_tas'])) {
            $situacao = $sitSieb[$formacaoClasseAluno['outCodSitMatricula']]['n_tas'];
        } else {
            $situacao = null;
        }

        if ($fk_id_tas == 0) {
            $sql = "SELECT ta.id_turma_aluno FROM ge_turma_aluno ta "
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma AND t.fk_id_pl = " . $turma['fk_id_pl']
                    . " JOIN ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                    . " JOIN ge_cursos c on c.id_curso = ci.fk_id_curso AND c.extra = 0 "
                    . " WHERE fk_id_pessoa = " . $pessoa['id_pessoa'] . " "
                    . " AND ta.fk_id_tas = 0 ";
            $query = pdoSis::getInstance()->query($sql);
            $turmaAntiga = $query->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($turmaAntiga)) {
                foreach ($turmaAntiga as $v) {
                    $sql = "UPDATE ge_turma_aluno SET "
                            . " situacao = 'Transferido Escola', "
                            . " fk_id_tas = 1, "
                            . " fk_id_sit_sed = 1 "
                            . " WHERE id_turma_aluno = " . $v['id_turma_aluno'];
                    $query = pdoSis::getInstance()->query($sql);
                    $sql = "INSERT INTO `sed_erro` (erro) VALUES ('Aluno id_turma_aluno: " . @$v['id_turma_aluno'] . " foi setado transferido em id_turma_aluno: " . @$v['id_turma_aluno'] . ".');";
                    $query = pdoSis::getInstance()->query($sql);
                }
            }
        }

        $sql = "REPLACE INTO `ge_turma_aluno`("
                . " codigo_classe, fk_id_turma, periodo_letivo, fk_id_pessoa, fk_id_inst, chamada, situacao, dt_matricula, dt_gdae, fk_id_sit_sed, fk_id_tas"
                . " ) "
                . " VALUES ( "
                . "'" . $turma['codigo'] . "', " //codigo_classe
                . "'" . $turma['id_turma'] . "', " //fk_id_turma
                . "'" . $turma['periodo_letivo'] . "', " //periodo_letivo
                . "'" . $pessoa['id_pessoa'] . "', " //fk_id_pessoa
                . "'" . $turma['fk_id_inst'] . "', " //fk_id_inst
                . "'" . $formacaoClasseAluno['outNumAluno'] . "', " //chamada
                . "'" . $situacao . "', " //situacao
                . "'" . date("Y-m-d") . "', " //dt_matricula
                . "'" . date("Y-m-d") . "', " //dt_gdae
                . "'" . $formacaoClasseAluno['outCodSitMatricula'] . "', " //fk_id_sit_sed
                . "'" . $fk_id_tas . "' " //fk_id_tas
                . ")";
        try {
            pdoSis::getInstance()->query($sql);
        } catch (Exception $exc) {
            $sql = "INSERT INTO `sed_erro` (erro) VALUES ('id_pessoa " . $pessoa['id_pessoa'] . " não foi cadastrado na tabela ge_turma_aluno - id_pessoa " . @$pessoa['id_pessoa'] . " id_turma " . @$pessoa['id_turma'] . " -- codigo: " . @$pessoa['codigo'] . "');";
            $query = pdoSis::getInstance()->query($sql);
        }
    }

}
