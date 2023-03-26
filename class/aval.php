<?php

class aval {

    public static function proficiencia($id_gl, $id_turma = NULL, $id_inst = NULL) {
        $faltas = NULL;
        if (!empty($id_turma)) {
            $id_turma = " and ta.fk_id_turma = $id_turma ";
        }

        if (!empty($id_inst)) {
            $id_inst = " and ta.fk_id_inst = $id_inst ";
            $faltas =  " and rl.nfez is null "; // ajuste de última hora. Precisa ser reavaliado.
        } else {
            $faltas =  " and rl.nfez is null ";
        }
        $fields = " rl.fk_id_gl, rl.fk_id_pessoa, rl.fk_id_turma, rl.fk_id_inst, "
                . " ta.chamada, ta.situacao, rl.nfez, "
                . " rl.q01, rl.q02, rl.q03, rl.q04, rl.q05, rl.q06, rl.q07, rl.q08, rl.q09, rl.q10, "
                . " rl.q11, rl.q12, rl.q13, rl.q14, rl.q15, rl.q16, rl.q17, rl.q18, rl.q19, rl.q20, "
                . " rl.q21, rl.q22, rl.q23, rl.q24, rl.q25, rl.q26, rl.q27, rl.q28, rl.q29, rl.q30, "
                . " rl.q31, rl.q32, rl.q33, rl.q34, rl.q35, rl.q36, rl.q37, rl.q38, rl.q39, rl.q40, "
                . " rl.q41, rl.q42, rl.q43, rl.q44, rl.q45, rl.q46, rl.q47, rl.q48, rl.q49, rl.q50  ";

       // $sql =    " select distinct $fields from global_respostas rl "
        $sql =    " select $fields from global_respostas rl "
              //. " join global_result rs on rs.fk_id_gl = rl.fk_id_gl "
                . " left join ge_turma_aluno ta on ta.fk_id_pessoa = rl.fk_id_pessoa "
                . "                            and ta.fk_id_turma = rl.fk_id_turma "
                . " where rl.fk_id_gl = $id_gl "
                . $id_turma
                . $id_inst
                . $faltas
                . " order by ta.chamada ";

        $query = pdoSis::getInstance()->query($sql);
        $array = $query->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($array)){
            return $array;
        } else {
            tool::alertModal("Não há dados referente a esta consulta");
            exit();
        }
    }

    
    public static function getAval($id_gl) {
        return sql::get('global_aval', '*', ['id_gl' => $id_gl], 'fetch');
    }

    public static function quantHab($id_gl, $id_turma = NULL, $id_inst = NULL) {

        $proficeiencia = aval::proficiencia($id_gl, $id_turma, $id_inst);
        $aval = aval::getAval($id_gl);
        $valores = explode(',', $aval['valores']);
        $val = unserialize($aval['val']);


        foreach ($proficeiencia as $v) {
            for ($c = 1; $c <= 50; $c++) { // de <= 40 para <= 50
                @$descritor = $valores[($v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)] - 1)];
                if (!empty($descritor)) {
                    @$graf[$c][$descritor] ++;
                    @$cont[$c] ++;
                }
            }
        }
        if (!empty($graf)) {
            foreach ($graf as $k => $v) {
                foreach ($v as $kk => $vv) {
                    $graf[$k][$kk] = round((($graf[$k][$kk] / $cont[$k]) * 100), 1);
                }
            }
            ksort($graf);
            return $graf;
        } else {
            tool::alertModal("Não há dados referente a esta consulta");
            exit();
        }
    }

}
