<?php

class coordena {

    public static function objetoConhecimento($where = NULL, $fields = '*', $limit = [0, 1000]) {
        if (!empty($where) && is_array($where)) {
            $where = sql::where($where);
        }
        $sql = "SELECT $fields FROM coord_objeto_conhecimento "
                . $where
                . " order by n_oc "
                . " limit $limit[0], $limit[1] ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function unTematica($where = NULL, $fields = NULL, $limit = [0, 1000]) {
        if (empty($fields)) {
            $fields = ' d.n_disc, u.* ';
        }
        if (!empty($where) && is_array($where)) {
            $where = sql::where($where);
        }
        $sql = "SELECT $fields FROM coord_uni_tematica u "
                . "LEFT JOIN ge_disciplinas d on d.id_disc = u.fk_id_disc "
                . $where
                . " order by d.n_disc, n_ut "
                . " limit $limit[0], $limit[1] ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function grupHab($where = NULL, $fields = NULL, $limit = [0, 1000]) {
        if (!empty($where) && is_array($where)) {
            $where = sql::where($where);
        }
        if (empty($fields)) {
            $fields = ' s.n_tp_ens n_seg, g.* ';
        }
        $sql = "SELECT $fields FROM coord_grup_hab g "
                . " JOIN ge_tp_ensino s on s.id_tp_ens = g.fk_id_seg "
                . $where
                . " order by n_seg, n_gh "
                . " limit $limit[0], $limit[1] ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function campExp($where = NULL, $fields = NULL, $limit = [0, 1000]) {
        if (!empty($where) && is_array($where)) {
            $where = sql::where($where);
        }
        if (empty($fields)) {
            $fields = ' g.n_gh, c.* ';
        }
        $sql = "SELECT $fields FROM coord_campo_experiencia c "
                . " JOIN coord_grup_hab g on g.id_gh = c.fk_id_gh "
                . $where
                . " order by n_gh, n_ce "
                . " limit $limit[0], $limit[1] ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function habGrupo($where = NULL, $fields = NULL, $limit = [0, 1000], $cicloForce = NULL) {
        if (!empty($where) && is_array($where)) {
            if (!empty($where['atual_letiva'])) {
                $atual_letiva = " and atual_letiva like '%" . $where['atual_letiva'] . "%'";
            } else {
                $atual_letiva = null;
            }
            unset($where['atual_letiva']);
            $where_ = sql::where($where);
        }
        if (empty($where['id_ciclo']) && empty($cicloForce)) {
            if (empty($fields)) {
                $fields = ' d.n_disc, h.* ';
            }
            $sql = "SELECT distinct $fields FROM coord_hab h "
                    . " LEFT  JOIN coord_hab_ciclo cc on cc.fk_id_hab = h.id_hab "
                    . " LEFT JOIN ge_disciplinas d on d.id_disc = h.fk_id_disc "
                    . $where_
                    . $atual_letiva
                    . " order by h.codigo "
                    . " limit $limit[0], $limit[1] ";
        } elseif (!empty($where['data'])) {
            $fields = ' d.n_disc, h.* ';
            $sql = "SELECT * FROM coord_hab h "
                    . " JOIN coord_hab_ciclo cc on cc.fk_id_hab = h.id_hab "
                    . " JOIN coord_plano_aula_hab ph on ph.fk_id_hab = h.id_hab "
                    . " JOIN coord_plano_aula pa on pa.id_pa = ph.fk_id_pa "
                    . " WHERE pa.fk_id_ciclo = " . $where['id_ciclo'] . " "
                    . " AND pa.fk_id_disc = " . $where['id_disc'] . "  "
                    . " and turmas like '%," . $where['id_turma'] . ",%'"
                    . " and '" . $where['data'] . "' BETWEEN date(pa.inicio) AND date(pa.fim)"
                    . $atual_letiva
                    . " order by h.codigo ";
        } else {
            if (empty($fields)) {
                $fields = ' d.n_disc, ci.n_ciclo, cc.atual_letiva as letivas, h.* ';
            }
            $sql = "SELECT distinct $fields FROM coord_hab h "
                    . " LEFT JOIN ge_disciplinas d on d.id_disc = h.fk_id_disc "
                    . " JOIN coord_hab_ciclo cc on cc.fk_id_hab = h.id_hab "
                    . " JOIN ge_ciclos ci on ci.id_ciclo = cc.fk_id_ciclo "
                    . $where_
                    . $atual_letiva
                    . " order by h.codigo "
                    . " limit $limit[0], $limit[1] ";
        }
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);


