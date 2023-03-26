<style>
    td{
        -webkit-print-color-adjust: exact !important; 
        color-adjust: exact !important;
    }
    div{
        -webkit-print-color-adjust: exact !important; 
        color-adjust: exact !important;
    }
</style>
<?php
ini_set('memory_limit', '2000M');
if (empty($_POST['periodoLetivo'])) {
    $periodoLetivo = sql::get('ge_setup', 'fk_id_pl', ['id_set' => 1], 'fetch')['fk_id_pl'];
} else {
    $periodoLetivo = $_POST['periodoLetivo'];
}
@$id_agrup = @$_POST['id_agrup'];
$hidden['id_agrup'] = @$id_agrup;

if ($_SESSION['userdata']['id_nivel'] == 8) {
    $id_inst = tool::id_inst();
} else {
    $id_inst = @$_POST['id_inst'];
}
$hidden['id_inst'] = $id_inst;

$id_gl = @$_POST['id_gl'];
$hidden['id_gl'] = $id_gl;
if (!empty(@$id_agrup)) {
    $tipoProva = sql::get('global_agrupamento', 'tipo', ['id_agrup' => $id_agrup], 'fetch')['tipo'];
    $aval = sql::get('global_aval', 'id_gl, n_gl', ['fk_id_agrup' => $id_agrup, '>' => 'n_gl']);
    if (count($aval) == 1) {
        $id_gl = current($aval)['id_gl'];
    }
    foreach ($aval as $v) {
        $avalia[$v['id_gl']] = $v['n_gl'];
    }
}

