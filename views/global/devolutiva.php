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
    <div class="fieldTop">
        Devolutivas - Relatórios
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
                $agrup = sql::get('global_agrupamento','id_agrup, n_agrup, tipo' ,@$where);
                foreach ($agrup as $value) {
                    
                    $agrupx [$value['id_agrup']]=$value['n_agrup'];
                    $tipox [$value['id_agrup']]=$value['tipo'];                            
                }
                formulario::select('id_agrup', $agrupx, 'Agrupamento', @$id_agrup, 1);
                ?>
            </div>
        </div>
        <br /><br />
        <div class="row">
            <div class="col-md-4">
                <?php
                if (!empty(@$id_agrup) && !empty($avalia)) {
                    if ($_SESSION['userdata']['id_nivel'] == 8) {
                        $hidden['id_inst'] = tool::id_inst();
                    }
                    $hidden['acessarGrafico']=1;
                    formulario::select('id_gl', $avalia, 'Avaliação', @$id_gl, 1, $hidden);
                    ?>
                </div>
                <?php
                if ($_SESSION['userdata']['id_nivel'] != 8 && !empty($id_gl)) {
                    ?>
                    <form method="POST">
                        <div class="col-md-5">
                            <?php
                            formulario::select('id_inst', $escolas, 'Escola', @$id_inst, NULL, @$hidden);
                            $hidden['id_inst'] = $id_inst;
                            ?>
                        </div>

                        <div class="col-md-3">
                            <input class="btn btn-info" name="acessarGrafico" type="submit" value="Acessar Gráfico" />
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
   // if (!empty($_POST['acessarGrafico'])) {
        if (!empty($id_gl) && empty($id_inst)) {
            include ABSPATH . '/views/global/_devolutiva/graf.php';
        } else {
            include ABSPATH . '/views/global/_devolutiva/grafesc.php';
        }
    //}
    ?>
</div>
