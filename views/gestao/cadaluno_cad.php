<?php
alunos::AlunosAutocomplete(NULL, "p.id_pessoa, p.n_pessoa");
?>
<br />
<div class="fieldWhite">
    <form method="POST">

        <input type="hidden" name="novo" value="1" />
        <input type="hidden" name="aba" value="esc" />
        <div class="row">
            <div class="col-md-8">
                <?php echo formulario::input('n_pessoa', 'Nome do Aluno:', NULL, @$_POST['n_pessoa'], ' id="busca" onkeypress="complete()" ') ?>
            </div>
            <div class="col-md-2">
                <?php echo formulario::input('id_pessoa', 'RSE:', NULL, @$_POST['id_pessoa'], ' id="rse" required ') ?>
            </div>
            <div class="col-md-2">
                <button class="btn btn-success" type="submit">
                    Pesquisar
                </button>
            </div>
        </div>
    </form>
</div>
