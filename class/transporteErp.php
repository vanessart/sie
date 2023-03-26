<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of transporte
 *
 * @author mc
 */
class transporteErp {

    /**
     * Busca as linhas de ônibus
     * @param type $id_inst
     * @return type
     */
    public static function search($id_inst = NULL, $acessibilidade = NULL) {
        if ($acessibilidade) {
            $acessibilidade = " and acessibilidade = 1 ";
        }
        if (empty($id_inst)) {
            $sql = "SELECT "
                    . " l.id_li, l.n_li, at_nome, l.motorista, l.tel1, l.tel2, v.n_tv, v.placa, l.periodo, l.abrangencia, l.viagem, v.n_tv  "
                    . " FROM transporte_linha l "
                    . " JOIN transporte_veiculo v ON v.id_tv = l.fk_id_tv "
                    . " where 1 "
                    . $acessibilidade
                    . ' order by n_li ';
        } else {
            $sql = "SELECT "
                    . " l.id_li, l.n_li, at_nome, l.motorista, l.tel1, l.tel2, v.n_tv, v.placa, l.periodo, l.abrangencia, l.viagem, v.n_tv "
                    . " FROM transporte_linha l "
                    . " JOIN transporte_veiculo v ON v.id_tv = l.fk_id_tv "
                    . " JOIN transporte_inst_linha il ON il.fk_id_li = l.id_li "
                    . " where 1 "
                    . $acessibilidade
                    . " and il.fk_id_inst = $id_inst "
                    . ' order by l.viagem, n_li ';
        }

        $query = pdoSis::getInstance()->query($sql);

        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    /**
     * verifica em que linha o aluno está
     * @param type $id_pessoa
     * @return type
     */
    public static function alunoLinha($id_pessoa) {
        $sql = "SELECT "
                . " l.id_li, l.n_li, l.tel1, l.tel2, v.n_tv,"
                . " v.placa, a.id_alu, motorista, monitor, l.periodo, s.n_sa, l.abrangencia  "
                . " FROM transporte_aluno a "
                . " left JOIN transporte_linha l ON a.fk_id_li = l.id_li "
                . " left JOIN transporte_veiculo v ON v.id_tv = l.fk_id_tv "
                . " left join transporte_status_aluno s ON s.id_sa = a.fk_id_sa "
                . " WHERE a.fk_id_pessoa = " . $id_pessoa;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    /**
     * Busca uma linha expecífica
     * @param type $id_li
     * @return type
     */
    public static function linhaGet($id_li) {
        $sql = "SELECT * FROM `transporte_linha` l "
                . "left join transporte_veiculo v ON v.id_tv = l.fk_id_tv "
                . " where id_li = $id_li ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    /**
     * Cria um nome de linha dinanmico
     * @param type $id_inst
     * @return type
     */
    public static function nomeLinha($id_inst = null, $acessibilidade = NULL) {

        $n = self::search($id_inst, $acessibilidade);
        foreach ($n as $v) {
            if ($v['at_nome'] == 1) {
                $linhaEsc[$v['id_li']] = $v['n_li'];
            } else {
                $linhaEsc[$v['id_li']] = $v['n_tv'] . '-V' . $v['viagem'] . '-' . (explode(' ', $v['motorista'])[0]) . '-' . $v['periodo'];
            }
        }

        @$linha['Escola'] = $linhaEsc;
        $n = self::search(13);
        foreach ($n as $v) {
            if ($v['at_nome'] == 1) {
                $linhaSE[$v['id_li']] = $v['n_li'];
            } else {
                $linhaSE[$v['id_li']] = $v['n_tv'] . '-V' . $v['viagem'] . '-' . (explode(' ', $v['motorista'])[0]) . '-' . $v['periodo'];
            }
        }

        @$linha['S.E'] = $linhaSE;

        return @$linha;
    }

    /**
     * Busca alunos que estão cadastrados em alguma linha
     * @param type $id_inst
     * @param string "A" Aguardando deferimento, "SE" solicitando encerramento
     * "T" todos menos encerrado e indeferido, "E" encerrado e indeferido,
     * "D" deferidos
     * @return type
     */
    public static function alunos($id_inst = NULL, $situacao = NULL, $dt_inicio = null, $dt_fim = null, $dt_solicita = null) {
        $sqlAux = "";
        if ($situacao == 'A') {
            $sqlAux .= " AND (a.fk_id_sa = 0 OR a.fk_id_sa IS NULL) AND a.dt_solicita IS NOT NULL ";
        } elseif ($situacao == 'SE') {
            $sqlAux .= " AND a.fk_id_mot IS NOT NULL AND IFNULL(a.fk_id_sa, -1) != 6 ";
        } elseif ($situacao == 'T') {
            $sqlAux .= " AND IFNULL(a.fk_id_sa, -1) NOT IN (2,6) ";
        } elseif ($situacao == 'E') {
            $sqlAux .= " AND a.fk_id_sa in (2,6) ";
        } elseif ($situacao == 'D') {
            $sqlAux .= " AND a.fk_id_sa = 1 ";
        } elseif ($situacao == 'ND') {
            $sqlAux .= " AND a.fk_id_sa = 0 && LENGTH(a.justificativa) > 0 ";
        }
        if (!empty($id_inst)) {
            $sqlAux .= " AND a.fk_id_inst = $id_inst ";
        }
        if (!empty($dt_inicio)) {
            $sqlAux .= " AND a.dt_inicio >= '$dt_inicio' ";
        }
        if (!empty($dt_fim)) {
            $sqlAux .= " AND a.dt_fim >= '$dt_fim' ";
        }
        if (!empty($dt_solicita)) {
            $sqlAux .= " AND a.dt_solicita >= '$dt_solicita' ";
        }

        $sql = "SELECT "
                . " p.id_pessoa, p.n_pessoa, p.ra, a.id_alu, ta.chamada, "
                . " t.codigo, t.n_turma, a.fk_id_sa, a.distancia_esc, t.fk_id_ciclo, a.dt_fim, "
                . " i.id_inst, i.n_inst, li.id_li, li.n_li, t.periodo, sa.n_sa, sa.id_sa, tv.capacidade, a.justificativa, "
                . " li.viagem, tv.n_tv, li.motorista, li.periodo, m.n_mot, li.periodo, a.dt_solicita, a.dt_inicio, a.transp_secundario, li.fk_id_tv_s "
                . " FROM `ge_turma_aluno` ta "
                . " JOIN ge_turma_aluno_situacao tas ON tas.id_tas = ta.fk_id_tas AND tas.id_tas = 0 "
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl "
                . " JOIN pessoa p ON p.id_pessoa = ta.fk_id_pessoa "
                . " JOIN transporte_aluno a ON a.fk_id_pessoa = p.id_pessoa "
                . " JOIN instancia i ON i.id_inst = a.fk_id_inst "
                . " JOIN transporte_linha li ON li.id_li = a.fk_id_li "
                . " JOIN transporte_veiculo tv ON tv.id_tv = li.fk_id_tv "
                . " LEFT JOIN transporte_status_aluno sa ON sa.id_sa = IFNULL(a.fk_id_sa, 0) "
                . " LEFT JOIN transporte_motivo m ON m.id_mot = a.fk_id_mot "
                . " JOIN ge_ciclos ci ON ci.id_ciclo = t.fk_id_ciclo "
                . " WHERE ci.fk_id_curso in (1,3) "
                . $sqlAux
                . " and pl.at_pl = 1 "
                . " ORDER BY n_inst, n_turma, sa.n_sa, `chamada` ASC";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    /**
     * alunos de uma linha
     * @param type $id_li
     * @return type
     */
    public static function LinhaAlunos($id_li, $id_inst = NULL, $statusNaoMostrar = '2,6', $statusMostrar = null, $dt_inicio = null) {
        $sqlAux = "";
        if (!empty($id_inst)) {
            $sqlAux .= " AND ta.fk_id_inst = $id_inst ";
        }

        if (!empty($statusMostrar)) {
            $sqlAux .= " AND a.fk_id_sa = $statusMostrar ";
        }

        if (!empty($dt_inicio)) {
            $sqlAux .= " AND a.dt_inicio <= '$dt_inicio' ";
        }

        $sql = "SELECT "
                . " p.id_pessoa, p.n_pessoa, p.ra, ta.chamada, t.codigo, t.n_turma, "
                . " t.fk_id_ciclo, a.*, IFNULL(a.fk_id_sa, 0) fk_id_sa "
                . " FROM `ge_turma_aluno` ta "
                . " JOIN ge_turma_aluno_situacao tas ON tas.id_tas = ta.fk_id_tas AND tas.id_tas = 0 "
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl "
                . " JOIN pessoa p ON p.id_pessoa = ta.fk_id_pessoa "
                . " JOIN transporte_aluno a ON a.fk_id_pessoa = p.id_pessoa "
                . " JOIN ge_ciclos ci ON ci.id_ciclo = t.fk_id_ciclo "
                . " LEFT JOIN transporte_status_aluno s ON s.id_sa = a.fk_id_sa "
                . " WHERE ci.fk_id_curso in (1,3) "
                . " AND a.fk_id_li = $id_li "
                . " AND IFNULL(a.fk_id_sa, 0) NOT IN ($statusNaoMostrar)"
                . " AND pl.at_pl = 1 "
                . $sqlAux
                . " ORDER BY s.n_sa, p.n_pessoa ASC";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function LinhaAlunosAdaptado($id_li, $id_inst = NULL, $statusNaoMostrar = '2,6') {
        if (!empty($id_inst)) {
            $id_inst_ = " and ta.fk_id_inst = $id_inst ";
        } else {
            $id_inst_ = NULL;
        }
        $sql = "SELECT "
                . " p.id_pessoa, p.n_pessoa, p.ra, ta.chamada, t.codigo, t.n_turma, "
                . " t.fk_id_ciclo, p.ra, p.ra_dig, p.dt_nasc, a.*, att.* "
                . " FROM `ge_turma_aluno` ta "
                . " JOIN ge_turma_aluno_situacao tas ON tas.id_tas = ta.fk_id_tas AND tas.id_tas = 0 "
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl "
                . " JOIN pessoa p ON p.id_pessoa = ta.fk_id_pessoa "
                . " JOIN transporte_alu_adaptado a ON a.id_pessoa = p.id_pessoa "
                . " JOIN transporte_alu_adaptado_time att ON att.fk_id_pessoa = p.id_pessoa "
                . " JOIN ge_ciclos ci ON ci.id_ciclo = t.fk_id_ciclo "
                . " WHERE ci.fk_id_curso in (1,3) "
                . " AND att.fk_id_li = $id_li "
                . " AND IFNULL(att.fk_id_sa, -1) not in ($statusNaoMostrar)"
                . " AND pl.at_pl = 1 "
                . $id_inst_
                . " ORDER BY p.n_pessoa ASC";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    /**
     * veículo, capacidade e empresa
     * @return type
     */
    public static function veiculoEmpresa() {
        $sql = "select l.id_tv, l.n_tv,  e.n_em, l.capacidade from transporte_veiculo l "
                . "join transporte_empresa e ON e.id_em = l.fk_id_em "
                . " order by l.n_tv ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            if (@$v['at_nome'] == 1) {
                $vei[$v['id_tv']] = $v['n_li'];
            } else {
                $vei[$v['id_tv']] = $v['n_tv'] . ' (' . $v['capacidade'] . ') - ' . $v['n_em'];
            }
        }

        return @$vei;
    }

    /**
     * escolas que uma linha atende
     * @param type $id_li
     * @return type
     */
    public static function linhaEscolas($id_li) {
        $sql = "SELECT "
                . " i.n_inst, i.id_inst "
                . " FROM `transporte_inst_linha` il "
                . " join instancia i ON id_inst = il.fk_id_inst "
                . " join transporte_linha l ON il.fk_id_li = l.id_li "
                . " where il.fk_id_li = $id_li";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        $esc = tool::idName($array);

        return $esc;
    }

    /**
     * pegando os alunos a partir da tabela transporte_aluno
     * @param type $id_alu
     * @return type
     */
    public static function aluAluno($id_alu) {
        $sql = "select a.*, p.n_pessoa, p.sexo from transporte_aluno a "
                . "join pessoa p ON p.id_pessoa = a.fk_id_pessoa "
                . " where a.id_alu = $id_alu";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array;
    }

    /**
     * total de alunos utillizando o veículo
     * @param type $id_li
     * @return type
     */
    public static function ocupacao($id_li, $fk_id_sa = null) {
        $sqlAux = "";
        if (!empty($fk_id_sa)) {
            $sqlAux .= " AND fk_id_sa = $fk_id_sa";
        }
        $sql = "SELECT count(id_alu) as ct "
                . " FROM `transporte_aluno` "
                . " WHERE `fk_id_li` = $id_li "
                . " AND IFNULL(`fk_id_sa`, -1) NOT IN (2,6)"
                . $sqlAux;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        return $array['ct'];
    }

    /**
     * pesquisa a tabela pessoa e vê associação ou nao com uma linha
     * @param type $id_inst
     * @param type $nome
     * @return type
     */
    public static function alunoPesq($id_inst = NULL, $nome = NULL, $fk_id_sa = NULL) {
        $sqlAux = "";
        if (!empty($id_inst)) {
            $sqlAux .= " AND t.fk_id_inst = $id_inst ";
        }
        if (!empty($fk_id_sa) || $fk_id_sa === '0') {
            $sqlAux .= "AND IFNULL(s.id_sa, 0) = $fk_id_sa";
        }
        if (!empty($nome)) 
        {
            if (is_numeric($nome)) {
                $sqlAux .= " AND p.ra = '$nome' ";
            } else {
                $sqlAux .= " AND p.n_pessoa like '%$nome%' ";
            }
        }
        $sql = "SELECT "
                . " p.id_pessoa, p.n_pessoa, p.dt_nasc, p.ra, p.ra_dig, p.ra_uf, p.sexo, p.mae, "
                . " a.id_alu, a.fk_id_inst, a.distancia_esc, a.tempo_esc, a.dt_solicita, "
                . " a.dt_solicita_fim, a.dt_inicio, a.dt_fim, "
                . " e.cep, e.logradouro_gdae, e.num_gdae, e.complemento, e.bairro, e.cidade, e.uf, "
                . " e.latitude, e.longitude, "
                . " l.id_li, l.n_li, l.viagem, l.motorista, l.monitor, l.abrangencia, l.saida, l.duracao, l.periodo, "
                . " v.n_tv, v.placa, v.capacidade, v.acessibilidade, "
                . " s.n_sa, m.n_mot, t.n_turma, "
                . " CONCAT(v.n_tv, '-v', l.viagem, '-', l.motorista, '-', l.periodo) AS n_li_v "
                . " FROM pessoa p "
                . " LEFT JOIN transporte_aluno a ON p.id_pessoa = a.fk_id_pessoa "
                . " LEFT JOIN endereco e ON e.fk_id_pessoa = p.id_pessoa "
                . " LEFT JOIN transporte_linha l ON a.fk_id_li = l.id_li "
                . " LEFT JOIN transporte_veiculo v ON v.id_tv = l.fk_id_tv "
                . " LEFT JOIN transporte_status_aluno s ON s.id_sa = a.fk_id_sa "
                . " LEFT JOIN transporte_motivo m ON m.id_mot = a.fk_id_mot "
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa "
                . " JOIN ge_turma_aluno_situacao tas ON tas.id_tas = ta.fk_id_tas AND tas.id_tas = 0 "
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl "
                . " JOIN ge_ciclos ci ON ci.id_ciclo = t.fk_id_ciclo "
                . " WHERE ci.fk_id_curso IN (1,3) "
                . " AND a.fk_id_li IS NOT NULL "
                . " AND pl.at_pl = 1 "
                . $sqlAux
                . @$id_inst_
                . " ORDER BY p.n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    /**
     * se usar mes não pode usar dtInicio e dt Fim
     * @param type $idlinha
     * @param type $mes
     * @param type $dtInicio
     * @param type $dtFim
     */
    public static function alunoFrequencia($mes, $idlinha = NULL, $ano = NULL, $dtInicio = NULL, $dtFim = NULL, $empresa = NULL) {

        if (empty($ano)) {
            $ano = date("Y");
        }

        $sqlAux = "";
        if (!empty($idlinha)) {
            $sqlAux .= " AND al.fk_id_li IN($idlinha) ";
        }

        if (!empty($empresa)) {
            $sqlAux .= " AND tv.fk_id_em = $empresa";
        }
        if (!empty($mes)) {
            $mes = str_pad($mes, 2, "0", STR_PAD_LEFT);
            $dt_falta = "";
            $diasUteis = dataErp::diasUteis($mes, $ano, NULL, 'N');
            $feriados = dataErp::feriadoMes($mes, NULL, $ano, 1);
            $totaldia = count($diasUteis);
            $totalferiado = count($feriados);
            $proximoMesAno = $mes != 12 ? ($ano . '-' . (str_pad(($mes + 1), 2, "0", STR_PAD_LEFT))) : (($ano + 1) . '-01');

            $sqlAux .= " AND al.dt_inicio < '$proximoMesAno-01' "
                    . " AND ("
                        . " al.dt_fim >= '" . $ano . '-' . $mes . "-01' "
                        . " OR al.dt_fim IS NULL "
                    . " ) ";
        } elseif (!empty($dtFim) && !empty($dtInicio)) {
            echo 'Falta implementar';
        }
        /**
         * lista os alunos
         */
        $sql = "SELECT p.id_pessoa, p.n_pessoa, i.n_inst, i.id_inst, p.ra, p.ra_dig,"
                . " t.codigo, p.dt_nasc, t.fk_id_ciclo, al.id_alu,"
                . " al.dt_inicio, al.dt_fim, al.fk_id_sa, tl.n_li, tl.id_li, tl.periodo,"
                . " tl.motorista, tl.monitor, tl.tel1, tv.n_tv, tv.placa, tv.capacidade,"
                . " tv.acessibilidade, ttv.n_tiv, e.n_em FROM transporte_aluno al"
                . " JOIN pessoa p ON p.id_pessoa = al.fk_id_pessoa"
                . " JOIN instancia i ON i.id_inst = al.fk_id_inst"
                . " JOIN ge_turmas t ON t.id_turma = al.fk_id_turma "
                . " JOIN transporte_linha tl ON tl.id_li = al.fk_id_li"
                . " JOIN transporte_veiculo tv ON tv.id_tv = tl.fk_id_tv"
                . " JOIN transporte_empresa e ON e.id_em = tv.fk_id_em"
                . " JOIN transporte_tipo_veiculo ttv ON ttv.id_tiv = tv.fk_id_tiv "
                . " WHERE 1 "
                . $sqlAux
                . " AND al.fk_id_sa in (1,6)"
                . " ORDER BY p.n_pessoa";

        $query = pdoSis::getInstance()->query($sql);
        $alunos = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($alunos)) {
            /**
             * lista as faltas
             */
            $id_alus = array_column($alunos, 'id_alu');

            $sql = " SELECT * FROM `transporte_falta` "
                    . " WHERE fk_id_alu in (" . (implode(",", $id_alus)) . ") "
                    . " AND dt_falta LIKE '$ano-" . $mes . "-%' ";
            $query = pdoSis::getInstance()->query($sql);
            $f = $query->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($f)) {
                foreach ($f as $v) {
                    $faltas[$v['fk_id_alu']][substr($v['dt_falta'], 8)] = substr($v['dt_falta'], 8);
                }
            } else {
                $faltas = [];
            }
            /**
             * lista os dias que houve lançamentos
             */
            $sql = "SELECT "
                    . " j.dt_falta, j.fk_id_li "
                    . " FROM `transporte_falta_justifica` j "
                    . " JOIN transporte_aluno al ON al.fk_id_li = j.fk_id_li "
                    . " where al.id_alu in (" . (implode(",", $id_alus)) . ") "
                    . " AND j.dt_falta LIKE '$ano-" . $mes . "-%' ";
            $query = pdoSis::getInstance()->query($sql);
            $l = $query->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($l)) {
                foreach ($l as $v) {
                    $lancado[$v['fk_id_li']][substr($v['dt_falta'], 8)] = substr($v['dt_falta'], 8);
                }
            } else {
                $lancado = [];
            }

            foreach ($alunos as $v) {
                $AlunosFeriados = [];
                @$alu['aluno'][$v['id_alu']] = $v;
                @$AlunosFeriados = $feriados[self::getTipoEnsino($v['fk_id_ciclo'])];

                $dataInicio = intval(str_replace('-', '', $v['dt_inicio']));
                $dataFim = intval(str_replace('-', '', $v['dt_fim']));
                foreach ($diasUteis as $d) {
                    $dd = str_pad($d, 2, "0", STR_PAD_LEFT);
                    if (!empty($lancado[$v['id_li']][$dd])) {
                        $dataDia = intval($ano . $mes . $dd);
                        if (($dataInicio <= $dataDia) && (($dataDia <= $dataFim) || empty($dataFim))) {
                            if (@array_key_exists($d, $AlunosFeriados)) {
                                @$alu['aluno'][$v['id_alu']]['frequencia'][$dd] = $AlunosFeriados[$d];
                            } elseif (!empty($faltas[$v['id_alu']][$dd])) {
                                @$alu['aluno'][$v['id_alu']]['frequencia'][$dd] = 'F';
                                @$alu['aluno'][$v['id_alu']]['ContaFalta'] ++;
                                @$alu['linha'][$v['id_li']]['ContaFalta'] ++;
                                @$alu['rede']['ContaFalta'] ++;
                            } else {
                                @$alu['aluno'][$v['id_alu']]['frequencia'][$dd] = 'P';
                                @$alu['aluno'][$v['id_alu']]['ContaFrenquencia'] ++;
                                @$alu['linha'][$v['id_li']]['ContaFrenquencia'] ++;
                                @$alu['linha'][$v['id_li']][$dd] ++;
                                @$alu['rede']['ContaFrenquencia'] ++;
                                //Alunos transportados
                                @$alu[$v['id_inst']][$v['n_em']][$v['periodo']] ++;
                                @$alu['Total'][$v['n_em']][$v['periodo']] ++;
                                @$alu['PeriodoT'][$v['id_inst']][$v['n_em']] ++;
                                @$alu['c'][$v['id_inst']][$v['n_em']][$v['id_li']] = $v['capacidade'];
                                @$alu['capaci'][$v['id_li']] = $v['capacidade'];
                                @$alu['Total'][$v['periodo']] ++;
                            }
                        } else {

                            @$alu['aluno'][$v['id_alu']]['frequencia'][$dd] = '*';
                        }
                    } else {
                        if (!empty($AlunosFeriados)) {

                            if (array_key_exists($dd, $AlunosFeriados)) {
                                @$alu['aluno'][$v['id_alu']]['frequencia'][$dd] = $AlunosFeriados[$dd];
                            } else {
                                @$alu['aluno'][$v['id_alu']]['frequencia'][$dd] = 'N';
                            }
                        } else {
                            @$alu['aluno'][$v['id_alu']]['frequencia'][$dd] = 'N';
                        }

                        @$alu['linha'][$v['id_li']][$dd] = 0;
                    }
                }

                @$alu['linha'][$v['id_li']]['totalAlunos'] ++;
                // @$alu['linha'][$v['id_li']]['totalAlunos'] ++;
                @$alu['id_inst'][$v['id_inst']]['totalAlunosPeriodo'][$v['periodo']] ++;
                @$alu['id_inst'][$v['id_inst']]['totalAlunos'] ++;

                @$alu['rede']['totalAlunos'] ++;
                if (!empty($v['dt_inicio'])) {
                    @$alu['inclusao'][$v['id_inst']] ++;
                    @$alu['totali'] ++;
                }
                if (!empty($v['dt_fim'])) {
                    @$alu['exclusao'][$v['id_inst']] ++;
                    @$alu['totale'] ++;
                }
            }

            $esp = self::getListaEspera($ano . '-' . $mes);
            if (!empty($esp)) {
                foreach ($esp as $w) {
                    @$alu['espera'][$w['fk_id_inst']] ++;
                    @$alu['totals'] ++;
                }
            }

            @$alu['totaldia'] = $totaldia;
            @$alu['totalferiado'] = $totalferiado;

            return @$alu;
        }
    }

    public static function relatoriosrede($mes, $idlinha = NULL, $ano = NULL, $dtInicio = NULL, $dtFim = NULL, $empresa = NULL) {

        if (empty($ano)) {
            $ano = date("Y");
        }

        $sqlAux = "";
        if (!empty($idlinha)) {
            $sqlAux .= " AND al.fk_id_li IN($idlinha) ";
        }

        if (!empty($empresa)) {
            $sqlAux .= " AND tv.fk_id_em = $empresa";
        }

        if (!empty($mes)) {
            $dt_falta = "";
            $diasUteis = data::diasUteis($mes, $ano, NULL, 'N');
            $feriados = data::feriadoMes($mes, NULL, $ano, 1);
            $totaldia = count($diasUteis);
            $totalferiado = count($feriados);

            $proximoMesAno = $mes != 12 ? ($ano . '-' . (str_pad(($mes + 1), 2, "0", STR_PAD_LEFT))) : (($ano + 1) . '-01');

            $sqlAux .= " AND al.dt_inicio < '$proximoMesAno-01'"
                    . " AND ("
                        . " al.dt_fim >= '" . $ano . '-' . (str_pad(($mes), 2, "0", STR_PAD_LEFT)) . "-01'"
                        . " OR al.dt_fim IS NULL "
                    . " ) ";
        }
        /**
         * lista os alunos
         */
        $sql = "SELECT p.id_pessoa, p.n_pessoa, i.n_inst, i.id_inst, p.ra, p.ra_dig,"
                . " t.codigo, p.dt_nasc, t.fk_id_ciclo, al.id_alu,"
                . " al.dt_inicio, al.dt_fim, tl.n_li, tl.id_li, tl.periodo,"
                . " tl.motorista, tl.monitor, tl.tel1, tv.n_tv, tv.id_tv, tv.placa, tv.capacidade,"
                . " tv.acessibilidade, ttv.n_tiv, ttv.id_tiv, e.n_em FROM transporte_aluno al"
                . " JOIN pessoa p ON p.id_pessoa = al.fk_id_pessoa"
                . " JOIN instancia i ON i.id_inst = al.fk_id_inst"
                . " JOIN ge_turmas t ON t.id_turma = al.fk_id_turma AND t.fk_id_inst = al.fk_id_inst "
                . " JOIN transporte_linha tl ON tl.id_li = al.fk_id_li"
                . " JOIN transporte_veiculo tv ON tv.id_tv = tl.fk_id_tv"
                . " JOIN transporte_empresa e ON e.id_em = tv.fk_id_em"
                . " JOIN transporte_tipo_veiculo ttv ON ttv.id_tiv = tv.fk_id_tiv"
                . " WHERE 1 "
                . $sqlAux
                . " AND al.fk_id_sa in (1,6)"
                . " ORDER BY p.n_pessoa";

        $query = pdoSis::getInstance()->query($sql);
        $alunos = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($alunos)) {
            /**
             * lista as faltas
             */
            $id_alus = array_column($alunos, 'id_alu');

            $sql = " SELECT * FROM `transporte_falta` "
                    . " WHERE fk_id_alu in (" . (implode(",", $id_alus)) . ") "
                    . " AND dt_falta LIKE '$ano-" . (str_pad(($mes), 2, "0", STR_PAD_LEFT)) . "-%' ";

            $query = pdoSis::getInstance()->query($sql);
            $f = $query->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($f)) {
                foreach ($f as $v) {
                    $faltas[$v['fk_id_alu']][substr($v['dt_falta'], 8)] = substr($v['dt_falta'], 8);
                }
            } else {
                $faltas = [];
            }

            /**
             * lista os dias que houve lançamentos
             */
            $sql = "SELECT "
                    . " j.dt_falta, j.fk_id_li "
                    . " FROM `transporte_falta_justifica` j "
                    . " JOIN transporte_aluno al ON al.fk_id_li = j.fk_id_li "
                    . " WHERE al.id_alu in (" . (implode(",", $id_alus)) . ") "
                    . " AND j.dt_falta LIKE '$ano-" . (str_pad(($mes), 2, "0", STR_PAD_LEFT)) . "-%' ";

            $query = pdoSis::getInstance()->query($sql);
            $l = $query->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($l)) {
                foreach ($l as $v) {
                    $lancado[$v['fk_id_li']][substr($v['dt_falta'], 8)] = substr($v['dt_falta'], 8);
                }
            } else {
                $lancado = [];
            }

            foreach ($alunos as $v) {
                // $alu['linha'][$v['id_li']] = $v;
                @$alu['escola'][$v['id_inst']] = $v;
                @$alu['escola'][$v['periodo']][$v['id_inst']] ++;
                @$alu['escola']['total'][$v['periodo']] ++;

                @$alu['contaveiculo'][$v['id_inst']][$v['id_tv']] = 1;
                @$alu['contaveiculotipo'][$v['id_tiv']][$v['id_tv']] = 1;
                @$alu['contaveiculo']['totalv'][$v['n_tv']] = 1;
                @$alu['nrveiculo'][$v['id_inst']][$v['id_tv']] = $v['n_tv'];

                if ((empty($v['dt_fim'])) || ($v['dt_fim'] > $proximoMesAno)) {
                    @$alu['escola']['Ativos'][$v['id_inst']] ++;
                    @$alu['escola']['total']['Ativos'] ++;
                } else {
                    @$alu['escola']['Inativos'][$v['id_inst']] ++;
                    @$alu['escola']['total']['Inativos'] ++;
                }

                @$AlunosFeriados = $feriados[self::getTipoEnsino($v['fk_id_ciclo'])];

                $AlunosFeriados = empty($AlunosFeriados) ? [] : $AlunosFeriados;
                $dataInicio = intval(str_replace('-', '', $v['dt_inicio']));
                $dataFim = intval(str_replace('-', '', $v['dt_fim']));
                foreach ($diasUteis as $d) {
                    $dd = str_pad($d, 2, "0", STR_PAD_LEFT);
                    if (!empty($lancado[$v['id_li']][$dd])) {
                        $dataDia = intval($ano . (str_pad(($mes), 2, "0", STR_PAD_LEFT)) . $dd);
                        if (($dataInicio <= $dataDia) && (($dataDia <= $dataFim) || empty($dataFim))) {

                            if (array_key_exists($d, $AlunosFeriados)) {
                                @$alu[$v['id_alu']]['frequencia'][$d] = $AlunosFeriados[$d];
                            } elseif (!empty($faltas[$v['id_alu']][$dd])) {
                                @$alu[$v['id_alu']]['frequencia'][$d] = 'F';
                                @$alu[$v['id_alu']]['ContaFalta'] ++;
                                @$alu['linha'][$v['id_li']]['ContaFalta'] ++;
                                @$alu['rede']['ContaFalta'] ++;
                            } else {
                                @$alu[$v['id_alu']]['frequencia'][$d] = 'P';
                                @$alu[$v['id_alu']]['ContaFrenquencia'] ++;
                                @$alu['linha'][$v['id_li']]['ContaFrenquencia'] ++;
                                @$alu['rede']['ContaFrenquencia'] ++;

                                // @$alu['qat']['Total'] ++;
                                // @$alu['qat'][$v['periodo']] ++;
                                //  @$alu['qat'][$v['id_inst']][$v['n_em']][$v['periodo']] ++;
                                //  @$alu['qat'][$v['id_inst']][$v['n_em']]['c'][$v['id_li']] = $v['capacidade'];
                            }
                        } else {
                            @$alu[$v['id_alu']]['frequencia'][$d] = '*';
                        }
                    } else {
                        @$alu[$v['id_alu']]['frequencia'][$d] = 'N';
                    }
                }
            }

            $esp = self::getListaEspera($ano . '-' . $mes);
            if (!empty($esp)) {
                foreach ($esp as $w) {
                    @$alu['espera'][$w['fk_id_inst']] ++;
                    @$alu['totalespera'] ++;
                }
            }

            return $alu;
        }
    }

    public static function nomeempresa() {

        $sql = "SELECT * FROM transporte_empresa";

        $query = pdoSis::getInstance()->query($sql);
        $emp = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($emp as $v) {
            $nome_empr[$v['n_em']] = $v['n_em'];
        }
        return $nome_empr;
    }

    public static function empresas() {

        $sql = "SELECT * FROM transporte_empresa "
                . "order by n_em ";

        $query = pdoSis::getInstance()->query($sql);
        $emp = $query->fetchAll(PDO::FETCH_ASSOC);

        return $emp;
    }

    public static function empresasOption() {

        $emp = sql::idNome('transporte_empresa');

        return $emp;
    }

    public static function getEmpresa($id_em) {

        $sql = "SELECT * FROM transporte_empresa "
                . " where id_em = $id_em";

        $query = pdoSis::getInstance()->query($sql);
        $emp = $query->fetch(PDO::FETCH_ASSOC);

        return $emp;
    }

    public static function arquivoexcel($mes) {

        $ano = date("Y");
        $sqlAux = "";

        if (!empty($mes)) {
            $dt_falta = "";
            $diasUteis = data::diasUteis($mes, $ano, NULL, 'N');
            $feriados = data::feriadoMes($mes, NULL, $ano, 1);
            $totaldia = count($diasUteis);
            $totalferiado = count($feriados);

            $proximoMesAno = $mes != 12 ? ($ano . '-' . (str_pad(($mes + 1), 2, "0", STR_PAD_LEFT))) : (($ano + 1) . '-01');

            $sqlAux .= " AND al.dt_inicio < '$proximoMesAno-01'"
                    . " AND ("
                        . " al.dt_fim >= '" . $ano . '-' . (str_pad(($mes), 2, "0", STR_PAD_LEFT)) . "-01'"
                        . " OR al.dt_fim IS NULL "
                    . " ) ";
        }

        $sql = "SELECT p.id_pessoa AS RSE, p.n_pessoa AS Aluno, i.n_inst AS Escola,"
                . " i.id_inst AS Id_Escola, p.ra AS RA, p.ra_dig AS Dig_RA,"
                . " t.codigo AS CodClasse, p.dt_nasc AS Dt_Nasc, t.fk_id_ciclo as SérieAno, al.id_alu As ID_Aluno,"
                . " al.dt_inicio, al.dt_fim, tl.n_li AS Linha, tl.id_li, tl.periodo as Período,"
                . " tl.motorista, tl.monitor, tl.tel1, tv.n_tv AS NrVeículo, tv.id_tv, tv.placa, tv.capacidade,"
                . " tv.acessibilidade, ttv.n_tiv, ttv.id_tiv, e.n_em,"
                . " ed.logradouro_gdae, ed.num_gdae, ed.complemento, ed.bairro, ed.cidade, ed.cep FROM transporte_aluno al"
                . " JOIN pessoa p ON p.id_pessoa = al.fk_id_pessoa"
                . " JOIN instancia i ON i.id_inst = al.fk_id_inst"
                . " JOIN ge_turmas t ON t.id_turma = al.fk_id_turma AND t.fk_id_inst = al.fk_id_inst "
                . " JOIN transporte_linha tl ON tl.id_li = al.fk_id_li"
                . " JOIN transporte_veiculo tv ON tv.id_tv = tl.fk_id_tv"
                . " JOIN transporte_empresa e ON e.id_em = tv.fk_id_em"
                . " JOIN transporte_tipo_veiculo ttv ON ttv.id_tiv = tv.fk_id_tiv"
                . " LEFT JOIN endereco ed ON ed.fk_id_pessoa = p.id_pessoa"
                . " WHERE 1 "
                . $sqlAux
                . " AND al.fk_id_sa in (1,6)"
                . " ORDER BY p.n_pessoa";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        return $array;
    }

    public static function transportesecundario() {

        if (empty($ano)) {
            $ano = date("Y");
        }

        if (!empty($mes)) {
            $dt_falta = "";
            $diasUteis = data::diasUteis($mes, $ano, NULL, 'N');
            $feriados = data::feriadoMes($mes, NULL, $ano, 1);
            $totaldia = count($diasUteis);
            $totalferiado = count($feriados);

            $proximoMesAno = $mes != 12 ? ($ano . '-' . (str_pad(($mes + 1), 2, "0", STR_PAD_LEFT))) : (($ano + 1) . '-01');

            $mes_ = " AND al.dt_inicio < '$proximoMesAno-01'"
                    . " AND ("
                    . " al.dt_fim >= '" . $ano . '-' . (str_pad(($mes), 2, "0", STR_PAD_LEFT)) . "-01'"
                    . " OR dt_fim IS NULL "
                    . " ) ";
        }

        $sql = "SELECT p.id_pessoa AS RSE, p.n_pessoa AS Aluno, i.n_inst AS Escola,"
                . " i.id_inst AS Id_Escola, p.ra AS RA, p.ra_dig AS Dig_RA,"
                . " t.codigo AS CodClasse, p.dt_nasc AS Dt_Nasc, t.fk_id_ciclo as SérieAno, al.id_alu As ID_Aluno,"
                . " al.dt_inicio, al.dt_fim, tl.n_li AS Linha, tl.id_li, tl.periodo as Período,"
                . " tl.motorista, tl.monitor, tl.tel1, tv.n_tv AS NrVeículo, tv.id_tv, tv.placa, tv.capacidade,"
                . " tv.acessibilidade, ttv.n_tiv, ttv.id_tiv, e.n_em FROM transporte_aluno al"
                . " JOIN pessoa p ON p.id_pessoa = al.fk_id_pessoa"
                . " JOIN instancia i ON i.id_inst = al.fk_id_inst"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turma_aluno_situacao tas ON tas.id_tas = ta.fk_id_tas AND tas.id_tas = 0 "
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND t.fk_id_inst = ta.fk_id_inst "
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl "
                . " JOIN transporte_linha tl ON tl.id_li = al.fk_id_li"
                . " JOIN transporte_veiculo tv ON tv.id_tv = tl.fk_id_tv"
                . " JOIN transporte_empresa e ON e.id_em = tv.fk_id_em"
                . " JOIN transporte_tipo_veiculo ttv ON ttv.id_tiv = tv.fk_id_tiv"
                . " JOIN ge_ciclos ci ON ci.id_ciclo = t.fk_id_ciclo "
                . " WHERE ci.fk_id_curso in (1,3) "
                . $mes_
                . " AND pl.at_pl = 1"
                . " AND al.fk_id_sa in (1,6)"
                . " ORDER BY p.n_pessoa";

        return $sql;
    }

