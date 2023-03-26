<?php
if (!empty($_POST['id_gl'])) {
    $escolas = escolas::idInst();
    $id_gl = @$_POST['id_gl'];
    $sql = " select * from `global_descritivo` "
            . " where `aval` =  " . @$_POST['id_gl'];
    $query = $model->db->query($sql);
    $d = $query->fetchAll();

    foreach ($d as $key => $value) {
        $habil[$value['valor']] = 0; //$value['valorNominal'];
    }

    if (!empty($d)) {
        foreach ($d as $v) {
            $devol[$v['num']][$v['valor']]['questao'] = $v['questao'];
            $devol[$v['num']][$v['valor']]['titulo'] = $v['titulo'];
            $devol[$v['num']][$v['valor']]['valorNominal'] = $v['valorNominal'];
            $devol[$v['num']][$v['valor']]['descricao'] = $v['valorNominal'] .'. '. $v['descricao'];
        }
        $sql = " select escrita from `global_aval` where `id_gl` = $id_gl";
        $query = $model->db->query($sql);
        $escrita = $query->fetch()['escrita'];
        $ano = sql::get('ge_periodo_letivo', '*', ['id_pl' => $periodoLetivo], 'fetch')['ano'];
        
       $r = $model->resultadoAvalGlobal($id_gl, $ano);
       
        if (empty($r)) {
            tool::alertModal("Não há dados referente a esta consulta");
            exit();
        }


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

        foreach ($quest as $key => $value) {

            $quest[$key] = $value + $habil;
        }
        
        

        if (!empty($quest)) {
            foreach ($quest as $k => $v) {
                @$titulo = $devol[$k][1]['titulo'];
                for ($c = 1; $c <= count($v); $c++) {
                    if (!empty(@$quest[@$k][@$c]) || @$quest[@$k][@$c] == 0 ) {
                        @$graf[@$titulo][@$devol[@$k][@$c]['descricao']] = $quest[@$k][@$c];
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
                2 => 'yellow', // 'orange'
                3 => 'orange', // 'yellow'
                4 => 'red'
            ];
            if (empty($_POST['escrita'])) {
                include ABSPATH . '/views/global/_devolutiva/_graf/aval.php';
            } else {
                include ABSPATH . '/views/global/_devolutiva/_graf/escrita.php';
            }
        }
    }
}
