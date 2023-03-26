<?php
$escolas = $model->listaEscolasFundamental();

$totalEscolas = count($escolas);

$alunosComEmail = $model->alunosComEmail();

?>

<div class="container">
    <div class="row center">
        <div class="count">
            <div class="col-md-3">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h3 class="panel-title">Alunos com E-mail</h3>
                    </div>
                    <div class="panel-body">
                        <h3><b><span data-numero><?= @$alunosComEmail[0]['Total_Alunos'] ?></span></b></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">Alunos sem E-mail</h3>
                    </div>
                    <div class="panel-body">
                    <h3><b><span data-numero><?= @$alunosComEmail[1]['Total_Alunos'] ?></span></b></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Total Alunos</h3>
                    </div>
                    <div class="panel-body">
                    <h3><b><span data-numero><?= @$alunosComEmail[0]['Total_Alunos'] + @$alunosComEmail[1]['Total_Alunos'] ?></span></b></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">Total Escolas</h3>
                    </div>
                    <div class="panel-body">
                    <h3><b><span><?= @$totalEscolas ?></span></b></h3>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-1">
        </div>
        <div class="col-md-10">
            <table class="table table-striped table-bordered table-sm">
                <thead>
                    <tr>
                        <th scope="col">Escola</th>
                        <th scope="col" class="text-center">Com E-mail</th>
                        <th scope="col" class="text-center">Sem E-mail</th>
                        <th scope="col" class="text-center">Acessar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($escolas as $chave => $valor): ?>
                    <?php $emails = $model->emailAlunosEscolaTotal($valor['id_inst']) ?>
                    <tr>
                        <td><p class="text-escola"><?= $valor['n_inst'] ?></p></td>
                        <td><p class="text-escola text-center text-success"><?= $emails[0]['email'] ?></p></td>
                        <td><p class="text-escola text-center text-danger"><?= $emails[1]['email'] ?></p></td>
                        <td>
                            <a href="<?= HOME_URI ?>/info/emailAlunoEscola/<?= $valor['id_inst'] ?>"
                            class="btn btn-success btn-sm btn-acessar">Acessar</a>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function contaNumero() {

    const numeros = document.querySelectorAll('[data-numero]');

    numeros.forEach((numero) => {
        const total = +numero.innerText;
        const incremento = Math.floor(total / 100);
        let start = 0;
        const timer = setInterval(() => {
            start = start + incremento;
            numero.innerText = start;
            if (start > total) {
                numero.innerText = total;
                clearInterval(timer);
            }
        }, 25 * Math.random());
    })
}
contaNumero();
</script>



<style>
.count {
    display: flex;
    justify-content: space-around;
    align-items: center;
    margin-top: 30px;
    text-align: center;
    flex-wrap: wrap;
}

.text-escola {
    font-size:1.5rem;
    font-weight: 500;
}

.btn-acessar {
    width: 100%;
}
</style>