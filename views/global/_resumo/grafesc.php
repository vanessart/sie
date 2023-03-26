<?php
if (!empty($_POST['id_gl'])) {
    $escolas = escolas::idInst();
    if ($_SESSION['userdata']['id_nivel'] == 8) {
        $id_inst = tool::id_inst();
    } else {
        $id_inst = @$_POST['id_inst'];
    }
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
        if (!empty($id_turma)){
            $trm = " and t.id_turma = $id_turma ";
        } else {
            $trm = NULL;
        }

        $sql =    " select fk_id_pessoa, t.n_turma from ge_turma_aluno ta "
                . " join ge_turmas t on t.id_turma = ta.fk_id_turma "
                . " where fk_id_pl = $periodoLetivo "
                . " and t.fk_id_inst = $id_inst "
                . " $trm ";
        
        $query = $model->db->query($sql);
        $p = $query->fetchAll();

        foreach ($p as $v) {
            $id_pessoa[$v['fk_id_pessoa']] = $v['fk_id_pessoa'];
        }


        $sql = " select "
                . " `fk_id_gl`,`fk_id_pessoa`,`nfez`, "
                . " `q01`,`q02`,`q03`,`q04`,`q05`,`q06`,`q07`,`q08`,`q09`,`q10`, "
                . " `q11`,`q12`,`q13`,`q14`,`q15`,`q16`,`q17`,`q18`,`q19`,`q20`, "
                . " `q21`,`q22`,`q23`,`q24`,`q25`,`q26`,`q27`,`q28`,`q29`,`q30`, "
                . " `q31`,`q32`,`q33`,`q34`,`q35`,`q36`,`q37`,`q38`,`q39`,`q40`, "
                . " `q41`,`q42`,`q43`,`q44`,`q45`,`q46`,`q47`,`q48`,`q49`,`q50`, "
                . " escrita  "
                . " from global_respostas "
                . " where `fk_id_gl` = $id_gl "
                . " and fk_id_pessoa in (" . implode(",", $id_pessoa) . ")";
        $query = $model->db->query($sql);
        $r = $query->fetchAll();
        
        if (count($r)<10){
            tool::alert("Não Existem Dados!");
            unset($r);
            exit();
            }
        
        
        

        foreach ($r as $v) {
            if ($escrita == 1) {
                if (!empty($v['escrita'])) {
                    @$escritaEscola[$id_inst][$v['escrita']] ++;
                    @$escritaEscolaSoma[$id_inst] ++;
                    @$escritaSoma++;
                    @$escritaa[$v['escrita']] ++;
                    @$totalEscritaa[$id_inst][$v['escrita']] ++;
                }
            }

            for ($c = 1; $c <= count($devol); $c++) {
                @$quest[$c][$v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)]] ++;
                @$totalQuest[$id_inst][$c][$v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)]] ++;
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
            if (!empty($escrita)) {
                ?>
                <div class="noprint">
                    <div class="row">
                        <div class="col-sm-6 text-center">
                            <form method="POST">
                                                                                  <input type="hidden" name="id_agrup" value="<?php echo @$id_agrup ?>" />
         <input type="hidden" name="id_inst" value="<?php echo @$_POST['id_inst'] ?>" />
                                <input type="hidden" name="id_turma" value="<?php echo @$_POST['id_turma'] ?>" />
                                <input type="hidden" name="id_gl" value="<?php echo @$_POST['id_gl'] ?>" />
                                <input class="btn btn-info" name="escrita" type="submit" value="Nível de Escrita" />
                            </form>
                        </div>
                        <div class="col-sm-6 text-center">
                            <form method="POST">
                                <input type="hidden" name="id_inst" value="<?php echo @$_POST['id_inst'] ?>" />
                                <input type="hidden" name="id_turma" value="<?php echo @$_POST['id_turma'] ?>" />
                                <input type="hidden" name="id_gl" value="<?php echo @$_POST['id_gl'] ?>" />
                                <input class="btn btn-primary" type="submit" value="Avaliação" />
                            </form>
                        </div>
                    </div>
                    <br /><br />
                </div>
                <?php
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
