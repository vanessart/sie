<div class="fieldBody">
    <?php
    $escola = new escola();
    if (empty($escola->_ato_cria) || empty($escola->_ato_municipa)) {
        ?>
        <div class="alert alert-danger" style="font-size: 20px;  line-height: 30px">
            <a href="<?php echo HOME_URI ?>/gestao/escola">
                Para utilizar o sistema de histório será necessário cadastrar o <strong style="color: blue">Ato de Criação</strong> e o <strong style="color: blue">Ato de Municipalização</strong> da escola, acessando <strong style="color: blue">CADASTRO</strong> >> <strong style="color: blue">ESCOLA</strong>
            </a>
        </div>
        <?php
    } else {
        @$historico = $_POST['historico'];
        if (empty($historico)) {
            @$nome = $_POST['nome'];
            ?>
            <div class="fieldTop">
                Histórico Escolar
            </div>   
            <br>
            <div class="fieldWhite">
                <form method="POST">
                    <div id="search" class="row" >
                        <div class="col-lg-9">
                            <?php echo formulario::input('nome', 'RSE ou Nome do Aluno', NULL, NULL, 'required')
                            ?>
                        </div>
                        <div class="col-lg-2">
                            <input type="hidden" name="aba" value="cad" />
                            <button class="btn btn-success">
                                Pesquisar
                            </button>
                        </div>
                    </div>
                </form>
                <?php
                if (!empty($nome)) {
                    $alunos = alunos::busca($nome);
                    foreach ($alunos as $v) {
                        $id_[] = $v['id_pessoa'];
                    }
                    if (!empty($id_)) {
                        $sql = "select * from hist_esc "
                                . "where fk_id_pessoa in (" . implode(',', $id_) . ") "
                                . "and fk_id_inst = " . tool::id_inst();
                        $query = $model->db->query($sql);
                        $array = $query->fetchAll();
                        foreach ($array as $v) {
                            $per[$v['fk_id_pessoa']] = $v['fk_id_pessoa'];
                        }

                        if (!empty($alunos) && is_array($alunos)) {
                            ?>
                            <br />
                            <?php
                            $location = HOME_URI . '/hist/histpessoa';
                            if (!empty($alunos)) {
                                foreach ($alunos as $k => $v) {

                                    @$instDono = sql::get('hist_', 'fk_id_inst', ['id_pessoa' => $v['id_pessoa']], 'fetch')['fk_id_inst'];
                                    if (@substr($v['codigo'], 0, 2) != 'EF' && @$v['codigo'] != "") {
                                        unset($alunos[$k]);
                                    } else {
                                        $alunos[$k]['acc'] = formulario::submit('Acessar', NULL, ['id_pessoa' => $v['id_pessoa']], $location);
                                        $alunos[$k]['n_inst'] = !empty($alunos[$k]['n_inst']) ? $alunos[$k]['n_inst'] : (!empty($instDono) ? escolas::idInst($instDono, 'id_inst')[@$instDono] : '');
                                        @$alunos[$k]['situacao'] = $v['situacao'] == 'Frequente' ? '<div style="color: red; font-weight: bolder; font-size: 18px">' . $v['situacao'] . '</div>' : $v['situacao'];
                                    }
                                }
                            }
                            $form['array'] = $alunos;
                            $form['fields'] = [
                                'Aluno' => 'n_pessoa',
                                'Dt. Nasc.' => 'dt_nasc',
                                'Mãe' => 'mae',
                                'Escola' => 'n_inst',
                                'Classe' => 'codigo',
                                'Situação' => 'situacao',
                                '||' => 'acc'
                            ];
                            tool::relatSimples($form);
                        } else {
                            ?>
                            <br /><br />
                            <div class="alert alert-danger text-center">
                                Aluno não encontrado
                            </div>

                            <?php
                        }
                    } else {
                        ?>
                        <br /><br />
                        <div class="alert alert-danger text-center">
                            Aluno não encontrado
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <?php
        }
    }
    ?>
</div>
