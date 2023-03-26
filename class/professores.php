<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of professores
 *
 * @author marco
 */
class professores {

    public static function listar($id_inst = NULL, $id_disc = NULL, $hac_dia = NULL, $hac_periodo = NULL) {
        if (!empty($id_inst)) {
            $id_inst = " and  p.fk_id_inst  = $id_inst ";
        }
        if (!empty($hac_dia)) {
            $hac_dia = " and  hac_dia  = $hac_dia ";
        }
        if (!empty($hac_periodo)) {
            $hac_periodo = " and   hac_periodo  = $hac_periodo ";
        }

        $sql = "SELECT "
                . "pe.n_pessoa, p.rm, `disciplinas`, `hac_dia`, `hac_periodo`, n_inst, f.fk_id_pessoa  "
                . "FROM ge_prof_esc p "
                . " join instancia i on i.id_inst = p.fk_id_inst "
                . " join ge_funcionario f on f.rm = p.rm "
                . " join pessoa pe on pe.id_pessoa = f.fk_id_pessoa "
                . " WHERE `disciplinas` LIKE '%$id_disc%' $id_inst $hac_dia $hac_periodo "
                . " order by n_pessoa ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        return $array;
    }

    public static function diaHac() {
        return [2 => '2º Feira', 3 => '3º Feira', 4 => '4º Feira', 5 => '5º Feira'];
        ;
    }

