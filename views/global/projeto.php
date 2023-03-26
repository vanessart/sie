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
            Bimestrais - Relatórios
        </div>
        <div class="noprint">
            <br /><br />
            <div class="row">
                <div class="col-md-6">
                    <?php
                    if ($_SESSION['userdata']['id_nivel'] == 8) {
                        $where = ['ativo' => 1, '>' => 'n_agrup'];
                    } else {
                        $where = ['>' => 'n_agrup'];
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
            if (!empty($id_turma)) {
                $aba1 = 1;
            } else {
                $aba1 = 0;
            }
            $hidden['acessarGrafico'] = 1;
            $abas[1] = ['nome' => "Gráficos por Habilidades", 'ativo' => 1, 'hidden' => $hidden, 'link' => "",];
            $abas[2] = ['nome' => "Proficiência por Aluno", 'ativo' => $aba1, 'hidden' => $hidden, 'link' => "",];
            $abasSet = tool::abas($abas);
            ?>
        </div>
        <?php
        if (@$_POST['activeNav']) {
            include ABSPATH . '/views/global/_projeto/' . $abasSet . '.php';
        }
    }
    ?>
</div>
