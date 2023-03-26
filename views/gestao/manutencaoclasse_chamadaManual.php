<?php tool::modalInicio('width: 80%') ?>
<form method="POST">
    <table class="table table-striped">
        <thead>
            <tr>
                <td>
                    Chamada Atual
                </td>
                <td>
                    Chamada Novo Nr
                </td>
                <td>
                    RSE
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
            <?php
            foreach ($classe as $c) {
                ?>
                <tr>
                    <td>
                        <?php echo $c['chamada'] ?>
                    </td>
                    <td>
                        <input style="width: 50px" type="text" name="chamada_nr[<?php echo $c['id_turma_aluno'] ?>]" value="<?php echo $c['chamada'] ?>" />
                    </td>
                    <td>
                        <?php echo $c['id_pessoa'] ?>
                    </td>
                    <td>
                        <?php echo $c['n_pessoa'] ?>
                    </td>
                    <td>
                        <?php echo $c['situacao'] ?>
                    </td>
                </tr>

                <?php
            }
            ?>
        </tbody>   
    </table>

    <input type="hidden" name="turma" value="<?php echo $_POST['turma'] ?>" />
    <input class="art-button" style="width: 45.5%; font-weight: 900"  type="submit" name="atz_ch" value="Salvar" />
    <button class="art-button" style="width: 45.5%; font-weight: 900" >
        Fechar
    </button>
</form>
<?php
tool::modalFim();
