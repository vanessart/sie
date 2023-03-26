<?php

class ng_hist {

    public $_notas;
    public $id_pessoa;

    public function __construct($id_pessoa) {
        $this->id_pessoa = $id_pessoa;
    }

    public function notas() {
        $idTurma = [];
        $sql = " SELECT * FROM ge_disciplinas ";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        $disc = toolErp::idName($array);
        $fields = " id_final, fk_id_pessoa, fk_id_turma, fk_id_ciclo, "
                . " fk_id_pl, "
                . " media_6, media_9, media_10, media_11, media_12, media_13, "
                . " media_14, media_15, media_16, media_17, media_18, media_19, "
                . " media_20, media_21, media_22, media_23, media_24, media_25, "
                . " media_26, media_27, media_29, media_30, cons_1, cons_2, "
                . " cons_3, cons_4, cons_5, cons_6, cons_7, cons_8, cons_9, "
                . " cons_10, cons_11, cons_12, cons_13, cons_14, cons_15, "
                . " cons_16, cons_17, cons_18, cons_19, cons_20, cons_21, "
                . " cons_22, cons_23, cons_24, cons_25, cons_26, cons_27, "
                . " cons_29, cons_30 ";
        $sql = " SELECT $fields FROM hab.aval_final "
                . " WHERE fk_id_pessoa = " . $this->id_pessoa;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        foreach ($array as $v) {
            $idTurma[$v['fk_id_turma']] = $v['fk_id_turma'];
        }
        $discCicloGrades = $this->discCicloGrades($idTurma);
        
        foreach ($array as $v) {

//verificar nota abaixo de 5
            $pula = null;
            foreach ($v as $ky => $y) {
                if (substr($ky, 0, 6) == 'media_' && !empty($y)) {
                    $y = str_replace(',', '.', $y);
                    if (is_numeric($y) && empty($v['cons_' . substr($ky, 6)]) && $y < 5) {
                        $pula = 1;
                    }
                }
            }
            if ($pula) {
                continue;
            }
            foreach ($v as $ky => $y) {
                if (substr($ky, 0, 5) == 'cons_' && !empty($y)) {
                    if (!empty($discCicloGrades[substr($ky, 5)][$v['fk_id_ciclo']])) {
                        $base = $discCicloGrades[substr($ky, 5)][$v['fk_id_ciclo']];
                    } elseif (in_array(substr($ky, 5), [6, 9, 10, 11, 12, 13, 14, 30])) {
                        $base = 1;
                    } else {
                        $base = 2;
                    }
                    $nota[substr($ky, 5)]['base'] = $base;
                    $nota[substr($ky, 5)]['id_disc'] = substr($ky, 5);
                    $nota[substr($ky, 5)]['n_disc'] = $disc[substr($ky, 5)];
                    $nota[substr($ky, 5)]['notas'][$v['fk_id_ciclo']] = [
                        'nota' => $y,
                        'id_pl' => $v['fk_id_pl'],
                        'id_turma' => $v['fk_id_turma'],
                        'fk_id_ciclo' => $v['fk_id_ciclo'],
                    ];
                } elseif (substr($ky, 0, 6) == 'media_' && !empty($y)) {
                    if (!empty($discCicloGrades[substr($ky, 6)][$v['fk_id_ciclo']])) {
                        $base = $discCicloGrades[substr($ky, 6)][$v['fk_id_ciclo']];
                    } elseif (in_array(substr($ky, 6), [6, 9, 10, 11, 12, 13, 14, 30])) {
                        $base = 1;
                    } else {
                        $base = 2;
                    }
                    $nota[substr($ky, 6)]['id_base'] = $base;
                    $nota[substr($ky, 6)]['id_disc'] = substr($ky, 6);
                    $nota[substr($ky, 6)]['n_disc'] = $disc[substr($ky, 6)];
                    $nota[substr($ky, 6)]['notas'][$v['fk_id_ciclo']] = [
                        'nota' => $y,
                        'id_pl' => $v['fk_id_pl'],
                        'id_turma' => $v['fk_id_turma'],
                        'fk_id_ciclo' => $v['fk_id_ciclo'],
                    ];
                }
            }
        }
        
        $notaMongo = $this->notasMongo();
        if ($notaMongo) {
            foreach ($notaMongo as $v) {
                $nota[] = $v;
            }
        }
        if (!empty($nota)) {
            $this->_notas = $nota;
            return $this->_notas;
        }
    }

    public function discCicloGrades($idTurma = null) {
        if (empty($idTurma)) {
            $idTurma = [1];
        }
        $sql = "SELECT distinct a.fk_id_disc, a.fk_id_adb, t.fk_id_ciclo FROM ge_aloca_disc a "
                . " JOIN ge_turmas t on t.fk_id_grade = a.fk_id_grade "
                . " WHERE t.id_turma in (" . implode(', ', $idTurma) . ")";
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($array as $v) {
            $dg[$v['fk_id_disc']][$v['fk_id_ciclo']] = $v['fk_id_adb'];
        }
        if (!empty($dg)) {
            return $dg;
        }
    }

    public function notasMongo() {
        $mongo = new mongoCrude('Hist');
        $filter = [
            'fk_id_pessoa' => (string) $this->id_pessoa
        ];
        $dados = $mongo->query('disciplinasDiversificadas', $filter);

        foreach ($dados as $v) {
            $nota[$v->n_disc]['base'] = 2;
            $nota[$v->n_disc]['id_disc'] = null;
            $nota[$v->n_disc]['n_disc'] = $v->n_disc;
            $nota[$v->n_disc]['notas'][$v->fk_id_ciclo] = $v->nota;
        }

        if (!empty($nota)) {
            return $nota;
        }
    }

}
