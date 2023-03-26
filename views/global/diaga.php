<?php
$id_gl = @$_POST['id_gl'];
@$id_agrup = @$_POST['id_agrup'];
$hidden['id_agrup'] = @$id_agrup;
$professor = @$_POST['professor'];
$id_turma = @$_POST['id_turma'];
?>
<style>
    body {
        -webkit-print-color-adjust: exact;
    }
</style>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php
$disc = sql::idNome('ge_disciplinas');
if (empty($_POST['periodoLetivo'])) {
    $periodoLetivo = sql::get('ge_setup', 'fk_id_pl', ['id_set' => 1], 'fetch')['fk_id_pl'];
} else {
    $periodoLetivo = $_POST['periodoLetivo'];
}
$cor = [
    1 => 'green',
    2 => 'orange',
    3 => 'yellow',
    4 => 'red'
];
if ($_SESSION['userdata']['id_nivel'] == 8) {
    $id_inst = tool::id_inst();
} else {
    $id_inst = @$_POST['id_inst'];
}
?>
<div class="fieldBody">
    <div class="fieldTop">
        Diagnóstica
    </div>
    <br /><br />

    <?php
        if (!empty($_POST['professor'])) {
            ?>
            <div style="text-align: center; font-size: 18px; font-weight: bold">
                <?php echo @$_POST['escola'] ?> - <?php echo @$_POST['classeDisc'] ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="btn btn-info" href="<?php echo HOME_URI ?>/global/classeprof">Voltar</a>
            </div>
            <br /><br />
            <?php
        } else {
            ?>
            <div class="row">
                <div class="col-md-6">
                    <?php
                    if ($_SESSION['userdata']['id_nivel'] == 8) {
                        $where = ['ativo' => 1, '>' => 'n_agrup', 'tipo' => 'normal'];
                    } else {
                        $where = ['>' => 'n_agrup'];
                    }
                    $agrup = sql::idNome('global_agrupamento', @$where);
                    formulario::select('id_agrup', $agrup, 'Agrupamento', @$id_agrup, 1);
                    ?>
                </div>
            </div>
            <br /><br />
            <div class="row">
                <div class="col-md-3">
                    <?php
                    $per = gtMain::periodosPorSituacao();
                    formulario::select('periodoLetivo', $per, 'Período Letivo', @$periodoLetivo, 1);
                    $hidden['periodoLetivo'] = @$periodoLetivo;
                    ?>
                </div>
                <?php
                if ($_SESSION['userdata']['id_nivel'] == 8) {
                    $id_inst = tool::id_inst();
                } else {
                    ?>
                    <div class="col-md-5">
                        <?php
                        formulario::select('id_inst', escolas::idInst(), 'Escola', @$id_inst, 1, @$hidden);
                        ?>
                    </div>
                    <?php
                }
                $hidden['id_inst'] = $id_inst;
                ?>
                <div class="col-md-4">
                    <form method="POST">
                        <table>
                            <tr>
                                <td>
                                    <?php
                                    if (!empty($id_inst)) {
                                        $turmas = turma::option($id_inst, $periodoLetivo, 'fk_id_inst', '1,2,3,4,5,6,7,8,9,25,26');
                                        formulario::select('id_turma', $turmas, 'Turma', @$id_turma, NULL, $hidden);
                                        $hidden['id_turma'] = @$id_turma;
                                    }
                                    ?>   
                                </td>
                                <td>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                </td>
                                <td>
                                    <input class="btn btn-success" type="submit" value="Buscar" />   
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
            <br /><br />
            <?php
        }
        if (!empty($id_turma)) {
            $sql = "SELECT fk_id_pessoa FROM `ge_turma_aluno` WHERE `fk_id_turma` = " . $id_turma;
            $query = $model->db->query($sql);
            $alu = $query->fetchAll();
            foreach ($alu as $v) {
                $id_pessoas[] = $v['fk_id_pessoa'];
            }
            if(!empty($id_agrup)){
            $sql = "SELECT "
                    . " g.`fk_id_gl`,g.`fk_id_pessoa`,g.`nfez`,g.`q01`,g.`q02`,g.`q03`,g.`q04`,g.`q05`,g.`q06`,g.`q07`,g.`q08`,g.`q09`, g.escrita as escrita, a.fk_id_disc, "
                    . " a.escrita as ne, a.ciclos, "
                    . " a.escrita as ne, g.escrita as escrita "
                    . " FROM global_respostas g "
                    . " left join global_aval a on a.id_gl = g.fk_id_gl "
                    . " join global_agrupamento ag on ag.id_agrup = a.fk_id_agrup "
                    . " WHERE g.`fk_id_pessoa` in (" . implode(',', $id_pessoas) . ") "
                    . " and ag.id_agrup = $id_agrup "
                    . " order by n_gl";
            } else {
                $sql = "SELECT "
                    . " g.`fk_id_gl`,g.`fk_id_pessoa`,g.`nfez`,g.`q01`,g.`q02`,g.`q03`,g.`q04`,g.`q05`,g.`q06`,g.`q07`,g.`q08`,g.`q09`, g.escrita as escrita, a.fk_id_disc, "
                    . " a.escrita as ne, a.ciclos, "
                    . " a.escrita as ne, g.escrita as escrita "
                    . " FROM global_respostas g "
                    . " left join global_aval a on a.id_gl = g.fk_id_gl "
                    . " join global_agrupamento ag on ag.id_agrup = a.fk_id_agrup "
                    . " WHERE g.`fk_id_pessoa` in (" . implode(',', $id_pessoas) . ") "
                    . " and a.id_gl = $id_gl "
                    . " order by n_gl";
            }
            $query = $model->db->query($sql);
            $a = $query->fetchAll();
            if (!empty($a)) {
                foreach ($a as $v) {
                    if (!empty($v['ne'])) {
                        @$totalEscrita[$v['escrita']] ++;
                        $escrita[$v['fk_id_pessoa']] = $v['escrita'];
                    }
                    $av[$v['fk_id_gl']] = $v['fk_id_gl'];
                    $aval[$v['fk_id_pessoa']][$v['fk_id_gl']] = $v;

                    for ($c = 1; $c <= 50; $c++) {
                        if (!empty($v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)])) {
                            @$quest[$v['fk_id_gl']][$c][$v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)]] ++;
                        }
                    }
                }
                $sql = "SELECT * FROM `global_aval` "
                        . " WHERE `id_gl` in  (" . implode(',', $av) . ") ";
                $query = $model->db->query($sql);
                $avali = $query->fetchAll();
                foreach ($avali as $w) {
                    $avaliacao[$w['id_gl']] = $w['n_gl'];
                }
                $sql = "SELECT * FROM `global_descritivo` "
                        . " WHERE `aval` in  (" . implode(',', $av) . ") ";
                $query = $model->db->query($sql);
                $d = $query->fetchAll();
                if (empty($d[0]['descricao'])) {
                    tool::alertModal('Esta Avaliação não é compatível com este gráfico');
                    exit();
                }
                foreach ($d as $v) {
                    $devol[$v['aval']][$v['num']][$v['valor']]['questao'] = $v['questao'];
                    $devol[$v['aval']][$v['num']][$v['valor']]['titulo'] = $v['titulo'];
                    $devol[$v['aval']][$v['num']][$v['valor']]['valorNominal'] = $v['valorNominal'];
                    $devol[$v['aval']][$v['num']][$v['valor']]['descricao'] = $v['descricao'];
                }
                if (!empty($quest)) {
                    foreach ($quest as $k => $v) {

                        for ($c = 1; $c <= count($v); $c++) {

                            @$titulo = $devol[$k][$c][1]['titulo'];
                            if (!empty($quest[$k][$c])) {
                                foreach ($v as $ky => $y) {
                                    @$graf[$avaliacao[$k]][$titulo][$devol[$k][$c][$ky]['descricao']] = $quest[$k][$c][$ky];
                                }
                            }
                        }
                    }
                }

                $alu = alunos::listar($id_turma, 'id_pessoa, n_pessoa, dt_nasc, deficiencia');

                foreach ($alu as $v) {
                    $aluno[$v['id_pessoa']]['nome'] = $v['n_pessoa'];
                    $aluno[$v['id_pessoa']]['apd'] = @$v['deficiencia'] == 1 ? 'Sim' : 'Não';
                    $aluno[$v['id_pessoa']]['idade'] = data::idade($v['dt_nasc']);
                }
                ?>
                <?php
                $id_glOld = NULL;
                foreach ($aluno as $k => $v) {
                    ?>
                    <div class="fieldBorder2" style="padding: 15px" >
                        <table   style="width: 100%">
                            <tr>
                                <td style="width: 20%" valign="top">
                                    <br /><br /><br />
                                    <div style="text-align: center">
                                        <?php
                                        if (file_exists(ABSPATH . "/pub/fotos/" . $k . ".jpg")) {
                                            ?>
                                            <img src="<?php echo HOME_URI ?>/pub/fotos/<?php echo $k ?>.jpg" width="150" height="180" alt="foto"/>
                                            <?php
                                        } else {
                                            ?>
                                            <img src="<?php echo HOME_URI ?>/pub/fotos/anonimo.png" width="150" height="180" alt="foto"/>
                                            <?php
                                        }
                                        ?>  
                                    </div>
                                    <br /><br />
                                    Nome: <?php echo $v['nome'] ?>

                                    <br /><br />
                                    Idade: <?php echo $v['idade'] ?>
                                    <br /><br />
                                    <?php
                                    if (!empty($escrita[$k])) {
                                        ?>
                                        Nível de Escrita: <?php echo $escrita[$k] ?>
                                        <br /><br />
                                        <?php
                                    }
                                    ?>

                                    APD: <?php echo @$v['apd'] ?>
                                </td>
                                <td>
                                    <table class="table table-bordered table-hover table-responsive table-striped">
                                        <?php
                                        if (!empty($aval[$k])) {
                                            foreach ($aval[$k] as $v) {
                                                if ($id_glOld != $v['fk_id_gl']) {
                                                    ?>
                                                    <tr>
                                                        <td colspan="3">
                                                            <br /><br /><br /><br />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" style="text-align: center; font-weight: bold; font-size: 25px">
                                                            <?php echo @$disc[$v['fk_id_disc']] ?>
                                                            <br />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2" style="padding: 5px; font-weight: bold">
                                                            Descritivo
                                                        </td>
                                                        <td style="padding: 5px; background-color: gainsboro; color: blue; font-weight: bold">
                                                            Habilidade
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                $id_glOld = $v['fk_id_gl'];
                                                for ($c = 1; $c <= 50; $c++) {
                                                    if (!empty($v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)])) {
                                                        ?>
                                                        <tr style="font-weight: bold;  border-radius: 5px">
                                                            <td>
                                                                <div style="border-radius: 20px;width: 20px;height: 20px; background-color: <?php echo $cor[$v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)]]; ?>;color: <?php echo $cor[$v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)]]; ?>">
                                                                    <?php echo $v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)] ?>	
                                                                </div>
                                                            </td>
                                                            <td style="padding: 5px">
                                                                <?php echo $devol[$v['fk_id_gl']][$c][$v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)]]['descricao'] ?>:
                                                            </td>
                                                            <td style="padding: 5px; color: blue">
                                                                <?php echo $devol[$v['fk_id_gl']][$c][$v['q' . str_pad($c, 2, "0", STR_PAD_LEFT)]]['titulo'] ?>:
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </table>
                                    <br />
                                </td>
                            </tr>
                        </table>
                    </div>
                    <br /><br /><br />
                    <?php
                }
                ?>
            </div>
            <?php
        } else {
            tool::alertModal("Não há dados referente a esta consulta");
            exit();
        }
    }
    