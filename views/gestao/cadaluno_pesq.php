<div class="container">   
    <div id="search" class="row" style="display: <?php echo (empty($dados) && empty($_POST['novo'])) ? '' : 'none' ?>">
        <form method="POST">
            <div class="col-lg-5">
                <?php echo formulario::input('pessoa', ' Nome ou RSE:', NULL, @$pessoa) ?>

            </div>
            <div class="col-lg-2">
                <button type="submit" class="btn btn-success">
                    <span aria-hidden="true">
                        Buscar
                    </span>
                </button>
            </div>
        </form>

        <div class="col-lg-3">
            <form method="POST">
                <button type="submit" class="btn btn-warning">
                    Limpar
                </button>
            </form>
        </div>
    </div>
</div>
<br />
<?php
if (!empty($pessoa)) {
    alunos::relatAluno($pessoa);
}
?>