###################

    public static function AlunosFreqAdaptado($mes, $idlinha = NULL, $ano = NULL, $dtInicio = NULL, $dtFim = NULL, $empresa = NULL) {

        if (empty($ano)) {
            $ano = date("Y");
        }

        if (!empty($idlinha)) {
            $idlinha = "and al.fk_id_li IN($idlinha) ";
        }

        if (!empty($empresa)) {
            $empresa = " and tv.fk_id_em = $empresa";
        }
        if (!empty($mes)) {
            $dt_falta = "";
            $diasUteis = data::diasUteis($mes, $ano, NULL, 'N');
            $feriados = data::feriadoMes($mes, NULL, $ano, 1);
            $totaldia = count($diasUteis);
            $totalferiado = count($feriados);
            $proximoMesAno = $mes != 12 ? ($ano . '-' . (str_pad(($mes + 1), 2, "0", STR_PAD_LEFT))) : (($ano + 1) . '-01');

            $mes_ = " AND al.dt_inicio < '$proximoMesAno-01' "
                    . " AND ("
                    . " al.dt_fim >= '" . $ano . '-' . (str_pad(($mes), 2, "0", STR_PAD_LEFT)) . "-01' "
                    . " OR dt_fim IS NULL "
                    . " ) ";
        } elseif (!empty($dtFim) && !empty($dtInicio)) {
            echo 'Falta implementar';
        }
        /**
         * lista os alunos
         */
        $sql = "SELECT p.id_pessoa, p.n_pessoa, i.n_inst, i.id_inst, p.ra, p.ra_dig,"
                . " t.codigo, p.dt_nasc, t.fk_id_ciclo, al.id_alu,"
                . " al.dt_inicio, al.dt_fim, al.fk_id_sa, tl.n_li, tl.id_li, tl.periodo,"
                . " tl.motorista, tl.monitor, tl.tel1, tv.n_tv, tv.placa, tv.capacidade,"
                . " tv.acessibilidade, ttv.n_tiv, e.n_em FROM transporte_aluno al"
                . " JOIN pessoa p ON p.id_pessoa = al.fk_id_pessoa"
                . " JOIN instancia i ON i.id_inst = al.fk_id_inst"
                . " JOIN ge_turma_aluno ta ON ta.fk_id_pessoa = p.id_pessoa"
                . " JOIN ge_turma_aluno_situacao tas ON tas.id_tas = ta.fk_id_tas AND tas.id_tas = 0 "
                . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma AND t.fk_id_inst = ta.fk_id_inst "
                . " JOIN ge_periodo_letivo pl ON pl.id_pl = t.fk_id_pl "
                . " JOIN transporte_linha tl ON tl.id_li = al.fk_id_li"
                . " JOIN transporte_veiculo tv ON tv.id_tv = tl.fk_id_tv"
                . " JOIN transporte_empresa e ON e.id_em = tv.fk_id_em"
                . " JOIN transporte_tipo_veiculo ttv ON ttv.id_tiv = tv.fk_id_tiv "
                . " JOIN ge_ciclos ci ON ci.id_ciclo = t.fk_id_ciclo "
                . " WHERE ci.fk_id_curso in (1,3) "
                . $idlinha
                . $mes_
                . $empresa
                . " AND pl.at_pl = 1"
                . " AND al.fk_id_sa in (1,6)"
                . " ORDER BY p.n_pessoa";

        $query = pdoSis::getInstance()->query($sql);
        $alunos = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($alunos)) {
            /**
             * lista as faltas
             */
            $id_alus = array_column($alunos, 'id_alu');

            $sql = " SELECT * FROM `transporte_falta` "
                    . " where fk_id_alu in (" . (implode(",", $id_alus)) . ") "
                    . " AND dt_falta LIKE '$ano-" . (str_pad(($mes), 2, "0", STR_PAD_LEFT)) . "-%' ";
            $query = pdoSis::getInstance()->query($sql);
            $f = $query->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($f)) {
                foreach ($f as $v) {
                    $faltas[$v['fk_id_alu']][substr($v['dt_falta'], 8)] = substr($v['dt_falta'], 8);
                }
            } else {
                $faltas = [];
            }
            /**
             * lista os dias que houve lançamentos
             */
            $sql = "SELECT "
                    . " j.dt_falta, j.fk_id_li "
                    . " FROM `transporte_falta_justifica` j "
                    . " JOIN transporte_aluno al ON al.fk_id_li = j.fk_id_li "
                    . " where al.id_alu in (" . (implode(",", $id_alus)) . ") "
                    . " AND j.dt_falta LIKE '$ano-" . (str_pad(($mes), 2, "0", STR_PAD_LEFT)) . "-%' ";
            $query = pdoSis::getInstance()->query($sql);
            $l = $query->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($l)) {
                foreach ($l as $v) {
                    $lancado[$v['fk_id_li']][substr($v['dt_falta'], 8)] = substr($v['dt_falta'], 8);
                }
            } else {
                $lancado = [];
            }


            foreach ($alunos as $v) {
                $AlunosFeriados = [];
                @$alu['aluno'][$v['id_alu']] = $v;

                if (in_array($v['fk_id_ciclo'], [1, 2, 3, 4, 5, 6, 7, 8, 9, 25, 26, 27, 28, 29, 30, 31, 32, 34, 35])) {
                    $AlunosFeriados = $feriados['EF'];
                } else {
                    $AlunosFeriados = $feriados['EI'];
                }
                $dataInicio = intval(str_replace('-', '', $v['dt_inicio']));
                $dataFim = intval(str_replace('-', '', $v['dt_fim']));
                foreach ($diasUteis as $d) {
                    if (!empty($lancado[$v['id_li']][str_pad(($d), 2, "0", STR_PAD_LEFT)])) {
                        $dataDia = intval($ano . (str_pad(($mes), 2, "0", STR_PAD_LEFT)) . (str_pad(($d), 2, "0", STR_PAD_LEFT)));
                        if (($dataInicio <= $dataDia) && (($dataDia <= $dataFim) || empty($dataFim))) {
                            if (@array_key_exists($d, $AlunosFeriados)) {
                                @$alu['aluno'][$v['id_alu']]['frequencia'][(str_pad(($d), 2, "0", STR_PAD_LEFT))] = $AlunosFeriados[$d];
                            } elseif (!empty($faltas[$v['id_alu']][(str_pad(($d), 2, "0", STR_PAD_LEFT))])) {
                                @$alu['aluno'][$v['id_alu']]['frequencia'][(str_pad(($d), 2, "0", STR_PAD_LEFT))] = 'F';
                                @$alu['aluno'][$v['id_alu']]['ContaFalta'] ++;
                                @$alu['linha'][$v['id_li']]['ContaFalta'] ++;
                                @$alu['rede']['ContaFalta'] ++;
                            } else {
                                @$alu['aluno'][$v['id_alu']]['frequencia'][(str_pad(($d), 2, "0", STR_PAD_LEFT))] = 'P';
                                @$alu['aluno'][$v['id_alu']]['ContaFrenquencia'] ++;
                                @$alu['linha'][$v['id_li']]['ContaFrenquencia'] ++;
                                @$alu['linha'][$v['id_li']][(str_pad(($d), 2, "0", STR_PAD_LEFT))] ++;
                                @$alu['rede']['ContaFrenquencia'] ++;
                                //Alunos transportados
                                @$alu[$v['id_inst']][$v['n_em']][$v['periodo']] ++;
                                @$alu['Total'][$v['n_em']][$v['periodo']] ++;
                                @$alu['PeriodoT'][$v['id_inst']][$v['n_em']] ++;
                                @$alu['c'][$v['id_inst']][$v['n_em']][$v['id_li']] = $v['capacidade'];
                                @$alu['capaci'][$v['id_li']] = $v['capacidade'];
                                @$alu['Total'][$v['periodo']] ++;
                            }
                        } else {

                            @$alu['aluno'][$v['id_alu']]['frequencia'][(str_pad(($d), 2, "0", STR_PAD_LEFT))] = '*';
                        }
                    } else {
                        if (!empty($AlunosFeriados)) {

                            if (array_key_exists((str_pad(($d), 2, "0", STR_PAD_LEFT)), $AlunosFeriados)) {
                                @$alu['aluno'][$v['id_alu']]['frequencia'][(str_pad(($d), 2, "0", STR_PAD_LEFT))] = $AlunosFeriados[(str_pad(($d), 2, "0", STR_PAD_LEFT))];
                            } else {
                                @$alu['aluno'][$v['id_alu']]['frequencia'][(str_pad(($d), 2, "0", STR_PAD_LEFT))] = 'N';
                            }
                        } else {
                            @$alu['aluno'][$v['id_alu']]['frequencia'][(str_pad(($d), 2, "0", STR_PAD_LEFT))] = 'N';
                        }

                        @$alu['linha'][$v['id_li']][(str_pad(($d), 2, "0", STR_PAD_LEFT))] = 0;
                    }
                }

                @$alu['linha'][$v['id_li']]['totalAlunos'] ++;
                // @$alu['linha'][$v['id_li']]['totalAlunos'] ++;
                @$alu['id_inst'][$v['id_inst']]['totalAlunosPeriodo'][$v['periodo']] ++;
                @$alu['id_inst'][$v['id_inst']]['totalAlunos'] ++;

                @$alu['rede']['totalAlunos'] ++;
                if (!empty($v['dt_inicio'])) {
                    @$alu['inclusao'][$v['id_inst']] ++;
                    @$alu['totali'] ++;
                }
                if (!empty($v['dt_fim'])) {
                    @$alu['exclusao'][$v['id_inst']] ++;
                    @$alu['totale'] ++;
                }
            }

            $esp = self::getListaEspera($ano . '-' . $mes);
            if (!empty($esp)) {
                foreach ($esp as $w) {
                    @$alu['espera'][$w['fk_id_inst']] ++;
                    @$alu['totals'] ++;
                }
            }

            @$alu['totaldia'] = $totaldia;
            @$alu['totalferiado'] = $totalferiado;

            return @$alu;
        }
    }

    public static function getClassStatus($fk_id_sa=null)
    {
        $cl = '';
        switch ($fk_id_sa) {
            case 0:
                $cl = 'warning';
                break;
            case 1:
                $cl = 'success';
                break;
            case 2:
                $cl = 'danger';
                break;
            case 3:
                $cl = 'primary';
                break;
            case 6:
                $cl = 'secondary';
                break;
        }

        return $cl;
    }

    public static function getTotaisStatus($alunos, $fk_id_sa=null)
    {
        $totais[0] = 0;
        $totais[1] = 0;
        $totais[2] = 0;
        $totais[3] = 0;
        $totais[6] = 0;
        if (empty($alunos)) {
            return $totais;
        }
        foreach ($alunos as $v)
        {
            $totais[$v['fk_id_sa']]++;
        }

        return $totais;
    }

    public static function trataDias($mes, $dias, $feriado = null){
        if (empty($feriado)) {
            $feriado = dataErp::feriadoMes(str_pad($mes, 2, "0", STR_PAD_LEFT));
        }

        $igual = [];
        if (!empty($feriado['EI'])) {
            foreach ($feriado['EI'] as $v) {
                if (!empty($feriado['EF'][$v])) {
                    $igual[$v] = $v;
                }
            }
        }

        foreach ($dias as $k => $v) {
            if ($mes != date("m")) {
                if (in_array($v, $igual)) {
                    unset($dias[$k]);
                }
            } elseif (in_array($v, $igual) || $v > date("d")) {
                unset($dias[$k]);
            }
        }
        return [
            'dias' => $dias,
            'feriado' => $feriado,
        ];
    }

    public static function getTipoEnsino($fk_id_ciclo)
    {
        if (empty($fk_id_ciclo)) {
            return null;
        }

        if (in_array($fk_id_ciclo, [19, 20, 21, 22, 23, 24])) {
            $tipoEnsino = "EI";
        } else {
            $tipoEnsino = "EF";
        }
        return $tipoEnsino;
    }

    /**
     * Busca os alunos na lista de Espera
     * @param string $periodo: YYYY-MM
     * @param int $id_inst
     * @return array
     */
    public static function getListaEspera($periodo = null, $id_inst = null)
    {
        $sqlAux = '';

        if (!empty($periodo)) {
            $pr = explode("-", $periodo);
            $dt1 = $pr[0] ."-". $pr[1] ."-01";
            $dt2 = $pr[0] ."-". $pr[1] ."-31";

            $sqlAux .= " AND ta.dt_solicita BETWEEN '$dt1' AND '$dt2' ";
        }

        if (!empty($id_inst)) {
            $sqlAux .= " AND ta.fk_id_inst = $id_inst ";
        }

        $wsql = "SELECT p.id_pessoa, p.n_pessoa, p.ra, ta.id_alu, ta.fk_id_inst, DATE_FORMAT(ta.dt_solicita, '%d/%m/%Y') AS dt_solicita, i.id_inst, i.n_inst, t.n_turma, t.codigo, t.periodo, e.id_em, e.n_em, e.razao_social, e.email, e.nome_contato, l.id_li, l.n_li, l.viagem, v.capacidade "
            . " FROM transporte_aluno ta "
            . " JOIN pessoa p ON p.id_pessoa = ta.fk_id_pessoa "
            . " JOIN instancia i ON i.id_inst = ta.fk_id_inst "
            . " JOIN ge_turmas t ON t.id_turma = ta.fk_id_turma "
            . " LEFT JOIN transporte_linha l ON ta.fk_id_li = l.id_li "
            . " LEFT JOIN transporte_veiculo v ON v.id_tv = l.fk_id_tv"
            . " LEFT JOIN transporte_empresa e ON e.id_em = v.fk_id_em"
            . " WHERE ta.dt_inicio IS NULL "
            . " AND ta.dt_solicita IS NOT NULL "
            . " AND IFNULL(ta.fk_id_sa, 0) IN(0, 3)"
            . $sqlAux
            . " ORDER BY i.n_inst, p.n_pessoa";

        $query = pdoSis::getInstance()->query($wsql);
        $esp = $query->fetchAll(PDO::FETCH_ASSOC);

        return $esp ?? [];
    }
}
