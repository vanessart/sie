<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of disciplina
 *
 * @author marco
 */
class disciplina {

    public static function areas() {
        $disc = sql::get('ge_areas', '*', ['>' => 'n_area']);

        return $disc;
    }

    public static function disc() {
        $disc = sql::get(['ge_disciplinas', 'ge_areas'], '*', ['>' => 'n_disc']);

        return $disc;
    }

    public static function discId() {
        $disc_ = sql::get('ge_disciplinas', '*', ['>' => 'n_disc']);
        foreach ($disc_ as $dc) {
            $n_disc[$dc['id_disc']] = $dc['n_disc'];
        }
        $n_disc['nc'] = 'Professor Polivalente';

        return $n_disc;
    }

    public static function abrevId() {
        $disc_ = sql::get('ge_disciplinas', '*', ['>' => 'sg_disc']);
        foreach ($disc_ as $dc) {
            $sg_disc[$dc['id_disc']] = $dc['sg_disc'];
        }

        return $sg_disc;
    }

    public static function hab() {
        $hab = sql::get(['ge_habilidades', 'ge_areas'], '*', ['>' => 'n_hab']);

        return $hab;
    }

    public static function grade($id_grade = NULL) {
        if (!empty($id_grade)) {
            $where = ['id_grade' => $id_grade, '>' => 'n_grade'];
            $fetch = 'fetch';
        } else {
            $where = ['>' => 'n_grade'];
            $fetch = 'fetchAll';
        }
        $grade = sql::get(['ge_grades', 'ge_tp_aval'], '*', $where, $fetch);

        return $grade;
    }

    public static function alocado($id_grade) {
        $grade = disciplina::grade($id_grade);
        if ($grade['fk_id_ta'] == 1) {
            $alocado = sql::get(['ge_aloca_disc', 'ge_disciplinas', 'ge_aloca_disc_base'], '*', ['fk_id_grade' => $id_grade, '>' => 'ordem']);
            $alocado['table'] = 'ge_aloca_disc';
            $alocado['tipo'] = 'Disciplina';
            $alocado['nome'] = 'n_disc';
        } elseif ($grade['fk_id_ta'] == 2) {
            $alocado = sql::get(['ge_aloca_hab', 'ge_habilidades'], '*', ['fk_id_grade' => $id_grade, '>' => 'ordem']);
            $alocado['table'] = 'ge_aloca_hab';
            $alocado['tipo'] = 'Habilidades';
            $alocado['nome'] = 'n_hab';
        }

        return $alocado;
    }

    public static function gradeDisc($id_grade, $idNome = NULL) {
        $sql = "select d.id_disc, d.n_disc from ge_disciplinas d "
                . " join ge_aloca_disc ad on ad.fk_id_disc = d.id_disc "
                . " where ad.fk_id_grade =  " . @$id_grade;
        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($array)) {
            if (empty($idNome)) {
                return $array;
            } else {
                foreach ($array as $v) {

                    $disc[$v['id_disc']] = $v['n_disc'];
                }
                return $disc;
            }
        } else {
            ?>
            <div class="alert alert-danger">
                Não há Disciplinas alocadas nesta grade
            </div>
            <?php
        }
    }

//inclui o núcleo comum
    public static function selecaodisciplina() {
        $d = sql::get('ge_disciplinas', 'n_disc', ['>' => 'n_disc']);

        $x = 0;
        foreach ($d as $v) {
            $di[$x]['n_disc'] = $v['n_disc'];
            $x++;
        }
        $di[$x]['n_disc'] = 'Núcleo Comum';

        return $di;
    }

}