$id_turma = @$_POST['id_turma'];
if (!empty($id_gl)) {
    $dados = sql::get('global_aval', '*', ['id_gl' => $id_gl], 'fetch');
    $es = str_replace('|', ',', substr($dados['escolas'], 1, -1));
    $sql = "select n_inst, id_inst from instancia "
            . " where id_inst in (" . $es . ") "
            . "order by n_inst ";
    $query = $model->db->query($sql);
    $esc = $query->fetchAll();
    foreach ($esc as $v) {
        $escolas[$v['id_inst']] = $v['n_inst'];
    }
}
?>
<div class="fieldBody">
    <div class="noprint">
        <div class="fieldTop">
            Lançamento de Notas
        </div>
        <div class="noprint">
            <br /><br />
            <div class="row">
                <div class="col-md-6">
                    <?php
                    if ($_SESSION['userdata']['id_nivel'] == 8) {
                        $where = " where ativo = '1' and tipo != 'tri' and n_agrup not like ('%EJA%') order by n_agrup asc ";
                    } else {
                        $where = " where tipo != 'tri' order by n_agrup asc ";
                    }
                    $agrup = sql::idNome('global_agrupamento', @$where);
                    formulario::select('id_agrup', $agrup, 'Agrupamento', @$id_agrup, 1);
                    ?>
                </div>
                <div class="col-md-6">
                    <?php
                    if (!empty(@$id_agrup) && !empty($avalia)) {
                        if ($_SESSION['userdata']['id_nivel'] == 8) {
                            $hidden['id_inst'] = tool::id_inst();
                        }

                        formulario::select('id_gl', $avalia, 'Avaliação', @$id_gl, 1, $hidden);
                        ?>
                    </div>
                </div>
                <br /><br />
                <div class="row">
                    <?php
                    if ($_SESSION['userdata']['id_nivel'] != 8 && !empty($id_gl)) {
                        ?>
                        <div class="col-md-5">
                            <?php
                            formulario::select('id_inst', $escolas, 'Escola', @$id_inst, 1, @$hidden);
                            $hidden['id_inst'] = $id_inst;
                            ?>
                        </div>
                        <?php
                        if (empty($id_inst)) {
                            $h = $hidden;
                            $h['acessarGrafico'] = 1;
                            echo formulario::submit('Toda Rede', NULL, $h);
                        }
                    }
                    if (!empty($id_inst)) {
                        ?>
                        <form method="POST">
                            <div class="col-md-5">
                                <?php
                                if (!empty($id_gl)) {
                                    $turmas = turma::option($id_inst, $periodoLetivo, 'fk_id_inst', $dados['ciclos']);
                                    formulario::select('id_turma', $turmas, 'Turma', @$id_turma, NULL, $hidden);
                                    $hidden['id_turma'] = @$id_turma;
                                }
                                ?> 
                            </div>
                            <div class="col-md-2">
                                <input class="btn btn-info" name="acessarGrafico" type="submit" value="Acessar" />
                            </div>
                        </form>
                        <?php
                    }
                }
                ?>
            </div>
            <br /><br />
        </div>
        <?php
        if (!empty($_POST['acessarGrafico'])) {


            if (!empty($id_inst)) {

                $inst = " and tu.fk_id_inst = $id_inst ";
            } else {
                $inst = null;
            }

            if (!empty($id_turma)) {

                $turma = " and fk_id_turma = $id_turma ";
            } else {
                $turma = null;
            }

            $sql =    " select i.n_inst, tu.fk_id_inst, tu.id_turma, tu.n_turma, count(fk_id_turma) as 'ttalunos', "
                    . " tu.fk_id_ciclo, pl.at_pl "
                    . " from ge_turma_aluno ta "
                    . " left join ge_turmas tu on tu.id_turma = ta.fk_id_turma "
                    . " left join ge_periodo_letivo pl on pl.id_pl = tu.fk_id_pl "
                    . " left join instancia i on i.id_inst = tu.fk_id_inst "
                    . " where pl.at_pl in ('1','0') "
                    . $inst
                    . " and fk_id_turma is not null "
                    . $turma
                    . " and ta.situacao in ('frequente') "
                    . " group by tu.fk_id_inst, tu.id_turma "
                    . " order by tu.fk_id_inst, tu.id_turma ";
            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($array as $v) {
                $ttmatric[$v['id_turma']] = $v;  // atribui a turma como índice do array
            }

            if (!empty($id_inst)) {
                $inst = " and gr.fk_id_inst = $id_inst ";
            } else {
                $inst = null;
            }

            $sql =    " select gr.fk_id_inst, gr.fk_id_turma, gr.fk_id_gl, ga.n_gl, ga.abrev_gl, ga.fk_id_disc, ga.ciclos, ga.escolas, gr.avaliador,"
                    . " count(gr.id_resp) as 'ttresp' from global_respostas gr "
                    . " left join global_aval ga on ga.id_gl = gr.fk_id_gl  "
                    . " left join global_agrupamento ag on ag.id_agrup = ga.fk_id_agrup "
                    . " left join instancia i on gr.fk_id_inst = i.id_inst "
                    . " where gr.nfez is null"
                    . $inst
                    . $turma
                    . " and ag.id_agrup in ('$id_agrup')"
                    . " and fk_id_gl in ('$id_gl') "
                    . " group by gr.fk_id_inst, gr.fk_id_turma, gr.fk_id_gl"
                    . " order by gr.fk_id_gl, i.n_inst, gr.fk_id_turma ";

            $query = pdoSis::getInstance()->query($sql);
            $array = $query->fetchAll(PDO::FETCH_ASSOC);

            if (empty($array)) {
                ?>
                <div style="color: black; font-weight: bold; font-size: 18px" class="alert alert-warning text-center">

                    Não existem lançamentos para esta Avaliação / Escola / Turma
                </div>
                <?php
                exit();
            }

            $instvalid = explode('|', current($array)['escolas']);

            foreach ($array as $k => $v) {
                $arr_iciclos = explode(',', $v['ciclos']);

                    foreach ($arr_iciclos as $key => $value) {
                        $ciclos = $value;

                        if ((in_array($v['fk_id_inst'], $instvalid)) && ($ciclos == $ttmatric[$v['fk_id_turma']]['fk_id_ciclo'])) {
                            $qtdAval[$v['fk_id_inst']][$k] = $v;
                            $qtdAval[$v['fk_id_inst']][$k]['n_inst'] = $ttmatric[$v['fk_id_turma']]['n_inst'];
                            $qtdAval[$v['fk_id_inst']][$k]['avaliador'] = $v['avaliador'];
                            $qtdAval[$v['fk_id_inst']][$k]['n_turma'] = $ttmatric[$v['fk_id_turma']]['n_turma'];
                            $qtdAval[$v['fk_id_inst']][$k]['ttalunos'] = $ttmatric[$v['fk_id_turma']]['ttalunos'];
                            $qtdAval[$v['fk_id_inst']][$k]['porc'] = round(($v['ttresp']) / ($qtdAval[$v['fk_id_inst']][$k]['ttalunos']), 3) * 100; // . "%";
                            if ($qtdAval[$v['fk_id_inst']][$k]['porc'] > 100) {
                                $qtdAval[$v['fk_id_inst']][$k]['porc'] = 100 . "%";
                            } else {
                                $qtdAval[$v['fk_id_inst']][$k]['porc'] = $qtdAval[$v['fk_id_inst']][$k]['porc'] . "%";
                            }
                            $qtdAval[$v['fk_id_inst']][$k]['dif'] = ($qtdAval[$v['fk_id_inst']][$k]['ttalunos']) - ($v['ttresp']);
                            if ($qtdAval[$v['fk_id_inst']][$k]['dif'] < 0) {
                                $qtdAval[$v['fk_id_inst']][$k]['dif'] = 0;
                            }
                            $ndif = $qtdAval[$v['fk_id_inst']][$k]['dif'];
                            $ncor = ($ndif < 2 ? "glyphicon glyphicon-ok-circle" : ($ndif < 4 ? "glyphicon glyphicon-exclamation-sign" : "glyphicon glyphicon-remove-circle" ));
                            $qtdAval[$v['fk_id_inst']][$k]['ncor'] = $ncor;
                            $ncor2 = ($ndif < 2 ? "green" : ($ndif < 4 ? "yellow" : "red" ));
                            $qtdAval[$v['fk_id_inst']][$k]['ncor2'] = $ncor2;
                        }
                    }
            }

            foreach ($qtdAval as $v) {
                ?>
                <table class="table table-bordered table-striped table-hover">

                    <tr>
                        <td colspan="7">
                            <?php
                            echo current($v)['n_inst'] . ' (' . current($v)['fk_id_inst'] . ')';
                            echo '<br />';
                            echo current($v)['n_gl'] . ' (' . current($v)['fk_id_gl'] . ')';
                            echo '<br />';
                            echo current($v)['abrev_gl'] . ' (' . current($v)['fk_id_disc'] . ')';
                            ;
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Turma
                        </td>
                        <td>
                            Avaliador
                        </td>
                        <td>
                            Matriculados
                        </td>
                        <td>
                            Lançamentos
                        </td>
                        <td>
                            Porcentual
                        </td>
                        <td>
                            Não Lançados
                        </td>   
                        <td>

                        </td>                        
                    </tr>
                    <?php
                    foreach ($v as $y) {
                        ?>  
                        <tr>
                            <td>
                                <?php echo $y['n_turma']; ?>
                            </td>
                            <td>
                                <?php echo $y['avaliador']; ?>
                            </td>                            
                            <td>
                                <?php echo $y['ttalunos']; ?>
                            </td>
                            <td>
                                <?php echo $y['ttresp']; ?>
                            </td>
                            <td>
                                <?php echo $y['porc']; ?>
                            </td>
                            <td>
                                <?php echo $y['dif']; ?>
                            </td> 
                            <td style="width: 30px; text-align: center">
                                <span style="color: <?php echo $y['ncor2']; ?>" class="<?php echo $y['ncor']; ?>" aria-hidden="true"></span>

                            </td> 
                        </tr>             

                        <?php
                    }
                    ?>

                </table>

                <?php
            }
        }
        ?>
    </div>