        return $array;
    }

    public static function hab($where = NULL, $fields = NULL, $limit = [0, 1000], $cicloForce = NULL) {
        if (!empty($where) && is_array($where)) {
            $where_ = sql::where($where);
        }
        if (empty($where['id_ciclo']) && empty($cicloForce)) {
            if (empty($fields)) {
                $fields = ' d.n_disc, h.* ';
            }
            $sql = "SELECT distinct $fields FROM coord_hab h "
                    . " JOIN coord_set_grupo_curso sgc on sgc.fk_id_gh = h.fk_id_gh AND sgc.fk_id_cur = h.fk_id_cur"
                    . " LEFT JOIN ge_disciplinas d on d.id_disc = h.fk_id_disc "
                    . $where_
                    . " order by d.n_disc, h.ordem "
                    . " limit $limit[0], $limit[1] ";
        } elseif (!empty($where['data'])) {
            $fields = ' d.n_disc, h.* ';
            $sql = "SELECT * FROM coord_hab h "
                    . " JOIN coord_plano_aula_hab ph on ph.fk_id_hab = h.id_hab "
                    . " JOIN coord_plano_aula pa on pa.id_pa = ph.fk_id_pa "
                    . " WHERE pa.fk_id_ciclo = " . $where['id_ciclo'] . " "
                    . " AND pa.fk_id_disc = " . $where['id_disc'] . "  "
                    . " and turmas like '%," . $where['id_turma'] . ",%'"
                    . " and '" . $where['data'] . "' BETWEEN date(pa.inicio) AND date(pa.fim)";
        } else {
            if (empty($fields)) {
                $fields = ' d.n_disc, ci.n_ciclo, cc.atual_letiva as letivas, h.* ';
            }
            $sql = "SELECT distinct $fields FROM coord_hab h "
                    . " JOIN coord_set_grupo_curso sgc on sgc.fk_id_gh = h.fk_id_gh AND sgc.fk_id_cur = h.fk_id_cur"
                    . " LEFT JOIN ge_disciplinas d on d.id_disc = h.fk_id_disc "
                    . " JOIN coord_hab_ciclo cc on cc.fk_id_hab = h.id_hab "
                    . " JOIN ge_ciclos ci on ci.id_ciclo = cc.fk_id_ciclo "
                    . $where_
                    . " order by ci.n_ciclo, d.n_disc, h.ordem "
                    . (!empty($limit) ? " limit $limit[0], $limit[1] " : null);
        }
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);


        return $array;
    }

    public static function habCiclo($id_habs) {

        if (!empty(@$id_habs)) {
            $sql = "SELECT ci.n_ciclo, hc.fk_id_hab, hc.atual_letiva FROM `coord_hab_ciclo` hc "
                    . " join ge_ciclos ci on ci.id_ciclo = hc.fk_id_ciclo "
                    . " WHERE `fk_id_hab` IN (" . implode(',', $id_habs) . ") ";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($array as $v) {
                $ano[$v['fk_id_hab']][$v['n_ciclo']] = toolErp::virgulaE(explode(',', $v['atual_letiva']), 'ª');
            }
        }
        if (!empty($ano)) {
            return $ano;
        } else {
            return;
        }
    }

    public static function setGrupCurso() {
        $sql = "SELECT * FROM coord_set_grupo_curso";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $sgc[$v['fk_id_cur']] = $v['fk_id_gh'];
        }

        return @$sgc;
    }

    /**
     * 
     * @param type $id_pa se for array busca os criterios
     * @param type $ids
     * @return type
     */
    public static function habPlanoAula($id_pa, $ids = NULL) {
        if (is_array($id_pa)) {
            $id_pa = sql::where($id_pa);
        } else {
            $id_pa = "WHERE c.fk_id_pa = $id_pa ";
        }
        if (empty($ids)) {
            $fields = " h.id_hab, h.descricao, h.obs_hab ";
        } else {
            $fields = " h.id_hab ";
        }
        $sql = "SELECT $fields FROM coord_plano_aula_hab c "
                . "JOIN coord_hab h on h.id_hab = c.fk_id_hab "
                . $id_pa;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($ids)) {
            foreach ($array as $v) {
                $id[$v['id_hab']] = $v['id_hab'];
            }
            if (!empty($id)) {
                return $id;
            } else {
                return [];
            }
        } else {
            return $array;
        }
    }

    public static function habDiario($id_di, $ids = NULL) {
        if (is_array($id_di)) {
            $id_di = sql::where($id_di);
        } else {
            $id_di = "WHERE c.fk_id_di = $id_di ";
        }
        if (empty($ids)) {
            $fields = " h.id_hab, h.descricao, h.obs_hab, id_dh ";
        } else {
            $fields = " h.id_hab, id_dh ";
        }
        $sql = "SELECT $fields FROM coord_diario_hab c "
                . "JOIN coord_hab h on h.id_hab = c.fk_id_hab "
                . $id_di;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($ids)) {
            foreach ($array as $v) {
                $id[$v['id_hab']] = $v['id_hab'];
            }
            if (!empty($id)) {
                return $id;
            } else {
                return [];
            }
        } else {
            return $array;
        }
    }

    public static function planoAulaDoPeriodo($id_turma, $id_disc, $data) {
        $sql = "";
    }

    public static function habPorcent($id_pessoa, $id_inst, $id_cur, $id_ciclo, $id_disc, $atualLetiva) {


        $sql = "SELECT count(distinct fk_id_hab) ct FROM `coord_plano_aula` cp "
                . "join coord_plano_aula_hab h on h.fk_id_pa "
                . " WHERE `fk_id_pessoa` = $id_pessoa AND "
                . " `fk_id_inst` = $id_inst "
                . " AND `fk_id_ciclo` = $id_ciclo "
                . " AND `fk_id_disc` = $id_disc "
                . " AND `atualLetiva` = $atualLetiva  "
                . " AND `at_pa` = 1  ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetch(PDO::FETCH_ASSOC);

        $where['h.fk_id_cur'] = $id_cur;
        $where['at_hab'] = 1;
        $where['id_ciclo'] = $id_ciclo;
        $where['id_disc'] = $id_disc;
        $where['cc.atual_letiva'] = '%,' . $atualLetiva . ',%';
        $conta = coordena::hab($where, ' count(id_hab) as ct', [0, 1])[0]['ct'];
        if ($conta > 0) {
            $porc = ($array['ct'] / $conta) * 100;
        } else {
            $porc = 0;
        }
        return $porc;
    }

    public static function habDadas($id_turma, $id_disc = NULL) {
        if (!empty($id_disc)) {
            $id_disc = " and fk_id_disc = $id_disc ";
        }
        $sql = "SELECT n_hab, id_hab, descricao, fk_id_disc FROM `coord_hab` WHERE `id_hab` IN ("
                . "SELECT h.fk_id_hab FROM coord_diario d JOIN coord_diario_hab h on h.fk_id_di = d.id_di "
                . " where fk_id_turma = $id_turma "
                . " $id_disc "
                . ") ";



        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function habDadasLacadas($id_turma, $id_disc = NULL) {
        if (!empty($id_disc)) {
            $filter = ['id_turma' => $id_turma, 'id_disc' => $id_disc];
        } else {
            $filter = ['id_turma' => $id_turma];
        }

        $mongo = new mongoDb();
        $lanc = $mongo->query('habilidades', ['id_turma' => $id_turma]);
        foreach ($lanc as $v) {
            foreach ($v as $k => $y) {
                if (is_numeric($k)) {
                    $hab[$k] = $k;
                }
            }
        }
        if (!empty($hab)) {

            $sql = "SELECT n_hab, id_hab, descricao, fk_id_disc FROM `coord_hab` WHERE `id_hab` IN ("
                    . implode(',', $hab)
                    . ") ";

            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);

            return $array;
        }
    }

    public static function habDadasNaoLacadas($id_turma, $id_disc = NULL) {
        if (!empty($id_disc)) {
            $id_disc = " and fk_id_disc = $id_disc ";
            $filter = ['id_turma' => $id_turma, 'id_disc' => $id_disc];
        } else {
            $filter = ['id_turma' => $id_turma];
        }

        $mongo = new mongoDb();
        $lanc = $mongo->query('habilidades', ['id_turma' => $id_turma]);
        foreach ($lanc as $v) {
            foreach ($v as $k => $y) {
                if (is_numeric($k)) {
                    $hab[$k] = $k;
                }
            }
        }
        if (!empty($hab)) {

            $sql = "SELECT n_hab, id_hab, descricao, fk_id_disc FROM `coord_hab` WHERE `id_hab` IN ("
                    . "SELECT h.fk_id_hab FROM coord_diario d JOIN coord_diario_hab h on h.fk_id_di = d.id_di "
                    . " where fk_id_turma = $id_turma "
                    . " $id_disc "
                    . ") "
                    . " AND id_hab NOT IN ("
                    . implode(',', $hab)
                    . ")";

            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);

            return $array;
        }
    }

    public static function grupoHabCurso($id_cur) {
        $sql = " SELECT "
                . " gh.id_gh, gh.n_gh "
                . " FROM coord_grup_hab gh "
                . " JOIN ge_cursos c on c.fk_id_tp_ens = gh.fk_id_seg "
                . " WHERE c.id_curso = $id_cur "
                . " order by gh.n_gh ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return toolErp::idName($array);
    }

    public static function planoAulaHabLancada($turmas) {
        $lancado = [];
        $turmas = explode(',', $turmas);
        foreach ($turmas as $v) {
            if (!empty($v)) {
                $n_turma = sql::get('ge_turmas', 'n_turma', ['id_turma' => $v], 'fetch')['n_turma'];
                $sql = "SELECT pah.fk_id_hab FROM coord_plano_aula pa JOIN coord_plano_aula_hab pah on pah.fk_id_pa = pa.id_pa WHERE turmas LIKE '%,$v,%' ";
                $query = pdoSis::getInstance()->query($sql);
                $array = $query->fetchAll(PDO::FETCH_ASSOC);
                foreach ($array as $y) {
                    $lancado[$y['fk_id_hab']][$v] = $n_turma;
                }
            }
        }
        return $lancado;
    }

    public static function letivaHabCiclo($id_ciclo, $letiva) {
        $sql = "SELECT fk_id_hab FROM `coord_hab_ciclo` WHERE `fk_id_ciclo` = $id_ciclo AND `atual_letiva` LIKE '%,$letiva,%'";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return array_column($array, 'fk_id_hab');
    }

    public static function habDisc($id_disc, $id_gh = null) {
        if (!empty($id_gh)) {
            $id_gh = " AND h.fk_id_gh = $id_gh ";
        }
        $sql = "SELECT "
                . " codigo, descricao, id_hab, n_ciclo "
                . " FROM coord_hab h "
                . " left JOIN coord_hab_ciclo c on c.fk_id_hab = h.id_hab "
                . " left JOIN ge_ciclos ci on ci.id_ciclo = c.fk_id_ciclo "
                . " WHERE fk_id_disc = $id_disc "
                . $id_gh
                . " order by codigo ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($array)) {
            foreach ($array as $v) {
                $todos[$v['id_hab']] = $v;
            }
            if (!empty($todos)) {
                return $todos;
            }
        }
    }

    /**
     * 
     * @param type $id_pessoa
     * @param type $curso = [id_turma, id_cur, id_pl, id_ciclo, atual_letiva, un_letiva]
     * 
     */
    public static function habilAluno($id_pessoa, $curso) {
        $mongo = new mongoDb('Habil');
        $array = $mongo->query('habilidades', ['id_pessoa' => intval($id_pessoa), 'id_cur' => $curso['id_cur']]);
        if ($array) {
            if (is_array($array)) {
                $nota = 0;
                foreach ($array as $v) {
                    $v = (array) $v;
                    @$adquirida[$v['id_hab']] = $v['sit'];
                    if ($v['sit'] == 2) {
                        $nota += 0.5;
                        @$notaDisc[$v['id_disc']] += 0.5;
                    } elseif ($v['sit'] == 3) {
                        $nota += 1;
                        @$notaDisc[$v['id_disc']] += 1;
                    }
                }
            }
        }
        $result['adquirida'] = @$adquirida;
        $result['nota'] = $nota;
        $result['notaDisc'] = @$notaDisc;
        $sql = "SELECT "
                . " h.id_hab, h.descricao n_hab, h.codigo, h.fk_id_cur as id_cur, d.id_disc, d.abrev_disc, d.n_disc, ci.fk_id_ciclo as id_ciclo, ci.atual_letiva "
                . " FROM ge_cursos c "
                . " JOIN coord_grup_hab gh on gh.fk_id_seg = c.fk_id_seg AND c.id_curso = " . $curso['id_cur']
                . " JOIN coord_hab h on h.fk_id_gh = gh.id_gh "
                . " JOIN coord_hab_ciclo ci on h.id_hab = ci.fk_id_hab AND ci.fk_id_ciclo = " . $curso['id_ciclo']
                . " JOIN ge_disciplinas d on d.id_disc = h.fk_id_disc "
                . " order by d.n_disc, h.ordem";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $result['disc'][$v['id_disc']]['dados'] = $v;
            $result['disc'][$v['id_disc']]['hab'][$v['id_hab']] = $v;
        }
        $result['total'] = count($array);

        return $result;
    }

}
