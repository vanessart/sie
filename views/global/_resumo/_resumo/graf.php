<?php
if (!empty($_POST['id_gl'])) {
    $escolas = escolas::idInst();
    $id_gl = @$_POST['id_gl'];
    $sql = "SELECT * FROM `global_descritivo` "
            . " WHERE `aval` =  " . @$_POST['id_gl'];
    $query = $model->db->query($sql);
    $d = $query->fetchAll();
    if (!empty($d)) {
        foreach ($d as $v) {
            $devol[$v['num']][$v['valor']]['questao'] = $v['questao'];
            $devol[$v['num']][$v['valor']]['titulo'] = $v['titulo'];
            $devol[$v['num']][$v['valor']]['valorNominal'] = $v['valorNominal'];
            $devol[$v['num']][$v['valor']]['descricao'] = $v['valorNominal'];
        }
        $sql = "SELECT escrita FROM `global_aval` WHERE `id_gl` = $id_gl";
        $query = $model->db->query($sql);
        $escrita = $query->fetch()['escrita'];
        $ano = sql::get('ge_periodo_letivo', '*', ['id_pl' => $periodoLetivo], 'fetch')['ano'];

        $sql = "SELECT "
                . " g.`fk_id_gl`,g.`fk_id_pessoa`,g.`nfez`,"
                . " g.`q01`,g.`q02`,g.`q03`,g.`q04`,g.`q05`,g.`q06`,g.`q07`,g.`q08`,g.`q09`, g.`q10`, "
                . " g.`q11`,g.`q12`,g.`q13`,g.`q14`,g.`q15`,g.`q16`,g.`q17`,g.`q18`,g.`q19`, g.`q20`, "
                . " g.`q21`,g.`q22`,g.`q23`,g.`q24`,g.`q25`,g.`q26`,g.`q27`,g.`q28`,g.`q29`, g.`q30`, "
                . " g.`q31`,g.`q32`,g.`q33`,g.`q34`,g.`q35`,g.`q36`,g.`q37`,g.`q38`,g.`q39`, g.`q40`, "
                . " g.`q41`,g.`q42`,g.`q43`,g.`q44`,g.`q45`,g.`q46`,g.`q47`,g.`q48`,g.`q49`, g.`q50`, "
                . " g.escrita, ta.fk_id_inst "
                . "FROM global_respostas g "
                . "join ge_turma_aluno ta on ta.fk_id_pessoa = g.fk_id_pessoa "
                . "WHERE `fk_id_gl` = $id_gl "
                . " and periodo_letivo like '%$ano%' ";
    
        $query = $model->db->query($sql);
        $r = $query->fetchAll();
        foreach ($r as $v) {
            if ($escrita == 1) {
                if (!empty($v['escrita'])) {
                    @$escritaEscola[$v['fk_id_inst']][$v['escrita']] ++;
                    @$escritaEscolaSoma[$v['fk_id_inst']] ++;
                    @$escritaSoma++;
                    @$escritaa[$v['escrita']] ++;
                    @$totalEscritaa[$v['fk_id_inst']][$v['escrita']] ++;
                }
            }
            for ($c = 1; $c <= count($devol); $c++) {
                @$quest[$c][$v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)]] ++;
                @$totalQuest[$v['fk_id_inst']][$c][$v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)]] ++;
            }
        }
        if (!empty($quest)) {
            foreach ($quest as $k => $v) {
                @$titulo = $devol[$k][1]['titulo'];
                for ($c = 1; $c <= count($v); $c++) {
                    if (!empty($quest[$k][$c])) {
                        @$graf[$titulo][$devol[$k][$c]['descricao']] = $quest[$k][$c];
                    }
                }
            }

            foreach ($totalQuest as $ki => $vi) {
                foreach ($vi as $k => $v) {
                    @$titulo = $devol[$k][1]['titulo'];
                    for ($c = 1; $c <= count($v); $c++) {
                        if (!empty($quest[$k][$c])) {
                            @$totalGraf_[$ki][$titulo][$devol[$k][$c]['descricao']] = @$totalQuest[$ki][$k][$c];
                        }
                    }
                }
            }
            foreach ($escolas as $ke => $e) {
                if (!empty($totalGraf_[$ke])) {
                    @$totalGraf[$e] = $totalGraf_[$ke];
                }
            }
       

            $cor = [
                1 => 'green',
                2 => 'orange',
                3 => 'yellow',
                4 => 'red'
            ];
            if (empty($_POST['escrita'])) {
                include ABSPATH . '/views/global/_resumo/_graf/aval.php';
            } else {
                include ABSPATH . '/views/global/_resumo/_graf/escrita.php';
            }
        }
    }
}
