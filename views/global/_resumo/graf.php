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

        $r = $model->resultadoAvalGlobal($id_gl, $ano);

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
