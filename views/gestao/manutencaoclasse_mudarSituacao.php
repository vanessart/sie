<?php
tool::modalInicio('width: 60%; text-align: center');
if (!empty($_POST['id_turma_aluno'])) {
    $dados = sql::get(['pessoa', 'ge_turma_aluno', 'ge_turmas'], 'n_pessoa, id_pessoa, ge_turma_aluno.chamada, ge_turma_aluno.situacao', ['id_turma_aluno' => $_POST['id_turma_aluno']], 'fetch');
    extract($dados);

    $frequente = sql::get('ge_turma_aluno', 'situacao', ['fk_id_pessoa' => $id_pessoa, 'situacao' => "Frequente"], 'fetch')['situacao'];
    if (!empty($id_pessoa)) {
        ?>
        <form  method="POST">
            <br />
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td>
                            RSE
                        </td>
                        <td>
                            Chamada
                        </td>
                        <td>
                            Nome Aluno
                        </td>
                        <td>
                            Situação
                        </td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?php echo $id_pessoa ?>
                        </td>
                        <td>
                            <?php echo $chamada ?>
                            <input type="hidden" name="ch_a" value="<?php echo $wchamada ?>" />
                        </td>
                        <td>
                            <?php echo $n_pessoa ?>
                        <td>
                            <select required name = "1[situacao]">
                                <option value = "">Escolha a Situação</option>
                                <option>Abandono</option>
                                <option>Evadido</option>
                                <option>Falecido</option>
                                <?php
                                if (empty($frequente)|| in_array(tool::id_pessoa(), [1,6488, 2984,6482,6485])) {
                                    ?>
                                    <option>Frequente</option>
                                    <?php
                                }
                                ?>
                                <option>Não Comparecimento</option>
                                <option>Remanejado</option>
                                <option>Reclassificado</option>
                                <option>Cessão por objetivos</option>
                                <option>Cessão por não frequência e desistência</option>
                                <option>Cessão por exame</option>
                            </select> 

                        </td>
                    </tr>
                </tbody>

            </table>
            <input type="hidden" name="1[id_turma_aluno]" value="<?php echo $_POST['id_turma_aluno'] ?>" />
            <?php echo DB::hiddenKey('ge_turma_aluno', 'replace') ?>
            <input type="hidden" name="turma" value="<?php echo $_POST['turma'] ?>" /> 
            <input type="hidden" name="id_pessoa" value="<?php echo @$_POST['id_pessoa'] ?>" />
             <input type="hidden" name="activeNav" value="<?php echo  @$_POST['activeNav'] ?>" />
           <input type="hidden" name="aba" value="esc" />
            <input style="width: 45.5%; font-weight: 900" class="art-button" type="submit" value="Salvar" />                      
        </form>
        <?php
    } else {
        ?>
        <div style="text-align: center; font-size: 20px">
            Erro
        </div>
        <?php
    }
} else {
    ?>
    <div style="text-align: center; font-size: 20px">
        Favor selecionar aluno
    </div>
    <?php
}
tool::modalFim();
?>