    public static function profDiscClasse($disc = NULL, $hac_dia = NULL, $hac_periodo = NULL, $id_inst = NULL, $id_ciclo = NULL) {
        $n_disc = disciplina::discId();
        if (!empty($disc)) {
            $disc_i = " and (";
            foreach ($disc as $dd) {
                $disc_i .= " iddisc like '" . $dd . "' or ";
            }
            $disc_i = substr($disc_i, 0, -4) . ")";
        } else {
            $disc_i = NULL;
        }
        if (!empty($hac_dia)) {
            $hac_dia = " and hac_dia like '" . $hac_dia . "' ";
        } else {
            $hac_dia = NULL;
        }
        if (!empty($hac_periodo)) {
            $hac_periodo = " and hac_periodo like '" . $hac_periodo . "' ";
        } else {
            $hac_periodo = NULL;
        }
        if (!empty($id_inst)) {
            $id_inst = " and id_inst = '" . $id_inst . "' ";
        } else {
            $id_inst = NULL;
        }
        if (!empty($id_ciclo)) {
            $id_ciclo = " and fk_id_ciclo = '" . $id_ciclo . "' ";
        } else {
            $id_ciclo = NULL;
        }

        $sql = "SELECT n_inst as escola, pe.n_pessoa as Professor, pe.emailgoogle, p.rm as matricula, disciplinas, hac_dia, hac_periodo FROM ge_prof_esc p "
                . " join ge_aloca_prof ap on ap.rm = p.rm "
                . " join ge_escolas e on e.fk_id_inst = p.fk_id_inst "
                . " join instancia i on i.id_inst = p.fk_id_inst "
                . " join ge_turmas t on t.id_turma=ap.fk_id_turma "
                . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " join ge_funcionario f on f.rm = p.rm "
                . " join pessoa pe on pe.id_pessoa = f.fk_id_pessoa "
                . "where 1 "
                . " and at_pl = 1"
                . " $disc_i "
                . " $hac_dia "
                . " $hac_periodo "
                . " $id_inst "
                . " $id_ciclo "
                . "order by Professor ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($array)) {
            foreach ($array as $v) {
                $prof[$v['matricula']]['dia'] = $v['hac_dia'];
                $prof[$v['matricula']]['periodo'] = $v['hac_periodo'];
                $prof[$v['matricula']]['nome'] = $v['Professor'];
                $prof[$v['matricula']]['emailgoogle'] = $v['emailgoogle'];
                $disc = explode('|', $v['disciplinas']);
                @$disciplinas = NULL;
                foreach ($disc as $d) {
                    if (!empty($d)) {
                        @$prof[$v['matricula']]['id_disc'][$d] = $d;
                        @$disciplinas[$n_disc[$d]] = $n_disc[$d];
                    }
                }
                if (!empty($disciplinas)) {
                    $prof[$v['matricula']]['disciplinas'] = implode(' ', $disciplinas);
                }
                @$prof[$v['matricula']]['escolas'] [$v['escola']] = $v['escola'] . ' ';
                $sql = "SELECT n_turma, fk_id_ciclo, codigo, periodo, n_inst FROM ge_aloca_prof p "
                        . " join ge_turmas t on t.id_turma = p.fk_id_turma "
                        . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                        . " join instancia i on i.id_inst = t.fk_id_inst "
                        . " WHERE `rm` LIKE '" . $v['matricula'] . "' "
                        . "and at_pl = 1 ";
                $query = pdoSis::getInstance()->query($sql);
                $classe = $query->fetchAll(PDO::FETCH_ASSOC);
                @$classePeriodo = NULL;
                foreach ($classe as $cl) {
                    @$prof[$v['matricula']]['codigo'][] = $cl['codigo'];
                    if (!empty($id_ciclo)) {
                        if ($cl['fk_id_ciclo'] == $id_ciclo) {
                            @$prof[$v['matricula']]['classes'] .= $cl['n_turma'];
                        }
                    } else {
                        @$prof[$v['matricula']]['classes'] .= $cl['n_turma'] . '; ';
                    }
                    @$classePeriodo [$cl['periodo']][$cl['n_inst']][$cl['n_turma']] = $cl['n_turma'];
                }
                if (!empty($classePeriodo)) {

                    foreach ($classePeriodo as $kp => $p) {
                        @$prof[$v['matricula']]['classesPorPeriodo'][$kp] = key($p) . ': ';
                        foreach ($p as $ke => $e) {
                            @$prof[$v['matricula']]['classesPorPeriodo'][$kp] .= (implode('<br />', $e));
                        }
                    }
                }
            }

            return $prof;
        }
    }

    /**
     * 
     * @param type $id_pessoa
     * @return type [id_inst][id_turma]
     */
    public static function classesDisc($id_pessoa, $Sem_AEE = null) {
        $ciclo = "";
        if ($Sem_AEE == 1) {
            $ciclo = " AND t.fk_id_ciclo != 32 "; // 32 = turmas AEE
        }
        $escola = escolas::idInst();
        $sql = "select fk_id_grade, id_disc, n_disc from ge_aloca_disc a "
                . " join ge_disciplinas d on d.id_disc = a.fk_id_disc "
                . " where nucleo_comum = 1 "
                . "order by ordem ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $grade[$v['fk_id_grade']][$v['id_disc']] = $v['n_disc'];
        }

        $sql = "select rm from ge_funcionario where fk_id_pessoa = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $rms[$v['rm']] = $v['rm'];
        }
        if (!empty($rms)) {
            foreach ($rms as $rm) {
                $sql = "SELECT "
                        . " t.id_turma, a.iddisc, a.rm, i.id_inst, i.n_inst, d.n_disc, d.sg_disc, "
                        . "t.fk_id_grade, t.n_turma, t.codigo, pl.id_pl, ci.fk_id_curso id_curso, ci.id_ciclo, ci.aulas "
                        . " FROM ge_aloca_prof a "
                        . " join instancia i on i.id_inst = a.fk_id_inst "
                        . " join ge_turmas t on t.id_turma = a.fk_id_turma ".$ciclo
                        . " join ge_ciclos ci on ci.id_ciclo = t.fk_id_ciclo "
                        . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                        . " left join ge_disciplinas d on d.id_disc = a.iddisc "
                        . " WHERE a.`rm` = '$rm' "
                        . " and pl.at_pl = 1 ";

                $query = pdoSis::getInstance()->query($sql);
                $array = $query->fetchAll(PDO::FETCH_ASSOC);
                foreach ($array as $k => $v) {
                    $classes[$v['id_inst']]['escola'] = $v['n_inst'];
                    $classes[$v['id_inst']]['turmas'][$v['id_turma']] = $v['n_turma'];
                    $classes[$v['id_inst']]['id_pl'][$v['id_turma']] = $v['id_pl'];
                    $classes[$v['id_inst']]['id_curso'][$v['id_turma']] = $v['id_curso'];
                    $classes[$v['id_inst']]['id_ciclo'][$v['id_turma']] = $v['id_ciclo'];
                    $classes[$v['id_inst']]['aulas'][$v['id_turma']] = $v['aulas'];
                    if ($v['iddisc'] == 'nc') {
                        $classes[$v['id_inst']]['nucleoComum'][$v['id_turma']] = @$grade[$v['fk_id_grade']];
                    } else {
                        $classes[$v['id_inst']]['disciplinas'][$v['id_turma']][$v['iddisc']] = $v['n_disc'];
                    }
                }
            }
        } else {
            return;
        }
        if (!empty($classes)) {
            return $classes;
        }
    }

    public static function classes($id_pessoa) {
        $esc = professores::classesDisc($id_pessoa);
        if (!empty($esc)) {
            foreach ($esc as $id_inst => $turma) {
                asort($turma['turmas']);
                foreach ($turma['turmas'] as $id_turma => $t) {
                    $turmas['inst'][$id_turma] = $id_inst;
                    $turmas['classes'][$id_turma] = $t;
                    $turmas['classesEscolas'][$id_turma] = $t . ' - ' . $esc[$id_inst]['escola'];
                }
            }

            return $turmas;
        }
    }

    public static function leciona($rm, $id_inst = NULL, $periodoDia = NULL) {
        if (!empty($periodoDia)) {
            $periodoDia = " and periodo in ($periodoDia) ";
        }
        if (!empty($id_inst)) {
            $id_inst = " and ap.fk_id_inst in ($id_inst) ";
        }
        $sql = "SELECT t.id_turma, t.codigo FROM ge_aloca_prof ap "
                . "join ge_turmas t on t.id_turma = ap.fk_id_turma "
                . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " WHERE `rm` LIKE '$rm' "
                . $periodoDia
                . $id_inst
                . " and at_pl = 1 "
                . "order by t.n_turma";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $turma[$v['id_turma']] = $v['codigo'];
        }

        return @$turma;
    }

    public static function profDisc($rm) {
        $sql = "select * from ge_aloca_prof  al"
                . " JOIN ge_turmas t on t.id_turma = al.fk_id_turma "
                . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . "where rm = $rm "
                . "  and at_pl = 1 ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $disc[$v['iddisc']] = $v['iddisc'];
        }

        return $disc;
    }

    public static function horario($rm) {
        $sql = "SELECT t.id_turma, al.iddisc, h.dia_semana, h.aula, t.periodo, t.n_turma, t.codigo FROM ge_aloca_prof al "
                . " JOIN ge_horario h on h.fk_id_turma = al.fk_id_turma AND h.iddisc = al.iddisc "
                . " JOIN ge_turmas t on t.id_turma = al.fk_id_turma "
                . " join ge_periodo_letivo pl on pl.id_pl = t.fk_id_pl "
                . " WHERE al.rm = '$rm' "
                . " and at_pl = 1 "
                . " order by n_turma";
        $query = pdoSis::getInstance()->query($sql);
        $h = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($h as $v) {
            $ho[$v['iddisc']][$v['dia_semana']][$v['periodo']][$v['aula']] = [
                'aula' => $v['aula'],
                'n_turma' => $v['n_turma'],
                'codigo' => $v['codigo'],
                'id_turma' => $v['id_turma']
            ];
        }
        return @$ho;
    }

    public static function profEscola($id_pessoa) {
        $sql = "SELECT "
                . " i.id_inst, i.n_inst "
                . " FROM ge_prof_esc ap "
                . " JOIN ge_funcionario f on f.rm = ap.rm "
                . " JOIN instancia i on i.id_inst = ap.fk_id_inst "
                . " WHERE f.fk_id_pessoa = $id_pessoa";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $esc[$v['id_inst']] = $v['n_inst'];
        }
        if (empty($esc)) {
            return;
        } else {
            return $esc;
        }
    }

    public static function rm($id_pessoa) {
        $rm = sql::get('ge_funcionario', 'rm', ['fk_id_pessoa' => $id_pessoa]);
        foreach ($rm as $v) {
            $rm_[$v['rm']] = $v['rm'];
        }
        return $rm_;
    }

}
