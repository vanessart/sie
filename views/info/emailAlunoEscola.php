<?php
if (tool::id_nivel() == 43) {
    $idInst = tool::id_inst();
} else {
    $id_inst = explode('/', $_GET['path']);
    $idInst = $id_inst[2];
}
$turmasEscola = $model->turmasEscola($idInst);

?>

<br>

<div class="container">
    <div class="row">
        <div class="col-md-1"></div>
        <div class="col-md-10">
            <a href="<?= HOME_URI ?>/info/buscaEmailAluno/" class="btn btn-enviar">Voltar</a>
        </div>
    </div>
</div>

<br>

<!-- Tabela -->
<?php if ($turmasEscola): ?>
    <div class="container">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10 text-center">
                <h1 class="title-escolas"><?= @$turmasEscola[0]['n_inst'] ?></h1>
            </div>

        </div>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <table class="table table-striped table-bordered table-sm">
                    <thead class="card-header">
                        <tr>
                            <th scope="col" style="width: 50px;">CÓDIGO</th>
                            <th scope="col">Turma</th>
                            <th scope="col" style="width: 150px;">Com e-mail</th>
                            <th scope="col" style="width: 150px;">Sem e-mail</th>
                            <th scope="col" style="width: 100px;">Acessar</th>
                        </tr>
                    </thead>
                    <tbody>          
                        <?php foreach ($turmasEscola as $key => $value): ?>
                            <?php $emailAlunosTurma = $model->emailAlunosTurma($turmasEscola[0]['id_inst'], $value['codigo']); ?>
                            <tr>
                                <td><p><?= $value['codigo'] ?></p></td>
                                <td><p><?= $value['n_turma'] ?></p></td>
                                <td><p class="text-success"><?= $emailAlunosTurma[0]['email'] ?></p></td>
                                <td><p class="text-danger"><?= $emailAlunosTurma[1]['email'] ?></p></td>
                                <td>
                                    <a href="<?= HOME_URI ?>/info/emailAlunoClasse/<?= $value['id_inst'] . "/" . $value['codigo']. "/" . $value['id_turma'] ?>" 
                                       class="btn btn-success btn-sm btn-acessar">Acessar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?> 
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php else: ?>
    <div class="container">
        <div class="row d-flex justify-content-center my-5">
            <div class="col-md-12 text-center">
                <div class="alert alert-danger" role="alert">
                    Nenhuma Turma encontrada
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


<style>
    .title-escolas {
        color: #83295c;
        font-size: 2em;
        font-weight: bold;
    }

    .card-header {
        padding: .75rem 1.25rem;
        margin-bottom: 0;
        background-color: rgb(131 41 92);
        border-bottom: 1px solid rgba(0, 0, 0, .125);
        color: #FFF !important;
    }

    .text-prof {
        font-weight: bold;
        font-size: 1.1em;
        margin-top: 4px;
    }

    .btn-acessar {
        width: 100%;
    }

    .btn-enviar {
        color: #FFF;
        background-color: #83295c;
        font-size: 1.1em;
        padding: 5px 15px 5px 15px;
    }

    td>p {
        font-size: 1.5rem;

    }

    tr>th,tr>td {
        text-align: center;
    }

</style>