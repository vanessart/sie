<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ng_main
 *
 * @author marco
 */
class ng_main {

    protected static $tentativa = 0;
    protected static $tentativaMax = 2;
    /**
     * 
     * @param type $pesquisa Nome ou id_pessoa
     */
    public static function alunoPesquisa($pesquisa, $id_inst = null, $limit = 100, $semTurma = null) {
        self::$tentativa++;

        $pesquisa = trim($pesquisa);
        if ($id_inst) {
            $id_inst = " AND i.id_inst = $id_inst ";
        }
        if (is_numeric($pesquisa)) {
            $pesquisa = " AND id_pessoa = $pesquisa";
        } else {
            $pesquisa = "AND n_pessoa like '$pesquisa%'";
        }
        if (empty($semTurma)) {
            $sql = "SELECT "
                    . " ta.situacao, p.id_pessoa, p.ra, p.ra_dig, p. ra_uf, p.n_pessoa, t.n_turma, t.id_turma, i.n_inst, pl.n_pl FROM pessoa p "
                    . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa AND (ta.fk_id_tas = 0 OR ta.fk_id_tas IS NULL) "
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                    . " JOIN instancia i on i.id_inst = t.fk_id_inst "
                    . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl and pl.at_pl = 1 "
                    . " WHERE 1 "
                    . $pesquisa
                    . $id_inst
                    . " order by p.n_pessoa, pl.id_pl "
                    . " limit 0, $limit";
        }
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if (empty($array)) {
            $sql = "SELECT "
                    . " ta.situacao, p.id_pessoa, p.ra, p.ra_dig, p. ra_uf, p.n_pessoa, t.n_turma, t.id_turma, i.n_inst, pl.n_pl FROM pessoa p "
                    . " JOIN ge_turma_aluno ta on ta.fk_id_pessoa = p.id_pessoa AND (ta.fk_id_tas = 0 OR ta.fk_id_tas IS NULL) "
                    . " JOIN ge_turmas t on t.id_turma = ta.fk_id_turma "
                    . " JOIN instancia i on i.id_inst = t.fk_id_inst "
                    . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                    . " WHERE 1 "
                    . $pesquisa
                    . $id_inst
                    . " order by p.n_pessoa, pl.id_pl "
                    . " limit 0, $limit";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        if ($array) {
            foreach ($array as $v) {
                $turma = $v['n_turma'] . ' - ' . $v['n_inst'] . ' - ' . $v['n_pl']. ' - ' . $v['situacao'];
                if (!empty($alunos[$v['id_pessoa']])) {
                    $alunos[$v['id_pessoa']]['turmas'][$v['id_turma']]=$turma;
                } else {
                    $v['turmas'][$v['id_turma']]=$turma;
                    $alunos[$v['id_pessoa']] = $v;
                }
            }
            unset($array);
            return $alunos;

        /*} elseif (self::$tentativa < self::$tentativaMax) {

            try {

                $integracao = new integracao();
                if (empty($integracao)) {
                    throw new Exception("Nenhuma resposta da integracao de alunos");
                }

                if (empty($integracao['status'])) {
                    throw new Exception($integracao['message']);
                }

                return self::alunoPesquisa($pesquisa, $id_inst, $limit, $semTurma);

            } catch (Exception $e) {
                return null;
            }
        */
        }
    }

    public static function disciplinas($id_turma, $fields = NULL) {
        if (empty($fields)) {
            $fields = "ge_turmas.fk_id_grade, nucleo_comum, id_disc, n_disc, sg_disc, aulas";
        } else {
            $fields = 'id_disc, ' . $fields;
        }

        $sql = " select $fields from ge_turmas "
                . " join ge_aloca_disc on ge_aloca_disc.fk_id_grade = ge_turmas.fk_id_grade "
                . " join ge_disciplinas on ge_disciplinas.id_disc = ge_aloca_disc.fk_id_disc "
                . " where id_turma = $id_turma "
                . " order by n_disc ";
        $query = autenticador::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $disc[$v['id_disc']] = $v;
        }
        unset($array);

        return $disc;
    }

    /**
     * se id_pl for nulo, retorna id_pl ativo e preferencial
     * @param type $id_pl
     * @return type int
     */
    public static function periodoSet($id_pl = null) {
        if (empty($id_pl)) {
            $sql = "SELECT id_pl FROM `ge_periodo_letivo` WHERE `at_pl` = 1 AND `preferencial` = 1 ";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetch(PDO::FETCH_ASSOC);
            if (!empty($array['id_pl'])) {
                return $array['id_pl'];
            } else {
                return $id_pl;
            }
        } else {
            return $id_pl;
        }
    }

    public static function periodosPorSituacao($situacao_ = NULL) {
        $per = sql::get('ge_periodo_letivo', '*', ['<'=>'ano']);
        if (!empty($situacao_)) {
            if (!is_array($situacao_)) {
                $situacao[] = $situacao_;
            } else {
                $situacao = $situacao_;
            }
            foreach ($per as $v) {
                if (in_array($v['at_pl'], $situacao)) {
                    $perin['Ativo'][$v['id_pl']] = $v['n_pl'];
                }
            }
        } else {
            $perin['Ativo'] = [];
            $perin['Previstos'] = [];
            $perin['Encerrados'] = [];
            foreach ($per as $v) {
                if ($v['at_pl'] == 1) {
                    $perin['Ativo'][$v['id_pl']] = $v['n_pl'];
                } elseif ($v['at_pl'] == 2) {
                    $perin['Previstos'][$v['id_pl']] = $v['n_pl'];
                } else {
                    $perin['Encerrados'][$v['id_pl']] = $v['n_pl'];
                }
            }
        }
        return $perin;
    }

