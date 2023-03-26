<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#myModal").modal('show');
    });
</script>
<div id="myModal" class="modal fade">
    <div class="modal-dialog" style="width: 90%">
        <div class="modal-content">

            <div class="modal-body">
                <div style="text-align: right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">X</button>
                </div>
                <?php
                javaScript::somenteNumero()
                ?>
                
                <form id="bt" style="display: " method="POST">

                    <div id="search" class="row" style="padding-left: 15px">
                        <div class="col-lg-6">
                            <br />
                            <?php echo formulario::input('nome', 'RSE', NULL, NULL, "required onkeypress='return SomenteNumero(event)'")
                            ?>
                        </div>
                        <br />
                        <div class="col-lg-3">

                            <input style="width: 200px" type="hidden" name="aba" value="cad" />

                            <button class="btn btn-success" style="width: 200px">
                                Buscar Dados
                            </button>
                        </div>
                       
                    </div>

                    <input type="hidden" name="turma" value="<?php echo $_POST['turma'] ?>" /> 
                    <input type="hidden" name="matricular_aluno" value="Matricular Aluno" />

                    <br /><br />
                    <?php
                    if (!empty($_POST['jamatriculado'])) {

                        $mat = $model->alunoMat($_POST['jamatriculado']);
                        ?>
                        <div style="padding-left: 170px" class="col-lg-11">

                            <?php
                            $form['array'] = $mat;
                            $form['fields'] = [
                                'RSE' => 'id_pessoa',
                                'Nome Aluno' => 'n_pessoa',
                                'Data Nasc.' => 'dt_nasc',
                                'Nome Escola' => 'n_inst',
                                'Cód. Classe' => 'codigo_classe',
                                'Ch' => 'chamada',
                                'Situação' => 'situacao'
                            ];

                            tool::relatSimples($form);
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div>
                        <?php
                        if (!empty(@$_POST['nome'])) {
                            if (is_numeric(@$_POST['nome'])) {
                                @$alunos = alunos::busca($_POST['nome']);
                                if (!empty($alunos)) {
                                    foreach ($alunos as $k => $v) {
                                        if ($v['situacao'] <> 'Frequente') {
                                            $alunos[$k]['acc'] = formulario::submit('Matricular', NULL, ['mat_aluno' => 1, 'id_pessoa' => $v['id_pessoa'], 'turma' => $_POST['turma']]);
                                        }
                                    }
                                    $form['array'] = $alunos;
                                    $form['fields'] = [
                                        'Aluno' => 'n_pessoa',
                                        'Dt. Nasc.' => 'dt_nasc',
                                        'Mãe' => 'mae',
                                        'Certidão de Nasc.' => 'certidao',
                                        'Situação' => 'situacao',
                                        'Escola' => 'n_inst',
                                        'Classe' => 'codigo_classe',
                                        '||' => 'acc'
                                    ];
                                    tool::relatSimples($form);
                                } else {
                                    tool::alert("Aluno Não Encontrado");
                                }
                            } else {
                                tool::alert("Insira Somente Números");
                            }
                        }
                        ?>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
