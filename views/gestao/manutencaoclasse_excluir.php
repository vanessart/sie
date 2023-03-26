<?php
tool::modalInicio("width: 80%");
if (!empty($_POST['id_turma_aluno'])) {

    $dados = sql::get(['pessoa', 'ge_turma_aluno', 'ge_turmas'], ' ge_turmas.codigo, n_pessoa, id_pessoa, ge_turma_aluno.chamada, ge_turma_aluno.situacao', ['id_turma_aluno' => $_POST['id_turma_aluno']], 'fetch');
    extract($dados);
    ?>
    <table class="table" style="width: 75%; border: #000000 thin solid; height: 80px" > 
        <thead>
        <label>Dados do Aluno</label>
        <tr>
            <td>
                RSE
            </td>
            <td>
                Cód.Classe
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
                <?php echo $codigo ?>
            </td>
            <td>
                <?php echo $chamada ?>
            </td>
            <td>
                <?php echo $n_pessoa ?>
            </td>
            <td>
                <?php echo $situacao ?>
            </td>
        </tr>
    </tbody>

    </table>
    <div >
        <form method="POST">
            <?php echo DB::hiddenKey('ge_turma_aluno', 'delete') ?>
            <input type="hidden"  name= "1[id_turma_aluno]" value="<?php echo $_POST['id_turma_aluno'] ?>" /> 
            <input type="hidden" name="turma" value="<?php echo $_POST['turma'] ?>" /> 
            <input type="submit" style="width: 33%" class ="art-button" value="Confirmar" />                                                  
        </form>  
    </div>
    <?php
} else {
    ?>
    <div style="text-align: center; font-size: 20px">
        Favor selecionar aluno
        <br /><br />
    </div>
    <?php
}
tool::modalFim();
