<?php
$hiddenKey = DB::hiddenKey('ge_turma_aluno', 'replace')
?>
<br /><br />
<div class="panel panel-default">
    <div class="panel panel-heading">
        Vida escolar do(a) aluno(a) <?php echo $dados['n_pessoa'] . ', RSE:' . @$dados['id_pessoa'] ?>
    </div>
    <div class="panel panel-body">
        <div class="row">
            <div class="col-md-5">
                <?php
                $aluno_ = new aluno($id);
                $aluno_->vidaEscolar(NULL, tool::id_inst());

                if (!empty($aluno_->_escola)) {
                    ?>
                    <div class="alert alert-info">
                        <table style="width: 100%">
                            <tr>
                                <td>
                                    <div style="text-align: center; font-size: 18px">
                                        <?php echo $aluno_->_periodo_letivo; ?>
                                    </div>
                                    <br /><br />
                                    Escola:
                                    <?php
                                    echo $aluno_->_escola;
                                    ?>
                                    <br /><br />
                                    <?php
                                    if (empty($aluno_->_situacaoFinal)) {
                                        ?>
                                        Situação:
                                        <?php
                                        echo @$aluno_->_situacao;
                                    } else {
                                        ?>
                                        Situação Final:
                                        <?php
                                        echo @$aluno_->_situacaoFinal;
                                    }
                                    ?>
                                    <br /><br />
                                    D. Nasc.:
                                    <?php echo @data::converteBr($aluno_->_nasc) ?>
                                    <br /><br />
                                    Classe: 
                                    <?php echo @$aluno_->_nome_classe ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <?php
                                    if ($aluno_->_id_inst == tool::id_inst()) {
                                        ?>
                                        <button onclick="document.getElementById('turma').submit()" class="art-button">
                                            <?php echo $aluno_->_codigo_classe ?>
                                        </button>
                                        <?php
                                    } else {
                                        echo $aluno_->_codigo_classe;
                                    }
                                    ?>
                                    <br /><br />
                                    N. Chamada: 
                                    <?php echo @$aluno_->_chamada ?>

                                    <br /><br />
                                    Data de Matrícula: 
                                    <form method="POST">
                                        <table>
                                            <tr>
                                                <td>
                                                    <input type="text" name="1[dt_matricula]" value="<?php echo @$aluno_->_dt_matricula ?>" />
                                                </td>
                                                <td>
                                                    <?php echo $hiddenKey ?>
                                                    <input type="hidden" name="novo" value="1" />
                                                    <input type="hidden" name="aba" value="esc" />
                                                    <input type="hidden" name="id_pessoa" value="<?php echo @$id ?>" />
                                                    <input type="hidden" name="1[id_turma_aluno]" value="<?php echo $aluno_->_id_turma_aluno ?>" />
                                                    <input type="submit" value="Alterar" />
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                    <br />
                                    <?php
                                    if ($aluno_->_situacao <> "Frequente") {
                                        ?>
                                        Data de Transferência: 

                                        <form method="POST">
                                            <table>
                                                <tr>
                                                    <td>
                                                        <input type="text" name="1[dt_transferencia]" value="<?php echo @$aluno_->_dt_transferencia ?>" />
                                                    </td>
                                                    <td>
                                                        <?php echo $hiddenKey ?>
                                                        <input type="hidden" name="novo" value="1" />
                                                        <input type="hidden" name="aba" value="esc" />
                                                        <input type="hidden" name="id_pessoa" value="<?php echo @$id ?>" />
                                                        <input type="hidden" name="1[id_turma_aluno]" value="<?php echo $aluno_->_id_turma_aluno ?>" />
                                                        <input type="submit" value="Alterar" />
                                                    </td>
                                                </tr>
                                            </table>
                                        </form>
                                        <br />
                                        <?php
                                    }
                                    if (!empty($aluno_->_origem_escola)) {
                                        ?>
                                        Escola de Origem: 
                                        <?php echo @$aluno_->_origem_escola ?>
                                        <br /><br />
                                        <?php
                                    }
                                    if (!empty($aluno_->_destino_escola)) {
                                        ?>
                                        Escola de Destino: 
                                        <?php echo @$aluno_->_destino_escola ?>
                                        <br /><br />
                                        <?php
                                    }
                                    ?>
                                </td>
                                <td style="width: 200px">

                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php
                }
                if (!empty($aluno_->_outrosRegistros)) {

                    foreach ($aluno_->_outrosRegistros as $classeAluno) {
                        ?>
                        <div class="alert alert-warning">
                            <div style="text-align: center; font-size: 18px">
                                <?php echo $classeAluno['periodo_letivo']; ?>
                            </div>
                            <br /><br />
                            Escola:
                            <?php
                            echo $classeAluno['n_inst'];
                            ?>
                            <br /><br />
                            <?php
                            if (empty($classeAluno['situacao_final'])) {
                                ?>
                                Situação:
                                <?php
                                echo @$classeAluno['situacao'];
                            } else {
                                ?>
                                Situação Final:
                                <?php
                                echo @$classeAluno['n_sf'];
                            }
                            ?>
                            <br /><br />
                            D. Nasc.:
                            <?php echo @data::converteBr($aluno_->_nasc) ?>
                            <br /><br />
                            Classe: 
                            <?php echo @$classeAluno['n_turma'] ?>
                            <br /><br />
                            N. Chamada: 
                            <?php echo @$classeAluno['chamada'] ?>
                            <br /><br />
                            Data de Matrícula: 
                            <?php echo data::converteBr($classeAluno['dt_matricula']) ?>
                            <br />
                            <?php
                            if (!empty($classeAluno['dt_transferencia'])) {
                                ?>
                                Data de Transferência: 
                                <?php echo $classeAluno['dt_transferencia'] <> '0000-00-00' ? data::converteBr($classeAluno['dt_transferencia']) : NULL; ?>
                                <br />
                                <?php
                            }
                            if (!empty($classeAluno['origem_escola'])) {
                                ?>
                                Escola de Origem: 
                                <?php echo @$classeAluno['origem_escola'] ?>
                                <br /><br />
                                <?php
                            }
                            if (!empty($classeAluno['destino_escola'])) {
                                ?>
                                Escola de Destino: 
                                <?php echo @$classeAluno['destino_escola'] ?>
                                <br /><br />
                                <?php
                            }
                            ?>
                        </div>
                        <?php
                    }
                }
                ?>
                <form id="turma" action="<?php echo HOME_URI . '/gestao/manutencaoclasse' ?>" method="POST">
                    <input type="hidden" name="turma" value="<?php echo $aluno_->_id_turma ?>" />
                </form>
            </div>
            <div class="col-md-2 text-center" style="font-size: 20px; padding-left: 30px; padding-right: 30px">
                <?php
                if (@$aluno_->_situacaoAtual != 'Frequente') {
                     if (!in_array(tool::id_inst(), [28, 49])) {
                    ?>  
                    <div>   
                        <form method="POST">
                            <input type="hidden" name="novo" value="1" />
                            <input type="hidden" name="aba" value="esc" />
                            <input type="hidden" name="id_pessoa" value="<?php echo @$id ?>" />
                            <input style="width: 100%; font-weight: 900" class="btn btn-success" type="submit" name="Matricular" value="Matricular" />
                        </form>
                    </div>
                    <?php
                     }
                } elseif (tool::id_inst() == $aluno_->_id_inst) {
                    ?>
                    <br />
                    <div style="display: ">   
                        <form method="POST">
                            <input type="hidden" name="novo" value="1" />
                            <input type="hidden" name="aba" value="esc" />
                            <input type="hidden" name="id_turma_aluno" value="<?php echo $aluno_->_id_turma_aluno ?>" />
                            <input type="hidden" name="turma" value="<?php echo $aluno_->_id_turma ?>" />
                            <input type="hidden" name="id_pessoa" value="<?php echo @$id ?>" />
                            <input type="hidden" name="id_pessoa_chamada" value="<?php echo $aluno_->_rse ?>|<?php echo $aluno_->_chamada ?>|<?php echo $aluno_->_nome ?>|<?php echo $aluno_->_codigo_classe ?>|<?php echo tool::id_inst() ?>|<?php echo $aluno_->_situacao ?>" />
                            <input style="width: 100%; font-weight: 900" class="btn btn-success" type="submit" name="mudarSituacao" value="Mudar Situação" />
                        </form>
                    </div>
                    <?php
                }
                ?>

                <br />
                <div>   
                    <form action="<?php echo HOME_URI ?>/hist/histpessoa" method="POST">
                        <input type="hidden" name="id_pessoa" value="<?php echo @$id ?>" />
                        <input style="width: 100%; font-weight: 900" class="btn btn-success" type="submit" value="Historico" />
                    </form>
                </div>
                <br />
                <div>
                    <?php
                    if (@$aluno_->_id_curso == 1) {
                        if ($apd_a = ($model->verificaalunoapd(@$aluno_->_rse)) == 1) {
                            $tano = 'apd';
                        } else {
                            if (substr(@$aluno_->_nome_classe, 0, 1) <= 5) {
                                $tano = 15;
                            } else {
                                $tano = 69;
                            }
                        }
                        ?>
                        <form target="_blank" action="https://sieb.educ.net.br/hab/aval/pdfboletim" method="POST">
                            <input type="hidden" name="t" value="<?php echo $tano ?>" />
                            <input type="hidden" name="id_turma" value="<?php echo @$aluno_->_id_turma ?>" />
                            <input type="hidden" name="id_pessoa" value="<?php echo @$aluno_->_rse ?>" />
                            <input type="hidden" name="id_inst" value="<?php echo @$aluno_->_id_inst ?>" />
                            <input style="width: 100%; font-weight: 900" class="btn btn-success" type="submit" value="Boletim" />
                        </form>
                        <?php
                    }
                    ?>
                </div>
            </div> 
            <div class="col-md-2 text-center">

            </div>
            <div class="col-md-3 text-center" style="font-size: 20px">

                <?php
                if (file_exists(ABSPATH . "/pub/fotos/" . $dados['id_pessoa'] . ".jpg")) {
                    ?>
                    <img src="<?php echo HOME_URI ?>/pub/fotos/<?php echo $dados['id_pessoa'] ?>.jpg?ass=<?php echo uniqid() ?>" width="199.2" height="240" alt="foto"/>
                    <?php
                }
                ?>
            </div>
        </div>
        <div class="col-md-12 ">

            <?php
            if (!empty($_POST['Matricular'])) {
                tool::modalInicio();
                ?>
                <div class="row">
                    <div class="col-md-5">
                        <?php
                        $id_pl = @$_POST['id_pl'];
                        $per = gtMain::periodosPorSituacao([1, 2]);

                        formulario::select('id_pl', $per, 'Período Letivo', @$periodoLetivo, 1, ['id_pessoa' => $id, 'novo' => 1, 'aba' => 'esc', 'Matricular' => 1, 'id_pl' => $id_pl]);
                        ?>
                    </div>
                    <div class="col-md-4">
                        <?php
                        if (!empty($id_pl)) {
                            $options = turmas::option(NULL, NULL, NULL, $id_pl);
                            formulario::select('id_turma', $options, 'Classe: ', $_POST['id_turma'], 1, ['id_pessoa' => $id, 'novo' => 1, 'aba' => 'esc', 'Matricular' => 1, 'id_pl' => $id_pl]);
                        }
                        ?>
                    </div>
                </div>
                <br /><br /><br />
                <?php
                if (!empty($_POST['id_turma'])) {
                    $ativo = gtMain::periodos();
                    ?>
                    <div class="row">
                        <form method="POST">
                            <div class="col-lg-10">
                                <?php
                                formulario::input('1[origem_escola]', 'Escola de Origem');
                                ?>
                                <input type="hidden" name="1[fk_id_turma]" value="<?php echo $_POST['id_turma'] ?>" />
                                <input type="hidden" name="1[situacao]" value="Frequente" />
                                <input type="hidden" name="1[turma_status]" value="M" />
                                <input type="hidden" name="1[dt_matricula]" value="<?php echo date("Y-m-d") ?>" />
                                <input type="hidden" name="1[fk_id_inst]" value="<?php echo tool::id_inst() ?>" />
                                <input type="hidden" name="1[fk_id_pessoa]" value="<?php echo $id ?>" />
                                <input type="hidden" name="1[codigo_classe]" value="<?php echo turma::codigo($_POST['id_turma']) ?>" />
                                <input type="hidden" name="1[chamada]" value="<?php echo $model->ultimo_turmaNovaChamada($_POST['id_turma'])['chamada'] ?>" />
                                <input type="text" name="1[periodo_letivo]" value="<?php echo $ativo[$id_pl] ?>" />
                                <input type="hidden" name="novo" value="1" />
                                <input type="hidden" name="aba" value="esc" />
                                <input type="hidden" name="id_pessoa" value="<?php echo @$id ?>" />
                                <?php echo DB::hiddenKey('ge_turma_aluno', 'replace') ?>
                            </div>
                            <div class="col-lg-2">
                                <button class="art-button" type="submit">
                                    Matricular
                                </button>
                            </div>
                        </form>
                    </div>
                    <?php
                }
                tool::modalFim();
            } elseif (!empty($_POST['mudarSituacao'])) {
                include ABSPATH . '/views/gestao/manutencaoclasse_mudarSituacao.php';
            }
            ?>
        </div>

    </div>
    <br />

</div>
</div>
