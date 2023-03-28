<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of hitórico
 *
 * @author marco
 */
class historico {

    Public $_dadosAntigos;
    public $_situacao;

    public function __construct($id_aluno, $backup = NULL) {
        //$id_aluno=250142;
        $bc = [9, 10, 11, 6, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25];

        $escolas = escolas::idInst(NULL, 'fk_id_tp_ens');

        $d = disciplina::disc();
        foreach ($d as $dd) {
            $disciplinas[$dd['id_disc']] = $dd['n_disc'];
        }
   
        if (empty($backup)) {
            $dd = sql::get('hist_', '*', ['id_pessoa' => $id_aluno], 'fetch');
        } else {
            $dd = sql::get('hist_bk', '*', ['id_hist' => $backup, '<' => 'id_hist'], 'fetch');
        }
        if (!empty($dd)) {
            @$dados = unserialize($dd['dados']);
            $this->_situacao = @$dd['fechado'];
        } else {
            $this->_situacao = 0;
            $sql = "select * from AlunoHistorico "
                    . "where IdAluno = $id_aluno "
                    . "and SituacaoFinalAlternativa not like 'Retido' "
                    . "and (NotaOficial != 0 or NotaAlternativa != '') "
                    . "order by fk_id_ciclo ";
            $query = pdoConsulta::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($array as $v) {

                //setar disciplinas
                if ($v['fk_id_disc'] == 0) {
                    $dados['disciplinas'][$v['base']][$v['DisciplinaAlternativa']] = $v['DisciplinaAlternativa'];
                } else {
                    $dados['disciplinas'][$v['base']][$disciplinas[$v['fk_id_disc']]] = $v['fk_id_disc'];
                }


                //setar escolas

                if (@$v['fk_id_inst'] != 0) {
                    $dados['fk_id_inst'][$v['fk_id_ciclo']] = $v['fk_id_inst'];
                    $dados['escola'][$v['fk_id_ciclo']] = @$escolas[$v['fk_id_inst']];
                    $dados['uf'][$v['fk_id_ciclo']] = CLI_UF;
                    $dados['cidade'][$v['fk_id_ciclo']] = ucfirst(CLI_CIDADE);
                } else {
                    $dados['fk_id_inst'][$v['fk_id_ciclo']] = $v['EscolaAlternativa'];
                    $dados['escola'][$v['fk_id_ciclo']] = $v['EscolaAlternativa'];
                    $dados['uf'][$v['fk_id_ciclo']] = $v['UfEscolaAlternativa'];
                    $dados['cidade'][$v['fk_id_ciclo']] = $v['MunicipioEscolaAlternativa'];
                }






                //última escola da rede que passou e escolas da rede
                if (is_numeric($v['fk_id_inst']) && $v['fk_id_inst'] != 0) {
                    $dados['daRede'][$v['fk_id_inst']] = $v['fk_id_inst'];
                    $dados['ultima'] = $v['fk_id_inst'];
                }

                //Notas
                if (!empty($v['NotaOficial'])) {
                    $nota = $v['NotaOficial'];
                } else {
                    $nota = @$v['NotaAlternativa'];
                }


                if (in_array($v['fk_id_disc'], $bc)) {
                    $dados['discNota'][$v['fk_id_ciclo']][$v['fk_id_disc']] = $nota;
                } else {
                    $dados['discNota'][$v['fk_id_ciclo']][$v['DisciplinaAlternativa']] = $nota;
                }

                $dados['serieAno'][$v['fk_id_ciclo']] = $v['AnoLetivo'];

                //Regime
                if (!empty($v['RegimeAlternativo'])) {
                    $regime = $v['RegimeAlternativo'];
                } elseif ($v['AnoLetivo'] >= 2015 && (!empty($v['IdEscola']) || $v['MunicipioEscolaAlternativa'] == CLI_CIDADE)) {
                    @$regime = 'EF9';
                } elseif ($v['AnoLetivo'] <= 2005) {
                    @$regime = 'EF8';
                }
                $dados['regime'][$v['fk_id_ciclo']] = @$regime;

                @$dadosSerie = serialize($dados);
                
                $sql = "REPLACE INTO `hist_` (`id_pessoa`, `dados`, `dt_hist`, `fk_id_pessoa`, `token`) VALUES ("
                        . "'" . $_POST['id_pessoa'] . "', "
                        . "'" . str_replace("'", '', $dadosSerie) . "', "
                        . "'" . date("Y-m-d") . "', "
                        . "'" . tool::id_pessoa() . "', "
                        . "'" . substr(md5(uniqid("")), 0, 4) . "'"
                        . ");";
                $query = pdoSis::getInstance()->query($sql);

                if ($query) {
                    $sql = "delete from AlunoHistorico "
                            . "where IdAluno = " . $_POST['id_pessoa'] . " ";
                    //$query = pdoConsulta::getInstance()->query($sql);
                }
            }
            unset($array);
        }

        $pl = sql::idNome('ge_periodo_letivo', ['at_pl' => '0']);
        $sql = "select * from aval_final "
                . "where fk_id_pessoa = $id_aluno ";
        $query = pdoHab::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $sql = "SELECT `deficiencia` FROM `pessoa` WHERE `id_pessoa` = $id_aluno ";
        $query = pdoSis::getInstance()->query($sql);
        $apd_ = $query->fetch(PDO::FETCH_ASSOC)['deficiencia'];
        foreach ($array as $k => $v) {
                $reprovado = 0;
                foreach ($v as $ky => $y) {
                    if (!empty($y) && substr($ky, 0, 6) == 'media_' && $y < 5 && empty($array[$k]['cons_' . substr($ky, 6)])) {
                         $reprovado = 1;
                    }
                }

            if (@$reprovado == 0) {
                foreach ($v as $kk => $vv) {
                    if (!empty($_POST['editUltAnos'])) {
                        if (substr($kk, 0, 6) == 'media_' && !empty($vv)) {
                            if (@$apd_ == 'Sim') {
                                $dados['discNota'][$v['fk_id_ciclo']][substr($kk, 6)] = 'APD';
                            } else {
                                if (!empty($v['cons_' . substr($kk, 6)])) {
                                    $dados['discNota'][$v['fk_id_ciclo']][substr($kk, 6)] = round($v['cons_' . substr($kk, 6)] / 0.5, 0) * 0.5;
                                } else {
                                    $dados['discNota'][$v['fk_id_ciclo']][substr($kk, 6)] = round($vv / 0.5, 0) * 0.5;
                                }
                            }
                        }
                    }
                }
            }
        }
        
        @$dados['id_inst'] = $dd['fk_id_inst'];
        /**
          ?>
          <pre>
          <?php
          print_r($dados)
          ?>
          </pre>
          <?php
          /**
          ?>
          <pre>
          <?php
          print_r($dados)
          ?>
          </pre>
          <?php
          echo '---------------------------------------------------------------';
          $sql = "select * from aval_final "
          . "where fk_id_pessoa = " . $id_aluno;
          $query = pdoHab::getInstance()->query($sql);
          $array = $query->fetchAll(PDO::FETCH_ASSOC);
          ?>
          <pre>
          <?php
          print_r($array)
          ?>
          </pre>
          <?php
         * 
         */
        $this->_dadosAntigos = @$dados;
    }

}
