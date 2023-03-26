<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#modalMatric").modal('show');
    });
</script>
<div id="modalMatric" class="modal fade">
    <div class="modal-dialog" style="width: 50%">
        <div class="modal-content">
            <div class="modal-body">
                <div style="text-align: right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">X</button>
                </div>

                <?php
                if (!empty($_POST['last_id'])) {
                    $pessoa = pessoa::get($_POST['last_id']);
                    ?>
                    <div class="fieldTop">
                        Matricular <?php echo tool::sexoArt($pessoa['sexo']) ?> alun<?php echo tool::sexoArt($pessoa['sexo']) ?> <?php echo $pessoa['n_pessoa'] ?>
                    </div>
                    <br /><br /><br />
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-5">
                            <?php
                            $id_pl = @$_POST['id_pl'];
                            $id_ciclo = $model->setCiclo($pessoa['dt_nasc']);
                            $periodos = gtMain::periodosCiclos($id_ciclo);
                            if (!empty($periodos)) {
                                formulario::select('id_pl', $periodos, 'Período', $id_pl, 1, ['aba' => 1, 'id_vaga' => $id_vaga, 'matricular' => 1, 'last_id' => $_POST['last_id']]);
                            }
                            ?>
                        </div>
                        <div class="col-md-5">
                            <?php
                            if (!empty($id_pl)) {
                                //$options = turmas::option($id_ciclo, NULL, NULL, NULL, NULL, tool::id_inst());
                                $ativo = gtMain::periodos(1);
                                if(array_key_exists($id_pl, $ativo)){
                                $opt = gtTurmas::turmas(NULL, $id_pl, 'fk_id_inst', $id_ciclo, 't.id_turma, t.n_turma');
                                }elseif ($id_ciclo == 21) {
                                     $opt = gtTurmas::turmas(NULL, $id_pl, 'fk_id_inst', '21,22', 't.id_turma, t.n_turma');
                                }elseif ($id_ciclo == 22) {
                                     $opt = gtTurmas::turmas(NULL, $id_pl, 'fk_id_inst', 23, 't.id_turma, t.n_turma');
                                }elseif ($id_ciclo == 23) {
                                     $opt = gtTurmas::turmas(NULL, $id_pl, 'fk_id_inst', 24, 't.id_turma, t.n_turma');
                                }elseif ($id_ciclo == 24) {
                                     $opt = gtTurmas::turmas(NULL, $id_pl, 'fk_id_inst', 19, 't.id_turma, t.n_turma');
                                }
                                foreach ($opt as $v) {
                                    $options[$v['id_turma']] = $v['n_turma'];
                                }
                                if (!empty($options)) {
                                    formulario::select('id_turma', $options, 'Selecione a Classe', @$_POST['id_turma'], 1, ['aba' => 1, 'id_vaga' => $id_vaga, 'matricular' => 1, 'last_id' => $_POST['last_id'], 'id_pl' => $id_pl]);
                                    ?>
                                    <br /><br />
                                </div>
                                <?php
                                if (!empty($_POST['id_turma'])) {
                                    ?>

                                    <div class="col-lg-12 text-center">
                                        <form method="POST">
                                            <input type="hidden" name="aba" value="1" />
                                            <input type="hidden" name="id_vaga" value="<?php echo $id_vaga ?>" />
                                            <input type="hidden" name="1[fk_id_turma]" value="<?php echo $_POST['id_turma'] ?>" />
                                            <input type="hidden" name="1[situacao]" value="Frequente" />
                                            <input type="hidden" name="1[turma_status]" value="M" />
                                            <input type="hidden" name="1[dt_matricula]" value="<?php echo date("Y-m-d") ?>" />
                                            <input type="hidden" name="1[fk_id_inst]" value="<?php echo tool::id_inst() ?>" />
                                            <input type="hidden" name="1[fk_id_pessoa]" value="<?php echo $_POST['last_id'] ?>" />
                                            <input type="hidden" name="1[codigo_classe]" value="<?php echo turma::codigo($_POST['id_turma']) ?>" />
                                            <input type="hidden" name="1[chamada]" value="<?php echo turma::ultimo_turmaNovaChamada($_POST['id_turma'])['chamada'] ?>" />
                                            <input type="hidden" name="1[periodo_letivo]" value="<?php echo date("Y") ?>" />
                                            <?php echo DB::hiddenKey('ge_turma_aluno', 'replace') ?>
                                            <button class="btn btn-success" name="finalizar_matricula" value="1" type="submit">
                                                Matricular
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="alert alert-danger text-center">
                            Não há classe para a idade dessa criança
                        </div>

                        <?php
                    }
                }
            } else {
                ?>
                <div style="text-align: center; font-size: 20px">
                    Esta criança ja esta matriculada
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
</div>