    public static function periodosAtivos() {
        $sql = "SELECT id_pl FROM `ge_periodo_letivo` WHERE `at_pl` = 1 ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return array_column($array, 'id_pl');
    }

    public static function periodoDoDia($sigla = NULL) {
        $per = ['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite', 'I' => 'Integral', 'G' => 'Geral'];
        if (empty($sigla)) {
            return $per;
        } else {
            return $per[$sigla];
        }
    }

    public static function escolas($where = null, $cursos = null, $fields = null) {
        if ($cursos) {
            if (is_array($cursos)) {
                $cursos = implode(',', $cursos);
            }
            $cursos = " AND i.id_inst in ("
                    . "SELECT fk_id_inst FROM sed_inst_curso where fk_id_curso in ($cursos)"
                    . ")";
        }
        if (empty($fields)) {
            $fields = "i.id_inst, i.n_inst, i.ativo, i.email, i.terceirizada, "
                    . "e.id_escola, e.cie_escola, e.maps, e.latitude, e.longitude, e.esc_site, e.esc_contato ";
        }
        if (!empty($where)) {
            $where = $where + ['instancia.ativo' => 1];
            $where = sql::where($where);
        } else {
            $where = " where i.ativo = 1";
        }
        $sql = "select $fields from instancia i "
                . "join ge_escolas e on e.fk_id_inst = i.id_inst "
                . $where
                . $cursos
                . " order by n_inst";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchALL(PDO::FETCH_ASSOC);

        asort($array);

        return $array;
    }

    public static function cursosDados($where = NULL) {
        if (empty($where)) {
            $where = ['ativo' => 1, '>' => 'n_curso'];
        }
        $seg = sql::get(['ge_tp_ensino', 'ge_cursos'], '*', $where);

        return $seg;
    }

    public static function letivaData($id_curso, $id_pl) {
        $dt = sql::get('sed_letiva_data', '*', ['fk_id_curso' => $id_curso, 'fk_id_pl' => $id_pl]);
        foreach ($dt as $v) {
            $ld[$v['atual_letiva']] = $v;
        }

        return @$ld;
    }

    public static function cursosSeg($where = NULL) {
        if (empty($where)) {
            $where = ['ativo' => 1, '>' => 'n_cur'];
        }
        $seg = sql::get(['ge_tp_ensino', 'ge_cursos'], 'id_curso, id_curso id_cur, n_curso, n_curso n_cur, n_tp_ens, n_tp_ens n_seg , id_tp_ens, id_tp_ens id_seg', $where);
        foreach ($seg as $v) {
            $s[$v['id_cur']] = $v;
        }
        return $s;
    }

    /**
     * 
     * @param type $segmentos inclui o segmento entre parentes
     * @param type $where
     * @return string
     */
    public static function cursos($segmentos = NULL, $where = NULL) {
        if (empty($where)) {
            $where = ['ativo' => 1, '>' => 'n_cur'];
        }
        $seg = sql::get(['ge_tp_ensino', 'ge_cursos'], 'id_curso id_cur, n_curso n_cur, n_tp_ens n_seg', $where);
        foreach ($seg as $v) {
            if (empty($segmentos)) {
                $s[$v['id_cur']] = $v['n_cur'];
            } else {
                $s[$v['id_cur']] = $v['n_cur'] . ' (' . $v['n_seg'] . ')';
            }
        }
        natcasesort($s);
        return $s;
    }

    public static function disciplinasCurso($id_cur, $abrev = NULL) {
        $sql = "SELECT DISTINCT d.id_disc, d.n_disc, d.sg_disc FROM ge_curso_grade cg "
                . " JOIN ge_ciclos ci on ci.id_ciclo = cg.fk_id_ciclo "
                . " JOIN ge_aloca_disc ad on ad.fk_id_grade = cg.fk_id_grade "
                . " JOIN ge_disciplinas d on d.id_disc = ad.fk_id_disc "
                . " WHERE ci.fk_id_curso = $id_cur ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if (empty($abrev)) {
            $nome = 'n_disc';
        } else {
            $nome = 'abrev_disc';
        }
        foreach ($array as $v) {
            $d[$v['id_disc']] = $v[$nome];
        }

        return $d;
    }

    public static function ciclos($id_cur) {
        $sql = "SELECT ci.id_ciclo, ci.n_ciclo FROM ge_ciclos ci "
                . " JOIN ge_cursos c on c.id_curso = ci.fk_id_curso "
                . ' WHERE c.id_curso = ' . $id_cur
                . " ORDER by ci.n_ciclo ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $ciclos[$v['id_ciclo']] = $v['n_ciclo'];
        }

        return $ciclos;
    }

}
