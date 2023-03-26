<?php
$escola = sql::idNome('instancia', " where n_inst like 'emef%' or n_inst like 'emeief%' order by n_inst");
$id_inst = filter_input(INPUT_POST, 'id_inst', FILTER_SANITIZE_NUMBER_INT);
$id_turma = filter_input(INPUT_POST, 'id_turma', FILTER_SANITIZE_NUMBER_INT);
$btn = [1 => 'info', 2 => 'primary', 3 => 'success', 4 => 'danger', 5 => 'default'];
?>

<div class="fieldBody">
    <div class="fieldTop">
        Lançamento de Notas
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-8">
            <?php echo form::select('id_inst', $escola, 'Escola', $id_inst, 1) ?>
        </div>
        <div class="col-sm-4">
            <?php
            if (!empty($id_inst)) {
                $turma = turmas::Listar('1,2,3,4,5,6,7,8,9', NULL, NULL, NULL, NULL, $id_inst);
                $turma = tool::idName($turma, 'id_turma', 'n_turma');
                echo form::select('id_turma', $turma, 'Classe', $id_turma, 1, ['id_inst' => $id_inst]);
            }
            ?>
        </div>
    </div>
    <br /><br />
    <div class="row">
        <div class="col-sm-12">
            <?php
            if (!empty($id_turma)) {
                $dadoTurma = sql::get('ge_turmas', '*', ['id_turma' => $id_turma], 'fetch');
                $sql = "SELECT * FROM `global_aval` ga "
                        . " JOIN ge_disciplinas d on d.id_disc = ga.fk_id_disc "
                        . " WHERE `ciclos` LIKE '" . $dadoTurma['fk_id_ciclo'] . "' "
                        . " AND `ativo` = 1 "
                        . " ORDER BY ga.ordem ";
                $query = pdoSis::getInstance()->query($sql);
                $aval = $query->fetchAll(PDO::FETCH_ASSOC);
                foreach ($aval as $v) {
                    $idGl[$v['id_gl']] = $v['id_gl'];
                }
                $alunos = alunos::listar($id_turma);
                foreach ($alunos as $v) {
                    $idPessoa[$v['id_pessoa']] = $v['id_pessoa'];
                }
                $sql = "SELECT * FROM `global_respostas` "
                        . " WHERE `fk_id_gl` in (" . implode(',', $idGl) . ") "
                        . " AND `fk_id_pessoa` in (" . implode(',', $idPessoa) . ") ";
                $query = pdoSis::getInstance()->query($sql);
                $array = $query->fetchAll(PDO::FETCH_ASSOC);
                foreach ($array as $v) {
                    $fez[$v['fk_id_gl']][$v['fk_id_pessoa']] = $v['fk_id_pessoa'];
                }
                ?>
                <table class="table table-responsive table-striped table-hover table-bordered">
                    <tr>
                        <td>
                            Nº
                        </td>
                        <td style="width: 30%">
                            Aluno
                        </td>
                        <?php
                        foreach ($aval as $a) {
                            ?>
                            <td>
                                <?php echo $a['n_gl'] ?>
                            </td>
                            <?php
                        }
                        ?>
                        <td></td>
                    </tr>
                    <?php
                    foreach ($alunos as $v) {
                        ?>
                        <tr class="triii" id="tri<?php echo $v['id_pessoa'] ?>">
                            <td>
                                <a name="l<?php echo $v['id_pessoa'] ?>" id="l<?php echo $v['id_pessoa'] ?>"></a>
                                <?php echo $v['chamada'] ?>
                            </td>
                            <td style="font-size: 20px; font-weight: bold">
                                <?php echo $v['n_pessoa'] ?>
                            </td>
                            <?php
                            $numBtn = 1;
                            foreach ($aval as $a) {
                                ?>
                                <td>
                                    <form id="f<?php echo $v['id_pessoa'] . '_' . $a['id_gl'] ?>" target="lancamento" action="<?php echo HOME_URI ?>/global/lancabanca" method="POST">
                                        <?php
                                        echo form::hidden($v);
                                        echo form::hidden($a);
                                        if (empty($fez[$a['id_gl']])) {
                                            $fez[$a['id_gl']] = [];
                                        }
                                        ?>
                                        <input type="hidden" name="id_inst" value="<?php echo @$id_inst ?>" />
                                        <button onclick=" $('#myModal').modal('show');"  class="btn btn-<?php echo in_array($v['id_pessoa'], $fez[$a['id_gl']]) ? 'success' : 'default' ?>">
                                            <?php echo $a['n_disc'] ?>
                                        </button>
                                    </form>
                                </td>
                                <?php
                            }
                            ?>
                        </tr>
                        <?php
                    }
                    ?>
                </table>
                <?php
            }
            ?>
        </div>
    </div>
</div>
<?php
tool::modalInicio('width:100%', 1);
?>
<iframe id="lancamento"  style="width: 100%; height: 150vh; border: none" name="lancamento" src="<?php echo HOME_URI ?>/global/aguarde"></iframe>
<?php
tool::modalFim();
?>
