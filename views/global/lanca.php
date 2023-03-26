<style>
    .imgExpande {
        list-style: none;
        padding: 0;
    }
    .imgExpande li {
        display: inline;
    }
    .imgExpande li img {
        width: 100px; /* Aqui é o tamanho da imagem */
        -webkit-transition: 1s all ease; /* É para pega no Chrome e Safira */
        -moz-transition: 1s all ease; /* Firefox */
        -o-transition: 1s all ease; /* Opera */
    }
    .imgExpande li img:hover {
        -moz-transform: scale(2);
        -webkit-transform: scale(2);
        -o-transform: scale(2);
    }

</style>
<?php
$id_turma = @$_POST['id_turma'];
if (empty($id_inst)) {
    $id_inst = @$_POST['id_inst'];
}
$id_gl = @$_POST['id_gl'];
$aval = sql::get('global_aval', 'id_gl, n_gl', ['ativo' => 1,'>'=>'n_gl']);
if (count($aval) == 1) {
    $id_gl = current($aval)['id_gl'];
}


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
foreach ($aval as $v) {
    // $dados[$v['id_gl']] = $v;
    $avalia[$v['id_gl']] = $v['n_gl'];
}
?>
<div class="fieldBody">
    <div class="fieldTop">
        Lançamento de Notas
    </div>
    <br /><br />
    <div class="row">
        <?php
        if (empty($_POST['professor'])) {
            if (count($aval) > 1) {
                ?>
                <div class="col-md-4">
                    <?php
                    formulario::select('id_gl', $avalia, 'Avaliação', @$id_gl, 1);
                    $hidden['id_gl'] = @$id_gl;
                    ?>
                </div>
                <?php
            } else {
                $hidden['id_gl'] = $id_gl = $aval[0]['id_gl'];
            }
            ?>

            <?php
            if (!empty($id_gl) && empty($escola)) {
                ?>
                <div class="col-md-5">
                    <?php
                    formulario::select('id_inst', $escolas, 'Escola', @$id_inst, 1, @$hidden);
                    $hidden['id_inst'] = $id_inst;
                    ?>
                </div>
                <?php
            }
            ?>

            <div class="col-md-3">
                <?php
                if (!empty($id_inst) && !empty($id_gl)) {
                    $turmas = turma::option($id_inst, NULL, 'fk_id_inst', $dados['ciclos']);
                    formulario::select('id_turma', $turmas, 'Turma', @$id_turma, 1, $hidden);
                    $hidden['id_turma'] = $id_turma;
                }
                ?>
            </div>
            <?php
        } else {
            $id_gl = $dados['id_gl'];
            ?>
            <div class="col-md-12 text-center">
                <a class="btn btn-info" href="<?php echo HOME_URI ?>/global/lancap">Voltar</a>
            </div>
            <?php
        }
        ?>
    </div>
    <br /><br />
    <div style="width: 90%; margin: 0 auto">
        <?php
        if (!empty($id_turma)) {
            $cor1 = '#DEDEDD';
            $cor2 = 'black';
            $turmaDados = gtTurmas::get($id_turma);
            ?>
            <div class="row">
                <div class="col-lg-12">
                    <div style="background-color: <?php echo $cor1 ?>; color: <?php echo $cor2 ?>; width: 100%; height: 100px;  border-radius: 20px; padding: 16px; text-align: center; font-size: 20px">
                        <?php echo $dados['n_gl'] ?>
                        <br /><br />
                        <?php echo $turmaDados['n_turma'] ?>
                    </div>
                </div>
            </div>
            <br /><br />
            <?php
            $alunos = alunos::listar($id_turma, 'id_pessoa, n_pessoa, sexo, chamada, situacao');
            foreach ($alunos as $l) {
                $lancados_[] = $l['id_pessoa'];
            }
            $sql = "select fk_id_pessoa from global_respostas "
                    . " where fk_id_pessoa in (" . implode(',', $lancados_) . ") "
                    . "and fk_id_gl = $id_gl";
            $query = $model->db->query($sql);
            $array = $query->fetchAll();
            foreach ($array as $vl) {
                $lancados[] = $vl['fk_id_pessoa'];
            }

            @$porcent = ((count(@$lancados) + 1) / count(@$alunos)) * 100;
            foreach ($alunos as $k => $v) {
                @$alunos[$k]['porcent'] = intval(@$porcent);
                $hidden['modal'] = 1;
                @$alunos[$k]['lanc'] = in_array($v['id_pessoa'], $lancados) ? 'btn btn-success' : 'btn btn-danger';
            }

            foreach ($alunos as $v) {
                ?>
                <a id="lin<?php echo $v['id_pessoa'] ?>"></a>
                <div colspan="2" style="background-color: <?php echo $cor1 ?>; border-radius: 60px 0px 0px 10px;">
                    <table style="width: 100%;">
                        <tr>
                            <td style="background-color: <?php echo $cor1 ?>; width: 100px; border-radius: 100px 0 0px 100px ; padding-top: 10px; padding-left: 10px" >
                                <ul class="imgExpande" >
                                    <li>
                                        <?php
                                        if (file_exists(ABSPATH . '/pub/fotos/' . $v['id_pessoa'] . '.jpg')) {
                                            $img = HOME_URI . '/pub/fotos/' . $v['id_pessoa'] . '.jpg';
                                        } else {
                                            $img = HOME_URI . "/pub/fotos/anonimo.png";
                                        }
                                        ?>
                                        <img src="<?php echo $img ?>" style="width: 100px; height: 100px; border-radius: 50px;  z-index: 999" alt="Foto"/>
                                    </li>
                                </ul>
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-2" style=" color: <?php echo $cor2 ?>; font-weight: bold; font-size: 16px; padding: 8px; padding-left: 50px">
                                        <form method="POST">
                                            <?php
                                            echo formulario::hidden($hidden);
                                            echo formulario::hidden($dados);
                                            echo formulario::hidden($v);
                                            ?>
                                            <input type="hidden" name="id_inst" value="<?php echo @$id_inst ?>" />
                                            <input type="hidden" name="id_turma" value="<?php echo @$_POST['id_turma'] ?>" />
                                            <input type="hidden" name="professor" value="<?php echo @$_POST['professor'] ?>" />
                                            <button name="btn<?php echo $v['id_pessoa'] ?>" style="border-radius: 20px" class="<?php echo $v['lanc'] ?>" type="submit" >
                                                <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span> Lançar
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-4" style=" color: <?php echo $cor2 ?>; font-weight: bold; font-size: 16px; padding: 8px; padding-left: 50px">
                                        <?php echo $v['chamada'] ?> - <?php echo $v['n_pessoa'] ?>
                                    </div>
                                    <div class="col-md-3" style="color: <?php echo $cor2 ?>; font-weight: bold; font-size: 16px; padding: 8px;">
                                        Situação: <?php echo $v['situacao'] ?>
                                    </div>
                                    <div class="col-md-3" style="color: <?php echo $cor2 ?>; font-weight: bold; font-size: 16px; padding: 8px;">
                                        RSE/ID: <?php echo $v['id_pessoa'] ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <br /><br />
                <?php
            }
        }
        ?>
    </div>
    <div class="col-md-8">
        <?php
        if (!empty($_POST['modal'])) {
            include ABSPATH . '/views/global/lancaa.php';
        }
        ?>
    </div>
</div>