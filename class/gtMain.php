<?php

class gtMain {

    public static function gdaeAtivo($id_inst) {
//52 camisão
        $ativo = [52];
        if (in_array($id_inst, $ativo)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function periodoSet($id_pl = NULL) {
        if (empty($id_pl)) {
            return sql::get('ge_setup', 'fk_id_pl', ['id_set' => 1], 'fetch')['fk_id_pl'];
        } else {
            return $id_pl;
        }
    }

    /**
     * 
     * @param int $situacao 0 = Períodos Encerrados; 1 = Períodos Ativos 2 = Períodos Previstos
     * @return type
     */
    public static function periodos($situacao = NULL) {
        if (!empty($situacao)) {
            $situacao = ['at_pl' => $situacao];
        }
        $tp = sql::get('ge_periodo_letivo', ' id_pl, n_pl ', $situacao);
        foreach ($tp as $v) {
            $periodos[$v['id_pl']] = $v['n_pl'];
        }

        return @$periodos;
    }

    public static function periodosPorAno($ano = NULL) {
        $p = sql::get('ge_periodo_letivo');
        foreach ($p as $v) {
            $per[$v['ano']][$v['id_pl']] = $v['n_pl'];
        }
        krsort($per);
        if (empty($ano)) {
            return $per;
        } else {
            return $per[$ano];
        }
    }

    public static function periodosPorSituacao($situacao = NULL) {
        $per = sql::get('ge_periodo_letivo', '*', ['<'=>'ano']);
        if (!empty($situacao)) {
            if (!is_array($situacao)) {
                $situacao[] = $situacao;
            }
            foreach ($per as $v) {
                if (in_array(1, $situacao)) {
                    $perin['Ativo'][$v['id_pl']] = $v['n_pl'];
                } elseif (in_array(1, $situacao)) {
                    $perin['Previstos'][$v['id_pl']] = $v['n_pl'];
                } elseif (in_array(1, $situacao)) {
                    $perin['Encerrados'][$v['id_pl']] = $v['n_pl'];
                }
            }
        } else {
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
        if (!empty($perin['Ativo'])) {
            $p['Ativo'] = @$perin['Ativo'];
        }
        if (!empty($perin['Previstos'])) {
            $p['Previstos'] = @$perin['Previstos'];
        }
        if (!empty($perin['Encerrados'])) {
            $p['Encerrados'] = @$perin['Encerrados'];
        }

        return $p;
    }

    public static function cursosPorSegmento($id_inst = NULL) {
        $sql = "select id_tp_ens, n_tp_ens, id_curso, n_curso from ge_tp_ensino e "
                . "join ge_cursos c on c.fk_id_tp_ens = e.id_tp_ens "
                . "order by n_tp_ens, n_curso";
        $query = pdoSis::getInstance()->query($sql);
        $seg = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($id_inst)) {
            $te = sql::get('ge_escolas', 'fk_id_tp_ens', ['fk_id_inst' => $id_inst], 'fetch')['fk_id_tp_ens'];
            if (!empty($te)) {
                $tens = explode('|', $te);
            }
        }
        foreach ($seg as $v) {
            if (empty($tens) || in_array($v['id_tp_ens'], $tens)) {
                $segm[$v['n_tp_ens']][$v['id_curso']] = $v['n_curso'];
            }
        }

        return $segm;
    }

    public static function periodosCiclos($id_ciclo) {
        $sql = " select * from ge_periodo_ciclo pc "
                . " join ge_periodo_letivo pl on pl.id_pl = pc.fk_id_pl "
                . " where fk_id_ciclo = $id_ciclo "
                . " and at_pl in (1,2) ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $list[$v['id_pl']] = $v['n_pl'];
        }
        return $list;
    }

    public static function periodoDoDia($sigla = NULL) {
        $per = ['M' => 'Manhã', 'T' => 'Tarde', 'N' => 'Noite', 'I' => 'Integral', 'G' => 'Geral'];
        if (empty($sigla)) {
            return $per;
        } else {
            return $per[$sigla];
        }
    }

    /**
     * 
     * @param type $id_grade
     * @return type $grade, $disc, $iddisc
     */
    public static function discGrade($id_grade) {
        $sql = "SELECT d.id_disc, d.n_disc, d.sg_disc, a.aulas, nucleo_comum FROM `ge_aloca_disc` a "
                . " join ge_disciplinas d on d.id_disc = a.fk_id_disc "
                . " where fk_id_grade =  " . $id_grade
                . " ORDER BY `d`.`n_disc` ASC ";
        $query = pdoSis::getInstance()->query($sql);
        $gr = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($gr as $v) {
            if ($v['nucleo_comum'] == 1) {
                $grade['nc']['n_disc'] = 'Núcleo Comum';
                $grade['nc']['sg_disc'] = 'NC';
                $grade['nc']['aulas'] = $v['aulas'];
            } else {
                $grade[$v['id_disc']]['n_disc'] = $v['n_disc'];
                $grade[$v['id_disc']]['sg_disc'] = $v['sg_disc'];
                $grade[$v['id_disc']]['aulas'] = $v['aulas'];
            }
        }

        return $grade;
    }

    public static function suporteCheck($id_inst = NULL) {
        $id_inst = tool::id_inst($id_inst);
        $sql = "SELECT * FROM `dttie_suporte_trab` "
                . " WHERE `status_sup` LIKE 'Aberto' "
                . " AND `fk_id_inst` = $id_inst "
                . " AND `ultimo_lado` LIKE 'Suporte' "
                . " ORDER BY `dt_sup` DESC ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function situacaoFinal() {
        $sql = "select i.n_inst, i.id_inst, c.n_ciclo, c.id_ciclo, t.periodo_letivo, ta.situacao, sf.n_sf "
                . "from ge_turma_aluno ta "
                . " JOIN ge_turma_aluno_situacao tas on tas.id_tas = ta.fk_id_tas AND tas.id_tas = 0 "
                . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " join ge_ciclos c on c.id_ciclo = t.fk_id_ciclo "
                . " join instancia i on i.id_inst = t.fk_id_inst "
                . " join ge_situacao_final sf on sf.id_sf = ta.situacao_final "
                . " where 1 "
                . " and t.periodo_letivo like '" . date("Y") . "' "
                . " order by n_inst, n_ciclo, n_sf";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            @$result[$v['n_inst'] . '|' . $v['n_ciclo'] . '|' . $v['n_sf']] ++;
        }
        ?>
        <pre>
            <?php
            print_r($result)
            ?>
        </pre>
        <?php
    }

    public static function periodosAno($ano) {
        $sql = "SELECT id_pl FROM `ge_periodo_letivo` WHERE `ano` = $ano";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $id_pl[$v['id_pl']] = $v['id_pl'];
        }
        if (!empty($id_pl)) {
            $id_pl = implode(',', $id_pl);

            return $id_pl;
        }
    }

    public static function AnoSemestrePeriodos($ano = NULL) {
        if(!empty($ano)){
            $ano = " WHERE `ano` = $ano ";
        }
        $sql = "SELECT * FROM `ge_periodo_letivo` $ano";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $id_pl[$v['ano']][$v['semestre']] = $v['id_pl'];
        }

        return $id_pl;
    }

    public static function semestrePeriodos($ano) {
        $sql = "SELECT * FROM `ge_periodo_letivo`  WHERE `ano` = $ano ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $id_pl[$v['semestre']] = $v['id_pl'];
        }

        return $id_pl;
    }

    public static function periodoLetivoAtual() {
        $sql = "SELECT pl.id_pl, pl.n_pl, pl.ano "
            . " FROM ge_periodo_letivo pl "
            . " WHERE pl.at_pl = 1 AND pl.preferencial = 1";
        $query = autenticador::getInstance()->query($sql);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}
