<?php
@$idTurma = $_POST['turma'];
?>

<div class="fieldBody">
    <div class="fieldTop">
        Alocação de Alunos
    </div>
    <?php
    $periodo = '27';
    $turmaOptions = turma::option(tool::id_inst(), $periodo);
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <?php
                formulario::select('turma', $turmaOptions, 'Selecione Código da Classe:', @$id_turma, 1, ['codclasse' => 'turma']);
                ?>
            </div>
        </div>
    </div>
    <br />

    <?php
    if (!empty($idTurma)) {
        
        $aloca = $model->pegaalunoalocacao();
        ?>
        <div class="container">
            <div class ="row">
                <div class ="col-md-6">
                    Selecionar Aluno
                    <form method="POST">
                        <div style="width: 100%;overflow: auto;height: 350px">  
                            <?php
                            $ciclo = $model->pegaciclo($idTurma);
                            $turma = sql::get('ge_aloca_aluno', '*', 'WHERE fk_id_inst=' . tool::id_inst() . " AND sg_ciclo_futuro ='" . $ciclo . "' AND status = 0 AND fk_id_pl  = 27 ORDER BY aluno");
                            ?>

                            <table class="table table-striped table-hover" style="font-weight: bold">
                                <tr>
                                    <td>
                                        RSE
                                    </td>
                                    <td>
                                        Nome Aluno
                                    </td>
                                    <td>
                                        Data Nasc.
                                    <td>
                                        Todos <input type="checkbox" name="chkAll" onClick="checkAll(this)" />
                                    </td>
                                </tr>
                                <?php
                                foreach ($turma as $k => $v) {
                                    $v[$k]['tur'] = formulario::checkboxSimples('sel[]', $v['fk_id_pessoa'], NULL, NULL, 'id="' . $v['fk_id_pessoa'] . '"');
                                }

                                foreach ($turma as $w) {
                                    ?>
                                    <tr>
                                        <td style="text-align: left">
                                            <?php echo $w['fk_id_pessoa'] ?>
                                        </td>
                                        <td style="text-align: left">
                                            <?php echo $w['aluno'] ?>
                                        </td>
                                        <td style="text-align: left">
                                            <?php echo data::converteBr($w['dt_aluno']) ?>
                                        </td>
                                        <td>
                                            <input class="checksel" type="checkbox" name="sel[]" value="<?php echo $w['fk_id_pessoa'] ?>" />
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>

                            </table>
                        </div>

                        <input type="hidden" name="alocacao" value="1" />
                        <input type="hidden" name="turma" value="<?php echo $idTurma ?>" /> 
                        <input type="hidden" name="codturma" value="<?php echo $idTurma ?>" />
                        <br />
                        <input style="width: 92%; font-weight: 900" class="art-button" type="submit" value="Matricular Aluno" />
                    </form>
                </div>
                <div class ="col-md-6">
                    Alunos Matriculados
                    <form method="POST">
                        <div style="width: 100%;overflow: auto;height: 350px"> 

                            <?php
                            $sala = $model->pegaalunosmatriculados($idTurma);
                            ?>
                            <table class = "table table-striped table-hover" style = "font-weight: bold">
                                <tr>
                                    <td>
                                        RSE
                                    </td>
                                    <td>
                                        Nome Aluno
                                    </td>
                                    <td>
                                        Data Nasc.
                                    <td>
                                        Todos <input type = "checkbox" name = "chkAll2" onClick = "checkAll2(this)" />
                                    </td>
                                </tr>
                                <?php
                                foreach ($sala as $kk => $vv) {
                                    $id_turma = $vv['fk_id_turma'];
                                    $vv[$kk]['tur'] = formulario::checkboxSimples('sel2[]', $vv['fk_id_pessoa'], NULL, NULL, 'id="' . $vv['fk_id_pessoa'] . '"');
                                }

                                foreach ($sala as $ww) {
                                    ?>
                                    <tr>
                                        <td style="text-align: left">
                                            <?php echo $ww['fk_id_pessoa'] ?>
                                        </td>
                                        <td style="text-align: left">
                                            <?php echo $ww['n_pessoa'] ?>
                                        </td>
                                        <td style="text-align: left">
                                            <?php echo data::converteBr($ww['dt_nasc']) ?>
                                        </td>
                                        <td>
                                            <input class="checkmat" type="checkbox" name="sel2[]" value="<?php echo $ww['fk_id_pessoa'] ?>" />
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                        </div>
                        <br />
                        <input type="hidden" name="turma" value="<?php echo $idTurma ?>" />
                        <input style="width: 41%; font-weight: 900" class="art-button" type="submit" name="excluir_aluno" value="Excluir Matrícula" /> 
                        <?php echo DB::hiddenKey('excluir_aluno') ?>

                        <button name = "lista" value = "Imprimir" onclick="document.getElementById('imprimir').submit()" style="width: 41%;  font-weight: 900" type="submit" class="art-button">
                            Lista Piloto
                        </button>
                    </form>

                    <form target="_blank" action="<?php echo HOME_URI ?>/gestao/lista_piloto" id="imprimir" method="POST">
                        <input type="hidden" name="turma" value="<?php echo $idTurma ?>" />
                        <input type="hidden" name="aloca" value="<?php echo "alocacao" ?>" />
                    </form>  
                </div> 
            </div> 
        </div> 
        <?php
    }
    ?>

</div>
<script>

    function checkAll(o) {
        var boxes = document.getElementsByClassName("checksel");
        for (var x = 0; x < boxes.length; x++) {
            var obj = boxes[x];
            if (obj.type == "checkbox") {
                if (obj.name != "chkAll")
                    obj.checked = o.checked;
            }
        }
    }
    function checkAll2(o) {
        var boxes = document.getElementsByClassName("checkmat");
        for (var x = 0; x < boxes.length; x++) {
            var obj = boxes[x];
            if (obj.type == "checkbox") {
                if (obj.name != "chkAll2")
                    obj.checked = o.checked;
            }
        }
    }
</script>